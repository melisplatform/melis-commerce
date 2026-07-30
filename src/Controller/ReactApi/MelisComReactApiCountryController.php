<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour l'outil Countries (Pays) MelisCommerce.
 *
 * Un pays = ctry_name + une devise (ctry_currency_id, FK par convention vers
 * melis_ecom_currency, pas de contrainte réelle) + statut + drapeau (ctry_flag).
 * Pas de notion de zone/région. Pas d'onglets : formulaire simple à une section.
 *
 * Routes :
 *   GET    /melis/react-api/countries                → liste paginée + filtres
 *   GET    /melis/react-api/countries/stats           → KPI
 *   GET    /melis/react-api/countries/options         → toutes les devises (id/name/symbol)
 *   GET    /melis/react-api/countries/:id              → détail
 *   POST   /melis/react-api/countries/save             → créer / mettre à jour
 *   DELETE /melis/react-api/countries/delete/:id        → supprimer (pas de garde-fou, idem legacy)
 *
 * Écriture via le service générique MelisEcomCountryTable::save()/delete() (comme le
 * contrôleur legacy MelisComCountryController), lecture en SQL brut (join melis_ecom_currency).
 *
 * ctry_flag : legacy stocke directement le contenu du fichier en base64 (base64_encode() à
 * l'upload, jamais redécodé, voir MelisComCountryController::saveAction() et le rendu .phtml
 * `data:image/jpeg;base64,{ctry_flag}`) — on reproduit exactement ce comportement : le champ
 * `flag` reçu (déjà en base64 côté client, préfixe data: retiré) est stocké TEL QUEL, sans
 * base64_decode().
 */
