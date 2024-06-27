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

class MelisCommerceSaveAttributeListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            'MelisCommerce',
            'meliscommerce_attribute_save_start',
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
        		    'attribute-valid-data',
        		    'MelisCommerce\Controller\MelisComAttribute',
        		    ['action' => 'validateAttributeForm']
                );
        		
        		if(!$success)
        		    return;        		
        		    
    		    list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
    		        $e,
    		        'meliscommerce',
    		        'attribute-valid-data',
    		        'MelisCommerce\Controller\MelisComAttribute',
    		        ['action' => 'validateAttributeTransForm']
		        );
    		    
    		    if(!$success)
    		        return; 
    		    
		        list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
		            $e,
		            'meliscommerce',
		            'attribute-valid-data',
		            'MelisCommerce\Controller\MelisComAttribute',
		            ['action' => 'saveAttributeData']
                );
		         
		        if(!$success)
		            return;
        	},
        100
        );
    }
}