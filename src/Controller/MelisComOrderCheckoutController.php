<<<<<<< HEAD
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

class MelisComOrderCheckoutController extends AbstractActionController
{
    public function testFunctionAction(){
        
//         $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
//         $result = $melisComBasketService->getBasket(1, 12345);
//         $result = $melisComBasketService->cleanAnonymousBaskets(date('Y-m-d H:i:s'));
        
//         echo count($result).PHP_EOL;
        
//         echo '<pre>';
//         print_r($result);
//         echo '</pre>';
        
        
//         $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
//         $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
        
//         $result = $melisComVariantService->getVariantFinalStocks(21, 1);
//         $result = $melisComVariantService->getVariantStocksById(21);
//         $result = $melisComVariantService->getVariantFinalPrice(21 , 1);
//         $result = $melisComProductService->getProductFinalPrice(4 , 1);
        
//         if (is_null($result))
//         {
//             echo 'NULL';
//         }

        $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
//         $result = $melisComOrderCheckoutService->validateBasket(1);
//         $result = $melisComOrderCheckoutService->computeOrderCost(1);
//         $result = $melisComOrderCheckoutService->computeShipmentCost(1);
        $result = $melisComOrderCheckoutService->computeAllCosts(1);
//         $result = $melisComOrderCheckoutService->checkoutStep1_prePayment(1);
        
//         if ($result['success'] == true)
//         {
            echo '<pre>';
            print_r($result);
            echo '</pre>';
//         }
        
        return new JsonModel();
    }
    
    const PLUGIN_INDEX = 'meliscommerce';
    const TOOL_KEY = '';
    
    public function renderOrderCheckoutPageAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutContentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function validateCheckoutStepAction()
    {
        
    }
    
    public function renderOrderCheckoutChooseProductAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutChooseProductHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutChooseProductContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
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
    
    public function renderOrderCheckoutProductBasketAction()
    {
        
        $container = new Container('meliscommerce');
        $clientId = (!empty($container['checkout']['clientId'])) ? $container['checkout']['clientId'] : null;
        $clientKey = (!empty($container['checkout']['clientKey'])) ? $container['checkout']['clientKey'] : null;
        
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
        $total = 0;
        if (!is_null($basketData)){
            foreach ($basketData As $val)
            {
                $variantId = $val->getVariantId();
                $variant = $val->getVariant();
                $quantity = $val->getQuantity();
                $productId = $variant->getVariant()->var_prd_id;
                $varSku = $variant->getVariant()->var_sku;
            
                $varPrice = $melisComVariantService->getVariantFinalPrice($variantId, $langId);
            
                $variantTotal = $quantity * $varPrice->price_net;
                $data = array(
                    'var_id' => $variantId,
                    'var_sku' => $varSku,
                    'var_quantity' => $quantity,
                    'var_price' => number_format($varPrice->price_net, 2),
                    'product_name' => $melisComProductService->getProductName($productId, $langId),
                    'var_total' => number_format($variantTotal, 2)
                );
                
                $total += $variantTotal;
                array_push($basket, $data);
            }
        }
        
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->basket = $basket;
        $view->total = $total;
        return $view;
    }
    
