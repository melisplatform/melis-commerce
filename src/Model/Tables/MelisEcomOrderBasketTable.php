<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomOrderBasketTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'obas_id';
    }
    
    public function getOrderBaskets($orderId, $start = 0, $limit = null, $search = null, $order = 'obas_id ASC')
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $clause['melis_ecom_order_basket.obas_order_id'] = (int) $orderId;
        $select->where($clause);
        if(!is_null($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('obas_product_name', $search)
            ->or->like('obas_id', $search)            
            ->or->like('obas_sku', $search)
            ->or->like('obas_quantity', $search)
            ->or->like('obas_price_net', $search);
        }
        
        if(!is_null($limit)) {
            $select->limit((int)$limit);
        }
        
        $select->order($order);
        $select->offset((int)$start);
        
        return $this->tableGateway->selectwith($select);
    }
}