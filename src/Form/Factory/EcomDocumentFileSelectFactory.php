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
 * MelisCommerce Document File Select 
 */
class EcomDocumentFileSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
	{
		$serviceManager = $formElementManager->getServiceLocator();

		$melisDocTypeTable = $serviceManager->get('MelisEcomDocTypeTable');
		$ecomDocType = $melisDocTypeTable->fetchAll();

		$valueoptions = array();
		$max = $ecomDocType->count();
		for ($i = 0; $i < $max; $i++)
		{
			$data = $ecomDocType->current();
			if($data->dtype_code != 'IMG' && !$data->dtype_parent_type_id) {
			    $valueoptions[$data->dtype_id] = $data->dtype_name;
			}
			
			$ecomDocType->next();
		}
		
		return $valueoptions;
	}

}