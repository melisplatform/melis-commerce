<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use MelisCommerce\Entity\MelisClientPerson;
use Zend\Crypt\BlockCipher;
use Zend\Crypt\Symmetric\Mcrypt;
/**
 *
 * This service handles the client system of MelisCommerce.
 *
 */
class MelisComClientService extends MelisComGeneralService
{
	/**
	 * 
	 * This method gets all clients account.
	 * Client datas will have: client account, persons, addresses, company
	 * 
	 * @param int $countryId If specified, it will bring back client for the corresponding country.
	 * @param DateTime $dateCreationMin If specified, only accounts starting this date will be sent back
	 * @param DateTime $dateCreationMax If specified, only accounts before this date will be sent back
	 * @param boolean $onlyValid if true, returns only active status clients
	 * @param int $start If not specified, it will start at the begining of the list
	 * @param int $limit If not specified, it will bring all categories of the list
	 * 
	 * @return MelisClient[] Array of Client objects
	 */
	public function getClientList($countryId = null, $dateCreationMin = null, $dateCreationMax = null, $onlyValid = null, 
                                    $start = 0, $limit = null, $order = null, $search = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_list_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomClientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
	    $melisEcomClientPersonTable = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
	    $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
	    
	    // Get Client Data
	    $clientData = $melisEcomClientTable->getClientList($arrayParameters['countryId'], $arrayParameters['dateCreationMin'], $arrayParameters['dateCreationMax'], $arrayParameters['onlyValid'], 
	                                                       $arrayParameters['start'], $arrayParameters['limit'], $arrayParameters['order'],$arrayParameters['search']);
	    $ctr = 0;
	    foreach ($clientData As $val)
	    {
	        $companySearchFlag = false;
	        
	        $melisClient = new \MelisCommerce\Entity\MelisClient();
	        $melisClient->setId($val->cli_id);
	        
	        // Set Client Data to MelisClient
	        $melisClient->setClient($val);
	        
	        // Set Person Data to MelisClient
            $clientPersonData = $melisEcomClientPersonTable->getClientPersonByClientId($val->cli_id);
            $clientPerson = array();
            foreach ($clientPersonData As $pval)
            {
                $pval->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($pval->civ_id);
                $pval->addresses = $this->getClientAddressesByClientPersonId($pval->cper_id);
                
                array_push($clientPerson, $pval);
            }
	        
	        $melisClient->setPersons($clientPerson);
	        
	        // Set Addresses to MelisClient
	        $clientAddressData = $melisEcomClientAddressTable->getClientAddressByClientId($val->cli_id);
	        $clientAddress = array();
	        foreach ($clientAddressData As $aVal)
	        {
	            $aVal->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($aVal->civ_id);
	            $aVal->address_trans = $this->getAddressTransByAddressTypeIdAndLangId($aVal->catype_id);
	            array_push($clientAddress, $aVal);
	        }
	        $melisClient->setAddresses($clientAddress);
	        
	        // Set Company to MelisClient
	        $clientCompanyData = $this->getCompanyByClientId($val->cli_id);
	        $clientCompany = array();
	        foreach ($clientCompanyData As $cVal)
	        {
                array_push($clientCompany, $cVal);
	        }
	        $melisClient->setCompany($clientCompany);
	        
            array_push($results, $melisClient);
	    }
	    // Service implementation end
	    
        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_list_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets a specific client account.
	 * Client datas will have: client account, persons, addresses, company
	 *
	 * @param int $clientId The client account id
	 * @param int $personId If specified, brings only this person with the client account
	 * @param string $personEmail If specified, brings only this person with the client account
	 *
	 * @return MelisClient|null Client object
	 */
	public function getClientByIdAndClientPerson($clientId, $personId = null, $personEmail = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_byid_andperson_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisClient = new \MelisCommerce\Entity\MelisClient();
	    
	    // Set Client Data to MelisClient
	    $melisEcomClientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
        $client = $melisEcomClientTable->getEntryById($arrayParameters['clientId'])->current();
        $melisClient->setClient($client);
	        
        // Set Person Data to MelisClient
        $melisEcomClientPersonTable = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
        $clientPerson = $melisEcomClientPersonTable->getClientPersonByClientIdPersonIdAndPersonEmail($arrayParameters['clientId'], 
                                                                    $arrayParameters['personId'], $arrayParameters['personEmail']);
        $clientPersonData = array();
        foreach ($clientPerson As  $pval)
        {
            $pval->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($pval->civ_id);
            $pval->addresses = $this->getClientAddressesByClientPersonId($pval->cper_id);
            array_push($clientPersonData, $pval);
        }
        $melisClient->setPersons($clientPersonData);
        
        // Set Addresses to MelisClient
        $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
        $clientAddressData = $melisEcomClientAddressTable->getClientAddressByClientId($arrayParameters['clientId']);
        $clientAddress = array();
        foreach ($clientAddressData As $aVal)
        {
            $aVal->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($aVal->civ_id);
            $aVal->address_trans = $this->getAddressTransByAddressTypeIdAndLangId($aVal->catype_id);
            array_push($clientAddress, $aVal);
        }
        $melisClient->setAddresses($clientAddress);
        
        // Set Company to MelisClient
        $clientCompany = $this->getCompanyByClientId($arrayParameters['clientId']);
        $melisClient->setCompany($clientCompany);
        
        // Push MelisClient Object to Return Array
	    $results = $melisClient;
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_byid_andperson_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets a specific client person.
	 * Person datas will have: person, addresses
	 *
	 * @param int $personId The client person id
	 *
	 * @return MelisClientPerson|null ClientPerson object
	 */
	public function getClientPersonById($personId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_person_byid_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisClientPerson = new \MelisCommerce\Entity\MelisClientPerson();
	    
	    // Set Person Data to MelisClientPerson
	    $melisEcomClientPersonTable = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
	    $clientPersonData = $melisEcomClientPersonTable->getClientPersonByPersonId($arrayParameters['personId']);
	    $clientPerson = $clientPersonData->current();
	    if (!empty($clientPerson))
	    {
	        $clientPerson->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($clientPerson->civ_id);
	        $melisClientPerson->setPerson($clientPerson);
	    }
	    
	    // Set Addresses Data to MelisClientPerson
	    $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
	    $clientAddressData = $melisEcomClientAddressTable->getPersonAddressByPersonId($arrayParameters['personId']);
	    $clientAddress = array();
	    foreach ($clientAddressData As $aVal)
	    {
	        $aVal->address_trans = $this->getAddressTransByAddressTypeIdAndLangId($aVal->catype_id);
	        $aVal->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($aVal->civ_id);
	        array_push($clientAddress, $aVal);
	    }
	    $melisClientPerson->setAddresses($clientAddress);
	    
	    $results = $melisClientPerson;
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_person_byid_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 * This method will return the Client Main Person
	 * 
	 * @param $clientId the Client ID of the Mein Person
	 * @param $langId if specified return only Translations with the same LangID, otherwise return all Translations
	 * 
	 * @return MelisClientPerson
	 */
	public function getClientMainPersonByClientId($clientId, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresses_byclientid_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisClientPerson = new \MelisCommerce\Entity\MelisClientPerson();
	    
	    $melisEcomClientPersonTable = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
	    $melisEcomCivilityTransTable = $this->getServiceLocator()->get('MelisEcomCivilityTransTable');
	    $clientPersonData = $melisEcomClientPersonTable->getClientMainPersonByClientId($arrayParameters['clientId']);
	    $clientPerson = $clientPersonData->current();
	    if (!empty($clientPerson))
	    {
	        // Set Person ID
	        $melisClientPerson->setId($clientPerson->cper_id);
	        
	        // Set Person Data to MelisClientPerson
	        $clientPerson->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($clientPerson->civ_id, $arrayParameters['langId']);
	        $melisClientPerson->setPerson($clientPerson);
	        
	        // Set Addresses Data to MelisClientPerson
	        $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
	        $clientAddressData = $melisEcomClientAddressTable->getPersonAddressByPersonId($clientPerson->cper_id);
	        $clientAddress = array();
	        foreach ($clientAddressData As $aVal)
	        {
	            $aVal->address_trans = $this->getAddressTransByAddressTypeIdAndLangId($aVal->catype_id, $arrayParameters['langId']);
	            $aVal->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($clientPerson->civ_id, $arrayParameters['langId']);
	            array_push($clientAddress, $aVal);
	        }
	        $melisClientPerson->setAddresses($clientAddress);
	    }
	    $results = $melisClientPerson;
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresses_byclientid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets a addresses of a client account.
	 *
	 * @param int $clientId The client account id
	 * @param int $addressType The type of address, as defined in table address_type, null brings all
	 *
	 * @return MelisEcomClientAddress[] Array of Client Addresses object
	 */
	public function getClientAddressesByClientId($clientId, $addressType = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresses_byclientid_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
	    $clientAddress = $melisEcomClientAddressTable->getClientAddressByClientId($arrayParameters['clientId'], $arrayParameters['addressType']);
	    foreach ($clientAddress As $val)
	    {
	        array_push($results, $val);
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresses_byclientid_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets a addresses of a client person.
	 *
	 * @param int $personId The client person id
	 * @param int $addressType The type of address, as defined in table address_type, null brings all
	 *
	 * @return MelisEcomClientAddress[] Array of Client Addresses object
	 */
	public function getClientAddressesByClientPersonId($personId, $addressType = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresses_byclientpersonid_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
	    $clientAddress = $melisEcomClientAddressTable->getPersonAddressByPersonId($arrayParameters['personId'], $arrayParameters['addressType']);
	    foreach ($clientAddress As $val)
	    {
	        array_push($results, $val);
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresses_byclientpersonid_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	public function getAddressTransByAddressTypeIdAndLangId($addTypeId, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresses_byclientpersonid_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomClientAddressTypeTransTable = $this->getServiceLocator()->get('MelisEcomClientAddressTypeTransTable');
	    $addTypeTrans = $melisEcomClientAddressTypeTransTable->getAddressTransByAddressTypeIdAndLangId($arrayParameters['addTypeId'], $arrayParameters['langId']);
	    
	    if (!is_null($arrayParameters['langId']))
	    {
	        $results = $addTypeTrans->current();
	    }
	    else
	    {
	        foreach ($addTypeTrans As $val)
	        {
	            array_push($results, $val);
	        }
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresses_byclientpersonid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the company of a client account
	 *
	 * @param int $clientId The client id
	 *
	 * @return MelisEcomCompany|null Company object
	 */
	public function getCompanyByClientId($clientId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_company_byclientid_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisEcomClientCompanyTable = $this->getServiceLocator()->get('MelisEcomClientCompanyTable');
	    $clientCompany = $melisEcomClientCompanyTable->getClientCompanyByClientId($arrayParameters['clientId']);
	    foreach ($clientCompany As $val)
	    {
	        array_push($results, $val);
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_company_byclientid_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the civilities and their translations
	 *
	 * @param int $langId If specified, translations of civilities will be limited to that lang
	 *
	 * @return MelisEcomCivility[] Array of Civility object
	 */
	public function getCivilityList($langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_civility_list_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisEcomCivilityTransTable = $this->getServiceLocator()->get('MelisEcomCivilityTransTable');
	    $civility = $melisEcomCivilityTransTable->getCivilityByLangId($arrayParameters['langId']);
	    foreach ($civility As $val)
	    {
	        array_push($results, $val);
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_civility_list_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
    
	/**
	 * This method will get Civility Translations
	 * @param int $civilityId id of the Civility
	 * @param int $langId if specified return only with the same LangId, otherwise return all Civility Translations
	 * @return Array 
	 */
	public function getCivilityTransByCivilityIdAndLangId($civilityId, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_civility_list_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomCivilityTransTable = $this->getServiceLocator()->get('MelisEcomCivilityTransTable');
	    
	    // Set Person Data to MelisClientPerson
	    $clientPersonCivilityTrans = $melisEcomCivilityTransTable->getCivilityTransByCivilityId($arrayParameters['civilityId'], $arrayParameters['langId']);
	     
	    if (!is_null($arrayParameters['langId']))
	    {
	        $results = $clientPersonCivilityTrans->current();
	    }
	    else
	    {
	        foreach ($clientPersonCivilityTrans As $val)
	        {
	            array_push($results, $val);
	        }
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_civility_list_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the address types and their translations
	 *
	 * @param int $langId If specified, translations of address types will be limited to that lang
	 *
	 * @return MelisEcomAddressType[] Array of AddressType object
	 */
	public function getAddressTypesList($langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresstype_list_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisEcomClientAddressTypeTransTable = $this->getServiceLocator()->get('MelisEcomClientAddressTypeTransTable');
	    $addressType = $melisEcomClientAddressTypeTransTable->getAddressTypeTransByLangId($arrayParameters['langId']);
	    foreach ($addressType As $val)
	    {
	        array_push($results, $val);
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresstype_list_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets a specific client account and person by the credentials
	 * Client datas will have: client account, required person, addresses, company
	 *
	 * @param string $personEmail The email of the client person
	 * @param string $personPassword The password of the client person
	 *
	 * @return MelisClient|null Client object
	 */
	public function getClientByEmailAndPassword($personEmail, $personPassword)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_byemailandpassword_start', $arrayParameters);
	
	    // Service implementation start
	    $melisEcomClientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
	    $client = $melisEcomClientTable->getClientByEmailAndPassword($arrayParameters['personEmail'], $arrayParameters['personPassword'])->current();
	    
	    if (!empty($client))
	    {
	        $clientId = $client->cli_id;
	        // Get Client Details
	        $results = $this->getClientByIdAndClientPerson($clientId);
	    }
	    else
	    {
	        // Retrun null MelisClient Object
	        $melisClient = new \MelisCommerce\Entity\MelisClient();
	        $results = $melisClient;
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_byemailandpassword_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves a client account in database.
	 *
	 * @param array $client Reflects the melis_ecom_client table
	 * @param array[] $persons Array of persons reflecting the melis_ecom_client_person table
	 * @param array[] $clientAccountAddresses Array of addresses reflecting the melis_ecom_client_address table
	 * @param array $company Reflects the melis_ecom_company table
	 * @param int $clientId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The client id created or updated, null if an error occured
	 */
	public function saveClient($client, $persons = array(), $clientAccountAddresses = array(), 
	                           $company = array(), $clientId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_save_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisEcomClientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
	    $clntId = null;
	    
	    try
	    {
            if (is_null($arrayParameters['clientId']))
            {
                $arrayParameters['client']['cli_date_creation'] = date('Y-m-d H:i:s');
            }
            $clntId = $melisEcomClientTable->save($arrayParameters['client'], $arrayParameters['clientId']);
	    }
	    catch (\Exception $e)
	    {
	        
	    }
	    
	    if (!is_null($clntId))
	    {
	        $successflag = true;
	        
	        // Saving Client Person Details
	        $personsData = $arrayParameters['persons'];
	        foreach ($personsData As $key => $val)
	        {
	            $cperId = $val['cper_id'] ? $val['cper_id'] : null;
	            unset($val['cper_id']);
	            $val['cper_client_id'] = $clntId;
	            
	            $personAddress = array();
	            if (!empty($val['contact_address']))
	            {
	                $personAddress = $val['contact_address'];
	            }
	            unset($val['contact_address']);
	            
	            $successflag = $this->saveClientPerson($val, $personAddress, $cperId);
	            
	            if (!$successflag)
	            {
	                return null;
	            }
	        }
	        
	        // Saving Client Addresses Details
	        $clientAccountAddressesData = $arrayParameters['clientAccountAddresses'];
	        foreach ($clientAccountAddressesData As $key => $val)
	        {
	            $caddId = $val['cadd_id'] ? $val['cadd_id'] : null;
	            unset($val['cadd_id']);
	            $val['cadd_client_id'] = $clntId;
	            
	            $successflag = $this->saveClientAddress($val, $caddId);
	            
	            if (!$successflag)
	            {
	                return null;
	            }
	        }
	        
	        // Saving Client Company Detials
	        if (!empty($arrayParameters['company']))
	        {
	            $companyData = $arrayParameters['company'];
	            $compId = $companyData['ccomp_id'] ? $companyData['ccomp_id'] : null;
	            unset($companyData['ccomp_id']);
	            $companyData['ccomp_client_id'] = $clntId;
	            
	            $successflag = $this->saveClientCompany($companyData, $compId);
	             
	            if (!$successflag)
	            {
	                return null;
	            }
	        }
	        
	        $results = $clntId;
	    }
	    
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_save_end', $arrayParameters);
	     
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
	public function saveClientPerson($person, $clientPersonAddresses = array(), $personId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_persons_save_start', $arrayParameters);
	
	    // Service implementation start
	    $melisEcomClientPersonTable = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
	    $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
	    
        try
        {
            $clientId = $arrayParameters['person']['cper_client_id'];
            if (is_null($arrayParameters['personId']))
            {
                $arrayParameters['person']['cper_date_creation'] = date('Y-m-d H:i:s');
            }
            $arrayParameters['person']['cper_is_main_person'] = (!empty($arrayParameters['person']['cper_is_main_person'])) ? $arrayParameters['person']['cper_is_main_person'] : 0;
            $arrayParameters['person']['cper_civility'] = (!empty($arrayParameters['person']['cper_civility'])) ? $arrayParameters['person']['cper_civility'] : 0;
            
            if (!empty($arrayParameters['person']['cper_password']))
            {
                $arrayParameters['person']['cper_password'] = $this->crypt($arrayParameters['person']['cper_password']);
            }
            
            $arrayParameters['person']['cper_firstname'] = ucwords(mb_strtolower($arrayParameters['person']['cper_firstname']));
            $arrayParameters['person']['cper_name'] = mb_strtoupper($arrayParameters['person']['cper_name']);
            
            if (!empty($arrayParameters['person']['cper_middle_name']))
            {
                $arrayParameters['person']['cper_middle_name'] = ucwords(mb_strtolower($arrayParameters['person']['cper_middle_name']));
            }
            
            $arrayParameters['person']['cper_email'] = mb_strtolower($arrayParameters['person']['cper_email']);
            
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
                
                $val['cadd_civility'] = (!empty($val['cadd_civility'])) ? $val['cadd_civility'] : 0;
                
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
            
        }
        
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_persons_save_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 * Encryption and Decryption of passwords for melis
	 *
	 * @param String $data, String to encrypted/decrypted
	 * @param string $type, ecrypt/decrypt
	 * @return string
	 */
	protected function crypt($data, $type = 'encrypt')
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_passwordcryptdecrypt_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisConfig = $this->getServiceLocator()->get('MelisCoreConfig');
	    $datas = $melisConfig->getItemPerPlatform('meliscommerce/datas');
	
	    $hashMethod = $datas['accounts']['hash_method'];
	    $salt = $datas['accounts']['salt'];
	    // hash password
	    $bEncryptor = new BlockCipher(new Mcrypt(array(
	        'algo' => 'aes',
	        'mode' => 'cfb',
	        'hash' => $hashMethod
	    )));
	    $bEncryptor->setKey($salt);
	   
	    $value = null;
	    
	    if($arrayParameters['type'] == 'encrypt')
	    {
	        $value = $bEncryptor->encrypt($arrayParameters['data']);
	    }
	    elseif($arrayParameters['type'] == 'decrypt')
	    {
	        $value = $bEncryptor->decrypt($arrayParameters['data']);
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $value;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_passwordcryptdecrypt_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves a client address in database.
	 *
	 * @param array $address Address reflecting the melis_ecom_client_address table
	 * @param int $addressId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The Adress id created or updated, null if an error occured
	 */
	public function saveClientAddress($address, $addressId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_address_save_start', $arrayParameters);
	
	    // Service implementation start
	    $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
	    
        try
        {
            if (is_null($arrayParameters['addressId']))
            {
                $arrayParameters['address']['cadd_creation_date'] = date('Y-m-d H:i:s');
            }
            
            $arrayParameters['address']['cadd_client_person'] = null;
            
            $arrayParameters['address']['cadd_civility'] = (!empty($arrayParameters['address']['cadd_civility'])) ? $arrayParameters['address']['cadd_civility'] : 0;
           
            $arrayParameters['address']['cadd_name'] = mb_strtoupper($arrayParameters['address']['cadd_name']);
            $arrayParameters['address']['cadd_firstname'] = ucwords(mb_strtolower($arrayParameters['address']['cadd_firstname']));
            if (!empty($arrayParameters['address']['cadd_middle_name']))
            {
                $arrayParameters['address']['cadd_middle_name'] = ucwords(mb_strtolower($arrayParameters['address']['cadd_middle_name']));
            }
            
            $caddId = $melisEcomClientAddressTable->save($arrayParameters['address'], $arrayParameters['addressId']);
            $results = $caddId;
        }
        catch (\Exception $e)
        {
            
        }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_address_save_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves a client company in database.
	 * Only one company per client, so the previous one will be deleted first if it exists
	 *
	 * @param array $company Company reflecting the melis_ecom_company table
	 * @param int $companyId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The Company id created or updated, null if an error occured
	 */
	public function saveClientCompany($company, $companyId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_company_save_start', $arrayParameters);
	
	    // Service implementation start
	    $melisEcomClientCompanyTable = $this->getServiceLocator()->get('MelisEcomClientCompanyTable');
	    try 
	    {
	        if (is_null($arrayParameters['companyId']))
	        {
	            $arrayParameters['company']['ccomp_date_creation'] = date('Y-m-d H:i:s');
	        }
	        
	        $arrayParameters['company']['ccomp_date_edit'] = date('Y-m-d H:i:s');
            $comId = $melisEcomClientCompanyTable->save($arrayParameters['company'], $arrayParameters['companyId']);
            $results = $comId;
	    }
	    catch (\Exception $e)
	    {
	        
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_company_save_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}


	/**
	 *
	 * This method saves a client Addresses in database.
	 *
	 * @param array $addressType AddressType reflecting the melis_ecom_company table
	 * @param array $addressTypeTranslations AddressTypeTranslations reflecting the melis_ecom_client_address_type_trans table
	 * @param int $adressTypeId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The Adress Type id created or updated, null if an error occured
	 */
	public function saveClientAddressType($addressType, $addressTypeTranslations, $addressTypeId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresstype_save_start', $arrayParameters);
	
	    // Service implementation start
	    $melisEcomClientAddressTypeTable = $this->getServiceLocator()->get('MelisEcomClientAddressTypeTable');
	    $melisEcomClientAddressTypeTransTable = $this->getServiceLocator()->get('MelisEcomClientAddressTypeTransTable');
	    try 
	    {
	        if (!empty($arrayParameters['addressType'])&&is_array($arrayParameters['addressType']))
	        {
	            $addTypeId = $melisEcomClientAddressTypeTable->save($arrayParameters['addressType'], $arrayParameters['addressTypeId']);
	            
	            $addTypeTrans = $arrayParameters['addressTypeTranslations'];
	            
	            foreach ($addTypeTrans As $key => $val)
	            {
	                $addTypeTrans[$key][] = $addTypeId;
	                $addTypeTransId = $val['catypt_id'] ? $val['catypt_id'] : null;
	                
	                $melisEcomClientAddressTypeTransTable->save($addTypeTrans[$key], $addTypeTransId);
	            }
	            
	            $results = $addTypeId;
	        }
	    }
	    catch(\Exception $e)
	    {
	        
	    }
	    
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_addresstype_save_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method deletes a client in database.
	 * It will also Delete the Basket Persistent of the Client
	 *
	 * @param int $clientId The client id
	 *
	 * @return int|null The client id deleted
	 */
	public function deleteClient($clientId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_delete_start', $arrayParameters);
	
	    // Service implementation start
	    $melisEcomClientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
	    $melisEcomBasketPersistentTable = $this->getServiceLocator()->get('MelisEcomBasketPersistentTable');
	    
	    try
	    {
	        $client = $melisEcomClientTable->getEntryById($arrayParameters['clientId']);
	        
	        if (!empty($client->toArray()))
	        {
	            // Logical Deletion by Updating Status of the Client
	            $data['cli_status'] = 0;
	            $clntId = $melisEcomClientTable->save($data, $arrayParameters['clientId']);
	            
	            // Physical Deletion on Basket Persistent
	            $melisEcomBasketPersistentTable->deleteByField('bper_client_id', $clntId);
	            
	            $results = $clntId;
	        }
	    }
	    catch (\Exception $e)
	    {
	        
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_delete_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method deletes a client address in database.
	 *
	 * @param int $adressId The adress id
	 *
	 * @return int|null The address id deleted
	 */
	public function deleteClientAddress($addressId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_address_delete_start', $arrayParameters);
	
	    // Service implementation start
	    $melisEcomClientAddressTable = $this->getServiceLocator()->get('MelisEcomClientAddressTable');
	    
	    try
	    {
	        $clientAdd = $melisEcomClientAddressTable->getEntryById($arrayParameters['addressId'])->current();
	        
	        if (!empty($clientAdd))
	        {
	            $results = $melisEcomClientAddressTable->deleteById($arrayParameters['addressId']);
	        }
	        
	    }
	    catch (\Exception $e)
	    {
	        
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_address_delete_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method deletes a client company in database.
	 *
	 * @param int $companyId The company id
	 *
	 * @return int|null The company id deleted
	 */
	public function deleteClientCompany($companyId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_company_delete_start', $arrayParameters);
	
	    // Service implementation start
	    $melisEcomClientCompanyTable = $this->getServiceLocator()->get('MelisEcomClientCompanyTable');
	    
	    try 
	    {
	        $clientCompany = $melisEcomClientCompanyTable->getEntryById($arrayParameters['companyId']);
	        
	        if (!empty($clientCompany->toArray()))
	        {
	            $results = $melisEcomClientCompanyTable->deleteById($arrayParameters['companyId']);
	        }
	    }
	    catch (\Exception $e)
	    {
	        
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_company_delete_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 * This method retrieves the data used for the list widget
	 * @param varchar $identifier accepts curMonth|avgMonth
	 * @return float|null , float on success, otherwise null
	 */
	public function getWidgetClients($identifier)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	     
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_widget_client_start', $arrayParameters);
	     
	    // Service implementation start
	    $clientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
	    switch($arrayParameters['identifier']){
	        case 'curMonth':
	            $results = $clientTable->getCurrentMonth()->count(); break;
	        case 'avgMonth':
	            $results = $clientTable->getAvgMonth()->current(); break;
	        default:
	            break;
	    }
	    // Service implementation end
	     
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_client_widget_client_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
}