<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomOrderMessageTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'omsg_id';
    }
    
    public function getOrderMessageByOrderId($orderId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where('omsg_order_id ='.$orderId);
        $select->order('omsg_date_creation');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
}