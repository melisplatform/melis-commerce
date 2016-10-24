<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use Zend\Session\Container;

/**
 *
 * This service handles the checkout system of MelisCommerce.
 *
 */
class MelisComOrderCheckoutService extends MelisComGeneralService
{
    /**
     * This service will check the validity of a client's basket
     * 
     * Return value structure:
     * array(
     *      'success' => true/false
     *      'basket' => array(
     *          'ok' => array(
     *              'variantId' => MelisBasket,
     *              'variantId' => MelisBasket,
     *              'variantId' => MelisBasket,
     *          ),
     *          'ko' => array(
     *              'variantId' => array(
     *                  'error' => 'ERROR_CODE', // Exemple: MELIS_COMMERCE_CHECKOUT_ERROR_BASKET_QUANTITY
     *                  'variantId' => MelisBasket,
     *              ),
     *              'variantId' => array(
     *                  'error' => 'ERROR_CODE', // Exemple: MELIS_COMMERCE_CHECKOUT_ERROR_BASKET_VARIANT_NOT_EXISTING
     *                  'variantId' => MelisBasket,
     *              ),
     *          )
     *      )
     * )
     * 
     * @param int $clientId
     * @return array[]
     */
    public function validateBasket($clientId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array(
            'success' => false,
            'basket' => array()
        );
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_basket_validate_start', $arrayParameters);
        
        // Variant Service Manager
        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
        // Get the basket from the BasketService
        $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
        $clientBasket = $melisComBasketService->getBasket($arrayParameters['clientId']);
        
        $melisEcomClientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
        $client = $melisEcomClientTable->getEntryById($arrayParameters['clientId']);
        $clientData = $client->current();
        $clientCountryId = null;
        if (!empty($clientData))
        {
            $clientCountryId = $clientData->cli_country_id;
        }
        
        // Validation results handler
        $okVariant = array();
        $koVariant = array();
        
        // For each item in the basket, check if variant exists, if it's active and if quantity match the demand
        foreach ($clientBasket As $val)
        {
            // Basket Variant details
            $variantId = $val->getVariantId();
            $variant = $val->getVariant();
            $variantQty = $val->getQuantity();
            
            // Checking if Variant exist on Database
            if (!empty($variant))
            {
                // Variant Service that will return Variant final Stocks
                $variantStock = $melisComVariantService->getVariantFinalStocks($variantId, $clientCountryId);
                
                if (!is_null($variantStock))
                {
                    if ($variantQty <= $variantStock->stock_quantity)
                    {
                        // Checking if Variant status is Active
                        $variantStatus = $variant->getVariant()->var_status;
                        if ($variantStatus)
                        {
                            // OK : Variant validated
                            $okVariant[$variantId] = $val;
                        }
                        else 
                        {
                            // KO : Variant is Inactive status
                            $koVariant[$variantId] = array(
                                'error' => 'MELIS_COMMERCE_CHECKOUT_ERROR_BASKET_VARIANT_NOT_ACTIVE',
                                $variantId => $val,
                            );
                        }
                    }
                    else 
                    {
                        // Variant Quantity is not enough of Basket demand
                        // KO : Variant Quantity not enough
                        $koVariant[$variantId] = array(
                            'error' => 'tr_MELIS_COMMERCE_CHECKOUT_ERROR_BASKET_QUANTITY',
                            $variantId => $val,
                        );
                    }
                }
                else 
                {
                    // Variant Quantity is not set on Variant Page (Back-office)
                    // KO : Variant Quantity not Set
                    $koVariant[$variantId] = array(
                        'error' => 'MELIS_COMMERCE_CHECKOUT_ERROR_BASKET_VARIANT_QUANTITY_NOT_SET',
                        $variantId => $val,
                    );
                }
            }
            else
            {
                // Variant is not Exist
                // KO : Variant not exist
                $koVariant[$variantId] = array(
                    'error' => 'MELIS_COMMERCE_CHECKOUT_ERROR_PRODUCT_NOT_EXISTING',
                    $variantId => $val,
                );
            }
            // End of Variant Stocks Validations
        }
        
        if (!empty($okVariant))
        {
            $results['basket']['ok'] = $okVariant;
        }
        
        if (!empty($koVariant))
        {
            $results['basket']['ko'] = $koVariant;
        }
        else 
        {
            $results['succss'] = true;
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_basket_validate_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    
    /**
     * This service will validates delivery and billing addresses 
     * 
     * Checking of addresses by default doesn't do anything and assumes that all addresses are ok.
     * Therefore, any custom checking should attach the meliscommerce_service_checkout_address_validation_end event
     * to add tests (validity of address through 3rd party API, limitations of area, etc).
     * 
     * Return value structure:
     * array(
     *      'success' => true/false
     *      'addresses' => array(
     *          'delivery' => array(
     *              'success' => true,
     *              'address' => $deliveryAddress,
     *          ),   
     *          'billing' => array(
     *              'success' => false,
     *              'address' => $deliveryAddress,
     *              'error' => 'ERROR_CODE', // Exemple: MELIS_COMMERCE_CHECKOUT_ERROR_ADDRESS_NOT_ACCEPTED
     *          ),
     *      )
     * )
     * 
     * 
     * @param int|array $deliveryAddress The clientAddressId for delivery, or an array respecting table melis_ecom_client_address
     * @param int|array $billingAddress The clientAddressId for billing, or an array respecting table melis_ecom_client_address
     */
    public function validateAddresses($deliveryAddress, $billingAddress)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // No tests by default in Melis Platform, return will always be ok if no listener attached to events
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_address_validation_start', $arrayParameters);
        
        $results = array(
            'success' => true,
            'addresses' => array(
                'delivery' => array(
                    'success' => true,
                    'address' => $arrayParameters['deliveryAddress'],
                ),   
                'billing' => array(
                    'success' => true,
                    'address' => $arrayParameters['billingAddress'],
                ),
            )
        );
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_address_validation_end', $arrayParameters);
        
        
        if ($arrayParameters['results']['success'])
        {
            // Saving addresses in session for later use
            $container = new Container('meliscommerce');
            if (empty($container['checkout']))
                $container['checkout'] = array();
            $container['checkout']['addresses'] = $arrayParameters['results']['addresses'];
        }
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service will compute the shipment price 
     * 
     * Computing shipment by default doesn't do anything.
     * Therefore, any custom shipment should attach the meliscommerce_service_checkout_shipment_computation_end event
     * to add custom computation depending on the carriers.
     * 
     * Return value structure:
     * array(
     *      'success' => true/false
     *      'clientId' => xx,
     *      'costs' => array(
     *          'shipment' => array(
     *              'total' => xx
     *              'details' => array(
     *                  // free
     *              ),
     *              'errors' => array(
     *                  'variantId' => 'ERROR_CODE', // Exemple: MELIS_COMMERCE_CHECKOUT_ERROR_CANT_COMPUTE_PRODUCT
     *              ),
     *          )
     *      )
     * )
     * 
     * @param int $clientId
     * @return array[]
     * 
     */
    public function computeShipmentCost($clientId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // No tests by default in Melis Platform, return will always be ok if no listener attached to events
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_shipment_computation_start', $arrayParameters);
        
        $results = array(
            'success' => true,
            'clientId' => $arrayParameters['clientId'],
            'costs' => array(
                'shipment' => array(
                    'total' => 0,
                ),
            ),
        );
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_shipment_computation_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service will compute the order price
     *
     * Computing order by default will calculate based on quantity in the basket and price_net of variant
     * Therefore, any custom computation should attach the meliscommerce_service_checkout_order_computation_end event
     * to add custom computation depending on your rules / region / country (taxes...)
     *
     * Return value structure:
     * array(
     *      'success' => true/false
     *      'clientId' => xx,
     *      'costs' => array(
     *          'order' => array(
     *              'total' => xx,
     *              'details' => array(
     *                  'variantId1' => array('unit_price' => xx, 'quantity' => xx, 'total_price' => xx),
     *                  'variantId2' => array('unit_price' => xx, 'quantity' => xx, 'total_price' => xx),
     *                  'variantId3' => array('unit_price' => xx, 'quantity' => xx, 'total_price' => xx),
     *              ),
     *              'errors' => array(
     *                  'variantId4' => 'ERROR_CODE', // Exemple: MELIS_COMMERCE_CHECKOUT_ERROR_CANT_COMPUTE_PRODUCT
     *              ),
     *          )
     *      ),
     * )
     *
     * @param int $clientId
     * @return array[]
     *
     */
    public function computeOrderCost($clientId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
    
        // No tests by default in Melis Platform, return will always be ok if no listener attached to events
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_order_computation_start', $arrayParameters);
    
        $results = array(
            'success' => false,
            'clientId' => $arrayParameters['clientId'],
            'costs' => array(
                'order' => array(
                    'total' => 0,
                ),
            ),
        );
        
        // Product and Variant Service managers
        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
        
        $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
        $clientBasket = $melisComBasketService->getBasket($arrayParameters['clientId']);
        
        $melisEcomClientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
        $client = $melisEcomClientTable->getEntryById($arrayParameters['clientId']);
        $clientData = $client->current();
        $clientCountryId = null;
        if (!empty($clientData))
        {
            $clientCountryId = $clientData->cli_country_id;
        }
        
        // computation of variants based on price_net and quantity
        
        $variantDetails = array();
        $errors = array();
        
        // Total Amount of the Basket
        $totalCost = 0;
        
        foreach ($clientBasket As $val)
        {
            $variantId = $val->getVariantId();
            $variant = $val->getVariant();
            $variantQty = $val->getQuantity();
            
            $varianTotalAmount = 0;
            if (!empty($variant))
            {
                // Variant Service that will return Variant final Price
                $variantPrice = $melisComVariantService->getVariantFinalPrice($variantId, $clientCountryId);
                
                // Check if Variant final price has result
                if (!is_null($variantPrice))
                {
                    $varianTotalAmount = $variantPrice->price_net * $variantQty;
                    if ($varianTotalAmount > 0)
                    {
                        $variantDetails[$variantId] = array(
                            'unit_price' => $variantPrice->price_net, 
                            'quantity' => $variantQty, 
                            'total_price' => $varianTotalAmount
                        );
                        $totalCost += $varianTotalAmount;
                    }
                    else
                    {
                        // KO : Variant total Amount is 0 (Zero)
                        $errors[$variantId] = 'MELIS_COMMERCE_CHECKOUT_ERROR_PRODUCT_PRICE_IS_ZERO';
                    }
                }
                else 
                {
                    /**
                     * If the Variant Final price not set, this will try to look price in product info
                     * and use as Variant price
                     */
                    
                    // Product Service that will return Product final Price
                    $productPrice = $melisComProductService->getProductFinalPrice($variantId , $clientCountryId);
                    if (!is_null($productPrice))
                    {
                        $varianTotalAmount = $variantPrice->price_net * $variantQty;
                        if ($varianTotalAmount > 0)
                        {
                            $variantDetails[$variantId] = array(
                                'unit_price' => $variantPrice->price_net,
                                'quantity' => $variantQty,
                                'total_price' => $varianTotalAmount
                            );
                            $totalCost += $varianTotalAmount;
                        }
                        else
                        {
                            // KO : Variant total Amount is 0 (Zero)
                            $errors[$variantId] = 'MELIS_COMMERCE_CHECKOUT_ERROR_PRODUCT_PRICE_IS_ZERO';
                        }
                    }
                    else 
                    {
                        // KO : Variant price is not set
                        $errors[$variantId] = 'MELIS_COMMERCE_CHECKOUT_ERROR_PRODUCT_PRICE_NOT_SET';
                    }
                }
            }
            else 
            {
                // KO : Variant not exist
                $errors[$variantId] = 'MELIS_COMMERCE_CHECKOUT_ERROR_PRODUCT_NOT_EXISTING';
            }
        }
        
        $results['costs']['order']['total'] = $totalCost;
        
        if (!empty($variantDetails))
        {
            $results['costs']['order']['details'] = $variantDetails;
        }
        
        if (!empty($errors))
        {
            $results['costs']['order']['errors'] = $errors;
        }
        else 
        {
            $results['success'] = true;
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_order_computation_end', $arrayParameters);
    
        return $arrayParameters['results'];
    }
    
    /**
     * This service will compute the full order price, with all costs
     *
     * Costs included in the computation are shipment and order.
     * Others costs must attach events to be added
     *
     * Return value structure:
     * array(
     *      'success' => true/false
     *      'clientId' => xx,
     *      'costs' => array(
     *          'total' => xx,
     *          'shipment' => ShipmentCostArray,
     *          'order' => OrderCostArray,
     *      ),
     * )
     *
     * @param int $clientId
     * @return array[]
     *
     */
    public function computeAllCosts($clientId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_allcosts_computation_start', $arrayParameters);
        
        $success = false;
        
        $results = array(
            'success' => $success,
            'clientId' => $arrayParameters['clientId'],
            'costs' => array(
                'total' => 0,
            )
        );
        
        $orderCosts = $this->computeOrderCost($arrayParameters['clientId']);
        $shipmentCosts = $this->computeShipmentCost($arrayParameters['clientId']);
        
        if ($orderCosts['success'] == true && $shipmentCosts['success'] == true)
        {
            $success = true;
        }
        
        // Success flag
        $arrayParameters['success'] = $success;
        
        // Merging all Costs
        $allCosts = array_merge($results['costs'], $orderCosts['costs'], $shipmentCosts['costs']);
        $results['costs'] = $allCosts;
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_allcosts_computation_end', $arrayParameters);
    
        // Total Amount of Cost
        $totalCost = 0;
        
        foreach ($arrayParameters['results']['costs'] As $key => $val)
        {
            // exclude Total index
            if ($key != 'total')
            {
                // Add total for each costs
                $totalCost += $arrayParameters['results']['costs'][$key]['total'];
            }
        }
        
        // Now update the full total price
        $arrayParameters['results']['costs']['total'] = $totalCost;
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service must be called before payment.
     * It will check that everything is ok before proceeding to payment and will save the temporary order
     * Only call that must be done before is validateAddresses as they will be saved in session
     * 
     * Return value structure:
     * array(
     *      'success' => true/false
     *      'clientId' => xx,
     *      'orderId' => xx,
     *      'errors' => array(
     *          'basket' => BasketValidityArray,
     *          'addresses' => ShipmentCostArray,
     *          'costs' => OrderCostArray
     *      ),
     * )
     * 
     * @param int $clientId
     * 
     * @return array[]
     */
    public function checkoutStep1_prePayment($clientId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_step1_prepayment_start', $arrayParameters);

        $results = array(
            'success' => false,
            'clientId' => $arrayParameters['clientId'],
            'orderId' => null,
            'errors' => array(
                'basket' => array(),
                'addresses' => array(),
                'costs' => array(),
            ),
        );
        
        // basket validity
        $basketResults = $this->validateBasket($arrayParameters['clientId']);
        if (!empty($basketResults['success']) && $basketResults['success'] == true)
            $basketValidity = true;
        else
            $basketValidity = false;

        $addressesValidity = false;
        $container = new Container('meliscommerce');
        if (!empty($container['checkout']['addresses']))
            $addressesValidity = true;
        
        $costsResults = $this->computeAllCosts($arrayParameters['clientId']); 
        if (!empty($costsResults['success']) && $costsResults['success'] == true)
            $costsValidity = true;
        else
            $costsValidity = false;
        
        $success = false;
        if ($basketValidity && $addressesValidity && $costsValidity)
        {
            // Proceed to order creation with temporary status ongoing
            
            /**
             * Do save order with:
             * - order (status -1, temporary)
             * - order basket
             * - order addresses
             */ 
            
            // Unset Checkout Addresses on Container
            unset($container['checkout']['addresses']);
            
            $results['success'] = true;
        }
        else
        {
            // create response with errors from basket or addresses
            if ($basketValidity != true)
            {
                foreach ($basketResults['basket']['ko'] As $key => $val)
                {
                    $results['errors']['basket'][$key] = $val;
                }
            }
            
            if ($addressesValidity != true)
            {
                if (!empty($container['checkout']['addresses']))
                {
                    foreach ($container['checkout']['addresses'] As $key => $val)
                    {
                        $results['errors']['addresses'][$key] = $val;
                    }
                }
            }
            
            if ($costsValidity != true)
            {
                foreach ($costsResults['costs'] As $key => $val)
                {
                    // exclude Total index
                    if ($key != 'total')
                    {
                        $results['errors']['costs'][$key] = $val;
                    }
                }
            }
            
            $results['success'] = false;
        }
            
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_step1_prepayment_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service is to be called after payment (can be on the page if full integration or on call back
     * page from the bank or payment system).
     * The custom payment decoding and analyzing must be done by attaching a listenner on the event
     * meliscommerce_service_checkout_step2_postpayment_end and modifying the results
     * 
     * Return value structure:
     * array(
     *      'success' => true/false
     *      'clientId' => xx,
     *      'orderId' => xx,
     *      'payment_details' => array(
     *          'paymentType' => x,
     *          'transactionId' => 'xxxx',
     *          'transactionReturnCode' => 'xx',
     *          'transactionPricePaid' => x,
     *          'transactionFullRawResponse' => 'xxxxxxxxxxx',
     *          'transactionDateTime' => 'yyyy-mm-dd hh:ii:ss'
     *      ),
     *      'errors' => array(
     *          'payment' => array(
     *              'error_code' => 'xxxx',
     *              'error_code' => 'xxxx',
     *              'error_code' => 'xxxx',
     *          ),
     *      ),
     * )
     * 
     */
    public function checkoutStep2_postPayment()
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_step2_postpayment_start', $arrayParameters);

        $results = array(
            'success' => false,
            'clientId' => null,
            'orderId' => null,
            'payment_details' => array(
                'paymentType' => null,
                'transactionId' => '',
                'transactionReturnCode' => '',
                'transactionPricePaid' => 0,
                'transactionFullRawResponse' => '',
                'transactionDateTime' => ''
            ),
            'errors' => array(
                'payment' => array(),
            ),
        );

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_step2_postpayment_end', $arrayParameters);
        
        if ($arrayParameters['results']['success'] && !empty($arrayParameters['results']['orderId']))
        {
            // We have a validated payment and the orderId has been retrieved
            
            // one last check: full price must be equal to price paid, if not save order with error status
            
            // else proceed with finalizing order: orderPayment, and update status to new order on order table
        }
        
        return $arrayParameters['results'];
    }
}