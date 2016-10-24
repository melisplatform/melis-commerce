<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomVariantStockTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'stock_id';
    }
    
    public function getStocksByVariantId($variantId, $countryId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $select->join('melis_ecom_country','melis_ecom_country.ctry_id = melis_ecom_variant_stock.stock_country_id', array('*'), $select::JOIN_LEFT);
        
        if(!is_null($countryId))
            $clause['melis_ecom_country.ctry_id'] = (int) $countryId;        
        
        $clause['melis_ecom_variant_stock.stock_var_id'] = (int) $variantId;
        
        if($clause){
            $select->where($clause);
        }
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
}