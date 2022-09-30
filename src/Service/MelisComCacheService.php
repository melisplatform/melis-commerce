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
    CONST COMMERCE_DOCUMENT_CACHE_KEY = 'document-';
    CONST COMMERCE_ATTRIBUTE_CACHE_KEY = 'attribute-';
    CONST COMMERCE_ATTRIBUTE_VAL_CACHE_KEY = 'attribute-value-';
    CONST COMMERCE_ATTRIBUTE_VAL_TRANS_CACHE_KEY = 'attribute-value-trans-';


    /**
     * Deletes cache
     * @param $type
     * @param $id
     * @param null $additionalParam
     */
    public function deleteCache($type, $id, $additionalParam = []) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_cache_start', $arrayParameters);

        if ($arrayParameters['type'] == 'product')
            $this->deleteProductCache($arrayParameters['id']);

        if ($arrayParameters['type'] == 'category')
            $this->deleteCategoryCache($arrayParameters['id']);

        if ($arrayParameters['type'] == 'variant')
            $this->deleteVariantCache($arrayParameters['id'], $arrayParameters['additionalParam']);

        if ($arrayParameters['type'] == 'variant_association')
            $this->deleteVariantAssociationCache($arrayParameters['id']);

        if ($arrayParameters['type'] == 'document')
            $this->deleteDocumentCache($arrayParameters['id'], $arrayParameters['additionalParam']);

        if ($arrayParameters['type'] == 'attribute')
            $this->deleteAttributeCache($arrayParameters['id'], $arrayParameters['additionalParam']);

        if ($arrayParameters['type'] == 'attribute-value')
            $this->deleteAttributeValueCache($arrayParameters['id'], $arrayParameters['additionalParam']);

        if ($arrayParameters['type'] == 'attribute-value-trans')
            $this->deleteAttributeValueTransCache($arrayParameters['id'], $arrayParameters['additionalParam']);

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_cache_end', $arrayParameters);
    }

    /**
     * Function to clear document cache by prefix
     *
     * @param $id
     * @param $docRelation
     */
    private function deleteDocumentCache($id, $docRelation)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_document_cache_start', $arrayParameters);

        if (is_array($arrayParameters['docRelation'])) {
            foreach ($arrayParameters['docRelation'] as $relation) {
                //remove all document cache starts with document-
                $this->deleteCacheByPrefix(self::COMMERCE_DOCUMENT_CACHE_KEY .$relation.'-'. $arrayParameters['id']);
                //this to remove the cache depending on relation (variant/product)
                $this->deleteCacheByPrefix($relation.'-'. $arrayParameters['id']);
            }
        } else {
            //remove all document cache starts with document-
            $this->deleteCacheByPrefix(self::COMMERCE_DOCUMENT_CACHE_KEY . $arrayParameters['docRelation'] . '-' . $arrayParameters['id']);
            //this to remove the cache depending on relation (variant/product)
            $this->deleteCacheByPrefix($arrayParameters['docRelation'] . '-' . $arrayParameters['id']);
        }

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_document_cache_end', $arrayParameters);
    }

    /**
     * Deletes product cache
     * @param $id
     */
    private function deleteProductCache($id) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_product_cache_start', $arrayParameters);

        // delete main cache for product
        $this->deleteCacheByPrefix(self::COMMERCE_PRODUCT_CACHE_KEY . $arrayParameters['id']);
        // delete other product cache that are associated with this one
        $this->deleteProductAssociationCache($arrayParameters['id']);

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_product_cache_end', $arrayParameters);
    }

    /**
     * Deletes category cache
     * @param $id
     */
    private function deleteCategoryCache($id) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_category_cache_start', $arrayParameters);

        // delete main cache for category
        $this->deleteCacheByPrefix(self::COMMERCE_CATEGORY_CACHE_KEY . $arrayParameters['id']);
        $this->deleteCacheByPrefix('categories');
        // delete parent category cache
        $this->deleteParentCategoryCache($arrayParameters['id']);
        // delete product cache for the products of this category
        $this->deleteCategoryProductsCache($arrayParameters['id']);

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_category_cache_end', $arrayParameters);
    }

    /**
     * Deletes variant cache
     * @param $id
     * @param $productId
     */
    private function deleteVariantCache($id, $productId) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_variant_cache_start', $arrayParameters);

        // delete main cache for variant
        $this->deleteCacheByPrefix(self::COMMERCE_VARIANT_CACHE_KEY . $arrayParameters['id']);
        // delete variant's product cache
        $this->deleteCache('product', $arrayParameters['productId']);

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_variant_cache_end', $arrayParameters);
    }

    /**
     * Deletes variant association cache
     * @param $id
     */
    private function deleteVariantAssociationCache($id) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_variant_association_cache_start', $arrayParameters);

        // delete variant association
        $this->deleteVariantProductAssociationCache($arrayParameters['id']);

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_variant_association_cache_end', $arrayParameters);
    }

    /**
     * Deletes attribut cache
     * @param $id
     * @param array $additionalParam
     */
    private function deleteAttributeCache($id, $additionalParam = []) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_attribute_cache_start', $arrayParameters);

        // delete attribute cache
        $this->deleteCacheByPrefix(self::COMMERCE_ATTRIBUTE_CACHE_KEY . $arrayParameters['id']);
        // delete product cache that are using this attribute
        if (! empty($arrayParameters['additionalParam']['productId'])) {
            $this->deleteCache('product', $arrayParameters['additionalParam']['productId']);
        } else {
            // if productId is not specified we need to manually get the products that are using this attribute
            // $productIds = $this->getProductIdsUsingAttributeByAttributeId($arrayParameters['id']);

            // foreach ($productIds as $productId) {
            //     $this->deleteCache('product', $productId);
            // }

            $this->deleteCacheByPrefix('product');
        }
        // delete variant cache that are using this attribute
        if (! empty($arrayParameters['additionalParam']['variantId'])) {
            $this->deleteCache('variant', $arrayParameters['additionalParam']['variantId']);
        } else {
            // if variantId is not specified we need to manually get the variants that are using this attribute
            // $variantIds = $this->getVariantIdsUsingAttributeByAttributeId($arrayParameters['id']);

            // foreach ($variantIds as $variantId) {
            //     $this->deleteCache('variant', $variantId);
            // }

            $this->deleteCacheByPrefix('variant');
        }

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_attribute_cache_end', $arrayParameters);
    }

    public function deleteAttributeValueCache($attrValueId, $additionalParam = [])
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_attribute_value_cache_start', $arrayParameters);

        // Attribute value translations
        $this->deleteCacheByPrefix(self::COMMERCE_ATTRIBUTE_VAL_CACHE_KEY.$attrValueId);
    
        if(!isset($additionalParam['skipDeleteAttrValTrans'])) {
            $attrValueTransTbl = $this->getServiceManager()->get('MelisEcomAttributeValueTransTable');
            foreach($attrValueTransTbl->getEntryByFiled('av_attribute_value_id', $attrValueId) As $val){
                // Removing associated translations
                $this->deleteAttributeValueTransCache($val->avt_id, ['av_attribute_value_id' => $attrValueId]);
            }
        }

        // Attribute
        if (!empty($additionalParam['atval_attribute_id'])) {
            // Attribute cache
            $this->deleteAttributeCache($additionalParam['atval_attribute_id']);
        }

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_attribute_value_cache_end', $arrayParameters);
    }

    public function deleteAttributeValueTransCache($attrValueTransId, $additionalParam = [])
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_attribute_value_trans_cache_start', $arrayParameters);

        $this->deleteCacheByPrefix(self::COMMERCE_ATTRIBUTE_VAL_TRANS_CACHE_KEY.$attrValueTransId);

        // Attribute value trans
        $attrValueTransTbl = $this->getServiceManager()->get('MelisEcomAttributeValueTransTable');
        $attrValueTrans = $attrValueTransTbl->getEntryById($attrValueTransId)->current();

        if(!empty($additionalParam))
            $additionalParam = array_merge($additionalParam, ['skipDeleteAttrValTrans' => true]);
        else 
            $additionalParam['skipDeleteAttrValTrans'] = true;

        if (!empty($attrValueTrans)){
            $attrValueTbl = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
            $attrValue = $attrValueTbl->getEntryById($attrValueTrans->av_attribute_value_id)->current();
            
            if(!empty($attrValue))
                $additionalParam['atval_attribute_id'] = $attrValue->atval_attribute_id;
    
            // Attribute value
            $this->deleteAttributeValueCache($attrValueTrans->av_attribute_value_id, $additionalParam);
        }

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_attribute_value_trans_cache_end', $arrayParameters);
    }

    /**
     * Deletes product association cache
     * @param $prodId
     */
    private function deleteProductAssociationCache($prodId) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_product_association_cache_start', $arrayParameters);

        $productVariants = $this->getProductVariants($arrayParameters['prodId']);
        if (! empty($variantsAssociatedToProduct)) {
            $variantsAssociatedToProduct = $this->getVariantIdsAssociatedToProduct($productVariants);
            $productIdsAssociatedToProduct = $this->getProductIdsAssociatedToProduct($variantsAssociatedToProduct);

            if (!empty($productIdsAssociatedToProduct))
                $this->deleteProductServiceProductAssociationCache($productIdsAssociatedToProduct);
        }

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_product_association_cache_end', $arrayParameters);
    }

    /**
     * Retrieves the product variants
     * @param $prodId
     * @return mixed
     */
    private function getProductVariants($prodId) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_product_variants_start', $arrayParameters);

        $service = $this->getServiceManager()->get('MelisComVariantService');
        $arrayParameters['result'] = $service->getVariantListByProductId($arrayParameters['prodId'], null, null, true);

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_product_variants_end', $arrayParameters);
        return $arrayParameters['result'];
    }

    /**
     * Retrieves the variant ids associated to product
     * @param $variants
     * @return mixed
     */
    private function getVariantIdsAssociatedToProduct($variants) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_variant_ids_associated_to_product_start', $arrayParameters);

        $variantsAssociatedToProduct = [];
        $table = $this->getServiceManager()->get('MelisEcomAssocVariantTable');

        foreach ($arrayParameters['variants'] as $variant) {
            $variantAssociatedToProduct = $table->getEntryByField('avar_two', $variant->getId())->current();

            if (! empty($variantAssociatedToProduct))
                $variantsAssociatedToProduct[] = $variantAssociatedToProduct->avar_one;
        }

        $arrayParameters['result'] = $variantsAssociatedToProduct;

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_variant_ids_associated_to_product_end', $arrayParameters);
        return $arrayParameters['result'];
    }

    /**
     * Retrieves the product ids associated to product
     * @param $variantIds
     * @return mixed
     */
    private function getProductIdsAssociatedToProduct($variantIds) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_product_ids_associated_to_product_start', $arrayParameters);

        $productIdsAssociatedToProduct = [];
        $service = $this->getServiceManager()->get('MelisComVariantService');

        foreach ($arrayParameters['variantIds'] as $variantId) {
            $product = $service->getProductByVariantId($variantId);

            if (! in_array($product->prd_id, $productIdsAssociatedToProduct)) {
                $productIdsAssociatedToProduct[] = $product->prd_id;
            }
        }

        $arrayParameters['result'] = $productIdsAssociatedToProduct;

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_product_ids_associated_to_product_end', $arrayParameters);
        return $arrayParameters['result'];
    }

    /**
     * Deletes products cache that are using the category
     * @param $id
     */
    private function deleteCategoryProductsCache($id) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_category_products_cache_start', $arrayParameters);

        // $categoryProducts = $this->getCategoryProducts($arrayParameters['id']);
        // $categoryProducts = $this->getCategoryProducts($arrayParameters['id']);

        $prdCatTbl = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
        $catPrd = $prdCatTbl->getEntryByField('pcat_cat_id', $arrayParameters['id']);

        foreach ($catPrd as $product) {
            $this->deleteCache('product', $product->pcat_prd_id);
        }

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_category_products_cache_end', $arrayParameters);
    }

    /**
     * Retrieves products that are using the category
     * @param $id
     * @return mixed
     */
    private function getCategoryProducts($id) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_category_products_start', $arrayParameters);

        $service = $this->getServiceManager()->get('MelisComCategoryService');
        $arrayParameters['result'] =  $service->getCategoryProductsById($arrayParameters['id']);

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_category_products_end', $arrayParameters);
        return $arrayParameters['result'];
    }

    /**
     * Retrieves the product of a variant
     * @param $id
     * @return mixed
     */
    private function getVariantProductId($id) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_variant_product_id_start', $arrayParameters);

        $service = $this->getServiceManager()->get('MelisComVariantService');
        $arrayParameters['result'] = $service->getVariantById($arrayParameters['id']);

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_variant_product_id_end', $arrayParameters);
        return $arrayParameters['result'];
    }

    /**
     * Deletes the variant product association cache
     * @param $id
     */
    private function deleteVariantProductAssociationCache($id) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_variant_product_association_cache_start', $arrayParameters);

        $variant = $this->getVariantProductId($arrayParameters['id']);
        $variantProductId = $variant->getVariant()->var_prd_id;
        $this->deleteProductServiceProductAssociationCache([$variantProductId]);

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_variant_product_association_cache_end', $arrayParameters);
    }

    /**
     * Deletes the product service product association cache
     * @param array $productIds
     */
    private function deleteProductServiceProductAssociationCache($productIds = []) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_product_service_product_association_cache_start', $arrayParameters);

        foreach ($arrayParameters['productIds'] as $productId) {
            $prefix = self::COMMERCE_PRODUCT_CACHE_KEY . $productId . '-getAssocProducts_' . $productId;
            $this->deleteCacheByPrefix(
                $prefix
            );
        }

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_product_service_product_association_cache_end', $arrayParameters);
    }

    /**
     * Retrieves the ids of the products that are using the attribute
     * @param $attributeId
     * @return mixed
     */
    private function getProductIdsUsingAttributeByAttributeId($attributeId) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_product_ids_using_attribute_by_attribute_id_start', $arrayParameters);

        $table = $this->getServiceManager()->get('MelisEcomAttributeTable');
        $results = $table->getProductsUsingAttributeByAttributeId($arrayParameters['attributeId'])->toArray();
        $productIds = [];

        foreach ($results as $result) {
            $productIds[] = $result['patt_product_id'];
        }

        $arrayParameters['result'] = $productIds;

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_product_ids_using_attribute_by_attribute_id_end', $arrayParameters);

        return $arrayParameters['result'];
    }

    /**
     * Retrieves the ids of the variants that are using the attribute
     * @param $attributeId
     * @return mixed
     */
    private function getVariantIdsUsingAttributeByAttributeId($attributeId) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_variant_ids_using_attribute_by_attribute_id_start', $arrayParameters);

        $table = $this->getServiceManager()->get('MelisEcomAttributeTable');
        $results = $table->getVariantsUsingAttributeByAttributeId($arrayParameters['attributeId'])->toArray();
        $variantIds = [];

        foreach ($results as $result) {
            $variantIds[] = $result['vatv_variant_id'];
        }

        $arrayParameters['result'] = $variantIds;

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_get_variant_ids_using_attribute_by_attribute_id_end', $arrayParameters);
        return $arrayParameters['result'];
    }

    /**
     * Deletes the parent category cache
     * @param $parentId
     */
    private function deleteParentCategoryCache($categoryId)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_parent_category_cache_rec_start', $arrayParameters);

        // get parent
        $service = $this->getServiceManager()->get('MelisComCategoryService');
        $parentData = [];

        $parentDataListUntilRoot = $service->getParentCategory($arrayParameters['categoryId'], [], true);
        if (! empty($parentDataListUntilRoot[1])) {
            // we only need it's preceding parent
            $parentData = $parentDataListUntilRoot[1];
        }

        if (! empty($parentData)) {
            // delete cache
            $this->deleteCache('category', $parentData['cat_id'], $parentData['cat_father_cat_id']);
        }

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_parent_category_cache_rec_end', $arrayParameters);
    }

    /**
     * Delete cache by prefix
     * @param $prefix
     * @param string $confName
     */
    private function deleteCacheByPrefix($prefix, $confName = self::COMMERCE_CACHE_KEY) {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_cache_by_prefix_start', $arrayParameters);

        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $melisEngineCacheSystem->deleteCacheByPrefix($arrayParameters['prefix'], $arrayParameters['confName']);

        $arrayParameters = $this->sendEvent('meliscommerce_cache_service_delete_cache_by_prefix_end', $arrayParameters);
    }
}