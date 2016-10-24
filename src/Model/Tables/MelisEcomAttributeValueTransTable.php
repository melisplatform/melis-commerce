<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomAttributeValueTransTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'avt_id';
    }
    
    public function getAttributeValueTransbyId($attributeValueTransId, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $clause['melis_ecom_attribute_value_trans.avt_id'] = (int) $attributeValueTransId;
        
        if(!is_null($langId)) {
            $clause['melis_ecom_attribute_value_trans.avt_lang_id'] = (int) $langId;
        }
        
        if($clause){
            $select->where($clause);
        }
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
}