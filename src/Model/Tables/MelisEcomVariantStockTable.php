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
        
        if(!is_null($countryId))
            $clause['stock_country_id'] = (int) $countryId;        
        
        $clause['melis_ecom_variant_stock.stock_var_id'] = (int) $variantId;
        
        if($clause){
            $select->where($clause);
        }
        
        $resultSet = $this->tableGateway->selectwith($select);

        return $resultSet;
    }
}