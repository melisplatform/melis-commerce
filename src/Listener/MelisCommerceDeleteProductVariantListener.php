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

class MelisCommerceDeleteProductVariantListener extends MelisGeneralListener implements ListenerAggregateInterface
{
	public function attach(EventManagerInterface $events, $priority = 1)
	{
		

		$this->attachEventListener(
			$events,
			'*',
			'meliscommerce_service_variant_delete_end',
			function($e){
				
				$sm = $e->getTarget()->getServiceManager();
				$params = $e->getParams();
				
				if(empty($params['variantId']))
					return;

				$variantId = $params['variantId'];
				
				$basketSrv = $sm->get('MelisComBasketService');
				$basketSrv->deleteVariantFromBasketByVariantId($variantId);
			},
			100
		);

		// checking if product status deactivated
		$this->attachEventListener(
			$events,
			'*',
			'meliscommerce_service_product_save_end',
			function($e){
				
				$sm = $e->getTarget()->getServiceManager();
				$params = $e->getParams();
				if(empty($params['results']))
					return;

				$prodId = $params['results'];

				$product = $sm->get('MelisEcomProductTable')->getEntryById($prodId)->current();

				if ($product) {
					if (!$product->prd_status) {

						foreach ($sm->get('MelisEcomVariantTable')->getEntryByField('var_prd_id', $product->prd_id) As $var)
							$sm->get('MelisComBasketService')->deleteVariantFromBasketByVariantId($var->var_id);
					}
				}
			},
			100
		);

		// checking if product status deactivated
		$this->attachEventListener(
			$events,
			'*',
			'meliscommerce_service_variant_save_end',
			function($e){
				
				$sm = $e->getTarget()->getServiceManager();
				$params = $e->getParams();
				if(empty($params['results']))
					return;

				$variantId = $params['results'];

				$variant = $sm->get('MelisEcomVariantTable')->getEntryById($variantId)->current();

				if ($variant) {
					if (!$variant->var_status) 
							$sm->get('MelisComBasketService')->deleteVariantFromBasketByVariantId($variant->var_id);
				}
			},
			100
		);
	}
}