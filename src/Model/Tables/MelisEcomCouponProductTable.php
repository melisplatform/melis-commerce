<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomCouponProductTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_coupon_product';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'cprod_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function checkCouponProductExist($couponId, $clientId, $productId = null)
    {

        $select = $this->getTableGateway()->getSql()->select();

        if (!is_null($clientId)) {
            $select->join('melis_ecom_coupon_client', 'melis_ecom_coupon_client.ccli_coupon_id = cprod_coupon_id', array(), $select::JOIN_LEFT);
            $select->where->equalTo('ccli_client_id', $clientId);
        }

        $select->where->equalTo('cprod_coupon_id', (int)$couponId);

        $select->where->equalTo('cprod_product_id', (int)$productId);

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }

    public function deleteCouponproduct($couponId, $productId)
    {
        $delete = $this->getTableGateway()->getSql()->delete();

        $delete->where->equalTo('cprod_coupon_id', (int)$couponId);
        $delete->where->equalTo('cprod_product_id', (int)$productId);

        $resultData = $this->getTableGateway()->deleteWith($delete);
        return $resultData;
    }
}
