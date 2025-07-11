<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\Sql\Where;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\Operator;

class MelisEcomCountryTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_country';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'ctry_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getCountries()
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));
        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_country.ctry_currency_id', array('cur_symbol'), $select::JOIN_LEFT);
        $select->where->equalTo('ctry_status', 1);
        $select->order('ctry_name ASC');

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }

    public function getCountryCurrency($countryId, $status = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));
        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_country.ctry_currency_id', array('*'), $select::JOIN_LEFT);
        $select->where->equalTo('ctry_id', $countryId);

        if (!is_null($status)) {
            $select->where->equalTo('melis_ecom_currency.cur_status', 1);
            $select->where->equalTo('melis_ecom_country.ctry_status', 1);
        }

        $resultSet = $this->getTableGateway()->selectWith($select);
        return $resultSet;
    }

    public function getCountryList(array $options, $fixedCriteria = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $result = $this->getTableGateway()->select();

        $where = !empty($options['where']['key']) ? $options['where']['key'] : '';
        $whereValue = !empty($options['where']['value']) ? $options['where']['value'] : '';

        $order = !empty($options['order']['key']) ? $options['order']['key'] : '';
        $orderDir = !empty($options['order']['dir']) ? $options['order']['dir'] : 'ASC';

        $start = (int) $options['start'];
        $limit = (int) $options['limit'] === -1 ? $this->getTotalData() : (int) $options['limit'];

        $columns = $options['columns'];

        // check if there's an extra variable that should be included in the query
        $dateFilter = $options['date_filter'];
        $dateFilterSql = '';

        if (count($dateFilter)) {
            if (!empty($dateFilter['startDate']) && !empty($dateFilter['endDate'])) {
                $dateFilterSql = '`' . $dateFilter['key'] . '` BETWEEN \'' . $dateFilter['startDate'] . '\' AND \'' . $dateFilter['endDate'] . '\'';
            }
        }

        $select->join('melis_ecom_currency', 'melis_ecom_currency.cur_id = melis_ecom_country.ctry_currency_id', array('*'), $select::JOIN_LEFT);

        if (!empty($where)) {
            $w = new Where();
            $p = new PredicateSet();
            $filters = array();
            $likes = array();
            foreach ($columns as $colKeys) {
                $likes[] = new Like($colKeys, '%' . $whereValue . '%');
            }

            if (!empty($dateFilterSql)) {
                $filters = array(new PredicateSet($likes, PredicateSet::COMBINED_BY_OR), new \Laminas\Db\Sql\Predicate\Expression($dateFilterSql));
            } else {
                $filters = array(new PredicateSet($likes, PredicateSet::COMBINED_BY_OR));
            }
            $fixedWhere = array(new PredicateSet(array(new Operator('', '=', ''))));
            if (is_null($fixedCriteria)) {
                $select->where($filters);
            } else {
                $select->where(array(
                    $fixedWhere,
                    $filters,
                ), PredicateSet::OP_AND);
            }
        }

        // used when column ordering is clicked
        if (!empty($order))
            $select->order($order . ' ' . $orderDir);

        $getCount = $this->getTableGateway()->selectWith($select);
        $this->setCurrentDataCount((int) $getCount->count());

        // this is used in paginations
        $select->limit($limit);
        $select->offset($start);

        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }


    /**
     * Get Category Country/Countries by Category ID
     * @param int $categoryId, Id of category
     * @param boolean $onlyValid, only return active country if true else return all
     * 
     * @return NULL|\Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getCategoryCountriesByCategoryId($categoryId, $onlyValid = false)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->columns(array('*'));

        $select->join('melis_ecom_country_category', 'melis_ecom_country_category.ccat_country_id = melis_ecom_country.' . $this->idField, array(), $select::JOIN_RIGHT);
        $select->join('melis_ecom_category', 'melis_ecom_category.cat_id = melis_ecom_country_category.ccat_category_id', array(), $select::JOIN_LEFT);
        $select->group('ctry_id');

        if ($onlyValid) {
            $select->where('melis_ecom_country.ctry_status = 1');
        }

        $select->where->equalTo('melis_ecom_country_category.ccat_category_id,', (int)$categoryId);

        $dataCategory = $this->getTableGateway()->selectWith($select);
        return $dataCategory;
    }
}
