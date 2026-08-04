<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

/**
 * Read-only service exposing the commerce country reference data.
 *
 * Countries were previously only reachable through \MelisCommerce\Model\Tables\MelisEcomCountryTable
 * (a Table, not a Service), which the token web-service dispatcher cannot invoke (it only resolves
 * \MelisCommerce\Service\*). This thin service delegates to that table so country lookups can be
 * exposed as microservices, following the same conventions as the other MelisCom* services.
 */
class MelisComCountryService extends MelisComGeneralService
{
    /**
     * Returns the list of active countries (with their currency symbol), ordered by name.
     * @return array
     */
    public function getCountries()
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_countries_start', $arrayParameters);

        // Service implementation start
        $countryTbl = $this->getServiceManager()->get('MelisEcomCountryTable');
        $data = $countryTbl->getCountries();
        foreach ($data as $country) {
            $results[] = $country;
        }
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_countries_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Returns a single country by its id.
     * @param int $countryId
     * @return object|null
     */
    public function getCountryById($countryId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_country_by_id_start', $arrayParameters);

        // Service implementation start
        $countryTbl = $this->getServiceManager()->get('MelisEcomCountryTable');
        $results = $countryTbl->getEntryById($arrayParameters['countryId'])->current();
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_country_by_id_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Returns a country joined with its currency. When $status is provided, only active
     * country + active currency rows are returned.
     * @param int $countryId
     * @param int|null $status
     * @return array
     */
    public function getCountryCurrency($countryId, $status = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_country_currency_start', $arrayParameters);

        // Service implementation start
        $countryTbl = $this->getServiceManager()->get('MelisEcomCountryTable');
        $results = $countryTbl->getCountryCurrency($arrayParameters['countryId'], $arrayParameters['status'])->toArray();
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_country_currency_end', $arrayParameters);

        return $arrayParameters['results'];
    }
}
