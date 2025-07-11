<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomCouponTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_coupon';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'coup_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getCouponList($orderId, $clientId = null, $start = null, $limit = null, $order = null, $search = '')
    {
        $select = $this->getTableGateway()->getSql()->select();
        $clause = array();
        $select->quantifier('DISTINCT');
        $select->join('melis_ecom_coupon_order', 'melis_ecom_coupon_order.cord_coupon_id = melis_ecom_coupon.coup_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_coupon_client', 'melis_ecom_coupon_client.ccli_coupon_id = melis_ecom_coupon.coup_id', array(), $select::JOIN_LEFT);

        if (!is_null($orderId)) {
            $select->where->equalTo('melis_ecom_coupon_order.cord_order_id', (int)$orderId);
        }

        if (!is_null($clientId)) {
            $select->where('melis_ecom_coupon_client.ccli_client_id', (int)$clientId);
        }

        if (!is_null($search)) {
            $search = '%' . $search . '%';
            $select->where->NEST->like('coup_id', $search)
                ->or->like('melis_ecom_coupon.coup_code', $search)
                ->or->like('melis_ecom_coupon.coup_discount_value', $search)
                ->or->like('melis_ecom_coupon.coup_percentage', $search);
        }

        if (!is_null($start)) {
            $select->offset($start);
        }

        if (!is_null($limit) && $limit != -1) {
            $select->limit($limit);
        }

        if (!is_null($order)) {
            $select->order($order);
        }

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    public function getCouponByType($type, $orderId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->quantifier("DISTINCT");
        $select->join('melis_ecom_coupon_order', 'melis_ecom_coupon_order.cord_coupon_id = melis_ecom_coupon.coup_id', array(), $select::JOIN_LEFT);

        if ($type == 'general') {
            $select->where->equalTo('melis_ecom_coupon.coup_product_assign', '0');
        } elseif ($type == 'product') {
            $select->where->equalTo('melis_ecom_coupon.coup_product_assign', '1');
        }

        if (!is_null($orderId)) {
            $select->where->equalTo('cord_order_id', (int)$orderId);
        }

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }
}
