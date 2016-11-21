<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Form\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use MelisCore\Form\Factory\MelisSelectFactory;

/**
 * MelisCommerce Currency select factory
 */
class EcomCurrencySelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
	{
		$serviceManager = $formElementManager->getServiceLocator();

		$melisEcomCurrencyTable = $serviceManager->get('MelisEcomCurrencyTable');
		$melisEcomCurrencyData = $melisEcomCurrencyTable->fetchAll();
		
		$valueoptions = array();
		
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