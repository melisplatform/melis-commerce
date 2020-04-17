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

class MelisCommerceDocumentCountryDeletedListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            'MelisCommerce',
            'meliscommerce_country_delete_end',
        	function($e){
        	    
        		$sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
        		$params = $e->getParams();

        		$countryId = (int) $params['countryId'];
        		$docRelTable = $sm->get('MelisEcomDocRelationsTable');
        		$docRelTable->update(['rdoc_country_id' => '-1'], 'rdoc_country_id', $countryId);
        	},
        -1000
        );
    }
}