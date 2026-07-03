<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour l'outil Attributs MelisCommerce.
 *
 * Un attribut (melis_ecom_attribute) porte un TYPE fixe (Integer/Decimal/Boolean/Short
 * string/Text/Date and time/File — melis_ecom_attribute_type) qui détermine, pour TOUTES
 * ses valeurs, quelle colonne de melis_ecom_attribute_value_trans est utilisée
 * (avt_v_int/float/bool/varchar/text/datetime/binary). Traductions par langue à deux
 * niveaux : le LABEL de l'attribut (attribute_trans : name/description, onglet Labels)
 * et, par valeur, le CONTENU typé (attribute_value_trans, onglet Values).
 *
 * Routes :
 *   GET    /melis/react-api/attributes                          → liste paginée + filtres
 *   GET    /melis/react-api/attributes/stats                     → KPI
 *   GET    /melis/react-api/attributes/options                   → langues + types
 *   GET    /melis/react-api/attributes/:id                       → détail + labels (tous langues)
 *   POST   /melis/react-api/attributes/save                      → créer / mettre à jour
 *   DELETE /melis/react-api/attributes/delete/:id                → supprimer (cascade)
 *   GET    /melis/react-api/attributes/:id/values                → valeurs + traductions typées
 *   POST   /melis/react-api/attributes/:id/values/save            → créer / mettre à jour une valeur
 *   DELETE /melis/react-api/attributes/:id/values/:valId           → supprimer une valeur
 *   GET    /melis/react-api/attributes/:id/values/:valId/binary    → télécharger le fichier (type File)
 *
 * Logique métier inchangée pour les écritures : réutilise MelisComAttributeService
 * (saveAttribute/saveAttributeValue/saveAttributeValueTrans/deleteAttributeById/
 * deleteAttributeValueById) — voir le gotcha ob_start()/ob_end_clean() (warnings PHP
 * legacy qui polluent la réponse JSON).
 */
