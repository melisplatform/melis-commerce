<?php 

namespace MelisCommerce\Entity;

class MelisDocument
{
    protected $docId;
    protected $document;
    protected $docType;
    protected $docSubType;
    
    public function setDocumentID($id) 
    {
        $this->docId = $id;
    }
    
    public function getDocumentID()
    {
        return $this->docId;
    }
    
    public function setDocument($document)
    {
        $this->document = $document;
    }
    
    public function getDocument()
    {
        return $this->document;
    }
    
    public function setDocumentType($docType)
    {
        $this->docType = $docType;
    }
    
    public function getDocumentType()
    {
        return $this->docType;
    }
    
    public function setDocumentSubType($docSubType)
    {
        $this->docSubType = $docSubType;
    }
    
    public function getDocumentSubType()
    {
        return $this->docSubType;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}