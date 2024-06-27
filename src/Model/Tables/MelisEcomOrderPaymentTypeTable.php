<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomOrderPaymentTypeTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_order_payment_type';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'opty_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}