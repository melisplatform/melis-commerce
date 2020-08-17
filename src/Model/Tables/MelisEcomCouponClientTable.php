<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomCouponClientTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_coupon_client';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'ccli_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function checkCouponClientExist($couponId, $clientId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where('melis_ecom_coupon_client.ccli_coupon_id ='.$couponId);
        $select->where('melis_ecom_coupon_client.ccli_client_id ='.$clientId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function removeCouponFromClient($couponId, $clientId)
    {
        $delete = $this->tableGateway->getSql()->delete();
        
        $delete->where('melis_ecom_coupon_client.ccli_coupon_id ='.$couponId);
        $delete->where('melis_ecom_coupon_client.ccli_client_id ='.$clientId);
        
        $resultData = $this->tableGateway->deleteWith($delete);
        return $resultData;
    }
    
}