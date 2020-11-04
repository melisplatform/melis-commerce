<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Entity;

class MelisCoupon
{
	protected $id;
	protected $coupon;

	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getCoupon()
	{
		return $this->coupon;
	}
	
	public function setCoupon($coupon)
	{
		$this->coupon = $coupon;
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}