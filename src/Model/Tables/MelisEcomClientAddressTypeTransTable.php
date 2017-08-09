<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomClientAddressTypeTransTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'catypt_id';
    }
    
    public function getAddressTypeTransByLangId($langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('melis_ecom_client_address_type', 'melis_ecom_client_address_type_trans.catypt_type_id = melis_ecom_client_address_type.catype_id', array('*'), $select::JOIN_LEFT);
        if (!is_null($langId))
        {
            $select->where('catypt_lang_id ='.$langId);
        }
        
        $resullData = $this->tableGateway->selectWith($select);
        return $resullData;
    }
    
    public function getAddressTransByAddressTypeIdAndLangId($addTypeId, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();        

        if($addTypeId) {
            $select->where('catypt_type_id ='.$addTypeId);
        }

        if (!is_null($langId))
        {
            $select->where('catypt_lang_id ='.$langId);
        }


        $resullData = $this->tableGateway->selectWith($select);


        return $resullData;
    }
}