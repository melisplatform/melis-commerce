<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomVariantStockTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_variant_stock';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'stock_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getStocksByVariantId($variantId, $countryId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        if(!is_null($countryId))
            $clause['stock_country_id'] = (int) $countryId;        
        
        $clause['melis_ecom_variant_stock.stock_var_id'] = (int) $variantId;
        
        if($clause){
            $select->where($clause);
        }
        
        $resultSet = $this->getTableGateway()->selectwith($select);

        return $resultSet;
    }
}