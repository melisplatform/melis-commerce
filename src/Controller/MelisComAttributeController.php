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
        if(isset($this->layout()->attribute)){
            $atrans_name = $this->layout()->attribute->attr_trans[0]->atrans_name;
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
        
        foreach($langTable->fetchAll() as $lang){
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
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#'.$attributeId.'_tableAttributeValue', true);
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
        $tableData = array();
        $langId = $this->getTool()->getCurrentLocaleID();
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        
        $attributeId = $this->getRequest()->getPost('attributeId');
        
        if($attributeId) {
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
             
            $valCol = null;
            foreach($attributeSvc->getAttributeById($attributeId)->getAttributeValues() as $val){
                $valCol = $val->atype_column_value;
                $valCol = 'avt_v_'.$valCol;
            }           
                        
            $attributeValues = $attributeSvc->getAttributeValuesList($attributeId, $langId, $start, $length, $colOrder, $search, $valCol);
            $dataCount = count($attributeValues);
//             echo '<pre>'; print_r($attributeValues); echo '</pre>'; die();
            $c = 0;
            foreach($attributeValues as $attributeValue){
               
                $valCol = 'avt_v_'.$attributeValue->atype_column_value;
                // some deep shit checking of avt_v_shit that will control the displaying of table values
                // maybe for future updates
                $value = $attributeValue->atval_trans[0]->$valCol;
                // end of deep shit checking                
                
                $tableData[$c]['DT_RowId']  = $attributeValue->atval_id;
                $tableData[$c]['atval_id']  = $attributeValue->atval_id;
                $tableData[$c]['value']     = $value;
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
    
    /**
     * renders the order modal container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeModalAction()
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
     * renders the order modal container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderAttributeModalValueFormAction()
    {
        $langTable = $this->getServiceLocator()->get('MelisEcomLangTable');
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        $langs = array();
        $forms = array();
        $attributeValues = array();
        
        //set form config
        $attributeId = (int) $this->params()->fromQuery('attributeId', '');
        $attributeValueId = (int) $this->params()->fromQuery('attributeValueId', '');
        
        $attrVal = 'meliscommerce_attribute_value';
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_attributes/'.$attrVal,$attrVal);
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $attributeValueForm = $factory->createForm($appConfigForm);
        
        $this->setAttributeVariables($attributeId);
//         echo '<pre>'; print_r($this->layout()->attributeValues); echo '</pre>'; die();
        foreach($langTable->fetchAll() as $lang){
            
            $form = clone($attributeValueForm);       
            
            //each attribute type has its own form label and input, they are styled display: none, this will unhide the specific label and input
            //that are based on the current attribute type
            $atype_column_value = $this->layout()->attributeValues[0]->atype_column_value;
            $atype_column_value = 'avt_v_'.$atype_column_value;
            $form->get($atype_column_value)->setAttribute('style','display:block;');
            $settings = $form->get($atype_column_value)->getLabelAttributes();
            $form->get($atype_column_value)->setLabelAttributes(array());             
            
            //auto populate lang id
            $langData['avt_lang_id'] = $lang->elang_id;
            $form->setData($langData);
            
            //set the form data
            if($attributeValueId){
                foreach($attributeSvc->getAttributeValuesById($attributeValueId) as $val){
                    if($val->atval_id == $attributeValueId){
                        foreach($val->atval_trans as $trans){
                            if($trans->avt_lang_id == $lang->elang_id){
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
        $view->langs = $langs;
        return $view;
        
    }
    
    public function saveAttributeStatusAction()
    {
        $response = array();
        $success = false;
        $errors  = array();
        $data = array();
        $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_order_message_save_fail');
        $textTitle = $this->getTool()->getTranslation('tr_meliscommerce_attribute_page');
        $this->getEventManager()->trigger('meliscommerce_attribute_value_save_start', $this, array());
        
        $attrVal = 'meliscommerce_attribute_value';
        $melisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_attributes/'.$attrVal,$attrVal);
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $attributeValueForm = $factory->createForm($appConfigForm);
        
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        if($this->getRequest()->isPost()){
            $postValues = get_object_vars($this->getRequest()->getPost());
//             echo '<pre>'; print_r($postValues); echo '</pre>'; die();
            foreach($postValues['attributeValue'] as $attributeValue){
                $attributeValueForm->setData($attributeValue);
                
                if(!$attributeValueForm->isValid()){
                    $errors[] = $this->getFormErrors($attributeValueForm, $attrVal);
                }
                
                $tmp = $attributeValue;
                unset($tmp['avt_id']);
                unset($tmp['avt_lang_id']);
                
                if(array_filter($tmp)){
                    // to be continued
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
        $this->getEventManager()->trigger('meliscommerce_attribute_value_save_end', $this, $response);
        return new JsonModel($response);
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
            $resultData = $attributeSvc->getAttributeById($attributeId, $this->getTool()->getCurrentLocaleID());
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