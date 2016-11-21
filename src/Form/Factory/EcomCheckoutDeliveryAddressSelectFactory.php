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
use Zend\Session\Container;
/**
 * MelisCommerce Chechout Delivery Addresses Select Factory
 */
class EcomCheckoutDeliveryAddressSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
    {
        $serviceManager = $formElementManager->getServiceLocator();
        
        // Getting Current Langauge ID
        $melisTool = $serviceManager->get('MelisCoreTool');
        $langId = $melisTool->getCurrentLocaleID();
        
        $valueoptions = array();
        
        // Saving addresses in session for later use
        $container = new Container('meliscommerce');
        if (!empty($container['checkout']['clientId']))
        {
            $clientId = $container['checkout']['clientId'];
            
            $melisEcomClientAddressTable = $serviceManager->get('MelisEcomClientAddressTable');
            $ecomAddress = $melisEcomClientAddressTable->getClientDeliveryAddresses($clientId);
            
            foreach ($ecomAddress As $val)
            {
                $contactName = '';
                if (!is_null($val->cadd_client_person))
                {
                    $contactName = ' ( '.$val->cadd_firstname.' '.$val->cadd_name.' )';
                }
                
                $valueoptions[$val->cadd_id] = $val->cadd_address_name.$contactName;
            }
            
            $contactId = $container['checkout']['contactId'];
            $contactAddress = $melisEcomClientAddressTable->getContactDeliveryAddresses($contactId);
            
            foreach ($contactAddress As $val)
            {
                $valueoptions[$val->cadd_id] = $val->cadd_address_name;
            }
        }
        
        return $valueoptions;
    }
}