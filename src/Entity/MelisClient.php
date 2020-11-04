<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Entity;

class MelisClient
{
	protected $id;
	protected $client;
	protected $persons;
	protected $addresses;
	protected $company;

	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getClient()
	{
		return $this->client;
	}
	
	public function setClient($client)
	{
		$this->client = $client;
	}
	
	public function getPersons()
	{
		return $this->persons;
	}
	
	public function setPersons($persons)
	{
		$this->persons = $persons;
	}
	
	public function getAddresses()
	{
		return $this->addresses;
	}
	
	public function setAddresses($addresses)
	{
		$this->addresses = $addresses;
	}
	
	public function getCompany()
	{
		return $this->company;
	}
	
	public function setCompany($company)
	{
		$this->company = $company;
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}