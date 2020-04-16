<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Form\Factory;

use Laminas\ServiceManager\ServiceManager;
use MelisCore\Form\Factory\MelisSelectFactory;
use Laminas\Session\Container;

class EcomCatalogListSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceManager $serviceManager)
    {
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