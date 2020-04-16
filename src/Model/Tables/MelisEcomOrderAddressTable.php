<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomOrderAddressTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_order_address';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'oadd_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getOrderAddressesByOrderId($orderId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->where('oadd_order_id ='.$orderId);
        
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
    
    public function getorderAddressByOrderAddressIdandTypeId($orderAddressId, $typeId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->where('oadd_id ='.$orderAddressId);
        $select->where('oadd_type ='.$typeId);
        
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
    
}