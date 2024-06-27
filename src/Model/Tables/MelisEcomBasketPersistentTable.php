<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomBasketPersistentTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_basket_persistent';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'bper_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getbasketPersistentByClientIdAndVariantId($variantId, $clientId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->where('bper_client_id ='. $clientId);
        $select->where('bper_variant_id ='. $variantId);
        
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
    
    public function getBasket($clientId, $currencyId = 0)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->join('melis_ecom_variant', 'bper_variant_id = var_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_price', 'var_id = price_var_id', array(), $select::JOIN_LEFT);
        
        $select->where->equalTo('bper_client_id', $clientId);
        $select->where->equalTo('price_currency', $currencyId);
        
        return $this->getTableGateway()->selectWith($select);
    }
    
}