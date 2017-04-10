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
 * "checkOutAddresses" plugin.
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
 * $plugin = $this->MelisCommerceCheckoutAddressesPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCheckoutCartPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'checkOutAddresses');
 * 
 * How to display in your controller's view:
 * echo $this->checkOutAddresses;
 * 
 * 
 */
class MelisCommerceCheckoutAddressesPlugin extends MelisTemplatingPlugin
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
        $checkootErrorMsg = '';
        $siteId = (!empty($this->pluginFrontConfig['m_site_id'])) ? $this->pluginFrontConfig['m_site_id'] : null;
        
        // Preparing the Container/Session of Commerce checkout
        $container = new Container('meliscommerce');
        if (!isset($container['checkout']))
        {
            $container['checkout'] = array();
        }
        
        $translator = $this->getServiceLocator()->get('translator');
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $appConfigDeliveryAddForm = (!empty($this->pluginFrontConfig['forms']['delivery_address'])) ? $this->pluginFrontConfig['forms']['delivery_address'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $deliveryAddForm = $factory->createForm($appConfigDeliveryAddForm);
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $appConfigBillingAddForm = (!empty($this->pluginFrontConfig['forms']['billing_address'])) ? $this->pluginFrontConfig['forms']['billing_address'] : array();
        $billingAddForm = $factory->createForm($appConfigBillingAddForm);
        
        
        $isSubmit = (!empty($this->pluginFrontConfig['m_add_is_submit'])) ? $this->pluginFrontConfig['m_add_is_submit'] : false;
        
        if ($isSubmit)
        {
            $sameAddress = (!empty($this->pluginFrontConfig['m_add_use_same_address'])) ? $this->pluginFrontConfig['m_add_use_same_address'] : 0;
            $container['checkout'][$siteId]['sameAddress'] = $sameAddress;
        }
        else
        {
            if (isset($container['checkout'][$siteId]['sameAddress']))
            {
                $sameAddress = $container['checkout'][$siteId]['sameAddress'];
            }
            else
            {
                $sameAddress = (!empty($this->pluginFrontConfig['m_add_use_same_address'])) ? $this->pluginFrontConfig['m_add_use_same_address'] : 0;
            }
        }
        
        /**
         * Getting the User identity using Commerce Authentication Service
         */
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
            $personId = $ecomAuthSrv->getPersonId();
            
            /**
             * Getting the Checkout addresses from session
             * that will fillup the address form
             */
            $deliveryAddFormSessData = array();
            if (!empty($container['checkout'][$siteId]['addresses']['addresses']['delivery']))
            {
                if ($container['checkout'][$siteId]['addresses']['addresses']['delivery']['success'])
                {
                    $deliveryAddFormSessDataTmp = $container['checkout'][$siteId]['addresses']['addresses']['delivery']['address'];
                    foreach ($deliveryAddFormSessDataTmp As $key => $val)
                    {
                        $deliveryAddFormSessData[str_replace('cadd_', 'm_add_delivery_', $key)] = $val;
                    }
                }
            }
            
            /**
             * Set form data of Delivery Address
             */
            $deliveryAddFormData = ($isSubmit) ? $this->pluginFrontConfig : $deliveryAddFormSessData;
            $deliveryAddForm->setData($deliveryAddFormData);
            
            $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
            $personBilAddress = $clientSrv->getClientAddressesByClientPersonId($personId, 'BIL');
            if (empty($personBilAddress) && !$isSubmit)
            {
                $billingAddForm->get('m_add_billing_id')->setValue('new_address')->setAttribute('type', 'hidden');
            }
            
            $personDelAddress = $clientSrv->getClientAddressesByClientPersonId($personId, 'DEL');
            if (empty($personDelAddress) && !$isSubmit)
            {
                $deliveryAddForm->get('m_add_delivery_id')->setValue('new_address')->setAttribute('type', 'hidden');
            }
            
            $deliverySelectAddress = (!empty($this->pluginFrontConfig['m_add_delivery_id'])) ? $this->pluginFrontConfig['m_add_delivery_id'] : null;
            if (!in_array($deliverySelectAddress, array('', 'new_address')))
            {
                /**
                 * If the checkout selection selected existing address
                 * this will retrieve from db and set to the Form
                 */
                $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
                $clientPersonDelAdd = $clientSrv->getClientPersonAddressByAddressId($personId, $deliverySelectAddress);
                if (!empty($clientPersonDelAdd))
                {
                    $personDelAdd = array();
                    foreach ($clientPersonDelAdd As $key => $val)
                    {
                        $personDelAdd[str_replace('cadd_', 'm_add_delivery_', $key)] = $val;
                    }
            
                    $deliveryAddForm->setData(ArrayUtils::merge($this->pluginFrontConfig, $personDelAdd));
                }
            }
            
            /**
             * Getting the Checkout addresses from session
             * that will fillup the address form
             */
            $billingAddFormSessData = array();
            if (!empty($container['checkout'][$siteId]['addresses']['addresses']['billing']))
            {
                if ($container['checkout'][$siteId]['addresses']['addresses']['billing']['success'])
                {
                    $billingAddFormSessDataTmp = $container['checkout'][$siteId]['addresses']['addresses']['billing']['address'];
                    foreach ($billingAddFormSessDataTmp As $key => $val)
                    {
                        $billingAddFormSessData[str_replace('cadd_', 'm_add_billing_', $key)] = $val;
                    }
                }
            }
            
            /**
             * Set Form data of Billing Address
             */
            $billingAddFormData = ($isSubmit) ? $this->pluginFrontConfig : $billingAddFormSessData;
            // Set Billing form only if the option of "Same Address" is to use same addess
            if (!$sameAddress)
            {
                $billingAddForm->setData($billingAddFormData);
            }
            
            $billingSelectAddress = (!empty($this->pluginFrontConfig['m_add_billing_id'])) ? $this->pluginFrontConfig['m_add_billing_id'] : null;
            if (!in_array($billingSelectAddress, array('', 'new_address')))
            {
                /**
                 * If the checkout selection selected existing address
                 * this will retrieve from db and set to the Form
                 */
                $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
                $clientPersonBilAdd = $clientSrv->getClientPersonAddressByAddressId($personId, $billingSelectAddress);
                if (!empty($clientPersonBilAdd))
                {
                    $personBilAdd = array();
                    foreach ($clientPersonBilAdd As $key => $val)
                    {
                        $personBilAdd[str_replace('cadd_', 'm_add_billing_', $key)] = $val;
                    }
            
                    $billingAddForm->setData(ArrayUtils::merge($this->pluginFrontConfig, $personBilAdd));
                }
            }
            
            // Getting the client basket list using Client key
            $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
            $basketData = $melisComBasketService->getBasket($clientId, $clientKey);
            
            if (is_null($basketData)){
                $checkootErrorMsg = $translator->translate('tr_meliscommerce_client_Checkout_cart_empty');
            }
        }
        else 
        {
            $checkootErrorMsg = $translator->translate('tr_meliscommerce_client_Checkout_no_identity');
        }
        
        if ($isSubmit)
        {
            $scapeValidateBillingAddress = false;
            if (!$deliveryAddForm->isValid())
            {
                $errors = $deliveryAddForm->getMessages();
                $scapeValidateBillingAddress = true;
            }
            else 
            {
                // Checking if the Billing address will use the same with Delivery Address
                if ($sameAddress)
                {
                   
                    $deliveryAddData = $deliveryAddForm->getData();
                    $billingAddData = array();
                    foreach ($deliveryAddData As $dKey => $dVal)
                    {
                        // Replacing index of delivery to billing index
                        $billingAddData[str_replace('m_add_delivery_', 'm_add_billing_', $dKey)] = $dVal;
                    }
                    
                    if (!empty($billingAddData['m_add_billing_type']))
                    {
                        // Modifying the billing type id for billing type id
                        $billingAddData['m_add_billing_type'] = 1;
                    }
                    
                    $billingAddForm->setData($billingAddData);
                }
            }
            
            if (!$scapeValidateBillingAddress)
            {
                if (!$billingAddForm->isValid())
                {
                    $errors = ArrayUtils::merge($errors, $billingAddForm->getMessages());
                }
            }
            
            if (empty($errors))
            {
                /**
                 * Putting back index to original name base on DB column
                 * before validating addresses and adding to checkout container/session
                 */
                $personDelAdd = array();
                foreach ($deliveryAddForm->getData() As $key => $val)
                {
                    $personDelAdd[str_replace('m_add_delivery_', 'cadd_', $key)] = $val;
                }
                
                if ($personDelAdd['cadd_id'] == 'new_address')
                {
                    unset($personDelAdd['cadd_id']);
                }
                /**
                 * Unsetting datas that are not needed to store 
                 * to session after address validation
                 */
                unset($personDelAdd['m_checkout_step']);
                unset($personDelAdd['m_add_is_submit']);
                
                $personBilAdd = array();
                foreach ($billingAddForm->getData() As $key => $val)
                {
                    $personBilAdd[str_replace('m_add_billing_', 'cadd_', $key)] = $val;
                }
                
                if ($personBilAdd['cadd_id'] == 'new_address')
                {
                    unset($personBilAdd['cadd_id']);
                }
                /**
                 * Unsetting datas that are not needed to store
                 * to session after address validation
                 */
                unset($personBilAdd['m_checkout_step']);
                unset($personBilAdd['m_add_is_submit']);
                
                // Validating addresses using Checkout service
                $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
                $melisComOrderCheckoutService->setSiteId($siteId);
                $validatedAddresses = $melisComOrderCheckoutService->validateAddresses($personDelAdd, $personBilAdd);
                
                if ($validatedAddresses['success'] != true)
                {
                    foreach ($validatedAddresses['addresses'] As $key => $val)
                    {
                        if (in_array($key, array('delivery', 'billing')))
                        {
                            if (!empty($validatedAddresses['addresses'][$key]['error']))
                            {
                                $errors[$key] = array(
                                    'label' => $translator->translate('tr_meliscommerce_order_checkout_address_'.$key),
                                    $key.'_err_msg' => $translator->translate('tr_'.$validatedAddresses['addresses'][$key]['error'])
                                );
                            }
                        }
                    }
                }
                
                if ($validatedAddresses['success'] == true)
                {
                    $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
                    
                    $deliveryAddId = null;
                    
                    $checkoutAddresses = $validatedAddresses['addresses'];
                    krsort($checkoutAddresses);
                    
                    foreach ($checkoutAddresses As $key => $val)
                    {
                        // checking if the entry is existing in db, else this will save to selected contact addresses
                        if (empty($val['address']['cadd_id']))
                        {
                            
                            $val['address']['cadd_client_id'] = $container['checkout'][$siteId]['clientId'];
                            $val['address']['cadd_client_person'] = $container['checkout'][$siteId]['contactId'];
                            
                            if ($key == 'delivery')
                            {
                                $addId = $clientSrv->saveClientAddress($val['address']);
                                $deliveryAddId = $addId;
                            }
                            else 
                            {
                                if ($sameAddress)
                                {
                                    $addId = $deliveryAddId;
                                }
                                else 
                                {
                                    $addId = $clientSrv->saveClientAddress($val['address']);
                                }
                            }
                            
                            $validatedAddresses['addresses'][$key]['address'] = (Array) $clientSrv->getClientPersonAddressByAddressId($personId, $addId);
                        }
                    }
                    
                    $container['checkout'][$siteId]['addresses'] = $validatedAddresses;
                    $success = 1;
                }
            }
        }
        
        $deliveryAddForm->get('m_add_is_submit')->setvalue(1);
        $deliveryAddForm->get('m_add_delivery_type')->setvalue(2);
        $billingAddForm->get('m_add_is_submit')->setvalue(1);
        $billingAddForm->get('m_add_billing_type')->setvalue(1);
        
        $viewVariables = array(
            'checkOutDeliveryAddress' => $deliveryAddForm,
            'checkOutBillingAddress' => $billingAddForm,
            'sameAddress' => $sameAddress,
            'checkootErrorMsg' => $checkootErrorMsg,
            'success' => $success,
            'errors' => $errors
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
