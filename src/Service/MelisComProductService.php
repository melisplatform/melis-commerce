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
use Zend\Session\Container;

/**
 *
 * This service handles the product system of MelisCommerce.
 *
 */
class MelisComProductService extends MelisComGeneralService
{
    const SHORT_TEXT = 1;
    const LONG_TEXT  = 2;
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
    public function getProductList($langId = null, $categoryIds = array(), $countryId = null, 
	       $onlyValid = null, $start = 0, $limit = null,  $orderColumn = 'prd_id', $order = 'ASC', $search = '')
	{ 
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_list_start', $arrayParameters);
	    
        $prodTable = $this->getServiceLocator()->get('MelisEcomProductTable');
        $products = $prodTable->getProductList($arrayParameters['categoryIds'], $arrayParameters['countryId'], $arrayParameters['onlyValid'], $arrayParameters['start'], 
                                                 $arrayParameters['limit'], $arrayParameters['orderColumn'], $arrayParameters['order'], $arrayParameters['search']);
        
        foreach ($products As $val)
        {
            $product = $this->getProductById($val->prd_id, $arrayParameters['langId']);
            
            array_push($results, $product);
        }
	    // Service implementation end
	    
        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_list_end', $arrayParameters);

	    
	    return $arrayParameters['results'];
	}
	
	public function getAssocProducts($productId , $langId = null)
	{
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-' . $productId . '-getAssocProducts_' . $productId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_assoc_start', $arrayParameters);
	   
