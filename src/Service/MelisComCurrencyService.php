<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;


/**
 *
 * This service handles the document system of MelisCommerce.
 * Documents can be anything: images, word, pdf, etc.
 *
 */
use MelisCommerce\Entity\MelisDocument; 

class MelisComCurrencyService extends MelisComGeneralService
{
     /**
      * Returns a list of currencies
      * @param int $status currency status
      * @param int $start Query start
      * @param int $limit Query limit
      * @param int $order Query order
      * @param string $search query search string
      * @return object[]
      */
    public function getCurrencies($status = null, $start = null, $limit = null, $order = null, $search = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_currencies_start', $arrayParameters);
        
        // Service implementation start
        $currencyTbl = $this->getServiceLocator()->get('MelisEcomCurrencyTable');
        $data = $currencyTbl->getCurrencies($arrayParameters['status'], $arrayParameters['start'], 
                                            $arrayParameters['limit'], $arrayParameters['order'], $arrayParameters['search']);
        foreach($data as $currency){
            $results[] = $currency;
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_currencies_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This function gets the default currency of the platform
     * @return object
     */
    public function getDefaultCurrency()
    {
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'getDefaultCurrency';
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_default_currency_start', $arrayParameters);
        
        // Service implementation start
        $currencyTbl = $this->getServiceLocator()->get('MelisEcomCurrencyTable');
        $results = $currencyTbl->getDefaultCurrency()->current();        
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_default_currency_end', $arrayParameters);

        // Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);
         
        return $arrayParameters['results'];
    }
}