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
use Zend\Stdlib\ArrayUtils;
use Zend\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "checkoutSummary" plugin.
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
 * $plugin = $this->MelisCommerceCheckoutSummaryPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCheckoutSummaryPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'checkoutSummary');
 * 
 * How to display in your controller's view:
 * echo $this->checkoutSummary;
 * 
 * 
 */
class MelisCommerceCheckoutSummaryPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceCheckoutSummaryPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $checkoutCart = array();
        $currency = '';
        $subTotal = 0;
        $totalDiscount = 0;
        $discountInfo = '';
        $couponMsg = '';
        $shippingTotal = 0;
        $couponCode = '';
        $total = 0;
        $hasCartItems = false;
        $hasErr = false;
        $confirm_success = 0;
        $checkoutErrorMsg = '';
        
        $translator = $this->getServiceLocator()->get('translator');
        
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        $siteId = (!empty($this->pluginFrontConfig['m_summary_site_id'])) ? $this->pluginFrontConfig['m_summary_site_id'] : null;

        /**
         * Getting the User identity using Commerce Authentication Service
         */
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
            $melisComOrderCheckoutService = $this->getServiceLocator()->get('MelisComOrderCheckoutService');
            $melisComOrderCheckoutService->setSiteId($siteId);
            $clientOrderCost = $melisComOrderCheckoutService->computeAllCosts($clientId);
            $validatedBasket = $melisComOrderCheckoutService->validateBasket($clientId);
            
            if (isset($clientOrderCost['costs']['order']))
            {
                $clientOrder = $clientOrderCost['costs']['order'];
                
                if (isset($clientOrder['details']))
                {
                    $clientOrderVariant =  $clientOrder['details'];
                    
                    if (!empty($clientOrderVariant))
                    {
                        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
                        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
                        
                        foreach ($clientOrderVariant As $key => $val)
                        {
                            $variantErr = '';
                            if (!empty($validatedBasket['basket']['ko'][$key]['error']))
                            {
                                $variantErr = $translator->translate('tr_'.$validatedBasket['basket']['ko'][$key]['error']);
                                $hasErr = true;
                            }
                            
                            $variantId = $key;
                            $variant = $melisComVariantService->getVariantById($variantId);
                            $productId = $variant->getVariant()->var_prd_id;
                            $varSku = $variant->getVariant()->var_sku;
                            
                            $currency = $val['price_details']['currency_symbol'];
                            
                            $data = array(
                                'var_id' => $variantId,
                                'var_sku' => $varSku,
                                'var_quantity' => $val['quantity'],
                                'var_currency_symbol' => $currency,
                                'var_price' => number_format($val['unit_price'], 2),
                                'var_product_name' => $melisComProductService->getProductName($productId, $langId),
                                'var_discount' => $val['discount'],
                                'var_total' => number_format($val['total_price'], 2),
                                'var_err' => $variantErr
                            );
                            
                            array_push($checkoutCart, $data);
                        }
                    }
                }
                
                if (isset($clientOrder['totalWithoutCoupon']))
                {
                    $subTotal = $clientOrder['totalWithoutCoupon'];
                }
                
                if (isset($clientOrderCost['costs']['total']))
                {
                    $total = $clientOrderCost['costs']['total'];
                }
                
                if (isset($clientOrderCost['costs']['shipment']['total']))
                {
                    $shippingTotal = $clientOrderCost['costs']['shipment']['total'];
                }
                
                if (isset($clientOrderCost['costs']['order']['orderDiscount']))
                {
                    $totalDiscount = $clientOrderCost['costs']['order']['orderDiscount'];
                }
            }
            
            // Getting the client basket list using Client key
            $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
            $basketData = $melisComBasketService->getBasket($clientId, $clientKey);
            
            if (is_null($basketData)){
                $checkoutErrorMsg = $translator->translate('tr_meliscommerce_client_Checkout_cart_empty');
            }
        }
        else
        {
            $checkoutErrorMsg = $translator->translate('tr_meliscommerce_client_Checkout_no_identity');
        }
        
        $viewVariables = array(
            'checkoutCart' => $checkoutCart,
            'checkoutCartSubTotal' => $currency.number_format($subTotal, 2),
            'checkoutCartDiscount' => $currency.number_format($totalDiscount, 2),
            'checkoutCartCouponCode' => $couponCode,
            'checkoutCartDiscountInfo' => $discountInfo,
            'checkoutCartCouponErrMsg' => $couponMsg,
            'checkoutShipping' => $currency.number_format($shippingTotal, 2),
            'checkoutCartTotal' => $currency.number_format($total, 2),
            'checkoutHasErr' => $hasErr,
            'checkoutErrorMsg' => $checkoutErrorMsg,
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
            
            if (!empty($xml->m_summary_site_id))
            {
                $configValues['m_summary_site_id'] = (string)$xml->m_summary_site_id;
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
        
        if (!empty($parameters['m_summary_site_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_summary_site_id><![CDATA[' . $parameters['m_summary_site_id'] . ']]></m_summary_site_id>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        var_dump($xmlValueFormatted);
        
        return $xmlValueFormatted;
    }
}
