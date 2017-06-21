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
use Zend\Stdlib\ArrayUtils;
/**
 * This plugin implements the business logic of the
 * "order" plugin.
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
 * $plugin = $this->MelisCommerceOrderPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceOrderPlugin();
 * $parameters = array(
 *      'template_path' => 'your-site-folder/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'orderList');
 * 
 * How to display in your controller's view:
 * echo $this->orderList;
 * 
 * 
 */
class MelisCommerceOrderPlugin extends MelisTemplatingPlugin
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
        $orderAddressPluginView = array();
        $orderShippingDetailsView = array();
        $orderMessagesView = array();
        
        $langId = !empty($this->pluginFrontConfig['language'])? $this->pluginFrontConfig['language'] : 1;
        $orderId = !empty($this->pluginFrontConfig['m_c_order'])? $this->pluginFrontConfig['m_c_order'] : null;
        $imageType = !empty($this->pluginFrontConfig['m_basket_image'])? $this->pluginFrontConfig['m_basket_image'] : array();
        $imageSrc = !empty($this->pluginFrontConfig['m_image_src'])? $this->pluginFrontConfig['m_image_src'] : null;
        $addressTempPath = !empty($this->pluginFrontConfig['address_template_path'])? $this->pluginFrontConfig['address_template_path'] : null;
        $shippTempPath = !empty($this->pluginFrontConfig['shipping_template_path'])? $this->pluginFrontConfig['shipping_template_path'] : null;
        $messageTempPath = !empty($this->pluginFrontConfig['message_template_path'])? $this->pluginFrontConfig['message_template_path'] : null;
        
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $orderSvc = $this->getServiceLocator()->get('MelisComOrderService');
        $orderStatus = $orderSvc->getOrderStatusList($langId);
        $currencySvc = $this->getServiceLocator()->get('MelisComCurrencyService');
        $couponSvc = $this->getServiceLocator()->get('MelisComCouponService');
        $variantSvc = $this->getServiceLocator()->get('MelisComVariantService');
        $documentSvc = $this->getServiceLocator()->get('MelisComDocumentService');

        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $data = $orderSvc->getOrderById($orderId, $langId);
           
            $orderCoupons = $couponSvc->getCouponList($orderId);
            $tmp = array();
            foreach($orderCoupons as $coupon){
            
                if($coupon->getCoupon()->coup_product_assign){
                    $coupon->getCoupon()->discountedBasket = $couponSvc->getCouponDiscountedBasketItems($coupon->getCoupon()->coup_id, $orderId);
                }
                $tmp[] = $coupon;
            }
            
            $orderCoupons = $tmp;
            
            if(!empty($data->getClient())){
                if($data->getClient()->cli_id == $clientId){
                    //auhtorized
                    $order = array();
                    
                    $currencies = $currencySvc->getCurrencies();
                    
                    
                    $status = '';
                    
                    foreach($orderStatus as $stat){
                    
                        if($stat->ostt_status_id == $data->getOrder()->ord_status){
                            $status = $stat->ostt_status_name;
                        }
                    }
                    
                    $count = 0;
                    $orderDetails = array();
                    $subTotal = 0;
                    $total = 0;
                    $shipping = 0;
                   
                    foreach($data->getBasket() as $basket){
                        
                        $basket->discount = 0;
                        if(!empty($orderCoupons)){
                        
                            foreach($orderCoupons as $coupon){
                        
                                if($coupon->getCoupon()->coup_product_assign){
                        
                                    foreach($coupon->getCoupon()->discountedBasket as $item){
                        
                                        if ($item->cord_basket_id == $basket->obas_id){
                        
                                            if(!empty($coupon->getCoupon()->coup_percentage)){
                        
                                                $basket->discount = ($coupon->getCoupon()->coup_percentage / 100) * $basket->obas_price_net;
                        
                                            } elseif (!empty($coupon->getCoupon()->coup_discount_value)){
                        
                                                $basket->discount = $coupon->getCoupon()->coup_discount_value * $item->cord_quantity_used;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    
                        $count = $count + $basket->obas_quantity;
                        $details = array();
                        $currency = '';
                        
                        foreach($currencies as $cur){
                    
                            if($cur->cur_id == $basket->obas_currency){
                                $currency = $cur->cur_symbol;
                            }
                        }
                        $image = '';
                        if(!is_null($imageType)){
                           $tmp = array();
                           
                            if(is_null($imageSrc)){
                                $imageSrc = 'variant';
                                $id = $basket->obas_variant_id;
                            }
                            
                            if($imageSrc == 'product'){
                                $product = $variantSvc->getProductByVariantId($basket->obas_variant_id);
                                $id = $product->prd_id;
                            }
                            
                            $tmp = $documentSvc->getDocumentsByRelationAndTypes($imageSrc, $id, 'IMG', array($imageType));
                            
                            if(empty($tmp) && $imageSrc == 'variant'){
                                $product = $variantSvc->getProductByVariantId($basket->obas_variant_id);
                                $id = $product->prd_id;
                                $tmp = $documentSvc->getDocumentsByRelationAndTypes($imageSrc, $id, 'IMG', array($imageType));
                            }
                            
                            if(!empty($tmp)){
                                $image = $tmp[0]->doc_path;
                            }
                        }
                        $details['image'] = $image;
                        $details['productName'] = $basket->obas_product_name;
                        $details['sku'] = $basket->obas_sku;
                        $details['currency'] = $currency;
                        $details['price'] = $basket->obas_price_net;
                        $details['total'] = ($basket->obas_price_net * $basket->obas_quantity) - $basket->discount;
                        $details['discount'] = $basket->discount;
                        $details['quantity'] = $basket->obas_quantity;
                        
                        $subTotal += $details['total'];
                        $items[] = $details;
                    }
                    
                    $couponDetails = array();
                    if(!empty($orderCoupons)){
                    
                        foreach($orderCoupons as $coupon){
                    
                            if(!$coupon->getCoupon()->coup_product_assign){
                                $couponDetails[] = array(
                                    'couponCode' => $coupon->getCoupon()->coup_code,
                                    'couponIsInPercentage' => ($coupon->getCoupon()->coup_percentage) ? true : false,
                                    'couponValue' => ($coupon->getCoupon()->coup_percentage) ? $coupon->getCoupon()->coup_percentage.'%' : $coupon->getCoupon()->coup_discount_value,
                                    'couponDiscount' => ($coupon->getCoupon()->coup_percentage) ? ($coupon->getCoupon()->coup_percentage / 100) * $coupon->getCoupon()->opay_price_order : $coupon->getCoupon()->coup_discount_value,
                                );
                            }
                        }
                    }
                    
                    if(!empty($data->getPayment())){
                    
                        foreach($data->getPayment() as $payment){
                            $shipping = $shipping + $payment->opay_price_shipping;
                            $total = $total + $payment->opay_price_total;
                        }
                    }
                    
                    $order['subTotal'] = $subTotal;
                    $order['total'] = $total;
                    $order['shipping'] = $shipping;
                    $order['id'] = $data->getId();
                    $order['reference'] = $data->getOrder()->ord_reference;
                    $order['date'] = $data->getOrder()->ord_date_creation;
                    $order['status'] = $status;
                    $order['itemCount'] = $count;
                    $order['currency'] = $currency;
                    $order['items'] = $items;
                    $order['coupons'] = $couponDetails;
                }
            }
        }
        
        // Use Address plugin
        $orderAddressPlugin = $this->getServiceLocator()->get('ControllerPluginManager')->get('MelisCommerceOrderAddressPlugin');
        $orderAddressParameter = array(
            'template_path' => $addressTempPath,
        );
        $orderAddressPluginView = $orderAddressPlugin->render($orderAddressParameter);
        
        // Use shipping details plugin
        $orderShippingDetailsPlugin = $this->getServiceLocator()->get('ControllerPluginManager')->get('MelisCommerceOrderShippingDetailsPlugin');
        $orderShippingDetailsParemter = array(
            'template_path' => $shippTempPath,
        );
        $orderShippingDetailsView = $orderShippingDetailsPlugin->render($orderShippingDetailsParemter);
        
        // Use order messages plugin
        $orderMessagesPlugin = $this->getServiceLocator()->get('ControllerPluginManager')->get('MelisCommerceOrderMessagesPlugin');
        $orderMessagesParameter = array(
            'template_path' => $messageTempPath,
        );
        
        $orderMessagesView = $orderMessagesPlugin->render($orderMessagesParameter);
        
        $viewVariables = array(
            'order' => $order,
            'orderAddressView' => $orderAddressPluginView,
            'orderShippingDetailsView' => $orderShippingDetailsView,
            'orderMessagesView' => $orderMessagesView,
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    public function back()
    {
        
    }
}
