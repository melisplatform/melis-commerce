<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;

class MelisEcomProductCategoryTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway){
        parent::__construct($tableGateway);
        $this->idField = 'pcat_id';
    }
    
    public function getCategoryProductsByCategoryId($categoryId, $onlyValid = false){
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_product', 'melis_ecom_product.prd_id = melis_ecom_product_category.pcat_prd_id',
            array('*'), $select::JOIN_LEFT);
        
        if (is_bool($onlyValid)&&$onlyValid){
            $select->where('prd_status = 1');
        }
        
        $select->where('pcat_cat_id = '.$categoryId);
        $select->order(array('pcat_order' => 'ASC'));
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    public function getCategoryProductCount($categoryId)
    {
        $select = $this->tableGateway->getSql()->select();    
        $select->columns(array('count'=> new Expression('COUNT(DISTINCT pcat_prd_id)')));
        $select->where('pcat_cat_id = '.$categoryId);
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
}