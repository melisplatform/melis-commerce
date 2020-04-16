<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Form\Factory; 

use Laminas\Form\Element\Text;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceManager;

/**
 * Melis commerce date field
 */

class EcomColorPicker extends Text
{
    public function createService(ServiceManager $serviceManager)
    { 
        $element = new Text;        
        $element->setAttribute('class', 'color-picker');
        
        return $element;
    }
}

