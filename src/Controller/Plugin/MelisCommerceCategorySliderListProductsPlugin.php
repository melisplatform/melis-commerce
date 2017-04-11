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
use Zend\Session\Container;
use Zend\Stdlib\ArrayUtils;
/**
 * This plugin implements the business logic of the
 * "Products slider" plugin.
 * 
 * Please look inside app.plugins.products.php for possible awaited parameters
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
 * $plugin = $this->MelisCommerceCategorySliderListProductsPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCategorySliderListProductsPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCms/melis-demo-cms'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'sliderList');
 * 
 * How to display in your controller's view:
 * echo $this->sliderList;
 * 
 * 
 */
class MelisCommerceCategorySliderListProductsPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.product.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $container = new Container('melisplugins');
        $lang = $container['melis-plugins-lang-id'];
        $categoryListProducts = array();
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        // $showMainVariant default TRUE, main variant will be displayed and if set to false this plugin will retrieve
        $categoryProductSliderTemplate =  !empty($this->pluginFrontConfig['template_path'])? $this->pluginFrontConfig['template_path'] : '';
        $fieldType = !empty($this->pluginFrontConfig['m_box_filter_field_type'])? $this->pluginFrontConfig['m_box_filter_field_type'] : array();
        $categoryId = !empty($this->pluginFrontConfig['m_box_filter_categories_ids_selected']) ? $this->pluginFrontConfig['m_box_filter_categories_ids_selected'] : array();
        $country = !empty($this->pluginFrontConfig['m_box_filter_country'])? $this->pluginFrontConfig['m_box_filter_country'] : null;
        $docTypes = !empty($this->pluginFrontConfig['m_box_filter_docs'])? $this->pluginFrontConfig['m_box_filter_docs'] : array();
        $priceColumn = !empty($this->pluginFrontConfig['priceColumn'])? $this->pluginFrontConfig['priceColumn'] : 'price_net';
        $data  = array();
        
        $productSearchSvc = $this->getServiceLocator()->get('MelisComProductSearchService');
        $productSvc = $this->getServiceLocator()->get('MelisComProductService');
        $currencySvc = $this->getServiceLocator()->get('MelisComCurrencyService');
        $categorySvc = $this->getServiceLocator()->get('MelisComCategoryService');
        
        foreach($categoryId as $catId){
            $categoryId = array_merge($categoryId, $this->categoryIdIterator($categorySvc->getAllSubCategoryIdById($catId, true)));
        }
        
        $data = $productSearchSvc->getProductByCategory($categoryId, $lang, $country,$fieldType, $docTypes);
        
        $currency = $currencySvc->getDefaultCurrency();
        
        foreach($data as $categoryProduct){
            
            // get lowest variant price, maily used for displayig
            $priceObj = $productSvc->getProductVariantPriceById($categoryProduct->prd_id);
        
            if(!empty($priceObj)){
                if(empty($priceObj->cur_symbol)){
                    // use default currency if price symbol is not set
                    if($priceObj->$priceColumn){
                        $categoryProduct->display_price = $currency->cur_symbol . $priceObj->$priceColumn;
                    }
                }else{
                    if($priceObj->$priceColumn){
                        $categoryProduct->display_price = $priceObj->cur_symbol . $priceObj->$priceColumn;
                    }
                }
            }else{
                $symbol = empty($categoryProduct->cur_symbol)? $currency->cur_symbol : $categoryProduct->cur_symbol;
                $categoryProduct->display_price = $symbol . $categoryProduct->$priceColumn;
            }
            
            $categoryListProducts[] = $categoryProduct;
        }
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'categorySliderListProducts' => $categoryListProducts,
            'langId' => $lang,
        );
       
        // return the variable array and let the view be created
        return $viewVariables;
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
