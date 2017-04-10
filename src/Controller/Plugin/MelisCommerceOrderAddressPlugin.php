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
use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Stdlib\ArrayUtils;
/**
 * This plugin implements the business logic of the
 * "order address" plugin.
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
 * $plugin = $this->MelisCommerceOrderAddressPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceOrderAddressPlugin();
 * $parameters = array(
 *      'template_path' => 'your-site-folder/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'orderAddress');
 * 
 * How to display in your controller's view:
 * echo $this->orderAddress;
 * 
 * 
 */
class MelisCommerceOrderAddressPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $clientId = null;
        $billing = array();
        $delivery = array();
        $addresses = array();
        
        $orderId = !empty($this->pluginFrontConfig['m_c_order'])? $this->pluginFrontConfig['m_c_order'] : null;
        $container = new Container('melisplugins');
        $lang = $container['melis-plugins-lang-id'];
        
        $orderSvc = $this->getServiceLocator()->get('MelisComOrderService');
        if(!empty($orderId)){
            $addresses = $orderSvc->getOrderAddressesByOrderId($orderId, $lang);
        }
        
        foreach($addresses as $address){
            switch($address->oadd_type){
                case(1):
                    // Billing address
                    $billing = $address->getArrayCopy();
                    break;
                case(2):
                    // delivery address
                    $delivery = $address->getArrayCopy();
                    break;
                default:
                    break;
            }
        }
        
        $viewVariables = array(
            'deliveryAddress' => $delivery,
            'billingAddress' => $billing,
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    public function back()
    {
        
    }
}
