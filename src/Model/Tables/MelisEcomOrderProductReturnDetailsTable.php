<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomOrderProductReturnDetailsTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_order_product_return_details';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'pretd_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}
