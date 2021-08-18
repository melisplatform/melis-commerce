<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use MelisCommerce\Model\Tables\MelisEcomGenericTable;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Predicate\In;
use Laminas\Db\Sql\Expression;

class MelisEcomProductTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_product';

    protected $_currentProdDataCount;

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'prd_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getProduct($productId, $categoriId = array(), $onlyValid = null, 
                        $start = 0, $limit = null, $order = 'ASC', $column = 'prd_id')
    {
        $select = $this->getTableGateway()->getSql()->select();
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
        
        $resultSet = $this->getTableGateway()->selectwith($select);

        return $resultSet;
    }
    
    public function getProductList($categoryIds = array(), $countryId = null, $onlyValid = null,  $start = null, $limit = null, $orderColumn = 'prd_id', $order = 'ASC', $search = '', $count = false)
    {
        $select = $this->getTableGateway()->getSql()->select();

        if ($count)
            $select->columns(array('count'=> new Expression('COUNT(DISTINCT prd_id)')));
        
        $select->quantifier('DISTINCT');
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_category_trans', 'melis_ecom_category_trans.catt_category_id = melis_ecom_product_category.pcat_cat_id', [], $select::JOIN_LEFT);
        $select->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_price', 'melis_ecom_price.price_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        //include product variant
        $select->join(array("var_price" => "melis_ecom_price"), 'var_price.price_var_id = melis_ecom_variant.var_id', array(), $select::JOIN_LEFT);
    
        if(is_array($categoryIds) && !empty($categoryIds)) {
            $select->where->and->in('melis_ecom_product_category.pcat_cat_id', $categoryIds);
        }

        if(!empty($countryId)) {
            $select->where->NEST->equalTo('melis_ecom_price.price_country_id', $countryId)
            ->or->equalTo('var_price.price_country_id', $countryId);
        }
        
        if (!empty($onlyValid))
        {
            $select->where('melis_ecom_product.prd_status = 1');
        }
        
        if(!empty($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('melis_ecom_product.prd_id', $search)
            ->or->like('melis_ecom_product.prd_reference', $search)
            ->or->like('melis_ecom_product_text.ptxt_field_short', $search)
            ->or->like('melis_ecom_variant.var_sku', $search)
            ->or->like('melis_ecom_category_trans.catt_name', $search);
        }
        
        if (!empty($start))
        {
            $select->offset($start);
        }
        
        if (!empty($limit) && $limit != -1)
        {
            $select->limit((int) $limit);
        }

        if (!$count)
            $select->order($orderColumn .' '. $order);

        $resultData = $this->getTableGateway()->selectWith($select);

        return $resultData;
    }
    
    public function getProductCategory($productId, $categoryId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $clause = array();
        
        $select->join(array('product_category' => 'melis_ecom_product_category'), 'product_category.pcat_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join(array('category' => 'melis_ecom_category'), 'category.cat_id = product_category.pcat_cat_id', array('*'), $select::JOIN_LEFT);

        $clause['melis_ecom_product.prd_id'] = (int) $productId;
        
        if($categoryId) {
            $clause['product_category.pcat_cat_id'] = (int) $categoryId;
        }
        
        $select->where($clause);
        $resultSet = $this->getTableGateway()->selectwith($select);
        
        return $resultSet;

    }
    
    public function getProductText($productId, $langId = null, $productTextCode = null) 
    {
        $select = $this->getTableGateway()->getSql()->select();
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

        $resultSet = $this->getTableGateway()->selectwith($select);
        
        return $resultSet;
    }
    
    public function getProductPrice($productId, $countryId = null) 
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array());
        $clause = array();
        $select->join(array('product_price' => 'melis_ecom_price'), 'product_price.price_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = product_price.price_currency', array('cur_symbol','cur_code'), $select::JOIN_LEFT);
        
        $clause['melis_ecom_product.prd_id'] = (int) $productId;
        
        if(!is_null($countryId)) {
            $clause['product_price.price_country_id'] = (int) $countryId;
        }
        
        $select->where($clause);
        
        $resultSet = $this->getTableGateway()->selectwith($select);
        
        return $resultSet;
    }
    
    
    public function getProductAttributes($productId, $langId = null, $start = 0, $limit = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
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
        
        $resultSet = $this->getTableGateway()->selectwith($select);
        
        return $resultSet;
    }
    
    public function getProductByName($productName, $langId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        $select->join(array('product_text' => 'melis_ecom_product_text'), 'product_text.ptxt_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        $select->join(array('product_text_type' => 'melis_ecom_product_text_type'), 'product_text_type.ptt_id = product_text.ptxt_type', array('*'), $select::JOIN_LEFT);
        $select->where->NEST->like('melis_ecom_product.prd_reference', $productName)
            ->or->like('product_text.ptxt_field_short', $productName)->UNNEST
                ->and->equalTo('product_text.ptxt_lang_id', $langId)
                ->and->equalTo('product_text_type.ptt_code', 'TITLE');
        
        $select->where($clause);

        $resultSet = $this->getTableGateway()->selectwith($select);
        return $resultSet;
    }
    
    
    public function getProductByTextAndType($productText, $productType = array('TITLE'), $categoryIds = array(), $langId = null, $status = 1, $start = 0, $limit = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
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
        
        $resultSet = $this->getTableGateway()->selectwith($select);
        
        //echo $this->getRawSql($select);
        
        return $resultSet;
    }
    
    public function getProductCategoryByProductIdAndCategoryId($productId, $categoryIds = array(), $langId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
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
        $resultSet = $this->getTableGateway()->selectwith($select);

        return $resultSet;
    }
    
    public function getProductDocumentByProductIdAndCountryId($productId, $countryId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
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
        $resultSet = $this->getTableGateway()->selectwith($select);
        
        return $resultSet;
        
    }
    
    public function getProductByAttributeValueIdsAndPriceRange($attrValIds = array(), $minPrice = null , $maxPrice = null, $categoryIds = array(), $countryId = null, $status = 1, $start = 0, $limit = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
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
        

        
        $resultSet = $this->getTableGateway()->selectwith($select);
        //echo $this->getRawSql($select);
        
        return $resultSet;
    }

    /**
        * Function to get the product variants lists
        * by attribute value id
        *
        * This function is expecting to receive an array
        * of attributes with the ff example array format:
        * array(
        *      array(1,2,3),   ---->   Let's say this array is came from "COLOR" attribute
        *      array(4,5,6),           and the ID's inside it is came from selected attribute values
        *      array(7),               which is let's say "RED" => 1, "BLUE" => 2 and "GREEN" => 3.
        *      array(8,9,10,11,12),    It's the same with the other array. So basically every array
        *      etc,                    is came from ONE attribute that consists of selected attribute
        * )                            value ids.
        *
        * @param array $attrValIds
        * @return array
        */
    public function getProductVariantByAttributesId($attrValIds = array())
    {
        $variants = array();
        $attrSelect = new \Laminas\Db\Sql\Select;
        $attrSelect->columns(array(new Expression('DISTINCT(melis_ecom_variant_attribute_value.vatv_variant_id) AS variants')));
        $attrSelect->from('melis_ecom_variant_attribute_value');
        foreach($attrValIds as $key => $val){
            $attrSelect->join(array($key.'_var_attr'=>'melis_ecom_variant_attribute_value'), $key.'_var_attr.'.'vatv_variant_id = melis_ecom_variant_attribute_value.vatv_variant_id', array());
            $attrSelect->where->in($key.'_var_attr.'.'vatv_attribute_value_id', $val);
        }

        $attrResult = $this->getTableGateway()->selectwith($attrSelect);

        foreach ($attrResult As $val){
            array_push($variants, $val->variants);
        }
        return $variants;
    }
    
    public function getProductByNameTextTypeAttrIdsAndPrice($productSearchKey = null, $textTypeCode = array(), $selectedVariants = array(), $categoryIds = array(), $minPrice, $maxPrice, $langId = null, $countryId = null, $status = 1, $start = 0, $limit = null, $order = null, $priceColumn = null)
    {
        if(empty($priceColumn)){
            $priceColumn = 'price_net';
        }
        
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array(
            'prd_id',
            'price' => new \Laminas\Db\Sql\Expression('COALESCE(prod_price.'.$priceColumn.', var_price.'.$priceColumn.')'),
            'country' => new \Laminas\Db\Sql\Expression('COALESCE(prod_price.price_country_id, var_price.price_country_id)')
        ));
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT)
        
        ->join('melis_ecom_product_text_type', 'melis_ecom_product_text_type.ptt_id = melis_ecom_product_text.ptxt_type', array(), $select::JOIN_LEFT)
        
        ->join(array('prod_price'=>'melis_ecom_price'), 'prod_price.price_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT)
        
        ->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT)
        
        ->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);

        $select->join(array('var_price'=>'melis_ecom_price'), 'var_price.price_var_id = melis_ecom_variant.var_id', array(), $select::JOIN_LEFT);
        
        if(!empty($productSearchKey)){
            if(is_array($textTypeCode) && !empty($textTypeCode)) {
                
                $txtTypeSelect = new \Laminas\Db\Sql\Select;
                $txtTypeSelect->columns(array('ptt_field_type'));
                $txtTypeSelect->from('melis_ecom_product_text_type');
                $txtTypeSelect->where->in('ptt_code', $textTypeCode);
                $txtTypeSelect->group('ptt_field_type');
                $txtTypeResult = $this->getTableGateway()->selectwith($txtTypeSelect);
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
                $select->where->NEST->like('melis_ecom_product.prd_reference', '%'. $productSearchKey. '%')
                    ->or->like('melis_ecom_product_text.ptxt_field_short', '%'. $productSearchKey. '%')
                    ->or->like('melis_ecom_product_text.ptxt_field_long', '%'. $productSearchKey. '%');
            }
        }
        
        if(!is_null($langId)) {
            $select->where->and->equalTo('melis_ecom_product_text.ptxt_lang_id', $langId);
        }
        
        if(!empty($selectedVariants)){
            $select->where->in('melis_ecom_variant.var_id', $selectedVariants);
        }

        if(!is_null($countryId)) {
            $select->where->and->equalTo(new \Laminas\Db\Sql\Expression('COALESCE(prod_price.price_country_id, var_price.price_country_id)'), $countryId);
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
            // $select->where->notEqualTo('melis_ecom_product_text.ptxt_field_short', "");
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

        $resultSet = $this->getTableGateway()->selectwith($select);

        return $resultSet;
    }
    
    public function productFullSearch($searchKey = ''){
    
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->columns(array(
            '*',
//             'price' => new \Laminas\Db\Sql\Expression('COALESCE(product_price.price_net, variant_price.price_net)'),
//             'price' => new \Laminas\Db\Sql\Expression('MIN(variant_price.price_net)'),
            
        ));
        
        $join = new Expression('melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id)');
        
        $select->join('melis_ecom_variant', $join, array(), $select::JOIN_LEFT);
        
        $select->join(array('product_price' => 'melis_ecom_price'), 'product_price.price_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        
        $select->join(array('variant_price' => 'melis_ecom_price'), 'variant_price.price_var_id = melis_ecom_variant.var_id', array('tests' => new Expression('MIN(variant_price.price_net)')), $select::JOIN_LEFT);
        
        $select->where->like('melis_ecom_product.prd_reference', '%'. $searchKey. '%');
        
//         $select->order('variant_price.price_net ASC');
        
        $resultSet = $this->getTableGateway()->selectWith($select);
        
        return $resultSet;
    }
    
    public function getProductVariantPriceById($productId, $order = 'ASC', $priceColumn = 'price_net')
    {
        $select = $this->getTableGateway()->getSql()->select();
    
        $select->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
                ->join('melis_ecom_price', 'melis_ecom_price.price_var_id = melis_ecom_variant.var_id', array('*'), $select::JOIN_LEFT)
                ->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency', array('*'), $select::JOIN_LEFT);
        
        $select->where->equalTo('prd_id', $productId);
        $select->order($priceColumn . " $order");

        $resultSet = $this->getTableGateway()->selectwith($select);
        
        return $resultSet;
        
    }
    
    public function getProductTitleAndSeoById($productId, $langId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_text_type', 'melis_ecom_product_text.ptxt_type = melis_ecom_product_text_type.ptt_id', array(), $select::JOIN_LEFT)
        ->join('melis_ecom_seo', 'melis_ecom_seo.eseo_product_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT);
        
        $select->where->NEST->equalTo('melis_ecom_product_text_type.ptt_code', 'TITLE')
        ->or->isNull('melis_ecom_product_text_type.ptt_code')->UNNEST;
        
        $select->where->NEST->equalTo('melis_ecom_product_text.ptxt_lang_id', $langId)
        ->or->isNull('melis_ecom_product_text.ptxt_lang_id')->UNNEST;

        $select->where->equalTo('melis_ecom_product.prd_id', $productId);
        
        $resultSet = $this->getTableGateway()->selectWith($select);
        return $resultSet;
    }
    
    public function getProductByVariantId($variantId)
    {
        $select = $this->getTableGateway()->getSql()->select();
    
        $select->join('melis_ecom_variant', 'melis_ecom_variant.var_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT);
        
        $select->where->equalTo('melis_ecom_variant.var_id', $variantId);
        
        $resultSet = $this->getTableGateway()->selectWith($select);
        
        return $resultSet;
    }
    
    public function getProductCategoryByProductId($productId, $langId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array());

        $prdCatjoin = new \Laminas\Db\Sql\Predicate\Expression('melis_ecom_category_trans.catt_category_id = melis_ecom_category.cat_id AND catt_name IS NOT NULL');

        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_category', 'melis_ecom_category.cat_id = melis_ecom_product_category.pcat_cat_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_category_trans', $prdCatjoin , array('*'), $select::JOIN_LEFT);
        $select->where->equalTo('melis_ecom_product_category.pcat_prd_id', $productId);

        if (!empty($langId)) {
            $select->where->equalTo('melis_ecom_category_trans.catt_lang_id', $langId);
        }

        // $select->group('melis_ecom_product_category.pcat_id');
        $select->order('melis_ecom_category.cat_order ASC');
        $resultSet = $this->getTableGateway()->selectwith($select);

        return $resultSet;
    }
    
    public function getProductCategoryPriceByProductId($productId = null, $categoryIds = array(), $langId, $countryId = null, $fieldsTypeCodes = array('TITLE'), $documents = array('mainImage' => 'DEFAULT'))
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->join('melis_ecom_product_category', 'melis_ecom_product_category.pcat_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT)
        ->join('melis_ecom_category', 'melis_ecom_category.cat_id = melis_ecom_product_category.pcat_cat_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_category_trans', 'melis_ecom_category_trans.catt_category_id = melis_ecom_category.cat_id', array('catt_name'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_text', 'melis_ecom_product_text.ptxt_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_product_text_type', 'melis_ecom_product_text_type.ptt_id = melis_ecom_product_text.ptxt_type', array(), $select::JOIN_LEFT)
        ->join('melis_ecom_price', 'melis_ecom_price.price_prd_id = melis_ecom_product.prd_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency', array('cur_symbol'), $select::JOIN_LEFT);
        
        if (is_array($documents)){
            foreach($documents as $key => $value){
                $docTypeSelect = new \Laminas\Db\Sql\Select;
                $docTypeSelect->columns(array('rdoc_product_id'));
                
                $docTypeSelect->from('melis_ecom_doc_relations');
            
                $docTypeSelect->join(array($key.'_melis_ecom_document' => 'melis_ecom_document'), $key.'_melis_ecom_document.doc_id = melis_ecom_doc_relations.rdoc_doc_id', array($key=>'doc_path'), $select::JOIN_LEFT)
                ->join(array($key.'_melis_ecom_doc_type' => 'melis_ecom_doc_type'), $key.'_melis_ecom_doc_type.dtype_id = '.$key.'_melis_ecom_document.doc_subtype_id', array(), $select::JOIN_LEFT);
            
                $docTypeSelect->where->equalTo($key.'_melis_ecom_doc_type.dtype_code', $value);
            
                $docTypeSelect->group('rdoc_product_id');
            
                $tmp = new \Laminas\Db\Sql\Expression('(' .$this->getRawSql($docTypeSelect).')');
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
        
        $resultSet = $this->getTableGateway()->selectWith($select);
        
        return $resultSet;
    }
    
    protected function setProdCurrentDataCount($dataCount)
    {
        $this->_currentProdDataCount = $dataCount;
    }
    

    public function getCouponProductList($couponId, $assigned = null, $start = null, $limit = null, $order = null, $search = null)
    {
        $select= $this->getTableGateway()->getSql()->select();
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
        
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
        
    }
    
    public function getProductsByCategoryId($categoryId, $onlyValid = false, $langId = null, $order = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
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

        if(!empty($order)){
            $select->order($order);
        }

        $resultSet = $this->getTableGateway()->selectWith($select);
        
        return $resultSet;
    }
}