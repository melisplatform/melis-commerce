<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomBasketAnonymousTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_basket_anonymous';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'bano_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getBasketAnonymousByVarianIdAndClientKey($variantId, $clientKey)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->where->equalTo('bano_key', $clientKey);
        $select->where->equalTo('bano_variant_id', (int)$variantId);

        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }

    public function cleanAnonymousBaskets($daysToKeep)
    {
        $delete = $this->tableGateway->getSql()->delete();

        $delete->where->lessThan('bano_date_added', $daysToKeep);

        $resultData = $this->tableGateway->deleteWith($delete);
        return $resultData;
    }
}
