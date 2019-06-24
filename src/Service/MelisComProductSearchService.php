<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;


/**
 *
 * This service handles the product search system of MelisCommerce.
 *
 */
use MelisCommerce\Entity\MelisProduct;
class MelisComProductSearchService extends MelisComGeneralService
{
	/**
	 *
	 * This method will search for products by texts defined for them
	 * Product will come back with: categories, texts translations, price, documents
	 * It won't load variants
	 *
	 * @param string $search The search string
	 * @param string[] $fieldsTypeCodes The types of text to search in
	 * @param int $langId If specified, translations of products will be limited to that lang
	 * @param int[] $categoryId If not empty, only products from the list of categories will be taken
	 * @param int $countryId If specified, prices will be given only for the country specified
	 * @param boolean $onlyValid if true, returns only active status products
	 * @param int $start If not specified, it will start at the begining of the list
	 * @param int $limit If not specified, it will bring all products of the list
	 *
	 * @example searchProductByTextField('long shirt', array('TITLE'), 1, array(1, 2, 3));
	 *
	 * @return MelisProduct[] Product object
	 */
	public function searchProductByTextFields($search, $fieldsTypeCodes = array(), $langId = null, $categoryId = array(), 
	                                         $countryId = null, $onlyValid = true, $start = 0, $limit = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_bytextfields_start', $arrayParameters);
	    
	    // Service implementation start
	    $prodTable = $this->getServiceLocator()->get('MelisEcomProductTable');

	    $productData = $prodTable->getProductByTextAndType($arrayParameters['search'], 
                                                                $arrayParameters['fieldsTypeCodes'], $arrayParameters['categoryId'], $arrayParameters['langId'], (int) $arrayParameters['onlyValid'],
                                                                $arrayParameters['start'], $arrayParameters['limit']
                                                           )->toArray();
        if($productData) {
       
           $categoryData = array();
           $docData = array();
           $prodTexts = array();
           $prodPrice = array();
       
           $ctr = 0;
           foreach($productData as $searchedData) {
                $product = new MelisProduct();
                $product->setId( (int) $searchedData['prd_id'] );

                $categoryData = $prodTable->getProductCategoryByProductIdAndCategoryId($searchedData['prd_id'], $arrayParameters['categoryId'], $arrayParameters['langId'])->toArray();
                $product->setCategories($categoryData);

                $docData =  $prodTable->getProductDocumentByProductIdAndCountryId($searchedData['prd_id'], $arrayParameters['countryId'])->toArray();
                $product->setDocuments($docData);
               
                $prodTexts = $prodTable->getProductText($searchedData['prd_id'], $arrayParameters['langId'])->toArray();
                $product->setTexts($prodTexts);
               
                $prodPrice[] =  $prodTable->getProductPrice($searchedData['prd_id'], $arrayParameters['countryId'])->toArray();
                if(isset($prodPrice[$ctr])) {
                   $tmpPrice = array();
                   $prcCtr = 0;
                   foreach($prodPrice[$ctr] as $prcData) {
                       $tmpPrice[$ctr][$prcCtr] = $this->removeDataWithPrefix('prd_', $prcData);
                       $prcCtr++;
                   }
                   if(isset($tmpPrice[$ctr])) {
                       $prodPrice[$ctr] = $tmpPrice[$ctr];
                       $product->setPrice($prodPrice[$ctr]);
                   }
                }

                $tmpProductData = $this->splitData('prd_', $productData[$ctr]);
                unset($tmpProductData['ptxt_prd_id']);
                
                $product->setProduct($tmpProductData);
                
                $results[] = $product;
                $ctr++;
           }
        }
	    // Service implementation end

        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_bytextfields_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method will search for products by attribute values and prices
	 * This method is ideally used for filtering menus on categories' pages
	 * Product will come back with: categories, texts translations, price, documents
	 * It won't load variants
	 *
	 * @param int[] $attributeValuesIds The list of attributes values ids that are required
	 * @param float $priceMin Minimum price
	 * @param float $priceMax Maximum price
	 * @param int $langId If specified, translations of products will be limited to that lang
	 * @param int[] $categoryId If not empty, only products from the list of categories will be taken
	 * @param int $countryId If specified, prices will be givena nd searched only for the country specified
	 * @param boolean $onlyValid if true, returns only active status products
	 * @param int $start If not specified, it will start at the begining of the list
	 * @param int $limit If not specified, it will bring all products of the list
	 *
	 * @example searchProductByAttributeValuesAndPriceRange(array(1, 2), 0, 500, 1, array(1, 2, 3));
	 *
	 * @return MelisProduct[] Product object
	 */
	public function searchProductByAttributeValuesAndPriceRange($attributeValuesIds = array(), $priceMin = null, $priceMax = null,
	                                                            $langId = null, $categoryId = array(), $countryId = null, 
	                                                            $onlyValid = true, $start = 0, $limit = null)
	{
	    
	    
	    
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_byattvalues_pricerange_start', $arrayParameters);

	    // Service implementation start
	    $prodTable = $this->getServiceLocator()->get('MelisEcomProductTable');
	    $product = new MelisProduct();
	    
	    $productData = $prodTable->getProductByAttributeValueIdsAndPriceRange($arrayParameters['attributeValuesIds'], (float) $arrayParameters['priceMin'], (float) $arrayParameters['priceMax'],
                                                                	        $arrayParameters['categoryId'], $arrayParameters['countryId'], (int) $arrayParameters['onlyValid'], $arrayParameters['start'], $arrayParameters['limit']
                                                                        )->toArray();
                                                                        
                                                                        
        if($productData) {
             
            $categoryData = array();
            $docData = array();
            $prodTexts = array();
            $prodPrice = array();
             
            $ctr = 0;
            foreach($productData as $searchedData) {
                $product = new MelisProduct();
                $product->setId( (int) $searchedData['prd_id'] );
                 
                $categoryData = $prodTable->getProductCategoryByProductIdAndCategoryId($searchedData['prd_id'], $arrayParameters['categoryId'], $arrayParameters['langId'])->toArray();
                $product->setCategories($categoryData);

                $docData =  $prodTable->getProductDocumentByProductIdAndCountryId($searchedData['prd_id'], $arrayParameters['countryId'])->toArray();
                $product->setDocuments($docData);
               
                $prodTexts = $prodTable->getProductText($searchedData['prd_id'], $arrayParameters['langId'])->toArray();
                $product->setTexts($prodTexts);
                 
                $prodPrice[] =  $prodTable->getProductPrice($searchedData['prd_id'], $arrayParameters['countryId'])->toArray();
                if(isset($prodPrice[$ctr])) {
                    $tmpPrice = array();
                    $prcCtr = 0;
                    foreach($prodPrice[$ctr] as $prcData) {
                        $tmpPrice[$ctr][$prcCtr] = $this->removeDataWithPrefix('prd_', $prcData);
                        $prcCtr++;
                    }
                    if(isset($tmpPrice[$ctr])) {
                        $prodPrice[$ctr] = $tmpPrice[$ctr];
                        $product->setPrice($prodPrice[$ctr]);
                    }
                }
        
                $tmpProductData = $this->splitData('prd_', $productData[$ctr]);
                unset($tmpProductData['ptxt_prd_id']);
        
                $product->setProduct($tmpProductData);
                 
                $results[] = $product;
                $ctr++;
            }
        }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_byattvalues_pricerange_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method will search for products by texts, attribute values and prices
	 * This search functions is combination of searchProductByTextFields and searchProductByAttributeValuesAndPriceRange
	 * Depending on your needs, you can only use this one, or use independtly the others for better performance
	 * Product will come back with: categories, texts translations, price, documents
	 * It won't load variants
	 *
	 * @param string $search The search string
	 * @param string[] $fieldsTypeCodes The types of text to search in
	 * @param int[] $attributeValuesIds The list of attributes values ids that are required
	 * @param float $priceMin Minimum price
	 * @param float $priceMax Maximum price
	 * @param int $langId If specified, translations of products will be limited to that lang
	 * @param int[] $categoryId If not empty, only products from the list of categories will be taken
	 * @param int $countryId If specified, prices will be givena nd searched only for the country specified
	 * @param boolean $onlyValid if true, returns only active status products
	 * @param int $start If not specified, it will start at the begining of the list
	 * @param int $limit If not specified, it will bring all products of the list
	 *
	 * @example searchProductByAttributeValuesAndPriceRange(array(1, 2), 0, 500, 1, array(1, 2, 3));
	 *
	 * @return MelisProduct[] Product object
	 */
	public function searchProductFull($search, $fieldsTypeCodes = array(),
	                                  $attributeValuesIds = array(), $priceMin = null, $priceMax = null,
	                                  $langId = null, $categoryId = array(), $countryId = null, 
	                                  $onlyValid = true, $start = 0, $limit = null, $sort = null, $priceColumn = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_full_pricerange_start', $arrayParameters);
	     
	    // Service implementation start
        $selectedVariants = array();
	    $prodTable = $this->getServiceLocator()->get('MelisEcomProductTable');
	    if(!empty($arrayParameters['attributeValuesIds']) && is_array($arrayParameters['attributeValuesIds'])) {
            $selectedVariants = $prodTable->getProductVariantByAttributesId($arrayParameters['attributeValuesIds']);
            if(empty($selectedVariants)){
                $selectedVariants = array('');
            }
        }

	    $productData = array();
	    $data = $prodTable->getProductByNameTextTypeAttrIdsAndPrice($arrayParameters['search'], $arrayParameters['fieldsTypeCodes'],
            $selectedVariants, $arrayParameters['categoryId'], (float) $arrayParameters['priceMin'], (float) $arrayParameters['priceMax'],  $arrayParameters['langId'],
	        $arrayParameters['countryId'], (int) $arrayParameters['onlyValid'], $arrayParameters['start'], $arrayParameters['limit'], $arrayParameters['sort'], $arrayParameters['priceColumn']
        );

        if($data) {
            //remove the product if the price is empty (only if there is any filter related to price)
            foreach($data as $product){
                if(empty($arrayParameters['priceMin']) && empty($arrayParameters['priceMax']) && empty($arrayParameters['priceColumn'])) {
                    $productData[] = $product;
                }else{
                    if(!empty($product->price)){
                        $productData[] = $product;
                    }
                }
                unset($product->price);
                unset($product->country);
            }
            
            $productData = array_unique($productData, SORT_REGULAR);
            
            $prdSrv = $this->getServiceLocator()->get('MelisComProductService');
            
            foreach ($productData As $val)
            {
                /**
                 * Retieving basic details of a single product
                 * from Product service
                 */
                $results[] = $prdSrv->getProductBasicDetails($val->prd_id, $countryId, $langId);
            }
            
        }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_full_pricerange_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method will fetch products by category ID
	 * Product will come back with: category selected, texts translations, price, documents
	 * It won't load variants
	 *
	 * @param array $categoryId Retrieves a list of product based on the category ID
	 * @param int $langId If specified, translations of products will be limited to that lang
	 * @param int $countryId If specified, prices will be givena nd searched only for the country specified
	 *
	 * @example searchProductByAttributeValuesAndPriceRange(array(1, 2), 0, 500, 1, array(1, 2, 3));
	 *
	 * @return MelisProduct[] Product object
	 */
	public function getProductByCategory($categoryId, $langId = null, $countryId = null, $fieldsTypeCodes = array(), $docTypes = array())
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_product_by_category_start', $arrayParameters);
	    
	    // Service implementation start
	    $prodTable = $this->getServiceLocator()->get('MelisEcomProductTable');
	    
	    $prods = $prodTable->getProductCategoryPriceByProductId(null, $arrayParameters['categoryId'], 
	        $arrayParameters['langId'], $arrayParameters['countryId'], 
	        $arrayParameters['fieldsTypeCodes'], $arrayParameters['docTypes']
        );
	   
	    foreach($prods as $prod){
	         $results[] = $prod;
	    }
	   
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_product_by_category_end', $arrayParameters);

	    return $arrayParameters['results'];
	    
	}
	
	/**
	 * This method returns either the minimum or maximum price on the price table
	 * @param string $order db order, ASC/DESC
	 * @param string $column column to sort
	 * @param array $categoryId limits the queary to the array of category
	 * @return object returns the first row of the result
	 */
	public function getPriceByColumn($order, $column = 'price_net', $categoryId = array())
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = false;
	     
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_get_price_by_column_start', $arrayParameters);
	     
	    // Service implementation start
	    $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
	    $results = $priceTable->getPriceByColumnOrder($arrayParameters['order'], $arrayParameters['column'], $arrayParameters['categoryId'])->current(); 
	    // Service implementation end
	     
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_get_price_by_column_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 * This function is the opposite of splitData, this function removes
	 * the data with prefix  provided in the parameter
	 * @param string $prefix
	 * @param array $haystack
	 * @return array
	 */
	public function removeDataWithPrefix($prefix, $haystack = array())
	{
	    $data = array();
	    if($haystack) {
	        foreach($haystack as $key => $value) {
	            if(strpos($key, $prefix) !== false) {
	                unset($data[$key]);
	            }
	            else {
	                $data[$key] = $value;
	            }
	        }
	    }
	     
	    return $data;
	}
	
	/**
	 * Used to split array data and return the data you need
	 * @param String $prefix of the array data
	 * @param array $haystack
	 * @return array
	 */
	public function splitData($prefix, $haystack = array())
	{
	    $data = array();
	    if($haystack) {
	        foreach($haystack as $key => $value) {
	            if(strpos($key, $prefix) !== false) {
	                $data[$key] = $value;
	            }
	        }
	    }
	
	    return $data;
	}
}