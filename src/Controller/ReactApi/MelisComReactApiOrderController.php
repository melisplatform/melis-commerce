<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour la gestion des commandes MelisCommerce (table melis_ecom_order).
 *
 * Routes :
 *   GET    /melis/react-api/orders                → liste paginée + filtres
 *   GET    /melis/react-api/orders/stats          → KPI
 *   GET    /melis/react-api/orders/statuses       → liste des statuts de commande
 *   GET    /melis/react-api/orders/:id            → détail complet
 *   POST   /melis/react-api/orders/:id/status     → changer le statut
 *   POST   /melis/react-api/orders/:id/address    → sauvegarder une adresse
 *   POST   /melis/react-api/orders/:id/shipping   → ajouter un suivi d'expédition
 *   POST   /melis/react-api/orders/:id/message    → ajouter un message
 */
class MelisComReactApiOrderController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_order_list_page';

    private function langId(): int
    {
        try {
            $c = new \Laminas\Session\Container('meliscore');
            $id = (int) ($c['melis-lang-id'] ?? 0);
            return $id > 0 ? $id : 1;
        } catch (\Throwable) { return 1; }
    }

    private function currentUserId(): int
    {
        try {
            return (int) ($this->getServiceManager()->get('MelisCoreAuth')->getStorage()->read()->usr_id ?? 0);
        } catch (\Throwable) { return 0; }
    }

    // ─── Response helpers ─────────────────────────────────────────────────────

    private function jsonResponse(array $data, int $status = 200): HttpResponse
    {
        /** @var HttpResponse $response */
        $response = $this->getResponse();
        $response->setStatusCode($status);
        $response->getHeaders()->addHeaders([
            'Content-Type'           => 'application/json; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
        ]);
        $response->setContent(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return $response;
    }

    private function ok(mixed $data): HttpResponse
    {
        return $this->jsonResponse(['success' => true, 'data' => $data]);
    }

    private function err(\Throwable $e): HttpResponse
    {
        return $this->jsonResponse([
            'success' => false,
            'error'   => $e->getMessage(),
            'file'    => basename($e->getFile()) . ':' . $e->getLine(),
        ], 500);
    }

    private function isAuthenticated(): bool
    {
        return $this->getServiceManager()->get('MelisCoreAuth')->hasIdentity();
    }

    private function denyUnlessAccess(): ?HttpResponse
    {
        if (!$this->isAuthenticated()) {
            return $this->jsonResponse(['success' => false, 'error' => 'Unauthenticated'], 401);
        }
        try {
            if (!$this->getServiceManager()->get('MelisCoreRights')->canAccess(self::MELIS_KEY)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Forbidden'], 403);
            }
        } catch (\Throwable) {}
        return null;
    }

    // ─── GET /orders ──────────────────────────────────────────────────────────
    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $page      = max(1, (int) $this->params()->fromQuery('page', 1));
            $limit     = min(9999, max(1, (int) $this->params()->fromQuery('limit', 25)));
            $search    = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawStatus = $this->params()->fromQuery('status', '');
            $status    = ($rawStatus !== '' && $rawStatus !== null) ? (int) $rawStatus : null;
            $dateStart = $this->params()->fromQuery('dateStart', '');
            $dateEnd   = $this->params()->fromQuery('dateEnd', '');
            $offset    = ($page - 1) * $limit;
            $lang      = $this->langId();

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $where  = [];
            $params = [];
            if ($status !== null)    { $where[] = 'o.ord_status = ?';                            $params[] = $status; }
            if ($dateStart !== '')   { $where[] = 'DATE(o.ord_date_creation) >= ?';              $params[] = $dateStart; }
            if ($dateEnd !== '')     { $where[] = 'DATE(o.ord_date_creation) <= ?';              $params[] = $dateEnd; }
            if ($search !== '') {
                $like    = '%' . $search . '%';
                $where[] = '(o.ord_reference LIKE ? OR p.cper_firstname LIKE ? OR p.cper_name LIKE ? OR c.cli_name LIKE ?)';
                $params  = array_merge($params, [$like, $like, $like, $like]);
            }
            $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $countRow = iterator_to_array($db->query(
                "SELECT COUNT(*) AS total FROM melis_ecom_order o
                 LEFT JOIN melis_ecom_client c ON c.cli_id = o.ord_client_id
                 LEFT JOIN melis_ecom_client_person p ON p.cper_id = o.ord_client_person_id
                 $whereClause", $params
            ));
            $total = (int) ($countRow[0]['total'] ?? 0);

            $dataSql = "
                SELECT o.ord_id, o.ord_reference, o.ord_status, o.ord_client_id, o.ord_client_person_id,
                       o.ord_date_creation,
                       c.cli_name AS company,
                       p.cper_firstname, p.cper_name,
                       ostt.ostt_status_name AS status_name,
                       osta.osta_color_code AS status_color,
                       (SELECT COALESCE(SUM(obas_quantity), 0) FROM melis_ecom_order_basket WHERE obas_order_id = o.ord_id) AS product_count,
                       (SELECT COALESCE(SUM(opay_price_total), 0) FROM melis_ecom_order_payment WHERE opay_order_id = o.ord_id) AS total_amount
                FROM melis_ecom_order o
                LEFT JOIN melis_ecom_client c ON c.cli_id = o.ord_client_id
                LEFT JOIN melis_ecom_client_person p ON p.cper_id = o.ord_client_person_id
                LEFT JOIN melis_ecom_order_status_trans ostt ON ostt.ostt_status_id = o.ord_status AND ostt.ostt_lang_id = ?
                LEFT JOIN melis_ecom_order_status osta ON osta.osta_id = o.ord_status
                $whereClause
                ORDER BY o.ord_id DESC
                LIMIT ? OFFSET ?
            ";
            $rows = $db->query($dataSql, array_merge([$lang], $params, [$limit, $offset]));

            $items = [];
            foreach ($rows as $row) {
                $r = (array) $row;
                $items[] = [
                    'id'           => (int) $r['ord_id'],
                    'reference'    => $r['ord_reference'] ?? '',
                    'status'       => (int) $r['ord_status'],
                    'statusName'   => $r['status_name'] ?? '',
                    'statusColor'  => $r['status_color'] ?? '#6b7280',
                    'clientId'     => (int) ($r['ord_client_id'] ?? 0),
                    'personId'     => (int) ($r['ord_client_person_id'] ?? 0),
                    'company'      => $r['company'] ?? '',
                    'firstname'    => $r['cper_firstname'] ?? '',
                    'name'         => $r['cper_name'] ?? '',
                    'productCount' => (int) ($r['product_count'] ?? 0),
                    'totalAmount'  => (float) ($r['total_amount'] ?? 0),
                    'dateCreation' => $r['ord_date_creation'] ?? null,
                ];
            }
            return $this->ok(['items' => $items, 'total' => $total, 'page' => $page, 'limit' => $limit]);
        } catch (\Throwable $e) {
            return $this->err($e);
        }
    }

    // ─── GET /orders/stats ────────────────────────────────────────────────────
    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $total   = iterator_to_array($db->query("SELECT COUNT(*) AS n FROM melis_ecom_order", []));
            $today   = iterator_to_array($db->query("SELECT COUNT(*) AS n FROM melis_ecom_order WHERE DATE(ord_date_creation) = CURDATE()", []));
            $month   = iterator_to_array($db->query("SELECT COUNT(*) AS n FROM melis_ecom_order WHERE MONTH(ord_date_creation)=MONTH(CURDATE()) AND YEAR(ord_date_creation)=YEAR(CURDATE())", []));
            $pending = iterator_to_array($db->query("SELECT COUNT(*) AS n FROM melis_ecom_order WHERE ord_status = 1", []));
            $revenue = iterator_to_array($db->query("SELECT COALESCE(SUM(p.opay_price_total),0) AS r FROM melis_ecom_order_payment p JOIN melis_ecom_order o ON o.ord_id = p.opay_order_id WHERE MONTH(o.ord_date_creation)=MONTH(CURDATE()) AND YEAR(o.ord_date_creation)=YEAR(CURDATE())", []));

            return $this->ok([
                'total'        => (int) ($total[0]['n']   ?? 0),
                'today'        => (int) ($today[0]['n']   ?? 0),
                'thisMonth'    => (int) ($month[0]['n']   ?? 0),
                'pending'      => (int) ($pending[0]['n'] ?? 0),
                'revenueMonth' => (float) ($revenue[0]['r'] ?? 0),
            ]);
        } catch (\Throwable $e) {
            return $this->err($e);
        }
    }

    // ─── GET /orders/statuses ─────────────────────────────────────────────────
    public function statusesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();
            $rows = iterator_to_array($db->query(
                "SELECT osta.osta_id, osta.osta_color_code, ostt.ostt_status_name
                 FROM melis_ecom_order_status osta
                 LEFT JOIN melis_ecom_order_status_trans ostt ON ostt.ostt_status_id = osta.osta_id AND ostt.ostt_lang_id = ?
                 WHERE osta.osta_status = 1
                 ORDER BY osta.osta_id ASC",
                [$lang]
            ));
            $statuses = [];
            foreach ($rows as $row) {
                $r = (array) $row;
                $statuses[] = ['id' => (int) $r['osta_id'], 'name' => $r['ostt_status_name'] ?? '', 'color' => $r['osta_color_code'] ?? '#6b7280'];
            }
            return $this->ok($statuses);
        } catch (\Throwable $e) {
            return $this->err($e);
        }
    }

    // ─── GET /orders/:id ──────────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->jsonResponse(['success' => false, 'error' => 'Missing order id'], 400);
        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();

            $orderRows = iterator_to_array($db->query(
                "SELECT o.*, c.cli_name AS company, p.cper_firstname, p.cper_name,
                        ostt.ostt_status_name AS status_name, osta.osta_color_code AS status_color
                 FROM melis_ecom_order o
                 LEFT JOIN melis_ecom_client c ON c.cli_id = o.ord_client_id
                 LEFT JOIN melis_ecom_client_person p ON p.cper_id = o.ord_client_person_id
                 LEFT JOIN melis_ecom_order_status_trans ostt ON ostt.ostt_status_id = o.ord_status AND ostt.ostt_lang_id = ?
                 LEFT JOIN melis_ecom_order_status osta ON osta.osta_id = o.ord_status
                 WHERE o.ord_id = ? LIMIT 1",
                [$lang, $id]
            ));
            if (!$orderRows) return $this->jsonResponse(['success' => false, 'error' => 'Order not found'], 404);
            $o = (array) $orderRows[0];

            $basketRows = iterator_to_array($db->query(
                "SELECT obas_id, obas_sku, obas_product_name, obas_category_name, obas_variant_id,
                        obas_quantity, obas_price_net, obas_price_gross, obas_attributes, obas_currency
                 FROM melis_ecom_order_basket WHERE obas_order_id = ? ORDER BY obas_id ASC",
                [$id]
            ));
            $basket = [];
            foreach ($basketRows as $row) {
                $r = (array) $row;
                $basket[] = [
                    'id'         => (int) $r['obas_id'],
                    'sku'        => $r['obas_sku'] ?? '',
                    'name'       => $r['obas_product_name'] ?? '',
                    'category'   => $r['obas_category_name'] ?? '',
                    'variantId'  => (int) ($r['obas_variant_id'] ?? 0),
                    'qty'        => (int) ($r['obas_quantity'] ?? 0),
                    'priceNet'   => (float) ($r['obas_price_net'] ?? 0),
                    'priceGross' => (float) ($r['obas_price_gross'] ?? 0),
                    'attributes' => $r['obas_attributes'] ?? '',
                    'currency'   => $r['obas_currency'] ?? '',
                ];
            }

            $addrRows = iterator_to_array($db->query(
                "SELECT * FROM melis_ecom_order_address WHERE oadd_order_id = ? ORDER BY oadd_type ASC",
                [$id]
            ));
            $billing = null; $delivery = null;
            foreach ($addrRows as $row) {
                $r = (array) $row;
                $addr = [
                    'id' => (int) $r['oadd_id'], 'type' => (int) $r['oadd_type'],
                    'company' => $r['oadd_company'] ?? '', 'civility' => (int) ($r['oadd_civility'] ?? 0),
                    'firstname' => $r['oadd_firstname'] ?? '', 'name' => $r['oadd_name'] ?? '',
                    'middleName' => $r['oadd_middle_name'] ?? '', 'num' => $r['oadd_num'] ?? '',
                    'street' => $r['oadd_street'] ?? '', 'building' => $r['oadd_building_name'] ?? '',
                    'stairs' => $r['oadd_stairs'] ?? '', 'city' => $r['oadd_city'] ?? '',
                    'state' => $r['oadd_state'] ?? '', 'country' => $r['oadd_country'] ?? '',
                    'zipcode' => $r['oadd_zipcode'] ?? '', 'phoneMobile' => $r['oadd_phone_mobile'] ?? '',
                    'phoneLandline' => $r['oadd_phone_landline'] ?? '', 'complementary' => $r['oadd_complementary'] ?? '',
                ];
                if ((int) $r['oadd_type'] === 1) $billing  = $addr;
                if ((int) $r['oadd_type'] === 2) $delivery = $addr;
            }

            $payRows = iterator_to_array($db->query(
                "SELECT p.*, pt.opty_name AS payment_type_name, cu.cur_code AS currency_code
                 FROM melis_ecom_order_payment p
                 LEFT JOIN melis_ecom_order_payment_type pt ON pt.opty_id = p.opay_payment_type_id
                 LEFT JOIN melis_ecom_currency cu ON cu.cur_id = p.opay_currency_id
                 WHERE p.opay_order_id = ? ORDER BY p.opay_id ASC",
                [$id]
            ));
            $payments = [];
            foreach ($payRows as $row) {
                $r = (array) $row;
                $payments[] = [
                    'id' => (int) $r['opay_id'], 'total' => (float) ($r['opay_price_total'] ?? 0),
                    'orderPrice' => (float) ($r['opay_price_order'] ?? 0), 'shipping' => (float) ($r['opay_price_shipping'] ?? 0),
                    'currency' => $r['currency_code'] ?? '', 'paymentType' => $r['payment_type_name'] ?? '',
                    'transacId' => $r['opay_transac_id'] ?? '', 'confirmed' => (float) ($r['opay_transac_price_paid_confirm'] ?? 0),
                    'datePay' => $r['opay_date_payment'] ?? null,
                ];
            }

            $shipRows = iterator_to_array($db->query(
                "SELECT * FROM melis_ecom_order_shipping WHERE oship_order_id = ? ORDER BY oship_id DESC", [$id]
            ));
            $shippings = [];
            foreach ($shipRows as $row) {
                $r = (array) $row;
                $shippings[] = ['id' => (int) $r['oship_id'], 'trackingCode' => $r['oship_tracking_code'] ?? '', 'content' => $r['oship_content'] ?? '', 'dateSent' => $r['oship_date_sent'] ?? null];
            }

            $msgRows = iterator_to_array($db->query(
                "SELECT m.*, u.usr_firstname, u.usr_lastname, u.usr_email FROM melis_ecom_order_message m
                 LEFT JOIN melis_core_user u ON u.usr_id = m.omsg_user_id
                 WHERE m.omsg_order_id = ? ORDER BY m.omsg_id DESC",
                [$id]
            ));
            $messages = [];
            foreach ($msgRows as $row) {
                $r = (array) $row;
                $messages[] = [
                    'id' => (int) $r['omsg_id'], 'message' => $r['omsg_message'] ?? '',
                    'userId' => (int) ($r['omsg_user_id'] ?? 0),
                    'userName' => trim(($r['usr_firstname'] ?? '') . ' ' . ($r['usr_lastname'] ?? '')) ?: 'Admin',
                    'userEmail' => $r['usr_email'] ?? '',
                    'clientId' => (int) ($r['omsg_client_id'] ?? 0),
                    'dateCreation' => $r['omsg_date_creation'] ?? null,
                ];
            }

            return $this->ok([
                'id' => (int) $o['ord_id'], 'reference' => $o['ord_reference'] ?? '',
                'status' => (int) $o['ord_status'], 'statusName' => $o['status_name'] ?? '',
                'statusColor' => $o['status_color'] ?? '#6b7280',
                'clientId' => (int) ($o['ord_client_id'] ?? 0), 'personId' => (int) ($o['ord_client_person_id'] ?? 0),
                'company' => $o['company'] ?? '', 'firstname' => $o['cper_firstname'] ?? '', 'name' => $o['cper_name'] ?? '',
                'dateCreation' => $o['ord_date_creation'] ?? null,
                'basket' => $basket, 'billing' => $billing, 'delivery' => $delivery,
                'payments' => $payments, 'shippings' => $shippings, 'messages' => $messages,
            ]);
        } catch (\Throwable $e) {
            return $this->err($e);
        }
    }

    // ─── GET /orders/:id/returns ──────────────────────────────────────────────
    public function returnsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->jsonResponse(['success' => false, 'error' => 'Missing id'], 400);
        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query(
                "SELECT pr.pret_id, pr.pret_date_creation,
                        pd.pretd_id, pd.pretd_sku, pd.pretd_quantity, pd.pretd_variant_id,
                        COALESCE(pt.ptxt_field_short, pd.pretd_sku) AS product_name
                 FROM melis_ecom_order_product_return pr
                 LEFT JOIN melis_ecom_order_product_return_details pd ON pd.pretd_pret_id = pr.pret_id
                 LEFT JOIN melis_ecom_variant v ON v.var_id = pd.pretd_variant_id
                 LEFT JOIN melis_ecom_product p ON p.prd_id = v.var_prd_id
                 LEFT JOIN melis_ecom_product_text pt ON pt.ptxt_prd_id = p.prd_id AND pt.ptxt_lang_id = 1
                 WHERE pr.pret_order_id = ?
                 ORDER BY pr.pret_id DESC",
                [$id]
            ));
            $items = array_map(fn($r) => [
                'returnId'  => (int) $r['pret_id'],
                'detailId'  => (int) $r['pretd_id'],
                'sku'       => $r['pretd_sku'] ?? '',
                'product'   => $r['product_name'] ?? '',
                'qty'       => (int) ($r['pretd_quantity'] ?? 0),
                'variantId' => (int) ($r['pretd_variant_id'] ?? 0),
                'date'      => $r['pret_date_creation'],
            ], $rows);
            return $this->ok($items);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── Upload directory ─────────────────────────────────────────────────────
    private function uploadDir(int $orderId): string
    {
        $dir = dirname(__DIR__, 5) . '/public/MelisCommerce/documents/orders/' . $orderId . '/';
        if (!is_dir($dir)) { mkdir($dir, 0775, true); }
        return $dir;
    }

    private function uploadUrl(string $relPath): string
    {
        return '/MelisCommerce/documents/' . ltrim($relPath, '/');
    }

    // ─── GET /orders/:id/attachments ──────────────────────────────────────────
    public function attachmentsListAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->jsonResponse(['success' => false, 'error' => 'Missing order id'], 400);
        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query(
                "SELECT d.doc_id, d.doc_name, d.doc_path FROM melis_ecom_document d
                 JOIN melis_ecom_doc_relations r ON r.rdoc_doc_id = d.doc_id
                 WHERE r.rdoc_order_id = ? AND d.doc_type_id = 2 ORDER BY d.doc_id DESC",
                [$id]
            ));
            $items = [];
            foreach ($rows as $row) {
                $r = (array) $row;
                $items[] = ['id' => (int) $r['doc_id'], 'name' => $r['doc_name'], 'path' => $r['doc_path'], 'url' => $this->uploadUrl($r['doc_path'])];
            }
            return $this->ok($items);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/:id/attachments/upload ──────────────────────────────────
    public function attachmentUploadAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->jsonResponse(['success' => false, 'error' => 'Missing order id'], 400);
        try {
            $files = $this->getRequest()->getFiles()->toArray();
            if (empty($files['file'])) return $this->jsonResponse(['success' => false, 'error' => 'No file uploaded'], 400);
            $file    = $files['file'];
            $docName = trim($this->getRequest()->getPost('name', ''));
            if ($docName === '') $docName = $file['name'];

            $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $safeName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', basename($file['name']));
            $dir      = $this->uploadDir($id);
            $relPath  = 'orders/' . $id . '/' . time() . '_' . $safeName;
            $dest     = $dir . time() . '_' . $safeName;

            if (!move_uploaded_file($file['tmp_name'], $dest)) {
                return $this->jsonResponse(['success' => false, 'error' => 'File move failed'], 500);
            }

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $db->query(
                "INSERT INTO melis_ecom_document (doc_name, doc_path, doc_type_id) VALUES (?, ?, 2)",
                [$docName, $relPath]
            );
            $docId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];
            $db->query(
                "INSERT INTO melis_ecom_doc_relations (rdoc_doc_id, rdoc_order_id, rdoc_country_id) VALUES (?, ?, -1)",
                [$docId, $id]
            );

            $this->getEventManager()->trigger('meliscommerce_order_save_end', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_order_page', 'textMessage' => 'tr_meliscommerce_order_page_save_success',
                'typeCode' => 'ECOM_ORDER_ATTACHMENT_ADD', 'itemId' => $id,
            ]);

            return $this->ok(['id' => $docId, 'name' => $docName, 'path' => $relPath, 'url' => $this->uploadUrl($relPath)]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/:id/attachments/:docId/delete ───────────────────────────
    public function attachmentDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id    = (int) $this->params()->fromRoute('id', 0);
        $docId = (int) $this->params()->fromRoute('docId', 0);
        if (!$id || !$docId) return $this->jsonResponse(['success' => false, 'error' => 'Missing ids'], 400);
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = iterator_to_array($db->query(
                "SELECT d.doc_path FROM melis_ecom_document d JOIN melis_ecom_doc_relations r ON r.rdoc_doc_id = d.doc_id WHERE d.doc_id = ? AND r.rdoc_order_id = ? LIMIT 1",
                [$docId, $id]
            ));
            if (!$row) return $this->jsonResponse(['success' => false, 'error' => 'Not found'], 404);

            $path = $this->uploadDir($id) . basename(((array) $row[0])['doc_path']);
            if (file_exists($path)) @unlink($path);

            $db->query("DELETE FROM melis_ecom_doc_relations WHERE rdoc_doc_id = ? AND rdoc_order_id = ?", [$docId, $id]);
            $db->query("DELETE FROM melis_ecom_document WHERE doc_id = ?", [$docId]);

            $this->getEventManager()->trigger('meliscommerce_order_save_end', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_order_page', 'textMessage' => 'tr_meliscommerce_order_page_save_success',
                'typeCode' => 'ECOM_ORDER_ATTACHMENT_DELETE', 'itemId' => $id,
            ]);

            return $this->ok(['id' => $docId]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── GET /orders/:id/attachments/:docId/download ──────────────────────────
    public function attachmentDownloadAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id    = (int) $this->params()->fromRoute('id', 0);
        $docId = (int) $this->params()->fromRoute('docId', 0);
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = iterator_to_array($db->query(
                "SELECT d.doc_name, d.doc_path FROM melis_ecom_document d JOIN melis_ecom_doc_relations r ON r.rdoc_doc_id = d.doc_id WHERE d.doc_id = ? AND r.rdoc_order_id = ? LIMIT 1",
                [$docId, $id]
            ));
            if (!$row) return $this->jsonResponse(['success' => false, 'error' => 'Not found'], 404);
            $r    = (array) $row[0];
            $dir  = $this->uploadDir($id);
            $file = $dir . basename($r['doc_path']);
            if (!file_exists($file)) return $this->jsonResponse(['success' => false, 'error' => 'File not found on disk'], 404);

            /** @var HttpResponse $response */
            $response = $this->getResponse();
            $response->setStatusCode(200);
            $mime = mime_content_type($file) ?: 'application/octet-stream';
            $response->getHeaders()->addHeaders([
                'Content-Type'        => $mime,
                'Content-Disposition' => 'attachment; filename="' . addslashes($r['doc_name']) . '"',
                'Content-Length'      => filesize($file),
            ]);
            $response->setContent(file_get_contents($file));
            return $response;
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/create ──────────────────────────────────────────────────
    public function createAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b         = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $reference = trim($b['reference'] ?? '');
            if ($reference === '') return $this->jsonResponse(['success' => false, 'error' => 'Reference is required'], 400);

            $db       = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $status   = (int) ($b['status'] ?? 1);
            $clientId = (int) ($b['clientId'] ?? 0);
            $now      = date('Y-m-d H:i:s');

            $db->query(
                "INSERT INTO melis_ecom_order (ord_reference, ord_status, ord_client_id, ord_client_person_id, ord_country_id, ord_billing_address, ord_delivery_address, ord_date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [$reference, $status, $clientId ?: 0, 0, 0, 0, 0, $now]
            );
            $newId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];

            $this->getEventManager()->trigger('meliscommerce_checkout_order_add', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_order_checkout_add_order', 'textMessage' => 'tr_meliscommerce_order_checkout_save_success',
                'typeCode' => 'ECOM_CHECKOUT_ORDER_ADD', 'itemId' => $newId,
            ]);

            return $this->ok(['id' => $newId, 'reference' => $reference]);
        } catch (\Throwable $e) {
            return $this->err($e);
        }
    }

    // ─── POST /orders/:id/save ────────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->jsonResponse(['success' => false, 'error' => 'Missing order id'], 400);
        try {
            $b   = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $cur = iterator_to_array($db->query("SELECT ord_status FROM melis_ecom_order WHERE ord_id = ? LIMIT 1", [$id]));
            if (!$cur) return $this->jsonResponse(['success' => false, 'error' => 'Order not found'], 404);

            $fields = [];
            $params = [];
            if (isset($b['status'])) {
                $fields[] = 'ord_status = ?';
                $params[] = (int) $b['status'];
            }
            if (isset($b['reference']) && trim($b['reference']) !== '') {
                $fields[] = 'ord_reference = ?';
                $params[] = trim($b['reference']);
            }
            if ($fields) {
                $params[] = $id;
                $db->query('UPDATE melis_ecom_order SET ' . implode(', ', $fields) . ' WHERE ord_id = ?', $params);

                $this->getEventManager()->trigger('meliscommerce_order_save_end', $this, [
                    'success' => true, 'textTitle' => 'tr_meliscommerce_order_page', 'textMessage' => 'tr_meliscommerce_order_page_save_success',
                    'typeCode' => 'ECOM_ORDER_UPDATE', 'itemId' => $id,
                ]);
            }
            return $this->ok(['id' => $id]);
        } catch (\Throwable $e) {
            return $this->err($e);
        }
    }

    // ─── POST /orders/:id/status ──────────────────────────────────────────────
    public function statusSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->jsonResponse(['success' => false, 'error' => 'Missing order id'], 400);
        try {
            $body      = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $newStatus = (int) ($body['status'] ?? 0);
            if (!$newStatus) return $this->jsonResponse(['success' => false, 'error' => 'Missing status'], 400);

            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $cur = iterator_to_array($db->query("SELECT ord_status FROM melis_ecom_order WHERE ord_id = ? LIMIT 1", [$id]));
            if (!$cur) return $this->jsonResponse(['success' => false, 'error' => 'Order not found'], 404);
            if ((int) ((array) $cur[0])['ord_status'] === 5) {
                return $this->jsonResponse(['success' => false, 'error' => 'Cancelled order cannot be updated'], 422);
            }

            $db->query("UPDATE melis_ecom_order SET ord_status = ? WHERE ord_id = ?", [$newStatus, $id]);

            $this->getEventManager()->trigger('meliscommerce_order_status_save_end', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_order_page', 'textMessage' => 'tr_meliscommerce_order_page_save_success',
                'typeCode' => 'ECOM_ORDER_STATUS_UPDATE', 'itemId' => $id,
            ]);

            return $this->ok(['id' => $id, 'status' => $newStatus]);
        } catch (\Throwable $e) {
            return $this->err($e);
        }
    }

    // ─── POST /orders/:id/address ─────────────────────────────────────────────
    public function addressSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->jsonResponse(['success' => false, 'error' => 'Missing order id'], 400);
        try {
            $b    = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $type = (int) ($b['type'] ?? 0);
            if (!in_array($type, [1, 2], true)) return $this->jsonResponse(['success' => false, 'error' => 'Invalid address type'], 400);

            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $now = date('Y-m-d H:i:s');

            $existing = iterator_to_array($db->query(
                "SELECT oadd_id FROM melis_ecom_order_address WHERE oadd_order_id = ? AND oadd_type = ? LIMIT 1",
                [$id, $type]
            ));

            $cols = [
                'oadd_order_id' => $id, 'oadd_type' => $type,
                'oadd_company' => $b['company'] ?? '', 'oadd_civility' => $b['civility'] ? (int) $b['civility'] : null,
                'oadd_firstname' => $b['firstname'] ?? '', 'oadd_name' => $b['name'] ?? '',
                'oadd_middle_name' => $b['middleName'] ?? '', 'oadd_num' => $b['num'] ?? '',
                'oadd_street' => $b['street'] ?? '', 'oadd_building_name' => $b['building'] ?? '',
                'oadd_stairs' => $b['stairs'] ?? '', 'oadd_city' => $b['city'] ?? '',
                'oadd_state' => $b['state'] ?? '', 'oadd_country' => $b['country'] ?? '',
                'oadd_zipcode' => $b['zipcode'] ?? '', 'oadd_phone_mobile' => $b['phoneMobile'] ?? '',
                'oadd_phone_landline' => $b['phoneLandline'] ?? '', 'oadd_complementary' => $b['complementary'] ?? '',
                'oadd_creation_date' => $now,
            ];

            if ($existing) {
                $oadd_id = (int) ((array) $existing[0])['oadd_id'];
                $sets    = implode(', ', array_map(fn($k) => "$k = ?", array_keys($cols)));
                $db->query("UPDATE melis_ecom_order_address SET $sets WHERE oadd_id = ?", array_merge(array_values($cols), [$oadd_id]));

                $this->getEventManager()->trigger('meliscommerce_order_save_end', $this, [
                    'success' => true, 'textTitle' => 'tr_meliscommerce_order_page', 'textMessage' => 'tr_meliscommerce_order_page_save_success',
                    'typeCode' => 'ECOM_ORDER_ADDRESS_UPDATE', 'itemId' => $id,
                ]);

                return $this->ok(['id' => $oadd_id]);
            }

            $keys   = implode(', ', array_keys($cols));
            $placeh = implode(', ', array_fill(0, count($cols), '?'));
            $db->query("INSERT INTO melis_ecom_order_address ($keys) VALUES ($placeh)", array_values($cols));
            $newId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];

            $this->getEventManager()->trigger('meliscommerce_order_save_end', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_order_page', 'textMessage' => 'tr_meliscommerce_order_page_save_success',
                'typeCode' => 'ECOM_ORDER_ADDRESS_UPDATE', 'itemId' => $id,
            ]);

            return $this->ok(['id' => $newId]);
        } catch (\Throwable $e) {
            return $this->err($e);
        }
    }

    // ─── POST /orders/:id/shipping ────────────────────────────────────────────
    public function shippingSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->jsonResponse(['success' => false, 'error' => 'Missing order id'], 400);
        try {
            $b    = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $code = trim($b['trackingCode'] ?? '');
            if ($code === '') return $this->jsonResponse(['success' => false, 'error' => 'Tracking code is required'], 400);

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $db->query(
                "INSERT INTO melis_ecom_order_shipping (oship_order_id, oship_tracking_code, oship_content, oship_date_sent) VALUES (?, ?, ?, ?)",
                [$id, $code, $b['content'] ?? '', $b['dateSent'] ?: date('Y-m-d')]
            );
            $newId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];

            $this->getEventManager()->trigger('meliscommerce_order_save_end', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_order_page', 'textMessage' => 'tr_meliscommerce_order_page_save_success',
                'typeCode' => 'ECOM_ORDER_SHIPPING_UPDATE', 'itemId' => $id,
            ]);

            return $this->ok(['id' => $newId]);
        } catch (\Throwable $e) {
            return $this->err($e);
        }
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // ── Checkout wizard ("New Order") — wraps the legacy MelisComOrderCheckoutService /
    //    MelisComBasketService instead of duplicating their logic. Wizard state lives in
    //    the SAME session mechanism the legacy BO checkout uses (Laminas\Session\Container
    //    'meliscommerce', key 'checkout'), but under a DISTINCT site id so an in-progress
    //    React wizard never collides with an open legacy checkout tab in the same session.
    // ═══════════════════════════════════════════════════════════════════════════

    private const WIZARD_SITE_ID = 'react-order-wizard';

    /** Always use this instead of getServiceManager()->get('MelisComOrderCheckoutService')
     *  directly — the service's siteId is a plain instance property, never persisted, so it
     *  MUST be primed on every single call or it silently reads/writes the wrong session bucket. */
    private function checkoutSvc(): \MelisCommerce\Service\MelisComOrderCheckoutService
    {
        $svc = $this->getServiceManager()->get('MelisComOrderCheckoutService');
        $svc->setSiteId(self::WIZARD_SITE_ID);
        return $svc;
    }

    private function checkoutContainer(): \Laminas\Session\Container
    {
        return new \Laminas\Session\Container('meliscommerce');
    }

    private function checkoutState(): array
    {
        $container = $this->checkoutContainer();
        return $container['checkout'][self::WIZARD_SITE_ID] ?? [];
    }

    // ─── POST /orders/checkout/start ──────────────────────────────────────────
    public function checkoutStartAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $container = $this->checkoutContainer();
            if (empty($container['checkout']) || !is_array($container['checkout'])) {
                $container['checkout'] = [];
            }
            $container['checkout'][self::WIZARD_SITE_ID] = ['countryId' => null];
            return $this->ok(['started' => true]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── DEV ONLY — dumps the raw wizard session bucket, remove before Phase 6 ──
    public function checkoutDebugStateAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            return $this->ok($this->checkoutState());
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/checkout/select-contact ─────────────────────────────────
    public function checkoutSelectContactAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $contactId = (int) ($b['contactId'] ?? 0);
            if (!$contactId) return $this->jsonResponse(['success' => false, 'error' => 'Missing contactId'], 400);

            $container = $this->checkoutContainer();
            $state = $container['checkout'][self::WIZARD_SITE_ID] ?? [];
            $state['contactId'] = $contactId;
            $container['checkout'][self::WIZARD_SITE_ID] = $state;
            return $this->ok(['contactId' => $contactId]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/checkout/select-account ─────────────────────────────────
    public function checkoutSelectAccountAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $clientId = (int) ($b['clientId'] ?? 0);
            if (!$clientId) return $this->jsonResponse(['success' => false, 'error' => 'Missing clientId'], 400);

            $container = $this->checkoutContainer();
            $state = $container['checkout'][self::WIZARD_SITE_ID] ?? [];
            $state['clientId'] = $clientId;
            $state['deliveryMethod'] = $state['deliveryMethod'] ?? 'delivery';
            $container['checkout'][self::WIZARD_SITE_ID] = $state;

            // Empty + reprime the persistent basket for this client — mirrors legacy's
            // selectContactAccountAction, so switching accounts never carries a stale basket.
            $this->getServiceManager()->get('MelisComBasketService')->emptyBasket($clientId);

            return $this->ok(['clientId' => $clientId]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/checkout/country ────────────────────────────────────────
    public function checkoutSetCountryAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $countryId = (int) ($b['countryId'] ?? 0);
            if (!$countryId) return $this->jsonResponse(['success' => false, 'error' => 'Missing countryId'], 400);

            $container = $this->checkoutContainer();
            $state = $container['checkout'][self::WIZARD_SITE_ID] ?? [];
            $state['countryId'] = $countryId;
            $container['checkout'][self::WIZARD_SITE_ID] = $state;

            // Country drives price/stock — empty the basket on change, matching legacy.
            if (!empty($state['clientId'])) {
                $this->getServiceManager()->get('MelisComBasketService')->emptyBasket((int) $state['clientId']);
            }

            return $this->ok(['countryId' => $countryId]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── GET /orders/checkout/contacts ────────────────────────────────────────
    // Contact-step DataTable for the wizard: replicates MelisEcomClientPersonTable::
    // getContactLists()'s shape as used by the legacy checkout controller's own
    // getContactListAction (cart-count + last-order columns), restricted to contacts
    // that have at least one linked account (INNER JOIN, same restriction legacy applies).
    public function checkoutContactsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $page   = max(1, (int) $this->params()->fromQuery('page', 1));
            $limit  = min(200, max(1, (int) $this->params()->fromQuery('limit', 25)));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $offset = ($page - 1) * $limit;

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $where  = [];
            $params = [];
            if ($search !== '') {
                $like    = '%' . $search . '%';
                $where[] = '(p.cper_firstname LIKE ? OR p.cper_name LIKE ? OR p.cper_email LIKE ?)';
                $params  = array_merge($params, [$like, $like, $like]);
            }
            $whereClause = $where ? 'AND ' . implode(' AND ', $where) : '';

            $countRow = iterator_to_array($db->query(
                "SELECT COUNT(DISTINCT p.cper_id) AS total
                 FROM melis_ecom_client_person p
                 INNER JOIN melis_ecom_client_person_rel rel ON rel.cpr_client_person_id = p.cper_id
                 $whereClause", $params
            ));
            $total = (int) ($countRow[0]['total'] ?? 0);

            $dataSql = "
                SELECT p.cper_id, p.cper_status, p.cper_firstname, p.cper_name, p.cper_email,
                       g.cgroup_name,
                       (SELECT COUNT(*) FROM melis_ecom_order o WHERE o.ord_client_person_id = p.cper_id) AS num_orders,
                       (SELECT MAX(o2.ord_date_creation) FROM melis_ecom_order o2 WHERE o2.ord_client_person_id = p.cper_id) AS last_order
                FROM melis_ecom_client_person p
                INNER JOIN melis_ecom_client_person_rel rel ON rel.cpr_client_person_id = p.cper_id
                LEFT JOIN melis_ecom_client c ON c.cli_id = rel.cpr_client_id
                LEFT JOIN melis_ecom_client_groups g ON g.cgroup_id = c.cli_group_id
                $whereClause
                GROUP BY p.cper_id
                ORDER BY p.cper_id DESC
                LIMIT ? OFFSET ?
            ";
            $rows = $db->query($dataSql, array_merge($params, [$limit, $offset]));

            $items = [];
            foreach ($rows as $row) {
                $r = (array) $row;
                $items[] = [
                    'id'        => (int) $r['cper_id'],
                    'status'    => (int) $r['cper_status'],
                    'firstname' => $r['cper_firstname'] ?? '',
                    'name'      => $r['cper_name'] ?? '',
                    'email'     => $r['cper_email'] ?? '',
                    'groupName' => $r['cgroup_name'] ?? '',
                    'numOrders' => (int) ($r['num_orders'] ?? 0),
                    'lastOrder' => $r['last_order'] ?? null,
                ];
            }
            return $this->ok(['items' => $items, 'total' => $total, 'page' => $page, 'limit' => $limit]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    private function titleTypeId($db): int
    {
        $rows = iterator_to_array($db->query("SELECT ptt_id FROM melis_ecom_product_text_type WHERE ptt_code = 'TITLE' LIMIT 1", []));
        return $rows ? (int) ((array) $rows[0])['ptt_id'] : 1;
    }

    private function attributeValueNameSql(): string
    {
        return "COALESCE(
            NULLIF(tr.avt_v_varchar, ''), NULLIF(tr.avt_v_text, ''),
            CAST(tr.avt_v_int AS CHAR), CAST(tr.avt_v_float AS CHAR),
            CASE WHEN tr.avt_v_bool IS NOT NULL THEN CAST(tr.avt_v_bool AS CHAR) END,
            CAST(tr.avt_v_datetime AS CHAR),
            NULLIF(av.atval_reference, '')
        )";
    }

    private function clientGroupId($db, int $clientId): int
    {
        $rows = iterator_to_array($db->query('SELECT cli_group_id FROM melis_ecom_client WHERE cli_id = ? LIMIT 1', [$clientId]));
        return $rows ? (int) ((array) $rows[0])['cli_group_id'] : -1;
    }

    // ─── GET /orders/checkout/products ────────────────────────────────────────
    public function checkoutProductsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $page   = max(1, (int) $this->params()->fromQuery('page', 1));
            $limit  = min(100, max(1, (int) $this->params()->fromQuery('limit', 25)));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $offset = ($page - 1) * $limit;
            $lang   = $this->langId();

            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $ttId = $this->titleTypeId($db);

            $where  = ['p.prd_status = 1'];
            $params = [];
            if ($search !== '') {
                $like = '%' . $search . '%';
                $where[] = '(p.prd_reference LIKE ? OR EXISTS (SELECT 1 FROM melis_ecom_product_text pt WHERE pt.ptxt_prd_id = p.prd_id AND pt.ptxt_field_short LIKE ?))';
                $params  = array_merge($params, [$like, $like]);
            }
            $wc = 'WHERE ' . implode(' AND ', $where);

            $total = (int) ((array) iterator_to_array($db->query("SELECT COUNT(*) AS t FROM melis_ecom_product p $wc", $params))[0])['t'];

            $rows = $db->query("
                SELECT p.prd_id, p.prd_reference,
                       (SELECT pt.ptxt_field_short FROM melis_ecom_product_text pt WHERE pt.ptxt_prd_id = p.prd_id AND pt.ptxt_type = ? AND pt.ptxt_lang_id = ? LIMIT 1) AS name_cur,
                       (SELECT pt2.ptxt_field_short FROM melis_ecom_product_text pt2 WHERE pt2.ptxt_prd_id = p.prd_id AND pt2.ptxt_type = ? AND pt2.ptxt_field_short <> '' LIMIT 1) AS name_any,
                       (SELECT d.doc_path FROM melis_ecom_doc_relations dr JOIN melis_ecom_document d ON d.doc_id = dr.rdoc_doc_id WHERE dr.rdoc_product_id = p.prd_id AND d.doc_type_id != 2 ORDER BY d.doc_id ASC LIMIT 1) AS image_path,
                       (SELECT COUNT(*) FROM melis_ecom_variant v WHERE v.var_prd_id = p.prd_id) AS variant_count
                FROM melis_ecom_product p
                $wc
                ORDER BY p.prd_id DESC
                LIMIT ? OFFSET ?
            ", array_merge([$ttId, $lang, $ttId], $params, [$limit, $offset]));

            $items = [];
            foreach ($rows as $row) {
                $r = (array) $row;
                $items[] = [
                    'id'           => (int) $r['prd_id'],
                    'reference'    => $r['prd_reference'] ?? '',
                    'name'         => ($r['name_cur'] ?: null) ?? ($r['name_any'] ?: null) ?? ($r['prd_reference'] ?? ''),
                    'image'        => $r['image_path'] ?? '',
                    'variantCount' => (int) ($r['variant_count'] ?? 0),
                ];
            }
            return $this->ok(['items' => $items, 'total' => $total, 'page' => $page, 'limit' => $limit]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── GET /orders/checkout/products/:productId/variants ───────────────────
    // Prix/stock résolus via les MÊMES services que checkoutStep1_prePayment (MelisComPriceService::
    // getItemPrice / MelisComVariantService::getVariantFinalStocks) pour garantir que ce que l'utilisateur
    // voit en parcourant le catalogue est bien ce qui sera facturé à la confirmation de la commande.
    public function checkoutVariantsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $productId = (int) $this->params()->fromRoute('productId', 0);
        if (!$productId) return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400);
        try {
            $state     = $this->checkoutState();
            $countryId = (int) ($state['countryId'] ?? -1);
            $clientId  = (int) ($state['clientId'] ?? 0);

            $db      = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang    = $this->langId();
            $groupId = $clientId ? $this->clientGroupId($db, $clientId) : -1;
            $valName = $this->attributeValueNameSql();

            $priceSvc   = $this->getServiceManager()->get('MelisComPriceService');
            $variantSvc = $this->getServiceManager()->get('MelisComVariantService');

            $items = [];
            foreach ($db->query("SELECT v.var_id, v.var_sku,
                    (SELECT d.doc_path FROM melis_ecom_doc_relations dr JOIN melis_ecom_document d ON d.doc_id = dr.rdoc_doc_id WHERE dr.rdoc_variant_id = v.var_id AND d.doc_type_id <> 2 LIMIT 1) AS image,
                    (SELECT GROUP_CONCAT($valName SEPARATOR ', ') FROM melis_ecom_variant_attribute_value vatv JOIN melis_ecom_attribute_value av ON av.atval_id = vatv.vatv_attribute_value_id LEFT JOIN melis_ecom_attribute_value_trans tr ON tr.av_attribute_value_id = av.atval_id AND tr.avt_lang_id = ? WHERE vatv.vatv_variant_id = v.var_id) AS attrs
                FROM melis_ecom_variant v WHERE v.var_prd_id = ? AND v.var_status = 1 ORDER BY v.var_main_variant DESC, v.var_id", [$lang, $productId]) as $row) {
                $r = (array) $row;
                $variantId = (int) $r['var_id'];

                $price = $priceSvc->getItemPrice($variantId, $countryId, $groupId, 'variant', ['skipLogsTranslation' => true]);
                $stock = $variantSvc->getVariantFinalStocks($variantId, $countryId);

                $items[] = [
                    'id'         => $variantId,
                    'sku'        => (string) ($r['var_sku'] ?? ''),
                    'image'      => (string) ($r['image'] ?? ''),
                    'attributes' => (string) ($r['attrs'] ?? ''),
                    'price'      => $price['price'] !== null ? (float) $price['price'] : null,
                    'stock'      => isset($stock->stock_quantity) ? (int) $stock->stock_quantity : null,
                ];
            }
            return $this->ok(['items' => $items]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── GET /orders/checkout/basket ──────────────────────────────────────────
    public function checkoutBasketAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            return $this->ok(['items' => $this->basketItems()]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    /** Shared by checkoutBasketAction/Add/SetQty/Remove — reads the CURRENT basket state after
     *  each mutation (server stays the single source of truth for pricing, never trust a client total). */
    private function basketItems(): array
    {
        $state     = $this->checkoutState();
        $clientId  = (int) ($state['clientId'] ?? 0);
        if (!$clientId) return [];
        $countryId = (int) ($state['countryId'] ?? -1);

        $db         = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
        $lang       = $this->langId();
        $ttId       = $this->titleTypeId($db);
        $groupId    = $this->clientGroupId($db, $clientId);
        $priceSvc   = $this->getServiceManager()->get('MelisComPriceService');
        $basketSvc  = $this->getServiceManager()->get('MelisComBasketService');

        $basket = $basketSvc->getBasket($clientId);
        $items  = [];
        if ($basket) {
            foreach ($basket as $line) {
                $variantId = (int) $line->getVariantId();
                $qty       = (int) $line->getQuantity();

                $rows = iterator_to_array($db->query(
                    "SELECT v.var_sku,
                            (SELECT pt.ptxt_field_short FROM melis_ecom_product_text pt WHERE pt.ptxt_prd_id = v.var_prd_id AND pt.ptxt_type = ? AND pt.ptxt_lang_id = ? LIMIT 1) AS name_cur,
                            (SELECT pt2.ptxt_field_short FROM melis_ecom_product_text pt2 WHERE pt2.ptxt_prd_id = v.var_prd_id AND pt2.ptxt_type = ? AND pt2.ptxt_field_short <> '' LIMIT 1) AS name_any
                     FROM melis_ecom_variant v WHERE v.var_id = ? LIMIT 1", [$ttId, $lang, $ttId, $variantId]
                ));
                if (!$rows) continue;
                $r = (array) $rows[0];

                $price = $priceSvc->getItemPrice($variantId, $countryId, $groupId, 'variant', ['skipLogsTranslation' => true]);
                $unitPrice = $price['price'] !== null ? (float) $price['price'] : null;

                $items[] = [
                    'variantId'   => $variantId,
                    'sku'         => $r['var_sku'] ?? '',
                    'productName' => ($r['name_cur'] ?: null) ?? ($r['name_any'] ?: null) ?? '',
                    'quantity'    => $qty,
                    'price'       => $unitPrice,
                    'lineTotal'   => $unitPrice !== null ? $unitPrice * $qty : null,
                ];
            }
        }
        return $items;
    }

    // ─── POST /orders/checkout/basket/add ─────────────────────────────────────
    public function checkoutBasketAddAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $state    = $this->checkoutState();
            $clientId = (int) ($state['clientId'] ?? 0);
            if (!$clientId) return $this->jsonResponse(['success' => false, 'error' => 'No account selected'], 400);

            $b         = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $variantId = (int) ($b['variantId'] ?? 0);
            $quantity  = max(1, (int) ($b['quantity'] ?? 1));
            if (!$variantId) return $this->jsonResponse(['success' => false, 'error' => 'Missing variantId'], 400);

            $this->getServiceManager()->get('MelisComBasketService')->addVariantToBasket($variantId, $quantity, $clientId);
            return $this->ok(['items' => $this->basketItems()]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/checkout/basket/qty ─────────────────────────────────────
    // ⚠️ MelisComBasketService::removeVariantFromBasket's $quantity is an ABSOLUTE new total, not a delta.
    public function checkoutBasketSetQtyAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $state    = $this->checkoutState();
            $clientId = (int) ($state['clientId'] ?? 0);
            if (!$clientId) return $this->jsonResponse(['success' => false, 'error' => 'No account selected'], 400);

            $b         = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $variantId = (int) ($b['variantId'] ?? 0);
            $quantity  = max(0, (int) ($b['quantity'] ?? 0));
            if (!$variantId) return $this->jsonResponse(['success' => false, 'error' => 'Missing variantId'], 400);

            $this->getServiceManager()->get('MelisComBasketService')->removeVariantFromBasket($variantId, $quantity, $clientId);
            return $this->ok(['items' => $this->basketItems()]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/checkout/basket/remove ──────────────────────────────────
    public function checkoutBasketRemoveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $state    = $this->checkoutState();
            $clientId = (int) ($state['clientId'] ?? 0);
            if (!$clientId) return $this->jsonResponse(['success' => false, 'error' => 'No account selected'], 400);

            $b         = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $variantId = (int) ($b['variantId'] ?? 0);
            if (!$variantId) return $this->jsonResponse(['success' => false, 'error' => 'Missing variantId'], 400);

            $this->getServiceManager()->get('MelisComBasketService')->removeVariantFromBasket($variantId, 0, $clientId);
            return $this->ok(['items' => $this->basketItems()]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/checkout/addresses ──────────────────────────────────────
    // Both ids must reference addresses ALREADY saved in the selected account's address book
    // (the wizard's Addresses step saves new addresses via the existing /accounts/:id/addresses/save
    // endpoint FIRST, then references them here by id) — sidesteps re-implementing
    // MelisComOrderCheckoutService::validateAddresses()'s raw-array/new-address branch.
    // Resolves id → full melis_ecom_client_address row before calling the legacy service, exactly
    // mirroring what the legacy controller's own selectAddressesAction does — the service stores
    // whatever it's given VERBATIM in session for checkoutStep1_prePayment to consume later, and it
    // hard-expects a full cadd_*-keyed array, never a bare id.
    public function checkoutValidateAddressesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $state    = $this->checkoutState();
            $clientId = (int) ($state['clientId'] ?? 0);
            if (!$clientId) return $this->jsonResponse(['success' => false, 'error' => 'No account selected'], 400);

            $b          = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $billingId  = (int) ($b['billingId'] ?? 0);
            $deliveryId = (int) ($b['deliveryId'] ?? 0);
            if (!$billingId || !$deliveryId) return $this->jsonResponse(['success' => false, 'error' => 'Missing billingId/deliveryId'], 400);

            $addrTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
            $billingRow  = $addrTable->getEntryById($billingId)->current();
            $deliveryRow = $addrTable->getEntryById($deliveryId)->current();
            if (!$billingRow || !$deliveryRow) return $this->jsonResponse(['success' => false, 'error' => 'Address not found'], 404);

            $billingArr  = (array) $billingRow;
            $deliveryArr = (array) $deliveryRow;
            if ((int) ($billingArr['cadd_client_id'] ?? 0) !== $clientId || (int) ($deliveryArr['cadd_client_id'] ?? 0) !== $clientId) {
                return $this->jsonResponse(['success' => false, 'error' => 'Address does not belong to the selected account'], 403);
            }

            $result = $this->checkoutSvc()->validateAddresses($deliveryArr, $billingArr);
            return $this->ok(['success' => (bool) ($result['success'] ?? false)]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── GET /orders/checkout/summary ─────────────────────────────────────────
    public function checkoutSummaryAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $state    = $this->checkoutState();
            $clientId = (int) ($state['clientId'] ?? 0);
            if (!$clientId) return $this->jsonResponse(['success' => false, 'error' => 'No account selected'], 400);

            $costs = $this->checkoutSvc()->computeAllCosts($clientId);
            return $this->ok([
                'success'  => (bool) ($costs['success'] ?? false),
                'subTotal' => (float) ($costs['costs']['order']['subTotal'] ?? 0),
                'total'    => (float) ($costs['costs']['order']['total'] ?? 0),
                'errors'   => $costs['costs']['order']['errors'] ?? [],
                'items'    => $this->basketItems(),
            ]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/checkout/coupon ─────────────────────────────────────────
    // MelisComOrderCheckoutService::validateCoupon() only VALIDATES (status/dates/usage-limit/client
    // assignment) — it doesn't itself persist to session or apply a discount (that's done by the
    // legacy CONTROLLER + only actually reflected in totals via a listener on the cost-computation
    // event, none of which is wired in this deployment). V1 scope: validate + store a single couponId
    // in session for future use; the displayed total is computeAllCosts()'s real, undiscounted figure.
    public function checkoutCouponAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $state    = $this->checkoutState();
            $clientId = (int) ($state['clientId'] ?? 0);
            if (!$clientId) return $this->jsonResponse(['success' => false, 'error' => 'No account selected'], 400);

            $b    = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $code = trim((string) ($b['code'] ?? ''));
            if ($code === '') return $this->jsonResponse(['success' => false, 'error' => 'Missing coupon code'], 400);

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $productIds = [];
            foreach ($db->query(
                "SELECT DISTINCT v.var_prd_id FROM melis_ecom_basket_persistent b JOIN melis_ecom_variant v ON v.var_id = b.bper_variant_id WHERE b.bper_client_id = ?",
                [$clientId]
            ) as $row) { $productIds[] = (int) ((array) $row)['var_prd_id']; }

            $result = $this->checkoutSvc()->validateCoupon($code, $clientId, $productIds);
            if (empty($result['success'])) {
                return $this->jsonResponse(['success' => false, 'error' => (string) ($result['error'] ?? 'Invalid coupon')], 422);
            }

            $couponId = isset($result['coupon']->coup_id) ? (int) $result['coupon']->coup_id : null;
            $container = $this->checkoutContainer();
            $s = $container['checkout'][self::WIZARD_SITE_ID] ?? [];
            $s['couponId'] = $couponId;
            $container['checkout'][self::WIZARD_SITE_ID] = $s;

            return $this->ok(['couponId' => $couponId, 'type' => $result['type'] ?? null]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/checkout/confirm ────────────────────────────────────────
    // The ONE step that writes an order row. Wraps checkoutStep1_prePayment() (creates the order
    // at status -1, exactly like legacy's Summary-step confirm) immediately followed — same request —
    // by the payment finalization legacy would normally do on a SEPARATE payment-gateway callback
    // (checkoutStep2_postPayment(), which can't be called as-is here: it requires an event listener
    // that decodes a real gateway's response, none of which exists in this deployment). Collapsing
    // both into one atomic call is a deliberate scope decision (see plan): this repo has no real
    // payment gateway, so exposing two separate clickable steps would leave orphaned status=-1 orders
    // every time a user closed the wizard between "confirm" and "pay". The logic below replicates
    // checkoutStep2_postPayment()'s post-success body directly (payment row, stock deduction, empty
    // basket) using values we already know, instead of the event-populated results it normally reads.
    public function checkoutConfirmAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $state    = $this->checkoutState();
            $clientId = (int) ($state['clientId'] ?? 0);
            if (!$clientId) return $this->jsonResponse(['success' => false, 'error' => 'No account selected'], 400);

            $svc    = $this->checkoutSvc();
            $result = $svc->checkoutStep1_prePayment($clientId);

            if (empty($result['success'])) {
                // Business-rule failure (stock/price/address issue) — 200 + success:false INSIDE
                // data, not a 422 envelope, so the frontend gets the structured errors object back
                // instead of apiFetch collapsing it to a flat thrown Error message.
                return $this->ok(['success' => false, 'errors' => $result['errors'] ?? []]);
            }

            $orderId = (int) $result['orderId'];
            $db      = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            // Total cost of the just-created order (basket + shipment), same computation legacy's
            // own checkoutStep2_postPayment uses to sanity-check price-paid vs. total — here there's
            // no external "price paid" to compare against, so we simply record the computed total.
            $costs     = $svc->computeOrderTotalCosts($orderId);
            $totalCost = (float) ($costs['costs']['total'] ?? 0);
            $orderSub  = (float) ($costs['costs']['order']['subTotal'] ?? 0);
            $shipTotal = (float) ($costs['costs']['shipment']['total'] ?? 0);

            // Default currency (no real gateway to report one) — best-effort, falls back to null.
            $curRow = iterator_to_array($db->query('SELECT cur_id FROM melis_ecom_currency WHERE cur_default = 1 LIMIT 1', []));
            $currencyId = $curRow ? (int) ((array) $curRow[0])['cur_id'] : null;

            // "TRANSFERT" (Account Transfer) is the closest existing payment-type code to a manually
            // created BO order — there is no dedicated "manual"/"cash" seed in this platform.
            $ptRow = iterator_to_array($db->query("SELECT opty_id FROM melis_ecom_order_payment_type WHERE opty_code = 'TRANSFERT' LIMIT 1", []));
            $paymentTypeId = $ptRow ? (int) ((array) $ptRow[0])['opty_id'] : null;

            $db->query(
                "INSERT INTO melis_ecom_order_payment
                    (opay_order_id, opay_price_total, opay_price_order, opay_price_shipping, opay_currency_id,
                     opay_payment_type_id, opay_transac_id, opay_transac_return_value, opay_transac_price_paid_confirm,
                     opay_transac_raw_response, opay_date_payment)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$orderId, $totalCost, $orderSub, $shipTotal, $currencyId, $paymentTypeId, 'BO-MANUAL', 'OK', $totalCost, 'Manual order (BO checkout wizard)', date('Y-m-d H:i:s')]
            );

            $db->query('UPDATE melis_ecom_order SET ord_status = 1 WHERE ord_id = ?', [$orderId]);

            // Deduct stock for each basket item, mirroring checkoutStep2_postPayment's own loop.
            $countryId  = (int) ($state['countryId'] ?? -1);
            $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
            $stockTable = $this->getServiceManager()->get('MelisEcomVariantStockTable');
            $basketSvc  = $this->getServiceManager()->get('MelisComBasketService');
            $basket     = $basketSvc->getBasket($clientId);
            if ($basket) {
                foreach ($basket as $line) {
                    $variantId = (int) $line->getVariantId();
                    $qty       = (int) $line->getQuantity();
                    $stock     = $variantSvc->getVariantFinalStocks($variantId, $countryId);
                    if ($stock && isset($stock->stock_id)) {
                        $db->query('UPDATE melis_ecom_variant_stock SET stock_quantity = GREATEST(0, stock_quantity - ?) WHERE stock_id = ?', [$qty, $stock->stock_id]);
                    }
                }
            }

            // Note: legacy's own coupon-order "mark as used" step reads melis_ecom_coupon_order rows
            // for this order — nothing in checkoutStep1_prePayment ever inserts one (confirmed by
            // reading the full checkout flow), so there is nothing to mark here either; V1 scope
            // limits coupons to validation only (see checkoutCouponAction).

            $basketSvc->emptyBasket($clientId);

            $refRow = iterator_to_array($db->query('SELECT ord_reference FROM melis_ecom_order WHERE ord_id = ?', [$orderId]));
            $reference = $refRow ? (string) ((array) $refRow[0])['ord_reference'] : '';

            $container = $this->checkoutContainer();
            $s = $container['checkout'][self::WIZARD_SITE_ID] ?? [];
            $s['orderId'] = $orderId;
            $container['checkout'][self::WIZARD_SITE_ID] = $s;

            $this->getEventManager()->trigger('meliscommerce_checkout_order_add', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_order_checkout_add_order', 'textMessage' => 'tr_meliscommerce_order_checkout_save_success',
                'typeCode' => 'ECOM_CHECKOUT_ORDER_ADD', 'itemId' => $orderId,
            ]);

            return $this->ok(['success' => true, 'orderId' => $orderId, 'reference' => $reference]);
        } catch (\Throwable $e) { return $this->err($e); }
    }

    // ─── POST /orders/:id/message ─────────────────────────────────────────────
    public function messageSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->jsonResponse(['success' => false, 'error' => 'Missing order id'], 400);
        try {
            $b   = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $msg = trim($b['message'] ?? '');
            if ($msg === '') return $this->jsonResponse(['success' => false, 'error' => 'Message cannot be empty'], 400);

            $db     = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $orderRow = iterator_to_array($db->query('SELECT ord_client_id FROM melis_ecom_order WHERE ord_id = ?', [$id]));
            if (!$orderRow) return $this->jsonResponse(['success' => false, 'error' => 'Order not found'], 404);
            $clientId = (int) ((array) $orderRow[0])['ord_client_id'];
            $userId = $this->currentUserId();
            $now    = date('Y-m-d H:i:s');
            $db->query(
                "INSERT INTO melis_ecom_order_message (omsg_order_id, omsg_client_id, omsg_user_id, omsg_message, omsg_date_creation) VALUES (?, ?, ?, ?, ?)",
                [$id, $clientId, $userId ?: null, $msg, $now]
            );
            $newId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];

            $this->getEventManager()->trigger('meliscommerce_order_message_save_end', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_order_page', 'textMessage' => 'tr_meliscommerce_order_message_save_success',
                'typeCode' => 'ECOM_ORDER_MESSAGE_ADD', 'itemId' => $newId,
            ]);

            return $this->ok(['id' => $newId, 'dateCreation' => $now]);
        } catch (\Throwable $e) {
            return $this->err($e);
        }
    }
}
