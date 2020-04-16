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

class MelisCommerceSaveAttributeListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            'meliscommerce_attribute_save_start',
        	function($e){
        	    
        		$sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
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
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}