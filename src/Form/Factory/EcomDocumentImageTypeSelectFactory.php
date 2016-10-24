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
class EcomDocumentImageTypeSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
	{
		$serviceManager = $formElementManager->getServiceLocator();

		$docTypeSvc = $serviceManager->get('MelisComDocumentService');
		$ecomImageType = $docTypeSvc->getDocumentTypes(1);

		$valueoptions = array();
		foreach($ecomImageType as $type) {
		    $valueoptions[$type['dtype_id']] = $type['dtype_name'];
		}
		
		return $valueoptions;
	}

}