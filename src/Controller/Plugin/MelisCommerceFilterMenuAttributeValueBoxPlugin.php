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
/**
 * This plugin implements the business logic of the
 * "Filter menu attribute value" plugin.
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
 * $plugin = $this->MelisCommerceFilterMenuAttributeValueBoxPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceFilterMenuAttributeValueBoxPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCms/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'filterMenuAttributeValue');
 * 
 * How to display in your controller's view:
 * echo $this->filterMenuAttributeValue;
 * 
 * 
 */
class MelisCommerceFilterMenuAttributeValueBoxPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.product.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $selected = array();
        $container = new Container('melisplugins');
        $lang = $container['melis-plugins-lang-id'];
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $attributeId = !empty($this->pluginFrontConfig['attribute_id']) ? $this->pluginFrontConfig['attribute_id'] : 1;
        $selectedAttrVal = !empty($this->pluginFrontConfig['m_box_filter_attribute_values_ids_selected']) ? $this->pluginFrontConfig['m_box_filter_attribute_values_ids_selected'] : array();
        foreach($selectedAttrVal as $attrVal){
            $selected = array_merge($selected, $attrVal);
        }
        // Retrieve data from the melis services
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        $attributeValuesObj = $attributeSvc->getAttributeValuesByAttributeId($attributeId , $lang);        
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'filterMenuAttributeValue' => $attributeValuesObj,
            'selectedAttributes' => $selected,
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
