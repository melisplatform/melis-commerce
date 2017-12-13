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
use Zend\Db\Sql\Predicate\Expression;

class MelisEcomPriceTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'price_id';
    }
    
    public function getPricesByVariantId($variantId, $countryId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $clause = array();

        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency',
            array('*'), $select::JOIN_LEFT);

        $select->where->equalTo('melis_ecom_price.price_var_id', (int) $variantId);
        if(!is_null($countryId)) {
            $select->where->and->equalTo('melis_ecom_price.price_country_id', (int) $countryId)->and->equalTo('melis_ecom_currency.cur_status', 1);
        }

        $resultSet = $this->tableGateway->selectwith($select);

        return $resultSet;
    }
    
    public function getPricesByProductId($productId, $countryId = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->join('melis_ecom_country', 'melis_ecom_country.ctry_id = melis_ecom_price.price_country_id',
            array('*'), $select::JOIN_LEFT);

        $select->where->equalTo('melis_ecom_price.price_prd_id', (int) $productId);

        if(!is_null($countryId)) {
            $select->where->and->equalTo('melis_ecom_price.price_country_id', (int) $countryId)->and->equalTo('melis_ecom_country.ctry_status', 1);
        }

        $resultSet = $this->tableGateway->selectwith($select);

        return $resultSet;
    }
    
    public function getVariantFinalPrice($variantId, $countryId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        if ($countryId == -1)
        {   // General price with a default currency
            $join = new Expression('melis_ecom_currency.cur_id = melis_ecom_price.price_currency OR cur_default = 1');
        }
        else
        {
            $join = new Expression('melis_ecom_currency.cur_id = melis_ecom_price.price_currency');
        }
        
        $select->join('melis_ecom_currency', $join, array('*'), $select::JOIN_LEFT);
        
        $select->where->equalTo('price_var_id', $variantId)
                ->and->equalTo('price_country_id', $countryId)
                ->and->equalTo('melis_ecom_currency.cur_status', 1);
        
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
    
    public function getProductFinalPrice($productId, $countryId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        if ($countryId == -1)
        {   // General price with a default currency
            $join = new Expression('melis_ecom_currency.cur_id = melis_ecom_price.price_currency OR cur_default = 1');
        }
        else
        {
            $join = new Expression('melis_ecom_currency.cur_id = melis_ecom_price.price_currency');
        }
        
        $select->join('melis_ecom_currency', $join, array('*'), $select::JOIN_LEFT);
        
        $select->where->equalTo('price_prd_id', $productId)
                ->and->equalTo('price_country_id', $countryId)
                ->and->equalTo('melis_ecom_currency.cur_status', 1);
        
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
    
    /**
     * 
     * @param string $order  ASC/DESC, order of retrieving data
     * @param string $column column order
     * @param string $type variant|product 
     * @return NULL|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getPriceByColumnOrder($order, $column = 'price_net', $categoryId = array())
    {
        $select = $this->tableGateway->getSql()->select();
        
//         if(!is_null($type)){
//             if($type == 'variant'){
//                 $select->where->isNotNull('price_var_id');
//             }else{
//                 $select->where->isNotNull('price_prd_id');
//             }
//         }
        $select->join('melis_ecom_product', 'melis_ecom_product.prd_id = melis_ecom_price.price_prd_id', array(), $select::JOIN_LEFT)       
        ->join(array('product_category' => 'melis_ecom_product_category'), 'product_category.pcat_prd_id = melis_ecom_product.prd_id', array(), $select::JOIN_LEFT)
        ->join('melis_ecom_variant', 'melis_ecom_variant.var_id = melis_ecom_price.price_var_id', array(), $select::JOIN_LEFT)
        ->join(array('product_variant'=> 'melis_ecom_product'), 'product_variant.prd_id = melis_ecom_variant.var_prd_id', array(), $select::JOIN_LEFT)
        ->join(array('variant_product_category' => 'melis_ecom_product_category'), 'variant_product_category.pcat_prd_id = product_variant.prd_id', array('*'), $select::JOIN_LEFT);
        if(!empty($categoryId)){
            $select->where->in('product_category.pcat_cat_id' , $categoryId)->OR->in('variant_product_category.pcat_cat_id', $categoryId);
        }
        
        $select->order($column.' '.$order);
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }

    /**
     *
     * Function to get the minimum or maximum value
     * of product price.
     * Price will depend on the $priceType value
     *
     * @param $type - Maximum or Minimum
     * @param $priceColumn - price type of the product(price_net, price_gross, price_vat_price, etc.)
     * @param $from - specify where do we get the price (product or from variant)
     * @return mixed
     */
    public function getMaximumMinimumPrice($type, $priceColumn = "price_net", $from = "product")
    {
        $select = $this->tableGateway->getSql()->select();
        if($type == "max") {
            $select->columns(array('max_price' => new Expression('MAX(' . $priceColumn . ')')));
        }elseif($type == "min"){
            $select->columns(array('min_price' => new Expression('MIN(' . $priceColumn . ')')));
        }
        if($from == "product") {
            $select->where->isNotNull('price_prd_id');
        }else if($from == "variant"){
            $select->where->isNotNull('price_var_id');
        }

        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
}