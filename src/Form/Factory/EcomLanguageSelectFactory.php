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
 * MelisCommerce Countries select factory
 */
class EcomLanguageSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
	{
		$serviceManager = $formElementManager->getServiceLocator();

		$langTable = $serviceManager->get('MelisEcomLangTable');
		$langData = $langTable->fetchAll();

		$valueoptions = array();
		$max = $langData->count();
		for ($i = 0; $i < $max; $i++)
		{
			$data = $langData->current();
			$valueoptions[$data->elang_id] = $data->elang_name;
			$langData->next();
		}
		return $valueoptions;
	}

}