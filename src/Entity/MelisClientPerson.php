<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Entity;

class MelisClientPerson
{
	protected $id;
	protected $person;
	protected $addresses;
	protected $emails;

	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getPerson()
	{
		return $this->person;
	}
	
	public function setPerson($person)
	{
		$this->person = $person;
	}
	
	public function getAddresses()
	{
		return $this->addresses;
	}
	
	public function setAddresses($addresses)
	{
		$this->addresses = $addresses;
	}

	public function setEmails($emails) {
	    $this->emails = $emails;
    }

    public function getEmails() {
	    return $this->emails;
    }
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}