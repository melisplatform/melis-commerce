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
 * This Service will Compute Checkout Shipment Cost
 * 
 * This Service is created for a testing
 *
 */
class MelisComShipmentCostService extends MelisComGeneralService
{
    public function computeShipmentCost($shipment)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_compute_shipment_costs_start', $arrayParameters);
        
        // Service implementation start
        $shipment = $arrayParameters['shipment'];
        
        // Shipping Total Amount
        $total = 0;
        // Shipping errors
        $errors = array();
        
        /**
         * Computing Shipping Cost can be place here
         */
        
        // Static Value for Shipping Cost, for testing
        $total = 0; 
        // Static error
        //$errors['xx'] = 'MELIS_COMMERCE_CHECKOUT_ERROR_SHIPMENT_CANT_COMPUTE_PRODUCT';
        // Static values End here
        
        //  Results initialization
        $shipment['costs']['shipment']['total'] = $total;
        if (!empty($errors))
            $shipment['costs']['shipment']['errors'] = $errors;
        
        // Service implementation end
        
        $arrayParameters['results'] = $shipment;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_compute_shipment_costs_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
}