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
 * $plugin = $this->MelisCommerceCartMenuPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCartMenuPlugin();
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
class MelisCommerceCartMenuPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $limit = !empty($this->pluginFrontConfig['cart_menu_limit'])? $this->pluginFrontConfig['cart_menu_limit'] : null;
        $basket = array();
        
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $basketSrv = $this->getServiceLocator()->get('MelisComBasketService');
        $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
        $currencyTbl = $this->getServiceLocator()->get('MelisEcomCurrencyTable');
        
        $clientKey = $ecomAuthSrv->getId();
        $clientId = null;
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
        }
        
        $basketObj = $basketSrv->getBasket($clientId, $clientKey);
        
        if($basketObj){
            foreach($basketObj as $item){
            
                $var = $item->getVariant();
                
                $product = $prodSvc->getProductById($var->getVariant()->var_prd_id, null, null, 'IMG', array('DEFAULT'));

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
                    $currency = $currencyTbl->getEntryByField('cur_default', 1)->current();
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
                        if($doc->dtype_sub_code == 'DEFAULT'){
                            $tmp['image'] = $doc->doc_path;
                        }
                    }
                }
            
                $basket[] = $tmp;
            }
        }
        
        $viewVariables = array(
            'basket' => $basket,
        );
//         echo '<pre>'; print_r($viewVariables); echo 'asdasd</pre>';
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    /**
     * This function return the back office rendering for the template edition system
     * TODO
     */
    public function back()
    {
        return array();
    }
}