    public function getProductListAction()
    {
        $draw = 0;
        $dataCount = 0;
        $tableData = array();
        $clientDataFiltered = array();
        
        if($this->getRequest()->isPost()) {
            
            // Getting Current Langauge ID
            $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
            $langId = $melisTool->getCurrentLocaleID();
            
            $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
            $docSvc = $this->getServiceLocator()->get('MelisComDocumentService');
            
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
            
            $order = $this->getRequest()->getPost('order');
            
            $draw = (int) $this->getRequest()->getPost('draw');
            
            $start = (int) $this->getRequest()->getPost('start');
            $length =  (int) $this->getRequest()->getPost('length');
            
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];
            
            $prodData = $prodSvc->getProductList($langId, null, null, null, $start, $length, $search, $order[0]['dir']);
            
            $clientDataFiltered = $prodSvc->getProductList($langId, null, null, null, null, null, $search, $order[0]['dir']);
            $prodImage = '<img src="%s" width="60" height="60" class="img-rounded img-responsive"/>';
            foreach($prodData as $val) {
                $productId = $val->getProduct()->prd_id;
                
                $rowData = array(
                    'DT_RowId' => $productId,
                    'product_id' => $productId,
                    'product_image' => sprintf($prodImage, $docSvc->getDocDefaultImageFilePath('product', $productId)),
                    'product_name' => $prodSvc->getProductName($productId, $langId),
                );
                array_push($tableData, $rowData);
            }
        }
        
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => count($tableData),
            'recordsFiltered' =>  count($clientDataFiltered),
            'data' => $tableData,
        ));
    }
    
    public function renderOrderCheckoutProductVariantListAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        
        // Getting Current Langauge ID
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $langId = $melisTool->getCurrentLocaleID();
        
        $productId = $this->params()->fromQuery('productId');
        
        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
        $variantData = $melisComVariantService->getVariantListByProductId($productId, $langId, null, true);
        
        $variants = array();
        foreach ($variantData As $val)
        {
            $variantId = $val->getId();
            $variant = $val->getVariant();
            
            $varPrice = $melisComVariantService->getVariantFinalPrice($variantId, $langId);
            $varStock = $melisComVariantService->getVariantFinalStocks($variantId, $langId);
            
            if (!is_null($varPrice) && !is_null($varStock))
            {
                $variantRow = array(
                    'var_id' => $variant->var_id,
                    'label' => $translator->translate('tr_meliscommerce_order_checkout_common_sku'),
                    'var_sku' => $variant->var_sku,
                    'var_price' => $varPrice->price_net
                );
                array_push($variants, $variantRow);
            }
        }
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $productId;
        $view->variants = $variants;
        $view->variantData = $variantData;
        return $view;
    }
    
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
            $postValues = get_object_vars($request->getPost());
            
            $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
            
            $quantity = 1;
            $variantId = $postValues['var_id'];
            
            $container = new Container('meliscommerce');
            if (!empty($container['checkout']['clientKey']))
            {
                $clientKey = $container['checkout']['clientKey'];
            }
            else 
            {
                $clientKey = md5(uniqid(date('YmdHis')));
                $container['checkout'] = array();
                $container['checkout']['clientKey'] = $clientKey;
            }
            
            $basketId = $melisComBasketService->addVariantToBasket($variantId, $quantity, null, $clientKey);
            
            if (!is_null($basketId))
            {
                $success = 1;
                $textMessage = 'tr_meliscommerce_order_checkout_variant_added_success';
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
    
    public function renderOrderCheckoutProductListLimitAction()
    {
        return new ViewModel();
    }
    
    public function renderOrderCheckoutProductListSearchAction()
    {
        return new ViewModel();
    }
    
    public function renderOrderCheckoutProductListRefreshAction()
    {
        return new ViewModel();
    }
    
    public function renderOrderCheckoutProductListViewVariantAction()
    {
        return new ViewModel();
    }
    
    public function renderOrderCheckoutProductVariantListSelectVariantAction()
    {
        return new ViewModel();
    }
    
    
    
    
    public function renderOrderCheckoutChooseClientAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutChooseClientHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutChooseClientContentAction(){
        
        $translator = $this->getServiceLocator()->get('translator');
        
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, 'meliscommerce_order_checkout_client_list');
        $columns = $melisTool->getColumns();
        $columns['actions'] = array('text' => $translator->translate('tr_meliscommerce_order_checkout_common_action'));
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration();
        return $view;
    }
    
    public function renderOrderCheckoutClientListLimitAction()
    {
        return new ViewModel();
    }
    
    public function renderOrderCheckoutClientListSearchAction()
    {
        return new ViewModel();
    }
    
    public function renderOrderCheckoutClientListRefreshAction()
    {
        return new ViewModel();
    }
    
    public function renderOrderCheckoutClientListSelectAction()
    {
        return new ViewModel();
    }
    
    
    
    public function renderOrderCheckoutSelectAddressesAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutSelectAddressesHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutSelectAddressesContentAction(){
        
        // Getting Category Translations Form from App.forms.php Config
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_checkout/meliscommerce_order_checkout_billing_address_form','meliscommerce_order_checkout_billing_address_form');
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyBillingAddressForm = $factory->createForm($appConfigForm);
        
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_checkout/meliscommerce_order_checkout_delivery_address_form','meliscommerce_order_checkout_delivery_address_form');
        $propertyDeliveryAddressForm = $factory->createForm($appConfigForm);
        
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_checkout/meliscommerce_order_checkout_address_form','meliscommerce_order_checkout_address_form');
        $propertyAddressForm = $factory->createForm($appConfigForm);
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->setVariable('meliscommerce_order_checkout_billing_address_form', $propertyBillingAddressForm);
        $view->setVariable('meliscommerce_order_checkout_delivery_address_form', $propertyDeliveryAddressForm);
        $view->setVariable('meliscommerce_order_checkout_address_form', $propertyAddressForm);
        return $view;
    }
    
    public function renderOrderCheckoutSummaryAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutSummaryHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutSummaryContentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutPaymentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutPaymentHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutPaymentContentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    public function renderOrderCheckoutConfirmationAction(){
        return new ViewModel();
    }
}