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

use Zend\Mvc\Controller\Plugin\Redirect;
/**
 * This plugin implements the business logic of the
 * "account" plugin.
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
 * $plugin = $this->MelisCommerceClientAccountPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/client/account'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'account');
 * 
 * How to display in your controller's view:
 * echo $this->account;
 * 
 * 
 */
class MelisCommerceAccountPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $pluginManager = $this->getServiceLocator()->get('ControllerPluginManager');
        
        // Getting custom param for Profile Plugin
        $profileParam = (!empty($this->pluginFrontConfig['profile_parameter'])) ? $this->pluginFrontConfig['profile_parameter'] : array();
        $clientProfilePlugin = $pluginManager->get('MelisCommerceProfilePlugin');
        $clientProfile = $clientProfilePlugin->render($profileParam);
        /**
         * Retrieving the variables from the resultview 
         * of the plugin and add as new viewVariable to return to this plugin
         */
        $clientProfileVariables = $clientProfile->getVariables();
        
        // Getting custom param for Profile Plugin
        $clientDeliveryAddressParam = (!empty($this->pluginFrontConfig['delivery_parameter'])) ? $this->pluginFrontConfig['delivery_parameter'] : array();
        $clientDeliveryAddressPlugin = $pluginManager->get('MelisCommerceDeliveryAddressPlugin');
        $clientDeliveryAddress = $clientDeliveryAddressPlugin->render($clientDeliveryAddressParam);
        /**
         * Retrieving the variables from the resultview
         * of the plugin and add as new viewVariable to return to this plugin
         */
        $clientDeliveryAddressVariables = $clientDeliveryAddress->getVariables();
        
        // Getting custom param for Profile Plugin
        $clientBillingAddressParam = (!empty($this->pluginFrontConfig['billing_parameter'])) ? $this->pluginFrontConfig['billing_parameter'] : array();
        $clientBillingAddressPlugin = $pluginManager->get('MelisCommerceBillingAddressPlugin');
        $clientBillingAddress = $clientBillingAddressPlugin->render($clientBillingAddressParam);
        /**
         * Retrieving the variables from the resultview
         * of the plugin and add as new viewVariable to return to this plugin
         */
        $clientBillingAddressVariables = $clientBillingAddress->getVariables();
        
        // Getting custom param for Profile Plugin
        $clientMyCartParam = (!empty($this->pluginFrontConfig['my_cart_parameter'])) ? $this->pluginFrontConfig['my_cart_parameter'] : array();
        $clientMyCartPlugin = $pluginManager->get('MelisCommerceCartMenuPlugin');
        $clientMyCart = $clientMyCartPlugin->render($clientMyCartParam);
        
        // Order list plugin
        $clientOrderParameter =(!empty($this->pluginFrontConfig['order_history_paremeter'])) ? $this->pluginFrontConfig['order_history_paremeter'] : array();
        $clientOrderPlugin = $pluginManager->get('MelisCommerceOrderListPlugin');
        $clientOrderHistory = $clientOrderPlugin->render($clientOrderParameter);
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'profile' => $clientProfile,
            'profile_variables' => $clientProfileVariables,
            'deliveryAddress' => $clientDeliveryAddress,
            'delivery_variables' => $clientDeliveryAddressVariables,
            'billingAddress' => $clientBillingAddress,
            'billing_variables' => $clientBillingAddressVariables,
            'myCart' => $clientMyCart,
            'orderHistory' => $clientOrderHistory,
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    /**
     * This method retrieving the address details of a client logged in
     * $parma $addId, Id of the address
     * 
     * @return MelisEcomClientAddress
     */
    public function getSelectedAddressDetailsAction($addId)
    {
        $address  = array();
         
        $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        
        if ($melisComAuthSrv->hasIdentity())
        {
            $personId = $melisComAuthSrv->getPersonId();
            $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
            $address = $clientSrv->getClientPersonAddressByAddressId($personId, $addId);
        }
         
        return $address;
    }
    
    /**
     * This method deleting the client person address
     * $parma $addId, Id of the address
     * 
     * @return Boolean
     */
    public function deleteAddressAction($addId)
    {
        $success = false;
        
        $comClientSvc = $this->getServiceLocator()->get('MelisComClientService');
        $success      = (int) $comClientSvc->deleteClientAddressByAddressId($addId);
        if($success) {
            $success = true;
        }
        
        return $success;
    }
}
