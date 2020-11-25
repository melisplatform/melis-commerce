<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Predicate\Predicate;
use Laminas\Db\Sql\Expression;

class MelisEcomOrderProductReturnTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_order_product_return';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'pret_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * @param null $orderId
     * @param null $start
     * @param null $limit
     * @param string $order
     * @param null $orderKey
     * @param null $searchValue
     * @param array $searchKeys
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getOrderProductReturnList($orderId = null, $start = null, $limit = null, $order = 'ASC', $orderKey = null, $searchValue = null, $searchKeys = [])
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join('melis_ecom_order_product_return_details', 'melis_ecom_order_product_return_details.pretd_pret_id = melis_ecom_order_product_return.pret_id', array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_order_basket', 'melis_ecom_order_basket.obas_order_id = melis_ecom_order_product_return.pret_order_id AND melis_ecom_order_basket.obas_variant_id = melis_ecom_order_product_return_details.pretd_variant_id', array('obas_price_net'), $select::JOIN_LEFT);

        if (!empty($searchValue)){
            $search = [];
            foreach ($searchKeys As $col)
                $search[$col] = new Like($col, '%'.$searchValue.'%');

            $filters = [new PredicateSet($search, PredicateSet::COMBINED_BY_OR)];
            $select->where($filters);
        }

        if(!empty($orderId)){
            $select->where->equalTo('pret_order_id', $orderId);
        }

        if(!empty($start)){
            $select->offset($start);
        }

        if(!empty($limit)){
            $select->limit($limit);
        }

        if (!empty($orderKey))
            $select->order($orderKey.' '.$order);

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;

    }
}