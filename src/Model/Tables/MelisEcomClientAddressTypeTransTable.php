<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomClientAddressTypeTransTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_client_address_type_trans';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'catypt_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getAddressTypeTransByLangId($langId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->join('melis_ecom_client_address_type', 'melis_ecom_client_address_type_trans.catypt_type_id = melis_ecom_client_address_type.catype_id', array('*'), $select::JOIN_LEFT);
        if (!is_null($langId)) {
            $select->where->equalTo('catypt_lang_id', (int)$langId);
        }

        $resullData = $this->getTableGateway()->selectWith($select);
        return $resullData;
    }

    public function getAddressTransByAddressTypeIdAndLangId($addTypeId, $langId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();

        if ($addTypeId) {
            $select->where->equalTo('catypt_type_id', (int)$addTypeId);
        }

        if (!is_null($langId)) {
            $select->where->equalTo('catypt_lang_id', (int)$langId);
        }

        $resullData = $this->getTableGateway()->selectWith($select);

        return $resullData;
    }
}