class MelisComReactApiCountryController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_country_list_container';

    private function formatCountry(array $r): array
    {
        return [
            'id' => (int) $r['ctry_id'],
            'name' => (string) ($r['ctry_name'] ?? ''),
            'status' => (int) ($r['ctry_status'] ?? 0),
            'currencyId' => (int) ($r['ctry_currency_id'] ?? 0),
            'currencyName' => (string) ($r['cur_name'] ?? ''),
            'currencySymbol' => (string) ($r['cur_symbol'] ?? ''),
            'flag' => !empty($r['ctry_flag']) ? (string) $r['ctry_flag'] : null,
        ];
    }

    // ─── GET /countries ─────────────────────────────────────────────────────────
    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $limit  = min(9999, max(1, (int) $this->params()->fromQuery('limit', 25)));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawSt  = $this->params()->fromQuery('status', '');
            $status = ($rawSt !== '' && $rawSt !== null) ? (int) $rawSt : null;

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $join = 'FROM melis_ecom_country c LEFT JOIN melis_ecom_currency cu ON cu.cur_id = c.ctry_currency_id';

            $filterWhere = []; $filterParams = [];
            if ($status !== null) { $filterWhere[] = 'c.ctry_status = ?'; $filterParams[] = $status; }
            if ($search !== '') {
                $like = '%' . $search . '%';
                $filterWhere[] = '(c.ctry_id LIKE ? OR c.ctry_name LIKE ? OR cu.cur_name LIKE ? OR cu.cur_symbol LIKE ?)';
                $filterParams = array_merge($filterParams, [$like, $like, $like, $like]);
            }

            // Tri server-side (whitelist non-null). « flag » non triable.
            $sortMap = [
                'id'       => 'c.ctry_id',
                'status'   => 'c.ctry_status',
                'name'     => "COALESCE(c.ctry_name,'')",
                'currency' => "COALESCE(cu.cur_name,'')",
            ];
            $sortKey  = (string) $this->params()->fromQuery('sort', 'id');
            if (!isset($sortMap[$sortKey])) { $sortKey = 'id'; }
            $sortExpr = $sortMap[$sortKey];
            $dir = strtolower((string) $this->params()->fromQuery('dir', 'desc')) === 'asc' ? 'ASC' : 'DESC';
            $op  = $dir === 'ASC' ? '>' : '<';

            $countWhere = $filterWhere ? 'WHERE ' . implode(' AND ', $filterWhere) : '';
            $total = (int) ((array) iterator_to_array($db->query("SELECT COUNT(*) AS t $join $countWhere", $filterParams))[0])['t'];

            $dataWhere = $filterWhere; $dataParams = $filterParams;
            $after = (string) ($this->params()->fromQuery('after', '') ?? '');
            if ($after !== '') {
                $cur = json_decode((string) base64_decode($after, true), true);
                if (is_array($cur) && array_key_exists('v', $cur) && array_key_exists('id', $cur)) {
                    $dataWhere[]  = "($sortExpr $op ? OR ($sortExpr = ? AND c.ctry_id $op ?))";
                    $dataParams[] = $cur['v']; $dataParams[] = $cur['v']; $dataParams[] = (int) $cur['id'];
                }
            }
            $dataWhereClause = $dataWhere ? 'WHERE ' . implode(' AND ', $dataWhere) : '';

            $rows = iterator_to_array($db->query(
                "SELECT c.*, cu.cur_name, cu.cur_symbol, $sortExpr AS __sortval $join $dataWhereClause ORDER BY $sortExpr $dir, c.ctry_id $dir LIMIT ?",
                array_merge($dataParams, [$limit])
            ));

            $items = []; $lastSortVal = null; $lastId = null;
            foreach ($rows as $r) {
                $a           = (array) $r;
                $lastSortVal = $a['__sortval'] ?? null;
                $lastId      = (int) ($a['ctry_id'] ?? 0);
                $items[]     = $this->formatCountry($a);
            }
            $nextCursor = null;
            if (count($rows) === $limit && $lastId !== null) {
                $nextCursor = base64_encode((string) json_encode(['v' => $lastSortVal, 'id' => $lastId]));
            }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'total' => $total, 'nextCursor' => $nextCursor, 'limit' => $limit]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /countries/stats ───────────────────────────────────────────────────
    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = (array) iterator_to_array($db->query(
                'SELECT COUNT(*) AS total, SUM(ctry_status=1) AS active, SUM(ctry_status=0) AS inactive FROM melis_ecom_country', []
            ))[0];
            return $this->jsonResponse(['success' => true, 'data' => [
                'total' => (int) ($row['total'] ?? 0),
                'active' => (int) ($row['active'] ?? 0),
                'inactive' => (int) ($row['inactive'] ?? 0),
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /countries/options ──────────────────────────────────────────────────
    // Répertoire complet des devises (TOUS statuts confondus — legacy EcomCurrencyAllStatusSelect).
    public function optionsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $currencies = [];
            foreach ($db->query('SELECT cur_id, cur_name, cur_symbol FROM melis_ecom_currency ORDER BY cur_name ASC', []) as $r) {
                $r = (array) $r;
                $currencies[] = ['id' => (int) $r['cur_id'], 'name' => (string) ($r['cur_name'] ?? ''), 'symbol' => (string) ($r['cur_symbol'] ?? '')];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['currencies' => $currencies]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /countries/:id ──────────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query(
                'SELECT c.*, cu.cur_name, cu.cur_symbol FROM melis_ecom_country c
                 LEFT JOIN melis_ecom_currency cu ON cu.cur_id = c.ctry_currency_id
                 WHERE c.ctry_id = ? LIMIT 1',
                [$id]
            ));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Country not found'], 404); }
            return $this->jsonResponse(['success' => true, 'data' => $this->formatCountry((array) $rows[0])]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /countries/save ────────────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b  = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $id = (int) ($b['id'] ?? 0);

            $name = trim((string) ($b['name'] ?? ''));
            if ($name === '') {
                return $this->jsonResponse(['success' => false, 'error' => 'Please enter the name of the country'], 400);
            }
            $name = mb_substr($name, 0, 45);

            $currencyId = (int) ($b['currencyId'] ?? 0);

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            // Dédoublonnage exact-match, insensible à l'id courant (legacy : ctry_name unique) —
            // à la création : refuser si CE nom existe déjà ; en édition : refuser seulement si le
            // NOUVEAU nom appartient à une AUTRE ligne (garder le même nom sur la même ligne est OK).
            $dupParams = $id > 0 ? [$name, $id] : [$name];
            $dupSql = 'SELECT ctry_id FROM melis_ecom_country WHERE ctry_name = ?' . ($id > 0 ? ' AND ctry_id <> ?' : '') . ' LIMIT 1';
            $dup = iterator_to_array($db->query($dupSql, $dupParams));
            if (!empty($dup)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Country name already exists'], 409);
            }

            $data = [
                'ctry_name' => $name,
                'ctry_currency_id' => $currencyId,
                'ctry_status' => (int) (!empty($b['status']) ? 1 : 0),
            ];

            // Drapeau : reproduit exactement le stockage legacy (base64_encode() du fichier à
            // l'upload, jamais redécodé — voir MelisComCountryController::saveAction()). Le champ
            // `flag` reçu est déjà en base64 côté client (data: prefix retiré) → stocké tel quel.
            // Absent/null = on NE touche PAS à ctry_flag (ne pas écraser un drapeau existant).
            if (array_key_exists('flag', $b) && $b['flag'] !== null && $b['flag'] !== '') {
                $data['ctry_flag'] = (string) $b['flag'];
            }

            $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
            ob_start();
            $newId = $countryTable->save($data, $id ?: null);
            ob_end_clean();

            $this->getEventManager()->trigger('meliscommerce_country_save_end', $this, [
                'success' => true, 'textTitle' => 'Country',
                'textMessage' => $id ? 'tr_meliscommerce_country_edit_success' : 'tr_meliscommerce_country_add_success',
                'typeCode' => $id ? 'ECOM_COUNTRY_UPDATE' : 'ECOM_COUNTRY_ADD', 'itemId' => (int) $newId,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], $id ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /countries/delete/:id ────────────────────────────────────────────
    // Pas de garde-fou (idem legacy : suppression silencieuse même si d'autres tables
    // référencent ctry_id sans contrainte FK réelle).
    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
            ob_start();
            $countryTable->delete($id);
            ob_end_clean();

            $this->getEventManager()->trigger('meliscommerce_country_delete_end', $this, [
                'success' => true, 'textTitle' => 'Country', 'textMessage' => 'tr_meliscommerce_country_delete_success',
                'typeCode' => 'ECOM_COUNTRY_DELETE', 'itemId' => $id,
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
