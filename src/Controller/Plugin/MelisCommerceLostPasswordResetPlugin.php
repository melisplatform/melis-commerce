<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

use MelisEngine\Controller\Plugin\MelisTemplatingPlugin;
use MelisFront\Navigation\MelisFrontNavigation;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Sendmail;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\ArrayUtils;
/**
 * This plugin implements the business logic of the
 * "lostPasswordReset" plugin.
 * 
 * Please look inside app.plugins.php for possible awaited parameters
 * in front and back function calls.
 * 
 * front() and back() are the only functions to create / update.
 * front() generates the website view
 * back() generates the plugin view in template edition mode (TODO)
 * 
 * Configuration can be found in $pluginConfig / $pluginFrontConfig / $pluginBackConfig
 * Configuration is automatically merged with the parameters provided when calling the plugin.
 * Merge detects automatically from the route if rendering must be done for front or back.
 * 
 * How to call this plugin without parameters:
 * $plugin = $this->MelisCommerceLostPasswordResetPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/password/reset'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'lostPasswordReset');
 * 
 * How to display in your controller's view:
 * echo $this->lostPasswordReset;
 * 
 * 
 */
class MelisCommerceLostPasswordResetPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceLostPasswordResetPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $success = 0;
        $errors = array();
        $message = '';
        $loggedinRedirectLink = null;
        $notLoggedinRedirectLink = null;
        
        $formData = $this->getFormData();
        
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['lost_password_reset'])) ? $this->pluginFrontConfig['forms']['lost_password_reset'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $lostPasswordReset = $factory->createForm($appConfigForm);
        
        $data['m_password'] = (!empty($formData['m_password'])) ? $formData['m_password'] : '';
        $data['m_password2'] = (!empty($formData['m_password2'])) ? $formData['m_password2'] : '';
        $data['m_recovery_key'] = (!empty($formData['m_recovery_key'])) ? $formData['m_recovery_key'] : '';
        $data['m_autologin'] = (!empty($formData['m_autologin'])) ? $formData['m_autologin'] : false;
        $data['m_redirection_link_ok'] = (!empty($formData['m_redirection_link_ok'])) ? $formData['m_redirection_link_ok'] : '';
        
        $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
        $translator = $this->getServiceLocator()->get('translator');
        $clientPerson = $clientSrv->getClientPersonByRecoveryKey($data['m_recovery_key']);
        
        if (empty($clientPerson))
        {
            $isValidKey = false;
            $message = 'Invalid password recovery key';
            $message = $translator->translate('tr_meliscommerce_client_pass_key_invalid');
        }
        else
        {
            $isValidKey = true;
            // Value that trigger if the form is submitted
            $is_submit = (!empty($formData['m_lost_password_reset_is_submit'])) ? $formData['m_lost_password_reset_is_submit'] : false;
        
            // Setting the Datas to Lost Password Form
            $lostPasswordReset->setData($data);
        
            if ($is_submit)
            {
                if ($lostPasswordReset->isValid())
                {
                    $person = (Array) $clientPerson;
                    $person['cper_password'] = $data['m_password'];
                    $person['cper_password_recovery_key'] = null;
                    unset($person['cper_id']);
                    
                    $clientSrv->saveClientPerson($person, null, $clientPerson->cper_id);
                    
                    if ($data['m_autologin'])
                    {
                        $pluginManager = $this->getServiceLocator()->get('ControllerPluginManager');
                        
                        $login = $pluginManager->get('MelisCommerceLoginPlugin');
                        // Adding the custom link the user is already identify
                        $loginParameters = array(
                            'm_login' => $clientPerson->cper_email,
                            'm_password' => $data['m_password'],
                            'm_login_is_submit' => true
                        );
                        // add generated view to children views for displaying it in the contact view
                        $login->render($loginParameters);
                    }
                    
                    $message = $translator->translate('tr_meliscommerce_client_pass_change_success');
                    $success = 1;
                    
                    /**
                     * This will redirect to the $redirection_link url
                     * if the request is not from ajax request
                     */
                    if (!$this->getController()->getRequest()->isXmlHttpRequest())
                    {
                        $this->getController()->redirect()->toUrl($data['m_redirection_link_ok']);
                    }
                }
                else
                {
                    $message = $translator->translate('tr_meliscommerce_client_pass_errors');
                    $errors = $lostPasswordReset->getMessages();
                }
            }
        }
        
        /**
         * As default form will created with the "m_lost_password_reset_is_submit" input having value of "1"
         * so that after form render this will ready for submission
         */
        $lostPasswordReset->get('m_lost_password_reset_is_submit')->setValue('1');
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'lostPasswordReset' => $lostPasswordReset,
            'message' => $message,
            'success' => $success,
            'errors' => $errors,
            'm_redirection_link_ok' => $data['m_redirection_link_ok'],
            'isValidKey' => $isValidKey,
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    /**
     * This function generates the form displayed when editing the parameters of the plugin
     */
    public function createOptionsForms()
    {
        // construct form
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig = $this->pluginBackConfig['modal_form'];
        
        $response = [];
        $render   = [];
        if (!empty($formConfig))
        {
            foreach ($formConfig as $formKey => $config)
            {
                $form = $factory->createForm($config);
                $request = $this->getServiceLocator()->get('request');
                $parameters = $request->getQuery()->toArray();
                
                if (!isset($parameters['validate']))
                {
                    $form->setData($this->getFormData());
                    $viewModelTab = new ViewModel();
                    $viewModelTab->setTemplate($config['tab_form_layout']);
                    $viewModelTab->modalForm = $form;
                    $viewModelTab->formData   = $this->getFormData();
                    
                    $viewRender = $this->getServiceLocator()->get('ViewRenderer');
                    $html = $viewRender->render($viewModelTab);
                    array_push($render, array(
                        'name' => $config['tab_title'],
                        'icon' => $config['tab_icon'],
                        'html' => $html
                    ));
                }
                else
                {
                    // validate the forms and send back an array with errors by tabs
                    $success = false;
                    $errors = array();
                    
                    $post = get_object_vars($request->getPost());
                    
                    $form->setData($post);
                    
                    if (!$form->isValid())
                    {
                        if (empty($errors))
                        {
                            $errors = $form->getMessages();
                        }
                        else
                        {
                            $errors = ArrayUtils::merge($errors, $form->getMessages());
                        }
                    }
                    
                    if (empty($errors))
                    {
                        $success = true;
                    }
                    
                    if (!empty($errors))
                    {
                        foreach ($errors as $keyError => $valueError)
                        {
                            foreach ($config['elements'] as $keyForm => $valueForm)
                            {
                                if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                                {
                                    $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                                }
                            }
                        }
                    }
                    
                    array_push($response, array(
                        'name' => $this->pluginBackConfig['modal_form'][$formKey]['tab_title'],
                        'success' => $success,
                        'errors' => $errors,
                        'message' => '',
                    ));
                }
            }
        }
        
        if (!isset($parameters['validate']))
        {
            return $render;
        }
        else
        {
            return $response;
        }
    }
    
    /**
     * Returns the data to populate the form inside the modals when invoked
     * @return array
     */
    public function getFormData()
    {
        $data = parent::getFormData();
        return $data;
    }
    
    /**
     * This method will decode the XML in DB to make it in the form of the plugin config file
     * so it can overide it. Only front key is needed to update.
     * The part of the XML corresponding to this plugin can be found in $this->pluginXmlDbValue
     */
    public function loadDbXmlToPluginConfig()
    {
        $configValues = array();
        
        $xml = simplexml_load_string($this->pluginXmlDbValue);
        
        if ($xml)
        {
            if (!empty($xml->template_path))
            {
                $configValues['template_path'] = (string)$xml->template_path;
            }
            
//             if (!empty($xml->lost_password_reset_page_link))
//             {
//                 $configValues['lost_password_reset_page_link'] = (string)$xml->lost_password_reset_page_link;
//             }
        }
        
        return $configValues;
    }
    
    /**
     * This method saves the XML version of this plugin in DB, for this pageId
     * Automatically called from savePageSession listenner in PageEdition
     */
    public function savePluginConfigToXml($parameters)
    {
        $xmlValueFormatted = '';
        
        // template_path is mendatory for all plugins
        if (!empty($parameters['template_path']))
        {
            $xmlValueFormatted .= "\t\t" . '<template_path><![CDATA[' . $parameters['template_path'] . ']]></template_path>';
        }
        
//         if (!empty($parameters['lost_password_reset_page_link']))
//         {
//             $xmlValueFormatted .= "\t\t" . '<lost_password_reset_page_link><![CDATA[' . $parameters['lost_password_reset_page_link'] . ']]></lost_password_reset_page_link>';
//         }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}