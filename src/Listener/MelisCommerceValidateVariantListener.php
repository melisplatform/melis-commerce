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

class MelisCommerceValidateVariantListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            'MelisCommerce',
            'meliscommerce_variant_save_start',
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
        		    'variant-tmp-data',
        		    'MelisCommerce\Controller\MelisComVariant',
        		    ['action' => 'validateVariantForm']
                );
        		
                if(!$success)
                    return;
        		            		
        		
    		    list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
    		        $e,
    		        'meliscommerce',
    		        'variant-tmp-data',
    		        'MelisCommerce\Controller\MelisComVariant',
    		        ['action' => 'validateStockForm']
                );
    		    
                if(!$success)
                   return;
		       
                list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
                    $e,
                    'meliscommerce',
                    'variant-tmp-data',
                    'MelisCommerce\Controller\MelisComPrice',
                    ['action' => 'validatePriceForm']
                );
                
                if(!$success)
                    return;

                list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
                    $e,
                    'meliscommerce',
                    'variant-tmp-data',
                    'MelisCommerce\Controller\MelisComVariant',
                    ['action' => 'validateVariantAttribute']
                );
		        
		        if(!$success)
		            return;
		        
	            list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
	                $e,
	                'meliscommerce',
	                'variant-tmp-data',
	                'MelisCommerce\Controller\MelisComVariant',
	                ['action' => 'validateVariantSeo']
                );
	            
	            if(!$success)
	                return;
	            
                list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
                    $e,
                    'meliscommerce',
                    'variant-tmp-data',
                    'MelisCommerce\Controller\MelisComVariant',
                    ['action' => 'saveVariantData']
                );
                 
                if(!$success)
                    return;
        	},
        100
        );
    }
}