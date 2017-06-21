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
use Zend\Stdlib\ArrayUtils;

class MelisComCouponController extends AbstractActionController
{
    /**
     * renders the page container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponPageAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $this->setCouponVariables($couponId);        
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the page header container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponHeaderContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the page header left container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponHeaderLeftContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the page header left container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponHeaderRightContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the page header title
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $couponCode = '';
        if(isset($this->layout()->coupon)){
            $couponCode = $this->layout()->coupon->coup_code;
        }
        $view->melisKey = $melisKey;
        $view->couponCode = $couponCode;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the page header save button
     * @return \Zend\View\Model\ViewModel 
     */
    public function renderCouponHeaderSaveAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the page content container
     * @return \Zend\View\Model\ViewModel 
     */
    public function renderCouponPageContentAction()
    {   
        $coup_type = isset($this->layout()->coupon)? $this->layout()->coupon->coup_type: '';
        $coup_product_assign = isset($this->layout()->coupon)? $this->layout()->coupon->coup_product_assign: '';
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->coup_type = $coup_type;
        $view->product_assign = $coup_product_assign;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the page main content container
     * @return \Zend\View\Model\ViewModel render-coupon-page-tab-main
     */
    public function renderCouponPageTabsMainAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content header container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentHeaderAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content header left container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentHeaderLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content header right container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentHeaderRightAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content header status
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentHeaderStatusAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $status = '';
        if(isset($this->layout()->coupon->coup_status)){
            $status = ($this->layout()->coupon->coup_status)? 'checked': '';
        }
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        $view->status = $status;
        return $view;
    }
    
    /**
     * renders the tabs content header title
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content details container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentDetailsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content details container left
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentDetailsLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content details container right
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentDetailsRightAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content details sub header container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentSubHeaderAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content details sub header container left
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentSubHeaderLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content details sub header container right
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentSubHeaderRightAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content details sub header container title
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentSubHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }
    
    /**
     * renders the tabs content details sub details container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentSubDetailsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        return $view;
    }    
    
   /**
    * renders the coupon form for general data
    * @return \Zend\View\Model\ViewModel
    */
    public function renderCouponFormGeneralDataAction()
    {   
        $generalForm = 'meliscommerce_coupon_general_data';
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_coupon/'.$generalForm,$generalForm);
        $type = '';
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $couponForm = $factory->createForm($appConfigForm);
        if(isset($this->layout()->coupon)){
            $coupon = $this->layout()->coupon;
            $coupon->coup_date_valid_start = (string) $coupon->coup_date_valid_start != '1990-01-01 00:00:00' ? $this->getTool()->dateFormatLocale($coupon->coup_date_valid_start) : null;
            $coupon->coup_date_valid_end = (string) $coupon->coup_date_valid_end != '1990-01-01 00:00:00' ? $this->getTool()->dateFormatLocale($coupon->coup_date_valid_end) : null;
            $couponForm->setData((array)$coupon);
            if($coupon->coup_type){
                $type = 'checked';
            }
            
            if ($coupon->coup_current_use_number){
                $couponForm->get('coup_code')->setAttribute('disabled', 'disabled');
            }
        }
        $datepickerInit = $this->getTool()->datePickerInit('couponStart');
        $datepickerInit .= $this->getTool()->datePickerInit('couponEnd');
        
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        $view->type = $type;
        $view->couponForm = $couponForm;
        $view->datePickerInit = $datepickerInit;
        return $view;
    }
    
