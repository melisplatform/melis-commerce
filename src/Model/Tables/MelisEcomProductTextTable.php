<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;

class MelisEcomProductTextTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_product_text';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'ptxt_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getProductTextById($productId, $productTextCode = null, $langId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));
        $select->join('melis_ecom_product_text_type', 'melis_ecom_product_text_type.ptt_id = melis_ecom_product_text.ptxt_type', array('*'), $select::JOIN_LEFT);

        $where = new Where();
        $nest = $where->nest();

        $nest->equalTo('melis_ecom_product_text.ptxt_prd_id', $productId);

        if (!is_null($productTextCode)) {
            $nest->equalTo('melis_ecom_product_text_type.ptt_code', $productTextCode);
        }

        if (!is_null($langId)) {
            $nest->equalTo('melis_ecom_product_text.ptxt_lang_id', $langId);
        }

        $nest = $nest->where->nest();
        $nest->isNotNull('melis_ecom_product_text.ptxt_field_short');
        $nest->OR->isNotNull('melis_ecom_product_text.ptxt_field_long');

        if (!is_null($langId)) {
            $nest = $where->OR->nest();
                    $nest->OR->equalTo('ptxt_prd_id', $productId);
                    $nest->isNotNull('melis_ecom_product_text.ptxt_lang_id');

            $nest = $nest->where->nest();
                    $nest->isNotNull('melis_ecom_product_text.ptxt_field_short');
                    $nest->OR->isNotNull('melis_ecom_product_text.ptxt_field_long');
        }

        $select->where($where);
        $select->order('ptt_id ASC');

        $resultSet = $this->getTableGateway()->selectwith($select);

        return $resultSet;
    }
    
    public function getProductTextLangId($productId) 
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('ptxt_lang_id'));

        $select->where->equalTo('ptxt_prd_id', $productId);
        
        $select->order('ptxt_id ASC');
        $resultSet = $this->getTableGateway()->selectwith($select);
        
        return $resultSet;
    }
    
    public function getProductTextsByProductId($productId, $langId = 1) 
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));

        $select->where->equalTo('ptxt_prd_id', $productId)->and->equalTo('ptxt_lang_id', $langId);
        
        $select->order('ptxt_id ASC');
        $resultSet = $this->getTableGateway()->selectwith($select);
        
        return $resultSet;
    }
    
    public function getProductTextsWithLang($productId, $langId = 1) 
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));

        $select->join('melis_ecom_lang', 'melis_ecom_lang.elang_id = melis_ecom_product_text.ptxt_lang_id', array('*'), $select::JOIN_LEFT);

        $select->where->equalTo('ptxt_prd_id', $productId)->and->equalTo('ptxt_lang_id', $langId);
        
        $resultSet = $this->getTableGateway()->selectwith($select);
        
        return $resultSet;
    }
    
}