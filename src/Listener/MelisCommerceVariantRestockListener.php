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

class MelisCommerceVariantRestockListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'meliscommerce_service_variant_save_stocks_start',
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceManager();
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
        100
        );
    }
}