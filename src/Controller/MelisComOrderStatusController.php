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
use MelisCore\Controller\MelisAbstractActionController;

class MelisComOrderStatusController extends MelisAbstractActionController
{
    /**
     * renders the order status page container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusPageAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order status page header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusHeaderContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order status page left header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusHeaderLeftContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order status page right header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusHeaderRightContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order status page header title
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order status add button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusAddAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order status page content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusContentAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the order status content table filter limit
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusContentFilterLimitAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order status content table filter search
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusContentFilterSearchAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order status content table filter refresh
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusContentFilterRefreshAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order status content table action info
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusContentActionInfoAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order status content table action delete
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusContentActionDeleteAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the order list page table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusContentTableAction()
    {
    
        $columns = $this->getTool()->getColumns();
        $columns['actions'] = array('text' => 'Action');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#tableOrderStatus', true, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }
    
    /**
     * renders the order status modal container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderOrderStatusModalAction()
    {
        $id = $this->params()->fromQuery('id');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->id = $id;
        $view->setTerminal(true);
        return $view;
    }
    
    public function renderOrderStatusFormAction()
    {
        $langTable = $this->getServiceManager()->get('MelisEcomLangTable');
        
        $langs = array();
        $forms = array();
        $statusTrans = array();
        $orderStatus = array();
        $isPrime = 0;
        $status = '';
        $color = '#bd3131';
        $modalName = 'tr_meliscommerce_order_status_form_new';
        
        $statusId = (int) $this->params()->fromQuery('ostaId', '');
        
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        
        if(!empty($statusId)){
            $modalName = 'tr_meliscommerce_order_status_form_edit';
            $orderStatus = $orderSvc->getOrderStatus($statusId);
            if(!empty($orderStatus)){
                $color = $orderStatus->osta_color_code;
                $status = ($orderStatus->osta_status)? 'checked' : '';
                
                $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
                $primeStatus = $melisCoreConfig->getItem('meliscommerce/datas/default/permanent_order_status');
                if(in_array($orderStatus->osta_id, $primeStatus)){
                    $isPrime = 1;
                }
            }
        }
        
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_status/meliscommerce_order_status_form','meliscommerce_order_status_form');
        
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $orderStatusForm = $factory->createForm($appConfigForm);
        
        $orderStatusForm->setData((array) $orderStatus);
        
        $appConfigFormTrans = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_status/meliscommerce_order_status_trans_form','meliscommerce_order_status_trans_form');
        $orderTransForm = $factory->createForm($appConfigFormTrans);
        
        //form used for filling all values
        $fillAllForm = clone($orderTransForm);
        
        foreach($langTable->langOrderByName() as $lang){
        
            $form = clone($orderTransForm);
        
            //auto populate lang id
            $langData['ostt_lang_id'] = $lang->elang_id;
            $form->setData($langData);
        
            //set the form data
            if(!empty($orderStatus->trans)){
                foreach($orderStatus->trans as $val){
                    if($val->ostt_lang_id == $lang->elang_id){
                        $form->setData((array)$val);
                    }
                }
            }
            
            $forms[] = $form;
            $langs[] = $lang;
        }

        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->statusForm = $orderStatusForm;
        $view->forms = $forms;
        $view->fillAllForm = $fillAllForm;
        $view->langs = $langs;
        $view->color = $color;
        $view->statusId = $statusId;
        $view->status = $status;
        $view->isPrime = $isPrime;
        $view->modalName = $modalName;
        return $view;
    }

    public function deleteAllOrderStatusAction()
    {
        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();

        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );


        return new JsonModel($response);
    }

    public function getOrderStatusDataAction()
    {
        $success = 0;
        $dataCount = 0;
        $dataFilter = 0;
        $tableData = array();
        $primeStatus = array();
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $primeStatus = $melisCoreConfig->getItem('meliscommerce/datas/default/permanent_order_status');
        
        if($this->getRequest()->isPost()) {
            $lang = $orderSvc->getEcomLang();
            
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
            
            $tmp = $orderSvc->getOrderStatuses();
            
            $dataFilter = count($tmp);
            
            $orderStatusData = $orderSvc->getOrderStatuses( null, null, $start, $length, $colOrder, $search);
            
            $dataCount = count($orderStatusData);
            
            $c = 0;
            
            $divSwatch = '<div class="swatch" style="background-color:%s"></div>';
            
            foreach($orderStatusData as $data){
                
                $statusName = '';
                
                foreach($data->trans as $trans){
                    if($trans->ostt_lang_id == $lang->elang_id){
                        $statusName = $trans->ostt_status_name;
                    }
                }
                
                if(empty($statusName)){
                    foreach($data->trans as $trans){
                        if($trans->ostt_lang_id == 1){
                            $statusName = $trans->ostt_status_name;
                        }
                    }
                }
                
                $status = ($data->osta_status)? '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>' : '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
                
                $tableData[$c]['DT_RowId'] = $data->osta_id;
                $tableData[$c]['osta_id'] = $data->osta_id;
                $tableData[$c]['osta_status'] = $status;
                $tableData[$c]['osta_color_code'] = $data->osta_color_code;
                $tableData[$c]['ostt_status_name'] = $statusName;
                $tableData[$c]['color_preview'] = sprintf($divSwatch, $data->osta_color_code);
                $tableData[$c]['DT_RowClass'] = (in_array($data->osta_id, $primeStatus)) ? 'primeStatus' : '';
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
    
    public function saveOrderStatusAction()
    {
        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();
        $filledUp = 0;
        $orderStatusTrans = array();
        $orderStatusNames = array();
        
        $textMessage = 'tr_meliscommerce_order_status_save_failed';
        $textTitle = 'tr_meliscommerce_order_status_tool_leftmenu';
        
        $this->getEventManager()->trigger('meliscommerce_order_status_save_start', $this, array());
        
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_status/meliscommerce_order_status_form','meliscommerce_order_status_form');
        
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $orderStatusForm = $factory->createForm($appConfigForm);
        
        $appConfigFormTrans = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_order_status/meliscommerce_order_status_trans_form','meliscommerce_order_status_trans_form');
        
        if($this->getRequest()->isPost()){
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);
            
            $orderStatusData = $postValues['order_status'][0];
            
            $orderStatusForm->setData($orderStatusData);
            
            if(!$orderStatusForm->isValid()){
                $formError = $orderStatusForm->getMessages();
                foreach ($formError as $keyError => $valueError)
                {
                    foreach ($appConfigForm['elements'] as $keyForm => $valueForm)
                    {
                        if ($valueForm['spec']['name'] == $keyError &&
                            !empty($valueForm['spec']['options']['label']))
                            $formError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                    }
                }
                $errors = array_merge($errors, $formError);
            }
            
            // check if the first form is filled(all fill form)
            $fillAllForm = array_shift($postValues['order_trans']);
            $translationForms = $postValues['order_trans'];
            if(!empty(array_filter($fillAllForm))){
                // check if translation forms are filled up
                foreach($translationForms as $trans){
                    foreach($trans as $key => $val){
                        if($key == 'ostt_status_name' && !empty($val)){
                            $filledUp = 1;
                        }
                    }
                }
                
                // if filled up throw error
                if($filledUp){
                    $errors = array_merge(
                        $errors, array(
                            'ostt_status_name' => array(
                                'isFilled' => $this->getTool()->getTranslation('tr_meliscommerce_order_status_status_filled_all_error'),
                                'label' => $this->getTool()->getTranslation('tr_meliscommerce_order_status_col_ord_status'),
                            )
                        )
                    );
                }else{
                    $orderTransForm = $factory->createForm($appConfigFormTrans);
                    $orderTransForm->setData($fillAllForm);
                    
                    if(!$orderTransForm->isValid()){
                        $formError = $orderTransForm->getMessages();
                        foreach ($formError as $keyError => $valueError)
                        {
                            foreach ($appConfigForm['elements'] as $keyForm => $valueForm)
                            {
                                if ($valueForm['spec']['name'] == $keyError &&
                                    !empty($valueForm['spec']['options']['label']))
                                    $formError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                            }
                        }
                        $errors = array_merge($errors, $formError);
                    }else{
                        $langTable = $this->getServiceManager()->get('MelisEcomLangTable');
                        $data = $orderTransForm->getData();
                        
                        foreach($translationForms as $form){
                            $form['ostt_status_name'] = $data['ostt_status_name'];
                            $orderStatusNames = $data['ostt_status_name'];
                            $orderStatusTrans[] = $form;
                        }
                    }
                }
                
                
            }else{
                foreach($translationForms as $trans){
                    $orderTransForm = $factory->createForm($appConfigFormTrans);
                    $transLangId = $trans['ostt_lang_id'];
                    unset($trans['ostt_lang_id']);
                    
                    if(array_filter($trans)){
                        $trans['ostt_lang_id'] = $transLangId;
                        $orderTransForm->setData($trans);
                        
                        if(!$orderTransForm->isValid()){
                            $formError = $orderTransForm->getMessages();
                            foreach ($formError as $keyError => $valueError)
                            {
                                foreach ($appConfigForm['elements'] as $keyForm => $valueForm)
                                {
                                    if ($valueForm['spec']['name'] == $keyError &&
                                        !empty($valueForm['spec']['options']['label']))
                                        $formError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                                }
                            }
                            $errors = array_merge($errors, $formError);
                        }else{
                            $data = $orderTransForm->getData();
                            $orderStatusNames = $data['ostt_status_name'];
                            $orderStatusTrans[] = $data;
                        }
                    }
                }
            }
            
            //if translations are empty
            if(empty($orderStatusNames) && empty($errors)){
                $errors = array_merge(
                    $errors, array(
                        'ostt_status_name' => array(
                            'isFilled' => $this->getTool()->getTranslation('tr_meliscommerce_order_status_status_filled_all_error'),
                            'label' => $this->getTool()->getTranslation('tr_meliscommerce_order_status_col_ord_status'),
                        )
                    )
                );
            }
           
            // save status if there are no errors
            if(empty($errors)){
                $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
                $orderStatus = $orderStatusForm->getData();
                $orderStatus['osta_status'] = $orderStatusData['osta_status'];
               $id =  $orderSvc->saveOrderStatus($orderStatus, $orderStatusTrans, $postValues['statusId']);
               if($id){
                   $success = 1;
                   $textMessage = 'tr_meliscommerce_order_status_save_success';
               }
            }
            
//             echo '<pre>'; print_r($errors); echo '</pre>'; die();
            
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        
        $this->getEventManager()->trigger('meliscommerce_order_status_save_end', $this, $response);
        return new JsonModel($response);
    }
    
    /**
     * This method deletes order status that are not permanent statuses
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function deleteOrderStatusAction()
    {
        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();
        $orderStatusId = null;
        $textMessage = 'tr_meliscommerce_coupon_delete_fail';
        $textTitle = 'tr_meliscommerce_order_status_tool_leftmenu';
    
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        if($this->getRequest()->isPost()){
            $this->getEventManager()->trigger('meliscommerce_order_status_delete_start', $this, array());
            $postValues = $this->getRequest()->getPost()->toArray();
            $orderStatusId = $postValues['ostaId'];
            if($orderSvc->deleteOrderStatusById($orderStatusId)){
                $success = 1;
                $textMessage = 'tr_meliscommerce_order_status_delete_success';
            }
        }
    
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
    
        $this->getEventManager()->trigger('meliscommerce_order_status_delete_end',
            $this, array_merge($response, array('typeCode' => 'ECOM_ORDER_STATUS_DELETE', 'itemId' => $orderStatusId)));
    
        return new JsonModel($response);
    }
    
    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_order_status');
    
        return $melisTool;
    }

}