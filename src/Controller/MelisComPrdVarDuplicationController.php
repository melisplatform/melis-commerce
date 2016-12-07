<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * Products and Variants Duplication Controller
 */
class MelisComPrdVarDuplicationController extends AbstractActionController
{
    /**
     * Render Duplicate button on Product list
     * @return \Zend\View\Model\ViewModel
     */
    public function renderDuplicateProductButtonAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    /**
     * Render Duplicate button on Variant list
     * @return \Zend\View\Model\ViewModel
     */
    public function renderDuplicateVariantButtonAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    /**
     * Modal container in duplicating Product/Variant
     * @return \Zend\View\Model\ViewModel
     */
    public function renderDuplicateModalAction()
    {
        $id = $this->params()->fromQuery('id');
        $melisKey = $this->params()->fromQuery('melisKey');
        
        $view = new ViewModel();
        $view->setTerminal(false);
        $view->id = $id;
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Modal content in duplicating variant
     * This content also render the form for variant SKU
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderVariantDuplicationFormAction()
    {
        $variantSku = null;
        $melisKey = $this->params()->fromQuery('melisKey');
        $variantId = $this->params()->fromQuery('variantId');
        
        // Retreiving the form from config
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_duplications/meliscommerce_duplications_sku_form','meliscommerce_duplications_sku_form');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
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
            $varTbl = $this->getServiceLocator()->get('MelisEcomVariantTable');
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
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_duplications/meliscommerce_duplications_sku_form','meliscommerce_duplications_sku_form');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
        $prdVariants = array();
        if ($productId)
        {
            // Retriving the Product variants
            $varTbl = $this->getServiceLocator()->get('MelisEcomVariantTable');
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
     * @return \Zend\View\Model\JsonModel
     */
    public function duplicateVariantAction()
    {
        $translator = $this->serviceLocator->get('translator');
        
        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_duplication_Duplicate_variant');
        $textMessage = '';
        $errors = array();
        $datas = array();
        $request = $this->getRequest();
        
        if ($request->isPost())
        {
            $postValues = get_object_vars($request->getPost());
            
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
                    $dupSrv = $this->getServiceLocator()->get('MelisComDuplicationService');
                    // DuplicateVariant function will return the new added variant id if success, else this will return null
                    $varId = $dupSrv->duplicateVariant($variantData, $postValues['var_status'], $postValues['duplicate_images'], $postValues['duplicate_documents']);
                    
                    if (!is_null($varId))
                    {
                        $textMessage = $translator->translate('tr_meliscommerce_duplication_variant_success');
                        $success  = 1;
                    }
                    else 
                    {
                        $textMessage = $translator->translate('tr_meliscore_error_message');
                    }
                }
                else 
                {
                    $textMessage = $translator->translate('tr_meliscore_error_message');
                }
            }
            else 
            {
                $textMessage = $translator->translate('tr_meliscommerce_duplication_variant_unable');
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );
        
        if ($success){
            $this->getEventManager()->trigger('meliscommerce_duplicate_variant_end', $this, $response);
        }
        
        return new JsonModel($response);
    }
    
    /**
     * This method will duplicate the selected product
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function duplicateProductAction()
    {
        $translator = $this->serviceLocator->get('translator');
        
        $success = 0;
        $textTitle = $translator->translate('tr_meliscommerce_duplication_Duplicate_product');
        $textMessage = '';
        $errors = array();
        $datas = array();
        $request = $this->getRequest();
        
        if ($request->isPost())
        {
            $postValues = get_object_vars($request->getPost());
            
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
                $dupSrv = $this->getServiceLocator()->get('MelisComDuplicationService');
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
                            $textMessage = $translator->translate('tr_meliscommerce_duplication_product_success');
                            $success  = 1;
                        }
                        else 
                        {
                            $textMessage = $translator->translate('tr_meliscore_error_message');
                        }
                    }
                    else 
                    {
                        $textMessage = $translator->translate('tr_meliscommerce_duplication_product_success');
                        $success  = 1;
                    }
                }
                else 
                {
                    $textMessage = $translator->translate('tr_meliscore_error_message');
                }
            }
            else 
            {
                $textMessage = $translator->translate('tr_meliscommerce_duplication_product_unable');
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );
        
        if ($success){
            $this->getEventManager()->trigger('meliscommerce_duplicate_variant_end', $this, $response);
        }
        
        return new JsonModel($response);
    }
    
    /**
     * This method will validate variant data that submitted
     * This will also return the validated data, or errors from validation
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function validateVariantDataAction()
    {
        $request = $this->getRequest();
        $data = get_object_vars($request->getPost());
        // Variant validated using Duliplication Service
        $dupSrv = $this->getServiceLocator()->get('MelisComDuplicationService');
        $result = $dupSrv->validateVariantData($data);
        
        return new JsonModel($result);
    }
}