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

class MelisCommerceProductStockCountryDeletedListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            'MelisCommerce',
            'meliscommerce_country_delete_end',
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceManager();
        		$params = $e->getParams();
        		$paramData = [];
        		if(isset($params['data'])) {
        		    $paramData = $params['data'];
        		}

        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'country-deleted-data',
        		    'MelisCommerce\Controller\MelisComVariant',
        		    ['action' => 'stockCountryDeleted']
                );
        	},
        100
        );
    }
}