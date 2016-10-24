<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

use MelisCore\Listener\MelisCoreGeneralListener;

class MelisCommerceSaveClientListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            array(
                'meliscommerce_clients_save_start'
            ),
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceLocator();   	
        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-client-tmp',
        		    'MelisCommerce\Controller\MelisComClient',
        		    array('action' => 'validateClient')
        		    );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-client-tmp',
        		    'MelisCommerce\Controller\MelisComClient',
        		    array('action' => 'validateClientContacts')
    		    );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-client-tmp',
        		    'MelisCommerce\Controller\MelisComClient',
        		    array('action' => 'validateClientAddresses')
    		    );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-client-tmp',
        		    'MelisCommerce\Controller\MelisComClient',
        		    array('action' => 'validateClientCompany')
    		    );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-client-tmp',
        		    'MelisCommerce\Controller\MelisComClient',
        		    array('action' => 'deleteClientAddresses')
        		    );
        	},
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}