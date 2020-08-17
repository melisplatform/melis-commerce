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
 * MelisCommerce Plugin Civility Select
 */
class EcomPluginCivilitySelectFactory extends MelisSelectFactory
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

        $valueoptions = [];
        if (!empty($elangLangId)) {
            $melisEcomCivilityTransTable = $serviceManager->get('MelisEcomCivilityTransTable');
            $ecomCivility = $melisEcomCivilityTransTable->getCivilityByLangId($elangLangId);
            
            $max = $ecomCivility->count();
            for ($i = 0; $i < $max; $i++) {
                $data = $ecomCivility->current();
                $valueoptions[$data->civt_civ_id] = $data->civt_min_name;
                $ecomCivility->next();
            }
        }
        
        return $valueoptions;
    }

}