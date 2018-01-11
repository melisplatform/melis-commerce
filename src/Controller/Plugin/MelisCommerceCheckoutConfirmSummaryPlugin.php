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
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\ArrayUtils;
/**
 * This plugin implements the business logic of the
 * "checkoutConfirmSummary" plugin.
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
 * $plugin = $this->MelisCommerceCheckoutConfirmSummaryPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCheckoutConfirmSummaryPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'checkoutConfirmSummary');
 * 
 * How to display in your controller's view:
 * echo $this->checkoutConfirmSummary;
 * 
 * 
 */
class MelisCommerceCheckoutConfirmSummaryPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceCheckoutConfirmSummaryPlugin';
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
        $orderId = null;
        
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $siteId = (!empty($this->pluginFrontConfig['m_conf_summary_site_id'])) ? $this->pluginFrontConfig['m_conf_summary_site_id'] : 1;
        var_dump($siteId);
        /**
         * Login using the Commerce Athentication Service
         */
        $melisComAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $clientId = $melisComAuthSrv->getClientId();
        
        /**
         * Checkout Order validation before proceed to payment
         * if this will success this will create order record with temporary Order status
         * else this will show errors
         * 
         * Return value structure of checkoutStep1_prePayment method:
         * array(
         *      'success' => true/false
         *      'clientId' => xx,
         *      'orderId' => xx,
         *      'errors' => array(
         *          'basket' => BasketValidityArray,
         *          'addresses' => ShipmentCostArray,
         *          'costs' => OrderCostArray
         *      ),
         * )
         * 
         */
        $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
        $melisComOrderCheckoutService->setSiteId($siteId);
        $checkoutAddOrderValidation = $melisComOrderCheckoutService->checkoutStep1_prePayment($clientId);
        
        if ($checkoutAddOrderValidation['success'])
        {
            $success = 1;
            $orderId = $checkoutAddOrderValidation['clientId'];
        }
        else
        {
            $translator = $this->getServiceLocator()->get('translator');
            foreach ($checkoutAddOrderValidation['errors'] As $key => $val)
            {
                switch ($key)
                {
                    case 'basket':
                        
                        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
                        
                        $errors[$key] = array();
                        $prdName = '';
                        $errMsg = '';
                        if (!empty($val))
                        {
                            foreach ($val As $eKey => $eVal)
                            {
                                foreach ($eVal As $vKey => $vVal)
                                {
                                    if ($vKey == 'error')
                                    {
                                        $errMsg = $translator->translate('tr_'.$vVal);
                                    }
                                    else
                                    {
                                        $variantBasket = $vVal->getVariant();
                                        $variant = $variantBasket->getVariant();
                                        $prdName = $melisComProductService->getProductName($variant->var_prd_id, $langId).' ('.$variant->var_sku.')';
                                    }
                                }
                                
                                $errors[$key][$eKey] = $prdName.' : '.$errMsg;
                            }
                        }
                        
                        break;
                    case 'addresses':
                        
                        if (!empty($val))
                        {
                            $errors[$key] = $val;
                        }
                        
                        break;
                        
                    case 'costs' :
                        
                        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
                        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
                        
                        if (!empty($val['order']['errors']))
                        {
                            foreach ($val['order']['errors'] As $cKey => $cval)
                            {
                                $variantEntity = $melisComVariantService->getVariantById($cKey);
                                $variant = $variantEntity->getVariant();
                                $prdName = $melisComProductService->getProductName($variant->var_prd_id, $langId).' ('.$variant->var_sku.')';
                                $errors[$key][$cKey] = $prdName.' : '.$translator->translate('tr_'.$cval);
                            }
                        }
                        
                        break;
                }
            }
        }
        
        $viewVariables = array(
            'orderId' => $orderId,
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
            
            if (!empty($xml->m_conf_summary_site_id))
            {
                $configValues['m_conf_summary_site_id'] = (string)$xml->m_conf_summary_site_id;
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
        
        if (!empty($parameters['m_conf_summary_site_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_conf_summary_site_id><![CDATA[' . $parameters['m_conf_summary_site_id'] . ']]></m_conf_summary_site_id>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
