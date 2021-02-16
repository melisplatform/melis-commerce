<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Predicate\Predicate;
use Laminas\Db\Sql\Expression;

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