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
