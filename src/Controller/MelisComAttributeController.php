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

class MelisComAttributeController extends AbstractActionController
{
    /**
     * renders the page container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributePageAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $this->setAttributeVariables($attributeId);
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the page header container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeHeaderAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the page header left container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeHeaderLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the page header right container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeHeaderRightAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the page header title
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeHeaderTitleAction()
    {
        $view = new ViewModel();
        $atrans_name = '';
        if(!empty($this->layout()->attribute)){
            if(!empty($this->layout()->attribute->attr_trans)){
                $atrans_name = $this->layout()->attribute->attr_trans[0]->atrans_name;
            }else{
                $atrans_name = $this->layout()->attribute->attr_reference;
            }
            
        }            
       
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        $view->atrans_name = $atrans_name;
        return $view;
    }
    
    /**
     * renders the page header save button
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeHeaderSaveAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the page content container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributePageContentAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the page main content container
     * @return \Zend\View\Model\ViewModel render-coupon-page-tab-main
     */
    public function renderAttributePageTabsMainAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content header container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentHeaderAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content header left container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentHeaderLeftAction()
    {
         $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content header right container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentHeaderRightAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content header status
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentHeaderStatusAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $status = '';
        if(isset($this->layout()->attribute)){
            $status = ($this->layout()->attribute->attr_status)? 'checked': '';
        }
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        $view->status = $status;
        return $view;
    }
    
    /**
     * renders the tabs content header container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentHeaderValuesAddAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content header title
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content details container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentDetailsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content details container left
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentDetailsLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content details container right
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentDetailsRightAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content details sub header container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentSubHeaderAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content details sub header container left
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentSubHeaderLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content details sub header container right
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentSubHeaderRightAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content details sub header container title
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentSubHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the tabs content details sub details container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentSubDetailsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        return $view;
    }
    
    /**
     * renders the coupon form for general data
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeFormGeneralDataAction()
    {
        $generalForm = 'meliscommerce_attribute_general_data';
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_attributes/'.$generalForm,$generalForm);

        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $attributeForm = $factory->createForm($appConfigForm);
       
        $visible = '';
        $searchable = '';
        if(isset($this->layout()->attribute)){
            $attribute = $this->layout()->attribute;
            $attributeForm->setData((array)$attribute);
            $attributeForm->get('attr_type_id')->setAttribute('disabled','disabled');
            if($this->layout()->attribute->attr_visible){
                $visible = 'checked';
            }
            if($this->layout()->attribute->attr_searchable){
                $searchable = 'checked';
            }
        }        
    
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        $view->attributeForm = $attributeForm;
        $view->visible = $visible;
        $view->searchable = $searchable;
        return $view;
    }
    
    /**
     * renders the tabs content details for labels
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentDetailsLabelsAction()
    {
        //set svc
        $langTable = $this->getServiceLocator()->get('MelisEcomLangTable');
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        $langs = array();
        $attribute = array();
        //set form config
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $attributeTrans = 'meliscommerce_attribute_text_trans';
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_attributes/'.$attributeTrans,$attributeTrans);
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $attributeTransForm = $factory->createForm($appConfigForm);
        
        if($attributeId){
            $attribute = $attributeSvc->getAttributeById($attributeId)->getAttribute();
        }        
        
        foreach($langTable->langOrderByName() as $lang){
            $langs[] = $lang;           
            $form = clone($attributeTransForm);
            
            if(!empty($attribute)){
                foreach($attribute->attr_trans as $trans){
                    if($trans->atrans_lang_id == $lang->elang_id){
                        $form->setData((array)$trans);
                    }
                }
            }           
            //auto populate atrans_lang_id fields
            $tmp['atrans_lang_id'] = $lang->elang_id;
            
            $form->setData($tmp);
            $forms[] = $form;
        }
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        $view->forms = $forms;
        $view->langs = $langs;
        return $view;
    }
    
    /**
     * renders the attribute content table filter limit
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeContentFilterLimitAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the attribute content table filter search
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeContentFilterSearchAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the attribute content table filter refresh
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeContentFilterRefreshAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the attribute content table action info
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeContentActionInfoAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the attribute content table action delete
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeContentActionDeleteAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the tabs content details values table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeTabsContentDetailsValuesTableAction()
    {
        
        $columns = $this->getTool()->getColumns();
        $columns['actions'] = array('text' => 'Action');

        $view = new ViewModel(); 
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->attributeId = $attributeId;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#'.$attributeId.'_tableAttributeValue', true, null, array('order' => '[[ 0, "desc" ]]'));
        return $view;
    }
    
    /**
     * retrieves the data for the attribute value table
     * @return \Zend\View\Model\JsonModel
     */
    public function getAttributeValueDataAction()
    {
        $success = 0;
        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $dataFiltered = 0;
        $tableData = array();
        $langId = $this->getTool()->getCurrentLocaleID();
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        $attributeTypeTable = $this->getServiceLocator()->get('MelisEcomAttributeTypeTable');
        $attributeId = $this->getRequest()->getPost('attributeId');
        
        if($attributeId) {
            $colId = array_keys($this->getTool()->getColumns());  
            
            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];
            
            $selCol = $this->getRequest()->getPost('order');
            $selCol = $colId[$selCol[0]['column']];            
            
            $draw = (int) $this->getRequest()->getPost('draw');
            
            $start = (int) $this->getRequest()->getPost('start');
            $length =  (int) $this->getRequest()->getPost('length');
            
            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];            
           
