<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Entity;

#[\AllowDynamicProperties]
class MelisBasket
{
	protected $id;         // id in anonymous or persistent table
	protected $type;       // anonymous or persistent
	protected $variantId;  // variant id
	protected $variant;    // MelisVariant
	protected $quantity;   // quantity
	protected $dateAdded;  // datetime
	protected $extra = array();

	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getVariantId()
	{
		return $this->variantId;
	}
	
	public function setVariantId($variantId)
	{
		$this->variantId = $variantId;
	}
	
	public function getVariant()
	{
		return $this->variant;
	}
	
	public function setVariant($variant)
	{
		$this->variant = $variant;
	}
	
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
	}
	
	public function getDateAdded()
	{
		return $this->dateAdded;
	}
	
	public function setDateAdded($dateAdded)
	{
		$this->dateAdded = $dateAdded;
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	public function setExtra($var, $val)
	{
		$this->extra[$var] = $val;
	}
	public function getExtra()
	{
		return $this->extra; 
	}
}