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
use Zend\Db\Sql\Predicate\In;
class MelisEcomProductTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'prd_id';
    }
    
    public function getProduct($productId = null, $onlyValid = null, $start = 0, $limit = null, $order = 'ASC', $column = 'prd_id')
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

       $select->order($column .' '. $order);
       $select->offset($start);
        
        $resultSet = $this->tableGateway->selectwith($select);
        //echo $this->getRawSql($select);
        return $resultSet;
    }
    
    public function getProductList($langId = null, $categoryId, 
                        $onlyValid = null,  $start = null, $limit = null, $order, $search = '')
    {
        $select = $this->tableGateway->getSql()->select();
        $clause = array();
        $select->quantifier('DISTINCT');
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        
        if (!is_null($categoryId))
        {
            $select->where('melis_ecom_product_category.pcat_cat_id ='.$categoryId);
        }
        
        if (!is_null($onlyValid))
        {
            $select->where('melis_ecom_product.prd_status = 1');
        }
        
        if(!is_null($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('melis_ecom_product.prd_id', $search)
            ->or->like('melis_ecom_product.prd_reference', $search)
            ->or->like('melis_ecom_product_text.ptxt_field_short', $search);
        }
        
        if (!is_null($start))
        {
            $select->offset($start);
        }
        
        if (!is_null($limit)&&$limit!=-1)
        {
            $select->limit($limit);
        }
        
        $select->order('prd_id '.$order);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
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
    
    public function getProductByName($productName, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        $select->join(array('product_text' => 'melis_ecom_product_text'), 'product_text.ptxt_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        $select->join(array('product_text_type' => 'melis_ecom_product_text_type'), 'product_text_type.ptt_id = product_text.ptxt_type', array('*'), $select::JOIN_LEFT);
        $select->where->NEST->like('melis_ecom_product.prd_reference', $productName)
               ->or->like('product_text.ptxt_field_short', $productName)->UNNEST
                   ->and->equalTo('product_text.ptxt_lang_id', $langId)
                   ->and->equalTo('product_text_type.ptt_code', 'TITLE');
        
        $select->where($clause);

        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
    
    
    public function getProductByTextAndType($productText, $productType = array('TITLE'), $categoryIds = array(), $langId = null, $status = 1, $start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_text_type', 'melis_ecom_product_text_type.ptt_id = melis_ecom_product_text.ptxt_type', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        
        $select->where->nest->like('melis_ecom_product_text.ptxt_field_short', '%'. $productText . '%')
        ->or->like('melis_ecom_product_text.ptxt_field_long', '%'.$productText.'%')->UNNEST;
        
        $select->where->and->in('melis_ecom_product_text_type.ptt_code', $productType);
        
        if(is_array($categoryIds) && !empty($categoryIds)) {
            $select->where->and->in('melis_ecom_product_category.pcat_cat_id', $categoryIds);
        }
        
        if(!is_null($langId)) {
            $select->where->and->equalTo('melis_ecom_product_text.ptxt_lang_id', $langId);
        }
        

        //$select->where->and->equalTo('melis_ecom_product.prd_status', (int) $status);


        
        
        if(!is_null($limit)) {
            $select->limit($limit);
        }
        
        $select->order('melis_ecom_product.prd_id ASC');
        
        $select->offset($start);
        $select->group('melis_ecom_product.prd_id');
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        //echo $this->getRawSql($select);
        
        return $resultSet;
    }
    
    public function getProductCategoryByProductIdAndCategoryId($productId, $categoryIds = array(), $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_category', 'melis_ecom_category.cat_id = melis_ecom_product_category.pcat_cat_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_category_trans', 'melis_ecom_category_trans.catt_category_id = melis_ecom_category.cat_id', array('*'), $select::JOIN_LEFT);
        $select->where->equalTo('melis_ecom_product_category.pcat_prd_id', $productId);
        
        if(is_array($categoryIds) && !empty($categoryIds)) {
            $select->where->and->in('melis_ecom_product_category.pcat_cat_id', $categoryIds);
        }
        
        if(!is_null($langId)) {
            $select->where->and->equalTo('melis_ecom_category_trans.catt_lang_id', (int) $langId);
        }

        $select->order('melis_ecom_category.cat_order ASC');
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getProductDocumentByProductIdAndCountryId($productId, $countryId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('melis_ecom_doc_relations', 'melis_ecom_doc_relations.rdoc_product_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_document', 'melis_ecom_document.doc_id = melis_ecom_doc_relations.rdoc_doc_id', array('*'), $select::JOIN_LEFT);
        
        if(!is_null($countryId)) {
            $select->where->equalTo('melis_ecom_doc_relations.rdoc_product_id', $productId)
            ->and->equalTo('melis_ecom_doc_relations.rdoc_country_id', $countryId);
        }else {
            $select->where->equalTo('melis_ecom_doc_relations.rdoc_product_id', $productId);
        }
        
        $select->order('melis_ecom_product.prd_id ASC');
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
        
    }
    
    public function getProductByAttributeValueIdsAndPriceRange($attrValIds, $minPrice , $maxPrice, $categoryIds = array(), $countryId = null, $status = 1, $start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_variant_attribute_value', 'melis_ecom_variant_attribute_value.vatv_variant_id = melis_ecom_variant.var_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_attribute_value', 'melis_ecom_attribute_value.atval_id = melis_ecom_variant_attribute_value.vatv_attribute_value_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_price', 'melis_ecom_price.price_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        
        
        $select->where->in('melis_ecom_attribute_value.atval_id', $attrValIds);
        
        if(!is_null($countryId)) {
            $select->where->and->equalTo('melis_ecom_price.price_country_id', $countryId);
        }

        if(is_array($categoryIds) && !empty($categoryIds)) {
            $select->where->and->in('melis_ecom_product_category.pcat_cat_id', $categoryIds);
        }
        
        if($maxPrice) {
            $select->where->and->between('melis_ecom_price.price_net', (float) $minPrice, (float) $maxPrice);
        }
        
        //$select->where->and->equalTo('melis_ecom_product.prd_status', $status);
        $select->order('melis_ecom_product.prd_id ASC');
        
        
        if(!is_null($limit)) {
            $select->limit($limit);
        }
        
        if(!is_null($start)) {
            $select->offset($start);
        }
        
        $select->group('melis_ecom_product.prd_id');
        

        
        $resultSet = $this->tableGateway->selectwith($select);
        //echo $this->getRawSql($select);
        
        return $resultSet;
    }
    
    public function getProductByNameTextTypeAttrIdsAndPrice($productText, $textType = array('TITLE'), $attrValIds = array(), $categoryIds = array(), $minPrice, $maxPrice, $langId = null, $countryId = null, $status = 1, $start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_text_type', 'melis_ecom_product_text_type.ptt_id = melis_ecom_product_text.ptxt_type', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_price', 'melis_ecom_price.price_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_variant_attribute_value', 'melis_ecom_variant_attribute_value.vatv_variant_id = melis_ecom_variant.var_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_attribute_value', 'melis_ecom_attribute_value.atval_id = melis_ecom_variant_attribute_value.vatv_attribute_value_id', array('*'), $select::JOIN_LEFT);

        
        $select->where->nest->like('melis_ecom_product_text.ptxt_field_short', '%'. $productText. '%')->or->like('melis_ecom_product_text.ptxt_field_long', '%'. $productText . '%')->UNNEST;
        $select->where->and->in('melis_ecom_product_text_type.ptt_code', $textType);
        if(!is_null($langId)) {
            $select->where->and->equalTo('melis_ecom_product_text.ptxt_lang_id', $langId);
        }
        if(!is_null($attrValIds)) {
            $select->where->and->in('melis_ecom_attribute_value.atval_id', $attrValIds);
        }
        
        if(!is_null($countryId)) {
            $select->where->and->equalTo('melis_ecom_price.price_country_id', $countryId);
        }
        
        if(is_array($categoryIds) && !empty($categoryIds)) {
            $select->where->and->in('melis_ecom_product_category.pcat_cat_id', $categoryIds);
        }
        
        if($maxPrice) {
            $select->where->and->between('melis_ecom_price.price_net', (float) $minPrice, (float) $maxPrice);
        }
        //$select->where->and->equalTo('melis_ecom_product.prd_status', $status);
        
        
        $select->order('melis_ecom_product.prd_id ASC');
        
        if(!is_null($limit)) {
            $select->limit($limit);
        }
        
        if(!is_null($start)) {
            $select->offset($start);
        }
        
        $select->group('melis_ecom_product.prd_id');
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        //echo $this->getRawSql($select);

        return $resultSet;
    }

}