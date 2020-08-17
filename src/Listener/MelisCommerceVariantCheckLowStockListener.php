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
use MelisCore\Listener\MelisGeneralListener;

class MelisCommerceVariantCheckLowStockListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_checkout_step2_postpayment_proccess_end',
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceManager();
        		$orderSvc = $sm->get('MelisComStockEmailAlertService');
        		$params = $e->getParams();

        		if ($params['results']['success']){
        		    $orderSvc->checkStockLevelByOrderId($params['results']['orderId']);
        		}
        	},
        100
        );
    }
}