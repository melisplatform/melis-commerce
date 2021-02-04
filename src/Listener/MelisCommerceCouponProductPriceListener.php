<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\Session\Container;
use MelisCore\Listener\MelisGeneralListener;

class MelisCommerceCouponProductPriceListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_get_item_price_end',
            function($e) {
                $sm = $e->getTarget()->getServiceManager();
                $params = $e->getParams();

                // Price data from Event
                $price = $params['results'];

                // Extra data from params
                $data = $params['data'];

                if (empty($params['data']) 
                    || empty($price['price']))
                    return;

                if (!isset($data['method']))
                    return;

                // Coupon only computed in checkout transaction
                $container = new Container('meliscommerce');
                if(!isset($container['checkout'])) 
                    return;

                $checkoutService = $sm->get('MelisComOrderCheckoutService');
                $siteId = $checkoutService->getSiteId();

                // No coupons assign to site checkout
                if(!isset($container['checkout'][$siteId]['coupons'])) 
                    return;

                // Checkout coupons
                $coupons = $container['checkout'][$siteId]['coupons'];

                // Product coupon session stored
                if (empty($coupons['productCoupons']))
                    return;

                if ($params['data']['method'] == 'computeOrderCost') {

                    if ($params['type'] == 'product')
                        $productId = $params['itemId'];
                    else {
                        $variantSrv = $sm->get('MelisComVariantService');
                        $variant = $variantSrv->getVariantById($params['itemId']);

                        if ($variant) {
                            $variant = $variant->getVariant();
                            $productId = $variant->var_prd_id;
                        }
                    }

                    // Matching if product assigned to coupon
                    $couponSrv = $sm->get('MelisComCouponService');
                    $productCoupon = null;
                    foreach($coupons['productCoupons'] As $coupon) {
                        $couponPrd = $couponSrv->getCouponProductList($coupon->coup_id, true);

                        foreach ($couponPrd As $prd)
                            if ($productId == $prd->getId())
                                $productCoupon = $coupon;
                    }

                    if (is_null($productCoupon))
                        return;

                    // Current available coupon to be used
                    $couponLimit = 0;
                    if(isset($container['checkout'][$siteId]['coupon_limit'][$productCoupon->coup_id]))
                        $couponLimit = $container['checkout'][$siteId]['coupon_limit'][$productCoupon->coup_id];
                    else
                        $couponLimit = $productCoupon->coup_max_use_number - $productCoupon->coup_current_use_number;

                    if (!$couponLimit) 
                        return;

                    // Discount computation
                    if (!empty($productCoupon->coup_percentage)) 
                        $disAmount = ($productCoupon->coup_percentage / 100) * $price['price'];
                    elseif (!empty($productCoupon->coup_discount_value)) 
                        $disAmount = $productCoupon->coup_discount_value;

                    // Current Item in the basket
                    $basket = $params['data']['basket'];

                    // Item Quantity from MelisBasket
                    $itemQuantity = $basket->getQuantity();

                    if ($itemQuantity > $couponLimit) {
                        $usableCoupon = 0;
                        $appliedCoupon = $couponLimit;
                    } else {
                        $usableCoupon = $couponLimit - $itemQuantity;
                        if ($usableCoupon < 1)
                            $usableCoupon = 0;

                        $appliedCoupon = $itemQuantity;
                    }
                    
                    // New Product/variant price with discount
                    $newPrice = $price['price'] - $disAmount;

                    // Coupon type
                    $couponAssign = 'GENERAL';
                    if ($productCoupon->coup_product_assign) 
                        $couponAssign = 'PRODUCT';

                    $module = 'MelisCommerce';
                    if (($productCoupon->coup_percentage))
                        $label = $productCoupon->coup_code . ' - '. $productCoupon->coup_percentage .'%';
                    else
                        $label = $productCoupon->coup_code . ' - '. $productCoupon->coup_discount_value;
                        
                    // Surcharge modules
                    $price['surcharge_module'][] = [
                        'module' => $module,
                        'label' => $label,
                        'coupon_id' => $productCoupon->coup_id,
                        'coupon_code' => $productCoupon->coup_code,
                        'coupon_percentage' => $productCoupon->coup_percentage,
                        'coupon_discount_value' => $productCoupon->coup_discount_value,
                        'coupon_assign' => $couponAssign,
                        'coupon_discount' => $disAmount,
                        'coupon_applied' => $appliedCoupon,
                        'initial_price' => $price['price'],
                        'new_price' => $newPrice
                    ];

                    // Adding logs to results
                    if (!empty($productCoupon->coup_percentage)) {
                        $price['logs'][] = $module.': Coupon of '.$productCoupon->coup_percentage.'%';
                        $price['logs'][] = $module.': tr_meliscommerce_price_common_discount_computation: '.$price['price'].' * '.$productCoupon->coup_percentage . '% = '.$disAmount;
                    } else {
                        $price['logs'][] = $module.': Coupon value '.$productCoupon->coup_discount_value;
                        $price['logs'][] = $module.': tr_meliscommerce_price_common_discount_computation: '.$price['price'].' - '.$productCoupon->coup_discount_value . ' = '.$disAmount;
                    }
                    
                    $price['logs'][] = $module.': tr_meliscommerce_price_common_price_change '.   $price['price'] .' - '. $disAmount .' = '. $newPrice;
                    
                    // Total amount computation of the item on the basket
                    $totalAmount = $newPrice * $basket->getQuantity();

                    if ($appliedCoupon < $basket->getQuantity()) {
                        $withCouponTotalAmount = $newPrice * $appliedCoupon;

                        $qtyNoCoupon = ($basket->getQuantity() - $appliedCoupon);
                        $withoutTotalAmount = $price['price'] * $qtyNoCoupon;
                        $totalAmount = $withCouponTotalAmount + $withoutTotalAmount;

                        $price['logs'][] = $module.': Coupon applied only '. $appliedCoupon .' Product(s) with the price of '. $newPrice .', total of '. $withCouponTotalAmount;
                        $price['logs'][] = $module.': Remaining '. $qtyNoCoupon .' Product(s) used regular price of '. $price['price'] .', total of '. $withoutTotalAmount;
                    }

                    // Deducting Product coupon limit
                    $container['checkout'][$siteId]['coupon_limit'] = [
                        $productCoupon->coup_id => $usableCoupon
                    ];

                    // Set new price to result
                    $price['price'] = $newPrice;

                    // Set new item total amount to result
                    $price['total_amount'] = $totalAmount;

                    // Set param from updated price
                    $e->setParam('results', $price);
                }
            },
            // Discount listener trigger from the lowest priority of the event
            // For Coupon this listener executed from the lowest priority of the events
            -999
        );


        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_checkout_order_computation_end',
            function($e){
                $sm = $e->getTarget()->getServiceManager();

                // If there is no data from $_GET[], this will try to use coupon data from Session
                $checkoutService = $sm->get('MelisComOrderCheckoutService');
                $siteId = $checkoutService->getSiteId();

                // Product coupon count limit 
                // Data stored only when order computation if called
                $container = new Container('meliscommerce');
                $container['checkout'][$siteId]['coupon_limit'] = [];
            },
            -999
        );     
    }
}