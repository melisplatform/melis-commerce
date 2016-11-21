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
	    $coreToolSvc = $this->getServiceLocator()->get('MelisCoreTool');
	    

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
       
               $categoryData[] = $prodTable->getProductCategoryByProductIdAndCategoryId($searchedData['prd_id'], $arrayParameters['categoryId'], $arrayParameters['langId'])->toArray();
               if(isset($categoryData[$ctr])) {
                   $tmpCategory = array();
                   $catCtr = 0;
                   foreach($categoryData[$ctr] as $catData) {
                       $tmpCategory[$ctr][$catCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $catData);
                       $tmpCategory[$ctr][$catCtr] = $coreToolSvc->removeDataWithPrefix('pcat_', $tmpCategory[$ctr][$catCtr]);
                       $catCtr++;
                   }
                   if(isset($tmpCategory[$ctr])) {
                       $categoryData[$ctr] = $tmpCategory[$ctr];
                       $product->setCategories($categoryData[$ctr]);
                   }
               }

               $docData[] =  $prodTable->getProductDocumentByProductIdAndCountryId($searchedData['prd_id'], $arrayParameters['countryId'])->toArray();
               if(isset($docData[$ctr])) {
                   $tmpDoc = array();
                   $docCtr = 0;
                   foreach($docData[$ctr] as $docData) {
                       $tmpDoc[$ctr][$docCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $docData);
                       $tmpDoc[$ctr][$docCtr] = $coreToolSvc->removeDataWithPrefix('rdoc_', $tmpDoc[$ctr][$docCtr]);
                       $docCtr++;
                   }
                   if(isset($tmpDoc[$ctr])) {
                       $docData[$ctr] = $tmpDoc[$ctr];
                       $product->setDocuments($docData[$ctr]);
                   }
               }
               
               
               $prodTexts[] = $prodTable->getProductText($searchedData['prd_id'], $arrayParameters['langId'])->toArray();
               if(isset($prodTexts[$ctr])) {
                   $tmpTexts = array();
                   $txtCtr = 0;
                   foreach($prodTexts[$ctr] as $txtData) {
                       $tmpTexts[$ctr][$txtCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $txtData);
                       $txtCtr++;
                   }
                   if(isset($tmpTexts[$ctr])) {
                       $prodTexts[$ctr] = $tmpTexts[$ctr];
                       $product->setTexts($prodTexts[$ctr]);
                   }
               }
               
               
               $prodPrice[] =  $prodTable->getProductPrice($searchedData['prd_id'], $arrayParameters['countryId'])->toArray();
               if(isset($prodPrice[$ctr])) {
                   $tmpPrice = array();
                   $prcCtr = 0;
                   foreach($prodPrice[$ctr] as $prcData) {
                       $tmpPrice[$ctr][$prcCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $prcData);
                       $prcCtr++;
                   }
                   if(isset($tmpPrice[$ctr])) {
                       $prodPrice[$ctr] = $tmpPrice[$ctr];
                       $product->setPrice($prodPrice[$ctr]);
                   }
               }

               $tmpProductData = $coreToolSvc->splitData('prd_', $productData[$ctr]);
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
	    $coreToolSvc = $this->getServiceLocator()->get('MelisCoreTool');
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
                 
                $categoryData[] = $prodTable->getProductCategoryByProductIdAndCategoryId($searchedData['prd_id'], $arrayParameters['categoryId'], $arrayParameters['langId'])->toArray();
                if(isset($categoryData[$ctr])) {
                    $tmpCategory = array();
                    $catCtr = 0;
                    foreach($categoryData[$ctr] as $catData) {
                        $tmpCategory[$ctr][$catCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $catData);
                        $tmpCategory[$ctr][$catCtr] = $coreToolSvc->removeDataWithPrefix('pcat_', $tmpCategory[$ctr][$catCtr]);
                        $catCtr++;
                    }
                    if(isset($tmpCategory[$ctr])) {
                        $categoryData[$ctr] = $tmpCategory[$ctr];
                        $product->setCategories($categoryData[$ctr]);
                    }
                }
        
                $docData[] =  $prodTable->getProductDocumentByProductIdAndCountryId($searchedData['prd_id'], $arrayParameters['countryId'])->toArray();
                if(isset($docData[$ctr])) {
                    $tmpDoc = array();
                    $docCtr = 0;
                    foreach($docData[$ctr] as $docData) {
                        $tmpDoc[$ctr][$docCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $docData);
                        $tmpDoc[$ctr][$docCtr] = $coreToolSvc->removeDataWithPrefix('rdoc_', $tmpDoc[$ctr][$docCtr]);
                        $docCtr++;
                    }
                    if(isset($tmpDoc[$ctr])) {
                        $docData[$ctr] = $tmpDoc[$ctr];
                        $product->setDocuments($docData[$ctr]);
                    }
                }
                 
                 
                $prodTexts[] = $prodTable->getProductText($searchedData['prd_id'], $arrayParameters['langId'])->toArray();
                if(isset($prodTexts[$ctr])) {
                    $tmpTexts = array();
                    $txtCtr = 0;
                    foreach($prodTexts[$ctr] as $txtData) {
                        $tmpTexts[$ctr][$txtCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $txtData);
                        $txtCtr++;
                    }
                    if(isset($tmpTexts[$ctr])) {
                        $prodTexts[$ctr] = $tmpTexts[$ctr];
                        $product->setTexts($prodTexts[$ctr]);
                    }
                }
                 
                 
                $prodPrice[] =  $prodTable->getProductPrice($searchedData['prd_id'], $arrayParameters['countryId'])->toArray();
                if(isset($prodPrice[$ctr])) {
                    $tmpPrice = array();
                    $prcCtr = 0;
                    foreach($prodPrice[$ctr] as $prcData) {
                        $tmpPrice[$ctr][$prcCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $prcData);
                        $prcCtr++;
                    }
                    if(isset($tmpPrice[$ctr])) {
                        $prodPrice[$ctr] = $tmpPrice[$ctr];
                        $product->setPrice($prodPrice[$ctr]);
                    }
                }
        
                $tmpProductData = $coreToolSvc->splitData('prd_', $productData[$ctr]);
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
	                                  $onlyValid = true, $start = 0, $limit = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_full_pricerange_start', $arrayParameters);
	     
	    // Service implementation start
	    $prodTable = $this->getServiceLocator()->get('MelisEcomProductTable');
	    $coreToolSvc = $this->getServiceLocator()->get('MelisCoreTool');
	    $product = new MelisProduct();
	    $productData = $prodTable->getProductByNameTextTypeAttrIdsAndPrice($arrayParameters['search'], $arrayParameters['fieldsTypeCodes'], 
	        $arrayParameters['attributeValuesIds'], $arrayParameters['categoryId'], (float) $arrayParameters['priceMin'], (float) $arrayParameters['priceMax'],  $arrayParameters['langId'],
	        $arrayParameters['countryId'], (int) $arrayParameters['onlyValid'], $arrayParameters['start'], $arrayParameters['limit']
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
                 
                $categoryData[] = $prodTable->getProductCategoryByProductIdAndCategoryId($searchedData['prd_id'], $arrayParameters['categoryId'], $arrayParameters['langId'])->toArray();
                if(isset($categoryData[$ctr])) {
                    $tmpCategory = array();
                    $catCtr = 0;
                    foreach($categoryData[$ctr] as $catData) {
                        $tmpCategory[$ctr][$catCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $catData);
                        $tmpCategory[$ctr][$catCtr] = $coreToolSvc->removeDataWithPrefix('pcat_', $tmpCategory[$ctr][$catCtr]);
                        $catCtr++;
                    }
                    if(isset($tmpCategory[$ctr])) {
                        $categoryData[$ctr] = $tmpCategory[$ctr];
                        $product->setCategories($categoryData[$ctr]);
                    }
                }
        
                $docData[] =  $prodTable->getProductDocumentByProductIdAndCountryId($searchedData['prd_id'], $arrayParameters['countryId'])->toArray();
                if(isset($docData[$ctr])) {
                    $tmpDoc = array();
                    $docCtr = 0;
                    foreach($docData[$ctr] as $docData) {
                        $tmpDoc[$ctr][$docCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $docData);
                        $tmpDoc[$ctr][$docCtr] = $coreToolSvc->removeDataWithPrefix('rdoc_', $tmpDoc[$ctr][$docCtr]);
                        $docCtr++;
                    }
                    if(isset($tmpDoc[$ctr])) {
                        $docData[$ctr] = $tmpDoc[$ctr];
                        $product->setDocuments($docData[$ctr]);
                    }
                }
                 
                 
                $prodTexts[] = $prodTable->getProductText($searchedData['prd_id'], $arrayParameters['langId'])->toArray();
                if(isset($prodTexts[$ctr])) {
                    $tmpTexts = array();
                    $txtCtr = 0;
                    foreach($prodTexts[$ctr] as $txtData) {
                        $tmpTexts[$ctr][$txtCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $txtData);
                        $txtCtr++;
                    }
                    if(isset($tmpTexts[$ctr])) {
                        $prodTexts[$ctr] = $tmpTexts[$ctr];
                        $product->setTexts($prodTexts[$ctr]);
                    }
                }
                 
                 
                $prodPrice[] =  $prodTable->getProductPrice($searchedData['prd_id'], $arrayParameters['countryId'])->toArray();
                if(isset($prodPrice[$ctr])) {
                    $tmpPrice = array();
                    $prcCtr = 0;
                    foreach($prodPrice[$ctr] as $prcData) {
                        $tmpPrice[$ctr][$prcCtr] = $coreToolSvc->removeDataWithPrefix('prd_', $prcData);
                        $prcCtr++;
                    }
                    if(isset($tmpPrice[$ctr])) {
                        $prodPrice[$ctr] = $tmpPrice[$ctr];
                        $product->setPrice($prodPrice[$ctr]);
                    }
                }
        
                $tmpProductData = $coreToolSvc->splitData('prd_', $productData[$ctr]);
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
	    $arrayParameters = $this->sendEvent('meliscommerce_service_productsearch_full_pricerange_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
}