<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\Sql\Expression;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Select;
use Laminas\Db\Metadata\Metadata;
use Laminas\Db\Sql\Where;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\Operator;
use Laminas\Db\Sql\Predicate\Predicate;

class MelisEcomClientPersonTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_client_person';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'cper_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * @param null $accountId
     * @param null $type
     * @param null $status
     * @param string $searchValue
     * @param array $searchKeys
     * @param null $start
     * @param null $limit
     * @param string $orderColumn
     * @param string $order
     * @param bool $defaultAccountOnly
     * @param bool $hasDefaultOnly
     * @param bool $count
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getContactLists($accountId = null, $type = null, $status = null, $searchValue = '', $searchKeys = [], $start = null, $limit = null, $orderColumn = 'cper_id', $order = 'DESC', $defaultAccountOnly = false, $hasDefaultOnly = false,$count = false)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $slct = ['*', new Expression($this->getTableGateway()->getTable() . '.' . $this->idField.' As DT_RowId')];
        if ($count) {
            $slct = [new Expression('COUNT(' . $this->getTableGateway()->getTable() . '.' . $this->idField . ') As totalRecords')];
        }
        $select->columns($slct);

        if($defaultAccountOnly && empty($accountId))
            $select->join('melis_ecom_client_person_rel', new Expression('melis_ecom_client_person_rel.cpr_client_person_id = melis_ecom_client_person.cper_id AND melis_ecom_client_person_rel.cpr_default_client = 1'), array('cpr_default_client'), $select::JOIN_LEFT);
        else
            $select->join('melis_ecom_client_person_rel', 'melis_ecom_client_person_rel.cpr_client_person_id = melis_ecom_client_person.cper_id', array('cpr_default_client'), $select::JOIN_LEFT);

        $select->join(['client' => 'melis_ecom_client'], 'client.cli_id = melis_ecom_client_person_rel.cpr_client_id', array('cli_id', 'cli_name'), $select::JOIN_LEFT);
        $select->join('melis_ecom_client_groups', 'melis_ecom_client_groups.cgroup_id=client.cli_group_id', array('cgroup_name'), $select::JOIN_LEFT);

        if (!empty($searchValue)){
            $search = [];
            foreach ($searchKeys As $col)
                $search[$col] = new Like($col, '%'.$searchValue.'%');

            $filters = [new PredicateSet($search, PredicateSet::COMBINED_BY_OR)];
            $select->where($filters);
        }

        if($hasDefaultOnly)
            $select->where->equalTo('melis_ecom_client_person_rel.cpr_default_client', 1);

        if(!empty($accountId))
            $select->where->equalTo('client.cli_id', $accountId);

        if(!empty($type))
            $select->where->equalTo('cper_type', $type);

        if(!is_null($status) && $status != '')
            $select->where->equalTo('cper_status', $status);

        if (!empty($start))
        {
            $select->offset($start);
        }

        if (!empty($limit) && $limit != -1)
        {
            $select->limit((int) $limit);
        }

        if(!empty($orderColumn))
            $select->order($orderColumn .' '. $order);

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    /**
     * @param $contactId
     * @param string $searchValue
     * @param array $searchKeys
     * @param null $start
     * @param null $limit
     * @param string $orderColumn
     * @param string $order
     * @param bool $count
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getContactAssocAccountLists($contactId, $searchValue = '', $searchKeys = [], $start = null, $limit = null, $orderColumn = 'cli_id', $order = 'DESC', $count = false)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $slct = ['*', new Expression('client.cli_id As DT_RowId')];
        if ($count) {
            $slct = [new Expression('COUNT(' . $this->getTableGateway()->getTable() . '.' . $this->idField . ') As totalRecords')];
        }
        $select->columns($slct);

        $select->join('melis_ecom_client_person_rel', 'melis_ecom_client_person_rel.cpr_client_person_id = melis_ecom_client_person.cper_id', array('cpr_id','cpr_default_client'), $select::JOIN_LEFT);
        $select->join(['client' => 'melis_ecom_client'], 'client.cli_id = melis_ecom_client_person_rel.cpr_client_id', array('cli_id','cli_name','cli_status'), $select::JOIN_LEFT);

        if (!empty($searchValue)){
            $search = [];
            foreach ($searchKeys As $col)
                $search[$col] = new Like($col, '%'.$searchValue.'%');

            $filters = [new PredicateSet($search, PredicateSet::COMBINED_BY_OR)];
            $select->where($filters);
        }

        if(!empty($contactId))
            $select->where->equalTo('melis_ecom_client_person_rel.cpr_client_person_id', $contactId);

        if (!empty($start))
        {
            $select->offset($start);
        }

        if (!empty($limit) && $limit != -1)
        {
            $select->limit((int) $limit);
        }

        $select->order($orderColumn .' '. $order);

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    /**
     * @param $clientId
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getClientPersonByClientId($clientId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join('melis_ecom_client_person_rel', 'melis_ecom_client_person_rel.cpr_client_person_id=melis_ecom_client_person.cper_id',
            array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);

        $select->where('melis_ecom_client_person_rel.cpr_client_id ='.$clientId);

        $select->order('melis_ecom_client_person_rel.cpr_default_client DESC');

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    /**
     * @param $personId
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getClientPersonByPersonId($personId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->columns(
            ['*', new Expression('melis_ecom_client_person_rel.cpr_client_id as client_id'), new Expression('client.cli_group_id as client_group')]
        );

        $select->join('melis_ecom_client_person_rel', 'melis_ecom_client_person_rel.cpr_client_person_id=melis_ecom_client_person.cper_id',
            array('*'), $select::JOIN_LEFT);
        $select->join(['client' => 'melis_ecom_client'], 'client.cli_id = melis_ecom_client_person_rel.cpr_client_id',
            array('cli_id','cli_name','cli_status'), $select::JOIN_LEFT);
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id = melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);

        if($personId) {
            $select->where('melis_ecom_client_person_rel.cpr_client_person_id =' . $personId);
        }

    
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    /**
     * @param $clientId
     * @param null $personId
     * @param null $personEmail
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getClientPersonByClientIdPersonIdAndPersonEmail($clientId, $personId = null, $personEmail = null)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join('melis_ecom_client_person_rel', 'melis_ecom_client_person_rel.cpr_client_person_id=melis_ecom_client_person.cper_id',
            array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);

        $select->where('melis_ecom_client_person_rel.cpr_client_id ='.$clientId);

        if(!empty($personId))
            $select->where('melis_ecom_client_person_rel.cpr_client_person_id ='.$personId);
        if(!empty($personEmail))
            $select->where('melis_ecom_client_person.cper_email ='.$personEmail);

        $select->order('cper_is_main_person DESC', 'cper_firstname', 'cper_name');

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    /**
     * This function will get all contact associated to the given account/client
     *
     * @param $clientId
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getContactListByClientId($clientId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join('melis_ecom_client_account_rel', 'melis_ecom_client_account_rel.car_client_person_id=melis_ecom_client_person.cper_id',
            array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);

        $select->where('melis_ecom_client_account_rel.car_client_id ='.$clientId);

        $select->order('melis_ecom_client_account_rel.car_default_person DESC');

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
    
    public function getClientMainPersonByClientId($clientId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join('melis_ecom_client_account_rel', 'melis_ecom_client_account_rel.car_client_person_id=melis_ecom_client_person.cper_id',
            array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);

        $select->where('melis_ecom_client_account_rel.car_client_id ='.$clientId);
        $select->where('car_default_person = 1');
        
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
    
    public function getContacts(array $options, $fixedCriteria = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $cper_contact = new \Laminas\Db\Sql\Predicate\Expression("CONCAT(COALESCE(`cper_firstname`,''),' ',COALESCE(`cper_middle_name`,''),' ',COALESCE(`cper_name`,'')) as cper_contact");
        $max_order_date = new \Laminas\Db\Sql\Predicate\Expression('(SELECT ord_date_creation FROM melis_ecom_order WHERE ord_client_person_id = cper_client_id ORDER BY ord_date_creation DESC LIMIT 1)'); 
        $select->columns(array('*', $cper_contact, 'cper_last_order' => $max_order_date));
        
        $result = $this->getTableGateway()->select();
    
        $where = !empty($options['where']['key']) ? $options['where']['key'] : '';
        $whereValue = !empty($options['where']['value']) ? $options['where']['value'] : '';
    
        $order = !empty($options['order']['key']) ? $options['order']['key'] : '';
        $orderDir = !empty($options['order']['dir']) ? $options['order']['dir'] : 'ASC';
    
        $start = (int) $options['start'];
        $limit = (int) $options['limit'] === -1 ? $this->getTotalData() : (int) $options['limit'];
    
        $columns = $options['columns'];
         
        // check if there's an extra variable that should be included in the query
        $dateFilter = $options['date_filter'];
        $dateFilterSql = '';
         
        if(count($dateFilter)) {
            if(!empty($dateFilter['startDate']) && !empty($dateFilter['endDate'])) {
                $dateFilterSql = '`' . $dateFilter['key'] . '` BETWEEN \'' . $dateFilter['startDate'] . '\' AND \'' . $dateFilter['endDate'] . '\'';
            }
        }
        
        $select->join('melis_ecom_client', 'melis_ecom_client.cli_id=melis_ecom_client_person.cper_client_id',
            array('*'), $select::JOIN_LEFT);

        $select->join('melis_ecom_client_groups', 'melis_ecom_client_groups.cgroup_id=melis_ecom_client.cli_group_id',
            array('*'), $select::JOIN_LEFT);
    
        // this is used when searching
        if(!empty($where)) {
            $w = new Where();
            $p = new PredicateSet();
            $filters = array();
            $likes = array();
            foreach($columns as $colKeys)
            {
                $likes[] = new Like($colKeys, '%'.$whereValue.'%');
            }
             
            if(!empty($dateFilterSql))
            {
                $filters = array(new PredicateSet($likes,PredicateSet::COMBINED_BY_OR), new \Laminas\Db\Sql\Predicate\Expression($dateFilterSql));
            }
            else
            {
                $filters = array(new PredicateSet($likes,PredicateSet::COMBINED_BY_OR));
            }
            $fixedWhere = array(new PredicateSet(array(new Operator('', '=', ''))));
            if(is_null($fixedCriteria))
            {
                $select->where($filters);
            }
            else
            {
                $select->where(array(
                    $fixedWhere,
                    $filters,
                ), PredicateSet::OP_AND);
            }
             
        }
        //for the client id 
        if (!empty($options['client_id'])) {
            $select->where('cper_client_id = '.$options['client_id']);
        }
        
        $select->where('cli_status = 1');
        $select->where('cper_status = 1');
         
        // used when column ordering is clicked
        if(!empty($order))
            $select->order($order . ' ' . $orderDir);
    
             
            $getCount = $this->getTableGateway()->selectWith($select);
            $this->setCurrentDataCount((int) $getCount->count());
             
             
            // this is used in paginations
            $select->limit($limit);
            $select->offset($start);
    
            $resultSet = $this->getTableGateway()->selectWith($select);
    
            return $resultSet;
    
    }
    
//    public function getClientList(array $options, $fixedCriteria = null)
//    {
//        $select = $this->getTableGateway()->getSql()->select();
//
//        $cper_contact = new \Laminas\Db\Sql\Predicate\Expression("CONCAT(COALESCE(`cper_firstname`,''),' ',COALESCE(`cper_middle_name`,''),' ',COALESCE(`cper_name`,'')) as cli_person");
//        $max_order_date = new \Laminas\Db\Sql\Predicate\Expression('(SELECT ord_date_creation FROM melis_ecom_order WHERE ord_client_id = cper_client_id ORDER BY ord_date_creation DESC LIMIT 1)');
//        $select->columns(array('*', $cper_contact, 'cli_last_order' => $max_order_date));
//
////         $result = $this->$this->getTableGateway()->select();
//
//        $where = !empty($options['where']['key']) ? $options['where']['key'] : '';
//        $whereValue = !empty($options['where']['value']) ? $options['where']['value'] : '';
//
//        $order = !empty($options['order']['key']) ? $options['order']['key'] : '';
//        $orderDir = !empty($options['order']['dir']) ? $options['order']['dir'] : 'ASC';
//
//        $start = (int) $options['start'];
//        $limit = (int) $options['limit'] === -1 ? $this->getTotalData() : (int) $options['limit'];
//
//        $groupId = $options['groupId'];
//
//        $clientStatus = $options['clientStatus'];
//
//        $columns = $options['columns'];
//
//        // check if there's an extra variable that should be included in the query
//        $dateFilter = $options['date_filter'];
//        $dateFilterSql = '';
//
//        if(count($dateFilter)) {
//            if(!empty($dateFilter['startDate']) && !empty($dateFilter['endDate'])) {
//                $dateFilterSql = '`' . $dateFilter['key'] . '` BETWEEN \'' . $dateFilter['startDate'] . '\' AND \'' . $dateFilter['endDate'] . '\'';
//            }
//        }
//
//        $select->join('melis_ecom_client', 'melis_ecom_client.cli_id=melis_ecom_client_person.cper_client_id',
//            array('*'), $select::JOIN_LEFT);
//
//        $select->join('melis_ecom_client_company', 'melis_ecom_client_company.ccomp_client_id = melis_ecom_client.cli_id', array('cli_company' => 'ccomp_name'), $select::JOIN_LEFT);
//        $select->join('melis_ecom_client_groups', 'melis_ecom_client_groups.cgroup_id=melis_ecom_client.cli_group_id',
//            array('cgroup_name'),$select::JOIN_LEFT);
//
//        $select->where('cper_is_main_person = 1');
//
//        if(!is_null($groupId) && $groupId != "")
//            $select->where->equalTo('cli_group_id', $groupId);
//
//        if(!is_null($clientStatus) && $clientStatus != "")
//            $select->where->equalTo('cli_status', $clientStatus);
//
//        // this is used when searching
//        if(!empty($where)) {
//            $w = new Where();
//            $p = new PredicateSet();
//            $filters = array();
//            $likes = array();
//            foreach($columns as $colKeys)
//            {
//                $likes[] = new Like($colKeys, '%'.$whereValue.'%');
//            }
//
//            if(!empty($dateFilterSql))
//            {
//                $filters = array(new PredicateSet($likes,PredicateSet::COMBINED_BY_OR), new \Laminas\Db\Sql\Predicate\Expression($dateFilterSql));
//            }
//            else
//            {
//                $filters = array(new PredicateSet($likes,PredicateSet::COMBINED_BY_OR));
//            }
//            $fixedWhere = array(new PredicateSet(array(new Operator('', '=', ''))));
//            if(is_null($fixedCriteria))
//            {
//                $select->where($filters);
//            }
//            else
//            {
//                $select->where(array(
//                    $fixedWhere,
//                    $filters,
//                ), PredicateSet::OP_AND);
//            }
//
//        }
//
//        // used when column ordering is clicked
//        if(!empty($order))
//            $select->order($order . ' ' . $orderDir);
//
//        $getCount = $this->getTableGateway()->selectWith($select);
//        $this->setCurrentDataCount((int) $getCount->count());
//
//        // this is used in paginations
//        $select->limit($limit);
//        $select->offset($start);
//
//        $select->group('cli_id');
//
//        $resultSet = $this->getTableGateway()->selectWith($select);
//
//
//        return $resultSet;
//
//    }
    
    public function checkEmailExist($email, $personId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->where('cper_email = "'.$email.'"');
        $select->where('cper_id !='.$personId);
        $resultData = $this->getTableGateway()->selectWith($select);
        
        return $resultData;
    }

    public function getAllContactsAndCompany()
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->join('melis_ecom_client_company', 'melis_ecom_client_company.ccomp_client_id = melis_ecom_client_person.cper_client_id', array('ccomp_id', 'ccomp_name'), $select::JOIN_LEFT);

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    /**
     * @param $contactId
     * @return mixed
     */
    public function getContactDefaultAccount($contactId)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->join('melis_ecom_client_person_rel', 'melis_ecom_client_person_rel.cpr_client_person_id=melis_ecom_client_person.cper_id',
            array(),$select::JOIN_LEFT);
        $select->join('melis_ecom_client', 'melis_ecom_client_person_rel.cpr_client_id=melis_ecom_client.cli_id',
            array('*'),$select::JOIN_LEFT);

        $select->where(array('cper_id' => $contactId));
        $select->where(array('cpr_default_client' => 1));

        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
}