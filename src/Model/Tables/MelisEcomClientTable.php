<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\Operator;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\Sql\Where;

class MelisEcomClientTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_client';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'cli_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * @param $accountId
     * @param string $searchValue
     * @param array $searchKeys
     * @param null $start
     * @param null $limit
     * @param string $orderColumn
     * @param string $order
     * @param bool $count
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getAccountAssocContactLists($accountId, $searchValue = '', $searchKeys = [], $start = null, $limit = null, $orderColumn = 'cli_id', $order = 'DESC', $count = false)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $slct = ['*', new Expression('person.cper_id As DT_RowId')];
        if ($count) {
            $slct = [new Expression('COUNT(' . $this->getTableGateway()->getTable() . '.' . $this->idField . ') As totalRecords')];
        }
        $select->columns($slct);

        $select->join('melis_ecom_client_account_rel', 'melis_ecom_client_account_rel.car_client_id = melis_ecom_client.cli_id', array('*'), $select::JOIN_LEFT);
        $select->join(['person' => 'melis_ecom_client_person'], 'person.cper_id = melis_ecom_client_account_rel.car_client_person_id', array('cper_id', 'cper_firstname', 'cper_name', 'cper_status', 'cper_email', 'cper_job_title'), $select::JOIN_LEFT);

        if (!empty($searchValue)) {
            $search = [];
            foreach ($searchKeys as $col)
                $search[$col] = new Like($col, '%' . $searchValue . '%');

            $filters = [new PredicateSet($search, PredicateSet::COMBINED_BY_OR)];
            $select->where($filters);
        }

        $select->where->equalTo('melis_ecom_client_account_rel.car_client_id', $accountId);

        if (!empty($start)) {
            $select->offset($start);
        }

        if (!empty($limit) && $limit != -1) {
            $select->limit((int) $limit);
        }

        $select->order($orderColumn . ' ' . $order);

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    /**
     * @param array $options
     * @param null $fixedCriteria
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getAccountToolList(array $options, $fixedCriteria = null)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $max_order_date = new \Laminas\Db\Sql\Predicate\Expression('(SELECT ord_date_creation FROM melis_ecom_order WHERE ord_client_id = cli_id ORDER BY ord_date_creation DESC LIMIT 1)');

        $count = $options['count'] ?? false;

        if ($count) {
            $select->columns(['total' => new \Laminas\Db\Sql\Expression('COUNT(*)')]);
        } else {
            $select->columns(array('*', 'cli_last_order' => $max_order_date));
        }

        $where = !empty($options['where']['key']) ? $options['where']['key'] : '';
        $whereValue = !empty($options['where']['value']) ? $options['where']['value'] : '';

        $order = !empty($options['order']['key']) ? $options['order']['key'] : '';
        $orderDir = !empty($options['order']['dir']) ? $options['order']['dir'] : 'ASC';

        $start = (int) $options['start'];
        $limit = (int) $options['limit'] === -1 ? $this->getTotalData() : (int) $options['limit'];

        $groupId = $options['groupId'];

        $clientStatus = $options['clientStatus'];

        $columns = $options['columns'];

        // check if there's an extra variable that should be included in the query
        $dateFilter = $options['date_filter'];
        $dateFilterSql = '';

        if (count($dateFilter)) {
            if (!empty($dateFilter['startDate']) && !empty($dateFilter['endDate'])) {
                $dateFilterSql = '`' . $dateFilter['key'] . '` BETWEEN \'' . $dateFilter['startDate'] . '\' AND \'' . $dateFilter['endDate'] . '\'';
            }
        }

        if (!$count) {
            $select->join(
                'melis_ecom_client_account_rel',
                'melis_ecom_client.cli_id=melis_ecom_client_account_rel.car_client_id',
                array('*'),
                $select::JOIN_LEFT
            );
            $select->join(
                'melis_ecom_client_person',
                'melis_ecom_client_account_rel.car_client_person_id = melis_ecom_client_person.cper_id',
                array('cper_firstname', 'cper_name', 'cper_id', 'cper_email'),
                $select::JOIN_LEFT
            );
        }
        $select->join('melis_ecom_client_company', 'melis_ecom_client_company.ccomp_client_id = melis_ecom_client.cli_id', array('cli_company' => 'ccomp_name', '*'), $select::JOIN_LEFT);
        $select->join(
            'melis_ecom_client_groups',
            'melis_ecom_client_groups.cgroup_id=melis_ecom_client.cli_group_id',
            array('cgroup_name'),
            $select::JOIN_LEFT
        );

        //        $select->where('melis_ecom_client_account_rel.car_default_person = 1');

        if (!is_null($groupId) && $groupId != "")
            $select->where->equalTo('cli_group_id', $groupId);

        if (!is_null($clientStatus) && $clientStatus != "")
            $select->where->equalTo('cli_status', $clientStatus);

        // this is used when searching
        if (!empty($where)) {
            $w = new Where();
            $p = new PredicateSet();
            $filters = array();
            $likes = array();
            foreach ($columns as $colKeys) {
                $likes[] = new Like($colKeys, '%' . $whereValue . '%');
            }

            if (!empty($dateFilterSql)) {
                $filters = array(new PredicateSet($likes, PredicateSet::COMBINED_BY_OR), new \Laminas\Db\Sql\Predicate\Expression($dateFilterSql));
            } else {
                $filters = array(new PredicateSet($likes, PredicateSet::COMBINED_BY_OR));
            }
            $fixedWhere = array(new PredicateSet(array(new Operator('', '=', ''))));
            if (is_null($fixedCriteria)) {
                $select->where($filters);
            } else {
                $select->where(array(
                    $fixedWhere,
                    $filters,
                ), PredicateSet::OP_AND);
            }
        }

        // used when column ordering is clicked
        if (!empty($order) && !$count)
            $select->order($order . ' ' . $orderDir);

        if ($count) {
            $getCount = $this->getTableGateway()->selectWith($select);
            $this->setCurrentDataCount((int) $getCount->count());
        }

        if (!empty($start)) {
            $select->offset($start);
        }

        if (!empty($limit)) {
            $select->limit($limit);
        }

        if (!$count)
            $select->group('cli_id');

        $resultSet = $this->getTableGateway()->selectWith($select);


        return $resultSet;
    }


    public function getClientList(
        $countryId = null,
        $dateCreationMin = null,
        $dateCreationMax = null,
        $onlyValid = null,
        $start = 0,
        $limit = null,
        $order = array(),
        $search = null,
        $count = false
    ) {
        $select = $this->tableGateway->getSql()->select();
        if ($count) {
            $select->columns(['total' => new \Laminas\Db\Sql\Expression('COUNT(*)')]);
        } else {
            $select->quantifier('DISTINCT');
        }
        $select->join(
            'melis_ecom_client_company',
            'melis_ecom_client_company.ccomp_client_id=melis_ecom_client.cli_id',
            array(),
            $select::JOIN_LEFT
        );
        $select->join(
            'melis_ecom_client_groups',
            'melis_ecom_client_groups.cgroup_id=melis_ecom_client.cli_group_id',
            array('cgroup_name'),
            $select::JOIN_LEFT
        );

        if (!is_null($countryId)) {
            $select->where->equalTo('melis_ecom_client.cli_country_id', (int)$countryId);
        }

        if (!is_null($dateCreationMin)) {
            $select->where->greaterThan('melis_ecom_client.cli_date_creation', $dateCreationMin);
        }

        if (!is_null($dateCreationMax)) {
            $select->where->lessThan('melis_ecom_client.cli_date_creation', $dateCreationMax);
        }

        if ($onlyValid == 'active') {
            $onlyValid = 1;
        }

        if ($onlyValid == 'inactive') {
            $onlyValid = 0;
        }

        if (!is_null($onlyValid) && in_array($onlyValid, array('0', '1'))) {
            $select->where->equalTo('cli_status', (int)$onlyValid);
        }

        if (!is_null($search)) {
            $search = '%' . $search . '%';
            $select->where->NEST->like('cli_id', $search)
                ->or->like('melis_ecom_client_person.cper_name', $search)

                ->or->like('melis_ecom_client_company.ccomp_name', $search)
                ->or->like('melis_ecom_client_groups.cgroup_name', $search);
        }

        if (!$count) {
            if (!is_null($start) && is_numeric($start)) {
                $select->offset((int)$start);
            }

            if (!is_null($limit) && is_numeric($limit) && $limit != -1) {
                $select->limit((int)$limit);
            }

            $select->order(array('cli_id' => $order));
        }

        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }

    public function getClientByEmailAndPassword($personEmail, $personPassword)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->join(
            'melis_ecom_client_person',
            'melis_ecom_client_person.cper_client_id=melis_ecom_client.cli_id',
            array(),
            $select::JOIN_LEFT
        );
        $select->where(array('cper_email' => $personEmail, 'melis_ecom_client_person.cper_password' => $personPassword));

        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }

    /**
     * @param null $langId
     * @param null $onlyValid
     * @param null $isMain
     * @param null $couponId
     * @param int $start
     * @param null $limit
     * @param string $order
     * @param null $searchValue
     * @param array $searchKeys
     * @param bool $count
     * @return mixed
     */
    public function getCouponClientList($onlyValid = null, $isMain = null, $couponId = null, $start = 0, $limit = null, $order = 'cli_id ASC', $searchValue = null, $searchKeys = [], $count = false)
    {
        $select = $this->tableGateway->getSql()->select();

        if ($count) {
            $select->columns(['total' => new \Laminas\Db\Sql\Expression('COUNT(*)')]);
        } else {
            $select->quantifier('DISTINCT');
        }

        $select->join(
            'melis_ecom_client_company',
            'melis_ecom_client_company.ccomp_client_id=melis_ecom_client.cli_id',
            array(),
            $select::JOIN_LEFT
        );
        $select->join(
            'melis_ecom_coupon_client',
            'melis_ecom_coupon_client.ccli_client_id=melis_ecom_client.cli_id',
            array(),
            $select::JOIN_LEFT
        );

        if (!empty($searchValue)) {
            $search = [];
            foreach ($searchKeys as $col)
                $search[$col] = new Like($col, '%' . $searchValue . '%');

            $filters = [new PredicateSet($search, PredicateSet::COMBINED_BY_OR)];
            $select->where($filters);
        }

        if (!is_null($couponId)) {
            $select->where->equalTo('melis_ecom_coupon_client.ccli_coupon_id', (int)$couponId);
        }

        if (!is_null($onlyValid) && in_array($onlyValid, array('0', '1'))) {
            $select->where->equalTo('cli_status', (int)$onlyValid);
        }

        if (!is_null($start)) {
            $select->offset($start);
        }

        if (!is_null($limit) && $limit != -1) {
            $select->limit($limit);
        }

        $select->order($order);
        $select->group('cli_id');

        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }

    public function getCurrentMonth()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where('YEAR(cli_date_creation) = YEAR(CURRENT_DATE())');
        $select->where('MONTH(cli_date_creation) = MONTH(CURRENT_DATE())');

        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }

    public function getAvgMonth()
    {
        $sql = 'SELECT AVG(`monthly`) AS average FROM (SELECT COUNT(*) as `monthly` from melis_ecom_client group by YEAR(`cli_date_creation`), MONTH(`cli_date_creation`)) AS average';
        $resultData = $this->tableGateway->getAdapter()->driver->getConnection()->execute($sql);

        return $resultData;
    }

    public function getActiveInactive($type)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['total' => new \Laminas\Db\Sql\Expression('COUNT(*)')]);
        if ($type == 'active')
            $select->where->equalTo('cli_status', 1);
        elseif ($type == 'inactive')
            $select->where->equalTo('cli_status', 0);

        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }

    /**
     * @param $clientId
     * @return mixed
     */
    public function getClientDefaultContactByClientId($clientId)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->join(
            'melis_ecom_client_account_rel',
            'melis_ecom_client_account_rel.car_client_id=melis_ecom_client.cli_id',
            array(),
            $select::JOIN_LEFT
        );
        $select->join(
            'melis_ecom_client_person',
            'melis_ecom_client_account_rel.car_client_person_id=melis_ecom_client_person.cper_id',
            array('*'),
            $select::JOIN_LEFT
        );

        $select->where(array('cli_id' => (int)$clientId));
        $select->where(array('car_default_person' => 1));

        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }

    /**
     * @param $clientId
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getContactListByClientId($clientId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join(
            'melis_ecom_client_account_rel',
            'melis_ecom_client_account_rel.car_client_id=melis_ecom_client.cli_id',
            array('*'),
            $select::JOIN_LEFT
        );
        $select->join(
            'melis_ecom_client_person',
            'melis_ecom_client_account_rel.car_client_person_id=melis_ecom_client_person.cper_id',
            array('*'),
            $select::JOIN_LEFT
        );
        $select->join(
            'melis_ecom_civility',
            'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'),
            $select::JOIN_LEFT
        );

        $select->where->equalTo('melis_ecom_client_account_rel.car_client_id', (int)$clientId);

        $select->order('melis_ecom_client_account_rel.car_default_person DESC');

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
}
