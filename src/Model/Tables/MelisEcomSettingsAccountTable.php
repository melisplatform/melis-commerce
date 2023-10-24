<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\TableGateway\TableGateway;

class MelisEcomSettingsAccountTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_settings_account';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'sa_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}