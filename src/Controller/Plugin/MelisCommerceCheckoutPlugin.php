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
 * $plugin = $this->MelisCommerceCheckoutPlugin();
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
class MelisCommerceCheckoutPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $redirect = null;
        $pluginManager = $this->getServiceLocator()->get('ControllerPluginManager');
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $countryId = (!empty($this->pluginFrontConfig['m_checkout_country_id'])) ? $this->pluginFrontConfig['m_checkout_country_id'] : 1;
        $siteId = (!empty($this->pluginFrontConfig['m_checkout_site_id'])) ? $this->pluginFrontConfig['m_checkout_site_id'] : 1;
        $steps = (!empty($this->pluginFrontConfig['m_checkout_step'])) ? $this->pluginFrontConfig['m_checkout_step'] : '';
        
        $checkoutCartPage = (!empty($this->pluginFrontConfig['m_checkout_cart_link'])) ? $this->pluginFrontConfig['m_checkout_cart_link'] : 'http://www.test.com';
        $checkoutAddressesPage = (!empty($this->pluginFrontConfig['m_checkout_addresses_link'])) ? $this->pluginFrontConfig['m_checkout_addresses_link'] : 'http://www.test.com';
        $checkoutSummaryPage = (!empty($this->pluginFrontConfig['m_checkout_summary_link'])) ? $this->pluginFrontConfig['m_checkout_summary_link'] : 'http://www.test.com';
        
        $checkoutPaymentUrl = (!empty($this->pluginFrontConfig['m_checkout_payment_url'])) ? $this->pluginFrontConfig['m_checkout_payment_url'] : 'http://www.test.com';
        $checkoutPaymentNotifyUrl = (!empty($this->pluginFrontConfig['m_checkout_payment_notify_url'])) ? $this->pluginFrontConfig['m_checkout_payment_notify_url'] : 'http://www.test.com';
        $checkoutConfirmationUrl = (!empty($this->pluginFrontConfig['m_checkout_confirmation_url'])) ? $this->pluginFrontConfig['m_checkout_confirmation_url'] : 'http://www.test.com';
        $loginPage = (!empty($this->pluginFrontConfig['m_login_page_link'])) ? $this->pluginFrontConfig['m_login_page_link'] : 'http://www.test.com';
        
        // Preparing the Container/Session of Commerce checkout
        $container = new Container('meliscommerce');
        if (!isset($container['checkout']))
        {
            $container['checkout'] = array();
        }
        $container['checkout'][$siteId]['countryId'] = $countryId;
        
        switch ($steps)
        {
            case 'checkout-addresses':
                /**
                 * Login using the Commerce Athentication Service
                 */
                $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
                
                /**
                 * Checking if the user has Loggedin
                 * else this should redirect to Login page
                 * with the param of the Checkout address page
                 */
                if (!$melisComAuthSrv->hasIdentity())
                {
                    $link_query = array(
                        'm_redirection_link_ok' => $checkoutAddressesPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-addresses'))
                    );
                    
                    $redirect = $loginPage.'?'.http_build_query($link_query);
                }
                else 
                {
                    // Checkout client credintials added to container/session
                    $container['checkout'][$siteId]['clientKey'] = $melisComAuthSrv->getClientKey();
                    $container['checkout'][$siteId]['contactId'] = $melisComAuthSrv->getPersonId();
                    $container['checkout'][$siteId]['clientId'] = $melisComAuthSrv->getClientId();
                    
                    $checkOutAddressesPlugin = $pluginManager->get('MelisCommerceCheckoutAddressesPlugin');
                    $checkOutAddressesParameters = array(
                        'm_site_id' => $siteId
                    );
                    
                    $checkout = $checkOutAddressesPlugin->render($checkOutAddressesParameters);
                    
                    $checkoutAddressesVars = $checkout->getVariables();
                    
                    if ($checkoutAddressesVars->success)
                    {
                        $redirect = $checkoutSummaryPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-summary'));
                    }
                    else 
                    {
                        $checkoutAddressesVars->checkOutDeliveryAddress->get('m_checkout_step')->setValue('checkout-addresses');
                        $checkoutAddressesVars->checkOutBillingAddress->get('m_checkout_step')->setValue('checkout-addresses');
                        
                        $checkout->setVariable('prevStep_link', $checkoutCartPage);
                        $checkout->setVariable('nextStep_link', $checkoutSummaryPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-summary')));
                    }
                }
                break;
                
            case 'checkout-summary':
                
                /**
                 * Login using the Commerce Athentication Service
                 */
                $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
                
                /**
                 * Checking if the user has Loggedin
                 * else this should redirect to Login page
                 * with the param of the Checkout address page
                 */
                if (!$melisComAuthSrv->hasIdentity())
                {
                    $link_query = array(
                        'm_redirection_link_ok' => $checkoutAddressesPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-summary'))
                    );
                    
                    $redirect = $loginPage.'?'.http_build_query($link_query);
                }
                else
                {
                    $checkOutSummaryPlugin = $pluginManager->get('MelisCommerceCheckoutSummaryPlugin');
                    $checkOutSummaryParameters = array();
                    $checkout = $checkOutSummaryPlugin->render($checkOutSummaryParameters);
                    
                    $checkout->setVariable('prevStep_link', $checkoutAddressesPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-addresses')));
                    $checkout->setVariable('nextStep_link', $checkoutSummaryPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-payment')));
                }
                break;
                
            case 'checkout-payment':
                
                /**
                 * Login using the Commerce Athentication Service
                 */
                $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
                
                /**
                 * Checking if the user has Loggedin
                 * else this should redirect to Login page
                 * with the param of the Checkout address page
                 */
                if (!$melisComAuthSrv->hasIdentity())
                {
                    $link_query = array(
                        'm_redirection_link_ok' => $checkoutAddressesPage
                    );
                    
                    $redirect = $loginPage.'?'.http_build_query($link_query);
                }
                else 
                {
                    $clientId = $melisComAuthSrv->getClientId();
                    
                    $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
                    $melisComOrderCheckoutService->setSiteId($siteId);
//                     $validatedBasket = $melisComOrderCheckoutService->validateBasket($clientId);
                    $validatedBasket = $melisComOrderCheckoutService->checkoutStep1_prePayment($clientId);
                    
                    echo '<pre>';
                    print_r($validatedBasket);
                    echo '</pre>';
                    
                    die();
                    if (!empty($validatedBasket['basket']['ko']))
                    {
                        $redirect = $checkoutCartPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-addresses'));
                    }
                    else 
                    {
                        
                        $data = array(
                            'tmp-payment-gateway' => 1
                        );
                        $redirect = $checkoutPaymentUrl.'?'.http_build_query($data);
                    }
                }
                
                break;
            default:
                
                $checkOutcartPlugin = $pluginManager->get('MelisCommerceCheckoutCartPlugin');
                $checkOutCartParameters = array(
                    'm_country_id' => $countryId,
                    'm_v_remove_link' => $checkoutCartPage,
                    'm_site_id' => $siteId
                );
                $checkout = $checkOutcartPlugin->render($checkOutCartParameters);
                
                $checkout->setVariable('nextStep_link', $checkoutAddressesPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-addresses')));
                
                break;
        }
        
        $checkoutSteps = array(
            'checkout-cart' => array(
                'label' => 'Checkout Cart',
                'isActive' => ($steps == '') ? true : false,
            ),
            'checkout-addresses' => array(
                'label' => 'Checkout Addresses',
                'isActive' => ($steps == 'checkout-addresses') ? true : false,
            ),
            'checkout-summary' => array(
                'label' => 'Checkout Summary',
                'isActive' => ($steps == 'checkout-summary') ? true : false,
            ),
        );
        
        $viewVariables = array(
            'checkout' => $checkout,
            'checkoutSteps' => $checkoutSteps,
            'redirect' => $redirect
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
