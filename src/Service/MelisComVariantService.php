<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use MelisCommerce\Entity\MelisVariant;
use MelisCommerce\Model\MelisEcomPrice;
/**
 *
 * This service handles the variant system of MelisCommerce.
 *
 */
class MelisComVariantService extends MelisComGeneralService
{
    /**
	 *
	 * This method gets all variants from a product
	 * Variant will come back with: price, stock, documents, attribute values
	 *
	 * @param int $productId Product Id of the variants
	 * @param int $langId If specified, translations of attribute values will be limited to that lang
	 * @param int $countryId If specified, stocks and prices will be given only for the country specified
	 * @param boolean $onlyValid if true, returns only active status products
	 * @param int $start If not specified, it will start at the begining of the list
	 * @param int $limit If not specified, it will bring all products of the list
	 *
	 * @return MelisVariant[] Variant object
	 */
	public function getVariantListByProductId($productId, $langId = null, $countryId = null,
	                                          $onlyValid = null, $isMain = null, $start = 0, $limit = null, $search = '', $order = 'var_id')
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    $variantList = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_list_start', $arrayParameters);
	    
	    // Service implementation start
	   
        $variantTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
        $results = $variantTable->getVariantByProdId($arrayParameters['productId'], $arrayParameters['onlyValid'], $arrayParameters['isMain'], $arrayParameters['start'], $arrayParameters['limit'], $arrayParameters['search'], $arrayParameters['order']);       
        
        //elminate duplicate entries
        if($results){
            foreach($results as $result){
                $variantList[] = $this->getVariantById($result->var_id, $arrayParameters['langId'], $arrayParameters['countryId']);
            }
        }
        // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $variantList;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_list_end', $arrayParameters);
	    
