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
use Zend\Code\Scanner\Util;
use Zend\Stdlib\ArrayUtils;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "Registration" plugin.
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
 * $plugin = $this->MelisCommerceRegisterPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/registration/regitration'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'regitration');
 * 
 * How to display in your controller's view:
 * echo $this->registration;
 * 
 * 
 */
class MelisCommerceRegisterPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceRegisterPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['meliscommerce_registration'])) ? $this->pluginFrontConfig['forms']['meliscommerce_registration'] : array();
        $appConfigForm = $this->getFormMergedAndOrdered($appConfigForm, 'meliscommerce_registration');

        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $registrationForm = $factory->createForm($appConfigForm);
        $melisComClientService = $this->getServiceLocator()->get('MelisComClientService');
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $is_submit              = (!empty($this->pluginFrontConfig['m_registration_is_submit'])) ? $this->pluginFrontConfig['m_registration_is_submit'] : false;
        $data['m_autologin']    = (!empty($this->pluginFrontConfig['m_autologin'])) ? $this->pluginFrontConfig['m_autologin'] : true;
        $data['m_redirection_link_ok']      = (!empty($this->pluginFrontConfig['m_redirection_link_ok'])) ? $this->pluginFrontConfig['m_redirection_link_ok'] : '';
        
        // get submitted form values
        foreach($registrationForm->getElements() as $key => $val){
            $data[$key] = (!empty($this->pluginFrontConfig[$key])) ? $this->pluginFrontConfig[$key] : null;
        }
        
        $redirection_link = $data['m_redirection_link_ok'];
        
        $registrationForm->setData($data);
        
        $success = 0;
        $errors = array();
        if ($is_submit)
        {

            $isExist = false;
            if(!empty($data['cper_email'])) {
                // Checking if the Email entered is existing on the database
                $clientPerson = $melisComClientService->getClientPersonByEmail($data['cper_email']);

                if ($clientPerson)
                {
                    $isExist = true;
                    $translator = $this->getServiceLocator()->get('translator');
                    $errors['cper_email'] = array(
                        'label' => $translator->translate('tr_meliscommerce_client_Contact_email_address'),
                        'emailExist' => $translator->translate('tr_meliscommerce_client_Contact_email_address_exist'),
                    );
                }
            }

            if($registrationForm->isValid())
            {
                // Preparing the client data
                $client = array(
                    'cli_status'        => 1,
                    'cli_country_id'    => $data['m_country']
                );

                // Getting the language id from CMS to Commerce language
                $container = new Container('melisplugins');
                $langLocale = $container['melis-plugins-lang-locale'];
                $ecomLang = $this->getServiceLocator()->get('MelisEcomLangTable');
                $ecomLangData = $ecomLang->getEntryByField('elang_locale', $langLocale)->current();
                
                // Preparing the person data
                $tmpData = [];
                $tmpData['cper_status'] = 1;
                $tmpData['cper_is_main_person'] = 1;
                $tmpData['cper_lang_id'] = $ecomLangData->elang_id;
                $exclude = ['m_autologin', 'm_redirection_link_ok', 'cper_password2', 'm_country', 'm_registration_is_submit'];
                
                foreach($data as $key => $value) {
                    if(!in_array($key, $exclude)) {
                        $tmpData[$key] = $value;
                    }
                }

                $person[] = $tmpData;

                if (!$isExist)
                {
                    // Saving Client data using Client Servive
                    $success = (int) $melisComClientService->saveClient($client, $person);
                    
                    /**
                     * if the autologgin has true/1 value 
                     * this will automatically loggin after registration
                     */
                    if ($data['m_autologin'])
                    {
                        /**
                         * Login using Commerce Authentication Service
                         */
                        $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
                        $melisComAuthSrv->login($data['cper_email'], $data['cper_password']);

                        // This will transfer basket items from anonymous to persistent basket if there is one
                        $clientId = $melisComAuthSrv->getClientId();
                        $clientKey = $melisComAuthSrv->getClientKey();

                        $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
                        // Preparing the client Basket, which is added to Persistent basket
                        $melisComBasketService->getBasket($clientId, $clientKey);
                    }
                    
                    /**
                     * This will redirect to the $redirection_link url
                     * if the request is not from ajax request
                     */
                    if (!$this->getController()->getRequest()->isXmlHttpRequest())
                    {
                        $this->getController()->redirect()->toUrl($redirection_link);
                    }
                }
            }
            else
            {
                if (!empty($errors))
                {
                    $errors = ArrayUtils::merge($errors, $registrationForm->getMessages());
                }
                else
                {
                    $errors = $registrationForm->getMessages();
                }
            }
        }
        
        /**
         * This input field set value in order to validate
         * after submission of the form proivided of this plugin
         */
        $registrationForm->get('m_registration_is_submit')->setValue(true);
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'registration' => $registrationForm,
            'redirect_link' => $redirection_link,
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
        return parent::getFormData();
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
            
            if (!empty($xml->m_redirection_link_ok))
            {
                $configValues['m_redirection_link_ok'] = (string)$xml->m_redirection_link_ok;
            }
            
            if (!empty($xml->m_autologin))
            {
                $configValues['m_autologin'] = (string)$xml->m_autologin;
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
        
        if (!empty($parameters['m_redirection_link_ok']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_redirection_link_ok><![CDATA[' . $parameters['m_redirection_link_ok'] . ']]></m_redirection_link_ok>';
        }
        
        if (!empty($parameters['m_autologin']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_autologin><![CDATA[' . $parameters['m_autologin'] . ']]></m_autologin>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
