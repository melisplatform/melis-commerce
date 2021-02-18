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
 * MelisCommerce Clients group select factory
 */
class EcomClientsGroupSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceManager $serviceManager)
	{
		$groupService = $serviceManager->get('MelisComClientGroupsService');
		$groups = $groupService->getClientsGroupList(null,null,'ASC','cgroup_name',null,null, $status = 1);

		$translator = $serviceManager->get('translator');
		$valueoptions = [];

		foreach($groups as $key => $val){
            $valueoptions[$val['cgroup_id']] = html_entity_decode($val['cgroup_name']);
        }

		return $valueoptions;
	}

}