<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomStockEmailAlertTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_stock_email_alert';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'sea_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getStockEmailRecipients($productIds = array(-1))
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->where->in('sea_prd_id', array($productIds));

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }

    public function setDefaultValues($datas)
    {
        $id = (int) $datas['sea_id'];

        if ($this->getEntryById($id)->current()) {
            unset($datas['sea_id']);
            $this->getTableGateway()->update($datas, array($this->idField => $id));
            return $id;
        } else {
            $this->getTableGateway()->insert($datas);
            $insertedId = $this->getTableGateway()->lastInsertValue;
            return $insertedId;
        }
    }
}
