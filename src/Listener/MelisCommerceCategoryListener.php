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

class MelisCommerceCategoryListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            'meliscommerce_category_save_start',
        	function($e){
        	    
        		$sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-category-tmp',
        		    'MelisCommerce\Controller\MelisComCategory',
        		    ['action' => 'getCategory']
                );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-category-tmp',
        		    'MelisCommerce\Controller\MelisComCategory',
        		    ['action' => 'validateCategoryTranslations']
                );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-category-tmp',
        		    'MelisCommerce\Controller\MelisComCategory',
        		    ['action' => 'getCategoryCountries']
                );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-category-tmp',
        		    'MelisCommerce\Controller\MelisComCategory',
        		    ['action' => 'validateCategorySeo']
                );
        		
        	},
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}