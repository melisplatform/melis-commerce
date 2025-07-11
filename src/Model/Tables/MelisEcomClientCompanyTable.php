<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomClientCompanyTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_client_company';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'ccomp_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getClientCompanyByClientId($clientId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->where->equalTo('ccomp_client_id', (int)$clientId);

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    public function getClientCompaniesByClientIdArray($clientIds = [])
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->where->in('ccomp_client_id', $clientIds);

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
}
