<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use Laminas\View\Model\JsonModel;

class MelisComCacheService extends MelisComGeneralService
{
    CONST COMMERCE_CACHE_KEY = 'commerce_big_services';
    CONST COMMERCE_PRODUCT_CACHE_KEY = 'product-';
    CONST COMMERCE_CATEGORY_CACHE_KEY = 'category-';
    CONST COMMERCE_VARIANT_CACHE_KEY = 'variant-';

    public function deleteCache($type, $id) {
        if ($type == 'product')
            $this->deleteProductCache($id);

        if ($type == 'category')
            $this->deleteCategoryCache($id);

        if ($type == 'variant')
            $this->deleteVariantCache($id);

        if ($type == 'variant_association')
            $this->deleteVariantAssociationCache($id);
    }

    private function deleteProductCache($id) {
        // delete main cache for product
        $this->deleteCacheByPrefix(self::COMMERCE_PRODUCT_CACHE_KEY . $id);
        // delete product cache for products that are associated with this one
        $this->deleteProductAssociationCache($id);
    }

    private function deleteCategoryCache($id) {
        // delete main cache for category
        $this->deleteCacheByPrefix(self::COMMERCE_CATEGORY_CACHE_KEY . $id);
        $this->deleteCacheByPrefix('categories');
        // delete product cache for the products of this category
        $this->deleteCategoryProductsCache($id);
    }

    private function deleteVariantCache($id) {
        // delete main cache for variant
        $this->deleteCacheByPrefix(self::COMMERCE_VARIANT_CACHE_KEY . $id);
        // delete variant's product cache
        $this->deleteVariantProductAssociationCache($id);
    }

    private function deleteVariantAssociationCache($id) {
        // delete variant association
        $this->deleteVariantProductAssociationCache($id);
    }

    private function deleteProductAssociationCache($prodId) {
        $productVariants = $this->getProductVariants($prodId);
        $variantsAssociatedToProduct = $this->getVariantIdsAssociatedToProduct($productVariants);
        $productIdsAssociatedToProduct = $this->getProductIdsAssociatedToProduct($variantsAssociatedToProduct);

        if (! empty($productIdsAssociatedToProduct))
            $this->deleteProductServiceProductAssociationCache($productIdsAssociatedToProduct);
    }

    private function getProductVariants($prodId) {
        $service = $this->getServiceManager()->get('MelisComVariantService');
        return $service->getVariantListByProductId($prodId, null, null, true);
    }

    private function getVariantIdsAssociatedToProduct($variants) {
        $variantsAssociatedToProduct = [];
        $table = $this->getServiceManager()->get('MelisEcomAssocVariantTable');

        foreach ($variants as $variant) {
            $variantAssociatedToProduct = $table->getEntryByField('avar_two', $variant->getId())->current();

            if (! empty($variantAssociatedToProduct))
                $variantsAssociatedToProduct[] = $variantAssociatedToProduct->avar_one;
        }

        return $variantsAssociatedToProduct;
    }

    private function getProductIdsAssociatedToProduct($variantIds) {
        $productIdsAssociatedToProduct = [];
        $service = $this->getServiceManager()->get('MelisComVariantService');

        foreach ($variantIds as $variantId) {
            $product = $service->getProductByVariantId($variantId);

            if (! in_array($product->prd_id, $productIdsAssociatedToProduct)) {
                $productIdsAssociatedToProduct[] = $product->prd_id;
            }
        }

        return $productIdsAssociatedToProduct;
    }

    private function deleteCategoryProductsCache($id) {
        $categoryProducts = $this->getCategoryProducts($id);

        foreach ($categoryProducts as $product) {
            $this->deleteCache('product', $product->getId());
        }
    }

    private function getCategoryProducts($id) {
        $service = $this->getServiceManager()->get('MelisComCategoryService');
        return $service->getCategoryProductsById($id);
    }

    private function getVariantProductId($id) {
        $service = $this->getServiceManager()->get('MelisComVariantService');
        return $service->getVariantById($id);
    }

    private function deleteVariantProductAssociationCache($id) {
        $variant = $this->getVariantProductId($id);
        $variantProductId = $variant->getVariant()->var_prd_id;
        $this->deleteProductServiceProductAssociationCache([$variantProductId]);
    }

    private function deleteProductServiceProductAssociationCache($productIds = []) {
        foreach ($productIds as $productId) {
            $prefix = self::COMMERCE_PRODUCT_CACHE_KEY . $productId . '-getAssocProducts_' . $productId;
            $this->deleteCacheByPrefix(
                $prefix
            );
        }
    }

    private function deleteCacheByPrefix($prefix, $confName = self::COMMERCE_CACHE_KEY) {
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $melisEngineCacheSystem->deleteCacheByPrefix($prefix, $confName);
    }
}