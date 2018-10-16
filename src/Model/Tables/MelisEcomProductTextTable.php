<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomProductTextTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'ptxt_id';
    }
    
    public function getProductTextById($productId, $productTextCode = null, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $select->join('melis_ecom_product_text_type', 'melis_ecom_product_text_type.ptt_id = melis_ecom_product_text.ptxt_type', array('*'), $select::JOIN_LEFT);
        
        if(!is_null($productTextCode)){            
            $clause['melis_ecom_product_text_type.ptt_code'] = $productTextCode;
        }
        
        if(!is_null($langId)){
            $clause['melis_ecom_product_text.ptxt_lang_id'] = (int) $langId;
        }
        
        $clause['melis_ecom_product_text.ptxt_prd_id'] = (int) $productId;
        $select->order('ptt_id ASC');
        if($clause){
            $select->where($clause);
        }
        $resultSet = $this->tableGateway->selectwith($select);

        return $resultSet;
    }
    
    public function getProductTextLangId($productId) 
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('ptxt_lang_id'));

        $select->where->equalTo('ptxt_prd_id', $productId);
        
        $select->order('ptxt_id ASC');
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getProductTextsByProductId($productId, $langId = 1) 
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));

        $select->where->equalTo('ptxt_prd_id', $productId)->and->equalTo('ptxt_lang_id', $langId);
        
        $select->order('ptxt_id ASC');
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getProductTextsWithLang($productId, $langId = 1) 
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));

        $select->join('melis_ecom_lang', 'melis_ecom_lang.elang_id = melis_ecom_product_text.ptxt_lang_id', array('*'), $select::JOIN_LEFT);

        $select->where->equalTo('ptxt_prd_id', $productId)->and->equalTo('ptxt_lang_id', $langId);
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
}