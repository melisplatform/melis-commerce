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

class MelisCommerceProductTextLanguageDeleteListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            'MelisCommerce',
            'meliscommerce_language_delete_end',
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceManager();
        		$params = $e->getParams();

        		$langId = (int) $params['langId'];
        		$prodTextTable = $sm->get('MelisEcomProductTextTable');
                $prodTextTable->deleteByField('ptxt_lang_id', $langId);
        	},
        -1000
        );
    }
}