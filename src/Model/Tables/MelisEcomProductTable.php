<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use MelisCommerce\Model\Tables\MelisEcomGenericTable;
use Zend\Db\TableGateway\TableGateway;

class MelisEcomProductTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'prd_id';
    }
    
    public function getProduct($productId = null, $onlyValid = null, $start = 0, $limit = null, $order = 'ASC')
    {

        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();

        // parameter validations
        if(!is_null($productId))
            $clause['melis_ecom_product.prd_id'] = (int) $productId;

        if(!is_null($onlyValid))
            $clause['melis_ecom_product.prd_status'] = $onlyValid;
            
            
       if($clause) { 
           $select->where($clause);
       }
             
       if(!is_null($limit) && $limit != -1) {
           $select->limit($limit);
       }

       $select->order('prd_id ' . $order);
       $select->offset($start);
        
        $resultSet = $this->tableGateway->selectwith($select);

        return $resultSet;
    }
    
    public function getProductCategory($productId, $categoryId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $clause = array();
        
        $select->join(array('product_category' => 'melis_ecom_product_category'), 'product_category.pcat_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join(array('category' => 'melis_ecom_category'), 'category.cat_id = product_category.pcat_cat_id', array('*'), $select::JOIN_LEFT);

        $clause['melis_ecom_product.prd_id'] = (int) $productId;
        
        if($categoryId) {
            $clause['product_category.pcat_cat_id'] = (int) $categoryId;
        }
        
        $select->where($clause);
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;

    }
    
    public function getProductText($productId, $langId = null, $productTextCode = null) 
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $select->join(array('product_text' => 'melis_ecom_product_text'), 'product_text.ptxt_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        $select->join(array('product_text_type' => 'melis_ecom_product_text_type'), 'product_text_type.ptt_id = product_text.ptxt_type', array('*'), $select::JOIN_LEFT);
        
        $caluse['melis_ecom_product.prd_id'] =  (int) $productId;
        
        if($langId) {
            $caluse['product_text.ptxt_lang_id'] =  (int) $langId;
        }
        
        if($productTextCode) {
            $caluse['product_text_type.ptt_code'] =  $productTextCode;
        }

        $select->where($caluse);

        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getProductPrice($productId, $countryId = null) 
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        $select->join(array('product_price' => 'melis_ecom_price'), 'product_price.price_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        
        $clause['melis_ecom_product.prd_id'] = (int) $productId;
        
        if($countryId) {
            $clause['product_price.price_country_id'] = (int) $countryId;
        }
        
        $select->where($clause);
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    
    public function getProductAttributes($productId, $langId = null, $start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $select->join('melis_ecom_product_attribute', 'melis_ecom_product_attribute.patt_product_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_attribute', 'melis_ecom_attribute.attr_id = melis_ecom_product_attribute.patt_attribute_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_attribute_trans', 'melis_ecom_attribute_trans.atrans_attribute_id = melis_ecom_attribute.attr_id', array('*'), $select::JOIN_LEFT);
        
        $clause['melis_ecom_product_attribute.patt_product_id'] = (int) $productId;

        if(!is_null($langId)) {
            $clause['melis_ecom_attribute_trans.atrans_lang_id'] = (int) $langId;
        }
         
        $select->where($clause);
        
        if(!is_null($limit)) {
            $select->limit($limit);
        }
        
        $select->offset($start);
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getProductByName($product, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        $select->join(array('product_text' => 'melis_ecom_product_text'), 'product_text.ptxt_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        $select->join(array('product_text_type' => 'melis_ecom_product_text_type'), 'product_text_type.ptt_id = product_text.ptxt_type', array('*'), $select::JOIN_LEFT);
        $select->where->like('melis_ecom_product.prd_reference', $product)
               ->or->NEST->like('product_text.ptxt_field_short', $product)
                   ->and->equalTo('product_text.ptxt_lang_id', $langId)
                   ->and->equalTo('product_text_type.ptt_code', 'TITLE');
        
        $select->where($clause);
//         echo $select->getSqlString();die();
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }

}