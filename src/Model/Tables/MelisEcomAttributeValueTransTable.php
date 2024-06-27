<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomAttributeValueTransTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_attribute_value_trans';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'avt_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
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