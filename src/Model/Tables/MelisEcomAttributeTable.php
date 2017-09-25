<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;

class MelisEcomAttributeTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'attr_id';
    }
    
    public function getAttributeList($status = null, $visible = null, $searchable = null,
                                  $start = null, $limit = null, $order = null, $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $clause = array();
        $select->quantifier('DISTINCT');
        
        $select->join('melis_ecom_attribute_type', 'melis_ecom_attribute_type.atype_id = melis_ecom_attribute.attr_type_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_attribute_trans', 'melis_ecom_attribute_trans.atrans_attribute_id = melis_ecom_attribute.attr_id', array(), $select::JOIN_LEFT);
        
        if(!is_null($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('attr_id', $search)
            ->or->like('melis_ecom_attribute.attr_reference', $search)
            ->or->like('melis_ecom_attribute_trans.atrans_name', $search)
            ->or->like('melis_ecom_attribute_type.atype_name', $search);
        }
        
        if (!is_null($status))
        {
            $select->where->equalTo('melis_ecom_attribute.attr_status ='.$status);
        }
        
        if (!is_null($visible))
        {

            $select->where->equalTo('melis_ecom_attribute.attr_visible ='.$visible);
        }
        
        if (!is_null($searchable))
        {
            $select->where->equalTo('melis_ecom_attribute.attr_searchable ='.$searchable);
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
    
    public function getProductAttributes($langId = null, $status = 1, $visible = 1, $searchable = 1)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $clause = array();
        
        $select->join(array('attribute_trans' => 'melis_ecom_attribute_trans'), 'attribute_trans.atrans_attribute_id = melis_ecom_attribute.attr_type_id', array('*'), $select::JOIN_LEFT);
        
        if(!is_null($langId)) {
            $clause['attribute_trans.atrans_lang_id'] = (int) $langId;
        }
        
        $clause['melis_ecom_attribute.attr_status'] = (int) $status;
        $clause['melis_ecom_attribute.attr_visible'] = (int) $visible;
        $clause['melis_ecom_attribute.attr_searchable'] = (int) $searchable;

        $select->where($clause);
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getUsedAttributeByProduct($productId, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->quantifier('DISTINCT');
        
        $select->join('melis_ecom_attribute_value', 'melis_ecom_attribute_value.atval_attribute_id = melis_ecom_attribute.attr_id', array(), $select::JOIN_LEFT)
        ->join('melis_ecom_variant_attribute_value', 'melis_ecom_variant_attribute_value.vatv_attribute_value_id = melis_ecom_attribute_value.atval_id', array(), $select::JOIN_LEFT)
        ->join('melis_ecom_variant', 'melis_ecom_variant.var_id = melis_ecom_variant_attribute_value.vatv_variant_id', array(), $select::JOIN_LEFT);
    
        $select->where->equalTo('melis_ecom_variant.var_prd_id', $productId);
        $resultData = $this->tableGateway->selectWith($select);
    
        return $resultData;
    }
    
    public function getAttributeListAndValues($attributeId = null, $status = false, $searchable = false, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        if (!is_null($langId))
            $join = new Expression('melis_ecom_attribute_trans.atrans_attribute_id = melis_ecom_attribute.'.$this->idField.' AND atrans_lang_id ='.$langId.' AND atrans_name != ""');
        else
            $join = new Expression('melis_ecom_attribute_trans.atrans_attribute_id = melis_ecom_attribute.'.$this->idField.' AND atrans_name IS NOT NULL AND atrans_name != ""');
        
        $select->join('melis_ecom_attribute_trans', $join, array('*'), $select::JOIN_LEFT);
        
        if (!is_null($attributeId))
            $select->where($this->idField.' = '.$attributeId);
        
        if ($status)
            $select->where('attr_status = 1');
        
        if ($searchable)
            $select->where('attr_searchable = 1');
        
        $select->group($this->idField);
        
        $resultData = $this->tableGateway->selectWith($select);
        
        return $resultData;
    }
    
}