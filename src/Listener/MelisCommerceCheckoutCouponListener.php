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

class MelisCommerceCheckoutCouponListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {

        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_checkout_order_computation_start',
            function ($e) {
                $sm = $e->getTarget()->getServiceManager();

                $params = $e->getParams();

                // Getting $_GET[] parameters for CouponCode
                $getValues = $sm->get('request')->getQuery()->toArray();


                if (!empty($params['clientId'])) {

                    $clientId = $params['clientId'];

                    // Get the basket from the BasketService
                    $basketService = $sm->get('MelisComBasketService');
                    $orders = $basketService->getBasket($clientId);

                    $productIds = [];
                    if (!empty($orders)) {
                        foreach ($orders as $var) {
                            $variant = $var->getVariant();
                            $productIds[] = $variant->getVariant()->var_prd_id;
                        }
                        $productIds = array_unique($productIds);
                    }

                    $orderCheckoutService = $sm->get('MelisComOrderCheckoutService');
                    $siteId = $orderCheckoutService->getSiteId();


                    $container = new Container('meliscommerce');
                    $container['checkout'][$siteId]['coupons']['couponErr'] = [];
                    $coupons = $container['checkout'][$siteId]['coupons'] ?? [];
                    $productCoupons = !empty($coupons['productCoupons']) ? $coupons['productCoupons'] : array();
                    $generalCoupons = !empty($coupons['generalCoupons']) ? $coupons['generalCoupons'] : array();

                    // Checking first if couponId from Url $_GET[] data has a value
                    if (!empty($getValues['couponCode'])) {

                        $validatedCoupon = $orderCheckoutService->validateCoupon($getValues['couponCode'], $clientId, $productIds);

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

                            $container['checkout'][$siteId]['coupons']['couponErr'] = $sm->get('translator')->translate('tr_' . $validatedCoupon['error']);
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

                    $container['checkout'][$siteId]['coupons']['productCoupons'] = $productCoupons;
                    $container['checkout'][$siteId]['coupons']['generalCoupons'] = $generalCoupons;
                }
            }
        );


        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_checkout_order_computation_end',
            function ($e) {
                $sm = $e->getTarget()->getServiceManager();
                $couponTable = $sm->get('MelisEcomCouponTable');
                $variantTable = $sm->get('MelisEcomVariantTable');
                $translator = $sm->get('translator');
                $couponSrv = $sm->get('MelisComCouponService');
                $checkoutService = $sm->get('MelisComOrderCheckoutService');
                $params = $e->getParams();

                // Getting $_GET[] parameters for CouponCode
                $getValues = $sm->get('request')->getQuery()->toArray();

                if ($params['results']['success']) {
                    // $clientId = $params['results']['clientId'];
                    $container = new Container('meliscommerce');
                    // If there is no data from $_GET[], this will try to use coupon data from Session
                    $melisComOrderCheckoutService = $sm->get('MelisComOrderCheckoutService');
                    $siteId = $melisComOrderCheckoutService->getSiteId();

                    $orders = !empty($params['results']['costs']['order']['details']) ? $params['results']['costs']['order']['details'] : array();
                    $discountedOrders = $orders;
                    $items = array();

                    if (isset($container['checkout'])) {

                        if (!isset($container['checkout'][$siteId]['coupons']))
                            return;

                        // $container['checkout'][$siteId]['coupons']['couponErr'] = array();
                        $coupons = $container['checkout'][$siteId]['coupons'];
                        $productCoupons = !empty($coupons['productCoupons']) ? $coupons['productCoupons'] : array();
                        $generalCoupons = !empty($coupons['generalCoupons']) ? $coupons['generalCoupons'] : array();

                        $totalProductDiscount = 0;
                        foreach ($orders as $key => $order) {

                            // $discount = 0;
                            if (!empty($order['price_details']['surcharge_module'])) {
                                foreach ($order['price_details']['surcharge_module'] as $dis) {

                                    if (
                                        !empty($dis['module']) & $dis['module'] == 'MelisCommerce'
                                        && $dis['coupon_assign'] == 'PRODUCT'
                                    ) {

                                        // $discount += $dis['coupon_discount'] * $dis['coupon_applied'];
                                        $orders[$key]['discount_details'][] = [
                                            'discount' => $dis['coupon_discount'],
                                            'qty' => $dis['coupon_applied'],
                                        ];
                                    }
                                }
                            }

                            $totalProductDiscount += $order['discount'];
                        }

                        $params['results']['costs']['order']['details'] = $orders;
                        $params['results']['costs']['order']['totalProductDiscount'] = $totalProductDiscount;

                        $orderDiscount = 0;
                        // process general coupons to affect order prices
                        $subTotal = $params['results']['costs']['order']['subTotal'];

                        foreach ($generalCoupons as $key => $val) {
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

                            $params['results']['costs']['order']['totalGeneralDiscount'] = $orderDiscount;

                            // Total Discount including Product coupon and General Coupon
                            // $totalDiscount = $params['results']['costs']['order']['total'] - $orderDiscount;
                            $params['results']['costs']['order']['total'] -= $orderDiscount;
                        }

                        $params['results']['costs']['order']['orderDiscount'] = $orderDiscount;
                        $params['results']['costs']['order']['generalCoupons'] = $generalCoupons;
                        $params['results']['costs']['order']['productCoupons'] = $productCoupons;
                    }
                }
            },
            -999
        );


        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_checkout_step1_prepayment_start',
            function ($e) {
                $container = new Container('meliscommerce');
                $container['checkout']['checkout_step1_prepayment'] = $e->getParams();
            }
        );

        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_checkout_step1_prepayment_end',
            function ($e) {
                $container = new Container('meliscommerce');
                unset($container['checkout']['checkout_step1_prepayment']);
            }
        );

        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_order_basket_save_end',
            function ($e) {
                $container = new Container('meliscommerce');

                if (!isset($container['checkout']['checkout_step1_prepayment']))
                    return;

                $clientId = $container['checkout']['checkout_step1_prepayment']['clientId'];

                $params = $e->getParams();

                if (empty($params['basket']) || empty($params['results']))
                    return;

                $sm = $e->getTarget()->getServiceManager();

                $orderId = $params['basket']['obas_order_id'];
                $orderBasketId = $params['results'];

                $melisComOrderCheckoutService = $sm->get('MelisComOrderCheckoutService');
                $clientOrderCost = $melisComOrderCheckoutService->computeAllCosts($clientId);
                $orderItems = $clientOrderCost['costs']['order']['details'];

                $melisComCouponService = $sm->get('MelisComCouponService');


                foreach ($orderItems as $item) {

                    if (
                        $params['basket']['obas_quantity'] == $item['quantity']
                        && $params['basket']['obas_variant_id'] == $item['variant_id']
                    ) {

                        $couponOrderData = [];

                        foreach ($item['price_details']['surcharge_module'] as $key => $dis) {

                            if (
                                !empty($dis['module']) & $dis['module'] == 'MelisCommerce'
                                && $dis['coupon_assign'] == 'PRODUCT'
                                && !isset($item['price_details']['surcharge_module'][$key]['used'])
                            ) {

                                $couponOrderData = [
                                    'cord_coupon_id' => $dis['coupon_id'],
                                    'cord_order_id' => $orderId,
                                    'cord_basket_id' => $orderBasketId,
                                    'cord_status' => 1,
                                    'cord_quantity_used' =>  $dis['coupon_applied'],
                                ];

                                $item['price_details']['surcharge_module'][$key]['used'] = true;
                            }
                        }

                        if (!empty($couponOrderData)) {
                            $couponOrderTable = $sm->get('MelisEcomCouponOrderTable');
                            if (empty($couponOrderTable->getEntryByField('cord_basket_id', $orderBasketId)->current()))
                                $melisComCouponService->saveCouponOrder($couponOrderData);
                        }
                    }
                }
            }
        );


        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_checkout_step1_prepayment_save_success',
            function ($e) {

                $param = $e->getParams();

                if (empty($param['orderId']))
                    return;

                $sm = $e->getTarget()->getServiceManager();
                $melisComCouponService = $sm->get('MelisComCouponService');
                $orderCheckoutService = $sm->get('MelisComOrderCheckoutService');
                $siteId = $orderCheckoutService->getSiteId();

                $container = new Container('meliscommerce');
                if (!empty($container['checkout'][$siteId]['coupons']['generalCoupons'])) {
                    $generalCoupons = $container['checkout'][$siteId]['coupons']['generalCoupons'];
                    foreach ($generalCoupons as $key => $val) {

                        $couponOrderData = [
                            'cord_coupon_id' => $key,
                            'cord_order_id' => $param['orderId'],
                            'cord_status' => 1,
                            'cord_quantity_used' => 1,
                        ];

                        $melisComCouponService->saveCouponOrder($couponOrderData);
                    }
                }
            }
        );
    }
}
