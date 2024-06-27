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
class EcomOrderStatusAllSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceManager $serviceManager)
	{
		$orderStatusTransTable = $serviceManager->get('MelisComOrderService');
		$melisTool = $serviceManager->get('MelisCoreTool');
        foreach($orderStatusTransTable->getOrderStatusList($melisTool->getCurrentLocaleID()) as $status){
            $orderStatus[$status->ostt_status_id ] = $status->ostt_status_name;            
        }       
		
		return $orderStatus;
	}
}