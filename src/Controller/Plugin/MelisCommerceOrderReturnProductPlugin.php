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
        $returnProducts = [];
        $success = 0;
        
        $ecomAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $currencySvc = $this->getServiceManager()->get('MelisComCurrencyService');
        $couponSvc = $this->getServiceManager()->get('MelisComCouponService');
        $productReturn = $this->getServiceManager()->get('MelisComOrderProductReturnService');
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $prodSvc = $this->getServiceManager()->get('MelisComProductService');
        $trans = $this->getServiceManager()->get('translator');

        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];

        $formData = $this->getFormData();

        $orderId  = !empty($formData['m_rp_order_id']) ? $formData['m_rp_order_id'] : null;
        $isSubmit  = !empty($formData['m_rp_is_submit']) ? $formData['m_rp_is_submit'] : 0;
        $orderReturnStatus  = !empty($formData['m_rp_status']) ? $formData['m_rp_status'] : 4;
        //consist of variant id ang quantity to return
        /**
         * Format:
         * [
         *      1 => 10, //1 is the variant id while 10 is the quantity to return
         *      2 => 20
         * ]
         */
        $returnVariantData  = !empty($formData['m_rp_data']) ? $formData['m_rp_data'] : [];

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

        /**
         * Prepare form for message
         */
        $appConfigForm  = (!empty($this->pluginFrontConfig['forms']['meliscommerce_order_return_product_form'])) ? $this->pluginFrontConfig['forms']['meliscommerce_order_return_product_form'] : [];
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $addMessageForm = $factory->createForm($appConfigForm);

        //set order id to the form
        $addMessageForm->get('m_rp_order_id')->setValue($orderId);

        $orderStatus = $orderSvc->getOrderStatusList($langId);

        //make sure we have an order
        if(!empty($orderId)) {
            if ($ecomAuthSrv->hasIdentity() || $this->renderMode == 'melis') {
                $clientId = $ecomAuthSrv->getClientId();
                $personid = $ecomAuthSrv->getPersonId();

                $data = $orderSvc->getOrderById($orderId, $langId);

                //check if order is already delivered to the customer
                if ($data->getOrder()->ord_status == $orderReturnStatus || $this->renderMode == 'melis') {//order delivered
                    //check if form is submitted
                    if ($isSubmit) {
                        if (!empty($returnVariantData)) {
                            //get all return product to get the quantity
                            $returnProduct = $productReturn->getOrderProductReturnList($orderId);
                            $isQtyOk = true;
                            //re check returned quantity
                            foreach ($returnVariantData as $variantId => $returnQty) {
                                $returnProd = 0;
                                //count already returned product
                                foreach ($returnProduct as $key => $rProduct) {
                                    if ($rProduct['pretd_variant_id'] == $variantId) {
                                        $returnProd += $rProduct['pretd_quantity'];
                                    }
                                }
                                foreach ($data->getBasket() as $bas) {
                                    if ($variantId == $bas->obas_variant_id) {
                                        $remainingQty = $bas->obas_quantity - $returnProd;
                                        if ($returnQty > $remainingQty) {
                                            $isQtyOk = false;
                                            break;
                                        }
                                    }
                                }
                                //if quantity is not okay, break the loop
                                if (!$isQtyOk)
                                    break;
                            }
                            /**
                             * Check if return quantity is not greater that the ordered quantity
                             */
                            if ($isQtyOk) {
                                $postValues = $formData;

                                $orderMesasge = [];
                                $returnProductData = [];
                                $returnProductDetailsData = [];
                                //prepare return product data
                                foreach ($postValues as $key => $val) {
                                    if (strpos($key, 'pret_') !== false) {
                                        $returnProductData[$key] = $val;
                                    }
                                }
                                //prepare return product details data
                                foreach ($postValues as $key => $val) {
                                    if (strpos($key, 'pretd_') !== false) {
                                        $returnProductDetailsData[$key] = $val;
                                    }
                                }
                                //prepare order msg data
                                foreach ($postValues as $key => $val) {
                                    if (strpos($key, 'omsg_') !== false) {
                                        $orderMesasge[$key] = $val;
                                    }
                                }

                                //start saving
                                //set order id
                                $returnProductData['pret_order_id'] = $orderId;
                                //include client id
                                $returnProductData['pret_client_id'] = $clientId;
                                $pretId = $productReturn->saveOrderProductReturn($returnProductData);
                                if (!empty($pretId)) {
                                    //pepare product details to include on message
                                    $msgProdDetails = '<p>' . $trans->translate('tr_melis_commerce_orders_return_details') . '<br>';
                                    //start save the details
                                    foreach ($returnVariantData as $variantId => $quantity) {
                                        //get variant info
                                        $variant = $variantSvc->getVariantById($variantId, $langId)->getVariant();
                                        //get product name
                                        $productId = $variant->var_prd_id;
                                        $productName = $prodSvc->getProductName($productId, $langId);

                                        //add other return details
                                        $returnProductDetailsData['pretd_sku'] = $variant->var_sku;
                                        $returnProductDetailsData['pretd_pret_id'] = $pretId;
                                        $returnProductDetailsData['pretd_quantity'] = $quantity;
                                        $returnProductDetailsData['pretd_variant_id'] = $variantId;

                                        //save product return details
                                        $productReturn->saveOrderProductReturnDetails($returnProductDetailsData);

                                        //set msg product details
                                        $msgProdDetails .= "<span>" . $productName . " / " . $productId . " / " . $variant->var_sku . ": " . $quantity . "</span><br>";
                                    }
                                    $msgProdDetails .= "</p>";

                                    //save message
                                    $orderMesasge['omsg_message'] .= htmlentities($msgProdDetails);
                                    $orderMesasge['omsg_order_id'] = $orderId;
                                    $orderMesasge['omsg_client_id'] = $clientId;
                                    $orderMesasge['omsg_client_person_id'] = $personid;
                                    $orderMesasge['omsg_date_creation'] = date('Y-m-d H:i:s');
                                    $orderMesasge['omsg_type'] = 'RETURN';
                                    $orderSvc->saveOrderMessage($orderMesasge);

                                    $success = 1;
                                }
                            }
                        }
                    }
                    //get already returned product list
                    $returnProduct = $productReturn->getOrderProductReturnList($orderId);
                    $orderCoupons = $couponSvc->getCouponList($orderId);

                    $tmp = array();
                    foreach ($orderCoupons as $coupon) {
                        if ($coupon->getCoupon()->coup_product_assign) {
                            $coupon->getCoupon()->discountedBasket = $couponSvc->getCouponDiscountedBasketItems($coupon->getCoupon()->coup_id, $orderId);
                        }

                        $tmp[] = $coupon;
                    }

                    $orderCoupons = $tmp;

                    if (!empty($data->getClient()) || $this->renderMode = 'melis') {
                        if (($data->getClient()->cli_id == $clientId) || $this->renderMode = 'melis') {
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
                                $returnProductValue = 0;
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

                                //count already returned product
                                foreach ($returnProduct as $key => $rProduct) {
                                    if ($rProduct['pretd_variant_id'] == $basket->obas_variant_id) {
                                        $returnProductValue += $rProduct['pretd_quantity'];
                                    }
                                }
                                $details['returnedProduct'] = $returnProductValue;
                                $details['remainingQtyToReturn'] = (int)$basket->obas_quantity - $returnProductValue;
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
            }
        }

        /**
         * This input field set value in order to validate
         * after submission of the form proivided of this plugin
         */
        $addMessageForm->get('m_rp_is_submit')->setValue(true);

        $viewVariables = array(
            'returnProducts' => $returnProducts,
            'addMessageForm' => $addMessageForm,
            'success' => $success
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
