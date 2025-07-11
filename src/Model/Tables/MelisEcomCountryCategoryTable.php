<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomCountryCategoryTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_country_category';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'ccat_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getCountriesByCategoryId($categoryId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join(
            'melis_ecom_country',
            'melis_ecom_country.ctry_id = melis_ecom_country_category.ccat_country_id',
            array('*'),
            $select::JOIN_LEFT
        );

        $select->where->equalTo('melis_ecom_country_category.ccat_category_id', (int) $categoryId)->and->equalTo('melis_ecom_country.ctry_status', 1);

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }
}
