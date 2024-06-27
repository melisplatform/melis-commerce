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
 * MelisCommerce Document File Select 
 */
class EcomDocumentImageTypeSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceManager $serviceManager)
	{
		$docTypeSvc = $serviceManager->get('MelisComDocumentService');
		$ecomImageType = $docTypeSvc->getDocumentTypes(1);

		$valueoptions = [];
		foreach($ecomImageType as $type) {
		    $valueoptions[$type['dtype_id']] = $type['dtype_name'];
		}
		
		return $valueoptions;
	}

}