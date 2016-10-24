<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomVariantTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'var_id';
    }
    
    public function getVariants($variantId, $onlyValid = null, $isMain = null,$start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        if($onlyValid){            
            $clause['melis_ecom_variant.var_status'] = (bool)$onlyValid;
        }
            
        if(!is_null($isMain)){            
            $clause['melis_ecom_variant.var_main_variant'] = $isMain;
        }
        $clause['melis_ecom_variant.var_id'] = $variantId;        
        
        if($clause){
            $select->where($clause);
        }
        
       if(!is_null($limit) && $limit != -1) {
           $select->limit($limit);
       }
        
        $select->offset($start);
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
        
    }
    
    public function getMainVariantById($productId, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $select ->join('melis_ecom_variant_attribute_value', 'melis_ecom_variant_attribute_value.vatv_variant_id = melis_ecom_variant.var_id', array('*'), $select::JOIN_LEFT)     
                ->join('melis_ecom_attribute_value', 'melis_ecom_attribute_value.atval_id = melis_ecom_variant_attribute_value.vatv_attribute_value_id', array('*'), $select::JOIN_LEFT)
                ->join('melis_ecom_attribute_trans', 'melis_ecom_attribute_trans.atrans_attribute_id = melis_ecom_attribute_value.atval_attribute_id', array('*'), $select::JOIN_LEFT);
    
        if(!is_null($productId))
            $clause['melis_ecom_variant.var_prd_id'] = (int) $productId;
                
        if(!is_null($langId))
            $clause['melis_ecom_attribute_trans.atrans_lang_id'] = (int) $langId;
        
        $clause['melis_ecom_variant.var_main_variant'] = 1;
        
        if($clause){
            $select->where($clause);
        }
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getVariantByProdId($productId, $onlyValid = true, $isMain = null,$start = 0, $limit = null, $search = '', $order = 'var_id')
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();

        $clause['melis_ecom_variant.var_prd_id'] = (int) $productId;
         
        
        if($onlyValid){            
            $clause['melis_ecom_variant.var_status'] = (bool)$onlyValid;
        }
        
        if($search){
            $search = '%'.$search.'%';
            $select->where->NEST->like('melis_ecom_variant.var_sku', $search)
                   ->or->like('melis_ecom_variant.var_id', $search);
        }
        
        if(!is_null($limit) && $limit != -1) {
           $select->limit($limit);
        }
        
        $select->order($order);
        $select->offset((int)$start);
       
        if($clause){
            $select->where($clause);
        }
//         $sql = $select->getSqlString();
//         echo $sql;die();
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
   
}