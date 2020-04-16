<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomCouponOrderTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_coupon_order';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'cord_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function checkUsedClientCoupon($couponId, $clientId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->join('melis_ecom_order', 'melis_ecom_order.ord_id = melis_ecom_coupon_order.cord_order_id', array('*'), $select::JOIN_LEFT);
        $select->where('melis_ecom_coupon_order.cord_coupon_id ='.$couponId);
        $select->where('melis_ecom_order.ord_client_id ='.$clientId);
        
        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
    
    public function getAssociatedBasketItem($couponId, $orderId)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->join('melis_ecom_order_basket', 'melis_ecom_order_basket.obas_id = melis_ecom_coupon_order.cord_basket_id', array('*'), $select::JOIN_LEFT);
        
        $select->where->equalTo('melis_ecom_coupon_order.cord_coupon_id', $couponId);
        
        $select->where->equalTo('melis_ecom_coupon_order.cord_order_id', $orderId);
        
        $resultData = $this->getTableGateway()->selectWith($select);
        
        return $resultData;
    }
    
    public function getCouponDiscountedBasketItems($couponId, $orderId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->where->equalTo('cord_coupon_id', $couponId);
        
        if(!is_null($orderId)){
            $select->where->equalTo('cord_order_id', $orderId);
        }
        
        $resultData = $this->getTableGateway()->selectWith($select);
        
         return $resultData;
        
    }
    
}