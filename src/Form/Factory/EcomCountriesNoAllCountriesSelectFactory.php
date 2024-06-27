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

/**
 * MelisCommerce Countries select factory With no Option "All Countries"
 */
class EcomCountriesNoAllCountriesSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceManager $serviceManager)
    {
        $melisEcomCountryTable = $serviceManager->get('MelisEcomCountryTable');
        $ecomCountries = $melisEcomCountryTable->getCountries();

        $translator = $serviceManager->get('translator');
        $valueoptions = [];
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