<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

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

        $select->where->equalTo('oadd_order_id', (int)$orderId);

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    public function getorderAddressByOrderAddressIdandTypeId($orderAddressId, $typeId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->where->equalTo('oadd_id', (int)$orderAddressId);
        $select->where->equalTo('oadd_type', (int)$typeId);

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
}
