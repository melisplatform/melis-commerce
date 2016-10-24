<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomOrderAddressTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'oadd_id';
    }
    
    public function getOrderAddressesByOrderId($orderId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where('oadd_order_id ='.$orderId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getorderAddressByOrderAddressIdandTypeId($orderAddressId, $typeId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where('oadd_id ='.$orderAddressId);
        $select->where('oadd_type ='.$typeId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
}