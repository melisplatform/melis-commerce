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
/**
 * MelisCommerce Civility Select
 */
class EcomCivilitySelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
    {
        $serviceManager = $formElementManager->getServiceLocator();
        
        // Getting Current Langauge ID
        $melisTool = $serviceManager->get('MelisCoreTool');
        $langId = $melisTool->getCurrentLocaleID();
        
        $melisEcomCivilityTransTable = $serviceManager->get('MelisEcomCivilityTransTable');
        $ecomCivility = $melisEcomCivilityTransTable->getCivilityByLangId($langId);

        $translator = $serviceManager->get('translator');
        $valueoptions = array();
        $max = $ecomCivility->count();
        for ($i = 0; $i < $max; $i++)
        {
            $data = $ecomCivility->current();
            $valueoptions[$data->civt_civ_id] = $data->civt_min_name;
            $ecomCivility->next();
        }
        return $valueoptions;
    }

}