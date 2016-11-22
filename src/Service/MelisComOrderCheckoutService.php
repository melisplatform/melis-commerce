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
        
        $container = new Container('meliscommerce');
        $clientCountryId = $container['checkout']['countryId'];
        
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
            $results['success'] = true;
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
            'success' => false,
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
     *              'totalWithoutCoupon' => xx,
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
                    'totalWithoutCoupon' => 0,
                    'total' => 0,
                ),
            ),
        );
        
        // Product and Variant Service managers
        $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
        $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
        
        $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
        $clientBasket = $melisComBasketService->getBasket($arrayParameters['clientId']);
        
        $container = new Container('meliscommerce');
        $clientCountryId = $container['checkout']['countryId'];
        
        // computation of variants based on price_net and quantity
        
        $variantDetails = array();
        $errors = array();
        
        // Total Amount of the Basket
        $totalCost = 0;
        
        if (!is_null($clientBasket))
        {
            foreach ($clientBasket As $val)
            {
                $variantId = $val->getVariantId();
                $variant = $val->getVariant();
                $variantQty = $val->getQuantity();
                $productId = $variant->getVariant()->var_prd_id;
                
                $varianTotalAmount = 0;
                if (!empty($variant))
                {
                    // Variant Service that will return Variant final Price
                    $variantPrice = $melisComVariantService->getVariantFinalPrice($variantId, $clientCountryId);
    
                    if (is_null($variantPrice))
                    {
                        $variantPrice = $melisComProductService->getProductFinalPrice($productId, $clientCountryId);
                    }
                    
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
                        $productPrice = $melisComProductService->getProductFinalPrice($productId , $clientCountryId);
                        if (!is_null($productPrice))
                        {
                            $varianTotalAmount = $productPrice->price_net * $variantQty;
                            if ($varianTotalAmount > 0)
                            {
                                $variantDetails[$variantId] = array(
                                    'unit_price' => $productPrice->price_net,
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
        }
        
        // as default value totalWithoutCoupon is equal to the total of order cost
        $results['costs']['order']['totalWithoutCoupon'] = $totalCost;
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
        $results['success'] = $success;
        
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
            // exclude "total" index
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
        
        $orderReferenceCode = $this->genderOrderRefernceCode();
        
        $melisComOrderService = $this->getServiceLocator()->get('MelisComOrderService');
        
        $success = false;
        if ($basketValidity && $addressesValidity && $costsValidity && $orderReferenceCode['success'])
        {
            // Proceed to order creation with temporary status ongoing
            
            /**
             * Do save order with:
             * - order (status -1, temporary)
             * - order basket
             * - order addresses
             */ 
            
            $container = new Container('meliscommerce');
            $clientCountryId = $container['checkout']['countryId'];
            
            $billingAdd = $container['checkout']['addresses']['addresses']['billing']['address'];
            
            unset($billingAdd['cadd_client_id']);
            unset($billingAdd['cadd_client_person']);
            unset($billingAdd['cadd_address_name']);
            
            foreach ($billingAdd As $bKey => $bVal)
            {
                $newIndex = substr($bKey, 1);
                $billingAdd['o'.substr($bKey, 1)] = $bVal;
                unset($billingAdd[$bKey]);
            }
             
            $billingAddress[] = $billingAdd;
            
            
            $deliveryAdd = $container['checkout']['addresses']['addresses']['delivery']['address'];
            
            unset($deliveryAdd['cadd_client_id']);
            unset($deliveryAdd['cadd_client_person']);
            unset($deliveryAdd['cadd_address_name']);
            
            foreach ($deliveryAdd As $bKey => $bVal)
            {
                $newIndex = substr($bKey, 1);
                $deliveryAdd['o'.substr($bKey, 1)] = $bVal;
                unset($deliveryAdd[$bKey]);
            }
            
            $deliveryAddress[] = $deliveryAdd;
            
            $melisEcomClientPersonTable = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
            $clientMainPerson = $melisEcomClientPersonTable->getClientMainPersonByClientId($container['checkout']['clientId'])->current();
            
            $contactId = $container['checkout']['contactId'];
            
            $order = array(
                'ord_client_id' => $container['checkout']['clientId'],
                'ord_client_person_id' => $contactId,
                'ord_status' => '-1',
                'ord_reference' => $orderReferenceCode['code'],
                'ord_billing_address' => -1,
                'ord_delivery_address' => -1,
                'ord_date_creation' => date('Y-m-d H:i:s')
            );
            
            // Getting Current Langauge ID
            $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
            $langId = $melisTool->getCurrentLocaleID();
            
            $melisComProductService = $this->getServiceLocator()->get('MelisComProductService');
            $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
            $melisComCategoryService = $this->getServiceLocator()->get('MelisComCategoryService');
            $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
            
            $clientBasket = $melisComBasketService->getBasket($container['checkout']['clientId']);
            
            $basket = array();
            foreach ($clientBasket As $key => $val)
            {
                
                $variantQty = $val->getQuantity();
                $varianData = $val->getVariant();
                $variant = $varianData->getVariant();
                
                $variantAttributes = array();
                $variantAttributesStr = '';
                
                foreach ($varianData->getAttributeValues() As $aVal)
                {
                    foreach ($aVal->atval_trans As $tVal)
                    {
                        if ($tVal->avt_lang_id == $langId)
                        {
                            $varAttrArray = (Array) $tVal;
                            array_push($variantAttributes, $varAttrArray['avt_v_'.$aVal->atype_column_value]);
                        }
                    }
                }
                if (!empty($variantAttributes))
                {
                    $variantAttributesStr = implode(', ', $variantAttributes);
                }
                
                $productId = $variant->var_prd_id;
                $productData = $melisComProductService->getProductById($productId);
                
                $productCategories = $productData->getCategories();
                $productCategoryName = '';
                foreach ($productCategories As $cVal)
                {
                    $productCategoryName = $melisComCategoryService->getCategoryNameById($cVal->pcat_cat_id, $langId);
                    break;
                }
                
                $variantPrice = $melisComVariantService->getVariantFinalPrice($variant->var_id, $clientCountryId);
                
                if (is_null($variantPrice))
                {
                    $variantPrice = $melisComProductService->getProductFinalPrice($productId, $clientCountryId);
                }
                
                $data = array(
                    'obas_id' => null,
                    'obas_variant_id' => $variant->var_id,
                    'obas_product_name' => $melisComProductService->getProductName($productId, $langId),
                    'obas_quantity' => $variantQty,
                    'obas_sent' => '0',
                    'obas_sku' => $variant->var_sku,
                    'obas_attributes' => $variantAttributesStr,
                    'obas_category_name' => $productCategoryName,
                    'obas_currency' => ($variantPrice->cur_id) ? $variantPrice->cur_id : '-1',
                    'obas_price_net' => $variantPrice->price_net,
                    'obas_price_gross' => $variantPrice->price_gross,
                    'obas_price_vat' => $variantPrice->price_vat_price,
                    'obas_price_other_tax' => $variantPrice->price_other_tax_price,
                );
                
                array_push($basket, $data);
            }
            
            // Save new Order
            $orderId = $melisComOrderService->saveOrder($order, $basket, $billingAddress, $deliveryAddress);
            
            // Set Order Id to Session
            $container['checkout']['orderId'] = $orderId;
            
            // Unset Checkout Addresses on Container
            unset($container['checkout']['addresses']);
            
            
            $results['success'] = true;
        }
        else
        {
            // create response with errors from basket or costs
            if ($basketValidity != true)
            {
                foreach ($basketResults['basket']['ko'] As $key => $val)
                {
                    $results['errors']['basket'][$key] = $val;
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
            
            if ($orderReferenceCode['success'] != true)
            {
                $results['errors']['order'] = $orderReferenceCode['error'];
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
                'transactionPricepaidConfirm' => '',
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
            $orderId = $arrayParameters['results']['orderId'];
            // one last check: full price must be equal to price paid, if not save order with error status
            // else proceed with finalizing order: orderPayment, and update status to new order on order table
            
            $order = $this->computeAllCosts($arrayParameters['results']['clientId']);
            
            $totalCost = $order['costs']['total'];
            
            if ($totalCost == $arrayParameters['results']['payment_details']['transactionPricePaid'])
            {
                $orderData = array(
                    'ord_status' => 1 // Status new Order
                );
            }
            else 
            {
                $orderData = array(
                    'ord_status' => 6 // Payment total cost not equal to paid amount
                );
            }
            
            $paymentData = $arrayParameters['results']['payment_details'];
            
            $payment = array(
                'opay_order_id' => $orderId,
                'opay_price_total' => $totalCost,
                'opay_price_order' => $order['costs']['order']['total'],
                'opay_price_shipping' => $order['costs']['shipment']['total'],
                'opay_currency_id' => '-1', // Static data, -1 is Defualt Currentcy id
                'opay_payment_type_id' => $paymentData['paymentType'],
                'opay_transac_id' => $paymentData['transactionId'],
                'opay_transac_return_value' => $paymentData['transactionReturnCode'],
                'opay_transac_price_paid_confirm' => $paymentData['transactionPricepaidConfirm'],
                'opay_transac_raw_response' => $paymentData['transactionFullRawResponse'],
                'opay_date_payment' => date('Y-m-d H:i:s'),
            );
            
            $melisEcomOrderPaymentTable = $this->getServiceLocator()->get('MelisEcomOrderPaymentTable');
            $melisEcomOrderPaymentTable->save($payment);
            
            $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
            $melisEcomOrderTable->save($orderData, $arrayParameters['results']['orderId']);
            
            // Deduct Quantity of the Product/Variant
            $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
            $clientBasket = $melisComBasketService->getBasket($arrayParameters['results']['clientId']);
            
            $melisComVariantService = $this->getServiceLocator()->get('MelisComVariantService');
            $melisEcomVariantStockTable = $this->getServiceLocator()->get('MelisEcomVariantStockTable');
            
            $container = new Container('meliscommerce');
            $clientCountryId = $container['checkout']['countryId'];
            
            foreach ($clientBasket As $val)
            {
                $variantData = $val->getVariant();
                $variantId = $variantData->getId();
                $variantQty = $val->getQuantity();
                
                $variantStock = $melisComVariantService->getVariantFinalStocks($variantId, $clientCountryId);
                
                if (!empty($variantStock))
                {
                    $newQty = array(
                        'stock_quantity' => $variantStock->stock_quantity - $variantQty
                    );
                    $melisEcomVariantStockTable->save($newQty, $variantStock->stock_id);
                }
            }
            
            
            // Empty Client Basket
            $melisComBasketService = $this->getServiceLocator()->get('MelisComBasketService');
            $melisComBasketService->emptyBasket($arrayParameters['results']['clientId']);
        }
        
        return $arrayParameters['results'];
    }
    
    public function genderOrderRefernceCode()
    {
        $result = array(
            'success' => true,
        );
        
        $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
        
        do {
            $orderReferenceCode = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 10)), 0, 10);
            $order = $melisEcomOrderTable->getEntryByField('ord_reference', $orderReferenceCode)->current();
        }while(!empty($order));
        
        $result['code'] = $orderReferenceCode;
        
        $arrayParameters['results'] = $result;
        
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_generate_order_reference_code_end', $arrayParameters);
        
        $order = $melisEcomOrderTable->getEntryByField('ord_reference', $arrayParameters['results']['code'])->current();
        
        if (!empty($order))
        {
            $arrayParameters['results']['success'] = false;
            $arrayParameters['results']['error'] = 'Order referece code exist';
        }
        
        return $arrayParameters['results'];
    }
}