<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use MelisCommerce\Entity\MelisClientPerson;
use Laminas\Crypt\BlockCipher;
use Laminas\Crypt\Symmetric\Mcrypt;
use Laminas\Http\Response;
/**
 *
 * This service handles the client system of MelisCommerce.
 *
 */
class MelisComContactService extends MelisComGeneralService
{
    /**
     * @param null $accountId
     * @param string $searchValue
     * @param array $searchKeys
     * @param null $start
     * @param null $limit
     * @param string $orderColumn
     * @param string $order
     * @param bool $defaultAccountOnly
     * @param bool $count
     * @return mixed
     */
    public function getContactLists($accountId = null, $searchValue = '', $searchKeys = [], $start = null, $limit = null, $orderColumn = 'cper_id', $order = 'DESC', $defaultAccountOnly = false, $count = false)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_contact_lists_start', $arrayParameters);

        // Service implementation start
        $personTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
        $result = null;
        $result = $personTable->getContactLists(
            $arrayParameters['accountId'],
            $arrayParameters['searchValue'],
            $arrayParameters['searchKeys'],
            $arrayParameters['start'],
            $arrayParameters['limit'],
            $arrayParameters['orderColumn'],
            $arrayParameters['order'],
            $arrayParameters['defaultAccountOnly'],
            $arrayParameters['count']
        );

        $arrayParameters['results'] = $result;

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_contact_lists_end', $arrayParameters);

