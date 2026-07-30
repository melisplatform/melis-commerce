<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour l'outil "Commerce languages" (registre des langues MelisCommerce,
 * `melis_ecom_lang`) — DISTINCT des langues d'interface du back-office (melis_core_lang)
 * et de `/melis/react-api/langs` (MelisCore). Ici il s'agit des langues utilisées côté
 * catalogue/traductions e-commerce (attributs, catégories, produits, ...).
 *
 * Routes :
 *   GET    /melis/react-api/languages           → liste paginée + filtres (search, status)
 *   GET    /melis/react-api/languages/stats      → KPI (total/active/inactive)
 *   GET    /melis/react-api/languages/:id        → détail
 *   POST   /melis/react-api/languages/save       → créer / mettre à jour
 *   DELETE /melis/react-api/languages/delete/:id  → supprimer (aucune garde de cascade,
 *                                                    comme en legacy : d'autres tables
 *                                                    référencent *_lang_id par convention)
 *
 * Règle de dédoublonnage (reprise telle quelle du legacy MelisComLanguageController::saveAction) :
 * `elang_locale` doit être unique. À la création, on rejette si la locale existe déjà.
 * En modification, on rejette seulement si la NOUVELLE locale correspond à une AUTRE ligne
 * (renommer/garder la même locale sur la même ligne est autorisé).
 *
 * Écriture réutilise le service de table existant `MelisEcomLangTable` (save()/deleteById()),
 * qui porte déjà cette logique en legacy — lecture en SQL brut comme les autres contrôleurs
 * React API (Coupon, Attribute).
 */
