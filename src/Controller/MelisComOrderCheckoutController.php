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
use Composer\Json\JsonManipulator;

class MelisComOrderCheckoutController extends AbstractActionController
{
    const PLUGIN_INDEX = 'meliscommerce';
    const TOOL_KEY = '';
    const SITE_ID = -1;
    
    /**
     * Render Order Checkout page
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutPageAction(){
        $container = new Container('meliscommerce');
        if (!isset($container['checkout']))
        {
            $container['checkout'] = array();
        }
        // Default BO Checkout Site ID
        $container['checkout'][self::SITE_ID] = array();
        $container['checkout'][self::SITE_ID]['countryId'] = null;
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout page header
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout page content
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutContentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout First Step, Choosing Products
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutChooseProductAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Choose Product header
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutChooseProductHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Choose Product content
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutChooseProductContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Order checkout setup the Country Id to Checkout session
     * @return \Zend\View\Model\JsonModel
     */
    public function orderCheckoutSetCountryAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        
        $request = $this->getRequest();
        
        $success = 0;
        $errors  = array();
        $textTitle = $translator->translate('tr_meliscommerce_order_checkout_select_country');
        $textMessage = $translator->translate('tr_meliscore_error_message');
        $cat_id = 0;
        $catParents = '';
        
        if($request->isPost()) {
            
            $data = get_object_vars($request->getPost());
            $countryId = $data['countryId'];
            $container = new Container('meliscommerce');
            // check if the clientKey is set no checkout session
            if (!empty($container['checkout'][self::SITE_ID]['clientKey']))
            {
                // cleaning the Client basket after selecting country
                $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
                $melisComBasketService->emptyAnonymousBasket($container['checkout'][self::SITE_ID]['clientKey']);
            }
            // Set Country id to checkout countryId
            // CountryId will be the base data for collecting details from the Products and variants
            // In this case Country Id is representing the country where the Front Site is accessible to a particular country
            $container['checkout'][self::SITE_ID]['countryId'] = $countryId;
                
            $success = 1;
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );
         
