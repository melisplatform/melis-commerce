<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

/**
 * MelisCommerce Price Service
 */

class MelisComPriceService extends MelisComGeneralService
{

    public function getItemPrice($itemId, $countryId, $groupId, $type = 'variant', Array $data = [])
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_item_price_start', $arrayParameters);

        $results = [
            'price' => null,
            'price_type' => $arrayParameters['type'],
            'price_default' => null,
            'price_client_group' => null,
            'price_details' => [],
            'price_currency' => [],
            'surcharge_module' => [],
            'logs' => []
        ];

        // Service implementation start
		$priceTable = $this->getServiceManager()->get('MelisEcomPriceTable');
        $price = $this->validatePrice($priceTable->getItemPrice($arrayParameters['type'], $arrayParameters['itemId'], 
                        $arrayParameters['countryId'], $arrayParameters['groupId'])->current());

        /** General Prices **/

        // General Price Group
        if (empty($price)) 
            $price = $this->validatePrice($priceTable->getItemPrice($arrayParameters['type'], $arrayParameters['itemId'], 
                    $arrayParameters['countryId'])->current());
	
        //  Client Group in General Country
        if (empty($price))
            $price = $this->validatePrice($priceTable->getItemPrice($arrayParameters['type'], $arrayParameters['itemId'], 
                    -1, $arrayParameters['groupId'])->current());
		
		// General Country and General Client Group
        if (empty($price))
            $price = $this->validatePrice($priceTable->getItemPrice($arrayParameters['type'], $arrayParameters['itemId'],
                    -1)->current());

        if (!empty($price)) {

            // Getting price details prefix with price_
            $priceDetails = [];
            foreach($price As $key => $val)
                if(!is_bool(strpos($key, 'price_')))
                    $priceDetails[$key] = $val;


            $priceNet = (float)$price->price_net;
            
            $results = [
                'price' => $priceNet,
                'price_type' => $arrayParameters['type'],
                'price_default' => $priceNet,
                'price_client_group' => [
                    'name' => $price->cgroup_name,
                    'price' => $priceNet,
                ],
                'price_details' => $priceDetails,
                'price_currency' => [
                    'id' => $price->cur_id,
                    'name' => $price->cur_name,
                    'code' => $price->cur_code,
                    'symbol' => $price->cur_symbol
                ],
                'surcharge_module' => [],
            ];

            if ($arrayParameters['type'] == 'variant')
                $results['logs'] = ['MelisCommerce: tr_meliscommerce_price_variant_price: '. $priceNet .' '. $price->cur_code .' - '. $price->cgroup_name];
            else
                $results['logs'] = ['MelisCommerce: tr_meliscommerce_price_product_price: '. $priceNet .' '. $price->cur_code .' - '. $price->cgroup_name];
        }
        
        // If the type if variant then no price
        // this will try to get the price of the product
        if ($arrayParameters['type'] == 'variant' && empty($price)) {

            $prdTbl = $this->getServiceManager()->get('MelisEcomVariantTable');
            $variant = $prdTbl->getEntryById($arrayParameters['itemId'])->current();
            $results = $this->getItemPrice($variant->var_prd_id, $arrayParameters['countryId'], 
                    $arrayParameters['groupId'], 'product', $arrayParameters['data']);
        }
        $results['initial_price'] = $results['price'];
		// Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;

        $arrayParameters = $this->sendEvent('meliscommerce_service_get_item_price_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
	 * Validating Product price
	 */
	private function validatePrice($productPrice)
	{
		if (!empty($productPrice)) {
			// Just to be sure that data on Price is in Numeric data type
			if (is_numeric((float)$productPrice->price_net) && !is_null($productPrice->price_net))
				return $productPrice;
		}

		return null;
	}

    public function translateLogs($priceLogs)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_translate_logs_start', $arrayParameters);

        $translator = $this->getServiceManager()->get('translator');

        if (empty($priceLogs))
            return;

        $skipEncoding = false;
        if (!is_array($priceLogs))
            $skipEncoding = true;

        if ($skipEncoding)
            $priceLogs = json_decode($priceLogs, true);

        foreach($priceLogs As $key => $log) {

            if (is_array($log)) {

                // If log data is array that contain values to be display/place on the translated text
                // the log value should ONLY contain element with index of target translation and the value is array
                foreach($log As $targetTrans => $values) {
                    if (strpos($targetTrans, 'tr_') !== false) {

                        $text = $translator->translate($targetTrans);
                        if (is_array($values)) {
                            foreach($values As $vKey => $value) {
                                $text = str_replace($vKey, $value, $text);
                            }
                        } else {
                            $text = sprintf($text, $value);
                        }

                        $priceLogs[$key] = $text;

                        break;
                    }
                }

            } else {
                $logWords = explode(' ', $log);
                foreach($logWords As $lKey => $word) {
                    if (strpos($word, 'tr_') !== false)
                        $logWords[$lKey] = $translator->translate($word);
                }

                $priceLogs[$key] = implode(' ', $logWords);
            }
        }

        if ($skipEncoding)
            $priceLogs = json_encode($priceLogs);

        $arrayParameters['results'] = $priceLogs;

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_translate_logs_end', $arrayParameters);

        return $arrayParameters['results'];
    }
}