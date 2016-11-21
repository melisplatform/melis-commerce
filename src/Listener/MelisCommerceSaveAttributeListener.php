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

class MelisCommerceSaveAttributeListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            array(
                'meliscommerce_attribute_save_start'
            ),
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceLocator();   	
        		$params = $e->getParams();
        		$paramData = array();
        		if(isset($params['data'])) {
        		    $paramData = $params['data'];
        		}

        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'attribute-valid-data',
        		    'MelisCommerce\Controller\MelisComAttribute',
        		    array('action' => 'validateAttributeForm')
        		    );
        		
        		if(!$success)
        		    return;        		
        		    
    		    list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
    		        $e,
    		        'meliscommerce',
    		        'attribute-valid-data',
    		        'MelisCommerce\Controller\MelisComAttribute',
    		        array('action' => 'validateAttributeTransForm')
		        );
    		    
    		    if(!$success)
    		        return; 
    		    
		        list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
		            $e,
		            'meliscommerce',
		            'attribute-valid-data',
		            'MelisCommerce\Controller\MelisComAttribute',
		            array('action' => 'saveAttributeData')
		            );
		         
		        if(!$success)
		            return;
		        
        	},
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}