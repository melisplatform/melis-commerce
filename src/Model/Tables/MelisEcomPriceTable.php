<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

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
        
        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency',
            array('*'), $select::JOIN_LEFT);
        $select->where->equalTo('price_var_id', $variantId)
                ->and->equalTo('price_country_id', $countryId)
                ->and->equalTo('melis_ecom_currency.cur_status', 1);
        
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
    
    public function getVariantGeneralPrice($variantId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency',
            array(), $select::JOIN_LEFT);
        $select->where->equalTo('price_var_id', $variantId)
                ->and->equalTo('price_country_id', 0);
        
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
    
    public function getProductFinalPrice($productId, $countryId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency',
            array('*'), $select::JOIN_LEFT);
        $select->where->equalTo('price_prd_id', $productId)
                ->and->equalTo('price_country_id', $countryId)
                ->and->equalTo('melis_ecom_currency.cur_status', 1);
        
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
    
    public function getProductGeneralPrice($productId)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency',
            array(), $select::JOIN_LEFT);
        $select->where->equalTo('price_prd_id', $productId)
        ->and->equalTo('price_country_id', 0);
    
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
}