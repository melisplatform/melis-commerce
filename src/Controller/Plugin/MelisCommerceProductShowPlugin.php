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
 * "show porduct plugin" plugin.
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
 * $plugin = $this->MelisCommerceProductShowPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceProductShowPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCms/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'filterMenuCategoryList');
 * 
 * How to display in your controller's view:
 * echo $this->filterMenuCategoryList;
 * 
 * 
 */
class MelisCommerceProductShowPlugin extends MelisTemplatingPlugin
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
        $images = array();
        $rawImages = array();
        $variant = null; 
        $action = null;
        $selection = array();
        $currency = array();
        $productId = ($this->pluginFrontConfig['m_p_id'])? $this->pluginFrontConfig['m_p_id'] : 1;
        $variantId = ($this->pluginFrontConfig['m_p_var_id'])? $this->pluginFrontConfig['m_p_var_id'] : NULL;
        $countryId = ($this->pluginFrontConfig['m_p_country'])? $this->pluginFrontConfig['m_p_country'] : NULL;
        $langId = ($this->pluginFrontConfig['m_p_lang'])? $this->pluginFrontConfig['m_p_lang'] : NULL;
        $firstImagetype     = ($this->pluginFrontConfig['m_p_timage'])? $this->pluginFrontConfig['m_p_timage'] : NULL;
        $imageTypeAllowed   = ($this->pluginFrontConfig['m_p_timage_ok'])? $this->pluginFrontConfig['m_p_timage_ok'] : array();
        $attributeViewTemplate = ($this->pluginFrontConfig['template_path_attributes_view']) ? $this->pluginFrontConfig['template_path_attributes_view'] : 'MelisCommerceProduct/show-attributes';
        $addToCartViewTemplate = ($this->pluginFrontConfig['template_path_add_to_cart_view']) ? $this->pluginFrontConfig['template_path_add_to_cart_view'] : 'MelisCommerceProduct/show-add-to-cart';
        
        $productSvc = $this->getServiceLocator()->get('MelisComProductService');
        $variantSvc = $this->getServiceLocator()->get('MelisComVariantService');
        $currencySvc = $this->getServiceLocator()->get('MelisComCurrencyService');
        
        // get variant, main variant is fetch by default
        $product = $productSvc->getProductById($productId, $langId, $countryId, null, $imageTypeAllowed);
        
        if(is_null($variantId)){            
            $variant = $variantSvc->getMainVariantByProductId($productId, $langId, $countryId, null, $imageTypeAllowed);
            
            if(is_null($variant)){
                $variant = $variantSvc->getVariantListByProductId($productId);
                $variant = !empty($variant)? $variant[0] : $variant;
            }
        }else{
            $variant = $variantSvc->getVariantById($variantId, $langId, $countryId, null, $imageTypeAllowed);
        }
        
        if(!empty($rawImages) && $firstImagetype != NULL){
           $images = $this->reOrderImages($rawImages, $firstImagetype);
        }else{
            $images = $rawImages;
        }
        
        if(!empty($variant)){
            $tmp = $variant->getAttributeValues();
            usort($tmp, function($a, $b)
            {
                return strcmp($a->atval_attribute_id, $b->atval_attribute_id);
            });
            
            foreach($tmp as $val){
                $action = $val->atval_attribute_id;
                $selection[$val->atval_attribute_id] = $val->atval_id;
            }
        }
        
        $attributeShowPlugin = $this->getServiceLocator()->get('ControllerPluginManager')->get('MelisCommerceAttributesShowPlugin');
        $attributeShowParameters = array(
            'm_p_id' => $productId,
            'template_path' => $attributeViewTemplate,
            'm_p_country' => $countryId,
            'm_action' => $action,
            'm_attrSelection' => $selection,
            'm_is_submit' => !empty($action)? true : false
        );
        $attributeShowPluginView = $attributeShowPlugin->render($attributeShowParameters);
        
        if(!empty($variant)){
            $variantId = $variant->getVariant()->var_id;
        }
        
        $addToCartShowPlugin = $this->getServiceLocator()->get('ControllerPluginManager')->get('MelisCommerceCartAddPlugin');
        $addToCartShowParameters = array(
            'template_path' => $addToCartViewTemplate,
            'm_v_id' => $variantId,
            'm_v_country' => $countryId,
        );        
        $addToCartShowPluginView = $addToCartShowPlugin->render($addToCartShowParameters);
        
        $currency = $currencySvc->getDefaultCurrency();
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'product' => $product,
            'product_variant' => $variant,
            'currency' => $currency,
            'images' => $images,
            'attributes_view' => $attributeShowPluginView,
            'add_to_cart_view' => $addToCartShowPluginView,
        ); 
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    private function getStockQuantity($variant)
    {
        $stock = 0;
        if(!empty($variant)){
           $stock = $variant->getStocks()[0]->stock_quantity;
        }
        
        return $stock;
    }
    
    private function reOrderImages($rawImages, $firstImagetype)
    {
        $tmpImage = NULL;
        $images = array();
        foreach($rawImages as $rawImage){
            if($rawImage->dtype_sub_code == $firstImagetype ){
                $tmpImage = $rawImage;
            }else{
                $images[] = $rawImage;
            }
        }
        
        if(!empty($tmpImage)){
            array_unshift($images, $tmpImage);
        }
        return $images;
    }
}
