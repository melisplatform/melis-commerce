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

class MelisCommerceCategoryCountryLinkCountryDeletedListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            array(
                'meliscommerce_country_delete_end'
            ),
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceLocator();   	
        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
        		$params = $e->getParams();
        		
        		$melisEcomCountryCategoryTable = $sm->get('MelisEcomCountryCategoryTable');
        		if ($params['success']){
        		    $melisEcomCountryCategoryTable->deleteByField('ccat_country_id', $params['countryId']);
        		}
        	},
        	
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}