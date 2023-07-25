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

class MelisEcomClientAccountRelTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_client_account_rel';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'car_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * @param $accountId
     * @param $contactId
     * @return mixed
     */
    public function unlinkAccountContact($accountId, $contactId)
    {
        $delete = $this->tableGateway->getSql()->delete();

        $delete->where->equalTo('car_client_id', $accountId);
        $delete->where->equalTo('car_client_person_id', $contactId);

        $resultData = $this->tableGateway->deleteWith($delete);
        return $resultData;
    }
}