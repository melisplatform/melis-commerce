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
     * @param $data
     * @param null $id
     * @return mixed
     */
    public function saveClientsGroup($data, $id = null)
    {
        //if status is not given, we set it to 1
        if(!isset($data['cgroup_status']))
            $data['cgroup_status'] = 1;

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_save_clients_group_start', $arrayParameters);

        // Service implementation start
        $group = $this->getServiceManager()->get('MelisEcomClientGroupsTable');
        $results = $group->save($arrayParameters['data'], $arrayParameters['id']);
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_save_clients_group_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getClientsGroupById($id)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_clients_group_by_id_start', $arrayParameters);

        // Service implementation start
        $group = $this->getServiceManager()->get('MelisEcomClientGroupsTable');
        $results = $group->getEntryById($arrayParameters['id'])->current();
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_clients_group_by_id_end', $arrayParameters);

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