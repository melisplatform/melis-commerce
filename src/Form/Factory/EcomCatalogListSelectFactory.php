<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Form\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use MelisCore\Form\Factory\MelisSelectFactory;
use Zend\Session\Container;

class EcomCatalogListSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
    {
        $serviceManager = $formElementManager->getServiceLocator();

        // Getting Current Langauge ID
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];

        $catTble = $serviceManager->get('MelisComCategoryService');
        $catalogList = $catTble->getCategoryListByIdRecursive(null, $langId, true, null, null, -1);

        foreach ($catalogList as $catalog) {
            $catalogId = $catalog->getTranslations()[0]->cat_id;
            $catalogName = $catalog->getTranslations()[0]->catt_name;

            $valueoptions[$catalogId] = $catalogName;
        }

        return $valueoptions;
    }

}