	    return $arrayParameters['results']; 
	}
	
	/**
	 *
	 * This method gets a variant by its variantId
	 * Variant will come back with: price, stock, documents, attribute values
	 *
	 * @param int $variantId Variant Id to look for
	 * @param int $langId If specified, translations of attribute values will be limited to that lang
	 * @param int $countryId Country id, variant stocks and prices will be filtered if specified
	 * @param string $docType Key identifier for filtering variant documents, filters are 'IMG' or 'FILE'
	 * @param array @docSubType key identified for filtering document sub type, filters are array('DEFAULT','SMALL','LARGE','MEDIUM')
	 *
	 * @return MelisVariant|null Variant object
	 */
	public function getVariantById($variantId, $langId = null, $countryId = null, $docType = null, $docSubType = array())
	{
	    
        // Retrieve cache version if front mode to avoid multiple calls
        $tmp = '';
	    foreach($docSubType as $type)
	        $tmp .= '_' . $type;
        $cacheKey = 'variant-' . $variantId . '-getVariantById_' . $variantId . '_' . $langId . '_' . $countryId . '_' . $docType . '_' . $tmp;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
        
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_byid_start', $arrayParameters);
	    
	    // Service implementation start	   
	    $variantTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    $docService = $this->getServiceLocator()->get('MelisComDocumentService');
	    $entVariant = new MelisVariant();
	    $variant = array();
	    $data = array();
	    $results = $variantTable->getVariants($arrayParameters['variantId']);
	    
	    if($results){
	       foreach($results as $result){
	         
	           $entVariant->setId($arrayParameters['variantId']);
	           $entVariant->setVariant($result);
	           $entVariant->setAttributeValues($this->getVariantAttributesValuesById($arrayParameters['variantId'], $arrayParameters['langId']));
	           $entVariant->setStocks($this->getVariantStocksById($arrayParameters['variantId'], $arrayParameters['countryId']));
	           $entVariant->setPrices($this->getVariantPricesById($arrayParameters['variantId'], $arrayParameters['countryId']));
	           $entVariant->setDocuments($docService->getDocumentsByRelationAndTypes('variant', $arrayParameters['variantId'], $arrayParameters['docType'], $arrayParameters['docSubType']));
	           $data = $entVariant;
	       }
	    }	    
	    // Service implementation end
	    
        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $data;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_byid_end', $arrayParameters);	

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	    
	    return $arrayParameters['results']; 
	}

	/**
	 *
	 * This method gets a variant by its variantId
	 * Variant will come back with: price, stock, documents, attribute values
	 *
	 * @param string $variantSKU Variant SKU to look for
	 * @param int $langId If specified, translations of attribute values will be limited to that lang
	 *
	 * @return MelisVariant|null Variant object
	 */
	public function getVariantBySKU($variantSKU, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_bysku_start', $arrayParameters);
	    
	    // Service implementation start
       
	    $variantTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    $results = $variantTable->getEntryByField('var_sku', $arrayParameters['variantSKU']);
	    if($results){
	        $results->toArray()[0];
	    }
	        
	    $entVariant = $this->getVariantById($results['var_id'], $arrayParameters['langId']);
	    $results = $entVariant;
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_bysku_end', $arrayParameters);
	    
	     return $arrayParameters['results'];
	}

	/**
	 *
	 * This method get the main variant of a productId
	 * Variant will come back with: price, stock, documents, attribute values
	 *
	 * @param int $productId Product id to look for
	 * @param int $langId If specified, translations of attribute values will be limited to that lang
	 * @param int $countryId If specified, stocks will be limited to the country provided
	 *
	 * @return MelisVariant|null Variant object
	 */
	public function getMainVariantByProductId($productId, $langId = null, $countryId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_main_byproductid_start', $arrayParameters);
	    
	    // Service implementation start
	    $variantTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    $mainVariant = $variantTable->getMainVariantById($arrayParameters['productId'], $arrayParameters['langId'])->current();
	    
    	if($mainVariant) { 
    	    $results = $this->getVariantById($mainVariant->var_id, $arrayParameters['langId'], $countryId);
	    }
	    // Service implementation end
	    
        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_main_byproductid_end', $arrayParameters);
	
	    return $arrayParameters['results']; 
	}
	
	/**
	 *
	 * This method gets the product of a specified variant id
	 * Product will come back with: categories, text translations, price, documents.
	 *
	 * @param int $variantId Variant Id to look for
	 * @param int $langId If specified, translations of attribute values will be limited to that lang
	 *
	 * @return MelisProduct|null Product object
	 */
	public function getProductByVariantId($variantId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_product_byid_start', $arrayParameters);
	    
	    // Service implementation start
        $productTbl = $this->getServiceLocator()->get('MelisEcomProductTable');
        $results = $productTbl->getProductByVariantId($arrayParameters['variantId'])->current();

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_product_byid_end', $arrayParameters);
	    
	    return $arrayParameters['results']; 
	}
	
	/**
	 *
	 * This method gets the stocks associated with a variant
	 *
	 * @param int $variantId Variant Id to look for
	 * @param int $countryId If specified, stocks will be given only for the country specified
	 *
	 * @return MelisEcomVariantStock[] Stock object
	 */
	public function getVariantStocksById($variantId, $countryId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    $datas = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_stocks_start', $arrayParameters);

	    // Service implementation start
        $stocksTable = $this->getServiceLocator()->get('MelisEcomVariantStockTable');
        if($arrayParameters['variantId']){
            $datas = $stocksTable->getStocksByVariantId($arrayParameters['variantId'], $arrayParameters['countryId']);
        }
	    if($datas){
	        foreach($datas as $data){
	            $results[] = $data;
	        }
	    }
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_stocks_end', $arrayParameters);
	   
	    return $arrayParameters['results'];
	}
	
	/**
	 * This method will return the Variant Final Stocks
	 * 
	 * @param int $variantId
	 * @param int $countryId
	 * @return MelisEcomVarianStock|null
	 */
	public function getVariantFinalStocks($variantId, $countryId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_final_stocks_start', $arrayParameters);
	    
	    // Sending service start event
	    $stocksTable = $this->getServiceLocator()->get('MelisEcomVariantStockTable');
	    $variantStocks = $stocksTable->getEntryByField('stock_var_id', $arrayParameters['variantId']);
	    
	    $generalStocks = array();
	    $findStocksOnGeneral = true;
	    $isCountryStockFound = false;
	    foreach ($variantStocks As $val)
	    {
	        // Getting the General Stocks which is CountryId is -1
	        if ($val->stock_country_id == -1)
	        {
	            $generalStocks = $val;
	        }
	        // Checking if CountryId match to Stock CountryId
	        if ($val->stock_country_id == $arrayParameters['countryId'])
	        {
	            // Just to be sure that data on quantity is in Numeric data type
	            if (is_numeric($val->stock_quantity))
	            {
                    $results = $val;
                    
	                $findStocksOnGeneral = false;
	            }
	            $isCountryStockFound = true;
	        }
	        
	        /**
	         * If Gerenal stocks has already has a data and Variant Coutnry stocks found,
	         * loop will break and proceed to next process
	         * In this process loop will stop after data needed are found.
	         */
	        if (!empty($generalStocks) && $isCountryStockFound)
	        {
	            break;
	        }
	    }
	    
	    // If CountryId did not match to data result, this will try to look Stocks on General Stocks
	    if ($findStocksOnGeneral)
	    {
	        if (!empty($generalStocks))
	        {
	            // Just to be sure that data on quantity is in Numeric data type
	            if (is_numeric($generalStocks->stock_quantity))
	            {
	                $results = $generalStocks;
	            }
	        }
	    }
	    
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_final_stocks_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}

	/**
	 *
	 * This method gets the attribute values affected to a variant
	 *
	 * @param int $variantId Variant Id to look for
	 * @param int $langId If specified, translations of attribute value will be limited to that lang
	 *
	 * @return MelisEcomAttributeValue[] AttributeValue object
	 */
	public function getVariantAttributesValuesById($variantId, $langId = null)
	{
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'variant-' . $variantId . '-getVariantAttributesValuesById_' . $variantId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
        
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    $attribueValues = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_attributesvalues_start', $arrayParameters);
	    
	    // Service implementation start	    
        $varAttrTable = $this->getServiceLocator()->get('MelisEcomProductVariantAttributeValueTable');
        $attrService = $this->getServiceLocator()->get('MelisComAttributeService');
        
        $varAttrs = $varAttrTable->getEntryByField('vatv_variant_id', $arrayParameters['variantId']);
        foreach($varAttrs as $varAttr){
            $tmp =  $attrService->getAttributeValuesById($varAttr->vatv_attribute_value_id, $arrayParameters['langId']);
            foreach($tmp as $attrVal){
                $attribueValues[] = $attrVal;
            }
        }
        // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $attribueValues;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_attributesvalues_end', $arrayParameters);

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	     
	    return $arrayParameters['results'];
	}

	
	/**
	 *
	 * This method gets the price affected to a variant
	 *
	 * @param int $variantId Variant Id to look for
	 * @param int $countryId If specified, stocks will be given only for the country specified
	 *
	 * @return MelisEcomPrice[] Price object
	 */
	public function getVariantPricesById($variantId, $countryId = null)
	{
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'variant-' . $variantId . '-getVariantPricesById_' . $variantId . '_' . $countryId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
        
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    $datas = array();	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_prices_start', $arrayParameters);
	    
	    // Service implementation start	  
	    
        $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
        if($arrayParameters['variantId'])
        {
            $datas = $priceTable->getPricesByVariantId($arrayParameters['variantId'], $arrayParameters['countryId']);
        }        
        
        if($datas)
        {
            foreach($datas as $data)
            {                
                $results[] = $data;
            }
        }        
	    // Service implementation end
        
        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_prices_end', $arrayParameters);

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	    
	    return  $arrayParameters['results'];
	}
	
	/**
	 * This method will return the Variant final Price
	 * 
	 * @param int $variantId
	 * @param int $countryId
	 * @return MelisEcomPrice|null
	 */
	public function getVariantFinalPrice($variantId, $countryId)
	{
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'variant-' . $variantId . '-getVariantFinalPrice_' . $variantId . '_' . $countryId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
        
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_final_prices_start', $arrayParameters);
	     
	    // Service implementation start
	    $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
	    $variantPrice = $priceTable->getVariantFinalPrice($arrayParameters['variantId'], $arrayParameters['countryId'])->current();
	    
	    if(!empty($variantPrice))
	    {
	        // Just to be sure that data on Price is in Numeric data type
	        if (is_numeric($variantPrice->price_net))
            {
                $results = $variantPrice;
            }
	    }
	    
	    /**
	     * If the Variant Country price has no data
	     * this will try to get the General price of the Variant
	     */
	    if ($arrayParameters['countryId'] != -1 && empty($variantPrice))
	    {
	        // Retreiving the General price of the Variant
	        $results = $this->getVariantFinalPrice($arrayParameters['variantId'], -1);
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_final_prices_end', $arrayParameters);

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	     
	    return  $arrayParameters['results'];
	}
	
	/**
	 * This Method will return all the variants that are associated to the product's variants
	 * 
	 * @param int $productId Id of the product
	 * @return array[] Array of variants|NULL
	 */
	public function getAssocVariantsByProductId($productId)
	{
        // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_get_assoc_variants_by_product_start', $arrayParameters);
	    
	    // Service implementation start
	   $variantTbl = $this->getServiceLocator()->get('MelisEcomVariantTable');	   
	   $assocVariants = $variantTbl->getAssocVariantsByProductId($arrayParameters['productId']);
	   
	   foreach($assocVariants as $assocVariant){
	          $results[] = $this->getVariantById($assocVariant->var_id);
	   }
	   
	    // Service implementation end
	     
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_get_assoc_variants_by_product_end', $arrayParameters);

	    return  $arrayParameters['results'];
	}
	
	/**
	 * This method will retrieve variant that has common attributes to other variant 
	 * @param int $productId 
	 * @param int $attributeId 
	 * @param int $attributeValueId
	 * @return MelisEcomVariant
	 */
	public function getVariantCommonAttr($productId, $attributeId, $attributeValueId)
	{
        // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_get_variant_common_attr_start', $arrayParameters);
	     
	    // Service implementation start
	    
	    $variantTbl = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    
	    $results = $variantTbl->getVariantCommonAttr($arrayParameters['productId'], $arrayParameters['attributeId'], $arrayParameters['attributeValueId']);
	    
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_get_variant_common_attr_end', $arrayParameters);
	     
	    return  $arrayParameters['results'];
	}
	
	/**
	 * This method will retrieve variants
	 * 
	 * @param array $variantsIds
	 * @return MelisEcomVariant
	 */
	public function getVariantsAttrGroupByAttr($variantsIds)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_get_variant_attr_group_start', $arrayParameters);
	    
	    // Service implementation start
	    
	    $variantTbl = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    
	    foreach($variantTbl->getVariantsAttrGroupByAttr($arrayParameters['variantsIds']) as $result){
	        $results[] = $result;
	    }
	    
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_get_variant_attr_group_end', $arrayParameters);
	    
	    return  $arrayParameters['results'];
	}
	
	/**
	 * This method will return the Image Path of the variant
	 * if the variant get the image with "STATIC" type 
	 * this will try to get the image from Product
	 * @param int $variantId
	 * @param array $docType
	 * @param string $customDefaultImg
	 * @return String|null
	 */
	public function getFinalVariantImage($variantId, $docType = array(), $customDefaultImg = null)
	{
// 	    $tmp = '';
// 	    foreach ($docType as $type)
// 	        $tmp .= '_' . $type;
// 	    $tmpCustomDefaultImg = str_replace('.', '', $customDefaultImg);
// 	    $tmpCustomDefaultImg = str_replace('/', '', $tmpCustomDefaultImg);
// 	    $cacheKey = 'variant-' . $variantId . '-getFinalVariantImage_' . $variantId . '_' . $tmp . '_' . $tmpCustomDefaultImg;
// 	    $results = $this->getCacheServiceResults($cacheKey, 'commerce_big_services');
// 	    if (!empty($results))
// 	        return $results;
	    
	         
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_get_final_iamge_start', $arrayParameters);
	     
	    // Service implementation start
	    $docSrv = $this->getServiceLocator()->get('MelisComDocumentService');
	    $data = $docSrv->getFinalImageFilePath('variant', $arrayParameters['variantId'], $arrayParameters['docType'], $arrayParameters['customDefaultImg']);
	    
	    if (!empty($data))
	    {
	        /**
	         * Checking if the image type
	         * if the type is STATIC this will try to get the image from
	         * Product image
	         */
	        if ($data['imageType'] != 'STATIC')
	        {
	            $results = $data['imagePath'];
	        }
	        else 
	        {
	            $product = $this->getProductByVariantId($variantId);
	            if (!empty($product))
	            {
	                $data = $docSrv->getFinalImageFilePath('product', $product->prd_id, $arrayParameters['docType'], $arrayParameters['customDefaultImg']);
	                
	                $results = $data['imagePath'];
	            }
	        }
	    }
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_get_final_iamge_end', $arrayParameters);

	    // Save cache key
// 	    $this->setCacheServiceResults($cacheKey, $arrayParameters['results'], 'commerce_big_services');
	    
	    return  $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves a variant in database.
	 *
	 * 
	 * @param array[] $variant Reflects the melis_ecom_variant table
	 * @param array[] $prices Reflects the melis_ecom_price table
	 * @param array[] $stocks Reflects the melis_ecom_variant_stock table
	 * @param int[] $attributeValues List of attribute values ids affected to the product
	 * @param int[] $seo Reflects the melis_ecom_seo table
	 * @param int $variantId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The variant id created or updated, null if an error occured
	 */
	public function saveVariant($variant, $prices = array(), $stocks = array(), 
	                            $attributeValues = array(), $seo = array(), $variantId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_save_start', $arrayParameters);
	    
	    // Service implementation start
        $variantTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
        $varAttrTable = $this->getServiceLocator()->get('MelisEcomProductVariantAttributeValueTable');        
        
        try{
            $successFlag = true;
            $variantId = (int) $variantTable->save($arrayParameters['variant'], $arrayParameters['variantId']);
        }catch(\Exception $e){
            $successFlag = false;
        }
        
        if($variantId){
            
            if(!empty($arrayParameters['prices'])){
                foreach($arrayParameters['prices'] as $price){
                    $price['price_var_id'] = $variantId;
                    $priceId = (!empty($price['price_id']) ? $price['price_id'] : null);
                    unset($price['price_id']);
                    $this->saveVariantPrices($price, $priceId);
                }             
            }
            
            if(!empty($arrayParameters['stocks'])){
                foreach($arrayParameters['stocks'] as $stock){   
                    $stock['stock_var_id'] = $variantId;
                    $stockId = (!empty($stock['stock_id']) ? $stock['stock_id'] : null);
                    unset($stock['stock_id']);
                    $this->saveVariantStocks($stock, $stockId);
                }
            }
            
            if(!empty($arrayParameters['attributeValues'])){               
                foreach($arrayParameters['attributeValues'] as $attributeValue){                    
                    $attributeValue['vatv_variant_id'] = $variantId;
                    $attributeValueId = (!empty($attributeValue['vatv_id']) ? $attributeValue['vatv_id'] : null);                    
                    unset($attributeValue['vatv_id']);
                    $this->saveVariantAttributesValues($attributeValue, $attributeValueId);
                }
            }  
                
            // SEO Service
            if(!empty($arrayParameters['seo'])){
                $variantSeo = $arrayParameters['seo'];
                $melisComSeoService = $this->getServiceLocator()->get('MelisComSeoService');
                $melisComSeoService->saveSeoDataAction('variant', $variantId, $variantSeo);
            }

            $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
            $melisEngineCacheSystem->deleteCacheByPrefix('variant-' . $variantId, 'commerce_big_services');
            
            $results = $variantId;
        }
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_save_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves the attributes values for a variant
	 * This will create/update entries and delete the one that could exist and not linked anymore,
	 * the list of attributes values must be full.
	 *
	 * @param array[] $attributeValues List of attribute values ids affected to the variant
	 * @param int[] $attributeValueId attribute value id to look for
	 *
	 * @return boolean True/false if the attributes were successfuly added to the product
	 */
	public function saveVariantAttributesValues($attributesValues, $attributeValueId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = 0;
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_save_attributesvalues_start', $arrayParameters);
	    
	    // Service implementation start
        $variantAttributeTable = $this->getServiceLocator()->get('MelisEcomProductVariantAttributeValueTable');  
        try{
            $results = true;
            $variantAttributeTable->save($arrayParameters['attributesValues'],$arrayParameters['attributeValueId']);
            
            if (!empty($arrayParameters['attributesValues']['vatv_variant_id']))
            {
                $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
                $melisEngineCacheSystem->deleteCacheByPrefix('variant-' . $arrayParameters['attributesValues']['vatv_variant_id'], 'commerce_big_services');
            }
            
        }catch(\Exception $e){
            $results = false;
        }
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_save_attributesvalues_end', $arrayParameters);
	    
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves the prices for a variant
	 * This will update or save prices.
	 *
	 * @param array[] $prices Reflects the melis_ecom_price table
	 * @param int $priceId Price Id to look for
	 *
	 * @return boolean True/false if the prices were successfuly added to the product
	 */
	public function saveVariantPrices($prices, $priceId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = 0;
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_save_prices_start', $arrayParameters);
	    
	    // Service implementation start
       $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');    
       $productSvc = $this->getServiceLocator()->get('MelisComProductService');
       try{
          $results = true;
          $results = (bool) $priceTable->save($arrayParameters['prices'], $arrayParameters['priceId']);

          if (!empty($arrayParameters['prices']['price_var_id']))
          {
                $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
                $melisEngineCacheSystem->deleteCacheByPrefix('variant-' . $arrayParameters['prices']['price_var_id'], 'commerce_big_services');
          }
          
          //$priceTable->save($arrayParameters['prices'],$arrayParameters['priceId']);
       }catch(\Exception $e){
          $results = false; 
       }
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_save_prices_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves the stocks for a variant
	 * This will update or save stocks.
	 *
	 * @param array[] $stocks Reflects the melis_ecom_variant_stock table
	 * @param int $stocksId Stocks Id to look for
	 *
	 * @return boolean True/false if the stocks were successfuly added to the product
	 */
	public function saveVariantStocks($stocks, $stockId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = 0;
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_save_stocks_start', $arrayParameters);
	    
	    // Service implementation start
        $stockTable = $this->getServiceLocator()->get('MelisEcomVariantStockTable');
        try{
            $results = true;
            $stockTable->save($arrayParameters['stocks'],$arrayParameters['stockId']);
        }catch(\Exception $e){
            $results = false;
        }
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_save_stocks_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method deletes the stocks for a variant
	 * This will delete.
	 *
	 * @param int $variantPriceId Variant price id to look for
	 *
	 * @return boolean True/false if the price were successfuly deleted
	 */
	public function deleteVariantById($variantId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = 0;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_delete_start', $arrayParameters);
	
	    // Service implementation start
	    $varTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    $docSvc = $this->getServiceLocator()->get('MelisComDocumentService');
	    $docs = $docSvc->getDocumentsByRelation('variant', $arrayParameters['variantId']);
	    try{
	        $results = $varTable->deleteById($arrayParameters['variantId']);
	        if(!$results)
	            throw new \Exception('Unable to delete variant');
	             
            $results = $this->deleteVariantPriceById($arrayParameters['variantId']);
            if(!$results)
                throw new \Exception('Unable to delete variant price');
	                 
            $results = $this->deleteVariantStockById($arrayParameters['variantId']);
            if(!$results)
                throw new \Exception('Unable to delete variant stock');
            
            $results = $this->deleteVariantAttributeById($arrayParameters['variantId']);
            if(!$results)
                throw new \Exception('Unable to delete variant attribute');
            
            if(!empty($docs)){
                foreach($docs->getDocument() as $doc){
                    $results = $docSvc->deleteDocument($doc['doc_id']);
                    if(!$results){
                        throw new \Exception('Unable to delete variant attribute');
                    }
                }
            }

            $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
            $melisEngineCacheSystem->deleteCacheByPrefix('variant-' . $arrayParameters['variantId'], 'commerce_big_services');
           
	    }catch(\Exception $e){
	        $results = false;
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_delete_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	
	/**
	 * 
	 * This method deletes the price for a variant
	 * This will delete.
	 * 
	 * @param int $variantPriceId Variant price id to look for
	 * 
	 * @return boolean True/false if the price were successfuly deleted
	 */
	public function deleteVariantPriceById($variantPriceId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = 0;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_delete_price_start', $arrayParameters);
	     
	    // Service implementation start
	    $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
	    try{
	        $results = true;
	        $priceTable->deleteByField('price_var_id', $arrayParameters['variantPriceId']);
	        
	    }catch(\Exception $e){
	        $results = false;
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_delete_price_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method deletes the stocks for a variant
	 * This will delete.
	 *
	 * @param int $variantPriceId Variant stock id to look for
	 *
	 * @return boolean True/false if the price were successfuly deleted
	 */
	public function deleteVariantStockById($variantStockId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = 0;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_delete_stocks_start', $arrayParameters);
	
	    // Service implementation start
	    $stockTable = $this->getServiceLocator()->get('MelisEcomVariantStockTable');
	    try{
	        $results = true;
	        $stockTable->deleteByField('stock_var_id', $arrayParameters['variantStockId']);
	    }catch(\Exception $e){
	        $results = false;
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_delete_stocks_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method deletes the variant attribute value for a variant
	 * This will delete.
	 *
	 * @param int $variantPriceId Variant attribute id to look for
	 *
	 * @return boolean True/false if the price were successfuly deleted
	 */
	public function deleteVariantAttributeById($variantAttributeId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = 0;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_delete_attribute_start', $arrayParameters);
	
	    // Service implementation start
	    $varAttrValTable = $this->getServiceLocator()->get('MelisEcomProductVariantAttributeValueTable');
	    try{
	        $results = true;
	        $varAttrValTable->deleteByField('vatv_variant_id', $arrayParameters['variantAttributeId']);
	    }catch(\Exception $e){
	        $results = false;
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_delete_attribute_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 * This function returns the variant and its SEO
	 * @param int $variantId Id of the variant
	 * @param int $langId language Id of the SEO
	 * @return Object|false
	 */
	public function getVariantAndSeoById($variantId, $langId)
	{
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'variant-' . $variantId . '-getVariantAndSeoById_' . $variantId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = false;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_seo_byid_start', $arrayParameters);
	     
	    // Service implementation start
	    $variantTbl = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    try {
	        $results = $variantTbl->getVariantAndSeoById($arrayParameters['variantId'], $arrayParameters['langId'])->current();
	    
	    }catch(\Exception $e) {
	    }
	     
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_seo_byid_end', $arrayParameters);

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	    
	    return $arrayParameters['results'];
	}

    public function getVariantsByAttributeValueIds($attrValueIds, $langId, $isValid = true)
    {

        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'getVariantsByAttributeValueIds_' . implode('', $attrValueIds) . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_variant_by_attribute_values_ids_start', $arrayParameters);


        $varAttrvalsTbl = $this->getServiceLocator()->get('MelisEcomProductVariantAttributeValueTable');

        $variants = $varAttrvalsTbl->getVariantsByAttributeValueIds($arrayParameters['attrValueIds'], $arrayParameters['langId'], $arrayParameters['isValid']);

        foreach ($variants As $var){
            array_push($results, $var);
        }

        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_variant_by_attribute_values_ids_end', $arrayParameters);

        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);

        return $arrayParameters['results'];
    }

}