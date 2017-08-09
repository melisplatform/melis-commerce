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
 * $plugin = $this->MelisCommerceCheckoutConfirmPlugin();
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
class MelisCommerceCheckoutConfirmPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $clientId = null;
        $order = array();
        $orderBasket = array();
        $clientOrder = array();
        $orderAddressPluginView = array();
        $orderShippingDetailsView = array();
        $errMsg = '';
        
        $translator = $this->getServiceLocator()->get('translator');
        
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        $orderId = !empty($this->pluginFrontConfig['m_c_order'])? $this->pluginFrontConfig['m_c_order'] : null;
        $imageType = !empty($this->pluginFrontConfig['m_image_type'])? $this->pluginFrontConfig['m_image_type'] : '';
        $customImage = !empty($this->pluginFrontConfig['m_custom_image'])? $this->pluginFrontConfig['m_custom_image'] : '';
        $addressParameters = !empty($this->pluginFrontConfig['address_parameters'])? $this->pluginFrontConfig['address_parameters'] : array();
        $shippingParameters = !empty($this->pluginFrontConfig['shipping_parameters'])? $this->pluginFrontConfig['shipping_parameters'] : array();
        
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $orderSvc = $this->getServiceLocator()->get('MelisComOrderService');
        $currencyTbl = $this->getServiceLocator()->get('MelisEcomCurrencyTable');
        $couponSvc = $this->getServiceLocator()->get('MelisComCouponService');
        $variantSvc = $this->getServiceLocator()->get('MelisComVariantService');
        
        $clientOrderDetails = $orderSvc->getClientOrderDetailsById($orderId, $ecomAuthSrv->getClientId(), $ecomAuthSrv->getPersonId(), $langId);
        
        $orderCoupons = $couponSvc->getCouponList($orderId);
        $tmp = array();
        foreach($orderCoupons as $coupon){
            
            if($coupon->getCoupon()->coup_product_assign){
                $coupon->getCoupon()->discountedBasket = $couponSvc->getCouponDiscountedBasketItems($coupon->getCoupon()->coup_id, $orderId);
            }
            $tmp[] = $coupon;
        }
        
        $orderCoupons = $tmp;
        
        if (!empty($clientOrderDetails))
        {
            switch ($clientOrderDetails->ord_status)
            {
                case '1':
                    break;
                case '-1':
                    // Result message if the checkout payment had been skiped
                    $errMsg = $translator->translate('tr_meliscommerce_order_checkout_confirmation_skip_payment');
                    break;
                case '6':
                    // 6 is the primary key of status code for error in checkout
                    // Result message if the checkout payment paid amount is not equal to Total cost of the client basket
                    $errMsg = $translator->translate('tr_meliscommerce_order_checkout_confirmation_price_not_equal');
                    break;
                default:
                    // System error
                    $errMsg = $translator->translate('Something went wrong');
                    break;
            }
            
            $clientOrderId = $clientOrderDetails->ord_id;
            
            $orderBasket = $orderSvc->getOrderBasketByOrderId($clientOrderId);
            $totalBasket = 0;
            $currency = null;
            $totalProductDiscount  = 0;
            foreach ($orderBasket As $key => $val)
            {
                $val->discount = 0;
                if(!empty($orderCoupons)){
                    
                    foreach($orderCoupons as $coupon){
                    
                        if($coupon->getCoupon()->coup_product_assign){
                    
                            foreach($coupon->getCoupon()->discountedBasket as $item){
                                
                                if ($item->cord_basket_id == $val->obas_id){
                                    
                                    if(!empty($coupon->getCoupon()->coup_percentage)){
                                    
                                        $val->discount = ($coupon->getCoupon()->coup_percentage / 100) * ($val->obas_price_net * $item->cord_quantity_used);
                                        
                                    } elseif (!empty($coupon->getCoupon()->coup_discount_value)){
                                        
                                        $val->discount = $coupon->getCoupon()->coup_discount_value * $item->cord_quantity_used;
                                    }
                                }
                            }
                        }
                    }
                }
                
                $totalBasket += ($val->obas_price_net * $val->obas_quantity);
                
                if (is_null($currency))
                {
                    $prdCurrency = $currencyTbl->getEntryById($val->obas_currency)->current();
                    if (!empty($prdCurrency))
                    {
                        $currency = $prdCurrency->cur_symbol;
                    }
                }
                
                if (!is_null($currency))
                {
                    $orderBasket[$key]->cur_symbol = $currency;
                }
                
                $totalProductDiscount += $val->discount;
                $orderBasket[$key] = $val;
                $orderBasket[$key]->final_image = $variantSvc->getFinalVariantImage($val->obas_variant_id, array($imageType), $customImage);
            }
            
            $couponDetails = array();
            if(!empty($orderCoupons)){
            
                foreach($orderCoupons as $coupon){
            
                    if(!$coupon->getCoupon()->coup_product_assign){
                        $couponDetails[] = array(
                            'couponCode' => $coupon->getCoupon()->coup_code,
                            'couponIsInPercentage' => ($coupon->getCoupon()->coup_percentage) ? true : false,
                            'couponValue' => ($coupon->getCoupon()->coup_percentage) ? $coupon->getCoupon()->coup_percentage.'%' : $coupon->getCoupon()->coup_discount_value,
                            'couponDiscount' => ($coupon->getCoupon()->coup_percentage) ? ($coupon->getCoupon()->coup_percentage / 100) * $totalBasket : $coupon->getCoupon()->coup_discount_value,
                        );
                    }
                }
            }
            
            $clientOrder = array(
                'orderId' => $clientOrderId,
                'orderStatus' => $clientOrderDetails->ostt_status_name,
                'orderReference' => $clientOrderDetails->ord_reference,
                'orderDate' => $clientOrderDetails->ord_date_creation,
                'orderSubtotal' => $clientOrderDetails->opay_price_order - $totalProductDiscount,
                'orderCouponDetails' => $couponDetails,
                'orderSippingTotal' => $clientOrderDetails->opay_price_shipping,
                'orderTotal' => $clientOrderDetails->opay_price_total,
                'orderCurrency' => $currency,
            );
            
            // Use Address plugin
            $orderAddressPlugin = $this->getServiceLocator()->get('ControllerPluginManager')->get('MelisCommerceOrderAddressPlugin');
            $addressParameters = ArrayUtils::merge($addressParameters, array('m_c_order' => $clientOrderId, 'language' => $langId));
            $orderAddressPluginView = $orderAddressPlugin->render($addressParameters);
            
        }
        
        $viewVariables = array(
            'order' => $clientOrder,
            'orderBasket' => $orderBasket,
            'orderAddressView' => $orderAddressPluginView,
            'orderErrMsg' => $errMsg,
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
