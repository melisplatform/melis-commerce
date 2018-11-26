<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomCurrencyTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'cur_id';
    }
    
    public function getCurrencies($status = null, $start = null, $limit = null, $order = null, $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        

        if(!is_null($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('cur_id', $search)
            ->or->like('cur_name', $search)
            ->or->like('cur_code', $search)
            ->or->like('cur_symbol', $search);
        }
        
        if(!is_null($status)){
            $select->where->equalTo('cur_status', $status);    
        }
        
        if(!is_null($start)){
            $select->offset($start);   
        }
        
        if(!is_null($limit)){
            $select->limit($limit);
        }
        
        if(!is_null($order)){
            $select->order($order);
        }
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
        
    }
    
    public function getDefaultCurrency()
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where->equalTo('cur_default', 1);
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }

    public function getCountriesUsingCurrency($currencyId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('melis_ecom_country', 'melis_ecom_country.ctry_currency_id = melis_ecom_currency.cur_id',
            array('*'), $select::JOIN_LEFT);

        $select->where->equalTo('cur_id', $currencyId);
        $select->where->equalTo('ctry_status', 1);

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }
}