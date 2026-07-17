<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour l'outil Currencies (Devises) MelisCommerce.
 *
 * Une devise (melis_ecom_currency) : nom, code (unique, ex. "EUR"), symbole, statut
 * actif/inactif, et un flag `cur_default` — EXACTEMENT une devise a `cur_default = 1` à
 * tout instant (la devise par défaut de la plateforme, utilisée par les sites/pays selon
 * leurs besoins). Elle ne peut pas être supprimée tant qu'elle est la devise par défaut ;
 * en revanche rien n'empêche de la modifier ou de la désactiver (comme en legacy).
 *
 * Routes :
 *   GET    /melis/react-api/currencies                     → liste paginée + filtres
 *   GET    /melis/react-api/currencies/stats                → KPI
 *   GET    /melis/react-api/currencies/:id                  → détail
 *   POST   /melis/react-api/currencies/save                 → créer / mettre à jour
 *   DELETE /melis/react-api/currencies/delete/:id            → supprimer (refusé pour la devise par défaut)
 *   POST   /melis/react-api/currencies/:id/set-default       → définir comme devise par défaut
 *
 * Logique métier inchangée pour les écritures : réutilise les méthodes GÉNÉRIQUES de
 * MelisEcomCurrencyTable (save()/deleteById(), héritées de MelisGenericTable) — même
 * pattern que l'ancien contrôleur legacy MelisComCurrencyController (saveAction/
 * deleteAction/setDefaultCurrencyAction). Lectures en SQL brut (cohérent avec les autres
 * contrôleurs ReactApi de ce module).
 */
