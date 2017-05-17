<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Form\Factory; 

use Zend\Form\Element\Text;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

/**
 * Melis commerce date field
 */

class EcomColorPicker extends Text implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $formElementManager)
    { 
        $element = new Text;        
        $element->setAttribute('class', 'color-picker');
        
        return $element;
    }
}

