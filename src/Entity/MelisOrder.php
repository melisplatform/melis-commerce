<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Entity;

class MelisOrder
{
	protected $id;
	protected $order;
	protected $client;
	protected $person;
	protected $addresses; // array
	protected $basket; // array
	protected $payment; // array
	protected $shipping; // array
	protected $messages; // array
	protected $documents; // array

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
	
	public function getPerson()
	{
		return $this->person;
	}
	
	public function setPerson($person)
	{
		$this->person = $person;
	}
	
	public function getOrder()
	{
		return $this->order;
	}
	
	public function setOrder($order)
	{
		$this->order = $order;
	}
	
	public function getAddresses()
	{
		return $this->addresses;
	}
	
	public function setAddresses($addresses)
	{
		$this->addresses = $addresses;
	}
	
	public function getBasket()
	{
		return $this->basket;
	}
	
	public function setBasket($basket)
	{
		$this->basket = $basket;
	}
	
	public function getPayment()
	{
		return $this->payment;
	}
	
	public function setPayment($payment)
	{
		$this->payment = $payment;
	}
	
	public function getShipping()
	{
		return $this->shipping;
	}
	
	public function setShipping($shipping)
	{
		$this->shipping = $shipping;
	}
	
	public function getMessages()
	{
		return $this->messages;
	}
	
	public function setMessages($messages)
	{
		$this->messages = $messages;
	}

	public function getDocuments()
	{
		return $this->documents;
	}
	
	public function setDocuments($documents)
	{
		$this->documents = $documents;
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}