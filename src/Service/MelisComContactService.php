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
     * @param null $type
     * @param string $searchValue
     * @param array $searchKeys
     * @param null $start
     * @param null $limit
     * @param string $orderColumn
     * @param string $order
     * @param bool $defaultAccountOnly
     * @param bool $hasDefaultOnly
     * @param bool $count
     * @return mixed
     */
    public function getContactLists($accountId = null, $type = null, $searchValue = '', $searchKeys = [], $start = null, $limit = null, $orderColumn = 'cper_id', $order = 'DESC', $defaultAccountOnly = false, $hasDefaultOnly = false, $count = false)
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
            $arrayParameters['type'],
            $arrayParameters['searchValue'],
            $arrayParameters['searchKeys'],
            $arrayParameters['start'],
            $arrayParameters['limit'],
            $arrayParameters['orderColumn'],
            $arrayParameters['order'],
            $arrayParameters['defaultAccountOnly'],
            $arrayParameters['hasDefaultOnly'],
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
     * This method saves a contact in database.
     *
     * Note: Contact emails must be included in the persons data
     * Ex:
     * [
     *      cper_name => 'Test',
     *      cper_firstname => 'Test',
     *      .......
     *      .......
     *      emails => [
     *          '0' => ['cpmail_email' => 'test@test.com']
     *      ]
     * ]
     * *email keys are the id of email in the table, 0 for new email
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
//            $clientId = $arrayParameters['person']['cper_client_id'];
            if (is_null($arrayParameters['personId']))
            {
                $arrayParameters['person']['cper_date_creation'] = date('Y-m-d H:i:s');
            }
            else
            {
                $arrayParameters['person']['cper_date_edit'] = date('Y-m-d H:i:s');
            }

//            if (! empty($arrayParameters['personId'])) {
//                if (! empty($arrayParameters['person']['cper_is_main_person'])) {
                    $arrayParameters['person']['cper_is_main_person'] = (int)$arrayParameters['person']['cper_is_main_person'] ?? 0;
