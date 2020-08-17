<?php

/**
 * Melis Technology (http://www.melistechnology.com)
*
* @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
*
*/

namespace MelisCommerce\Form\Factory\Plugin;

use Laminas\ServiceManager\ServiceManager;
use MelisCore\Form\Factory\MelisSelectFactory;
use Laminas\Session\Container;

/**
 * MelisCommerce Address Type Select
 */
class EcomPluginAddressTypeSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceManager $serviceManager)
    {
        // Getting Current Langauge ID
        $container = new Container('melisplugins');
        $locale = $container['melis-plugins-lang-locale'];
        
        $elangLangId = null;
        $eLangTbl = $serviceManager->get('MelisEcomLangTable');
        $eLang = $eLangTbl->getEntryByField('elang_locale', $locale)->current();
        if (!empty($eLang))
            $elangLangId = $eLang->elang_id;

        $melisEcomClientAddressTypeTransTable = $serviceManager->get('MelisEcomClientAddressTypeTransTable');
        $ecomAddressType = $melisEcomClientAddressTypeTransTable->getEntryByField('catypt_lang_id', $elangLangId);

        $valueoptions = [];
        $max = $ecomAddressType->count();
        for ($i = 0; $i < $max; $i++) {
            $data = $ecomAddressType->current();
            $valueoptions[$data->catypt_type_id] = $data->catypt_name;
            $ecomAddressType->next();
        }

        return $valueoptions;
    }
}