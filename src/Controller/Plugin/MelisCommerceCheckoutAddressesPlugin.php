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
use Laminas\Session\Container;
use Laminas\Stdlib\ArrayUtils;
use Laminas\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "checkOutAddresses" plugin.
 * 
 * Please look inside app.plugins.products.php for possible awaited parameters
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
 * $plugin = $this->MelisCommerceCheckoutAddressesPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCheckoutCartPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'checkoutAddresses');
 * 
 * How to display in your controller's view:
 * echo $this->checkoutAddresses;
 * 
 * 
 */
class MelisCommerceCheckoutAddressesPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceCheckoutPlugin';
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
        $checkoutErrorMsg = '';
        /**
         * we will going to used this variable to make our form variable name dynamic,
         * form name must be end must be equal with this
         *
         * For example the form name below is $deliveryAddForm / $billingAddForm
         * they both end with AddForm
         */
        $postFormName = 'AddForm';

        $translator = $this->getServiceManager()->get('translator');

        $siteId = (!empty($this->pluginFrontConfig['m_add_site_id'])) ? $this->pluginFrontConfig['m_add_site_id'] : null;
        $overrideData = (!empty($this->pluginFrontConfig['m_add_override_data'])) ? $this->pluginFrontConfig['m_add_override_data'] : false;
        $saveAddresses = (isset($this->pluginFrontConfig['m_save_addresses'])) ? $this->pluginFrontConfig['m_save_addresses'] : true;
        $checkoutAddressUse = (isset($this->pluginFrontConfig['m_checkout_address'])) ? $this->pluginFrontConfig['m_checkout_address'] : 'Person';

        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $appConfigDeliveryAddForm = (!empty($this->pluginFrontConfig['forms']['delivery_address'])) ? $this->pluginFrontConfig['forms']['delivery_address'] : array();
        $appConfigDeliveryAddForm = $this->getFormMergedAndOrdered($appConfigDeliveryAddForm, 'checkout_delivery_address');

        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $deliveryAddForm = $factory->createForm($appConfigDeliveryAddForm);

        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $appConfigBillingAddForm = (!empty($this->pluginFrontConfig['forms']['billing_address'])) ? $this->pluginFrontConfig['forms']['billing_address'] : array();
        $appConfigBillingAddForm = $this->getFormMergedAndOrdered($appConfigBillingAddForm, 'checkout_billing_address');
        $billingAddForm = $factory->createForm($appConfigBillingAddForm);

        // Preparing the Container/Session of Commerce checkout
        $container = new Container('meliscommerce');
        if (!isset($container['checkout']))
        {
            $container['checkout'] = array();
        }

        //set the delivery method choose by the client ot save in the order
        $container['checkout'][$siteId]['deliveryMethod'] = $this->pluginFrontConfig['m_add_order_method'];

        $isSubmit = (!empty($this->pluginFrontConfig['m_add_is_submit'])) ? $this->pluginFrontConfig['m_add_is_submit'] : false;
        /**
         * we will going to used this to determine the form that we will
         * validate first
         *
         * we will going to used this also to make our form name dynamic
         * by concatenating it with the $postFormName variable
         * so that the result will be $billingAddForm if the value of this
         * variable is billing
         */
        $firstFormToValidate = (!empty($this->pluginFrontConfig['m_add_first_form_to_validate'])) ? $this->pluginFrontConfig['m_add_first_form_to_validate'] : 'delivery';
        $firstFormToValidate = strtolower($firstFormToValidate);
        /**
         * if the value of the is not billing nor delivery
         * set the default value to delivery
         */
        if(!in_array($firstFormToValidate, array('billing', 'delivery'))){
            $firstFormToValidate = 'delivery';
        }

        if ($isSubmit)
        {
            $sameAddress = (!empty($this->pluginFrontConfig['m_add_use_same_address'])) ? $this->pluginFrontConfig['m_add_use_same_address'] : 0;
            $container['checkout'][$siteId]['sameAddress'] = $sameAddress;
        }
        else
        {
            if (isset($container['checkout'][$siteId]['sameAddress']))
            {
                $sameAddress = $container['checkout'][$siteId]['sameAddress'];
            }
            else
            {
                $sameAddress = (!empty($this->pluginFrontConfig['m_add_use_same_address'])) ? $this->pluginFrontConfig['m_add_use_same_address'] : 0;
            }
        }

        /**
         * Getting the User identity using Commerce Authentication Service
         */
        $ecomAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
            $personId = $ecomAuthSrv->getPersonId();

            /**
             * Getting the Checkout addresses from session
             * that will fillup the address form
             */
            $deliveryAddFormSessData = array();
            if (!empty($container['checkout'][$siteId]['addresses']['addresses']['delivery']))
            {
                if ($container['checkout'][$siteId]['addresses']['addresses']['delivery']['success'])
                {
                    $deliveryAddFormSessDataTmp = $container['checkout'][$siteId]['addresses']['addresses']['delivery']['address'];
                    foreach ($deliveryAddFormSessDataTmp As $key => $val)
                    {
                        $deliveryAddFormSessData[str_replace('cadd_', 'm_add_delivery_', $key)] = $val;
                    }
                }
            }

            $clientSrv = $this->getServiceManager()->get('MelisComClientService');
            if ($checkoutAddressUse == 'Person')
                $delAddress = $clientSrv->getClientAddressesByClientPersonId($personId, 'DEL');
            else
                $delAddress = $clientSrv->getClientAddressesByClientId($clientId, 'DEL');

            if (empty($delAddress))
            {
                $deliveryAddForm->get('m_add_delivery_id')->setValue('new_address')->setAttribute('type', 'hidden');
            }

            /**
             * This fill up the delivery form
             * if delivery session is empty
             */
            $fillDelAdd = array();
            if(empty($deliveryAddFormSessData)){
                if(!empty($delAddress)) {
                    if(isset($delAddress[0])) {
                        foreach ($delAddress[0] As $key => $val) {
                            $fillDelAdd[str_replace('cadd_', 'm_add_delivery_', $key)] = $val;
                        }
                        $deliveryAddFormSessData = $fillDelAdd;
                    }
                }
            }

            /**
             * Set form data of Delivery Address
             */
            $deliveryAddFormData = ($isSubmit) ? $this->pluginFrontConfig : $deliveryAddFormSessData;
            $deliveryAddForm->setData($deliveryAddFormData);

            $deliverySelectAddress = (!empty($this->pluginFrontConfig['m_add_delivery_id'])) ? $this->pluginFrontConfig['m_add_delivery_id'] : null;
            if (!in_array($deliverySelectAddress, array('', 'new_address')))
            {
                /**
                 * If the checkout selection selected existing address
                 * this will retrieve from db and set to the Form
                 */
                $clientSrv = $this->getServiceManager()->get('MelisComClientService');
                if ($checkoutAddressUse == 'Person')
                    $clientPersonDelAdd = $clientSrv->getClientPersonAddressByAddressId($personId, $deliverySelectAddress);
                else 
                    $clientPersonDelAdd = $clientSrv->getClientAddressByAddressId($clientId, $deliverySelectAddress);

                if (!empty($clientPersonDelAdd))
                {
                    $personDelAdd = array();
                    foreach ($clientPersonDelAdd As $key => $val)
                    {
                        $personDelAdd[str_replace('cadd_', 'm_add_delivery_', $key)] = $val;
                    }

                    if (!$overrideData)
                    {
                        $deliveryAddForm->setData(ArrayUtils::merge($this->pluginFrontConfig, $personDelAdd));
                    }
                    else
                    {
                        $deliveryAddForm->setData(ArrayUtils::merge($personDelAdd, $this->pluginFrontConfig));
                    }
                }
            }

            $personBilAddress = $clientSrv->getClientAddressesByClientPersonId($personId, 'BIL');
            if (empty($personBilAddress))
            {
                $billingAddForm->get('m_add_billing_id')->setValue('new_address')->setAttribute('type', 'hidden');
            }

            /**
             * Getting the Checkout addresses from session
             * that will fillup the address form
             */
            $billingAddFormSessData = array();
            if (!empty($container['checkout'][$siteId]['addresses']['addresses']['billing']))
            {
                if ($container['checkout'][$siteId]['addresses']['addresses']['billing']['success'])
                {
                    $billingAddFormSessDataTmp = $container['checkout'][$siteId]['addresses']['addresses']['billing']['address'];
                    foreach ($billingAddFormSessDataTmp As $key => $val)
                    {
                        $billingAddFormSessData[str_replace('cadd_', 'm_add_billing_', $key)] = $val;
                    }
                }
            }

            /**
             * This fill up the billing form
             * if billing session is empty
             */
            $personFillBillAdd = array();
            if(empty($billingAddFormSessData)){
                if(!empty($personBilAddress)) {
                    if(isset($personBilAddress[0])) {
                        foreach ($personBilAddress[0] As $key => $val) {
                            $personFillBillAdd[str_replace('cadd_', 'm_add_billing_', $key)] = $val;
                        }
                        $billingAddFormSessData = $personFillBillAdd;
                    }
                }
            }

            /**
             * Set Form data of Billing Address
             */
            $billingAddFormData = ($isSubmit) ? $this->pluginFrontConfig : $billingAddFormSessData;
            $billingAddForm->setData($billingAddFormData);

            $billingSelectAddress = (!empty($this->pluginFrontConfig['m_add_billing_id'])) ? $this->pluginFrontConfig['m_add_billing_id'] : null;
            if (!in_array($billingSelectAddress, array('', 'new_address')))
            {
                /**
                 * If the checkout selection selected existing address
                 * this will retrieve from db and set to the Form
                 */
                $clientSrv = $this->getServiceManager()->get('MelisComClientService');
                if ($checkoutAddressUse == 'Person')
                    $clientPersonBilAdd = $clientSrv->getClientPersonAddressByAddressId($personId, $billingSelectAddress);
                else
                    $clientPersonBilAdd = $clientSrv->getClientAddressByAddressId($clientId, $billingSelectAddress);

                if (!empty($clientPersonBilAdd))
                {
                    $personBilAdd = array();
                    foreach ($clientPersonBilAdd As $key => $val)
                    {
                        $personBilAdd[str_replace('cadd_', 'm_add_billing_', $key)] = $val;
                    }

                    if(!$overrideData){
                        $billingAddForm->setData(ArrayUtils::merge($this->pluginFrontConfig, $personBilAdd));
                    }else {
                        $billingAddForm->setData(ArrayUtils::merge($personBilAdd, $this->pluginFrontConfig));
                    }
                }
            }
            // Getting the client basket list using Client key
            $melisComBasketService = $this->getServiceManager()->get('MelisComBasketService');
            $basketData = $melisComBasketService->getBasket($clientId, $clientKey);

            if (is_null($basketData)){
                $checkoutErrorMsg = $translator->translate('tr_meliscommerce_client_Checkout_cart_empty');
            }
        }
        else
        {
            $checkoutErrorMsg = $translator->translate('tr_meliscommerce_client_Checkout_no_identity');
        }

        if ($isSubmit)
        {
            if($firstFormToValidate == 'delivery'){
                $secondFormPreName = 'billing';
                $addType = 1;

            }else{
                $secondFormPreName = 'delivery';
                $addType = 2;
            }

            /**
             * make our address form dynamic
             */
            $formName = ${$firstFormToValidate.$postFormName};
            $formSecondName = ${$secondFormPreName.$postFormName};

            if (!$formName->isValid())
            {
                $errors = ArrayUtils::merge($errors, $formName->getMessages());
            }else{
                if ($sameAddress)
                {
                    $firstFormData = $formName->getData();
                    $secondFormData = array();

                    foreach ($firstFormData As $dKey => $dVal)
                    {
                        $secondFormData[str_replace('m_add_'.$firstFormToValidate.'_', 'm_add_'.$secondFormPreName.'_', $dKey)] = $dVal;
                    }
                    if (!empty($secondFormData['m_add_'.$secondFormPreName.'_type']))
                    {
                        $secondFormData['m_add_'.$secondFormPreName.'_type'] = $addType;
                    }

                    $formSecondName->setData($secondFormData);
                    //validate again the second form
                    if (!$formSecondName->isValid())
                    {
                        $errors = $formSecondName->getMessages();
                    }
                }else{
                    if (!$formSecondName->isValid())
                    {
                        $errors = $formSecondName->getMessages();
                    }
                }
            }

            if (empty($errors))
            {
                /**
                 * Putting back index to original name base on DB column
                 * before validating addresses and adding to checkout container/session
                 */
                $personDelAdd = array();
                $personBilAdd = array();
                if($firstFormToValidate == "delivery") {
                    foreach ($deliveryAddForm->getData() As $key => $val) {
                        $personDelAdd[str_replace('m_add_delivery_', 'cadd_', $key)] = $val;
                        if ($sameAddress) {
                            $personBilAdd[str_replace('m_add_delivery_', 'cadd_', $key)] = $val;
                        }
                    }
                    /**
                     * if they are not the same address
                     * we need also to change the billing data
                     */
                    if(!$sameAddress) {
                        foreach ($billingAddForm->getData() As $key => $val) {
                            $personBilAdd[str_replace('m_add_billing_', 'cadd_', $key)] = $val;
                        }
                    }

                    if ($personDelAdd['cadd_id'] == 'new_address') {
                        unset($personDelAdd['cadd_id']);
                    }
                }else{
                    foreach ($billingAddForm->getData() As $key => $val) {
                        $personBilAdd[str_replace('m_add_billing_', 'cadd_', $key)] = $val;
                        if ($sameAddress) {
                            $personDelAdd[str_replace('m_add_billing_', 'cadd_', $key)] = $val;
                        }
                    }
                    /**
                     * if they are not the same address
                     * we need also to change the delivery data
                     */
                    if(!$sameAddress){
                        foreach ($deliveryAddForm->getData() As $key => $val) {
                            $personDelAdd[str_replace('m_add_delivery_', 'cadd_', $key)] = $val;
                        }
                    }
                    if ($personBilAdd['cadd_id'] == 'new_address')
                    {
                        unset($personBilAdd['cadd_id']);
                    }
                }

                /**
                 * Unsetting datas that are not needed to store
                 * to session after address validation
                 */
                unset($personBilAdd['m_checkout_step']);
                unset($personBilAdd['m_add_is_submit']);
                unset($personDelAdd['m_checkout_step']);
                unset($personDelAdd['m_add_is_submit']);

                // Validating addresses using Checkout service
                $melisComOrderCheckoutService = $this->getServiceManager()->get('MelisComOrderCheckoutService');
                $melisComOrderCheckoutService->setSiteId($siteId);
                $validatedAddresses = $melisComOrderCheckoutService->validateAddresses($personDelAdd, $personBilAdd);

                if ($validatedAddresses['success'] != true)
                {
                    foreach ($validatedAddresses['addresses'] As $key => $val)
                    {
                        if (in_array($key, array('delivery', 'billing')))
                        {
                            if (!empty($validatedAddresses['addresses'][$key]['error']))
                            {
                                $errors[$key] = array(
                                    'label' => $translator->translate('tr_meliscommerce_order_checkout_address_'.$key),
                                    $key.'_err_msg' => $translator->translate('tr_'.$validatedAddresses['addresses'][$key]['error'])
                                );
                            }
                        }
                    }
                }

                if ($validatedAddresses['success'] == true)
                {
                    $clientSrv = $this->getServiceManager()->get('MelisComClientService');

                    $deliveryAddId = null;

                    $checkoutAddresses = $validatedAddresses['addresses'];

                    if ($saveAddresses) {

                        krsort($checkoutAddresses);
                        foreach ($checkoutAddresses As $key => $val) {
                            $val['address']['cadd_client_id'] = $container['checkout'][$siteId]['clientId'];
    
                            if (in_array($deliverySelectAddress, ['', 'new_address']))
                                $val['address']['cadd_client_person'] = $container['checkout'][$siteId]['contactId']; // TODO need to confirm in other cases
    
                            $addId  = null;
    
                            if (!empty($val['address']['cadd_id']) && $val['address']['cadd_id'] != 'new_address' && $overrideData)
                            {
                                $addId = $val['address']['cadd_id'];
                            }
    
                            if ($key == 'delivery')
                            {
                                $addId = $clientSrv->saveClientAddress($val['address'], $addId);
                                $deliveryAddId = $addId;
                            }
                            else
                            {
                                if ($sameAddress)
                                {
                                    $addId = $deliveryAddId;
                                }
                                else
                                {
                                    $addId = $clientSrv->saveClientAddress($val['address'], $addId);
                                }
                            }
    
                            if ($checkoutAddressUse == 'Person')
                                $validatedAddresses['addresses'][$key]['address'] = (Array) $clientSrv->getClientPersonAddressByAddressId($personId, $addId);
                            else
                                $validatedAddresses['addresses'][$key]['address'] = (Array) $clientSrv->getClientAddressByAddressId($clientId, $addId);
                        }
                    }

                    $container['checkout'][$siteId]['addresses'] = $validatedAddresses;
                    $success = 1;
                }
            }
        }

        $deliveryAddForm->get('m_add_is_submit')->setvalue(1);
        $deliveryAddForm->get('m_add_delivery_type')->setvalue(2);
        $billingAddForm->get('m_add_is_submit')->setvalue(1);
        $billingAddForm->get('m_add_billing_type')->setvalue(1);

        $viewVariables = array(
            'checkoutDeliveryAddress' => $deliveryAddForm,
            'checkoutBillingAddress' => $billingAddForm,
            'sameAddress' => $sameAddress,
            'checkoutErrorMsg' => $checkoutErrorMsg,
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
            
            if (!empty($xml->m_add_site_id))
            {
                $configValues['m_add_site_id'] = (string)$xml->m_add_site_id;
            }
            
            if (isset($xml->m_add_use_same_address))
            {
                $configValues['m_add_use_same_address'] = (string)$xml->m_add_use_same_address;
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
        
        if (!empty($parameters['m_add_site_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_add_site_id><![CDATA[' . $parameters['m_add_site_id'] . ']]></m_add_site_id>';
        }
        
        if (isset($parameters['m_add_use_same_address']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_add_use_same_address><![CDATA[' . $parameters['m_add_use_same_address'] . ']]></m_add_use_same_address>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}