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
 * MelisCommerce Language select factory
 */
class EcomLanguageSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceManager $serviceManager)
	{
		$langTable = $serviceManager->get('MelisEcomLangTable');
		$langData = $langTable->langOrderByName();

		$valueoptions = [];
		$max = $langData->count();
		for ($i = 0; $i < $max; $i++)
		{
			$data = $langData->current();
            if($data->elang_status) {
                $valueoptions[$data->elang_id] = $data->elang_name;
            }
			$langData->next();
		}
		return $valueoptions;
	}
}