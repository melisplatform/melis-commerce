<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * This helper will generate links for a melis page
 *
 */
class MelisCommerceLinksHelper extends AbstractHelper
{
	public $serviceManager;
	public $dataBreadcrumbs;

	public function __construct($sm)
	{
		$this->serviceManager = $sm;
		$this->dataBreadcrumbs = array();
	}
	
	/**
	 * 
	 * @param int $typeLink category/product/variant
	 * @param int $id id of the item
	 * @param int $langId lang id of the item
	 * 
	 */
	public function __invoke($typeLink, $id, $langId, $absolute)
	{
		$melisTree = $this->serviceManager->get('MelisComLinksService');
		
		$link = $melisTree->getPageLink($typeLink, $id, $langId, $absolute);
		
		return $link;
	}
}