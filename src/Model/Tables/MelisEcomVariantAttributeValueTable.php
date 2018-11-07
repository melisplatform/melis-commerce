<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\TableGateway\TableGateway;

class MelisEcomVariantAttributeValueTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'vatv_id';
    }
    
    public function getVariantAttributeValuesById($variantId, $langId = 1)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $select->join('melis_ecom_attribute_value', 'melis_ecom_attribute_value.atval_id = melis_ecom_variant_attribute_value.vatv_attribute_value_id', array('*'), $select::JOIN_LEFT)
               ->join('melis_ecom_attribute_trans', 'melis_ecom_attribute_trans.atrans_attribute_id = melis_ecom_attribute_value.atval_attribute_id', array('*'), $select::JOIN_LEFT);
        
        
        if(!is_null($langId))
            $clause['melis_ecom_attribute_trans.atrans_lang_id'] = (int) $langId;
        
        $clause['melis_ecom_variant_atribute_value.vatv_variant_id'] = (int) $variantId;
        
        $resultSet = $this->tableGateway->selectwith($select);

        return $resultSet;
    }

    public function getVariantAttributeValueIdByVariantId($ids)
    {
        $select = $this->tableGateway->getSql()->select();

        if(is_array($ids)) {
            $select->where->in('vatv_variant_id', $ids);
        }else{
            $select->where->equalTo('vatv_variant_id', $ids);
        }

        $resultData = $this->tableGateway->selectWith($select);

        return $resultData;
    }

    public function getVariantsByAttributeValueIds($attrValueIds, $isValid = true)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array(new Expression('DISTINCT(melis_ecom_variant.var_id) as var_id')));

        $select->join('melis_ecom_variant', 'melis_ecom_variant.var_id = melis_ecom_variant_attribute_value.vatv_variant_id', array('*'), $select::JOIN_LEFT);

        if ($isValid){
            $select->where('melis_ecom_variant.var_status = 1');
        }

        $select->where->in('melis_ecom_variant_attribute_value.vatv_attribute_value_id', $attrValueIds);

        $resultData = $this->tableGateway->selectWith($select);

        return $resultData;
    }
}