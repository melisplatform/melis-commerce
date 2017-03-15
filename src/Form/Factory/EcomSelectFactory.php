<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Form\Factory;

use Zend\Form\Element\Select;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class EcomSelectFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $formElementManager)
	{
		$element = new Select;
		$element->setValueOptions($this->loadValueOptions($formElementManager));
		return $element;
	}

}