<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomClientPersonEmailsTable extends MelisEcomGenericTable
{
    const TABLE = 'melis_ecom_client_person_emails';
    const PRIMARY_KEY = 'cpmail_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getDataByClientPersonIdAndEmail($clientPersonId, $email)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->where->equalTo('cpmail_cper_id', (int)$clientPersonId);
        $select->where->equalTo('cpmail_email', $email);

        return $this->getTableGateway()->selectWith($select);
    }
}
