<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;


use MelisCommerce\Entity\MelisDocument; 

class MelisComClientGroupsService extends MelisComGeneralService
{
    /**
     * @param null $start
     * @param null $limit
     * @param null $order
     * @param null $orderKey
     * @param null $searchValue
     * @param array $searchKeys
     * @param null $status
     * @return mixed
     */
    public function getClientsGroupList($start = null, $limit = null, $order = null, $orderKey = null, $searchValue = null, $searchKeys = [], $status = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_clients_group_list_start', $arrayParameters);
        
        // Service implementation start
        $group = $this->getServiceManager()->get('MelisEcomClientGroupsTable');
        $results = $group->getClientsGroupList(
            $arrayParameters['start'],
            $arrayParameters['limit'],
            $arrayParameters['order'],
            $arrayParameters['orderKey'],
            $arrayParameters['searchValue'],
            $arrayParameters['searchKeys'],
            $arrayParameters['status']
        )->toArray();

        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_clients_group_list_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }

    /**
     * @return mixed
     */
    public function getClientGroupsTotalData()
    {
        $group = $this->getServiceManager()->get('MelisEcomClientGroupsTable');
        return $group->getTotalData();
    }
}