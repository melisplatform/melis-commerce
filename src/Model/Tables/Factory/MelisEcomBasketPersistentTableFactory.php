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
use MelisCommerce\Model\MelisEcomBasketPersistent;
use MelisCommerce\Model\Tables\MelisEcomBasketPersistentTable;

class MelisEcomBasketPersistentTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomBasketPersistent());
        $tableGateway = new TableGateway('melis_ecom_basket_persistent', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
        
        return new MelisEcomBasketPersistentTable($tableGateway);
    }
}