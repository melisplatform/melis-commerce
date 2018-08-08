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

class MelisComSettingsController extends AbstractActionController
{
    
    /**
     * renders the page container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsPageAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the page header container
     * @return \Zend\View\Model\ViewModel
     */

    public function renderSettingsHeaderContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the page header left container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsHeaderLeftContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the page header left container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsHeaderRightContainerAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
     
    /**
     * renders the page header title
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the page header save button
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsHeaderSaveAction()
    {
        return new ViewModel();
    }
    
    /**
     * renders the page content container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsPageContentAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the page tabs
     * @return \Zend\View\Model\ViewModel render-coupon-page-tab-main
     */
    public function renderSettingsPageTabsMainAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the tabs content header container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsTabsContentHeaderAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the tabs content header left container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsTabsContentHeaderLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the tabs content header right container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsTabsContentHeaderRightAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the tabs content header title
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsTabsContentHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the tabs content details container
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsTabsContentDetailsAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the tabs content details container left
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsTabsContentDetailsLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * renders the tabs content details container left
     * @return \Zend\View\Model\ViewModel
     */
    public function renderSettingsTabsContentDetailsGeneralAction()
    {
        $generalForm = 'meliscommerce_settings_alert_form';
        $melisCoreConfig = $this->getServiceLocator()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_settings/'.$generalForm,$generalForm);
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $alertForm = $factory->createForm($appConfigForm);
        
        $userTable = $this->getServiceLocator()->get('MelisCoreTableUser');
        $stockEmailAlertSvc = $this->getServiceLocator()->get('MelisComStockEmailAlertService');
        $users = array();
        $translator = $this->getServiceLocator()->get('translator');
        
        $stockLimit = '';
        $recipients = array();
        $usedEmails = array();
        
        $productId = (int) $this->params()->fromQuery('productId', '');
        
        if(!empty($productId)){
            $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
            $product = $prodSvc->getProductById($productId)->getProduct();
            
            $queryId = $productId;
            
        }else{
            $productId = null;
            $queryId = -1;
            $alertForm->get('sea_stock_level_alert')->setOption('tooltip', $translator->translate('tr_meliscommerce_settings_alert_stock tooltip'));
        }
       
        $stockAlerts = $stockEmailAlertSvc->getStockEmailRecipients(array($queryId));
        
        
        foreach($stockAlerts as $recipient){
            
            if($recipient['sea_id'] == -1){
                
                $stockLimit =  $recipient['sea_stock_level_alert'];
            }else{
                
                $usedEmails[] = $recipient['sea_email'];
                $recipients[] = $recipient;
            }
        }
        
        $data = array(
            'sea_id' => '-1',
            'sea_prd_id' => '-1',
            'sea_stock_level_alert' => $stockLimit,
        );
        
        if(!empty($productId)){
            $data['sea_id'] = null;
            $data['sea_prd_id'] = $productId;
            $data['sea_stock_level_alert'] = $product->prd_stock_low;
        }
        
        $alertForm->setData($data);
        
        foreach($userTable->fetchAll() as $user){
            
            if(!in_array($user->usr_email, $usedEmails)){
                
                $users[] = array(
                    'id' => $user->usr_id,
                    'email' => $user->usr_email,
                );
            }
        }
       
        
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->alertForm = $alertForm;
        $view->users = $users;
        $view->recipients = $recipients;
        $view->productId = $productId;
        $view->melisKey = $melisKey;
        return $view;
    }
    private function getLogFormsError($form, $formConfig)
    {
        $appConfigFormElements = $formConfig['elements'];
        $errors = $form;

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
    /**
     * 
     */
    public function saveSettingsAction()
    {
        $response = array();
        $success = 0;
        $errors  = array();
        $data = array();
        $result = false;
        $logTypeCode = '';
        $textMessage = 'tr_meliscommerce_settings_save_failed';
        $textTitle = 'tr_meliscommerce_settings';
        
        $stockEmailAlertSvc = $this->getServiceLocator()->get('MelisComStockEmailAlertService');
        $stockEmailTable = $this->getServiceLocator()->get('MelisEcomStockEmailAlertTable');
        
        $generalForm = 'meliscommerce_settings_alert_form';
        $melisCoreConfig = $this->getServiceLocator()->get('MelisCoreConfig');
        $appConfigForm = $melisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_settings/'.$generalForm,$generalForm);
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $alertForm = $factory->createForm($appConfigForm);
        
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_coupon');
        $translator = $this->serviceLocator->get('translator');
        
        $logTypeCode = 'ECOM_SAVE_SETTINGS';
        
        if($this->getRequest()->isPost()){
            
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $melisTool->sanitizeRecursive($postValues);
            
            $alertForm->setData($postValues);
            
            if($alertForm->isValid()){
                
                $formData = $alertForm->getData();
                $formData['sea_stock_level_alert'] = !empty($formData['sea_stock_level_alert'])? $formData['sea_stock_level_alert'] : null;
                $sea_stock_level_alert = $formData['sea_stock_level_alert'];
                $ids = array();
                
                $productId = $formData['sea_prd_id'];
                
                if($formData['sea_prd_id'] == -1){
                    // update default data
                    $stockEmailTable->setDefaultValues($formData);
                }
                
                // remove deleted recipients
                $stockAlerts = $stockEmailAlertSvc->getStockEmailRecipients($productId);
                
                if(!empty($postValues['recipients'])){
                    
                    foreach($postValues['recipients'] as $recipient){
                    
                        if(!empty($recipient['sea_id'])){
                            
                            $ids[] = $recipient['sea_id'];
                        }
                        
                        if(!filter_var($recipient['sea_email'], FILTER_VALIDATE_EMAIL)){
                            if(empty($errors['sea_email'])){
                                $errors['sea_email'] = array(
                                    'inValidEmail' => $translator->translate('tr_meliscommerce_settings_save_add_recipients_failed'). ': '. $recipient['sea_email'],
                                    'label' =>  $translator->translate('tr_meliscommerce_settings_label_recipients')
                                );
                            }else{
                                $errors['sea_email']['inValidEmail'] = $errors['sea_email']['inValidEmail']. ', '. $recipient['sea_email'];
                            }
                        }
                    
                        $data[] = array(
                    
                            'sea_id' => $recipient['sea_id'],
                            'sea_stock_level_alert' => $sea_stock_level_alert,
                            'sea_email' => $recipient['sea_email'],
                            'sea_user_id' => $recipient['sea_user_id'],
                            'sea_prd_id' => $productId,
                        );
                    }
                    
                    // delete removed recipients
                    foreach($stockAlerts as $recipient){
                
                        if(!in_array($recipient['sea_id'], $ids) && $recipient['sea_id'] != -1){
                
                            $stockEmailAlertSvc->deleteStockEmailAlertById($recipient['sea_id']);
                        }
                    }
                }else{
                    // if recipients are emptied delete recipients
                    foreach($stockAlerts as $recipient){
                        
                        if($recipient['sea_id'] != -1){
                            
                            $stockEmailAlertSvc->deleteStockEmailAlertById($recipient['sea_id']);
                        }
                    }
                }
                
                if(empty($errors)){
                    // insert data to db
                    foreach($data as $entry){
                    
                        $id = $entry['sea_id'];
                        unset($entry['sea_id']);
                        $result = $stockEmailAlertSvc->SaveStockEmailAlert($entry, $id);
                    
                        if(empty($result)){
                    
                            $errors['failedInsert'] = array(
                                'label' => $melisTool->getTranslation('tr_meliscommerce_settings_label_recipients'),
                                'failedInsert' => $melisTool->getTranslation('tr_meliscommerce_settings_save_add_recipients_failed'),
                            );
                    
                            $textMessage = 'tr_meliscommerce_settings_save_failed';
                            break;
                        }
                    }
                }
                
            }else{
                
                $errors = array_merge($errors, $this->getFormErrors($alertForm, $appConfigForm));
            }
        }
        
        if(empty($errors)){
            $success = 1;
            $textMessage = 'tr_meliscommerce_settings_save_success';
        }
        
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => $data,
        );
        
        $this->getEventManager()->trigger('meliscommerce_settings_save_end',
            $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => '')));
        
        return new JsonModel($response);
    }
    
    public function removeRecipientAction()
    {
        
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