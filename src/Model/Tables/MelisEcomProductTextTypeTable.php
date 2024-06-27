<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomProductTextTypeTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_product_text_type';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'ptt_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}