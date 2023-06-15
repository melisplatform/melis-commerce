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

class MelisCommerceSaveClientListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            'MelisCommerce',
            'meliscommerce_clients_save_start',
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceManager();
        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-client-tmp',
        		    'MelisCommerce\Controller\MelisComClient',
        		    ['action' => 'validateClient']
                );
                /**
                 * No need to validated contact as it was already in separate tool
                 */
//        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
//        		    $e,
//        		    'meliscommerce',
//        		    'action-client-tmp',
//        		    'MelisCommerce\Controller\MelisComClient',
//        		    ['action' => 'validateClientContacts']
//    		    );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-client-tmp',
        		    'MelisCommerce\Controller\MelisComClient',
        		    ['action' => 'validateClientAddresses']
    		    );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-client-tmp',
        		    'MelisCommerce\Controller\MelisComClient',
        		    ['action' => 'validateClientCompany']
    		    );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-client-tmp',
        		    'MelisCommerce\Controller\MelisComClient',
        		    ['action' => 'deleteClientAddresses']
                );
        	},
        100
        );
    }
}