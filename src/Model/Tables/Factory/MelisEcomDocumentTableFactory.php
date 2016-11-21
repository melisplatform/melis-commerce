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
use MelisCommerce\Model\MelisEcomDocument;
use MelisCommerce\Model\Tables\MelisEcomDocumentTable;

class MelisEcomDocumentTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisEcomDocument());
        $tableGateway = new TableGateway('melis_ecom_document', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
        
        return new MelisEcomDocumentTable($tableGateway);
    }
}