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
 * MelisCommerce Countries select factory
 */
class EcomCountriesSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceManager $serviceManager)
	{
		$melisEcomCountryTable = $serviceManager->get('MelisEcomCountryTable');
		$ecomCountries = $melisEcomCountryTable->getCountries();

		$translator = $serviceManager->get('translator');
		$valueoptions = [];
		$max = $ecomCountries->count();
		$valueoptions[-1] = $translator->translate('tr_meliscommerce_documents_action_all_countries');
		for ($i = 0; $i < $max; $i++)
		{
			$data = $ecomCountries->current();
            if($data->ctry_status) {
                $valueoptions[$data->ctry_id] = $data->ctry_name;
            }
			$ecomCountries->next();
		}
		return $valueoptions;
	}

}