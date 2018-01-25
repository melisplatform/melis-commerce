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
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\ArrayUtils;
/**
 * This plugin implements the business logic of the
 * "coupon" plugin.
 * 
 * Please look inside app.plugins.php for possible awaited parameters
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
 * $plugin = $this->MelisCommerceCheckoutCouponPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCheckoutCouponPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'coupon');
 * 
 * How to display in your controller's view:
 * echo $this->coupon;
 * 
 * 
 */
class MelisCommerceCheckoutCouponPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceCheckoutCouponPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $coupon = array();
        $message = '';
        $success = 0;
        $items = array();
        
        $couponSrv = $this->getServiceLocator()->get('MelisComCouponService');
        $basketSrv = $this->getServiceLocator()->get('MelisComBasketService');
        $checkoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
        $translator = $this->getServiceLocator()->get('translator');
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        
        $siteId         = (!empty($this->pluginFrontConfig['m_coupon_site_id']))        ? $this->pluginFrontConfig['m_coupon_site_id'] : '';
        $isMultiple     = (!empty($this->pluginFrontConfig['m_coupon_multiple']))       ? $this->pluginFrontConfig['m_coupon_multiple'] : false;
        $isSubmit       = (!empty($this->pluginFrontConfig['m_coupon_is_submit']))      ? $this->pluginFrontConfig['m_coupon_is_submit'] : false;
        $couponCode     = (!empty($this->pluginFrontConfig['m_coupon_code']))           ? $this->pluginFrontConfig['m_coupon_code'] : null;
        $couponRemove   = (!empty($this->pluginFrontConfig['m_coupon_remove']))         ? $this->pluginFrontConfig['m_coupon_remove'] : null;
        
        $appConfigForm  = (!empty($this->pluginFrontConfig['forms']['meliscommerce_checkout_coupon_form'])) ? $this->pluginFrontConfig['forms']['meliscommerce_checkout_coupon_form'] : $this->pluginConfig['forms']['meliscommerce_checkout_coupon_form'];
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $couponForm = $factory->createForm($appConfigForm);
        
        $container = new Container('meliscommerce');
        $coupons = !empty($container['checkout'][$siteId]['coupons'])? $container['checkout'][$siteId]['coupons'] : array();
	    $productCoupons = !empty($coupons['productCoupons'])? $coupons['productCoupons'] : array();
	    $generalCoupons = !empty($coupons['generalCoupons'])? $coupons['generalCoupons'] : array();
        
	    /**
	     * Removing Coupon
	     */
	    if(!empty($couponRemove))
	    {
	        // Remove prouduct coupon assign to a product
	        if(!empty($productCoupons))
	        {
	            foreach($productCoupons as $key => $val)
	            {
	                if($val->coup_code == $couponRemove)
	                {
	                    unset($container['checkout'][$siteId]['coupons']['productCoupons'][$key]);
	                }
	            }
	            
	            $productCoupons = $container['checkout'][$siteId]['coupons']['productCoupons'];
	        }
	        
	        // Removing a general coupon
	        if(!empty($generalCoupons))
	        {
	            foreach($generalCoupons as $key => $val)
	            {
	                if($val->coup_code == $couponRemove)
	                {
	                    unset($container['checkout'][$siteId]['coupons']['generalCoupons'][$key]);
	                }
	            }
	            
	            $generalCoupons = $container['checkout'][$siteId]['coupons']['generalCoupons'];
	        }
	    }
	    
        $clientKey = $ecomAuthSrv->getId();
        $clientId = null;
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
        }
        
        if ($isSubmit)
        {
            if (!empty($couponCode))
            {
                $couponForm->setData(array('m_coupon_code' => $couponCode));
                
                if($couponForm->isValid())
                {
                    $couponData = $couponForm->getData();
                    
                    // Retrieving basket items
                    $basketData = $basketSrv->getBasket($clientId, $clientKey);
                    
                    if(!empty($basketData))
                    {
                        foreach($basketData as $item)
                        {
                            $items[] = $item->getVariant()->getVariant()->var_prd_id;
                        }
                        
                        $items = array_unique($items);
                    }
                    
                    $validatedCoupon = $checkoutService->validateCoupon($couponData['m_coupon_code'], $clientId, $items);
                    
                    if($validatedCoupon['success'])
                    {
                        
                        $validCoupon = array($validatedCoupon['coupon']->coup_id => $validatedCoupon['coupon']);
                        
                        if($validatedCoupon['type'] == 'general')
                        {
                            // general coupons
                            $generalCoupons = ($isMultiple)? $validCoupon + $generalCoupons: $validCoupon;
                            $productCoupons = ($isMultiple)?  $productCoupons :  array();
                        }
                        else
                        {
                            // product coupons
                            $productCoupons = ($isMultiple)? $validCoupon + $productCoupons :  $validCoupon;
                            $generalCoupons = ($isMultiple)? $generalCoupons : array();
                        }
                        
                        $coupon = $validatedCoupon['coupon'];
                        $success = 1;
                        $message = $translator->translate('tr_meliscommerce_coupon_valid');
                        $sessionCoupons['couponErr'] = array();
                        $sessionCoupons['productCoupons'] = $productCoupons;
                        $sessionCoupons['generalCoupons'] = $generalCoupons;
                        
                        $couponForm->setData(array('m_coupon_code' => ''));
                    }
                    else
                    {
                        if(!$isMultiple)
                        {
                            // clear data if not multiple coupons
                            $sessionCoupons = array();
                        }
                        
                        // invalid coupon
                        $message = $translator->translate('tr_'.$validatedCoupon['error']);
                        $sessionCoupons['couponErr'] = $message;
                        $success = 0;
                    }
                }
                else
                {
                    // form errors
                    $varError = $couponForm->getMessages();
                    
                    foreach ($varError as $keyError => $valueError)
                    {
                        
                        foreach ($appConfigForm['elements'] as $keyForm => $valueForm)
                        {
                            if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                            {
                                $varError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                            }
                        }
                        
                        $message[] = $varError;
                    }
                }
            }
            else
            {
               $message = $translator->translate('tr_MELIS_COMMERCE_COUPON_DATE_VALIDITY_INVALID');
            }

            $container['checkout'][$siteId]['coupons'] = $sessionCoupons;
        }
        else
        {
            $sessionCoupons = !empty($container['checkout'][$siteId]['coupons']) ? $container['checkout'][$siteId]['coupons'] : array();
        }
        
        /**
         * This input field set value in order to validate
         * after submission of the form proivided of this plugin
         */
        $couponForm->get('m_coupon_is_submit')->setValue(true);
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'couponForm' => $couponForm,
            'coupon' => $sessionCoupons,
            'message' => $message,
            'success' => $success,
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
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig = $this->pluginBackConfig['modal_form'];
        
        $response = [];
        $render   = [];
        if (!empty($formConfig))
        {
            foreach ($formConfig as $formKey => $config)
            {
                $form = $factory->createForm($config);
                $request = $this->getServiceLocator()->get('request');
                $parameters = $request->getQuery()->toArray();
                
                if (!isset($parameters['validate']))
                {
                    $form->setData($this->getFormData());
                    $viewModelTab = new ViewModel();
                    $viewModelTab->setTemplate($config['tab_form_layout']);
                    $viewModelTab->modalForm = $form;
                    $viewModelTab->formData   = $this->getFormData();
                    
                    $viewRender = $this->getServiceLocator()->get('ViewRenderer');
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
                    
                    $post = get_object_vars($request->getPost());
                    
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
            
            if (isset($xml->m_coupon_multiple))
            {
                $configValues['m_coupon_multiple'] = (string)$xml->m_coupon_multiple;
            }
             
            if (!empty($xml->m_coupon_site_id))
            {
                $configValues['m_coupon_site_id'] = (string)$xml->m_coupon_site_id;
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
         
        if (!empty($parameters['m_coupon_site_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_coupon_site_id><![CDATA[' . $parameters['m_coupon_site_id'] . ']]></m_coupon_site_id>';
        }
        
        if (isset($parameters['m_coupon_multiple']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_coupon_multiple><![CDATA[' . $parameters['m_coupon_multiple'] . ']]></m_coupon_multiple>';
        }
         
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
