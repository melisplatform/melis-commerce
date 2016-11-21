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

class MelisCommerceProductTextLanguageDeleteListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
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
        		$params = $e->getParams();

        		$langId = (int) $params['langId'];
        		$prodTextTable = $sm->get('MelisEcomProductTextTable');
                $prodTextTable->deleteByField('ptxt_lang_id', $langId);
        		
        	},
        	
        -1000);
        
        $this->listeners[] = $callBackHandler;
    }
}