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
	 *
	 * @return MelisVariant|null Variant object
	 */
	public function getVariantById($variantId, $langId = null, $countryId = null)
	{
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
	           $entVariant->setStocks($this->getVariantStocksById($arrayParameters['variantId']), $arrayParameters['countryId']);
	           $entVariant->setPrices($this->getVariantPricesById($arrayParameters['variantId']), $arrayParameters['countryId']);
	           $entVariant->setDocuments($docService->getDocumentsByRelation('variant', $arrayParameters['variantId']));
	           $data = $entVariant;
	       }
	    }	    
	    // Service implementation end
	    
        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $data;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_byid_end', $arrayParameters);	
	    
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
	 *
	 * @return MelisVariant|null Variant object
	 */
	public function getMainVariantByProductId($productId, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_main_byproductid_start', $arrayParameters);
	    
	    // Service implementation start
	    $variantTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    $results = $variantTable->getMainVariantById($arrayParameters['productId'], $arrayParameters['langId']);
       
    	if($results) {	      
    	    foreach($results as $result){
    	        $variantsId[] = $result->var_id;
    	    }   	   
    	    $variantId = array_unique($variantsId)[0];
    	    $variant = $this->getVariantById($variantId, $arrayParameters['langId']);   	
    	    $results = $variant;
    	    
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
	public function getProductByVariantId($variantId, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_product_byid_start', $arrayParameters);
	    
	    // Service implementation start
        $entVariant = $this->getVariantById($arrayParameters['variantId'], $arrayParameters['langId']);

        $results = $entVariant;

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
	//    $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
	    // Service implementation start
        $stocksTable = $this->getServiceLocator()->get('MelisEcomVariantStockTable');
        if($arrayParameters['variantId']){
            $datas = $stocksTable->getStocksByVariantId($arrayParameters['variantId'], $arrayParameters['countryId']);
        }
	    if($datas){
	        foreach($datas as $data){
// 	            $data->stock_next_fill_up = $melisTool->dateFormateLocale($data->stock_next_fill_up);
	            $data->stock_next_fill_up = $data->stock_next_fill_up;
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
	        // Getting the General Stocks which is CountryId is 0 (Zero)
	        if ($val->stock_country_id == 0)
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
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    $datas = array();	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_prices_start', $arrayParameters);
	    
	    // Service implementation start	  
	    
        $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
        if($arrayParameters['variantId']){
            $datas = $priceTable->getPricesByVariantId($arrayParameters['variantId'], $arrayParameters['countryId']);
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
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_prices_end', $arrayParameters);
	    
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
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
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
	     
	    if (is_null($results))
	    {
	        $variantPrice = $priceTable->getVariantGeneralPrice($arrayParameters['variantId'])->current();
	        
	        if (!empty($variantPrice))
	        {
	            // Just to be sure that data on Price is in Numeric data type
	            if (is_numeric($variantPrice->price_net))
	            {
	                // Getting the default currency
	                $currencyTable = $this->getServiceLocator()->get('MelisEcomCurrencyTable');
	                $generalCurrency = $currencyTable->getEntryByField('cur_default', 1)->current();
	                
	                if(!empty($generalCurrency))
	                {
	                    // Merging results and cast as Object
	                    $results = (object) array_merge((array)$variantPrice, (array)$generalCurrency);
	                }
	            }
	        }
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_variant_final_prices_end', $arrayParameters);
	     
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
            echo 'Caught exception: ',  $e->getMessage(), "\n";
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
           
	    }catch(\Exception $e){
	        echo $e->getMessage();
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
}