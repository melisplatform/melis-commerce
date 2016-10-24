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
        // TODO CODE HERE
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
	    // TODO CODE HERE
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
	                                  $onlyValid = true, $start = 0, $limit = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_full_pricerange_start', $arrayParameters);
	     
	    // Service implementation start
	    // TODO CODE HERE
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_full_pricerange_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
}