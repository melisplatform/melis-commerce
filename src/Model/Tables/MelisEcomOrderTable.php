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

class MelisEcomOrderTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'ord_id';
    }
    
    public function getOrderList($clientId = null, $clientPersonId = null, $couponId = null,
                                 $reference = null, $dateCreationMin = null, $dateCreationMax = null, 
	                             $status = null, $start = 0, $limit = null, $order = 'ord_id', $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->quantifier('DISTINCT');
        $select->join('melis_ecom_coupon_order', 'melis_ecom_coupon_order.cord_order_id = melis_ecom_order.ord_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_client', 'melis_ecom_client.cli_id = melis_ecom_order.ord_client_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_client_company', 'melis_ecom_client_company.ccomp_client_id = melis_ecom_client.cli_id', array(), $select::JOIN_LEFT);
        $select->join('melis_ecom_client_person', 'melis_ecom_client_person.cper_id = melis_ecom_order.ord_client_person_id', array(), $select::JOIN_LEFT);
        
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
        
        if (!is_null($dateCreationMin))
        {
            $select->where('melis_ecom_order.ord_date_creation >= "'.$dateCreationMin.'"');
        }
        
        if (!is_null($dateCreationMax))
        {
            $select->where('melis_ecom_order.ord_date_creation <= "'.$dateCreationMax.'"');
        }
        
        if (!is_null($status)&&is_bool($status))
        {
            $select->where('melis_ecom_order.ord_status ='.$status);
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
        
        $select->order($order);
        
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
        $select->order('ord_id', 'DESC');
        $select->limit(1);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
}