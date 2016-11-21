<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomCountryCategoryTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'ccat_id';
    }

    public function getCountriesByCategoryId($categoryId)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->join('melis_ecom_country', 'melis_ecom_country.ctry_id = melis_ecom_country_category.ccat_country_id',
            array('*'), $select::JOIN_LEFT);

        $select->where->equalTo('melis_ecom_country_category.ccat_category_id', (int) $categoryId)->and->equalTo('melis_ecom_country.ctry_status', 1);

        $resultSet = $this->tableGateway->selectwith($select);

        return $resultSet;
    }
    
}