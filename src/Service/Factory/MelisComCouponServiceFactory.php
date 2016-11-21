<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use MelisCommerce\Service\MelisComCouponService;

class MelisComCouponServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{ 
	    $melisCommerceCoupons = new MelisComCouponService();
	    $melisCommerceCoupons->setServiceLocator($sl);
	    return $melisCommerceCoupons;
	}

}