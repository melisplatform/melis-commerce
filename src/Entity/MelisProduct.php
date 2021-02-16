<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Entity;

class MelisProduct
{
	protected $id;
	protected $product;
	protected $categories;
	protected $attributes;
	protected $texts;
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
	
	public function getProduct()
	{
		return $this->product;
	}
	
	public function setProduct($product)
	{
		$this->product = $product;
	}
	
	public function getCategories()
	{
		return $this->categories;
	}
	
	public function setCategories($categories)
	{
		$this->categories = $categories;
	}
	
	public function getAttributes()
	{
		return $this->attributes;
	}
	
	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;
	}
	
	public function getTexts()
	{
		return $this->texts;
	}
	
	public function setTexts($texts)
	{
		$this->texts = $texts;
	}
	
	public function getPrice()
	{
		return $this->price;
	}
	
	public function setPrice($price)
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