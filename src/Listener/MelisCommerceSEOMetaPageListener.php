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
use Laminas\Mvc\MvcEvent;

/**
 * This listener will activate when a page is deleted
 * 
 */
class MelisCommerceSEOMetaPageListener implements ListenerAggregateInterface
{
    public $listeners = [];

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $callBackHandler = $events->attach(
        	MvcEvent::EVENT_FINISH, 
        	function(MvcEvent $e){
        		
        	   // Get route match to know if we are displaying in back or front
            	$routeMatch = $e->getRouteMatch();
            	
            	$uri = $_SERVER['REQUEST_URI'];
        		preg_match('/.*\.((?!php).)+(?:\?.*|)$/i', $uri, $matches, PREG_OFFSET_CAPTURE);
        		if (count($matches) > 1)
        		    return;
            	
            	if (!$routeMatch)
            		return;
            	 
            	$renderMode = $routeMatch->getParam('renderMode');
            	
            	$categoryId = $routeMatch->getParam('categoryId', '');
            	$productId = $routeMatch->getParam('productId', '');
            	$variantId = $routeMatch->getParam('variantId', '');
            	
            	if (($renderMode == 'melis' || $renderMode == 'front') &&
            	    (!empty($categoryId) || !empty($productId) || !empty($variantId))) {
            		$sm = $e->getApplication()->getServiceManager();
            	
            		// Get the response generated
            		$response = $e->getResponse();
            		$content = $response->getContent();
            	
            		if (!empty($categoryId)) {
            		    $typeItem = 'eseo_category_id';
            		    $itemId = $categoryId;
            		} else
            		    if (!empty($productId)) {
            		        $typeItem = 'eseo_product_id';
            		        $itemId = $productId;
            		    } else
            		        if (!empty($variantId)) {
            		            $typeItem = 'eseo_variant_id';
            		            $itemId = $variantId;
            		        }
            	
            		/**
            		 * Replace Head and SEO datas automatically
            		 */
            		$melisComHead = $sm->get('MelisComHead');
            		$newContent = $melisComHead->updateTitleAndDescription($typeItem, $itemId, $content);
            	
            		// Set the updated content
            		$response->setContent($newContent);
            	}
        	},
        100);
        
        $this->listeners[] = $callBackHandler;
    }
    
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}