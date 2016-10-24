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
        $select->columns(array('*'));
        $clause = array();
        
        if(!is_null($countryId))
            $clause['melis_ecom_price.price_country_id'] = (int) $countryId;
        
        
        $clause['melis_ecom_price.price_var_id'] = (int) $variantId;
        
        
        if($clause){
            $select->where($clause);
        }
            $resultSet = $this->tableGateway->selectwith($select);
        
            return $resultSet;
    }
    
    public function getPricesByProductId($productId, $countryId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        if(!is_null($countryId))
            $clause['melis_ecom_price.price_country_id'] = (int) $countryId;
        
            
        $clause['melis_ecom_price.price_prd_id'] = (int) $productId;
            
        
            if($clause){
                $select->where($clause);
            }
            $resultSet = $this->tableGateway->selectwith($select);
        
            return $resultSet;
    }
    
    public function getVariantFinalPrice($variantId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency',
            array('*'), $select::JOIN_LEFT);
        $select->where('price_var_id = '.$variantId);
        
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
    
    public function getProductFinalPrice($productId)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_price.price_currency',
            array('*'), $select::JOIN_LEFT);
        $select->where('price_prd_id = '.$productId);
    
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
}