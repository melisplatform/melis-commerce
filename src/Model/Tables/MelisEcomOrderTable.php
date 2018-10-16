<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Expression;

class MelisEcomOrderTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'ord_id';
    }
    
    public function getOrderList($orderStatusId = null, $onlyValid, $clientId = null, $clientPersonId = null, 
                                 $couponId = null, $reference = null, $start = 0, $limit = null, $order = 'ord_id', 
                                 $search = null, $startDate = null, $endDate = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->quantifier('DISTINCT');
        $select->columns(array('ord_id'));
        
        //nested select for left join baskets
        $basketQuery = new \Zend\Db\Sql\Select ('melis_ecom_order_basket');
        $basketQuery->columns(array('obas_order_id', 'products' => new Expression('sum(melis_ecom_order_basket.obas_quantity)')));
        $basketQuery->group('obas_order_id');

        //nested select for left join payments
        $paymentQuery = new \Zend\Db\Sql\Select ('melis_ecom_order_payment');
        $paymentQuery->columns(array('opay_order_id', 'price' => new Expression('sum(melis_ecom_order_payment.opay_price_total)')));
        $paymentQuery->group('opay_order_id');

        $select->join(array('a' => $basketQuery), 'a.obas_order_id = melis_ecom_order.ord_id', array('products'), $select::JOIN_LEFT);
        $select->join(array('b' => $paymentQuery), 'b.opay_order_id = melis_ecom_order.ord_id', array('price'), $select::JOIN_LEFT);
        $select->join('melis_ecom_coupon_order', 'melis_ecom_coupon_order.cord_order_id = melis_ecom_order.ord_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_client', 'melis_ecom_client.cli_id = melis_ecom_order.ord_client_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_client_company', 'melis_ecom_client_company.ccomp_client_id = melis_ecom_client.cli_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_client_person', 'melis_ecom_client_person.cper_id = melis_ecom_order.ord_client_person_id', array('cper_name'), $select::JOIN_LEFT);
        
        if (!is_null($orderStatusId) && is_null($onlyValid))
        {
            $select->where('melis_ecom_order.ord_status ='.$orderStatusId);
        }

        if (!is_null($onlyValid) && is_null($orderStatusId))
        {
            $select->where('melis_ecom_order.ord_status != -1');
        }

        if (!is_null($couponId))
        {
            $select->where('melis_ecom_coupon_order.cord_coupon_id ='.$couponId);
        }

        if (!is_null($clientId))
        {
            $select->where('melis_ecom_order.ord_client_id ='.$clientId);
        }

        if (!is_null($clientPersonId))
        {
            $select->where('melis_ecom_order.ord_client_person_id ='.$clientPersonId);
        }

        if (!is_null($reference))
        {
            $reference = '%'.$reference.'%';
            $select->where->like('melis_ecom_order.ord_reference', $reference);
        }

        if (!is_null($startDate)){
            $select->where->greaterThan('melis_ecom_order.ord_date_creation', $startDate);
        }

        if (!is_null($endDate)){
            $select->where->lessThan('melis_ecom_order.ord_date_creation', $endDate);
        }

        if(!is_null($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('ord_id', $search)
            ->or->like('melis_ecom_order.ord_reference', $search)
            ->or->like('melis_ecom_order.ord_status', $search)
            ->or->like('melis_ecom_client_person.cper_name', $search)
            ->or->like('melis_ecom_client_person.cper_firstname', $search)
            ->or->like('melis_ecom_client_company.ccomp_name', $search);
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
        $select->group('melis_ecom_order.ord_id');



        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getOrderStatusByOrderId($orderId, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_order_status', 'melis_ecom_order_status.osta_id=melis_ecom_order.ord_status',
            array('*'),$select::JOIN_LEFT);
        $select->join('melis_ecom_order_status_trans', 'melis_ecom_order_status_trans.ostt_status_id=melis_ecom_order_status.osta_id',
            array('*'),$select::JOIN_LEFT);
        
        $select->where('ord_id ='.$orderId);
        
        if (!is_null($langId))
        {
            $select->where('ostt_lang_id ='.$langId);
        }
//         echo $select->getSqlString();die();
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientLastOrderByClientId($clientID)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where('ord_client_id ='. $clientID);
        $select->order('ord_id DESC');
        $select->limit(1);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getCurrentMonth()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where('YEAR(ord_date_creation) = YEAR(CURRENT_DATE())');
        $select->where('MONTH(ord_date_creation) = MONTH(CURRENT_DATE())');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getAvgMonth()
    {
        $sql = 'SELECT AVG(`monthly`) AS average FROM (SELECT COUNT(*) as `monthly` from melis_ecom_order group by YEAR(`ord_date_creation`), MONTH(`ord_date_creation`)) AS average';
        $resultData = $this->tableGateway->getAdapter()->driver->getConnection()->execute($sql);
    
        return $resultData;
    }
    
    public function getClientOrderDetailsById($orderId, $clientId, $personId = null, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $join = new Expression('melis_ecom_order_status.osta_id = melis_ecom_order.ord_status');
        $select->join('melis_ecom_order_status', $join, array('*'),$select::JOIN_LEFT);
        $join = new Expression('melis_ecom_order_status_trans.ostt_status_id = melis_ecom_order.ord_status AND (ostt_lang_id = '.$langId.' OR ostt_lang_id IS NOT NULL)');
        $select->join('melis_ecom_order_status_trans', $join, array('*'),$select::JOIN_LEFT);
        
        $join = new Expression('melis_ecom_order_payment.opay_order_id = melis_ecom_order.ord_id');
        $select->join('melis_ecom_order_payment', $join, array('*'),$select::JOIN_LEFT);
        
        $join = new Expression('melis_ecom_order_payment_type.opty_id = melis_ecom_order_payment.opay_payment_type_id');
        $select->join('melis_ecom_order_payment_type', $join, array('*'),$select::JOIN_LEFT);
        
//         $join = new Expression('melis_ecom_coupon_order.cord_order_id=melis_ecom_order.ord_id AND cord_order_id='.$orderId);
//         $select->join('melis_ecom_coupon_order', $join,
//             array('*'),$select::JOIN_LEFT);
//         $join = new Expression('melis_ecom_coupon.coup_id=melis_ecom_coupon_order.cord_coupon_id');
//         $select->join('melis_ecom_coupon', $join,
//             array('*'),$select::JOIN_LEFT);
        
        $select->where('ord_id ='.$orderId);
        $select->where('ord_client_id ='.$clientId);
        
        if (!is_null($personId))
        {
            $select->where('ord_client_person_id ='.$personId);
        }
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getOrderPaymentWithTypeAndCouponByOrderId($orderId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        
        $select->where('ord_id ='.$orderId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getOrderCouponByOrderId($orderId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_coupon_order', 'melis_ecom_coupon_order.cord_order_id=melis_ecom_order.ord_id',
            array('*'),$select::JOIN_LEFT);
        
        $select->where('melis_ecom_coupon_order.cord_order_id ='.$orderId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }

    public function getOrdersDataByDate($order = 'ASC')
    {
        $select = $this->tableGateway->getSql()->select();
        $select->order(array('ord_date_creation' => $order));

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }



}