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
        $currencyTbl = $this->getServiceManager()->get('MelisEcomCurrencyTable');
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
        $cacheKey = 'currency-getDefaultCurrency';
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
//        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
//        if (!empty($results)) return $results;
        $cache = $this->getServiceManager()->get($cacheConfig);
        if ($cache->hasItem($cacheKey)){
            return $cache->getItem($cacheKey);
        }
        
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_default_currency_start', $arrayParameters);
        
        // Service implementation start
        $currencyTbl = $this->getServiceManager()->get('MelisEcomCurrencyTable');
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

    public function getCountriesUsingCurrency($currencyId)
    {
        //prepare events parameters
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        //service event start
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_valid_children_by_lang_id', $arrayParameters);

        //implementation start
        $currencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
        $isCurrencyUsed = $currencyTable->getCountriesUsingCurrency($arrayParameters['currencyId'])->toArray();
        //implementation end

        $arrayParameters['results'] = $isCurrencyUsed;
        //service event end
        $arrayParameters = $this->sendEvent('meliscommerce_service_category_get_valid_children_by_lang_id_end', $arrayParameters);

        return $arrayParameters['results'];
    }
}