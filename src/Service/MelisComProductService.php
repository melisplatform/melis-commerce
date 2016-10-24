<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use MelisCommerce\Entity\MelisProduct;
use MelisCommerce\Entity\MelisAttribute;


/**
 *
 * This service handles the product system of MelisCommerce.
 *
 */
class MelisComProductService extends MelisComGeneralService
{
	/**
	 *
	 * This method gets all products
	 * Product will come back with: categories, texts translations, prices, documents
	 * It won't load variants
	 *
	 * @param int $langId If specified, translations of products will be limited to that lang
	 * @param int[] $categoryId If not empty, only products from the list of categories will be taken
	 * @param int $countryId If specified, prices will be given only for the country specified
	 * @param boolean $onlyValid if true, returns only active status products
	 * @param int $start If not specified, it will start at the begining of the list
	 * @param int $limit If not specified, it will bring all products of the list
	 *
	 * @return MelisProduct[] Product object
	 */
	public function getProductList($langId = null, $categoryId = array(), $countryId = null, 
	                               $onlyValid = null, $start = 0, $limit = null, $search = '', $order = 'ASC')
	{ 
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_list_start', $arrayParameters);
	    
	    // Service implementation start
	    $entProd = new MelisProduct();
	    $tmpData = array();
        $prodTable = $this->getServiceLocator()->get('MelisEcomProductTable');

        if($search) {
            $productData = $prodTable->getProduct(null, $arrayParameters['onlyValid'], 0, null, $arrayParameters['order']);
            if($productData) {
                foreach($productData as $prod) {
                    $tmpData[] = $this->getProductById($prod->prd_id, $arrayParameters['langId']);
                }
            }
            $search = strtolower($search);
            foreach($tmpData as $pData) {
                if((strpos(strtolower($this->getProductName($pData->getId(), $langId)), $search) !== false) ||
                   ((int) $pData->getId() == (int) $search)
                ) {
                    $results[] = $pData;
                }
            }
        }
        else {
            $productData = $prodTable->getProduct(null, $arrayParameters['onlyValid'], $arrayParameters['start'], $arrayParameters['limit'], $arrayParameters['order']);
            if($productData) {
                foreach($productData as $prod) {
                    // add searching statement here
                    $results[] = $this->getProductById($prod->prd_id, $arrayParameters['langId']);
                }
            }
        }
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_list_end', $arrayParameters);

	    
	    return $arrayParameters['results'];
	}
	
	
	/**
	 *
	 *
	 * This method gets a product by its productId
	 * Product will come back with: categories, text translations, prices, documents.
	 * It won't load variants and attributes
	 *
	 * @param int $productId Product Id to look for
	 * @param int $langId If specified, translations of product will be limited to that lang
	 * @param int $countryId If specified, prices will be given only for the country specified
	 *
	 * @return MelisProduct|null Product object
	 */
	public function getProductById($productId, $langId = null, $countryId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_byid_start', $arrayParameters);
	    
	    // Service implementation start
	    $prodTable = $this->getServiceLocator()->get('MelisEcomProductTable');
	    $productData = $prodTable->getProduct($arrayParameters['productId']);
	    $prodCatTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
	    
	    $category = array();
	    $prodDoc = array();

	    $docSvc = $this->getServiceLocator()->get('MelisComDocumentService');
	    $prodDoc = $docSvc->getDocumentsByRelation('product', $productId);
	    $entProd = new MelisProduct();
        if($productData) {
            foreach($productData as $prod) {
                $entProd->setId($prod->prd_id);
                $entProd->setProduct($prod);
                foreach($prodCatTable->getEntryByField('pcat_prd_id', $arrayParameters['productId']) as $prodCat){
                    $category[]= $prodCat;
                }
                $entProd->setCategories($category);
                $entProd->setAttributes($this->getProductAttributesById($arrayParameters['productId'], $arrayParameters['langId']));
                $entProd->setTexts($this->getProductTextsById($arrayParameters['productId'], null, $arrayParameters['langId']));
                $entProd->setPrice($this->getProductPricesById($arrayParameters['productId'], $arrayParameters['countryId']));                
                $entProd->setDocuments($prodDoc);
            }
        }
        
	    $results = $entProd;

	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_byid_end', $arrayParameters);

	    
	    return $arrayParameters['results'];
	}
	
	
	/**
	 *
	 * This method gets the attributes affected to a product
	 * Attribute will come back with: attribute, attribute translations.
	 *
	 * @param int $productId Product Id to look for
	 * @param int $langId If specified, translations of attribute will be limited to that lang
	 *
	 * @return MelisAttribute[] Attribute object
	 */
	public function getProductAttributesById($productId, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_attributes_byid_start', $arrayParameters);
	    
	    // Service implementation start
        $prdAttrTable = $this->getServiceLocator()->get('MelisEcomProductAttributeTable');
        $attrSvc = $this->getServiceLocator()->get('MelisComAttributeService');
       
        
        foreach($prdAttrTable->getEntryByField('patt_product_id', $arrayParameters['productId']) as $data){
            $results[] = $data;
        }
        
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_attributes_byid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the texts affected to a product
	 *
	 * @param int $productId Product Id to look for
	 * @param string $productTextCode If specified, only texts with this code will be taken
	 * @param int $langId If specified, translations of attribute will be limited to that lang
	 *
	 * @return MelisEcomProductText[] Attribute object
	 */
	public function getProductTextsById($productId, $productTextCode = null, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_texts_byid_start', $arrayParameters);
	    
	    // Service implementation start
	    $prodTextTable = $this->getServiceLocator()->get('MelisEcomProductTextTable');
	    foreach($prodTextTable->getProductTextById($arrayParameters['productId'], $arrayParameters['productTextCode'], $arrayParameters['langId']) as $data) {
	        $results[] = $data;
	    }
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_texts_byid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the price affected to a product
	 *
	 * @param int $productId Product Id to look for
	 * @param int $countryId If specified, prices will be given only for the country specified
	 *
	 * @return MelisEcomPrice[] Price object
	 */
	public function getProductPricesById($productId, $countryId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_prices_byid_start', $arrayParameters);
	    
	    // Service implementation start
	    $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
	    foreach($priceTable->getPricesByProductId($arrayParameters['productId'], $arrayParameters['countryId']) as $data) {
	        $results[] = $data;
	    }
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	     
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_prices_byid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 * This method will return the Product final Price
	 *
	 * @param int $productId
	 * @param tin $countryId
	 * @return MelisEcomPrice|null
	 */
	public function getProductFinalPrice($productId, $countryId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_final_price_start', $arrayParameters);
	
	    // Service implementation start
	    $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
	    $productPrices = $priceTable->getProductFinalPrice($arrayParameters['productId']);
	     
	    $generalPrices = array();
	    $findPriceOnGeneral = true;
	    $isCountryPriceFound = false;
	    foreach ($productPrices As $val)
	    {
	        // Getting the General Price which is CountryId is 0 (Zero)
	        if ($val->price_country_id == 0)
	        {
	            $generalPrices = $val;
	        }
	        // Checking if CountryId match to Price CountryId
	        if ($val->price_country_id == $arrayParameters['countryId'])
	        {
	            // Just to be sure that data on Price is in Numeric data type
	            if (is_numeric($val->price_net))
	            {
	                $results = $val;
	                 
	                $findPriceOnGeneral = false;
	            }
	            $isCountryPriceFound = true;
	        }
	        
	        /**
	         * If Gerenal price has already has a data and Variant Coutnry Price found,
	         * loop will break and proceed to next process
	         * In this process loop will stop after data needed are found.
	         */
	        if (!empty($generalPrices) && $isCountryPriceFound)
	        {
	            break;
	        }
	    }
	
	    // If CountryId did not match to data result, this will try to look Stocks on General Stocks
	    if ($findPriceOnGeneral)
	    {
	        if (!empty($generalPrices))
	        {
	            // Just to be sure that data on price is in Numeric data type
	            if (is_numeric($generalPrices->price_net))
	            {
	                $results = $generalPrices;
	            }
	        }
	    }
	     
	    // Service implementation end
	     
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_final_price_end', $arrayParameters);
	
	    return  $arrayParameters['results'];
	}
	
	/**
	 * Returns the product name of the selected product ID based on the language ID
	 * @param String $productId
	 * @param int $langId
	 * @return String
	 */
	public function getProductName($productId, $langId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	     
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_get_product_name_start', $arrayParameters);
	
	    // Service implementation start
	    $data = $this->getProductTextsById($arrayParameters['productId'], 'TITLE', $arrayParameters['langId']);
	    $productName = '';

	    foreach($data as $text) {
	        if($text) {
	            if(!empty($text->ptxt_field_short)) {
	                $productName = $text->ptxt_field_short;
	            }
	        }
	    }
	
	    if(empty($productName)) {

	        $data = $this->getProductById($arrayParameters['productId'], $langId)->getProduct();
	        if(isset($data->prd_reference) && $data->prd_reference) {
	            $productName = $data->prd_reference;
	        }
	    }
	    $results = $productName;
	
	    // Service implementation end
	     
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_get_product_name_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves a product in database.
	 *
	 * @param array $product Reflects the melis_ecom_product table
	 * @param array $productTexts Reflects the melis_ecom_product_text table
	 * @param int[] $attributes List of attribute ids affected to the product
	 * @param int[] $categories List of categories which the product is affected to
	 * @param array[] $prices Reflects the melis_ecom_price table
	 * @param array[] $seo Reflects the melis_ecom_seo table
	 * @param int $productId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The product id created or updated, null if an error occured
	 */
	public function saveProduct($product, $productTexts = array(), $attributes = array(), 
	                            $categories = array(), $prices = array(), $seo = array(), $productId = null)
	{
	    // Event parameters prepare
	    $saveProductId = null;
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_save_start', $arrayParameters);
	    
	    // Service implementation start
	    $translator = $this->getServiceLocator()->get('translator');
	    $productTable = $this->getServiceLocator()->get('MelisEcomProductTable');
	    $categorySvc = $this->getServiceLocator()->get('MelisComCategoryService');
	    
	    $arrayParameters['productId'] = (int) $productTable->save($arrayParameters['product'], $arrayParameters['productId']);
	    $saveProductId = (int) $arrayParameters['productId'];
        $results['saveProduct'] = $arrayParameters['productId'];
        
        try {
            if(!empty($arrayParameters['productTexts']))
            {
                foreach($arrayParameters['productTexts'] as $productText)
                {

                        $prodTextId = isset($productText['ptxt_id']) ? $productText['ptxt_id'] : null;
                        $productText['ptxt_prd_id'] = $saveProductId;
                        $this->saveProductTexts(array_merge($productText, array('ptxt_prd_id' => $saveProductId)), $prodTextId);

                    
                }
            }
            
            if(!empty($arrayParameters['attributes']))
            {
                foreach($arrayParameters['attributes'] as $attribute)
                {
                    $this->saveProductAttributes(array_merge($attribute, array('patt_product_id' => $saveProductId)), $attribute['patt_id']);
                }
            }
            
            if(!empty($arrayParameters['categories']))
            {
                foreach($arrayParameters['categories'] as $category)
                {
                    $categorySvc->addCategoryProduct(array_merge($category, array('pcat_prd_id' => $arrayParameters['productId'])), (int) $category['pcat_id']);  
                }
            }
            
            if(!empty($arrayParameters['prices']))
            {
                foreach($arrayParameters['prices'] as $price)
                {
                   $this->saveProductPrices(array_merge($price, array('price_prd_id' => $saveProductId)), (int) $price['price_id']);
                }
            }
            
            // SEO Service
            $productSeo = $arrayParameters['seo'];
            $melisComSeoService = $this->getServiceLocator()->get('MelisComSeoService');
            $result = $melisComSeoService->saveSeoDataAction('product', $arrayParameters['productId'], $productSeo);
            
        }catch(\Exception $e) {
            $saveProductId = null;
        }


	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $saveProductId;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_save_end', $arrayParameters); 
	    return $arrayParameters['results'];
       
	}
	
	
	/**
	 *
	 * This method saves the texts for a product
	 * This will create/update entries and delete the one that could exist and not linked anymore, 
	 * the list of attributes must be full.
	 *
	 * @param int $productId Product Id to look for
	 * @param array $productTexts Reflects the melis_ecom_product_text table
	 *
	 * @return boolean True/false if the texts were successfuly added to the product
	 */
	public function saveProductTexts($productTexts, $productTextId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = false;
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_save_texts_start', $arrayParameters);
	    
	    
	    // Service implementation start
	    $ptxtId = null;
	    $translator = $this->getServiceLocator()->get('translator');
	    $productTextTable = $this->getServiceLocator()->get('MelisEcomProductTextTable');
	    $successFlag = true;
	    try {
    	    if(isset($arrayParameters['productTexts']['ptxt_lang_id'])) {
    	        $ptxtId = empty($arrayParameters['productTexts']['ptxt_id'])? null : $arrayParameters['productTexts']['ptxt_id'];
	            $results = (bool) $productTextTable->save($arrayParameters['productTexts'], $arrayParameters['productTextId']);
    	    }
	    }catch(\Exception $e) {
	        $results = false;
	        echo $e->getMessage(), '\n';
	    }
	  
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_save_texts_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves the attributes for a product
	 * This will create/update entries and delete the one that could exist and not linked anymore, 
	 * the list of attributes must be full.
	 *
	 * @param int $productId Product Id to look for
	 * @param int[] $attributes Reflects the melis_ecom_product_attribute table
	 *
	 * @return boolean True/false if the attributes were successfuly added to the product
	 */
	public function saveProductAttributes($attributes, $attributesId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = false;
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_save_attributes_start', $arrayParameters);	    
	    
	    // Service implementation start
	    $translator = $this->getServiceLocator()->get('translator');
	    $productAttributeTable = $this->getServiceLocator()->get('MelisEcomProductAttributeTable');
        unset($arrayParameters['attributes']['patt_id']);

        try {
            $results = (bool) $productAttributeTable->save($arrayParameters['attributes'], $arrayParameters['attributesId']);
        }catch(\Exception $e) {
            $results = false;
            echo $e->getMessage(), '\n';
        }
       
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_save_attributes_end', $arrayParameters);
	    
	    return $arrayParameters['results']; 
	}

	
	/**
	 *
	 * This method saves the prices for a product
	 * This will update or save prices.
	 *
	 * @param int $productId Product Id to look for
	 * @param array[] $prices Reflects the melis_ecom_price table
	 *
	 * @return boolean True/false if the prices were successfuly added to the product
	 */
	public function saveProductPrices($prices, $priceId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = false;
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_save_prices_start', $arrayParameters);
	    
	    // Service implementation start
        $productPriceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
        unset($arrayParameters['prices']['price_id']);
        try {
            // if comma (,) is used for grouping the price digit, then this process forcefuly replace comma to dot so it will 
            // still be saved in the database
            $columns = array('price_net', 'price_gross', 'price_vat_percent', 'price_vat_price', 'price_other_tax_price');

            for($x = 0; $x <= count($arrayParameters['prices']); $x++) {
                foreach($columns as $column) {
                    if(!empty(trim($arrayParameters['prices'][$column]))) {
                        $arrayParameters['prices'][$column] = str_replace(',', '.', $arrayParameters['prices'][$column]);
                        $arrayParameters['prices'][$column] = str_replace(' ', '', $arrayParameters['prices'][$column]);
                    }
                    else {
                        $arrayParameters['prices'][$column] = 0;
                    }

                }
            }

            $results = (bool) $productPriceTable->save($arrayParameters['prices'], $arrayParameters['priceId']);
            
        }catch(\Exception $e) {
            $results = false;
            echo $e->getMessage(), '\n';
        }
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_save_prices_end', $arrayParameters);
	    
	    return $arrayParameters['results']; 
	}
	
	public function deleteProductById($prodId) 
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = false;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_delete_start', $arrayParameters);
	     
	    // Service implementation start
	    $prodTable = $this->getServiceLocator()->get('MelisEcomProductTable');
	    $prodCatTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
	    $prodDocRelTable = $this->getServiceLocator()->get('MelisEcomDocRelationsTable');
	    $docTable = $this->getServiceLocator()->get('MelisEcomDocumentTable');
	    $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
	    $prodTextTable = $this->getServiceLocator()->get('MelisEcomProductTextTable');
	    $prodAttrTable = $this->getServiceLocator()->get('MelisEcomProductAttributeTable');
	    
	    $productId = (int) $arrayParameters['prodId'];
	    if($productId) {
	        
	        try {
	            $prodAttrTable->deleteByField('patt_product_id', $productId);
	            $prodCatTable->deleteByField('pcat_prd_id', $productId);
	            $prodDocRelTable->deleteByField('rdoc_product_id', $productId);
	            //$docTable->deleteById();
	            $priceTable->deleteByField('price_prd_id', $productId);
	            $prodTextTable->deleteByField('ptxt_prd_id', $productId);
	            $prodTable->deleteByField('prd_id', $productId);
	            
	            $data = $prodTable->getEntryById($productId)->current();
	            if(!$data) {
	                $results = true;
	            }
	        }catch(\Exception $e) {
	            $results = false;
	            echo $e->getMessage() . PHP_EOL;
	        }

	        
	        
	    }
	    
	    
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_delete_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	
}