class MelisComReactApiLanguageController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_language_list_container';

    private function formatLanguage(array $r): array
    {
        return [
            'id' => (int) $r['elang_id'],
            'locale' => (string) ($r['elang_locale'] ?? ''),
            'name' => (string) ($r['elang_name'] ?? ''),
            'status' => (int) ($r['elang_status'] ?? 0),
            'flag' => !empty($r['elang_flag']) ? (string) $r['elang_flag'] : null,
        ];
    }

    // ─── GET /languages ────────────────────────────────────────────────────────
    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $limit  = min(9999, max(1, (int) $this->params()->fromQuery('limit', 25)));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawSt  = $this->params()->fromQuery('status', '');
            $status = ($rawSt !== '' && $rawSt !== null) ? (int) $rawSt : null;

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $filterWhere = []; $filterParams = [];
            if ($status !== null) { $filterWhere[] = 'elang_status = ?'; $filterParams[] = $status; }
            if ($search !== '') {
                $like = '%' . $search . '%';
                $filterWhere[] = '(elang_id LIKE ? OR elang_locale LIKE ? OR elang_name LIKE ?)';
                $filterParams[] = $like; $filterParams[] = $like; $filterParams[] = $like;
            }

            // Tri server-side (whitelist non-null). « flag » non triable.
            $sortMap = [
                'id'     => 'elang_id',
                'status' => 'elang_status',
                'locale' => "COALESCE(elang_locale,'')",
                'name'   => "COALESCE(elang_name,'')",
            ];
            $sortKey  = (string) $this->params()->fromQuery('sort', 'id');
            if (!isset($sortMap[$sortKey])) { $sortKey = 'id'; }
            $sortExpr = $sortMap[$sortKey];
            $dir = strtolower((string) $this->params()->fromQuery('dir', 'desc')) === 'asc' ? 'ASC' : 'DESC';
            $op  = $dir === 'ASC' ? '>' : '<';

            $countWhere = $filterWhere ? 'WHERE ' . implode(' AND ', $filterWhere) : '';
            $total = (int) ((array) iterator_to_array($db->query("SELECT COUNT(*) AS t FROM melis_ecom_lang $countWhere", $filterParams))[0])['t'];

            $dataWhere = $filterWhere; $dataParams = $filterParams;
            $after = (string) ($this->params()->fromQuery('after', '') ?? '');
            if ($after !== '') {
                $cur = json_decode((string) base64_decode($after, true), true);
                if (is_array($cur) && array_key_exists('v', $cur) && array_key_exists('id', $cur)) {
                    $dataWhere[]  = "($sortExpr $op ? OR ($sortExpr = ? AND elang_id $op ?))";
                    $dataParams[] = $cur['v']; $dataParams[] = $cur['v']; $dataParams[] = (int) $cur['id'];
                }
            }
            $dataWhereClause = $dataWhere ? 'WHERE ' . implode(' AND ', $dataWhere) : '';

            $rows = iterator_to_array($db->query(
                "SELECT elang_id, elang_locale, elang_name, elang_status, elang_flag, $sortExpr AS __sortval FROM melis_ecom_lang $dataWhereClause ORDER BY $sortExpr $dir, elang_id $dir LIMIT ?",
                array_merge($dataParams, [$limit])
            ));

            $items = []; $lastSortVal = null; $lastId = null;
            foreach ($rows as $r) {
                $a           = (array) $r;
                $lastSortVal = $a['__sortval'] ?? null;
                $lastId      = (int) ($a['elang_id'] ?? 0);
                $items[]     = $this->formatLanguage($a);
            }
            $nextCursor = null;
            if (count($rows) === $limit && $lastId !== null) {
                $nextCursor = base64_encode((string) json_encode(['v' => $lastSortVal, 'id' => $lastId]));
            }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'total' => $total, 'nextCursor' => $nextCursor, 'limit' => $limit]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /languages/stats ──────────────────────────────────────────────────
    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = (array) iterator_to_array($db->query(
                'SELECT COUNT(*) AS total, SUM(elang_status=1) AS active, SUM(elang_status=0) AS inactive FROM melis_ecom_lang', []
            ))[0];
            return $this->jsonResponse(['success' => true, 'data' => [
                'total' => (int) ($row['total'] ?? 0), 'active' => (int) ($row['active'] ?? 0), 'inactive' => (int) ($row['inactive'] ?? 0),
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /languages/:id ────────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT elang_id, elang_locale, elang_name, elang_status, elang_flag FROM melis_ecom_lang WHERE elang_id = ? LIMIT 1', [$id]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Language not found'], 404); }
            return $this->jsonResponse(['success' => true, 'data' => $this->formatLanguage((array) $rows[0])]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /languages/save ──────────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b  = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $id = (int) ($b['id'] ?? 0);

            $name = trim((string) ($b['name'] ?? ''));
            if ($name === '') { return $this->jsonResponse(['success' => false, 'error' => 'Please enter the language name'], 400); }

            $locale = trim((string) ($b['locale'] ?? ''));
            if ($locale === '') { return $this->jsonResponse(['success' => false, 'error' => 'Locale is empty'], 400); }

            /** @var \MelisCommerce\Model\Tables\MelisEcomLangTable $langTable */
            $langTable = $this->getServiceManager()->get('MelisEcomLangTable');

            // Dédoublonnage sur elang_locale (comportement legacy) : à la création on rejette
            // si la locale existe déjà ; en modification on rejette seulement si la locale
            // correspond à une AUTRE ligne que celle en cours d'édition.
            $existing = $langTable->getEntryByField('elang_locale', $locale)->current();
            if ($existing) {
                $existingId = (int) ((array) $existing)['elang_id'];
                if ($id <= 0 || $existingId !== $id) {
                    return $this->jsonResponse(['success' => false, 'error' => 'Language locale already exists'], 409);
                }
            }

            $data = [
                'elang_locale' => mb_substr($locale, 0, 45),
                'elang_name' => mb_substr($name, 0, 45),
                'elang_status' => (int) (!empty($b['status']) ? 1 : 0),
            ];

            // Drapeau : reproduit exactement le stockage legacy (base64_encode() du fichier à
            // l'upload, jamais redécodé — voir MelisComLanguageController::saveAction(), même
            // mécanisme que Country). Le champ `flag` reçu est déjà en base64 côté client (data:
            // prefix retiré côté FileReader) → stocké TEL QUEL, sans base64_decode().
            // Absent/null = on NE touche PAS à elang_flag (ne pas écraser un drapeau existant).
            if (array_key_exists('flag', $b) && $b['flag'] !== null && $b['flag'] !== '') {
                $data['elang_flag'] = (string) $b['flag'];
            }

            $newId = $langTable->save($data, $id ?: null);

            // Mêmes clés de traduction que MelisComLanguageController::saveAction() (legacy).
            $this->getEventManager()->trigger('meliscommerce_language_save_end', $this, [
                'success' => true,
                'textTitle' => $id ? 'tr_meliscommerce_language_edit' : 'tr_meliscommerce_language_add',
                'textMessage' => $id ? 'tr_meliscommerce_language_edit_success' : 'tr_meliscommerce_language_add_success',
                'typeCode' => $id ? 'ECOM_LANGUAGE_UPDATE' : 'ECOM_LANGUAGE_ADD', 'itemId' => (int) $newId,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], $id ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /languages/delete/:id ──────────────────────────────────────────
    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $this->getServiceManager()->get('MelisEcomLangTable')->deleteById($id);

            // Même clé de traduction que MelisComLanguageController::deleteAction() (legacy) —
            // note : tr_meliscore_tool_language_delete_success, pas tr_meliscommerce_..., repris tel quel.
            $this->getEventManager()->trigger('meliscommerce_language_delete_end', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_language_delete',
                'textMessage' => 'tr_meliscore_tool_language_delete_success',
                'typeCode' => 'ECOM_LANGUAGE_DELETE', 'itemId' => $id,
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
