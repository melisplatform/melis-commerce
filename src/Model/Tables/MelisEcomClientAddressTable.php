<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomClientAddressTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'cadd_id';
    }
    
    public function getClientAddressByClientId($clientId, $addressType = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_client_address_type', 'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),$select::JOIN_LEFT);
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_address.cadd_civility',
            array('*'), $select::JOIN_LEFT);
        
        $select->where('cadd_client_id ='.$clientId);
        
        if (!is_null($addressType)){
            $select->where->equalTo('cadd_type',$addressType);
        }
        
        $select->where('cadd_client_person IS NULL');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getPersonAddressByPersonId($personId, $addressType = null)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->join('melis_ecom_client_address_type', 'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),$select::JOIN_LEFT);
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_address.cadd_civility',
            array('*'), $select::JOIN_LEFT);
    
        $select->where('cadd_client_person ='.$personId);
        
        if (!is_null($addressType)){
            $select->where('melis_ecom_client_address_type.catype_code = "'.$addressType.'"');
        }
    
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientPersonAddressByAddressId($personId, $addrId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where('cadd_client_person ='.$personId);
        $select->where('cadd_id ='.$addrId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientBillingAddresses($clientId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_client_address_type', 'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),$select::JOIN_LEFT);
        
        $select->where('cadd_client_id ='.$clientId);
        $select->where('cadd_client_person IS NULL');
        $select->where('catype_code = "BIL"');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getContactBillingAddresses($contactId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_client_address_type', 'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),$select::JOIN_LEFT);
        
        $select->where('cadd_client_person ='.$contactId);
        $select->where('catype_code = "BIL"');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientDeliveryAddresses($clientId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_client_address_type', 'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),$select::JOIN_LEFT);
        
        $select->where('cadd_client_id ='.$clientId);
        $select->where('cadd_client_person IS NULL');
        $select->where('catype_code = "DEL"');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getContactDeliveryAddresses($contactId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_client_address_type', 'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),$select::JOIN_LEFT);
        
        $select->where('cadd_client_person ='.$contactId);
        $select->where('catype_code = "DEL"');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    
}