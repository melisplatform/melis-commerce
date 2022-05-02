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
use Laminas\EventManager\ListenerAggregateInterface;
use MelisCore\Listener\MelisGeneralListener;

class MelisCommerceGdprAutoDeleteTagsListListener extends MelisGeneralListener
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            MelisCoreGdprAutoDeleteService::TAGS_EVENT,
            function($e){
                /*
                * get list of tags
                */
                return $e->getTarget()->getServiceManager()->get('MelisCommerceGdprAutoDeleteService')->getListOfTags();
            },
            -1000
        );
    }
}