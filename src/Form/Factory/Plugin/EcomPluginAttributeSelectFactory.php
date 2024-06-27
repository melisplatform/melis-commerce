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
 * MelisCommerce Attributes Select
 */
class EcomPluginAttributeSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceManager $serviceManager)
    {
        // Getting Current Langauge ID
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        $attrSrv = $serviceManager->get('MelisComAttributeService');
        $attrs = $attrSrv->getAttributeListAndValues(null, true, true, $langId);

        $valueoptions = [];
        foreach ($attrs As $val)
        {
            $valueoptions[$val->attr_id] = $val->atrans_name;
        }
        return $valueoptions;
    }

}