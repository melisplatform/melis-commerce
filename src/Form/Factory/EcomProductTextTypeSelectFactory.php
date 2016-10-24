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
class EcomProductTextTypeSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
	{
		$serviceManager = $formElementManager->getServiceLocator();

		$melisEcomProdTxtTypeTable = $serviceManager->get('MelisEcomProductTextTypeTable');
		$ecomProdTxtType = $melisEcomProdTxtTypeTable->fetchAll();

		$valueoptions = array();
		$max = $ecomProdTxtType->count();
		for ($i = 0; $i < $max; $i++)
		{
			$data = $ecomProdTxtType->current();
			$valueoptions[$data->ptt_id] = $data->ptt_name;
			$ecomProdTxtType->next();
		}
		return $valueoptions;
	}

}