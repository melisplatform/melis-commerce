<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomOrderPaymentTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'opay_id';
    }
    
    public function getOrderPaymentByOrderId($orderId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_order_payment_type', 'melis_ecom_order_payment_type.opty_id=melis_ecom_order_payment.opay_payment_type_id',
            array('*'),$select::JOIN_LEFT);
        
        $select->where('opay_order_id ='.$orderId);
        $select->order('opay_date_payment');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
}