	    // Service implementation start
	    $entProd = new MelisProduct();
	    $tmpData = array();
	    $variantTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    
	    foreach($variantTable->getProductAssoc($arrayParameters['productId']) as $product){	        
	        $results[] = $this->getProductById($product->assoc_prd_id, $arrayParameters['langId']);
	    }
	    
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_assoc_end', $arrayParameters);

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	     
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
	 * @param string $docType Key string identifier  'IMG','FILE',
	 * @param string $docSubType Key string identifier for document sub type  'DEFAULT','SMALL','LARGE','MEDIUM'
	 *
	 * @return MelisProduct|null Product object
	 */
	public function getProductById($productId, $langId = null, $countryId = null, $docType = null, $docSubType = array())
	{
	    // Retrieve cache version if front mode to avoid multiple calls
	    $tmp = '';
	    foreach($docSubType as $type)
	        $tmp .= '_' . $type;
	    $cacheKey = 'product-' . $productId . '-getProductById_' . $productId . '_' . $countryId . '_' . $docType . '_' . $tmp;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
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
	    $prodDoc = $docSvc->getDocumentsByRelationAndTypes('product', $arrayParameters['productId'], $arrayParameters['docType'], $arrayParameters['docSubType']);
	    $entProd = new MelisProduct();
        if($productData) {
            foreach($productData as $prod) {
                $entProd->setId($prod->prd_id);
                $entProd->setProduct($prod);

                // Get all category
                $prodCats = $prodTable->getProductCategoryByProductId($arrayParameters['productId']);
                $catIds = [];
                $catt = [];

                foreach($prodCats as $prodCat) {
                    if (in_array($prodCat->pcat_id, $catIds)) {
                        if ($prodCat->catt_lang_id == $arrayParameters['langId']) {
                            // If category is already listed. override it with the correct language
                            $catt[$prodCat->pcat_id] = $prodCat;
                        }
                    } else {
                        $catt[$prodCat->pcat_id] = $prodCat;
                        array_push($catIds, $prodCat->pcat_id);
                    }
                }

                foreach ($catt as $cat) {
                    $category[] = $cat;
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

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	    
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
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-' . $productId . '-getProductAttributesById_' . $productId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
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
	    
	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	     
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
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-' . $productId . '-getProductTextsById_' . $productId . '_' . $productTextCode . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
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

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	     
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
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-' . $productId . '-getProductPricesById_' . $productId . '_' . $countryId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
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

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 * This method will retrieve the Categories that related
	 * to the Product
	 * @param int $productId
	 * @return MelisEcomProductCategoryTable
	 */
	public function getProductCategories($productId)
	{
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-' . $productId . '-getProductCategories_' . $productId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_get_product_categories_start', $arrayParameters);
	    
	    // Service implementation start
	    $prodCatTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
	    $productCat = $prodCatTable->getProductCategories($arrayParameters['productId']);
	    
	    foreach ($productCat As $key => $val)
	    {
	        array_push($results, $val);
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_get_product_categories_end', $arrayParameters);

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	     
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
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-' . $productId . '-getProductFinalPrice_' . $productId . '_' . $countryId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_final_price_start', $arrayParameters);
	
	    // Service implementation start
	    $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
	    $productPrice = $priceTable->getProductFinalPrice($arrayParameters['productId'], $arrayParameters['countryId'])->current();

	    if(!empty($productPrice))
	    {
	        // Just to be sure that data on Price is in Numeric data type
	        if (is_numeric($productPrice->price_net))
            {
                $results = $productPrice;
            }
	    }

	    /**
	     * If the Product Country price has no data
	     * this will try to get the General price of the Product
	     */
	    if (empty($arrayParameters['countryId']) && empty($productPrice))
	    {
	        // Retreiving the General price of the Product
	        $results = $this->getProductFinalPrice($arrayParameters['productId'], -1);
	    }
	    // Service implementation end
	     
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_final_price_end', $arrayParameters);

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	
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
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-' . $productId . '-getProductName_' . $productId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	     
	    // Sending service start event
	    $prodTextTable = $this->getServiceLocator()->get('MelisEcomProductTextTable');
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_get_product_name_start', $arrayParameters);
	
	    // Service implementation start 
	    $data = $this->getProductTextsById($arrayParameters['productId'], 'TITLE');
	    $productName = '';
	    foreach($data as $text) {
            if(!empty($text->ptxt_field_short) && $text->ptxt_lang_id == $arrayParameters['langId']) {
                $productName = $text->ptxt_field_short;
            }
	    }

		if(empty($productName)) {
			foreach($data as $text) {
	            if(!empty($text->ptxt_field_short)) {
	            	$produecText = $prodTextTable->getProductTextsWithLang($arrayParameters['productId'], $text->ptxt_lang_id)->current();
	                $productName = $produecText->ptxt_field_short.' ('.$produecText->elang_name.')';
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

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	    
	    return $arrayParameters['results'];
	}
	
	
	/**
	 *
	 * This method gets the texts affected to a product
	 *
	 * @param int $productId Product Id to look for
	 * @param string $typeCode If specified, only texts with this code will be taken
	 *
	 * @return String
	 */
	public function getProductTextByCode($productId, $typeCode, $langId = 1) 
	{
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-' . $productId . '-getProductTextByCode_' . $productId . '_' . $typeCode . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	        
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_texts_bycode_start', $arrayParameters);
	     
	    // Service implementation start
	    $prodTextTable = $this->getServiceLocator()->get('MelisEcomProductTextTable');
	    $prodTextData = $prodTextTable->getProductTextById($arrayParameters['productId'], $arrayParameters['typeCode'], $arrayParameters['langId'])->current();
	    $hasText = false;
	    if($prodTextData) {
	        $hasText = true;
	    }
	    else {
	        $prodTextData = $prodTextTable->getProductTextById($arrayParameters['productId'], $arrayParameters['typeCode'])->current();
	        if($prodTextData) {
	            $hasText = true;
	        }
	    }
	    
	    if($hasText) {
	        $typeId = (int) $prodTextData->ptt_field_type;
	         
	        if($typeId == self::SHORT_TEXT) {
	            $results = $prodTextData->ptxt_field_short;
	        }
	        elseif($typeId == self::LONG_TEXT) {
	            $results = $prodTextData->ptxt_field_long;
	        }
	    }
	    
	    // if result has empty text, then get the first data that has text
	    if(!$results) {
	        $prodTextData = $prodTextTable->getProductTextById($arrayParameters['productId'], $arrayParameters['typeCode']);
	        foreach($prodTextData as $text) {
	            $typeId = (int) $text->ptt_field_type;
	            
	            if($typeId == self::SHORT_TEXT && $text->ptxt_field_short) {
	                $results = $text->ptxt_field_short;
	            }
	            elseif($typeId == self::LONG_TEXT && $text->ptxt_field_long) {
	                $results = $text->ptxt_field_long;
	            }
	        }
	    }

	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_texts_bycode_end', $arrayParameters);

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	    
	    return $arrayParameters['results'];
	}
	
	public function getProductsByCategoryId($categoryId, $onlyValid = false, $langId = null, $order = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	     
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_products_by_category_id_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomProductTable = $this->getServiceLocator()->get('MelisEcomProductTable');
	    
	    $product = $melisEcomProductTable->getProductsByCategoryId($arrayParameters['categoryId'], $arrayParameters['onlyValid'], $arrayParameters['langId'], $arrayParameters['order']);
	    
	    foreach ($product As $val)
	    {
	        array_push($results, $val);
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_products_by_category_id_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	public function getProductVariants($productId, $onlyValid = false)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_pget_product_variants_start', $arrayParameters);
	     
	    // Service implementation start
	    
	    $variantTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    
	    $prdVariants = $variantTable->getProductVariants($arrayParameters['productId'], $arrayParameters['onlyValid']);
	    
	    foreach ($prdVariants As $val)
	    {
	        array_push($results, $val);
	    }
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_pget_product_variants_end', $arrayParameters);
	     
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
	    $variantTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
	    
	    $prodStatus = isset($arrayParameters['product']['prd_status']) ? (int) $arrayParameters['product']['prd_status'] : 0;
	    $prodId     = isset($arrayParameters['product']['prd_id']) ? (int) $arrayParameters['product']['prd_id'] : null;    
	    $arrayParameters['productId'] = (int) $productTable->save($arrayParameters['product'], $arrayParameters['productId']);
	    $saveProductId = (int) $arrayParameters['productId'];
        $results['saveProduct'] = $arrayParameters['productId'];
       
        try {
            // Deactivate variants that is under to this product
            if(!$prodStatus && !is_null($prodId)) {
                $variantTable->update(array('var_status' => 0), 'var_prd_id', $prodId);
            }
            
            
            if(!empty($arrayParameters['productTexts']))
            {
                foreach($arrayParameters['productTexts'] as $productText)
                {
                        $prodTextId = isset($productText['ptxt_id']) ? $productText['ptxt_id'] : null;
                        unset($productText['ptxt_id']);
                        $productText['ptxt_prd_id'] = $saveProductId;
                        $this->saveProductTexts(array_merge($productText, array('ptxt_prd_id' => $saveProductId)), $prodTextId);
                }
            }
            
            if(!empty($arrayParameters['attributes']))
            {
                foreach($arrayParameters['attributes'] as $attribute)
                {
                    $patt_id = isset($attribute['patt_id']) ? $attribute['patt_id'] : null;
                    unset($attribute['patt_id']);
                    $this->saveProductAttributes(array_merge($attribute, array('patt_product_id' => $saveProductId)), $patt_id);
                }
            }
            
            if(!empty($arrayParameters['categories']))
            {
                foreach($arrayParameters['categories'] as $category)
                {
                    $pcat_id = isset($category['pcat_id']) ? $category['pcat_id'] : null;
                    unset($category['pcat_id']);
                    $categorySvc->addCategoryProduct(array_merge($category, array('pcat_prd_id' => $arrayParameters['productId'])), (int) $pcat_id);  
                }
            }
            
            if(!empty($arrayParameters['prices']))
            {
                foreach($arrayParameters['prices'] as $price)
                {
                    $price_id = isset($price['price_id']) ? $price['price_id'] : null;
                    unset($price['price_id']);
                    $this->saveProductPrices(array_merge($price, array('price_prd_id' => $saveProductId)), (int) $price_id);
                }
            }
            
            // SEO Service
            if (!empty($arrayParameters['seo']))
            {
                $productSeo = $arrayParameters['seo'];
                $melisComSeoService = $this->getServiceLocator()->get('MelisComSeoService');
                $result = $melisComSeoService->saveSeoDataAction('product', $arrayParameters['productId'], $productSeo);
            }
            
            $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
            $melisEngineCacheSystem->deleteCacheByPrefix('product-' . $saveProductId, 'commerce_big_services');
            
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
	    $translator = $this->getServiceLocator()->get('translator');
	    $productTextTable = $this->getServiceLocator()->get('MelisEcomProductTextTable');
	    $successFlag = true;
	    try {
    	    if(isset($arrayParameters['productTexts']['ptxt_lang_id']) && $arrayParameters['productTexts']['ptxt_lang_id']) {
	            $results = (bool) $productTextTable->save($arrayParameters['productTexts'], $arrayParameters['productTextId']);
	            
	            if (!empty($arrayParameters['productTexts']['ptxt_prd_id']))
	            {
                    $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
                    $melisEngineCacheSystem->deleteCacheByPrefix('product-' . $arrayParameters['productTexts']['ptxt_prd_id'], 'commerce_big_services');
	            }
    	    }
    	    
	    }catch(\Exception $e) {
	        $results = false;
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

            if (!empty($arrayParameters['attributes']['patt_product_id']))
            {
                $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
                $melisEngineCacheSystem->deleteCacheByPrefix('product-' . $arrayParameters['attributes']['patt_product_id'], 'commerce_big_services');
            }
            
        }catch(\Exception $e) {
            $results = false;
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
            
            $results = (bool) $productPriceTable->save($arrayParameters['prices'], $arrayParameters['priceId']);
            
            if (!empty($arrayParameters['prices']['price_prd_id']))
            {
                $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
                $melisEngineCacheSystem->deleteCacheByPrefix('product-' . $arrayParameters['prices']['price_prd_id'], 'commerce_big_services');
            }
            
        }catch(\Exception $e) {
            $results = false;
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
	    $melisComCategoryService = $this->getServiceLocator()->get('MelisComCategoryService');
	    
	    $productId = (int) $arrayParameters['prodId'];
	    if($productId) {
	        
	        try {
	            $prodAttrTable->deleteByField('patt_product_id', $productId);
	            //$prodCatTable->deleteByField('pcat_prd_id', $productId);
	            $prodDocRelTable->deleteByField('rdoc_product_id', $productId);
	            //$docTable->deleteById();
	            $priceTable->deleteByField('price_prd_id', $productId);
	            $prodTextTable->deleteByField('ptxt_prd_id', $productId);
	            $prodTable->deleteByField('prd_id', $productId);
	            
	            $prdCategory = $prodCatTable->getEntryByField('pcat_prd_id', $productId);
	            foreach ($prdCategory As $prd){
	                $melisComCategoryService->deleteCategoryProduct($prd->pcat_id);
	            }
	            
	            $data = $prodTable->getEntryById($productId)->current();
	            if(!$data) {
	                $results = true;
                    $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
                    $melisEngineCacheSystem->deleteCacheByPrefix('product-' . $productId, 'commerce_big_services');
	            }
	        }catch(\Exception $e) {
	            $results = false;
	        }
	    }
	    
	    
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_delete_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 * Returns the price format depending on the locale set in the browser or in the platform session
	 * @param unknown $price
	 * @return string
	 */
	public function formatPrice($price, $overrideLocale = null)
	{
	    // by default, use request headers "Accept-Language"
	    $headerAcceptLang = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
	    // if Accept-Language is null, then default to english US
	    $sessionLocale = $headerAcceptLang ? $headerAcceptLang : 'en_US';
	
	    $container = new Container('meliscore');
	    if (!empty($container['melis-lang-locale']) && is_null($overrideLocale)) {
	        //$sessionLocale = $container['melis-lang-locale'];
	    }
	        
        if($sessionLocale == 'en_EN') {
            // replace en_EN to en_US, since en_EN is not defined in the browser or in Windows OS and Linux locale
            $sessionLocale = 'en_US';
        }
        
        if(!is_null($overrideLocale)) {
            $sessionLocale = $overrideLocale;
        }

        setlocale(LC_MONETARY, $sessionLocale);
        $locale = localeconv();
        $curSymbol = trim($locale['currency_symbol']) ? trim($locale['currency_symbol']) : '$';
        $curSymbolInt = trim($locale['int_curr_symbol']) ? trim($locale['int_curr_symbol']) : 'USD';
        $decimalPoint = trim($locale['mon_decimal_point']) ? trim($locale['mon_decimal_point']) : null;
        $thousandSeparator = trim($locale['mon_thousands_sep']) ? trim($locale['mon_thousands_sep']) : ',';
        $fmt = new \NumberFormatter($sessionLocale, \NumberFormatter::CURRENCY );
        $value = $fmt->formatCurrency((float)$price, $curSymbolInt);

        $value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value); // replace special characters
        $value = preg_replace('/[a-zA-Z$ ]/', '', $value); // replace $, [space] and alphabets in price

        $newVal = $price;

        if($decimalPoint) {
            $value = explode($decimalPoint, $value);
            if(is_array($value)) {
                $tmpVal = str_replace($thousandSeparator, '', $value[0]);
                $newVal = $tmpVal . $decimalPoint . $value[1];
            }
        }

        return $newVal;
	}
	
	/*
	 * Returns a product's variant price based on the column order and price oolumn requested
	 * 
	 * @param string $order The db order. ASC/DESC
	 * @param string $price_net The price column to get
	 * 
	 * @return Object|NULL
	 */
	public function getProductVariantPriceById($productId, $order = 'ASC', $priceColumn = 'price_net')
	{
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-' . $productId . '-getProductVariantPriceById_' . $productId . '_' . $order . '_' . $priceColumn;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = false;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_get_variant_price_start', $arrayParameters);	     
	     
	    // Service implementation start
	    $productTbl = $this->getServiceLocator()->get('MelisEcomProductTable');
	    $currencyTbl = $this->getServiceLocator()->get('MelisEcomCurrencyTable');
	    try {
	        $prices = $productTbl->getProductVariantPriceById($arrayParameters['productId'], $arrayParameters['order'], $arrayParameters['priceColumn']);
	        $column = $arrayParameters['priceColumn'];
	        foreach($prices as $price){
	            if(!empty($price->$column)){	                
	                $results = $price;
	                break;
	            }
	        }
	        
	    }catch(\Exception $e) {
	        $results = false;
	    }
	     
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_get_variant_price_end', $arrayParameters);

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 * Returns a product, product text and seo
	 * 
	 * @param int $productId Id of the product
	 * @param int $langId language id of the product text
	 * @return Object|False
	 */
	public function getProductTitleAndSeoById($productId, $langId)
	{
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'product-' . $productId . '-getProductTitleAndSeoById_' . $productId . '_' . $langId;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = false;
	     
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_titleseo_byid_start', $arrayParameters);
	    
	    // Service implementation start
	    $productTbl = $this->getServiceLocator()->get('MelisEcomProductTable');
	    try {
            $results = $productTbl->getProductTitleAndSeoById($arrayParameters['productId'], $arrayParameters['langId'])->current();
            
	    }catch(\Exception $e) {
	    }
	    
	    // Service implementation end
	     
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_titleseo_byid_end', $arrayParameters);

	    // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
	    
	    return $arrayParameters['results'];
	}
	
	public function getProductBasicDetails($productId, $countryId = null, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = false;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_get_product_basic_details_start', $arrayParameters);
	     
	    // Service implementation start
	    
	    /**
	     * Product prices
	     */
	    $prdPrice = $this->getProductFinalPrice($arrayParameters['productId'], $arrayParameters['countryId']);

	    $prdPriceDetails = array(
	        'prd_currency_symbol' => (!empty($prdPrice->cur_symbol)) ? $prdPrice->cur_symbol : '',
	        'prd_currency_code' => (!empty($prdPrice->cur_code)) ? $prdPrice->cur_code : '',
	        'prd_price_net' => (!empty($prdPrice->price_net)) ? $prdPrice->price_net : '',
	    );

	    $productTbl = $this->getServiceLocator()->get('MelisEcomProductTable');
	    $product = $productTbl->getEntryById($arrayParameters['productId'])->current();
	    
	    /**
	     * Product image documents
	     */
	    $docSrv = $this->getServiceLocator()->get('MelisComDocumentService');
	    $prdDocsImages = $docSrv->getFinalImageFilePath('product', $arrayParameters['productId'], array('DEFAULT'));
	    
	    /**
	     * Product texts
	     */
	    $prdText = '';
	    $prdTexts = $this->getProductTextsById($arrayParameters['productId'], 'TITLE', $arrayParameters['langId']);
	    if (!empty($prdTexts))
	    {
	        $prdText = ($prdTexts[0]->ptxt_type == 1) ? $prdTexts[0]->ptxt_field_short : $prdTexts[0]->ptxt_field_long;
	    }

	    /**
	     * Product categories
	     */
	    $catTable = $this->getServiceLocator()->get('MelisEcomCategoryTable');
	    $prdCats = $catTable->getProductCategoriesWithFinalTransalations($arrayParameters['productId'], $arrayParameters['langId'])->toArray();

	    $product = array(
	        'prd_id' => $arrayParameters['productId'],
	        'prd_text' => (!empty($prdText)) ? $prdText : $product->prd_reference,
	        'prd_price_details' => $prdPriceDetails,
	        'prd_categories' => $prdCats,
	        'prd_docs_image' => $prdDocsImages
	    );
	    
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $product;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_product_get_product_basic_details_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}

    /**
     * Function to get Maximum / Minimum Price
     * Depending on price column and the table (Product, Variant)
     *
     * @param null $type
     * @param string $priceColumn
     * @param string $from
     * @return mixed
     */
    public function getMaximumMinimumPrice($type = null, $priceColumn = "price_net", $from = "product")
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_product_get_maximum_minimum_price_start', $arrayParameters);

        $priceTable = $this->getServiceLocator()->get('MelisEcomPriceTable');
        $result = $priceTable->getMaximumMinimumPrice($arrayParameters['type'], $arrayParameters['priceColumn'], $arrayParameters['from'])->current();

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $result;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_product_get_maximum_minimum_price_end', $arrayParameters);

        return $arrayParameters['results'];
    }
	
	/**
	 * Returns true if the OS is Windows
	 * @return boolean
	 */
	public function isWindows()
	{
	    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	        return true;
	    } else {
	        return false;
	    }
	}
	
	
}