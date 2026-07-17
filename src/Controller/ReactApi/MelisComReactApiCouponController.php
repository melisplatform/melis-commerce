<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour l'outil Coupons MelisCommerce.
 *
 * Un coupon = code de réduction (pourcentage OU montant fixe), fenêtre de validité,
 * plafond d'utilisation. Deux restrictions optionnelles : clients assignés (coup_type)
 * et produits assignés (coup_product_assign). Onglet "Orders" = historique d'utilisation
 * (lecture seule, melis_ecom_coupon_order).
 *
 * Routes :
 *   GET    /melis/react-api/coupons                        → liste paginée + filtres
 *   GET    /melis/react-api/coupons/stats                   → KPI
 *   GET    /melis/react-api/coupons/:id                     → détail
 *   POST   /melis/react-api/coupons/save                    → créer / mettre à jour
 *   DELETE /melis/react-api/coupons/delete/:id                → supprimer (cascade)
 *   GET    /melis/react-api/coupons/:id/clients              → clients assignés + disponibles
 *   POST   /melis/react-api/coupons/:id/clients               → assigner un client
 *   DELETE /melis/react-api/coupons/:id/clients/:ccliId       → retirer
 *   GET    /melis/react-api/coupons/:id/products              → produits assignés + disponibles
 *   POST   /melis/react-api/coupons/:id/products               → assigner un produit
 *   DELETE /melis/react-api/coupons/:id/products/:cprodId      → retirer
 *   GET    /melis/react-api/coupons/:id/orders                → historique d'utilisation (lecture seule)
 *
 * Logique métier inchangée : l'enregistrement réutilise MelisComCouponService::saveCoupon
 * et la suppression deleteCouponById.
 */
