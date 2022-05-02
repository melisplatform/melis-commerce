<?php

namespace MelisCommerce\Listener\GDPR;

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2020 Melis Technology (http://www.melistechnology.com)
 *
 */

use MelisCore\Service\MelisCoreGdprAutoDeleteService;
use Laminas\EventManager\EventManagerInterface;
use MelisCore\Listener\MelisGeneralListener;

class MelisCommerceGdprAutoDeleteActionDeleteUserListener extends MelisGeneralListener
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            MelisCoreGdprAutoDeleteService::DELETE_ACTION_EVENT,
            function ($e) {
                return $e->getTarget()->getServiceManager()->get('MelisCommerceGdprAutoDeleteService')->removeOldUnvalidatedUsers($e->getParams());
            },
            -1000
        );
    }
}