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

class MelisCommerceShipmentCostListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {

        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_checkout_order_computation_end',
            function($e){
                $sm = $e->getTarget()->getServiceManager();
                $params = $e->getParams();

                $melisComOrderCheckoutService = $sm->get('MelisComOrderCheckoutService');
                $params['results'] = $melisComOrderCheckoutService->computeShipmentCost($params['results']);
            }
        ); 

        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_checkout_shipment_computation_end',
            function($e){
                
                $sm = $e->getTarget()->getServiceManager();
                $params = $e->getParams();
                
                $melisComShipmentCostService = $sm->get('MelisComShipmentCostService');

                $params['results'] = $melisComShipmentCostService->computeShipmentCost($params['results']);
            },
        100
        );
    }
}