    /**
     * renders the coupon from for values
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponFormValuesAction()
    {
        $generalForm = 'meliscommerce_coupon_values';
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_coupon/'.$generalForm,$generalForm);
    
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $couponForm = $factory->createForm($appConfigForm);
        if(isset($this->layout()->coupon)){
            $coupon = $this->layout()->coupon;
            if ($coupon->coup_current_use_number){
                $couponForm->get('coup_percentage')->setAttribute('disabled', 'disabled');
                $couponForm->get('coup_discount_value')->setAttribute('disabled', 'disabled');
            }
            $couponForm->setData((array)$coupon);
           
        }
        $couponForm->get('coup_current_use_number')->setAttribute('disabled','disabled');
        
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->couponId = $couponId;
        $view->couponForm = $couponForm;
        return $view;
    }
    
    /**
     * renders the client list table filter limit
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponContentFilterLimitAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the client list table filter search
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponContentFilterSearchAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the client list table button refresh
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponContentFilterRefreshAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the client list table action button assign
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponContentActionAssignAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the client list table action button assign
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponContentActionAssignProductAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the client list table action button delete
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponContentActionDeleteAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the product list table action button delete
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponContentActionDeleteAssignedProductAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the product list table action button edit client
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponContentActionEditClientAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the client list table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentDetailsAssignTableAction()
    {
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $columns = $this->getTool()->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $tableName = $couponId.'_clientList';
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->couponId = $couponId;
        $view->tableName = $tableName;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#'.$tableName, true, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }
    
    /**
     * renders the client list table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentDetailsAssignProductTableAction()
    {
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_coupon_product_assign');
        $columns = $melisTool->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $tableName = $couponId.'_productList';
        
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->couponId = $couponId;
        $view->tableName = $tableName;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$tableName, true, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }
    
    /**
     * renders the assigned coupon client list table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentDetailsAssignedTableAction()
    {
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_coupon_assigned');
        $columns = $melisTool->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $tableName = $couponId.'_clientListAssigned';
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->couponId = $couponId;
        $view->tableName = $tableName;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$tableName, true, null, array('order' => '[[ 0, "desc" ]]', "initComplete" => 'initCheckClientUsedCoupon()'));
        return $view;
    }
    
    
    /**
     * renders the assigned coupon product list table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentDetailsAssignedProductTableAction()
    {
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_coupon_product_assigned');
        $columns = $melisTool->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $tableName = $couponId.'_productListAssigned';
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->couponId = $couponId;
        $view->tableName = $tableName;
//         $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$tableName, true, null, array('order' => '[[ 0, "desc" ]]', "initComplete" => 'initCheckClientUsedCoupon()'));
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$tableName, true, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }
    
    /** 
     * renders the orders list table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentDetailsOrdersTableAction()
    {
        $status = array();
        $orderStatusTable = $this->getServiceLocator()->get('MelisEcomOrderStatusTable');
        foreach($orderStatusTable->fetchAll() as $orderStatus){
            $status[] = $orderStatus;
        }
        
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_coupon_order');
        $columns = $melisTool->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $tableName = $couponId.'_couponOrderList';
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->couponId = $couponId;
        $view->tableName = $tableName;
        $view->status = $status;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$tableName, true, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }
    
    public function getCouponClientDataAction()
    {
        $success = 0;
        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $tableData = array();
        $couponId = null;
        $dataFiltered = 0;
        
        $langId = $this->getTool()->getCurrentLocaleID();
        $clientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
        $clientSvc = $this->getServiceLocator()->get('MelisComClientService');
        $couponClientTable = $this->getServiceLocator()->get('MelisEcomCouponClientTable');
        if($this->getRequest()->isPost()) {
            $colId = array_keys($this->getTool()->getColumns());
            $couponId = $this->getRequest()->getPost('couponId');
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
            
            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];
            if($selCol == 'assign'){
                $selCol = 'ccli_coupon_id';
            }
            
            $colOrder = $selCol. ' ' . $sortOrder;
            
            $draw = (int) $this->getRequest()->getPost('draw');
            
            $start = (int) $this->getRequest()->getPost('start');
            $length =  (int) $this->getRequest()->getPost('length');
            
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];
            $isMain = 1;
            
            $tmp = $clientTable->getCouponClientList(null, null, $isMain, null, null, null, $colOrder, $search);
            $dataFiltered = count($tmp);
            
            $clients = $clientTable->getCouponClientList($langId, null, $isMain, null, $start, $length, $colOrder, $search);
            
            $c = 0;
            foreach($clients as $client){
                
                $assign = '';
                $companyName = '';
                $client = $clientSvc->getClientByIdAndClientPerson($client->cli_id);                

                foreach($couponClientTable->getEntryByField('ccli_coupon_id',$couponId) as $coupon){
                    if($coupon->ccli_client_id == $client->getClient()->cli_id ){
                        $assign = '<a data-ccli_id="'.$client->getId().'" class="btn btn-success" style="cursor:default" title="'.$this->getTool()->getTranslation('tr_meliscommerce_coupon_page_tabs_assign_hover').'" ><i class="fa fa-user" ></i></a>';
                    } 
                }
                
                foreach($client->getCompany() as $company){
                    $companyName = $company->ccomp_name;
                }
                
                $tableData[$c]['DT_RowId']      = $client->getClient()->cli_id;
                $tableData[$c]['cli_id']        = $client->getClient()->cli_id;
                $tableData[$c]['cper_firstname']= $this->getTool()->escapeHtml($client->getPersons()[0]->cper_firstname);
                $tableData[$c]['cper_name']     = $this->getTool()->escapeHtml($client->getPersons()[0]->cper_name);
                $tableData[$c]['cper_email']    = $this->getTool()->escapeHtml($client->getPersons()[0]->cper_email);
                $tableData[$c]['ccomp_name']    = $this->getTool()->escapeHtml($companyName);
                $tableData[$c]['assign']        = $assign;
                $tableData[$c]['DT_RowClass']   = (!empty($assign)) ? 'couponNoAddButton' : '';
                $c++;
            }
            $dataCount = $c;
        }
        return new JsonModel(array (
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataFiltered,
            'data' => $tableData,
        ));
    }
    
    public function getAssignedCouponClientDataAction()
    {
        $success = 0;
        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $tableData = array();
        $couponId = null;
        $dataFiltered = 0;
    
        $langId = $this->getTool()->getCurrentLocaleID();
        $clientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
        $clientSvc = $this->getServiceLocator()->get('MelisComClientService');
        $couponClientTable = $this->getServiceLocator()->get('MelisEcomCouponClientTable');
        $couponOrderTable = $this->getServiceLocator()->get('MelisEcomCouponOrderTable');
        
        if($this->getRequest()->isPost()) {
            $colId = array_keys($this->getTool()->getColumns());
            $couponId = $this->getRequest()->getPost('couponId');
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
    
            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];
            if($selCol == 'assign'){
                $selCol = 'ccli_coupon_id';
            }
    
            $colOrder = $selCol. ' ' . $sortOrder;
    
            $draw = (int) $this->getRequest()->getPost('draw');
    
            $start = (int) $this->getRequest()->getPost('start');
            $length =  (int) $this->getRequest()->getPost('length');
    
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];
            $isMain = 1;
    
            $tmp = $clientTable->getCouponClientList(null, null, $isMain, $couponId, null, null, $colOrder, $search);
            $dataFiltered = count($tmp);
    
            $clients = $clientTable->getCouponClientList($langId, null, $isMain, $couponId, $start, $length, $colOrder, $search);
    
            $c = 0;
            foreach($clients as $client){
                $assign = '';
                $companyName = '';
                $client = $clientSvc->getClientByIdAndClientPerson($client->cli_id);
    
                
                $usedCoupon = $couponOrderTable->checkUsedClientCoupon($couponId, $client->getClient()->cli_id)->toArray();
                if(count($usedCoupon) > 0){
                    $tableData[$c]['DT_RowClass'] = "couponAssigned";
                }
                
    
                foreach($client->getCompany() as $company){
                    $companyName = $company->ccomp_name;
                }
    
                $tableData[$c]['DT_RowId']      = $client->getClient()->cli_id;
                $tableData[$c]['DT_RowAttr']    = array('data-couponid' => $couponId);
                $tableData[$c]['cli_id']        = $client->getClient()->cli_id;
                $tableData[$c]['cper_firstname']= $this->getTool()->escapeHtml($client->getPersons()[0]->cper_firstname);
                $tableData[$c]['cper_name']     = $this->getTool()->escapeHtml($client->getPersons()[0]->cper_name);
                $tableData[$c]['cper_email']    = $this->getTool()->escapeHtml($client->getPersons()[0]->cper_email);
                $tableData[$c]['ccomp_name']    = $this->getTool()->escapeHtml($companyName);
                $tableData[$c]['assign']        = $assign;
                $c++;
            }
            $dataCount = $c;
        }
        return new JsonModel(array (
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataFiltered,
            'data' => $tableData,
        ));
    }
    
    public function getCouponProductDataAction()
    {
        $success = 0;
        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $tableData = array();
        $couponId = null;
        $dataFiltered = 0;
    
        if($this->getRequest()->isPost()) {
            $success = 0;
            $prodTable = $this->getServiceLocator()->get('MelisEcomProductTable');
            $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
            $couponSvc= $this->getServiceLocator()->get('MelisComCouponService');
            $categorySvc = $this->getServiceLocator()->get('MelisComCategoryService');
            $docSvc = $this->getServiceLocator()->get('MelisComDocumentService');
            $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
            $toolTipTable = $viewHelperManager->get('ToolTipTable');
            
            $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
            $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_coupon_product_assigned');
            
            $columns = array();
            $dataCount = 0;
            $filterCount = 0;
            $draw = 0;
            $tableData = array();
            $assigns = array();
            
            if($this->getRequest()->isPost()) {
                $couponId = ($this->getRequest()->getPost('couponId'))? $this->getRequest()->getPost('couponId') : null;
                $assign = ($this->getRequest()->getPost('assign'))? $this->getRequest()->getPost('assign') : null;
            
                $columns = array_keys($melisTool->getColumns());
            
                $sortOrder = $this->getRequest()->getPost('order');
                $sortOrder = $sortOrder[0]['dir'];
            
                $order = $this->getRequest()->getPost('order');
                $selColOrder = $columns[$order[0]['column']];
                $colOrder = $selColOrder. ' ' . $sortOrder;
                
                $search = $this->getRequest()->getPost('search');
                $search = $search['value'];
                
                $draw = (int) $this->getRequest()->getPost('draw');
            
                $start = (int) $this->getRequest()->getPost('start');
                $length =  (int) $this->getRequest()->getPost('length');
            
                $search = $this->getRequest()->getPost('search');
                $search = $search['value'];
                
                
                if(is_null($assign)){
                    
                    $tmp = $couponSvc->getCouponProductList(null, $assign, null, null, null, null);
                    $prodData = $couponSvc->getCouponProductList(null, $assign, $start, $length, $colOrder, $search);
                    $assign = $couponSvc->getCouponProductList($couponId, 1, $start, $length, $colOrder, $search);
                    
                    foreach($assign as $a){
                        $assigns[] = $a->getProduct()->prd_id;
                    }
                }else{
                    $tmp = $couponSvc->getCouponProductList($couponId, $assign, null, null, null, null);
                    $prodData = $couponSvc->getCouponProductList($couponId, $assign, $start, $length, $colOrder, $search);
                }
                $dataCount = count($tmp);
               
                $checkBox = '<div class="checkbox checkbox-single margin-none" data-product-id="%s">
							<label class="checkbox-custom">
								<i class="fa fa-fw fa-square-o"></i>
								<input type="checkbox" class="check-product">
							</label>
						</div>';
                $prodActive     = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
                $prodInactive   = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
                $prodImage      = '<img src="%s" width="60" height="60" class="img-rounded img-responsive"/>';
                $categoryDom    = '<span class="cell-val-table" style="margin:0 2px 4px 0;display:inline-block;padding: 3px 10px;background: #ECEBEB;border-radius: 4px;color: #7D7B7B;">%s</span>';
                $toolTipTextTag = '<a id="row-%s" class="toolTipHoverEvent tooltipTable" data-productId="%s" data-hasqtip="1" aria-describedby="qtip-%s">%s</a>';
                // PRODUCT DETAILS
                $ctr = 0;
                $variantSvc = $this->getServiceLocator()->get('MelisComVariantService');
                
                foreach($prodData as $prod) {
                    $prodText = $prodSvc->getProductName($prod->getProduct()->prd_id, $this->getTool()->getCurrentLocaleID());
                    $prodText = $this->getTool()->escapeHtml($prodText);
                    $assignData = '';
                    if(in_array($prod->getProduct()->prd_id, $assigns)){
                        $tableData[$ctr]['DT_RowClass']   = (!empty($assign)) ? 'couponProductNoAddButton' : '';
                    }
                    
                    $tableData[$ctr]['DT_RowData'] = array('productname', $prodText);
                    $tableData[$ctr]['DT_RowId'] = $prod->getProduct()->prd_id;
                    $tableData[$ctr]['product_table_checkbox'] = sprintf($checkBox, $prod->getProduct()->prd_id);
                    $tableData[$ctr]['prd_id'] = '<span data-productname="'.$prodText.'">'.$prod->getProduct()->prd_id . '</span>';
                    $tableData[$ctr]['assign'] = $assignData;
                    $tableData[$ctr]['prd_status'] = $prod->getProduct()->prd_status ? $prodActive : $prodInactive;
                    $tableData[$ctr]['product_image'] = sprintf($prodImage, $docSvc->getDocDefaultImageFilePath('product', $prod->getProduct()->prd_id));
            
            
                    $tableData[$ctr]['product_categories'] = '';
                    foreach($prod->getCategories() as $prodCat){
                        $cat     = $categorySvc->getCategoryById($prodCat->pcat_cat_id, $this->getTool()->getCurrentLocaleID());
                        $catName = $categorySvc->getCategoryNameById($prodCat->pcat_cat_id, $this->getTool()->getCurrentLocaleID());
                        $tableData[$ctr]['product_categories'] .= sprintf($categoryDom, $this->getTool()->escapeHtml($catName));
                    }
            
                    $toolTipTable->setTable('couponProductTable'.$prod->getProduct()->prd_id, 'table-row-'.($ctr+1). ' ' . 'couponProductTable'.$prod->getProduct()->prd_id, '');
                    $toolTipTable->setColumns($this->getToolTipColumns());
            
                    $prodTextData = $prod->getTexts();
            
            
                     
                    // Detect if Mobile remove qTipTable
                    $useragent=$_SERVER['HTTP_USER_AGENT'];
                    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
                        $tableData[$ctr]['product_name'] = sprintf($toolTipTextTag, ($ctr+1), $prod->getProduct()->prd_id, ($ctr+1), $prodText);
                    } else {
                        $tableData[$ctr]['product_name'] = sprintf($toolTipTextTag, ($ctr+1), $prod->getProduct()->prd_id, ($ctr+1), $prodText) . $toolTipTable->render();
                    }
            
            
                    $ctr++;
            
                }
            
                $filterCount = count($tableData);
            
            }
            
            return new JsonModel(array (
                'draw' => (int) $draw,
                'recordsTotal' => $dataCount,
                'recordsFiltered' => $dataCount, //$prodSvc->getTotalFiltered(),
                'data' => $tableData,
            ));
        }
        return new JsonModel(array (
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataFiltered,
            'data' => $tableData,
        ));
    }
    
    /**
     * This method saves or deletes a coupon assigned to a client
     * @return \Zend\View\Model\JsonModel
     */
    public function couponClientManagementAction()
    {
        $response = array();
        $success = false;
        $errors  = array();
        $data = array();
        $couponId = null;
        
        $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_coupon_save_fail');
        $textTitle = $this->getTool()->getTranslation('tr_meliscommerce_coupon_page');
        $this->getEventManager()->trigger('meliscommerce_coupon_client_management_start', $this, array());
        $couponSvc = $this->getServiceLocator()->get('MelisComCouponService');
        $couponTable = $this->getServiceLocator()->get('MelisEcomCouponClientTable');
        
        if($this->getRequest()->isPost()){
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            if($postValues['method'] == 'add'){
                unset($postValues['method']);
                $couponId = $postValues['ccli_coupon_id'];
                $check = $couponTable->checkCouponClientExist($postValues['ccli_coupon_id'], $postValues['ccli_client_id'])->current();
                if(empty($check)){
                    $result = $couponSvc->saveCouponClient($postValues);
                }else{
                    $result = true;
                }
            }            
        }
        
        if($result){
            $success = 1;
            $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_coupon_save_success');
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        
        $this->getEventManager()->trigger('meliscommerce_coupon_client_management_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_COUPON_ASSIGN', 'itemId' => $couponId)));
        return new JsonModel($response);
    }
    
    
    /**
     * This method saves or deletes a coupon assigned to a product
     * @return \Zend\View\Model\JsonModel
     */
    public function couponProductManagementAction()
    {
        $response = array();
        $success = false;
        $errors  = array();
        $data = array();
        $couponId = null;
        
        $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_coupon_save_fail');
        $textTitle = $this->getTool()->getTranslation('tr_meliscommerce_coupon_page');
        $this->getEventManager()->trigger('meliscommerce_coupon_client_management_start', $this, array());
        $couponSvc = $this->getServiceLocator()->get('MelisComCouponService');
        $couponTable = $this->getServiceLocator()->get('MelisEcomCouponProductTable');
        
        if($this->getRequest()->isPost()){
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            if($postValues['method'] == 'add'){
                unset($postValues['method']);
                $couponId = $postValues['cprod_coupon_id'];
                $check = $couponTable->checkCouponProductExist($postValues['cprod_coupon_id'], $postValues['cprod_product_id'])->current();
                if(empty($check)){
                    $result = $couponSvc->saveCouponProduct($postValues);
                }else{
                    $result = true;
                }
            }else{
                unset($postValues['method']);
                $result = $couponTable->deleteCouponproduct($postValues['cprod_coupon_id'], $postValues['cprod_product_id']);
                if($result){
                    $result = true;
                }
            }
        }
        
        if($result){
            $success = 1;
            $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_coupon_save_success');
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        
        $this->getEventManager()->trigger('meliscommerce_coupon_client_management_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_COUPON_ASSIGN', 'itemId' => $couponId)));
        return new JsonModel($response);
    }
    
    /**
     * This method saves the coupon
     * @return \Zend\View\Model\JsonModel
     */
    public function saveCouponDataAction()
    {

        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();
        $couponId = null;
        $exist = false;
        $result = false;
        $logTypeCode = '';
        $textMessage = 'tr_meliscommerce_coupon_save_fail';
        $textTitle = 'tr_meliscommerce_coupon_page';        
        $couponSvc = $this->getServiceLocator()->get('MelisComCouponService');
        $this->getEventManager()->trigger('meliscommerce_coupon_save_start', $this, array());
        $couponTable = $this->getServiceLocator()->get('MelisEcomCouponTable');
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $generalFormConfig = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_coupon/meliscommerce_coupon_general_data','meliscommerce_coupon_general_data');
        $generalForm = $factory->createForm($generalFormConfig);
        $valuesFormConfig = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_coupon/meliscommerce_coupon_values','meliscommerce_coupon_values');
        $valuesForm = $factory->createForm($valuesFormConfig);
        
        if($this->getRequest()->isPost()){
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues, array('coup_code'));
            
            if (!empty($postValues['couponId'])){
                $logTypeCode = 'ECOM_COUPON_UPDATE';
            }else{
                $logTypeCode = 'ECOM_COUPON_ADD';
            }
            
            $couponData = array();
            $couponIsUsed = false;
            if (!empty($postValues['couponId'])){
                $couponData = $couponTable->getEntryById($postValues['couponId'])->current();
                if (!empty($couponData)){
                    if ($couponData->coup_current_use_number > 0){
                        $couponIsUsed = true;
                        $generalForm->getInputFilter()->remove('coup_code');
                    }
                }
            }
            
            $generalForm->setData($postValues['coupon'][0]);
            if(!$generalForm->isValid()){
                $errors = array_merge($errors, $this->getFormErrors($generalForm, $generalFormConfig));
            }
            $coupon = $generalForm->getData();
            if ($couponIsUsed){
                $coupon = ArrayUtils::merge($coupon, array('coup_code' => $couponData->coup_code));
            }
            
            if (!$couponIsUsed){
                if(empty($postValues['couponValues'][0]['coup_percentage']) && empty($postValues['couponValues'][0]['coup_discount_value'])){
                    $title = $this->getTool()->getTranslation('tr_meliscommerce_coupon_tabs_content_values_header_title');
                    $message = $this->getTool()->getTranslation('tr_meliscommerce_coupon_empty_percentage_discount');
                    $errors = array_merge($errors, array('values' => array('isEmpty' => $message , 'label' => $title )));
                }
            }
            
            if ($couponIsUsed){
                $valuesForm->getInputFilter()->remove('coup_percentage');
                $valuesForm->getInputFilter()->remove('coup_discount_value');
            }
            
            $valuesForm->setData($postValues['couponValues'][0]);
            if(!$valuesForm->isValid()){
                $errors = array_merge($errors, $this->getFormErrors($valuesForm, $valuesFormConfig));
            }
            $coupon = array_merge($coupon, $valuesForm->getData());
            if ($couponIsUsed){
                $couponVal = array(
                    'coup_percentage' => $couponData->coup_percentage,
                    'coup_discount_value' => $couponData->coup_discount_value,
                );
                $coupon = ArrayUtils::merge($coupon, $couponVal);
            }
            
            $couponId = $postValues['couponId'];                       
            $coupon['coup_code'] = mb_strtoupper($this->str_without_accents($coupon['coup_code']));
            $coupon['coup_percentage'] = !empty($coupon['coup_percentage'])? $coupon['coup_percentage']:null;
            $coupon['coup_discount_value'] = !empty($coupon['coup_discount_value'])? $coupon['coup_discount_value']:null;
            
            $coupon['coup_max_use_number'] = is_numeric($coupon['coup_max_use_number'])? $coupon['coup_max_use_number']:null;
            $coupon['coup_date_valid_start'] = $this->getTool()->localeDateToSql($coupon['coup_date_valid_start']);
            $coupon['coup_date_valid_end'] = $this->getTool()->localeDateToSql($coupon['coup_date_valid_end']);
            
            $validFrom = $coupon['coup_date_valid_start'];
            $validTo = $coupon['coup_date_valid_end'];
            
            // date validation if valid start and end
            if(!empty($validFrom) && !empty($validTo)){
                if($validTo < $validFrom){
                    $title = $this->getTool()->getTranslation('tr_meliscommerce_coupon_date_end');
                    $message = $this->getTool()->getTranslation('tr_meliscommerce_coupon_invalid_end_date');
                    $errors['coup_date_valid_end'] = array('inValidDate' => $message ,'label' => $title);
                    $success = 0;
                }
            }
            
            //set date created on new coupons
            if(empty($couponId)){
                $coupon['coup_date_creation'] = date("Y-m-d H:i:s");
                $coupon['coup_user_id'] = $this->getTool()->getCurrentUserId();
            }
            
            // check if coupon code already exist with a different ID
            $exist = $couponTable->getEntryByField('coup_code', $coupon['coup_code'])->current();
             
            if($exist){
                if($exist->coup_id != $coupon['coup_id']){
                    $errorTitle   = $this->getTool()->getTranslation('tr_meliscommerce_coupon_list_col_code');
                    $errorMessage = $this->getTool()->getTranslation('tr_meliscommerce_coupon_code_duplicate_error_message');
                    $errors = array_merge($errors, array( 'coup_code' => array( 'label' => $errorTitle, 'isExist' => $errorMessage)));                    
                }
                
                if($exist->coup_current_use_number > $coupon['coup_max_use_number']){
                    $errorTitle   = $this->getTool()->getTranslation('tr_meliscommerce_coupon_max');
                    $errorMessage = $this->getTool()->getTranslation('tr_meliscommerce_coupon_code_current_use_error');
                    $errors = array_merge($errors, array( 'coup_max_use_number' => array( 'label' => $errorTitle, 'invalid' => $errorMessage)));
                }
            }
            
            if(!$errors){
                
                $coupon = array_merge($coupon, $postValues['switch']);
                unset($coupon['coup_id']);
                unset($coupon['coup_current_use_number']);                
                
                $result = $couponSvc->saveCoupon($coupon,$couponId);
            }
            
            if($result){
                $couponId = $result;
                $success = 1;
                $coupon = $couponSvc->getCouponById($result)->getCoupon();
                $data['couponId'] = $result;
                $data['coup_code'] = $coupon->coup_code;
                $textMessage = 'tr_meliscommerce_coupon_save_success';
            }            
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        
        $this->getEventManager()->trigger('meliscommerce_coupon_save_end', 
            $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $couponId)));
        
        return new JsonModel($response);
    }
    
    public function deleteAssignedCouponAction()
    {   
        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();
        $couponId = null;
        $this->getEventManager()->trigger('meliscommerce_coupon_remove_from_client_start', $this, array());
        $textMessage = 'tr_meliscommerce_coupon_delete_fail_remove';
        $textTitle = 'tr_meliscommerce_coupon_list_page_coupon';
        $couponClientTable = $this->getServiceLocator()->get('MelisEcomCouponClientTable');
        
        if($this->getRequest()->isPost()){
            $postValues = get_object_vars($this->getRequest()->getPost());
            $couponId = $postValues['couponId'];
            if($couponClientTable->removeCouponFromClient($couponId, $postValues['clientId'])){
                $success = 1;
                $textMessage = 'tr_meliscommerce_coupon_delete_success_remove';
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        
        $this->getEventManager()->trigger('meliscommerce_coupon_remove_from_client_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_COUPON_ASSIGN_REMOVE', 'itemId' => $couponId)));
        
        return new JsonModel($response);
    }
    
    public function getToolTipColumns()
    {
        $columns = array(
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_id') => array(
                'class' => 'center',
                //'rowspan' => '2',
                'style' => 'width:10px;',
            ),
            ' ' => array(
                'class' => 'center',
                //'rowspan' => '2',
                'style' => 'width:50px;',
            ),
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_image') => array(
                'class' => 'center',
                //'rowspan' => '2',
                'style' => 'width:100px;',
            ),
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_sku') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),
    
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_attributes') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),
    
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_country') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_price') => array(
                'class' => 'text-right',
                //'rowspan' => '2',
                'style' => 'width:100px;',
            ),
    
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_stocks') => array(
                'class' => 'text-right',
                //'rowspan' => '2',
                'style' => 'width:20px;',
            ),
    
        );
    
        return $columns;
    }
    
    /**
     * Returns the translation text
     * @param String $key
     * @param array $args
     * @return string
     */
    private function getTranslation($key, $args = null)
    {
        $translator = $this->getServiceLocator()->get('translator');
        $text = vsprintf($translator->translate($key), $args);
    
        return $text;
    }
    
    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_coupon');
    
        return $melisTool;
    
    }
    
    /**
     * sets the coupon data to the layout
     * @param unknown $couponId
     */
    private function setCouponVariables($couponId)
    {
        $layoutVar = array();
        $couponSvc = $this->getServiceLocator()->get('MelisComCouponService');
        if($couponId){
            $resultData = $couponSvc->getCouponById($couponId);
            $layoutVar['coupon'] = $resultData->getCoupon();
        }
        $this->layout()->setVariables( array_merge( array(
            'couponId' => $couponId,
        ), $layoutVar));
    }
    
    /**
     * Retrieves  form errors
     * @param object $form the form object
     * @param object $formConfig the app config of the form
     * @return errors[] | null
     */
    private function getFormErrors($form, $formConfig)
    {
        $appConfigFormElements = $formConfig['elements'];
        $errors = $form->getMessages();

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
        return $errors;
    }
    
    private function str_without_accents($str, $charset='utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
        return $str;   // or add this : mb_strtoupper($str); for uppercase :)
    }
    

    /**
     * Retrieves  form errors
     * @param object $form the form object
     * @param object $formConfig the app config of the form
     * @return errors[] | null
     */
//     private function getFormErrors($form, $formConfig)
//     {
//         $errors = array();
//         foreach ($form->getMessages() as $keyError => $valueError){
//             foreach ($formConfig['elements'] as $keyForm => $valueForm){
//                 if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label'])){
//                     $key = $valueForm['spec']['options']['label'];
//                     $errors[$key] = $valueError;
//                 }
//             }
//         }
//         return $errors;
//     }
}