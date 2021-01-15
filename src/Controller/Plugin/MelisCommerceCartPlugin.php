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
use Laminas\Paginator\Adapter\ArrayAdapter;
use Laminas\Paginator\Paginator;
use Laminas\Session\Container;
use Laminas\Stdlib\ArrayUtils;
use Laminas\View\Model\ViewModel;

/**
 * This plugin implements the business logic of the
 * "cart menu plugin" plugin.
 * 
 * Please look inside app.plugins.orders.php for possible awaited parameters
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
 * $plugin = $this->MelisCommerceCartPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCartPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'cartMenu');
 * 
 * How to display in your controller's view:
 * echo $this->cartMenu;
 * 
 * 
 */
class MelisCommerceCartPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceCartPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $request = $this->getServiceManager()->get('request');
        
        $data = $this->getFormData();
        
        $countryId = !empty($data['cart_country_id'])? $data['cart_country_id'] : null;
        
        // Pagination config
        $pageCurrent = !empty($data['cart_current'])   ? $data['cart_current'] : 1;
        $pagePerPage = !empty($data['cart_per_page']) ? $data['cart_per_page'] : null;
        $nbPageBeforeAfter = !empty($data['cart_nb_page_before_after']) ? $data['cart_nb_page_before_after'] : 2;
        
        $basket = array();
        $total = 0;
        $totalCurrency = '';
        
        $ecomAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        $basketSrv = $this->getServiceManager()->get('MelisComBasketService');
        $prodSvc = $this->getServiceManager()->get('MelisComProductService');
        $melisComPriceService = $this->getServiceManager()->get('MelisComPriceService');

        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        
        $container = new Container('melisplugins');
        $lang = $container['melis-plugins-lang-id'];
        
        $clientKey = $ecomAuthSrv->getId();
        $clientId = null;
        $clientGroupId = null;
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
            $clientGroupId = $ecomAuthSrv->getClientGroup();
        }
        
        //remove item from cart/basket
        if ($request->isPost())
        {
            $variantIdRemove = (!empty($data['cart_variant_remove'])) ? $data['cart_variant_remove'] : null;
            /**
             * Removing Product from Cart
             */
            if ($variantIdRemove)
            {
                $basketSrv->removeVariantFromBasket($variantIdRemove, 0, $clientId, $clientKey);
            }
        }
        
        $baskeEntity = $basketSrv->getBasket($clientId, $clientKey);
        
        if($baskeEntity){
            foreach($baskeEntity as $item){
                
                $itemTotal = 0;
                $var = $item->getVariant();
                
                // get vriant id
                $variant['var_id'] = $var->getId();
                
                // get product id
                $variant['product_id'] = $var->getVariant()->var_prd_id;
                
                // get variant sku
                $variant['var_sku'] = $var->getVariant()->var_sku;
                
                // get quantity
                $variant['quantity'] = $item->getQuantity();
                
                /**
                 * Getting the final price of the Variant
                 * or this will try to get the Price from the Product
                 */
                // Product variant price
                $prdVarPrice = $melisComPriceService->getItemPrice($var->getId(), $countryId, $clientGroupId);

                if (!empty($prdVarPrice)) {
                    $variant['price'] = $prdVarPrice['price'];
                    $variant['cur_symbol'] = $prdVarPrice['price_currency']['symbol'];
                    
                    $itemTotal = $variant['price'] * $variant['quantity'];
                    $totalCurrency = $variant['cur_symbol'];
                    $total = $total + $itemTotal;
                    array_push($basket, $variant);
                }
                else {
                    // Removing the Variant from the Cart if the doesn't have price
                    $basketSrv->removeVariantFromBasket($var->getId(), 0, $clientId, $clientKey);
                }
            }
        }
        
        // Pagination
        $paginator = new Paginator(new ArrayAdapter($basket));
        $paginator->setCurrentPageNumber($pageCurrent)
                    ->setItemCountPerPage($pagePerPage);
        
        $viewVariables = array(
            'cartList' => $paginator,
            'currency' => $totalCurrency,
            'total' => $total,
            'nbPageBeforeAfter' => $nbPageBeforeAfter,
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
            $request = $this->getServiceManager()->get('request');
            $parameters = $request->getQuery()->toArray();
            if (!isset($parameters['validate'])){
                $formData = $this->getFormData();
            }

            foreach ($formConfig as $formKey => $config)
            {
                $form = $factory->createForm($config);

                if (!isset($parameters['validate']))
                {
                    $form->setData($formData);
                    $viewModelTab = new ViewModel();
                    $viewModelTab->setTemplate($config['tab_form_layout']);
                    $viewModelTab->modalForm = $form;
                    $viewModelTab->formData   = $formData;

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

                    if ($form->isValid())
                    {
                        $success = true;
                    }
                    else
                    {
                        $errors = $form->getMessages();

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
     * @return array|bool|null
     */
    public function getFormData()
    {
        $data = parent::getFormData();
        $data = ArrayUtils::merge($data, $this->pluginFrontConfig['pagination']);
        return $data;
    }

    /**
     * This method will decode the XML in DB to make it in the form of the plugin config file
     * so it can override it. Only front key is needed to update.
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
            
            if (!empty($xml->cart_country_id))
            {
                $configValues['cart_country_id'] = (string)$xml->cart_country_id;
            }
            
            if (!empty($xml->cart_per_page))
            {
                $configValues['pagination']['cart_per_page'] = (string)$xml->cart_per_page;
            }
            
            if (!empty($xml->cart_nb_page_before_after))
            {
                $configValues['pagination']['cart_nb_page_before_after'] = (string)$xml->cart_nb_page_before_after;
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
        
        if (!empty($parameters['cart_country_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<cart_country_id><![CDATA[' . $parameters['cart_country_id'] . ']]></cart_country_id>';
        }
        
        if (!empty($parameters['cart_per_page']))
        {
            $xmlValueFormatted .= "\t\t" . '<cart_per_page><![CDATA[' . $parameters['cart_per_page'] . ']]></cart_per_page>';
        }
        
        if (!empty($parameters['cart_nb_page_before_after']))
        {
            $xmlValueFormatted .= "\t\t" . '<cart_nb_page_before_after><![CDATA[' . $parameters['cart_nb_page_before_after'] . ']]></cart_nb_page_before_after>';
        }
        
        // Something has been saved, let's generate an XML for DB
        //if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
