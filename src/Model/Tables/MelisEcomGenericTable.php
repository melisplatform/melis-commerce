<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use MelisCore\Model\Tables\MelisGenericTable;
use Laminas\Db\Sql\Where;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\Operator;

class MelisEcomGenericTable extends  MelisGenericTable
{
	protected $cacheResults = false;

	public function getEntryById($id)
	{
		$cacheKey = get_class($this) . '_getEntryById_' . $id;
		$cacheConfig = 'commerce_memory_services';
		if ($this->cacheResults) {
			// Retrieve cache version if front mode to avoid multiple calls
			$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
			$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
			if (!empty($results)) return $results;
		}

		$rowset = $this->getTableGateway()->select(array($this->idField => (int)$id));

		if ($this->cacheResults)
			$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $rowset);

		return $rowset;
	}

	public function getEntryByField($field, $value)
	{
		$cacheKey = get_class($this) . '_getEntryByField_' . $field . '_' . $value;
		$cacheConfig = 'commerce_memory_services';
		if ($this->cacheResults) {
			// Retrieve cache version if front mode to avoid multiple calls
			$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
			$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
			if (!empty($results)) return $results;
		}

		$rowset = $this->getTableGateway()->select(array($field => $value));

		if ($this->cacheResults)
			$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $rowset);

		return $rowset;
	}

	public function getEntryByFieldUsingLike($field, $value)
	{
		$select = $this->getTableGateway()->getSql()->select();
		$where = new Where();
		$where->like($field, '%' . $value . '%');
		$select->where($where);
		$rowset = $this->getTableGateway()->selectWith($select);

		return $rowset;
	}

	public function updateWithMultipleCondition($data, $whereCondition = array())
	{
		$this->getTableGateway()->update($data, $whereCondition);
	}


	/**
	 * This is used whenever you want to implement a pagination on your data table
	 * @tutorial Array Structure
	 * array(
	 *           'where' => array(
	 *               'key' => 'pros_id',
	 *               'value' => $search,
	 *           ),
	 *           'order' => array(
	 *               'key' => $selCol,
	 *               'dir' => $sortOrder,
	 *           ),
	 *           'start' => $start,
	 *           'limit' => $length,
	 *           'columns' => $colId
	 *       )
	 * @param array $options
	 * @param array $fixedCriteria (optional)
	 * @return array
	 */
	public function getPagedData(array $options, $fixedCriteria = null)
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

		// this is used when searching
		if (!empty($where)) {
			$w = new where();
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

		$sql = $this->getTableGateway()->getSql();
		$raw = $sql->getSqlstringForSqlObject($select);

		return $resultSet;
	}

	public function getEntryByFields($fields)
	{
		$cacheKey = get_class($this) . '_getEntryByFields_' . implode(',', $this->array_map_assoc(function ($k, $v) {
			return "$k ($v)";
		}, $fields));
		$cacheConfig = 'commerce_memory_services';
		if ($this->cacheResults) {
			// Retrieve cache version if front mode to avoid multiple calls
			$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
			$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
			if (!empty($results)) return $results;
		}

		$select = $this->getTableGateway()->getSql()->select();
		$where = new Where();
		$select->where($fields);
		$rowset = $this->getTableGateway()->selectWith($select);

		if ($this->cacheResults)
			$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $rowset);

		return $rowset;
	}

	private function array_map_assoc($callback, $array)
	{
		$r = array();
		foreach ($array as $key => $value)
			$r[$key] = $callback($key, $value);
		return $r;
	}
}