class MelisComReactApiCurrencyController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_currency_conf';

    private function formatCurrency(array $r): array
    {
        return [
            'id' => (int) $r['cur_id'],
            'name' => (string) ($r['cur_name'] ?? ''),
            'code' => (string) ($r['cur_code'] ?? ''),
            'symbol' => (string) ($r['cur_symbol'] ?? ''),
            'status' => (int) ($r['cur_status'] ?? 0),
            'isDefault' => (int) ($r['cur_default'] ?? 0) === 1,
        ];
    }

    // ─── GET /currencies ────────────────────────────────────────────────────────
    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $limit  = min(9999, max(1, (int) $this->params()->fromQuery('limit', 50)));
            $page   = max(1, (int) $this->params()->fromQuery('page', 1));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawSt  = $this->params()->fromQuery('status', '');
            $status = ($rawSt !== '' && $rawSt !== null) ? (int) $rawSt : null;

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $where = []; $params = [];
            if ($status !== null) { $where[] = 'cur_status = ?'; $params[] = $status; }
            if ($search !== '') {
                $like = '%' . $search . '%';
                $where[] = '(cur_id LIKE ? OR cur_name LIKE ? OR cur_code LIKE ? OR cur_symbol LIKE ?)';
                $params = array_merge($params, [$like, $like, $like, $like]);
            }
            $wc = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $total = (int) ((array) iterator_to_array($db->query("SELECT COUNT(*) AS t FROM melis_ecom_currency $wc", $params))[0])['t'];

            $offset = ($page - 1) * $limit;
            $rows = $db->query(
                "SELECT * FROM melis_ecom_currency $wc ORDER BY cur_id ASC LIMIT ? OFFSET ?",
                array_merge($params, [$limit, $offset])
            );

            $items = [];
            foreach ($rows as $r) { $items[] = $this->formatCurrency((array) $r); }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'total' => $total, 'page' => $page, 'limit' => $limit]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /currencies/stats ──────────────────────────────────────────────────
    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = (array) iterator_to_array($db->query(
                'SELECT COUNT(*) AS total, SUM(cur_status=1) AS active, SUM(cur_status=0) AS inactive FROM melis_ecom_currency', []
            ))[0];
            return $this->jsonResponse(['success' => true, 'data' => [
                'total' => (int) ($row['total'] ?? 0), 'active' => (int) ($row['active'] ?? 0), 'inactive' => (int) ($row['inactive'] ?? 0),
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /currencies/:id ─────────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT * FROM melis_ecom_currency WHERE cur_id = ? LIMIT 1', [$id]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Currency not found'], 404); }
            return $this->jsonResponse(['success' => true, 'data' => $this->formatCurrency((array) $rows[0])]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /currencies/save ───────────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b  = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $id = (int) ($b['id'] ?? 0);

            $name   = trim((string) ($b['name'] ?? ''));
            $code   = strtoupper(trim((string) ($b['code'] ?? '')));
            $symbol = trim((string) ($b['symbol'] ?? ''));

            if ($name === '')   { return $this->jsonResponse(['success' => false, 'error' => 'Veuillez saisir le nom de la devise.'], 400); }
            if ($code === '')   { return $this->jsonResponse(['success' => false, 'error' => 'Veuillez saisir le code de la devise.'], 400); }
            if ($symbol === '') { return $this->jsonResponse(['success' => false, 'error' => 'Veuillez saisir le symbole de la devise.'], 400); }

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            if ($id > 0) {
                $existing = iterator_to_array($db->query('SELECT cur_id FROM melis_ecom_currency WHERE cur_id = ? LIMIT 1', [$id]));
                if (empty($existing)) { return $this->jsonResponse(['success' => false, 'error' => 'Currency not found'], 404); }
            }

            // Dedup cur_code : refuser à la création si le code existe déjà ; en mise à jour,
            // refuser uniquement si le code cible appartient à une AUTRE ligne (garder le même
            // code sur la même ligne est autorisé).
            $dup = iterator_to_array($db->query('SELECT cur_id FROM melis_ecom_currency WHERE cur_code = ? LIMIT 1', [$code]));
            if (!empty($dup)) {
                $dupId = (int) ((array) $dup[0])['cur_id'];
                if ($id <= 0 || $dupId !== $id) {
                    return $this->jsonResponse(['success' => false, 'error' => 'Ce code de devise existe déjà, veuillez en choisir un autre.'], 400);
                }
            }

            $data = [
                'cur_name' => $name,
                'cur_code' => $code,
                'cur_symbol' => $symbol,
                'cur_status' => (int) (!empty($b['status']) ? 1 : 0),
            ];

            $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
            $newId = $currencyTable->save($data, $id ?: null);

            $this->getEventManager()->trigger('meliscommerce_currency_save_end', $this, [
                'success' => true, 'textTitle' => 'Devise',
                'textMessage' => $id ? 'tr_meliscommerce_currency_form_edit_success' : 'tr_meliscommerce_currency_form_add_success',
                'typeCode' => $id ? 'ECOM_CURRENCY_UPDATE' : 'ECOM_CURRENCY_ADD', 'itemId' => (int) $newId,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], $id ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /currencies/delete/:id ───────────────────────────────────────────
    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT cur_default FROM melis_ecom_currency WHERE cur_id = ? LIMIT 1', [$id]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Currency not found'], 404); }
            if ((int) ((array) $rows[0])['cur_default'] === 1) {
                return $this->jsonResponse(['success' => false, 'error' => 'La devise par défaut ne peut pas être supprimée.'], 400);
            }

            $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
            $currencyTable->deleteById($id);

            $this->getEventManager()->trigger('meliscommerce_currency_delete_end', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_currency_delete_currency',
                'textMessage' => 'tr_meliscommerce_currency_delete_successful',
                'typeCode' => 'ECOM_CURRENCY_DELETE', 'itemId' => $id,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /currencies/:id/set-default ────────────────────────────────────────
    public function setDefaultAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $target = iterator_to_array($db->query('SELECT cur_id, cur_default FROM melis_ecom_currency WHERE cur_id = ? LIMIT 1', [$id]));
            if (empty($target)) { return $this->jsonResponse(['success' => false, 'error' => 'Currency not found'], 404); }

            $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');

            // Déjà la devise par défaut : idempotent, rien à faire.
            if ((int) ((array) $target[0])['cur_default'] === 1) {
                return $this->jsonResponse(['success' => true, 'data' => null]);
            }

            $current = $currencyTable->getDefaultCurrency()->current();
            if ($current) { $currencyTable->save(['cur_default' => 0], (int) $current->cur_id); }
            $currencyTable->save(['cur_default' => 1], $id);

            $this->getEventManager()->trigger('meliscommerce_currency_set_default_end', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_currency_set_default',
                'textMessage' => 'tr_meliscommerce_currency_set_default_success',
                'typeCode' => 'ECOM_CURRENCY_SET_DEFAULT', 'itemId' => $id,
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
