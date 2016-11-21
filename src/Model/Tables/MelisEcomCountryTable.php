<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Predicate\Like;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Predicate\Predicate;
class MelisEcomCountryTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'ctry_id';
    }
    
    public function getCountries()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $select->where->equalTo('ctry_status', 1);
        $select->order('ctry_name ASC');

        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    public function getCountryCurrency($countryId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_country.ctry_currency_id', array('*'), $select::JOIN_LEFT);
        $select->where->equalTo('ctry_id', $countryId);
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
    
    public function getCountryList(array $options, $fixedCriteria = null)
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

        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_country.ctry_currency_id', array('*'), $select::JOIN_LEFT);
        
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
    
}