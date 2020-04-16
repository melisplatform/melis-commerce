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
use MelisCore\Listener\MelisCoreGeneralListener;

class MelisCommercePostPaymentListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            '*',
            'meliscommerce_service_checkout_step2_postpayment_end',
        	function($e){
        	    
        		$sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
        		$params = $e->getParams();
        		
        		$postedValues = $sm->get('request')->getPost();
        		
        		$postedValues = get_object_vars($postedValues);
        		
        		$melisComPostPaymentService = $sm->get('MelisComPostPaymentService');
        		$params['results'] = $melisComPostPaymentService->processPostPayment(get_object_vars($params)['results'], $postedValues);
        	},
        	
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}