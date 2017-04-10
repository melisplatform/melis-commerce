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
 * "show related product plugin" plugin.
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
 * $plugin = $this->MelisCommerceProductsRelatedPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceProductsRelatedPlugin();
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
class MelisCommerceProductsRelatedPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
               
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)       
        $productId = ($this->pluginFrontConfig['m_p_id'])? $this->pluginFrontConfig['m_p_id'] : NULL;
        
        $productSvc = $this->getServiceLocator()->get('MelisComProductService');
        $assocProducts = $productSvc->getAssocProducts($productId);
        $data = array();
        
        foreach($assocProducts as $productObj){
            
            // Retrieve display price, check MelisComProductService for available paremeters
            $productObj->display_price = $productSvc->getProductVariantPriceById($productObj->getId());
            $data[]= $productObj;     
        }
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'relatedProducts' => $data
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
