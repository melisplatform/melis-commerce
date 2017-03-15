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
use Zend\Stdlib\ArrayUtils;
/**
 * This plugin implements the business logic of the
 * "add to cart" plugin.
 * 
 * Please look inside app.plugins.products.php for possible awaited parameters
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
 * $plugin = $this->MelisCommerceCartAddPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCartAddPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCms/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'addToCart');
 * 
 * How to display in your controller's view:
 * echo $this->addToCart;
 * 
 * 
 */
class MelisCommerceCartAddPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $errors = array();
        $hasStock = false;
        $currentQty = 0;
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $countryId = ($this->pluginFrontConfig['m_v_country'])? $this->pluginFrontConfig['m_v_country'] : 0;
        $quantity = ($this->pluginFrontConfig['m_v_quantity'])? $this->pluginFrontConfig['m_v_quantity'] : 1;
        $variantId = ($this->pluginFrontConfig['m_v_id'])? $this->pluginFrontConfig['m_v_id'] : null;
        $is_submit = ($this->pluginFrontConfig['m_is_submit'])? $this->pluginFrontConfig['m_is_submit'] : false;
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['meliscommerce_add_to_cart_form'])) ? $this->pluginFrontConfig['forms']['meliscommerce_add_to_cart_form'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $addToCartForm = $factory->createForm($appConfigForm);

        
        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
        $basketSrv = $this->getServiceLocator()->get('MelisComBasketService');
        
        $clientKey = $ecomAuthSrv->getId();
        $clientId = null;
        if ($ecomAuthSrv->hasIdentity())
        {
            $clientId = $ecomAuthSrv->getClientId();
            $clientKey = $ecomAuthSrv->getClientKey();
        }
        
        $currentBasket = $basketSrv->getBasket($clientId, $clientKey);
        if(!empty($currentBasket)){
            foreach($currentBasket as $item){
                if($item->getVariantId() == $variantId){
                    $currentQty = $item->getQuantity();
                }
            }
        }
        
        $data['m_v_id'] = $variantId;
        $data['m_v_quantity'] = $quantity;
        $data['m_v_country'] = $countryId;
        
        $addToCartForm->setData($data);
        
//         echo '<pre>'; print_r($currentQty); echo '</pre>'; die();
        /**
         * Retrieving the Stock of the variant
         */
        $varStock = 0;
        if ($variantId)
        {
            $variantSrv = $this->getServiceLocator()->get('MelisComVariantService');
            $varStock = $variantSrv->getVariantFinalStocks($variantId, $countryId);
        
            if ($varStock)
            {
                $newStock = $varStock->stock_quantity - $currentQty;
                $varStock = ($newStock > 0) ? $newStock : 0; 
            }
        }
        
        // Pre value to form input field
        $addToCartForm->get('m_v_quantity')->setAttribute('data-maxvalue', $varStock);
        $addToCartForm->get('m_v_country')->setValue($countryId);
        
        /**
         * Stock validation
         */
        if ($varStock)
        {
            $hasStock = true;
            
            if ($quantity <= $varStock)
            {
                if ($is_submit)
                {
                    if ($addToCartForm->isValid())
                    {
                        $data = $addToCartForm->getData();
                        $quantity = $quantity + $currentQty;
                        $basketId = $basketSrv->addVariantToBasket($variantId, $quantity, $clientId, $clientKey);
                        
                        if (is_null($basketId))
                        {
                            $errors['genError'] = array(
                                'genError' => 'Something is wrong, please contact administrator for assistance'
                            );
                        }
                    }
                    else
                    {
                        $errors = $addToCartForm->getMessages();
                    }
                }
            }
            else
            {
                $errors['invalidStock'] = array(
                    'invalidStock' => 'The selected Product has only "'.$varStock.'" stock(s) left'
                );
            }
        }
        else
        {
            $errors['noStock'] = array(
                'noStock' => 'Product is not available'
            );;
        }
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'addToCcart' => $addToCartForm,
            'errors' => $errors
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
}