//                }
//            }

            $arrayParameters['person']['cper_civility'] = (!empty($arrayParameters['person']['cper_civility'])) ? $arrayParameters['person']['cper_civility'] : 0;

            if (!empty($arrayParameters['person']['cper_password']))
            {
                $clientService = $this->getServiceManager()->get('MelisComClientService');
                $arrayParameters['person']['cper_password'] = $clientService->crypt($arrayParameters['person']['cper_password']);
            }

            $arrayParameters['person']['cper_firstname'] = ucwords(mb_strtolower($arrayParameters['person']['cper_firstname']));

            if(!empty($arrayParameters['person']['cper_name']))
                $arrayParameters['person']['cper_name'] = mb_strtoupper($arrayParameters['person']['cper_name']);

            if (!empty($arrayParameters['person']['cper_middle_name']))
            {
                $arrayParameters['person']['cper_middle_name'] = ucwords(mb_strtolower($arrayParameters['person']['cper_middle_name']));
            }

            $arrayParameters['person']['cper_email'] = mb_strtolower($arrayParameters['person']['cper_email']);
            unset($arrayParameters['person']['cper_id']);
            //get emails
            $personEmails = $this->getServiceManager()->get('MelisEcomClientPersonEmailsTable');
            $perEmails = [];
            if(!empty($arrayParameters['person']['emails'])){
                $perEmails = $arrayParameters['person']['emails'];
                unset($arrayParameters['person']['emails']);
            }else{
                if(!empty($arrayParameters['personId'])) {
                    // check if email is not used twice
                    $personWithSameEmail = $personEmails->getEntryByField('cpmail_email', $arrayParameters['person']['cper_email'])->current();
                    if (!empty($personWithSameEmail)) {
                        foreach ($personWithSameEmail as $k => $mail) {
                            if ($mail->cpmail_cper_id != $arrayParameters['personId']) {
                                $perEmails[] = [//new email entry
                                    'cpmail_email' => $arrayParameters['person']['cper_email']
                                ];
                            } else {
                                $perEmails[$mail->cpmail_id] = [
                                    'cpmail_email' => $arrayParameters['person']['cper_email']
                                ];
                            }
                        }
                    } else {
                        $perEmails[] = [//new email entry
                            'cpmail_email' => $arrayParameters['person']['cper_email']
                        ];
                    }
                }else {
                    $perEmails[] = [//new email entry
                        'cpmail_email' => $arrayParameters['person']['cper_email']
                    ];
                }
            }
            $perId = $melisEcomClientPersonTable->save($arrayParameters['person'], $arrayParameters['personId']);
            //insert person email
            if(!empty($perEmails)) {
                foreach($perEmails as $id => $pEmData) {
                    $pEmData['cpmail_cper_id'] = $perId;
                    $personEmails->save($pEmData, $id);
                }
            }

            $clientPersonAddData = $arrayParameters['clientPersonAddresses'];
            foreach ($clientPersonAddData As $key => $val)
            {
                $val['cadd_client_id'] = 0;//$clientId; //contact now is totally separated form accounts/clients
                $val['cadd_client_person'] = $perId;
                $caddId = !empty($val['cadd_id']) ? $val['cadd_id'] : null;
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

    /**
     * @param $data
     * @param null $id
     * @return mixed
     */
    public function linkAccountContact($data, $id = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_link_account_contact_start', $arrayParameters);

        // Service implementation start
        $personRelTable = $this->getServiceManager()->get('MelisEcomClientPersonRelTable');
        $results = $personRelTable->save($arrayParameters['data'], $arrayParameters['id']);
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_link_account_contact_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * This function will fetch all contact except for those who are
     * already link to the account
     *
     * @param $contactId
     * @param $accountId
     * @param $searchValue
     * @param $searchKeys
     * @return mixed
     */
    public function fetchAllContactForLinking($contactId, $accountId, $searchValue, $searchKeys)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_fetch_all_contact_for_linking_start', $arrayParameters);

        // Service implementation start
        $personRelTable = $this->getServiceManager()->get('MelisEcomClientPersonRelTable');
        $results = $personRelTable->fetchAllContactForLinking(
            $arrayParameters['contactId'],
            $arrayParameters['accountId'],
            $arrayParameters['searchValue'],
            $arrayParameters['searchKeys']
        );
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_fetch_all_contact_for_linking_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getContactByEmail($email)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_get_contacts_by_email_start', $arrayParameters);

        // Service implementation start
        $table = $this->getServiceManager()->get('MelisEcomClientPersonEmailsTable');
        $arrayParameters['results'] = $table->getEntryByField('cpmail_email', $arrayParameters['email'])->toArray();

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_get_contact_by_email_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @param $fileContents
     * @param $postData
     * @param string $delimiter
     * @return mixed
     */
    public function importContacts($fileContents, $postData, $delimiter = ';')
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $contactId = 0;
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_import_contacts_start', $arrayParameters);

        $contacts = explode(PHP_EOL, $fileContents);

        /*
         * remove first line cause it is label for them
         */
        if (! empty($contacts)) {
            unset($contacts[0]);
        }

        $civilityTable = $this->getServiceManager()->get('MelisEcomCivilityTransTable');
        $languageTable = $this->getServiceManager()->get('MelisEcomLangTable');

        foreach($contacts as $contact){
            if(!empty($contact)) {
                $contactsData = explode($delimiter, $contact);

                //get civility id
                $civD = $civilityTable->getEntryByField('civt_min_name', ucfirst($contactsData[4]))->current();
                //get language id
                $langD = $languageTable->getEntryByField('elang_name', ucfirst($contactsData[0]))->current();

                //prepare contacts data
                $contactData = [
                    'cper_type' => $contactsData[1] ?? 'person',
                    'cper_lang_id' => !empty($langD) ? $langD->elang_id : 2,
                    'cper_status' => $contactsData[2] ?? 1,
                    'cper_email' => $contactsData[3],
                    'cper_civility' => !empty($civD) ? $civD->civt_civ_id : 0,
                    'cper_name' => $contactsData[5],
                    'cper_middle_name' => $contactsData[6] ?? null,
                    'cper_firstname' => $contactsData[7],
                    'cper_job_title' => $contactsData[8] ?? null,
                    'cper_job_service' => $contactsData[9] ?? null,
                    'cper_tel_mobile' => $contactsData[10] ?? null,
                    'cper_tel_landline' => $contactsData[11] ?? null,
                    'cper_date_creation' => date('Ymd'),
                ];
                //prepare address data
                //get civility id
                $civD = $civilityTable->getEntryByField('civt_min_name', ucfirst($contactsData[14]))->current();
                $type = [
                    'billing' => 1,
                    'delivery' => 2
                ];
                $addressData = [
                    [
                        'cadd_address_name' => $contactsData[12],
                        'cadd_type' => $type[strtolower($contactsData[13])],
                        'cadd_civility' => !empty($civD) ? $civD->civt_civ_id : 0,
                        'cadd_firstname' => $contactsData[15] ?? null,
                        'cadd_middle_name' => $contactsData[16] ?? null,
                        'cadd_name' => $contactsData[17] ?? null,
                        'cadd_num' => $contactsData[18] ?? null,
                        'cadd_street' => $contactsData[19] ?? null,
                        'cadd_building_name' => $contactsData[20] ?? null,
                        'cadd_stairs' => $contactsData[21] ?? null,
                        'cadd_city' => $contactsData[22] ?? null,
                        'cadd_state' => $contactsData[23] ?? null,
                        'cadd_country' => $contactsData[24] ?? null,
                        'cadd_zipcode' => $contactsData[25] ?? null,
                        'cadd_company' => $contactsData[26] ?? null,
                        'cadd_phone_mobile' => $contactsData[27] ?? null,
                        'cadd_phone_landline' => $contactsData[28] ?? null,
                        'cadd_complementary' => $contactsData[29] ?? null,
                    ]
                ];
                //insert contact datas
                $contactId = $this->saveContact($contactData, $addressData);
            }
        }

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $contactId;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_import_contacts_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @param $fileContents
     * @param string $delimiter
     * @return mixed
     */
    public function importFileValidator($fileContents, $delimiter = ';')
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_import_file_validator_start', $arrayParameters);

        $translator = $this->getServiceManager()->get('translator');

        $errors = [];

        // Transferring parameters to their original variables for readability
        $fileContents = $arrayParameters['fileContents'];
        $delimiter = $arrayParameters['delimiter'];

        if (!empty(trim($fileContents))) {
            $contacts = explode(PHP_EOL, $fileContents);

            // remove first line cause it's label for them
            if (!empty($contacts)){
                unset($contacts[0]);
            }
            $index = 0;
            foreach ($contacts as $contact) {
                $index++;
                $tmpErrors = null;
                if(!empty(trim($contact))) {
                    $contactsData = array_filter(explode($delimiter, trim($contact)));
                    /**
                     * Check for Mandatory Fields
                     */
                    $tmperrors = [];
                    if (! empty($contactsData)) {
                        $tmpErrors = $this->checkMandatoryFields($errors, $contactsData, $index);
                    }
                    if ($tmpErrors) {
                        // Report empty mandatory fields
                        foreach ($tmpErrors as $error) {
                            $errors[] = $error;
                        }
                        continue;
                    }

                    /**
                     * Check email format
                     */
                    $tmpErrors = $this->checkEmailFormat($errors, $contactsData, $index);
                    if ($tmpErrors) {
                        // Report empty mandatory fields
                        foreach ($tmpErrors as $error) {
                            $errors[] = $error;
                        }
                        continue;
                    }
                }
            }
        } else $errors[] = $translator->translate('tr_meliscommerce_contact_common_empty_file');

        $results['errors'] = $errors;

        // END BUSINESS LOGIC

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_contact_import_file_validator_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Returns an empty array when all mandatory fields are filled
     * otherwise, returns an array of errors
     *
     * @param array $contactsData
     * @param null $index
     * @param $errors
     */
    private function checkMandatoryFields(&$errors, $contactsData = [], $index = null)
    {
        $translator = $this->getServiceManager()->get('translator');
        $prefix = $translator->translate('tr_meliscommerce_contact_common_line') .' '. $index . ': ';
        /**
         * Set Mandatory Field positions here (index: 0)
         */
        $mandatoryFields = [
             $translator->translate('tr_contact_export_col_cper_lang_id') => 0,  // Language
             $translator->translate('tr_contact_export_col_cper_name') => 5,  // last name
             $translator->translate('tr_contact_export_col_cper_firstname') => 7,  // first name
             $translator->translate('tr_meliscommerce_contact_import_address_name') => 12,  // first name
             $translator->translate('tr_meliscommerce_contact_import_address_type') => 13,  // first name
        ];

        foreach ($mandatoryFields as $fieldName => $position) {
            if (empty($contactsData[$position])) {
                $errors[] = $prefix . $fieldName . $translator->translate('tr_meliscommerce_contact_import_is_mandatory');
            }
        }

        //check if one of the phone has data
        // if(empty($contactsData[6]) && empty($contactsData[7])) {
        //     $errors[] = $prefix . '(' .
        //         $translator->translate('tr_prospects_tool_phone_field_name') . ', ' . $translator->translate('tr_prospects_tool_cell_phone_field_name') .
        //         ')' . $translator->translate('tr_prospects_tool_empty_opt_mandatory');
        // }
    }

    /**
     * @param $errors
     * @param array $contactsData
     * @param null $index
     */
    private function checkEmailFormat(&$errors, $contactsData = [], $index = null)
    {
        $translator = $this->getServiceManager()->get('translator');
        $prefix = $translator->translate('tr_meliscommerce_contact_common_line') .' '. $index . ': ';

        $email = [
            $translator->translate('tr_contact_export_col_cper_email') => 3,  // Email
        ];

        foreach($email as $fieldName => $position){
            $isValid = true;
            if(!empty($contactsData[$position])) {
                $regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
                if (!preg_match($regex, $contactsData[$position])) {
                    $isValid = false;
                    $errors[] = $prefix . $fieldName . ' "' . $contactsData[$position] . '"' . $translator->translate('tr_meliscommerce_contact_import_invalid_email');
                }

                /**
                 * Check if email already exist
                 */
                if($isValid){
                    $emails = $this->getContactByEmail($contactsData[$position]);
                    if(!empty($emails)){
                        $errors[] = $prefix . $fieldName . ' "' . $contactsData[$position] . '"' . $translator->translate('tr_meliscommerce_contact_import_email_already_exist');
                    }
                }
            }
        }
    }

    /**
     * @param $contactId
     * @return mixed
     */
    public function getContactDefaultAccount($contactId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_contact_default_account_start', $arrayParameters);

        // Service implementation start
        $melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
        $result = null;
        if(!empty($arrayParameters['contactId'])) {
            $result = $melisEcomClientPersonTable->getContactDefaultAccount($arrayParameters['contactId']);
        }

        $arrayParameters['results'] = $result;

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_contact_default_account_end', $arrayParameters);

        return $arrayParameters['results'];

    }
}