<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;
use DateTime;
use Laminas\Http\Response;

/**
 *
 * This service handles the client product return system of MelisCommerce.
 *
 */
class MelisComOrderProductReturnService extends MelisComGeneralService
{
    /**
     * @param null $orderId
     * @param null $start
     * @param null $limit
     * @param null $order
     * @param null $orderKey
     * @param null $searchValue
     * @param array $searchKeys
     * @return mixed
     */
    public function getOrderProductReturnList($orderId = null, $start = null, $limit = null, $order = null, $orderKey = null, $searchValue = null, $searchKeys = [])
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_clients_group_list_start', $arrayParameters);

        // Service implementation start
        $productReturn = $this->getServiceManager()->get('MelisEcomOrderProductReturnTable');

        $results = $productReturn->getOrderProductReturnList(
            $arrayParameters['orderId'],
            $arrayParameters['start'],
            $arrayParameters['limit'],
            $arrayParameters['order'],
            $arrayParameters['orderKey'],
            $arrayParameters['searchValue'],
            $arrayParameters['searchKeys']
        )->toArray();

        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_clients_group_list_end', $arrayParameters);

        return $arrayParameters['results'];
    }
}