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

use MelisCore\Listener\MelisCoreGeneralListener;

class MelisCommercePostPaymentListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            '*',
            array(
                'meliscommerce_service_checkout_step2_postpayment_end'
            ),
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceLocator();   	
        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
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