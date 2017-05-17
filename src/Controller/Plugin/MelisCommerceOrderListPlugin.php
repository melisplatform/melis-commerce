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
use Zend\Mvc\Controller\Plugin\Redirect;
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
 * $plugin = $this->MelisCommerceOrderListPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceOrderListPlugin();
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
class MelisCommerceOrderListPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
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
        
        $sort = !empty($this->pluginFrontConfig['m_order_sort'])? $this->pluginFrontConfig['m_order_sort'] : 'ord_id DESC';
        
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
        
        $viewVariables = array(
            'orders' => $orders,
        );
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
