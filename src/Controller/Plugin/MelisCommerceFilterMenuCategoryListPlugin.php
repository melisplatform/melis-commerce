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
 * "filter menu category list" plugin.
 * 
 * Please look inside app.plugins.categories.php for possible awaited parameters
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
 * $plugin = $this->MelisCommerceFilterMenuCategoryListPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceFilterMenuCategoryListPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCms/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'filterMenuCategoryList');
 * 
 * How to display in your controller's view:
 * echo $this->filterMenuCategoryList;
 * 
 * 
 */
class MelisCommerceFilterMenuCategoryListPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
       
        $categories = array();
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $parentCategory = $this->pluginFrontConfig['parent_category_id'];
        $selectedCategories = $this->pluginFrontConfig['m_box_filter_categories_ids_selected'];
        $active = !empty($this->pluginFrontConfig['m_box_filter_categories_active'])? $this->pluginFrontConfig['m_box_filter_categories_active'] : 1;
        $lang = !empty($this->pluginFrontConfig['m_box_filter_categories_lang'])? $this->pluginFrontConfig['m_box_filter_categories_lang'] : NULL;
        $categorySvc = $this->getServiceLocator()->get('MelisComCategoryService');        
        
        $categories = $categorySvc->getCategoryListById($parentCategory, $lang, TRUE);
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'filterMenuCategoryList' => $categories,
            'selectedCategories' => $selectedCategories,
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
