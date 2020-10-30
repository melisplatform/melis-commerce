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
 * "order" plugin.
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
 * $plugin = $this->MelisCommerceCheckoutConfirmPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCheckoutConfirmPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'order');
 * 
 * How to display in your controller's view:
 * echo $this->order;
 * 
 * 
 */
class MelisCommerceCheckoutConfirmPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceCheckoutConfirmPlugin';
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
        $orderBasket = array();
        $clientOrder = array();
        $orderAddressPluginView = '';
        $errMsg = '';
        
        $translator = $this->getServiceManager()->get('translator');
        $pluginManager = $this->getServiceManager()->get('ControllerPluginManager');
        $ecomAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $currencyTbl = $this->getServiceManager()->get('MelisEcomCurrencyTable');
        $couponSvc = $this->getServiceManager()->get('MelisComCouponService');
        
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        $data = $this->getFormData();
        
        $orderId            = !empty($data['m_conf_order_id'])       ? $data['m_conf_order_id'] : null;
        $addressParameters  = !empty($data['address_parameters'])    ? $data['address_parameters'] : array();
        $addressParameters   = ArrayUtils::merge($addressParameters, array('id' => 'checkoutCoupon_'.$data['id'], 'pageId' => $data['pageId']));
        
        if ($orderId)
        {
            $clientOrderDetails = $orderSvc->getClientOrderDetailsById($orderId, $ecomAuthSrv->getClientId(), $ecomAuthSrv->getPersonId(), $langId);
            
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
            
            if (!empty($clientOrderDetails))
            {
                switch ($clientOrderDetails->ord_status)
                {
                    case '1':
                        break;
                    case '-1':
                        // Result message if the checkout payment had been skiped
                        $errMsg = $translator->translate('tr_meliscommerce_order_checkout_confirmation_skip_payment');
                        break;
                    case '6':
                        // 6 is the primary key of status code for error in checkout
                        // Result message if the checkout payment paid amount is not equal to Total cost of the client basket
                        $errMsg = $translator->translate('tr_meliscommerce_order_checkout_confirmation_price_not_equal');
                        break;
                    default:
                        // System error
                        $errMsg = $translator->translate('Something went wrong');
                        break;
                }
                
                $clientOrderId = $clientOrderDetails->ord_id;
                
                $orderBasket = $orderSvc->getOrderBasketByOrderId($clientOrderId);
                $totalBasket = 0;
                $currency = null;
                $totalProductDiscount  = 0;
                foreach ($orderBasket As $key => $val)
                {
                    $val->discount = 0;
                    if(!empty($orderCoupons))
                    {
                        foreach($orderCoupons as $coupon)
                        {
                            if($coupon->getCoupon()->coup_product_assign)
                            {
                                foreach($coupon->getCoupon()->discountedBasket as $item)
                                {
                                    if ($item->cord_basket_id == $val->obas_id)
                                    {
                                        if(!empty($coupon->getCoupon()->coup_percentage))
                                        {
                                            $val->discount = ($coupon->getCoupon()->coup_percentage / 100) * ($val->obas_price_net * $item->cord_quantity_used);
                                            
                                        } 
                                        elseif (!empty($coupon->getCoupon()->coup_discount_value))
                                        {
                                            
                                            $val->discount = $coupon->getCoupon()->coup_discount_value * $item->cord_quantity_used;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    $totalBasket += ($val->obas_price_net * $val->obas_quantity);
                    
                    if (is_null($currency))
                    {
                        $prdCurrency = $currencyTbl->getEntryById($val->obas_currency)->current();
                        if (!empty($prdCurrency))
                        {
                            $currency = $prdCurrency->cur_symbol;
                        }
                    }
                    
                    if (!is_null($currency))
                    {
                        $orderBasket[$key]->cur_symbol = $currency;
                    }
                    
                    $totalProductDiscount += $val->discount;
                    $orderBasket[$key] = $val;
                }
                
                $couponDetails = array();
                
                if(!empty($orderCoupons))
                {
                    foreach($orderCoupons as $coupon)
                    {
                        if(!$coupon->getCoupon()->coup_product_assign)
                        {
                            $couponDetails[] = array(
                                'couponCode' => $coupon->getCoupon()->coup_code,
                                'couponIsInPercentage' => ($coupon->getCoupon()->coup_percentage) ? true : false,
                                'couponValue' => ($coupon->getCoupon()->coup_percentage) ? $coupon->getCoupon()->coup_percentage.'%' : $coupon->getCoupon()->coup_discount_value,
                                'couponDiscount' => ($coupon->getCoupon()->coup_percentage) ? ($coupon->getCoupon()->coup_percentage / 100) * $totalBasket : $coupon->getCoupon()->coup_discount_value,
                            );
                        }
                    }
                }
                
                $clientOrder = array(
                    'orderId' => $clientOrderId,
                    'orderStatus' => $clientOrderDetails->ostt_status_name,
                    'orderReference' => $clientOrderDetails->ord_reference,
                    'orderDate' => $clientOrderDetails->ord_date_creation,
                    'orderSubtotal' => $clientOrderDetails->opay_price_order - $totalProductDiscount,
                    'orderCouponDetails' => $couponDetails,
                    'orderSippingTotal' => $clientOrderDetails->opay_price_shipping,
                    'orderTotal' => $clientOrderDetails->opay_price_total,
                    'orderCurrency' => $currency,
                );
                
                // Use Address plugin
                $orderAddressPlugin = $pluginManager->get('MelisCommerceOrderAddressPlugin');
                $addressParameters = ArrayUtils::merge($addressParameters, array('m_add_order_id' => $clientOrderId));
                $orderAddressPluginView = $orderAddressPlugin->render($addressParameters);
            }
        }
        
        $viewVariables = array(
            'order' => $clientOrder,
            'orderBasket' => $orderBasket,
            'orderAddressView' => $orderAddressPluginView,
            'orderErrMsg' => $errMsg,
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
        
        var_dump($xmlValueFormatted);
        
        return $xmlValueFormatted;
    }
}
