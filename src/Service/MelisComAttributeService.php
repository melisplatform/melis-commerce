<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use MelisCore\Service\MelisCoreGeneralService;
use MelisCommerce\Entity\MelisAttribute;
/**
 *
 * This service handles the attribute system of MelisCommerce.
 *
 */
class MelisComAttributeService extends MelisCoreGeneralService
{
    /**
     * Returns all the data from `melis_ecom_attribute` table
     * @param int $langId
     * @param int $status
     * @param int $visible
     * @param int $searchable
     * @return MelisEcomAttributeTable | Attributes
     */
    public function getAttributes($langId = null, $status = null, $visible = null, $searchable = null,
                                  $start = null, $limit = null, $order = null, $search = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_attributes_start', $arrayParameters);
        
        // Service implementation start
        $attrTable = $this->getServiceLocator()->get('MelisEcomAttributeTable');
        $data = $attrTable->getAttributeList($arrayParameters['status'], $arrayParameters['visible'], $arrayParameters['searchable'],
                                             $arrayParameters['start'], $arrayParameters['limit'], $arrayParameters['order'], $arrayParameters['search']);
        foreach($data as $attr){
            $results[] = $this->getAttributeById($attr->attr_id, $arrayParameters['langId']);
        }
        
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_attributes_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * Returns an attribute from `melis_ecom_attribute` table
     * Retrieves data by attribute ID
     * 
     * @param int $attributeId attribute id to look for
     * @param int $langId lang id to look for
     * 
     * @return null | attribute object
     */
    public function getAttributeById($attributeId, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attribute_byid_start', $arrayParameters);
        
        // Service implementation start
        $entAttribute = new MelisAttribute();
        $attrTable = $this->getServiceLocator()->get('MelisEcomAttributeTable');
        $attrTransTable = $this->getServiceLocator()->get('MelisEcomAttributeTransTable');
        $attrValTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTable');
        foreach($attrTable->getEntryById($arrayParameters['attributeId']) as $data){
            $data->{'attr_trans'} = array();
            $attributeValues = array();
            $entAttribute->setId($data->attr_id);
            
            foreach($attrTransTable->getAttributeTransByAtributeId( $data->attr_id, $arrayParameters['langId']) as $attrTrans){
                $data->{'attr_trans'} = array_merge($this->getAttributeTransById($attrTrans->atrans_id, $arrayParameters['langId']), $data->{'attr_trans'});
            }
            
            $entAttribute->setAttribute($data);
            foreach($attrValTable->getEntryByField('atval_attribute_id', $data->attr_id) as $attrVal){
                $attributeValues = array_merge($attributeValues, $this->getAttributeValuesById($attrVal->atval_id, $arrayParameters['langId']));
            }
            $entAttribute->setAttributeValues($attributeValues);
            $results = $entAttribute;
        }
        
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attribute_byid_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This returns attribute translations from melis_ecom_attribute_trans table
     * Retrieves data by attribute trans ID
     * 
     * @param int $attributeTransId the ID to look for
     * @param int $langId the lang ID to look for
     * @return null | array of attributeTrans object
     */
    public function getAttributeTransById($attributeTransId, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributetrans_byid_start', $arrayParameters);
        $attrTransTable = $this->getServiceLocator()->get('MelisEcomAttributeTransTable');

        foreach($attrTransTable->getAttributeTransById($arrayParameters['attributeTransId'], $arrayParameters['langId']) as $data){
            $results[] = $data;
        }
        
        // Service implementation start
      
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributetrans_byid_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }

    public function getAttributeText($attributeId, $langId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributestext_byid_start', $arrayParameters);
        // Service implementation start
        $attribute = $this->getAttributeById((int) $arrayParameters['attributeId'], (int) $arrayParameters['langId']);
        if($attribute) {
            $value = $attribute->getAttribute()->attr_trans;
            if(isset($value[0])) {
                $value = $value[0];
                $value = trim($value->atrans_name);
                if(empty($value)) {
                    $value = $attribute->getAttribute()->attr_reference;
                }
            }
            else {
                $value = $attribute->getAttribute()->attr_reference;
            }
            // last checker
            if(empty($value)) {
                $value = $attribute->getAttribute()->attr_reference;
            }
            $results = $value;
        }

        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributestext_byid_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    
    /**
     * This returns a list of attribue values from melis_ecom_attribute_value table
     * 
     * @param int $attributeId If specified, will look for attribute values linked to attributeId
     * @param int $langId  If specified, will look for the language id
     * @param int $start Used for table functions
     * @param int $limit Used for table functions
     * @param varchar $order Used for table functions
     * @param varchar $search Used for table functions
     * @param varchar $valCol Used for table functions
     * 
     * @return array object| null of attribute Values object
     */
    public function getAttributeValuesList($attributeId = null, $langId = null, $start = null, $limit = null, $order = null, $search = null, $valCol = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevalue_list_start', $arrayParameters);
        
        // Service implementation start
        $attrValTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTable');       
        foreach($attrValTable->getAttributeValuesList($arrayParameters['attributeId'], $arrayParameters['langId'], $arrayParameters['start'],
                                                      $arrayParameters['limit'], $arrayParameters['order'], $arrayParameters['search'], $arrayParameters['valCol']) as $attrVal){
            $results[] = $this->getAttributeValuesById($attrVal->atval_id, $arrayParameters['langId'])[0];
        }
        // Service implementation end
        
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevalue_list_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * 
     * This method gets the attribute values
     * attribute value will come back with attribute value and attribute value trans
     * 
     * @param int $attributeValueId If Specified, the attributeValueId to look for
     * @param int $attributeId If Specified, the attributeId to look for
     * @param int $langId If specified, translations of attribute values will be limited to that lang
     * 
     * @return MelisAttributeValue and attribute Value trans | null attributevalue object
     * 
     */
    public function getAttributeValuesById($attributeValueId, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevalue_byid_start', $arrayParameters);
        
        // Service implementation start
        $attrValTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTable');
        $attrValTransTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTransTable');
        foreach($attrValTable->getAttributeValuesById($arrayParameters['attributeValueId']) as $data){
            $data->{'atval_trans'} = array();
            foreach($attrValTransTable->getEntryByField('av_attribute_value_id', $data->atval_id) as $atvalTrans){
                $data->{'atval_trans'} = array_merge($data->{'atval_trans'}, $this->getAttributeValueTransById($atvalTrans->avt_id, $arrayParameters['langId']));
            }
            $results[] = $data;
        }
     
        // Service implementation end
        
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevalue_byid_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * 
     * $this method gets the attribute value trans
     * attribute value trans wil lcome back with attribute value trans 
     * 
     * @param int $attributeValueTransId If specified, the id to look for
     * @param unknown $attributeValueId If specified, the id to look for
     * @param unknown $langId If specified, translations of attribute values will be limited to that lang
     * 
     * @return MelisAttributeValueTrans | null object
     */
    public function getAttributeValueTransById($attributeValueTransId, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_start', $arrayParameters);
        
        // Service implementation start
        $attrValTransTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTransTable');
        
        foreach($attrValTransTable->getAttributeValueTransbyId($arrayParameters['attributeValueTransId'], $arrayParameters['langId']) as $data){
            $results[]= $data;
        }
        
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method saves the attribute and its translations values
     * 
     * @param array $attribute attribute reflecting the melis_ecom_attribute table
     * @param array $attributeTrans array of attribute translations reflecting the melis_ecom_attribute_trans table
     * @param int $attributeId The attribute id, if specified will perform an update
     * 
     * @return int id of the updated attribute, otherwise false
     */
    public function saveAttribute($attribute, $attributeTrans = null, $attributeId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_start', $arrayParameters);
        
        // Service implementation start
        $attributeTable = $this->getServiceLocator()->get('MelisEcomAttributeTable');
        try {
            $results = $attributeTable->save($arrayParameters['attribute'], $arrayParameters['attributeId']);
            if(!empty($arrayParameters['attributeTrans'])){
                foreach($arrayParameters['attributeTrans'] as $trans){
                    $attributeTransId = empty($trans['atrans_id'])? null : $trans['atrans_id'];
                    $trans['atrans_attribute_id'] = empty($trans['atrans_attribute_id'])? $results : $trans['atrans_attribute_id'];
                    unset($trans['atrans_id']);
                    $this->saveAttributeTrans($trans, $attributeTransId);
                }  
            }            
            
        }catch (\Exception $e){
            echo $e->getMessage(); die();
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method saves the attribute translations values
     * 
     * @param array $attributeTrans The attribute translations reflecting the melis_ecom_attribute_trans talbe
     * @param int $attributeTransId The attribute trans id, if specified will perform an update
     * 
     * @return int id of the updated attribute trans, otherwise false
     */
    public function saveAttributeTrans($attributeTrans, $attributeTransId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_start', $arrayParameters);
        
        // Service implementation start
        $attributeTransTable = $this->getServiceLocator()->get('MelisEcomAttributeTransTable');
        try {
            $results = $attributeTransTable->save($arrayParameters['attributeTrans'], $arrayParameters['attributeTransId']);            
            
        }catch (\Exception $e){
            echo $e->getMessage(); die();
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method saves the attribute values
     * 
     * @param array $attributeValue the attribute value reflecting the melis_ecom_attribute_value table
     * @param int $attributeValueId the attribute value id, if specified an update will be performed
     * 
     * @return int id of the inserted attribute value, otherwise false
     */
    public function saveAttributeValue($attributeValue, $attributeValueId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_start', $arrayParameters);
        
        // Service implementation start
        $attributeValueTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTable');
            try {
                $results = $attributeValueTable->save($arrayParameters['attributeValue'], $arrayParameters['attributeValueId']);
            }catch(\Exception $e){
                echo $e->getMessage(); die();
            }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method save the attribute value translations
     * 
     * @param array $attributeValueTrans the attribute value trans reflecting the melis_ecom_attribute_value_trans table
     * @param int $attributeValueTransId the attribute value trans id, if specified an update will be performed
     * 
     * @return int id of the last insert or update, otherwise false on error
     */
    public function saveAttributeValueTrans($attributeValueTrans, $attributeValueTransId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_start', $arrayParameters);
        
        // Service implementation start
        $attributeValueTransTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTransTable');
        try {
            $results = $attributeValueTransTable->save($arrayParameters['attributeValueTrans'], $arrayParameters['attributeValueTransId']);
        }catch(\Exception $e){
            echo $e->getMessage(); die();
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method deletes the attribute, attribute trans, attribute values, 
     * attributes assign to product and attribute values assign variants
     * 
     * @param int $attributeId, the attribute ID to be deleted
     * @return boolean true on success, otherwise false on error
     */
    public function deleteAttributeById($attributeId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_attribute_start', $arrayParameters);
        
        // Service implementation start
        $attributeTable = $this->getServiceLocator()->get('MelisEcomAttributeTable');
        $attributeTransTable = $this->getServiceLocator()->get('MelisEcomAttributeTrans');
        $productAttributeTable = $this->getServiceLocator()->get('MelisEcomProductAttributeTable');
        $attributeValueTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTable');
       
        try{
            //delete attribute trans
            foreach($attributeTransTable->getEntryByField('atrans_attribute_id', $arrayParameters['attributeId']) as $attributeTrans){
                $results = $this->deleteAttributeTransById($attributeTrans->atrans_id);
                if(!$results){
                    throw new \Exception('Unable to delete attribute trans'); die();
                }
            }
            
            //delete product attribute
            foreach($productAttributeTable->getEntryByField('patt_attribute_id', $arrayParameters['attributeId']) as $productAttribute){
                $results = $this->deleteProductAttributeById($productAttribute->patt_id);
                if(!$results){
                    throw new \Exception('Unable to delete product attribute');
                }
            }
            
            //delete attribute values
            foreach($attributeValueTable->getEntryByField('atval_attribute_id', $arrayParameters['attributeId']) as $attributeValue){                
                $results = $this->deleteAttributeValueById($attributeValue->atval_id);
                if(!$results){
                    throw new \Exception('Unable to delete attribute value');
                }
            }
            
           $results = $attributeTable->deleteById($arrayParameters['attributeId']);
            
        }catch (\Exception $e){
            echo $e->getMessage(); die();
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_attribute_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method deletes the attribute translations by ID
     * 
     * @param int $attributeTransId, the attribute trans Id to be deleted
     * @return boolean true on success, otherwise false on error
     */
    public function deleteAttributeTransById($attributeTransId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_attribute_trans_start', $arrayParameters);
        
        // Service implementation start
        $attributeTransTable = $this->getServiceLocator()->get('MelisEcomAttributeTrans');
        
        try{
            $results = $attributeTransTable->deleteById($arrayParameters['attributeTransId']);
        
        }catch (\Exception $e){
            echo $e->getMessage(); die();
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_attribute_trans_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method deletes the product attribute
     * 
     * @param int $productAttributeId, the product attribute table to be deleted
     * @return boolean true on success, otherwise false on error
     */
    public function deleteProductAttributeById($productAttributeId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_product_attribute_start', $arrayParameters);
        
        // Service implementation start
        $productAttributeTable = $this->getServiceLocator()->get('MelisEcomProductAttributeTable');
        
        try{
            $results = $productAttributeTable->deleteById($arrayParameters['productAttributeId']);
        
        }catch (\Exception $e){
            echo $e->getMessage(); die();
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_product_attribute_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method deletes the attribute values and its corresponding translations
     * 
     * @param int $attributeValueId The id to be deleted
     * 
     * @return boolean true on success, otherwise false
     */
    public function deleteAttributeValueById($attributeValueId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_attribute_value_start', $arrayParameters);
        
        // Service implementation start
        $attributeValueTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTable');
        $attributeValueTransTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTransTable');
        $varriantAttributeValueTable = $this->getServiceLocator()->get('MelisEcomProductVariantAttributeValueTable');
        
        try {
            
            //delete attribute value translations
            foreach($attributeValueTransTable->getEntryByField('av_attribute_value_id', $arrayParameters['attributeValueId']) as $trans){
                $results = $this->deleteAttributeValueTransById($trans->avt_id);
                if(!$results)
                    throw new \Exception('Unable to delete attribute value trans');
            }
            
            //delete variant attribute value
            foreach($varriantAttributeValueTable->getEntryByField('vatv_attribute_value_id', $arrayParameters['attributeValueId']) as $variantAttributeValue){
                $results = $this->deleteVariantAttributeValueById($variantAttributeValue->vatv_id);
                if(!$results){
                    throw new \Exception('Unable to delete attribute value');
                }
            }

            $results = $attributeValueTable->deleteById($arrayParameters['attributeValueId']);
        }catch(\Exception $e){
            echo $e->getMessage(); die();
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_attribute_value_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method deletes the attribute value trans by id
     * 
     * @param int $attributeValueTransId the id to be deleted
     * 
     * @return boolean true on success, otherwise false
     */
    public function deleteAttributeValueTransById($attributeValueTransId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_attribute_value_trans_start', $arrayParameters);
        
        // Service implementation start
        $attributeValueTransTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTransTable');
        
        try {            
            
            $results = $attributeValueTransTable->deleteById($arrayParameters['attributeValueTransId']);
            
        }catch(\Exception $e){
            echo $e->getMessage(); die();
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_attribute_value_trans_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method deletes the variant attribute value
     * 
     * @param int $variantAttributeValueId, the variant attribute value id to be deleted
     * @return boolean true on success, otherwise false
     */
    public function deleteVariantAttributeValueById($variantAttributeValueId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_variant_attribute_value_start', $arrayParameters);
        
        // Service implementation start
        $varriantAttributeValueTable = $this->getServiceLocator()->get('MelisEcomProductVariantAttributeValueTable');
        
        try {
        
            $results = $varriantAttributeValueTable->deleteById($arrayParameters['variantAttributeValueId']);
        
        }catch(\Exception $e){
            echo $e->getMessage(); die();
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_variant_attribute_value_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
}