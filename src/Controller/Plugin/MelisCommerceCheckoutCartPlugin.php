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
 * "checkOutCart" plugin.
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
 * $plugin = $this->MelisCommerceCheckoutCartPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'checkOutCart');
 * 
 * How to display in your controller's view:
 * echo $this->checkOutCart;
 * 
 * 
 */
class MelisCommerceCheckoutCartPlugin extends MelisTemplatingPlugin
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
        $currency = null;
        $subTotal = 0;
        $totalDiscount = 0;
        $subTotalWithProdDiscount = 0;
        $orderDiscount = 0;
        $discountInfo = '';
        $total = 0;
        $errors = array();
        $couponView = null;
        $hasErr = false;
        $hasDiscount = false;
        
        $checkoutCartCouponParameters = (!empty($this->pluginFrontConfig['checkout_cart_coupon_parameters'])) ? $this->pluginFrontConfig['checkout_cart_coupon_parameters'] : array();
        
        $translator = $this->getServiceLocator()->get('translator');
        
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        $countryId = (!empty($this->pluginFrontConfig['m_country_id'])) ? $this->pluginFrontConfig['m_country_id'] : null;
        $siteId = (!empty($this->pluginFrontConfig['m_site_id'])) ? $this->pluginFrontConfig['m_site_id'] : null;
        
        $variantQuantities = (!empty($this->pluginFrontConfig['m_v_quantity'])) ? $this->pluginFrontConfig['m_v_quantity'] : null;
        
        $variantIdRemove = (!empty($this->pluginFrontConfig['m_v_id_remove'])) ? $this->pluginFrontConfig['m_v_id_remove'] : null;
        $variantRemoveLink = (!empty($this->pluginFrontConfig['m_v_remove_link'])) ? $this->pluginFrontConfig['m_v_remove_link'] : null;
        $imageType = !empty($this->pluginFrontConfig['m_image_type'])? $this->pluginFrontConfig['m_image_type'] : array();
        
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $basketSrv = $this->getServiceLocator()->get('MelisComBasketService');
        $variantSrv = $this->getServiceLocator()->get('MelisComVariantService');
        $couponSrv = $this->getServiceLocator()->get('MelisComCouponService');
        
        $clientKey = $ecomAuthSrv->getId();
        $clientId = null;
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
        }
        
        /**
         * Removing Product from Cart
         */
        if ($variantIdRemove)
        {
            $basketSrv->removeVariantFromBasket($variantIdRemove, 0, $clientId, $clientKey);
        }
        
        if (!empty($variantQuantities))
        {
            /**
             * Adding/Removing the Product from cart
             * by changing the quantity of the product
             */
            foreach ($variantQuantities As $varId => $varQty)
            {
                if (is_numeric($varQty))
                {
                    if ($varQty < 1)
                    {
                        /**
                         * variant that has zero (0) quantity will 
                         * automatically remove from the use's cart
                         */
                        $basketSrv->removeVariantFromBasket($varId, 0, $clientId, $clientKey);
                    }
                    else  
                    {
                        /**
                         * Checking varain stock
                         * else this will try to look Product Stock
                         */
                        $varStock = $variantSrv->getVariantFinalStocks($varId, $countryId);
                        
                        if ($varStock)
                        {
                            $varStock = $varStock->stock_quantity;
                            
                            if ($varStock >= $varQty)
                            {
                                $basketSrv->addVariantToBasket($varId, $varQty, $clientId, $clientKey);
                            }
                            else
                            {
                                $errors[$varId] = sprintf($translator->translate('tr_meliscommerce_products_plugins_stock_left'), $varStock);
                            }
                        }
                        else 
                        {
                            $errors[$varId] = $translator->translate('tr_meliscommerce_products_plugins_no_stock');
                        }
                    }
                }
                else 
                {
                    $errors[$varId] = 'Quantity must be numeric';
                }
            }
        }
        
        // Getting the client basket list using Client key
        $basketData = $basketSrv->getBasket($clientId, $clientKey);
        
        /**
         * Validiting the Client basket
         * this will process if the user has already identify/Loggedin
         */
        $validatedBasket = array();
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
            $melisComOrderCheckoutService->setSiteId($siteId);
            $validatedBasket = $melisComOrderCheckoutService->validateBasket($clientId);
        }
        
        if (!is_null($basketData)){
            
            /**
             * Checkouk Coupon Plugin
             * This plugin will validate the coupon if coupon code if submitted
             */
            $pluginManager = $this->getServiceLocator()->get('ControllerPluginManager');
            $checkoutCouponPlugin = $pluginManager->get('MelisCommerceCheckoutCouponAddPlugin');
            $checkoutCartCouponParameters = ArrayUtils::merge($checkoutCartCouponParameters, array(
                'm_site_id' => $siteId
            ));
            $couponView = $checkoutCouponPlugin->render($checkoutCartCouponParameters);
            $coupon = $couponView->getVariables();
            
            $sessionCoupons = !empty($coupon['coupon'])? $coupon['coupon'] : array();
            $generalCoupons = !empty($sessionCoupons['generalCoupons'])? $sessionCoupons['generalCoupons'] : array();
            $productCoupons = !empty($sessionCoupons['productCoupons'])? $sessionCoupons['productCoupons'] : array();
            
            $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
            $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
            
            $tmp = array();
            foreach($productCoupons as $productCoupon){
                
                $discountedProducts = $couponSrv->getCouponProductList($productCoupon->coup_id, true);
                $ids = array();
                foreach($discountedProducts as $discountProduct){
                    
                    $ids[] = $discountProduct->getId();
                }
                $productCoupon->products = $ids;
                $tmp[$productCoupon->coup_id] = clone $productCoupon;
            }
            $productCoupons = $tmp;
            
            foreach ($basketData As $val)
            {
                $variantId = $val->getVariantId();
                $variant = $val->getVariant();
                $quantity = $val->getQuantity();
                $productId = $variant->getVariant()->var_prd_id;
                $varSku = $variant->getVariant()->var_sku;
                
                $variantErr = '';
                if (!empty($validatedBasket['basket']['ko'][$variantId]['error']))
                {
                    $variantErr = $translator->translate('tr_'.$validatedBasket['basket']['ko'][$variantId]['error']);
                    $hasErr = true;
                }
                
                // Getting the Final Price of the variant
                $varPrice = $melisComVariantService->getVariantFinalPrice($variantId, $countryId);
                
                if (is_null($varPrice))
                {
                    // If the variant price not set on variant page this will try to get from the Product Price
                    $varPrice = $melisComProductService->getProductFinalPrice($productId, $countryId);
                }
                
                /**
                 * Using MelisComDocumentService service Category will get the docoment
                 * with the type of "IMG" and subType with "DEFAULT" to get the
                 * image for DEFAULT
                 * with the return is multi array
                 */
                $documentSrv = $this->getServiceLocator()->get('MelisComDocumentService');
                $doc = $documentSrv->getDocumentsByRelationAndTypes('product', $productId, 'IMG', array($imageType));
                $productImg = null;
                if (!empty($doc))
                {
                    $productImg = $doc[0]->doc_path;
                }
                
                // Compute variant total amount
                $variantTotal = $quantity * $varPrice->price_net;
                
                $discount = 0;
                $discountDetails = '';
                $tmp = array();
                // calculate final variant price with coupons applied
                foreach($productCoupons as $productCoupon){
                    $hasDiscount = true;
                    // get coupon quantity
                    $couponQty = $productCoupon->coup_max_use_number - $productCoupon->coup_current_use_number;
                    
                    // check if variant is discounted by a coupon
                    if(in_array($variant->getVariant()->var_prd_id, $productCoupon->products)){
                        
                        // get actual usable quantity
                        $usableCouponQty = (($couponQty - $quantity) >= 0)? $quantity : $couponQty;
                        
                        if($usableCouponQty > 0){
                            
                            if(!empty($productCoupon->coup_percentage)){
                                
                                $discount += ($productCoupon->coup_percentage / 100) * ($varPrice->price_net * $usableCouponQty);
                                $discountDetails = "$productCoupon->coup_percentage%";
                                
                            } elseif (!empty($productCoupon->coup_discount_value)){
                                
                                $discount += $productCoupon->coup_discount_value * $usableCouponQty;
                                $discountDetails = $discount;
                            }
                            
                            $productCoupon->coup_current_use_number = $productCoupon->coup_current_use_number + $usableCouponQty;
                            
                        }
                    }
                    
                    $tmp[$productCoupon->coup_id] = clone $productCoupon;
                }
                $productCoupons = $tmp;
                
                $data = array(
                    'var_id' => $variantId,
                    'var_sku' => $varSku,
                    'var_quantity' => $quantity,
                    'var_currency_symbol' => $varPrice->cur_symbol,
                    'var_price' => $varPrice->price_net,
                    'product_name' => $melisComProductService->getProductName($productId, $langId),
                    'product_img' => $productImg,
                    'var_total' => $variantTotal - $discount,
                    'var_remove_link' => $variantRemoveLink.'?m_v_id_remove='.$variantId,
                    'var_err' => $variantErr,
                    'var_discount' => $discount,
                    'var_discount_details' => $discountDetails,
                );
                
                // Setting the currency use of the cart
                $currency = $varPrice->cur_symbol;
                $subTotal += $variantTotal;
                $subTotalWithProdDiscount += $variantTotal - $discount;
                array_push($checkOutCart, $data);
            }
            
            foreach($generalCoupons as $generalCoupon){
                $hasDiscount = true;
                if(!empty($generalCoupon->coup_percentage)){
                    
                    $totalDiscount = ($generalCoupon->coup_percentage / 100) * $subTotalWithProdDiscount;
                    $discountInfo[] = array(
                        'details' => $generalCoupon->coup_percentage.'%',
                        'amount' => $currency.number_format($totalDiscount, 2),
                    );
                    
                }elseif (!empty($generalCoupon->coup_discount_value)){
                    
                    $totalDiscount = $generalCoupon->coup_discount_value;
                    $discountInfo[] =  array(
                        'details' => $totalDiscount,
                        'amount' => $currency.number_format($totalDiscount,2),
                    );
                }
                
                $orderDiscount += $totalDiscount;
            }
            
            $total = $subTotalWithProdDiscount - $orderDiscount;
        }
        
        $viewVariables = array(
            'checkOutCart' => $checkOutCart,
            'checkOutCartSubTotal' => $currency.number_format($subTotalWithProdDiscount, 2),
            'checkOutCartDiscount' => $currency.number_format($orderDiscount, 2),
            'checkOutCartDiscountInfo' => $discountInfo,
            'checkOutCartTotal' => $currency.number_format($total, 2),
            'checkoutErrors' => $errors,
            'checkoutCoupon' => $couponView,
            'checkoutHasCoupon' => $hasDiscount,
            'checkoutHasErr' => $hasErr,
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
