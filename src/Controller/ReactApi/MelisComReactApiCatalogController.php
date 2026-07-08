<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour l'outil « Catalogues / Catégories » MelisCommerce (arbre drag-and-drop).
 *
 * Modèle : un *catalogue* = catégorie racine (cat_father_cat_id = -1) ; une *catégorie*
 * = catégorie enfant. Table `melis_ecom_category` (+ `_trans` pour nom/description par
 * langue, `melis_ecom_country_category` pour les pays).
 *
 * Routes :
 *   GET    /melis/react-api/catalog/tree          → arbre catalogues+catégories (filtré par langue)
 *   GET    /melis/react-api/catalog/options       → langues actives + pays
 *   GET    /melis/react-api/catalog/:id           → détail (textes par langue, pays, dates, statut)
 *   POST   /melis/react-api/catalog/save          → créer / mettre à jour (catalogue ou catégorie)
 *   POST   /melis/react-api/catalog/reorder       → déplacement drag-and-drop (ordre / parent)
 *   DELETE /melis/react-api/catalog/delete/:id     → supprimer (cascade trans/pays/produits)
 *
 * Aucune logique métier modifiée : les écritures réutilisent les services Laminas existants
 * (`MelisComCategoryService::saveCategory` / `deleteCategoryById`) ; le réordonnancement
 * rejoue exactement `MelisComCategoryListController::updateCategoryTreeViewAction`.
 */
