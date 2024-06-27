<?php

/**
 * Melis Technology (http://www.melistechnology.com?p
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\TableGateway\TableGateway;

class MelisEcomClientPersonRelTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_client_person_rel';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'cpr_id';

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

        $delete->where->equalTo('cpr_client_id', $accountId);
        $delete->where->equalTo('cpr_client_person_id', $contactId);

        $resultData = $this->tableGateway->deleteWith($delete);
        return $resultData;
    }

    /**
     * @param $accountId
     * @param $contactId
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getDataByAccountAndContactId($accountId, $contactId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->where->equalTo('cpr_client_id', $accountId);
        $select->where->equalTo('cpr_client_person_id', $contactId);

        return $this->getTableGateway()->selectwith($select);
    }
}
