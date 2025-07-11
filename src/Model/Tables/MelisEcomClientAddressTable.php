<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Model\Tables;

class MelisEcomClientAddressTable extends MelisEcomGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_ecom_client_address';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'cadd_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getClientAddressByClientId($clientId, $addressType = null)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join(
            'melis_ecom_client_address_type',
            'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),
            $select::JOIN_LEFT
        );
        $select->join(
            'melis_ecom_civility',
            'melis_ecom_civility.civ_id=melis_ecom_client_address.cadd_civility',
            array('*'),
            $select::JOIN_LEFT
        );

        $select->where->equalTo('cadd_client_id', (int)$clientId);

        if (!is_null($addressType)) {
            $select->where->equalTo('cadd_type', $addressType);
        }

        $select->where('cadd_client_person IS NULL');

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    public function getPersonAddressByPersonId($personId, $addressType = null, $caddId = null)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join(
            'melis_ecom_client_address_type',
            'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),
            $select::JOIN_LEFT
        );
        $select->join(
            'melis_ecom_civility',
            'melis_ecom_civility.civ_id=melis_ecom_client_address.cadd_civility',
            array('*'),
            $select::JOIN_LEFT
        );

        if ($personId) {
            $select->where->equalTo('cadd_client_person', (int)$personId);
        }


        if (!is_null($addressType)) {
            $select->where->equalTo('melis_ecom_client_address_type.catype_code', $addressType);
        }

        if (!is_null($caddId)) {
            $select->where->equalTo('cadd_id', (int) $caddId);
        }

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    public function getClientPersonAddressByAddressId($personId, $addrId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->where->equalTo('cadd_client_person', (int)$personId);
        $select->where->equalTo('cadd_id', (int)$addrId);

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    public function getClientBillingAddresses($clientId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join(
            'melis_ecom_client_address_type',
            'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),
            $select::JOIN_LEFT
        );

        $select->where->equalTo('cadd_client_id', (int)$clientId);
        $select->where('cadd_client_person IS NULL');
        $select->where('catype_code = "BIL"');

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    public function getContactBillingAddresses($contactId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join(
            'melis_ecom_client_address_type',
            'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),
            $select::JOIN_LEFT
        );

        $select->where->equalTo('cadd_client_person', (int)$contactId);
        $select->where('catype_code = "BIL"');

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    public function getClientDeliveryAddresses($clientId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join(
            'melis_ecom_client_address_type',
            'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),
            $select::JOIN_LEFT
        );

        $select->where->equalTo('cadd_client_id', (int)$clientId);
        $select->where('cadd_client_person IS NULL');
        $select->where('catype_code = "DEL"');

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }

    public function getContactDeliveryAddresses($contactId)
    {
        $select = $this->getTableGateway()->getSql()->select();

        $select->join(
            'melis_ecom_client_address_type',
            'melis_ecom_client_address_type.catype_id=melis_ecom_client_address.cadd_type',
            array('*'),
            $select::JOIN_LEFT
        );

        $select->where->equalTo('cadd_client_person', (int)$contactId);
        $select->where('catype_code = "DEL"');

        $resultData = $this->getTableGateway()->selectWith($select);
        return $resultData;
    }
}
