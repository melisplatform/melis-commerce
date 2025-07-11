<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomOrderBasketTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_order_basket';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'obas_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getOrderBaskets($orderId, $start = 0, $limit = null, $search = null, $order = 'obas_id ASC')
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));
        $clause = array();

        $clause['melis_ecom_order_basket.obas_order_id'] = (int) $orderId;
        $select->where($clause);
        if (!is_null($search)) {
            $search = '%' . $search . '%';
            $select->where->NEST->like('obas_product_name', $search)
                ->or->like('obas_id', $search)
                ->or->like('obas_sku', $search)
                ->or->like('obas_quantity', $search)
                ->or->like('obas_price_net', $search);
        }

        if (!is_null($limit)) {
            $select->limit((int)$limit);
        }

        $select->order($order);
        if (!empty($start)) {
            $select->offset((int)$start);
        }
        return $this->getTableGateway()->selectWith($select);
    }
}
