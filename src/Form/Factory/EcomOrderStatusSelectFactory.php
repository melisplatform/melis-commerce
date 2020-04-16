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
class EcomOrderStatusSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceManager $serviceManager)
	{
		$orderStatusTransTable = $serviceManager->get('MelisComOrderService');
		$melisTool = $serviceManager->get('MelisCoreTool');
        foreach($orderStatusTransTable->getOrderStatusList($melisTool->getCurrentLocaleID(), true) as $status){
            if($status->ostt_status_id != -1)
            $orderStatus[$status->ostt_status_id ] = $status->ostt_status_name;            
        }       
		
		return $orderStatus;
	}

}