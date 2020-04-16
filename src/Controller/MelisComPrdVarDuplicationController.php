<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Session\Container;
use MelisCore\Controller\AbstractActionController;

/**
 * Products and Variants Duplication Controller
 */
class MelisComPrdVarDuplicationController extends AbstractActionController
{
    /**
     * Render Duplicate button on Product list
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderDuplicateProductButtonAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    /**
     * Render Duplicate button on Variant list
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderDuplicateVariantButtonAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    /**
     * Modal container in duplicating Product/Variant
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderDuplicateModalAction()
    {
        $id = $this->params()->fromQuery('id');
        $melisKey = $this->params()->fromQuery('melisKey');
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->id = $id;
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Modal content in duplicating variant
     * This content also render the form for variant SKU
     * 
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantDuplicationFormAction()
    {
        $variantSku = null;
        $melisKey = $this->params()->fromQuery('melisKey');
        $variantId = $this->params()->fromQuery('variantId');
        
        // Retreiving the form from config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_duplications/meliscommerce_duplications_sku_form','meliscommerce_duplications_sku_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
        // Pre set Value of the Variant Id if variantId has value
        if($variantId)
        {
            // Applying Pre value to Variant Form
            $data = array(
                'var_id' => $variantId
            );
            $propertyForm->setData($data);
            
            // Retrieving the Variant SKU for Labeling the Input form for Variant SKU
            $varTbl = $this->getServiceManager()->get('MelisEcomVariantTable');
            $variant = $varTbl->getEntryById($variantId)->current();
            $variantSku = $variant->var_sku;
        }
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $variantId;
        $view->variantSku = $variantSku;
        $view->setVariable('meliscommerce_duplications_sku_form', $propertyForm);
        return $view;
    }
    
    public function renderProductDuplicationFormAction()
    {
        $melisKey = $this->params()->fromQuery('melisKey');
        $productId = $this->params()->fromQuery('productId');
        
        // Retreiving the form from config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_duplications/meliscommerce_duplications_sku_form','meliscommerce_duplications_sku_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
        $prdVariants = array();
        if ($productId)
        {
            // Retriving the Product variants
            $varTbl = $this->getServiceManager()->get('MelisEcomVariantTable');
            $prdVariants = $varTbl->getEntryByField('var_prd_id', $productId);
        }
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        $view->prdVariants = $prdVariants;
        $view->setVariable('meliscommerce_duplications_sku_form', $propertyForm);
        return $view;
    }
    
    /**
     * This method will duplicate a selected variant
     * This method also use when duplicating the product if 
     * the selected product has variant(s)
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function duplicateVariantAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $varId = null;
        $success = 0;
        $textTitle = 'tr_meliscommerce_duplication_Duplicate_variant';
        $textMessage = '';
        $errors = array();
        $datas = array();
        $request = $this->getRequest();
        
        if ($request->isPost())
        {
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);
            
            // Getting the variant Id from postvalue array
            if (!empty($postValues['variantSku']))
            {
                if (is_array($postValues['variantSku']))
                {
                    foreach ($postValues['variantSku'] As $key => $val)
                    {
                        // Once varaint array get the first value, this will break the loop to exit
                        $varId = $key;
                        break;
                    }
                }
            }
            
            // Client Saving Listener
            $this->getEventManager()->trigger('meliscommerce_duplicate_variant_start', $this, $request);
            
            // Get the MelisCommerce Module session as Data Container after Data Validation from Listener
            $container = new Container('meliscommerce');
            
            if (!empty($container['action-duplicate-tmp']))
            {
                if (!empty($container['action-duplicate-tmp']['errors']))
                {
                    $errors = $container['action-duplicate-tmp']['errors'];
                }
                
                if (!empty($container['action-duplicate-tmp']['datas']))
                {
                    $datas = $container['action-duplicate-tmp']['datas'];
                }
            }
            
            // Unset Temporary Data on Session
            unset($container['action-duplicate-tmp']);
            
            if (empty($errors))
            {
                if (!empty($datas['var_data']))
                {
                    // Duplicating the Variant details using Duplication Service
                    $variantData = $datas['var_data'];
                    $dupSrv = $this->getServiceManager()->get('MelisComDuplicationService');
                    // DuplicateVariant function will return the new added variant id if success, else this will return null
                    $varId = $dupSrv->duplicateVariant($variantData, $postValues['var_status'], $postValues['duplicate_images'], $postValues['duplicate_documents']);
                    
                    if (!is_null($varId))
                    {
                        $textMessage = 'tr_meliscommerce_duplication_variant_success';
                        $success  = 1;
                    }
                    else 
                    {
                        $textMessage = 'tr_meliscore_error_message';
                    }
                }
                else 
                {
                    $textMessage = 'tr_meliscore_error_message';
                }
            }
            else 
            {
                $textMessage = 'tr_meliscommerce_duplication_variant_unable';
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );
        
        $this->getEventManager()->trigger('meliscommerce_duplicate_variant_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_VARIANT_DUPLICATE', 'itemId' => $varId)));
        
        return new JsonModel($response);
    }
    
    /**
     * This method will duplicate the selected product
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function duplicateProductAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        
        $prdId = null;
        $success = 0;
        $textTitle = 'tr_meliscommerce_duplication_Duplicate_product';
        $textMessage = '';
        $errors = array();
        $datas = array();
        $request = $this->getRequest();
        
        if ($request->isPost())
        {
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);
            
            $prdId = $postValues['product_id'];
            
            // Client Saving Listener
            $this->getEventManager()->trigger('meliscommerce_duplicate_product_start', $this, $request);
            
            // Get the MelisCommerce Module session as Data Container after Data Validation from Listener
            $container = new Container('meliscommerce');
            
            if (!empty($container['action-duplicate-tmp']))
            {
                if (!empty($container['action-duplicate-tmp']['errors']))
                {
                    $errors = $container['action-duplicate-tmp']['errors'];
                }
                
                if (!empty($container['action-duplicate-tmp']['datas']))
                {
                    $datas = $container['action-duplicate-tmp']['datas'];
                }
            }
            
            // Unset Temporary Data on Session
            unset($container['action-duplicate-tmp']);
            
            if (empty($errors))
            {
                // Duplicating the Product details using Duplication Service
                $dupSrv = $this->getServiceManager()->get('MelisComDuplicationService');
                // DuplicateProduct function will return the new added product id if success, else this will return null
                $prdId = $dupSrv->duplicateProduct($postValues['product_id'], $postValues['prd_status'], $postValues['duplicate_images'], $postValues['duplicate_documents']);
                
                if (!is_null($prdId))
                {
                    if (!empty($datas['var_data']))
                    {
                        // Duplicating the Variant details using Duplication Service
                        $variantData = $datas['var_data'];
                        // DuplicateVariant function will return the new added variant id if success, else this will return null
                        $varId = $dupSrv->duplicateVariant($variantData, null , $postValues['duplicate_images'], $postValues['duplicate_documents'], $prdId);
                        
                        if (!is_null($varId))
                        {
                            $textMessage = 'tr_meliscommerce_duplication_product_success';
                            $success  = 1;
                        }
                        else 
                        {
                            $textMessage = 'tr_meliscore_error_message';
                        }
                    }
                    else 
                    {
                        $textMessage = 'tr_meliscommerce_duplication_product_success';
                        $success  = 1;
                    }
                }
                else 
                {
                    $textMessage = 'tr_meliscore_error_message';
                }
            }
            else 
            {
                $textMessage = 'tr_meliscommerce_duplication_product_unable';
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );
        
        $this->getEventManager()->trigger('meliscommerce_duplicate_variant_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_PRODUCT_DUPLICATE', 'itemId' => $prdId)));
        
        return new JsonModel($response);
    }
    
    /**
     * This method will validate variant data that submitted
     * This will also return the validated data, or errors from validation
     * 
     * @return \Laminas\View\Model\JsonModel
     */
    public function validateVariantDataAction()
    {
        $request = $this->getRequest();
        $data = get_object_vars($this->getRequest()->getPost());
        $data = $this->getTool()->sanitizeRecursive($data);
        // Variant validated using Duliplication Service
        $dupSrv = $this->getServiceManager()->get('MelisComDuplicationService');
        $result = $dupSrv->validateVariantData($data);
        
        return new JsonModel($result);
    }

    private function getTool()
    {
        $tool = $this->getServiceManager()->get('MelisCoreTool');
        return $tool;
    }

    public function searchVariantDataAction()
    {
        $request = $this->getRequest();
        $data = get_object_vars($this->getRequest()->getPost());
        $data = $this->getTool()->sanitizeRecursive($data);
        // Variant validated using Duliplication Service
        $dupSrv = $this->getServiceManager()->get('MelisComDuplicationService');


        return new JsonModel($result);
    }
}