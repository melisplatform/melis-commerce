<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use MelisCommerce\Listener\MelisCommerceSEODispatchRouterCommerceUrlListener;

class MelisCommerceSEODispatchRouterCommerceUrlListenerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{ 
    	$melisCommerceSEODispatchRouterCommerceUrlListener = new MelisCommerceSEODispatchRouterCommerceUrlListener($sl);
	    return $melisCommerceSEODispatchRouterCommerceUrlListener;
	}

}