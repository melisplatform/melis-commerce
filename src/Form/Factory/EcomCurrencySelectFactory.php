<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Form\Factory;

use Laminas\ServiceManager\ServiceManager;
use MelisCore\Form\Factory\MelisSelectFactory;

/**
 * MelisCommerce Currency select factory
 */
class EcomCurrencySelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceManager $serviceManager)
	{
		$melisEcomCurrencyTable = $serviceManager->get('MelisEcomCurrencyTable');
		$melisEcomCurrencyData = $melisEcomCurrencyTable->fetchAll();
		
		$valueoptions = [];
		
		if($melisEcomCurrencyData) { 
		    foreach($melisEcomCurrencyData as $currency) {
                if($currency->cur_status) {
                    $valueoptions[$currency->cur_id] = $currency->cur_name . ' (' . $currency->cur_symbol . ')';
                }
		    }
		}
		
		return $valueoptions;
	}
}