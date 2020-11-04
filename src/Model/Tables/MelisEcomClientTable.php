<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomClientTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_client';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'cli_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getClientList($countryId = null, $dateCreationMin = null, $dateCreationMax = null, 
	                              $onlyValid = null, $start = 0, $limit = null, $order = array(), $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->quantifier('DISTINCT');
        $select->join('melis_ecom_client_person', 'melis_ecom_client_person.cper_client_id=melis_ecom_client.cli_id',
            array(),$select::JOIN_LEFT);
        $select->join('melis_ecom_client_company', 'melis_ecom_client_company.ccomp_client_id=melis_ecom_client.cli_id',
            array(),$select::JOIN_LEFT);
        $select->join('melis_ecom_client_groups', 'melis_ecom_client_groups.cgroup_id=melis_ecom_client.cli_group_id',
            array('cgroup_name'),$select::JOIN_LEFT);
        
        if (!is_null($countryId)){
            $select->where('melis_ecom_client.cli_country_id ='.$countryId);
        }
        
        if (!is_null($dateCreationMin)){
            $select->where->greaterThan('melis_ecom_client.cli_date_creation', $dateCreationMin);
        }
        
        if (!is_null($dateCreationMax)){
            $select->where->lessThan('melis_ecom_client.cli_date_creation', $dateCreationMax);
        }
        
        if($onlyValid == 'active'){
            $onlyValid = 1;
        }
        
        if($onlyValid == 'inactive'){
            $onlyValid = 0 ;
        }
        
        if (!is_null($onlyValid)&&in_array($onlyValid, array('0','1'))){
            $select->where('cli_status ='.$onlyValid);
        }
        
        if(!is_null($search)){
            $search = '%'.$search.'%';
            $select->where->NEST->like('cli_id', $search)
            ->or->like('melis_ecom_client_person.cper_name', $search)
            ->or->like('melis_ecom_client_person.cper_firstname', $search)
            ->or->like('melis_ecom_client_company.ccomp_name', $search)
            ->or->like('melis_ecom_client_groups.cgroup_name', $search);
        }
        
        if (!is_null($start) && is_numeric($start))
        {
            $select->offset((int)$start);
        }
        
        if (!is_null($limit) && is_numeric($limit) && $limit != -1){
            $select->limit((int)$limit);
        }
        
        $select->order(array('cli_id' => $order));

        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getClientByEmailAndPassword($personEmail, $personPassword)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_client_person', 'melis_ecom_client_person.cper_client_id=melis_ecom_client.cli_id',
                        array(),$select::JOIN_LEFT);
        $select->where(array('cper_email' => $personEmail,'melis_ecom_client_person.cper_password' => $personPassword));
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getCouponClientList($langId = null, $onlyValid = null, $isMain = null, $couponId = null, $start = 0, $limit = null, $order = 'cli_id ASC', $search = null)
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
        
        if (!is_null($isMain))
        {
            $select->where('melis_ecom_client_person.cper_is_main_person ='.$isMain);
        }
        
        if (!is_null($start))
        {
            $select->offset($start);
        }
    
        if (!is_null($limit)&&$limit!=-1)
        {
            $select->limit($limit);
        }
        
        $select->where('melis_ecom_client.cli_status = 1');
    
        $select->order($order);
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getCurrentMonth()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where('YEAR(cli_date_creation) = YEAR(CURRENT_DATE())');
        $select->where('MONTH(cli_date_creation) = MONTH(CURRENT_DATE())');
    
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getAvgMonth()
    {
        $sql = 'SELECT AVG(`monthly`) AS average FROM (SELECT COUNT(*) as `monthly` from melis_ecom_client group by YEAR(`cli_date_creation`), MONTH(`cli_date_creation`)) AS average';
        $resultData = $this->tableGateway->getAdapter()->driver->getConnection()->execute($sql);
    
        return $resultData;
    }
}