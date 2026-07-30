<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour l'outil Statuts de commande MelisCommerce.
 *
 * Un statut (melis_ecom_order_status) porte un ID (id fixes 1-6 et -1 = "prime"/permanent,
 * indispensables au workflow de commande — NON supprimables), une couleur (osta_color_code)
 * et un statut actif/inactif. Le LIBELLÉ est traduit par langue (melis_ecom_order_status_trans).
 *
 * Routes :
 *   GET    /melis/react-api/order-statuses                → liste paginée + filtres
 *   GET    /melis/react-api/order-statuses/stats            → KPI
 *   GET    /melis/react-api/order-statuses/options          → langues (pour l'onglet traductions)
 *   GET    /melis/react-api/order-statuses/:id              → détail + traductions (toutes langues)
 *   POST   /melis/react-api/order-statuses/save              → créer / mettre à jour
 *   DELETE /melis/react-api/order-statuses/delete/:id        → supprimer (bloqué pour les IDs permanents)
 *
 * Logique métier inchangée pour les écritures : réutilise MelisComOrderService
 * (saveOrderStatus/deleteOrderStatusById) — voir le gotcha ob_start()/ob_end_clean().
 */
class MelisComReactApiOrderStatusController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_order_status_tool_page';

    /** IDs indispensables au workflow de commande — jamais supprimables (cf. config
     *  meliscommerce/datas/default/permanent_order_status dans app.interface.orders.php). */
    private const PERMANENT_IDS = [1, 2, 3, 4, 5, 6, -1];

    private function langId(): int
    {
        $c = new \Laminas\Session\Container('meliscore');
        $id = (int) ($c['melis-lang-id'] ?? 0);
        return $id > 0 ? $id : 1;
    }

    // ─── GET /order-statuses ─────────────────────────────────────────────────
    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $limit  = min(9999, max(1, (int) $this->params()->fromQuery('limit', 25)));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawSt  = $this->params()->fromQuery('status', '');
            $status = ($rawSt !== '' && $rawSt !== null) ? (int) $rawSt : null;
            $lang   = (int) $this->langId();

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $filterWhere = []; $filterParams = [];
            if ($status !== null) { $filterWhere[] = 'a.osta_status = ?'; $filterParams[] = $status; }
            if ($search !== '') {
                $like = '%' . $search . '%';
                $filterWhere[] = '(a.osta_color_code LIKE ? OR EXISTS (SELECT 1 FROM melis_ecom_order_status_trans t2 WHERE t2.ostt_status_id = a.osta_id AND t2.ostt_status_name LIKE ?))';
                $filterParams[] = $like; $filterParams[] = $like;
            }

            // Nom traduit ($lang inliné). Tri server-side ; « color » non triable.
            $nameExpr = "COALESCE("
                      . "(SELECT ostt_status_name FROM melis_ecom_order_status_trans WHERE ostt_status_id = a.osta_id AND ostt_lang_id = $lang LIMIT 1),"
                      . "(SELECT ostt_status_name FROM melis_ecom_order_status_trans WHERE ostt_status_id = a.osta_id ORDER BY ostt_id LIMIT 1),'')";
            $sortMap = [
                'id'     => 'a.osta_id',
                'name'   => $nameExpr,
                'status' => 'a.osta_status',
            ];
            $sortKey  = (string) $this->params()->fromQuery('sort', 'id');
            if (!isset($sortMap[$sortKey])) { $sortKey = 'id'; }
            $sortExpr = $sortMap[$sortKey];
            $dir = strtolower((string) $this->params()->fromQuery('dir', 'desc')) === 'asc' ? 'ASC' : 'DESC';
            $op  = $dir === 'ASC' ? '>' : '<';

            $countWhere = $filterWhere ? 'WHERE ' . implode(' AND ', $filterWhere) : '';
            $total = (int) ((array) iterator_to_array($db->query("SELECT COUNT(*) AS t FROM melis_ecom_order_status a $countWhere", $filterParams))[0])['t'];

            $dataWhere = $filterWhere; $dataParams = $filterParams;
            $after = (string) ($this->params()->fromQuery('after', '') ?? '');
            if ($after !== '') {
                $cur = json_decode((string) base64_decode($after, true), true);
                if (is_array($cur) && array_key_exists('v', $cur) && array_key_exists('id', $cur)) {
                    $dataWhere[]  = "($sortExpr $op ? OR ($sortExpr = ? AND a.osta_id $op ?))";
                    $dataParams[] = $cur['v']; $dataParams[] = $cur['v']; $dataParams[] = (int) $cur['id'];
                }
            }
            $dataWhereClause = $dataWhere ? 'WHERE ' . implode(' AND ', $dataWhere) : '';

            $rows = iterator_to_array($db->query(
                "SELECT a.osta_id, a.osta_color_code, a.osta_status, $nameExpr AS name, $sortExpr AS __sortval
                 FROM melis_ecom_order_status a
                 $dataWhereClause
                 ORDER BY $sortExpr $dir, a.osta_id $dir
                 LIMIT ?",
                array_merge($dataParams, [$limit])
            ));

            $items = []; $lastSortVal = null; $lastId = null;
            foreach ($rows as $r) {
                $r           = (array) $r;
                $lastSortVal = $r['__sortval'] ?? null;
                $lastId      = (int) ($r['osta_id'] ?? 0);
                $items[] = [
                    'id' => (int) $r['osta_id'],
                    'name' => (string) ($r['name'] ?? ''),
                    'colorCode' => (string) ($r['osta_color_code'] ?? ''),
                    'status' => (int) $r['osta_status'],
                    'isPermanent' => in_array((int) $r['osta_id'], self::PERMANENT_IDS, true),
                ];
            }
            $nextCursor = null;
            if (count($rows) === $limit && $lastId !== null) {
                $nextCursor = base64_encode((string) json_encode(['v' => $lastSortVal, 'id' => $lastId]));
            }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'total' => $total, 'nextCursor' => $nextCursor, 'limit' => $limit]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /order-statuses/stats ───────────────────────────────────────────
    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = (array) iterator_to_array($db->query(
                'SELECT COUNT(*) AS total, SUM(osta_status=1) AS active, SUM(osta_status=0) AS inactive FROM melis_ecom_order_status', []
            ))[0];
            return $this->jsonResponse(['success' => true, 'data' => [
                'total' => (int) ($row['total'] ?? 0), 'active' => (int) ($row['active'] ?? 0), 'inactive' => (int) ($row['inactive'] ?? 0),
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /order-statuses/options ─────────────────────────────────────────
    public function optionsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $languages = [];
            foreach ($db->query('SELECT elang_id, elang_name, elang_flag FROM melis_ecom_lang WHERE elang_status = 1 ORDER BY elang_id', []) as $r) {
                $r = (array) $r;
                $languages[] = ['id' => (int) $r['elang_id'], 'name' => (string) $r['elang_name'], 'flag' => (string) ($r['elang_flag'] ?? '')];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['languages' => $languages]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /order-statuses/:id ──────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT * FROM melis_ecom_order_status WHERE osta_id = ? LIMIT 1', [$id]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Order status not found'], 404); }
            $a = (array) $rows[0];

            $translations = [];
            foreach ($db->query('SELECT ostt_lang_id, ostt_status_name FROM melis_ecom_order_status_trans WHERE ostt_status_id = ?', [$id]) as $r) {
                $r = (array) $r;
                $translations[] = ['langId' => (int) $r['ostt_lang_id'], 'name' => (string) ($r['ostt_status_name'] ?? '')];
            }

            return $this->jsonResponse(['success' => true, 'data' => [
                'id' => (int) $a['osta_id'],
                'colorCode' => (string) ($a['osta_color_code'] ?? ''),
                'status' => (int) $a['osta_status'],
                'isPermanent' => in_array($id, self::PERMANENT_IDS, true),
                'translations' => $translations,
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /order-statuses/save ────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b  = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $id = (int) ($b['id'] ?? 0);

            $colorCode = trim((string) ($b['colorCode'] ?? ''));
            if ($colorCode === '') { return $this->jsonResponse(['success' => false, 'error' => 'La couleur est obligatoire.'], 400); }

            $rawTranslations = is_array($b['translations'] ?? null) ? $b['translations'] : [];
            $hasName = false;
            foreach ($rawTranslations as $t) { if (trim((string) ($t['name'] ?? '')) !== '') { $hasName = true; break; } }
            if (!$hasName) { return $this->jsonResponse(['success' => false, 'error' => 'Le nom est obligatoire pour au moins une langue.'], 400); }

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $existingByLang = [];
            if ($id !== 0) {
                foreach ($db->query('SELECT ostt_id, ostt_lang_id FROM melis_ecom_order_status_trans WHERE ostt_status_id = ?', [$id]) as $r) {
                    $r = (array) $r;
                    $existingByLang[(int) $r['ostt_lang_id']] = (int) $r['ostt_id'];
                }
            }

            $transArray = [];
            foreach ($rawTranslations as $t) {
                $name = trim((string) ($t['name'] ?? ''));
                if ($name === '') { continue; }
                $langId = (int) ($t['langId'] ?? 0);
                if ($langId <= 0) { continue; }
                $row = ['ostt_lang_id' => $langId, 'ostt_status_name' => $name];
                if (isset($existingByLang[$langId])) { $row['ostt_id'] = $existingByLang[$langId]; }
                $transArray[] = $row;
            }

            $orderStatus = [
                'osta_color_code' => $colorCode,
                'osta_status' => (int) (!empty($b['status']) ? 1 : 0),
            ];

            ob_start();
            $newId = $this->getServiceManager()->get('MelisComOrderService')->saveOrderStatus($orderStatus, $transArray, $id ?: null);
            ob_end_clean();

            $this->getEventManager()->trigger('meliscommerce_order_status_type_save_end', $this, [
                'success' => true, 'textTitle' => 'Order status', 'textMessage' => 'tr_meliscommerce_order_status_save_success',
                'typeCode' => $id ? 'ECOM_ORDER_STATUS_TYPE_EDIT' : 'ECOM_ORDER_STATUS_TYPE_ADD', 'itemId' => (int) $newId,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], $id ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /order-statuses/delete/:id ────────────────────────────────────
    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        if (in_array($id, self::PERMANENT_IDS, true)) {
            return $this->jsonResponse(['success' => false, 'error' => 'Ce statut est indispensable à la plateforme et ne peut pas être supprimé.'], 403);
        }
        try {
            ob_start();
            $this->getServiceManager()->get('MelisComOrderService')->deleteOrderStatusById($id);
            ob_end_clean();

            $this->getEventManager()->trigger('meliscommerce_order_status_delete_end', $this, [
                'success' => true, 'textTitle' => 'Order status', 'textMessage' => 'tr_meliscommerce_order_status_delete_success',
                'typeCode' => 'ECOM_ORDER_STATUS_DELETE', 'itemId' => $id,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => null]);
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
