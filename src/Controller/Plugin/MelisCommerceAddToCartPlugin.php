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
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "add to cart" plugin.
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
 * $plugin = $this->MelisCommerceAddToCartPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceAddToCartPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCms/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'addToCart');
 * 
 * How to display in your controller's view:
 * echo $this->addToCart;
 * 
 * 
 */
class MelisCommerceAddToCartPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceAddToCartPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $errors = array();
        $hasStock = false;
        $currentQty = 0;
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $variantId = ($this->pluginFrontConfig['m_variant_id']) ? $this->pluginFrontConfig['m_variant_id'] : null;
        $countryId = ($this->pluginFrontConfig['m_variant_country']) ? $this->pluginFrontConfig['m_variant_country'] : 0;
        $quantity = ($this->pluginFrontConfig['m_variant_quantity']) ? $this->pluginFrontConfig['m_variant_quantity'] : 1;
        
        $is_submit = ($this->pluginFrontConfig['m_add_to_cart_is_submit']) ? true : false;
        
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $addToCartForm = $factory->createForm($this->pluginFrontConfig['forms']['meliscommerce_add_to_cart_form']);
        
        $ecomAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        $basketSrv = $this->getServiceManager()->get('MelisComBasketService');
        $translator = $this->getServiceManager()->get('translator');
        
        $clientKey = $ecomAuthSrv->getId();
        $clientId = null;
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
        }
        
        $currentBasket = $basketSrv->getBasket($clientId, $clientKey);
        if(!empty($currentBasket))
        {
            foreach($currentBasket as $item)
            {
                if($item->getVariantId() == $variantId)
                {
                    $currentQty = $item->getQuantity();
                }
            }
        }
        
        $data['m_variant_id'] = $variantId;
        $data['m_variant_quantity'] = $quantity;
        $data['m_variant_country'] = $countryId;
        
        /**
         * Retrieving the Stock of the variant
         */
        if (is_numeric($quantity))
        {    
            $addToCartForm->setData($data);
            
            /**
             * Retrieving the Stock of the variant
             */
            $varStock = 0;
            if ($variantId)
            {
                $variantSrv = $this->getServiceManager()->get('MelisComVariantService');
                $varStock = $variantSrv->getVariantFinalStocks($variantId, $countryId);
                
                if ($varStock)
                {
                    $newStock = $varStock->stock_quantity - $currentQty;
                    $varStock = ($newStock > 0) ? $newStock : 0;
                }
            }
            
            // Pre value to form input field
            $addToCartForm->get('m_variant_quantity')->setAttribute('data-maxvalue', $varStock);
            $addToCartForm->get('m_variant_country')->setValue($countryId);
            
            /**
             * Stock validation
             */
            if ($varStock)
            {
                $hasStock = true;
                if ($quantity <= $varStock)
                {
                    if ($is_submit)
                    {
                        if ($addToCartForm->isValid())
                        {
                            $data = $addToCartForm->getData();
                            $quantity = $quantity + $currentQty;
                            $basketId = $basketSrv->addVariantToBasket($variantId, $quantity, $clientId, $clientKey);
                                
                            if (is_null($basketId))
                            {
                                $errors['genError'] = array(
                                    'genError' => $translator->translate('tr_meliscommerce_products_plugins_cart_error')
                                );
                            }
                        }
                        else
                        {
                            $errors = $addToCartForm->getMessages();
                        }
                    }
                }
                else
                {
                    $errors['invalidStock'] = array(
                        'invalidStock' => sprintf($translator->translate('tr_meliscommerce_products_plugins_stock_left'), $varStock),
                    );
                }
            }
            else
            {
                $errors['noStock'] = array(
                    'noStock' => $translator->translate('tr_meliscommerce_products_plugins_no_stock')
                );
            }
        }
        else 
        {
            $errors['inValidQty'] = array(
                'inValidQty' => $translator->translate('tr_meliscommerce_products_plugins_quantity_error')
            );
        }
        
        /**
         * This input field set value in order to validate
         * after submission of the form proivided of this plugin
         */
        $addToCartForm->get('m_add_to_cart_is_submit')->setValue(true);
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'addToCcart' => $addToCartForm,
            'hasStock' => $hasStock,
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
