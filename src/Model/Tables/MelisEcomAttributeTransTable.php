<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Where;

class MelisEcomAttributeTransTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_attribute_trans';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'atrans_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getAttributeTransById($attributeTransId, $langId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));
        $clause = array();

        $where = new Where();

        $where->equalTo('melis_ecom_attribute_trans.atrans_id', (int) $attributeTransId);

        if(!is_null($langId)) {
            $where->nest()
            ->equalTo('melis_ecom_attribute_trans.atrans_lang_id', (int) $langId)
            ->or
            ->isNotNull('melis_ecom_attribute_trans.atrans_name')
            ->unnest();
        }

        $select->where($where);

        $resultSet = $this->getTableGateway()->selectwith($select);
    
        return $resultSet;
    }
    
    public function getAttributeTransByAtributeId($attributeId, $langId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));

        $where = new Where();
        $nest = $where->nest();

        $nest->equalTo('melis_ecom_attribute_trans.atrans_attribute_id', $attributeId);
        $nest->equalTo('melis_ecom_attribute_trans.atrans_lang_id', $langId);

        $nest = $where->OR->nest();
        $nest->equalTo('atrans_attribute_id', $attributeId);
        $nest->isNotNull('melis_ecom_attribute_trans.atrans_lang_id');

        $select->where($where);
    
        $resultSet = $this->getTableGateway()->selectwith($select);
    
        return $resultSet;
    }
    
}