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

class EcomPluginProductListSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
    {
        $valueoptions = array();

        $serviceManager = $formElementManager->getServiceLocator();

        $productTble = $serviceManager->get('MelisEcomProductTable');
        $productList = $productTble->getProductList(array(), null, null, null, null, 'prd_reference');

        foreach ($productList As $val)
        {
            $valueoptions[$val->prd_id] = $val->prd_reference;
        }

        return $valueoptions;
    }
}