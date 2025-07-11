<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\PredicateSet;

class MelisEcomClientGroupsTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_client_groups';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'cgroup_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * @param null $status
     * @param null $start
     * @param null $limit
     * @param null $order
     * @param null $orderKey
     * @param null $searchValue
     * @param array $searchKeys
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getClientsGroupList($start = null, $limit = null, $order = 'ASC', $orderKey = null, $searchValue = null, $searchKeys = [], $status = null)
    {
        $select = $this->getTableGateway()->getSql()->select();

        if (!empty($searchValue)) {
            $search = [];
            foreach ($searchKeys as $col)
                $search[$col] = new Like($col, '%' . $searchValue . '%');

            $filters = [new PredicateSet($search, PredicateSet::COMBINED_BY_OR)];
            $select->where($filters);
        }

        if (!empty($status)) {
            $select->where->equalTo('cgroup_status', (int)$status);
        }

        if (!empty($start)) {
            $select->offset($start);
        }

        if (!empty($limit)) {
            $select->limit($limit);
        }

        if (!empty($orderKey))
            $select->order($orderKey . ' ' . $order);

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }

    public function getActiveClientGroups()
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->where->equalTo('cgroup_status', 1);

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }
}
