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
class EcomOrderStatusSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
	{
		$serviceManager = $formElementManager->getServiceLocator();
		
		$orderStatusTransTable = $serviceManager->get('MelisComOrderService');	
		$melisTool = $serviceManager->get('MelisCoreTool');
        foreach($orderStatusTransTable->getOrderStatusList($melisTool->getCurrentLocaleID()) as $status){
            $orderStatus[$status->ostt_status_id ] = $status->ostt_status_name;            
        }       
		
		return $orderStatus;
	}

}