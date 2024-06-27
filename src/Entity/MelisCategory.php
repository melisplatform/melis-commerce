<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Entity;

#[\AllowDynamicProperties]
class MelisCategory
{
	protected $id;
	protected $category;
	protected $translations;
	protected $seo;
	protected $countries;
	protected $children;

	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getCategory()
	{
		return $this->category;
	}
	
	public function setCategory($category)
	{
		$this->category = $category;
	}
	
	public function getTranslations()
	{
		return $this->translations;
	}
	
	public function setTranslations($translations)
	{
		$this->translations = $translations;
	}
	
	public function getSeo()
	{
		return $this->seo;
	}
	
	public function setSeo($seo)
	{
		$this->seo = $seo;
	}
	
	public function getCountries()
	{
		return $this->countries;
	}
	
	public function setCountries($countries)
	{
		$this->countries = $countries;
	}
	
	public function getChildren()
	{
		return $this->children;
	}
	
	public function setChildren($children)
	{
		$this->children = $children;
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}