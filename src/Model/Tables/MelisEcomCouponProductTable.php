<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomCouponProductTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'cprod_id';
    }
    
    public function checkCouponProductExist($couponId, $clientId, $productId = null)
    {
        
        $select = $this->tableGateway->getSql()->select();
        
        if(!is_null($clientId)){
            $select->join('melis_ecom_coupon_client', 'melis_ecom_coupon_client.ccli_coupon_id = cprod_coupon_id', array(), $select::JOIN_LEFT);
            $select->where->equalTo('ccli_client_id', $clientId);
        }
        
        $select->where->equalTo('cprod_coupon_id', $couponId);
        
        $select->where->equalTo('cprod_product_id', $productId);
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    public function deleteCouponproduct($couponId, $productId)
    {
        $delete = $this->tableGateway->getSql()->delete();
        
        $delete->where->equalTo('cprod_coupon_id', $couponId);
        $delete->where->equalTo('cprod_product_id', $productId);
        
        $resultData = $this->tableGateway->deleteWith($delete);
        return $resultData;
    }
    
}