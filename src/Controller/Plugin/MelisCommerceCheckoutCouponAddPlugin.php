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
        $messageErr = '';
        $siteId = (!empty($this->pluginFrontConfig['m_site_id'])) ? $this->pluginFrontConfig['m_site_id'] : '';
        
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['meliscommerce_checkout_coupon_form'])) ? $this->pluginFrontConfig['forms']['meliscommerce_checkout_coupon_form'] : null;
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $couponForm = $factory->createForm($appConfigForm);
        
        $couponSrv = $this->getServiceLocator()->get('MelisComCouponService');
        
        $container = new Container('meliscommerce');
        if (!isset($container['checkout']))
        {
            $container['checkout'] = array();
        }
        
        $couponCode = '';
        if (isset($container['checkout'][$siteId]['couponId']))
        {
            $couponTmp = $couponSrv->getCouponById($container['checkout'][$siteId]['couponId']);
            if (!is_null($couponTmp))
            {
                $couponCode = $couponTmp->getCoupon()->coup_code;
            }
        }
        
        $data['m_coupon_code'] = (!empty($this->pluginFrontConfig['m_coupon_code'])) ? $this->pluginFrontConfig['m_coupon_code'] : $couponCode;
       
        if (!empty($data['m_coupon_code']))
        {
            $couponForm->setData($data);
            
            $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
            $clientId = null;
            if ($ecomAuthSrv->hasIdentity())
            {
                $clientId = $ecomAuthSrv->getClientId();
            }
            
            $couponData = $couponSrv->validateCoupon($data['m_coupon_code'], $clientId);
            
            if ($couponData['success'])
            {
                $coupon = $couponData['coupon'];
                
                /**
                 * Adding the coupon id to commerce session
                 * to be able to access from other plugin easily
                 */
                $container['checkout'][$siteId]['couponId'] = $coupon->coup_id;
            }
            else 
            {
                $translator = $this->getServiceLocator()->get('translator');
                $messageErr = $translator->translate('tr_'.$couponData['error']);
                
                if (isset($container['checkout'][$siteId]['couponId']))
                {
                    unset($container['checkout'][$siteId]['couponId']);
                }
            }
        }
        else 
        {
            if (isset($container['checkout'][$siteId]['couponId']))
            {
                unset($container['checkout'][$siteId]['couponId']);
            }
        }
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'couponForm' => $couponForm,
            'coupon' => $coupon,
            'messageErr' => $messageErr
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
