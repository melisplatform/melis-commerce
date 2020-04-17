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

class MelisCommerceSaveOrderListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            'MelisCommerce',
            'meliscommerce_order_save_start',
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
        		    'order-valid-data',
        		    'MelisCommerce\Controller\MelisComOrder',
        		    ['action' => 'validateOrderForm']
                );
        		
        		if(!$success)
        		    return;        		
        		    
    		    list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
    		        $e,
    		        'meliscommerce',
    		        'order-valid-data',
    		        'MelisCommerce\Controller\MelisComOrder',
    		        ['action' => 'validateAddressForm']
		        );
    		    
    		    if(!$success)
    		        return;    		    
    		        
		        list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
		            $e,
		            'meliscommerce',
		            'order-valid-data',
		            'MelisCommerce\Controller\MelisComOrder',
		            ['action' => 'validateShippingForm']
                );
		        
		        if(!$success)
		            return;
		            
	            list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
	                $e,
	                'meliscommerce',
	                'order-valid-data',
	                'MelisCommerce\Controller\MelisComOrder',
	                ['action' => 'saveOrderData']
                );
	            
	            if(!$success)
	                return;
        	},
        100
        );
    }
}