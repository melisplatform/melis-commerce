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

use MelisCore\Listener\MelisCoreGeneralListener;

class MelisCommerceSEOLanguageDeletedListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            'meliscommerce_language_delete_end',
        	function($e){
        	    
        		$sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
        		$params = $e->getParams();
        		
        		$melisEcomSeoTable = $sm->get('MelisEcomSeoTable');
        		if ($params['success']){
        		    $melisEcomSeoTable->deleteByField('eseo_lang_id', $params['langId']);
        		}
        	},
        	
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}