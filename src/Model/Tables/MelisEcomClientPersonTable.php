<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Predicate\Like;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Predicate\Predicate;

class MelisEcomClientPersonTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'cper_id';
    }
    
    public function getClientPersonByClientId($clientId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility', 
                    array('*'), $select::JOIN_LEFT);
        
        $select->where('cper_client_id ='.$clientId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientPersonByPersonId($personId)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);
    
        $select->where('cper_id ='.$personId);
    
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientPersonByClientIdPersonIdAndPersonEmail($clientId, $personId = null, $personEmail = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);
        
        $select->where('cper_client_id ='.$clientId);
        
        if (!is_null($personId)){
            $select->where('cper_id ='.$personId);
        }
        
        if (!is_null($personEmail)){
            $select->where('cper_email = "'.$personEmail.'"');
        }
        
        $select->order('cper_is_main_person DESC', 'cper_firstname', 'cper_name');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientMainPersonByClientId($clientId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);
        
        $select->where('cper_client_id ='.$clientId);
        $select->where('cper_is_main_person = 1');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getContacts(array $options, $fixedCriteria = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $result = $this->tableGateway->select();
    
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
                $filters = array(new PredicateSet($likes,PredicateSet::COMBINED_BY_OR), new \Zend\Db\Sql\Predicate\Expression($dateFilterSql));
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
        
        $select->where('cli_status = 1');
        $select->where('cper_status = 1');
         
    
        // used when column ordering is clicked
        if(!empty($order))
            $select->order($order . ' ' . $orderDir);
    
             
            $getCount = $this->tableGateway->selectWith($select);
            $this->setCurrentDataCount((int) $getCount->count());
             
             
            // this is used in paginations
            $select->limit($limit);
            $select->offset($start);
    
            $resultSet = $this->tableGateway->selectWith($select);
    
            $sql = $this->tableGateway->getSql();
            $raw = $sql->getSqlstringForSqlObject($select);
    
            return $resultSet;
    
    }
    
    public function checkEmailExist($email, $personId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where('cper_email = "'.$email.'"');
        $select->where('cper_id !='.$personId);
        $resultData = $this->tableGateway->selectWith($select);
        
        return $resultData;
    }
    
}