<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
class MelisComLanguageController extends AbstractActionController
{


    public function renderLanguageListPageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        
        return $view;
    }
    
    public function renderLanguageListPageHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->title = $this->getTool()->getTitle();
        return $view;
    }
    
    public function renderLanguageListPageHeaderAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
                
        $view = new ViewModel();
        $view->melisKey = $melisKey;

        return $view;
    }
    
    public function renderLanguageListPageContentAction()
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
    
    public function renderLanguageListPageTableFilterLimitAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderLanguageListPageTableFilterSearchAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderLanguageListPageTableFilterRefreshAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderLanguageListPageContentActionDeleteAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderLanguageListPageContentActionEditAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderLanguageListPageModalContainerAction()
    {
        $id = $this->params()->fromRoute('id', $this->params()->fromQuery('id', ''));
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->setTerminal(false);
        $view->melisKey = $melisKey;
        $view->id = $id;
        return $view;
    }
    
    public function renderLanguageListPageModalFormAction()
    {
        $id = $this->params()->fromRoute('langId', $this->params()->fromQuery('langId', ''));
        $saveType = $this->params()->fromRoute('saveType', $this->params()->fromQuery('saveType', ''));
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $title = $this->getTool()->getTranslation('tr_meliscommerce_language_add');
        $data = array();
        
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/tools/meliscommerce_language/forms/meliscommerce_language_form','meliscommerce_language_form');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $form = $factory->createForm($appConfigForm);
        $langTable = $this->getServiceLocator()->get('MelisEcomLang');
        
        if($id) {
            $title = $this->getTool()->getTranslation('tr_meliscommerce_language_edit');
            $data = (array) $langTable->getEntryById((int) $id)->current();
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
    
    
    private function getTool()
    {
        $tool = $this->getServiceLocator()->get('MelisCoreTool');
        $tool->setMelisToolKey('meliscommerce', 'meliscommerce_language');
        
        return $tool;
    }
    
    
    public function getComLangDataAction()
    {
        $langTable = $this->getServiceLocator()->get('MelisEcomLang');

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
        
            $dataCount = $langTable->getTotalData();
        
            $getData = $langTable->getPagedData(array(
                'where' => array(
                    'key' => 'elang_id',
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
                    $tableData[$ctr][$vKey] = $this->getTool()->limitedText($vValue);
                }
        
                // manually modify value of the desired row
                // no specific row to be modified
        
        
                // add DataTable RowID, this will be added in the <tr> tags in each rows
                $tableData[$ctr]['DT_RowId'] = $tableData[$ctr]['elang_id'];
                $status = (int) $tableData[$ctr]['elang_status'];
                $tableData[$ctr]['elang_status'] = $status ? $activeDom : $inactiveDom;
            }
        
        }
        

        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $langTable->getTotalFiltered(),
            'data' => $tableData,
        ));
    }
    
    public function saveAction()
    {
        $success = 0;
        $errors  = array();
        $textTitle = 'tr_meliscommerce_language_add';
        $textMessage = 'tr_meliscommerce_language_add_failed';
        $form = $this->getTool()->getForm('meliscommerce_language_form');
        $isLocaleExistsError['elang_locale'] = array(
            'localeExists' => $this->getTool()->getTranslation('tr_meliscommerce_language_locale_exists'),
            'label' => $this->getTool()->getTranslation('tr_meliscommerce_language_elang_locale'),
        );
        $isNameExistsError['elang_name'] = array(
            'localeExists' => $this->getTool()->getTranslation('tr_meliscommerce_Language_name_exists'),
            'label' => $this->getTool()->getTranslation('tr_meliscommerce_language_elang_name'),
        );
        $savedLangId = null;
        
        if($this->getRequest()->isPost()) {
            $hasErrorFlag = false;
            $isNameChecked = false;
            $langTable = $this->getServiceLocator()->get('MelisEcomLangTable');

            $postData = get_object_vars($this->getRequest()->getPost());
            $this->getEventManager()->trigger('meliscommerce_language_save_start', $this, $postData);
            if($postData['elang_id']) {
                $textTitle = 'tr_meliscommerce_language_edit';
                $textMessage = 'tr_meliscommerce_language_edit_failed';
            }
            
            $isLocaleExists = $langTable->getEntryByField('elang_locale', $postData['elang_locale'])->current();

            $form->setData($postData);
            if($form->isValid()) {

                $data = $form->getData();
                $langId = $data['elang_id'];
                $tmpName = $data['tmp_elang_name'];
                $tmpLocale = $data['tmp_elang_locale'];
                $saveType = $postData['saveType'];

                unset($data['tmp_elang_name']);
                unset($data['tmp_elang_locale']);
                unset($data['saveType']);
                unset($data['elang_id']);
                $data['elang_status'] = (int) $postData['elang_status'];
                // for adding a new language
                if($isLocaleExists && $saveType == 'new') {
                    $hasErrorFlag = true;
                    $errors = $isLocaleExistsError;
                }
                
                
                // accept update if name wasn't change
                if($tmpLocale == $data['elang_locale'] && $saveType == 'edit') {
                    $hasErrorFlag = false;
                }
                elseif($saveType == 'edit' && empty($isLocaleExists)) {
                    $hasErrorFlag = false;
                }
                elseif(!empty($isLocaleExists) && $saveType == 'edit') {
                    $hasErrorFlag = true;
                    $errors = $isLocaleExistsError;
                }

                if(!$hasErrorFlag) {

                    if($langId) {
                        if($langTable->save($data, $langId)) {
                            $success = 1;
                            $textMessage = 'tr_meliscommerce_language_edit_success';
                        }
                        
                    }
                    else {
                        $savedLangId = $langTable->save($data);
                        if($savedLangId) {
                            $success = 1;
                            $textMessage = 'tr_meliscommerce_language_add_success';
                        }
                    }
                }
            }
            else {
                $errors = $form->getMessages();
            }
            
            $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getItem('meliscommerce/tools/meliscommerce_language/forms/meliscommerce_language_form');
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
            'textMessage' => $this->getTool()->getTranslation($textMessage),
            'textTitle' => $this->getTool()->getTranslation($textTitle),
            'langId' => $savedLangId,
        );
        
        $this->getEventManager()->trigger('meliscommerce_language_save_end', $this, $response);
        
        return new JsonModel($response);
    }
    
    public function deleteAction()
    {
        $response = array();
        $this->getEventManager()->trigger('meliscommerce_language_delete_start', $this, $response);
        $langTable = $this->getServiceLocator()->get('MelisEcomLangTable');
        $textMessage = 'tr_meliscore_tool_language_delete_failed';
        
        $id = 0;
        $success = 0;
        $lang = '';
        
        if($this->getRequest()->isPost())
        {
            $id = $this->getRequest()->getPost('id');
            if(is_numeric($id))
            {
                $langData = $langTable->getEntryById($id)->current();
                if($langData)
                {
                    $langTable->deleteById($id);
                    $textMessage = 'tr_meliscore_tool_language_delete_success';
                    $success = 1;
                }
            }
        }
        
        $response = array(
            'textTitle' => $this->getTool()->getTranslation('tr_meliscommerce_language_delete'),
            'textMessage' => $this->getTool()->getTranslation($textMessage),
            'success' => $success,
        );
        $this->getEventManager()->trigger('meliscommerce_language_delete_end', $this, array_merge($response, array('langId' => $id)));
        
        return new JsonModel($response);
    }
    
//     private function hasAccess($key = 'meliscommerce_currency_lists')
//     {
//         $melisCoreAuth = $this->getServiceLocator()->get('MelisCoreAuth');
//         $melisCoreRights = $this->getServiceLocator()->get('MelisCoreRights');
//         $xmlRights = $melisCoreAuth->getAuthRights();
        
//         $isAccessible = $melisCoreRights->isAccessible($xmlRights, MelisCoreRightsService::MELISCORE_PREFIX_TOOLS, $key);
        
//         return $isAccessible;
//     }

    
}