class MelisComReactApiAttributeController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_attribute_list_page';

    /** melis_ecom_attribute_value_trans column for a given attribute_type.atype_column_value. */
    private function columnFor(string $atypeColumnValue): string
    {
        return 'avt_v_' . $atypeColumnValue;
    }

    private function langId(): int
    {
        $c = new \Laminas\Session\Container('meliscore');
        $id = (int) ($c['melis-lang-id'] ?? 0);
        return $id > 0 ? $id : 1;
    }

    private function attributeTypeColumn(int $attributeId): ?string
    {
        $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
        $rows = iterator_to_array($db->query(
            'SELECT t.atype_column_value FROM melis_ecom_attribute a
             JOIN melis_ecom_attribute_type t ON t.atype_id = a.attr_type_id
             WHERE a.attr_id = ? LIMIT 1',
            [$attributeId]
        ));
        if (empty($rows)) { return null; }
        return (string) ((array) $rows[0])['atype_column_value'];
    }

    // ─── GET /attributes ───────────────────────────────────────────────────────
    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $limit  = min(9999, max(1, (int) $this->params()->fromQuery('limit', 50)));
            $page   = max(1, (int) $this->params()->fromQuery('page', 1));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawSt  = $this->params()->fromQuery('status', '');
            $status = ($rawSt !== '' && $rawSt !== null) ? (int) $rawSt : null;
            $rawTy  = $this->params()->fromQuery('typeId', '');
            $typeId = ($rawTy !== '' && $rawTy !== null) ? (int) $rawTy : null;
            $lang   = $this->langId();

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $where = []; $params = [];
            if ($status !== null) { $where[] = 'a.attr_status = ?'; $params[] = $status; }
            if ($typeId !== null) { $where[] = 'a.attr_type_id = ?'; $params[] = $typeId; }
            if ($search !== '') {
                $like = '%' . $search . '%';
                $where[] = '(a.attr_reference LIKE ? OR EXISTS (SELECT 1 FROM melis_ecom_attribute_trans t2 WHERE t2.atrans_attribute_id = a.attr_id AND t2.atrans_name LIKE ?))';
                $params[] = $like; $params[] = $like;
            }
            $wc = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $total = (int) ((array) iterator_to_array($db->query("SELECT COUNT(*) AS t FROM melis_ecom_attribute a $wc", $params))[0])['t'];

            $offset = ($page - 1) * $limit;
            $rows = $db->query(
                "SELECT a.attr_id, a.attr_reference, a.attr_type_id, a.attr_status, a.attr_visible, a.attr_searchable,
                    at.atype_name,
                    COALESCE(
                        (SELECT atrans_name FROM melis_ecom_attribute_trans WHERE atrans_attribute_id = a.attr_id AND atrans_lang_id = ? LIMIT 1),
                        (SELECT atrans_name FROM melis_ecom_attribute_trans WHERE atrans_attribute_id = a.attr_id ORDER BY atrans_id LIMIT 1),
                        a.attr_reference
                    ) AS name,
                    (SELECT COUNT(*) FROM melis_ecom_attribute_value WHERE atval_attribute_id = a.attr_id) AS values_count
                FROM melis_ecom_attribute a
                LEFT JOIN melis_ecom_attribute_type at ON at.atype_id = a.attr_type_id
                $wc
                ORDER BY a.attr_id DESC
                LIMIT ? OFFSET ?",
                array_merge([$lang], $params, [$limit, $offset])
            );

            $items = [];
            foreach ($rows as $r) {
                $r = (array) $r;
                $items[] = [
                    'id' => (int) $r['attr_id'],
                    'reference' => (string) ($r['attr_reference'] ?? ''),
                    'name' => (string) ($r['name'] ?? ''),
                    'typeId' => (int) $r['attr_type_id'],
                    'typeName' => (string) ($r['atype_name'] ?? ''),
                    'status' => (int) $r['attr_status'],
                    'visible' => (int) $r['attr_visible'],
                    'searchable' => (int) $r['attr_searchable'],
                    'valuesCount' => (int) ($r['values_count'] ?? 0),
                ];
            }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'total' => $total, 'page' => $page, 'limit' => $limit]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /attributes/stats ─────────────────────────────────────────────────
    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = (array) iterator_to_array($db->query(
                'SELECT COUNT(*) AS total, SUM(attr_status=1) AS active, SUM(attr_status=0) AS inactive FROM melis_ecom_attribute', []
            ))[0];
            return $this->jsonResponse(['success' => true, 'data' => [
                'total' => (int) ($row['total'] ?? 0), 'active' => (int) ($row['active'] ?? 0), 'inactive' => (int) ($row['inactive'] ?? 0),
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /attributes/options ───────────────────────────────────────────────
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

            $types = [];
            foreach ($db->query('SELECT atype_id, atype_name, atype_column_value FROM melis_ecom_attribute_type ORDER BY atype_id', []) as $r) {
                $r = (array) $r;
                $types[] = ['id' => (int) $r['atype_id'], 'name' => (string) $r['atype_name'], 'columnValue' => (string) $r['atype_column_value']];
            }

            return $this->jsonResponse(['success' => true, 'data' => ['languages' => $languages, 'types' => $types]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /attributes/:id ────────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT * FROM melis_ecom_attribute WHERE attr_id = ? LIMIT 1', [$id]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Attribute not found'], 404); }
            $a = (array) $rows[0];

            $translations = [];
            foreach ($db->query('SELECT atrans_lang_id, atrans_name, atrans_description FROM melis_ecom_attribute_trans WHERE atrans_attribute_id = ?', [$id]) as $r) {
                $r = (array) $r;
                $translations[] = ['langId' => (int) $r['atrans_lang_id'], 'name' => (string) ($r['atrans_name'] ?? ''), 'description' => (string) ($r['atrans_description'] ?? '')];
            }

            $valuesCount = (int) ((array) iterator_to_array($db->query('SELECT COUNT(*) AS c FROM melis_ecom_attribute_value WHERE atval_attribute_id = ?', [$id]))[0])['c'];

            return $this->jsonResponse(['success' => true, 'data' => [
                'id' => (int) $a['attr_id'],
                'reference' => (string) ($a['attr_reference'] ?? ''),
                'typeId' => (int) $a['attr_type_id'],
                'status' => (int) $a['attr_status'],
                'visible' => (int) $a['attr_visible'],
                'searchable' => (int) $a['attr_searchable'],
                'translations' => $translations,
                'valuesCount' => $valuesCount,
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /attributes/save ──────────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b  = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $id = (int) ($b['id'] ?? 0);

            $reference = trim((string) ($b['reference'] ?? ''));
            if ($reference === '') { return $this->jsonResponse(['success' => false, 'error' => 'La référence est obligatoire.'], 400); }

            $typeId = (int) ($b['typeId'] ?? 0);
            if ($typeId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Le type est obligatoire.'], 400); }

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            // Le type est DÉFINITIF une fois l'attribut créé (documenté dans le tooltip legacy du
            // champ) : il détermine la colonne typée utilisée par TOUTES les valeurs existantes
            // (melis_ecom_attribute_value_trans.avt_v_*). Le changer romprait ces valeurs. On ignore
            // donc silencieusement tout typeId envoyé sur une mise à jour et on garde l'existant.
            if ($id > 0) {
                $existing = iterator_to_array($db->query('SELECT attr_type_id FROM melis_ecom_attribute WHERE attr_id = ? LIMIT 1', [$id]));
                if (empty($existing)) { return $this->jsonResponse(['success' => false, 'error' => 'Attribute not found'], 404); }
                $typeId = (int) ((array) $existing[0])['attr_type_id'];
            }

            $rawTranslations = is_array($b['translations'] ?? null) ? $b['translations'] : [];
            $hasName = false;
            foreach ($rawTranslations as $t) { if (trim((string) ($t['name'] ?? '')) !== '') { $hasName = true; break; } }
            if (!$hasName) { return $this->jsonResponse(['success' => false, 'error' => 'Le nom est obligatoire pour au moins une langue.'], 400); }

            $existingByLang = [];
            if ($id > 0) {
                foreach ($db->query('SELECT atrans_id, atrans_lang_id FROM melis_ecom_attribute_trans WHERE atrans_attribute_id = ?', [$id]) as $r) {
                    $r = (array) $r;
                    $existingByLang[(int) $r['atrans_lang_id']] = (int) $r['atrans_id'];
                }
            }

            $transArray = [];
            foreach ($rawTranslations as $t) {
                $name = trim((string) ($t['name'] ?? ''));
                if ($name === '') { continue; }
                $langId = (int) ($t['langId'] ?? 0);
                if ($langId <= 0) { continue; }
                $row = [
                    'atrans_lang_id' => $langId,
                    'atrans_name' => $name,
                    'atrans_description' => trim((string) ($t['description'] ?? '')),
                ];
                if (isset($existingByLang[$langId])) { $row['atrans_id'] = $existingByLang[$langId]; }
                $transArray[] = $row;
            }

            $attribute = [
                'attr_type_id' => $typeId,
                'attr_reference' => $reference,
                'attr_status' => (int) (!empty($b['status']) ? 1 : 0),
                'attr_visible' => (int) (!empty($b['visible']) ? 1 : 0),
                'attr_searchable' => (int) (!empty($b['searchable']) ? 1 : 0),
            ];

            ob_start();
            $newId = $this->getServiceManager()->get('MelisComAttributeService')->saveAttribute($attribute, $transArray, $id ?: null);
            ob_end_clean();

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], $id ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /attributes/delete/:id ──────────────────────────────────────────
    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            ob_start();
            $this->getServiceManager()->get('MelisComAttributeService')->deleteAttributeById($id);
            ob_end_clean();
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /attributes/:id/values ─────────────────────────────────────────────
    public function valuesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid attribute'], 400); }
        try {
            $column = $this->attributeTypeColumn($id);
            if ($column === null) { return $this->jsonResponse(['success' => false, 'error' => 'Attribute not found'], 404); }
            $col = $this->columnFor($column);
            $lang = $this->langId();

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $items = [];
            foreach ($db->query('SELECT atval_id, atval_reference FROM melis_ecom_attribute_value WHERE atval_attribute_id = ? ORDER BY atval_id', [$id]) as $r) {
                $r = (array) $r;
                $valId = (int) $r['atval_id'];

                $selectCol = $column === 'binary' ? "(LENGTH($col) > 0) AS has_file" : $col;
                $translations = [];
                foreach ($db->query("SELECT avt_id, avt_lang_id, $selectCol FROM melis_ecom_attribute_value_trans WHERE av_attribute_value_id = ?", [$valId]) as $tr) {
                    $tr = (array) $tr;
                    $raw = $column === 'binary' ? null : ($tr[$col] ?? null);
                    $value = match ($column) {
                        'bool' => $raw !== null ? (bool) $raw : null,
                        'int' => $raw !== null ? (int) $raw : null,
                        'float' => $raw !== null ? (float) $raw : null,
                        'datetime' => $raw !== null ? substr((string) $raw, 0, 10) : null,
                        default => $raw !== null ? (string) $raw : null,
                    };
                    $translations[] = [
                        'avtId' => (int) $tr['avt_id'],
                        'langId' => (int) $tr['avt_lang_id'],
                        'value' => $value,
                        'hasFile' => $column === 'binary' ? !empty($tr['has_file']) : null,
                    ];
                }

                $display = null;
                foreach ($translations as $tr) { if ($tr['langId'] === $lang) { $display = $tr['value']; break; } }
                if ($display === null && !empty($translations)) { $display = $translations[0]['value']; }

                $items[] = [
                    'id' => $valId,
                    'reference' => (string) ($r['atval_reference'] ?? ''),
                    'translations' => $translations,
                    'displayValue' => $display,
                ];
            }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'typeColumn' => $column]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /attributes/:id/values/save ───────────────────────────────────────
    public function valueSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid attribute'], 400); }
        try {
            $column = $this->attributeTypeColumn($id);
            if ($column === null) { return $this->jsonResponse(['success' => false, 'error' => 'Attribute not found'], 404); }
            $col = $this->columnFor($column);

            $b = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $valId = (int) ($b['id'] ?? 0);
            $reference = trim((string) ($b['reference'] ?? ''));
            $translations = is_array($b['translations'] ?? null) ? $b['translations'] : [];

            $attributeSvc = $this->getServiceManager()->get('MelisComAttributeService');
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            ob_start();
            if ($valId <= 0) {
                $valueRow = ['atval_attribute_id' => $id, 'atval_type_id' => 0, 'atval_reference' => $reference];
                $typeRow = iterator_to_array($db->query('SELECT attr_type_id FROM melis_ecom_attribute WHERE attr_id = ? LIMIT 1', [$id]));
                $valueRow['atval_type_id'] = !empty($typeRow) ? (int) ((array) $typeRow[0])['attr_type_id'] : 0;
                $valId = (int) $attributeSvc->saveAttributeValue($valueRow);
            } else {
                $attributeSvc->saveAttributeValue(['atval_reference' => $reference], $valId);
            }

            $existingByLang = [];
            foreach ($db->query('SELECT avt_id, avt_lang_id FROM melis_ecom_attribute_value_trans WHERE av_attribute_value_id = ?', [$valId]) as $r) {
                $r = (array) $r;
                $existingByLang[(int) $r['avt_lang_id']] = (int) $r['avt_id'];
            }

            foreach ($translations as $t) {
                $langId = (int) ($t['langId'] ?? 0);
                if ($langId <= 0) { continue; }
                $value = $t['value'] ?? null;
                $existingAvtId = $existingByLang[$langId] ?? null;

                // 0 / false remain valid values for int/float/bool — only null (or, for
                // text-like columns, an empty/whitespace string) means "not filled in".
                $isEmpty = $value === null;
                if (!$isEmpty && in_array($column, ['varchar', 'text', 'datetime'], true)) {
                    $isEmpty = trim((string) $value) === '';
                }
                if ($column === 'binary') { $isEmpty = empty($value); }

                if ($isEmpty) {
                    if ($existingAvtId) { $attributeSvc->deleteAttributeValueTransById($existingAvtId); }
                    continue;
                }

                $converted = match ($column) {
                    'bool' => (int) (bool) $value,
                    'int' => (int) $value,
                    'float' => (float) $value,
                    'datetime' => date('Y-m-d H:i:s', strtotime((string) $value) ?: time()),
                    'binary' => base64_decode((string) $value, true) ?: '',
                    default => (string) $value,
                };

                $row = ['av_attribute_value_id' => $valId, 'avt_lang_id' => $langId, $col => $converted];
                $attributeSvc->saveAttributeValueTrans($row, $existingAvtId);
            }
            ob_end_clean();

            return $this->jsonResponse(['success' => true, 'data' => ['id' => $valId]], $b['id'] ?? null ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /attributes/:id/values/:valId ───────────────────────────────────
    public function valueDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        $valId = (int) $this->params()->fromRoute('valId', 0);
        if ($valId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid'], 400); }
        try {
            ob_start();
            $this->getServiceManager()->get('MelisComAttributeService')->deleteAttributeValueById($valId, $id ?: null);
            ob_end_clean();
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /attributes/:id/values/:valId/binary ───────────────────────────────
    public function valueBinaryAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $valId = (int) $this->params()->fromRoute('valId', 0);
        $langId = (int) $this->params()->fromQuery('langId', 0);
        if ($valId <= 0 || $langId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query(
                'SELECT avt_v_binary FROM melis_ecom_attribute_value_trans WHERE av_attribute_value_id = ? AND avt_lang_id = ? LIMIT 1',
                [$valId, $langId]
            ));
            if (empty($rows) || empty(((array) $rows[0])['avt_v_binary'])) {
                return $this->jsonResponse(['success' => false, 'error' => 'File not found'], 404);
            }
            $content = ((array) $rows[0])['avt_v_binary'];

            /** @var HttpResponse $response */
            $response = $this->getResponse();
            $response->getHeaders()->addHeaders([
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="attribute-value-' . $valId . '.bin"',
                'Content-Length' => (string) strlen($content),
            ]);
            $response->setContent($content);
            return $response;
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
