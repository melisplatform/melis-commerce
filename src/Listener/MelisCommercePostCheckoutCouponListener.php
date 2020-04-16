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
use MelisCore\Listener\MelisCoreGeneralListener;

class MelisCommercePostCheckoutCouponListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            '*',
            'meliscommerce_service_checkout_post_order_computation_end',
        	function($e){
        	    
        		$sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
        		$couponService = $sm->get('MelisComCouponService');
        		$couponOrderTable = $sm->get('MelisEcomCouponOrderTable');
        		$params = $e->getParams();
        		
        		if ($params['results']['success']) {

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
        		            $totalWithoutCoupon += $val['total_price'];
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
                    /**
                     * lets check if the total price has 3 or more decimals
                     * if it has, we need to round it to 2 decimals
                     */
                    $price = $params['results']['costs']['order']['total'];
                    if(strlen(substr(strrchr($price, "."), 1)) > 2){
                        $price = round($price, 2);
                        $params['results']['costs']['order']['total'] = $price;
                    }
        		}
        	},
        	
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}