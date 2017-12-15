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
use Zend\Db\Sql\Expression;

class MelisEcomProductTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    protected $_currentProdDataCount;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'prd_id';
    }
    
    public function getProduct($productId, $categoriId = array(), $onlyValid = null, 
                        $start = 0, $limit = null, $order = 'ASC', $column = 'prd_id')
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        
        $select->join('melis_ecom_price', 'melis_ecom_price.price_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        
        $clause = array();
        
        // parameter validations
        $clause['melis_ecom_product.prd_id'] = (int) $productId;
        
        if(!is_null($onlyValid))
            $clause['melis_ecom_product.prd_status'] = $onlyValid;
            
        if($clause) { 
            $select->where($clause);
        }
             
        if(!is_null($limit) && $limit != -1) {
            $select->limit($limit);
        }
        
        if(!empty($start)){
            $select->offset($start);
        }
        
        if (!empty($categoriId)){
            $select->where->in('melis_ecom_product_category.pcat_cat_id', $categoriId);
        }
       
        $select->order($column .' '. $order);
        
        $select->group($this->idField);
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getProductList($categoryIds = array(), $countryId = null, $onlyValid = null,  $start = null, $limit = null, $orderColumn = 'prd_id', $order = 'ASC', $search = '')
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->quantifier('DISTINCT');
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_price', 'melis_ecom_price.price_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
       
        if(is_array($categoryIds) && !empty($categoryIds)) {
            $select->where->and->in('melis_ecom_product_category.pcat_cat_id', $categoryIds);
        }

        if(!is_null($countryId)) {
            $select->where('melis_ecom_price.price_country_id = '.$countryId);
        }
        
        if (!is_null($onlyValid))
        {
            $select->where('melis_ecom_product.prd_status = 1');
        }
        
        if(!is_null($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('melis_ecom_product.prd_id', $search)
            ->or->like('melis_ecom_product.prd_reference', $search)
            ->or->like('melis_ecom_product_text.ptxt_field_short', $search)
            ->or->like('melis_ecom_variant.var_sku', $search);
        }
        
        if (!is_null($start))
        {
            $select->offset($start);
        }
        
        if (!is_null($limit) && $limit != -1)
        {
            $select->limit((int) $limit);
        }
        
        $select->order($orderColumn .' '. $order);
        
//         echo $this->getRawSql($select);

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
        $select->columns(array());
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
        $select->columns(array());
        $clause = array();
        $select->join(array('product_price' => 'melis_ecom_price'), 'product_price.price_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = product_price.price_currency', array('cur_symbol','cur_code'), $select::JOIN_LEFT);
        
        $clause['melis_ecom_product.prd_id'] = (int) $productId;
        
        if(!is_null($countryId)) {
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
        
        if(!empty($start)){
        $select->offset($start);
        }        
        
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
        
        if(!empty($start)){
            $select->offset($start);
        }
        $select->group('melis_ecom_product.prd_id');
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        //echo $this->getRawSql($select);
        
        return $resultSet;
    }
    
    public function getProductCategoryByProductIdAndCategoryId($productId, $categoryIds = array(), $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array());
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT)
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
        $select->columns(array());
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
    
    public function getProductByAttributeValueIdsAndPriceRange($attrValIds = array(), $minPrice = null , $maxPrice = null, $categoryIds = array(), $countryId = null, $status = 1, $start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_variant_attribute_value', 'melis_ecom_variant_attribute_value.vatv_variant_id = melis_ecom_variant.var_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_attribute_value', 'melis_ecom_attribute_value.atval_id = melis_ecom_variant_attribute_value.vatv_attribute_value_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_price', 'melis_ecom_price.price_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        
        if(is_array($attrValIds) && !empty($attrValIds)){
            $select->where->in('melis_ecom_attribute_value.atval_id', $attrValIds);
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
    
    public function getProductByNameTextTypeAttrIdsAndPrice($productSearchKey = null, $textTypeCode = array(), $attrValIds = array(), $categoryIds = array(), $minPrice, $maxPrice, $langId = null, $countryId = null, $status = 1, $start = 0, $limit = null, $order = null, $priceColumn = null)
    {
        $variants = array();
        if(is_array($attrValIds) && !empty($attrValIds)) {
            
            $attrSelect = new \Zend\Db\Sql\Select;
            $attrSelect->columns(array());
            $attrSelect->from('melis_ecom_attribute_value');
            $attrSelect->join('melis_ecom_variant_attribute_value','atval_id = vatv_attribute_value_id', array('vatv_variant_id'));
            $attrSelect->where->in('atval_id', $attrValIds);
            $attrSelect->group('vatv_variant_id');
            $attrResult = $this->tableGateway->selectwith($attrSelect);
            
            foreach ($attrResult As $val){
                array_push($variants, $val->vatv_variant_id);
            }
        }

        if(empty($priceColumn)){
            $priceColumn = 'price_net';
        }
        
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array(
            'prd_id',
            'price' => new \Zend\Db\Sql\Expression('COALESCE(prod_price.'.$priceColumn.', var_price.'.$priceColumn.')'),
            'country' => new \Zend\Db\Sql\Expression('COALESCE(prod_price.price_country_id, var_price.price_country_id)')
        ));
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT)
        
        ->join('melis_ecom_product_text_type', 'melis_ecom_product_text_type.ptt_id = melis_ecom_product_text.ptxt_type', array(), $select::JOIN_LEFT)
        
        ->join(array('prod_price'=>'melis_ecom_price'), 'prod_price.price_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT)
        
        ->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT)
        
        ->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);

        $select->join(array('var_price'=>'melis_ecom_price'), 'var_price.price_var_id = melis_ecom_variant.var_id', array(), $select::JOIN_LEFT);
        
        if(!empty($productSearchKey)){
            if(is_array($textTypeCode) && !empty($textTypeCode)) {
                
                $txtTypeSelect = new \Zend\Db\Sql\Select;
                $txtTypeSelect->columns(array('ptt_field_type'));
                $txtTypeSelect->from('melis_ecom_product_text_type');
                $txtTypeSelect->where->in('ptt_code', $textTypeCode);
                $txtTypeSelect->group('ptt_field_type');
                $txtTypeResult = $this->tableGateway->selectwith($txtTypeSelect);
                $txtTypeIds = array();
                foreach ($txtTypeResult As $val){
                    array_push($txtTypeIds, $val->ptt_field_type);
                }
                
                if (in_array('1', $txtTypeIds) && in_array('2', $txtTypeIds)){
                    $select->where->NEST->like('melis_ecom_product.prd_reference', '%'. $productSearchKey. '%')
                        ->or->like('melis_ecom_product_text.ptxt_field_short', '%'. $productSearchKey. '%')
                        ->or->like('melis_ecom_product_text.ptxt_field_long', '%'. $productSearchKey. '%')->UNNEST
                        ->and->in('melis_ecom_product_text_type.ptt_code', $textTypeCode);
                }elseif (in_array('1', $txtTypeIds)){
                    $select->where->NEST->like('melis_ecom_product.prd_reference', '%'. $productSearchKey. '%')
                        ->or->like('melis_ecom_product_text.ptxt_field_short', '%'. $productSearchKey. '%')->UNNEST
                        ->and->in('melis_ecom_product_text_type.ptt_code', $textTypeCode);
                }elseif (in_array('2', $txtTypeIds)){
                    $select->where->NEST->like('melis_ecom_product.prd_reference', '%'. $productSearchKey. '%')
                        ->or->like('melis_ecom_product_text.ptxt_field_long', '%'. $productSearchKey. '%')->UNNEST
                        ->and->in('melis_ecom_product_text_type.ptt_code', $textTypeCode);
                }
                
            }else{
                $select->where->like('melis_ecom_product.prd_reference', '%'. $productSearchKey. '%');
            }
        }
        
        if(!is_null($langId)) {
            $select->where->and->equalTo('melis_ecom_product_text.ptxt_lang_id', $langId);
        }
        
        if(!empty($variants)){
            $select->where->in('melis_ecom_variant.var_id', $variants);
        }

        if(!is_null($countryId)) {
            $select->where->and->equalTo(new \Zend\Db\Sql\Expression('COALESCE(prod_price.price_country_id, var_price.price_country_id)'), $countryId);
        }
        
        if(is_array($categoryIds) && !empty($categoryIds)) {
            $select->where->and->in('melis_ecom_product_category.pcat_cat_id', $categoryIds);
        }
        
        if($maxPrice) {
            $select->where->NEST->and
            ->between('prod_price.'.$priceColumn, (float) $minPrice, (float) $maxPrice)
            ->or->between('var_price.'.$priceColumn, (float) $minPrice, (float) $maxPrice);
        }
        
        if(!is_null($order)){
            $select->order($order);
        }        
        
        if(!is_null($limit)) {
            $select->limit((int)$limit);
        }
        
        if(!is_null($start)) {
            $select->offset((int)$start);
        }
        
        if ($status) {
            $select->where('prd_status = 1');
        }
       
        $resultSet = $this->tableGateway->selectwith($select);

        return $resultSet;
    }
    
    public function productFullSearch($searchKey = ''){
    
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array(
            '*',
//             'price' => new \Zend\Db\Sql\Expression('COALESCE(product_price.price_net, variant_price.price_net)'),
//             'price' => new \Zend\Db\Sql\Expression('MIN(variant_price.price_net)'),
            
        ));
        
        $join = new Expression('melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id)');
        
        $select->join('melis_ecom_variant', $join, array(), $select::JOIN_LEFT);
        
        $select->join(array('product_price' => 'melis_ecom_price'), 'product_price.price_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        
        $select->join(array('variant_price' => 'melis_ecom_price'), 'variant_price.price_var_id = melis_ecom_variant.var_id', array('tests' => new Expression('MIN(variant_price.price_net)')), $select::JOIN_LEFT);
        
        $select->where->like('melis_ecom_product.prd_reference', '%'. $searchKey. '%');
        
//         $select->order('variant_price.price_net ASC');
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    public function getProductVariantPriceById($productId, $order = 'ASC', $priceColumn = 'price_net')
    {
        $select = $this->tableGateway->getSql()->select();
       
        $select->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
                ->join('melis_ecom_price', 'melis_ecom_price.price_var_id = melis_ecom_variant.var_id', array('*'), $select::JOIN_LEFT)
                ->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency', array('*'), $select::JOIN_LEFT);
        
        $select->where->equalTo('prd_id', $productId);
        $select->order($priceColumn . " $order");
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
        
    }
    
    public function getProductTitleAndSeoById($productId, $langId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_text_type', 'melis_ecom_product_text.ptxt_type = melis_ecom_product_text_type.ptt_id', array(), $select::JOIN_LEFT)
        ->join('melis_ecom_seo', 'melis_ecom_seo.eseo_product_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        
        $select->where->NEST->equalTo('melis_ecom_product_text_type.ptt_code', 'TITLE')
        ->or->isNull('melis_ecom_product_text_type.ptt_code')->UNNEST;
        
        $select->where->NEST->equalTo('melis_ecom_product_text.ptxt_lang_id', $langId)
        ->or->isNull('melis_ecom_product_text.ptxt_lang_id')->UNNEST;

        $select->where->equalTo('melis_ecom_product.prd_id', $productId);
        
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
    
    public function getProductByVariantId($variantId)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        
        $select->where->equalTo('melis_ecom_variant.var_id', $variantId);
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    public function getProductCategoryByProductId($productId, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array());
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_category', 'melis_ecom_category.cat_id = melis_ecom_product_category.pcat_cat_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_category_trans', 'melis_ecom_category_trans.catt_category_id = melis_ecom_category.cat_id', array('*'), $select::JOIN_LEFT);
        $select->where->equalTo('melis_ecom_product_category.pcat_prd_id', $productId);
    
        if(!is_null($langId)) {
            $select->where->and->equalTo('melis_ecom_category_trans.catt_lang_id', (int) $langId);
        }
        
        $select->group('melis_ecom_product_category.pcat_id');
        $select->order('melis_ecom_category.cat_order ASC');
        $resultSet = $this->tableGateway->selectwith($select);
    
        return $resultSet;
    }
    
    public function getProductCategoryPriceByProductId($productId = null, $categoryIds = array(), $langId, $countryId = null, $fieldsTypeCodes = array('TITLE'), $documents = array('mainImage' => 'DEFAULT'))
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT)
        ->join('melis_ecom_category', 'melis_ecom_category.cat_id = melis_ecom_product_category.pcat_cat_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_category_trans', 'melis_ecom_category_trans.catt_category_id = melis_ecom_category.cat_id', array('catt_name'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_text_type', 'melis_ecom_product_text_type.ptt_id = melis_ecom_product_text.ptxt_type', array(), $select::JOIN_LEFT)
        ->join('melis_ecom_price', 'melis_ecom_price.price_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency', array('cur_symbol'), $select::JOIN_LEFT);
        
        if (is_array($documents)){
            foreach($documents as $key => $value){
                $docTypeSelect = new \Zend\Db\Sql\Select;
                $docTypeSelect->columns(array('rdoc_product_id'));
                 
                $docTypeSelect->from('melis_ecom_doc_relations');
            
                $docTypeSelect->join(array($key.'_melis_ecom_document' => 'melis_ecom_document'), $key.'_melis_ecom_document.doc_id = melis_ecom_doc_relations.rdoc_doc_id', array($key=>'doc_path'), $select::JOIN_LEFT)
                ->join(array($key.'_melis_ecom_doc_type' => 'melis_ecom_doc_type'), $key.'_melis_ecom_doc_type.dtype_id = '.$key.'_melis_ecom_document.doc_subtype_id', array(), $select::JOIN_LEFT);
            
                $docTypeSelect->where->equalTo($key.'_melis_ecom_doc_type.dtype_code', $value);
            
                $docTypeSelect->group('rdoc_product_id');
            
                $tmp = new \Zend\Db\Sql\Expression('(' .$this->getRawSql($docTypeSelect).')');
                $select->join(array($key => $tmp), $key.'.rdoc_product_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT );
            
            }
        }
        
        if(!is_null($productId)){
            $select->where->equalTo('melis_ecom_product.prd_id', $productId);
        }
            
        if(!empty($categoryIds)){
            $select->where->in('melis_ecom_category.cat_id', $categoryIds);
        }
        
        $select->where->equalTo('melis_ecom_category_trans.catt_lang_id', $langId);
        
        $select->where->equalTo('melis_ecom_product_text.ptxt_lang_id', $langId);
        
        if(!empty($fieldsTypeCodes)){
            $select->where->in('melis_ecom_product_text_type.ptt_code', $fieldsTypeCodes);
        }
        
        if(!is_null($countryId)){
            $select->where->equalTo('melis_ecom_price.price_country_id', $countryId);
        }
        
        if(is_null($productId)){
            $select->order('pcat_order ASC');
        }
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    protected function setProdCurrentDataCount($dataCount)
    {
        $this->_currentProdDataCount = $dataCount;
    }
    

    public function getCouponProductList($couponId, $assigned = null, $start = null, $limit = null, $order = null, $search = null)
    {
        $select= $this->tableGateway->getSql()->select();
        $clause = array();
    
        $select->quantifier('DISTINCT');
        $select->join('melis_ecom_coupon_product', 'melis_ecom_coupon_product.cprod_product_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        
        if(!is_null($assigned)){
            $select->where->equalTo('melis_ecom_coupon_product.cprod_coupon_id', $couponId);
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
        
        if (!is_null($order))
        {
            $select->order($order);
        }
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
        
    }
    
    public function getProductsByCategoryId($categoryId, $onlyValid = false, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        if (!is_null($langId))
            $join = new Expression('melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.'.$this->idField.' AND ptxt_lang_id ='.$langId.' AND ptxt_field_short != ""');
        else
            $join = new Expression('melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.'.$this->idField.' AND ptxt_field_short IS NOT NULL AND ptxt_field_short != ""');
            
        $select->join('melis_ecom_product_text', $join, array('*'), $select::JOIN_LEFT)
            ->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array('pcat_prd_id'), $select::JOIN_LEFT);
            $select->where->equalTo('melis_ecom_product_category.pcat_cat_id', $categoryId);
        
        if ($onlyValid)
            $select->where('prd_status = 1');
        
        $select->group($this->idField);
            
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
}