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
        		    $sm->get('MelisComPostPaymentService');
        		    
        		    $couponId = null;
        		    
        		    // Checking first if couponCode from Url $_GET[] data has a value
        		    if (!empty($getValues['couponCode']))
        		    {
        		        $couponCode = $getValues['couponCode'];
        		        $coupon = $couponSrv->validateCoupon($couponCode, $clientId);
        		        
        		        if($coupon['success'])
        		        {
        		            $couponData = $coupon['coupon'];
        		            $couponId = $couponData->coup_id;
        		        }
        		    }
        		    else
        		    {
        		        // If there is no data from $_GET[], this will try to use coupon data from Session
        		        $container = new Container('meliscommerce');
        		        if(!empty($container['checkout']['couponId']))
        		        {
        		            $couponId = $container['checkout']['couponId'];
        		        }
        		    }
        		    
        		    if (!is_null($couponId))
        		    {
        		        // Retrieving Coupon Data from database
        		        $couponData = $couponTable->getEntryById($couponId)->current();
        		        
        		        if (!empty($couponData))
        		        {
        		            $couponCode = $couponData->coup_code;
        		            
        		            $couponSrv = $sm->get('MelisComCouponService');
        		            
        		            $coupon = $couponSrv->validateCoupon($couponCode, $clientId);
        		            if($coupon['success'])
        		            {
        		                $subTotal = $params['results']['costs']['order']['total'];
        		                
        		                $couponData = $coupon['coupon'];
        		                $totalDiscount = 0;
        		                // Checking first if coupon percentage has value,
        		                // Percentage is first priority to be use in discounting the total amount of order cost
        		                // else this will try to use the fixed value for discount computations
        		                if ($subTotal > 0)
        		                {
        		                    if (!empty($couponData->coup_percentage))
        		                    {
        		                        $totalDiscount = ($couponData->coup_percentage / 100) * $subTotal;
        		                    }
        		                    elseif (!empty($couponData->coup_discount_value))
        		                    {
        		                        $totalDiscount = $couponData->coup_discount_value;
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
        		}
        	},
        	
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}