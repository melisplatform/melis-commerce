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

class MelisCommerceShipmentCostListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            '*',
            'meliscommerce_service_checkout_shipment_computation_end',
        	function($e){
        	    
        		$sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
        		$params = $e->getParams();
        		
        		$melisComShipmentCostService = $sm->get('MelisComShipmentCostService');
        		$params['results'] = $melisComShipmentCostService->computeShipmentCost(get_object_vars($params)['results']);
        	},
        	
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}