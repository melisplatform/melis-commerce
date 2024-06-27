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
use Laminas\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "Login" plugin.
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
 * $plugin = $this->MelisCommerceLoginPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/login/login'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'login');
 * 
 * How to display in your controller's view:
 * echo $this->login;
 * 
 * 
 */
class MelisCommerceLoginPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceLoginPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['meliscommerce_login'])) ? $this->pluginFrontConfig['forms']['meliscommerce_login'] : array();
        
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $loginForm = $factory->createForm($appConfigForm);
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $m_login = (!empty($this->pluginFrontConfig['m_login'])) ? $this->pluginFrontConfig['m_login'] : '';
        $m_password = (!empty($this->pluginFrontConfig['m_password'])) ? $this->pluginFrontConfig['m_password'] : '';
        $m_remeber_me = (!empty($this->pluginFrontConfig['m_remember_me'])) ? $this->pluginFrontConfig['m_remember_me'] : false;
        
        $is_submit = (!empty($this->pluginFrontConfig['m_login_is_submit'])) ? $this->pluginFrontConfig['m_login_is_submit'] : false;
        
        // Redirection key after login authentication success
        $redirection_link  = (!empty($this->pluginFrontConfig['m_redirection_link_ok'])) ? $this->pluginFrontConfig['m_redirection_link_ok'] : '';
        
        $success = 0;
        $message = null;
        $errors = array();
        
        $loginForm->setData($this->pluginFrontConfig);
        
        if ($is_submit)
        {
            if($loginForm->isValid())
            {
                /**
                 * Login using Commerce Authentication Service
                 */
                $melisComAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
                $result = $melisComAuthSrv->login($m_login, $m_password, $m_remeber_me);
                if ($result['success'] == 1)
                {
                    $success = 1;
                    
                    $clientId = $melisComAuthSrv->getClientId();
                    $clientKey = $melisComAuthSrv->getClientKey();
                    
                    $melisComBasketService = $this->getServiceManager()->get('MelisComBasketService');
                    // Preparing the client Basket, which is added to Persistent basket
                    $melisComBasketService->getBasket($clientId, $clientKey);
                    
                    /**
                     * This will redirect to the $redirection_link url 
                     * if the request is not from ajax request
                     */
                    if (!$this->getController()->getRequest()->isXmlHttpRequest())
                    {
                        $this->getController()->redirect()->toUrl($redirection_link);
                    }
                }
                
                $message = $result['message'];
            }
            else
            {
                // Retrieving the errors occured on form validation
                $errors = $loginForm->getMessages();
            }
        }
        
        /**
         * This input field set value in order to validate
         * after submission of the form proivided of this plugin
         */
        $loginForm->get('m_login_is_submit')->setValue(true);
        
        
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'login' => $loginForm,
            'redirect_link' => $redirection_link,
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
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
