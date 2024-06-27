<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisEcomDocRelationsTable extends MelisEcomGenericTable 
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_doc_relations';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'rdoc_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getVariantsDocumentsById($variantId, $countryId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $select->join('melis_ecom_document', 'melis_ecom_document.doc_id = melis_ecom_doc_relations.rdoc_doc_id',array('*'), $select::JOIN_LEFT);
        
        if(!is_null($countryId))
            $clause['melis_ecom_doc_relations.country_id'] = (int) $countryId;
        
        $clause['melis_ecom_doc_relations.rdoc_variant_id'] = (int) $variantId;
    
        $resultSet = $this->getTableGateway()->selectwith($select);
    
        return $resultSet;

    }
    
    
    public function getProductDefaultImageByProductId($productId = null, $countryId = null){
        
        $select = $this->getTableGateway()->getSql()->select();
        
        $select->join('melis_ecom_document', 'melis_ecom_document.doc_id = melis_ecom_doc_relations.rdoc_doc_id',
                        array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_doc_type', 'melis_ecom_doc_type.dtype_id = melis_ecom_document.doc_type_id',
            array('*'), $select::JOIN_LEFT);
        
        if (is_numeric($productId)){
            $select->where('rdoc_product_id ='.$productId);
        }
        
        if (is_numeric($countryId)){
            $select->where('rdoc_country_id ='.$countryId);
        }
        
        $select->where('dtype_code = "DEFAULT"');
        
        $resultSet = $this->getTableGateway()->selectwith($select);
        
        $docData = $resultSet->current();
        
        if (!empty($docData))
        {
            $resultSetData = $this->getDocumentDefaultData($docData->dtype_parent_type_id);
        }
        else 
        {
            $resultSetData = $this->getOneProductImageByProductId($productId);
        }
        
        return $resultSetData;
    }
    
    public function getDocumentDefaultData($docParentId){
        $select = $this->getTableGateway()->getSql()->select();
        $resultSet = $this->getTableGateway()->selectwith($select);
        
        $select->join('melis_ecom_document', 'melis_ecom_document.doc_id = melis_ecom_doc_relations.rdoc_doc_id',
            array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_doc_type', 'melis_ecom_doc_type.dtype_id = melis_ecom_document.doc_type_id',
            array('*'), $select::JOIN_LEFT);
        
        $select->where('dtype_id = '.$docParentId);
        
        $select->limit(1);
        
        return $resultSet;
    }
    
    public function getOneProductImageByProductId($productId){
    
        $select = $this->getTableGateway()->getSql()->select();
    
        $select->join('melis_ecom_document', 'melis_ecom_document.doc_id = melis_ecom_doc_relations.rdoc_doc_id',
            array('*'), $select::JOIN_LEFT);
        $select->join('melis_ecom_doc_type', 'melis_ecom_doc_type.dtype_id = melis_ecom_document.doc_type_id',
            array('*'), $select::JOIN_LEFT);
        
        $select->where('dtype_code = "IMG"');
        
        if (is_numeric($productId)){
            $select->where('rdoc_product_id ='.$productId);
        }
        
        $select->limit(1);
        
        $resultSet = $this->getTableGateway()->selectwith($select);
        return $resultSet;
    }
}