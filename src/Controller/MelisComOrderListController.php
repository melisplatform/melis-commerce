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
use Laminas\Http\Response;
use MelisCore\Controller\MelisAbstractActionController;

class MelisComOrderListController extends MelisAbstractActionController
{
    /**
     * renders the order list page container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListPageAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order list page header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListHeaderContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order list page left header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListHeaderLeftContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order list page right header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListHeaderRightContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order list page header title
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order list page content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListWidgetsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order list page content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListWidgetsNumOrdersAction()
    {
        $melisComOrderService = $this->getServiceManager()->get('MelisComOrderService');
        $orderCount = $melisComOrderService->getOrderList(null, true);
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->num = count($orderCount);
        return $view;
    }
    
    /**
     * renders the order list page content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListWidgetsMonthOrdersAction()
    {
        $melisComOrderService = $this->getServiceManager()->get('MelisComOrderService');
        $orderCount = $melisComOrderService->getWidgetOrders('curMonth', true);
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->num = $orderCount;
        return $view;
    }
    
    /**
     * renders the order list page content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListWidgetsAvgOrdersAction()
    {
        $melisComOrderService = $this->getServiceManager()->get('MelisComOrderService');
        $orderCount = $melisComOrderService->getWidgetOrders('avgMonth', true);
        
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->num = (float) $orderCount['average'];
        return $view;
    }
    
    /**
     * renders the order list page content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order list page table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentTableAction()
    {
        $status = array();
        $orderStatusTable = $this->getServiceManager()->get('MelisEcomOrderStatusTable');
        
        foreach($orderStatusTable->fetchAll() as $orderStatus){
            $status[] = $orderStatus;
        }
        
        $columns = $this->getTool()->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->status = $status;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#tableOrderList', true, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }
    
    /**
     * renders the order list content table filter bulk
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentFilterBulkAction()
    {
        $status = $this->getRequest()->getPost('osta_id');
        $options = '<option  value="">'.$this->getTool()->getTranslation('tr_meliscommerce_order_list_filter_status').'</option>';
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $langId = $this->getTool()->getCurrentLocaleID();
        foreach($orderSvc->getOrderStatusList($langId, false) as $orderStatus){
                $selected  = ($orderStatus->osta_id == $status)? 'selected' : ''; 
                $options .= '<option value="'.$orderStatus->osta_id.'" '.$selected.'>'.$orderStatus->ostt_status_name .'</option>';
        }
        $view = new ViewModel();
        $view->options = $options;
        return $view;
    }
    
    /**
     * renders the order list content table filter limit
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentFilterLimitAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table filter search
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentFilterDateAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table filter search
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentFilterSearchAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table filter grid view
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentFilterGridViewAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table filter list view
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentFilterListViewAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table filter export
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentFilterExportAction()
    {
        return new ViewModel();
    }
    /**
     * renders the order list content table filter refresh
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentFilterRefreshAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table action info
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentActionInfoAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list modal container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListModalAction()
    {   
        $id = $this->params()->fromQuery('id');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->id = $id;
        $view->setTerminal(true);
        return $view;
    }
    
    /**
     * renders the order list add new order button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListAddOrderAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list modal for updating order status
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentStatusFormAction()
    {
        $orderId = (int) $this->params()->fromQuery('orderId');
        $melisComOrderService = $this->getServiceManager()->get('MelisComOrderService');
//         $tabId = $this->params()->fromQuery('tabId');
        $status = array();
        $orderStatusTable = $this->getServiceManager()->get('MelisEcomOrderStatusTable');
        foreach($orderStatusTable->fetchAll() as $orderStatus){
            if($orderStatus->osta_id != -1){
                $status[] = $orderStatus;
            }            
        }
        
        
        $view = new ViewModel();
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_list/meliscommerce_order_list_status_form','meliscommerce_order_list_status_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);        
        $updateStatusForm = $factory->createForm($appConfigForm);
        $order = $melisComOrderService->getOrderStatusByOrderId($orderId, $this->getTool()->getCurrentLocaleID())[0];
        $updateStatusForm->setData((array)$order);

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->order = $order;
        $view->status = $status;
        $view->setVariable('meliscommerce_order_list_status_form', $updateStatusForm);
        return $view;
    }
    
    /**
     * renders the order list modal for updating order export
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderListContentExportFormAction()
    {
        
        $datepickerInit = $this->getTool()->datePickerInit('date_start');
        $datepickerInit .= $this->getTool()->datePickerInit('date_end');
        
        $status = array();
        $orderStatusTable = $this->getServiceManager()->get('MelisEcomOrderStatusTable');
        foreach($orderStatusTable->fetchAll() as $orderStatus){
            $status[] = $orderStatus;
        }
        
        $view = new ViewModel();
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_list/meliscommerce_order_list_export_form','meliscommerce_order_list_export_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);        
        $orderExportForm = $factory->createForm($appConfigForm);

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->status = $status;
        $view->datePickerInit = $datepickerInit;
        $view->setVariable('meliscommerce_order_list_export_form', $orderExportForm);
        return $view;
    }
    
    public function getOrderListDataAction()
    {
        $success = 0;
        $melisComOrderService = $this->getServiceManager()->get('MelisComOrderService');
        $clientCompanyTable = $this->getServiceManager()->get('MelisEcomClientCompanyTable');
        $clientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
        $civilityTransTable = $this->getServiceManager()->get('MelisEcomCivilityTransTable');
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        
        $confOrder = $melisCoreConfig->getItem('meliscommerce/conf/orderStatus');
        $colId = array();
        $dataCount = 0;
        $dataFilter = 0;
        $draw = 0;
        $tableData = array();
        
        $langId = $this->getTool()->getCurrentLocaleID();
        $checkBox = '<div class="checkbox checkbox-single margin-none" data-order-id="%s">
						<label class="checkbox-custom">
							<i class="fa fa-fw fa-square-o"></i>
							<input type="checkbox" class="check-product">
						</label>
					</div>';
        
        $status = '<a data-toggle="modal" href="#id_meliscommerce_order_list_modal_container" %s data-orderid="%s">
				        <span %s class="btn order-status-%s">%s</span>
				   </a>';
        
        
        if($this->getRequest()->isPost()) {
            $colId = array_keys($this->getTool()->getColumns());
            
            $osta_id = $this->getRequest()->getPost('osta_id');
            $startDate = $this->getRequest()->getPost('startDate');
            $startDate = $this->getTool()->localeDateToSql($startDate);
            $endDate = $this->getRequest()->getPost('endDate');
            $endDate = $this->getTool()->localeDateToSql($endDate);
            
            $onlyValid = true;
            if(!empty($osta_id)){
                $onlyValid = null;
            }
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
            
            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];
            $colOrder = $selCol. ' ' . $sortOrder;
            $draw = (int) $this->getRequest()->getPost('draw');
            
            $start = (int) $this->getRequest()->getPost('start');
            $length =  (int) $this->getRequest()->getPost('length');
            
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];
            $clientId = null;
            $couponId = null;
            $postValues = $this->getRequest()->getPost();
            if (isset($postValues['clientId'])) {
                $clientId = ($this->getRequest()->getPost('clientId'))? $this->getRequest()->getPost('clientId'): -1;
            }
            if (isset($postValues['couponId'])) {
                $couponId = ($this->getRequest()->getPost('couponId'))? $this->getRequest()->getPost('couponId'): -1;;
            }
            $tmp = $melisComOrderService->getOrderList(
                $osta_id, $onlyValid, null, $clientId, 
                null, $couponId, null, null, 
                null, null, $search, $startDate, $endDate
                );
            $dataFilter = count($tmp);
            $orderData = $melisComOrderService->getOrderList(
                $osta_id, $onlyValid, $langId, $clientId, 
                null, $couponId, null, $start, 
                $length, $colOrder, $search, $startDate, $endDate
            );
            $dataCount = count($orderData);
            $c = 0;

            foreach($orderData as $order){
                $price = 0;
                $products = 0;
                $company = '';
                $client = array();
                $civt_min_name = '';
                $cper_firstname = '';
                $cper_name = '';
                $disabled = '';
                $class = 'class="updateListStatus"';
                
                $orderStatus = null;
                $statusTrans = $melisComOrderService->getOrderStatusByOrderId($order->getId());
                foreach($statusTrans as $trans){
                    if($trans->ostt_lang_id == $langId){
                        $orderStatus = $trans;
                    }
                }
                
                $orderStatus = empty($orderStatus)? $statusTrans[0] : $orderStatus;
                
                foreach($order->getBasket() as $basket){
                    $products = $products + $basket->obas_quantity;
                }
                
                foreach($order->getPayment() as $payment){
                    $price = $price + $payment->opay_price_total;
                }
                
                $companyObj = $clientCompanyTable->getEntryByField('ccomp_client_id', $order->getClient()->cli_id)->current();
                if(!empty($companyObj)){
                    $company = $companyObj->ccomp_name;
                }
                $client = $order->getPerson();

                if(!empty($client)){
                    
                    foreach($client->civility_trans as $trans){
                        if($trans->civt_lang_id == $langId){
                            $civt_min_name = $trans->civt_min_name;
                        }
                    }
                    
                    $cper_firstname = $client->cper_firstname;
                    $cper_name = $client->cper_name;
                }
                
                if($order->getOrder()->ord_status == $confOrder['cancelled']){
                    $disabled = 'disabled';
                    $class = '';
                }
               
                $tableData[$c]['DT_RowId'] = $order->getId();
                $tableData[$c]['order_table_checkbox'] = sprintf($checkBox, $order->getId());                
                $tableData[$c]['ord_id'] = $order->getId();
                $tableData[$c]['ord_reference'] = $this->getTool()->escapeHtml($order->getOrder()->ord_reference);
                $tableData[$c]['ord_status'] = sprintf($status, $class, $order->getId(), $disabled, $orderStatus->osta_id, $orderStatus->ostt_status_name);
                $tableData[$c]['products'] = number_format($products, 0);
                $tableData[$c]['price'] = number_format($price, 2) . "€";
                $tableData[$c]['ccomp_name'] = $this->getTool()->escapeHtml($company);
                $tableData[$c]['civt_min_name'] = $this->getTool()->escapeHtml($civt_min_name);
                $tableData[$c]['cper_firstname'] = $this->getTool()->escapeHtml($cper_firstname);
                $tableData[$c]['cper_name'] = $this->getTool()->escapeHtml($cper_name);
                $tableData[$c]['ord_date_creation'] = $this->getTool()->dateFormatLocale($order->getOrder()->ord_date_creation);
                $tableData[$c]['last_status_update'] = '';
                
                $c++;
            }
        }

        return new JsonModel(array (
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataFilter,
            'data' => $tableData,
        ));
    }

    public function sendOrdersExportToCsvAction()
    {
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $csvConfig = $melisCoreConfig->getItem('meliscommerce/datas/default/export/csv');
        $csvFileName = $csvConfig['orderFileName'];
        $dir = $csvConfig['dir'];

        $csvData = file_get_contents($dir.$csvFileName);

        $response = new Response();
        $headers  = $response->getHeaders();

        $response->setContent($csvData);

        return $response;

    }

    public function saveOrderStatusAction()
    {
        $response = array();
        $success = false;
        $errors  = array();
        $data = array();
        $clientId = null;
        $orderId = null;
        $textMessage = 'tr_meliscommerce_order_page_save_fail';
        $textTitle = 'tr_meliscommerce_order_page';
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $variantStockTbl = $this->getServiceManager()->get('MelisEcomVariantStockTable');
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        
        $confOrder = $melisCoreConfig->getItem('meliscommerce/conf/orderStatus');
        
        if($this->getRequest()->isPost()){
            $this->getEventManager()->trigger('meliscommerce_order_status_save_start', $this, array());
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);
            $orderId = $postValues['ord_id'];
            unset($postValues['ord_id']);
            $data = $orderSvc->saveOrder($postValues, null, null, null, null, null, $orderId);
            
            //if status is cancelled
            if($postValues['ord_status'] == $confOrder['cancelled']){
                $orderEntity = $orderSvc->getOrderById($orderId);
                $order = $orderEntity->getOrder();
                $basket = $orderEntity->getBasket();   
                
                foreach($basket as $item){
                    $tmp = array();
                    
                    $variantStock = $variantStockTbl->getStocksByVariantId($item->obas_variant_id, $order->ord_country_id)->current();
                    if(!empty($variantStock)){                        
                        $tmp['stock_quantity'] = $item->obas_quantity + $variantStock->stock_quantity;                        
                        $variantSvc->saveVariantStocks($tmp, $variantStock->stock_id);
                    }else{
                        $variantStock = $variantStockTbl->getStocksByVariantId($item->obas_variant_id, 0)->current();                        
                        $tmp['stock_quantity'] = $item->obas_quantity + $variantStock->stock_quantity;
                        $variantSvc->saveVariantStocks($tmp, $variantStock->stock_id);
                    }                   
                }
            }
            
            if(!empty($data)){
                $textMessage = 'tr_meliscommerce_order_page_save_success';
                // Getting Order Client details
                $clientData = $orderSvc->getOrderById($orderId);
                $client = $clientData->getClient();
                $clientId = $client->cli_id;
                $success = true;
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
            'clientId' => $clientId
        );
        
        $this->getEventManager()->trigger('meliscommerce_order_status_save_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_ORDER_STATUS_UPDATE', 'itemId' => $orderId)));
        
        return new JsonModel($response);
    }
    
    public function ordersExportValidateAction()
    {   
        $errors = array();
        $dateErrors = array();
        $permErrors = array();
        $data = array();
        $success = 0;
        $textMessage = 'tr_meliscommerce_order_export_fail';
        $textTitle = 'tr_meliscommerce_order_page';
        
        $tool = $this->getServiceManager()->get('MelisCoreTool');
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_list/meliscommerce_order_list_export_form','meliscommerce_order_list_export_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $orderExportForm = $factory->createForm($appConfigForm);
        
        $csvConfig = $melisCoreConfig->getItem('meliscommerce/datas/default/export/csv');
        $csvFileName = $csvConfig['orderFileName'];
        $dir = $csvConfig['dir'];
        
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];
        $lang = $orderSvc->getEcomLang();
        
        if($this->getRequest()->isPost()){
            $postValues = get_object_vars($this->getRequest()->getPost());
            
            // convert date to generic for date comparison
            if(!empty($postValues['date_start'])){
                $startDate = $postValues['date_start'];
                $startDate = ($locale == 'fr_FR')? str_replace('/','-', $startDate) : $startDate;
            }
            
            if(!empty($postValues['date_end'])){
                $endDate = $postValues['date_end'];
                $endDate = ($locale == 'fr_FR')? str_replace('/','-', $endDate) : $endDate;
            }
            
            if( !empty($postValues['date_start']) && !empty($postValues['date_end'])){
                if(strtotime($startDate) > strtotime($endDate)){
                    $dateErrors['date_end'] = array(
                        'isGreaterThan' => $this->getTool()->getTranslation('tr_meliscommerce_orders_date_end_error'),
                        'label' => $this->getTool()->getTranslation('tr_meliscommerce_orders_date_end'),
                    );
                }
            }
            
            $orderExportForm->setData($postValues);
            if(!$orderExportForm->isValid()){
                $exportError = $orderExportForm->getMessages();
                foreach ($exportError as $keyError => $valueError)
                {
                    foreach ($appConfigForm['elements'] as $keyForm => $valueForm)
                    {
                        if ($valueForm['spec']['name'] == $keyError &&
                            !empty($valueForm['spec']['options']['label']))
                            $exportError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                    }
                }
                $errors = $exportError;

            }
            
            $errors = array_merge($dateErrors, $errors);
            
            // check file access and permission
            
            if(!is_dir($dir)){
                $dirCreate = mkdir($dir);
                if(!$dirCreate){
                    $permErrors['date_end'] = array(
                        'permissionError' => $tool->getTranslation('tr_meliscommerce_general_csv_permission_error', $dir),
                        'label' => $tool->getTranslation('tr_meliscommerce_general_permission_error_label'),
                    );
                }
            }
            
            $content = '';
            if(file_exists($dir)) {
                $test = file_put_contents($dir.$csvFileName, $content, LOCK_EX);
            }
            
            $errors = array_merge($errors, $permErrors);
            
            if(empty($errors)){
                $success = 1;
                $textMessage = 'tr_meliscommerce_order_export_success';
                $data = $orderExportForm->getData();
                
                $data['orderExportEncapse'] = ($postValues['orderExportEncapse'])? '"' : '';
                
                $data['date_start'] = !empty($data['date_start'])? $tool->localeDateToSql($data['date_start']) : $data['date_start'];
                if(!empty($endDate)){
                    $data['date_end'] = $tool->localeDateToSql($data['date_end']);
                    $data['date_end'] = date("Y-m-d", strtotime($data['date_end'] . "+1 days"));
                }
                
                $result = $orderSvc->exportOrderList($data['ord_status'], $data['date_start'], $data['date_end'], $data['separator'], $data['orderExportEncapse'], $lang->elang_id);
                
                if(!$result){
                    $errors[]['unknown'] = array(
                        'permissionError' => 'unknown error',
                        'label' => $tool->getTranslation('tr_meliscommerce_general_permission_error_label'),
                    );
                }
            }
        }
        
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
        );
        return new JsonModel($results);
    }
    
    public function ordersExportToCsvAction()
    {
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $csvConfig = $melisCoreConfig->getItem('meliscommerce/datas/default/export/csv');
        $csvFileName = $csvConfig['orderFileName'];
        $dir = $csvConfig['dir'];
        
        $csvData = file_get_contents($dir.$csvFileName);
        
        $response = new Response();
	    $headers  = $response->getHeaders();
	    $headers->addHeaderLine('Content-Type', 'text/csv; charset=utf-8');
	    $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"".$csvFileName."\"");
	    $headers->addHeaderLine('Accept-Ranges', 'bytes');
	    $headers->addHeaderLine('Content-Length', strlen($csvData));
	    $response->setContent($csvData);
	    
	    return $response;
                
    }
    
    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_order_list');
    
        return $melisTool;
    }


}