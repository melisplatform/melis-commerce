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
        $orderCount = $melisComOrderService->getOrderList(null, true, null, null, null, null, null,  null,
            null, null, '', null, null, true);
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->num = $orderCount->total ?? 0;
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
        $viewHelperManager = $this->getServiceManager()->get('ViewHelperManager');
        $toolTipTable = $viewHelperManager->get('ToolTipTable');
        
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
                null, null, $search, $startDate, $endDate, true
            );

            $dataFilter = $tmp->total ?? 0;
            $orderData = $melisComOrderService->getOrderList(
                $osta_id, $onlyValid, $langId, $clientId, 
                null, $couponId, null, $start, 
                $length, $colOrder, $search, $startDate, $endDate
            );
            $dataCount = $melisComOrderService->getOrderList(
                $osta_id, $onlyValid, $langId, $clientId,
                null, $couponId, null, $start,
                $length, $colOrder, $search, $startDate, $endDate, true
            );
            $dataCount = $dataCount->total ?? 0;
            $c = 0;

            $toolTipTextTag = '<a id="row-%s" class="clientOrderRefToolTipHoverEvent tooltipTable" data-orderId="%s" data-hasqtip="1" aria-describedby="qtip-%s">%s</a>';
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
                $tableData[$c]['ord_status'] = sprintf($status, $class, $order->getId(), $disabled, $orderStatus->osta_id, $orderStatus->ostt_status_name);
                $tableData[$c]['products'] = number_format($products, 0);
                $tableData[$c]['price'] = number_format($price, 2) . "€";
                $tableData[$c]['ccomp_name'] = $this->getTool()->escapeHtml($company);
                $tableData[$c]['civt_min_name'] = $this->getTool()->escapeHtml($civt_min_name);
                $tableData[$c]['cper_firstname'] = $this->getTool()->escapeHtml($cper_firstname);
                $tableData[$c]['cper_name'] = $this->getTool()->escapeHtml($cper_name);
                $tableData[$c]['ord_date_creation'] = $this->getTool()->dateFormatLocale($order->getOrder()->ord_date_creation);
                $tableData[$c]['last_status_update'] = '';
                //for the tooltip of the reference order                
                $toolTipTable->setTable('orderBasketTable'.$order->getId(), 'table-row-'.($c+1). ' ' . 'orderBasketTable'.$order->getId(), 'cursor:pointer;');
                $toolTipTable->setColumns($this->getToolTipColumns());
                // Detect if Mobile remove qTipTable
                $useragent=$_SERVER['HTTP_USER_AGENT'];
                if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
                {
                    $tableData[$c]['ord_reference'] = sprintf($toolTipTextTag, ($c+1), $order->getId(), ($c+1), $this->getTool()->escapeHtml($order->getOrder()->ord_reference));
                } 
                else 
                {
                    $tableData[$c]['ord_reference'] = sprintf($toolTipTextTag, ($c+1), $order->getId(), ($c+1), $this->getTool()->escapeHtml($order->getOrder()->ord_reference)) . $toolTipTable->render();
                }
                
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
    /**
     * Returns the translation text
     * @param String $key
     * @param array $args
     * @return string
     */
    private function getTranslation($key, $args = []) 
    {
        $translator = $this->getServiceManager()->get('translator');
        $text = vsprintf($translator->translate($key), $args);
        return $text;
    }
    /**
     * Returns the array of columns used in the basket tooltip table 
     * @return Laminas\View\Model\JsonModel
     */  
    private function getToolTipColumns()
    {
        $columns = array(
            $this->getTranslation('tr_meliscommerce_order_list_col_id') => array(
                'class' => 'center',               
                'style' => 'width:10px;',
            ),
            $this->getTranslation('tr_meliscommerce_order_list_col_image') => array(
                'class' => 'center',                
                'style' => 'width:100px;',
            ),
            $this->getTranslation('tr_meliscommerce_order_basket_list_name') => array(
                'class' => 'text-left',                
            ),
            $this->getTranslation('tr_meliscommerce_order_basket_list_sku') => array(
                'class' => 'text-left',                
            ),
            $this->getTranslation('tr_meliscommerce_order_basket_list_qty') => array(
                'class' => 'text-left',                
            ),
            $this->getTranslation('tr_meliscommerce_order_list_col_price') => array(
                'class' => 'text-left',                
            ),          
        );
        return $columns;
    }
    /**
     * Returns the basket data of the given order id which will be displayed in the tooltip table 
     * @return Laminas\View\Model\JsonModel
     */      
    public function getOrderBasketToolTipAction()
    {
        $content = array();
        if($this->getRequest()->isPost()) {
            $orderId = (int) $this->getRequest()->getPost('orderId');
            $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
            $docService = $this->getServiceManager()->get('MelisComDocumentService');
            $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
            $prodSvc = $this->getServiceManager()->get('MelisComProductService');
            //retrieve the basket given the order id           
            $basketList = $orderSvc->getOrderBasketByOrderId($orderId);
            $imageDom = '<img src="%s" width="60" class="rounded-circle"/>';
            $viewHelperManager = $this->getServiceManager()->get('ViewHelperManager');
            $table = $viewHelperManager->get('ToolTipTable');
            $langId = $this->getTool()->getCurrentLocaleID();
            if ($basketList) {
                $sContent = '';
                foreach($basketList as $basket) {
                    $sContent = '';
                    // TBODY START
                    $sContent .= $table->getBody();
                    $sContent .= $table->openTableRow();
                    // ID
                    $sContent .= $table->setRowData($basket->obas_id, array('class' => 'center'));                    
                    // IMAGE
                    $imageSrc = '';  
                    /*get variant image first*/
                    if (!empty($basket->obas_variant_id)) {
                        $imageSrc = $docService->getDocDefaultImageFilePath('variant', $basket->obas_variant_id);
                    }
                    /*if no variant image then get product image*/
                    if (empty($imageSrc)) {                                  
                        $var = $variantSvc->getVariantById($basket->obas_variant_id, $this->getTool()->getCurrentLocaleID());                    
                        $prod = $prodSvc->getProductById($var->getVariant()->var_prd_id, $this->getTool()->getCurrentLocaleID());                    
                        $imageSrc = $docService->getDocDefaultImageFilePath('product', $prod->getId());                       
                    }                        
                    $image = sprintf($imageDom, $imageSrc);
                    $sContent .= $table->setRowData($image, array('class' => 'center'));
                    //Name
                    $sContent .= $table->setRowData($this->getTool()->escapeHtml($basket->obas_product_name), array('class' => 'center'));
                    //SKU
                    $sContent .= $table->setRowData($this->getTool()->escapeHtml($basket->obas_sku), array('class' => 'center'));
                    //QTY
                    $sContent .= $table->setRowData($basket->obas_quantity, array('class' => 'center'));
                    //PRICE
                    $sContent .= $table->setRowData($basket->obas_price_net . "€", array('class' => 'center'));
                    $content[] = $sContent;                    
                }
            }            
        }
        return new JsonModel(array(
            'content' => $content
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
            $postValues = $this->getRequest()->getPost()->toArray();
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
            $postValues = $this->getRequest()->getPost()->toArray();
            
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