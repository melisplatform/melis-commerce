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
 * "checkOutCart" plugin.
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
 * $plugin = $this->MelisCommerceCheckoutCartPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCheckoutCartPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'checkoutCart');
 * 
 * How to display in your controller's view:
 * echo $this->checkoutCart;
 * 
 * 
 */
class MelisCommerceCheckoutCartPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // the key of the configuration in the app.plugins.php
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceCheckoutCartPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $checkOutCart = array();
        $currency = null;
        $subTotal = 0;
        $totalDiscount = 0;
        $subTotalWithProdDiscount = 0;
        $orderDiscount = 0;
        $discountInfo = array();
        $total = 0;
        $errors = array();
        $couponView = null;
        $hasErr = false;
        $hasDiscount = false;
        
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        $translator = $this->getServiceManager()->get('translator');
        $ecomAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');
        $basketSrv = $this->getServiceManager()->get('MelisComBasketService');
        $variantSrv = $this->getServiceManager()->get('MelisComVariantService');
        $couponSrv = $this->getServiceManager()->get('MelisComCouponService');
        
        $formData = $this->getFormData();
        $countryId                      = (!empty($formData['m_cc_country_id']))                 ? $formData['m_cc_country_id'] : null;
        $siteId                         = (!empty($formData['m_cc_site_id']))                    ? $formData['m_cc_site_id'] : null;
        $variantQuantities              = (!empty($formData['m_cc_var_qty']))                    ? $formData['m_cc_var_qty'] : null;
        $variantIdRemove                = (!empty($formData['m_cc_var_remove']))                 ? $formData['m_cc_var_remove'] : null;
        $checkoutCartCouponParameters   = (!empty($formData['checkout_cart_coupon_parameters'])) ? $formData['checkout_cart_coupon_parameters'] : array();
        $checkoutCartCouponParameters   = ArrayUtils::merge($checkoutCartCouponParameters, array('id' => 'checkoutCoupon_'.$formData['id'], 'pageId' => $formData['pageId']));

        $clientKey = $ecomAuthSrv->getId();
        $clientId = null;
        $clientGroupId = null;
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
            $clientGroupId = $ecomAuthSrv->getClientGroup();
        }
        
        /**
         * Removing Product from Cart
         */
        if ($variantIdRemove)
        {
            $basketSrv->removeVariantFromBasket($variantIdRemove, 0, $clientId, $clientKey);
        }

        $basketData = array();
        
        if (!empty($variantQuantities))
        {
            /**
             * Adding/Removing the Product from cart
             * by changing the quantity of the product
             */
            foreach ($variantQuantities As $varId => $varQty)
            {
                if (is_numeric($varQty))
                {
                    if ($varQty < 1)
                    {
                        /**
                         * variant that has zero (0) quantity will 
                         * automatically remove from the use's cart
                         */
                        $basketSrv->removeVariantFromBasket($varId, 0, $clientId, $clientKey);
                    }
                    else  
                    {
                        /**
                         * Checking varain stock
                         * else this will try to look Product Stock
                         */
                        $varStock = $variantSrv->getVariantFinalStocks($varId, $countryId);

                        if ($varStock)
                        {
                            $varStock = $varStock->stock_quantity;
                            
                            if ($varStock >= $varQty)
                            {
                                $basketSrv->addVariantToBasket($varId, $varQty, $clientId, $clientKey);
                            }
                            else
                            {
                                $basketData = $basketSrv->getBasket($clientId, $clientKey);
                                /**
                                 * get the quantity form the client basket
                                 * to compute the remaining stock
                                 */
                                $currentQty = 0;
                                if(!empty($basketData))
                                {
                                    foreach($basketData as $item)
                                    {
                                        if($item->getVariantId() == $varId)
                                        {
                                            $currentQty = $item->getQuantity();
                                        }
                                    }
                                }
                                $stockRemaining = $varStock - $currentQty;
                                $stockRemaining = ($stockRemaining > 0) ? $stockRemaining : 0;

                                $errors[$varId] = sprintf($translator->translate('tr_meliscommerce_products_plugins_stock_left'), $stockRemaining);
                            }
                        }
                        else 
                        {
                            $errors[$varId] = $translator->translate('tr_meliscommerce_products_plugins_no_stock');
                        }
                    }
                }
                else 
                {
                    $errors[$varId] = 'Quantity must be numeric';
                }
            }
        }

        //if basketData is empty or null, get the client basket
        if(empty($basketData) || is_null($basketData)) {
            $basketData = $basketSrv->getBasket($clientId, $clientKey);
        }
        
        /**
         * Validiting the Client basket
         * this will process if the user has already identify/Loggedin
         */
        $validatedBasket = array();
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $melisComOrderCheckoutService = $this->getServiceManager()->get('MelisComOrderCheckoutService');
            $melisComOrderCheckoutService->setSiteId($siteId);
            $validatedBasket = $melisComOrderCheckoutService->validateBasket($clientId);
        }
        
        if (!is_null($basketData))
        {
            /**
             * Checkouk Coupon Plugin
             * This plugin will validate the coupon if coupon code if submitted
             */
            $pluginManager = $this->getServiceManager()->get('ControllerPluginManager');
            $checkoutCouponPlugin = $pluginManager->get('MelisCommerceCheckoutCouponPlugin');
            $checkoutCartCouponParameters = ArrayUtils::merge($checkoutCartCouponParameters, array(
                'm_coupon_site_id' => $siteId
            ));
            $couponView = $checkoutCouponPlugin->render($checkoutCartCouponParameters);
            $coupon = $couponView->getVariables();

            $sessionCoupons = !empty($coupon['coupon'])? $coupon['coupon'] : array();
            $generalCoupons = !empty($sessionCoupons['generalCoupons'])? $sessionCoupons['generalCoupons'] : array();
            $productCoupons = !empty($sessionCoupons['productCoupons'])? $sessionCoupons['productCoupons'] : array();

            $melisComVariantService = $this->getServiceManager()->get('MelisComVariantService');
            $melisComProductService = $this->getServiceManager()->get('MelisComProductService');
            
            $tmp = array();
            foreach($productCoupons as $productCoupon)
            {
                
                $discountedProducts = $couponSrv->getCouponProductList($productCoupon->coup_id, true);
                $ids = array();
                foreach($discountedProducts as $discountProduct)
                {
                    $ids[] = $discountProduct->getId();
                }
                $productCoupon->products = $ids;
                $tmp[$productCoupon->coup_id] = clone $productCoupon;
            }
            
            $productCoupons = $tmp;
            
            foreach ($basketData As $val)
            {
                $variantId = $val->getVariantId();
                $variant = $val->getVariant();
                $quantity = $val->getQuantity();
                $productId = $variant->getVariant()->var_prd_id;
                $varSku = $variant->getVariant()->var_sku;
                
                $variantErr = '';
                if (!empty($validatedBasket['basket']['ko'][$variantId]['error']))
                {
                    $variantErr = $translator->translate('tr_'.$validatedBasket['basket']['ko'][$variantId]['error']);
                    $hasErr = true;
                }
                // Product variant price
                $prdVarPrice = $melisComPriceService->getItemPrice('variant', $variantId, $countryId, $clientGroupId);

                // Compute variant total amount
                $variantTotal = $quantity * $prdVarPrice['price'];
                
                $discount = 0;
                $discountDetails = '';
                $discountCouponCode = '';
                $usableCouponQty = '';
                $discountPercentage = '';
                $discountValue = '';
                $tmp = array();
                // calculate final variant price with coupons applied
                foreach($productCoupons as $productCoupon)
                {
                    $hasDiscount = true;
                    // get coupon quantity
                    $couponQty = $productCoupon->coup_max_use_number - $productCoupon->coup_current_use_number;
                    
                    // check if variant is discounted by a coupon
                    if(in_array($variant->getVariant()->var_prd_id, $productCoupon->products))
                    {
                        // get actual usable quantity
                        $usableCouponQty = (($couponQty - $quantity) >= 0)? $quantity : $couponQty;
                        
                        if($usableCouponQty > 0)
                        {
                            if(!empty($productCoupon->coup_percentage))
                            {
                                $discount += ($productCoupon->coup_percentage / 100) * ($prdVarPrice['price'] * $usableCouponQty);
                                $discountDetails = $productCoupon->coup_percentage.'%';
                                $discountPercentage = $productCoupon->coup_percentage;
                            } 
                            elseif (!empty($productCoupon->coup_discount_value))
                            {
                                $discount += $productCoupon->coup_discount_value * $usableCouponQty;
                                $discountDetails = $discount;
                                $discountValue = $productCoupon->coup_discount_value;
                            }
                            $discountCouponCode = $productCoupon->coup_code;
                            $productCoupon->coup_current_use_number = $productCoupon->coup_current_use_number + $usableCouponQty;
                        }
                    }
                    
                    $tmp[$productCoupon->coup_id] = clone $productCoupon;
                }
                
                $productCoupons = $tmp;
                
                $data = array(
                    'var_id' => $variantId,
                    'var_product_id' => $productId,
                    'var_product_name' => $melisComProductService->getProductName($productId, $langId),
                    'var_sku' => $varSku,
                    'var_quantity' => $quantity,
                    'var_currency_symbol' => $prdVarPrice['price_currency']['symbol'],
                    'var_price' => $prdVarPrice['price'],
                    'var_total' => $variantTotal - $discount,
                    'var_err' => $variantErr,
                    'var_discount' => $discount,
                    'var_discount_details' => $discountDetails,
                    'var_discount_coupon_code' => $discountCouponCode,
                    'var_discount_usable_qty' => $usableCouponQty,
                    'var_discount_percentage' => $discountPercentage,
                    'var_discount_value' => $discountValue,
                );
                
                // Setting the currency use of the cart
                $currency = $prdVarPrice['price_currency']['symbol'];
                $subTotal += $variantTotal;
                $subTotalWithProdDiscount += $variantTotal - $discount;
                array_push($checkOutCart, $data);
            }
            
            foreach($generalCoupons as $generalCoupon)
            {
                $hasDiscount = true;
                if(!empty($generalCoupon->coup_percentage))
                {
                    $totalDiscount = ($generalCoupon->coup_percentage / 100) * $subTotalWithProdDiscount;
                    $discountInfo[] = array(
                        'details' => $generalCoupon->coup_percentage.'%',
                        'amount' => number_format($totalDiscount, 2),
                        'code' => $generalCoupon->coup_code,
                    );
                }
                elseif (!empty($generalCoupon->coup_discount_value))
                {
                    $totalDiscount = $generalCoupon->coup_discount_value;
                    $discountInfo[] =  array(
                        'details' => $totalDiscount,
                        'amount' => number_format($totalDiscount,2),
                        'code' => $generalCoupon->coup_code,
                    );
                }
                
                $orderDiscount += $totalDiscount;
            }
            
            $total = $subTotalWithProdDiscount - $orderDiscount;
        }

        //check if there is no error
        if(!empty($errors)){
            $hasErr = true;
        }
        
        $viewVariables = array(
            'checkOutCart' => $checkOutCart,
            'checkOutCartSubTotal' => number_format($subTotalWithProdDiscount, 2),
            'checkOutCartDiscount' => number_format($orderDiscount, 2),
            'checkOutCartDiscountInfo' => $discountInfo,
            'checkOutCartTotal' => number_format($total, 2),
            'checkOutCurrency' => $currency,
            'checkoutErrors' => $errors,
            'checkoutCoupon' => $couponView,
            'checkoutHasCoupon' => $hasDiscount,
            'checkoutHasErr' => $hasErr,
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
            
            if (!empty($xml->m_cc_country_id))
            {
                $configValues['m_cc_country_id'] = (string)$xml->m_cc_country_id;
            }
            
            if (!empty($xml->m_cc_site_id))
            {
                $configValues['m_cc_site_id'] = (string)$xml->m_cc_site_id;
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
        
        if (!empty($parameters['m_cc_country_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_cc_country_id><![CDATA[' . $parameters['m_cc_country_id'] . ']]></m_cc_country_id>';
        }
        
        if (!empty($parameters['m_cc_site_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_cc_site_id><![CDATA[' . $parameters['m_cc_site_id'] . ']]></m_cc_site_id>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
