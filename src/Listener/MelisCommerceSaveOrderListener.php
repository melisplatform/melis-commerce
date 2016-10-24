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

class MelisCommerceSaveOrderListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            array(
                'meliscommerce_order_save_start'
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
        		    'order-valid-data',
        		    'MelisCommerce\Controller\MelisComOrder',
        		    array('action' => 'validateOrderForm')
        		    );
        		
        		if(!$success)
        		    return;        		
        		    
    		    list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
    		        $e,
    		        'meliscommerce',
    		        'order-valid-data',
    		        'MelisCommerce\Controller\MelisComOrder',
    		        array('action' => 'validateAddressForm')
		        );
    		    
    		    if(!$success)
    		        return;    		    
    		        
		        list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
		            $e,
		            'meliscommerce',
		            'order-valid-data',
		            'MelisCommerce\Controller\MelisComOrder',
		            array('action' => 'validateShippingForm')
		            );
		        
		        if(!$success)
		            return;
		            
	            list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
	                $e,
	                'meliscommerce',
	                'order-valid-data',
	                'MelisCommerce\Controller\MelisComOrder',
	                array('action' => 'saveOrderData')
	                );
	            
	            if(!$success)
	                return;

        	},
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}