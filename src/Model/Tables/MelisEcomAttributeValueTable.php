<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomAttributeValueTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'attr_id';
    }
    
    public function getAttributeValuesById($attributeValueId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $select ->join('melis_ecom_attribute_type', 'melis_ecom_attribute_type.atype_id = melis_ecom_attribute_value.atval_type_id', array('atype_column_value'), $select::JOIN_LEFT);
        $clause['melis_ecom_attribute_value.atval_id'] = (int) $attributeValueId;
        
        if($clause){
            $select->where($clause);
        }
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getAttributeValuesList($attributeId = null, $langId = null, $start = null, $limit = null, $order = null, $search = null, $valCol = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $clause = array();
        $select->quantifier('DISTINCT');

        $select->join('melis_ecom_attribute_value_trans', 'melis_ecom_attribute_value_trans.av_attribute_value_id = melis_ecom_attribute_value.atval_id', array(), $select::JOIN_LEFT);
        
        if (!is_null($attributeId))
        {
            $select->where('melis_ecom_attribute_value.atval_attribute_id ='.$attributeId);
        }
        
        if(!is_null($search) && is_null($valCol)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('atval_id', $search);           
        }
        
        if(!is_null($search)&& !is_null($valCol)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('atval_id', $search)
            ->or->like('melis_ecom_attribute_value_trans.'.$valCol, $search);           
        }
        
        if (!is_null($start))
        {
            $select->offset($start);
        }
        
        if (!is_null($limit)&&$limit!=-1)
        {
            $select->limit($limit);
        }
        
        if (!is_null($order))
        {
            $select->order($order);
        }
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
}