<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

use MelisEngine\Controller\Plugin\MelisTemplatingPlugin;
use MelisFront\Navigation\MelisFrontNavigation;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
/**
 * This plugin implements the business logic of the
 * "Breadcrumb" plugin.
 * 
 * Please look inside app.plugins.php for possible awaited parameters
 * in front and back function calls.
 * 
 * front() and back() are the only functions to create / update.
 * front() generates the website view
 * back() generates the plugin view in template edition mode (TODO)
 * 
 * Configuration can be found in $pluginConfig / $pluginFrontConfig / $pluginBackConfig
 * Configuration is automatically merged with the parameters provided when calling the plugin.
 * Merge detects automatically from the route if rendering must be done for front or back.
 * 
 * How to call this plugin without parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/breadcrumb/breadcrumb'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'breadcrumb');
 * 
 * How to display in your controller's view:
 * echo $this->breadcrumb;
 * 
 * 
 */
class MelisCommerceCategoryListProductsPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $categoryListProducts = array();
        $categorySvc = $this->getServiceLocator()->get('MelisComCategoryService');
        $productSearchSvc = $this->getServiceLocator()->get('MelisComProductSearchService');
        $productSvc = $this->getServiceLocator()->get('MelisComProductService');
        $productTable = $this->getServiceLocator()->get('MelisEcomProductTable');
        $currencyTbl = $this->getServiceLocator()->get('MelisEcomCurrencyTable');
        $categoryId = array(); 
        $attributeValueId = array();    
       
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $search = !empty($this->pluginFrontConfig['m_box_filter_search'])? $this->pluginFrontConfig['m_box_filter_search'] : '';
        $fieldType = !empty($this->pluginFrontConfig['m_box_filter_field_type'])? $this->pluginFrontConfig['m_box_filter_field_type'] : array();
        $min = !empty($this->pluginFrontConfig['m_box_filter_price_min'])? $this->pluginFrontConfig['m_box_filter_price_min'] : null;
        $max = !empty($this->pluginFrontConfig['m_box_filter_price_max'])? $this->pluginFrontConfig['m_box_filter_price_max'] : null;
        $priceColumn = !empty($this->pluginFrontConfig['priceColumn'])? $this->pluginFrontConfig['priceColumn'] : 'price_net';
        $lang = !empty($this->pluginFrontConfig['m_box_filter_lang'])? $this->pluginFrontConfig['m_box_filter_lang'] : null;
        $country = !empty($this->pluginFrontConfig['m_box_filter_country'])? $this->pluginFrontConfig['m_box_filter_country'] : null;
        $onlyValid = !empty($this->pluginFrontConfig['m_box_filter_only_valid'])? $this->pluginFrontConfig['m_box_filter_only_valid'] : true;
        $pageCurrent = !empty($this->pluginFrontConfig['m_pag_current']) ? $this->pluginFrontConfig['m_pag_current'] : 1;
        $pageNbPerPage = !empty($this->pluginFrontConfig['m_pag_nb_per_page'])? $this->pluginFrontConfig['m_pag_nb_per_page'] : 10;
        $pageNbBeforeAfter = !empty($this->pluginFrontConfig['m_pag_nb_page_before_after']) ? $this->pluginFrontConfig['m_pag_nb_page_before_after'] : 3;
        $attributeValueId = !empty($this->pluginFrontConfig['m_box_filter_attribute_values_ids_selected'])? $this->pluginFrontConfig['m_box_filter_attribute_values_ids_selected'] : array();
        $categoryId = !empty($this->pluginFrontConfig['m_box_filter_categories_ids_selected']) ? $this->pluginFrontConfig['m_box_filter_categories_ids_selected'] : array();
        $sortColName = !empty($this->pluginFrontConfig['m_col_name']) ? $this->pluginFrontConfig['m_col_name'] : 'prd_id';
        $sortOrder = !empty($this->pluginFrontConfig['m_order']) ? $this->pluginFrontConfig['m_order'] : 'ASC';
        $sort = $sortColName. ' ' . $sortOrder;
        
       
        foreach($categoryId as $catId){
            $categoryId = array_merge($categoryId, $this->categoryIdIterator($categorySvc->getAllSubCategoryIdById($catId, $onlyValid)));
        }
        
        $data = $productSearchSvc->searchProductFull(
            $search,                        // $search
            $fieldType,                     // $fieldsTypeCodes           
            $attributeValueId,              // $attributeValuesIds      
            $min,                           // $priceMin
            $max,                           // $priceMax
            $lang,                          // $langId
            array_unique($categoryId),      // $categoryId
            $country,                       // $countryId
            $onlyValid,                     // $onlyValid
            null,                           // $start
            null,                           // $limit
            $sort
        );
        
        // Retrieve lowest variant price

        foreach($data as $categoryProduct){ 
            $productArray = $categoryProduct;
            
            // get lowest variant price, maily used for displayig
            $priceObj = $productTable->getProductVariantPriceById($categoryProduct->getId())->current();

            // used product price if variant price is not set
            if(empty($priceObj->price_id)){
                $priceObj = $categoryProduct->getPrice()[0];
            }

            
            // set currency symbol
            if(empty($priceObj->cur_symbol)){
                // use default currency if price symbol is not set
                $currency = $currencyTbl->getEntryByField('cur_default', 1)->current();
                if($priceObj->$priceColumn){
                    $productArray->display_price = $currency->cur_symbol . $priceObj->$priceColumn;
                }                
            }else{
                if($priceObj->$priceColumn){
                    $productArray->display_price = $priceObj->cur_symbol . $priceObj->$priceColumn;
                }                
            }
           
            $categoryListProducts[] = $productArray;
        }
        // Pagination
        $paginator = new Paginator(new ArrayAdapter($categoryListProducts));
        $paginator->setCurrentPageNumber($pageCurrent)
        ->setItemCountPerPage($pageNbPerPage);

        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'categoryListProducts' => $paginator,
            'nbPageBeforeAfter' => $pageNbBeforeAfter,
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    /**
     * This function return the back office rendering for the template edition system
     * TODO
     */
    public function back()
    {
        return array();
    }
    
    /**
     * Recurssive function to retrieve category ids
     * @param [] $categories array of categories
     * @return $categoryId[]
     */
    private function categoryIdIterator($categories)
    {
        $categoryId = array();
        foreach($categories as $category){
            $categoryId[] = $category['cat_id'];
            if(is_array($category['cat_children'])){
                $categoryId = array_merge($categoryId, $this->categoryIdIterator($category['cat_children']));
            }
        }
        return $categoryId;
    }
}