class MelisComReactApiCatalogController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_categories_page';

    /** Langue back-office courante, repli 1. */
    private function langId(): int
    {
        $c = new \Laminas\Session\Container('meliscore');
        $id = (int) ($c['melis-lang-id'] ?? 0);
        return $id > 0 ? $id : 1;
    }

    private function currentUserId(): int
    {
        try {
            $identity = $this->getServiceManager()->get('MelisCoreAuth')->getStorage()->read();
            return (int) ($identity->usr_id ?? 0);
        } catch (\Throwable) {
            return 0;
        }
    }

    // ─── GET /catalog/tree ────────────────────────────────────────────────────
    public function treeAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $lang = (int) $this->params()->fromQuery('langId', 0) ?: $this->langId();
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $rows = [];
            foreach ($db->query(
                "SELECT c.cat_id, c.cat_father_cat_id, c.cat_order, c.cat_status,
                        (SELECT t.catt_name FROM melis_ecom_category_trans t
                          WHERE t.catt_category_id = c.cat_id AND t.catt_lang_id = ? LIMIT 1) AS name_lang,
                        (SELECT t2.catt_name FROM melis_ecom_category_trans t2
                          WHERE t2.catt_category_id = c.cat_id AND t2.catt_name <> '' LIMIT 1) AS name_any,
                        (SELECT COUNT(*) FROM melis_ecom_product_category pc WHERE pc.pcat_cat_id = c.cat_id) AS product_count
                 FROM melis_ecom_category c
                 ORDER BY c.cat_order", [$lang]
            ) as $r) {
                $r    = (array) $r;
                $name = trim((string) ($r['name_lang'] ?? '')) ?: trim((string) ($r['name_any'] ?? '')) ?: ('#' . $r['cat_id']);
                $rows[] = [
                    'id'           => (int) $r['cat_id'],
                    'fatherId'     => (int) $r['cat_father_cat_id'],
                    'order'        => (int) $r['cat_order'],
                    'status'       => (int) $r['cat_status'],
                    'name'         => $name,
                    'type'         => ((int) $r['cat_father_cat_id'] === -1) ? 'catalog' : 'category',
                    'productCount' => (int) $r['product_count'],
                    'children'     => [],
                ];
            }

            // Reconstruit l'arbre (racine = père -1) à partir de la liste plate ordonnée.
            $byFather = [];
            foreach ($rows as $row) { $byFather[$row['fatherId']][] = $row; }
            $build = function (int $fatherId) use (&$build, $byFather): array {
                $out = [];
                foreach ($byFather[$fatherId] ?? [] as $node) {
                    $node['children'] = $build($node['id']);
                    $out[] = $node;
                }
                return $out;
            };

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $build(-1)]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /catalog/options ─────────────────────────────────────────────────
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

            $countries = [];
            foreach ($db->query('SELECT ctry_id, ctry_name FROM melis_ecom_country ORDER BY ctry_name', []) as $r) {
                $r = (array) $r;
                $countries[] = ['id' => (int) $r['ctry_id'], 'name' => (string) $r['ctry_name']];
            }

            return $this->jsonResponse(['success' => true, 'data' => ['languages' => $languages, 'countries' => $countries]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /catalog/:id ─────────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query(
                'SELECT cat_id, cat_father_cat_id, cat_order, cat_status, cat_reference, cat_date_valid_start, cat_date_valid_end
                 FROM melis_ecom_category WHERE cat_id = ? LIMIT 1', [$id]
            ));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Category not found'], 404); }
            $c = (array) $rows[0];

            // Textes existants par langue.
            $texts = [];
            foreach ($db->query('SELECT catt_lang_id, catt_name, catt_description FROM melis_ecom_category_trans WHERE catt_category_id = ?', [$id]) as $r) {
                $r = (array) $r;
                $texts[(int) $r['catt_lang_id']] = ['name' => (string) ($r['catt_name'] ?? ''), 'description' => (string) ($r['catt_description'] ?? '')];
            }
            $textList = [];
            foreach ($db->query('SELECT elang_id, elang_name FROM melis_ecom_lang WHERE elang_status = 1 ORDER BY elang_id', []) as $r) {
                $r   = (array) $r;
                $lid = (int) $r['elang_id'];
                $textList[] = [
                    'langId'      => $lid,
                    'langName'    => (string) $r['elang_name'],
                    'name'        => $texts[$lid]['name']        ?? '',
                    'description' => $texts[$lid]['description'] ?? '',
                ];
            }

            $countryIds = [];
            foreach ($db->query('SELECT ccat_country_id FROM melis_ecom_country_category WHERE ccat_category_id = ?', [$id]) as $r) {
                $countryIds[] = (int) ((array) $r)['ccat_country_id'];
            }

            return $this->jsonResponse(['success' => true, 'data' => [
                'id'         => (int) $c['cat_id'],
                'fatherId'   => (int) $c['cat_father_cat_id'],
                'type'       => ((int) $c['cat_father_cat_id'] === -1) ? 'catalog' : 'category',
                'status'     => (int) $c['cat_status'],
                'reference'  => (string) ($c['cat_reference'] ?? ''),
                'validStart' => $c['cat_date_valid_start'] ?? null,
                'validEnd'   => $c['cat_date_valid_end'] ?? null,
                'texts'      => $textList,
                'countryIds' => $countryIds,
            ]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── POST /catalog/save ───────────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $id   = (int) ($body['id'] ?? 0);

            $fatherId  = array_key_exists('fatherId', $body) ? (int) $body['fatherId'] : -1;
            $status    = (int) (!empty($body['status']) ? 1 : 0);
            $reference = trim((string) ($body['reference'] ?? ''));
            $validStart = $this->normalizeDate($body['validStart'] ?? null);
            $validEnd   = $this->normalizeDate($body['validEnd'] ?? null);
            $texts      = is_array($body['texts'] ?? null) ? $body['texts'] : [];
            $countryIds = array_values(array_unique(array_map('intval', is_array($body['countryIds'] ?? null) ? $body['countryIds'] : [])));

            // Au moins un nom (toutes langues confondues), comme l'outil legacy.
            $hasName = false;
            foreach ($texts as $tx) { if (trim((string) ($tx['name'] ?? '')) !== '') { $hasName = true; break; } }
            if (!$hasName) {
                return $this->jsonResponse(['success' => false, 'error' => 'Un nom est obligatoire (au moins une langue).'], 400);
            }
            if ($validStart && $validEnd && strtotime($validStart) > strtotime($validEnd)) {
                return $this->jsonResponse(['success' => false, 'error' => 'La date de fin doit être postérieure à la date de début.'], 400);
            }

            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $now  = date('Y-m-d H:i:s');
            $uid  = $this->currentUserId();

            // Données catégorie (cf. MelisComCategoryController::getCategoryAction).
            $catData = [
                'cat_father_cat_id'    => $fatherId,
                'cat_status'           => $status,
                'cat_reference'        => $reference !== '' ? $reference : null,
                'cat_date_valid_start' => $validStart,
                'cat_date_valid_end'   => $validEnd,
                'cat_date_edit'        => $now,
                'cat_user_id_edit'     => $uid,
            ];
            if ($id <= 0) {
                // Nouveau : ordre = dernier ordre du père + 1.
                $cntRow = iterator_to_array($db->query('SELECT COUNT(*) AS c FROM melis_ecom_category WHERE cat_father_cat_id = ?', [$fatherId]));
                $catData['cat_order']            = (int) ((array) $cntRow[0])['c'] + 1;
                $catData['cat_user_id_creation'] = $uid;
                $catData['cat_date_creation']    = $now;
            }

            // Traductions : on récupère les catt_id existants pour faire un upsert par langue.
            $existing = [];
            if ($id > 0) {
                foreach ($db->query('SELECT catt_id, catt_lang_id FROM melis_ecom_category_trans WHERE catt_category_id = ?', [$id]) as $r) {
                    $r = (array) $r;
                    $existing[(int) $r['catt_lang_id']] = (int) $r['catt_id'];
                }
            }
            $catTransData = [];
            foreach ($texts as $tx) {
                if (!is_array($tx)) { continue; }
                $lid = (int) ($tx['langId'] ?? 0);
                if ($lid <= 0) { continue; }
                $catTransData[] = [
                    'catt_id'          => $existing[$lid] ?? null,
                    'catt_lang_id'     => $lid,
                    'catt_name'        => trim((string) ($tx['name'] ?? '')),
                    'catt_description' => (string) ($tx['description'] ?? ''),
                ];
            }

            $catCountriesData = [];
            foreach ($countryIds as $cid) {
                if ($cid > 0) { $catCountriesData[] = ['ccat_country_id' => $cid]; }
            }

            // Réutilise la logique métier existante (événements, cache, traductions, pays ; SEO inchangé).
            // ob_* : en dev (display_errors on), les services legacy peuvent émettre des
            // warnings/deprecations imprimés AVANT le JSON → on les capture et jette pour garder un JSON valide.
            $service = $this->getServiceManager()->get('MelisComCategoryService');
            ob_start();
            $catId = $service->saveCategory($catData, $catTransData, $catCountriesData, [], $id ?: null);
            ob_end_clean();

            if (!is_numeric($catId)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Échec de l\'enregistrement.'], 500);
            }

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $catId]], $id ? 200 : 201);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── POST /catalog/reorder ────────────────────────────────────────────────
    // Rejoue MelisComCategoryListController::updateCategoryTreeViewAction (drag-and-drop).
    public function reorderAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $body      = json_decode($this->getRequest()->getContent(), true) ?? [];
            $catId     = (int) ($body['catId'] ?? 0);
            $fatherId  = (int) ($body['fatherId'] ?? -1);
            $newOrder  = max(1, (int) ($body['order'] ?? 1));
            $oldParent = (int) ($body['oldParent'] ?? -1);

            if ($catId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
            // Interdit : transformer une catégorie en catalogue (cf. isFromCategoryToCatalog).
            if ($fatherId === -1 && $oldParent !== -1) {
                return $this->jsonResponse(['success' => false, 'error' => 'Une catégorie ne peut pas devenir un catalogue.'], 422);
            }

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            // Frères du nouveau père, ordonnés (la catégorie déplacée exclue).
            $siblings = [];
            foreach ($db->query('SELECT cat_id FROM melis_ecom_category WHERE cat_father_cat_id = ? ORDER BY cat_order', [$fatherId]) as $r) {
                $sid = (int) ((array) $r)['cat_id'];
                if ($sid !== $catId) { $siblings[] = $sid; }
            }
            // Insère la catégorie à la nouvelle position (newOrder - 1).
            array_splice($siblings, $newOrder - 1, 0, [$catId]);

            // Réordonne 1..n et persiste père + ordre.
            $order = 1;
            foreach ($siblings as $sid) {
                $db->query('UPDATE melis_ecom_category SET cat_father_cat_id = ?, cat_order = ? WHERE cat_id = ?', [$fatherId, $order++, $sid]);
            }

            try { $this->getServiceManager()->get('MelisComCacheService')->deleteCache('category', $catId, $fatherId); } catch (\Throwable) {}

            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── DELETE /catalog/delete/:id ───────────────────────────────────────────
    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $db        = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $fatherRow = iterator_to_array($db->query('SELECT cat_father_cat_id FROM melis_ecom_category WHERE cat_id = ? LIMIT 1', [$id]));
            $fatherId  = $fatherRow ? (int) ((array) $fatherRow[0])['cat_father_cat_id'] : -1;

            // Réutilise la suppression métier existante (cascade trans/pays/produits/seo + réordonnancement).
            ob_start();
            $ok = $this->getServiceManager()->get('MelisComCategoryService')->deleteCategoryById($id, (string) $fatherId);
            ob_end_clean();
            if (!$ok) {
                return $this->jsonResponse(['success' => false, 'error' => 'Échec de la suppression.'], 500);
            }
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /catalog/:id/seo ─────────────────────────────────────────────────
    public function seoAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $seo = [];
            foreach ($db->query('SELECT eseo_lang_id, eseo_page_id, eseo_url, eseo_url_redirect, eseo_url_301, eseo_meta_title, eseo_meta_description FROM melis_ecom_seo WHERE eseo_category_id = ?', [$id]) as $r) {
                $r = (array) $r;
                $seo[(int) $r['eseo_lang_id']] = [
                    'pageId'          => (string) ($r['eseo_page_id'] ?? ''),
                    'url'             => (string) ($r['eseo_url'] ?? ''),
                    'urlRedirect'     => (string) ($r['eseo_url_redirect'] ?? ''),
                    'url301'          => (string) ($r['eseo_url_301'] ?? ''),
                    'metaTitle'       => (string) ($r['eseo_meta_title'] ?? ''),
                    'metaDescription' => (string) ($r['eseo_meta_description'] ?? ''),
                ];
            }
            $list = [];
            foreach ($db->query('SELECT elang_id, elang_name FROM melis_ecom_lang WHERE elang_status = 1 ORDER BY elang_id', []) as $r) {
                $r   = (array) $r;
                $lid = (int) $r['elang_id'];
                $list[] = [
                    'langId'          => $lid,
                    'langName'        => (string) $r['elang_name'],
                    'pageId'          => $seo[$lid]['pageId']          ?? '',
                    'url'             => $seo[$lid]['url']             ?? '',
                    'urlRedirect'     => $seo[$lid]['urlRedirect']     ?? '',
                    'url301'          => $seo[$lid]['url301']          ?? '',
                    'metaTitle'       => $seo[$lid]['metaTitle']       ?? '',
                    'metaDescription' => $seo[$lid]['metaDescription'] ?? '',
                ];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $list]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /catalog/:id/seo/save ───────────────────────────────────────────
    public function seoSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $items = is_array($body['items'] ?? null) ? $body['items'] : [];
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            // eseo_id existants par langue (pour upsert via le service SEO).
            $existing = [];
            foreach ($db->query('SELECT eseo_id, eseo_lang_id FROM melis_ecom_seo WHERE eseo_category_id = ?', [$id]) as $r) {
                $r = (array) $r; $existing[(int) $r['eseo_lang_id']] = (int) $r['eseo_id'];
            }
            $seoData = [];
            foreach ($items as $it) {
                if (!is_array($it)) { continue; }
                $lid = (int) ($it['langId'] ?? 0);
                if ($lid <= 0) { continue; }
                $seoData[] = [
                    'eseo_id'               => $existing[$lid] ?? null,
                    'eseo_lang_id'          => $lid,
                    'eseo_page_id'          => ($it['pageId'] ?? '') === '' ? null : (int) $it['pageId'],
                    'eseo_url'              => trim((string) ($it['url'] ?? '')),
                    'eseo_url_redirect'     => trim((string) ($it['urlRedirect'] ?? '')),
                    'eseo_url_301'          => trim((string) ($it['url301'] ?? '')),
                    'eseo_meta_title'       => trim((string) ($it['metaTitle'] ?? '')),
                    'eseo_meta_description' => trim((string) ($it['metaDescription'] ?? '')),
                ];
            }
            // Réutilise la logique métier SEO existante (insert/update/delete-si-vide).
            ob_start();
            $this->getServiceManager()->get('MelisComSeoService')->saveSeoDataAction('category', $id, $seoData);
            ob_end_clean();
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /catalog/:id/products ────────────────────────────────────────────
    public function productsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();
            $ttRows = iterator_to_array($db->query("SELECT ptt_id FROM melis_ecom_product_text_type WHERE ptt_code = 'TITLE' LIMIT 1", []));
            $ttId   = $ttRows ? (int) ((array) $ttRows[0])['ptt_id'] : 1;

            $items = [];
            foreach ($db->query(
                "SELECT p.prd_id, p.prd_reference, p.prd_status,
                        (SELECT pt.ptxt_field_short FROM melis_ecom_product_text pt
                          WHERE pt.ptxt_prd_id = p.prd_id AND pt.ptxt_type = ? AND pt.ptxt_lang_id = ? LIMIT 1) AS name_lang,
                        (SELECT pt2.ptxt_field_short FROM melis_ecom_product_text pt2
                          WHERE pt2.ptxt_prd_id = p.prd_id AND pt2.ptxt_type = ? AND pt2.ptxt_field_short <> '' LIMIT 1) AS name_any
                 FROM melis_ecom_product_category pc
                 JOIN melis_ecom_product p ON p.prd_id = pc.pcat_prd_id
                 WHERE pc.pcat_cat_id = ? ORDER BY pc.pcat_order", [$ttId, $lang, $ttId, $id]
            ) as $r) {
                $r    = (array) $r;
                $name = trim((string) ($r['name_lang'] ?? '')) ?: trim((string) ($r['name_any'] ?? '')) ?: trim((string) ($r['prd_reference'] ?? '')) ?: ('#' . $r['prd_id']);
                $items[] = ['id' => (int) $r['prd_id'], 'reference' => (string) ($r['prd_reference'] ?? ''), 'name' => $name, 'status' => (int) $r['prd_status']];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────
    /** Normalise une date ISO (YYYY-MM-DD[ HH:MM:SS]) → 'Y-m-d H:i:s' ou null. */
    private function normalizeDate($value): ?string
    {
        $v = trim((string) ($value ?? ''));
        if ($v === '') { return null; }
        $ts = strtotime($v);
        return $ts ? date('Y-m-d H:i:s', $ts) : null;
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

    private function errorResponse(\Throwable $e, int $status = 500): HttpResponse
    {
        return $this->jsonResponse([
            'success' => false,
            'error'   => $e->getMessage(),
            'file'    => basename($e->getFile()) . ':' . $e->getLine(),
        ], $status);
    }
}