        return $arrayParameters['results'];

    }

    /**
     * Return all associated account for this contact
     *
     * @param null $contactId
     * @param string $searchValue
     * @param array $searchKeys
     * @param null $start
     * @param null $limit
     * @param string $orderColumn
     * @param string $order
     * @param bool $count
     * @return mixed
     */
    public function getContactAssocAccountLists($contactId = null, $searchValue = '', $searchKeys = [], $start = null, $limit = null, $orderColumn = 'cper_id', $order = 'DESC', $count = false)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_contact_lists_start', $arrayParameters);

        // Service implementation start
        $personTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
        $result = null;
        $result = $personTable->getContactAssocAccountLists(
            $arrayParameters['contactId'],
            $arrayParameters['searchValue'],
            $arrayParameters['searchKeys'],
            $arrayParameters['start'],
            $arrayParameters['limit'],
            $arrayParameters['orderColumn'],
            $arrayParameters['order'],
            $arrayParameters['count']
        );

        $arrayParameters['results'] = $result;

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_contact_lists_end', $arrayParameters);

        return $arrayParameters['results'];

    }

    /**
     *
     * This method saves a client person in database.
     *
     * @param array $person Person reflecting the melis_ecom_client_person table
     * @param array[] $clientAccountAddresses Array of addresses reflecting the melis_ecom_client_address table
     * @param int $personId If specified, an update will be done instead of an insert
     *
     * @return int|null The client person id created or updated, null if an error occured
     */
    public function saveContact($person, $clientPersonAddresses = array(), $personId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_save_contact_start', $arrayParameters);

        // Service implementation start
        $melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
        $melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');

        try
        {
            $clientId = $arrayParameters['person']['cper_client_id'];
            if (is_null($arrayParameters['personId']))
            {
                $arrayParameters['person']['cper_date_creation'] = date('Y-m-d H:i:s');
            }
            else
            {
                $arrayParameters['person']['cper_date_edit'] = date('Y-m-d H:i:s');
            }

            if (! empty($arrayParameters['personId'])) {
                if (! empty($arrayParameters['person']['cper_is_main_person'])) {
                    $arrayParameters['person']['cper_is_main_person'] = $arrayParameters['person']['cper_is_main_person'];
                }
            }

            $arrayParameters['person']['cper_civility'] = (!empty($arrayParameters['person']['cper_civility'])) ? $arrayParameters['person']['cper_civility'] : 0;

            if (!empty($arrayParameters['person']['cper_password']))
            {
                $clientService = $this->getServiceManager()->get('MelisComClientService');
                $arrayParameters['person']['cper_password'] = $clientService->crypt($arrayParameters['person']['cper_password']);
            }

            $arrayParameters['person']['cper_firstname'] = ucwords(mb_strtolower($arrayParameters['person']['cper_firstname']));
            $arrayParameters['person']['cper_name'] = mb_strtoupper($arrayParameters['person']['cper_name']);

            if (!empty($arrayParameters['person']['cper_middle_name']))
            {
                $arrayParameters['person']['cper_middle_name'] = ucwords(mb_strtolower($arrayParameters['person']['cper_middle_name']));
            }

            $arrayParameters['person']['cper_email'] = mb_strtolower($arrayParameters['person']['cper_email']);
            unset($arrayParameters['person']['cper_id']);
            $perId = $melisEcomClientPersonTable->save($arrayParameters['person'], $arrayParameters['personId']);

            $clientPersonAddData = $arrayParameters['clientPersonAddresses'];
            foreach ($clientPersonAddData As $key => $val)
            {
                $val['cadd_client_id'] = $clientId;
                $val['cadd_client_person'] = $perId;
                $caddId = $val['cadd_id'] ? $val['cadd_id'] : null;
                unset($val['cadd_id']);

                if (is_null($caddId))
                {
                    $val['cadd_creation_date'] = date('Y-m-d H:i:s');
                }

                $val['cadd_civility'] = ($val['cadd_civility']) ? $val['cadd_civility'] : null;

                $val['cadd_name'] = mb_strtoupper($val['cadd_name']);
                $val['cadd_firstname'] = ucwords(mb_strtolower($val['cadd_firstname']));
                if (!empty($val['cadd_middle_name']))
                {
                    $val['cadd_middle_name'] = ucwords(mb_strtolower($val['cadd_middle_name']));
                }

                $melisEcomClientAddressTable->save($val, $caddId);
            }

            $results = $perId;
        }
        catch (\Exception $e)
        {
            echo $e->getMessage(); die();
        }

        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_save_contact_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @param $clientPersonId
     * @param $email
     * @return mixed
     */
    public function saveContactEmail($clientPersonId, $email)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_save_contact_email_start', $arrayParameters);

        // Service implementation start
        $table = $this->getServiceManager()->get('MelisEcomClientPersonEmailsTable');
        $result = null;
        $id = null;

        try {
            $clientPersonEmailData = $table->getDataByClientPersonIdAndEmail($arrayParameters['clientPersonId'], $arrayParameters['email'])->current();

            if (!empty($clientPersonEmailData)) {
                $id = $clientPersonEmailData->cpmail_id;
            }

            $result = $table->save([
                'cpmail_cper_id' => $arrayParameters['clientPersonId'],
                'cpmail_email' => $arrayParameters['email']
            ], $id);
        } catch (\Exception $ex) {

        }

        $arrayParameters['results'] = $result;

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_save_contact_email_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @param $contactId
     * @return mixed
     */
    public function getContactById($contactId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_get_contact_by_id_start', $arrayParameters);

        // Service implementation start
        $melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');

        $clientContactData = $melisEcomClientPersonTable->getEntryById($arrayParameters['contactId'])->current();

        $arrayParameters['results'] = $clientContactData;

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_get_contact_by_id_end', $arrayParameters);

        return $arrayParameters['results'];
    }


    /**
     * @param $contactId
     * @param null $addressType
     * @param null $caddId
     * @return mixed
     */
    public function getContactAddressById($contactId, $addressType = null, $caddId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_get_contact_address_by_id_start', $arrayParameters);

        // Service implementation start
        $melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
        $clientAddress = $melisEcomClientAddressTable->getPersonAddressByPersonId($arrayParameters['contactId'], $arrayParameters['addressType'], $arrayParameters['caddId']);
        foreach ($clientAddress As $val)
        {
            array_push($results, $val);
        }
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_get_contact_address_by_id_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @param $accountId
     * @param $contactId
     * @return mixed
     */
    public function unlinkAccountContact($accountId, $contactId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_unlink_account_contact_start', $arrayParameters);

        // Service implementation start
        $personRelTable = $this->getServiceManager()->get('MelisEcomClientPersonRelTable');
        $results = $personRelTable->unlinkAccountContact($arrayParameters['accountId'], $arrayParameters['contactId']);
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_unlink_account_contact_end', $arrayParameters);

        return $arrayParameters['results'];
    }
}