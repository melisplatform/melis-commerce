<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\Sql\Predicate\Expression;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\Operator;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\Sql\Where;
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

    public function clientList(array $options)
    {
        $select = $this->getTableGateway()->getSql()->select();
    
        if (isset($options['count'])) {
            $totalRecords = new Expression('COUNT(cli_id)');
            $select->columns(['total_records' => $totalRecords]);
        } else {
            $clientGroup = new Expression('(SELECT cgroup_name FROM melis_ecom_client_groups WHERE cgroup_id = melis_ecom_client.cli_group_id)');
            $contactName = new Expression('(SELECT CONCAT(COALESCE(cper_firstname)," ",COALESCE(cper_middle_name)," ",COALESCE(cper_name)) FROM melis_ecom_client_person WHERE cper_client_id = melis_ecom_client.cli_id ORDER BY cper_is_main_person DESC LIMIT 1)');
            $clientCompany = new Expression('(SELECT ccomp_name FROM melis_ecom_client_company WHERE ccomp_client_id = melis_ecom_client.cli_id)');
            $dateLastOrder = new Expression('(SELECT ord_date_creation FROM melis_ecom_order WHERE ord_client_id = melis_ecom_client.cli_id ORDER BY ord_date_creation DESC LIMIT 1)');
            $totalNumberOrder = new Expression('(SELECT COUNT(ord_id) FROM melis_ecom_order WHERE ord_client_id = melis_ecom_client.cli_id ORDER BY ord_date_creation DESC LIMIT 1)');
            $select->columns(['*', 'client_group' => $clientGroup, 'contact_person' => $contactName, 'client_company' => $clientCompany, 'total_num_order' => $totalNumberOrder, 'date_last_order' => $dateLastOrder]);
        }
    
        // Options
        $whereValue = $options['where']['value'] ?? '';

        // Search statement
        if(!empty($whereValue)) {
            $columns = $options['columns'];

            // Table columns prefix
            $tableColPrefix = [];
            foreach ($columns As $col) {

                if (!is_bool(strpos($col, 'cli'))) {
                    $tableColPrefix['cli'][] = $col;
                }
                elseif (!is_bool(strpos($col, 'cper'))) {
                    $tableColPrefix['cper'][] = $col;
                }
                elseif (!is_bool(strpos($col, 'ccomp'))) {
                    $tableColPrefix['ccomp'][] = $col;
                }
            }

            $filters = [];
            foreach ($tableColPrefix As $prefix => $cols) {
                $likes = null;
                foreach($cols as $colKeys)
                    $likes[] = $colKeys . ' LIKE \'%' . $whereValue . '%\'';

                switch ($prefix) {
                    case 'cli':
                            $filters[] = '(' . implode(' OR ', $likes) .')';
                        break;
                    case 'cper':
                        if (empty($like))
                            $filters[] = '(SELECT cper_client_id FROM melis_ecom_client_person WHERE (' . implode(' OR ', $likes) . ') and cper_client_id = melis_ecom_client.cli_id)';
                        break;
                    case 'ccomp':
                        if (empty($like))
                            $filters[] = '(SELECT ccomp_client_id FROM melis_ecom_client_company WHERE (' . implode(' OR ', $likes) . ') and ccomp_client_id = melis_ecom_client.cli_id)';
                        break;
                }
            }

            if (!empty($filters))
                $select->where('(' . implode(' OR ', $filters) .')');
        }

        $select->where('(SELECT cper_id FROM melis_ecom_client_person WHERE cper_client_id = melis_ecom_client.cli_id LIMIT 1) IS NOT NULL');

        // Client group
        $groupId = $options['groupId'];
        if (!empty($groupId)) 
            $select->where(['cli_group_id' => $groupId]);

        if (!isset($options['count'])) {
            // Order
            $order = !empty($options['order']['key']) ? $options['order']['key'] : '';
            $orderDir = !empty($options['order']['dir']) ? $options['order']['dir'] : 'ASC';
            $select->order($order . ' ' . $orderDir);

            // Start and Limit/Offset
            $start = (int) $options['start'];
            $limit = (int) $options['limit'];
            $select->offset($start);
            $select->limit($limit);
        }

        return $this->getTableGateway()->selectWith($select);
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