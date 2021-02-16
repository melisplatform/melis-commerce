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

class MelisComCountryController extends MelisAbstractActionController
{


    public function renderCountryListPageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        
        return $view;
    }
    
    public function renderCountryListPageHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->title = $this->getTool()->getTitle();
        return $view;
    }
    
    public function renderCountryListPageHeaderAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
                
        $view = new ViewModel();
        $view->melisKey = $melisKey;

        return $view;
    }
    
    public function renderCountryListPageContentAction()
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
    
    public function renderCountryListPageTableFilterLimitAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCountryListPageTableFilterSearchAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCountryListPageTableFilterRefreshAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCountryListPageContentActionDeleteAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCountryListPageContentActionEditAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function renderCountryListPageModalContainerAction()
    {
        $id = $this->params()->fromRoute('id', $this->params()->fromQuery('id', ''));
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->melisKey = $melisKey;
        $view->id = $id;
        return $view;
    }
    
    public function renderCountryListPageModalFormAction()
    {
        $id = $this->params()->fromRoute('ctryId', $this->params()->fromQuery('ctryId', ''));
        $saveType = $this->params()->fromRoute('saveType', $this->params()->fromQuery('saveType', ''));
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $title = $this->getTool()->getTranslation('tr_meliscommerce_country_new_country');
        $data = array();
        
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/tools/meliscommerce_country/forms/meliscommerce_country_form','meliscommerce_country_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $form = $factory->createForm($appConfigForm);
        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        
        if($id) {
            $title = $this->getTool()->getTranslation('tr_meliscommerce_CountrY_edit_country');
            $data = (array) $countryTable->getEntryById((int) $id)->current();
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
        $tool = $this->getServiceManager()->get('MelisCoreTool');
        $tool->setMelisToolKey('meliscommerce', 'meliscommerce_country');
        
        return $tool;
    }
    
    
    public function getComCountryDataAction()
    {
        $ctryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
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
        
            $dataCount =  $ctryTable->getTotalData();

            $getData =  $ctryTable->getCountryList(array(
                'where' => array(
                    'key' => 'ctry_id',
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
                    if($vKey != "ctry_flag") {
                        $tableData[$ctr][$vKey] = $this->getTool()->limitedText($this->getTool()->escapeHtml($vValue));
                    }
                }
        
                // manually modify value of the desired row
                // add DataTable RowID, this will be added in the <tr> tags in each rows
                $imageData = $tableData[$ctr]['ctry_flag'];
                $image = !empty($imageData) ? '<img src="data:image/jpeg;base64,'. ($imageData) .'" class="imgDisplay"/>' : '<i class="fa fa-globe"></i>';
                $tableData[$ctr]['DT_RowId'] = $tableData[$ctr]['ctry_id'];
                $status = (int) $tableData[$ctr]['ctry_status'];
                $tableData[$ctr]['ctry_status'] = $status ? $activeDom : $inactiveDom;
                $tableData[$ctr]['ctry_flag'] = $image;
        
            }
        
        }
        

        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' => $ctryTable->getTotalFiltered(),
            'data' => $tableData,
        ));
    }
    
    private function getCountryCurrency($currencyId) 
    {
        $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
        $data = $currencyTable->getEntryById($currencyId)->current();
        $currency = '';
        if($data) {
            $currency =  $data->cur_name . ' (' . $data->cur_symbol . ')';
        }
        
        return $currency;
        
    }
    
    public function testAction()
    {
        
        echo $this->getCountryCurrency(6);
        die;
    }

    private function getAllCurrency($currencyId)
    {
        $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
        $data = $currencyTable->getEntryById($currencyId)->current();
        $currency = '';


        return $currency;

    }
    public function saveAction()
    {
        $success = 0;
        $errors  = array();
        $ctryId = null;
        $logTypeCode = '';
        $textTitle = 'tr_meliscommerce_country_new_country';
        $textMessage = 'tr_meliscommerce_country_add_failed';
        $form = $this->getTool()->getForm('meliscommerce_country_form');
        $isExistsError['ctry_name'] = array(
        		'countryNameExists' => $this->getTool()->getTranslation('tr_meliscommerce_country_ctry_name_exists'),
        		'label' => $this->getTool()->getTranslation('tr_meliscommerce_country_ctry_name'),
        );
        if($this->getRequest()->isPost()) {
            $hasErrorFlag = false;
            $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');

            $postData = $this->getRequest()->getPost()->toArray();
            $postData = $this->getTool()->sanitizePost($postData);
            $this->getEventManager()->trigger('meliscommerce_country_save_start', $this, $postData);
            if($postData['ctry_id']) {
                $textTitle = 'tr_meliscommerce_CountrY_edit_country';
                $textMessage = 'tr_meliscommerce_country_edit_failed';
                $logTypeCode = 'ECOM_COUNTRY_UPDATE';
                $ctryId = $postData['ctry_id'];
            }else{
                $logTypeCode = 'ECOM_COUNTRY_ADD';
            }
            
            $tmpName = $postData['tmp_ctry_name'];
            $name = $postData['ctry_name'];
            $saveType = $postData['saveType'];
            $countryName = $countryTable->getEntryByField('ctry_name', $name)->current();
            $uploadedImage = $this->params()->fromFiles('ctry_flag');
            $imageFile = !empty($uploadedImage['tmp_name']) ? $uploadedImage['tmp_name'] : null;
            $form->setData($postData);
            if($form->isValid()) {
                $data = $form->getData();
                $ctryId = $data['ctry_id'];
                // unset data
                unset($data['tmp_ctry_name']);
                unset($data['saveType']);
                unset($data['ctry_id']);
                $data['ctry_currency_id'] = !empty($data['ctry_currency_id']) ? $data['ctry_currency_id'] : 0;
                $data['ctry_status'] = (int) $postData['ctry_status'];
                if($imageFile) {
                    $uploadedImgFileContent = file_get_contents($uploadedImage['tmp_name']);
                    $data['ctry_flag'] = base64_encode($uploadedImgFileContent);
                }
                else {
                    unset($data['ctry_flag']);
                }

                // for adding a new country
                if($countryName && $saveType == 'new') {
                    $hasErrorFlag = true;
                    $errors = $isExistsError;
                }
                
                // accept update if name wasn't change
                if($tmpName == $name && $saveType == 'edit') {
                    $hasErrorFlag = false;
                }
                elseif($saveType == 'edit' && empty($countryName)) {
                    $hasErrorFlag = false;
                }
                elseif(!empty($countryName) && $saveType == 'edit') {
                    $hasErrorFlag = true;
                    $errors = $isExistsError;
                }
                
                if(!$hasErrorFlag) {
                    $tempCtryId = $ctryId;
                    $ctryId = $countryTable->save($data, $ctryId);
                    if($ctryId) {
                        $success = 1;
                        if($tempCtryId) {
                            $textMessage = 'tr_meliscommerce_country_edit_success';
                        } else {
                            $textMessage = 'tr_meliscommerce_country_add_success';
                        }
                    }
                }


            }
            else {
                $errors = $form->getMessages();
            }
            
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            $appConfigForm = $melisMelisCoreConfig->getItem('meliscommerce/tools/meliscommerce_country/forms/meliscommerce_country_form');
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
        
        $this->getEventManager()->trigger('meliscommerce_country_save_end', 
            $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $ctryId)));
        
        return new JsonModel($response);
    }
    
    public function deleteAction()
    {
        $response = array();
        $this->getEventManager()->trigger('meliscommerce_country_delete_start', $this, $response);
        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        $textMessage = 'tr_meliscommerce_country_delete_failed';
        
        $id = null;
        $success = 0;
        
        if($this->getRequest()->isPost())
        {
            $id = $this->getRequest()->getPost('id');
            if(is_numeric($id))
            {
                $countryData = $countryTable->getEntryById($id)->current();
                if($countryData)
                {
                    $countryTable->deleteById($id);
                    $textMessage = 'tr_meliscommerce_country_delete_success';
                    $success = 1;
                }
            }
        }
        
        $response = array(
            'textTitle' => 'tr_meliscommerce_country_delete_country',
            'textMessage' => $textMessage,
            'success' => $success
        );
        
        $this->getEventManager()->trigger('meliscommerce_country_delete_end', 
            $this, array_merge($response, array('typeCode' => 'ECOM_COUTNRY_DELETE', 'itemId' => $id, 'countryId' => $id)));
        
        return new JsonModel($response);
    }
    
//     private function hasAccess($key = 'meliscommerce_currency_lists')
//     {
//         $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
//         $melisCoreRights = $this->getServiceManager()->get('MelisCoreRights');
//         $xmlRights = $melisCoreAuth->getAuthRights();
        
//         $isAccessible = $melisCoreRights->isAccessible($xmlRights, MelisCoreRightsService::MELISCORE_PREFIX_TOOLS, $key);
        
//         return $isAccessible;
//     }


    
}