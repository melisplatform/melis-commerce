<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use MelisCommerce\Model\MelisCategory;
use Laminas\Stdlib\ArrayUtils;
/**
 *
 * This service handles the category system of MelisCommerce.
 *
 */
class MelisComCategoryService extends MelisComGeneralService
{
    /**
     *
     * This method gets all categories bellow a categoryId.
     *
     * @param int $categoryId If not specified, it will bring back the root categories.
     * @param int $langId If specified, translations of category will be limited to that lang
     * @param boolean $onlyValid if true, returns only active status and valid range of dates categories
     * @param int $start If not specified, it will start at the begining of the list
     * @param int $limit If not specified, it will bring all categories of the list
     *
     * @return MelisCategory[] Category object
     */
    public function getCategoryListById($categoryId = -1, $langId = null, $onlyValid = false, $start = 0, $limit = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_list_start', $arrayParameters);
        
        // Service implementation start
        
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        // Getting Categories under Category ID
        $melisCategoryData = $this->getCategoryListByIdRecursive($arrayParameters['categoryId'], $arrayParameters['langId'], $arrayParameters['onlyValid'], $arrayParameters['start'], $arrayParameters['limit']);
        
        $arrayParameters['results'] = $melisCategoryData;
        // Service implementation end
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_list_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * Getting the Category Details Recursively
     * This function will return children data of the parent category
     * @param int $categoryId If not specified, it will bring back the root categories.
     * @param int $langId If specified, translations of category will be limited to that lang
     * @param boolean $onlyValid if true, returns only active status and valid range of dates categories
     * @param int $start If not specified, it will start at the begining of the list
     * @param int $limit If not specified, it will bring all categories of the list
     * @param int $fatherId If Zero (0), this will return the root of the category
     * @return int Array
     */
    public function getCategoryListByIdRecursive($categoryId, $langId = null, $onlyValid = false, $start = null, $limit = null, $fatherId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_category_list_recursive_start', $arrayParameters);
        
        $categoryId = $arrayParameters['categoryId'];
        $langId = $arrayParameters['langId'];
        $onlyValid = $arrayParameters['onlyValid'];
        $start = $arrayParameters['start'];
        $limit = $arrayParameters['limit'];
        $fatherId = $arrayParameters['fatherId'];
        
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        
        $dataCategoryData = $melisEcomCategoryTable->getCategoryChildrenListById($categoryId, $langId, $onlyValid, $start, $limit, $fatherId);

        foreach ($dataCategoryData As $val){
            
            // Retrieving category entity
            $melisCategory = $this->getCategoryById($val->cat_id, $langId, $onlyValid);
            
            array_push($results, $melisCategory);
        }
        
        $arrayParameters['results'] = $results;
        // Service implementation end
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_category_list_recursive_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    
    /**
     *
     * This method gets a category by its categoryId and brings back
     * all the datas for a category: category, text translations, country
     *
     * @param int $categoryId Category Id to look for
     * @param int $langId If specified, translations of category will be limited to that lang
     *
     * @return MelisCategory|null Category object
     */
    public function getCategoryById($categoryId, $langId = null, $onlyValid = false)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'category-' . $categoryId . '-getCategoryById_' . $categoryId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
        
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_byid_start', $arrayParameters);
        
        // Service implementation start
        $melisCategory = new \MelisCommerce\Entity\MelisCategory();
        
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        
        // Getting Categories under Category ID
        $melisCategoryDataRes = $melisEcomCategoryTable->getEntryById($arrayParameters['categoryId']);
        $category = $melisCategoryDataRes->current();

        if (!empty($category))
        {
            // Id
            $melisCategory->setId($category->cat_id);

            // category
            $melisCategory->setCategory($category);

            // trasnalations
            $catTrans = $this->getCategoryTranslationById($category->cat_id, $arrayParameters['langId'], $arrayParameters['onlyValid']);
            $melisCategory->setTranslations($catTrans);

            // seo
            $catSeo = $this->getCategorySeoById($category->cat_id, $arrayParameters['langId']);
            $melisCategory->setSeo($catSeo);

            // countries
            $catCountries = $this->getCategoryCountriesById($category->cat_id, $arrayParameters['onlyValid']);
            $melisCategory->setCountries($catCountries);

            // children
            $catChildren = $this->getCategoryListByIdRecursive(null, $arrayParameters['langId'], $arrayParameters['onlyValid'], null, null, $category->cat_id);

            $melisCategory->setChildren($catChildren);
        }

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $melisCategory;
        // Service implementation end
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_byid_end', $arrayParameters);
        
        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This method will return the name of the category
     * @param int $categoryId category id
     * @param int $langId if specified this will return with the same language,
     * otherwise this will return first category name the available.
     * @return String||null
     */
    public function getCategoryNameById($categoryId, $langId = null)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'category-' . $categoryId . '-getCategoryNameById_' . $categoryId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
        
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_name_start', $arrayParameters);
        
        // Service implementation
        
        $category = $this->getCategoryById($arrayParameters['categoryId']);
        
        $catTrans = $category->getTranslations();
        
        $catNameStr = null;

        if (!empty($catTrans))
        {
            // Getting the first available translation of the Category
            foreach ($catTrans As $val)
            {
                if (!empty($val->catt_name) && $val->elang_id == $arrayParameters['langId']) 
                {
                    $catNameStr = $val->catt_name;
                    break;
                }
            }

            if (empty($catNameStr)) 
            {
                // Getting the first available translation of the Category
                foreach ($catTrans As $val)
                {
                    if (!empty($val->catt_name))
                    {
                        $catNameStr = $val->catt_name . ' ('.$val->elang_name.')';
                        break;
                    }
                }
            }
        }

        $results = $catNameStr;
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_products_byid_end', $arrayParameters);
        
        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }
    
    /**
     *
     * This method gets the products list affected to a category by its categoryId.
     * Products come with their text translations only
     *
     * @param int $categoryId Category Id to look for
     * @param int $langId If specified, translations of products will be limited to that lang
     * @param boolean $onlyValid if true, returns only active products
     *
     * @return MelisProduct|null Product object
     */
    public function getCategoryProductsById($categoryId, $langId = null, $onlyValid = false)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'category-' . $categoryId . '-getCategoryProductsById_' . $categoryId . '_' . $langId . '_'.$onlyValid;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
        
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_products_byid_start', $arrayParameters);
        
        // Service implementation start
        $melisProduct = new \MelisCommerce\Entity\MelisProduct();
        
        $melisComProductService = $this->getServiceManager()->get('MelisComProductService');
        $melisEcomProductCategoryTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
        
        $categorProductsData = $melisEcomProductCategoryTable->getCategoryProductsByCategoryId($arrayParameters['categoryId'], $arrayParameters['onlyValid']);
        
        foreach ($categorProductsData As $val)
        {
            $catProd = $melisComProductService->getProductById($val->prd_id, $arrayParameters['langId']);
            array_push($results, $catProd);
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_products_byid_end', $arrayParameters);
        
        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }
    
    public function getCategoriesProductsByIds($categoryIds = null, $onlyvalid = false, $langId = null, $column = 'cat_id', $order = 'ASC', $includeSubCategories = false)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_categories_products_byids_start', $arrayParameters);
        
        // Service implementation start
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        $prdSrv = $this->getServiceManager()->get('MelisComProductService');
        
        $category = $melisEcomCategoryTable->getCategoriesByIds($arrayParameters['categoryIds'], $arrayParameters['onlyvalid'], $arrayParameters['langId'],  $arrayParameters['column'],  $arrayParameters['order']);
        
        foreach ($category As $val)
        {
            $products = $prdSrv->getProductsByCategoryId($val->cat_id, $arrayParameters['onlyvalid'], $langId);
            
            if ($arrayParameters['includeSubCategories'])
            {
                $products = ArrayUtils::merge($products, $this->getSubCategoriesProducts($val->cat_id, array(), $arrayParameters['onlyvalid'], $arrayParameters['langId']));
            }
            
            $val->cat_products = $products;
            
            array_push($results, $val);
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_categories_products_byids_end', $arrayParameters);
        
        return $arrayParameters['results'];
        
    }

    public function getCategoriesByIds($categoryIds = null, $onlyvalid = false, $langId = null, $column = 'cat_id', $order = 'ASC')
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_categories_products_byids_start', $arrayParameters);
        
        // Service implementation start
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        $categories = $melisEcomCategoryTable->getCategoriesByIds($arrayParameters['categoryIds'], $arrayParameters['onlyvalid'], $arrayParameters['langId'],  $arrayParameters['column'],  $arrayParameters['order']);
        
        foreach ($categories As $key => $val)
        {
            $category = $this->getCategoryById($val->cat_id , 1);
            
            array_push($results, $category);
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_categories_products_byids_end', $arrayParameters);
        
        return $arrayParameters['results'];
        
    }
    
    public function getSubCategoriesProducts($categoryId, $products, $onlyvalid = false,  $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_categories_products_byids_start', $arrayParameters);
        
        // Service implementation start
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        $prdSrv = $this->getServiceManager()->get('MelisComProductService');
        
        $subCats = $melisEcomCategoryTable->getEntryByField('cat_father_cat_id', $categoryId);
        
        foreach ($subCats As $key => $val)
        {
            $relProducts = $prdSrv->getProductsByCategoryId($val->cat_id, $arrayParameters['onlyvalid'], $langId);
            
            $products = ArrayUtils::merge($products, $relProducts);
            
            $products = $this->getSubCategoriesProducts($val->cat_id, $products, $arrayParameters['langId']);
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $products;
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_categories_products_byids_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    
    /**
     *
     * This method gets the affected countries of a category
     *
     * @param int $categoryId Category Id to look for
     *
     * @return MelisEcomCountry|null Country object
     */
    public function getCategoryCountriesById($categoryId, $onlyValid = false)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'category-' . $categoryId . '-getCategoryCountriesById_' . $categoryId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_countries_byid_start', $arrayParameters);
        
        // Service implementation start
        
        if (!is_null($arrayParameters['categoryId']))
        {
            $melisEcomCountryCategoryTable = $this->getServiceManager()->get('MelisEcomCountryCategoryTable');
            $catCountriesData = $melisEcomCountryCategoryTable->getEntryByField('ccat_category_id', $arrayParameters['categoryId'])->current();
            
            if (!empty($catCountriesData))
            {
                $melisEcomCountryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
                
                if ($catCountriesData->ccat_country_id == '-1')
                {
                    $status = ($arrayParameters['onlyValid']) ? 1 : 0;
                    
                    if ($arrayParameters['onlyValid'])
                    {
                        $melisCategoryCountry = $melisEcomCountryTable->getEntryByField('ctry_status', 1);
                    }
                    else 
                    {
                        $melisCategoryCountry = $melisEcomCountryTable->fetchAll();
                    }
                    
                    foreach ($melisCategoryCountry As $val)
                    {
                        array_push($results, $val);
                    }
                }
                else
                {
                    // Getting Category Countries under category ID
                    $melisCategoryCountry = $melisEcomCountryTable->getCategoryCountriesByCategoryId($arrayParameters['categoryId'], $arrayParameters['onlyValid']);
                    foreach ($melisCategoryCountry As $val)
                    {
                        array_push($results, $val);
                    }
                }
            }
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Service implementation end
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_countries_byid_end', $arrayParameters);
        
        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This method gets the affected translations of a category
     * 
     * @param int $categoryId, id of the category
     * @param int $langId, langauge id of the category match on melis_ecom_category_trans.catt_lang_id else this will return available translation
     * @param boolean $onlyValid, true return only active status else return all
     * @return MelisEcomCategory[]|null MelisEcomCategory object
     */
    public function getCategoryTranslationById($categoryId, $langId = null, $onlyValid = false)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'category-' . $categoryId . '-getCategoryTranslationById_' . $categoryId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
        
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_category_translations_start', $arrayParameters);
        
        // Service implementation start
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        $melisCategoryTranslation = $melisEcomCategoryTable->getCategoryTranslationBylangId($arrayParameters['categoryId'], $arrayParameters['langId'], $arrayParameters['onlyValid']);
        
        foreach ($melisCategoryTranslation As $val)
        {
            array_push($results, $val);
        }
        
        if (empty($results))
        {
            $catText = $melisEcomCategoryTable->getCategoryTranslationBylangId($arrayParameters['categoryId'], null, $arrayParameters['onlyValid'])->current();
            
            if (!empty($catText))
            {
                array_push($results, $catText);
            }
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_category_translations_end', $arrayParameters);
        
        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }
    
    /**
     *
     * This method gets the whole list of sub categories Id from a category
     * The $categoryId will also be included in the results.
     * If $onlyValid is used, subcategories of an unactive category won't come back
     *
     * @param int $categoryId Category Id to look for
     * @param boolean $onlyValid if true, returns only active status and valid range of dates categories
     *
     * @return int[] List of category ids
     */
    public function getAllSubCategoryIdById($categoryId, $onlyValid = false)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_list_subcategories_byid_start', $arrayParameters);
        
        // Service implementation start
        $melisCategory = array();
        
        $melisCategoryDataRes = $this->getSubCategoryIdByIdRecursive($arrayParameters['categoryId'], $arrayParameters['onlyValid']);
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $melisCategoryDataRes;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_list_subcategories_byid_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * Getting Categories under Category ID
     * This function will return children categories of the parent category
     * Recursive Function
     * @param int $categoryId
     * @param boolean $onlyValid
     * @param int $fatherId If Zero (0), this will return the root of the category
     * @return int Array()
     */
    public function getSubCategoryIdByIdRecursive($categoryId, $onlyValid = false, $fatherId = 0, $langId = null)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'category-' . $categoryId . '-getSubCategoryIdByIdRecursive_' . $categoryId . '_' . $onlyValid . '_' . $fatherId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
        
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_sub_category_recursive_start', $arrayParameters);
        
        $categoryId = $arrayParameters['categoryId'];
        $onlyValid = $arrayParameters['onlyValid'];
        $fatherId = $arrayParameters['fatherId'];
        $langId = $arrayParameters['langId'];
        
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        
        
        $dataCategoryData = $melisEcomCategoryTable->getSubCategoryIdById($categoryId, $onlyValid, $fatherId, $langId)->toArray();
        
        foreach ($dataCategoryData As $key => $val)
        {
            $fatherId = $dataCategoryData[$key]['cat_id'];
            $dataCategoryData[$key]['cat_children'] = $this->getSubCategoryIdByIdRecursive($categoryId, $onlyValid, $fatherId, $langId);
        }
        
        $results = $dataCategoryData;
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_sub_category_recursive_end', $arrayParameters);
        
        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }
    
    
    /**
     * This method is retrieving the Categories from the top
     * to the root of the Category
     * @param int $parentId, parent id of the category
     * @param int $includeRoot, and option if the root "-1" is included to result
     * @param array $category, result of the function
     * @return array
     */
    public function getParentCategory($parentId, $category, $includeRoot = false, $langId = null, $addSeo = false)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_parent_category_start', $arrayParameters);
        
        // Service implementation start
        
        /**
         * Retreiving Category data using Category service
         */
        $categoryTbl = $this->getServiceManager()->get('MelisEcomCategoryTable');
        $categoryRes = $categoryTbl->getParentCategory($arrayParameters['parentId'], $arrayParameters['langId'], $arrayParameters['addSeo']);
        
        if (!empty($categoryRes))
        {
            $categoryRes = $categoryRes[0];
            
            // Checking if the Parent Id is not -1 which is the root of categories
            if ($categoryRes['cat_father_cat_id'] != -1)
            {
                array_push($category, $categoryRes);
                $category = $this->getParentCategory($categoryRes['cat_father_cat_id'], $category, $arrayParameters['includeRoot'], $arrayParameters['langId'], $arrayParameters['addSeo']);
            }
            else
            {
                if ($arrayParameters['includeRoot'])
                {
                    array_push($category, $categoryRes);
                }
            }
        }
        
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $category;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_parent_category_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     *
     * This method saves a category in database.
     *
     * @param array $category Reflects the melis_ecom_category table
     * @param array $categoryTranslations Reflects the melis_ecom_category_trans table
     * @param array $categoryCountries Reflects the melis_ecom_country_category table
     * @param array $categorySeo Reflects the melis_ecom_seo table
     * @param int $categoryId If specified, an update will be done instead of an insert
     *
     * @return int|null The category id created or updated, null if an error occured
     */
    public function saveCategory($category, $categoryTranslations = array(),
        $categoryCountries = array(), $categorySeo = array(), $categoryId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_save_start', $arrayParameters);
        
        // Service implementation start
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        
        $catId = null;
        try
        {
            $catId = $melisEcomCategoryTable->save($arrayParameters['category'], $arrayParameters['categoryId']);
        }
        catch (\Exception $e)
        {
            
        }
        
        if(is_numeric($catId))
        {
            
            $successflag = true;
            
            // Saving Category Translations
            $categoryTranslations = $arrayParameters['categoryTranslations'];
            foreach ($categoryTranslations As $val)
            {
                $cattId = (!empty($val['catt_id'])) ? $val['catt_id'] : null;
                unset($val['catt_id']);
                $val['catt_category_id'] = $catId;
                $result = $this->saveCategoryTranslations($val, $cattId);
                
                if ($result!=true)
                {
                    return null;
                }
            }
            
            // Saving Category Countries
            $this->saveCategoryCountries($catId, $arrayParameters['categoryCountries']);
            // Saving Category SEO
            
            // SEO Service
            $categorySeo = $arrayParameters['categorySeo'];
            $melisComSeoService = $this->getServiceManager()->get('MelisComSeoService');
            $result = $melisComSeoService->saveSeoDataAction('category', $catId, $categorySeo);
            
            if ($result!=true)
            {
                return null;
            }

            $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
            $commerceCacheService->deleteCache('category', $catId, $arrayParameters['category']['cat_father_cat_id'] ?? null);

            // Assign catId to Result As return value
            $results = $catId;
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_save_byid_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     *
     * This method saves the text translations for a category
     * This will create/update entries and delete the one that could exist and not linked anymore,
     * the list of attributes must be full.
     *
     * @param array $categoryTranslations Reflects the melis_ecom_category_trans table
     * @param int $categoryId Category Id to look for
     *
     * @return boolean True/false if the translations were successfuly added to the category
     */
    public function saveCategoryTranslations($categoryTranslations, $categoryTranslationId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_save_translations_start', $arrayParameters);
        $successFlag = false;
        // Service implementation start
        $melisEcomCategoryTransTable = $this->getServiceManager()->get('MelisEcomCategoryTransTable');
        
        $categoryTranslations = $arrayParameters['categoryTranslations'];
        $categoryTranslationId = $arrayParameters['categoryTranslationId'];
        
        if (is_null($categoryTranslationId))
        {
            try
            {
                $successFlag = true;
                $melisEcomCategoryTransTable->save($categoryTranslations);
            }
            catch(\Exception $e)
            {
                $successFlag = false;
            }
        }
        else
        {
            try
            {
                $successFlag = true;
                $melisEcomCategoryTransTable->save($categoryTranslations, $categoryTranslationId);
            }
            catch(\Exception $e)
            {
                $successFlag = false;
            }
        }
        
        if (!empty($categoryTranslations['catt_category_id']))
        {
            $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
            $commerceCacheService->deleteCache('category', $categoryTranslations['catt_category_id']);
        }
        
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $successFlag;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_save_translations_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     *
     * This method saves the list of countries of a category
     * This will delete all existing entries and create new entries,
     * the list of categories must be full.
     *
     * @param int $categoryId Category Id to look for
     * @param array $categoryCountries Reflects the melis_ecom_country_category table
     *
     * @return boolean True/false if the countries were successfuly added to the category
     */
    public function saveCategoryCountries($categoryId, $categoryCountries)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_save_countries_start', $arrayParameters);
        $successFlag = false;
        // Service implementation start
        $melisEcomCountryCategoryTable = $this->getServiceManager()->get('MelisEcomCountryCategoryTable');
        
        // Deleting existing data that has same Category ID
        $numDeleted = $melisEcomCountryCategoryTable->deleteByField('ccat_category_id',$arrayParameters['categoryId']);
        if ($numDeleted)
        {
            $successFlag = true;
        }
        
        $paramCategoryCountries = $arrayParameters['categoryCountries'];
        if (!empty($paramCategoryCountries)&&is_array($paramCategoryCountries))
        {
            foreach ($paramCategoryCountries As $key => $val)
            {
                $paramCategoryCountries[$key]['ccat_category_id'] = $arrayParameters['categoryId'];
                $melisEcomCountryCategoryTable->save($paramCategoryCountries[$key]);
                $successFlag = true;
            }
        }
        // Service implementation end
        $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
        $commerceCacheService->deleteCache('category', $categoryId);
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $successFlag;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_save_countries_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * Get Category SEO
     * @param int $categoryid
     * @return MelisEcomSeo Object|empty array
     */
    public function getCategorySeoById($categoryId = null, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_seo_start', $arrayParameters);
        
        
        if (!is_null($arrayParameters['categoryId']))
        {
            $ecomSeotable = $this->getServiceManager()->get('MelisEcomSeoTable');
            $data = $ecomSeotable->getCategorySeoById($arrayParameters['categoryId'], $arrayParameters['langId']);
            foreach($data as $seo){
                array_push($results, $seo);
            }
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_seo_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * Get Category Tree View, listing all sub category by using the FatherID/ParentID of the Category
     * @param int $fatherId
     * @param int $langId
     * @return int Array
     */
    public function getCategoryTreeview($fatherId = null, $langId = null, $onlyValid = false)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_category_tree_view_start', $arrayParameters);
        
        $fatherId = $arrayParameters['fatherId'];
        $langId = $arrayParameters['langId'];
        $onlyValid= $arrayParameters['onlyValid'];
        
        $melisEcomCategoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        $categoryData = $melisEcomCategoryTable->getCategoryByFatherId($fatherId, $onlyValid);

        $catData = $categoryData->toArray();

        /**
         * TEMPORARY, NEED TO CREATE GENERAL HELPER FOR THIS
         */
        $escaper = new \Laminas\Escaper\Escaper('utf-8');

        foreach ($catData As $key => $val)
        {
            // Getting Category Name
            $categoryData = $this->getCategoryById($val['cat_id'], null, $onlyValid);
            $category = $categoryData->getTranslations();

            $catName = '';
            $catNameLangName = '';
            foreach ($category As $val)
            {
                if ($val->elang_id == $langId)
                {
                    if (!empty($val->catt_name)){
                        $catName = $val->catt_name;
                        $catNameLangName = '';
                        break;
                    }
                }
                elseif (!empty($val->catt_name))
                {
                    // Getting available Name concatinated with the Language Name
                    $catName = $val->catt_name;
                    $catNameLangName = $val->elang_name;
                }
            }

            $catData[$key]['text'] = $escaper->escapeHtml($catName); //$tool->escapeHtml($catName);
            $catData[$key]['textLang'] = (!empty($catNameLangName)) ? '('.$catNameLangName.')' : '';

            $fatherId = $catData[$key]['cat_id'];

            $catData[$key]['children'] = $this->getCategoryTreeview($fatherId, $langId, $onlyValid);

        }

        $results = $catData;
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_category_tree_view_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * Add New Product Entry to Category
     *
     * @param array $categoryProducts
     * @param int $categoryProductsId
     *
     * @return boolean True if success, otherwise false
     */
    public function addCategoryProduct($categoryProducts, $categoryProductsId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $successFlag = false;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_product_add_start', $arrayParameters);
        
        $melisEcomProductCategoryTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
        
        try
        {
            
            $relProducts = $arrayParameters['categoryProducts'];
            
            // Getting category Product Order
            $categoryId = $relProducts['pcat_cat_id'];
            $categoryProductsOrder = $this->getCategoryProductsById($categoryId);
            
            // only increment pcat_order if it is not set
            if(!$relProducts['pcat_order']) {
                $relProducts['pcat_order'] = count($categoryProductsOrder) + 1;
            }
            
            if((int) $arrayParameters['categoryProductsId']) {
                $successFlag = $melisEcomProductCategoryTable->save($relProducts, $arrayParameters['categoryProductsId']);
            }
            else {
                unset($relProducts['pcat_id']);
                $successFlag = $melisEcomProductCategoryTable->save($relProducts);
            }
            
            
            
        }
        catch(\Exception $e)
        {
            
        }
        
        if($successFlag) {
            $successFlag = true;
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $successFlag;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_product_add_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * Update Category Products Order
     * @param int $categoryProducts
     * @return boolean true if success, otherwise false
     */
    public function updateCategoryProductsOrdering($categoryProductId, $categoryProductOrder)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $successFlag = false;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_product_order_start', $arrayParameters);
        
        $catProdId = $arrayParameters['categoryProductId'];
        
        $melisEcomProductCategoryTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
        
        try
        {
            $orderNum = (int) $arrayParameters['categoryProductOrder'];
            $prodCatData = $melisEcomProductCategoryTable->getEntryByField('pcat_cat_id', $arrayParameters['categoryProductId'])->toArray();
            foreach($prodCatData as $data) {
                if((int) $data['pcat_order'] === $orderNum) {
                    $orderNum += 1;
                }
            }
            
            $relProductsOrder = array(
                'pcat_order' => $orderNum
            );
            
            $melisEcomProductCategoryTable->save($relProductsOrder, $catProdId);
            $successFlag = true;
        }
        catch(\Exception $e)
        {
            $successFlag = false;
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $successFlag;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_product_order_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * Delete Categpry Product
     * @param int $categoryProductId, primary key of the entry
     * @return boolean true if success, otherwise false
     */
    public function deleteCategoryProduct($categoryProductId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $successFlag = false;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_product_delete_start', $arrayParameters);
        
        $melisEcomProductCategoryTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
        $categoryProduct = $melisEcomProductCategoryTable->getEntryById($arrayParameters['categoryProductId'])->current();
        if (!empty($categoryProduct))
        {
            try
            {
                $categoryId = $categoryProduct->pcat_cat_id;
                
                $melisEcomProductCategoryTable->deleteById($arrayParameters['categoryProductId']);
                
                // Reorder all Category Products after deletion
                $melisEcomProductCategoryTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
                $categoryProducts = $melisEcomProductCategoryTable->getCategoryProductsByCategoryId($categoryId)->toArray();
                
                $ctr = 1;
                $relProducts = array();
                foreach ($categoryProducts As $key => $val)
                {
                    // Saving new Category Product Order
                    $this->updateCategoryProductsOrdering($val['pcat_id'], $ctr++);
                }
                
                $successFlag = true;
            }
            catch(\Exception $e)
            {
                $successFlag = false;
            }
        }
        
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $successFlag;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_product_delete_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This method will setup the ordering of product list on category
     * @param int $categoryId
     * @return Boolean
     */
    public function reorderProductCategory($categoryId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $successFlag = false;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_product_reorder_start', $arrayParameters);
        $categoryId = (int) $arrayParameters['categoryId'];
        $productCategoryTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
        $prodCatData = $productCategoryTable->getEntryByField('pcat_cat_id', $categoryId)->toArray();
        $orders = array();
        $prodCatIds = array();
        $newOrders = array();
        $productId = array();
        if($prodCatData) {
            
            // store product category data temporarily
            foreach($prodCatData as $data) {
                $prodCatIds[] = $data['pcat_id'];
                $orders[] = $data['pcat_order'];
                $productId[] = $data['pcat_prd_id'];
            }
            
            // sort orders
            usort($orders, function($a, $b) {
                if ($a == $b) {
                    return 0;
                }
                return ($a < $b) ? -1 : 1;
            });
                
                foreach($orders as $orderNum => $order) {
                    $newOrders[] = $orderNum+1;
                }
                
                // save new order
                $ctr = 0;
                foreach($prodCatIds as $id) {
                    $productCategoryTable->save(array(
                        'pcat_order' => $newOrders[$ctr],
                        'pcat_prd_id' => $productId[$ctr],
                        'pcat_cat_id' => $categoryId),
                        $id);
                    $ctr++;
                }
                
                $successFlag = true;
        }
        
        
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $successFlag;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_product_reorder_start', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This method is retrieving the father category of the category ID
     *
     * @param int $categoryId, Id of the category
     * @param int $langId,langauge id
     *
     * @return array
     */
    public function getCategoryBreadCrumb($categoryId, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_father_category_start', $arrayParameters);
        
        // Service implementation start
        
        /**
         * Retreiving Category data using Category service
         */
        $categoryTbl = $this->getServiceManager()->get('MelisEcomCategoryTable');
        $categoryRes = $categoryTbl->getFatherCategory($arrayParameters['categoryId'], $arrayParameters['langId']);
        
        if($categoryRes->count()){
            $results = $categoryRes->current();
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_father_category_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }

    /**
     * This will get all children of a category
     *
     * @param int $fatherId
     * @param int $langId
     * @param boolean $valid
     * @return mixed
     */
    public function getChildrenByLangId($fatherId, $langId, $valid, $order = false) {
        //prepare events parameters
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        //service event start
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_valid_children_by_lang_id', $arrayParameters);

        //implementation start
        $categoryTable = $this->getServiceManager()->get('MelisEcomCategoryTable');
        $categories = $categoryTable->getChildrenByLangId($arrayParameters['fatherId'], $arrayParameters['langId'], $arrayParameters['valid'], $arrayParameters['order']);

        $results = $categories;
        //implementation end

        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_valid_children_by_lang_id_end', $arrayParameters);

        return $arrayParameters['results'];
    }
}
