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

class MelisCommerceDocumentCountryDeletedListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
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
        		$params = $e->getParams();
        		
        		
        		$countryId = (int) $params['countryId'];
        		$docRelTable = $sm->get('MelisEcomDocRelationsTable');
        		$docRelTable->update(array('rdoc_country_id' => '-1'), 'rdoc_country_id', $countryId);

        		
        	},
        	
        -1000);
        
        $this->listeners[] = $callBackHandler;
    }
}