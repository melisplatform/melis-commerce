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

/**
 * MelisCommerce Plugin Price Countries select factory
 */
class EcomPluginPriceCountriesSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceManager $serviceManager)
    {
        $translator = $serviceManager->get('translator');
        
        $melisEcomCountryTable = $serviceManager->get('MelisEcomCountryTable');
        $ecomCountries = $melisEcomCountryTable->getCountries();
        
        $valueoptions = [];
        $valueoptions[-1] = $translator->translate('tr_meliscommerce_general_text');
        
        $max = $ecomCountries->count();
        for ($i = 0; $i < $max; $i++)
        {
            $data = $ecomCountries->current();
            $valueoptions[$data->ctry_id] = $data->ctry_name;
            $ecomCountries->next();
        }
        
        return $valueoptions;
    }
    
}