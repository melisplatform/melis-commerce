<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Form\Factory\Plugin;

use Zend\ServiceManager\ServiceLocatorInterface;
use MelisCommerce\Form\Factory\EcomSelectFactory;

/**
 * MelisCommerce Plugin Select Delivery Address
 */
class EcomPluginDeliveryAddressSelectFactory extends EcomSelectFactory
{
    protected function loadValueOptions(ServiceLocatorInterface $formElementManager)
    {
        $serviceManager = $formElementManager->getServiceLocator();

        // user parameter
        $clientId         = null;
        $personId         = null;
        $clientSrv        = $serviceManager->get('MelisComClientService');
        $melisComAuthSrv  = $serviceManager->get('MelisComAuthenticationService');
        $translator       = $serviceManager->get('translator');

        if($melisComAuthSrv->hasIdentity()) {
            $personId =  (int) $melisComAuthSrv->getPersonId();
        }
        
        $options = array();
        
        if(!is_null($personId)){
            $ecomAddresData = $clientSrv->getClientAddressesByClientPersonId($personId, 'DEL');
            if($ecomAddresData)  {
                foreach($ecomAddresData as $data) {
                    $options[$data->cadd_id] = $data->cadd_address_name;
                }
            }
        }

        $options['new_address'] = $translator->translate('tr_meliscommerce_client_add_new_address');
        return $options;
    }

}