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

class MelisCommerceCategoryLanguageDeletedListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            array(
                'meliscommerce_language_delete_end'
            ),
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceLocator();   	
        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
        		$params = $e->getParams();
        		
        		$melisEcomCategoryTransTable = $sm->get('MelisEcomCategoryTransTable');
        		if ($params['success']){
        		    $melisEcomCategoryTransTable->deleteByField('catt_lang_id', $params['langId']);
        		}
        	},
        	
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}