<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener\GDPR;

use MelisCommerce\Service\MelisCommerceGdprAutoDeleteService;
use MelisCore\Service\MelisCoreGdprAutoDeleteService;
use Laminas\EventManager\EventManagerInterface;
use MelisCore\Listener\MelisGeneralListener;

class MelisCommerceGdprAutoDeleteModuleListListener extends MelisGeneralListener
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'melis_core_gdpr_auto_delete_modules_list',
            function($e){
                return [
                    MelisCoreGdprAutoDeleteService::MODULE_LIST_KEY => [
                        MelisCommerceGdprAutoDeleteService::MODULE_NAME => [
                            'name' => 'Melis Commerce',
                            'service' => MelisCommerceGdprAutoDeleteService::class,
                        ]
                    ]
                ];
            },
            -1000
        );
    }
}