<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomCategoryTransTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_category_trans';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'catt_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}
