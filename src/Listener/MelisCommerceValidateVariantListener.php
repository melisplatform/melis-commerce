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

class MelisCommerceValidateVariantListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            array(
                'meliscommerce_variant_save_start'
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
        		    'variant-tmp-data',
        		    'MelisCommerce\Controller\MelisComVariant',
        		    array('action' => 'validateVariantForm')
        		    );
        		
            		if(!$success)
    		            return;
        		            		
        		
    		    list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
    		        $e,
    		        'meliscommerce',
    		        'variant-tmp-data',
    		        'MelisCommerce\Controller\MelisComVariant',
    		        array('action' => 'validateStockForm')
		            );
    		    
                    if(!$success)
	                   return;  		    
		       
               list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
                   $e,
                   'meliscommerce',
                   'variant-tmp-data',
                   'MelisCommerce\Controller\MelisComPrice',
                   array('action' => 'validatePriceForm')
                   );
                
               if(!$success)
                   return;
               
		        list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
		            $e,
		            'meliscommerce',
		            'variant-tmp-data',
		            'MelisCommerce\Controller\MelisComVariant',
		            array('action' => 'validateVariantAttribute')
		            );
		        
		        if(!$success)
		            return;
		        
	            list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
	                $e,
	                'meliscommerce',
	                'variant-tmp-data',
	                'MelisCommerce\Controller\MelisComVariant',
	                array('action' => 'validateVariantSeo')
	                );
	            
	            if(!$success)
	                return;
	            
                list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
                    $e,
                    'meliscommerce',
                    'variant-tmp-data',
                    'MelisCommerce\Controller\MelisComVariant',
                    array('action' => 'saveVariantData')
                    );
                 
                if(!$success)
                    return;
    	        
        	},
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}