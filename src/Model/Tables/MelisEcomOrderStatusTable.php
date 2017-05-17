<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomOrderStatusTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'osta_id';
    }
    
    public function getOrderStatusListByLangId($langId = null, $onlyValid = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_order_status_trans', 'melis_ecom_order_status_trans.ostt_status_id=melis_ecom_order_status.osta_id',
            array('*'),$select::JOIN_LEFT);
        if (!is_null($langId))
        {
            $select->where('ostt_lang_id ='.$langId);
        }
        
        if(!is_null($onlyValid)){
            $select->where->equalTo('osta_status', 1);
        }
        
        $select->order('ostt_status_id ASC');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getOrderStatusList($onlyValid = null, $start = null, $limit = null, $colOrder = null, $search = '')
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->join('melis_ecom_order_status_trans', 'melis_ecom_order_status_trans.ostt_status_id=melis_ecom_order_status.osta_id',
            array(),$select::JOIN_LEFT);

        
        if(!is_null($onlyValid)){
            $select->where->equalTo('osta_status', 1);
        }
    
        if(!is_null($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('osta_color_code', $search)
            ->or->like('ostt_status_name', $search);
        }
    
        if (!is_null($start))
        {
            $select->offset($start);
        }
    
        if (!is_null($limit)&&$limit!=-1)
        {
            $select->limit($limit);
        }
    
        if (!is_null($colOrder))
        {
            $select->order($colOrder);
        }
    
        $select->group('osta_id');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
}