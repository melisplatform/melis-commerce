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
/**
 * This plugin implements the business logic of the
 * "category price filter" plugin.
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
 * $plugin = $this->MelisCommerceFilterMenuPriceValueBoxPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceFilterMenuPriceValueBoxPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCms/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'filterMenuPriceValue');
 * 
 * How to display in your controller's view:
 * echo $this->filterMenuPriceValue;
 * 
 * 
 */
class MelisCommerceFilterMenuPriceValueBoxPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
       $priceTbl = $this->getServiceLocator()->get('MelisEcomPriceTable');
       $categorySvc = $this->getServiceLocator()->get('MelisComCategoryService');
       
       $priceType = $this->pluginFrontConfig['price_type'];
       $type = $this->pluginFrontConfig['type'];       
       $categoryId = !empty($this->pluginFrontConfig['m_box_filter_categories_ids_selected']) ? $this->pluginFrontConfig['m_box_filter_categories_ids_selected'] : array();
       
       foreach($categoryId as $catId){
           $categoryId = array_merge($categoryId, $this->categoryIdIterator($categorySvc->getAllSubCategoryIdById($catId)));
       }
       
       $priceMin = $priceTbl->getPriceByColumnOrder('ASC', $priceType, $categoryId)->current();
       $priceMax = $priceTbl->getPriceByColumnOrder('DESC', $priceType, $categoryId)->current();
       $defaultMin =  ($priceMin)? $priceMin->$priceType: 0;
       $defaultMax =  ($priceMax)? $priceMax->$priceType: 1000;
       $min = !empty($this->pluginFrontConfig['m_box_filter_price_min'])? $this->pluginFrontConfig['m_box_filter_price_min'] : $defaultMin;
       $max = !empty($this->pluginFrontConfig['m_box_filter_price_max'])? $this->pluginFrontConfig['m_box_filter_price_max'] : $defaultMax;
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $priceConfig = array(
            'm_box_filter_price_min' =>  (int)$min,
            'm_box_filter_price_max' => (int)$max,
            'defaultMin' => (int)$defaultMin,
            'defaultMax' => (int)$defaultMax,
        );
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'filterMenuPriceValue' => $priceConfig
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
