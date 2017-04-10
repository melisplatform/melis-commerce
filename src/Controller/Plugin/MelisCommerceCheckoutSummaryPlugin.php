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
 * "checkOutSummary" plugin.
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
 * $plugin = $this->MelisCommerceCheckoutSummaryPlugin();
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
 * $view->addChild($pluginView, 'checkOutSummary');
 * 
 * How to display in your controller's view:
 * echo $this->checkOutSummary;
 * 
 * 
 */
class MelisCommerceCheckoutSummaryPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $checkOutCart = array();
        $currency = '';
        $subTotal = 0;
        $totalDiscount = 0;
        $discountInfo = '';
        $couponMsg = '';
        $shippingTotal = 0;
        $couponCode = '';
        $total = 0;
        $hasCartItems = false;
        $hasErr = false;
        $confirm_success = 0;
        $checkootErrorMsg = '';
        
        $translator = $this->getServiceLocator()->get('translator');
        
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        $countryId = (!empty($this->pluginFrontConfig['m_country_id'])) ? $this->pluginFrontConfig['m_country_id'] : null;
        $siteId = (!empty($this->pluginFrontConfig['m_site_id'])) ? $this->pluginFrontConfig['m_site_id'] : null;
        
        $confirmSummary = (!empty($this->pluginFrontConfig['m_confirm_summary'])) ? $this->pluginFrontConfig['m_confirm_summary'] : false;
        
        /**
         * Getting the User identity using Commerce Authentication Service
         */
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
            
            $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
            $melisComOrderCheckoutService->setSiteId($siteId);
            $clientOrderCost = $melisComOrderCheckoutService->computeAllCosts($clientId);
            $validatedBasket = $melisComOrderCheckoutService->validateBasket($clientId);
            
            if (isset($clientOrderCost['costs']['order']))
            {
                $clientOrder = $clientOrderCost['costs']['order'];
                
                if (isset($clientOrder['details']))
                {
                    $clientOrderVariant =  $clientOrder['details'];
                    
                    if (!empty($clientOrderVariant))
                    {
                        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
                        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
                        
                        foreach ($clientOrderVariant As $key => $val)
                        {
                            $variantErr = '';
                            if (!empty($validatedBasket['basket']['ko'][$key]['error']))
                            {
                                $variantErr = $translator->translate('tr_'.$validatedBasket['basket']['ko'][$key]['error']);
                                $hasErr = true;
                            }
                            
                            $variantId = $key;
                            $variant = $melisComVariantService->getVariantById($variantId);
                            $productId = $variant->getVariant()->var_prd_id;
                            $varSku = $variant->getVariant()->var_sku;
            
                            // Getting the Variant price from Variant Service
                            $varPrice = $melisComVariantService->getVariantFinalPrice($variantId, $countryId);
            
                            if (is_null($varPrice))
                            {
                                // if Vairant Price is null this will try to get from Product Price
                                $varPrice = $melisComProductService->getProductFinalPrice($productId, $countryId);
                            }
            
                            $quantity = $val['quantity'];
            
                            $variantTotal = $quantity * $varPrice->price_net;
                            $data = array(
                                'var_id' => $variantId,
                                'var_sku' => $varSku,
                                'var_quantity' => $quantity,
                                'var_currency_symbol' => $varPrice->cur_symbol,
                                'var_price' => number_format($varPrice->price_net, 2),
                                'product_name' => $melisComProductService->getProductName($productId, $langId),
                                'var_total' => number_format($variantTotal, 2),
                                'var_err' => $variantErr
                            );
                            
                            $currency = $varPrice->cur_symbol;
                            array_push($checkOutCart, $data);
                        }
                    }
                }
                
                if (isset($clientOrder['totalWithoutCoupon']))
                {
                    $subTotal = $clientOrder['totalWithoutCoupon'];
                }
                
                if (isset($clientOrderCost['costs']['total']))
                {
                    $total = $clientOrderCost['costs']['total'];
                }
                
                if (isset($clientOrderCost['costs']['shipment']['total']))
                {
                    $shippingTotal = $clientOrderCost['costs']['shipment']['total'];
                }
                
                /**
                 * Checkouk Coupon Plugin
                 * This plugin will validate the coupon if coupon code if submitted
                 */
                $pluginManager = $this->getServiceLocator()->get('ControllerPluginManager');
                $checkoutCouponPlugin = $pluginManager->get('MelisCommerceCheckoutCouponAddPlugin');
                $checkoutCouponPluginParameters = array(
                    'm_site_id' => $siteId,
                );
                $couponView = $checkoutCouponPlugin->render($checkoutCouponPluginParameters);
                $coupon = $couponView->getVariables();
                
                if (!empty($coupon->coupon))
                {
                    $couponData = $coupon->coupon;
                    /**
                     * Checking if the coupon is using Pecentage or value
                     * that will deducted to the sub-total of the cart
                     */
                    if (!empty($couponData->coup_percentage))
                    {
                        $totalDiscount = ($couponData->coup_percentage / 100) * $subTotal;
                        $discountInfo = $couponData->coup_percentage.'%';
                    }
                    elseif (!empty($couponData->coup_discount_value))
                    {
                        $totalDiscount = $couponData->coup_discount_value;
                    }
                    
                    $couponCode = $couponData->coup_code;
                }
                else 
                {
                    $couponMsg = $coupon->message;
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
        
        $viewVariables = array(
            'checkOutCart' => $checkOutCart,
            'checkOutCartSubTotal' => $currency.number_format($subTotal, 2),
            'checkOutCartDiscount' => $currency.number_format($totalDiscount, 2),
            'checkOutCartCouponCode' => $couponCode,
            'checkOutCartDiscountInfo' => $discountInfo,
            'checkOutCartCouponErrMsg' => $couponMsg,
            'checkOutShipping' => $currency.number_format($shippingTotal, 2),
            'checkOutCartTotal' => $currency.number_format($total, 2),
            'checkOutHasErr' => $hasErr,
            'checkootErrorMsg' => $checkootErrorMsg,
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
