<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomCivilityTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_civility';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'civ_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}