<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Entity;

#[\AllowDynamicProperties]
class MelisAttribute
{
	protected $id;
	protected $attribute;
	protected $attributeValues;

	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getAttribute()
	{
		return $this->attribute;
	}
	
	public function setAttribute($attribute)
	{
		$this->attribute = $attribute;
	}
	
	public function getAttributeValues()
	{
		return $this->attributeValues;
	}
	
	public function setAttributeValues($attributeValues)
	{
		$this->attributeValues = $attributeValues;
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}