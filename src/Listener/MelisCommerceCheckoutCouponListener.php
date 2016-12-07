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
use MelisCore\Listener\MelisCoreGeneralListener;


class MelisCommerceCheckoutCouponListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
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
        		$couponSrv = $sm->get('MelisComCouponService');
        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
        		$params = $e->getParams();
        		
        		// Getting $_GET[] parameters for CouponCode
        		$getValues = $sm->get('request')->getQuery();
        		$getValues = get_object_vars($getValues);
        		
        		if ($params['results']['success'])
        		{
        		    $clientId = $params['results']['clientId'];
        		    
        		    $coupon = array();
        		    
        		    // Checking first if couponId from Url $_GET[] data has a value
        		    if (!empty($getValues['couponCode']))
        		    {
        		        $coupon = $couponTable->getEntryByField('coup_code', $getValues['couponCode'])->current();
        		    }
        		    else
        		    {
        		        // If there is no data from $_GET[], this will try to use coupon data from Session
        		        $container = new Container('meliscommerce');
        		        if(!empty($container['checkout']['couponId']))
        		        {
        		            $couponId = $container['checkout']['couponId'];
        		            $couponEntity = $couponSrv->getCouponById($couponId);
        		            $coupon = $couponEntity->getCoupon();
        		        }
        		    }
        		    
    		        if (!empty($coupon))
    		        {
    		            $couponCode = $coupon->coup_code;
    		            $validitedCoupon = $couponSrv->validateCoupon($couponCode, $clientId);
    		            
    		            if($validitedCoupon['success'])
    		            {
    		                $subTotal = $params['results']['costs']['order']['total'];
    		                $totalDiscount = 0;
    		                
    		                // Checking first if coupon percentage has value,
    		                // Percentage is first priority to be use in discounting the total amount of order cost
    		                // else this will try to use the fixed value for discount computations
    		                if ($subTotal > 0)
    		                {
    		                    if (!empty($coupon->coup_percentage))
    		                    {
    		                        $totalDiscount = ($coupon->coup_percentage / 100) * $subTotal;
    		                    }
    		                    elseif (!empty($coupon->coup_discount_value))
    		                    {
    		                        $totalDiscount = $coupon->coup_discount_value;
    		                    }
    		                    
    		                    $params['results']['costs']['order']['totalWithoutCoupon'] = $subTotal;
    		                    
    		                    // Do nothing if totalDiscount is less than Zero
    		                    if ($totalDiscount > 0)
    		                    {
    		                        $params['results']['costs']['order']['total'] = $subTotal - $totalDiscount;
    		                    }
    		                }
    		            }
    		        }
        		}
        	},
        	
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}