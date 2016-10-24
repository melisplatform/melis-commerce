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

class MelisCommerceCategoryListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            array(
                'meliscommerce_category_save_start'
            ),
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceLocator();   	
        		$melisCoreDispatchService = $sm->get('MelisCoreDispatch');
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-category-tmp',
        		    'MelisCommerce\Controller\MelisComCategory',
        		    array('action' => 'getCategory')
        		    );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-category-tmp',
        		    'MelisCommerce\Controller\MelisComCategory',
        		    array('action' => 'validateCategoryTranslations')
        		    );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-category-tmp',
        		    'MelisCommerce\Controller\MelisComCategory',
        		    array('action' => 'getCategoryCountries')
        		    );
        		
        		list($success, $errors, $datas) = $melisCoreDispatchService->dispatchPluginAction(
        		    $e,
        		    'meliscommerce',
        		    'action-category-tmp',
        		    'MelisCommerce\Controller\MelisComCategory',
        		    array('action' => 'validateCategorySeo')
        		    );
        		
        	},
        100);
        
        $this->listeners[] = $callBackHandler;
    }
}