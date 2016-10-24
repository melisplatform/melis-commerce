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

class MelisComOrderListController extends AbstractActionController
{
    /**
     * renders the order list page container
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListContentTableAction()
    {
        $columns = $this->getTool()->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#tableOrderList', true);
        return $view;
    }
    
    /**
     * renders the order list content table filter bulk
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListContentFilterBulkAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table filter limit
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListContentFilterLimitAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table filter search
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListContentFilterSearchAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table filter grid view
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListContentFilterGridViewAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table filter list view
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListContentFilterListViewAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table filter refresh
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListContentFilterRefreshAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list content table action info
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListContentActionInfoAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list modal container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListModalAction()
    {   
        $id = $this->params()->fromQuery('id');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->id = $id;
        $view->setTerminal(false);
        return $view;
    }
    
    /**
     * renders the order list add new order button
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListAddOrderAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list modal for updating order status
     * @return \Zend\View\Model\ViewModel
     */
    public function renderOrderListContentStatusFormAction()
    {
        $orderId = (int) $this->params()->fromQuery('orderId');
        $melisComOrderService = $this->getServiceLocator()->get('MelisComOrderService');
//         $tabId = $this->params()->fromQuery('tabId');
        
        $view = new ViewModel();
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_list/meliscommerce_order_list_status_form','meliscommerce_order_list_status_form');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);        
        $updateStatusForm = $factory->createForm($appConfigForm);
        $order = $melisComOrderService->getOrderStatusByOrderId($orderId, $this->getTool()->getCurrentLocaleID())[0];
        $updateStatusForm->setData((array)$order);

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->order = $order;
        $view->setVariable('meliscommerce_order_list_status_form', $updateStatusForm);
        return $view;
    }
    
    public function getOrderListDataAction()
    {
        $success = 0;
        $melisComOrderService = $this->getServiceLocator()->get('MelisComOrderService');
        $clientCompanyTable = $this->getServiceLocator()->get('MelisEcomClientCompanyTable');
        $clientPersonTable = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
        $civilityTransTable = $this->getServiceLocator()->get('MelisEcomCivilityTransTable');
        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $tableData = array();
        
        $langId = $this->getTool()->getCurrentLocaleID();
        $checkBox = '<div class="checkbox checkbox-single margin-none" data-order-id="%s">
						<label class="checkbox-custom">
							<i class="fa fa-fw fa-square-o"></i>
							<input type="checkbox" class="check-product">
						</label>
					</div>';
        
        $status = '<a data-toggle="modal" href="#id_meliscommerce_order_list_modal_container" class="updateListStatus">
				        <span class="btn btn-default" style="border-color: %s;border-radius: 4px; color:%s;">%s</span>
				   </a>';
        
        
        
        if($this->getRequest()->isPost()) {
            $colId = array_keys($this->getTool()->getColumns());
            
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
            
            $orderData = $melisComOrderService->getOrderList($langId, $clientId, null, $couponId, null, null, null, null, $start, $length, $colOrder, $search);
            $dataCount = count($orderData);
            $c = 0;
//             echo '<pre>'; print_r($orderData); echo '</pre>'; die();
            foreach($orderData as $order){
                $price = 0;
                $company = '';
                $client = array();
                $civt_min_name = '';
                $cper_firstname = '';
                $cper_name = '';
                
                $orderStatus = $melisComOrderService->getOrderStatusByOrderId($order->getId(), $langId)[0];
                foreach($order->getPayment() as $payment){
                    $price = $price + $payment->opay_price_total;
                }
                $companyObj = $clientCompanyTable->getEntryByField('ccomp_client_id', $order->getClient()->cli_id)->current();
                if(!empty($companyObj)){
                    $company = $companyObj->ccomp_name;
                }
                $client = $clientPersonTable->getEntryByField('cper_client_id', $order->getClient()->cli_id)->current();
                if(!empty($client)){
                    $civt_min_name = $civilityTransTable->getCivilityTransByCivilityId($client->cper_civility, $langId)->current()->civt_min_name;
                    $cper_firstname = $client->cper_firstname;
                    $cper_name = $client->cper_name;
                }
                $tableData[$c]['DT_RowId'] = $order->getId();
                $tableData[$c]['order_table_checkbox'] = sprintf($checkBox, $order->getId());                
                $tableData[$c]['ord_id'] = $order->getId();
                $tableData[$c]['ord_reference'] = $order->getOrder()->ord_reference;
                $tableData[$c]['ord_status'] = sprintf($status, $orderStatus->osta_color_code, $orderStatus->osta_color_code, $orderStatus->ostt_status_name);
                $tableData[$c]['products'] = count($order->getBasket());
                $tableData[$c]['price'] = $price;
                $tableData[$c]['ccomp_name'] = $company;
                $tableData[$c]['civt_min_name'] = $civt_min_name;
                $tableData[$c]['cper_firstname'] = $cper_firstname;
                $tableData[$c]['cper_name'] = $cper_name;
                $tableData[$c]['ord_date_creation'] = $this->getTool()->dateFormatLocale($order->getOrder()->ord_date_creation, ' h:i');
                $tableData[$c]['last_status_update'] = '';
                
                $c++;
            }
        }

        return new JsonModel(array (
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataCount,
            'data' => $tableData,
        ));
    }
    
    public function saveOrderStatusAction()
    {
        $response = array();
        $success = false;
        $errors  = array();
        $data = array();
        $clientId = null;
        $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_order_page_save_fail');
        $textTitle = $this->getTool()->getTranslation('tr_meliscommerce_order_page');
        $orderSvc = $this->getServiceLocator()->get('MelisComOrderService');
        if($this->getRequest()->isPost()){
            $this->getEventManager()->trigger('meliscommerce_order_status_save_start', $this, array());
            $postValues = get_object_vars($this->getRequest()->getPost());
            $orderId = $postValues['ord_id'];
            unset($postValues['ord_id']);
            $data = $orderSvc->saveOrder($postValues, null, null, null, null, null, $orderId);
            
            if(!empty($data['ord_id'])){
                $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_order_page_save_success');
                
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
        $this->getEventManager()->trigger('meliscommerce_order_status_save_end', $this, $response);
        return new JsonModel($response);
    }
    
    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_order_list');
    
        return $melisTool;
    
    }
   
}