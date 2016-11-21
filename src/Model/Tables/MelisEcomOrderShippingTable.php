<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomOrderShippingTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'oship_id';
    }
    
    public function getOrderShippingByOrderId($orderId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where('oship_order_id ='.$orderId);
        $select->order('oship_date_sent');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
}