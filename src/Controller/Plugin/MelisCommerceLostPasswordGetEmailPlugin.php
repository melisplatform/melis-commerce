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
/**
 * This plugin implements the business logic of the
 * "lostPassword" plugin.
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
 * $plugin = $this->MelisCommerceLostPasswordGetEmailPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/password/lost'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'lostPassword');
 * 
 * How to display in your controller's view:
 * echo $this->lostPassword;
 * 
 * 
 */
class MelisCommerceLostPasswordGetEmailPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceLostPasswordGetEmailPlugin';
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
        
        $translator = $this->getServiceLocator()->get('translator');
        
        $title = $translator->translate('tr_meliscommerce_plugin_lost_password_message');
        $message = '';
        
        
        $formData = $this->getFormData();
        
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['lost_password'])) ? $this->pluginFrontConfig['forms']['lost_password'] : array();
        $emailConfig = (!empty($formData['email'])) ? $formData['email'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $lostPassword = $factory->createForm($appConfigForm);
        
        // Value that trigger if the form is submitted or requested
        $is_submit = (!empty($formData['m_lost_password_get_email_is_submit'])) ? true : false;
        
        $data['m_email'] = (!empty($formData['m_email'])) ? $formData['m_email'] : '';
        $data['lost_password_reset_page_link'] = (!empty($formData['lost_password_reset_page_link'])) ? $formData['lost_password_reset_page_link'] : '';
        
        // Setting the Datas to Lost Password Form
        $lostPassword->setData($data);

        if ($is_submit)
        {
            $message = $translator->translate('tr_meliscommerce_general_unable_to_send_email');
            
            if ($lostPassword->isValid())
            {
                $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
                
                $clientPersonTbl = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
                
                if(!empty($data['m_email']))
                {
                    
                    // Checking if the Email entered is existing on the database
                    $clientPerson = $clientSrv->getClientPersonByEmail($data['m_email']);
                    if (empty($clientPerson))
                    {
                        $message = $translator->translate('tr_meliscommerce_client_email_not_exist');
                    }
                    else 
                    {
                        $personId = $clientPerson->cper_id;
                        $recoveryKey = $clientSrv->generatePsswordRecoveryKey();
                        $clientSrv->savePasswordRecoveryKey($personId, $recoveryKey);
                        
                        $changePassConfig = array(
                            'lostPasswordLink' => $data['lost_password_reset_page_link'],
                            'recoveryKey' => $recoveryKey
                        );
                        
                        // Adding Person details to email config
                        $emailConfig['email_to'] = $data['m_email'];
                        $emailConfig['email_to_name'] = $clientPerson->cper_firstname.' '.$clientPerson->cper_name;
                        
                        // Translating subject and content
                        $emailConfig['email_subject'] = $translator->translate($emailConfig['email_subject']);
                        $emailConfig['email_content'] = $translator->translate($emailConfig['email_content']);
                        
                        // Merging the possible tags to replace the content of the email
                        $emailConfig['email_content_tag_replace'] = array_merge($emailConfig['email_content_tag_replace'], $changePassConfig);
                        
                        // Sending email using MelisEngineSendMail Service
                        $sendMailSvc = $this->getServiceLocator()->get('MelisEngineSendMail');
                        $sendMailSvc->sendEmail(
                            $emailConfig['email_template_path'], $emailConfig['email_from'], $emailConfig['email_from_name'],
                            $emailConfig['email_to'], $emailConfig['email_to_name'], $emailConfig['email_subject'],
                            $emailConfig['email_content'], $emailConfig['email_content_tag_replace'], $emailConfig['email_reply_to']
                        );
                        
                        $message = $translator->translate('tr_meliscommerce_general_send_email_success');
                        $success = 1;
                        $lostPassword->get('m_email')->setValue('');
                    }
                }
            }
            else 
            {
                $errors = $lostPassword->getMessages();
            }
        }
        
        /**
         * As default form will created with the "m_lost_password_reset_is_submit" input having value of "1"
         * so that after form render this will ready for submission
         */
        $lostPassword->get('m_lost_password_get_email_is_submit')->setValue('1');
        
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'lostPassword' => $lostPassword,
            'title' => $title,
            'message' => $message,
            'success' => $success,
            'errors' => $errors
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
        $data['email'] = $this->pluginFrontConfig['email'];
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
            
            if (!empty($xml->lost_password_reset_page_link))
            {
                $configValues['lost_password_reset_page_link'] = (string)$xml->lost_password_reset_page_link;
            }
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
        
        if (!empty($parameters['lost_password_reset_page_link']))
        {
            $xmlValueFormatted .= "\t\t" . '<lost_password_reset_page_link><![CDATA[' . $parameters['lost_password_reset_page_link'] . ']]></lost_password_reset_page_link>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
