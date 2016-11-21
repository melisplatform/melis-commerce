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
use MelisCommerce\Service\MelisComPostPaymentService;

class MelisComPostPaymentServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{ 
	    $melisComPostPayment = new MelisComPostPaymentService();
	    $melisComPostPayment->setServiceLocator($sl);
	    return $melisComPostPayment;
	}

}