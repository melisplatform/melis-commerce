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
use MelisCommerce\Model\MelisEcomCountry;
use MelisCommerce\Model\Tables\MelisEcomCountryTable;

class MelisEcomCountryTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomCountry());
        $tableGateway = new TableGateway('melis_ecom_country', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
        
        return new MelisEcomCountryTable($tableGateway);
    }
}