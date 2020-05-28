<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use MelisCommerce\Service\MelisComGeneralService;

/**
 *
 * This service handles the attribute system of MelisCommerce.
 *
 */
class MelisComLinksService extends MelisComGeneralService
{
    /**
     * This method deletes the link to a category / product / variant page
     * 
	 * @param int $typeLink category/product/variant
	 * @param int $id id of the item
	 * @param int $langId lang id of the item
     * @return string the link
     */
    public function getPageLink($typeLink, $id, $langId, $absolute = false)
    {
	    // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'getPageLink_' . $typeLink . '_' . $id . '_' . $langId . '_' . $absolute;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceLocator()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
	    
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_page_link_start', $arrayParameters);
        
        switch ($typeLink)
        {
            case 'category': $results = $this->getPageLinkCategory($id, $langId, $absolute); break;
            case 'product': $results = $this->getPageLinkProduct($id, $langId, $absolute); break;
            case 'variant': $results = $this->getPageLinkVariant($id, $langId, $absolute); break;
            default: $results = '';
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_page_link_end', $arrayParameters);

        // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }
    
    public function getPageIdAssociated($typeLink, $id, $langId)
    {
	    // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'getPageIdAssociated_' . $typeLink . '_' . $id . '_' . $langId;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceLocator()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
	    
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_page_id_associated_start', $arrayParameters);
        
        switch ($typeLink)
        {
            case 'category': $results = $this->getPageLinkCategory($id, $langId, false, true); break;
            case 'product': $results = $this->getPageLinkProduct($id, $langId, false, true); break;
            case 'variant': $results = $this->getPageLinkVariant($id, $langId, false, true); break;
            default: $results = '';
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_id_associated_end', $arrayParameters);

        // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }

    /**
     * This method deletes the link to a category
     *
     * @param int $id id of the category
     * @param int $langId lang id of the item
     * @return string the link
     */
    public function getPageLinkCategory($id, $langId, $absolute = false, $onlyPageId = false)
    {
	    // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'category-getPageLinkCategory_' . $id . '_' . $langId . '_' . $absolute;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceLocator()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
	    
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_page_link_category_start', $arrayParameters);
    
        // Service implementation start
        $melisComCategoryService = $this->getServiceLocator()->get('MelisComCategoryService');
        $catSeo = $melisComCategoryService->getParentCategory($id, array(), true, $langId, true);

        krsort($catSeo);

        $pageUrl = '';
        $pageId = null;
        $link = '';
        if (!empty($catSeo))
        {
            // first check if there's SEO url for this category
            if (!empty($catSeo[0]))
            {
                $tmp = $catSeo[0];
                if (!empty($tmp['eseo_url']))
                    $pageUrl = '/' . $tmp['eseo_url'];
            }
            
            // Check for the link and the first pageId defined
            // if no pageId defined found on category, we look for one defined in a parent page
            foreach ($catSeo as $seoObj)
            {
                $link .= '/' . $seoObj['catt_name'];
                if (!empty($seoObj['eseo_page_id']))
                    $pageId = $seoObj['eseo_page_id'];
            }
            
            if (empty($pageId))
            {
                // default one
                $commerceConfig = $this->getServiceLocator()->get('config');
                $defaultCategoryPage = $commerceConfig['plugins']['meliscommerce']['datas']['seo_default_pages']['category'];
                $pageId = $defaultCategoryPage;
            }
    
            if (!$onlyPageId)
                $pageUrl = $this->assembleDomainAndLink($id, $absolute, $pageUrl, $pageId, $link, 'cid');
        }
        
        // Adding results to parameters for events treatment if needed
        if (!$onlyPageId)
            $arrayParameters['results'] = $pageUrl;
        else
            $arrayParameters['results'] = $pageId;
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_page_link_category_end', $arrayParameters);

        // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }

    /**
     * This method deletes the link to a product
     *
     * @param int $id id of the product
     * @param int $langId lang id of the item
     * @return string the link
     */
    public function getPageLinkProduct($id, $langId, $absolute = false, $onlyPageId = false)
    {
	    // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-getPageLinkProduct_' . $id . '_' . $langId . '_' . $absolute;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceLocator()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
	    
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_page_link_product_start', $arrayParameters);
    
        // Service implementation start
        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
        
        $productDatas = $melisComProductService->getProductTitleAndSeoById($id, $langId);

        $pageId = null;
        $link = '';
        $pageUrl = '';
        if (!empty($productDatas))
        {
            if (!empty($productDatas->eseo_page_id))
                $pageId = $productDatas->eseo_page_id;
            else
            {
                $commerceConfig = $this->getServiceLocator()->get('config');
                $defaultProductPage = $commerceConfig['plugins']['meliscommerce']['datas']['seo_default_pages']['product'];
                $pageId = $defaultProductPage;
            }
            
            if (empty($productDatas->eseo_url))
            {
                if (!empty($productDatas->ptxt_field_short))
                    $link = '/' . $productDatas->ptxt_field_short;
                else
                    $link = '/' . $productDatas->prd_reference;
            }
            else
            {
                $pageUrl = '/' . $productDatas->eseo_url;
            }
            
            if (!$onlyPageId)
                $pageUrl = $this->assembleDomainAndLink($id, $absolute, $pageUrl, $pageId, $link, 'pid');
        }
        
    
        // Adding results to parameters for events treatment if needed
        if (!$onlyPageId)
            $arrayParameters['results'] = $pageUrl;
        else
            $arrayParameters['results'] = $pageId;
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_page_link_product_end', $arrayParameters);

        // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }

    /**
     * This method deletes the link to a variant
     *
     * @param int $id id of the variant
     * @param int $langId lang id of the item
     * @return string the link
     */
    public function getPageLinkVariant($id, $langId, $absolute = false, $onlyPageId = false)
    {
	    // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'variant-getPageLinkVariant_' . $id . '_' . $langId . '_' . $absolute;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceLocator()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
	    
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_page_link_variant_start', $arrayParameters);
    
        // Service implementation start
        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
        
        $variantDatas = $melisComVariantService->getVariantAndSeoById($id, $langId);

        $pageId = null;
        $link = '';
        $pageUrl = '';
        if (!empty($variantDatas))
        {
            if (!empty($variantDatas->eseo_page_id))
                $pageId = $variantDatas->eseo_page_id;
            else
            {
                $commerceConfig = $this->getServiceLocator()->get('config');
                $defaultVariantPage = $commerceConfig['plugins']['meliscommerce']['datas']['seo_default_pages']['variant'];
                $pageId = $defaultVariantPage;
            }
            
            if (empty($variantDatas->eseo_url))
                $link = '/' . $variantDatas->var_sku;
            else
                $pageUrl = '/' . $variantDatas->eseo_url;
            
            if (!$onlyPageId)
                $pageUrl = $this->assembleDomainAndLink($id, $absolute, $pageUrl, $pageId, $link, 'vid');
        }
    
        // Adding results to parameters for events treatment if needed
        if (!$onlyPageId)
            $arrayParameters['results'] = $pageUrl;
        else
            $arrayParameters['results'] = $pageId;
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_page_link_variant_end', $arrayParameters);

        // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }
    
    private function assembleDomainAndLink($id, $absolute, $pageUrl, $pageId, $link, $varName)
    {
	    // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'assembleDomainAndLink_' . $id . '_' . $absolute . '_' . $pageUrl . '_' . $pageId . '_' . $link . '_' . $varName;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceLocator()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
	    
        // At this point, a pageId must have been found or it's an arror or misconfiguration
        if (!empty($pageId))
        {
            $melisTree = $this->getServiceLocator()->get('MelisEngineTree');
                
            // if empty, no SEO url has been found, it's a classic url
            if ($pageUrl == '')
            {
                $linkPage = $melisTree->getPageLink($pageId, $absolute);
        
                $pageUrl = explode('/id/', $linkPage);
                if (!empty($pageUrl))
                    $pageUrl = $pageUrl[0];
                else
                    $pageUrl = ''; // page uses SEO URL, so just use the categories name

               $link = $melisTree->cleanLink($link);
               
               $pageUrl .= $link . '/id/' . $pageId . '/' . $varName . '/' . $id;
            }
            else
            {
                // a SEO url was found before, just add the domain for this pageId
                if ($absolute)
                {
                    $host = $melisTree->getDomainByPageId($pageId);
                    $pageUrl = $host . $pageUrl;
                }
            }
        }

        // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $pageUrl);
        
        return $pageUrl;
    }
}