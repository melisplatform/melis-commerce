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

class MelisCommerceProductPriceLogsTranslationListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_get_item_price_end',
            function($e){
                $sm = $e->getTarget()->getServiceManager();
                $params = $e->getParams();

                if (isset($params['data']['skipLogsTranslation']))
                    return;

                if (empty($params['results']['price']))
                    return;

                $price = $params['results'];

                // Translating price logs using PriceService
                $price['logs'] = $sm->get('MelisComPriceService')->translateLogs($price['logs']);

                $e->setParam('results', $price);
            },
            -9999
        );     
    }
}