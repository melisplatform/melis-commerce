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

class MelisCommerceDataTableTranslationsListener extends MelisGeneralListener
{
	public function attach(EventManagerInterface $events, $priority = 1)
	{
		$this->attachEventListener(
			$events,
			'MelisCore',
			'meliscore_data_table_languages',
			function($e){
				
				$sm = $e->getTarget()->getServiceManager();
				
				$params = $e->getParams();

				$comConfig = $params['default'];
				$comConfig['sEmptyTable'] = $sm->get('translator')->translate('tr_meliscommerce_select_country_empty');
				$comConfig['sZeroRecords'] = $sm->get('translator')->translate('tr_meliscommerce_search_empty_result');

				$params['commerce_checkout'] = $comConfig;

				return $params;
			}
		);
	}
}