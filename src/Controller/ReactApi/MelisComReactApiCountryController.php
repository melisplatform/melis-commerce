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
            $page   = max(1, (int) $this->params()->fromQuery('page', 1));
            $limit  = min(9999, max(1, (int) $this->params()->fromQuery('limit', 50)));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawSt  = $this->params()->fromQuery('status', '');
            $status = ($rawSt !== '' && $rawSt !== null) ? (int) $rawSt : null;

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $join = 'FROM melis_ecom_country c LEFT JOIN melis_ecom_currency cu ON cu.cur_id = c.ctry_currency_id';

            $where = []; $params = [];
            if ($status !== null) { $where[] = 'c.ctry_status = ?'; $params[] = $status; }
            if ($search !== '') {
                $like = '%' . $search . '%';
                $where[] = '(c.ctry_id LIKE ? OR c.ctry_name LIKE ? OR cu.cur_name LIKE ? OR cu.cur_symbol LIKE ?)';
                $params = array_merge($params, [$like, $like, $like, $like]);
            }
            $wc = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $total = (int) ((array) iterator_to_array($db->query("SELECT COUNT(*) AS t $join $wc", $params))[0])['t'];

            $offset = ($page - 1) * $limit;
            $rows = $db->query(
                "SELECT c.*, cu.cur_name, cu.cur_symbol $join $wc ORDER BY c.ctry_id DESC LIMIT ? OFFSET ?",
                array_merge($params, [$limit, $offset])
            );

            $items = [];
            foreach ($rows as $r) { $items[] = $this->formatCountry((array) $r); }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'total' => $total, 'page' => $page, 'limit' => $limit]]);
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
