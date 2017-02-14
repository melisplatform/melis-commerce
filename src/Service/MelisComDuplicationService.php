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
use phpDocumentor\Reflection\Types\Boolean;
/**
 *
 * This service handles the Products and variant duplication service for MelisCommerce.
 *
 */
class MelisComDuplicationService extends MelisCoreGeneralService
{
    /**
     * This method will validate the Variant Data from Form
     * 
     * @return Array
     */
    public function validateVariantData($data)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_duplicate_validate_variant_start', $arrayParameters);
        
        // Service implementation start
        $translator = $this->serviceLocator->get('translator');
        
        $variantSku = (!empty($arrayParameters['data']['variantSku'])) ? $arrayParameters['data']['variantSku'] : array();
        
        $success = 0;
        $varData = array();
        $errors = array();
        
        if (!empty($variantSku))
        {
            // Retreiving the form from config
            $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_duplications/meliscommerce_duplications_sku_form','meliscommerce_duplications_sku_form');
            $factory = new \Zend\Form\Factory();
            $formElements = $this->serviceLocator->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $propertyForm = $factory->createForm($appConfigForm);
            
            // Main Variant flag checker,
            // this flag will affect only on multiple variants
            $checkMainVariant = false;
            if (count($variantSku) > 1)
            {
                $checkMainVariant = true;
            }
            
            foreach ($variantSku As $key => $val)
            {
                $propertyForm->setData($val);
                // Retrieving variant details from Variant Service
                $variantTable = $this->getServiceLocator()->get('MelisEcomVariantTable');
                $variant = $variantTable->getEntryById($key)->current();
                
                if ($propertyForm->isValid())
                {
                    // Checking if the Variant SKU inputed is unique or doesn't exist on the database
                    $variantData = $variantTable->getEntryByField('var_sku', $val['var_sku'])->current();
                    if (empty($variantData))
                    {
                        
                        /**
                         *  Selecting the main variant
                         *  This process will occure only when the action is duplicating product
                         *  that related to the variants
                         */
                        $mainVariant = 0;
                        if (!empty($arrayParameters['data']['main_variant_id']))
                        {
                            if ($arrayParameters['data']['main_variant_id'] == $key)
                            {
                                $mainVariant = 1;
                            }
                        }
                        
                        // Preparing the validated variant data
                        array_push($varData, array(
                            'var_id' => $key,
                            'var_sku' => $val['var_sku'],
                            'var_main_variant' => $mainVariant,
                        ));
                    }
                    else 
                    {
                        $errors[$key.'_var_sku'] = array(
                            'label' => $translator->translate('tr_meliscommerce_duplication_Var_sku').' ('.$variant->var_sku.')',
                            'skuExist' => $translator->translate('tr_meliscommerce_duplication_variant_sku_exist'),
                            'form' => array($key.'_var_sku')
                        );
                    }
                }
                else
                {
                    // Getting Form errors if errors is occured
                    $errors_temp = $propertyForm->getMessages();
                    // Preparing the error message for multi form
                    foreach ($errors_temp as $keyError => $valueError)
                    {
                        $errors[$key.'_'.$keyError] = $valueError;
                        $errors[$key.'_'.$keyError]['form'][] = $key.'_var_sku';
                        
                        foreach ($appConfigForm['elements'] As $eKey => $eval)
                        {
                            if ($eval['spec']['name'] == $keyError)
                            {
                                $errors[$key.'_'.$keyError]['label'] = $eval['spec']['options']['label'].' ('.$variant->var_sku.')';
                            }
                        }
                    }
                }
            }
        }
        else
        {
            // Checking if the action is Duplicating Variant
            if (!empty($arrayParameters['data']['duplication_type']))
            {
                if ($arrayParameters['data']['duplication_type'] == 'variant')
                {
                    $errors['noVariants'] = array(
                        'label' => $translator->translate('tr_meliscommerce_duplication_common_variant'),
                        'noVariant' => $translator->translate('tr_meliscommerce_duplication_no_variant')
                    );
                }
            }
        }
        
        if (empty($errors))
        {
            $success = 1;
        }
        
        $result = array(
            'success' => $success,
            'errors' => array('var_errors' => $errors),
            'datas' =>  array('var_data' => $varData),
        );
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $result;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_duplicate_validate_variant_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method will duplicate a selected Product
     * 
     * @param int $productId product id of the selected product
     * @param int $status the status of the new product
     * @param boolean $duplicateImages if true this will duplicate the product iamge(s)
     * @param boolean $duplicateFiles if true this will duplicate the product file(s)
     * @return Int|null
     */
    public function duplicateProduct($productId, $status = 0, $duplicateImages = false, $duplicateFiles = false)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $result = null;
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_duplicate_product_start', $arrayParameters);
    
        // Service implementation start
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $prdSrv = $this->getServiceLocator()->get('MelisComProductService');
        // Retrieving Product details from Product service
        $productEntity = $prdSrv->getProductById($arrayParameters['productId']);
        
