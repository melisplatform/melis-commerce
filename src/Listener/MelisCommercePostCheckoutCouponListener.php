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

class MelisCommercePostCheckoutCouponListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            '*',
            array(
                'meliscommerce_service_checkout_post_order_computation_end'
            ),
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceLocator();
        		$couponService = $sm->get('MelisComCouponService');
        		$couponOrderTable = $sm->get('MelisEcomCouponOrderTable');
        		$params = $e->getParams();
        		
        		if ($params['results']['success'])
        		{
        		    $productCoupons = $couponService->getCouponByType('product', $params['results']['orderId']);
        		    $generalCoupons = $couponService->getCouponByType('general', $params['results']['orderId']);
                    
        		    $orderBaskets = $params['results']['costs']['order']['details'];
        		    if(!empty($productCoupons)){
        		        
        		        // apply product coupon discounts
        		        foreach($productCoupons as $couponEntity){
        		            $coupon = $couponEntity->getCoupon();
        		            $usableQty = $coupon->coup_max_use_number - $coupon->coup_current_use_number;
        		            
        		            if($usableQty > 0){
        		                $assocBaskets = $couponOrderTable->getAssociatedBasketItem($coupon->coup_id, $params['results']['orderId']);
        		                
        		                foreach($assocBaskets as $basket){
        		                    
        		                    if($usableQty > 0){
        		                        $tmp = $orderBaskets[$basket->obas_variant_id];
        		                        
        		                        $totalDiscount = 0;
        		                        
        		                        $qty  = (($usableQty - $tmp['quantity']) >= 0)? $tmp['quantity'] : $usableQty;
        		                        
        		                        if($qty > 0){
        		                            
        		                            if (!empty($coupon->coup_percentage)){
        		                                $totalDiscount = ($coupon->coup_percentage / 100) * ($basket->obas_price_net * $qty);
        		                            }
        		                            
        		                            elseif (!empty($coupon->coup_discount_value)){
        		                                $totalDiscount = $coupon->coup_discount_value * $qty;
        		                            }
        		                            
        		                            $orderBaskets[$basket->obas_variant_id]['discount_price'] = $tmp['total_price'] - $totalDiscount;
        		                            $orderBaskets[$basket->obas_variant_id]['discount'] = !empty($tmp['discount'])? $tmp['discount'] + $totalDiscount : $totalDiscount;
        		                           
        		                            $usableQty -= $tmp['quantity'];
        		                        }
        		                    }
        		                }
        		            }
        		        }
        		        
        		        $totalWithoutCoupon = 0;
        		        $total = 0;
        		        $discount = 0;
        		        
        		        foreach($orderBaskets as $key => $val){
        		        
        		            $discount += $val['discount'];
        		            $price = !empty($val['discount_price'])? $val['discount_price'] : $val['total_price'];
        		            $totalWithoutCoupon += $val['total_price'];
        		            $params['results']['costs']['order']['totalWithoutCoupon'] = $totalWithoutCoupon;
        		            $params['results']['costs']['order']['totalWithProductCoupon'] = $totalWithoutCoupon - $discount;
        		            $params['results']['costs']['order']['total'] = $totalWithoutCoupon - $discount;
        		        
        		        }
        		        
        		        $params['results']['costs']['order']['details'] = $orderBaskets;
        		    }
        		    
        		    if(!empty($generalCoupons)){
        		        $totalDiscount = 0 ;
        		        foreach($generalCoupons as $couponEntity){
        		            $coupon = $couponEntity->getCoupon();
        		            $subTotal = $params['results']['costs']['order']['total'];
//         		            $totalDiscount = $subTotal - $params['results']['costs']['order']['total'];
        		            
        		            if (!empty($coupon->coup_percentage))
        		            {
        		                $totalDiscount += (($coupon->coup_percentage / 100) * $subTotal);
        		            }
        		            elseif (!empty($coupon->coup_discount_value))
        		            {
        		                $totalDiscount += $coupon->coup_discount_value;
        		            }
        		        }
        		        

        		        // Do nothing if totalDiscount is less than Zero
        		        if ($totalDiscount > 0)
        		        {
        		            $params['results']['costs']['order']['total'] = $subTotal - $totalDiscount;
        		        }
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