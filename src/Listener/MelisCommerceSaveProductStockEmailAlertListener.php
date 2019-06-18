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

class MelisCommerceSaveProductStockEmailAlertListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            'MelisCommerce',
            array(
                'meliscommerce_product_save_end'
            ),
        	function($e){
        	    
        		$sm = $e->getTarget()->getServiceLocator();   	
        		$params = $e->getParams();
        		
        		if($params['success']){
        		    $data = array();
        		    $postedValues = $sm->get('request')->getPost();
        		    
        		    $postedValues = get_object_vars($postedValues);
        		    
        		    $stockEmailAlertSvc = $sm->get('MelisComStockEmailAlertService');
        		    
        		    $recipients = !empty($postedValues['recipients'])? $postedValues['recipients'] :  array();
        		    
        		    $productId = $params['itemId'];
        		    
        		    // remove deleted recipients
        		    $stockAlerts = $stockEmailAlertSvc->getStockEmailRecipients($productId);
        		    
        		    if(!empty($recipients)){
        		        
        		        foreach($recipients as $recipient){
        		        
        		            if(!empty($recipient['sea_id'])){
        		        
        		                $ids[] = $recipient['sea_id'];
        		            }
        		        
        		            $data[] = array(
        		        
        		                'sea_id' => $recipient['sea_id'],
        		                'sea_stock_level_alert' => null,
        		                'sea_email' => $recipient['sea_email'],
        		                'sea_user_id' => $recipient['sea_user_id'],
        		                'sea_prd_id' => $productId,
        		            );
        		        }
        		        
        		        // delete removed recipients
        		        foreach($stockAlerts as $recipient){
        		        
        		            if(!in_array($recipient['sea_id'], $ids) && $recipient['sea_id'] != -1){
        		        
        		                $stockEmailAlertSvc->deleteStockEmailAlertById($recipient['sea_id']);
        		            }
        		        }
        		    }else{
        		        
                        // if recipients are emptied delete recipients
                        foreach($stockAlerts as $recipient){
                            
                            $stockEmailAlertSvc->deleteStockEmailAlertById($recipient['sea_id']);
                        }
                    }

                    // insert data to db
                    foreach($data as $entry){
                    
                        $id = $entry['sea_id'];
                        unset($entry['sea_id']);
                        $result = $stockEmailAlertSvc->SaveStockEmailAlert($entry, $id);
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