            // identify the column to be queried in the melis_ecom_attribute_value_trans
            $attr_type_id = $attributeSvc->getAttributeById($attributeId)->getAttribute()->attr_type_id;
            $attributeType = $attributeTypeTable->getEntryById($attr_type_id)->current();
            $valCol = 'avt_v_'.$attributeType->atype_column_value;
            
            // provides the vaid column for sorting datatable
            if($selCol == 'value'){
                $colOrder = $valCol. ' ' . $sortOrder;
            }else{
                $colOrder = $selCol. ' ' . $sortOrder;
            }
            
            $tmp = $attributeSvc->getAttributeValuesList($attributeId, null, null, null, null, $search, $valCol);
            $dataFiltered = count($tmp);
            
            $attributeValues = $attributeSvc->getAttributeValuesList($attributeId, null, $start, $length, $colOrder, $search, $valCol);
            $dataCount = count($attributeValues);

            $c = 0;
            foreach($attributeValues as $attributeValue){               
                
                
                $valCol = 'avt_v_'.$attributeValue->atype_column_value;
                
                //check for attribute value translations
                $foundTrans = false;
                foreach($attributeValue->atval_trans as $valTrans){
                    if($valTrans->avt_lang_id == $langId){
                        $foundTrans = true;
                        $value = $valTrans->$valCol;
                    }
                }
                
                //if no corresponding tranlsation get the first available trans
                if(!$foundTrans){
                    foreach($attributeValue->atval_trans as $valTrans){
                        $foundTrans = true;
                        $value = $valTrans->$valCol;
                        break;
                    }
                }
                
                //use the attribute value reference as name if no translation
                if(!$foundTrans){
                    $value = $attributeValue->atval_reference;
                }
                
                // edit value before rendering to table if necessary
                switch($valCol){
                    case 'avt_v_datetime': $value = $this->getTool()->dateFormatLocale($value); break;
                    case 'avt_v_text': 
                    case 'avt_v_varchar' : $value = $this->getTool()->limitedText($value,50); break;
                }                    
                
                $tableData[$c]['DT_RowId']  = $attributeValue->atval_id;
                $tableData[$c]['atval_id']  = $attributeValue->atval_id;
                $tableData[$c]['value']     = $value;
                $c++;
                               
            }
        }
        return new JsonModel(array (
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataFiltered,
            'data' => $tableData,
        ));
    }
    
    /**
     * renders the order modal container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeModalAction()
    {
        $container = new Container('meliscore');
        if (!empty($container['melis-lang-locale'])){
            $sessionLocale = $container['melis-lang-locale'];
        }
          
        $id = $this->params()->fromQuery('id');
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->id = $id;
        $view->setTerminal(false);
        return $view;
    }
    
    /**
     * renders the order modal container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeModalValueFormAction()
    {
        $langTable = $this->getServiceLocator()->get('MelisEcomLangTable');
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        $attributeTypeTable = $this->getServiceLocator()->get('MelisEcomAttributeTypeTable');
        $langs = array();
        $forms = array();
        $attributeValues = array();
        $datepickerInit = '';
        
        //set form config
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $attributeValueId = (int) $this->params()->fromQuery('attributeValueId', '');
        $this->setAttributeVariables($attributeId);
        
        //each attribute type has its own form label and input, they are styled display: none, this will unhide the specific label and input
        //that are based on the current attribute type
        $attributeType = $attributeTypeTable->getEntryById($this->layout()->attribute->attr_type_id)->current();
        $atype_column_value = $attributeType->atype_column_value;
        $atype_column_value = 'avt_v_'.$atype_column_value;
        
        $attrVal = 'meliscommerce_attribute_value_'.$atype_column_value;
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_attributes/'.$attrVal,$attrVal);
        
        if($atype_column_value == 'avt_v_datetime'){
            $datepickerInit = $this->getTool()->datePickerInit('valueDate');
        }
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $attributeValueForm = $factory->createForm($appConfigForm);
        
        //form used for filling all values
        $fillAllForm = clone($attributeValueForm);
        
        foreach($langTable->langOrderByName() as $lang){
            
            $form = clone($attributeValueForm);
            
            //auto populate lang id
            $langData['avt_lang_id'] = $lang->elang_id;
            $form->setData($langData);
            
            //set the form data
            if($attributeValueId){
                foreach($attributeSvc->getAttributeValuesById($attributeValueId) as $val){
                    if($val->atval_id == $attributeValueId){
                        foreach($val->atval_trans as $trans){
                            if($trans->avt_lang_id == $lang->elang_id){
                                if($atype_column_value == 'avt_v_datetime'){
                                    $trans->avt_v_datetime = $this->getTool()->dateFormatLocale($trans->avt_v_datetime);
                                }
                                $form->setData((array)$trans);
                            }
                        }
                    }
                }
            }
            
            $forms[] = $form;
            $langs[] = $lang;            
        }
        
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        $view->attributeId = $attributeId;
        $view->forms = $forms;
        $view->fillAllForm = $fillAllForm;
        $view->langs = $langs;
        $view->datePickerInit = $datepickerInit;
        return $view;
        
    }
    
    /**
     * This method saves the attribute values and translations
     * @return \Zend\View\Model\JsonModel
     */
    public function saveAttributeValuesAction()
    {
        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();
        $attributeValues = array();
        $filledUp = 0;
        $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_coupon_save_fail');
        $textTitle = $this->getTool()->getTranslation('tr_meliscommerce_attribute_page');
        $this->getEventManager()->trigger('meliscommerce_attribute_value_save_start', $this, array());

        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        $attributeTypeTable = $this->getServiceLocator()->get('MelisEcomAttributeTypeTable');
        $langTable = $this->getServiceLocator()->get('MelisEcomLangTable');
        
        if($this->getRequest()->isPost()){
            $postValues = get_object_vars($this->getRequest()->getPost());
            $attributeId = $postValues['attributeId'];
            $attribute = $attributeSvc->getAttributeById($attributeId)->getAttribute();
            
            $this->setAttributeVariables($attributeId);
            //get specific form
            $attributeType = $attributeTypeTable->getEntryById($this->layout()->attribute->attr_type_id)->current();
            $atype_column_value = $attributeType->atype_column_value;
            $atype_column_value = 'avt_v_'.$atype_column_value;
            
            $attrVal = 'meliscommerce_attribute_value_'.$atype_column_value;
            $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
            $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_attributes/'.$attrVal,$attrVal);
            
            $factory = new \Zend\Form\Factory();
            $formElements = $this->serviceLocator->get('FormElementManager');
            $factory->setFormElementManager($formElements);
            $attributeValueForm = $factory->createForm($appConfigForm);
            
            //extract fill all form value
            $allFormValue = $postValues['attributeValueTrans'][0];
            unset($postValues['attributeValueTrans'][0]);            
            
            //check if fill all form is filled up
            if(array_filter($allFormValue)){
                //check if each language form is filled up
                foreach($postValues['attributeValueTrans'] as $valueTrans){                    
                    $data[] = $valueTrans;
                    if(!empty($valueTrans[$atype_column_value])){
                        $filledUp = 1;
                    }
                }
                
                // if filled up throw error, else if empty, save fill up form to fill up all languages
                if($filledUp){
                    $title = $this->getTool()->getTranslation('tr_meliscommerce_attribute_page_tabs_values');
                    $message = $this->getTool()->getTranslation('tr_meliscommerce_attribute_value_save_error');
                    $errors[$atype_column_value] = array('bothFilled' => $message ,'label' => $title);
                    $success = 0;
                }else{
                    $data = array();
                    $attributeValueForm->setData($allFormValue);
                    if(!$attributeValueForm->isValid()){
                            $errors = $this->getFormErrors($attributeValueForm, $appConfigForm);
                        }
                    else{
                        foreach($langTable->fetchAll() as $lang){
                            $allFormValue['avt_lang_id'] = $lang->elang_id;
                            $data[] = $allFormValue;
                        }   
                    }                    
                }
                
            }else{
                //if fill all form is empty the fill up each form will be save                
                
                foreach($postValues['attributeValueTrans'] as $attributeValueTrans){
                
                    $attributeValueForm->setData($attributeValueTrans);
                
                    if(!$attributeValueForm->isValid()){
                        $errors = $this->getFormErrors($attributeValueForm, $appConfigForm);
                    }
                    
                    if(!empty($attributeValueTrans[$atype_column_value]) || is_numeric($attributeValueTrans[$atype_column_value])){
                        $data[] = $attributeValueTrans;
                    }else{
                        //delete empty data from db
                        $attributeSvc->deleteAttributeValueTransById($attributeValueTrans['avt_id']);
                    }
                
                }
            }            
            
            if(empty($data)){
                $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_attribute_value_save_empty');
            }
            
            if(empty($errors) && !empty($data)){
                //attribute value id checking
                $atval_id = null;
                foreach($data as $trans){
                    $atval_id = !empty($trans['av_attribute_value_id'])? $trans['av_attribute_value_id'] : $atval_id;
                }
                // if no attribute value id, create attribute value
                if(empty($atval_id)){
                    $attributeValue = array(
                        'atval_attribute_id' => $attributeId,
                        'atval_type_id' => $attribute->attr_type_id,
                    );                
                    $atval_id = $attributeSvc->saveAttributeValue($attributeValue);                     
                }
                
                
                //the insert or update of attribute value trans happens here
                foreach($data as $trans){         
                    
                    $avt_id = empty($trans['avt_id'])? null : $trans['avt_id'];
                    unset($trans['avt_id']);                  
                    $trans['av_attribute_value_id'] = empty($trans['av_attribute_value_id'])? $atval_id : $trans['av_attribute_value_id'];
                    if(isset($trans['avt_v_datetime'])){
                        $trans['avt_v_datetime'] = $this->getTool()->localeDateToSql($trans['avt_v_datetime']);
                    }
                    
                    $attributeValueTransId = $attributeSvc->saveAttributeValueTrans($trans, $avt_id);                         
                }
                $success = 1;
                $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_attribute_value_save_success');
            }
        }
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        $this->getEventManager()->trigger('meliscommerce_attribute_value_save_end', $this, $response);
        return new JsonModel($response);
    }
    
    /**
     * This method deletes the attribute value from the attribute value table
     * @return \Zend\View\Model\JsonModel
     */
    public function deleteAttributeValueAction()
    {
        $response = array();
        $success = false;
        $errors  = array();
        $data = array();
        $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_attribute_value_delete_fail');
        $textTitle = $this->getTool()->getTranslation('tr_meliscommerce_attribute_page');
        $this->getEventManager()->trigger('meliscommerce_attribute_value_delete_start', $this, array());
        
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        
        if($this->getRequest()->isPost()){
            $postValues = get_object_vars($this->getRequest()->getPost());

            $success = $attributeSvc->deleteAttributeValueById($postValues['attributeValueId']);
            if($success){
                $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_attribute_value_delete_success');
            }
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        $this->getEventManager()->trigger('meliscommerce_attribute_value_delete_end', $this, $response);
        return new JsonModel($response);
    }
    
    public function saveAttributeAction()
    {
        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();
        
        $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_order_page_save_fail');
        $textTitle = $this->getTool()->getTranslation('tr_meliscommerce_attribute_page');
        
        $container = new Container('meliscommerce');
        unset($container['attribute-valid-data']);
        
        if($this->getRequest()->isPost()){
            $this->getEventManager()->trigger('meliscommerce_attribute_save_start', $this, array());
            
            if (!empty($container['attribute-valid-data']))
            {
                if (!empty($container['attribute-valid-data']['success'])){
                    $success = $container['attribute-valid-data']['success'];
                    $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_attribute_save_success');
                }
                if (!empty($container['attribute-valid-data']['errors']))
                    $errors = $container['attribute-valid-data']['errors'];
                if (!empty($container['attribute-valid-data']['datas']))
                    $data = $container['attribute-valid-data']['datas'];
                
                unset($container['attribute-valid-data']);
                if($success){
                   $this->setAttributeVariables($data['attributeId']);
                   if(!empty($this->layout()->attribute->attr_trans)){
                       $data['tabName'] = $this->layout()->attribute->attr_trans[0]->atrans_name;
                   }else{
                       $data['tabName'] = $this->layout()->attribute->attr_reference;
                   }
                   
                }
                
            }
        }
        
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        $this->getEventManager()->trigger('meliscommerce_attribute_save_end', $this, $response);
        return new JsonModel($response);
    }
    
    /**
     * This method validates the attribute form
     * @return \Zend\View\Model\JsonModel
     */
    public function validateAttributeFormAction()
    {
        $data['attribute'] = array();
        $errors = array();
        $success = 1;
        
        $generalForm = 'meliscommerce_attribute_general_data';
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_attributes/'.$generalForm,$generalForm);

        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        
        
        $postValues = get_object_vars($this->getRequest()->getPost());

        if(!empty($postValues['attribute'])){
            foreach($postValues['attribute'] as $attribute){
                $attributeForm = $factory->createForm($appConfigForm);
                $attributeForm->setData($attribute);
                if(!$attributeForm->isValid()){
                    $success = 0;
                    $errors[] = $this->getFormErrors($attributeForm, $appConfigForm);
                }
                $attribute = $attributeForm->getData();
                $attribute = array_merge($attribute, $postValues['switch']);
                $data['attribute'] = $attribute;
            }
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }
    
    public function validateAttributeTransFormAction()
    {
        $data['attributeTrans'] = array();
        $errors = array();
        $success = 1;

        $attributeTrans = 'meliscommerce_attribute_text_trans';
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_attributes/'.$attributeTrans,$attributeTrans);
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
    
        $postValues = get_object_vars($this->getRequest()->getPost());
       
        if(!empty($postValues['attributeTrans'])){
            foreach($postValues['attributeTrans'] as $attributeTrans){
                $attributeTransForm = $factory->createForm($appConfigForm);
                $attributeTransForm->setData($attributeTrans);
                if(!$attributeTransForm->isValid()){
                    $success = 0;
                    $errors[] = $this->getFormErrors($attributeTransForm, $appConfigForm);
                }
                $tmp = $attributeTrans;
                unset($tmp['atrans_lang_id']);
                if(array_filter($tmp)){
                    $data['attributeTrans'][] = $attributeTransForm->getData();
                }                
            }
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }
    
    public function saveAttributeDataAction()
    {
        $success = 0;
        $errors = array();
        $data = array();
        
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        
        $container = new Container('meliscommerce');
        if (!empty($container['attribute-valid-data'])){
            if (!empty($container['attribute-valid-data']['success'])){
                $success = $container['attribute-valid-data']['success'];
                $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_order_page_save_success');
            }
            if (!empty($container['attribute-valid-data']['errors']))
                $errors = $container['attribute-valid-data']['errors'];
            if (!empty($container['attribute-valid-data']['datas']))
                $formData = $container['attribute-valid-data']['datas'];
        }
        unset($container['attribute-valid-data']);
        
        if(empty($formData['attribute']['attr_reference'])){
            if(!empty($formData['attributeTrans'])){
                $formData['attribute']['attr_reference'] = $formData['attributeTrans'][0]['atrans_name'];
            }else{
                $title = $this->getTool()->getTranslation('tr_meliscommerce_attribute_reference');
                $message = $this->getTool()->getTranslation('tr_meliscommerce_address_error_empty');
                $errors[]['attr_reference'] = array('isEmpty' => $message ,'label' => $title);
                $success = 0;
            }
        }
        
        if(!$errors && !empty($formData)){
            $attributeId = $formData['attribute']['attr_id'];
            unset($formData['attribute']['attr_id']);
            $data['attributeId'] = $attributeSvc->saveAttribute($formData['attribute'], $formData['attributeTrans'], $attributeId);
            
            if(!empty($data)){
                $success = 1;
            }
            
        }
        
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }
    
    /**
     * This method deletes the attribute translations entry if the language its affected is deleted
     * @return \Zend\View\Model\JsonModel
     */
    public function attributeTransLangDeletedAction()
    {
        $success = 0;
        $errors = array();
        $data = array();
        $countryId = -1;
         
        $attributeTransTable = $this->getServiceLocator()->get('MelisEcomAttributeTransTable');
        $attributeValueTransTable = $this->getServiceLocator()->get('MelisEcomAttributeValueTransTable');
        $langTable = $this->getServiceLocator()->get('MelisEcomLangTable');
    
        $langId = $this->getRequest()->getPost('id');
    
        //check if language is already deleted
        $lang = $langTable->getEntryById($langId);
        if($lang->count() === 0){
            if(is_numeric($langId)){
                $attributeTransTable->deleteByField('atrans_lang_id', $langId);
                $attributeValueTransTable->deleteByField('avt_lang_id', $langId);
                $success = 1;
            }
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }
    
    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_attribute');
    
        return $melisTool;
    
    }
    
    /**
     * sets the coupon data to the layout
     * @param unknown $couponId
     */
    private function setAttributeVariables($attributeId)
    {
        $layoutVar = array();
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        if($attributeId){
            $resultData = $attributeSvc->getAttributeById($attributeId);
            $layoutVar['attribute'] = $resultData->getAttribute();
            $layoutVar['attributeValues'] = $resultData->getAttributeValues();
        }
        $this->layout()->setVariables( array_merge( array(
            'attributeId' => $attributeId,
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
}