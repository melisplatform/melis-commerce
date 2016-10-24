<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomBasketPersistentTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'bper_id';
    }
    
    public function getbasketPersistentByClientIdAndVariantId($variantId, $clientId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where('bper_client_id ='. $clientId);
        $select->where('bper_variant_id ='. $variantId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
}