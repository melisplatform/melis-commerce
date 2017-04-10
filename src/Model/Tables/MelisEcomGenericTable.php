<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Predicate\Like;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Predicate\Predicate;
class MelisEcomGenericTable implements ServiceLocatorAwareInterface
{
	protected $serviceLocator;
	protected $tableGateway;
	protected $idField;
	protected $lastInsertId;
	protected $_selectedColumns;
	protected $_selectedValues;
	protected $_currentDataCount;
	
	protected $cacheResults = false;
	
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}
	
	public function setServiceLocator(ServiceLocatorInterface $sl)
	{
		$this->serviceLocator = $sl;
		return $this;
	}
	
	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}
	
	public function getTableGateway()
	{
	    return $this->tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		
		return $resultSet;
	}

	public function getEntryById($id)
	{
        $cacheKey = get_class($this) . '_getEntryById_' . $id;
        $cacheConfig = 'commerce_memory_services';
	    if ($this->cacheResults)
	    {
            // Retrieve cache version if front mode to avoid multiple calls
            $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
            $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
            if (!empty($results)) return $results;
	    } 
	    
		$rowset = $this->tableGateway->select(array($this->idField => (int)$id));
		
		if ($this->cacheResults)
	        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $rowset);
		
		return $rowset;
	}

	public function getEntryByField($field, $value)
	{
        $cacheKey = get_class($this) . '_getEntryByField_' . $field . '_' . $value;
        $cacheConfig = 'commerce_memory_services';
	    if ($this->cacheResults)
	    {
            // Retrieve cache version if front mode to avoid multiple calls
            $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
            $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
            if (!empty($results)) return $results;
	    } 
	    
		$rowset = $this->tableGateway->select(array($field => $value));
		
		if ($this->cacheResults)
	        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $rowset);
		
		return $rowset;
	}
	
	public function getEntryByFieldUsingLike($field, $value) 
	{
	    $select = $this->tableGateway->getSql()->select();
	    $where = new Where();
	    //$where->addPredicate(new Like($field, '%'.$value.'%'));
	    $where->like($field, '%'.$value.'%');
	    $select->where($where);
	    $rowset = $this->tableGateway->selectwith($select);

	    return $rowset;
	}

	public function deleteById($id)
	{
		return $this->tableGateway->delete(array($this->idField => (int)$id));
	}
	
	public function deleteByField($field, $value)
	{
		return $this->tableGateway->delete(array($field => $value));
	}
	
	public function save($datas, $id = null)
	{
		$id = (int) $id;
		
		if ($this->getEntryById($id)->current())
		{
			$this->tableGateway->update($datas, array($this->idField => $id));
			return $id;
		} 
		else 
		{
			$this->tableGateway->insert($datas);
			$insertedId = $this->tableGateway->lastInsertValue;
			return $insertedId;
		}
	}
	
	public function update($datas, $whereField, $whereValue)
	{
	    if($this->getEntryByField($whereField, $whereValue)->current())
	    {
	        $this->tableGateway->update($datas, array($whereField => $whereValue));
	    }
	}
	
	public function updateWithMultipleCondition($data, $whereCondition = array())
	{
        $this->tableGateway->update($data, $whereCondition);
	}
	
	public function getLastInsertId()
	{
		return $this->lastInsertId;
	}
	
	protected function aliasColumnsFromTableDefinition($serviceTableName, $prefix)
	{
		$melisPageColumns = $this->serviceLocator->get($serviceTableName);
		
		$final = array();
		foreach ($melisPageColumns as $column)
			$final[$prefix . $column] = $column;
		
		return $final;
	}
	
	
	/**
	 * Returns the columns of the table
	 * @param Array $table
	 */
	public function getTableColumns()
	{
	    $metadata = new MetaData($this->tableGateway->getAdapter());
	    $columns = $metadata->getColumnNames($this->tableGateway->getTable());
	
	    return $columns;
	}
	
	/**
	 * Returns the data from a specific fields<br/>
	 * Sample Usage: fetchTableData(array('name, 'age'));<br/>
	 * If no parameter is supplied, this will automatically map<br/>
	 * to the table's columns.<br/>
	 *
	 * @param Array $columns | optional
	 * @return Array $data
	 */
	public function fetchData($columns = null)
	{
	
	    // auto populate columns with arrays from the existing Table
	    // when user does not supply a parameter to this function
	    if($columns == null)
	    {
	        $this->_selectedColumns = $this->getTableColumns();
	    }
	    else
	    {
	        if(is_array($columns))
	        {
	            $this->_selectedColumns = $columns;
	        }
	        else
	        {
	            throw new \InvalidArgumentException('Invalid argument provided on Column parameter');
	        }
	    }
	
	    $select = new Select();
	
	    $select->columns(array_keys($this->_selectedColumns));
	    $select->from($this->tableGateway->getTable());
	    $resultSet = $this->tableGateway->selectWith($select);
	    $resultSet->buffer();
	    $this->_selectedValues = $resultSet;
	
	    //return $resultSet = $this->getSelectedValues();
	    return $resultSet;
	
	}
	
	/**
	 * Returns the currently selected columns from a query
	 * @return Array
	 */
	public function getSelectedColumns()
	{
	    if(!empty($this->_selectedColumns))
	    {
	        return $this->_selectedColumns;
	    }
	    else
	    {
	        return $this->getTableColumns();
	    }
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
	    $select = $this->tableGateway->getSql()->select();
	    $result = $this->tableGateway->select();
	
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
	    
	    if(count($dateFilter)) {
	        if(!empty($dateFilter['startDate']) && !empty($dateFilter['endDate'])) {
	            $dateFilterSql = '`' . $dateFilter['key'] . '` BETWEEN \'' . $dateFilter['startDate'] . '\' AND \'' . $dateFilter['endDate'] . '\'';
	        }
	    }
	
        // this is used when searching
	    if(!empty($where)) {
	        $w = new Where();
	        $p = new PredicateSet();
	        $filters = array();
	        $likes = array();
	        foreach($columns as $colKeys)
	        {
	            $likes[] = new Like($colKeys, '%'.$whereValue.'%');
	        }
	        
	        if(!empty($dateFilterSql)) 
	        {
	            $filters = array(new PredicateSet($likes,PredicateSet::COMBINED_BY_OR), new \Zend\Db\Sql\Predicate\Expression($dateFilterSql));
	        }
	        else 
	        {
	            $filters = array(new PredicateSet($likes,PredicateSet::COMBINED_BY_OR));
	        }
	        $fixedWhere = array(new PredicateSet(array(new Operator('', '=', ''))));
	        if(is_null($fixedCriteria)) 
	        {
	            $select->where($filters);
	        }
	        else 
	        {
	            $select->where(array(
	                $fixedWhere,
	                $filters,
	            ), PredicateSet::OP_AND);
	        }
	        
	    }
	    
	   
	    // used when column ordering is clicked
	    if(!empty($order))
	        $select->order($order . ' ' . $orderDir);
	
	        
	    $getCount = $this->tableGateway->selectWith($select);
	    $this->setCurrentDataCount((int) $getCount->count());
	    
	    
        // this is used in paginations
        $select->limit($limit);
        $select->offset($start);

        $resultSet = $this->tableGateway->selectWith($select);
        
        $sql = $this->tableGateway->getSql();
        $raw = $sql->getSqlstringForSqlObject($select);
        
        return $resultSet;
        
	}
	
	
	/**
	 * Returns the total rows of the selected table
	 * @return int
	 */
	public function getTotalData($field = null, $idValue = null)
	{
	    $select = $this->tableGateway->getSql()->select();
	    
	    if (!empty($field) && !empty($idValue))
	    {
	    	$select->where(array($field => (int) $idValue));
	    }
	    
	    $resultSet = $this->tableGateway->selectWith($select);

	    return $resultSet->count();
	}
	
	/**
	 * Returns the total count of the filtered data
	 * @return int
	 */
	public function getTotalFiltered() 
	{
        return $this->_currentDataCount;
	}
	
	/**
	 * Returns all the data from the selected column
	 * @param String $filter
	 * @param array $columns
	 * @return Array
	 */
	public function getDataForExport($filter, $columns = array())
	{
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        
        $w = new Where();
        $filters = array();
        $likes = array();
        
        foreach($columns as $columnKeys) 
        {
            $likes[] = new Like($columnKeys, '%'.$filter.'%');
        }
        
        $filters = array(new PredicateSet($likes, PredicateSet::COMBINED_BY_OR));
        
        $select->where($filters);
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
        
	}
	
	public function getLastInsertedId()
	{
	    $adapter = $this->tableGateway->getAdapter();
	    $id = $adapter->getDriver()->getLastGeneratedValue(); 
	    return (int) $id;
	}
	
	/**
	 * @deprecated Do not use
	 * Returns the corresponding values in a column
	 */
	protected function getSelectedValues()
	{
	    $resultSet = array();
	    foreach($this->_selectedValues as $keys => $values)
	    {
	        // cast object into array
	        $resultSet[] = (array)$values;
	    }
	    return $resultSet;
	
	}
	
	protected function setCurrentDataCount($dataCount) 
	{
	   $this->_currentDataCount = $dataCount;   
	}
	
	protected function getRawSql($select)
	{
	    $sql = $this->tableGateway->getSql();
	    $raw = $sql->getSqlstringForSqlObject($select);
	
	    return $raw;
	}
	
}
