<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Session\Container;

class MelisCommerceCheckoutCouponListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            '*',
            array(
                'meliscommerce_service_checkout_order_computation_end'
            ),
        	function($e){
        		$sm = $e->getTarget()->getServiceLocator();
        		$couponTable = $sm->get('MelisEcomCouponTable');
        		$variantTable = $sm->get('MelisEcomVariantTable');
        		$translator = $sm->get('translator');
        		$couponSrv = $sm->get('MelisComCouponService');
        		$checkoutService = $sm->get('MelisComOrderCheckoutService');
        		$params = $e->getParams();

        		// Getting $_GET[] parameters for CouponCode
        		$getValues = $sm->get('request')->getQuery();
        		$getValues = get_object_vars($getValues);

        		if ($params['results']['success'])
        		{
        		    $clientId = $params['results']['clientId'];
        		    $container = new Container('meliscommerce');
        		    // If there is no data from $_GET[], this will try to use coupon data from Session
        		    $melisComOrderCheckoutService = $sm->get('MelisComOrderCheckoutService');
        		    $siteId = $melisComOrderCheckoutService->getSiteId();
        		    
        		    $orders = !empty($params['results']['costs']['order']['details'])? $params['results']['costs']['order']['details'] : array();
        		    $discountedOrders = $orders;
        		    $items = array();

        		    if(isset($container['checkout'])) {
                        $container['checkout'][$siteId]['coupons']['couponErr'] = array();
                        $coupons = $container['checkout'][$siteId]['coupons'];
                        $productCoupons = !empty($coupons['productCoupons']) ? $coupons['productCoupons'] : array();
                        $generalCoupons = !empty($coupons['generalCoupons']) ? $coupons['generalCoupons'] : array();

                        if (!empty($orders)) {
                            foreach ($orders as $key => $val) {

                                $variant = $variantTable->getEntryById($key)->current();
                                $items[] = $variant->var_prd_id;
                            }
                            $items = array_unique($items);
                        }

                        // Checking first if couponId from Url $_GET[] data has a value
                        if (!empty($getValues['couponCode'])) {

                            $validatedCoupon = $checkoutService->validateCoupon($getValues['couponCode'], $clientId, $items);

                            if ($validatedCoupon['success']) {

                                $validCoupon = array($validatedCoupon['coupon']->coup_id => $validatedCoupon['coupon']);

                                //validate coupons, group them
                                if ($validatedCoupon['type'] == 'product') {
                                    $productCoupons = $productCoupons + $validCoupon;
                                    $coupons['productCoupons'] = $productCoupons;

                                } else {
                                    $generalCoupons = $generalCoupons + $validCoupon;
                                    $coupons['generalCoupons'] = $generalCoupons;
                                }

                            } else {

                                $container['checkout'][$siteId]['coupons']['couponErr'] = $translator->translate('tr_' . $validatedCoupon['error']);
                            }
                        }

                        // Remove deleted coupons
                        if (!empty($getValues['removeCoupon'])) {

                            foreach ($productCoupons as $key => $val) {

                                if ($val->coup_code == $getValues['removeCoupon']) {
                                    unset($productCoupons[$key]);
                                }
                            }

                            foreach ($generalCoupons as $key => $val) {

                                if ($val->coup_code == $getValues['removeCoupon']) {
                                    unset($generalCoupons[$key]);
                                }
                            }
                        }

                        // process product coupons to affect order prices
                        $tmp = array();
                        foreach ($productCoupons as $productCoupon) {

                            $discountedProducts = $couponSrv->getCouponProductList($productCoupon->coup_id, true);
                            $ids = array();

                            foreach ($discountedProducts as $discountProduct) {

                                $ids[] = $discountProduct->getId();
                            }

                            $usableQty = $productCoupon->coup_max_use_number - $productCoupon->coup_current_use_number;

                            foreach ($orders as $key => $val) {

                                $variant = $variantTable->getEntryById($key)->current();
                                $totalDiscount = 0;
                                $discountQty = 0;
                                $discount = 0;

                                if (in_array($variant->var_prd_id, $ids)) {

                                    $qty = (($usableQty - $val['quantity']) >= 0) ? $val['quantity'] : $usableQty;

                                    if ($qty > 0) {
                                        if (!empty($productCoupon->coup_percentage)) {
                                            $totalDiscount = ($productCoupon->coup_percentage / 100) * ($val['unit_price'] * $qty);
                                            $discount = ($productCoupon->coup_percentage / 100) * $val['unit_price'];
                                        } elseif (!empty($productCoupon->coup_discount_value)) {
                                            $totalDiscount = $productCoupon->coup_discount_value * $qty;
                                            $discount = $productCoupon->coup_discount_value;
                                        }

                                        $discountQty = $qty;
                                        $usableQty -= $qty;
                                    }
                                }

                                $val['discount'] += $val['discount'] + $totalDiscount;

                                $val['discount_details'] = !empty($val['discount_details']) ? $val['discount_details'] : array();

                                if (!empty($totalDiscount)) {
                                    $discounts = array(
                                        'discount' => $discount,
                                        'qty' => $discountQty,
                                    );
                                    $val['discount_details'][] = $discounts;
                                }

                                $val['total_price'] -= $discount;
                                $val['discount_total'] = !empty($val['discount_total']) ? $val['discount_total'] + $totalDiscount : $totalDiscount;
                                $discountedOrders[$key] = $val;
                            }

                            $orders = $discountedOrders;
                        }

                        $params['results']['costs']['order']['details'] = $orders;

                        $totalWithoutCoupon = 0;
                        $total = 0;
                        $discount = 0;

                        foreach ($orders as $key => $val) {

                            $discount += !empty($val['discount_total']) ? $val['discount_total'] : 0;
                            $varTotal = $val['unit_price'] * $val['quantity'];
                            $totalWithoutCoupon += $varTotal;
                            $params['results']['costs']['order']['totalWithoutCoupon'] = $totalWithoutCoupon;
                            $params['results']['costs']['order']['totalWithProductCoupon'] = $totalWithoutCoupon - $discount;
                            $params['results']['costs']['order']['total'] = $totalWithoutCoupon - $discount;

                        }

                        $orderDiscount = 0;
                        // process general coupons to affect order prices
                        foreach ($generalCoupons as $key => $val) {

                            $subTotal = $params['results']['costs']['order']['total'];

                            // Checking first if coupon percentage has value,
                            // Percentage is first priority to be use in discounting the total amount of order cost
                            // else this will try to use the fixed value for discount computations
                            if ($subTotal > 0) {
                                if (!empty($val->coup_percentage)) {
                                    $orderDiscount += ($val->coup_percentage / 100) * $subTotal;
                                } elseif (!empty($val->coup_discount_value)) {
                                    $orderDiscount += $val->coup_discount_value;
                                }
                            }
                        }
                        // Do nothing if totalDiscount is less than Zero
                        if ($orderDiscount > 0) {
                            $params['results']['costs']['order']['total'] = $subTotal - $orderDiscount;
                        }

                        $params['results']['costs']['order']['orderDiscount'] = $orderDiscount;
                        $container['checkout'][$siteId]['coupons']['productCoupons'] = $productCoupons;
                        $container['checkout'][$siteId]['coupons']['generalCoupons'] = $generalCoupons;
                    }
        		}
        	},
        	
        100);
        
        $this->listeners[] = $callBackHandler;
    }
    
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}