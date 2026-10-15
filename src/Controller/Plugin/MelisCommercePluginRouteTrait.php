<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

/**
 * Commerce front URLs carry the item in the route: /…/id/<page>/pid/<productId> (also cid / vid), see the
 * melis_front_commerce_* routes. The product plugins only read m_product_id, so /pid/4 still showed the
 * product configured in the plugin (Mantis #0011156) — the links ProductList generates were useless.
 */
trait MelisCommercePluginRouteTrait
{
    /** A positive integer route parameter of the current front request (productId, categoryId, variantId), or null. */
    protected function commerceRouteParam(string $name): ?int
    {
        if ($this->renderMode === 'melis') {
            return null; // BO page edition: keep the product configured in the plugin
        }
        try {
            $sm = $this->getServiceManager();
            $match = $sm->get('router')->match($sm->get('request'));
            $value = $match ? $match->getParam($name) : null;
        } catch (\Throwable $e) {
            return null;
        }
        return (is_numeric($value) && (int) $value > 0) ? (int) $value : null;
    }

    /**
     * The product displayed by the ProductShow plugin of the SAME page (its m_product_id in the page content),
     * or null. A product page = ProductShow (set to a product) + AddToCart (no product setting of its own): opened
     * without /pid/N, AddToCart didn't know the product and said "Product is not available" (Mantis #0011159).
     */
    protected function productShownOnPage(): ?int
    {
        $pageId = (int) ($this->pluginFrontConfig['pageId'] ?? 0);
        if ($pageId <= 0) {
            return null;
        }
        try {
            $version = ($this->renderMode === 'melis' || $this->previewMode) ? 'saved' : 'published';
            $page = $this->getServiceManager()->get('MelisEnginePage')->getDatasPage($pageId, $version);
            $tree = $page ? $page->getMelisPageTree() : null;
            $content = $tree ? (string) ($tree->page_content ?? '') : '';
            if ($content === '' && $version === 'saved') { // no draft → the published content
                $page = $this->getServiceManager()->get('MelisEnginePage')->getDatasPage($pageId, 'published');
                $tree = $page ? $page->getMelisPageTree() : null;
                $content = $tree ? (string) ($tree->page_content ?? '') : '';
            }
            $useErrors = libxml_use_internal_errors(true);
            $xml = $content !== '' ? simplexml_load_string($content) : false;
            libxml_clear_errors();
            libxml_use_internal_errors($useErrors);
            if ($xml === false) {
                return null;
            }
            foreach ($xml->MelisCommerceProductShowPlugin as $node) {
                $id = (string) $node->m_product_id;
                if (ctype_digit($id) && (int) $id > 0) {
                    return (int) $id;
                }
            }
        } catch (\Throwable $e) {
        }
        return null;
    }
    /**
     * The variant used on a product page when none is given (AddToCart, ProductShow): the product's main variant if it has stock, else the
     * first active variant with stock, else the main one (then "not available" stays correct). Mantis #0011159.
     */
    protected function defaultVariantId(int $productId, $countryId): ?int
    {
        $variantSrv = $this->getServiceManager()->get('MelisComVariantService');
        $main = null;
        $firstInStock = null;
        foreach ($this->getServiceManager()->get('MelisEcomVariantTable')->getEntryByField('var_prd_id', $productId) as $variant) {
            if ((int) $variant->var_status !== 1) {
                continue;
            }
            $stock = $variantSrv->getVariantFinalStocks($variant->var_id, $countryId);
            $inStock = $stock && (float) $stock->stock_quantity > 0;
            if ((int) $variant->var_main_variant === 1) {
                if ($inStock) {
                    return (int) $variant->var_id;
                }
                $main = (int) $variant->var_id;
            } elseif ($inStock && $firstInStock === null) {
                $firstInStock = (int) $variant->var_id;
            }
        }
        return $firstInStock ?? $main;
    }
}
