<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomOrderPaymentTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_order_payment';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'opay_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getOrderPaymentByOrderId($orderId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->join('melis_ecom_order_payment_type', 'melis_ecom_order_payment_type.opty_id=melis_ecom_order_payment.opay_payment_type_id',
            array('*'),$select::JOIN_LEFT);
        
        $select->where('opay_order_id ='.$orderId);
        $select->order('opay_date_payment');
        
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
}