<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomSeoTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'eseo_id';
    }
    
    public function getSeoByTypeAndId($type, $id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
    
        if ($type == 'categoryId')
            $select->where(array('eseo_category_id' => $id));
        else
            if ($type == 'productId')
                $select->where(array('eseo_product_id' => $id));
            else
                if ($type == 'variantId')
                    $select->where(array('eseo_variant_id' => $id));
                else
                    return null;
                
        $resultSet = $this->tableGateway->selectwith($select);
    
        return $resultSet;
    }
    
    public function getSeoUrlByType($type, $url)
    {
        $select = $this->tableGateway->getSql()->select();
        
        if (in_array($type, array('category', 'product', 'variant')))
        {
            $select->where('eseo_'.$type.'_id IS NOT NULL');
        }
        
        $select->where(array('eseo_url' => $url));
        
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
    
    public function getCategorySeoById($categoryId = null, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        if(!is_null($categoryId)){
            $select->where->equalTo('eseo_category_id', $categoryId);
        }
        
        if(!is_null($langId)){
            $select->where->equalTo('eseo_lang_id', $langId);
        }
        
        $resultSet = $this->tableGateway->selectwith($select);
        return $resultSet;
    }
    
}