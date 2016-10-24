<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomBasketAnonymousTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'bano_id';
    }
    
    public function getBasketAnonymousByVarianIdAndClientKey($variantId, $clientKey)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where('bano_key = "'. $clientKey .'"');
        $select->where('bano_variant_id ='. $variantId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function cleanAnonymousBaskets($daysToKeep)
    {
        $delete = $this->tableGateway->getSql()->delete();
        
        $delete->where('bano_date_added < "'. $daysToKeep . '"');
        
        $resultData = $this->tableGateway->deleteWith($delete);
        return $resultData;
    }
    
}