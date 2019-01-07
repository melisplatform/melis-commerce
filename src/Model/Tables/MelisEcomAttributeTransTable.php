<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;

class MelisEcomAttributeTransTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'atrans_id';
    }
    
    public function getAttributeTransById($attributeTransId, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();

        $clause['melis_ecom_attribute_trans.atrans_id'] = (int) $attributeTransId;
        
        if(!is_null($langId)) {
            $clause['melis_ecom_attribute_trans.atrans_lang_id'] = (int) $langId;
        }
    
        if($clause){
            $select->where($clause);
        }
    
        $resultSet = $this->tableGateway->selectwith($select);
    
        return $resultSet;
    }
    
    public function getAttributeTransByAtributeId($attributeId, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));

        $where = new Where();
        $nest = $where->nest();

        $nest->equalTo('melis_ecom_attribute_trans.atrans_attribute_id', $attributeId);
        $nest->equalTo('melis_ecom_attribute_trans.atrans_lang_id', $langId);

        $nest = $where->OR->nest();
        $nest->equalTo('atrans_attribute_id', $attributeId);
        $nest->isNotNull('melis_ecom_attribute_trans.atrans_lang_id');

        $select->where($where);
    
        $resultSet = $this->tableGateway->selectwith($select);
    
        return $resultSet;
    }
    
}