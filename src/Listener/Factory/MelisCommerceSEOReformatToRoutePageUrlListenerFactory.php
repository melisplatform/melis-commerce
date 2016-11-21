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
use MelisCommerce\Listener\MelisCommerceSEOReformatToRoutePageUrlListener;

class MelisCommerceSEOReformatToRoutePageUrlListenerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{ 
    	$melisCommerceSEOReformatToRoutePageUrlListener = new MelisCommerceSEOReformatToRoutePageUrlListener($sl);
	    return $melisCommerceSEOReformatToRoutePageUrlListener;
	}

}