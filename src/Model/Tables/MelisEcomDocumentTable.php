<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisEcomDocumentTable extends MelisEcomGenericTable 
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'doc_id';
    }
    
    public function getDocumentAndType($documentId) 
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        
        $select->join('melis_ecom_doc_type', 'melis_ecom_doc_type.dtype_id = melis_ecom_document.doc_type_id', array('*'), $select::JOIN_LEFT);
        
        $select->where(array('doc_id' => $documentId));
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getDocumentsRelationAndDocType($docRelation = 'product', $relationId, $typeCode = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
    
        $select->join('melis_ecom_doc_relations', 'melis_ecom_doc_relations.rdoc_doc_id = melis_ecom_document.doc_id', array('*'), $select::JOIN_LEFT)
               ->join('melis_ecom_doc_type', 'melis_ecom_doc_type.dtype_id = melis_ecom_document.doc_type_id', array('*'), $select::JOIN_LEFT);

       $clause['rdoc_'.$docRelation.'_id'] = (int) $relationId;
       if($typeCode) {
           $clause['dtype_code'] = $typeCode;
       }
       
       $select->where($clause);
    
        $resultSet = $this->tableGateway->selectwith($select);
    
        return $resultSet;
    }
    
    public function getDocumentRelationsAndDocSubType($docRelation = 'product', $relationId, $typeId = null, $subTypeId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $clause = array();
        
        $select->join('melis_ecom_doc_relations', 'melis_ecom_doc_relations.rdoc_doc_id = melis_ecom_document.doc_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_doc_type', 'melis_ecom_doc_type.dtype_id = melis_ecom_document.doc_subtype_id', array('*'), $select::JOIN_LEFT);
        
        $clause['rdoc_'.$docRelation.'_id'] = (int) $relationId;
        
        if($typeId) {
            $clause['melis_ecom_document.doc_type_id'] = (int) $typeId;
        }
        
        if($subTypeId) {
            $clause['melis_ecom_document.doc_subtype_id'] = (int) $subTypeId;
        }
        
        $select->where($clause);
        
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getDocumentsByParentTypeId($docRelation, $docRelationId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_doc_relations', 'melis_ecom_doc_relations.rdoc_doc_id = melis_ecom_document.doc_id', array('*'), $select::JOIN_LEFT);
        
        $select->where('melis_ecom_doc_relations.rdoc_'.$docRelation.'_id ='.$docRelationId);
                
        $resultSet = $this->tableGateway->selectwith($select);
        
        return $resultSet;
    }
    
    public function getDocumentsByRelationsAndTypes($docRelation, $relationId, $typeCode1 = null, $typeCode2 = array())
    {
        
        $select = $this->tableGateway->getSql()->select();
        
        $select->join('melis_ecom_doc_relations', 'melis_ecom_doc_relations.rdoc_doc_id = melis_ecom_document.doc_id', array('*'), $select::JOIN_LEFT)
        ->join('melis_ecom_doc_type', 'melis_ecom_doc_type.dtype_id = melis_ecom_document.doc_type_id', array('*'), $select::JOIN_LEFT)
        ->join(array('doc_sub_type' => 'melis_ecom_doc_type'), 'doc_sub_type.dtype_id = melis_ecom_document.doc_subtype_id', array('dtype_sub_code'=>'dtype_code'), $select::JOIN_LEFT);
        
       $select->where->equalTo('rdoc_'.$docRelation.'_id', $relationId);
        
       if(!is_null($typeCode1)){
           $select->where->equalTo('melis_ecom_doc_type.dtype_code', $typeCode1);
       }
       
       if(!empty($typeCode2)){
           $select->where->in('doc_sub_type.dtype_code', $typeCode2);
       }
//        echo $this->getRawSql($select); die();
       return $this->tableGateway->selectWith($select);
    }
}