<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomOrderStatusTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'osta_id';
    }
    
    public function getOrderStatusListByLangId($langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_order_status_trans', 'melis_ecom_order_status_trans.ostt_status_id=melis_ecom_order_status.osta_id',
            array('*'),$select::JOIN_LEFT);
        if (!is_null($langId))
        {
            $select->where('ostt_lang_id ='.$langId);
        }
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
}