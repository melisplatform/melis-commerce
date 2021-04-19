<?php

/**
 * Melis Technology (http://www.melistechnology.com)
*
* @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
*
*/

namespace MelisCommerce\Form\Factory;

use Psr\Container\ContainerInterface;
use MelisCommerce\Form\ClientFieldset;

class EcomClientFieldsetFactory
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        return new ClientFieldset($container);
    }
}