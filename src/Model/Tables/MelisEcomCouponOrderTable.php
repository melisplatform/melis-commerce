<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomCouponOrderTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'cord_id';
    }
    
    public function checkUsedClientCoupon($couponId, $clientId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('melis_ecom_order', 'melis_ecom_order.ord_id = melis_ecom_coupon_order.cord_order_id', array('*'), $select::JOIN_LEFT);
        $select->where('melis_ecom_coupon_order.cord_coupon_id ='.$couponId);
        $select->where('melis_ecom_order.ord_client_id ='.$clientId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getAssociatedBasketItem($couponId, $orderId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_order_basket', 'melis_ecom_order_basket.obas_id = melis_ecom_coupon_order.cord_basket_id', array('*'), $select::JOIN_LEFT);
        
        $select->where->equalTo('melis_ecom_coupon_order.cord_coupon_id', $couponId);
        
        $select->where->equalTo('melis_ecom_coupon_order.cord_order_id', $orderId);
        
        $resultData = $this->tableGateway->selectWith($select);
        
        return $resultData;
    }
    
    public function getCouponDiscountedBasketItems($couponId, $orderId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where->equalTo('cord_coupon_id', $couponId);
        
        if(!is_null($orderId)){
            $select->where->equalTo('cord_order_id', $orderId);
        }
        
        $resultData = $this->tableGateway->selectWith($select);
        
         return $resultData;
        
    }
    
}