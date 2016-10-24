<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomClientCompanyTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'ccomp_id';
    }
    
    public function getClientCompanyByClientId($clientId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->where('ccomp_client_id ='.$clientId);
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
}