<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Form\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use MelisCore\Form\Factory\MelisSelectFactory;

/**
 * MelisCommerce Countries select factory
 */
class EcomAttributeTypeSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
	{
		$serviceManager = $formElementManager->getServiceLocator();
		
		$attributeTypeTable = $serviceManager->get('MelisEcomAttributeTypeTable');
		$attributeType = array();
		
        foreach($attributeTypeTable->fetchAll() as $type){
            $attributeType[$type->atype_id ] = $type->atype_name;            
        }       
		
        //unset boolean for now
        $temp = $attributeType;
        foreach($temp as $key => $value){
            if($value == 'Boolean'){
                unset($attributeType[$key]);
            }
        }
        
		return $attributeType;
	}

}