        return new JsonModel($response);
    }
    
    /**
     * Render Order Checkout Prodduct list
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutProductListAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_order_checkout_product_list');
        $columns = $melisTool->getColumns();
        $columns['actions'] = array('text' => $translator->translate('tr_meliscommerce_order_checkout_product_variant'));
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration();
        return $view;
    }
    
    /**
     * Render Order Checkout Product basket
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutProductBasketAction()
    {
        
        $container = new Container('meliscommerce');
        $clientKey = (!empty($container['checkout'][self::SITE_ID]['clientKey'])) ? $container['checkout'][self::SITE_ID]['clientKey'] : null;
        
        $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
        
        $action = $this->params()->fromQuery('action');
        $variantId = $this->params()->fromQuery('variantId');
        $variantQty = (int) $this->params()->fromQuery('variantQty');
        
        if (!empty($variantId))
        {
            if ($action == 'add')
            {
                /**
                 * This method will add the variant to basket if the variant is not yet exist to the client basket
                 * If the Variant exist to the basket this will Add the quantity of the variant
                 */
                $melisComBasketService->addVariantToBasket($variantId, $variantQty, null, $clientKey);
            }
            elseif ($action == 'deduct') 
            {
                /**
                 * This method will deduct the quantity of the variant,
                 * and if the variant quantity is Zero this will remove the variant from clients basket
                 */
                $melisComBasketService->removeVariantFromBasket($variantId, $variantQty, null, $clientKey);
            }
        }
        
        // Getting the client basket list using Client key
        $basketData = $melisComBasketService->getBasket(null, $clientKey); 
         
        // Getting Current Langauge ID
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $langId = $melisTool->getCurrentLocaleID();
        
        $container = new Container('meliscommerce');
        $countryId = $container['checkout'][self::SITE_ID]['countryId'];
        
        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
        
        $basket = array();
        $total = 0;
        if (!is_null($basketData)){
            foreach ($basketData As $val)
            {
                $variantId = $val->getVariantId();
                $variant = $val->getVariant();
                $quantity = $val->getQuantity();
                $productId = $variant->getVariant()->var_prd_id;
                $varSku = $variant->getVariant()->var_sku;
                
                // Getting the Final Price of the variant
                $varPrice = $melisComVariantService->getVariantFinalPrice($variantId, $countryId);
                
                if (is_null($varPrice))
                {
                    // If the variant price not set on variant page this will try to get from the Product Price
                    $varPrice = $melisComProductService->getProductFinalPrice($productId, $countryId);
                }
                
                // Compute variant total amount
                $variantTotal = $quantity * $varPrice->price_net;
                $data = array(
                    'var_id' => $variantId,
                    'var_sku' => $varSku,
                    'var_quantity' => $quantity,
                    'var_price' => $varPrice->cur_symbol.' '.number_format($varPrice->price_net, 2),
                    'product_name' => $melisComProductService->getProductName($productId, $langId),
                    'var_total' => $varPrice->cur_symbol.' '.number_format($variantTotal, 2)
                );
                
                $total += $variantTotal;
                array_push($basket, $data);
            }
        }
        
        $melisEcomCountryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
        $countryCurrency = $melisEcomCountryTable->getCountryCurrency($countryId)->current();
        
        $countryCurrencySympbol = '';
        if (!empty($countryCurrency)){
            $countryCurrencySympbol = $countryCurrency->cur_symbol;
        }
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->basket = $basket;
        $view->total = $total;
        $view->countryCurrencySympbol = $countryCurrencySympbol;
        return $view;
    }
    
    /**
     * This method will return the list of product for Product list
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function getProductListAction()
    {
        $draw = 0;
        $dataCount = 0;
        $tableData = array();
        $productFilter = array();
        $productList = array();
        
        if($this->getRequest()->isPost()) {
            
            $container = new Container('meliscommerce');
            if (!empty($container['checkout'][self::SITE_ID]['countryId']))
            {
                // Getting Current Langauge ID
                $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
                $langId = $melisTool->getCurrentLocaleID();
                
                $productTable = $this->getServiceLocator()->get('MelisEcomProductTable');
                
                $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
                $docSvc = $this->getServiceLocator()->get('MelisComDocumentService');
                
                $sortOrder = $this->getRequest()->getPost('order');
                $sortOrder = $sortOrder[0]['dir'];
                
                $sortOrder = $this->getRequest()->getPost('order');
                $sortOrder = $sortOrder[0]['dir'];
                
                $draw = (int) $this->getRequest()->getPost('draw');
                
                $start = (int) $this->getRequest()->getPost('start');
                $length =  (int) $this->getRequest()->getPost('length');
                
                $search = $this->getRequest()->getPost('search');
                $search = $search['value'];
                
                $productList = $productTable->getProductList(null, null, true,  $start, $length, $sortOrder, $search);
                
                $productFilter = $productTable->getProductList(null, null, true,  null, null, $sortOrder, $search)->toArray();
                
                $prodImage = '<img src="%s" width="60" height="60" class="img-rounded img-responsive"/>';
                
                foreach($productList as $val) {
                    $productId = $val->prd_id;
                
                    $rowData = array(
                        'DT_RowId' => $productId,
                        'prd_id' => $productId,
                        'prd_image' => sprintf($prodImage, $docSvc->getDocDefaultImageFilePath('product', $productId)),
                        'prd_name' => $prodSvc->getProductName($productId, $langId),
                    );
                    array_push($tableData, $rowData);
                }
            }
        }
        
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => count($productList),
            'recordsFiltered' =>  count($productFilter),
            'data' => $tableData,
        ));
    }
    
    /**
     * This will return the Product variant list
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutProductVariantListAction()
    {
        $productId = $this->params()->fromQuery('productId');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        
        $hasVariant = false;
        $variants = array();
        $container = new Container('meliscommerce');
        if (!empty($container['checkout'][self::SITE_ID]['countryId']))
        {
            $countryId = $container['checkout'][self::SITE_ID]['countryId'];
            
            $translator = $this->getServiceLocator()->get('translator');
            
            // Getting Current Langauge ID
            $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
            $langId = $melisTool->getCurrentLocaleID();
            
            
            $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
            $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
            // Getting the list of Activated Variant from Variant Service using the ProductId
            $variantData = $melisComVariantService->getVariantListByProductId($productId, $langId, $countryId, true);
            
            foreach ($variantData As $val)
            {
                $hasVariant = true;
                
                $variantId = $val->getId();
                $variant = $val->getVariant();
                
                // Getting the Final Price of the variant
                $varPrice = $melisComVariantService->getVariantFinalPrice($variantId, $countryId);
                
                if (is_null($varPrice))
                {
                    // If the variant price not set on variant page this will try to get from the Product Price
                    $varPrice = $melisComProductService->getProductFinalPrice($variant->var_prd_id, $countryId);
                }
                
                // Getting the Final Stocks of the Variant
                $varStock = $melisComVariantService->getVariantFinalStocks($variantId, $countryId);
                
                $variantCurrency = '';
                $variantPrice = '';
                $available = true;
                // Check if Variant has stocks, else remove add button
                if (is_null($varStock) || is_null($varPrice))
                {
                    $available = false;
                }
                else
                {
                    if ($varStock->stock_quantity < 1)
                    {
                        $available = false;
                    }
                    
                    $variantCurrency = $varPrice->cur_symbol;
                    $variantPrice = number_format($varPrice->price_net, 2);
                }
                
                $variantRow = array(
                    'var_id' => $variant->var_id,
                    'label' => $translator->translate('tr_meliscommerce_order_checkout_common_sku'),
                    'var_sku' => $variant->var_sku,
                    'var_price' => $variantCurrency.' '.$variantPrice,
                    'available' => $available
                );
                
                array_push($variants, $variantRow);
            }
        }
        
        $view->hasVariant = $hasVariant;
        $view->variants = $variants;
        $view->productId = $productId;
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * This method will add variant to the Client basket
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function addBasketAction()
    {

        $translator = $this->getServiceLocator()->get('translator');
        
        $request = $this->getRequest();
        
        // Default Values
        $success = 0;
        $errors  = array();
        $textTitle = $translator->translate('tr_meliscommerce_order_checkout_common_add_basket');
        $textMessage = $translator->translate('tr_meliscore_error_message');
        $cat_id = 0;
        $catParents = '';
        
        if($request->isPost()) {
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
            
            // Adding variant to bakset quantity will automatocally 1 (One)
            // But if the Variant already exist in basket this will Add 1 to the Variant Quantity
            $quantity = 1;
            $variantId = $postValues['var_id'];
            
            // Checking if ClientKey is exist on checkout session
            $container = new Container('meliscommerce');
            if (!empty($container['checkout'][self::SITE_ID]['clientKey']))
            {
                $clientKey = $container['checkout'][self::SITE_ID]['clientKey'];
            }
            else 
            {
                // Generating clientKey
                $clientKey = md5(uniqid(date('YmdHis')));
                $container['checkout'][self::SITE_ID]['clientKey'] = $clientKey;
            }
            
            // Add Variant to Client Basket
            $basketId = $melisComBasketService->addVariantToBasket($variantId, $quantity, null, $clientKey);
            
            if (!is_null($basketId))
            {
                $success = 1;
                $textMessage = $translator->translate('tr_meliscommerce_order_checkout_variant_added_success');
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );
         
        return new JsonModel($response);
    }
    
    /**
     * Checking client Basket if empty
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function checkBasketAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        
        $request = $this->getRequest();
        
        // Default Values
        $success = 0;
        $errors  = array();
        $textTitle = $translator->translate('tr_meliscommerce_order_checkout_common_add_basket');
        $textMessage = $translator->translate('tr_meliscore_error_message');
        $cat_id = 0;
        $catParents = '';
        
        if($request->isPost())
        {
            $container = new Container('meliscommerce');
            
            $clientKey = (!empty($container['checkout'][self::SITE_ID]['clientKey'])) ? $container['checkout'][self::SITE_ID]['clientKey'] : null;
            
            $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
            
            // Getting the Client Basket
            $basketData = $melisComBasketService->getBasket(null, $clientKey);
            
            // Checking if the Client basket is not empty, else this will return error message
            if (!is_null($basketData))
            {
                $textMessage = '';
                $success = 1;
            }
            else 
            {
                $textMessage = $translator->translate('tr_meliscommerce_order_checkout_variant_empty_basket_error');
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );
         
        return new JsonModel($response);
    }
    
    /**
     * Render Order Checkout product list Country selection
     * Country selection as datatable filter
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutProductListCountriesAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        $melisEcomCountryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
        $ecomCountries = $melisEcomCountryTable->getCountries();
        
        $selectedCountry = '';
        $container = new Container('meliscommerce');
        if (!empty($container['checkout'][self::SITE_ID]['countryId']))
        {
            $selectedCountry = $container['checkout'][self::SITE_ID]['countryId'];
        }
            
        $selectContainer = '%s %s';
        $select = '<select id="orderCheckoutCountries" class="form-control input-sm"> %s</select>';
        
        $selectOptions = '<option value="" selected>'.$translator->translate('tr_meliscommerce_order_checkout_common_chooose').'</option>';
        foreach ($ecomCountries As $val)
        {
            if ($selectedCountry == $val->ctry_id)
            {
                $selectOptions .= '<option value="'.$val->ctry_id.'" selected>'.$val->ctry_name.'</option>';
            }
            else 
            {
                $selectOptions .= '<option value="'.$val->ctry_id.'">'.$val->ctry_name.'</option>';
            }
            
        }
        
        $select = sprintf($select, $selectOptions);
        $selectContainer = sprintf($selectContainer, '<div class="pays">'.$translator->translate('tr_meliscommerce_order_checkout_country').'</div>', $select);
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->select = $selectContainer;
        return $view;
    }
    
    /**
     * Render Order Chekcout Product list Limit
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutProductListLimitAction()
    {
        return new ViewModel();
    }
    
    /**
     * Render Order Checkout Product list search input
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutProductListSearchAction()
    {
        return new ViewModel();
    }
    
    /**
     * Render Order Checkout Product list Refresh button
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutProductListRefreshAction()
    {
        return new ViewModel();
    }
    
    /**
     * Render Order Checkout Product list View Variant list
     * This Button is attach to product row to view a Product variant list
     * in extra row.
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutProductListViewVariantAction()
    {
        return new ViewModel();
    }
    
    /**
     * Render Order Checkout Product List Select variant
     * This button attach to variant, this will add to Client basket
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutProductVariantListSelectVariantAction()
    {
        return new ViewModel();
    }
    
    /**
     * Render Order Checkout Choose Contact Step
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutChooseContactAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Choose contact header
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutChooseContactHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Choose contact content
     * This content will provide Contact list in a table form
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutChooseContactContentAction(){
        
        $translator = $this->getServiceLocator()->get('translator');
        
        // Getting Contact list table configuration from config
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_order_checkout_contact_list');
        $columns = $melisTool->getColumns();
        $columns['actions'] = array('text' => $translator->translate('tr_meliscommerce_order_checkout_common_action'));
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration();
        return $view;
    }
    
    /**
     * This method will return the list of Active Contact
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function getContactListAction()
    {
        $draw = 0;
        $tableData = array();
        $dataCount = 0;
        $contactDataFiltered = array();
        
        if($this->getRequest()->isPost())
        {
            // Get the locale used from meliscore session
            $container = new Container('meliscore');
            $locale = $container['melis-lang-locale'];
            
            // Getting Contact list table configuration from config
            $translator = $this->getServiceLocator()->get('translator');
            $melisTranslation = $this->getServiceLocator()->get('MelisCoreTranslation');
            $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
            $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_order_checkout_contact_list');
            
            $colId = array_keys($melisTool->getColumns());
        
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
        
            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];
        
            $draw = $this->getRequest()->getPost('draw');
        
            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');
        
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];
        
            $melisEcomClientPersonTable = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
            $dataCount = $melisEcomClientPersonTable->getTotalData();
        
            $getData = $melisEcomClientPersonTable->getContacts(array(
                'where' => array(
                    'key' => 'usr_id',
                    'value' => $search,
                ),
                'order' => array(
                    'key' => $selCol,
                    'dir' => $sortOrder,
                ),
                'start' => $start,
                'limit' => $length,
                'columns' => $melisTool->getSearchableColumns(),
                'date_filter' => array(),
            ));
        
            // store fetched data for data modification (if needed)
            $contactData = $getData->toArray();
             
            $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
            
            foreach ($contactData As $val)
            {
                // Generating contact status html form
                if ($val['cper_status']==1)
                {
                    $contactStatus = '<i class="fa fa-circle text-success"></i>';
                }
                else
                {
                    $contactStatus = '<i class="fa fa-circle text-danger"></i>';
                }
                
                $contactName = $this->getTool()->sanitize($val['cper_firstname'].' '.$val['cper_name']);
                
                // Getting the Contact number of Order(s)
                $contactOrderData = $melisEcomOrderTable->getEntryByField('ord_client_person_id', $val['cper_id']);
                $contactOrder = $contactOrderData->toArray();
                $contactNumOrders = count($contactOrder);
                $contactLastDateOrderStr = '';
                foreach ($contactOrder As $oVal)
                {
                    // Getting the Contact last Order date
                    $contactLastDateOrderStr = mb_substr(strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($oVal['ord_date_creation'])), 0, 10);
                }
                $rowdata = array(
                    'DT_RowId' => $val['cper_id'],
                    'cper_id' => $val['cper_id'],
                    'cper_status' => $contactStatus,
                    'cper_contact' => $contactName,
                    'cper_email' => $this->getTool()->sanitize($val['cper_email']),
                    'cper_num_orders' => $contactNumOrders,
                    'cper_last_order' => $contactLastDateOrderStr,
                );
                
                array_push($tableData, $rowdata);
            }
        }
        
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' => $melisEcomClientPersonTable->getTotalFiltered(),
            'data' => $tableData,
        ));
    }
    
    /**
     * Render Order Checkout Contact list limit
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutContactListLimitAction()
    {
        return new ViewModel();
    }
    
    /**
     * Render Order Cehckout Contact list search input
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutContactListSearchAction()
    {
        return new ViewModel();
    }
    
    /**
     * Render Order Checkout list Refresh button
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutContactListRefreshAction()
    {
        return new ViewModel();
    }
    
    /**
     * Render Order Checkout Contact list Select button
     * This action is attach to contact row, this will select the contact
     * as part of the checkout process
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutContactListSelectAction()
    {
        return new ViewModel();
    }
    
    /**
     * This method will select a contact for checkout process
     * and store to checkout session
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function selectContactAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
    
        $request = $this->getRequest();
    
        // Default Values
        $success = 0;
        $errors  = array();
        $textTitle = $translator->translate('tr_meliscommerce_order_checkout_Choose_contact');
        $textMessage = $translator->translate('tr_meliscore_error_message');
        $cat_id = 0;
        $catParents = '';
    
        if($request->isPost())
        {
            $postValues = get_object_vars($request->getPost());
            
            // Getting the contact details from database
            $melisEcomClientPersonTable = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
            $contactData = $melisEcomClientPersonTable->getEntryById($postValues['contactId']);
            $contact = $contactData->current();
            
            // Checkout session initialization for contact details
            $container = new Container('meliscommerce');
            $container['checkout'][self::SITE_ID]['contactId'] = $contact->cper_id;
            $container['checkout'][self::SITE_ID]['clientId'] = $contact->cper_client_id;
    
            $clientId = $container['checkout'][self::SITE_ID]['clientId'];
            $clientKey = (!empty($container['checkout'][self::SITE_ID]['clientKey'])) ? $container['checkout'][self::SITE_ID]['clientKey'] : null;
    
            $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
            // After selecting contact this will clear the current Basket and create new entry for client basket
            // This process will avoid merging the old basket to the current basket
            $melisComBasketService->emptyPersistentBasket($clientId);
            // Preparing the client Basket, which is added to Persistent basket
            $melisComBasketService->getBasket($clientId, $clientKey);
    
            $success = 1;
            $textMessage = '';
        }
    
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );
         
        return new JsonModel($response);
    }
    
    /**
     * Render Order Checkout Select address step
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutSelectAddressesAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Select address header
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutSelectAddressesHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Select address content
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutSelectAddressesContentAction()
    {
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order checkout Billing Address 
     * This function will generate client billing addresses selection
     * and empty form for creating new billing address
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutBillingAddressAction()
    {
        $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
        $melisComOrderCheckoutService->setSiteId(self::SITE_ID);
        
        $container = new Container('meliscommerce');
        $emptyBillingAddress = $this->params()->fromQuery('emptyBillingAddress');
        
        // Address form will hide if Create address action is trigger
        $hideAddressForm = true;
        if ($emptyBillingAddress)
        {
            // Unsetting stored address for billing
            unset($container['checkout'][self::SITE_ID]['addresses']['addresses']['billing']);
            // Set flag to visible address form on rendered view
            $hideAddressForm = false;
        }
        
        // Checking if there is Billing address stored in checkout session
        // This process will remain the selected address on address selection
        $checkoutBillingAddressId = null;
        $checkoutBillingAddress = array();
        if (!empty($container['checkout'][self::SITE_ID]['addresses']))
        {
            if (!empty($container['checkout'][self::SITE_ID]['addresses']['addresses']['billing']['address']))
            {
                $checkoutBillingAddress = $container['checkout'][self::SITE_ID]['addresses']['addresses']['billing']['address'];
                
                if (!empty($checkoutBillingAddress['cadd_client_id']))
                {
                    if ($container['checkout'][self::SITE_ID]['clientId'] == $checkoutBillingAddress['cadd_client_id'])
                    {
                        $checkoutBillingAddressId = $checkoutBillingAddress['cadd_id'];
                    }
                    else 
                    {
                        $checkoutBillingAddress = array();
                    }
                }
            }
        }
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientAddId = ($this->params()->fromQuery('cadd_id')) ? $this->params()->fromQuery('cadd_id') : $checkoutBillingAddressId;
        
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_checkout/meliscommerce_order_checkout_billing_address_form','meliscommerce_order_checkout_billing_address_form');
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyBillingAddressForm = $factory->createForm($appConfigForm);
        
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_checkout/meliscommerce_order_checkout_address_form','meliscommerce_order_checkout_address_form');
        
        if (!empty($clientAddId))
        {
            foreach ($appConfigForm['elements'] As $key => $val)
            {
                $appConfigForm['elements'][$key]['spec']['attributes']['disabled'] = 'disabled';
            }
        }
        
        $propertyAddressForm = $factory->createForm($appConfigForm);
        
        if (!empty($clientAddId))
        {
            $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
            $clientAddress = $melisEcomClientAddressTable->getEntryById($clientAddId);
            
            if (!empty($clientAddress)){
                $propertyAddressForm->bind($clientAddress->current());
            }
            
            $propertyBillingAddressForm->get('cadd_id')->setValue($clientAddId);
            $hideAddressForm = false;
        }
        else 
        {
            if (!empty($checkoutBillingAddress))
            {
                $propertyAddressForm->setData($checkoutBillingAddress);
                $hideAddressForm = false;
            }
        }
        
        $propertyAddressForm->get('cadd_type')->setValue(1);
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->hideAddressForm = $hideAddressForm;
        $view->setVariable('meliscommerce_order_checkout_billing_address_form', $propertyBillingAddressForm);
        $view->setVariable('meliscommerce_order_checkout_address_form', $propertyAddressForm);
        return $view;
    }
    
    /**
     * Render Order checkout Delivery Address
     * This function will generate client delivery addresses selection
     * and empty form for creating new delivery address
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutDeliveryAddressAction()
    {
        $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
        $melisComOrderCheckoutService->setSiteId(self::SITE_ID);
        
        $container = new Container('meliscommerce');
        $emptyDeliveryAddress = $this->params()->fromQuery('emptyDeliveryAddress');
        
        // Address form will hide if Create address action is trigger
        $hideAddressForm = true;
        if ($emptyDeliveryAddress)
        {
            // Unsetting stored address for delivery
            unset($container['checkout'][self::SITE_ID]['addresses']['addresses']['delivery']);
            // Set flag to visible address form on rendered view
            $hideAddressForm = false;
        }
        
        // Checking if there is Delivery address stored in checkout session
        // This process will remain the selected address on address selection
        $checkoutDeliveryAddressId = null;
        $checkoutDeliveryAddress = array();
        $container = new Container('meliscommerce');
        
        if (!empty($container['checkout'][self::SITE_ID]['addresses']))
        {
            if (!empty($container['checkout'][self::SITE_ID]['addresses']['addresses']['delivery']['address']))
            {
                $checkoutDeliveryAddress = $container['checkout'][self::SITE_ID]['addresses']['addresses']['delivery']['address'];
                
                if (!empty($checkoutDeliveryAddress['cadd_client_id']))
                {
                    if ($container['checkout'][self::SITE_ID]['clientId'] == $checkoutDeliveryAddress['cadd_client_id'])
                    {
                        $checkoutDeliveryAddressId = $checkoutDeliveryAddress['cadd_id'];
                    }
                    else 
                    {
                        $checkoutDeliveryAddress = array();
                    }
                }
            }
        }
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $clientAddId = ($this->params()->fromQuery('cadd_id')) ? $this->params()->fromQuery('cadd_id') : $checkoutDeliveryAddressId;
        
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_checkout/meliscommerce_order_checkout_delivery_address_form','meliscommerce_order_checkout_delivery_address_form');
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyDeliveryAddressForm = $factory->createForm($appConfigForm);
        
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_checkout/meliscommerce_order_checkout_address_form','meliscommerce_order_checkout_address_form');
        
        if (!empty($clientAddId))
        {
            foreach ($appConfigForm['elements'] As $key => $val)
            {
                $appConfigForm['elements'][$key]['spec']['attributes']['disabled'] = 'disabled';
            }
        }
        
        $propertyAddressForm = $factory->createForm($appConfigForm);
        
        if (!empty($clientAddId))
        {
            $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
            $clientAddress = $melisEcomClientAddressTable->getEntryById($clientAddId);
        
            if (!empty($clientAddress)){
                $propertyAddressForm->bind($clientAddress->current());
            }
        
            $propertyDeliveryAddressForm->get('cadd_id')->setValue($clientAddId);
            $hideAddressForm = false;
        }
        else
        {
            if (!empty($checkoutDeliveryAddress))
            {
                $propertyAddressForm->setData($checkoutDeliveryAddress);
                $hideAddressForm = false;
            }
        }
        
        $propertyAddressForm->get('cadd_type')->setValue(2); 
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->hideAddressForm = $hideAddressForm;
        $view->setVariable('meliscommerce_order_checkout_delivery_address_form', $propertyDeliveryAddressForm);
        $view->setVariable('meliscommerce_order_checkout_address_form', $propertyAddressForm);
        return $view;
    }
    
    /**
     * This method will validate Checkout addresses and store to checkout session
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function selectAddressesAction()
    {
        $container = new Container('meliscommerce');
        $translator = $this->getServiceLocator()->get('translator');
        $request = $this->getRequest();
        
        // Default Values
        $success = 0;
        $errors  = array();
        $textTitle = $translator->translate('tr_meliscommerce_order_checkout_Select_addresses');
        $textMessage = '';
        $cat_id = 0;
        $catParents = '';
        
        if($request->isPost())
        {
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            
            // Getting address form configuration from config
            $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_checkout/meliscommerce_order_checkout_address_form','meliscommerce_order_checkout_address_form');
            $factory = new \Zend\Form\Factory();
            $formElements = $this->serviceLocator->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $appConfigFormElements = $appConfigForm['elements'];
                
            $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
            $melisEcomClientPersonTable = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
            
            $clientAddresses = array();
            foreach ($postValues As $key => $val)
            {
                if (!empty($val['noSelected']))
                {
                    if ($key == 'billing')
                    {
                        // Error message if no selected/datas for Billing address
                        $errors['billing'] = array(
                            'label' => $translator->translate('tr_meliscommerce_order_checkout_address_billing'),
                            'noSelected' => $translator->translate('tr_meliscommerce_order_checkout_address_no_selected_billing_address')
                        );
                    }
                    else 
                    {
                        // Error message if no selected/datas for delivery address
                        $errors['delivery'] = array(
                            'label' => $translator->translate('tr_meliscommerce_order_checkout_address_delivery'),
                            'noSelected' => $translator->translate('tr_meliscommerce_order_checkout_address_no_selected_delivery_address')
                        );
                    }
                }
                else 
                {
                    if ($val['cadd_id'])
                    {
                        // if cadd_id exist this means address use for checkout is from existing addresses
                        $clientAddress = $melisEcomClientAddressTable->getEntryById($val['cadd_id'])->current();
                        if (!empty($clientAddress))
                        {
                            $clientAddresses[$key] = (Array) $clientAddress;
                        }
                    }
                    else
                    {
                        $propertyForm = $factory->createForm($appConfigForm);
                        $propertyForm->setData($val);
                    
                        if ($propertyForm->isValid())
                        {
                            $clientAddress = $propertyForm->getData();
                            $clientAddresses[$key] = $clientAddress;
                        }
                        else
                        {
                            $error = $propertyForm->getMessages();
                            foreach ($error as $keyError => $valueError)
                            {
                                // Preparing form Id en-order to locate the error occured
                                // This process is for multiple form
                                $error[$keyError]['form'][] = $key.'AddressOrderCheckoutForm';
                            }
                            $errors = array_merge_recursive($errors, $error);
                    
                            foreach ($errors as $keyError => $valueError)
                            {
                                foreach ($appConfigFormElements as $keyForm => $valueForm)
                                {
                                    if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                                    {
                                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            if (empty($errors))
            {
                if (!empty($clientAddresses))
                {
                    if (count($clientAddresses) == 2)
                    {
                        // Validating addresses using Checkout service
                        $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
                        $melisComOrderCheckoutService->setSiteId(self::SITE_ID);
                        $validatedAddresses = $melisComOrderCheckoutService->validateAddresses($clientAddresses['delivery'], $clientAddresses['billing']);
                        
                        if ($validatedAddresses['success'] != true)
                        {
                            foreach ($validatedAddresses['addresses'] As $key => $val)
                            {
                                if (in_array($key, array('delivery', 'billing')))
                                {
                                    if (!empty($validatedAddresses['addresses'][$key]['error']))
                                    {
                                        $errors[$key] = array(
                                            'label' => $translator->translate('tr_meliscommerce_order_checkout_address_'.$key),
                                            $key.'_err_msg' => $translator->translate('tr_'.$validatedAddresses['addresses'][$key]['error'])
                                        );
                                    }
                                }
                            }
                        }
                        
                        if ($validatedAddresses['success'] == true)
                        {
                            $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
                            
                            foreach ($validatedAddresses['addresses'] As $key => $val)
                            {
                                // checking if the entry is existing in db, else this will save to selected contact addresses
                                if (empty($val['address']['cadd_id']))
                                {
                                    $val['address']['cadd_client_id'] = $container['checkout'][self::SITE_ID]['clientId'];
                                    $val['address']['cadd_client_person'] = $container['checkout'][self::SITE_ID]['contactId'];
                                    
                                    $addId = $clientSrv->saveClientAddress($val['address']);
                                    $validatedAddresses['addresses'][$key]['address'] = (Array) $melisEcomClientAddressTable->getEntryById($addId)->current();
                                }
                            }
                            
                            $container['checkout'][self::SITE_ID]['addresses'] = $validatedAddresses;
                            $success = 1;
                        }
                    }
                }
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );
         
        return new JsonModel($response);
    }
    
    /**
     * Render Order Checkout Summary step
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutSummaryAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Summary Basket
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutSummaryBasketAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        $container = new Container('meliscommerce');
        $clientId = (!empty($container['checkout'][self::SITE_ID]['clientId'])) ? $container['checkout'][self::SITE_ID]['clientId'] : null;
        $clientKey = (!empty($container['checkout'][self::SITE_ID]['clientKey'])) ? $container['checkout'][self::SITE_ID]['clientKey'] : null;
        
        $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
        $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
        
        $action = $this->params()->fromQuery('action');
        $variantId = $this->params()->fromQuery('variantId');
        $variantQty = (int) $this->params()->fromQuery('variantQty');
        
        if (!empty($variantId))
        {
            if ($action == 'add')
            {
                $melisComBasketService->addVariantToBasket($variantId, $variantQty, $clientId, $clientKey);
            }
            elseif ($action == 'deduct')
            {
                $melisComBasketService->removeVariantFromBasket($variantId, $variantQty, $clientId, $clientKey);
            }
        }
        
        $basketData = $melisComBasketService->getBasket($clientId, $clientKey);
         
        // Getting Current Langauge ID
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $langId = $melisTool->getCurrentLocaleID();
        
        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
        
        $basket = array();
        $subTotal = 0;
        $totalDiscount = 0;
        $total = 0;
        
        $container = new Container('meliscommerce');
        $countryId = $container['checkout'][self::SITE_ID]['countryId'];
        $melisComOrderCheckoutService->setSiteId(self::SITE_ID);
        $clientOrderCost = $melisComOrderCheckoutService->computeAllCosts($clientId);
        
        if (isset($clientOrderCost['costs']['order']))
        {
            $clientOrder = $clientOrderCost['costs']['order'];
            
            if (isset($clientOrder['details']))
            {
                $clientOrderVariant =  $clientOrder['details'];
                
                if (!empty($clientOrderVariant))
                {
                    foreach ($clientOrderVariant As $key => $val)
                    {
                        $variantId = $key;
                        $variant = $melisComVariantService->getVariantById($variantId);
                        $productId = $variant->getVariant()->var_prd_id;
                        $varSku = $variant->getVariant()->var_sku;
                        
                        // Getting the Variant price from Variant Service
                        $varPrice = $melisComVariantService->getVariantFinalPrice($variantId, $countryId);
                        
                        if (is_null($varPrice))
                        {
                            // if Vairant Price is null this will try to get from Product Price
                            $varPrice = $melisComProductService->getProductFinalPrice($productId, $countryId);
                        }
                        
                        $quantity = $val['quantity'];
                        
                        $variantTotal = $quantity * $varPrice->price_net;
                        $data = array(
                            'var_id' => $variantId,
                            'var_sku' => $varSku,
                            'var_quantity' => $quantity,
                            'var_price' => $varPrice->cur_symbol.' '.number_format($varPrice->price_net, 2),
                            'product_name' => $melisComProductService->getProductName($productId, $langId),
                            'var_total' => $varPrice->cur_symbol.' '.number_format($variantTotal, 2)
                        );
                        
                        array_push($basket, $data);
                    }
                }
            }
            
            if (isset($clientOrder['totalWithoutCoupon']))
            {
                $subTotal = $clientOrder['totalWithoutCoupon'];
            }
            
            if (isset($clientOrder['total']))
            {
                $total = $clientOrder['total'];
            }
        }
        
        $couponCode = $this->params()->fromQuery('couponCode');
        $couponErr = '';
        
        // Getting the Country currency, depend on country selected during step 1
        $melisEcomCountryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
        $countryCurrency = $melisEcomCountryTable->getCountryCurrency($countryId)->current();
        
        $countryCurrencySympbol = '';
        if (!empty($countryCurrency)){
            $countryCurrencySympbol = $countryCurrency->cur_symbol;
        }
        
        $discountInfo = '';
        if (!empty($couponCode) && !is_null($clientId))
        {
            $couponSrv = $this->getServiceLocator()->get('MelisComCouponService');
            $coupon = $couponSrv->validateCoupon($couponCode, $clientId);
            
            if($coupon['success'])
            {
                $couponData = $coupon['coupon'];
                
                if (!empty($couponData->coup_percentage))
                {
                    $totalDiscount = ($couponData->coup_percentage / 100) * $subTotal;
                    $discountInfo = $couponData->coup_percentage.'%';
                }
                elseif (!empty($couponData->coup_discount_value))
                {
                    $totalDiscount = $couponData->coup_discount_value;
                    $discountInfo = $countryCurrencySympbol.' '.number_format($couponData->coup_discount_value, 2);
                }
            }
            else
            {
                $couponErr = $translator->translate('tr_'.$coupon['error']);
            }
        }
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->clientOrderCost = $clientOrderCost;
        $view->couponCode = $couponCode;
        $view->couponErr = $couponErr;
        $view->basket = $basket;
        $view->subTotal = $subTotal;
        $view->totalDiscount = $totalDiscount;
        $view->discountInfo = $discountInfo;
        $view->total = $total;
        $view->countryCurrencySympbol = $countryCurrencySympbol;
        return $view;
    }
    
    /**
     * Render Order Checkout Summary Billing Address
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutSummaryBillingAddressAction()
    {
        // Getting Current Langauge ID
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $langId = $melisTool->getCurrentLocaleID();
        $melisEcomCivilityTransTable = $this->getServiceLocator()->get('MelisEcomCivilityTransTable');
        $melisEcomCountryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
        
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_checkout/meliscommerce_order_checkout_address_form','meliscommerce_order_checkout_address_form');
        $appConfigFormElements = $appConfigForm['elements'];
        
        $container = new Container('meliscommerce');
        $deliveryAddress = array();
        
        if (!empty($container['checkout'][self::SITE_ID]['addresses']))
        {
            foreach ($appConfigFormElements As $val)
            {
                if ($val['spec']['type'] != 'hidden')
                {
                    if ($val['spec']['name'] == 'cadd_civility')
                    {
                        $civility = $container['checkout'][self::SITE_ID]['addresses']['addresses']['billing']['address'][$val['spec']['name']];
                        
                        $civilityData = $melisEcomCivilityTransTable->getCivilityTransByCivilityId($civility, $langId)->current();
                        
                        if (!empty($civilityData))
                        {
                            $data = array(
                                'label' => $val['spec']['options']['label'],
                                'value' => $civilityData->civt_max_name
                            );
                        }
                    }
                    else
                    {
                        $data = array(
                            'label' => $val['spec']['options']['label'],
                            'value' => $container['checkout'][self::SITE_ID]['addresses']['addresses']['billing']['address'][$val['spec']['name']]
                        );
                    }
            
                    array_push($deliveryAddress, $data);
                }
            }
        }
            
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->deliveryAddress = $deliveryAddress;
        return $view;
    }
    
    /**
     * Reder Order Checkout Summary Delivery Address
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutSummaryDeliveryAddressAction()
    {
        // Getting Current Langauge ID
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $langId = $melisTool->getCurrentLocaleID();
        $melisEcomCivilityTransTable = $this->getServiceLocator()->get('MelisEcomCivilityTransTable');
        $melisEcomCountryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
        
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_checkout/meliscommerce_order_checkout_address_form','meliscommerce_order_checkout_address_form');
        $appConfigFormElements = $appConfigForm['elements'];
        
        $container = new Container('meliscommerce');
        $deliveryAddress = array();
        
        if (!empty($container['checkout'][self::SITE_ID]['addresses']))
        {
            foreach ($appConfigFormElements As $val)
            {
                if ($val['spec']['type'] != 'hidden')
                {
                    if ($val['spec']['name'] == 'cadd_civility')
                    {
                        $civility = $container['checkout'][self::SITE_ID]['addresses']['addresses']['delivery']['address'][$val['spec']['name']];
                        
                        $civilityData = $melisEcomCivilityTransTable->getCivilityTransByCivilityId($civility, $langId)->current();
                        
                        if (!empty($civilityData))
                        {
                            $data = array(
                                'label' => $val['spec']['options']['label'],
                                'value' => $civilityData->civt_max_name
                            );
                        }
                    }
                    else
                    {
                        $data = array(
                            'label' => $val['spec']['options']['label'],
                            'value' => $container['checkout'][self::SITE_ID]['addresses']['addresses']['delivery']['address'][$val['spec']['name']]
                        );
                    }
                    
                    array_push($deliveryAddress, $data);
                }
            }
        }
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->deliveryAddress = $deliveryAddress;
        return $view;
    }
    
    /**
     * This method will validate Client Basket and Addresses
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function confirmOrderCheckoutSummaryAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        
        $request = $this->getRequest();
        
        // Default Values
        $success = 0;
        $errors  = array();
        $textTitle = 'tr_meliscommerce_order_checkout_variant_basket';
        $textMessage = 'tr_meliscommerce_order_checkout_save_unable';
        $cat_id = 0;
        $catParents = '';
        $orderId = null;
        
        if($request->isPost())
        {
            $container = new Container('meliscommerce');
            $clientId = $container['checkout'][self::SITE_ID]['clientId'];
            
            // Retrieving Client basket, 
            // In this process variant quatity, variant amount and other details about checkout will validate
            // to ensure everything is good before proceding to payment
            $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
            $basketData = $melisComBasketService->getBasket($clientId);
            
            $couponSrv = $this->getServiceLocator()->get('MelisComCouponService');
            
            $couponCode = $request->getPost('couponCode');
            
            $totalDiscount = 0;
            $couponErr = '';
            $couponData = array();
            if (!empty($couponCode) && !is_null($clientId))
            {
                $coupon = $couponSrv->validateCoupon($couponCode, $clientId);
                if($coupon['success'])
                {
                    $couponData = $coupon['coupon'];
                }
                else
                {
                    $errors['couponError'] = array(
                        'label' => 'Coupon',
                        'couponError' => $translator->translate('tr_'.$coupon['error'])
                    );
                }
            }
            
            if (empty($errors))
            {
                if (!is_null($basketData))
                {
                    if (!empty($container['checkout'][self::SITE_ID]['clientId']))
                    {
                        // Getting Current Langauge ID
                        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
                        $langId = $melisTool->getCurrentLocaleID();
                        
                        $varSvc = $this->getServiceLocator()->get('MelisComVariantService');
                        
                        $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
                        $melisComOrderCheckoutService->setSiteId(self::SITE_ID);
                        $clientBasket = $melisComOrderCheckoutService->checkoutStep1_prePayment($clientId);
                        
                        if ($clientBasket['success'] != true)
                        {
                            if (!empty($clientBasket['errors']))
                            {
                                foreach ($clientBasket['errors'] As $key => $val)
                                {
                                    switch ($key)
                                    {
                                        case 'order':
                                            $errors['order'] = array(
                                                'label' => 'Order Reference',
                                                $key.'_exist' => $val,
                                            );
                                        break;
                                        case 'basket':
                                            foreach ($val As $bKey => $bVal)
                                            {
                                                if (!empty($bVal['error']))
                                                {
                                                    
                                                    $variantData = $bVal[$bKey]->getVariant();
                                                    $variant = $variantData->getVariant();
                                                    $productName = $variant->var_sku;
                                                    
                                                    $errors[$bKey.'_basket'] = array(
                                                        'label' => $productName,
                                                        $bKey.'_err_msg' => $translator->translate('tr_'.$bVal['error'])
                                                    );
                                                }
                                            }
                                        break;
                                        case 'costs':
                                            foreach ($val As $bKey => $bVal)
                                            {
                                                if (in_array($bKey, array('order', 'shipment')))
                                                {
                                                    if (!empty($bVal['errors']))
                                                    {
                                                        foreach ($bVal['errors'] As $cKey => $cVal)
                                                        {
                                                            $variantData = $varSvc->getVariantById($cKey);
                                                            
                                                            $productName = 'Unkown variant Id('.$cKey.')';
                                                            if (!empty($variantData))
                                                            {
                                                                $variant = $variantData->getVariant();
                                                                $productName = $variant->var_sku;
                                                            }
                                                            
                                                            $errors[$bKey.'_costs'] = array(
                                                                'label' => $productName,
                                                                $cKey.'_err_msg' => $translator->translate('tr_'.$cVal)
                                                            );
                                                        }
                                                    }
                                                }
                                            }
                                        break;
                                    }
                                }
                            }
                        }
                        else 
                        {
                            $orderId = $clientBasket['orderId'];
                        }
                        
                        if (empty($errors))
                        {
                            if (!empty($couponData))
                            {
                                $container['checkout'][self::SITE_ID]['couponId'] = $couponData->coup_id;
                            }
                            
                            $success = 1;
                            
                            $textMessage = 'tr_meliscommerce_order_checkout_save_success';
                        }
                    }
                }
                else
                {
                    $textMessage = 'tr_meliscommerce_order_checkout_variant_empty_basket_error';
                }
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => ($textTitle != 'tr_meliscommerce_order_checkout_save_success') ? $textTitle : '', // Just to be sure the message for log will not show to checkout interface as response
            'textMessage' => ($textMessage == 'tr_meliscommerce_order_checkout_save_success') ? $textMessage : '',
            'errors' => $errors,
        );
        
        $logData = array_merge($response, array(
            'textTitle' => 'tr_meliscommerce_order_checkout_add_order',
            'textMessage' => $textMessage,
            'typeCode' => 'ECOM_CHECKOUT_ORDER_ADD',
            'itemId' => $orderId
        ));
        
        $this->getEventManager()->trigger('meliscommerce_checkout_order_add', $this, $logData);
        
        return new JsonModel($response);
    }
    
    /**
     * Render Order Checkout Summary Header
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutSummaryHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Summary Content
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutSummaryContentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Payment Step
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutPaymentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Payment header
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutPaymentHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Payment Content
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutPaymentContentAction()
    {
        $orderId = null;
        $totalCost = 0;
        
        $container = new Container('meliscommerce');
        if (!empty($container['checkout'][self::SITE_ID]['orderId']))
        {
            $orderId = $container['checkout'][self::SITE_ID]['orderId'];
            
            // Retrieving the Checkout total cost
            $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
            $melisComOrderCheckoutService->setSiteId(self::SITE_ID);
            $order = $melisComOrderCheckoutService->computeAllCosts($container['checkout'][self::SITE_ID]['clientId']);
            $totalCost = $order['costs']['total'];
        }
        
        $couponId = null;
        if (!empty($container['checkout'][self::SITE_ID]['couponId']))
        {
            $couponId = $container['checkout'][self::SITE_ID]['couponId'];
        }
        
        $param = array(
            'countryId' => $container['checkout'][self::SITE_ID]['countryId'],
            'orderId' => $orderId,
            'couponId' => $couponId,
            'totalCost' => $totalCost,
        );
        
        $urlParam = http_build_query($param);
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->urlParam = $urlParam;
        return $view;
    }
    
    /**
     * Render Order Checkout payment Iframe
     * This will represent as payment Gateway/ Payment API
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutPaymentIframeAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        
        // Url data from iFrame src
        $countryId = $this->params()->fromQuery('countryId');
        $orderId = $this->params()->fromQuery('orderId');
        $couponId = $this->params()->fromQuery('couponId');
        $totalCost = $this->params()->fromQuery('totalCost');
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        
        $view->countryId = $countryId;
        $view->orderId = $orderId;
        $view->couponId = $couponId;
        $view->totalCost = $totalCost;
        $view->retrunCode = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 10)), 0, 10);
        return $view;
    }
    
    /**
     * Render Order Cehckout Payment Done
     * This will represent the payment is done using Payment Gateway/API
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutPaymentDoneAction()
    {
        $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
        $melisComOrderCheckoutService->setSiteId(self::SITE_ID);
        $melisComOrderCheckoutService->checkoutStep2_postPayment();
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Order Checkout Confirmation 
     * This method will return the result of the checkout process
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderCheckoutConfirmationAction()
    {
        $activateTab = $this->params()->fromQuery('activateTab'); 
        $translator = $this->getServiceLocator()->get('translator');
        
        $result = array();
        
        $container = new Container('meliscommerce');
        if (!empty($container['checkout'][self::SITE_ID]['orderId']))
        {
            // Retrieving order details
            $orderid = $container['checkout'][self::SITE_ID]['orderId'];
            $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
            $order = $melisEcomOrderTable->getEntryById($orderid)->current();
            
            if (!empty($order))
            {
                switch ($order->ord_status)
                {
                    case '1':
                        // Result message if checkout is successfully done
                        $result['message'] = $translator->translate('tr_meliscommerce_order_checkout_confirmation_success');
                        $result['refernce'] = $translator->translate('tr_meliscommerce_order_checkout_confirmation_reference').' <b>'.$order->ord_reference.'</b>';
                        $result['alert'] = 'success';
                        break;
                    case '-1':
                        // Result message if the checkout payment had been skiped
                        $result['message'] = $translator->translate('tr_meliscommerce_order_checkout_confirmation_skip_payment');
                        $result['alert'] = 'info';
                        break;
                    case '6':
                        // 6 is the primary key of status code for error in checkout
                        // Result message if the checkout payment paid amount is not equal to Total cost of the client basket
                        $result['message'] = $translator->translate('tr_meliscommerce_order_checkout_confirmation_price_not_equal');
                        $result['alert'] = 'danger';
                        break;
                    default:
                        // System error
                        $result['message'] = $translator->translate('tr_meliscore_error_message');
                        $result['alert'] = 'danger';
                        break;
                }
            }
        }
        else 
        {
            $result['message'] = $translator->translate('tr_meliscore_error_message');
            $result['alert'] = 'danger';
        }
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->result = $result;
        $view->activateTab = $activateTab;
        return $view;
    }

    private function getTool()
    {
        $tool = $this->getServiceLocator()->get('MelisCoreTool');
        return $tool;
    }
}