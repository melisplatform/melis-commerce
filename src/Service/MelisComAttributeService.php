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
}