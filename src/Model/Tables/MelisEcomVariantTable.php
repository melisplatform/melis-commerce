<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class MelisEcomVariantTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    protected $_currentVarDataCount;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'var_id';
    }
    
    public function getVariantCommonAttr($productId, $attrId, $attrVal = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        
        $select->join('melis_ecom_variant_attribute_value', 'melis_ecom_variant_attribute_value.vatv_variant_id = melis_ecom_variant.var_id', array(('*')), $select::JOIN_LEFT);
        
        $select->where('melis_ecom_variant.var_prd_id ='.$productId);
        
        if (!empty($attrVal))
        {
            $select->where('melis_ecom_variant_attribute_value.vatv_attribute_value_id ='.$attrVal);
        }
        
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
        
    }
    
    public function getVariantsAttrGroupByAttr($variants)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array());
        
        $select->join('melis_ecom_variant_attribute_value', 'melis_ecom_variant_attribute_value.vatv_variant_id = melis_ecom_variant.var_id', array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_attribute_value', 'melis_ecom_attribute_value.atval_id = melis_ecom_variant_attribute_value.vatv_attribute_value_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_attribute', 'melis_ecom_attribute.attr_id = melis_ecom_attribute_value.atval_attribute_id', array('attr_id'), $select::JOIN_LEFT);
        
        $select->group('vatv_attribute_value_id');
        $select->where->in('melis_ecom_variant_attribute_value.vatv_variant_id', $variants);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
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
        
       if(!empty($start)){
            $select->offset($start);
       }
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
        
    }
    
    public function getMainVariantById($productId, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->quantifier('DISTINCT');
        $select->columns(array('*'));
        $clause = array();
        
        $select ->join('melis_ecom_variant_attribute_value', 'melis_ecom_variant_attribute_value.vatv_variant_id = melis_ecom_variant.var_id', array(), $select::JOIN_LEFT)     
                ->join('melis_ecom_attribute_value', 'melis_ecom_attribute_value.atval_id = melis_ecom_variant_attribute_value.vatv_attribute_value_id', array(), $select::JOIN_LEFT)
                ->join('melis_ecom_attribute_value_trans', 'melis_ecom_attribute_value_trans.av_attribute_value_id = melis_ecom_attribute_value.atval_id', array(), $select::JOIN_LEFT);
    
       
        $clause['melis_ecom_variant.var_prd_id'] = (int) $productId;
                
        if(!is_null($langId))
            $clause['melis_ecom_attribute_value_trans.avt_lang_id'] = (int) $langId;
        
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
        if(!empty($start)){
            $select->offset((int)$start);
        }
        if($clause){
            $select->where($clause);
        }
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }

    public function getAssocVariantsList($variantId = null, $searchValue = null, $start = 0, $limit = null, $column = null, $order = 'ASC')
    {
        $select = $this->tableGateway->getSql()->select();
        $select->quantifier('DISTINCT');
        
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_variant.var_prd_id', array(), $select::JOIN_LEFT)
                ->join('melis_ecom_product', 'melis_ecom_product.prd_id = melis_ecom_variant.var_prd_id', array(), $select::JOIN_LEFT);


        if(!is_null($searchValue)) {
            $search = '%'.$searchValue.'%';
            $select->where->like('melis_ecom_variant.var_id', $search)
                ->or->like('melis_ecom_variant.var_sku', $search)
                ->or->like('melis_ecom_product_text.ptxt_field_short', $search)
                ->or->like('melis_ecom_product.prd_reference', $search);
        }

        if (!is_null($column)) {
            $select->order($column . ' ' . $order);
        }

        $getCount = $this->tableGateway->selectWith($select);
        $this->setVarCurrentDataCount((int) $getCount->count());

        if(!is_null($limit)) {
            $select->limit( (int) $limit);
        }

        if(!is_null($start)) {
            $select->offset( (int) $start);
        }

        $select->group('melis_ecom_variant.var_id');


        //echo $this->getRawSql($select);

        $resultData = $this->tableGateway->selectWith($select);

        return $resultData;
    }

    public function getAssocVariantsListById($varId = null, $searchValue = null, $start = 0, $limit = null, $column = null, $order = 'ASC')
    {
        $select = $this->tableGateway->getSql()->select();
        $select->quantifier('DISTINCT');

        $select->join('melis_ecom_assoc_variant', 'melis_ecom_assoc_variant.avar_one = melis_ecom_variant.var_id',
                array('avar_id_1' => 'avar_id', 'avar_one_1' => 'avar_one', 'avar_two_1' => 'avar_two', 'avar_type_id_1' => 'avar_type_id'), $select::JOIN_LEFT)
            ->join(array('assoc_variant_two' => 'melis_ecom_assoc_variant'), 'assoc_variant_two.avar_two = melis_ecom_variant.var_id',
                array('avar_id_2' => 'avar_id', 'avar_one_2' => 'avar_one', 'avar_two_2' => 'avar_two', 'avar_type_id_2' => 'avar_type_id'), $select::JOIN_LEFT)
            ->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_variant.var_prd_id', array(), $select::JOIN_LEFT)
            ->join('melis_ecom_product', 'melis_ecom_product.prd_id = melis_ecom_variant.var_prd_id', array(), $select::JOIN_LEFT);


        $select->where
                ->nest->equalTo('melis_ecom_assoc_variant.avar_one', (int) $varId)->or->equalTo('melis_ecom_assoc_variant.avar_two', (int) $varId)->unnest
                ->or
                ->nest->equalTo('assoc_variant_two.avar_one', (int) $varId)->or->equalTo('assoc_variant_two.avar_two', (int) $varId)->unnest;

        if(!is_null($searchValue)) {
            $search = '%'.$searchValue.'%';
            $select->where->and->nest->like('melis_ecom_variant.var_id', $search)->or->like('melis_ecom_variant.var_sku', $search)
                                    ->or->like('melis_ecom_product_text.ptxt_field_short', $search)
                                    ->or->like('melis_ecom_product.prd_reference', $search)
                                    ->unnest;
        }

        if (!is_null($column)) {
            $select->order($column . ' ' . $order);
        }

        $getCount = $this->tableGateway->selectWith($select);
        $this->setVarCurrentDataCount((int) $getCount->count());

        if(!is_null($limit)) {
            $select->limit( (int) $limit);
        }

        if(!is_null($start)) {
            $select->offset( (int) $start);
        }

        $select->group('melis_ecom_variant.var_id');

        $resultData = $this->tableGateway->selectWith($select);

        return $resultData;
    }
    
    public function getAssocVariantsByProductId($productId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array());
        $select->quantifier('DISTINCT');
        
        $select->join('melis_ecom_assoc_variant', 'melis_ecom_assoc_variant.avar_one = melis_ecom_variant.var_id', array('var_id' => 'avar_two'), $select::JOIN_LEFT);
        $select->where->equalTo('melis_ecom_variant.var_prd_id', $productId);
        
        return $this->tableGateway->selectWith($select);
    }
    
    public function getProductAssoc($productId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array());
        $select->quantifier('DISTINCT'); 
        $select->join('melis_ecom_assoc_variant', 'melis_ecom_assoc_variant.avar_one = melis_ecom_variant.var_id', array())
        ->join(array('assoc_variant' => 'melis_ecom_variant'), 'assoc_variant.var_id = melis_ecom_assoc_variant.avar_two', array(), $select::JOIN_LEFT)
        ->join(array('assoc_product' => 'melis_ecom_product'), 'assoc_product.prd_id = assoc_variant.var_prd_id', array('assoc_prd_id' => 'prd_id'), $select::JOIN_LEFT);
        $select->where->equalTo('melis_ecom_variant.var_prd_id', $productId);
        
        return $this->tableGateway->selectWith($select);
    }
    
    public function getVarTotalFiltered()
    {
        return $this->_currentVarDataCount;
    }
    
    public function getVariantAndSeoById($variantId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_seo', 'melis_ecom_seo.eseo_variant_id = melis_ecom_variant.var_id', array('*'), $select::JOIN_LEFT);
        
        $select->where->equalTo('melis_ecom_variant.var_id', $variantId);
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    public function getProductVariants($productId, $onlyValid = false)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('*'));
        
        if ($onlyValid)
            $select->where('var_status = 1');
        
        $select->where('var_prd_id = '.$productId);
        
        $select->group($this->idField);
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    protected function setVarCurrentDataCount($dataCount)
    {
        $this->_currentVarDataCount = $dataCount;
    }

    public function getVaraintsFullDetails($selectCols = array(), $whereClause = array(), $start = null, $limit = null, $sortOrder = array(), $onlyValid = true)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!empty($selectCols)){
            $select->columns($selectCols);
        }

        $select->join('melis_ecom_product', 'melis_ecom_product.prd_id = melis_ecom_variant.var_prd_id', array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_variant.var_prd_id', array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_price', 'melis_ecom_price.price_var_id = melis_ecom_variant.var_id', array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency', array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_variant_stock', 'melis_ecom_variant_stock.stock_var_id = melis_ecom_variant.var_id', array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_category', 'melis_ecom_category.cat_id = melis_ecom_product_category.pcat_cat_id', array('*'), $select::JOIN_LEFT);
        // Join using relation with price table
        $select->join('melis_ecom_country', 'melis_ecom_country.ctry_id = melis_ecom_price.price_country_id', array('*'), $select::JOIN_LEFT);

        if ($onlyValid){
            $select->where('melis_ecom_variant.var_status = 1');
            $select->where('melis_ecom_product.prd_status = 1');
            $select->where('melis_ecom_category.cat_status = 1');
            $select->where('melis_ecom_country.ctry_status = 1');
        }

        if (!empty($whereClause)){
            $select->where($whereClause);
        }

        if (!is_null($start)){
            $select->offset((int) $start);
        }

        if (!is_null($limit)){
            $select->limit((int) $limit);
        }

        if (!empty($sortOrder)){
            $select->order($sortOrder);
        }

        $select->group('var_id');

        $resultData = $this->tableGateway->selectWith($select);

        return $resultData;
    }

}