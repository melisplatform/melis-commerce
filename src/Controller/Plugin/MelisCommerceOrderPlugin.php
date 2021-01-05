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
 * "order" plugin.
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
class MelisCommerceOrderPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceOrderPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $clientId = null;
        $order = array();
        $orderAddressPluginView = array();
        $orderShippingDetailsView = array();
        $orderMessagesView = array();
        
        $ecomAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $currencySvc = $this->getServiceManager()->get('MelisComCurrencyService');
        $couponSvc = $this->getServiceManager()->get('MelisComCouponService');
        
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];

        $formData = $this->getFormData();

        $orderId                = !empty($formData['m_order_id'])                        ? $formData['m_order_id'] : null;
        $addressParameters      = !empty($formData['order_address_parameters'])          ? $formData['order_address_parameters'] : array();
        $addressParameters      = ArrayUtils::merge($addressParameters, array('id' => 'orderAddresses_'.$formData['id'], 'pageId' => $formData['pageId']));

        $shippingParameters     = !empty($formData['order_shipping_details_parameters']) ? $formData['order_shipping_details_parameters'] : array();
        $shippingParameters     = ArrayUtils::merge($shippingParameters, array('id' => 'orderShippingDetails_'.$formData['id'], 'pageId' => $formData['pageId']));

        $messagesParamenters    = !empty($formData['order_messages_parameters'])         ? $formData['order_messages_parameters'] : array();
        $messagesParamenters    = ArrayUtils::merge($messagesParamenters, array('id' => 'orderMessages_'.$formData['id'], 'pageId' => $formData['pageId']));

        $rpParameters    = !empty($formData['order_return_product_parameters']) ? $formData['order_return_product_parameters'] : array();
        $rpParameters    = ArrayUtils::merge($rpParameters, array('id' => 'orderProductReturn_'.$formData['id'], 'pageId' => $formData['pageId']));

        $orderStatus = $orderSvc->getOrderStatusList($langId);

        /**
         * If order is empty, we get any order
         * as our default data(for BO only)
         */
        if(empty($orderId)){
            if($this->renderMode == 'melis'){
                $ordList = $orderSvc->getOrderList();
                foreach($ordList as $ord){
                    if(!empty($ord->getId())){
                        $orderId = $ord->getId();
                        break;
                    }
                }
            }
        }
        //make sure we have an order
        if(!empty($orderId)) {
            if ($ecomAuthSrv->hasIdentity() || $this->renderMode == 'melis') {
                $clientId = $ecomAuthSrv->getClientId();
                $data = $orderSvc->getOrderById($orderId, $langId);

                $orderCoupons = $couponSvc->getCouponList($orderId);
                $tmp = array();
                foreach ($orderCoupons as $coupon) {
                    if ($coupon->getCoupon()->coup_product_assign) {
                        $coupon->getCoupon()->discountedBasket = $couponSvc->getCouponDiscountedBasketItems($coupon->getCoupon()->coup_id, $orderId);
                    }

                    $tmp[] = $coupon;
                }

                $orderCoupons = $tmp;

                if (!empty($data->getClient()) || $this->renderMode == 'melis') {
                    if (($data->getClient()->cli_id == $clientId) || $this->renderMode == 'melis') {
                        //auhtorized
                        $order = array();

                        $currencies = $currencySvc->getCurrencies();

                        $status = '';

                        foreach ($orderStatus as $stat) {
                            if ($stat->ostt_status_id == $data->getOrder()->ord_status) {
                                $status = $stat->ostt_status_name;
                            }
                        }

                        $count = 0;
                        $orderDetails = array();
                        $subTotal = 0;
                        $total = 0;
                        $shipping = 0;

                        foreach ($data->getBasket() as $basket) {
                            $basket->discount = 0;
                            if (!empty($orderCoupons)) {
                                foreach ($orderCoupons as $coupon) {
                                    if ($coupon->getCoupon()->coup_product_assign) {
                                        foreach ($coupon->getCoupon()->discountedBasket as $item) {
                                            if ($item->cord_basket_id == $basket->obas_id) {
                                                if (!empty($coupon->getCoupon()->coup_percentage)) {
                                                    $basket->discount = ($coupon->getCoupon()->coup_percentage / 100) * $basket->obas_price_net;
                                                } elseif (!empty($coupon->getCoupon()->coup_discount_value)) {
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

                            foreach ($currencies as $cur) {
                                if ($cur->cur_id == $basket->obas_currency) {
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

                            $subTotal += $details['total'];
                            $items[] = $details;
                        }

                        $couponDetails = array();
                        if (!empty($orderCoupons)) {
                            foreach ($orderCoupons as $coupon) {
                                if (!$coupon->getCoupon()->coup_product_assign) {
                                    $couponDetails[] = array(
                                        'couponCode' => $coupon->getCoupon()->coup_code,
                                        'couponIsInPercentage' => ($coupon->getCoupon()->coup_percentage) ? true : false,
                                        'couponValue' => ($coupon->getCoupon()->coup_percentage) ? $coupon->getCoupon()->coup_percentage . '%' : $coupon->getCoupon()->coup_discount_value,
                                        'couponDiscount' => ($coupon->getCoupon()->coup_percentage) ? ($coupon->getCoupon()->coup_percentage / 100) * $subTotal : $coupon->getCoupon()->coup_discount_value,
                                    );
                                }
                            }
                        }

                        if (!empty($data->getPayment())) {
                            foreach ($data->getPayment() as $payment) {
                                $shipping = $shipping + $payment->opay_price_shipping;
                                $total = $total + $payment->opay_price_total;
                            }
                        }

                        $order['subTotal'] = $subTotal;
                        $order['total'] = $total;
                        $order['shipping'] = $shipping;
                        $order['id'] = $data->getId();
                        $order['reference'] = $data->getOrder()->ord_reference;
                        $order['date'] = $data->getOrder()->ord_date_creation;
                        $order['status'] = $status;
                        $order['itemCount'] = $count;
                        $order['currency'] = $currency;
                        $order['items'] = $items;
                        $order['coupons'] = $couponDetails;
                    }
                }
            }
        }
        
        // Use Address plugin
        $orderAddressPlugin = $this->getServiceManager()->get('ControllerPluginManager')->get('MelisCommerceOrderAddressPlugin');
        $addressParameters = ArrayUtils::merge($addressParameters, array('m_add_order_id' => $orderId));
        $orderAddressPluginView = $orderAddressPlugin->render($addressParameters);
        
        // Use shipping details plugin
        $orderShippingDetailsPlugin = $this->getServiceManager()->get('ControllerPluginManager')->get('MelisCommerceOrderShippingDetailsPlugin');
        $shippingParameters = ArrayUtils::merge($shippingParameters, array('m_sd_order_id' => $orderId));
        $orderShippingDetailsView = $orderShippingDetailsPlugin->render($shippingParameters);
        
        // Use order messages plugin
        $orderMessagesPlugin = $this->getServiceManager()->get('ControllerPluginManager')->get('MelisCommerceOrderMessagesPlugin');
        $messagesParamenters = ArrayUtils::merge($messagesParamenters, array('m_om_order_id' => $orderId));
        $orderMessagesView = $orderMessagesPlugin->render($messagesParamenters);

        // Use order return product plugin
        $orderReturnProductPlugin = $this->getServiceManager()->get('ControllerPluginManager')->get('MelisCommerceOrderReturnProductPlugin');
        $rpParameters = ArrayUtils::merge($rpParameters, array('m_rp_order_id' => $orderId));
        $orderReturnProductView = $orderReturnProductPlugin->render($rpParameters);

        $viewVariables = array(
            'order' => $order,
            'orderAddress' => $orderAddressPluginView,
            'orderShippingDetails' => $orderShippingDetailsView,
            'orderMessages' => $orderMessagesView,
            'returnProduct' => $orderReturnProductView
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
