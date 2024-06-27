<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2017 Melis Technology (http://www.melistechnology.com)
 *
 */
namespace MelisCommerce\Form\Factory\Plugin;


use Laminas\ServiceManager\ServiceManager;
use MelisCore\Form\Factory\MelisSelectFactory;
use Laminas\Session\Container;

class EcomPluginProductListSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceManager $serviceManager)
    {
        $valueoptions = [];

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
                $productText = $productTble->getProductText($val->prd_id, null, 'TITLE')->toArray();
                if(!empty($productText)){
                    foreach($productText as $key => $textVal){
                        $flag = false;
                        if($textVal['ptxt_lang_id'] == $langIdBO){
                            $valueoptions[$val->prd_id] = $textVal['ptxt_field_short'];
                            $flag = true;
                            break;
                        }

                        if(!$flag){
                            $valueoptions[$val->prd_id] = $textVal['ptxt_field_short'];
                        }
                    }
                }
            }
        }

        return $valueoptions;
    }
}