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

use Laminas\Mvc\Controller\Plugin\Redirect;
use Laminas\Stdlib\ArrayUtils;
use Laminas\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "orderMessages" plugin.
 * 
 * Please look inside app.plugins.php for possible awaited parameters
 * in front and back function calls.
 * 
 * front() and back() are the only functions to create / update.
 * front() generates the website view
 * 
 * Configuration can be found in $pluginConfig / $pluginFrontConfig / $pluginBackConfig
 * Configuration is automatically merged with the parameters provided when calling the plugin.
 * Merge detects automatically from the route if rendering must be done for front or back.
 * 
 * How to call this plugin without parameters:
 * $plugin = $this->MelisCommerceOrderMessagesPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceOrderMessagesPlugin();
 * $parameters = array(
 *      'template_path' => 'your-site-folder/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'orderMessages');
 * 
 * How to display in your controller's view:
 * echo $this->orderMessages;
 * 
 * 
 */
class MelisCommerceOrderMessagesPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceOrderMessagesPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $clientId = null;
        $messages = array();
        $errors = array();
        $success = 0;
        
        $translator = $this->getServiceManager()->get('translator');
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $clientSvc = $this->getServiceManager()->get('MelisComClientService');
        $ecomAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        
        $orderId        = !empty($this->pluginFrontConfig['m_om_order_id'])           ? $this->pluginFrontConfig['m_om_order_id'] : null;
        $message        = !empty($this->pluginFrontConfig['m_om_message'])            ? $this->pluginFrontConfig['m_om_message'] : '';
        $returnId       = !empty($this->pluginFrontConfig['m_om_pret_id'])            ? $this->pluginFrontConfig['m_om_pret_id'] : null;
        $msgType        = !empty($this->pluginFrontConfig['m_om_message_type'])       ? $this->pluginFrontConfig['m_om_message_type'] : 'MSG';
        $isSubmit       = !empty($this->pluginFrontConfig['m_om_message_is_submit'])  ? $this->pluginFrontConfig['m_om_message_is_submit'] : 0;
        $appConfigForm  = (!empty($this->pluginFrontConfig['forms']['meliscommerce_order_add_message_form'])) ? $this->pluginFrontConfig['forms']['meliscommerce_order_add_message_form'] : array();

        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $addMessageForm = $factory->createForm($appConfigForm);
        
        $addMessageForm->setData(array('m_om_order_id' => $orderId));        
        if ($isSubmit) 
        {
            if ($ecomAuthSrv->hasIdentity()) 
            {
                $addMessageForm->setData(array('m_om_order_id' => $orderId, 'm_om_message' => $message, 'm_om_pret_id' => $returnId));
                
                if ($addMessageForm->isValid()) 
                {
                    $addMessageForm->get('m_om_message')->setValue(null);
                    
                    $data = $addMessageForm->getData();
                    $messageData = array(
                        'omsg_order_id' => $data['m_om_order_id'],
                        'omsg_pret_id' => $data['m_om_pret_id'],
                        'omsg_client_id' => $ecomAuthSrv->getClientId(),
                        'omsg_client_person_id' => $ecomAuthSrv->getPersonId(),
                        'omsg_message' => $data['m_om_message'],
                        'omsg_date_creation' => date('Y-m-d H:i:s'),
                    );
                    
                    $messageId = $orderSvc->saveOrderMessage($messageData);
                     
                    $success = 1;
                    if (empty($messageId)) 
                    {
                        $errors['genError'] = array(
                            'genError' => $translator->translate('tr_meliscommerce_general_error')
                        );
                        $success = 0;
                    }
                    
                } 
                else 
                {
                    $errors = $addMessageForm->getMessages();
                }
            }
            
        }
        
        if(!empty($orderId))
        {
            foreach($orderSvc->getOrderMessageByOrderId($orderId, $msgType) as $message)
            {
                $tmp = array();
                $tmp = $message->getArrayCopy();
                
                if(empty($message->omsg_user_id))
                {
                    // get clients
                    
                    $client = $clientSvc->getClientByIdAndClientPerson($message->omsg_client_id, $message->omsg_client_person_id);
                    $clientPerson = $client->getPersons()[0];
                    
                    $tmp['firstName'] = $clientPerson->cper_firstname;
                    $tmp['lastName'] = $clientPerson->cper_name;
                    $tmp['middleName'] = $clientPerson->cper_middle_name;
                    
                }
                else
                {
                    // get admin details
                    $tmp['firstName'] = 'Admin';
                    $tmp['lastName'] = '';
                    $tmp['middleName'] = '';
                }
                
                $messages[] = $tmp;
            }
        }
        
        /**
         * This input field set value in order to validate
         * after submission of the form proivided of this plugin
         */
        $addMessageForm->get('m_om_message_is_submit')->setValue(true);
        
        $viewVariables = array(
            'orderMessages' => $messages,
            'addMessageForm' => $addMessageForm,
            'success' => $success,
            'errors' => $errors,
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
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig = $this->pluginBackConfig['modal_form'];
        
        $response = [];
        $render   = [];
        if (!empty($formConfig))
        {
            foreach ($formConfig as $formKey => $config)
            {
                $form = $factory->createForm($config);
                $request = $this->getServiceManager()->get('request');
                $parameters = $request->getQuery()->toArray();
                
                if (!isset($parameters['validate']))
                {
                    $form->setData($this->getFormData());
                    $viewModelTab = new ViewModel();
                    $viewModelTab->setTemplate($config['tab_form_layout']);
                    $viewModelTab->modalForm = $form;
                    $viewModelTab->formData   = $this->getFormData();
                    
                    $viewRender = $this->getServiceManager()->get('ViewRenderer');
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
                    
                    $post = $request->getPost()->toArray();
                    
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
        $data = $this->pluginFrontConfig;
        
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
            
            if (!empty($xml->m_om_order_id))
            {
                $configValues['m_om_order_id'] = (string)$xml->m_om_order_id;
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
        
        if (!empty($parameters['m_om_order_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_om_order_id><![CDATA[' . $parameters['m_om_order_id'] . ']]></m_om_order_id>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
