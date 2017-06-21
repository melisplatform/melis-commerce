<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomStockEmailAlertTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'sea_id';
    }
    
    public function getStockEmailRecipients($productIds = array(-1))
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where->in('sea_prd_id', array($productIds));
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    public function setDefaultValues($datas)
    {
        $id = (int) $datas['sea_id'];
        
        if ($this->getEntryById($id)->current())
        {
            unset($datas['sea_id']);
            $this->tableGateway->update($datas, array($this->idField => $id));
            return $id;
        }
        else
        {
            $this->tableGateway->insert($datas);
            $insertedId = $this->tableGateway->lastInsertValue;
            return $insertedId;
        }
    }
}