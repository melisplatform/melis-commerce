<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use Laminas\Session\Container;

/**
 *
 * This service handles the checkout system of MelisCommerce.
 *
 */
class MelisComOrderCheckoutService extends MelisComGeneralService
{
    public $siteId;
    
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }
    
    public function getSiteId()
    {
        return $this->siteId;
    }
    
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
        $melisComVariantService = $this->getServiceManager()->get('MelisComVariantService');
        // Get the basket from the BasketService
        $melisComBasketService = $this->getServiceManager()->get('MelisComBasketService');
        $clientBasket = $melisComBasketService->getBasket($arrayParameters['clientId']);
        
        $container = new Container('meliscommerce');
        $clientCountryId = (isset($container['checkout'][$this->siteId]['countryId'])) ? $container['checkout'][$this->siteId]['countryId'] : null;
        
        // Validation results handler
        $okVariant = array();
        $koVariant = array();
        
        if (!is_null($clientBasket))
        {
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
                                'error' => 'MELIS_COMMERCE_CHECKOUT_ERROR_BASKET_QUANTITY',
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
            if (empty($container['checkout'][$this->siteId]))
                $container['checkout'][$this->siteId] = array();
            $container['checkout'][$this->siteId]['addresses'] = $arrayParameters['results'];
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
     *                  'variantId1' => array('unit_price' => xx, 'quantity' => xx, 'discount' => xx, 'total_price' => xx),
     *                  'variantId2' => array('unit_price' => xx, 'quantity' => xx, 'discount' => xx, 'total_price' => xx),
     *                  'variantId3' => array('unit_price' => xx, 'quantity' => xx, 'discount' => xx, 'total_price' => xx),
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
        $melisComProductService = $this->getServiceManager()->get('MelisComProductService');
        $melisComVariantService = $this->getServiceManager()->get('MelisComVariantService');
        
        $melisComBasketService = $this->getServiceManager()->get('MelisComBasketService');
        $clientBasket = $melisComBasketService->getBasket($arrayParameters['clientId']);

        // computation of variants based on price_net and quantity
        
        $variantDetails = array();
        $errors = array();
        
        // Total Amount of the Basket
        $totalCost = 0;
        
        if (!is_null($clientBasket))
        {
            $container = new Container('meliscommerce');
            $clientCountryId = $container['checkout'][$this->siteId]['countryId'];

            // Check Client Group
            $melisComClientSrv = $this->getServiceManager()->get('MelisComClientService');
            $client = $melisComClientSrv->getClientById($arrayParameters['clientId']);
            $clientGroupId = $client->cli_group_id;
            
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
                    $variantPrice = $melisComVariantService->getVariantFinalPrice($variantId, $clientCountryId, $clientGroupId);
    
                    /**
                     * If the Variant Final price not set, this will try to get price from the product
                     * and use as Variant price
                     */
                    if (empty($variantPrice))
                    {
                        $variantPrice = $melisComProductService->getProductFinalPrice($productId, $clientCountryId, $clientGroupId);
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
                                'discount' => 0,
                                'total_price' => $varianTotalAmount,
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
                    
                    if (empty($errors) && !empty($variantPrice))
                    {
                        $variantDetails[$variantId]['price_details'] = array(
                            'currency_id' => $variantPrice->cur_id,
                            'currency_symbol' => $variantPrice->cur_symbol,
                            'currency_name' => $variantPrice->cur_name
                        );
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
        
        if ($arrayParameters['results']['costs']['order']['total'] < 0)
        {
            $arrayParameters['results']['costs']['order']['total'] = 0;
        }
    
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
        $arrayParameters['results']['costs']['total'] = ($totalCost > 0) ? $totalCost : 0;
        
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
        if (!empty($container['checkout'][$this->siteId]['addresses']))
            $addressesValidity = true;
            
        $costsResults = $this->computeAllCosts($arrayParameters['clientId']); 
        
        if (!empty($costsResults['success']) && $costsResults['success'] == true)
            $costsValidity = true;
        else
            $costsValidity = false;
        
        $orderReferenceCode = $this->generateOrderRefernceCode();
        
        $melisComOrderService = $this->getServiceManager()->get('MelisComOrderService');
        $melisComCouponService = $this->getServiceManager()->get('MelisComCouponService');
        $melisEcomCouponProductTable = $this->getServiceManager()->get('MelisEcomCouponProductTable');
        $melisEcomProductTable = $this->getServiceManager()->get('MelisEcomProductTable');
        
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
            $clientCountryId = $container['checkout'][$this->siteId]['countryId'];
            
            $billingAdd = $container['checkout'][$this->siteId]['addresses']['addresses']['billing']['address'];
            
            unset($billingAdd['cadd_client_id']);
            unset($billingAdd['cadd_client_person']);
            unset($billingAdd['cadd_address_name']);
            
            foreach ($billingAdd As $bKey => $bVal)
            {
                $newIndex = substr($bKey, 1);
                $billingAdd['o'.substr($bKey, 1)] = $bVal;
                unset($billingAdd[$bKey]);
            }
            
            if (!empty($billingAdd['oadd_id']))
            {
                unset($billingAdd['oadd_id']);
            }
            
            $billingAdd['oadd_creation_date'] = date('Y-m-d H:i:s');
            $billingAddress[] = $billingAdd;
            
            
            $deliveryAdd = $container['checkout'][$this->siteId]['addresses']['addresses']['delivery']['address'];
            
            unset($deliveryAdd['cadd_client_id']);
            unset($deliveryAdd['cadd_client_person']);
            unset($deliveryAdd['cadd_address_name']);
            
            foreach ($deliveryAdd As $bKey => $bVal)
            {
                $newIndex = substr($bKey, 1);
                $deliveryAdd['o'.substr($bKey, 1)] = $bVal;
                unset($deliveryAdd[$bKey]);
            }
            
            if (!empty($deliveryAdd['oadd_id']))
            {
                unset($deliveryAdd['oadd_id']);
            }
            
            $deliveryAdd['oadd_creation_date'] = date('Y-m-d H:i:s');
            $deliveryAddress[] = $deliveryAdd;
            
            $melisEcomClientPersonTable = $this->getServiceManager()->get('MelisEcomClientPersonTable');
            $clientMainPerson = $melisEcomClientPersonTable->getClientMainPersonByClientId($container['checkout'][$this->siteId]['clientId'])->current();
            
            $contactId = $container['checkout'][$this->siteId]['contactId'];
            $ordDeliveryMethod = (!empty($container['checkout'][$this->siteId]['deliveryMethod']) ? $container['checkout'][$this->siteId]['deliveryMethod'] : 'delivery');
            
            $order = array(
                'ord_client_id' => $container['checkout'][$this->siteId]['clientId'],
                'ord_client_person_id' => $contactId,
                'ord_status' => '-1',
                'ord_country_id' => $clientCountryId,
                'ord_reference' => $orderReferenceCode['code'],
                'ord_billing_address' => -1,
                'ord_delivery_address' => -1,
                'ord_delivery_method' => $ordDeliveryMethod,
                'ord_date_creation' => date('Y-m-d H:i:s')
            );
            
            // Getting Current Langauge ID from Melis Plugin langid
            $containerPlugin = new Container('melisplugins');
            $langId = $containerPlugin['melis-plugins-lang-id'];
            
            $melisComProductService = $this->getServiceManager()->get('MelisComProductService');
            $melisComVariantService = $this->getServiceManager()->get('MelisComVariantService');
            $melisComCategoryService = $this->getServiceManager()->get('MelisComCategoryService');
            $melisComBasketService = $this->getServiceManager()->get('MelisComBasketService');
            
            $clientBasket = $melisComBasketService->getBasket($container['checkout'][$this->siteId]['clientId']);
            
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
                
                // Check Client Group
                $melisComClientSrv = $this->getServiceManager()->get('MelisComClientService');
                $client = $melisComClientSrv->getClientById($arrayParameters['clientId']);
                $clientGroupId = $client->cli_group_id;

                $variantPrice = $melisComVariantService->getVariantFinalPrice($variant->var_id, $clientCountryId, $clientGroupId);
                
                if (empty($variantPrice))
                    $variantPrice = $melisComProductService->getProductFinalPrice($productId, $clientCountryId, $clientGroupId);
                
                $data = array(
                    'obas_id' => null,
                    'obas_variant_id' => $variant->var_id,
                    'obas_product_name' => $melisComProductService->getProductName($productId, $langId),
                    'obas_quantity' => $variantQty,
                    'obas_sent' => '0',
                    'obas_sku' => $variant->var_sku,
                    'obas_attributes' => $variantAttributesStr,
                    'obas_category_name' => $productCategoryName,
                    'obas_currency' => ($variantPrice->cur_id) ? $variantPrice->cur_id : '-1', //TODO update the currency using the API/ payment gateway
                    'obas_price_net' => $variantPrice->price_net,
                    'obas_price_gross' => $variantPrice->price_gross,
                    'obas_price_vat' => $variantPrice->price_vat_price,
                    // 'obas_price_vat_percent' => $variantPrice->price_vat_percent,
                    'obas_price_other_tax' => $variantPrice->price_other_tax_price,
                );
                
                array_push($basket, $data);
            }
            
            // Save new Order
            $orderId = $melisComOrderService->saveOrder($order, $basket, $billingAddress, $deliveryAddress);
            $orderBasket = $melisComOrderService->getOrderBasketByOrderId($orderId);
            
            // set order coupons
            if(!empty($container['checkout'][$this->getSiteId()]['coupons']['productCoupons'])){
                
                $productCoupons = $container['checkout'][$this->getSiteId()]['coupons']['productCoupons'];
               
                foreach($productCoupons as $key => $val){
                    
                    $data = $melisEcomCouponProductTable->getEntryByField('cprod_coupon_id', $key)->toArray();
                    $coupon = $melisComCouponService->getCouponById($key)->getCoupon()->getArrayCopy();
                    $usableLimit = $coupon['coup_max_use_number'] - $coupon['coup_current_use_number'];
                    foreach($orderBasket as $basket){
                        
                        $product = $melisEcomProductTable->getProductByVariantId($basket->obas_variant_id)->current()->getArrayCopy();

                        $pos = array_search($product['prd_id'], array_column($data, 'cprod_product_id'));
                        
                        if(is_int($pos)){
                            
                            
                            $quantityUsed = (($usableLimit - $basket->obas_quantity) >= 0 )? $basket->obas_quantity : $usableLimit;
                            
                            if($quantityUsed > 0){
                                $couponOrderData = array(
                                    'cord_coupon_id' => $key,
                                    'cord_order_id' => $orderId,
                                    'cord_basket_id' => $basket->obas_id,
                                    'cord_quantity_used' =>  $quantityUsed,
                                );
                                
                                $melisComCouponService->saveCouponOrder($couponOrderData);
                                $usableLimit -= $quantityUsed;
                            }
                            
                        }
                    }
                }
            }
            
            if(!empty($container['checkout'][$this->getSiteId()]['coupons']['generalCoupons'])){
                $generalCoupons = $container['checkout'][$this->getSiteId()]['coupons']['generalCoupons'];
                foreach($generalCoupons as $key => $val){
                    $couponOrderData = array(
                        'cord_coupon_id' => $key,
                        'cord_order_id' => $orderId,
                        'cord_quantity_used' =>  1,
                    );
                    $melisComCouponService->saveCouponOrder($couponOrderData);
                }
            }
            // Set Order Id to Session
            $container['checkout'][$this->siteId]['orderId'] = $orderId;
            
            // Unset Checkout Addresses on Container
            unset($container['checkout'][$this->siteId]['addresses']);
            
            // Adding Order Id to result
            $results['orderId'] = $orderId;
            
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
            
            if ($addressesValidity != true)
            {
                $results['errors']['addresses'] = array(
                    'invalidAddress' => 'Something is wrong with the Checkout Addresses, Please check Addresses for Checkout before proceed to payment'
                );
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
            $melisEcomOrderTable = $this->getServiceManager()->get('MelisEcomOrderTable');
            $orderDetails = $melisEcomOrderTable->getEntryById($arrayParameters['results']['orderId'])->current();

            // Checking if the Order is existing
            // Just to be sure that the Order Id is existing on Order records
            if (!empty($orderDetails))
            {
                // Checking if the order is still new entry/temporary status
                // To avoid changing the status after payment
                if ($orderDetails->ord_status == -1)
                {
                    // We have a validated payment and the orderId has been retrieved
                    $orderId = $arrayParameters['results']['orderId'];
                    // one last check: full price must be equal to price paid, if not save order with error status
                    // else proceed with finalizing order: orderPayment, and update status to new order on order table
                    
                    $clientId = $arrayParameters['results']['clientId'];
                    $order = $this->computeOrderTotalCosts($orderId);

                    $totalCost = $order['costs']['total'];

                    if (bccomp($totalCost, $arrayParameters['results']['payment_details']['transactionPricePaid'], 3) == 0)
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
                    
                    /**
                     * Retreiving Payment Type Id
                     */
                    $paymentTypeTbl = $this->getServiceManager()->get('MelisEcomOrderPaymentTypeTable');
                    $paymentType = $paymentTypeTbl->getEntryByField('opty_code', $paymentData['paymentType'])->current();
                    $paymentTypeId = null;
                    if (!empty($paymentType))
                    {
                        $paymentTypeId = $paymentType->opty_id;
                    }
                    
                    /**
                     * Retrieving the Currency of the payment 
                     * using the Currency code return by third party payment gateway
                     */
                    $melisEcomCurrencyTable = $this->getServiceManager()->get('MelisEcomCurrencyTable');
                    $currency = $melisEcomCurrencyTable->getEntryByField('cur_code', $paymentData['transactionCourrencyCode'])->current();
                    
                    $currencyId = '-1';
                    if (!empty($currency)){
                        $currencyId = $currency->cur_id;
                    }

                    $payment = array(
                        'opay_order_id' => $orderId,
                        'opay_price_total' => $totalCost,
                        'opay_price_order' => $order['costs']['order']['totalWithoutCoupon'],
                        'opay_price_shipping' => $order['costs']['shipment']['total'],
                        'opay_currency_id' => $currencyId,
                        'opay_payment_type_id' => $paymentTypeId,
                        'opay_transac_id' => $paymentData['transactionId'],
                        'opay_transac_return_value' => $paymentData['transactionReturnCode'],
                        'opay_transac_price_paid_confirm' => $paymentData['transactionPricepaidConfirm'],
                        'opay_transac_raw_response' => $paymentData['transactionFullRawResponse'],
                        'opay_date_payment' => date('Y-m-d H:i:s'),
                    );

                    $clientCountryId = $paymentData['transactionCountryId'];
                    
                    // Unset data not requried to return as results
                    unset($arrayParameters['results']['payment_details']['transactionPricepaidConfirm']);
                    unset($arrayParameters['results']['payment_details']['transactionCountryId']);
                    
                    $melisEcomOrderPaymentTable = $this->getServiceManager()->get('MelisEcomOrderPaymentTable');
                    $melisEcomOrderPaymentTable->save($payment);
                    
                    // Save order
                    $melisEcomOrderTable->save($orderData, $arrayParameters['results']['orderId']);
                    
                    // Deduct Quantity of the Product/Variant
                    $melisComBasketService = $this->getServiceManager()->get('MelisComBasketService');
                    $clientBasket = $melisComBasketService->getBasket($arrayParameters['results']['clientId']);
                    
                    $melisComVariantService = $this->getServiceManager()->get('MelisComVariantService');
                    $melisEcomVariantStockTable = $this->getServiceManager()->get('MelisEcomVariantStockTable');
                    
                    if (!is_null($clientBasket))
                    {
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
                    }
                    
                    // Use update coupon to used
                    $couponOrderTable = $this->getServiceManager()->get('MelisEcomCouponOrderTable');
                    $usedOrderCoupons = $couponOrderTable->getEntryByField('cord_order_id', $orderId);
                    $couponSrv = $this->getServiceManager()->get('MelisComCouponService');
                    
                    foreach($usedOrderCoupons as $orderCoupon){
                        $couponEntity = $couponSrv->getCouponById($orderCoupon->cord_coupon_id);
                        $quantityUsed = $couponEntity->getCoupon()->coup_current_use_number + $orderCoupon->cord_quantity_used;
                        
                        $couponSrv->saveCoupon(array('coup_current_use_number' => $quantityUsed), $couponEntity->getId());
                        $couponOrderTable->save(array('cord_status' => 1), $orderCoupon->cord_id);
                    }
                    
                    // Empty Client Basket
                    $melisComBasketService = $this->getServiceManager()->get('MelisComBasketService');
                    $melisComBasketService->emptyBasket($arrayParameters['results']['clientId']);
                }
            }
        }
        
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_step2_postpayment_proccess_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This method will generate Preference Code for Order Preference
     * this will aslo validate reference code if the event modefied the pre-generated reference code
     * 
     * @return Array
     */
    public function generateOrderRefernceCode()
    {
        $result = array(
            'success' => true,
        );
        
        $melisEcomOrderTable = $this->getServiceManager()->get('MelisEcomOrderTable');
        
        do {
            $orderReferenceCode = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 10)), 0, 10);
            $order = $melisEcomOrderTable->getEntryByField('ord_reference', $orderReferenceCode)->current();
        }while(!empty($order));
        
        $result['code'] = $orderReferenceCode;
        
        $arrayParameters['results'] = $result;
        
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_generate_order_reference_code_end', $arrayParameters);
        
        // Checking if Referece code is available
        $order = $melisEcomOrderTable->getEntryByField('ord_reference', $arrayParameters['results']['code'])->current();
        
        if (!empty($order))
        {
            $arrayParameters['results']['success'] = false;
            $arrayParameters['results']['error'] = 'Order referece code exist';
        }
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service will compute the full post order price, with all costs
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
     * @param int $orderId
     * @return array[]
     *
     */
    public function computeOrderTotalCosts($orderId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_order_total_costs_computation_start', $arrayParameters);
    
        $success = false;
    
        $results = array(
            'success' => $success,
            'orderId' => $arrayParameters['orderId'],
            'costs' => array(
                'total' => 0,
            )
        );
    
        $orderCosts = $this->computePostOrderCost($arrayParameters['orderId']);
        $shipmentCosts = $this->computePostShipmentCost($arrayParameters['orderId']);
    
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
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_order_total_costs_computation_end', $arrayParameters);
    
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
        $arrayParameters['results']['costs']['total'] = ($totalCost > 0) ? $totalCost : 0;
    
        return $arrayParameters['results'];
    }
    
    /**
     * This service will compute the post order price
     *
     * Computing order by default will calculate based on quantity in the basket and price_net of variant
     * Therefore, any custom computation should attach the meliscommerce_service_checkout_post_order_computation_end event
     * to add custom computation depending on your rules / region / country (taxes...)
     *
     * Return value structure:
     * array(
     *      'success' => true/false
     *      'orderId' => xx,
     *      'costs' => array(
     *          'order' => array(
     *              'totalWithoutCoupon' => xx,
     *              'total' => xx,
     *              'details' => array(
     *                  'variantId1' => array('unit_price' => xx, 'quantity' => xx, 'discount' => xx, 'total_price' => xx),
     *                  'variantId2' => array('unit_price' => xx, 'quantity' => xx, 'discount' => xx, 'total_price' => xx),
     *                  'variantId3' => array('unit_price' => xx, 'quantity' => xx, 'discount' => xx, 'total_price' => xx),
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
    public function computePostOrderCost($orderId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // No tests by default in Melis Platform, return will always be ok if no listener attached to events
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_post_order_computation_start', $arrayParameters);
        
        $results = array(
            'success' => false,
            'orderId' => $arrayParameters['orderId'],
            'costs' => array(
                'order' => array(
                    'totalWithoutCoupon' => 0,
                    'total' => 0,
                ),
            ),
        );
        
        // Order service manager
        $orderService = $this->getServiceManager()->get('MelisComOrderService');
        $basket = $orderService->getOrderBasketByOrderId($arrayParameters['orderId']);

        // computation of variants based on price_net and quantity
        
        $variantDetails = array();
        $errors = array();
        
        // Total Amount of the Basket
        $totalCost = 0;
        
        if (!is_null($basket))
        {
            foreach($basket as $item){
                $variantDetails[$item->obas_variant_id] = array(
                    'unit_price' => $item->obas_price_net,
                    'quantity' => $item->obas_quantity,
                    'discount' => 0,
                    'total_price' => $item->obas_quantity * $item->obas_price_net,
                );
                $totalCost += $item->obas_quantity * $item->obas_price_net;
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
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_post_order_computation_end', $arrayParameters);

        if ($arrayParameters['results']['costs']['order']['total'] < 0)
        {
            $arrayParameters['results']['costs']['order']['total'] = 0;
        }
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service will compute the post shipment price
     *
     * Computing shipment by default doesn't do anything.
     * Therefore, any custom shipment should attach the meliscommerce_service_checkout_post_shipment_computation_end event
     * to add custom computation depending on the carriers.
     *
     * Return value structure:
     * array(
     *      'success' => true/false
     *      'orderId' => xx,
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
    public function computePostShipmentCost($orderId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // No tests by default in Melis Platform, return will always be ok if no listener attached to events
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_post_shipment_computation_start', $arrayParameters);
        
        $results = array(
            'success' => true,
            'clientId' => $arrayParameters['orderId'],
            'costs' => array(
                'shipment' => array(
                    'total' => 0,
                ),
            ),
        );
        
        $orderService = $this->getServiceManager()->get('MelisComOrderService');
        
        $shipments = $orderService->getOrderShippingByOrderId($arrayParameters['orderId']);
        $shippingDetails = array();
        
        if(!empty($shipments)){
            
            foreach($shipments as $ship){
                $shippingDetails[] = array(
                    'oship_id' => $ship->oship_id,
                    'oship_order_id' => $ship->oship_order_id,
                    'oship_tracking_code' => $ship->oship_tracking_code,
                    'oship_content' => $ship->oship_content,
                    'oship_date_sent' => $ship->oship_date_sent,
                );    
            }
        }
        
        $results['costs']['shipment']['details'] = $shippingDetails;
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_post_shipment_computation_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This service will validate coupons

     * Returned value structure: 
     * array(
     *   'success' => boolean,
     *   'error' => array(),
     *   'coupon' => array(),
     *   'type' => string,
     *);
     * 
     * @param string $couponCode
     * @param int $clientId
     * @param array $productIds
     * @return array
     */
    public function validateCoupon($couponCode, $clientId = null, $productIds = array())
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // No tests by default in Melis Platform, return will always be ok if no listener attached to events
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_coupon_validation_start', $arrayParameters);
        
        $results = array(
            'success' => false,
            'error' => array(),
            'coupon' => array(),
            'type' => null,
        );
        
        $coupon = array();
        $prodCoupons = array();
        
        $couponTable = $this->getServiceManager()->get('MelisEcomCouponTable');
        $couponProdTable = $this->getServiceManager()->get('MelisEcomCouponProductTable');
        
        if(!empty($arrayParameters['couponCode'])){
            
            $coupon = $couponTable->getEntryByField('coup_code', $arrayParameters['couponCode'])->current();
        }
        
        if(!empty($coupon)){
            
            //validation process
            if($coupon->coup_status){
                
                //validate coupon date
                $dateValid = false;
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $validStart = ($coupon->coup_date_valid_start) ? strtotime($coupon->coup_date_valid_start) : null;
                $validEnd = ($coupon->coup_date_valid_end) ? strtotime($coupon->coup_date_valid_end) : null;
                
                if (!is_null($validStart)&& is_null($validEnd)){
                    
                    if ($validStart <= $currentDate){
                        
                        $dateValid = true;
                    }
                }
                elseif (is_null($validStart)&& !is_null($validEnd)){
                    
                    if ($validEnd >= $currentDate){
                        
                        $dateValid = true;
                    }
                }
                elseif (!is_null($validStart)&& !is_null($validEnd)){
                    
                    if ($validStart <= $currentDate && $validEnd >= $currentDate){
                        
                        $dateValid = true;
                    }
                }else{
                    // Else date are empty
                    $dateValid = true;
                }
                
                if ($dateValid){
                    
                    if ($coupon->coup_current_use_number < $coupon->coup_max_use_number)
                    {
                        // Result success
                        $results['success'] = true;
                        
                        // validate if coupon is assigned to client
                        if(!is_null($arrayParameters['clientId'])){
                            
                            //check coupon type if assigned type
                            if ($coupon->coup_type == '1'){
                                
                                // Checking if Client is assigned to this couponId
                                $melisEcomCouponClientTable = $this->getServiceManager()->get('MelisEcomCouponClientTable');
                                $couponClient = $melisEcomCouponClientTable->checkCouponClientExist($coupon->coup_id, $arrayParameters['clientId']);
                            
                                if (empty($couponClient->current())){
                                    
                                    // Coupon is not assigned to the selected client
                                    $results['error'] = 'MELIS_COMMERCE_COUPON_CLIENT_NOT_ASSIGN';
                                    $results['success'] = false;
                                }
                            }
                        }
                        
                        if(empty($results['error'])){
                            
                            //identify coupon type
                            if($coupon->coup_product_assign){
                                
                                // product coupons
                                foreach($arrayParameters['productIds'] as $productId){
                                    
                                    $cliId = ($coupon->coup_type == '1')? $arrayParameters['clientId'] : null;
                                    $couponProd = $couponProdTable->checkCouponProductExist($coupon->coup_id, $cliId, $productId);
                                    
                                    if(!empty($couponProd->current())){
                                        $prodCoupons = $coupon;
                                        $results['coupon'] = $prodCoupons;
                                    }
                                }
                                
                                if(empty($prodCoupons)){
                            
                                    $results['error'] = 'MELIS_COMMERCE_COUPON_PRODUCT_NOT_ASSIGN';
                                    $results['success'] = false;
                                }else{
                                    
                                    $results['type'] = 'product'; 
                                    $results['success'] = true;
                                }
                            
                            }else{
                            
                                // general coupons
                                $results['coupon'] = $coupon;
                                $results['type'] =  'general';
                                $results['success'] = true;
                            }
                        }
                    }
                    else
                    {
                        // Number of coupon used had reached the Limit
                        $results['error'] = 'MELIS_COMMERCE_COUPON_REACHED_LIMIT';
                        $results['success'] = false;
                    }
                    
                }else{
                    
                    // Coupon date validity is not match to the current date, otherwise coupon is not yet started or coupon was expired
                    $results['error'] = 'MELIS_COMMERCE_COUPON_DATE_VALIDITY_INVALID';
                }
            }else{
                
                // Coupon is deactivated/Coupon status is not Active status
                $results['error'] = 'MELIS_COMMERCE_COUPON_NOT_ACTIVE';
            }
        }else{
            
            // Coupon not found from database/Coupon is not existing
            $results['error'] = 'MELIS_COMMERCE_COUPON_NOT_FOUND';
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_coupon_validation_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
}
