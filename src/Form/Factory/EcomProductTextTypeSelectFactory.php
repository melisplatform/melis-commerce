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
use Zend\Http\Request as HttpRequest;
/**
 * MelisCommerce Countries select factory
 */
class EcomProductTextTypeSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
	{
		$serviceManager = $formElementManager->getServiceLocator();
		$request = new HttpRequest();
		$productId = isset($_GET['productId']) ? (int) $_GET['productId'] : null; //$request->get('cpath', null);
		$textTypeId = isset($_GET['textTypes']) ? (int) $_GET['textTypes'] : null;
		$productTextData = array();
		$melisEcomProdTxtTypeTable = $serviceManager->get('MelisEcomProductTextTypeTable');
		$melisEcomProdTxtTable     = $serviceManager->get('MelisEcomProductTextTable');
		$ecomProdTxtType = $melisEcomProdTxtTypeTable->fetchAll();
		
		$valueoptions = array();
		foreach($ecomProdTxtType as $prodTextType) {
		    
		    $valueoptions[$prodTextType->ptt_id] = $prodTextType->ptt_name;
		}
		
		return $valueoptions;
	}

}