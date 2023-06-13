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
	 * @param bool $count
	 *
	 * @return MelisClient[] Array of Client objects
	 */
	public function getClientList($countryId = null, $dateCreationMin = null, $dateCreationMax = null, $onlyValid = null, 
									$start = 0, $limit = null, $order = null, $search = null, $count = false)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = array();
				
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_list_start', $arrayParameters);
		
		// Service implementation start
		$melisEcomClientTable = $this->getServiceManager()->get('MelisEcomClientTable');
		$melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
		$melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
		
		// Get Client Data
		$clientData = $melisEcomClientTable->getClientList($arrayParameters['countryId'], $arrayParameters['dateCreationMin'], $arrayParameters['dateCreationMax'], $arrayParameters['onlyValid'], 
														$arrayParameters['start'], $arrayParameters['limit'], $arrayParameters['order'],$arrayParameters['search'], $arrayParameters['count']);
        if(!$arrayParameters['count']) {
            $ctr = 0;
            foreach ($clientData As $val) {
                $companySearchFlag = false;

                $melisClient = new \MelisCommerce\Entity\MelisClient();
                $melisClient->setId($val->cli_id);

                // Set Client Data to MelisClient
                $melisClient->setClient($val);

                // Set Person Data to MelisClient
                $clientPersonData = $melisEcomClientPersonTable->getClientPersonByClientId($val->cli_id);
                $clientPerson = array();
                foreach ($clientPersonData As $pval) {
                    $pval->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($pval->civ_id);
                    $pval->addresses = $this->getClientAddressesByClientPersonId($pval->cper_id);

                    array_push($clientPerson, $pval);
                }

                $melisClient->setPersons($clientPerson);

                // Set Addresses to MelisClient
                $clientAddressData = $melisEcomClientAddressTable->getClientAddressByClientId($val->cli_id);
                $clientAddress = array();
                foreach ($clientAddressData As $aVal) {
                    $aVal->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($aVal->civ_id);
                    $aVal->address_trans = $this->getAddressTransByAddressTypeIdAndLangId($aVal->catype_id);
                    array_push($clientAddress, $aVal);
                }
                $melisClient->setAddresses($clientAddress);

                // Set Company to MelisClient
                $clientCompanyData = $this->getCompanyByClientId($val->cli_id);
                $clientCompany = array();
                foreach ($clientCompanyData As $cVal) {
                    array_push($clientCompany, $cVal);
                }
                $melisClient->setCompany($clientCompany);

                array_push($results, $melisClient);
            }
        }else{
            $results = $clientData->current();
        }
		// Service implementation end
		
		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $results;	    
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_list_end', $arrayParameters);
		
		return $arrayParameters['results'];
	}

	public function getClientById($clientId)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = array();
				
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_get_client_start', $arrayParameters);
		
		// Service implementation start

		$melisEcomClientTable = $this->getServiceManager()->get('MelisEcomClientTable');
		$results = $melisEcomClientTable->getEntryById($arrayParameters['clientId'])->current();

		// Service implementation end
		
		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $results;	    
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_get_client_end', $arrayParameters);
		
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
		$melisEcomClientTable = $this->getServiceManager()->get('MelisEcomClientTable');
		$client = $melisEcomClientTable->getEntryById($arrayParameters['clientId'])->current();
		$melisClient->setClient($client);
			
		// Set Person Data to MelisClient
		$melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
		$clientPerson = $melisEcomClientPersonTable->getClientPersonByClientIdPersonIdAndPersonEmail($arrayParameters['clientId'], 
																	$arrayParameters['personId'], $arrayParameters['personEmail']);

		$clientPersonData = array();
		foreach ($clientPerson As  $pval)
		{
			$pval->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($pval->civ_id);
			$pval->addresses = $this->getClientAddressesByClientPersonId($pval->cper_id);
			$pval->emails = $this->getPersonEmailsByPersonId($pval->cper_id);
			array_push($clientPersonData, $pval);
		}
		$melisClient->setPersons($clientPersonData);
		
		// Set Addresses to MelisClient
		$melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
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
	
	
	public function exportClientList($clientStatus, $startDate, $endDate, $separator, $encapseulate, $langId)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = false;
		
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_export_client_start', $arrayParameters);
		
		// Service implementation start
		$melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
		// get config for file name
		$csvConfig = $melisCoreConfig->getItem('meliscommerce/datas/default/export/csv');
		$csvFileName = $csvConfig['clientFileName'];
		$dir = $csvConfig['dir']; 
		$clientTable = $this->getServiceManager()->get('MelisEcomClientTable');

		$clienData = $clientTable->fetchAll();
		$count = $clienData->count();
		unset($clienData);
		$limit = $csvConfig['clientLimit'];

		
		if ($count)
		{
			$cycles = ceil((float)($count / $limit));
			
			// fetching data by segment
			for($c = 0 ; $c < $cycles;  $c++){
				$csvData = '';
				$start = $c * $limit;
				$clients = $this->getClientList(
					null, $arrayParameters['startDate'], $arrayParameters['endDate'], $arrayParameters['clientStatus'], 
					$start, $limit, null, null
					);
				
				foreach($clients as $client){
					$csvData .= $this->formatClientToCsv($client, $arrayParameters['separator'], $arrayParameters['encapseulate'], $arrayParameters['langId']);
				}
				$append = file_put_contents($dir.$csvFileName, $csvData, FILE_APPEND | LOCK_EX );
				if(!$append){
					break;
				}else{
					$results = true;
				}
			}
			
		}  
		
		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $results;
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_export_client_end', $arrayParameters);
		
		return $arrayParameters['results'];
	}
	
	public function formatClientToCsv($clientEntity, $separator, $encapsulate, $langId)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = '';
		
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_format_to_csv_start', $arrayParameters);
		
		// Service implementation start
		
		$s = $arrayParameters['separator'];
		$e =  $arrayParameters['encapsulate'];
		
		$client = $arrayParameters['clientEntity']->getClient();
		$companies = $arrayParameters['clientEntity']->getCompany();
		$addresses = $arrayParameters['clientEntity']->getAddresses();
		$persons  = $arrayParameters['clientEntity']->getPersons();
		
		$countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
		$country = $countryTable->getEntryByField('ctry_id', $client->cli_country_id);
		$countryName = ($country->count())? $country->current()->ctry_name : '';

		$clientGrouTable = $this->getServiceManager()->get('MelisEcomClientGroupsTable');
		$clientGroup = $clientGrouTable->getEntryById($client->cli_group_id)->current();
		$groupName = $clientGroup ? $clientGroup->cgroup_name : '';
		
		$langTable = $this->getServiceManager()->get('MelisEcomLangTable');
		$languages = $langTable->fetchAll();
		
		$content = array();
		
		// client
		$line1[] = $client->cli_id;
		$line1[] = $client->cli_status;
		$line1[] = $client->cli_country_id;
		$line1[] = $groupName;
		$line1[] = $countryName;
		$line1[] = $client->cli_date_creation;
		$tmp = array();
		
		foreach($line1 as $line){
			$tmp[] = addslashes($line);
		}
		
		$content[] = $e.implode($e.$s.$e, $tmp).$e.$s;
		
		//company
		if(!empty($companies)){
			$content[] = 'COMPANY';
		}
		
		foreach($companies as $company){
			$tmp = array();
			
			foreach($company as $detail){
				$tmp[] = addslashes($detail);
			}
			$content[] = $e.implode($e.$s.$e, $tmp).$e.$s;
		}
		
		//addresses
		if(!empty($addresses)){
			$content[] = 'ADDRESSES ACCOUNT';
		}
		
		foreach($addresses as $address){
			$tmp = array();
			foreach($address as $key => $val){
				if(gettype($val) != 'array'){
					$tmp[] = addslashes($val);
				}
			}
			$content[] = $e.implode($e.$s.$e, $tmp).$e.$s;
		}
		
		//persons
		foreach($persons as $person){
			$content[] = 'PERSON';
			$per = array();
			$per[] = $person->cper_id;
			$per[] = $person->cper_lang_id;
			
			$langName = '';
			foreach($languages as $lang){
				if($lang->elang_id == $person->cper_lang_id){
					$langName = $lang->elang_name;
				}
			}
			
			$per[] = $langName;
			$per[] = $person->cper_is_main_person;
			$per[] = $person->cper_civility;
			$per[] = $person->cper_name;
			$per[] = $person->cper_middle_name;
			$per[] = $person->cper_firstname;
			$per[] = $person->cper_email;
			$per[] = $person->cper_job_title;
			$per[] = $person->cper_job_service;
			$per[] = $person->cper_tel_mobile;
			$per[] = $person->cper_tel_landline;
			$per[] = $person->cper_date_creation;
			
			$tmp = array();
			foreach($per as $t){
				$tmp[] = addslashes($t);
			}
			
			$content[] = $e.implode($e.$s.$e, $tmp).$e.$s;
			
			foreach($person->addresses as $perAddress){
				$tmp = array();
				foreach($perAddress as $key => $val){
					if(gettype($val) != 'array'){
						$tmp[] = addslashes($val);
					}
				}
				$content[] = $e.implode($e.$s.$e, $tmp).$e.$s;
			}
		}
		$content[] = PHP_EOL;
		$results = implode(PHP_EOL, $content);
		
		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $results;
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_format_to_csv_end', $arrayParameters);
		
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
		$melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
		$clientPersonData = $melisEcomClientPersonTable->getClientPersonByPersonId($arrayParameters['personId']);
		$clientPerson = $clientPersonData->current();
		if (!empty($clientPerson))
		{
			unset($clientPerson->cper_password);
			$clientPerson->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($clientPerson->civ_id);
			$melisClientPerson->setPerson($clientPerson);
		}
		
		// Set Addresses Data to MelisClientPerson
		$melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
		$clientAddressData = $melisEcomClientAddressTable->getPersonAddressByPersonId($arrayParameters['personId']);
		$clientAddress = array();
		foreach ($clientAddressData As $aVal)
		{
			$aVal->address_trans = $this->getAddressTransByAddressTypeIdAndLangId($aVal->catype_id);
			$aVal->civility_trans = $this->getCivilityTransByCivilityIdAndLangId($aVal->civ_id);
			array_push($clientAddress, $aVal);
		}
		$melisClientPerson->setAddresses($clientAddress);
		$melisClientPerson->setEmails($this->getPersonEmailsByPersonId($personId));
		
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
		
		$melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
		$melisEcomCivilityTransTable = $this->getServiceManager()->get('MelisEcomCivilityTransTable');
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
			$melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
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
		$melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
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
	public function getClientAddressesByClientPersonId($personId, $addressType = null, $caddId = null)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = array();
	
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_addresses_byclientpersonid_start', $arrayParameters);
		
		// Service implementation start
		$melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
		$clientAddress = $melisEcomClientAddressTable->getPersonAddressByPersonId($arrayParameters['personId'], $arrayParameters['addressType'], $arrayParameters['caddId']);
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
	
	/**
	 * Returns the specific address of the client person by providing the address ID
	 * @param $addrId
	 * @return mixed
	 */
	public function getClientPersonAddressByAddressId($personId, $addrId)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = array();
	
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_person_address_by_address_id_start', $arrayParameters);
	
		// Service implementation start
		
		$addrTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
		$address  = $addrTable->getClientPersonAddressByAddressId($arrayParameters['personId'], $arrayParameters['addrId']);
		if (!empty($address))
		{
			$results = $address->current();
		}
		// Service implementation end
	
		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $results;
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_person_address_by_address_id_end', $arrayParameters);
	
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
		$melisEcomClientAddressTypeTransTable = $this->getServiceManager()->get('MelisEcomClientAddressTypeTransTable');
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
		$melisEcomClientCompanyTable = $this->getServiceManager()->get('MelisEcomClientCompanyTable');
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
		$melisEcomCivilityTransTable = $this->getServiceManager()->get('MelisEcomCivilityTransTable');
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
		$melisEcomCivilityTransTable = $this->getServiceManager()->get('MelisEcomCivilityTransTable');
		
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
		$melisEcomClientAddressTypeTransTable = $this->getServiceManager()->get('MelisEcomClientAddressTypeTransTable');
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
		$melisEcomClientTable = $this->getServiceManager()->get('MelisEcomClientTable');
		$client = $melisEcomClientTable->getClientByEmailAndPassword($arrayParameters['personEmail'], $this->crypt($arrayParameters['personPassword']))->current();
		
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
	 * This method wil retrieve client person detail 
	 * using recoevery key,
	 * this method can be use "Lost password" feature
	 * @param string $recoverKey
	 * @return Array|null
	 */
	public function getClientPersonByRecoveryKey($recoverKey)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = null;
		
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_save_start', $arrayParameters);
		
		// Service implementation start
		if (!empty($arrayParameters['recoverKey']))
		{
			$clientPersonTbl = $this->getServiceManager()->get('MelisEcomClientPersonTable');
			$clientPerson = $clientPersonTbl->getEntryByField('cper_password_recovery_key', $recoverKey)->current();
			
			if (!empty($clientPerson))
			{
				$results = $clientPerson;
			}
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
		$melisEcomClientTable = $this->getServiceManager()->get('MelisEcomClientTable');
		$clntId = null;
		
		try
		{
			if (is_null($arrayParameters['clientId']))
			{
				$arrayParameters['client']['cli_date_creation'] = date('Y-m-d H:i:s');
			}
			else 
			{
				$arrayParameters['client']['cli_date_edit'] = date('Y-m-d H:i:s');
			}

			if(empty($arrayParameters['client']['cli_group_id']))
				$arrayParameters['client']['cli_group_id'] = 1; //set group to General
				
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
				$cperId = (!empty($val['cper_id'])) ? $val['cper_id'] : null;
				if (!isset($val['cper_id']))
				{
					unset($val['cper_id']);
				}
				$val['cper_client_id'] = $clntId;
				
				$personAddress = array();
				if (!empty($val['contact_address']))
				{
					$personAddress = $val['contact_address'];
				}
				unset($val['contact_address']);
				unset($val['reset_pass_flag']);
				$successflag = $this->saveClientPerson($val, $personAddress, $cperId);
				$successflag = $this->saveClientPersonEmail($cperId ?? $successflag, $val['cper_email']);
				
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
			
			// Saving Client Company details
			if (!empty($arrayParameters['company']))
			{
				$companyData = $arrayParameters['company'];
				$compId = null;
				if (!empty($companyData['ccomp_id'])) {
					$compId = $companyData['ccomp_id'];
					unset($companyData['ccomp_id']);
				}

				$companyData['ccomp_client_id'] = $clntId;
				
				$successflag = $this->saveClientCompany($companyData, $compId);
				
				if (!$successflag)
					return null;
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
				$arrayParameters['person']['cper_password'] = $this->crypt($arrayParameters['person']['cper_password']);
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
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_persons_save_end', $arrayParameters);
	
		return $arrayParameters['results'];
	}
	
	/**
	 * This method will check if the email exist
	 * that not match to the person id
	 * @param string $email
	 * @param int $personId, person id of the current use
	 * @return boolean
	 */
	public function checkEmailExist($email, $personId)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = true;
		
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_check_email_exist_start', $arrayParameters);
		
		// Service implementation start
		$melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
		try 
		{
			$person = $melisEcomClientPersonTable->checkEmailExist($arrayParameters['email'], $arrayParameters['personId']);
			
			if (empty($person->current()))
			{
				$results = false;
			}
		}
		catch(\Exception $e){}
		// Service implementation end
		
		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $results;
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_check_email_exist_end', $arrayParameters);
		
		return $arrayParameters['results'];
	}

	/**
	 * Encryption of passwords for melis commerce
	 *
	 * @param $str
	 * @return mixed
	 */
	public function crypt($str)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = null;
		
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_passwordcryptdecrypt_start', $arrayParameters);
		
		// Service implementation start
		$authService = $this->getServiceManager()->get('MelisComAuthenticationService');
		$value = $authService->encryptPassword($arrayParameters['str']);
		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $value;
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_passwordcryptdecrypt_end', $arrayParameters);

		return $arrayParameters['results'];
	}

	public function generatePsswordRecoveryKey($key = null)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = null;

		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_generate_password_recovery_key_start', $arrayParameters);

		$value = $arrayParameters['key'].date('YmdHisu');
		$options = ['cost' => 12];
		$recoveryKey = password_hash($value, PASSWORD_BCRYPT, $options);

		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $recoveryKey;
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_generate_password_recovery_key_end', $arrayParameters);

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
		$melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
		
		try
		{
			if (is_null($arrayParameters['addressId']))
			{
				$arrayParameters['address']['cadd_creation_date'] = date('Y-m-d H:i:s');
			}
			else 
			{
				if (isset($arrayParameters['address']['cadd_creation_date']))
				{
					unset($arrayParameters['address']['cadd_creation_date']);
				}
			}
			
			
			if (empty($arrayParameters['address']['cadd_client_person']))
			{
				$arrayParameters['address']['cadd_client_person'] = null;
			}
			
			$arrayParameters['address']['cadd_civility'] = !empty($arrayParameters['address']['cadd_civility']) ? $arrayParameters['address']['cadd_civility'] : null;
		
			$arrayParameters['address']['cadd_name'] = mb_strtoupper($arrayParameters['address']['cadd_name']);
			$arrayParameters['address']['cadd_firstname'] = ucwords(mb_strtolower($arrayParameters['address']['cadd_firstname']));
			if (!empty($arrayParameters['address']['cadd_middle_name']))
			{
				$arrayParameters['address']['cadd_middle_name'] = ucwords(mb_strtolower($arrayParameters['address']['cadd_middle_name']));
			}

			// remove address when adding or updating
			unset($arrayParameters['address']['cadd_id']);
			
			$caddId = $melisEcomClientAddressTable->save($arrayParameters['address'], (int) $arrayParameters['addressId']);
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
		$melisEcomClientCompanyTable = $this->getServiceManager()->get('MelisEcomClientCompanyTable');
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
		$melisEcomClientAddressTypeTable = $this->getServiceManager()->get('MelisEcomClientAddressTypeTable');
		$melisEcomClientAddressTypeTransTable = $this->getServiceManager()->get('MelisEcomClientAddressTypeTransTable');
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
		$melisEcomClientTable = $this->getServiceManager()->get('MelisEcomClientTable');
		$melisEcomBasketPersistentTable = $this->getServiceManager()->get('MelisEcomBasketPersistentTable');
		
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
		$melisEcomClientAddressTable = $this->getServiceManager()->get('MelisEcomClientAddressTable');
		
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
		$melisEcomClientCompanyTable = $this->getServiceManager()->get('MelisEcomClientCompanyTable');
		
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

	public function deleteClientAddressByAddressId($addrId)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = array();

		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_address_delete_by_address_id_start', $arrayParameters);

		// Service implementation start
		$melisComAuthSrv = $this->getServiceManager()->get('MelisComAuthenticationService');

		$addrTable   = $this->getServiceManager()->get('MelisEcomClientAddressTable');
		$addrData    = $addrTable->deleteById($addrId);
		$results     = 1;
		// Service implementation end

		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $results;
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_address_delete_by_address_id_end', $arrayParameters);

		return $arrayParameters['results'];
	}
	
	/**
		* This method retrieves the data used for the list widget
		* @param varchar $identifier accepts curMonth|avgMonth
		* @return float|null , float on success, otherwise null
		*/
	public function getWidgetClients($identifier, $param = null)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = null;
		
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_widget_client_start', $arrayParameters);
		
		// Service implementation start
		$clientTable = $this->getServiceManager()->get('MelisEcomClientTable');
		switch($arrayParameters['identifier']){
			case 'curMonth':
				$results = $clientTable->getCurrentMonth()->count(); break;
			case 'activeInactive':
				$results = $clientTable->getActiveInactive($param)->current(); break;
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
	
	/**
		* This method retrieves a client person by email
		* @param string $email email identifier of the client person
		* @return object returns the a client person table row
		*/
	public function getClientPersonByEmail($email)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = false;
		
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_get_person_by_email_start', $arrayParameters);
		
		// Service implementation start
		$clientPersonTbl = $this->getServiceManager()->get('MelisEcomClientPersonTable');
		$results = $clientPersonTbl->getEntryByField('cper_email', $arrayParameters['email'])->current();
		// Service implementation end
		
		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $results;
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_get_person_by_email_end', $arrayParameters);
		
		return $arrayParameters['results'];
	}
	
	/**
		* This method saves the recovery key from the lost password plugin
		* @param int $personId id of the client person
		* @param string $recoverKey recovery key
		* @return int insert id
		*/
	public function savePasswordRecoveryKey($personId, $recoverKey)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = false;
		
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_save_password_recovery_key_start', $arrayParameters);
		
		// Service implementation start
		$clientPersonTbl = $this->getServiceManager()->get('MelisEcomClientPersonTable');
		try{
			$results = $clientPersonTbl->save(array('cper_password_recovery_key' => $arrayParameters['recoverKey']), $arrayParameters['personId']);

		}catch(\Exception $e){
			
			$results = false;
		}
		
		// Service implementation end
		
		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $results;
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_save_password_recovery_key_end', $arrayParameters);
		
		return $arrayParameters['results'];
	}

	public function getClientGroup($clientId)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
		$results = null;
		
		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_get_client_group_start', $arrayParameters);
		
		// Service implementation start

		$client = $this->getClientById($arrayParameters['clientId']);

		if (!empty($client)) {

			$melisEcomClientGroupsTbl = $this->getServiceManager()->get('MelisEcomClientGroupsTable');
			$clientGroup = $melisEcomClientGroupsTbl->getEntryById($client->cli_group_id)->current();

			if (!empty($clientGroup))
				$results = $clientGroup;
		}
		// Service implementation end
		
		// Adding results to parameters for events treatment if needed
		$arrayParameters['results'] = $results;
		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_get_client_group_end', $arrayParameters);
		
		return $arrayParameters['results'];
	}

	public function getPersonEmailsByPersonId($personId)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_get_person_emails_by_person_id_start', $arrayParameters);

		// Service implementation start
		$table = $this->getServiceManager()->get('MelisEcomClientPersonEmailsTable');
		$arrayParameters['results'] = $table->getEntryByField('cpmail_cper_id', $arrayParameters['personId'])->toArray();

		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_get_person_emails_by_person_id_end', $arrayParameters);

		return $arrayParameters['results'];
	}

	public function getPersonsByEmail($email)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_get_persons_by_email_start', $arrayParameters);

		// Service implementation start
		$table = $this->getServiceManager()->get('MelisEcomClientPersonEmailsTable');
		$arrayParameters['results'] = $table->getEntryByField('cpmail_email', $arrayParameters['email'])->toArray();

		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_get_persons_by_email_end', $arrayParameters);

		return $arrayParameters['results'];
	}

	public function saveClientPersonEmail($clientPersonId, $email)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_save_client_person_email_start', $arrayParameters);

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
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_save_client_person_email_end', $arrayParameters);

		return $arrayParameters['results'];
	}

	public function deleteClientPersonEmail($cpmail_id)
	{
		// Event parameters prepare
		$arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

		// Sending service start event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_save_client_person_email_start', $arrayParameters);

		// Service implementation start
		$table = $this->getServiceManager()->get('MelisEcomClientPersonEmailsTable');
		$result = null;

		try {
			$result = $table->deleteById($arrayParameters['cpmail_id']);
		} catch (\Exception $ex) {

		}

		$arrayParameters['results'] = $result;

		// Sending service end event
		$arrayParameters = $this->sendEvent('meliscommerce_service_client_save_client_person_email_end', $arrayParameters);

		return $arrayParameters['results'];
	}

    /**
     * Function to physically delete account
     *
     * @param $accountId
     * @return mixed
     */
	public function deleteAccount($accountId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_client_delete_account_start', $arrayParameters);

        // Service implementation start
        $melisEcomClientTable = $this->getServiceManager()->get('MelisEcomClientTable');
        $result = null;
        if(!empty($arrayParameters['accountId'])) {
            $result = $melisEcomClientTable->deleteById($arrayParameters['accountId']);
        }

        $arrayParameters['results'] = $result;

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_client_delete_account_end', $arrayParameters);

        return $arrayParameters['results'];

    }
}