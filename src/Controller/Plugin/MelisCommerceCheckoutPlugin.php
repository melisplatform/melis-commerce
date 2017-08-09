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
        /**
         * MelisCommerceCheckoutPlugin this Plugin process the Checkout
         * This pulig provides pages with specific task as follows
         * 1. Cart - the cart of the Client/user
         * 2. Addresses - Delivery and Billing address for checkout
         * 3. Summary - this page will show the summary of the checkout
         * 3. Comfirm summary - This page will validate the checkout to become Order,
         *                      this will display a page if the process encounter an
         *                      error(s), otherwire this will provide a url for payment page
         *                      where the payment of the checkout process.
         *                      In this Demo we created a FAKE payment form in-order to
         *                      proceed the checkout.
         * 4. Payment - the step required event listener to process Payment
         * 5. Order confirmation - the Order confirmation page of the checkout
         */
        $checkout = null;
        $showSteps = true;
        $redirect = null;
        $pluginManager = $this->getServiceLocator()->get('ControllerPluginManager');
        $translator = $this->getServiceLocator()->get('translator');
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $checkOutCartParameters = (!empty($this->pluginFrontConfig['checkout_cart_parameters'])) ? $this->pluginFrontConfig['checkout_cart_parameters'] : array();
        $checkOutAddressesParameters = (!empty($this->pluginFrontConfig['checkout_addresses_parameters'])) ? $this->pluginFrontConfig['checkout_addresses_parameters'] : array();
        $checkOutSummaryParameters = (!empty($this->pluginFrontConfig['checkout_summary_parameters'])) ? $this->pluginFrontConfig['checkout_summary_parameters'] : array();
        $checkOutConfirmSummaryParameters = (!empty($this->pluginFrontConfig['checkout_confirm_summary_parameters'])) ? $this->pluginFrontConfig['checkout_confirm_summary_parameters'] : array();
        $checkOutConfirmParameters = (!empty($this->pluginFrontConfig['checkout_confirm_parameters'])) ? $this->pluginFrontConfig['checkout_confirm_parameters'] : array();
        
        $countryId = (!empty($this->pluginFrontConfig['m_checkout_country_id'])) ? $this->pluginFrontConfig['m_checkout_country_id'] : 1;
        $siteId = (!empty($this->pluginFrontConfig['m_checkout_site_id'])) ? $this->pluginFrontConfig['m_checkout_site_id'] : 1;
        $steps = (!empty($this->pluginFrontConfig['m_checkout_step'])) ? $this->pluginFrontConfig['m_checkout_step'] : '';
        
        $checkoutPage = (!empty($this->pluginFrontConfig['m_checkout_page_link'])) ? $this->pluginFrontConfig['m_checkout_page_link'] : 'http://www.test.com';
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
                        'm_redirection_link_ok' => $checkoutPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-addresses')),
                        'm_autologin' => 1
                    );
                    
                    $redirect = $loginPage.'?'.http_build_query($link_query);
                }
                else 
                {
                    // Checkout client credintials added to container/session
                    $container['checkout'][$siteId]['clientKey'] = $melisComAuthSrv->getClientKey();
                    $container['checkout'][$siteId]['contactId'] = $melisComAuthSrv->getPersonId();
                    $container['checkout'][$siteId]['clientId'] = $melisComAuthSrv->getClientId();
                    
                    /**
                     * Checkout Addresses this Plugin process the checkout addresses such as Delivery and Billing
                     * needed to create an Order
                     */
                    $checkOutAddressesPlugin = $pluginManager->get('MelisCommerceCheckoutAddressesPlugin');
                    $checkOutAddressesParameters = ArrayUtils::merge($checkOutAddressesParameters, array('m_site_id' => $siteId));
                    $checkout = $checkOutAddressesPlugin->render($checkOutAddressesParameters);
                    $checkoutAddressesVars = $checkout->getVariables();
                    if ($checkoutAddressesVars->success)
                    {
                        $redirect = $checkoutPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-summary'));
                    }
                    else 
                    {
                        /**
                         * Set value to the Address form input hidden
                         * the purpose is to identify the request after submission of the form
                         */
                        if ($checkoutAddressesVars->checkOutDeliveryAddress)
                        {
                            $checkoutAddressesVars->checkOutDeliveryAddress->get('m_checkout_step')->setValue('checkout-addresses');
                            $checkoutAddressesVars->checkOutBillingAddress->get('m_checkout_step')->setValue('checkout-addresses');
                        }
                        
                        /**
                         * Adding view variable for "Previous" and "Next" step
                         * to the Plugin view
                         */
                        $checkout->setVariable('prevStep_link', $checkoutPage);
                        $checkout->setVariable('nextStep_link', $checkoutPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-summary')));
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
                        'm_redirection_link_ok' => $checkoutPage,
                        'm_autologin' => 1
                    );
                    
                    $redirect = $loginPage.'?'.http_build_query($link_query);
                }
                else
                {
                    /**
                     * This Plugin show the summary of the Checkout
                     * this will show the cart items, subtotal, discount, shippping and the total of the order
                     */
                    $checkOutSummaryPlugin = $pluginManager->get('MelisCommerceCheckoutSummaryPlugin');
                    $checkOutSummaryParameters = ArrayUtils::merge($checkOutSummaryParameters, array('m_site_id' => $siteId));
                    $checkout = $checkOutSummaryPlugin->render($checkOutSummaryParameters);
                    /**
                     * Adding view variable for "Previous" and "Next" step
                     * to the Plugin view
                     */
                    $checkout->setVariable('prevStep_link', $checkoutPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-addresses')));
                    $checkout->setVariable('nextStep_link', $checkoutPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-confirm-summary')));
                }
                break;
            case 'checkout-confirm-summary':
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
                        'm_redirection_link_ok' => $checkoutPage,
                        'm_autologin' => 1
                    );
                    
                    $redirect = $loginPage.'?'.http_build_query($link_query);
                }
                else 
                {
                    /**
                     * This Plugin process the checkout to become an Order record
                     * if the checkout complete this plugin return flag, else
                     * this will show some error(s) occured 
                     */
                    $checkOutConfirmSummaryPlugin = $pluginManager->get('MelisCommerceCheckoutConfirmSummaryPlugin');
                    $checkOutConfirmSummaryParameters = ArrayUtils::merge($checkOutConfirmSummaryParameters, array('m_site_id' => $siteId));
                    $checkout = $checkOutConfirmSummaryPlugin->render($checkOutConfirmSummaryParameters);
                    
                    $checkoutVars = $checkout->getVariables();
                    
                    /**
                     * if the process return success, this plugin "MelisCommerceCheckoutPlugin"
                     * will return a url where the next step process which is the payment
                     */
                    if ($checkoutVars->success)
                    {
                        $link_query = array(
                            'm_checkout_step' => 'checkout-payment'
                        );
                        $redirect = $checkoutPage.'?'.http_build_query($link_query);
                    }
                    else
                    {
                        /**
                         * Adding view variable for "Previous" step
                         * to the Plugin view
                         */
                        $checkout->setVariable('prevStep_link', $checkoutPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-summary')));
                    }
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
                        'm_redirection_link_ok' => $checkoutPage,
                        'm_autologin' => 1
                    );
                    
                    $redirect = $loginPage.'?'.http_build_query($link_query);
                    $checkout = null;
                }
                else 
                {
                    $clientId = $melisComAuthSrv->getClientId();
                    // Retrieving the Checkout total cost
                    $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
                    $melisComOrderCheckoutService->setSiteId($siteId);
                    $order = $melisComOrderCheckoutService->computeAllCosts($clientId);
                    $totalCost = $order['costs']['total'];
                    
                    if (!empty($container['checkout'][$siteId]['orderId']))
                    {
                        $orderId = $container['checkout'][$siteId]['orderId'];
                        
                        $couponId = null;
                        if (!empty($container['checkout'][$siteId]['couponId']))
                        {
                            $couponId = $container['checkout'][$siteId]['couponId'];
                        }
                        
                        $param = array(
                            'chechout' => null,
                            'orderDetails' => array(
                                'countryId' => $countryId,
                                'orderId' => $orderId,
                                'couponId' => $couponId,
                                'totalCost' => $totalCost,
                            )
                        );
                        
                        $showSteps = false;
                        $melisEngineGeneralService = $this->getServiceLocator()->get('MelisEngineGeneralService');
                        $checkoutPaymentEvent = $melisEngineGeneralService->sendEvent('meliscommerce_checkout_plugin_payment', $param, $this);
                        $checkout = $checkoutPaymentEvent['chechout'];
                    }
                }
                
                break;
            case 'checkout-confirm':
                
                $checkOutConfirmPlugin = $pluginManager->get('MelisCommerceCheckoutConfirmPlugin');
                $checkout = $checkOutConfirmPlugin->render($checkOutConfirmParameters);
                
                if(!empty($container['checkout'][$siteId]))
                {
                    // Unsetting Site Checkout Session
                    unset($container['checkout'][$siteId]);
                }
                $showSteps = false;
                
                break;
            default:
                
                /**
                 * As default step the cart of the checkout will show first
                 * This Plugin include the updating of quantity of the item(s)
                 * and validating coupon for discount
                 */
                $checkOutcartPlugin = $pluginManager->get('MelisCommerceCheckoutCartPlugin');
                
                $checkOutCartParameters = ArrayUtils::merge($checkOutCartParameters, 
                                                            array(
                                                                'm_country_id' => $countryId,
                                                                'm_v_remove_link' => $checkoutPage,
                                                                'm_site_id' => $siteId
                                                            ));
                $checkout = $checkOutcartPlugin->render($checkOutCartParameters);
                
                /**
                 * Adding view variable for "Next" step
                 * to the Plugin view
                 */
                $checkout->setVariable('nextStep_link', $checkoutPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-addresses')));
                
                break;
        }
        
        /**
         * if the plugin redturn a redirect url this should redirect to the return url
         * in-order to proceed the next step of the checkout
         */
        if ($redirect)
        {
            $this->getController()->redirect()->toUrl($redirect);
        }
        
        /**
         * Default steps of "MelisCommerceCheckoutPlugin"
         * with activation of step depend on the data step provided by the controller/url
         */
        $checkoutSteps = array(
            'checkout-cart' => array(
                'label' => $translator->translate('tr_meliscommerce_checkout_cart_label'),
                'isActive' => ($steps == '') ? true : false,
            ),
            'checkout-addresses' => array(
                'label' => $translator->translate('tr_meliscommerce_checkout_addresses_label'),
                'isActive' => ($steps == 'checkout-addresses') ? true : false,
            ),
            'checkout-summary' => array(
                'label' => $translator->translate('tr_meliscommerce_checkout_summary_label'),
                'isActive' => ($steps == 'checkout-summary') ? true : false,
            ),
        );
        
        $viewVariables = array(
            'checkout' => $checkout,
            'checkoutSteps' => $checkoutSteps,
            'showSteps' => $showSteps,
            'redirect' => $redirect
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
