<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Entity;

class MelisVariant
{
	protected $id;
	protected $variant;
	protected $attributesValues;
	protected $stock;
	protected $price;
	protected $documents;

	public function getId()
	{
	    return $this->id;
	}
	
	public function setId($id)
	{
	    $this->id = $id;
	}
	
	public function getVariant()
	{
	    return $this->variant;
	}
	
	public function setVariant($variant)
	{
	    $this->variant = $variant;
	}
	
	public function getAttributeValues()
	{
	    return $this->attributesValues;
	}
	
	public function setAttributeValues($attributesValues)
	{
	    $this->attributesValues = $attributesValues;
	}
	
	public function getStocks()
	{
	    return $this->stock;
	}
	
	public function setStocks($stock)
	{
	    $this->stock = $stock;
	}
	
	public function getPrices()
	{
	    return $this->price;
	}
	
	public function setPrices($price)
	{
	    $this->price = $price;
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