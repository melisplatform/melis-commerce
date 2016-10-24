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
 * This service handles the client system of MelisCommerce.
 *
 */
class MelisComOrderService extends MelisComGeneralService
{
	/**
	 * 
	 * This method gets all orders.
	 * Orders datas will have: order, order status, payment and payment type, basket, 
	 * client account and person id who made the order
	 * 
	 * @param int $clientId If specified, it will bring back orders from this client account
	 * @param int $clientPersonId If specified, it will bring back orders from this client person
	 * @param string $reference If specified, it will bring back orders matching the reference with a like "ref%"
	 * @param DateTime $dateCreationMin If specified, only orders starting this date will be sent back
	 * @param DateTime $dateCreationMax If specified, only orders before this date will be sent back
	 * @param int $status if true, returns only orders with specific status
	 * @param int $start If not specified, it will start at the begining of the list
	 * @param int $limit If not specified, it will bring all categories of the list
	 * 
	 * @return MelisOrder[] Array of Order objects
	 */
	public function getOrderList($langId = null, $clientId = null, $clientPersonId = null, 
                                 $couponId = null, $reference = null, $dateCreationMin = null,
                                 $dateCreationMax = null, $status = null, $start = 0, 
	                             $limit = null, $colOrder = null, $search = '')
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_list_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
	    
        $orderData = $melisEcomOrderTable->getOrderList($arrayParameters['clientId'], $arrayParameters['clientPersonId'], 
                                                       $arrayParameters['couponId'], $arrayParameters['reference'],
                                                       $arrayParameters['dateCreationMin'], $arrayParameters['dateCreationMax'],
                                                       $arrayParameters['status'], $arrayParameters['start'], $arrayParameters['limit'], 
                                                       $arrayParameters['colOrder'], $arrayParameters['search'] )->toArray();
	    foreach ($orderData As $key => $val)
	    {
	        $melisOrder = $this->getOrderById($val['ord_id']);
	        array_push($results, $melisOrder);
	    }
	    // Service implementation end
	    
        // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;	    
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_list_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets an order by its id
	 * Orders datas will have: order, order status, addresses, payment and payment type, basket, 
	 * client account and person id who made the order
	 *
	 * @param int $orderId The order id
	 * @param int $langId the lang id
	 * @return MelisOrder|null Order object
	 */
	public function getOrderById($orderId, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_byid_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
	    $melisEcomOrderShippingTable = $this->getServiceLocator()->get('MelisEcomOrderShippingTable');	    
	    $melisEcomClientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
	    $melisEcomClientSvc = $this->getServiceLocator()->get('MelisComClientService');
	    
	    $melisOrder = new \MelisCommerce\Entity\MelisOrder();
	    
	    $orderData = $melisEcomOrderTable->getEntryById($arrayParameters['orderId']);
	    
	    if (!empty($orderData))
	    {
	        $order = $orderData->current();
	        
	        if (!empty($order))
	        {
	            $orderId = $order->ord_id;
	            $orderClientId = $order->ord_client_id;
	            $orderPersonId = $order->ord_client_person_id;
	            
	            $melisOrder->setId($orderId);
	            $melisOrder->setOrder($order);
	            
	            // Get Order Addresses
	            $orderAddresses = $this->getOrderAddressesByOrderId($orderId, $arrayParameters['langId']);	            
	            $melisOrder->setAddresses($orderAddresses);
	            
	            // Get Order Client
	            $orderClient = $melisEcomClientTable->getEntryById($orderClientId)->current();
	            $melisOrder->setClient($orderClient);
	            
	            // Get Order Person
	            $orderPerson = $melisEcomClientSvc->getClientPersonById($orderPersonId)->getPerson();
	            $melisOrder->setPerson($orderPerson);
	            
	            // Get Order Basket
	            $orderBasket = $this->getOrderBasketByOrderId($orderId);
	            $melisOrder->setBasket($orderBasket);
	            
	            // Get Order Payment
	            $orderPayment = $this->getOrderPaymentByOrderId($orderId);
	            
	            $melisOrder->setPayment($orderPayment);
	            
	            // Get Order Shipping
	            $orderShipping = $this->getOrderShippingByOrderId($orderId);
	            $melisOrder->setShipping($orderShipping);
	            
	            //Get Order Messages
	            $orderMessages = $this->getOrderMessageByOrderId($orderId);
	            $melisOrder->setMessages($orderMessages);
	        }
	    }
	    $results = $melisOrder;
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_byid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the status of an order
	 * Comes with translations
	 *
	 * @param int $orderId The order id
	 * @param int $langId If specified, it will send back only the translations in this language
	 *
	 * @return MelisEcomOrderStatus[]|null Order status object
	 */
	public function getOrderStatusByOrderId($orderId, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_byorderid_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
	    foreach($melisEcomOrderTable->getOrderStatusByOrderId($arrayParameters['orderId'], $arrayParameters['langId']) as $status){
	        $results[] = $status;
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_byorderid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the addresses of an order
	 *
	 * @param int $orderId The order id
	 * $param int $langId the language id
	 * @return MelisEcomOrderAddress[] Array of Order address objects
	 */
	public function getOrderAddressesByOrderId($orderId, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_addresses_byorderid_start', $arrayParameters);
	    
	    // Service implementation start
	    $addressTrans = array();
	    $civilityTrans = array();	    
	    $melisEcomOrderAddressTable = $this->getServiceLocator()->get('MelisEcomOrderAddressTable');
	    $melisEcomCivilityTransTable = $this->getServiceLocator()->get('MelisEcomCivilityTransTable');
	    $melisEcomClientAddressTypeTransTable = $this->getServiceLocator()->get('MelisEcomClientAddressTypeTransTable');
	    foreach($melisEcomOrderAddressTable->getOrderAddressesByOrderId($arrayParameters['orderId']) as $address){
	        foreach($melisEcomClientAddressTypeTransTable->getAddressTransByAddressTypeIdAndLangId($address->oadd_type, $arrayParameters['langId']) as $trans){
	            $addressTrans[] = $trans;
	        }
	        $address->{'address_trans'} = $addressTrans;
	        foreach($melisEcomCivilityTransTable->getCivilityTransByCivilityId($address->oadd_civility, $arrayParameters['langId']) as $trans){
	            $civilityTrans[] = $trans;
	        }
	        $address->{'civility_trans'} = $civilityTrans;
	        $results[] = $address;
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_addresses_byorderid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the basket of an order
	 *
	 * @param int $orderId The order id
	 *
	 * @return MelisEcomOrderBasket[] Array of Order basket objects
	 */
	public function getOrderBasketByOrderId($orderId, $start = 0, $limit = null, $search = '', $order = 'obas_id ASC')
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_basket_byorderid_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderBasketTable = $this->getServiceLocator()->get('MelisEcomOrderBasketTable');

	    foreach ($melisEcomOrderBasketTable->getOrderBaskets($arrayParameters['orderId'], $arrayParameters['start'], $arrayParameters['limit'], $arrayParameters['search'], $arrayParameters['order']) as $basket){
	        $results[] = $basket;
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_basket_byorderid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the shipping informations of an order
	 * Results are ordered by creation date
	 *
	 * @param int $orderId The order id
	 *
	 * @return MelisEcomOrderShipping[] Array of Order shipping objects
	 */
	public function getOrderShippingByOrderId($orderId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_shipping_byorderid_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderShippingTable = $this->getServiceLocator()->get('MelisEcomOrderShippingTable');
	    foreach($melisEcomOrderShippingTable->getOrderShippingByOrderId($arrayParameters['orderId']) as $shipping){
	        $results[] =  $shipping;
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_shipping_byorderid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the messages of an order
	 * Results are ordered by creation date
	 *
	 * @param int $orderId The order id
	 *
	 * @return MelisEcomOrderMessage[] Array of Order message objects
	 */
	public function getOrderMessageByOrderId($orderId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_message_byorderid_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderMessageTable = $this->getServiceLocator()->get('MelisEcomOrderMessageTable');
	    foreach($melisEcomOrderMessageTable->getOrderMessageByOrderId($arrayParameters['orderId']) as $message){
	        $results[] = $message;
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_message_byorderid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the payments of an order
	 * Results are ordered by creation date
	 *
	 * @param int $orderId The order id
	 *
	 * @return MelisEcomOrderPayment[] Array of Order payment objects
	 */
	public function getOrderPaymentByOrderId($orderId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_payment_byorderid_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderPaymentTable = $this->getServiceLocator()->get('MelisEcomOrderPaymentTable');
	    $melisEcomOrderPaymentTypeTable = $this->getServiceLocator()->get('MelisEcomOrderPaymentTypeTable');
	    foreach($melisEcomOrderPaymentTable->getEntryByField('opay_order_id', $arrayParameters['orderId']) as  $payment){
	        $payment->payment_type = $melisEcomOrderPaymentTypeTable->getEntryByField('opty_id', $payment->opay_payment_type_id)->current();
	        $results[] = $payment;
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_payment_byorderid_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the list of order status and their translations
	 *
	 * @param int $langId If specified, it will send back only the translations in this language
	 *
	 * @return MelisEcomOrderStatus[] Array of Order status objects
	 */
	public function getOrderStatusList($langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_list_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderStatusTable = $this->getServiceLocator()->get('MelisEcomOrderStatusTable');
	    $results = $melisEcomOrderStatusTable->getOrderStatusListByLangId($arrayParameters['langId']);
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_list_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method gets the list of order payments types
	 *
	 * @return MelisEcomOrderPaymentType[] Array of Order payment type objects
	 */
	public function getOrderPaymentTypeList()
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_paymenttype_list_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderPaymentTypeTable = $this->getServiceLocator()->get('MelisEcomOrderPaymentTypeTable');
	    $results = $melisEcomOrderPaymentTypeTable->fetchAll();
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_paymenttype_list_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves an order in database.
	 *
	 * @param array $order Reflects the melis_ecom_order table
	 * @param array[] $basket Array of oreder basket item reflecting the melis_ecom_order_basket table, items will be added in update
	 * @param array $deliveryAddress Address reflecting the melis_ecom_order_address table, items will replace existent in update
	 * @param array $billingAddress Address reflecting the melis_ecom_order_address table, items will replace existent in update, if not provided billing will be copied from delivery
	 * @param array $payment Reflects the melis_ecom_order_payment table, items will be added in update
	 * $param array $shipping Reflects the melis_ecom_order_shippign table, items will be added in update
	 * @param int $orderId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The order id created or updated, null if an error occured
	 */
	public function saveOrder($order, $basket = array(), $billingAddress = array(), $deliveryAddress = array(), 
	                          $payment = array(), $shipping = array(), $orderId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_save_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
	    $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
	    
	    if (!empty($order)&&is_array($order))
	    {
	        try
	        {
	            $results['ord_id'] = $melisEcomOrderTable->save($arrayParameters['order'], $arrayParameters['orderId']);
	        }
	        catch(\Exception $e){
	            
	        }
	    }
// 	    var_dump($arrayParameters['basket']);die();
	    if ($results['ord_id'])
	    {
	        
	        $basketData = $arrayParameters['basket'];
	        if(!empty($basketData)){
    	        foreach ($basketData As $key => $val)
    	        {
    	            $obasId = ($val['obas_id']) ? $val['obas_id'] : null;
    	            unset($basketData[$key]['obas_id']);
    	            $basketData[$key]['obas_order_id'] = $results['ord_id'];
    	            $results['obas_id'] = $this->saveOrderBasket($basketData[$key], $obasId);
    	            
    	        }
	        }
	        $billingAddressData = $arrayParameters['billingAddress'];
	        if(!empty($billingAddressData)){
    	        foreach ($billingAddressData As $key => $val)
    	        {
    	           $oaddId = ($val['oadd_id']) ? $val['oadd_id'] : null;
    	            unset($billingAddressData[$key]['oadd_id']);
    	            $billingAddressData[$key]['oadd_order_id'] = $results['ord_id'];
    	            $addType = 1; // Billing Id of the Address
    	            $result['oadd_id'] = $this->saveOrderAddress($billingAddressData[$key], $addType, $oaddId);
    	            
    	        }
	        }
	        $deliveryAddressData = (!empty($arrayParameters['deliveryAddress'])) ? $arrayParameters['deliveryAddress'] : $billingAddressData;
	        if(!empty($deliveryAddressData)){
    	        foreach ($deliveryAddressData As $key => $val)
    	        {
    	            $oaddId = ($val['oadd_id']) ? $val['oadd_id'] : null;
    	            unset($deliveryAddressData[$key]['oadd_id']);
    	            $deliveryAddressData[$key]['oadd_order_id'] = $results['ord_id'];
    	            $addType = 2; // Delivery Id of the Address
    	            $result['oadd_id'] = $this->saveOrderAddress($deliveryAddressData[$key], $addType, $oaddId);
    	        }
	        }
	        $paymentData = $arrayParameters['payment'];
	        if(!empty($paymentData)){
    	        foreach ($paymentData As $key => $val)
    	        {
    	            $opayId = ($val['opay_id']) ? $val['opay_id'] : null;
    	            unset($paymentData[$key]['opay_id']);
    	            $paymentData[$key]['opay_order_id'] = $results['ord_id'];
    	            $result['opay_id'] = $this->saveOrderPayment($paymentData[$key], $opayId);	            
    	        }
	        }
	        $shippingData = $arrayParameters['shipping'];
	        if(!empty($shippingData)){
	            foreach ($shippingData as $key => $val){
	                $oship_id = ($val['oship_id']) ? $val['oship_id'] : null;
	                unset($shippingData[$key]['oship_id']);
	                $shippingData[$key]['oship_order_id'] = $results['ord_id'];
	                $result['oship_id'] = $this->saveOrderShipping($shippingData[$key], $oship_id);
	            }
	        }
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_save_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves an order address in database.
	 * The type given will update the relation for delivery or billing address.
	 * Previous address will be deleted an replaced by the one given
	 *
	 * @param array $address Address reflecting the melis_ecom_order_address table
	 * @param int $type Billing/Delivery, 1/2
	 * @param int $addressId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The client person id created or updated, null if an error occured
	 */
	public function saveOrderAddress($address, $type, $addressId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_address_save_start', $arrayParameters);
         
	    // Service implementation start
	    $melisEcomOrderAddressTable = $this->getServiceLocator()->get('MelisEcomOrderAddressTable');
	    try
	    {
	        if (!is_null($arrayParameters['addressId']))
	        {
	            // Delete Current Order Address using Order Address Id
	            $melisEcomOrderAddressTable->deleteById($arrayParameters['addressId']);
	        }
	        
	        // Add Type Address to Address Data
	        $arrayParameters['address']['oadd_type'] = $arrayParameters['type'];
	        
	        $oaddId = $melisEcomOrderAddressTable->save($arrayParameters['address']);
	        $results = $oaddId;
	    }
	    catch(\Exception $e){	        
	        echo $e->getMessage();
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_address_save_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves or updates an order payment in database.
	 *
	 * @param array $payment Payment reflecting the melis_ecom_order_payment table
	 * @param int $paymentId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The payment id created or updated, null if an error occured
	 */
	public function saveOrderPayment($payment, $paymentId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_payment_save_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderPaymentTypeTable = $this->getServiceLocator()->get('MelisEcomOrderPaymentTypeTable');
	    try
	    {
	        $opayId = $melisEcomOrderPaymentTypeTable->save($arrayParameters['payment'], $arrayParameters['paymentId']);
	        $results = $opayId;
	    }
	    catch(\Exception $e){}
	    
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_payment_save_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves or updates an order payment in database.
	 *
	 * @param array $basket Basket reflecting the melis_ecom_order_basket table
	 * @param int $basketId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The basket id created or updated, null if an error occured
	 */
	public function saveOrderBasket($basket, $basketId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_basket_save_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderBasketTable = $this->getServiceLocator()->get('MelisEcomOrderBasketTable');
	    try
	    {
	        $obasId = $melisEcomOrderBasketTable->save($arrayParameters['basket'], $arrayParameters['basketId']);
	        $results = $obasId;
	    }
	    catch(\Exception $e){}
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_basket_save_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves or updates an order shipping in database.
	 *
	 * @param array $shipping Basket reflecting the melis_ecom_order_basket table
	 * @param int $shippingId If specified, an update will be done instead of an insert
	 *
	 * @return int|null The shipping id created or updated, null if an error occured
	 */
	public function saveOrderShipping($shipping, $shippingId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_shipping_save_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderShippingTable = $this->getServiceLocator()->get('MelisEcomOrderShippingTable');
	    try
	    {
	        $oshipId = $melisEcomOrderShippingTable->save($arrayParameters['shipping'], $arrayParameters['shippingId']);
	        $results = $oshipId;
	    }
	    catch(\Exception $e){
	        echo $e->getMessage();
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_shipping_save_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method changes the status of an order
	 *
	 * @param int $newStatusId New status id to change
	 * @param int $orderId Order Id to update
	 *
	 * @return int|null The order id updated, null if an error occured
	 */
	public function updateOrderStatusByOrderId($newStatusId, $orderId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_orderstatus_byorderid_update_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
	    try
	    {
	        $orderStatus = array('ord_status' => $arrayParameters['newStatusId']);
	        $orderId = $melisEcomOrderTable->save($orderStatus, $arrayParameters['newStatusId']);
	        $results = $orderId;
	    }
	    catch(\Exception $e){}
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_orderstatus_byorderid_update_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves or updates an order status in database.
	 *
	 * @param array $orderStatus Status reflecting the melis_ecom_order_status table
	 * @param array $orderStatusTranslations[] Status reflecting the melis_ecom_order_status_trans table, it will add or update existing, but won't delete others
	 * @param int $orderStatusId Order status Id to update
	 *
	 * @return int|null The oreder status id created or updated, null if an error occured
	 */
	public function saveOrderStatus($orderStatus, $orderStatusTranslations, $orderStatusId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_save_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderStatusTable = $this->getServiceLocator()->get('MelisEcomOrderStatusTable');
	    $melisEcomOrderStatusTransTable = $this->getServiceLocator()->get('MelisEcomOrderStatusTransTable');
	    try
	    {
	        $ostaId = $melisEcomOrderStatusTable->save($arrayParameters['orderStatus'], $arrayParameters['newStatusId']);
	        
	        $statusTransData = $arrayParameters['orderStatusTranslations'];
	        $statusTransData['ostt_status_id'] = $ostaId;
	        
	        $statusTrans = $melisEcomOrderStatusTransTable->getEntryByField('ostt_status_id', $ostaId)->current();
	        $osttId = (!empty($statusTrans)) ? $statusTrans->ostt_id : null;
	        
            $melisEcomOrderStatusTransTable->save($statusTransData, $osttId);
	        
	        $results = $ostaId;
	    }
	    catch(\Exception $e){}
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_save_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves or updates an order payment type in database.
	 *
	 * @param array $paymentType Payment type reflecting the melis_ecom_order_payment_type table
	 * @param int $orderPaymentTypeId Order payment type Id to update
	 *
	 * @return int|null The payment type id created or updated, null if an error occured
	 */
	public function saveOrderPaymentType($paymentType, $orderPaymentTypeId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_paymenttype_save_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderPaymentTypeTable = $this->getServiceLocator()->get('MelisEcomOrderPaymentTypeTable');
	    try
	    {
	        $optyId = $melisEcomOrderPaymentTypeTable->save($arrayParameters['paymentType'], $arrayParameters['orderPaymentTypeId']);
	        $results = $optyId;
	    }
	    catch(\Exception $e){}
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_paymenttype_save_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	/**
	 *
	 * This method saves or updates an order message.
	 *
	 * @param array $orderMessage message reflecting the meslis_ecom_order_message table
	 * @param int $orderMessageId order message Id to update
	 *
	 * @return int|null The order type id created or updated, null if an error occured
	 */
	public function saveOrderMessage($orderMessage, $orderMessageId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	     
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_message_save_start', $arrayParameters);
	     
	    // Service implementation start
	    $orderMessageTable = $this->getServiceLocator()->get('MelisEcomOrderMessageTable');
	    try
	    {
	        $omsg_id = $orderMessageTable->save($arrayParameters['orderMessage'], $arrayParameters['orderMessageId']);
	        $results = $omsg_id;
	    }
	    catch(\Exception $e){}
	    // Service implementation end
	     
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_message_save_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
}