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
use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Model\ViewModel;

/**
 * This plugin implements the business logic of the
 * "order list" plugin.
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
 * $plugin = $this->MelisCommerceOrderHistoryPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceOrderHistoryPlugin();
 * $parameters = array(
 *      'template_path' => 'your-site-folder/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'orderList');
 * 
 * How to display in your controller's view:
 * echo $this->orderList;
 * 
 * 
 */
class MelisCommerceOrderHistoryPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceOrderHistoryPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $clientId = null;
        $orders = array();

        $container = new Container('melisplugins');
        $lang = $container['melis-plugins-lang-id'];

        $data = $this->getFormData();

        $sort = !empty($data['m_order_sort'])? $data['m_order_sort'] : 'ord_id DESC';
        // Pagination config
        $pageCurrent        = !empty($data['order_history_current'])   ? $data['order_history_current'] : 1;
        $pagePerPage      = !empty($data['order_history_per_page']) ? $data['order_history_per_page'] : null;
        $nbPageBeforeAfter      = !empty($data['order_history_page_before_after']) ? $data['order_history_page_before_after'] : 2;

        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $orderSvc = $this->getServiceLocator()->get('MelisComOrderService');
        $orderStatus = $orderSvc->getOrderStatusList($lang);
        $currencySvc = $this->getServiceLocator()->get('MelisComCurrencyService');
        $currencies = $currencySvc->getCurrencies();

        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $data = $orderSvc->getOrderList(null, true, $lang, $clientId, null, null, null, null, null, $sort);
            
            foreach($data as $item){
                
                $status = '';
                
                foreach($orderStatus as $stat){
                    
                    if($stat->ostt_status_id == $item->getOrder()->ord_status){
                        $status = $stat->ostt_status_name;
                    }
                }
                
                $count = 0;
                $orderDetails = array();
                $subTotal = 0;
                $total = 0;
                $shipping = 0;
                
                foreach($item->getBasket() as $basket){
                    
                    $count = $count + $basket->obas_quantity;
                    $details = array();
                    $currency = '';
                    
                    foreach($currencies as $cur){
                        
                        if($cur->cur_id == $basket->obas_currency){
                            $currency = $cur->cur_symbol;
                        }
                    }
                    
                    $details['productName'] = $basket->obas_product_name;
                    $details['sku'] = $basket->obas_sku;
                    $details['currency'] = $currency;
                    $details['price'] = $basket->obas_price_net;
                    $details['quantity'] = $basket->obas_quantity;                   
                    
                    $orderDetails['items'][] = $details;
                }
               
                if(!empty($item->getPayment())){
                    
                    foreach($item->getPayment() as $payment){
                        
                        $shipping = $shipping + $payment->opay_price_shipping;
                        $subTotal = $subTotal + $payment->opay_price_order;
                        $total = $total + $payment->opay_price_total;
                    }
                }
                
                $orderDetails['subTotal'] = $subTotal;
                $orderDetails['total'] = $total;
                $orderDetails['shipping'] = $shipping;
                
                $order = array();
                $order['id'] = $item->getId();
                $order['reference'] = $item->getOrder()->ord_reference;
                $order['date'] = $item->getOrder()->ord_date_creation;
                $order['status'] = $status;
                $order['itemCount'] = $count;
                $order['total'] = $total;
                $order['currency'] = $currency;
                $order['details'] = $orderDetails;
                
                $orders[] = $order;
            }
        }

        // Pagination
        $paginator = new Paginator(new ArrayAdapter($orders));
        $paginator->setCurrentPageNumber($pageCurrent)
            ->setItemCountPerPage($pagePerPage);

        $viewVariables = array(
            'orders' => $paginator,
            'orderHistoryBeforeAfter' => $nbPageBeforeAfter,
            'hasData' => (sizeof($orders) > 0) ? true : false,
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

            if (!empty($xml->m_order_sort))
            {
                $configValues['m_order_sort'] = (string)$xml->m_order_sort;
            }

            if (!empty($xml->order_history_current))
            {
                $configValues['order_history_current'] = (string)$xml->order_history_current;
            }

            if (!empty($xml->order_history_per_page))
            {
                $configValues['order_history_per_page'] = (string)$xml->order_history_per_page;
            }

            if (!empty($xml->order_history_page_before_after))
            {
                $configValues['order_history_page_before_after'] = (string)$xml->order_history_page_before_after;
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

        if (!empty($parameters['m_order_sort']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_order_sort><![CDATA[' . $parameters['m_order_sort'] . ']]></m_order_sort>';
        }

        if (!empty($parameters['order_history_current']))
        {
            $xmlValueFormatted .= "\t\t" . '<order_history_current><![CDATA[' . $parameters['order_history_current'] . ']]></order_history_current>';
        }

        if (!empty($parameters['order_history_per_page']))
        {
            $xmlValueFormatted .= "\t\t" . '<order_history_per_page><![CDATA[' . $parameters['order_history_per_page'] . ']]></order_history_per_page>';
        }

        if (!empty($parameters['order_history_page_before_after']))
        {
            $xmlValueFormatted .= "\t\t" . '<order_history_page_before_after><![CDATA[' . $parameters['order_history_page_before_after'] . ']]></order_history_page_before_after>';
        }

        // Something has been saved, let's generate an XML for DB
        //if (!empty($xmlValueFormatted))
        //{
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        //}

        return $xmlValueFormatted;
    }
}
