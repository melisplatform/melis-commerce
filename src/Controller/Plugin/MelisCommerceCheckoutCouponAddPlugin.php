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
/**
 * This plugin implements the business logic of the
 * "coupon" plugin.
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
 * $plugin = $this->MelisCommerceCheckoutCouponAddPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/checkout/coupon'
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
class MelisCommerceCheckoutCouponAddPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
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
        $siteId = (!empty($this->pluginFrontConfig['m_site_id'])) ? $this->pluginFrontConfig['m_site_id'] : '';
        $isMultiple = !empty($this->pluginFrontConfig['m_coupon_multiple'])? $this->pluginFrontConfig['m_coupon_multiple'] : false;
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['meliscommerce_checkout_coupon_form'])) ? $this->pluginFrontConfig['forms']['meliscommerce_checkout_coupon_form'] : $this->pluginConfig['forms']['meliscommerce_checkout_coupon_form'];
        $isSubmit = (!empty($this->pluginFrontConfig['m_is_submit'])) ? $this->pluginFrontConfig['m_is_submit'] : false;
        $couponCode = (!empty($this->pluginFrontConfig['m_coupon_code'])) ? $this->pluginFrontConfig['m_coupon_code'] : null;
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $couponForm = $factory->createForm($appConfigForm);
        
        $couponSrv = $this->getServiceLocator()->get('MelisComCouponService');
        $basketSrv = $this->getServiceLocator()->get('MelisComBasketService');
        $checkoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
        $translator = $this->getServiceLocator()->get('translator');
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        
        $container = new Container('meliscommerce');
        $coupons = !empty($container['checkout'][$siteId]['coupons'])? $container['checkout'][$siteId]['coupons'] : array();
	    $productCoupons = !empty($coupons['productCoupons'])? $coupons['productCoupons'] : array();
	    $generalCoupons = !empty($coupons['generalCoupons'])? $coupons['generalCoupons'] : array();
        
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
                if($couponForm->isValid()){
                    $couponData = $couponForm->getData();
                    
                    $basketData = $basketSrv->getBasket($clientId, $clientKey);
                    
                    if(!empty($basketData)){
                        foreach($basketData as $item){
                            
                            $items[] = $item->getVariant()->getVariant()->var_prd_id;
                        }
                        $items = array_unique($items);
                    }
                    
                    $validatedCoupon = $checkoutService->validateCoupon($couponData['m_coupon_code'], $clientId, $items);
                    
                    if($validatedCoupon['success']){
                        
                        $validCoupon = array($validatedCoupon['coupon']->coup_id => $validatedCoupon['coupon']);
                        
                        if($validatedCoupon['type'] == 'general'){
                            
                            // general coupons
                            $generalCoupons = ($isMultiple)? $validCoupon + $generalCoupons: $validCoupon;
                            $productCoupons = ($isMultiple)?  $productCoupons :  array();
                            
                            
                        }else{
                            
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
                        
                    }else{
                        
                        if(!$isMultiple){
                            // clear data if not multiple coupons
                            $sessionCoupons = array();
                        }
                        
                        // invalid coupon
                        $message = $translator->translate('tr_'.$validatedCoupon['error']);
                        $sessionCoupons['couponErr'] = $message;
                        $success = 0;
                    }
                    
                }else{
                    
                    // form errors
                    $varError = $couponForm->getMessages();
                    
                    foreach ($varError as $keyError => $valueError){
                        
                        foreach ($appConfigForm['elements'] as $keyForm => $valueForm){
                            
                            if ($valueForm['spec']['name'] == $keyError &&
                                !empty($valueForm['spec']['options']['label']))
                                $varError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                        }
                        $message[] = $varError;
                    }
                }
            
            }
            else
            {
               $message = $translator->translate('tr_MELIS_COMMERCE_COUPON_DATE_VALIDITY_INVALID');
               
            }
        }else{
            $sessionCoupons = array();
        }
        
        $container['checkout'][$siteId]['coupons'] = $sessionCoupons;
        
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
}
