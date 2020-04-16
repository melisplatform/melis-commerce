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
use Laminas\Stdlib\ArrayUtils;
use Laminas\Mvc\Controller\Plugin\Redirect;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "billingAddress" plugin.
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
 * $plugin = $this->MelisCommerceBillingAddressPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceBillingAddressPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/address/billing'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'billingAddress');
 * 
 * How to display in your controller's view:
 * echo $this->billingAddress;
 * 
 * 
 */
class MelisCommerceBillingAddressPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceBillingAddressPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $addressType = 'BIL';
        $success = 0;
        $errors = array();
        $message = '';
        $data = array();
        $selectBillingAddress = null;
        $showDeleteButton = false;
        $billingAddressId = null;
        $clientAddress = array();
        $deletetAddress = false;
        
        $translator = $this->getServiceManager()->get('translator');
        
        $formData = $this->getFormData();
        
        $showSelectAddresData = ($formData['show_select_address_data']) ? true : false;
        
        // Creating Address form
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $billingAddressForm = $this->pluginFrontConfig['forms']['billing_address'];
        $billingAddress = $factory->createForm($billingAddressForm);
        
        /**
         * Checking if client has been authenticated
         */
        $melisComAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        if ($melisComAuthSrv->hasIdentity())
        {
            // Getting the current use Client Id and Person Id from session
            $clientId = $melisComAuthSrv->getClientId();
            $personId = $melisComAuthSrv->getPersonId();
            
            if ($formData['billing_address_delete_submit'] && !empty($formData['cadd_id']))
            {
                // Retrieving address select from list of addresses
                $clientSrv = $this->getServiceManager()->get('MelisComClientService');
                $clientBilAddress = $clientSrv->getClientPersonAddressByAddressId($personId, $formData['cadd_id']);
                
                if (!empty($clientBilAddress))
                {
                    // Deleting address
                    $delRes = $clientSrv->deleteClientAddress($formData['cadd_id']);
                    
                    if (!is_null($delRes))
                    {
                        $deletetAddress = true;
                    }
                }
            }
            
            // flag to create new Address
            $createNewAdd = false;
            
            // Checking if plugin config shows the List of addresses
            if($showSelectAddresData && !$deletetAddress)
            {
                // Checking if the List of addresses submitted to select a particular address
                $selectBillingAddressSubmit = ($formData['select_billing_addresses_submit']) ? true : false;
                if ($selectBillingAddressSubmit)
                {
                    $billingAddressId = $formData['select_billing_addresses'];
                    if ($billingAddressId != 'new_address')
                    {
                        // Retrieving address select from list of addresses
                        $clientSrv = $this->getServiceManager()->get('MelisComClientService');
                        $clientBilAddress = $clientSrv->getClientPersonAddressByAddressId($personId, $billingAddressId);
                        
                        $clientAddress = (Array) $clientBilAddress;
                        $billingAddressId = $clientAddress['cadd_id'];
                    }
                    else 
                    {
                        /**
                         * Flag to Create a new Address this will clear the Address form
                         * and ready to fillup 
                         */
                        $createNewAdd = true;
                    }
                }
                else
                {
                    $billingAddressId = 'new_address';
                }
            }
            
            // Value that trigger if the form is submitted or requested
            $is_submit = (!empty($formData['billing_address_save_submit'])) ? $formData['billing_address_save_submit'] : false;
            
            if ((!$is_submit && !$createNewAdd && empty($clientAddress)) || $deletetAddress)
            {
                $clientSrv = $this->getServiceManager()->get('MelisComClientService');
                $clientBilAddress = $clientSrv->getClientAddressesByClientPersonId($personId, $addressType);
                
                // Getting only the first Address of the client person
                if (!empty($clientBilAddress[0]))
                {
                    $clientAddress    = (Array) $clientBilAddress[0];
                    $billingAddressId = $clientAddress['cadd_id'];
                }
            }
            elseif (empty($clientAddress))
            {
                $clientAddress = $formData;
            }
            
            if ($deletetAddress)
            {
                $success = 1;
                $message = $translator->translate('tr_meliscommerce_plugin_billing_address_delete_success');
                $is_submit = false;
            }
            
            // Binding result data to the Address form
            $billingAddress->setData($clientAddress);
            
            if ($is_submit)
            {
                $billingAddressId = ($clientAddress['cadd_id']) ? $clientAddress['cadd_id'] : null;
                
                if ($billingAddress->isValid())
                {
                    $data = $billingAddress->getData();
                    
                    $data['cadd_client_id']     = $clientId;
                    $data['cadd_client_person'] = $personId;
                    
                    // Unset submit flag
                    unset($data['billing_address_save_submit']);
                    
                    /**
                     * Retrieving the id of the address type
                     * using the address type code
                     */
                    $clientAddTypeTbl = $this->getServiceManager()->get('MelisEcomClientAddressTypeTable');
                    $clientAddType = $clientAddTypeTbl->getEntryByField('catype_code', $addressType)->current();
                    $data['cadd_type'] = $clientAddType->catype_id;
                    
                    // Saving Client person delivery addrress
                    $clientSrv = $this->getServiceManager()->get('MelisComClientService');
                    $billingAddressId = $clientSrv->saveClientAddress($data, $billingAddressId);
                    
                    
                    if (!is_null($billingAddressId))
                    {
                        $success = 1;
                        $message = $translator->translate('tr_meliscommerce_checkout_billing_save_success');
                    }
                    else
                    {
                        $message = $translator->translate('tr_meliscommerce_general_error');
                    }
                }
                else 
                {
                    // $message = $translator->translate('tr_meliscommerce_general_error_occured');
                    $errors  = $billingAddress->getMessages();
                }
            }
            
            // Creating the list of Addresses form
            if($showSelectAddresData)
            {
                $selectBillingAddressForm = $this->pluginFrontConfig['forms']['select_billing_address'];
                $selectBillingAddress = $factory->createForm($selectBillingAddressForm);
                
                if (is_null($billingAddressId))
                {
                    $billingAddressId = 'new_address';
                }
                // Selecting the default Address
                $selectBillingAddress->get('select_billing_addresses')->setValue($billingAddressId);
                
                /**
                 * This input field set value in order to validate
                 * after submission of the form proivided of this plugin
                 */
                $selectBillingAddress->get('select_billing_addresses_submit')->setValue(true);
            }
        }
        
        /**
         * This input field set value in order to validate
         * after submission of the form proivided of this plugin
         */
        $billingAddress->get('billing_address_save_submit')->setValue(1);
        
        // Checking of delete button is to show
        if ($showSelectAddresData && $billingAddressId != 'new_address')
        {
            $showDeleteButton = true;
        }
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'selectBillingAddress' => $selectBillingAddress,
            'billingAddress' => $billingAddress,
            'showDeleteButton' => $showDeleteButton,
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
            
            if (!empty($xml->show_select_address_data))
            {
                $configValues['show_select_address_data'] = (string)$xml->show_select_address_data;
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
        
        if (!empty($parameters['show_select_address_data']))
        {
            $xmlValueFormatted .= "\t\t" . '<show_select_address_data><![CDATA[' . $parameters['show_select_address_data'] . ']]></show_select_address_data>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
