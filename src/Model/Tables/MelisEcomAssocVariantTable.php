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

        $select->where->nest->equalTo('avar_one', $varOne)->and->equalTo('avar_two', $varTwo)->unnest
        ->or->nest->equalTo('avar_one', $varTwo)->and->equalTo('avar_two', $varOne)->unnest;
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;


    }
}