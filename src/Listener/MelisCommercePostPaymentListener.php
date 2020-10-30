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

class MelisCommercePostPaymentListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_checkout_step2_postpayment_end',
            function($e){
                
                $sm = $e->getTarget()->getServiceManager();
                $params = $e->getParams();
                
                $postedValues = $sm->get('request')->getPost()->toArray();
                
                $melisComPostPaymentService = $sm->get('MelisComPostPaymentService');
                $params['results'] = $melisComPostPaymentService->processPostPayment(get_object_vars($params)['results'], $postedValues);
            },
        100
        );
    }
}