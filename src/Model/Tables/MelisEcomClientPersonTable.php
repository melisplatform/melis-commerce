<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomClientPersonTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'cper_id';
    }
    
    public function getClientPersonByClientId($clientId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility', 
                    array('*'), $select::JOIN_LEFT);
        
        $select->where('cper_client_id ='.$clientId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientPersonByPersonId($personId)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);
    
        $select->where('cper_id ='.$personId);
    
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientPersonByClientIdPersonIdAndPersonEmail($clientId, $personId = null, $personEmail = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);
        
        $select->where('cper_client_id ='.$clientId);
        
        if (!is_null($personId)){
            $select->where('cper_id ='.$personId);
        }
        
        if (!is_null($personEmail)){
            $select->where('cper_email = "'.$personEmail.'"');
        }
        
        $select->order('cper_is_main_person DESC', 'cper_firstname', 'cper_name');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientMainPersonByClientId($clientId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_civility', 'melis_ecom_civility.civ_id=melis_ecom_client_person.cper_civility',
            array('*'), $select::JOIN_LEFT);
        
        $select->where('cper_client_id ='.$clientId);
        $select->where('cper_is_main_person = 1');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
}