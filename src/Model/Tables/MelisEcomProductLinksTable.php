<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use MelisCommerce\Model\Tables\MelisEcomGenericTable;

class MelisEcomProductLinksTable extends MelisEcomGenericTable
{
    const TABLE = 'melis_ecom_product_links';
    const PRIMARY_KEY = 'plink_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getProductPageAssociationsByProductId($productId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->where->equalTo('plink_product_id', (int)$productId);
        return $this->tableGateway->selectWith($select);
    }
}
