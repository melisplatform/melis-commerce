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
 * "checkOut" plugin.
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
 * $plugin = $this->MelisCommerceCheckoutCartPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceConfirmSummaryPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'checkOut');
 * 
 * How to display in your controller's view:
 * echo $this->checkOut;
 * 
 * 
 */
class MelisCommerceCheckoutConfirmSummaryPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $success = 0;
        $errors = array();
        $orderId = null;
        
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $siteId = (!empty($this->pluginFrontConfig['m_site_id'])) ? $this->pluginFrontConfig['m_site_id'] : 1;
        
        /**
         * Login using the Commerce Athentication Service
         */
        $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $clientId = $melisComAuthSrv->getClientId();
        
        /**
         * Checkout Order validation before proceed to payment
         * if this will success this will create order record with temporary Order status
         * else this will show errors
         * 
         * Return value structure of checkoutStep1_prePayment method:
         * array(
         *      'success' => true/false
         *      'clientId' => xx,
         *      'orderId' => xx,
         *      'errors' => array(
         *          'basket' => BasketValidityArray,
         *          'addresses' => ShipmentCostArray,
         *          'costs' => OrderCostArray
         *      ),
         * )
         * 
         */
        $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
        $melisComOrderCheckoutService->setSiteId($siteId);
        $checkoutAddOrderValidation = $melisComOrderCheckoutService->checkoutStep1_prePayment($clientId);
        
        if ($checkoutAddOrderValidation['success'])
        {
            $success = 1;
            $orderId = $checkoutAddOrderValidation['clientId'];
        }
        else
        {
            
            $translator = $this->getServiceLocator()->get('translator');
            foreach ($checkoutAddOrderValidation['errors'] As $key => $val)
            {
                switch ($key)
                {
                    case 'basket':
                        
                        
                        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
                        
                        $errors[$key] = array();
                        $prdName = '';
                        $errMsg = '';
                        if (!empty($val))
                        {
                            foreach ($val As $eKey => $eVal)
                            {
                            
                                foreach ($eVal As $vKey => $vVal)
                                {
                                    if ($vKey == 'error')
                                    {
                                        $errMsg = $translator->translate('tr_'.$vVal);
                                    }
                                    else
                                    {
                                        $variantBasket = $vVal->getVariant();
                                        $variant = $variantBasket->getVariant();
                                        $prdName = $melisComProductService->getProductName($variant->var_prd_id, $langId).' ('.$variant->var_sku.')';
                                    }
                                }
                            
                                $errors[$key][$eKey] = $prdName.' : '.$errMsg;
                            }
                        }
                        
                        break;
                    case 'addresses':
                        
                        if (!empty($val))
                        {
                            $errors[$key] = $val;
                        }
                        
                        break;
                    case 'costs' :
                        
                        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
                        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
                        
                        if (!empty($val['order']['errors']))
                        {
                            foreach ($val['order']['errors'] As $cKey => $cval)
                            {
                                $variantEntity = $melisComVariantService->getVariantById($cKey);
                                $variant = $variantEntity->getVariant();
                                $prdName = $melisComProductService->getProductName($variant->var_prd_id, $langId).' ('.$variant->var_sku.')';
                                $errors[$key][$cKey] = $prdName.' : '.$translator->translate('tr_'.$cval);
                            }
                        }
                        
                        break;
                }
            }
        }
        
        $viewVariables = array(
            'orderId' => $orderId,
            'success' => $success,
            'errors' => $errors,
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
