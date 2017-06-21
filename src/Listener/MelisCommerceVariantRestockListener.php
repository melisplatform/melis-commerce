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

class MelisCommerceVariantRestockListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            '*',
            array(
                'meliscommerce_service_variant_save_stocks_start'
            ),
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceLocator();   	
        		$stockTable = $sm->get('MelisEcomVariantStockTable');
        		$params = $e->getParams();
                
        		if(!empty($params['stockId'])){
        		    
        		    $stocks = $stockTable->getEntryById($params['stockId']);
        		    
        		    foreach($stocks as $stock){
        		        
        		        if($stock->stock_id == $params['stockId'] && !empty($params['stocks']['stock_quantity'])){
        		            $params['stocks']['stock_qty_email_sent'] = ($params['stocks']['stock_quantity'] != $stock->stock_quantity)? 0 : 1;
        		        }
        		    }
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