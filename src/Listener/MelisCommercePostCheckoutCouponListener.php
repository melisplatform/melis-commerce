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

class MelisCommercePostCheckoutCouponListener extends MelisGeneralListener implements ListenerAggregateInterface
{
	public function attach(EventManagerInterface $events, $priority = 1)
	{
		$this->attachEventListener(
			$events,
			'*',
			'meliscommerce_service_checkout_post_order_computation_end',
			function($e){
				
				$sm = $e->getTarget()->getServiceManager();
				$couponService = $sm->get('MelisComCouponService');
				$couponOrderTable = $sm->get('MelisEcomCouponOrderTable');
				$params = $e->getParams();
				
				if ($params['results']['success']) {

					// $productCoupons = $couponService->getCouponByType('product', $params['results']['orderId']);
					$generalCoupons = $couponService->getCouponByType('general', $params['results']['orderId']);
					
					if(!empty($generalCoupons)){
						$totalDiscount = 0 ;
						foreach($generalCoupons as $couponEntity){
							$coupon = $couponEntity->getCoupon();
							$subTotal = $params['results']['costs']['order']['total'];
							
							if (!empty($coupon->coup_percentage))
								$totalDiscount += (($coupon->coup_percentage / 100) * $subTotal);
							elseif (!empty($coupon->coup_discount_value))
								$totalDiscount += $coupon->coup_discount_value;
						}
						

						// Do nothing if totalDiscount is less than Zero
						if ($totalDiscount > 0)
							$params['results']['costs']['order']['total'] = $subTotal - $totalDiscount;
					}
					/**
					 * lets check if the total price has 3 or more decimals
					 * if it has, we need to round it to 2 decimals
					 */
					$price = $params['results']['costs']['order']['total'];
					if(strlen(substr(strrchr($price, "."), 1)) > 2)
						$params['results']['costs']['order']['total'] = $price;
				}
			},
			100
		);
	}
}