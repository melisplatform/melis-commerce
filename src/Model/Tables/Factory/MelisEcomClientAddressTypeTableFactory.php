<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */
namespace MelisCommerce\Model\Tables\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use MelisCommerce\Model\MelisEcomClientAddressType;
use MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTable;

class MelisEcomClientAddressTypeTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomClientAddressType());
        $tableGateway = new TableGateway('melis_ecom_client_address_type', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
        
        return new MelisEcomClientAddressTypeTable($tableGateway);
    }
}