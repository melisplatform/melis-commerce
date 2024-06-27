<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use Laminas\Session\Container;
use MelisCore\Service\MelisGeneralService;

/**
 * 
 * This service handles the generic service system of Melis.
 *
 */
class MelisComGeneralService extends MelisGeneralService
{
	/**
	 * This method gets the list of column names from the requested table
	 * @param string $tableName name of table to be retrieved
	 */
	public function getTableColumns($tableName)
	{
	    $table = $this->getServiceManager()->get($tableName);
	    return $table->getTableColumns();
	}

	public function getEcomLang($locale = null)
	{
	    $melisEcomLangTable = $this->getServiceManager()->get('MelisEcomLangTable');
	    
	    if(empty($locale)){
	        $container = new Container('meliscore');
	        $locale = $container['melis-lang-locale'];
	    }
	    
	    $currentLangData = $melisEcomLangTable->getEntryByField('elang_locale', $locale)->current();
	    
	    // use enlish as default
	    if(empty($currentLangData)){
	        $currentLangData = $melisEcomLangTable->getEntryByField('elang_locale', 'en_EN')->current();
	    }
	    
	    return $currentLangData;
	}
	
	public function getFrontPluginLangId()
	{
	    $container = new Container('melisplugins');
	    $langId = $container['melis-plugins-lang-id'];
	    return $langId;
	}
	
	public function getFrontPluginLangLocale()
	{
	    $container = new Container('melisplugins');
	    $langLocale = $container['melis-plugins-lang-locale'];
	    return $langLocale;
	}
}