class MelisComReactApiCouponController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_coupon_list_page';

    private function langId(): int
    {
        $c = new \Laminas\Session\Container('meliscore');
        $id = (int) ($c['melis-lang-id'] ?? 0);
        return $id > 0 ? $id : 1;
    }

    private function currentUserId(): int
    {
        try { return (int) ($this->getServiceManager()->get('MelisCoreAuth')->getStorage()->read()->usr_id ?? 0); }
        catch (\Throwable) { return 0; }
    }

    private function formatCoupon(array $r): array
    {
        return [
            'id' => (int) $r['coup_id'],
            'code' => (string) ($r['coup_code'] ?? ''),
            'status' => (int) ($r['coup_status'] ?? 0),
            'assignClients' => (int) ($r['coup_type'] ?? 0) === 1,
            'assignProducts' => (int) ($r['coup_product_assign'] ?? 0) === 1,
            'percentage' => $r['coup_percentage'] !== null ? (float) $r['coup_percentage'] : null,
            'discountValue' => $r['coup_discount_value'] !== null ? (float) $r['coup_discount_value'] : null,
            'maxUseNumber' => $r['coup_max_use_number'] !== null ? (int) $r['coup_max_use_number'] : null,
            'currentUseNumber' => (int) ($r['coup_current_use_number'] ?? 0),
            'dateValidStart' => $r['coup_date_valid_start'] ?? null,
            'dateValidEnd' => $r['coup_date_valid_end'] ?? null,
            'dateCreation' => $r['coup_date_creation'] ?? null,
        ];
    }

    // ─── GET /coupons ──────────────────────────────────────────────────────────
    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $limit  = min(9999, max(1, (int) $this->params()->fromQuery('limit', 50)));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawSt  = $this->params()->fromQuery('status', '');
            $status = ($rawSt !== '' && $rawSt !== null) ? (int) $rawSt : null;

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $where = []; $params = [];
            if ($status !== null) { $where[] = 'coup_status = ?'; $params[] = $status; }
            if ($search !== '') { $where[] = 'coup_code LIKE ?'; $params[] = '%' . $search . '%'; }
            $wc = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $total = (int) ((array) iterator_to_array($db->query("SELECT COUNT(*) AS t FROM melis_ecom_coupon $wc", $params))[0])['t'];

            $rows = $db->query("SELECT * FROM melis_ecom_coupon $wc ORDER BY coup_id DESC LIMIT ?", array_merge($params, [$limit]));

            $items = [];
            foreach ($rows as $r) { $items[] = $this->formatCoupon((array) $r); }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'total' => $total]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /coupons/stats ────────────────────────────────────────────────────
    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = (array) iterator_to_array($db->query(
                'SELECT COUNT(*) AS total, SUM(coup_status=1) AS active, SUM(coup_status=0) AS inactive, SUM(coup_current_use_number) AS uses FROM melis_ecom_coupon', []
            ))[0];
            return $this->jsonResponse(['success' => true, 'data' => [
                'total' => (int) ($row['total'] ?? 0), 'active' => (int) ($row['active'] ?? 0),
                'inactive' => (int) ($row['inactive'] ?? 0), 'uses' => (int) ($row['uses'] ?? 0),
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /coupons/:id ──────────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT * FROM melis_ecom_coupon WHERE coup_id = ? LIMIT 1', [$id]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Coupon not found'], 404); }
            return $this->jsonResponse(['success' => true, 'data' => $this->formatCoupon((array) $rows[0])]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /coupons/save ────────────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b  = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $id = (int) ($b['id'] ?? 0);

            $code = strtoupper(trim((string) ($b['code'] ?? '')));
            if ($code === '') { return $this->jsonResponse(['success' => false, 'error' => 'Le code est obligatoire.'], 400); }

            $num = static fn($v) => ($v === '' || $v === null) ? null : (float) $v;
            $percentage = $num($b['percentage'] ?? null);
            $discount   = $num($b['discountValue'] ?? null);
            if ($percentage === null && $discount === null) {
                return $this->jsonResponse(['success' => false, 'error' => 'Renseignez un pourcentage ou un montant de réduction.'], 400);
            }

            $toDatetime = static function ($v) {
                $v = trim((string) ($v ?? ''));
                return $v !== '' && strtotime($v) ? date('Y-m-d H:i:s', strtotime($v)) : null;
            };

            $coupon = [
                'coup_code' => $code,
                'coup_status' => (int) (!empty($b['status']) ? 1 : 0),
                'coup_type' => (int) (!empty($b['assignClients']) ? 1 : 0),
                'coup_product_assign' => (int) (!empty($b['assignProducts']) ? 1 : 0),
                'coup_percentage' => $percentage,
                'coup_discount_value' => $discount,
                'coup_max_use_number' => ($b['maxUseNumber'] ?? '') === '' || ($b['maxUseNumber'] ?? null) === null ? null : (int) $b['maxUseNumber'],
                'coup_date_valid_start' => $toDatetime($b['dateValidStart'] ?? null),
                'coup_date_valid_end' => $toDatetime($b['dateValidEnd'] ?? null),
            ];
            if ($id <= 0) {
                $coupon['coup_date_creation'] = date('Y-m-d H:i:s');
                $coupon['coup_user_id'] = $this->currentUserId() ?: null;
                $coupon['coup_current_use_number'] = 0;
            }

            ob_start();
            $newId = $this->getServiceManager()->get('MelisComCouponService')->saveCoupon($coupon, $id ?: null);
            ob_end_clean();

            $this->getEventManager()->trigger('meliscommerce_coupon_save_end', $this, [
                'success' => true, 'textTitle' => 'Coupon', 'textMessage' => 'tr_meliscommerce_coupon_save_success',
                'typeCode' => $id ? 'ECOM_COUPON_UPDATE' : 'ECOM_COUPON_ADD', 'itemId' => (int) $newId,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], $id ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /coupons/delete/:id ────────────────────────────────────────────
    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            ob_start();
            $this->getServiceManager()->get('MelisComCouponService')->deleteCouponById($id);
            ob_end_clean();

            $this->getEventManager()->trigger('meliscommerce_coupon_delete_end', $this, [
                'success' => true, 'textTitle' => 'Coupon', 'textMessage' => 'tr_meliscommerce_coupon_delete_success',
                'typeCode' => 'ECOM_COUPON_DELETE', 'itemId' => $id,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    /** Nom lisible d'un client : cli_name, repli sur l'email de la personne principale. */
    private function clientLabelSql(string $alias = 'c'): string
    {
        return "COALESCE(NULLIF($alias.cli_name,''), (SELECT cp.cper_email FROM melis_ecom_client_person cp WHERE cp.cper_client_id = $alias.cli_id AND cp.cper_is_main_person = 1 LIMIT 1), CONCAT('#', $alias.cli_id))";
    }

    // ─── GET /coupons/clients-directory ────────────────────────────────────────
    // Répertoire complet des clients (indépendant d'un coupon) — utilisé pour le
    // formulaire d'un coupon NON encore enregistré (pas d'id pour /coupons/:id/clients).
    public function clientsDirectoryAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lbl = $this->clientLabelSql('c');
            $comp = "(SELECT ccomp_name FROM melis_ecom_client_company WHERE ccomp_client_id = c.cli_id LIMIT 1)";
            $items = [];
            foreach ($db->query("SELECT c.cli_id, $lbl AS name, $comp AS company FROM melis_ecom_client c ORDER BY name", []) as $r) {
                $r = (array) $r; $items[] = ['clientId' => (int) $r['cli_id'], 'name' => (string) $r['name'], 'company' => (string) ($r['company'] ?? '')];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /coupons/:id/clients ──────────────────────────────────────────────
    public function clientsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid coupon'], 400); }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lbl = $this->clientLabelSql('c');
            $comp = "(SELECT ccomp_name FROM melis_ecom_client_company WHERE ccomp_client_id = c.cli_id LIMIT 1)";

            $assigned = [];
            foreach ($db->query("SELECT ccli.ccli_id, c.cli_id, $lbl AS name, $comp AS company FROM melis_ecom_coupon_client ccli
                JOIN melis_ecom_client c ON c.cli_id = ccli.ccli_client_id
                WHERE ccli.ccli_coupon_id = ? ORDER BY ccli.ccli_id DESC", [$id]) as $r) {
                $r = (array) $r; $assigned[] = ['ccliId' => (int) $r['ccli_id'], 'clientId' => (int) $r['cli_id'], 'name' => (string) $r['name'], 'company' => (string) ($r['company'] ?? '')];
            }
            $available = [];
            foreach ($db->query("SELECT c.cli_id, $lbl AS name, $comp AS company FROM melis_ecom_client c
                WHERE c.cli_id NOT IN (SELECT ccli_client_id FROM melis_ecom_coupon_client WHERE ccli_coupon_id = ?)
                ORDER BY name", [$id]) as $r) {
                $r = (array) $r; $available[] = ['clientId' => (int) $r['cli_id'], 'name' => (string) $r['name'], 'company' => (string) ($r['company'] ?? '')];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['assigned' => $assigned, 'available' => $available]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /coupons/:id/clients ─────────────────────────────────────────────
    public function clientSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid coupon'], 400); }
        try {
            $b = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $clientId = (int) ($b['clientId'] ?? 0);
            if ($clientId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid client'], 400); }
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $dup = iterator_to_array($db->query('SELECT ccli_id FROM melis_ecom_coupon_client WHERE ccli_coupon_id = ? AND ccli_client_id = ? LIMIT 1', [$id, $clientId]));
            $newId = $dup ? (int) ((array) $dup[0])['ccli_id'] : null;
            if (!$dup) {
                ob_start();
                $newId = $this->getServiceManager()->get('MelisComCouponService')->saveCouponClient(['ccli_coupon_id' => $id, 'ccli_client_id' => $clientId]);
                ob_end_clean();
            }

            $this->getEventManager()->trigger('meliscommerce_coupon_client_management_end', $this, [
                'success' => true, 'textTitle' => 'Coupon', 'textMessage' => 'tr_meliscommerce_coupon_save_success',
                'typeCode' => 'ECOM_COUPON_ASSIGN', 'itemId' => $id,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /coupons/:id/clients/:ccliId ───────────────────────────────────
    public function clientDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id     = (int) $this->params()->fromRoute('id', 0);
        $ccliId = (int) $this->params()->fromRoute('ccliId', 0);
        if ($ccliId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid'], 400); }
        try {
            $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface')
                ->query('DELETE FROM melis_ecom_coupon_client WHERE ccli_id = ?', [$ccliId]);

            $this->getEventManager()->trigger('meliscommerce_coupon_remove_from_client_end', $this, [
                'success' => true, 'textTitle' => 'Coupon', 'textMessage' => 'tr_meliscommerce_coupon_delete_success_remove',
                'typeCode' => 'ECOM_COUPON_ASSIGN_REMOVE', 'itemId' => $id,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    /** Nom lisible d'un produit : texte TITLE, repli sur la référence. */
    private function productLabelSql(): string
    {
        return "COALESCE((SELECT pt.ptxt_field_short FROM melis_ecom_product_text pt WHERE pt.ptxt_prd_id = p.prd_id AND pt.ptxt_field_short <> '' LIMIT 1), p.prd_reference)";
    }

    // ─── GET /coupons/products-directory ───────────────────────────────────────
    // Répertoire complet des produits (indépendant d'un coupon) — même usage que
    // clients-directory pour un coupon non encore enregistré.
    public function productsDirectoryAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lbl = $this->productLabelSql();
            $items = [];
            foreach ($db->query("SELECT p.prd_id, p.prd_reference, $lbl AS name FROM melis_ecom_product p ORDER BY name", []) as $r) {
                $r = (array) $r; $items[] = ['productId' => (int) $r['prd_id'], 'reference' => (string) $r['prd_reference'], 'name' => (string) $r['name']];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /coupons/:id/products ─────────────────────────────────────────────
    public function productsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid coupon'], 400); }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lbl = $this->productLabelSql();

            $assigned = [];
            foreach ($db->query("SELECT cprod.cprod_id, p.prd_id, p.prd_reference, $lbl AS name FROM melis_ecom_coupon_product cprod
                JOIN melis_ecom_product p ON p.prd_id = cprod.cprod_product_id
                WHERE cprod.cprod_coupon_id = ? ORDER BY cprod.cprod_id DESC", [$id]) as $r) {
                $r = (array) $r; $assigned[] = ['cprodId' => (int) $r['cprod_id'], 'productId' => (int) $r['prd_id'], 'reference' => (string) $r['prd_reference'], 'name' => (string) $r['name']];
            }
            $available = [];
            foreach ($db->query("SELECT p.prd_id, p.prd_reference, $lbl AS name FROM melis_ecom_product p
                WHERE p.prd_id NOT IN (SELECT cprod_product_id FROM melis_ecom_coupon_product WHERE cprod_coupon_id = ?)
                ORDER BY name", [$id]) as $r) {
                $r = (array) $r; $available[] = ['productId' => (int) $r['prd_id'], 'reference' => (string) $r['prd_reference'], 'name' => (string) $r['name']];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['assigned' => $assigned, 'available' => $available]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /coupons/:id/products ────────────────────────────────────────────
    public function productSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid coupon'], 400); }
        try {
            $b = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $productId = (int) ($b['productId'] ?? 0);
            if ($productId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $dup = iterator_to_array($db->query('SELECT cprod_id FROM melis_ecom_coupon_product WHERE cprod_coupon_id = ? AND cprod_product_id = ? LIMIT 1', [$id, $productId]));
            $newId = $dup ? (int) ((array) $dup[0])['cprod_id'] : null;
            if (!$dup) {
                ob_start();
                $newId = $this->getServiceManager()->get('MelisComCouponService')->saveCouponProduct(['cprod_coupon_id' => $id, 'cprod_product_id' => $productId]);
                ob_end_clean();
            }

            $this->getEventManager()->trigger('meliscommerce_coupon_client_management_end', $this, [
                'success' => true, 'textTitle' => 'Coupon', 'textMessage' => 'tr_meliscommerce_coupon_save_success',
                'typeCode' => 'ECOM_COUPON_ASSIGN', 'itemId' => $id,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /coupons/:id/products/:cprodId ─────────────────────────────────
    public function productDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id      = (int) $this->params()->fromRoute('id', 0);
        $cprodId = (int) $this->params()->fromRoute('cprodId', 0);
        if ($cprodId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid'], 400); }
        try {
            $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface')
                ->query('DELETE FROM melis_ecom_coupon_product WHERE cprod_id = ?', [$cprodId]);

            $this->getEventManager()->trigger('meliscommerce_coupon_client_management_end', $this, [
                'success' => true, 'textTitle' => 'Coupon', 'textMessage' => 'tr_meliscommerce_coupon_save_success',
                'typeCode' => 'ECOM_COUPON_ASSIGN', 'itemId' => $id,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /coupons/:id/orders ────────────────────────────────────────────────
    public function ordersAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid coupon'], 400); }
        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();

            $search    = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawStatus = $this->params()->fromQuery('status', '');
            $status    = ($rawStatus !== '' && $rawStatus !== null) ? (int) $rawStatus : null;
            $dateStart = trim((string) ($this->params()->fromQuery('dateStart', '') ?? ''));
            $dateEnd   = trim((string) ($this->params()->fromQuery('dateEnd', '') ?? ''));

            $where = ['cord.cord_coupon_id = ?']; $params = [$id];
            if ($status !== null)  { $where[] = 'o.ord_status = ?';               $params[] = $status; }
            if ($dateStart !== '') { $where[] = 'DATE(o.ord_date_creation) >= ?'; $params[] = $dateStart; }
            if ($dateEnd !== '')   { $where[] = 'DATE(o.ord_date_creation) <= ?'; $params[] = $dateEnd; }
            if ($search !== '') {
                $like = '%' . $search . '%';
                $where[] = '(o.ord_reference LIKE ? OR p.cper_firstname LIKE ? OR p.cper_name LIKE ?)';
                $params = array_merge($params, [$like, $like, $like]);
            }
            $wc = 'WHERE ' . implode(' AND ', $where);

            $items = [];
            foreach ($db->query("SELECT cord.cord_id, cord.cord_order_id, cord.cord_quantity_used,
                    o.ord_reference, o.ord_status AS order_status, o.ord_date_creation,
                    p.cper_firstname, p.cper_name, civ.civt_min_name AS civility_name,
                    ostt.ostt_status_name AS status_name, osta.osta_color_code AS status_color,
                    (SELECT COALESCE(SUM(obas_quantity), 0) FROM melis_ecom_order_basket WHERE obas_order_id = o.ord_id) AS product_count,
                    (SELECT COALESCE(SUM(opay_price_total), 0) FROM melis_ecom_order_payment WHERE opay_order_id = o.ord_id) AS total_amount
                FROM melis_ecom_coupon_order cord
                LEFT JOIN melis_ecom_order o ON o.ord_id = cord.cord_order_id
                LEFT JOIN melis_ecom_client_person p ON p.cper_id = o.ord_client_person_id
                LEFT JOIN melis_ecom_civility_trans civ ON civ.civt_civ_id = p.cper_civility AND civ.civt_lang_id = ?
                LEFT JOIN melis_ecom_order_status_trans ostt ON ostt.ostt_status_id = o.ord_status AND ostt.ostt_lang_id = ?
                LEFT JOIN melis_ecom_order_status osta ON osta.osta_id = o.ord_status
                $wc
                ORDER BY cord.cord_id DESC", array_merge([$lang, $lang], $params)) as $r) {
                $r = (array) $r;
                $items[] = [
                    'id' => (int) $r['cord_id'], 'orderId' => (int) $r['cord_order_id'],
                    'orderReference' => (string) ($r['ord_reference'] ?? ('#' . $r['cord_order_id'])),
                    'status' => $r['order_status'] !== null ? (int) $r['order_status'] : null,
                    'statusName' => (string) ($r['status_name'] ?? ''),
                    'statusColor' => (string) ($r['status_color'] ?? '#6b7280'),
                    'civility' => (string) ($r['civility_name'] ?? ''),
                    'firstname' => (string) ($r['cper_firstname'] ?? ''),
                    'lastname' => (string) ($r['cper_name'] ?? ''),
                    'productCount' => (int) ($r['product_count'] ?? 0),
                    'price' => (float) ($r['total_amount'] ?? 0),
                    'quantityUsed' => (int) ($r['cord_quantity_used'] ?? 0),
                    'dateCreation' => $r['ord_date_creation'] ?? null,
                ];
            }

            $statuses = [];
            foreach ($db->query("SELECT osta.osta_id, osta.osta_color_code, ostt.ostt_status_name
                FROM melis_ecom_order_status osta
                LEFT JOIN melis_ecom_order_status_trans ostt ON ostt.ostt_status_id = osta.osta_id AND ostt.ostt_lang_id = ?
                WHERE osta.osta_status = 1 ORDER BY osta.osta_id ASC", [$lang]) as $r) {
                $r = (array) $r;
                $statuses[] = ['id' => (int) $r['osta_id'], 'name' => (string) ($r['ostt_status_name'] ?? ''), 'color' => (string) ($r['osta_color_code'] ?? '#6b7280')];
            }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'statuses' => $statuses]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    private function isAuthenticated(): bool { return $this->getServiceManager()->get('MelisCoreAuth')->hasIdentity(); }

    private function denyUnlessAccess(): ?HttpResponse
    {
        if (!$this->isAuthenticated()) { return $this->jsonResponse(['success' => false, 'error' => 'Unauthenticated'], 401); }
        try { if (!$this->getServiceManager()->get('MelisCoreRights')->canAccess(self::MELIS_KEY)) { return $this->jsonResponse(['success' => false, 'error' => 'Forbidden'], 403); } } catch (\Throwable) {}
        return null;
    }

    private function jsonResponse(array $data, int $status = 200): HttpResponse
    {
        /** @var HttpResponse $response */
        $response = $this->getResponse();
        $response->setStatusCode($status);
        $response->getHeaders()->addHeaders(['Content-Type' => 'application/json; charset=utf-8', 'X-Content-Type-Options' => 'nosniff']);
        $response->setContent(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return $response;
    }

    private function errorResponse(\Throwable $e, int $status = 500): HttpResponse
    {
        return $this->jsonResponse(['success' => false, 'error' => $e->getMessage(), 'file' => basename($e->getFile()) . ':' . $e->getLine()], $status);
    }
}
