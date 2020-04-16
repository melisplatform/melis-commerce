<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

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

    public function getClientCompanyByClientId($clientId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->where('ccomp_client_id ='.$clientId);
        
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
}