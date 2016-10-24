<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomCivilityTransTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'civt_id';
    }
    
    public function getCivilityByLangId($langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        if (!is_null($langId))
        {
            $select->where('civt_lang_id ='.$langId);
        }
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    
    public function getCivilityTransByCivilityId($civt_civ_id, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $clause = array();
        $clause['civt_civ_id'] = (int) $civt_civ_id;
        if (!is_null($langId))
        {
            $clause['civt_lang_id'] = (int) $langId;
        }
        
        $select->where($clause);
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
}