<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener;

use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;

use MelisCore\Listener\MelisGeneralListener;

class MelisCommercePrdVarDuplicationListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $identifier = 'MelisCommerce';

        $eventNames = [
            'meliscommerce_duplicate_variant_start',
            'meliscommerce_duplicate_product_start'
        ];

        $this->attachEventListener($events, $identifier, $eventNames, function(EventInterface $e) {
            $sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
            $melisCoreDispatchService = $sm->get('MelisCoreDispatch');

            list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
                $e,
                'meliscommerce',
                'action-duplicate-tmp',
                'MelisCommerce\Controller\MelisComPrdVarDuplication',
                ['action' => 'validateVariantData']
            );
        });
    }
}