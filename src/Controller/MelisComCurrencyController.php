<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use MelisCore\Controller\MelisAbstractActionController;

class MelisComCurrencyController extends MelisAbstractActionController
{
    public function renderCurrencyContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        
        return $view;
    }
    
    public function renderCurrencyHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->title = $this->getTool()->getTitle();
        return $view;
    }
    
    public function renderCurrencyHeaderAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
    
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->title = $this->getTool()->getTitle();
        return $view;
    }
    
    public function renderCurrencyContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        
        $columns = $this->getTool()->getColumns();

        $columns['action'] =  array(
            'text' => $this->getTool()->getTranslation('tr_meliscommerce_product_list_col_action'),
            'css' => array('width' => '10%')
        );
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration(null, null, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }
    
    public function renderCurrencyTableFilterLimitAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCurrencyTableFilterSearchAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCurrencyTableFilterRefreshAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCurrencyContentActionDefaultAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCurrencyContentActionEditAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCurrencyContentActionDeleteAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCurrencyModalContainerAction()
    {
        $id = $this->params()->fromRoute('id', $this->params()->fromQuery('id', ''));
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        // $view->setTerminal(true);
        $view->melisKey = $melisKey;
        $view->id = $id;
        return $view;
    }
    
    public function renderCurrencyModalFormAction()
    {
        $id = $this->params()->fromRoute('curId', $this->params()->fromQuery('curId', ''));
        $saveType = $this->params()->fromRoute('saveType', $this->params()->fromQuery('saveType', ''));
    
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $title = $this->getTool()->getTranslation('tr_meliscommerce_currency_form_new');
        $data = array();

       
        $form = $this->getTool()->getForm('meliscommerce_currency_form');
        $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
    
        if(is_numeric($id)) {
            $title = $this->getTool()->getTranslation('tr_meliscommerce_currency_form_edit');
            $data = (array) $currencyTable->getEntryById((int) $id)->current();
        }
    
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->id = $id;
        $view->title = $title;
        $view->form = $form;
        $view->data = $data;
        $view->saveType = $saveType;
        return $view;
    }
    
    public function getComCurrencyDataAction()
    {
        $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
        
        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $tableData = array();
        
        if($this->getRequest()->isPost()) {
        
            $colId = array_keys($this->getTool()->getColumns());
        
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
        
            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];
        
            $draw = $this->getRequest()->getPost('draw');
        
            $start = $this->getRequest()->getPost('start');
            $length =  $this->getRequest()->getPost('length');
        
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];
        
            $dataCount =  $currencyTable->getTotalData();
        
            $getData =  $currencyTable->getPagedData(array(
                'where' => array(
                    'key' => 'cur_id',
                    'value' => $search,
                ),
                'order' => array(
                    'key' => $selCol,
                    'dir' => $sortOrder,
                ),
                'start' => $start,
                'limit' => $length,
                'columns' => $this->getTool()->getSearchableColumns(),
                'date_filter' => array()
            ));
        
            $tableData = $getData->toArray();
            $activeDom     = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
            $inactiveDom   = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
            for($ctr = 0; $ctr < count($tableData); $ctr++)
            {
                // apply text limits
                foreach($tableData[$ctr] as $vKey => $vValue)
                {
                    $tableData[$ctr][$vKey] = $this->getTool()->limitedText($this->getTool()->escapeHtml($vValue));
        
                }
        
                // manually modify value of the desired row
                // add DataTable RowID, this will be added in the <tr> tags in each rows
                $tableData[$ctr]['DT_RowId'] = $tableData[$ctr]['cur_id'];

                $status = (int) $tableData[$ctr]['cur_status'];
                $tableData[$ctr]['cur_status'] = $status ? $activeDom : $inactiveDom;
        
                $tableData[$ctr]['cur_default'] = ($tableData[$ctr]['cur_default']) ? '<i class="fa fa-star"></i>' : '';
                $tableData[$ctr]['DT_RowClass'] = ($tableData[$ctr]['cur_default']) ? 'defaultEcomCurrency' : '';
            }
        
        }
        
        
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' => $currencyTable->getTotalFiltered(),
            'data' => $tableData,
        ));
    }
    
    public function saveAction()
    {
        $success = 0;
        $errors  = array();
        $curId = null;
        $logTypeCode = '';
        $textTitle = 'tr_meliscommerce_currency_form_new';
        $textMessage = 'tr_meliscommerce_currency_form_add_fail';
        $form = $this->getTool()->getForm('meliscommerce_currency_form');
        $isExistsError['ctry_name'] = array(
            'countryNameExists' => $this->getTool()->getTranslation('tr_meliscommerce_currency_form_code_already_exists'),
            'label' => $this->getTool()->getTranslation('tr_meliscommerce_currency_code'),
        );
        if($this->getRequest()->isPost()) {
            $hasErrorFlag = false;
            $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
    
            $postData = $this->getRequest()->getPost()->toArray();
            $postData = $this->getTool()->sanitizePost($postData);
            $this->getEventManager()->trigger('meliscommerce_currency_save_start', $this, $postData);
            if($postData['cur_id']) {
                $textTitle = 'tr_meliscommerce_currency_form_edit';
                $textMessage = 'tr_meliscommerce_currency_form_edit_fail';
                $logTypeCode = 'ECOM_CURRENCY_UPDATE';
            }else{
                $logTypeCode = 'ECOM_CURRENCY_ADD';
            }
    
            $tmpCode = $postData['tmp_cur_code'];
            $code = $postData['cur_code'];
            $saveType = $postData['saveType'];
            $currencyCodeData = $currencyTable->getEntryByField('cur_code', $code)->current();
    
            $form->setData($postData);
            if($form->isValid()) {
                $data = $form->getData();
                $curId = $data['cur_id'];
                // unset data
                unset($data['tmp_cur_code']);
                unset($data['saveType']);
                $data['cur_status'] = (int) $postData['cur_status'];
                // for adding a new country
                if($currencyCodeData && $saveType == 'new') {
                    $hasErrorFlag = true;
                    $errors = $isExistsError;
                    
                }
    
                // accept update if name wasn't change
                if($tmpCode == $code && $saveType == 'edit') {
                    $hasErrorFlag = false;
                }
                elseif($saveType == 'edit' && empty($currencyCodeData)) {
                    $hasErrorFlag = false;
                }
                elseif(!empty($currencyCodeData) && $saveType == 'edit') {
                    $hasErrorFlag = true;
                    $errors = $isExistsError;
                }
    
                if(!$hasErrorFlag){
                    unset($data['cur_id']);
                    $tmpCurId = $curId;
                    $curId = $currencyTable->save($data, $curId);
                    if($curId) {
                        $success = 1;
                        if($tmpCurId) {
                            $textMessage = 'tr_meliscommerce_currency_form_edit_success'; 
                        }
                        else {
                            $textMessage = 'tr_meliscommerce_currency_form_add_success';
                        }
                    }
                }
            }
            else {
                $errors = $form->getMessages();
            }
    
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getItem('meliscommerce/tools/meliscommerce_currency/forms/meliscommerce_currency_form');
            $appConfigForm = $appConfigForm['elements'];
    
            foreach ($errors as $keyError => $valueError)
            {
                foreach ($appConfigForm as $keyForm => $valueForm)
                {
                    if ($valueForm['spec']['name'] == $keyError &&
                        !empty($valueForm['spec']['options']['label']))
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                }
            }
        }
    
        $response = array(
            'success' => $success,
            'errors' => $errors,
            'textMessage' => $textMessage,
            'textTitle' => $textTitle,
        );
    
        $this->getEventManager()->trigger('meliscommerce_currency_save_end', 
            $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $curId)));
    
        return new JsonModel($response);
    }
    
    public function deleteAction()
    {
        $this->getEventManager()->trigger('meliscommerce_currency_delete_start', $this, array());
        $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
        $textMessage = 'tr_meliscommerce_currency_delete_failed';
    
        $id = null;
        $success = 0;
    
        if($this->getRequest()->isPost())
        {
            $id = (int) $this->getRequest()->getPost('id');
            if(is_numeric($id))
            {
                $currencyData = $currencyTable->getEntryById($id)->current();
                if($currencyData)
                {
                    $currencyTable->deleteById($id);
                    $textMessage = 'tr_meliscommerce_currency_delete_successful';
                    $success = 1;
                }
            }
        }
    
        $response = array(
            'textTitle' => 'tr_meliscommerce_currency_delete_currency',
            'textMessage' => $textMessage,
            'success' => $success
        );
        
        $this->getEventManager()->trigger('meliscommerce_currency_delete_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_CURRENCY_DELETE', 'itemId' => $id, 'currencyId' => $id)));
    
        return new JsonModel($response);
    }
    
    public function setDefaultCurrencyAction()
    {
        $this->getEventManager()->trigger('meliscommerce_currency_set_default_start', $this, array());
        $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
        $textMessage = 'tr_meliscommerce_currency_set_default_failed';
        
        $id = null;
        $success = 0;
        
        if($this->getRequest()->isPost())
        {
            $id = $this->getRequest()->getPost('id');
            if(is_numeric($id))
            {
                $defaultCurrencyData = $currencyTable->getEntryByField('cur_default','1')->current();
                $currencyData = $currencyTable->getEntryById($id)->current();
                
                if (!empty($defaultCurrencyData) && !empty($currencyData))
                {
                    $currencyTable->save(array('cur_default' => '0'), $defaultCurrencyData->cur_id);
                    $currencyTable->save(array('cur_default' => '1'), $currencyData->cur_id);
                    
                    $textMessage = 'tr_meliscommerce_currency_set_default_success';
                    $success = 1;
                }
            }
        }
        
        $response = array(
            'textTitle' => 'tr_meliscommerce_currency_set_default',
            'textMessage' => $textMessage,
            'success' => $success
        );
        
        $this->getEventManager()->trigger('meliscommerce_currency_set_default_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_CURRENCY_SET_DEFAULT', 'itemId' => $id, 'currencyId' => $id)));
        
        return new JsonModel($response);
    }
    
    private function getTool()
    {
        $tool = $this->getServiceManager()->get('MelisCoreTool');
        $tool->setMelisToolKey('meliscommerce', 'meliscommerce_currency');
    
        return $tool;
    }

    public function getDefaultCurrencyAction()
    {
        $this->getEventManager()->trigger('meliscommerce_currency_set_default_start', $this, array());
        $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
        $textMessage = 'tr_meliscommerce_currency_set_default_failed';

        $id = null;
        $success = 0;


        $response = array(
            'textTitle' => 'tr_meliscommerce_currency_set_default',
            'textMessage' => $textMessage,
            'success' => $success
        );


        return new JsonModel($response);
    }

    /**
     * This will check if the currency is being used by a country
     * @param $currencyId
     */
    public function getCountriesUsingCurrencyAction()
    {
        $currencyId = $this->params()->fromPost('currencyId', null);

        $currencyTable = $this->getServiceManager()->get('MelisComCurrencyService');
        $countries = $currencyTable->getCountriesUsingCurrency($currencyId);

        $response = array(
            'countries' => $countries,
        );

        return new JsonModel($response);
    }
}