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

use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\ArrayUtils;
/**
 * This plugin implements the business logic of the
 * "account" plugin.
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
 * $plugin = $this->MelisCommerceAccountPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceAccountPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/client/account'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'account');
 * 
 * How to display in your controller's view:
 * echo $this->account;
 * 
 * 
 */
class MelisCommerceAccountPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceAccountPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $pluginManager = $this->getServiceLocator()->get('ControllerPluginManager');
        
        $data = $this->getFormData();
        
        // Getting custom param for Profile Plugin
        $profileParam = (!empty($data['profile_parameter'])) ? $data['profile_parameter'] : array();
        $clientProfilePlugin = $pluginManager->get('MelisCommerceProfilePlugin');
        $profileParam = ArrayUtils::merge($profileParam, array('id' => 'userProfile_'.$data['id'], 'pageId' => $data['pageId']));
        $clientProfile = $clientProfilePlugin->render($profileParam);
        
        // Getting custom param for Delivery Address Plugin
        $clientDeliveryAddressParam = (!empty($data['delivery_address_parameter'])) ? $data['delivery_address_parameter'] : array();
        $clientDeliveryAddressPlugin = $pluginManager->get('MelisCommerceDeliveryAddressPlugin');
        $clientDeliveryAddressParam = ArrayUtils::merge($clientDeliveryAddressParam, array('id' => 'userDeliveryAddress_'.$data['id'], 'pageId' => $data['pageId']));
        $clientDeliveryAddress = $clientDeliveryAddressPlugin->render($clientDeliveryAddressParam);
        
        // Getting custom param for Billing Address Plugin
        $clientBillingAddressParam = (!empty($data['billing_address_parameter'])) ? $data['billing_address_parameter'] : array();
        $clientBillingAddressPlugin = $pluginManager->get('MelisCommerceBillingAddressPlugin');
        $clientBillingAddressParam = ArrayUtils::merge($clientBillingAddressParam, array('id' => 'userBillingAddress_'.$data['id'], 'pageId' => $data['pageId']));
        $clientBillingAddress = $clientBillingAddressPlugin->render($clientBillingAddressParam);
        
        // Getting custom param for Cart Plugin
        $clientCartParam = (!empty($data['cart_parameter'])) ? $data['cart_parameter'] : array();
        $clientCartPlugin = $pluginManager->get('MelisCommerceCartPlugin');
        $clientCartParam = ArrayUtils::merge($clientCartParam, array('id' => 'cartPlugin_'.$data['id'], 'pageId' => $data['pageId']));
        $clientCart = $clientCartPlugin->render($clientCartParam);
        
        // Getting custom param for Order list Plugin
        $clientOrderParameter =(!empty($data['order_list_paremeter'])) ? $data['order_list_paremeter'] : array();
        $clientOrderPlugin = $pluginManager->get('MelisCommerceOrderHistoryPlugin');
        $clientOrderParameter = ArrayUtils::merge($clientOrderParameter, array('id' => 'cartPlugin_'.$data['id'].uniqid(), 'pageId' => $data['pageId']));
        $clientOrderHistory = $clientOrderPlugin->render($clientOrderParameter);
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'profile' => $clientProfile,
            'deliveryAddress' => $clientDeliveryAddress,
            'billingAddress' => $clientBillingAddress,
            'cart' => $clientCart,
            'orderHistory' => $clientOrderHistory,
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    /**
     * This method retrieving the address details of a client logged in
     * $parma $addId, Id of the address
     * 
     * @return MelisEcomClientAddress
     */
    public function getSelectedAddressDetailsAction($addId)
    {
        $address  = array();
         
        $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        
        if ($melisComAuthSrv->hasIdentity())
        {
            $personId = $melisComAuthSrv->getPersonId();
            $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
            $address = $clientSrv->getClientPersonAddressByAddressId($personId, $addId);
        }
         
        return $address;
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
                            $errors = ArrayUtil::merge($errors, $form->getMessages());
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
//         $data = parent::getFormData();
        $data['profile_parameter'] = (!empty($this->pluginFrontConfig['profile_parameter'])) ? $this->pluginFrontConfig['profile_parameter'] : array();
        $data['delivery_address_parameter'] = (!empty($this->pluginFrontConfig['delivery_address_parameter'])) ? $this->pluginFrontConfig['delivery_address_parameter'] : array();
        $data['billing_address_parameter'] = (!empty($this->pluginFrontConfig['billing_address_parameter'])) ? $this->pluginFrontConfig['billing_address_parameter'] : array();
        $data['cart_parameter'] = (!empty($this->pluginFrontConfig['cart_parameter'])) ? $this->pluginFrontConfig['cart_parameter'] : array();
        $data['order_list_paremeter'] = (!empty($this->pluginFrontConfig['order_list_paremeter'])) ? $this->pluginFrontConfig['order_list_paremeter'] : array();
        
        unset($this->pluginFrontConfig['profile_parameter']);
        unset($this->pluginFrontConfig['delivery_address_parameter']);
        unset($this->pluginFrontConfig['billing_address_parameter']);
        unset($this->pluginFrontConfig['cart_parameter']);
        unset($this->pluginFrontConfig['order_list_paremeter']);
        
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
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
