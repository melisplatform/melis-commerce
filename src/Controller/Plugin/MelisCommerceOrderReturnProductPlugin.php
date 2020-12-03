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
use Laminas\Stdlib\ArrayUtils;
use Laminas\View\Model\ViewModel;
use Laminas\Session\Container;
/**
 * This plugin implements the business logic of the
 * "order return product" plugin.
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
 * $plugin = $this->MelisCommerceOrderPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceOrderPlugin();
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
class MelisCommerceOrderReturnProductPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceOrderReturnProductPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $clientId = null;
        $returnProducts = [];
        
        $ecomAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $currencySvc = $this->getServiceManager()->get('MelisComCurrencyService');
        $couponSvc = $this->getServiceManager()->get('MelisComCouponService');
        $productReturn = $this->getServiceManager()->get('MelisComOrderProductReturnService');
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $prodSvc = $this->getServiceManager()->get('MelisComProductService');
        
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];

        $formData = $this->getFormData();

        $orderId  = !empty($formData['m_rp_order_id']) ? $formData['m_rp_order_id'] : null;

        /**
         * Prepare form for message
         */
        $appConfigForm  = (!empty($this->pluginFrontConfig['forms']['meliscommerce_order_return_product_form'])) ? $this->pluginFrontConfig['forms']['meliscommerce_order_return_product_form'] : [];
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $addMessageForm = $factory->createForm($appConfigForm);

        $orderStatus = $orderSvc->getOrderStatusList($langId);

        if ($ecomAuthSrv->hasIdentity())
        {
            //set order id value
            $addMessageForm->setData(['m_rp_order_id' => $orderId]);

            //get already returned product list
            $returnProduct =  $productReturn->getOrderProductReturnList($orderId);

            $clientId = $ecomAuthSrv->getClientId();
            $data = $orderSvc->getOrderById($orderId, $langId);

            $orderCoupons = $couponSvc->getCouponList($orderId);
            $tmp = array();
            foreach($orderCoupons as $coupon)
            {
                if($coupon->getCoupon()->coup_product_assign)
                {
                    $coupon->getCoupon()->discountedBasket = $couponSvc->getCouponDiscountedBasketItems($coupon->getCoupon()->coup_id, $orderId);
                }

                $tmp[] = $coupon;
            }

            $orderCoupons = $tmp;

            if(!empty($data->getClient()))
            {
                if($data->getClient()->cli_id == $clientId)
                {
                    $currencies = $currencySvc->getCurrencies();

                    $status = '';

                    foreach($orderStatus as $stat)
                    {
                        if($stat->ostt_status_id == $data->getOrder()->ord_status)
                        {
                            $status = $stat->ostt_status_name;
                        }
                    }

                    $count = 0;
                    $orderDetails = array();
                    $subTotal = 0;
                    $total = 0;
                    $shipping = 0;

                    foreach($data->getBasket() as $basket)
                    {
                        $returnProductValue = 0;
                        $basket->discount = 0;
                        if(!empty($orderCoupons))
                        {
                            foreach($orderCoupons as $coupon)
                            {
                                if($coupon->getCoupon()->coup_product_assign)
                                {
                                    foreach($coupon->getCoupon()->discountedBasket as $item)
                                    {
                                        if ($item->cord_basket_id == $basket->obas_id)
                                        {
                                            if(!empty($coupon->getCoupon()->coup_percentage))
                                            {
                                                $basket->discount = ($coupon->getCoupon()->coup_percentage / 100) * $basket->obas_price_net;
                                            }
                                            elseif (!empty($coupon->getCoupon()->coup_discount_value))
                                            {
                                                $basket->discount = $coupon->getCoupon()->coup_discount_value * $item->cord_quantity_used;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $count = $count + $basket->obas_quantity;
                        $details = array();
                        $currency = '';

                        foreach($currencies as $cur)
                        {
                            if($cur->cur_id == $basket->obas_currency)
                            {
                                $currency = $cur->cur_symbol;
                            }
                        }

                        $details['variant_id'] = $basket->obas_variant_id;
                        $details['productName'] = $basket->obas_product_name;
                        $details['sku'] = $basket->obas_sku;
                        $details['currency'] = $currency;
                        $details['price'] = $basket->obas_price_net;
                        $details['total'] = ($basket->obas_price_net * $basket->obas_quantity) - $basket->discount;
                        $details['discount'] = $basket->discount;
                        $details['quantity'] = $basket->obas_quantity;

                        //count already returned product
                        foreach($returnProduct as $key => $rProduct){
                            if($rProduct['pretd_variant_id'] == $basket->obas_variant_id){
                                $returnProductValue = $rProduct['pretd_quantity'];
                            }
                        }
                        $details['returnedProduct'] = $returnProductValue;
                        $items[] = $details;
                    }

                    $returnProducts['id'] = $data->getId();
                    $returnProducts['reference'] = $data->getOrder()->ord_reference;
                    $returnProducts['date'] = $data->getOrder()->ord_date_creation;
                    $returnProducts['status'] = $status;
                    $returnProducts['itemCount'] = $count;
                    $returnProducts['currency'] = $currency;
                    $returnProducts['items'] = $items;
                }
            }
        }

        $viewVariables = array(
            'returnProducts' => $returnProducts,
            'addMessageForm' => $addMessageForm
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

//            if (!empty($xml->m_rp_order_id))
//            {
//                $configValues['m_rp_order_id'] = (string)$xml->m_rp_order_id;
//            }
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

//        if (!empty($parameters['m_rp_order_id']))
//        {
//            $xmlValueFormatted .= "\t\t" . '<m_rp_order_id><![CDATA[' . $parameters['m_rp_order_id'] . ']]></m_rp_order_id>';
//        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
