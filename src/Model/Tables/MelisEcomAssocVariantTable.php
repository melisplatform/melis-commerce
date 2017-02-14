<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomAssocVariantTable extends MelisEcomGenericTable
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'avar_id';
    }

    public function getVariantAssociationData($varOne, $varTwo)
    {
        $select = $this->tableGateway->getSql()->select();
        $varOne = (int) $varOne;
        $varTwo = (int) $varTwo;

        $select->columns(array('*'));

        $select->where->nest->equalTo('avar_one', $varOne)->and->equalTo('avar_two', $varTwo)->unnest;
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;


    }
    
    public function getVariantAssociationById($variantId, $searchValue = null, $start = 0, $limit = null, $column = null, $order = 'ASC')
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('melis_ecom_variant', 'melis_ecom_variant.var_id = melis_ecom_assoc_variant.avar_two', array('*'), $select::JOIN_LEFT);
       
        $select->where->equalTo('melis_ecom_assoc_variant.avar_one', $variantId);
        
        if (!is_null($column)) {
            $select->order($column . ' ' . $order);
        }
        
        
        if(!is_null($limit)) {
            $select->limit( (int) $limit);
        }
        
        if(!is_null($start)) {
            $select->offset( (int) $start);
        }
        
        $resultData = $this->tableGateway->selectWith($select);
        
        return $resultData;
    }
}