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
        $hasCartItems = false;
        $siteId = (!empty($this->pluginFrontConfig['m_site_id'])) ? $this->pluginFrontConfig['m_site_id'] : null;
        
        // Preparing the Container/Session of Commerce checkout
        $container = new Container('meliscommerce');
        if (!isset($container['checkout']))
        {
            $container['checkout'] = array();
        }
        
        /**
         * Getting the User identity using Commerce Authentication Service
         */
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        
        $clientKey = $ecomAuthSrv->getId();
        $clientId = null;
        $personId = null;
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
            $personId = $ecomAuthSrv->getPersonId();
        }
        
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
         * Setting up the Delivery Address form
         */
        $deliveryAddFormData = ($isSubmit) ? $this->pluginFrontConfig : $deliveryAddFormSessData;
        $deliveryAddForm->setData($deliveryAddFormData);
        
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
         * Setting up the Billing Address form
         */
        $billingAddFormData = ($isSubmit) ? $this->pluginFrontConfig : $billingAddFormSessData;
        $billingAddForm->setData($billingAddFormData);
        
        $hasDeliveryAddress = false;
        $deliverySelectAddress = (!empty($this->pluginFrontConfig['m_add_delivery_id'])) ? $this->pluginFrontConfig['m_add_delivery_id'] : null;
        if (!in_array($deliverySelectAddress, array('', 'new_address')))
        {
            $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
            $clientPersonDelAdd = $clientSrv->getClientPersonAddressByAddressId($personId, $deliverySelectAddress)->current();
            if (!empty($clientPersonDelAdd))
            {
                $personDelAdd = array();
                foreach ($clientPersonDelAdd As $key => $val)
                {
                    $personDelAdd[str_replace('cadd_', 'm_add_delivery_', $key)] = $val;
                }
                
                $deliveryAddForm->setData(ArrayUtils::merge($this->pluginFrontConfig, $personDelAdd));
                $hasDeliveryAddress = true;
            }
        }
        
        $hasBillingAddress = false;
        $billingSelectAddress = (!empty($this->pluginFrontConfig['m_add_billing_id'])) ? $this->pluginFrontConfig['m_add_billing_id'] : null;
        if (!in_array($billingSelectAddress, array('', 'new_address')))
        {
            $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
            $clientPersonBilAdd = $clientSrv->getClientPersonAddressByAddressId($personId, $billingSelectAddress)->current();
            if (!empty($clientPersonBilAdd))
            {
                $personBilAdd = array();
                foreach ($clientPersonBilAdd As $key => $val)
                {
                    $personBilAdd[str_replace('cadd_', 'm_add_billing_', $key)] = $val;
                }
                
                $billingAddForm->setData(ArrayUtils::merge($this->pluginFrontConfig, $personBilAdd));
                $hasBillingAddress = true;
            }
        }
        
        // Getting the client basket list using Client key
        $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
        $basketData = $melisComBasketService->getBasket($clientId, $clientKey);
        
        if (!is_null($basketData)){
            $hasCartItems = true;
        }
        
        if ($isSubmit)
        {
            if (!$deliveryAddForm->isValid() || !$hasDeliveryAddress)
            {
                $errors = $deliveryAddForm->getMessages();
            }
            
            if (!$billingAddForm->isValid() || !$hasBillingAddress)
            {
                $errors = (!empty($errors)) ? ArrayUtils::merge($errors, $billingAddForm->getMessages()) : $errors;
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
                    unset($personDelAdd['m_checkout_step']);
                    unset($personDelAdd['m_add_is_submit']);
                }
                
                $personBilAdd = array();
                foreach ($billingAddForm->getData() As $key => $val)
                {
                    $personBilAdd[str_replace('m_add_billing_', 'cadd_', $key)] = $val;
                }
                
                if ($personBilAdd['cadd_id'] == 'new_address')
                {
                    unset($personBilAdd['cadd_id']);
                    unset($personBilAdd['m_checkout_step']);
                    unset($personBilAdd['m_add_is_submit']);
                }
                
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
                    $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
                    
                    foreach ($validatedAddresses['addresses'] As $key => $val)
                    {
                        // checking if the entry is existing in db, else this will save to selected contact addresses
                        if (empty($val['address']['cadd_id']))
                        {
                            $val['address']['cadd_client_id'] = $container['checkout'][$siteId]['clientId'];
                            $val['address']['cadd_client_person'] = $container['checkout'][$siteId]['contactId'];
                            $val['address']['cadd_creation_date'] = date('Y-m-d H:i:s');
                            $val['address']['cadd_civility'] = (int) $val['address']['cadd_civility'];
                            
                            $addId = $melisEcomClientAddressTable->save($val['address']);
                            
                            $validatedAddresses['addresses'][$key]['address'] = (Array) $melisEcomClientAddressTable->getEntryById($addId)->current();
                        }
                    }
                
                    $container['checkout'][$siteId]['addresses'] = $validatedAddresses;
                    $success = 1;
                }
            }
        }
        
        if (!$success && $isSubmit)
        {
            unset($container['checkout'][$siteId]['addresses']);
        }
        
        $deliveryAddForm->get('m_add_is_submit')->setvalue(1);
        $deliveryAddForm->get('m_add_delivery_type')->setvalue(2);
        $billingAddForm->get('m_add_is_submit')->setvalue(1);
        $billingAddForm->get('m_add_billing_type')->setvalue(1);
        
        $viewVariables = array(
            'checkOutDeliveryAddress' => $deliveryAddForm,
            'checkOutBillingAddress' => $billingAddForm,
            'hasCartItems' => $hasCartItems,
            'success' => $success,
            'errors' => $errors
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
