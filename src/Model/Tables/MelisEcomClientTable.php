<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomClientTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'cli_id';
    }
    
    public function getClientList($countryId = null, $dateCreationMin = null, $dateCreationMax = null, 
	                              $onlyValid = null, $start = 0, $limit = null, $order = 'ASC')
    {
        $select = $this->tableGateway->getSql()->select();
        
        if (!is_null($countryId)){
            $select->where('cli_country_id ='.$countryId);
        }
        
        if (!is_null($dateCreationMin)){
            $select->where('cli_date_creation >='.$dateCreationMin);
        }
        
        if (!is_null($dateCreationMax)){
            $select->where('cli_date_creation <='.$dateCreationMax);
        }
        
        if (!is_null($onlyValid)&&in_array($onlyValid, array('0','1'))){
            $select->where('cli_status ='.$onlyValid);
        }
        
//         if (!is_null($limit)&&is_numeric($limit)){
//             $select->limit((int)$limit);
//         }
        
//         $select->offset((int)$start);
        
        $select->order(array('cli_id' => $order));
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientByEmailAndPassword($personEmail, $personPassword)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_client_person', 'melis_ecom_client_person.cper_client_id=melis_ecom_client.cli_id',
                        array(),$select::JOIN_LEFT);
        $select->where('cper_email ="'.$personEmail.'"');
        $select->where('cper_password ="'.$personPassword.'"');
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getCouponClientList($langId = null, $onlyValid = null, $couponId = null, $start = 0, $limit = null, $order = 'cli_id ASC', $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->quantifier('DISTINCT');
        $select->join('melis_ecom_client_person', 'melis_ecom_client_person.cper_client_id=melis_ecom_client.cli_id',
            array(),$select::JOIN_LEFT);
        $select->join('melis_ecom_client_company', 'melis_ecom_client_company.ccomp_client_id=melis_ecom_client.cli_id',
            array(),$select::JOIN_LEFT);
        $select->join('melis_ecom_civility_trans', 'melis_ecom_civility_trans.civt_civ_id=melis_ecom_client_person.cper_civility',
            array(),$select::JOIN_LEFT);
        $select->join('melis_ecom_coupon_client', 'melis_ecom_coupon_client.ccli_client_id=melis_ecom_client.cli_id',
            array(),$select::JOIN_LEFT);
        
        if(!is_null($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('cli_id', $search)
            ->or->like('melis_ecom_client_person.cper_name', $search)
            ->or->like('melis_ecom_client_person.cper_firstname', $search)
            ->or->like('melis_ecom_client_person.cper_email', $search)
            ->or->like('melis_ecom_client_company.ccomp_name', $search);
        }
        
        if (!is_null($langId))
        {
            $select->where('melis_ecom_civility_trans.civt_lang_id ='.$langId);
        }
        
        if (!is_null($couponId))
        {
             $select->where('melis_ecom_coupon_client.ccli_coupon_id ='.$couponId);
        }
        
        if (!is_null($onlyValid)&&in_array($onlyValid, array('0','1'))){
            $select->where('cli_status ='.$onlyValid);
        }
        
        if (!is_null($start))
        {
            $select->offset($start);
        }
    
        if (!is_null($limit)&&$limit!=-1)
        {
            $select->limit($limit);
        }
    
        $select->order($order);
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
}