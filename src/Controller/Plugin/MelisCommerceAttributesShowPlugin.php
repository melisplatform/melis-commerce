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
 * "show attribute plugin" plugin.
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
 * $plugin = $this->MelisCommerceAttributesShowPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceAttributesShowPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCommerce/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'attributesShowPlugin');
 * 
 * How to display in your controller's view:
 * echo $this->attributesShowPlugin;
 * 
 * 
 */
class MelisCommerceAttributesShowPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $variant = array();
        $varPrice = array();
        $varStock = array();
        $variantAttr = array();
        
        $images = array();
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $productId = ($this->pluginFrontConfig['m_p_id'])? $this->pluginFrontConfig['m_p_id'] : NULL;    
        $action = ($this->pluginFrontConfig['m_action'])? $this->pluginFrontConfig['m_action'] : array();
        $attrSelection = ($this->pluginFrontConfig['m_attrSelection'])? $this->pluginFrontConfig['m_attrSelection'] : array();
        $countryId = ($this->pluginFrontConfig['m_p_country'])? $this->pluginFrontConfig['m_p_country'] : 0;
        $is_submit = ($this->pluginFrontConfig['m_is_submit'])? $this->pluginFrontConfig['m_is_submit'] : false;
        
        $attributeSvc = $this->getServiceLocator()->get('MelisComAttributeService');
        // Array of attribute entities, refer to melis-commerce/src/entity/MelisAttribute
        $attributes = $attributeSvc->getUsedAttributeValuesByProductId($productId);
        
        if ($is_submit)
        {
            $variantSrv = $this->getServiceLocator()->get('MelisComVariantService');
            
            /**
             * re-initialize attrSelection
             * This will depend on action, this will initialize to empty the greater value
             * of the current action
             * Sample:
             * Posted
             * $action = 1;
             * $attrSelection = array(
             *  '1' => 2,
             *  '2' => 3,
             *  '3' => 4
             *  );
             * Result
             * $attrSelection = array(
             *  '1' => 2,
             *  '2' => '',
             *  '3' => ''
             *  );
             *
             *  The purpose is to select all variant having the first attribute up to last attribut
             */
            foreach ($attrSelection As $key => $val)
            {
                if ($action < $key)
                {
                    $attrSelection[$key] = '';
                }
            }
            
            /**
             * Retrieving Variant that has common attribute to other variant
             * with the same Attribute id and attribute value id
             */
            $variantsAttr = array();
            foreach ($attrSelection As $aKey => $aVal)
            {
                $temp = $variantSrv->getVariantCommonAttr($productId, $aKey, $aVal);
            
                foreach ($temp As $vKey => $vVey)
                {
                    if (!empty($variantsAttr[$aKey]))
                    {
                        if (!in_array($vVey->var_id, $variantsAttr[$aKey]))
                        {
                            array_push($variantsAttr[$aKey], $vVey->var_id);
                        }
                    }
                    else
                    {
                        $variantsAttr[$aKey][] = $vVey->var_id;
                    }
                }
            }
            
            /**
             * merging results of variants ids
             * In this case this will only merge that match from other array
             */
            $variantsIds = array();
            foreach ($attrSelection As $key => $val)
            {
                if (empty($variantsIds))
                {
                    $variantsIds = $variantsAttr[$key];
                }
                else
                {
                    $variantsIds = array_intersect($variantsIds, $variantsAttr[$key]);
                }
            }
            
            /**
             * Retrieving variant the match to the attributes submited
             */
            $variants = $variantSrv->getVariantsAttrGroupByAttr($variantsIds);
            foreach ($variants As $val)
            {
                if (!isset($variantAttr[$val->attr_id]))
                {
                    $variantAttr[$val->attr_id]['selected'] = (int) $attrSelection[$val->attr_id];
                    $variantAttr[$val->attr_id]['selections'][] = (int) $val->vatv_attribute_value_id;
                }
                else
                {
                    $variantAttr[$val->attr_id]['selections'][] = (int) $val->vatv_attribute_value_id;
                }
            }
            
            if (count(array_filter($attrSelection)) == count($attrSelection))
            {
                if (!empty($variantsIds))
                {
                    sort($variantsIds);
                    
                    // Getting the Variant from Variant Service Entity
                    $variant = $variantSrv->getVariantById($variantsIds[0]);
                    
                    // Getting the Final Price of the variant
                    $varPrice = $variantSrv->getVariantFinalPrice($variantsIds[0], $countryId, true);
                    
                    if (is_null($varPrice))
                    {
                        $productSrv = $this->getServiceLocator()->get('MelisComProductService');
                        // If the variant price not set on variant page this will try to get from the Product Price
                        $varPrice = $productSrv->getProductFinalPrice($productId, $countryId, true);
                    }
                    
                    // Getting Variant stock
                    $varStock = $variantSrv->getVariantFinalStocks($variantsIds[0], $countryId);
                    if ($varStock)
                    {
                        $ecomAuthSrv = $this->getServiceLocator()->get('MelisComAuthenticationService');
                        $basketSrv = $this->getServiceLocator()->get('MelisComBasketService');
                        
                        $clientKey = $ecomAuthSrv->getId();
                        $clientId = null;
                        if ($ecomAuthSrv->hasIdentity())
                        {
                            $clientId = $ecomAuthSrv->getClientId();
                            $clientKey = $ecomAuthSrv->getClientKey();
                        }
                        
                        $currentQty = 0;
                        
                        $currentBasket = $basketSrv->getBasket($clientId, $clientKey);
                        if(!empty($currentBasket)){
                            foreach($currentBasket as $item){
                                if($item->getVariantId() == $variantsIds[0]){
                                    $currentQty = $item->getQuantity();
                                }
                            }
                        }
                        $varStock->stock_quantity = $varStock->stock_quantity - $currentQty;
                        
                    }
                }
            }
        }
       
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'attributes' => $attributes,
            'product_id' => $productId,
            'variant' => $variant,
            'variant_price' => $varPrice,
            'variant_stock' => $varStock,
            'variant_attr' => $variantAttr
        );
        
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
