<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomClientAddressTypeTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_client_address_type';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'catype_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}
