<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour l'outil Produits (Catalogue) MelisCommerce.
 *
 * Onglets du formulaire (mêmes que le legacy) : Main / Text / Variants / SEO.
 * Tables : melis_ecom_product (+ _text TITLE par langue, _category liens, _variant, _seo).
 *
 * Routes :
 *   GET    /melis/react-api/products             → liste paginée + filtres
 *   GET    /melis/react-api/products/stats        → KPI
 *   GET    /melis/react-api/products/options      → catégories + langues + pays
 *   GET    /melis/react-api/products/:id          → détail (textes, catégories, seo, variantes)
 *   GET    /melis/react-api/products/:id/variants → variantes du produit
 *   POST   /melis/react-api/products/save         → créer / mettre à jour
 *   DELETE /melis/react-api/products/delete/:id    → supprimer
 *
 * Logique métier inchangée : l'enregistrement réutilise MelisComProductService::saveProduct
 * et la suppression deleteProductById ; SEO via MelisComSeoService::saveSeoDataAction.
 */
class MelisComReactApiProductController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_product_list_container';

    private function langId(): int
    {
        $c = new \Laminas\Session\Container('meliscore');
        $id = (int) ($c['melis-lang-id'] ?? 0);
        return $id > 0 ? $id : 1;
    }

    /** ptt_id du type de texte « TITLE » (nom du produit), repli 1. */
    private function titleTypeId($db): int
    {
        $rows = iterator_to_array($db->query("SELECT ptt_id FROM melis_ecom_product_text_type WHERE ptt_code = 'TITLE' LIMIT 1", []));
        return $rows ? (int) ((array) $rows[0])['ptt_id'] : 1;
    }

    private function currentUserId(): int
    {
        try { return (int) ($this->getServiceManager()->get('MelisCoreAuth')->getStorage()->read()->usr_id ?? 0); }
        catch (\Throwable) { return 0; }
    }

    // ─── GET /products ────────────────────────────────────────────────────────
    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $limit  = min(9999, max(1, (int) $this->params()->fromQuery('limit', 50)));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawSt  = $this->params()->fromQuery('status', '');
            $status = ($rawSt !== '' && $rawSt !== null) ? (int) $rawSt : null;
            $rawCat = $this->params()->fromQuery('categoryId', '');
            $catId  = ($rawCat !== '' && $rawCat !== null) ? (int) $rawCat : null;
            $lang   = $this->langId();

            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $ttId = $this->titleTypeId($db);

            $where = []; $params = [];
            if ($status !== null) { $where[] = 'p.prd_status = ?'; $params[] = $status; }
            if ($catId !== null)  { $where[] = 'EXISTS (SELECT 1 FROM melis_ecom_product_category pc WHERE pc.pcat_prd_id = p.prd_id AND pc.pcat_cat_id = ?)'; $params[] = $catId; }
            if ($search !== '') {
                $like = '%' . $search . '%';
                $where[] = '(p.prd_reference LIKE ? OR EXISTS (SELECT 1 FROM melis_ecom_product_text pt WHERE pt.ptxt_prd_id = p.prd_id AND pt.ptxt_field_short LIKE ?))';
                $params = array_merge($params, [$like, $like]);
            }
            $wc = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $total = (int) ((array) iterator_to_array($db->query("SELECT COUNT(*) AS t FROM melis_ecom_product p $wc", $params))[0])['t'];

            $rows = $db->query("
                SELECT p.prd_id, p.prd_reference, p.prd_status, p.prd_date_creation, p.prd_date_edit,
                       (SELECT pt.ptxt_field_short FROM melis_ecom_product_text pt WHERE pt.ptxt_prd_id = p.prd_id AND pt.ptxt_type = ? AND pt.ptxt_lang_id = ? LIMIT 1) AS name_cur,
                       (SELECT pt2.ptxt_field_short FROM melis_ecom_product_text pt2 WHERE pt2.ptxt_prd_id = p.prd_id AND pt2.ptxt_type = ? AND pt2.ptxt_field_short <> '' LIMIT 1) AS name_any,
                       (SELECT d.doc_path FROM melis_ecom_doc_relations dr JOIN melis_ecom_document d ON d.doc_id = dr.rdoc_doc_id WHERE dr.rdoc_product_id = p.prd_id AND d.doc_type_id != 2 ORDER BY d.doc_id ASC LIMIT 1) AS image_path
                FROM melis_ecom_product p
                $wc
                ORDER BY p.prd_id DESC
                LIMIT ?
            ", array_merge([$ttId, $lang, $ttId], $params, [$limit]));

            $items = [];
            foreach ($rows as $r) { $items[] = $this->formatProduct((array) $r, $db, $lang); }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'total' => $total]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/:id/tooltip ────────────────────────────────────────────
    // Détails des variantes (survol du nom produit) : ID, image, SKU, attributs, prix/stock « General ».
    public function tooltipAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        if ($prdId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();
            $valName = "COALESCE(NULLIF(tr.avt_v_varchar,''), NULLIF(tr.avt_v_text,''), av.atval_reference)";
            $items = [];
            foreach ($db->query("SELECT v.var_id, v.var_sku,
                    (SELECT d.doc_path FROM melis_ecom_doc_relations dr JOIN melis_ecom_document d ON d.doc_id = dr.rdoc_doc_id WHERE dr.rdoc_variant_id = v.var_id AND d.doc_type_id <> 2 LIMIT 1) AS image,
                    (SELECT GROUP_CONCAT($valName SEPARATOR ', ') FROM melis_ecom_variant_attribute_value vatv JOIN melis_ecom_attribute_value av ON av.atval_id = vatv.vatv_attribute_value_id LEFT JOIN melis_ecom_attribute_value_trans tr ON tr.av_attribute_value_id = av.atval_id AND tr.avt_lang_id = ? WHERE vatv.vatv_variant_id = v.var_id) AS attrs,
                    (SELECT price_net FROM melis_ecom_price WHERE price_var_id = v.var_id AND price_country_id = -1 LIMIT 1) AS price,
                    (SELECT stock_quantity FROM melis_ecom_variant_stock WHERE stock_var_id = v.var_id AND stock_country_id = -1 LIMIT 1) AS stock
                FROM melis_ecom_variant v WHERE v.var_prd_id = ? ORDER BY v.var_main_variant DESC, v.var_id", [$lang, $prdId]) as $r) {
                $r = (array) $r;
                $items[] = [
                    'id' => (int) $r['var_id'], 'sku' => (string) ($r['var_sku'] ?? ''),
                    'image' => isset($r['image']) && $r['image'] !== null ? (string) $r['image'] : '',
                    'attributes' => (string) ($r['attrs'] ?? ''),
                    'price' => $r['price'] !== null ? (float) $r['price'] : null,
                    'stock' => $r['stock'] !== null ? (int) $r['stock'] : null,
                ];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/stats ──────────────────────────────────────────────────
    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = (array) iterator_to_array($db->query('SELECT COUNT(*) AS total, SUM(prd_status=1) AS active, SUM(prd_status=0) AS inactive FROM melis_ecom_product', []))[0];
            return $this->jsonResponse(['success' => true, 'data' => [
                'total' => (int) ($row['total'] ?? 0), 'active' => (int) ($row['active'] ?? 0), 'inactive' => (int) ($row['inactive'] ?? 0),
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/options ────────────────────────────────────────────────
    public function optionsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();
            $categories = [];
            foreach ($db->query('SELECT c.cat_id, t.catt_name FROM melis_ecom_category c LEFT JOIN melis_ecom_category_trans t ON t.catt_category_id = c.cat_id AND t.catt_lang_id = ? WHERE c.cat_father_cat_id <> -1 ORDER BY t.catt_name', [$lang]) as $r) {
                $r = (array) $r; $categories[] = ['id' => (int) $r['cat_id'], 'name' => (string) ($r['catt_name'] ?? ('#' . $r['cat_id']))];
            }
            $languages = [];
            foreach ($db->query('SELECT elang_id, elang_name, elang_flag FROM melis_ecom_lang WHERE elang_status = 1 ORDER BY elang_id', []) as $r) {
                $r = (array) $r; $languages[] = ['id' => (int) $r['elang_id'], 'name' => (string) $r['elang_name'], 'flag' => (string) ($r['elang_flag'] ?? '')];
            }
            $countries = [];
            foreach ($db->query('SELECT ctry_id, ctry_name, ctry_flag FROM melis_ecom_country WHERE ctry_status = 1 ORDER BY ctry_name', []) as $r) {
                $r = (array) $r; $countries[] = ['id' => (int) $r['ctry_id'], 'name' => (string) $r['ctry_name'], 'flag' => (string) ($r['ctry_flag'] ?? '')];
            }
            $currencies = [];
            foreach ($db->query('SELECT cur_id, cur_code, cur_symbol, cur_name FROM melis_ecom_currency ORDER BY cur_default DESC, cur_code', []) as $r) {
                $r = (array) $r; $currencies[] = ['id' => (int) $r['cur_id'], 'name' => trim(((string) $r['cur_code']) . ' ' . ((string) ($r['cur_symbol'] ?? '')))];
            }
            $attributes = [];
            foreach ($db->query("SELECT a.attr_id, COALESCE(NULLIF(tr.atrans_name,''), a.attr_reference) AS name FROM melis_ecom_attribute a LEFT JOIN melis_ecom_attribute_trans tr ON tr.atrans_attribute_id = a.attr_id AND tr.atrans_lang_id = ? ORDER BY name", [$lang]) as $r) {
                $r = (array) $r; $attributes[] = ['id' => (int) $r['attr_id'], 'name' => (string) ($r['name'] ?? ('#' . $r['attr_id']))];
            }
            $users = [];
            foreach ($db->query('SELECT usr_id, usr_login, usr_email, usr_firstname, usr_lastname FROM melis_core_user WHERE usr_status = 1 ORDER BY usr_login', []) as $r) {
                $r = (array) $r;
                $nm = trim(((string) ($r['usr_firstname'] ?? '')) . ' ' . ((string) ($r['usr_lastname'] ?? ''))) ?: (string) ($r['usr_login'] ?? '');
                $email = (string) ($r['usr_email'] ?? '');
                $users[] = ['id' => (int) $r['usr_id'], 'name' => $nm . ($email ? " ($email)" : ''), 'email' => $email];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['categories' => $categories, 'languages' => $languages, 'countries' => $countries, 'currencies' => $currencies, 'attributes' => $attributes, 'users' => $users]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/:id ────────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();
            $ttId = $this->titleTypeId($db);

            $rows = iterator_to_array($db->query('SELECT prd_id, prd_reference, prd_status, prd_stock_low, prd_date_creation, prd_date_edit FROM melis_ecom_product WHERE prd_id = ? LIMIT 1', [$id]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Product not found'], 404); }
            $p = (array) $rows[0];

            // Textes TITLE par langue
            $texts = [];
            foreach ($db->query('SELECT ptxt_lang_id, ptxt_field_short, ptxt_field_long FROM melis_ecom_product_text WHERE ptxt_prd_id = ? AND ptxt_type = ?', [$id, $ttId]) as $r) {
                $r = (array) $r; $texts[(int) $r['ptxt_lang_id']] = ['name' => (string) ($r['ptxt_field_short'] ?? ''), 'description' => (string) ($r['ptxt_field_long'] ?? '')];
            }
            $seoRows = [];
            foreach ($db->query('SELECT eseo_lang_id, eseo_page_id, eseo_url, eseo_url_redirect, eseo_url_301, eseo_meta_title, eseo_meta_description FROM melis_ecom_seo WHERE eseo_product_id = ?', [$id]) as $r) {
                $r = (array) $r; $seoRows[(int) $r['eseo_lang_id']] = ['pageId' => (string) ($r['eseo_page_id'] ?? ''), 'url' => (string) ($r['eseo_url'] ?? ''), 'urlRedirect' => (string) ($r['eseo_url_redirect'] ?? ''), 'url301' => (string) ($r['eseo_url_301'] ?? ''), 'metaTitle' => (string) ($r['eseo_meta_title'] ?? ''), 'metaDescription' => (string) ($r['eseo_meta_description'] ?? '')];
            }
            $textList = []; $seoList = [];
            foreach ($db->query('SELECT elang_id, elang_name FROM melis_ecom_lang WHERE elang_status = 1 ORDER BY elang_id', []) as $r) {
                $r = (array) $r; $lid = (int) $r['elang_id'];
                $textList[] = ['langId' => $lid, 'langName' => (string) $r['elang_name'], 'name' => $texts[$lid]['name'] ?? '', 'description' => $texts[$lid]['description'] ?? ''];
                $seoList[]  = ['langId' => $lid, 'langName' => (string) $r['elang_name'], 'pageId' => $seoRows[$lid]['pageId'] ?? '', 'url' => $seoRows[$lid]['url'] ?? '', 'urlRedirect' => $seoRows[$lid]['urlRedirect'] ?? '', 'url301' => $seoRows[$lid]['url301'] ?? '', 'metaTitle' => $seoRows[$lid]['metaTitle'] ?? '', 'metaDescription' => $seoRows[$lid]['metaDescription'] ?? ''];
            }

            $categoryIds = [];
            foreach ($db->query('SELECT pcat_cat_id FROM melis_ecom_product_category WHERE pcat_prd_id = ? ORDER BY pcat_order', [$id]) as $r) { $categoryIds[] = (int) ((array) $r)['pcat_cat_id']; }

            $variants = $this->variantList($db, $id);

            // Page associations (melis_ecom_product_links : une ligne, 3 liens).
            $linkRows = iterator_to_array($db->query('SELECT plink_link_1, plink_link_2, plink_link_3 FROM melis_ecom_product_links WHERE plink_product_id = ? LIMIT 1', [$id]));
            $pl = $linkRows ? (array) $linkRows[0] : [];

            $data = $this->formatProduct($p, $db, $lang);
            $data['stockLow']    = isset($p['prd_stock_low']) && $p['prd_stock_low'] !== null ? (int) $p['prd_stock_low'] : null;
            $data['texts']       = $textList;
            $data['seo']         = $seoList;
            $data['categoryIds'] = $categoryIds;
            $data['variants']    = $variants;
            $data['pageLinks']   = ['link1' => (string) ($pl['plink_link_1'] ?? ''), 'link2' => (string) ($pl['plink_link_2'] ?? ''), 'link3' => (string) ($pl['plink_link_3'] ?? '')];

            return $this->jsonResponse(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/:id/variants ───────────────────────────────────────────
    public function variantsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $this->variantList($db, $id)]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    private function variantList($db, int $prdId): array
    {
        $lang = $this->langId();
        $valName = "COALESCE(NULLIF(tr.avt_v_varchar,''), NULLIF(tr.avt_v_text,''), av.atval_reference)";
        $items = [];
        foreach ($db->query("SELECT v.var_id, v.var_sku, v.var_status, v.var_main_variant, v.var_date_creation,
                (SELECT d.doc_path FROM melis_ecom_doc_relations dr JOIN melis_ecom_document d ON d.doc_id = dr.rdoc_doc_id WHERE dr.rdoc_variant_id = v.var_id AND d.doc_type_id <> 2 LIMIT 1) AS image,
                (SELECT GROUP_CONCAT($valName SEPARATOR ', ') FROM melis_ecom_variant_attribute_value vatv
                  JOIN melis_ecom_attribute_value av ON av.atval_id = vatv.vatv_attribute_value_id
                  LEFT JOIN melis_ecom_attribute_value_trans tr ON tr.av_attribute_value_id = av.atval_id AND tr.avt_lang_id = ?
                  WHERE vatv.vatv_variant_id = v.var_id) AS attrs
            FROM melis_ecom_variant v WHERE v.var_prd_id = ? ORDER BY v.var_main_variant DESC, v.var_id", [$lang, $prdId]) as $r) {
            $r = (array) $r;
            $items[] = [
                'id' => (int) $r['var_id'], 'sku' => (string) ($r['var_sku'] ?? ''), 'status' => (int) $r['var_status'], 'isMain' => (int) ($r['var_main_variant'] ?? 0),
                'image' => isset($r['image']) && $r['image'] !== null ? (string) $r['image'] : '', 'attributes' => (string) ($r['attrs'] ?? ''),
                'dateCreation' => $r['var_date_creation'] ?? null,
            ];
        }
        return $items;
    }

    // ─── POST /products/:id/variants/save ─────────────────────────────────────
    public function variantSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        if ($prdId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $varId  = (int) ($body['variantId'] ?? 0);
            $sku    = trim((string) ($body['sku'] ?? ''));
            $status = (int) (!empty($body['status']) ? 1 : 0);
            $isMain = (int) (!empty($body['isMain']) ? 1 : 0);
            if ($sku === '') { return $this->jsonResponse(['success' => false, 'error' => 'Le SKU est obligatoire.'], 400); }

            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $now = date('Y-m-d H:i:s');
            $uid = $this->currentUserId();

            // Une seule variante principale par produit.
            if ($isMain) { $db->query('UPDATE melis_ecom_variant SET var_main_variant = 0 WHERE var_prd_id = ?', [$prdId]); }

            $variant = ['var_prd_id' => $prdId, 'var_sku' => $sku, 'var_status' => $status, 'var_main_variant' => $isMain, 'var_date_edit' => $now, 'var_user_id_edit' => $uid];
            if ($varId <= 0) { $variant['var_date_creation'] = $now; $variant['var_user_id_creation'] = $uid; }

            ob_start();
            $newId = $this->getServiceManager()->get('MelisComVariantService')->saveVariant($variant, [], [], [], [], $varId ?: null);
            ob_end_clean();

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], $varId ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/:id/variants/:variantId ────────────────────────────────
    public function variantGetAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        if ($varId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid variant'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT var_id, var_sku, var_status, var_main_variant FROM melis_ecom_variant WHERE var_id = ? LIMIT 1', [$varId]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Variant not found'], 404); }
            $r = (array) $rows[0];
            // SEO par langue (champs complets : Page ID, meta, URL, redirection, 301)
            $seoRows = [];
            foreach ($db->query('SELECT eseo_lang_id, eseo_page_id, eseo_url, eseo_url_redirect, eseo_url_301, eseo_meta_title, eseo_meta_description FROM melis_ecom_seo WHERE eseo_variant_id = ?', [$varId]) as $s) {
                $s = (array) $s;
                $seoRows[(int) $s['eseo_lang_id']] = ['pageId' => (string) ($s['eseo_page_id'] ?? ''), 'url' => (string) ($s['eseo_url'] ?? ''), 'urlRedirect' => (string) ($s['eseo_url_redirect'] ?? ''), 'url301' => (string) ($s['eseo_url_301'] ?? ''), 'metaTitle' => (string) ($s['eseo_meta_title'] ?? ''), 'metaDescription' => (string) ($s['eseo_meta_description'] ?? '')];
            }
            $seoList = [];
            foreach ($db->query('SELECT elang_id, elang_name FROM melis_ecom_lang WHERE elang_status = 1 ORDER BY elang_id', []) as $l) {
                $l = (array) $l; $lid = (int) $l['elang_id']; $e = $seoRows[$lid] ?? [];
                $seoList[] = ['langId' => $lid, 'langName' => (string) $l['elang_name'], 'pageId' => $e['pageId'] ?? '', 'url' => $e['url'] ?? '', 'urlRedirect' => $e['urlRedirect'] ?? '', 'url301' => $e['url301'] ?? '', 'metaTitle' => $e['metaTitle'] ?? '', 'metaDescription' => $e['metaDescription'] ?? ''];
            }
            return $this->jsonResponse(['success' => true, 'data' => [
                'id' => (int) $r['var_id'], 'sku' => (string) ($r['var_sku'] ?? ''), 'status' => (int) $r['var_status'], 'isMain' => (int) ($r['var_main_variant'] ?? 0), 'seo' => $seoList,
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── Variant prices (price_var_id) ────────────────────────────────────────
    public function variantPricesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $items = [];
            foreach ($db->query('SELECT price_id, price_country_id, price_currency, price_net, price_gross, price_vat_percent, price_vat_price, price_other_tax_price FROM melis_ecom_price WHERE price_var_id = ? ORDER BY price_id', [$varId]) as $r) {
                $r = (array) $r;
                $items[] = ['id' => (int) $r['price_id'], 'countryId' => (int) ($r['price_country_id'] ?? 0), 'currency' => (int) ($r['price_currency'] ?? 0),
                    'net' => $r['price_net'] !== null ? (float) $r['price_net'] : null, 'gross' => $r['price_gross'] !== null ? (float) $r['price_gross'] : null, 'vatPercent' => $r['price_vat_percent'] !== null ? (float) $r['price_vat_percent'] : null,
                    'vatPrice' => $r['price_vat_price'] !== null ? (float) $r['price_vat_price'] : null, 'otherTax' => $r['price_other_tax_price'] !== null ? (float) $r['price_other_tax_price'] : null];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    public function variantPriceSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $priceId = (int) ($body['id'] ?? 0);
            $num = static fn($v) => ($v === '' || $v === null) ? null : (float) $v;
            $vals = ['price_prd_id' => $prdId, 'price_var_id' => $varId, 'price_country_id' => (int) ($body['countryId'] ?? 0), 'price_currency' => (int) ($body['currency'] ?? 0), 'price_group_id' => 1,
                'price_net' => $num($body['net'] ?? null), 'price_gross' => $num($body['gross'] ?? null), 'price_vat_percent' => $num($body['vatPercent'] ?? null),
                'price_vat_price' => $num($body['vatPrice'] ?? null), 'price_other_tax_price' => $num($body['otherTax'] ?? null)];
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            if ($priceId > 0) {
                $set = implode(', ', array_map(fn($c) => "$c = ?", array_keys($vals)));
                $db->query("UPDATE melis_ecom_price SET $set WHERE price_id = ?", array_merge(array_values($vals), [$priceId]));
            } else {
                $cols = implode(', ', array_keys($vals)); $ph = implode(', ', array_fill(0, count($vals), '?'));
                $db->query("INSERT INTO melis_ecom_price ($cols) VALUES ($ph)", array_values($vals));
            }
            return $this->jsonResponse(['success' => true, 'data' => null], $priceId ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    public function variantPriceDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $priceId = (int) $this->params()->fromRoute('priceId', 0);
        try { $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface')->query('DELETE FROM melis_ecom_price WHERE price_id = ?', [$priceId]); return $this->jsonResponse(['success' => true, 'data' => null]); }
        catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── Variant stocks (melis_ecom_variant_stock) ────────────────────────────
    public function variantStocksAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $items = [];
            foreach ($db->query('SELECT stock_id, stock_country_id, stock_quantity, stock_low, stock_next_fill_up FROM melis_ecom_variant_stock WHERE stock_var_id = ? ORDER BY stock_id', [$varId]) as $r) {
                $r = (array) $r;
                $items[] = ['id' => (int) $r['stock_id'], 'countryId' => (int) ($r['stock_country_id'] ?? 0), 'quantity' => (int) ($r['stock_quantity'] ?? 0), 'low' => $r['stock_low'] !== null ? (int) $r['stock_low'] : null, 'nextFillUp' => $r['stock_next_fill_up'] ?? null];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    public function variantStockSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $stockId = (int) ($body['id'] ?? 0);
            $nextFill = trim((string) ($body['nextFillUp'] ?? '')); $nextFill = $nextFill !== '' && strtotime($nextFill) ? date('Y-m-d H:i:s', strtotime($nextFill)) : null;
            $vals = ['stock_var_id' => $varId, 'stock_country_id' => (int) ($body['countryId'] ?? 0), 'stock_quantity' => (int) ($body['quantity'] ?? 0), 'stock_low' => ($body['low'] ?? '') === '' ? null : (int) $body['low'], 'stock_next_fill_up' => $nextFill];
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            if ($stockId > 0) {
                $set = implode(', ', array_map(fn($c) => "$c = ?", array_keys($vals)));
                $db->query("UPDATE melis_ecom_variant_stock SET $set WHERE stock_id = ?", array_merge(array_values($vals), [$stockId]));
            } else {
                $cols = implode(', ', array_keys($vals)); $ph = implode(', ', array_fill(0, count($vals), '?'));
                $db->query("INSERT INTO melis_ecom_variant_stock ($cols) VALUES ($ph)", array_values($vals));
            }
            return $this->jsonResponse(['success' => true, 'data' => null], $stockId ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    public function variantStockDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $stockId = (int) $this->params()->fromRoute('stockId', 0);
        try { $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface')->query('DELETE FROM melis_ecom_variant_stock WHERE stock_id = ?', [$stockId]); return $this->jsonResponse(['success' => true, 'data' => null]); }
        catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /products/:id/variants/:variantId/seo/save ──────────────────────
    public function variantSeoSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $items = is_array($body['items'] ?? null) ? $body['items'] : [];
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $existing = [];
            foreach ($db->query('SELECT eseo_id, eseo_lang_id FROM melis_ecom_seo WHERE eseo_variant_id = ?', [$varId]) as $r) { $r = (array) $r; $existing[(int) $r['eseo_lang_id']] = (int) $r['eseo_id']; }
            $seoData = [];
            foreach ($items as $s) {
                if (!is_array($s)) { continue; }
                $lid = (int) ($s['langId'] ?? 0); if ($lid <= 0) { continue; }
                $seoData[] = ['eseo_id' => $existing[$lid] ?? null, 'eseo_lang_id' => $lid,
                    'eseo_page_id' => ($s['pageId'] ?? '') === '' ? null : (int) $s['pageId'],
                    'eseo_url' => trim((string) ($s['url'] ?? '')), 'eseo_url_redirect' => trim((string) ($s['urlRedirect'] ?? '')), 'eseo_url_301' => trim((string) ($s['url301'] ?? '')),
                    'eseo_meta_title' => trim((string) ($s['metaTitle'] ?? '')), 'eseo_meta_description' => trim((string) ($s['metaDescription'] ?? ''))];
            }
            ob_start(); $this->getServiceManager()->get('MelisComSeoService')->saveSeoDataAction('variant', $varId, $seoData); ob_end_clean();
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /products/:id/variants/:variantId ─────────────────────────────
    public function variantDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        if (strtoupper($this->getRequest()->getMethod()) !== 'DELETE') { return $this->jsonResponse(['success' => false, 'error' => 'Method not allowed'], 405); }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        if ($varId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid variant'], 400); }
        try {
            ob_start();
            $this->getServiceManager()->get('MelisComVariantService')->deleteVariantById($varId);
            ob_end_clean();
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/:id/prices ─────────────────────────────────────────────
    public function pricesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        if ($prdId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $items = [];
            foreach ($db->query('SELECT price_id, price_country_id, price_currency, price_net, price_gross, price_vat_percent, price_vat_price, price_other_tax_price FROM melis_ecom_price WHERE price_prd_id = ? AND (price_var_id = 0 OR price_var_id IS NULL) ORDER BY price_id', [$prdId]) as $r) {
                $r = (array) $r;
                $items[] = [
                    'id' => (int) $r['price_id'], 'countryId' => (int) ($r['price_country_id'] ?? 0), 'currency' => (int) ($r['price_currency'] ?? 0),
                    'net' => $r['price_net'] !== null ? (float) $r['price_net'] : null, 'gross' => $r['price_gross'] !== null ? (float) $r['price_gross'] : null,
                    'vatPercent' => $r['price_vat_percent'] !== null ? (float) $r['price_vat_percent'] : null,
                    'vatPrice' => $r['price_vat_price'] !== null ? (float) $r['price_vat_price'] : null, 'otherTax' => $r['price_other_tax_price'] !== null ? (float) $r['price_other_tax_price'] : null,
                ];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /products/:id/prices/save ───────────────────────────────────────
    public function priceSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        if ($prdId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $priceId = (int) ($body['id'] ?? 0);
            $num = static fn($v) => ($v === '' || $v === null) ? null : (float) $v;

            $price = [
                'price_prd_id'     => $prdId,
                'price_var_id'     => 0,
                'price_country_id' => (int) ($body['countryId'] ?? 0),
                'price_currency'   => (int) ($body['currency'] ?? 0),
                'price_group_id'   => (int) ($body['groupId'] ?? 1) ?: 1,
                'price_net'        => $num($body['net'] ?? null),
                'price_gross'      => $num($body['gross'] ?? null),
                'price_vat_percent'=> $num($body['vatPercent'] ?? null),
                'price_vat_price'  => $num($body['vatPrice'] ?? null),
                'price_other_tax_price' => $num($body['otherTax'] ?? null),
            ];
            ob_start();
            $this->getServiceManager()->get('MelisComProductService')->saveProductPrices($price, $priceId ?: null);
            ob_end_clean();
            return $this->jsonResponse(['success' => true, 'data' => null], $priceId ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/:id/attributes ─────────────────────────────────────────
    public function attributesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        if ($prdId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();
            $items = [];
            foreach ($db->query("SELECT pa.patt_id, pa.patt_attribute_id, COALESCE(NULLIF(tr.atrans_name,''), a.attr_reference) AS name
                FROM melis_ecom_product_attribute pa
                JOIN melis_ecom_attribute a ON a.attr_id = pa.patt_attribute_id
                LEFT JOIN melis_ecom_attribute_trans tr ON tr.atrans_attribute_id = a.attr_id AND tr.atrans_lang_id = ?
                WHERE pa.patt_product_id = ? ORDER BY name", [$lang, $prdId]) as $r) {
                $r = (array) $r;
                $items[] = ['pattId' => (int) $r['patt_id'], 'attributeId' => (int) $r['patt_attribute_id'], 'name' => (string) ($r['name'] ?? ('#' . $r['patt_attribute_id']))];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /products/:id/attributes/save ───────────────────────────────────
    public function attributeSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        if ($prdId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $attrId = (int) ($body['attributeId'] ?? 0);
            if ($attrId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid attribute'], 400); }
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $dup = iterator_to_array($db->query('SELECT patt_id FROM melis_ecom_product_attribute WHERE patt_product_id = ? AND patt_attribute_id = ? LIMIT 1', [$prdId, $attrId]));
            if (!$dup) { $db->query('INSERT INTO melis_ecom_product_attribute (patt_product_id, patt_attribute_id) VALUES (?, ?)', [$prdId, $attrId]); }
            return $this->jsonResponse(['success' => true, 'data' => null], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /products/:id/attributes/:pattId ──────────────────────────────
    public function attributeDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $pattId = (int) $this->params()->fromRoute('pattId', 0);
        if ($pattId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid'], 400); }
        try {
            $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface')->query('DELETE FROM melis_ecom_product_attribute WHERE patt_id = ?', [$pattId]);
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/page-tree ──────────────────────────────────────────────
    // Arbre des pages CMS (melis_cms_page_tree) pour le sélecteur « Association de page ».
    public function pageTreeAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = [];
            foreach ($db->query("SELECT t.tree_page_id AS id, t.tree_father_page_id AS father, t.tree_page_order AS ord,
                    COALESCE((SELECT page_name FROM melis_cms_page_published WHERE page_id = t.tree_page_id LIMIT 1),
                             (SELECT page_name FROM melis_cms_page_saved WHERE page_id = t.tree_page_id LIMIT 1)) AS name,
                    COALESCE((SELECT page_type FROM melis_cms_page_published WHERE page_id = t.tree_page_id LIMIT 1),
                             (SELECT page_type FROM melis_cms_page_saved WHERE page_id = t.tree_page_id LIMIT 1)) AS type
                FROM melis_cms_page_tree t ORDER BY t.tree_page_order", []) as $r) {
                $r = (array) $r;
                $rows[] = ['id' => (int) $r['id'], 'father' => (int) $r['father'], 'name' => (string) ($r['name'] ?? ('#' . $r['id'])), 'type' => (string) ($r['type'] ?? 'PAGE'), 'children' => []];
            }
            $byFather = [];
            foreach ($rows as $row) { $byFather[$row['father']][] = $row; }
            $build = function (int $f) use (&$build, $byFather): array {
                $out = [];
                foreach ($byFather[$f] ?? [] as $n) { $n['children'] = $build($n['id']); $out[] = $n; }
                return $out;
            };
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $build(-1)]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/:id/alert ──────────────────────────────────────────────
    public function alertAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        if ($prdId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $items = [];
            foreach ($db->query('SELECT sea.sea_id, sea.sea_user_id, sea.sea_email, u.usr_login, u.usr_firstname, u.usr_lastname
                FROM melis_ecom_stock_email_alert sea LEFT JOIN melis_core_user u ON u.usr_id = sea.sea_user_id
                WHERE sea.sea_prd_id = ? ORDER BY sea.sea_id', [$prdId]) as $r) {
                $r = (array) $r;
                $nm = trim(((string) ($r['usr_firstname'] ?? '')) . ' ' . ((string) ($r['usr_lastname'] ?? ''))) ?: (string) ($r['usr_login'] ?? '');
                $items[] = ['seaId' => (int) $r['sea_id'], 'userId' => (int) ($r['sea_user_id'] ?? 0), 'email' => (string) ($r['sea_email'] ?? ''), 'name' => $nm ?: (string) ($r['sea_email'] ?? '')];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['recipients' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /products/:id/alert/recipients/save ─────────────────────────────
    public function recipientSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        if ($prdId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $userId = (int) ($body['userId'] ?? 0);
            $level  = ($body['stockLevel'] ?? '') === '' ? 0 : (int) $body['stockLevel'];
            if ($userId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid user'], 400); }
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $uRows = iterator_to_array($db->query('SELECT usr_email FROM melis_core_user WHERE usr_id = ? LIMIT 1', [$userId]));
            $email = $uRows ? (string) ((array) $uRows[0])['usr_email'] : '';
            $dup = iterator_to_array($db->query('SELECT sea_id FROM melis_ecom_stock_email_alert WHERE sea_prd_id = ? AND sea_user_id = ? LIMIT 1', [$prdId, $userId]));
            if (!$dup) { $db->query('INSERT INTO melis_ecom_stock_email_alert (sea_stock_level_alert, sea_email, sea_prd_id, sea_user_id) VALUES (?, ?, ?, ?)', [$level, $email, $prdId, $userId]); }
            return $this->jsonResponse(['success' => true, 'data' => null], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /products/:id/alert/recipients/:seaId ─────────────────────────
    public function recipientDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $seaId = (int) $this->params()->fromRoute('seaId', 0);
        if ($seaId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid'], 400); }
        try {
            $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface')->query('DELETE FROM melis_ecom_stock_email_alert WHERE sea_id = ?', [$seaId]);
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    /** Nom lisible d'une variante : "Produit (SKU)" — nom produit via texte TITLE. */
    private function variantLabelSql(): string
    {
        return "(SELECT pt.ptxt_field_short FROM melis_ecom_product_text pt WHERE pt.ptxt_prd_id = v.var_prd_id AND pt.ptxt_field_short <> '' LIMIT 1)";
    }

    // ─── GET /products/:id/variants/:variantId/assoc ──────────────────────────
    public function variantAssocAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        if ($varId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid variant'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lbl = $this->variantLabelSql();
            $assoc = [];
            foreach ($db->query("SELECT a.avar_id, v.var_id, v.var_sku, v.var_status, $lbl AS pname
                FROM melis_ecom_assoc_variant a JOIN melis_ecom_variant v ON v.var_id = a.avar_two
                WHERE a.avar_one = ? ORDER BY a.avar_id", [$varId]) as $r) {
                $r = (array) $r; $assoc[] = ['avarId' => (int) $r['avar_id'], 'variantId' => (int) $r['var_id'], 'sku' => (string) ($r['var_sku'] ?? ''), 'status' => (int) $r['var_status'], 'productName' => (string) ($r['pname'] ?? ('#' . $r['var_id']))];
            }
            $available = [];
            foreach ($db->query("SELECT v.var_id, v.var_sku, v.var_status, $lbl AS pname FROM melis_ecom_variant v
                WHERE v.var_id <> ? AND v.var_id NOT IN (SELECT avar_two FROM melis_ecom_assoc_variant WHERE avar_one = ?) ORDER BY v.var_id", [$varId, $varId]) as $r) {
                $r = (array) $r; $available[] = ['variantId' => (int) $r['var_id'], 'sku' => (string) ($r['var_sku'] ?? ''), 'status' => (int) $r['var_status'], 'productName' => (string) ($r['pname'] ?? ('#' . $r['var_id']))];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['associated' => $assoc, 'available' => $available]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    public function variantAssocSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $other = (int) ($body['variantId'] ?? 0);
            if ($other <= 0 || $other === $varId) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid variant'], 400); }
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $typeRow = iterator_to_array($db->query('SELECT avart_id FROM melis_ecom_assoc_variants_type LIMIT 1', []));
            $typeId = $typeRow ? (int) ((array) $typeRow[0])['avart_id'] : 0;
            $dup = iterator_to_array($db->query('SELECT avar_id FROM melis_ecom_assoc_variant WHERE avar_one = ? AND avar_two = ? LIMIT 1', [$varId, $other]));
            if (!$dup) { $db->query('INSERT INTO melis_ecom_assoc_variant (avar_one, avar_two, avar_type_id) VALUES (?, ?, ?)', [$varId, $other, $typeId]); }
            return $this->jsonResponse(['success' => true, 'data' => null], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    public function variantAssocDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $avarId = (int) $this->params()->fromRoute('avarId', 0);
        try { $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface')->query('DELETE FROM melis_ecom_assoc_variant WHERE avar_id = ?', [$avarId]); return $this->jsonResponse(['success' => true, 'data' => null]); }
        catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── Variant attribute values (melis_ecom_variant_attribute_value) ────────
    public function variantAttributesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();
            $valName = "COALESCE(NULLIF(tr.avt_v_varchar,''), NULLIF(tr.avt_v_text,''), av.atval_reference)";
            $items = [];
            foreach ($db->query("SELECT vatv.vatv_id, vatv.vatv_attribute_value_id AS vid, $valName AS name
                FROM melis_ecom_variant_attribute_value vatv
                JOIN melis_ecom_attribute_value av ON av.atval_id = vatv.vatv_attribute_value_id
                LEFT JOIN melis_ecom_attribute_value_trans tr ON tr.av_attribute_value_id = av.atval_id AND tr.avt_lang_id = ?
                WHERE vatv.vatv_variant_id = ?", [$lang, $varId]) as $r) {
                $r = (array) $r; $items[] = ['vatvId' => (int) $r['vatv_id'], 'valueId' => (int) $r['vid'], 'name' => (string) ($r['name'] ?? ('#' . $r['vid']))];
            }
            $available = [];
            foreach ($db->query("SELECT av.atval_id, $valName AS name FROM melis_ecom_attribute_value av
                LEFT JOIN melis_ecom_attribute_value_trans tr ON tr.av_attribute_value_id = av.atval_id AND tr.avt_lang_id = ?
                WHERE av.atval_id NOT IN (SELECT vatv_attribute_value_id FROM melis_ecom_variant_attribute_value WHERE vatv_variant_id = ?) ORDER BY name", [$lang, $varId]) as $r) {
                $r = (array) $r; $available[] = ['id' => (int) $r['atval_id'], 'name' => (string) ($r['name'] ?? ('#' . $r['atval_id']))];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'available' => $available]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    public function variantAttributeSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $valueId = (int) ($body['valueId'] ?? 0);
            if ($valueId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid value'], 400); }
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $dup = iterator_to_array($db->query('SELECT vatv_id FROM melis_ecom_variant_attribute_value WHERE vatv_variant_id = ? AND vatv_attribute_value_id = ? LIMIT 1', [$varId, $valueId]));
            if (!$dup) { $db->query('INSERT INTO melis_ecom_variant_attribute_value (vatv_variant_id, vatv_attribute_value_id) VALUES (?, ?)', [$varId, $valueId]); }
            return $this->jsonResponse(['success' => true, 'data' => null], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    public function variantAttributeDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $vatvId = (int) $this->params()->fromRoute('vatvId', 0);
        try { $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface')->query('DELETE FROM melis_ecom_variant_attribute_value WHERE vatv_id = ?', [$vatvId]); return $this->jsonResponse(['success' => true, 'data' => null]); }
        catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/:id/variants/:variantId/media ──────────────────────────
    public function variantMediaAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        if ($varId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid variant'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $images = []; $files = [];
            foreach ($db->query('SELECT d.doc_id, d.doc_name, d.doc_path, d.doc_type_id FROM melis_ecom_doc_relations dr JOIN melis_ecom_document d ON d.doc_id = dr.rdoc_doc_id WHERE dr.rdoc_variant_id = ? ORDER BY d.doc_id', [$varId]) as $r) {
                $r = (array) $r; $entry = ['id' => (int) $r['doc_id'], 'name' => (string) ($r['doc_name'] ?? ''), 'path' => (string) ($r['doc_path'] ?? '')];
                if ((int) $r['doc_type_id'] === 2) { $files[] = $entry; } else { $images[] = $entry; }
            }
            return $this->jsonResponse(['success' => true, 'data' => ['images' => $images, 'files' => $files]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    /**
     * Stocke un fichier uploadé (base64) sous public/media/commerce/<prdId>/ et crée le
     * document + la relation (produit ou variante). Renvoie l'id du document.
     */
    private function storeUpload($db, int $prdId, array $body, string $relCol, int $relId): int
    {
        $name      = basename(trim((string) ($body['name'] ?? 'file')));
        $kind      = ((string) ($body['kind'] ?? 'file')) === 'image' ? 'image' : 'file';
        $subtypeId = ($body['typeId'] ?? null) !== null ? (int) $body['typeId'] : null;
        $countryId = ($body['countryId'] ?? null) !== null ? (int) $body['countryId'] : -1;
        $data      = (string) ($body['data'] ?? '');
        if (($p = strpos($data, ',')) !== false && strpos($data, ';base64') !== false) { $data = substr($data, $p + 1); }
        $bytes = base64_decode($data, true);
        if ($bytes === false || $bytes === '') { throw new \RuntimeException('Fichier invalide.'); }

        $docroot = (string) ($_SERVER['DOCUMENT_ROOT'] ?? '');
        if ($docroot === '' || !is_dir($docroot)) { $docroot = getcwd() ?: '/var/www/melis/public'; }
        $dir = rtrim($docroot, '/') . '/media/commerce/' . $prdId;
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) { throw new \RuntimeException('Dossier média inaccessible.'); }

        $safe  = preg_replace('/[^A-Za-z0-9._-]/', '_', $name) ?: 'file';
        $fname = substr(md5(uniqid('', true)), 0, 8) . '_' . $safe;
        if (file_put_contents($dir . '/' . $fname, $bytes) === false) { throw new \RuntimeException('Écriture du fichier impossible.'); }

        $path   = '/media/commerce/' . $prdId . '/' . $fname;
        // Images: doc_type_id=1 (IMG), doc_subtype_id = chosen subtype; Files: doc_type_id = chosen file type
        if ($kind === 'image') {
            $db->query('INSERT INTO melis_ecom_document (doc_name, doc_path, doc_type_id, doc_subtype_id) VALUES (?, ?, 1, ?)', [$name, $path, $subtypeId]);
        } else {
            $fileTypeId = $subtypeId ?? 2;
            $db->query('INSERT INTO melis_ecom_document (doc_name, doc_path, doc_type_id) VALUES (?, ?, ?)', [$name, $path, $fileTypeId]);
        }
        $docId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];
        $db->query("INSERT INTO melis_ecom_doc_relations (rdoc_doc_id, $relCol, rdoc_country_id) VALUES (?, ?, ?)", [$docId, $relId, $countryId]);
        return $docId;
    }

    /**
     * Copies media (images and/or files) from a source product/variant to a new one.
     * Works around the broken dir-path bug in MelisComDuplicationService::duplicateDocuments.
     */
    private function copyMedia($db, string $relCol, int $srcRelId, int $srcDirId, int $dstDirId, bool $images, bool $files, ?int $dstRelId = null): void
    {
        if (!$images && !$files) { return; }
        if ($dstRelId === null) { $dstRelId = $dstDirId; }

        $docroot = (string) ($_SERVER['DOCUMENT_ROOT'] ?? '') ?: (getcwd() ?: '/var/www/melis/public');
        $srcDir  = rtrim($docroot, '/') . '/media/commerce/' . $srcDirId;
        $dstDir  = rtrim($docroot, '/') . '/media/commerce/' . $dstDirId;
        if (!is_dir($dstDir) && !@mkdir($dstDir, 0775, true) && !is_dir($dstDir)) { return; }

        $docs = iterator_to_array($db->query(
            "SELECT d.doc_id, d.doc_name, d.doc_path, d.doc_type_id, dr.rdoc_country_id
             FROM melis_ecom_doc_relations dr
             JOIN melis_ecom_document d ON d.doc_id = dr.rdoc_doc_id
             WHERE dr.$relCol = ?
             ORDER BY d.doc_id", [$srcRelId]
        ));

        foreach ($docs as $row) {
            $row = (array) $row;
            $isImage = (int) $row['doc_type_id'] !== 2;
            if ($isImage && !$images) { continue; }
            if (!$isImage && !$files) { continue; }

            $srcPath = rtrim($docroot, '/') . '/' . ltrim((string) $row['doc_path'], '/');
            if (!is_file($srcPath)) { continue; }

            $fname   = substr(md5(uniqid('', true)), 0, 8) . '_' . basename((string) $row['doc_path']);
            $dstPath = $dstDir . '/' . $fname;
            if (!@copy($srcPath, $dstPath)) { continue; }

            $newDocPath = '/media/commerce/' . $dstDirId . '/' . $fname;
            $db->query('INSERT INTO melis_ecom_document (doc_name, doc_path, doc_type_id) VALUES (?, ?, ?)',
                [(string) $row['doc_name'], $newDocPath, (int) $row['doc_type_id']]);
            $newDocId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];
            $db->query("INSERT INTO melis_ecom_doc_relations (rdoc_doc_id, $relCol, rdoc_country_id) VALUES (?, ?, ?)",
                [$newDocId, $dstRelId, $row['rdoc_country_id']]);
        }
    }

    /** Supprime un document + sa relation (et le fichier sur disque si présent). */
    private function deleteDoc($db, int $docId): void
    {
        $rows = iterator_to_array($db->query('SELECT doc_path FROM melis_ecom_document WHERE doc_id = ? LIMIT 1', [$docId]));
        $db->query('DELETE FROM melis_ecom_doc_relations WHERE rdoc_doc_id = ?', [$docId]);
        $db->query('DELETE FROM melis_ecom_document WHERE doc_id = ?', [$docId]);
        if ($rows) {
            $path = (string) ((array) $rows[0])['doc_path'];
            $docroot = (string) ($_SERVER['DOCUMENT_ROOT'] ?? '') ?: (getcwd() ?: '/var/www/melis/public');
            $full = rtrim($docroot, '/') . '/' . ltrim($path, '/');
            if ($path !== '' && is_file($full)) { @unlink($full); }
        }
    }

    // ─── POST /products/:id/media/upload ──────────────────────────────────────
    public function mediaUploadAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        if ($prdId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $this->storeUpload($db, $prdId, $body, 'rdoc_product_id', $prdId);
            return $this->jsonResponse(['success' => true, 'data' => null], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    // ─── DELETE /products/:id/media/:docId ────────────────────────────────────
    public function mediaDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $docId = (int) $this->params()->fromRoute('docId', 0);
        if ($docId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid'], 400); }
        try { $this->deleteDoc($this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface'), $docId); return $this->jsonResponse(['success' => true, 'data' => null]); }
        catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    // ─── POST /products/:id/variants/:variantId/media/upload ──────────────────
    public function variantMediaUploadAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        if ($varId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid variant'], 400); }
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $this->storeUpload($db, $prdId ?: $varId, $body, 'rdoc_variant_id', $varId);
            return $this->jsonResponse(['success' => true, 'data' => null], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }
    public function variantMediaUpdateAction(): HttpResponse { return $this->mediaUpdateAction(); }
    public function variantMediaDeleteAction(): HttpResponse { return $this->mediaDeleteAction(); }

    // ─── POST /products/:id/variants/:variantId/duplicate ─────────────────────
    public function variantDuplicateAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        $varId = (int) $this->params()->fromRoute('variantId', 0);
        if ($varId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid variant'], 400); }
        try {
            $body      = json_decode($this->getRequest()->getContent(), true) ?? [];
            $dupImages = !empty($body['duplicateImages']);
            $dupDocs   = !empty($body['duplicateDocuments']);
            $status    = !empty($body['putOnline']) ? 1 : 0;
            $sku       = trim((string) ($body['sku'] ?? ''));

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT var_sku FROM melis_ecom_variant WHERE var_id = ? LIMIT 1', [$varId]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Not found'], 404); }
            if ($sku === '') { $sku = ((string) ((array) $rows[0])['var_sku']) . '-copy'; }

            // Duplicate without images/docs — service's duplicateDocuments has a broken dir path
            $variant = [['var_id' => $varId, 'var_sku' => $sku, 'var_main_variant' => 0]];
            ob_start();
            $newVarId = $this->getServiceManager()->get('MelisComDuplicationService')->duplicateVariant($variant, $status, false, false, $prdId);
            ob_end_clean();

            // Manually copy variant media (stored under product dir, indexed by rdoc_variant_id)
            if (($dupImages || $dupDocs) && is_numeric($newVarId) && (int) $newVarId > 0) {
                $this->copyMedia($db, 'rdoc_variant_id', $varId, $prdId, $prdId, $dupImages, $dupDocs, (int) $newVarId);
            }

            return $this->jsonResponse(['success' => true, 'data' => null], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/:id/media ──────────────────────────────────────────────
    public function mediaAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        if ($prdId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid product'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $images = []; $files = [];
            $sql = 'SELECT d.doc_id, d.doc_name, d.doc_path, d.doc_type_id, d.doc_subtype_id,
                           dr.rdoc_country_id,
                           t.dtype_name AS type_name, t.dtype_code AS type_code,
                           st.dtype_name AS subtype_name, st.dtype_code AS subtype_code
                    FROM melis_ecom_doc_relations dr
                    JOIN melis_ecom_document d ON d.doc_id = dr.rdoc_doc_id
                    LEFT JOIN melis_ecom_doc_type t ON t.dtype_id = d.doc_type_id
                    LEFT JOIN melis_ecom_doc_type st ON st.dtype_id = d.doc_subtype_id
                    WHERE dr.rdoc_product_id = ? ORDER BY d.doc_id';
            foreach ($db->query($sql, [$prdId]) as $r) {
                $r = (array) $r;
                $isFile = (int) $r['doc_type_id'] === 2 || ((int)$r['doc_type_id'] !== 1 && $r['type_code'] !== 'IMG');
                $entry = [
                    'id'         => (int) $r['doc_id'],
                    'name'       => (string) ($r['doc_name'] ?? ''),
                    'path'       => (string) ($r['doc_path'] ?? ''),
                    'typeId'     => $isFile ? (int) $r['doc_type_id'] : ($r['doc_subtype_id'] !== null ? (int) $r['doc_subtype_id'] : null),
                    'typeName'   => $isFile ? (string) ($r['type_name'] ?? '') : (string) ($r['subtype_name'] ?? ''),
                    'countryId'  => $r['rdoc_country_id'] !== null ? (int) $r['rdoc_country_id'] : -1,
                ];
                if ($isFile) { $files[] = $entry; } else { $images[] = $entry; }
            }
            return $this->jsonResponse(['success' => true, 'data' => ['images' => $images, 'files' => $files]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /products/:id/media/:docId/update ───────────────────────────────
    public function mediaUpdateAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $prdId = (int) $this->params()->fromRoute('id', 0);
        $docId = (int) $this->params()->fromRoute('docId', 0);
        if ($prdId <= 0 || $docId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid'], 400); }
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT doc_type_id FROM melis_ecom_document WHERE doc_id = ? LIMIT 1', [$docId]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Not found'], 404); }
            $isImage = (int) ((array) $rows[0])['doc_type_id'] === 1;

            $name      = trim((string) ($body['name'] ?? ''));
            $typeId    = ($body['typeId'] ?? null) !== null ? (int) $body['typeId'] : null;
            $countryId = ($body['countryId'] ?? null) !== null ? (int) $body['countryId'] : -1;

            if ($isImage) {
                $db->query('UPDATE melis_ecom_document SET doc_name = ?, doc_subtype_id = ? WHERE doc_id = ?', [$name, $typeId, $docId]);
            } else {
                if ($typeId !== null) {
                    $db->query('UPDATE melis_ecom_document SET doc_name = ?, doc_type_id = ? WHERE doc_id = ?', [$name, $typeId, $docId]);
                } else {
                    $db->query('UPDATE melis_ecom_document SET doc_name = ? WHERE doc_id = ?', [$name, $docId]);
                }
            }
            $db->query('UPDATE melis_ecom_doc_relations SET rdoc_country_id = ? WHERE rdoc_doc_id = ?', [$countryId, $docId]);

            // Replace file if new data provided
            if (!empty($body['data'])) {
                $data = (string) $body['data'];
                if (($p = strpos($data, ',')) !== false && strpos($data, ';base64') !== false) { $data = substr($data, $p + 1); }
                $bytes = base64_decode($data, true);
                if ($bytes !== false && $bytes !== '') {
                    $docroot = (string) ($_SERVER['DOCUMENT_ROOT'] ?? '');
                    if ($docroot === '' || !is_dir($docroot)) { $docroot = getcwd() ?: '/var/www/melis/public'; }
                    $dir = rtrim($docroot, '/') . '/media/commerce/' . $prdId;
                    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) { /* ignore dir error */ }
                    $safe  = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($name ?: 'file')) ?: 'file';
                    $fname = substr(md5(uniqid('', true)), 0, 8) . '_' . $safe;
                    if (file_put_contents($dir . '/' . $fname, $bytes) !== false) {
                        $path = '/media/commerce/' . $prdId . '/' . $fname;
                        $db->query('UPDATE melis_ecom_document SET doc_path = ? WHERE doc_id = ?', [$path, $docId]);
                    }
                }
            }
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /products/document-types ────────────────────────────────────────
    // Returns image subtypes (parent=1) and root file types (code != IMG, no parent).
    public function documentTypesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $imageTypes = []; $fileTypes = [];
            foreach ($db->query('SELECT dtype_id, dtype_code, dtype_name, dtype_parent_type_id FROM melis_ecom_doc_type ORDER BY dtype_id', []) as $r) {
                $r = (array) $r;
                $entry = ['id' => (int) $r['dtype_id'], 'code' => (string) $r['dtype_code'], 'name' => (string) $r['dtype_name']];
                if ((int) $r['dtype_parent_type_id'] === 1) { $imageTypes[] = $entry; }
                elseif ((string) $r['dtype_code'] !== 'IMG' && (int) $r['dtype_parent_type_id'] === 0) { $fileTypes[] = $entry; }
            }
            if ($this->getRequest()->isPost()) {
                $body = json_decode($this->getRequest()->getContent(), true) ?? [];
                $code = strtoupper(trim((string) ($body['code'] ?? '')));
                $name = trim((string) ($body['name'] ?? ''));
                $kind = (string) ($body['kind'] ?? 'file');
                if ($code === '' || $name === '') { return $this->jsonResponse(['success' => false, 'error' => 'code and name required'], 400); }
                $parentId = $kind === 'image' ? 1 : 0;
                $db->query('INSERT INTO melis_ecom_doc_type (dtype_code, dtype_name, dtype_parent_type_id) VALUES (?, ?, ?)', [$code, $name, $parentId]);
                return $this->jsonResponse(['success' => true, 'data' => null], 201);
            }
            return $this->jsonResponse(['success' => true, 'data' => ['imageTypes' => $imageTypes, 'fileTypes' => $fileTypes]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /products/:id/prices/:priceId ─────────────────────────────────
    public function priceDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $priceId = (int) $this->params()->fromRoute('priceId', 0);
        if ($priceId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid price'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $db->query('DELETE FROM melis_ecom_price WHERE price_id = ?', [$priceId]);
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /products/save ──────────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $id   = (int) ($body['id'] ?? 0);

            $reference = trim((string) ($body['reference'] ?? ''));
            $status    = (int) (!empty($body['status']) ? 1 : 0);
            $stockLow  = ($body['stockLow'] ?? '') === '' ? null : (int) $body['stockLow'];
            $texts     = is_array($body['texts'] ?? null) ? $body['texts'] : [];
            $seo       = is_array($body['seo'] ?? null) ? $body['seo'] : [];
            $categoryIds = array_values(array_unique(array_map('intval', is_array($body['categoryIds'] ?? null) ? $body['categoryIds'] : [])));

            if ($reference === '') { return $this->jsonResponse(['success' => false, 'error' => 'La référence est obligatoire.'], 400); }
            $hasName = false;
            foreach ($texts as $tx) { if (trim((string) ($tx['name'] ?? '')) !== '') { $hasName = true; break; } }
            if (!$hasName) { return $this->jsonResponse(['success' => false, 'error' => 'Un nom de produit est obligatoire.'], 400); }

            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $ttId = $this->titleTypeId($db);
            $now  = date('Y-m-d H:i:s');
            $uid  = $this->currentUserId();

            // Produit
            $product = ['prd_reference' => $reference, 'prd_status' => $status, 'prd_stock_low' => $stockLow, 'prd_date_edit' => $now, 'prd_user_id_edit' => $uid];
            if ($id > 0) { $product['prd_id'] = $id; } else { $product['prd_date_creation'] = $now; $product['prd_user_id_creation'] = $uid; }

            // Textes (upsert par langue, type TITLE) — ptxt_id existants
            $existing = [];
            if ($id > 0) {
                foreach ($db->query('SELECT ptxt_id, ptxt_lang_id FROM melis_ecom_product_text WHERE ptxt_prd_id = ? AND ptxt_type = ?', [$id, $ttId]) as $r) {
                    $r = (array) $r; $existing[(int) $r['ptxt_lang_id']] = (int) $r['ptxt_id'];
                }
            }
            $productTexts = [];
            foreach ($texts as $tx) {
                if (!is_array($tx)) { continue; }
                $lid = (int) ($tx['langId'] ?? 0); if ($lid <= 0) { continue; }
                $name = trim((string) ($tx['name'] ?? '')); $desc = (string) ($tx['description'] ?? '');
                if ($name === '' && $desc === '') { continue; }
                $row = ['ptxt_lang_id' => $lid, 'ptxt_type' => $ttId, 'ptxt_field_short' => $name, 'ptxt_field_long' => $desc];
                if (isset($existing[$lid])) { $row['ptxt_id'] = $existing[$lid]; }
                $productTexts[] = $row;
            }

            // Réutilise la logique métier (produit + textes). ob_* : jette les warnings dev pour garder un JSON valide.
            $service = $this->getServiceManager()->get('MelisComProductService');
            ob_start();
            $serviceResult = $service->saveProduct($product, $productTexts, [], [], [], [], $id ?: null);
            ob_end_clean();

            $newId = $id > 0 ? $id : (int) $serviceResult;
            if ($newId <= 0 && $id == 0) {
                $refRows = iterator_to_array($db->query('SELECT prd_id FROM melis_ecom_product WHERE prd_reference = ? ORDER BY prd_id DESC LIMIT 1', [$reference]));
                if ($refRows) { $newId = (int) ((array) $refRows[0])['prd_id']; }
            }
            if ($newId <= 0) { $newId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id']; }

            // Catégories : remplacement complet (set), faute de delta côté form React.
            $db->query('DELETE FROM melis_ecom_product_category WHERE pcat_prd_id = ?', [$newId]);
            $order = 0;
            foreach ($categoryIds as $cid) { if ($cid > 0) { $db->query('INSERT INTO melis_ecom_product_category (pcat_prd_id, pcat_cat_id, pcat_order) VALUES (?, ?, ?)', [$newId, $cid, $order++]); } }

            // SEO via service (réutilise insert/update/delete-si-vide)
            $seoExisting = [];
            foreach ($db->query('SELECT eseo_id, eseo_lang_id FROM melis_ecom_seo WHERE eseo_product_id = ?', [$newId]) as $r) { $r = (array) $r; $seoExisting[(int) $r['eseo_lang_id']] = (int) $r['eseo_id']; }
            $seoData = [];
            foreach ($seo as $s) {
                if (!is_array($s)) { continue; }
                $lid = (int) ($s['langId'] ?? 0); if ($lid <= 0) { continue; }
                $seoData[] = ['eseo_id' => $seoExisting[$lid] ?? null, 'eseo_lang_id' => $lid, 'eseo_page_id' => ($s['pageId'] ?? '') === '' ? null : (int) $s['pageId'], 'eseo_url' => trim((string) ($s['url'] ?? '')), 'eseo_url_redirect' => trim((string) ($s['urlRedirect'] ?? '')), 'eseo_url_301' => trim((string) ($s['url301'] ?? '')), 'eseo_meta_title' => trim((string) ($s['metaTitle'] ?? '')), 'eseo_meta_description' => trim((string) ($s['metaDescription'] ?? ''))];
            }
            if ($seoData) { ob_start(); $this->getServiceManager()->get('MelisComSeoService')->saveSeoDataAction('product', $newId, $seoData); ob_end_clean(); }

            // Page associations (réutilise saveProductPageAssociations).
            if (array_key_exists('pageLinks', $body) && is_array($body['pageLinks'])) {
                $linkRow = iterator_to_array($db->query('SELECT plink_id FROM melis_ecom_product_links WHERE plink_product_id = ? LIMIT 1', [$newId]));
                $plinkId = $linkRow ? (int) ((array) $linkRow[0])['plink_id'] : null;
                $assoc = ['plink_id' => $plinkId, 'plink_link_1' => trim((string) ($body['pageLinks']['link1'] ?? '')), 'plink_link_2' => trim((string) ($body['pageLinks']['link2'] ?? '')), 'plink_link_3' => trim((string) ($body['pageLinks']['link3'] ?? ''))];
                ob_start(); $service->saveProductPageAssociations($assoc, $newId); ob_end_clean();
            }

            return $this->jsonResponse(['success' => true, 'data' => ['id' => $newId]], $id ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /products/:id/duplicate ─────────────────────────────────────────
    public function duplicateAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $body      = json_decode($this->getRequest()->getContent(), true) ?? [];
            $dupImages = !empty($body['duplicateImages']);
            $dupDocs   = !empty($body['duplicateDocuments']);
            $status    = !empty($body['putOnline']) ? 1 : 0;
            $ref       = trim((string) ($body['reference'] ?? ''));

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            // Duplicate without images/docs — service's duplicateDocuments has a broken dir path
            ob_start();
            $newId = $this->getServiceManager()->get('MelisComDuplicationService')->duplicateProduct($id, $status, false, false);
            ob_end_clean();
            if (!is_numeric($newId) || (int) $newId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Échec de la duplication.'], 500); }
            if ($ref !== '') { $db->query('UPDATE melis_ecom_product SET prd_reference = ? WHERE prd_id = ?', [$ref, (int) $newId]); }

            // Manually copy product media (images and/or files)
            if ($dupImages || $dupDocs) {
                $this->copyMedia($db, 'rdoc_product_id', $id, $id, (int) $newId, $dupImages, $dupDocs);
            }

            // Duplicate variants (duplicateProduct() does not copy them)
            $srcVariants = iterator_to_array($db->query(
                'SELECT var_id, var_sku, var_main_variant FROM melis_ecom_variant WHERE var_prd_id = ? ORDER BY var_main_variant DESC, var_id',
                [$id]
            ));
            if (!empty($srcVariants)) {
                $dupSrv = $this->getServiceManager()->get('MelisComDuplicationService');
                foreach ($srcVariants as $sv) {
                    $sv = (array) $sv;
                    $origSku = (string) ($sv['var_sku'] ?? '');
                    $varEntry = [['var_id' => (int) $sv['var_id'], 'var_sku' => $origSku !== '' ? $origSku . '-' . (int) $newId : '', 'var_main_variant' => (int) $sv['var_main_variant']]];
                    ob_start();
                    $newVarId = $dupSrv->duplicateVariant($varEntry, $status, false, false, (int) $newId);
                    ob_end_clean();
                    if (is_numeric($newVarId) && (int) $newVarId > 0 && ($dupImages || $dupDocs)) {
                        $this->copyMedia($db, 'rdoc_variant_id', (int) $sv['var_id'], $id, (int) $newId, $dupImages, $dupDocs, (int) $newVarId);
                    }
                }
            }

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /products/delete/:id ──────────────────────────────────────────
    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            ob_start();
            $this->getServiceManager()->get('MelisComProductService')->deleteProductById($id);
            ob_end_clean();
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────
    private function formatProduct(array $r, $db, int $lang): array
    {
        $id   = (int) $r['prd_id'];
        $name = trim((string) ($r['name_cur'] ?? '')) ?: trim((string) ($r['name_any'] ?? '')) ?: trim((string) ($r['prd_reference'] ?? '')) ?: ('#' . $id);
        $categories = [];
        foreach ($db->query('SELECT t.catt_name FROM melis_ecom_product_category pc JOIN melis_ecom_category c ON c.cat_id = pc.pcat_cat_id LEFT JOIN melis_ecom_category_trans t ON t.catt_category_id = c.cat_id AND t.catt_lang_id = ? WHERE pc.pcat_prd_id = ? ORDER BY pc.pcat_order', [$lang, $id]) as $cr) {
            $cr = (array) $cr; if (!empty($cr['catt_name'])) { $categories[] = (string) $cr['catt_name']; }
        }
        return [
            'id' => $id, 'reference' => (string) ($r['prd_reference'] ?? ''), 'status' => (int) ($r['prd_status'] ?? 0),
            'name' => $name, 'image' => isset($r['image_path']) && $r['image_path'] !== null ? (string) $r['image_path'] : '',
            'categories' => $categories, 'dateCreation' => $r['prd_date_creation'] ?? null, 'dateEdit' => $r['prd_date_edit'] ?? null,
        ];
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
