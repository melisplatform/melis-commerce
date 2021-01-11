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
        $this->deleteCacheByPrefix(self::COMMERCE_PRODUCT_CACHE_KEY . $id);
    }

    private function deleteCategoryCache($id) {
        $this->deleteCacheByPrefix(self::COMMERCE_CATEGORY_CACHE_KEY . $id);
        $this->deleteCategoryProductsCache($id);
    }

    private function deleteVariantCache($id) {
        $this->deleteCacheByPrefix(self::COMMERCE_VARIANT_CACHE_KEY . $id);
        $this->deleteVariantProductAssociationCache($id);
    }

    private function deleteVariantAssociationCache($id) {
        $this->deleteVariantProductAssociationCache($id);
    }

    private function getProductAssociations($prodId) {
        $service = $this->getServiceManager()->get('MelisEcomAssocVariantTable');
        return $service->getEntryByField('', '');
    }

    private function deleteProductAssociationCache($prodId) {
        $associatedProducts = $this->getProductAssociations($prodId);
        $associatedProductsIds = [];
        foreach ($associatedProducts as $product) {
            $associatedProductsIds[] = $product->getId();
        }

        $this->deleteProductServiceProductAssociationCache($associatedProductsIds);
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