<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service; 

use MelisCommerce\Model\MelisCategory;
use Zend\Form\Annotation\Object;
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
    public function getCategoryListById($categoryId = -1, $langId = null, 
                                        $onlyValid = false, $start = 0, $limit = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
                
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_list_start', $arrayParameters);
        
        // Service implementation start
        
        
        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisEcomCategoryTable');
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
    public function getCategoryListByIdRecursive($categoryId, $langId = null, $onlyValid = false,
                                                    $start = null, $limit = null, $fatherId = 0)
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
        
        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisEcomCategoryTable');
        
        $dataCategoryData = $melisEcomCategoryTable->getCategoryChildrenListById($categoryId, $langId, $onlyValid, $start, $limit, $fatherId);
        
        foreach ($dataCategoryData As $val){
            
            $melisCategory = new \MelisCommerce\Entity\MelisCategory();
            
            // Id
            $melisCategory->setId($val->cat_id);
            
            // category
            $melisCategory->setCategory($val);
            
            // trasnalations
            $catTrans = $this->getCategoryTranslationById($val->cat_id, $langId);
            $melisCategory->setTranslations($catTrans);
            // seo
            $catSeo = $this->getCategorySeoById($val->cat_id);
            $melisCategory->setSeo($catSeo);
            
            // countries
            $catCountries = $this->getCategoryCountriesById($val->cat_id);
            $melisCategory->setCountries($catCountries);
            // children
            $fatherId = $val->cat_id;
            $catChildren = $this->getCategoryListByIdRecursive($categoryId, $langId, $onlyValid, $start, $limit, $fatherId);
            $melisCategory->setChildren($catChildren);
            
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
    public function getCategoryById($categoryId, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_byid_start', $arrayParameters);
         
        // Service implementation start
        $melisCategory = new \MelisCommerce\Entity\MelisCategory();
        $melisCategory->setId($arrayParameters['categoryId']);
        
        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisEcomCategoryTable');
        
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
            $catTrans = $this->getCategoryTranslationById($category->cat_id, $arrayParameters['langId']);
            $melisCategory->setTranslations($catTrans);
            // seo
            $catSeo = $this->getCategorySeoById($category->cat_id);
            $melisCategory->setSeo($catSeo);
            
            // countries
            $catCountries = $this->getCategoryCountriesById($category->cat_id);
            $melisCategory->setCountries($catCountries);
            // children
            $fatherId = $category->cat_id;
            $catChildren = $this->getCategoryListByIdRecursive($category->cat_id, $arrayParameters['langId']);
            $melisCategory->setChildren($catChildren);
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $melisCategory;
        // Service implementation end
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_byid_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method will return the name of the category
     * @param int $categoryId category id
     * @param unknown $langId if specified this will return with the same language,
     * otherwise this will return first category name the available.
     * @return String||null
     */
    public function getCategoryNameById($categoryId, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
         
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_name_start', $arrayParameters);
        
        // Service implementation start
        
        $category = $this->getCategoryById($arrayParameters['categoryId']);
        
        $catTrans = $category->getTranslations();
        
        $catNameStr = null;
        
        if (!empty($catTrans))
        {
            
            if (!is_null($arrayParameters['langId']))
            {
                foreach ($catTrans As $val)
                {
                    // checking language id
                    if ($val->catt_lang_id == $arrayParameters['langId'])
                    {
                        $catNameStr = $val->catt_name;
                    }
                }
            }
            
            // if Category Name still emppty, this will will get the first tanslation available for category
            if (is_null($catNameStr)||ctype_space($catNameStr)||empty($catNameStr))
            {
                foreach ($catTrans As $val)
                {
                    if (!ctype_space($val->catt_name)&&!empty($val->catt_name))
                    {
                        $catNameStr = $val->catt_name.' ('.$val->elang_name.')';
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
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
         
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_products_byid_start', $arrayParameters);
        
        // Service implementation start
        $melisProduct = new \MelisCommerce\Entity\MelisProduct();
        
        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
        $melisEcomProductCategoryTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
        
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
    public function getCategoryCountriesById($categoryId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
         
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_countries_byid_start', $arrayParameters);
        
        // Service implementation start
        
        if (!is_null($arrayParameters['categoryId']))
        {
            
            $melisEcomCountryCategoryTable = $this->getServiceLocator()->get('MelisEcomCountryCategoryTable');
            $catCountriesData = $melisEcomCountryCategoryTable->getEntryByField('ccat_category_id', $arrayParameters['categoryId'])->current();
            
            if (!empty($catCountriesData))
            {
                if ($catCountriesData->ccat_country_id == '-1')
                {
                    $melisEcomCountryCategoryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
                    $melisCategoryCountry = $melisEcomCountryCategoryTable->fetchAll();
                    
                    foreach ($melisCategoryCountry As $val)
                    {
                        array_push($results, $val);
                    }
                    
                }else{
                    // Getting Category Countries under category ID
                    $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisEcomCategoryTable');
                    $melisCategoryCountry = $melisEcomCategoryTable->getCategoryCountriesByCategoryId($arrayParameters['categoryId']);
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
        
        return $arrayParameters['results'];
    }
    
    public function getCategoryTranslationById($categoryId , $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
         
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_category_translations_start', $arrayParameters);
        
        // Service implementation start
        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisEcomCategoryTable');
        $melisCategoryTranslation = $melisEcomCategoryTable->getCategoryTranslationBylangId($arrayParameters['categoryId'], $arrayParameters['langId']);
        
        if (!is_null($arrayParameters['langId']))
        {
            $results = $melisCategoryTranslation->current();
        }
        else 
        {
            foreach ($melisCategoryTranslation As $val)
            {
                array_push($results, $val);
            }
        }
        
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_category_translations_end', $arrayParameters);
        
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
    public function getSubCategoryIdByIdRecursive($categoryId, $onlyValid = false, $fatherId = 0)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
         
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_sub_category_recursive_start', $arrayParameters);
        
        $categoryId = $arrayParameters['categoryId'];
        $onlyValid = $arrayParameters['onlyValid'];
        $fatherId = $arrayParameters['fatherId'];
        
        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisEcomCategoryTable');
        
        $dataCategoryData = $melisEcomCategoryTable->getSubCategoryIdById($categoryId, $onlyValid, $fatherId);
        
        foreach ($dataCategoryData As $key => $val)
        {
            $fatherId = $dataCategoryData[$key]['cat_id'];
            $dataCategoryData[$key]['cat_children'] = $this->getSubCategoryIdByIdRecursive($categoryId, $onlyValid, $fatherId);
        }
        
        $results = $dataCategoryData;
        // Service implementation end
         
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_sub_category_recursive_end', $arrayParameters);
        
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
        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisEcomCategoryTable');
        
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
            $melisComSeoService = $this->getServiceLocator()->get('MelisComSeoService');
            $result = $melisComSeoService->saveSeoDataAction('category', $catId, $categorySeo);
            
            if ($result!=true)
            {
                return null;
            }
            
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
        $melisEcomCategoryTransTable = $this->getServiceLocator()->get('MelisEcomCategoryTransTable');
        
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
        $melisEcomCountryCategoryTable = $this->getServiceLocator()->get('MelisEcomCountryCategoryTable');
        
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
    public function getCategorySeoById($categoryid = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_seo_start', $arrayParameters);
        
        $categoryid = $arrayParameters['categoryid'];
        
        if (!is_null($categoryid))
        {
            $ecomSeotable = $this->serviceLocator->get('MelisEcomSeoTable');
            $results = $ecomSeotable->getEntryByField('eseo_category_id', $categoryid)->current();
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
    public function getCategoryTreeview($fatherId = null, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_category_tree_view_start', $arrayParameters);
        
        $fatherId = $arrayParameters['fatherId'];
        $langId = $arrayParameters['langId'];
        
        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisEcomCategoryTable');
        $categoryData = $melisEcomCategoryTable->getCategoryByFatherId($fatherId);
        $catData = $categoryData->toArray();
        
        foreach ($catData As $key => $val)
        {
            // getting category name
            $catData[$key]['text'] = $this->getCategoryNameById($val['cat_id'], $langId);
            
            $fatherId = $catData[$key]['cat_id'];
            
            $catData[$key]['children'] = $this->getCategoryTreeview($fatherId, $langId);
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
        
        $melisEcomProductCategoryTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
        
        try
        {
            $catProducts = $arrayParameters['categoryProducts'];
            
            // Getting category Product Order
            $categoryId = $catProducts['pcat_cat_id'];
            $categoryProductsOrder = $this->getCategoryProductsById($categoryId);
            
            // only increment pcat_order if it is not set
            if(!$catProducts['pcat_order']) {
                $catProducts['pcat_order'] = count($categoryProductsOrder) + 1;
            }
            
            if(!$catProducts['pcat_id']) { 
                $catProducts['pcat_id'] = null; 
            }

            $successFlag = $melisEcomProductCategoryTable->save($catProducts, $arrayParameters['categoryProductsId']);

            
        }
        catch(\Exception $e)
        {
            echo $e->getMessage();
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
        
        $melisEcomProductCategoryTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
        
        try
        {
            $orderNum = (int) $arrayParameters['categoryProductOrder'];
            $prodCatData = $melisEcomProductCategoryTable->getEntryByField('pcat_cat_id', $arrayParameters['categoryProductId'])->toArray();
            foreach($prodCatData as $data) {
                if((int) $data['pcat_order'] === $orderNum) {
                    $orderNum += 1;
                }
            }
            
            $catProductsOrder = array(
                'pcat_order' => $orderNum
            );
            
            $melisEcomProductCategoryTable->save($catProductsOrder, $catProdId);
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
     * @param int $categoryProductId
     * @return boolean true if success, otherwise false
     */
    public function deleteCategoryProduct($categoryProductId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $successFlag = false;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_product_delete_start', $arrayParameters);
        
        $melisEcomProductCategoryTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
        $categoryProduct = $melisEcomProductCategoryTable->getEntryById($arrayParameters['categoryProductId'])->current();
        if (!empty($categoryProduct))
        {
            try
            {
                $categoryId = $categoryProduct->pcat_cat_id;
                
                $melisEcomProductCategoryTable->deleteById($arrayParameters['categoryProductId']);
            
                // Reorder all Category Products after deletion
                $melisEcomProductCategoryTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
                $categoryProducts = $melisEcomProductCategoryTable->getCategoryProductsByCategoryId($categoryId)->toArray();
            
                $ctr = 1;
                $catProducts = array();
                foreach ($categoryProducts As $key => $val)
                {
                    // Saving new Category Product Order
                    $this->updateCategoryProductsOrdering($val['pcat_id'], $ctr++);
                }
                
                $successFlag = true;
            }
            catch(\Exception $e)
            {
                echo $e->getMessage();
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
    
    public function reorderProductCategory($categoryId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $successFlag = false;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_product_reorder_start', $arrayParameters);
        $categoryId = (int) $arrayParameters['categoryId'];
        $productCategoryTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
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
}