        // Creating new Product Data entry
        $product = $productEntity->getProduct();
        $productData = array(
            'prd_reference' => $product->prd_reference,
            'prd_status' => $arrayParameters['status'],
            'prd_date_creation' => date('Y-m-d H:i:s'),
            'prd_user_id_creation' => $melisTool->getCurrentUserId()
        );
        
        // Creating new Product Texts Data entry
        $productTextsData = array();
        $productTexts = $productEntity->getTexts();
        foreach ($productTexts As $tKey => $tVal)
        {
            array_push($productTextsData, array(
                'ptxt_lang_id' => $tVal->ptxt_lang_id,
                'ptxt_type' => $tVal->ptxt_type,
                'ptxt_field_short' => $tVal->ptxt_field_short,
                'ptxt_field_long' => $tVal->ptxt_field_long,
            ));
        }
        
        // Creating new Product Attributes Data entry
        $productAttributesData = array();
        $productAttributes = $productEntity->getAttributes();
        foreach ($productAttributes As $aKey => $aVal)
        {
            array_push($productAttributesData, array(
                'patt_attribute_id' => $aVal->patt_attribute_id
            ));
        }
        
        // Creating new Product Categories Data entry
        $productCategoriesData = array();
        $productCategories = $productEntity->getCategories();
        foreach ($productCategories As $cKey => $cVal)
        {
            array_push($productCategoriesData, array(
                'pcat_cat_id' => $cVal->pcat_cat_id,
                'pcat_order' => $cVal->pcat_order
            ));
        }
        
        // Creating new Product Prices Data entry
        $productPriceData = array();
        $productPrices = $productEntity->getPrice();
        foreach ($productPrices As $pKey => $pVal)
        {
            array_push($productPriceData, array(
                'price_country_id' => $pVal->price_country_id,
                'price_currency' => $pVal->price_currency,
                'price_net' => $pVal->price_net,
                'price_gross' => $pVal->price_gross,
                'price_vat_percent' => $pVal->price_vat_percent,
                'price_vat_price' => $pVal->price_vat_price,
                'price_other_tax_price' => $pVal->price_other_tax_price,
            ));
        }
        // Saving new Product entry using Product Service
        // Saving Product data as new entry, if saving is success this will return the Prouct Id
        $result = $prdSrv->saveProduct($productData, $productTextsData, $productAttributesData, $productCategoriesData, $productPriceData);
        
