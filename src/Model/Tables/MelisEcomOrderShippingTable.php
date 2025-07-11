<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomOrderShippingTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_order_shipping';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'oship_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getOrderShippingByOrderId($orderId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->where->equalTo('oship_order_id', (int)$orderId);
        $select->order('oship_date_sent');

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
}
