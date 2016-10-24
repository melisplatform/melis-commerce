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
/**
 * MelisCommerce Address Type Select
 */
class EcomAddressTypeSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
    {
        $serviceManager = $formElementManager->getServiceLocator();
        
        // Getting Current Langauge ID
        $melisTool = $serviceManager->get('MelisCoreTool');
        $langId = $melisTool->getCurrentLocaleID();
        
        $melisEcomClientAddressTypeTransTable = $serviceManager->get('MelisEcomClientAddressTypeTransTable');
        $ecomAddressType = $melisEcomClientAddressTypeTransTable->getEntryByField('catypt_lang_id', $langId);

        $valueoptions = array();
        $max = $ecomAddressType->count();
        for ($i = 0; $i < $max; $i++)
        {
            $data = $ecomAddressType->current();
            $valueoptions[$data->catypt_type_id] = $data->catypt_name;
            $ecomAddressType->next();
        }
        return $valueoptions;
    }

}