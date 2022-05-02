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

class MelisCommerceGdprAutoDeleteWarningListUsersListener extends MelisGeneralListener
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            MelisCoreGdprAutoDeleteService::WARNING_EVENT,
            function ($e) {
                // get the first list of warning users
                return $e->getTarget()->getServiceManager()->get('MelisCommerceGdprAutoDeleteService')->getWarningListOfUsers();
            },
            -1000
        );
    }
}