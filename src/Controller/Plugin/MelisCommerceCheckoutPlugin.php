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
use Laminas\Session\Container;
use Laminas\Stdlib\ArrayUtils;
use Laminas\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "checkOut" plugin.
 * 
 * Please look inside app.plugins.products.php for possible awaited parameters
 * in front and back function calls.
 * 
 * front() and back() are the only functions to create / update.
 * front() generates the website view
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
 * $view->addChild($pluginView, 'checkout');
 * 
 * How to display in your controller's view:
 * echo $this->checkout;
 * 
 * 
 */
class MelisCommerceCheckoutPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceCheckoutPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        /**
         * MelisCommerceCheckoutPlugin this Plugin process the Checkout
         * This plugin provides pages with specific task as follows
         * 1. Cart - the cart of the Client/user
         * 2. Addresses - Delivery and Billing address for checkout
         * 3. Summary - this page will show the summary of the checkout
         * 3. Comfirm summary - This page will validate the checkout to become Order,
         *                      this will display a page if the process encounter an
         *                      error(s), otherwise this will provide a url for payment page
         *                      where the payment of the checkout process.
         *                      In this Demo we created a FAKE payment form in-order to
         *                      proceed the checkout.
         * 4. Payment - the step required event listener to process Payment
         * 5. Order confirmation - the Order confirmation page of the checkout
         */
        $checkout = null;
        $showSteps = true;
        $redirect = null;
        $pluginManager = $this->getServiceManager()->get('ControllerPluginManager');
        $translator = $this->getServiceManager()->get('translator');
        
        $data = $this->getFormData();
        
        $checkOutCartParameters = (!empty($data['checkout_cart_parameters'])) ? $data['checkout_cart_parameters'] : array();
        $checkOutCartParameters = ArrayUtils::merge($checkOutCartParameters, array('id' => 'checkoutCart_'.$data['id'], 'pageId' => $data['pageId']));
        
        $checkOutAddressesParameters = (!empty($data['checkout_addresses_parameters'])) ? $data['checkout_addresses_parameters'] : array();
        $checkOutAddressesParameters = ArrayUtils::merge($checkOutAddressesParameters, array('id' => 'checkoutAddresses_'.$data['id'], 'pageId' => $data['pageId']));
        
        $checkOutSummaryParameters = (!empty($data['checkout_summary_parameters'])) ? $data['checkout_summary_parameters'] : array();
        $checkOutSummaryParameters = ArrayUtils::merge($checkOutSummaryParameters, array('id' => 'checkoutSummary_'.$data['id'], 'pageId' => $data['pageId']));
        
        $checkOutConfirmSummaryParameters = (!empty($data['checkout_confirm_summary_parameters'])) ? $data['checkout_confirm_summary_parameters'] : array();
        $checkOutConfirmSummaryParameters = ArrayUtils::merge($checkOutConfirmSummaryParameters, array('id' => 'checkoutConfirmSummary_'.$data['id'], 'pageId' => $data['pageId']));
        
        $checkOutConfirmParameters = (!empty($data['checkout_confirm_parameters'])) ? $data['checkout_confirm_parameters'] : array();
        $checkOutConfirmParameters = ArrayUtils::merge($checkOutConfirmParameters, array('id' => 'checkoutConfirmation_'.$data['id'], 'pageId' => $data['pageId']));
        
        $countryId = (!empty($data['m_checkout_country_id'])) ? $data['m_checkout_country_id'] : null;
        $siteId = (!empty($data['m_checkout_site_id'])) ? $data['m_checkout_site_id'] : null;
        $steps = (!empty($data['m_checkout_step'])) ? $data['m_checkout_step'] : '';
        
        $checkoutPage = (!empty($data['m_checkout_page_link'])) ? $data['m_checkout_page_link'] : '';
        $loginPage = (!empty($data['m_login_page_link'])) ? $data['m_login_page_link'] : '';

        $redirectUrl = (isset($data['m_redirect_to_url'])) ? $data['m_redirect_to_url'] : true;

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
                $melisComAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
                
                /**
                 * Checking if the user has Loggedin
                 * else this should redirect to Login page
                 * with the param of the Checkout address page
                 */
                if (!$melisComAuthSrv->hasIdentity())
                {
                    if($this->renderMode != "melis")
                    {
                        $link_query = array(
                            'm_redirection_link_ok' => $checkoutPage . '?' . http_build_query(array('m_checkout_step' => 'checkout-addresses')),
                            'm_autologin' => 1
                        );

                        $redirect = $loginPage . '?' . http_build_query($link_query);
                    }
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
                    $checkOutAddressesParameters = ArrayUtils::merge($checkOutAddressesParameters, array('m_add_site_id' => $siteId));
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
                        if ($checkoutAddressesVars->checkoutDeliveryAddress)
                        {
                            $checkoutStepInput = array(
                                'name' => 'm_checkout_step',
                                'type' => 'hidden',
                                'attributes' => array(
                                    'value' => 'checkout-addresses',
                                ),
                            );
                            
                            $checkoutAddressesVars->checkoutDeliveryAddress->add($checkoutStepInput);
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
                $melisComAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
                
                /**
                 * Checking if the user has Loggedin
                 * else this should redirect to Login page
                 * with the param of the Checkout address page
                 */
                if (!$melisComAuthSrv->hasIdentity())
                {
                    if($this->renderMode != "melis")
                    {
                        $link_query = array(
                            'm_redirection_link_ok' => $checkoutPage,
                            'm_autologin' => 1
                        );

                        $redirect = $loginPage . '?' . http_build_query($link_query);
                    }
                }
                else
                {
                    /**
                     * This Plugin show the summary of the Checkout
                     * this will show the cart items, subtotal, discount, shippping and the total of the order
                     */
                    $checkOutSummaryPlugin = $pluginManager->get('MelisCommerceCheckoutSummaryPlugin');
                    $checkOutSummaryParameters = ArrayUtils::merge($checkOutSummaryParameters, array('m_summary_country_id' => $countryId, 'm_summary_site_id' => $siteId));
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
                $melisComAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
                
                /**
                 * Checking if the user has Loggedin
                 * else this should redirect to Login page
                 * with the param of the Checkout address page
                 */
                if (!$melisComAuthSrv->hasIdentity())
                {
                    if($this->renderMode != "melis")
                    {
                        $link_query = array(
                            'm_redirection_link_ok' => $checkoutPage,
                            'm_autologin' => 1
                        );

                        $redirect = $loginPage . '?' . http_build_query($link_query);
                    }
                }
                else 
                {
                    /**
                     * This Plugin process the checkout to become an Order record
                     * if the checkout complete this plugin return flag, else
                     * this will show some error(s) occurred 
                     */
                    $checkOutConfirmSummaryPlugin = $pluginManager->get('MelisCommerceCheckoutConfirmSummaryPlugin');
                    $checkOutConfirmSummaryParameters = ArrayUtils::merge($checkOutConfirmSummaryParameters, array('m_conf_summary_site_id' => $siteId));
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
                 * Login using the Commerce Authentication Service
                 */
                $melisComAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
                
                /**
                 * Checking if the user has Logged in
                 * else this should redirect to Login page
                 * with the param of the Checkout address page
                 */
                if (!$melisComAuthSrv->hasIdentity())
                {
                    if($this->renderMode != "melis")
                    {
                        $link_query = array(
                            'm_redirection_link_ok' => $checkoutPage,
                            'm_autologin' => 1
                        );

                        $redirect = $loginPage . '?' . http_build_query($link_query);
                        $checkout = null;
                    }
                }
                else 
                {
                    $clientId = $melisComAuthSrv->getClientId();
                    // Retrieving the Checkout total cost
                    $melisComOrderCheckoutService = $this->getServiceManager()->get('MelisComOrderCheckoutService');
                    $melisComOrderCheckoutService->setSiteId($siteId);
                    $order = $melisComOrderCheckoutService->computeAllCosts($clientId);

                    $totalCost = $order['costs']['order']['total'];
                    
                    if (!empty($container['checkout'][$siteId]['orderId']))
                    {
                        $orderId = $container['checkout'][$siteId]['orderId'];

                        $param = array(
                            'checkout' => null,
                            'orderDetails' => array(
                                'orderId' => $orderId,
                                'totalCost' => $totalCost,
                            )
                        );
                        
                        $showSteps = false;
                        $melisGeneralService = $this->getServiceManager()->get('MelisGeneralService');
                        $checkoutPaymentEvent = $melisGeneralService->sendEvent('meliscommerce_checkout_plugin_payment', $param, $this);
                        $checkout = $checkoutPaymentEvent['checkout'];
                    }
                }
                
                break;
            case 'checkout-confirm':

                /**
                 * Login using the Commerce Athentication Service
                 */
                $melisComAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');

                /**
                 * Checking if the user has Loggedin
                 * else this should redirect to Login page
                 * with the param of the Checkout address page
                 */
                if (!$melisComAuthSrv->hasIdentity())
                {
                    if($this->renderMode != "melis")
                    {
                        $link_query = array(
                            'm_redirection_link_ok' => $checkoutPage,
                            'm_autologin' => 1
                        );

                        $redirect = $loginPage . '?' . http_build_query($link_query);
                        $checkout = null;
                    }
                }
                else
                {
                    $checkOutConfirmPlugin = $pluginManager->get('MelisCommerceCheckoutConfirmPlugin');
                    $checkout = $checkOutConfirmPlugin->render($checkOutConfirmParameters);

                    if(!empty($container['checkout'][$siteId]))
                    {
                        // Unset Site Checkout Session
                        unset($container['checkout'][$siteId]);
                    }
                    $showSteps = false;
                }

                
                break;
            case 'checkout-cart':
            default:
                
                if ($checkOutCartParameters['m_required_login']) {
                    /**
                     * Login using the Commerce Athentication Service
                     */
                    $melisComAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');

                    /**
                     * Checking if the user has Loggedin
                     * else this should redirect to Login page
                     * with the param of the Checkout address page
                     */
                    if (!$melisComAuthSrv->hasIdentity())
                    {
                        if($this->renderMode != "melis")
                        {
                            $link_query = array(
                                'm_redirection_link_ok' => $checkoutPage,
                                'm_autologin' => 1
                            );

                            $redirect = $loginPage . '?' . http_build_query($link_query);
                            $checkout = null;
                        }
                    }
                } 

                if (!empty($redirect))
                    break;

                /**
                 * As default step the cart of the checkout will show first
                 * This Plugin include the updating of quantity of the item(s)
                 * and validating coupon for discount
                 */
                $checkoutCartPlugin = $pluginManager->get('MelisCommerceCheckoutCartPlugin');
                
                $checkOutCartParameters = ArrayUtils::merge($checkOutCartParameters, 
                                                            array(
                                                                'm_cc_country_id' => $countryId,
                                                                'm_cc_site_id' => $siteId
                                                            ));
                $checkout = $checkoutCartPlugin->render($checkOutCartParameters);
                
                /**
                 * Adding view variable for "Next" step
                 * to the Plugin view
                 */
                $checkout->setVariable('nextStep_link', $checkoutPage.'?'.http_build_query(array('m_checkout_step' => 'checkout-addresses')));
                
                
                break;
        }

        /**
         * if the plugin return a redirect url this should redirect to the return url
         * in-order to proceed the next step of the checkout
         */
        if ($redirect && $redirectUrl)
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
            'checkoutPluginVars' => !is_null($checkout) ? $checkout->getVariables() : null,
            'checkoutSteps' => $checkoutSteps,
            'showSteps' => $showSteps,
            'redirect' => $redirect
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    /**
     * This function generates the form displayed when editing the parameters of the plugin
     */
    public function createOptionsForms()
    {
        // construct form
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig = $this->pluginBackConfig['modal_form'];
        
        $response = [];
        $render   = [];
        if (!empty($formConfig))
        {
            foreach ($formConfig as $formKey => $config)
            {
                $form = $factory->createForm($config);
                $request = $this->getServiceManager()->get('request');
                $parameters = $request->getQuery()->toArray();
                
                if (!isset($parameters['validate']))
                {
                    $form->setData($this->getFormData());
                    $viewModelTab = new ViewModel();
                    $viewModelTab->setTemplate($config['tab_form_layout']);
                    $viewModelTab->modalForm = $form;
                    $viewModelTab->formData   = $this->getFormData();
                    
                    
                    
                    
                    $viewRender = $this->getServiceManager()->get('ViewRenderer');
                    $html = $viewRender->render($viewModelTab);
                    array_push($render, array(
                        'name' => $config['tab_title'],
                        'icon' => $config['tab_icon'],
                        'html' => $html
                    ));
                }
                else
                {
                    // validate the forms and send back an array with errors by tabs
                    $success = false;
                    $errors = array();
                    
                    $post = $request->getPost()->toArray();
                    
                    $form->setData($post);
                    
                    if (!$form->isValid())
                    {
                        if (empty($errors))
                        {
                            $errors = $form->getMessages();
                        }
                        else
                        {
                            $errors = ArrayUtils::merge($errors, $form->getMessages());
                        }
                    }
                    
                    if (empty($errors))
                    {
                        $success = true;
                    }
                    
                    if (!empty($errors))
                    {
                        foreach ($errors as $keyError => $valueError)
                        {
                            foreach ($config['elements'] as $keyForm => $valueForm)
                            {
                                if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                                {
                                    $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                                }
                            }
                        }
                    }
                    
                    array_push($response, array(
                        'name' => $this->pluginBackConfig['modal_form'][$formKey]['tab_title'],
                        'success' => $success,
                        'errors' => $errors,
                        'message' => '',
                    ));
                }
            }
        }
        
        if (!isset($parameters['validate']))
        {
            return $render;
        }
        else
        {
            return $response;
        }
    }
    
    /**
     * Returns the data to populate the form inside the modals when invoked
     * @return array
     */
    public function getFormData()
    {
        $data = $this->pluginFrontConfig;
        
        return $data;
    }
    
    /**
     * This method will decode the XML in DB to make it in the form of the plugin config file
     * so it can overide it. Only front key is needed to update.
     * The part of the XML corresponding to this plugin can be found in $this->pluginXmlDbValue
     */
    public function loadDbXmlToPluginConfig()
    {
        $configValues = array();
        
        $xml = simplexml_load_string($this->pluginXmlDbValue);
        
        if ($xml)
        {
            if (!empty($xml->template_path))
            {
                $configValues['template_path'] = (string)$xml->template_path;
            }
            
            if (!empty($xml->m_checkout_step))
            {
                $configValues['m_checkout_step'] = (string)$xml->m_checkout_step;
            }
            
            if (!empty($xml->m_checkout_country_id))
            {
                $configValues['m_checkout_country_id'] = (string)$xml->m_checkout_country_id;
            }
            
            if (!empty($xml->m_checkout_site_id))
            {
                $configValues['m_checkout_site_id'] = (string)$xml->m_checkout_site_id;
            }
            
            if (!empty($xml->m_checkout_page_link))
            {
                $configValues['m_checkout_page_link'] = json_decode((string)$xml->m_checkout_page_link);
            }
        }
        
        return $configValues;
    }
    
    /**
     * This method saves the XML version of this plugin in DB, for this pageId
     * Automatically called from savePageSession listenner in PageEdition
     */
    public function savePluginConfigToXml($parameters)
    {
        $xmlValueFormatted = '';
        
        // template_path is mendatory for all plugins
        if (!empty($parameters['template_path']))
        {
            $xmlValueFormatted .= "\t\t" . '<template_path><![CDATA[' . $parameters['template_path'] . ']]></template_path>';
        }
        
         if (!empty($parameters['m_checkout_step']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_checkout_step><![CDATA[' . $parameters['m_checkout_step'] . ']]></m_checkout_step>';
        }
        
        if (!empty($parameters['m_checkout_country_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_checkout_country_id><![CDATA[' . $parameters['m_checkout_country_id'] . ']]></m_checkout_country_id>';
        }
        
        if (!empty($parameters['m_checkout_site_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_checkout_site_id><![CDATA[' . $parameters['m_checkout_site_id'] . ']]></m_checkout_site_id>';
        }
        
        if (!empty($parameters['m_checkout_page_link']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_checkout_page_link><![CDATA[' . $parameters['m_checkout_page_link'] . ']]></m_checkout_page_link>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
