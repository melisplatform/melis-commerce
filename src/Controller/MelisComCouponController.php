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
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $couponId = (int) $this->params()->fromQuery('couponId', '');
        $view->melisKey = $melisKey;
        $view->coup_type = $coup_type;
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
            $coupon->coup_date_valid_start = $this->getTool()->dateFormatLocale($coupon->coup_date_valid_start);
            $coupon->coup_date_valid_end = $this->getTool()->dateFormatLocale($coupon->coup_date_valid_end);
            $couponForm->setData((array)$coupon);
            if($coupon->coup_type){
                $type = 'checked';
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
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#'.$tableName, true);
        return $view;
    }
    
    /** 
     * renders the orders list table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCouponTabsContentDetailsOrdersTableAction()
    {
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
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('#'.$tableName, true);
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
        
        $status = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';        
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
            
            $clients = $clientTable->getCouponClientList($langId, null, null, $start, $length, $colOrder, $search);
            
            $c = 0;
            foreach($clients as $client){
                $assign = '';
                
                $client = $clientSvc->getClientByIdAndClientPerson($client->cli_id);                
//                 echo '<pre>'; print_r($client->getCompany()[0]); echo '</pre>'; die();
                if($client->getClient()->cli_status){
                    $status = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
                }
                foreach($couponClientTable->getEntryByField('ccli_coupon_id',$couponId) as $coupon){
                    if($coupon->ccli_client_id == $client->getClient()->cli_id ){
                        $assign = '<a data-ccli_id="'.$client->getId().'" class="btn btn-success" style="cursor:default"><i class="fa fa-user" title="Given"></i></a>';
                    } 
                }
                
                foreach($client->getPersons()[0]->civility_trans as $trans){
                    if($trans->civt_lang_id == $langId){
                        $civt_min_name = $trans->civt_min_name;
                    }
                }
                foreach($client->getCompany() as $company){
                    $companyName = $company->ccomp_name;
                }
                
                $tableData[$c]['DT_RowId']      = $client->getClient()->cli_id;
                $tableData[$c]['cli_id']        = $client->getClient()->cli_id;
                $tableData[$c]['cli_status']    = $status;
                $tableData[$c]['civt_min_name'] = $civt_min_name;
                $tableData[$c]['cper_firstname']= $client->getPersons()[0]->cper_firstname;
                $tableData[$c]['cper_name']     = $client->getPersons()[0]->cper_name;
                $tableData[$c]['cper_email']    = $client->getPersons()[0]->cper_email;
                $tableData[$c]['ccomp_name']    = $companyName;
                $tableData[$c]['assign']        = $assign;
                $c++;
            }
            $dataCount = $c;
        }
        return new JsonModel(array (
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataCount,
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
        
        if($this->getRequest()->isPost()){
            $postValues = get_object_vars($this->getRequest()->getPost());
//              echo '<pre>'; print_r($postValues); echo '</pre>';die();
            if($postValues['method'] == 'add'){
                unset($postValues['method']);
                $result = $couponSvc->saveCouponClient($postValues);
                
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
        $this->getEventManager()->trigger('meliscommerce_coupon_client_management_end', $this, array());
        return new JsonModel($response);
    }
    
    /**
     * This method saves the coupon
     * @return \Zend\View\Model\JsonModel
     */
    public function saveCouponDataAction()
    {

        $response = array();
        $success = false;
        $errors  = array();
        $data = array();
        $couponId = null;
        $exist = false;
        $result = false;
        $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_coupon_save_fail');
        $textTitle = $this->getTool()->getTranslation('tr_meliscommerce_coupon_page');        
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
            
            $generalForm->setData($postValues['coupon'][0]);
            if(!$generalForm->isValid()){
                $errors = array_merge($errors, $this->getFormErrors($generalForm, $generalFormConfig));
            }
            $coupon = $generalForm->getData();
            
            $valuesForm->setData($postValues['couponValues'][0]);
            if(!$valuesForm->isValid()){
                $errors = array_merge($errors, $this->getFormErrors($valuesForm, $valuesFormConfig));
            }
            $coupon = array_merge($coupon, $valuesForm->getData());
            
            $couponId = $postValues['couponId'];                       
            
            $coupon['coup_percentage'] = !empty($coupon['coup_percentage'])? $coupon['coup_percentage']:null;
            $coupon['coup_discount_value'] = !empty($coupon['coup_discount_value'])? $coupon['coup_percentage']:null;
            $coupon['coup_date_valid_start'] = $this->getTool()->localeDateToSql($coupon['coup_date_valid_start']);
            $coupon['coup_date_valid_end'] = $this->getTool()->localeDateToSql($coupon['coup_date_valid_end']);
            
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
                    $errors = array_merge($errors, array( $errorTitle => $errorMessage));                    
                }                
            }
            
            if(!$errors){
                $coupon = array_merge($coupon, $postValues['switch']);
                unset($coupon['coup_id']);
                unset($coupon['coup_current_use_number']);
                $result = $couponSvc->saveCoupon($coupon,$couponId);
            }
            
            if($result){
                $success = true;
                $coupon = $couponSvc->getCouponById($result)->getCoupon();
                $data['couponId'] = $result;
                $data['coup_code'] = $coupon->coup_code;
                $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_coupon_save_success');
            }            
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        $this->getEventManager()->trigger('meliscommerce_coupon_save_end', $this, $response);
        return new JsonModel($response);
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
        $errors = array();
        foreach ($form->getMessages() as $keyError => $valueError){
            foreach ($formConfig['elements'] as $keyForm => $valueForm){
                if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label'])){
                    $key = $valueForm['spec']['options']['label'];
                    $errors[$key] = $valueError;
                }
            }
        }
        return $errors;
    }
}