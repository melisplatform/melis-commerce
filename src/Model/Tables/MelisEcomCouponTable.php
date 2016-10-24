<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomCouponTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'coup_id';
    }
    
    public function getCouponList($orderId, $clientId, $start = null, $limit = null, $order = null, $search = '')
    {
        $select = $this->tableGateway->getSql()->select();    
        $clause = array();
        $select->quantifier('DISTINCT');
        $select->join('melis_ecom_coupon_order', 'melis_ecom_coupon_order.cord_coupon_id = melis_ecom_coupon.coup_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_coupon_client', 'melis_ecom_coupon_client.ccli_coupon_id = melis_ecom_coupon.coup_id', array(), $select::JOIN_LEFT);
        
        if (!is_null($orderId))
        {
            $select->where('melis_ecom_coupon_order.cord_order_id ='.$orderId);
        }
        
        if (!is_null($clientId))
        {
             $select->where('melis_ecom_coupon_client.ccli_client_id ='.$clientId);
        }
        
        if(!is_null($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('coup_id', $search)
            ->or->like('melis_ecom_coupon.coup_code', $search)
            ->or->like('melis_ecom_coupon.coup_discount_value', $search)
            ->or->like('melis_ecom_coupon.coup_percentage', $search);
        }
        
        if (!is_null($start))
        {
            $select->offset($start);
        }
        
        if (!is_null($limit)&&$limit!=-1)
        {
            $select->limit($limit);
        }
        
        if (!is_null($order))
        {
            $select->order($order);
        }
        
//         echo $select->getSqlString();die();
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
}