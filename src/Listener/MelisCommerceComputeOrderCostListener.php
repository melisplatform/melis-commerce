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

class MelisCommerceComputeOrderCostListener extends MelisGeneralListener implements ListenerAggregateInterface
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


                $results = $params['results'];

                $totalCost = $results['costs']['order']['total'];

                foreach ($results['costs'] As $key => $val) {
                    // exclude "total" and "order" index
                    if (is_array($val) && !in_array($key, ['order', 'total']))
                        if (isset($val['total'])) {

                            // Add total for each costs
                            $results['costs']['order']['total'] += $val['total'];
                        }
                }
                $e->setParam('results', $results);
            },
            // Last event listener to compute total order
            -9999
        ); 
    }
}