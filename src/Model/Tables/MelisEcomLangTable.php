<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomLangTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'elang_id';
    }
    
    // get all lang ordered by name ASC
    public function langOrderByName()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where->equalTo('elang_status', 1);
        $order = 'elang_name ASC';
        $select->order($order);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
}