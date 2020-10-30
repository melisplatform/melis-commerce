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

class MelisCommerceSaveProductStockEmailAlertListener extends MelisGeneralListener implements ListenerAggregateInterface
{
	public function attach(EventManagerInterface $events, $priority = 1)
	{
		$this->attachEventListener(
			$events,
			'MelisCommerce',
			'meliscommerce_product_save_end',
			function($e){
				
				$sm = $e->getTarget()->getServiceManager();
				$params = $e->getParams();
				
				if($params['success']){
					$data = [];
					$postedValues = $sm->get('request')->getPost()->toArray();
					
					$stockEmailAlertSvc = $sm->get('MelisComStockEmailAlertService');
					
					$recipients = !empty($postedValues['recipients'])? $postedValues['recipients'] : [];
					
					$productId = $params['itemId'];
					
					// remove deleted recipients
					$stockAlerts = $stockEmailAlertSvc->getStockEmailRecipients($productId);
					
					if(!empty($recipients)){
						
						foreach($recipients as $recipient){
						
							if(!empty($recipient['sea_id'])){
								$ids[] = $recipient['sea_id'];
							}
						
							$data[] = [
								'sea_id' => $recipient['sea_id'],
								'sea_stock_level_alert' => null,
								'sea_email' => $recipient['sea_email'],
								'sea_user_id' => $recipient['sea_user_id'],
								'sea_prd_id' => $productId,
							];
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
		100
		);
	}
}