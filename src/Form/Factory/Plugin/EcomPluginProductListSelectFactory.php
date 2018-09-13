<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2017 Melis Technology (http://www.melistechnology.com)
 *
 */
namespace MelisCommerce\Form\Factory\Plugin;


use MelisCore\Form\Factory\MelisSelectFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class EcomPluginProductListSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
    {
        $valueoptions = array();

        $serviceManager = $formElementManager->getServiceLocator();

        $productTble = $serviceManager->get('MelisEcomProductTable');
        $container = new Container('meliscore');
        $langIdBO = $container['melis-lang-id'];

        $productList = $productTble->getProductList(array(), null, null, null, null, 'prd_reference');

        foreach ($productList As $val)
        {
            if(!empty($val->prd_reference)) {
                $valueoptions[$val->prd_id] = $val->prd_reference;
            }else{
                /**
                 * although its impossible to make the product reference
                 * to be empty, but there are cases like if the database
                 * is migrated and the product don't have a reference,
                 * so if this will happen, we will used the product title
                 */
                $productText = $productTble->getProductText($val->prd_id, $langIdBO, 'TITLE')->toArray();
                if(!empty($productText)){
                    if(isset($productText[0])){
                        $valueoptions[$val->prd_id] = $productText[0]['ptxt_field_short'];
                    }
                }
            }
        }

        return $valueoptions;
    }
}