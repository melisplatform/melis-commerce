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
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        // $showMainVariant default TRUE, main variant will be displayed and if set to false this plugin will retrieve
        $categoryProductSliderTemplate =  !empty($this->pluginFrontConfig['template_path'])? $this->pluginFrontConfig['template_path'] : '';
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
        $data  = array();
                
        $categoryProductListPlugin = $this->getServiceLocator()->get('ControllerPluginManager')->get('MelisCommerceCategoryListProductsPlugin');
        $categoryProductListParemeter = array(
            'template_path' => $categoryProductSliderTemplate,
            'm_box_filter_search' => $search,
            'm_box_filter_field_type' => $fieldType,
            'm_box_filter_price_min' => $min,
            'm_box_filter_price_max' => $max,
            'priceColumn' => $priceColumn,
            'm_box_filter_lang' => $lang,
            'm_box_filter_country' => $country,
            'm_box_filter_only_valid' => $onlyValid,
            'm_pag_current' => $pageCurrent,
            'm_pag_nb_per_page' => $pageNbPerPage,
            'm_pag_nb_page_before_after' => $pageNbBeforeAfter,
            'm_box_filter_attribute_values_ids_selected' => $attributeValueId,
            'm_box_filter_categories_ids_selected' => $categoryId,
            'm_col_name' => $sortColName,
            'm_order' => $sortOrder,
        );
        $data = $categoryProductListPlugin->render($categoryProductListParemeter)->getVariables();
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'categorySliderListProducts' => $data->categoryListProducts
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
}
