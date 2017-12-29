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
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\Session\Container;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Model\ViewModel;

/**
 * This plugin implements the business logic of the
 * "cart menu plugin" plugin.
 * 
 * Please look inside app.plugins.orders.php for possible awaited parameters
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
        $request = $this->getServiceLocator()->get('request');

        $data = $this->getFormData();
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
//        $limit = !empty($data['cart_menu_limit'])? $data['cart_menu_limit'] : null;
        $imageType = !empty($data['image_type'])? $data['image_type'] : array();
        // Pagination config
        $pageCurrent        = !empty($data['my_cart_current'])   ? $data['my_cart_current'] : 1;
        $pagePerPage      = !empty($data['my_cart_per_page']) ? $data['my_cart_per_page'] : null;
        
        $basket = array();
        $total = 0;
        $totalCurrency = '';
        
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $basketSrv = $this->getServiceLocator()->get('MelisComBasketService');
        $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
        $currencySvc = $this->getServiceLocator()->get('MelisComCurrencyService');
        $container = new Container('melisplugins');
        $lang = $container['melis-plugins-lang-id'];
        
        $clientKey = $ecomAuthSrv->getId();
        $clientId = null;
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
        }

        //remove item from cart/basket
        if ($request->isPost())
        {
            $requestVar = get_object_vars($request->getPost());
            $variantIdRemove = (isset($requestVar['cart_variant_id'])) ? $requestVar['cart_variant_id'] : null;
            /**
             * Removing Product from Cart
             */
            if ($variantIdRemove)
            {
                $basketSrv->removeVariantFromBasket($variantIdRemove, 0, $clientId, $clientKey);
            }
        }
        
        $basketObj = $basketSrv->getBasket($clientId, $clientKey);
        
        if($basketObj){
            foreach($basketObj as $item){
                
                $itemTotal = 0;
                $var = $item->getVariant();
                
                $product = $prodSvc->getProductById($var->getVariant()->var_prd_id, $lang, null, 'IMG', array('DEFAULT'));
                
                // Get the product name
                foreach($product->getTexts() as $text){
                    if($text->ptt_code == 'TITLE'){
                        $tmp['name'] = $text->ptxt_field_short;
                    }
                }
                // get product id
                $tmp['product_id'] = $product->getId();
                
                // get variant sku
                $tmp['var_sku'] = $var->getVariant()->var_sku;
                
                // get vriant id
                $tmp['var_id'] = $var->getId();
                
                // get quantity
                $tmp['quantity'] = $item->getQuantity();
                
                // get variant prices, if doesn't exist use the product price
                if(!empty($var->getPrices()[0]->price_net)){
                    $tmp['price'] = $var->getPrices()[0]->price_net;
                    $tmp['cur_symbol'] = $var->getPrices()[0]->cur_symbol;
                }else{
                    $tmp['price'] = $product->getPrice()[0]->price_net;
                    $tmp['cur_symbol'] = $product->getPrices()[0]->cur_symbol;
                }
                
                // if no currency set from the prices, get the default site currency
                if(empty($tmp['cur_symbol'])){
                    $currency = $currencySvc->getDefaultCurrency();
                    $tmp['cur_symbol'] = $currency->cur_symbol;
                }
                
                // get the default image
                if(!empty($var->getDocuments())){
                    foreach($var->getDocuments() as $doc){
                        if($doc->dtype_sub_code == 'DEFAULT'){
                            $tmp['image'] = $doc->doc_path;
                        }
                    }
                }else{
                    foreach($product->getDocuments() as $doc){
                        if($doc->dtype_sub_code == $imageType){
                            $tmp['image'] = $doc->doc_path;
                        }
                    }
                }
                
                $itemTotal = $tmp['price'] * $tmp['quantity'];
                $totalCurrency = $tmp['cur_symbol'];
                $total = $total + $itemTotal;
                $basket[] = $tmp;
            }
        }

        // Pagination
        $paginator = new Paginator(new ArrayAdapter($basket));
        $paginator->setCurrentPageNumber($pageCurrent)
            ->setItemCountPerPage($pagePerPage);
        
        $viewVariables = array(
            'basket' => $basket,
            'basketTotal' => $total,
            'currency' => $totalCurrency,
            'paginator' => $paginator,
            'myCartBeforeAfter' => 2,
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
            $request = $this->getServiceLocator()->get('request');
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
        //if (!empty($xmlValueFormatted))
       // {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
       // }

        return $xmlValueFormatted;
    }
}
