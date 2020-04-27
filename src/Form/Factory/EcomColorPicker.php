<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Form\Factory; 

use Laminas\Form\Element\Text;
use Psr\Container\ContainerInterface;

/**
 * Melis commerce date field
 */
class EcomColorPicker extends Text
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return Text
     */
    public function __invoke(ContainerInterface $container, $requestedName)
    { 
        $element = new Text;        
        $element->setAttribute('class', 'color-picker');
        
        return $element;
    }
}

