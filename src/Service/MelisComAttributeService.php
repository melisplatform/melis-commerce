<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use MelisCommerce\Entity\MelisAttribute;
/**
 *
 * This service handles the attribute system of MelisCommerce.
 *
 */
class MelisComAttributeService extends MelisComGeneralService
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
        $attrTable = $this->getServiceManager()->get('MelisEcomAttributeTable');
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
        foreach($this->getAttrById($arrayParameters['attributeId']) as $data){
            $data = (object) $data;
            $data->{'attr_trans'} = array();
            $attributeValues = array();
            $entAttribute->setId($data->attr_id);
            
            foreach($this->getAttributeTransByAtributeId( $data->attr_id, $arrayParameters['langId']) as $attrTrans){
                $data->{'attr_trans'} = array_merge($this->getAttributeTransById($attrTrans['atrans_id'], $arrayParameters['langId']), $data->{'attr_trans'});
            }
            
            $entAttribute->setAttribute($data);
            foreach($this->getAttrValByField('atval_attribute_id', $data->attr_id) as $attrVal){
                $attributeValues = array_merge($attributeValues, $this->getAttributeValuesById($attrVal['atval_id'], $arrayParameters['langId']));
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
     * @param $attrId
     * @return mixed
     */
    public function getAttrById($attrId)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'attribute-' . $attrId .'-getAttrById_' . $attrId;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');

        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }

        // Service implementation start
        $attrTable = $this->getServiceManager()->get('MelisEcomAttributeTable');
        $attr = $attrTable->getEntryById($attrId)->toArray();

        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $attr);

        return $attr;
    }

    /**
     * @param $col
     * @param $attrId
     * @return mixed
     */
    public function getAttrValByField($col, $attrId)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'attribute-' . $attrId . '-getAttrById_' . $col .'_'.$attrId;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');

        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }

        // Service implementation start
        $attrValTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
        $attrVal = $attrValTable->getEntryByField($col, $attrId)->toArray();

        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $attrVal);

        return $attrVal;
    }

    public function getAttributeTransByAtributeId($attrId, $langId)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'attribute-' . $attrId . '-getAttributeTransByAtributeId_' . $attrId . '_' . $langId;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');

        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        $usedAttributes = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_attribute_trans_by_attr_id_start', $arrayParameters);

        // Service implementation start
        $attrTransTable = $this->getServiceManager()->get('MelisEcomAttributeTransTable');
        $results = $attrTransTable->getAttributeTransByAtributeId($arrayParameters['attrId'], $arrayParameters['langId']);

        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results->toArray();
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_attribute_trans_by_attr_id_end', $arrayParameters);

        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);

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

        // Service implementation start
        foreach($this->getAttributeTrans($arrayParameters['attributeTransId'], $arrayParameters['langId']) as $data){
            $results[] = (object) $data;
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributetrans_byid_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }

    /**
     * @param $attributeTransId
     * @param $langId
     * @return mixed
     */
    public function getAttributeTrans($attributeTransId, $langId)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'attribute-getAttributeTrans_' . $attributeTransId .'_'.$langId;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');

        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }

        $attrTransTable = $this->getServiceManager()->get('MelisEcomAttributeTransTable');
        $attrTrans = $attrTransTable->getAttributeTransById($attributeTransId, $langId)->toArray();

        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $attrTrans);

        return $attrTrans;
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
        $attrValTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
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
    
    public function getUsedAttributeValuesByProductId($productId, $status = false, $langId = null)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'attribute-getUsedAttributeValuesByProductId_' . $productId . '_' . $status . '_' . $langId;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;

        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        $usedAttributes = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_used_attribute_values_by_product_start', $arrayParameters);

        // Service implementation start
        
        $attrTable = $this->getServiceManager()->get('MelisEcomAttributeTable');
        $attributes = $attrTable->getUsedAttributeByProduct($arrayParameters['productId'], $arrayParameters['status'], $arrayParameters['langId']);

        foreach($attributes as $data)
        {
            $entAttribute = new MelisAttribute();
            $entAttribute->setId($data->attr_id);
            
            $data->{'attr_trans'} = array();
            foreach($this->getAttributeTransByAtributeId( $data->attr_id, $arrayParameters['langId']) as $attrTrans)
            {
                $data->{'attr_trans'}[] = $attrTrans; //array_merge($this->getAttributeTransById($attrTrans->atrans_id, $arrayParameters['langId']), $data->{'attr_trans'});
            }
            $entAttribute->setAttribute($data);
            
            $attributeValues = array();
            foreach($this->getUsedAttributeValuesByProduct($productId, $data->attr_id) as $attrVal)
            {
                $attributeValues = array_merge($attributeValues, $this->getAttributeValuesById($attrVal->atval_id, $arrayParameters['langId']));
            }
            $entAttribute->setAttributeValues($attributeValues);
            $results[] = $entAttribute;
        }        
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_used_attribute_values_by_product_start', $arrayParameters);

        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }

    public function getUsedAttributeValuesByProduct($productId, $attrId)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'attribute-getUsedAttributeValuesByProduct_' . $productId . '_' . $attrId;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');

        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        $usedAttributes = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_used_attribute_values_by_product_start', $arrayParameters);

        // Service implementation start
        $attrValueTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
        $results = $attrValueTable->getUsedAttributeValuesByProduct($productId, $attrId);

        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_used_attribute_values_by_product_start', $arrayParameters);

        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);

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
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'attribute-getAttributeValuesById_' . $attributeValueId . '_' . $langId;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);

        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevalue_byid_start', $arrayParameters);
        
        // Service implementation start
        $attrValTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
        $attrValTransTable = $this->getServiceManager()->get('MelisEcomAttributeValueTransTable');
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

        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }
    
    /**
     *
     * This method gets the attribute values id and translation in a single line, used in the front end
     *
     * @param int $attributeId If Specified, the attributeId to look for
     * @param int $langId If specified, translations of attribute values will be limited to that lang
     *
     * @return MelisAttributeValue and attribute Value trans | null attributevalue object
     *
     */
    public function getAttributeValuesByAttributeId($attributeId, $langId = null)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'attribute-' . $attributeId . '-getAttributeValuesByAttributeId_' . $attributeId . '_' . $langId;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;

        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
        
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevalue_byid_start', $arrayParameters);
    
        // Service implementation start
        $attrValTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
        foreach($attrValTable->getAttributeValuesByAttributeId($arrayParameters['attributeId'], $arrayParameters['langId']) as $data){
            $results[] = $data;
        }
        
        // Service implementation end


    
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevalue_byid_end', $arrayParameters);

        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
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

        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'attribute-value-trans-' . $attributeValueTransId . '-getAttributeValueTransById_' . $attributeValueTransId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);

        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }

        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_start', $arrayParameters);
        
        // Service implementation start
        $attrValTransTable = $this->getServiceManager()->get('MelisEcomAttributeValueTransTable');
        foreach($attrValTransTable->getAttributeValueTransbyId($arrayParameters['attributeValueTransId'], $arrayParameters['langId']) as $data){
            $results[]= $data;
        }

        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_attributevaluetrans_byid_end', $arrayParameters);


        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This will return the Attribute list and its values
     * @param int $attributeId, id of attribute if null this will get the list of attributes
     * @param int $langId, lang id related to translations
     * @return Array
     */
    public function getAttributeListAndValues($attributeId = null, $status = false, $searchable = false, $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_attribute_list_and_values_start', $arrayParameters);
        
        // Service implementation start
        $attrTable = $this->getServiceManager()->get('MelisEcomAttributeTable');
        $attrValTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
        
        $attributes = $attrTable->getAttributeListAndValues($arrayParameters['attributeId'], $arrayParameters['status'], $arrayParameters['searchable'], $arrayParameters['langId']);
        foreach ($attributes As $val)
        {
            $val->attr_values = $this->getAttributeValuesByAttributeId($val->attr_id, $arrayParameters['langId']);
            array_push($results, $val);
        }
        
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_attribute_list_and_values_end', $arrayParameters);
        
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
        $attributeTable = $this->getServiceManager()->get('MelisEcomAttributeTable');
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

            $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
            $commerceCacheService->deleteCache('attribute', $results);
        }catch (\Exception $e){
            
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
        $attributeTransTable = $this->getServiceManager()->get('MelisEcomAttributeTransTable');
        try {
            $results = $attributeTransTable->save($arrayParameters['attributeTrans'], $arrayParameters['attributeTransId']);            

            $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
            $commerceCacheService->deleteCache('attribute-', $arrayParameters['attributeTrans']['atrans_attribute_id']);
            
        }catch (\Exception $e){
            
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
        $attributeValueTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
            try {
                $results = $attributeValueTable->save($arrayParameters['attributeValue'], $arrayParameters['attributeValueId']);

                $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
                $commerceCacheService->deleteCache('attribute', $arrayParameters['attributeValue']['atval_attribute_id']);
            }catch(\Exception $e){
                
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
        $attributeValueTransTable = $this->getServiceManager()->get('MelisEcomAttributeValueTransTable');
        try {
            $results = $attributeValueTransTable->save($arrayParameters['attributeValueTrans'], $arrayParameters['attributeValueTransId']);

            $attrValTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
            $attValueDatas = $attrValTable->getEntryById($arrayParameters['attributeValueTrans']['av_attribute_value_id'])->current();

            $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
            $commerceCacheService->deleteCache('attribute-value-trans',$results);
        }catch(\Exception $e){
            
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
        $attributeTable = $this->getServiceManager()->get('MelisEcomAttributeTable');
        $attributeTransTable = $this->getServiceManager()->get('MelisEcomAttributeTransTable');
        $productAttributeTable = $this->getServiceManager()->get('MelisEcomProductAttributeTable');
        $attributeValueTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
    
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
                $results = $this->deleteProductAttributeById($productAttribute->patt_id, $arrayParameters['attributeId']);
                if(!$results){
                    throw new \Exception('Unable to delete product attribute');
                }
            }
            
            //delete attribute values
            foreach($attributeValueTable->getEntryByField('atval_attribute_id', $arrayParameters['attributeId']) as $attributeValue){                
                $results = $this->deleteAttributeValueById($attributeValue->atval_id, $arrayParameters['attributeId']);
                if(!$results){
                    throw new \Exception('Unable to delete attribute value');
                }
            }
            
        $results = $attributeTable->deleteById($arrayParameters['attributeId']);

        $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
        $commerceCacheService->deleteCache('attribute', $arrayParameters['attributeId']);
            
        }catch (\Exception $e){
            
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
        $attributeTransTable = $this->getServiceManager()->get('MelisEcomAttributeTransTable');
        
        try{
            $attTransDatas = $attributeTransTable->getEntryById($arrayParameters['attributeTransId'])->current();
            
            $results = $attributeTransTable->deleteById($arrayParameters['attributeTransId']);

            if ($attTransDatas) {
                $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
                $commerceCacheService->deleteCache('attribute', $attTransDatas->atrans_attribute_id);
            }
            
        }catch (\Exception $e){
            
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
    public function deleteProductAttributeById($productAttributeId, $attributeId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_product_attribute_start', $arrayParameters);
        
        // Service implementation start
        $productAttributeTable = $this->getServiceManager()->get('MelisEcomProductAttributeTable');
        
        try{
            $results = $productAttributeTable->deleteById($arrayParameters['productAttributeId']);

            $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
            $commerceCacheService->deleteCache('attribute', $arrayParameters['attributeId']);
        }catch (\Exception $e){
            
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
    public function deleteAttributeValueById($attributeValueId, $attributeId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_attribute_value_start', $arrayParameters);
        
        // Service implementation start
        $attributeValueTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');
        $attributeValueTransTable = $this->getServiceManager()->get('MelisEcomAttributeValueTransTable');
        $varriantAttributeValueTable = $this->getServiceManager()->get('MelisEcomProductVariantAttributeValueTable');
        
        try {
            
            //delete attribute value translations
            foreach($attributeValueTransTable->getEntryByField('av_attribute_value_id', $arrayParameters['attributeValueId']) as $trans){
                $results = $this->deleteAttributeValueTransById($trans->avt_id);
                if(!$results)
                    throw new \Exception('Unable to delete attribute value trans');
            }
            
            //delete variant attribute value
            foreach($varriantAttributeValueTable->getEntryByField('vatv_attribute_value_id', $arrayParameters['attributeValueId']) as $variantAttributeValue){
                $results = $this->deleteVariantAttributeValueById($variantAttributeValue->vatv_id, $arrayParameters['attributeId']);
                if(!$results){
                    throw new \Exception('Unable to delete attribute value');
                }
            }

            $results = $attributeValueTable->deleteById($arrayParameters['attributeValueId']);

            $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
            $commerceCacheService->deleteCache('attribute', $arrayParameters['attributeId']);
        }catch(\Exception $e){
            
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
        $attributeValueTransTable = $this->getServiceManager()->get('MelisEcomAttributeValueTransTable');
        
        try {
            $results = $attributeValueTransTable->deleteById($arrayParameters['attributeValueTransId']);

            $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
            $commerceCacheService->deleteCache('attribute-value-trans', $arrayParameters['attributeValueTransId']);
        }catch(\Exception $e){
            
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
    public function deleteVariantAttributeValueById($variantAttributeValueId, $attributeId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_variant_attribute_value_start', $arrayParameters);
        
        // Service implementation start
        $varriantAttributeValueTable = $this->getServiceManager()->get('MelisEcomProductVariantAttributeValueTable');
        
        try {
            $results = $varriantAttributeValueTable->deleteById($arrayParameters['variantAttributeValueId']);

            $commerceCacheService = $this->getServiceManager()->get('MelisComCacheService');
            $commerceCacheService->deleteCache('attribute', $arrayParameters['attributeId']);
        } catch(\Exception $e){
            
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_delete_variant_attribute_value_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }

    /**
     * Function to check the format of selected attribute ids
     *
     * If the function receive an already formatted array, then
     * it will just return the array, else if it receive a query
     * string, then the function will will convert the string
     * into an array before returning, but of course if you
     * pass a query string, make sure that it is an array
     * that has been converted to query string
     *
     * @param $selectedAttributes
     * @return array
     */
    public function checkSelectedAttributesFormat($selectedAttributes)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_check_selected_attr_format_start', $arrayParameters);

        $attrValTable = $this->getServiceManager()->get('MelisEcomAttributeValueTable');

        if(!empty($arrayParameters['selectedAttributes'])){
            if(isset($arrayParameters['selectedAttributes'][0])){
                //check the array first to make sure it is well formed
                $newDatas = [];
                foreach ($arrayParameters['selectedAttributes'] as $key => $val) {
                    //check if it is an array already
                    if (!is_array($val)) {
                        if(is_numeric($val)){
                            $attributeData = $attrValTable->getParentAttributeByAttrId($val)->toArray();
                            if(!empty($attributeData)){
                                foreach($attributeData as $atts){
                                    $attrNewKey = strtolower(str_replace(' ', '_', $atts['attr_reference']));
                                    if(!array_key_exists($attrNewKey, $newDatas)){
                                        $newDatas[$attrNewKey] = [];
                                    }
                                    array_push($newDatas[$attrNewKey], $atts['atval_id']);
                                }
                            }
                        }else {
                            //we need to parse it if it is a string(query string)
                            $temp = htmlspecialchars_decode($val);
                            parse_str(htmlspecialchars_decode($temp), $attributes);
                            $newDatas = $attributes;
                        }
                    }
                }
                $arrayParameters['selectedAttributes'] = $newDatas;
            }else{
                if(!is_array($arrayParameters['selectedAttributes'])) {
                    //we need to parse it if it is a string(query string)
                    $temp = htmlspecialchars_decode($arrayParameters['selectedAttributes']);
                    parse_str(htmlspecialchars_decode($temp), $attributes);
                    $arrayParameters['selectedAttributes'] = $attributes;
                }
            }
        }else{
            $arrayParameters['selectedAttributes'] = array();
        }
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_check_selected_attr_format_end', $arrayParameters);

        return $arrayParameters['selectedAttributes'];
    }
}