        if (!is_null($result))
        {
            // Checking or Duplication of Images and Files
            if ($arrayParameters['duplicateImages'] || $arrayParameters['duplicateFiles'])
            {
                $this->duplicateDocuments('product', $arrayParameters['productId'], $result, $arrayParameters['duplicateImages'], $arrayParameters['duplicateFiles']);
            }
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $result;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_duplicate_product_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method will duplicate the target Variant
     * 
     * @param array $variant validated variant data from varaint validation
     * @param int $status status of the new variant
     * @param boolean $duplicateImages if true this will duplicate the product iamge(s)
     * @param boolean $duplicateFiles if true this will duplicate the product file(s)
     * @param int $productId if specified this will use as a new product Id of the new variant 
     * @return Int|null
     */
    public function duplicateVariant($variant, $status = 0, $duplicateImages = false, $duplicateFiles = false, $productId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $result = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_duplicate_variant_start', $arrayParameters);
        
        // Service implementation start
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $varSrv = $this->getServiceLocator()->get('MelisComVariantService');
        
        foreach ($arrayParameters['variant'] As $key => $val)
        {
            // Retrieving Variant details from Variant Service
            $variantEntity = $varSrv->getVariantById($val['var_id']);
            $variant = $variantEntity->getVariant();
            
            // Creating new Variant Data entry
            $variantData = array(
                'var_sku' => $val['var_sku'],
                'var_prd_id' => (!is_null($arrayParameters['productId'])) ? $arrayParameters['productId'] : $variant->var_prd_id,
                'var_main_variant' => $val['var_main_variant'],
                'var_status' => (!is_null($arrayParameters['status'])) ? $arrayParameters['status'] : $variant->var_status,
                'var_date_creation' => date('Y-m-d H:i:s'),
                'var_user_id_creation' => $melisTool->getCurrentUserId()
            );
            
            // Creating new Variant Prices Data entry
            $variantPricesData = array();
            $variantPrices = $variantEntity->getPrices();
            foreach ($variantPrices As $pkey => $pVal)
            {
                array_push($variantPricesData, array(
                    'price_country_id' => $pVal->price_country_id,
                    'price_currency' => $pVal->price_currency,
                    'price_net' => $pVal->price_net,
                    'price_gross' => $pVal->price_gross,
                    'price_vat_percent' => $pVal->price_vat_percent,
                    'price_vat_price' => $pVal->price_vat_price,
                    'price_other_tax_price' => $pVal->price_other_tax_price,
                ));
            }
            
            // Creating new Variant Stocks Data entry
            $variantStockData = array();
            $varianStock = $variantEntity->getStocks();
            foreach ($varianStock As $skey => $sVal)
            {
                array_push($variantStockData, array(
                    'stock_country_id' => $sVal->stock_country_id,
                    'stock_quantity' => $sVal->stock_quantity,
                    'stock_next_fill_up' => $sVal->stock_next_fill_up,
                ));
            }
            
            // Creating new Variant Attributes Data entry
            $variantArrtibuteData = array();
            $variantArrtibute = $variantEntity->getAttributeValues();
            foreach ($variantArrtibute As $akey => $aVal)
            {
                array_push($variantArrtibuteData, array(
                    'vatv_attribute_value_id' => $aVal->atval_id
                ));
            }
            
            // Saving new Varaint entry using Variant Service
            // Saving Variant data as new entry, if saving is success this will return the Variant Id
            $result = $varSrv->saveVariant($variantData, $variantPricesData, $variantStockData, $variantArrtibuteData);
            
            if (!is_null($result))
            {
                // Checking or Duplication of Images and Files
                if ($arrayParameters['duplicateImages'] || $arrayParameters['duplicateFiles'])
                {
                    $this->duplicateDocuments('variant', $variant->var_id, $result, $arrayParameters['duplicateImages'], $arrayParameters['duplicateFiles']);
                }
            }
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $result;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_duplicate_variant_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method will duplicate the Documents of the Product/Variant
     * 
     * @param string $docRelation product/variant
     * @param int $docRelationId the id of the ProductId/VariantId
     * @param int $newDocRelationId the id of the new add Product/Variant
     * @param boolean $duplicateImages if true this will duplicate the product iamge(s)
     * @param boolean $duplicateFiles if true this will duplicate the product file(s)
     * @return Boolean
     */
    public function duplicateDocuments($docRelation, $docRelationId, $newDocRelationId , $duplicateImages = false, $duplicateFiles = false)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $result = false;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_duplicate_documents_start', $arrayParameters);
        
        // Service implementation start
        if ($arrayParameters['duplicateImages'] || $arrayParameters['duplicateFiles'])
        {
            $docTable    = $this->getServiceLocator()->get('MelisEcomDocumentTable');
            $docRelTable = $this->getServiceLocator()->get('MelisEcomDocRelationsTable');
            
            // Retrieving all type of documents
            $documents = $docTable->getDocumentsByParentTypeId($arrayParameters['docRelation'], $arrayParameters['docRelationId']);
            
            foreach ($documents As $key => $val)
            {
                $duplicateFlag = false;
                
                // Checking if the Document Path is existing
                $docPath = 'public'.$val->doc_path;
                if (file_exists($docPath))
                {
                    /**
                     * Checking if the File is Image type
                     * getimagesize php function that will return
                     * Image details like size, width, heigth, name etc...
                     * if the return is empty, this means the file path in not Image type
                     */
                    if (getimagesize($docPath))
                    {
                        if ($arrayParameters['duplicateImages'])
                        {
                            $duplicateFlag = true;
                        }
                    }
                    else 
                    {
                        if ($arrayParameters['duplicateFiles'])
                        {
                            $duplicateFlag = true;
                        }
                    }
                }
                
                if ($duplicateFlag)
                {
                    // Checking if the file path is existing
                    if (file_exists ($docPath))
                    {
                        /**
                         * Checking if the Directory of the file is existing
                         * else this will try to create new Directory with the permission of 0777
                         */
                        $newDir = 'public/media/commerce/'.$arrayParameters['docRelation'].'/'.$arrayParameters['newDocRelationId'].'/';
                        if(!is_dir($newDir))
                        {
                            mkdir($newDir, 0777, true);
                        }
                        
                        // Just to be sure that the target Directory is exsting, else do nothing
                        if (is_dir($newDir))
                        {
                            // Replacing the Id of the directory folder name to new Created id
                            $newDocPath = str_replace($arrayParameters['docRelationId'], $arrayParameters['newDocRelationId'], $val->doc_path);
                            
                            /**
                             * copy is a PHP function that will copy a file path 
                             * into the traget directory, this will return True if the action is success
                             */
                            if (copy($docPath, 'public'.$newDocPath))
                            {
                                /**
                                 * If the copy of images/files is success
                                 * this will create a new entry for document of new added product
                                 */
                                $doc = array(
                                    'doc_name' => $val->doc_name,
                                    'doc_path' => $newDocPath,
                                    'doc_type_id' => $val->doc_type_id,
                                    'doc_subtype_id' => $val->doc_subtype_id
                                );
                                $docId = $docTable->save($doc);
                                
                                $relDoc = array(
                                    'rdoc_'.$arrayParameters['docRelation'].'_id' => $arrayParameters['newDocRelationId'],
                                    'rdoc_doc_id' => $docId,
                                    'rdoc_country_id' => $val->rdoc_country_id
                                );
                                $docRelTable->save($relDoc);
                                
                                $result = true;
                            }
                        }
                    }
                }
            }
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $result;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_duplicate_documents_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
}