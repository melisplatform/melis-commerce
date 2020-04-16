<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\View\Helper;

use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Helper\AbstractHelper;

/**
 * This helper will generate links for a melis page
 */
class MelisCommerceLinksHelper extends AbstractHelper
{
    /**
     * Service Manager
     */
	protected $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
	public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return mixed
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
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
		$melisTree = $this->getServiceManager()->get('MelisComLinksService');
		
		$link = $melisTree->getPageLink($typeLink, $id, $langId, $absolute);
		
		return $link;
	}
}