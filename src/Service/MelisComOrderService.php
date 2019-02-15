<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;
use DateTime;
use Zend\Http\Response;

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
	public function getOrderList($orderStatusId = null, $onlyValid = null, $langId = null, $clientId = null, 
	                             $clientPersonId = null, $couponId = null, $reference = null,  $start = null, 
	                             $limit = null, $colOrder = null, $search = '', $startDate = null, $endDate = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_list_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
	    
        $orderData = $melisEcomOrderTable->getOrderList($arrayParameters['orderStatusId'] , $arrayParameters['onlyValid'],
                                                       $arrayParameters['clientId'], $arrayParameters['clientPersonId'], 
                                                       $arrayParameters['couponId'], $arrayParameters['reference'],
                                                       $arrayParameters['start'], $arrayParameters['limit'], 
                                                       $arrayParameters['colOrder'], $arrayParameters['search'], 
                                                       $arrayParameters['startDate'], $arrayParameters['endDate'] )->toArray();
	    foreach ($orderData As $key => $val)
	    {
	        $melisOrder = $this->getOrderById($val['ord_id'], $arrayParameters['langId']);
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
	    $melisEcomClientTable = $this->getServiceLocator()->get('MelisEcomClientTable');
	    $melisEcomClientSvc = $this->getServiceLocator()->get('MelisComClientService');
	    $docSvc = $this->getServiceLocator()->get('MelisComDocumentService');
	    
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

	            // Get Order ducoments
	    		$orderdDoc = $docSvc->getDocumentsByRelationAndTypes('order', $orderId, 'FILE');
	            $melisOrder->setDocuments($orderdDoc);
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
	 * This method exports the orders into csv
	 * @param int $orderStatus status order id
	 * @param datetime $dateStart starting date 
	 * @param datetime $dateEnd end date
	 * @param char $separator seperator
	 * @param char $encapseulate encapsulator
	 * @param int $langId language id
	 * @return array()
	 */
	public function exportOrderList($orderStatus = null, $dateStart = null, $dateEnd = null, $separator, $encapseulate = '', $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_export_order_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisCoreConfig = $this->getServiceLocator()->get('MelisCoreConfig');
	    // get config for file name
	    $csvConfig = $melisCoreConfig->getItem('meliscommerce/datas/default/export/csv');
	    $csvFileName = $csvConfig['orderFileName'];
	    $dir = $csvConfig['dir'];
	    
	    $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
	    
	    $orderData = $melisEcomOrderTable->fetchAll();
	    $count = $orderData->count();
	    unset($orderData);
	    $limit = $csvConfig['orderLimit'];
	    
	    if ($count)
	    {
	        $cycles = ceil((float)($count / $limit));
	        
	        // fetching data by segment
	        for($c = 0 ; $c < $cycles;  $c++){
	            $csvData = '';
	            $start = $c * $limit;
	            $orders = $this->getOrderList(
	                $arrayParameters['orderStatus'], null, $arrayParameters['langId'], null,
	                null, null, null, $start,
	                $limit , null, null, $arrayParameters['dateStart'], $arrayParameters['dateEnd']
                );
	            
	            foreach($orders as $order){
	                $formatted = $this->formatOrderToCsv($order, $arrayParameters['separator'], $arrayParameters['encapseulate']);
	                if($arrayParameters['orderStatus'] != '-1' && $order->getOrder()->ord_status == '-1'){
	                    $formatted = '';
	                }
	                $csvData .= $formatted;
	            }
	            $append = file_put_contents($dir.$csvFileName, $csvData, FILE_APPEND | LOCK_EX );
	            if(!$append){
	                break;
	            }else{
	                $results = true;
	            }
	        }
	        
	    }
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_export_order_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}
	
	
	public function formatOrderToCsv($orderEntity, $separator, $encapsulate = '')
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = '';
	     
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_format_to_csv_start', $arrayParameters);
	     
	    // Service implementation start
	    $orderStatusTransTable = $this->getServiceLocator()->get('MelisEcomOrderStatusTransTable'); 
	    $clientSvc = $this->getServiceLocator()->get('MelisComClientService');
	    $countryTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
	    
	    $s = $arrayParameters['separator'];
	    $e =  $arrayParameters['encapsulate'];
	    $currencies = array();
	    $paymentTypes = array();
	    $billAdd = array();
	    $delAdd = array();
	    
	    $order = $arrayParameters['orderEntity']->getOrder();
	    // add status name
	    $orderTrans = $orderStatusTransTable->getEntryByField('ostt_status_id', $order->ord_status);
	    $order->status_name = ($orderTrans->count())? $orderTrans->current()->ostt_status_name : '';
	    
	    // add country nem
	    $country = $countryTable->getEntryByField('ctry_id', $order->ord_country_id);
	    $order->country_name = ($country->count())? $country->current()->ctry_name : '';
	    
	    $client = $arrayParameters['orderEntity']->getClient();
	    // add company name
	    $company = $clientSvc->getCompanyByClientId($client->cli_id);
	    $client->client_company_name = !empty($company)? $company[0]->ccomp_name : '';
	    
	    $person = $arrayParameters['orderEntity']->getPerson();
	    $payments = $arrayParameters['orderEntity']->getPayment();
	    
	    foreach($arrayParameters['orderEntity']->getAddresses() as $addr){
	        if($addr->oadd_type == 1){
	            $billAdd = $addr;
	        }
	        if($addr->oadd_type == 2){
	            $delAdd = $addr;
	        }
	    }
	    
	    //fetch currency
	    $currencyTable = $this->getServiceLocator()->get('MelisEcomCurrencyTable');
	    
	    foreach($currencyTable->fetchAll() as $data){
	        $currencies[] = $data;
	    }
	    
	    $content = array();
	    
	    // 1st line
	    $line1[] = addslashes($order->ord_id);
	    $line1[] = addslashes($order->ord_client_id);
	    $line1[] = addslashes($order->ord_status);
	    $line1[] = addslashes($order->status_name);
	    $line1[] = addslashes($order->ord_client_person_id);
	    $line1[] = addslashes($person->civility_trans[0]->civt_min_name);
	    $line1[] = addslashes($person->cper_name);
	    $line1[] = addslashes($person->cper_firstname);
	    $line1[] = addslashes($client->client_company_name);
	    $line1[] = addslashes($order->ord_country_id);
	    $line1[] = addslashes($order->country_name);
	    $line1[] = addslashes($order->ord_reference);
	    $line1[] = addslashes($order->ord_date_creation);
	    $content[] = $e.implode($e.$s.$e, $line1).$e.$s;
	    
	    $add1 = array();
	    // billing address
	    foreach($billAdd as $key => $val){
	        if(gettype($val) != 'array'){
	            $add1[] = $val;
	        }
	    }
	    $content[] = $e.implode($e.$s.$e, $add1).$e.$s;
	    
	    $add2 = array();
	    // delivery address
	    foreach($delAdd as $key => $val){
	        if(gettype($val) != 'array'){
	            $add2[] = $val;
	        }
	    }
	    $content[] = $e.implode($e.$s.$e, $add2).$e.$s;
	    
	    // payments
	    if(!empty($payments)){
	        $content[] = 'PAYMENTS';
	        
	        $pays = array();
	        foreach($payments as $data){
	            $currencyName = '';
	            foreach($currencies as $currency){
	                if($data->opay_currency_id == $currency->cur_id){
	                    $currencyName = $currency->cur_name;
	                }
	            }
	            $pay = array();
	            $pay[] = addslashes($data->opay_id);
	            $pay[] = addslashes($data->opay_price_total);
	            $pay[] = addslashes($data->opay_price_order);
	            $pay[] = addslashes($data->opay_price_shipping);
	            $pay[] = addslashes($data->opay_currency_id);
	            $pay[] = addslashes($currencyName);
	            $pay[] = addslashes($data->opay_payment_type_id);
	            $pay[] = addslashes($data->payment_type->opty_name);
	            $pay[] = addslashes($data->opay_transac_id);
	            $pay[] = addslashes($data->opay_transac_price_paid_confirm);
	            $pay[] = addslashes($data->opay_date_payment);
	             
	            $pays[] = $e.implode($e.$s.$e, $pay).$e.$s;
	        }
	        
	        $content[] = implode(PHP_EOL, $pays);
	    }
	    
	    
	    // coupons
	    // fetch order coupons
	    $couponSvc= $this->getServiceLocator()->get('MelisComCouponService');
	    $couponData= $couponSvc->getCouponList($order->ord_id);
	    if(!empty($couponData)){
	        $content[] = 'COUPONS';
	        
	        foreach($couponData as $data){
	            $tmp = (array) $data->getCoupon();
	            $coupon = array();
	            foreach($tmp as $t){
	                $coupon[] = addslashes($t);
	            }
	            $coupons[] = $e.implode($e.$s.$e, $coupon).$e.$s;
	        }
	        
	        $content[] = implode(PHP_EOL, $coupons);
	    }
	    
	    // shipping
	    $shippingData = $arrayParameters['orderEntity']->getShipping();
	    if(!empty($shippingData)){
	        $content[] = 'SHIPPING';
	         
	        foreach($shippingData as $data){
	            $tmp = (array) $data;
	            $shipping = array();
	            foreach($tmp as $t){
	                $shipping[] = addslashes($t);
	            }
	            $shippings[] = $e.implode($e.$s.$e, $shipping).$e.$s;
	        }
	         
	        $content[] = implode(PHP_EOL, $shippings);
	    }
	    
	    // basket
	    $basketData = $arrayParameters['orderEntity']->getBasket();
	    if(!empty($basketData)){
	        $content[] = 'BASKET';
	        
	        foreach($basketData as $data){
	            $tmp = (array) $data;
	            $basket = array();
	            foreach($tmp as $key => $val){
	                if($key == 'obas_currency'){
	                    foreach($currencies as $currency){
	                        if($val == $currency->cur_id){
	                            $basket[] = addslashes($val);
	                            $basket[] = addslashes($currency->cur_name);
	                        }
	                    }
	                }
	                else{
	                   $basket[] = addslashes($val);
	                }
	            }
	            $baskets[] = $e.implode($e.$s.$e, $basket).$e.$s;
	        }
	        
	        $content[] = implode(PHP_EOL, $baskets);
	    }
	    
	    // messages
	    $messagesData = $arrayParameters['orderEntity']->getMessages();
	    if(!empty($messagesData)){
	        $content[] = 'MESSAGES';
	        
	        foreach($messagesData as $data){
	            $tmp = (array) $data;
	            $message = array();
	            foreach($tmp as $t){
	                $message[] = addslashes($t);
	            }
	            $messages[] = $e.implode($e.$s.$e, $message).$e.$s;
	        }
	         
	        $content[] = implode(PHP_EOL, $messages);
	    }
	    
	    $content[] = PHP_EOL;

	    $results = implode(PHP_EOL, $content);
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_format_to_csv_end', $arrayParameters);
	     
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
	    $melisEcomOrderAddressTable = $this->getServiceLocator()->get('MelisEcomOrderAddressTable');
	    $melisEcomCivilityTransTable = $this->getServiceLocator()->get('MelisEcomCivilityTransTable');
	    $melisEcomClientAddressTypeTransTable = $this->getServiceLocator()->get('MelisEcomClientAddressTypeTransTable');
	    foreach($melisEcomOrderAddressTable->getOrderAddressesByOrderId($arrayParameters['orderId']) as $address){
	        $addressTrans = array();
	        $civilityTrans = array();
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
	public function getOrderStatusList($langId = null, $onlyValid = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_list_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderStatusTable = $this->getServiceLocator()->get('MelisEcomOrderStatusTable');
	    $status = $melisEcomOrderStatusTable->getOrderStatusListByLangId($arrayParameters['langId'], $arrayParameters['onlyValid']);
	    foreach($status as $item){
	        $results[] = $item;
	    }
	   
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_list_end', $arrayParameters);
	    
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
	public function getOrderStatuses($langId = null, $onlyValid = null, $start = null, $limit = null, $colOrder = null, $search = '')
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	     
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_statuses_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisEcomOrderStatusTable = $this->getServiceLocator()->get('MelisEcomOrderStatusTable');
	    $orderStatusTransTable = $this->getServiceLocator()->get('MelisEcomOrderStatusTransTable');
	    
	    $status = $melisEcomOrderStatusTable->getOrderStatusList(
	        $arrayParameters['onlyValid'], $arrayParameters['start'], $arrayParameters['limit'], 
	        $arrayParameters['colOrder'], $arrayParameters['search']
        );
	    
	    foreach($status as $item){
	        $trans = array();
	        foreach($orderStatusTransTable->getEntryByField('ostt_status_id', $item->osta_id) as $stat){
	            $trans[] = $stat;
	        }
	        $item->trans = $trans;
	        $results[] = $item;
	    }
	   
	    // Service implementation end
	     
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_statuses_end', $arrayParameters);
	     
	    return $arrayParameters['results'];
	}
	
	public function getOrderStatus($orderStatusId, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderStatusTable = $this->getServiceLocator()->get('MelisEcomOrderStatusTable');
	    $orderStatusTransTable = $this->getServiceLocator()->get('MelisEcomOrderStatusTransTable');
	     
	    $status = $melisEcomOrderStatusTable->getEntryById($arrayParameters['orderStatusId'])->current();;
	    
	    if(!empty($status)){
	        $trans = array();
	        foreach($orderStatusTransTable->getEntryByField('ostt_status_id', $status->osta_id) as $stat){
	            $trans[] = $stat;
	        }
	        $status->trans = $trans;
	    }
	    
	    $results = $status;
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_end', $arrayParameters);
	    
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
	
	public function getClientOrderDetailsById($orderId, $clientId, $personId = null, $langId = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_get_client_order_start', $arrayParameters);
	     
	    // Service implementation start
	    $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
        	    
	    $clientOrderData = $melisEcomOrderTable->getClientOrderDetailsById($arrayParameters['orderId'], $arrayParameters['clientId'], $arrayParameters['personId'], $arrayParameters['langId'])->current();
	    if (!empty($clientOrderData))
	    {
	        $results = $clientOrderData;
	    }
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_get_client_order_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	public function getOrderPaymentWithTypeAndCouponByOrderId($orderId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = array();
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_get_order_payment_payment_type_coupon_start', $arrayParameters);
	    
	    // Service implementation start
	    $melisEcomOrderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
	     
	    $orderPayment = $melisEcomOrderTable->getOrderPaymentWithTypeAndCouponByOrderId($orderId)->current(); 
	    
	    if (!empty($orderPayment))
	    {
	        $results = $orderPayment;
	    }
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_get_order_payment_payment_type_coupon_end', $arrayParameters);
	    
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
	    
	    if (!empty($order)&&is_array($order))
	    {
	        try
	        {
	            $results['ord_id'] = $melisEcomOrderTable->save($arrayParameters['order'], $arrayParameters['orderId']);
	        }
	        catch(\Exception $e){
	            
	        }
	    }

	    if ($results['ord_id'])
	    {
	        
	        $basketData = $arrayParameters['basket'];
	        if(!empty($basketData)){
    	        foreach ($basketData As $key => $val)
    	        {
    	            $obasId = (!empty($val['obas_id'])) ? $val['obas_id'] : null;
    	            unset($basketData[$key]['obas_id']);
    	            $basketData[$key]['obas_order_id'] = $results['ord_id'];
    	            $results['obas_id'] = $this->saveOrderBasket($basketData[$key], $obasId);
    	        }
	        }
	        $billingAddressData = $arrayParameters['billingAddress'];
	        if(!empty($billingAddressData)){
    	        foreach ($billingAddressData As $key => $val)
    	        {
    	           $oaddId = (!empty($val['oadd_id'])) ? $val['oadd_id'] : null;
    	            unset($billingAddressData[$key]['oadd_id']);
    	            $billingAddressData[$key]['oadd_order_id'] = $results['ord_id'];
    	            $addType = 1; // Billing Id of the Address
    	            $result['ord_billing_address'] = $this->saveOrderAddress($billingAddressData[$key], $addType, $oaddId);
    	            
    	        }
	        }
	        $deliveryAddressData = (!empty($arrayParameters['deliveryAddress'])) ? $arrayParameters['deliveryAddress'] : $billingAddressData;
	        if(!empty($deliveryAddressData)){
    	        foreach ($deliveryAddressData As $key => $val)
    	        {
    	            $oaddId = (!empty($val['oadd_id'])) ? $val['oadd_id'] : null;
    	            unset($deliveryAddressData[$key]['oadd_id']);
    	            $deliveryAddressData[$key]['oadd_order_id'] = $results['ord_id'];
    	            $addType = 2; // Delivery Id of the Address
    	            $result['ord_delivery_address'] = $this->saveOrderAddress($deliveryAddressData[$key], $addType, $oaddId);
    	        }
	        }
	        $paymentData = $arrayParameters['payment'];
	        if(!empty($paymentData)){
    	        foreach ($paymentData As $key => $val)
    	        {
    	            $opayId = (!empty($val['opay_id'])) ? $val['opay_id'] : null;
    	            unset($paymentData[$key]['opay_id']);
    	            $paymentData[$key]['opay_order_id'] = $results['ord_id'];
    	            $result['opay_id'] = $this->saveOrderPayment($paymentData[$key], $opayId);	            
    	        }
	        }
	        $shippingData = $arrayParameters['shipping'];
	        if(!empty($shippingData)){
	            foreach ($shippingData as $key => $val){
	                $oship_id = (!empty($val['oship_id'])) ? $val['oship_id'] : null;
	                unset($shippingData[$key]['oship_id']);
	                $shippingData[$key]['oship_order_id'] = $results['ord_id'];
	                $result['oship_id'] = $this->saveOrderShipping($shippingData[$key], $oship_id);
	            }
	        }
	        if(!empty($arrayParameters['billingAddress'])){
	            $orderUpdate = array(
	                'ord_delivery_address' =>  $result['ord_delivery_address'],
	                'ord_billing_address' =>   $result['ord_billing_address']
	            );
	            try
	            {
	                $results['ord_id'] = $melisEcomOrderTable->save($orderUpdate,  $results['ord_id']);
	            }
	            catch(\Exception $e){
	            
	            }
	        }	        
	        
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results['ord_id'];
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
	    catch(\Exception $e){}
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
	    catch(\Exception $e){
	        
	    }
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
	        unset($arrayParameters['orderStatus']['osta_id']);
	        
	        $ostaId = $melisEcomOrderStatusTable->save($arrayParameters['orderStatus'], $arrayParameters['orderStatusId']);
	        
	        $statusTransData = $arrayParameters['orderStatusTranslations'];
	        
	        foreach($statusTransData as $trans){
	            $trans['ostt_status_id'] = $ostaId;
	            $osttId = (!empty($trans['ostt_id'])) ? $trans['ostt_id'] : null;
	            unset($trans['ostt_id']);
	            
	            $melisEcomOrderStatusTransTable->save($trans, $osttId);
	        }
	        
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
	
	/**
	 * This method deletes the order status and its translations
	 * @param unknown $orderStatusId
	 * @return mixed
	 */
	public function deleteOrderStatusById($orderStatusId)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_delete_start', $arrayParameters);
	
	    // Service implementation start
	    $orderStatusTbl = $this->getServiceLocator()->get('MelisEcomOrderStatusTable');
	    $orderStatusTransTbl = $this->getServiceLocator()->get('MelisEcomOrderStatusTransTable');
	    try
	    {
	        $results = $orderStatusTransTbl->deleteByField('ostt_status_id', $arrayParameters['orderStatusId']);
	        $results = $orderStatusTbl->deleteById($arrayParameters['orderStatusId']);
	    }
	    catch(\Exception $e){}
	    // Service implementation end
	
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_status_delete_end', $arrayParameters);
	
	    return $arrayParameters['results'];
	}
	
	/**
	 * This method retrieves the data used for the list widget
	 * @param varchar $identifier accepts curMonth|avgMonth
	 * @param $onlyValid - valid order only
	 * @return float|null , float on success, otherwise null
	 */
	public function getWidgetOrders($identifier, $onlyValid = null)
	{
	    // Event parameters prepare
	    $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
	    $results = null;
	    
	    // Sending service start event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_widget_order_start', $arrayParameters);
	    
	    // Service implementation start
	    $orderTable = $this->getServiceLocator()->get('MelisEcomOrderTable');
	    switch($arrayParameters['identifier']){
	        case 'curMonth':
	            $results = $orderTable->getCurrentMonth($arrayParameters['onlyValid'])->count(); break;
	        case 'avgMonth':
	            $results = $orderTable->getAvgMonth($arrayParameters['onlyValid'])->current(); break;
	        default:
	            break;
	    }
	    // Service implementation end
	    
	    // Adding results to parameters for events treatment if needed
	    $arrayParameters['results'] = $results;
	    // Sending service end event
	    $arrayParameters = $this->sendEvent('meliscommerce_service_order_widget_order_end', $arrayParameters);
	    
	    return $arrayParameters['results'];
	}

	public function getOrdersDataByDate($type = 'daily', $date)
    {
        $count = 0;

        switch ($type) {
            case 'hourly':
                $year = date('Y', strtotime($date));
                $month = date('m', strtotime($date));
                $today = date('j', strtotime($date));
                $yesterday = date('j', strtotime($date)) - 1;

                $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $yesterday, $year));
                $endDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $today, $year));

                $ordersData = $this->getOrderList(
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    0,
                    null,
                    'ord_id DESC',
                    null,
                    $startDate,
                    $endDate
                );

                for ($i = 0 ; $i < count($ordersData); $i++){
                    // Checking if Date is same as the Param data
                    if (date('Y-m-d H',strtotime($date)) == date('Y-m-d H', strtotime($ordersData[$i]->getOrder()->ord_date_creation))){
                        $count++;
                    }
                }
                break;
            case 'weekly':
                $year = date('Y', strtotime($date));
                $month = date('m', strtotime($date));
                $lastDateOfMonth = date('t', strtotime($date));

                $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year));
                $endDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $lastDateOfMonth, $year));

                $ordersData = $this->getOrderList(
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    0,
                    null,
                    'ord_id DESC',
                    null,
                    $startDate,
                    $endDate
                );

                for ($i = 0 ; $i < count($ordersData); $i++){
                    // Checking if Date is same as the Param data
                    if (date('W',strtotime($date)) == date('W', strtotime($ordersData[$i]->getOrder()->ord_date_creation))){
                        $count++;
                    }
                }
                break;
            case 'daily':
                $year = date('Y', strtotime($date));
                $month = date('m', strtotime($date));
                $today = date('j', strtotime($date));
                $yesterday = date('j', strtotime($date)) - 1;

                $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $yesterday, $year));
                $endDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $today, $year));

                $ordersData = $this->getOrderList(
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    0,
                    null,
                    'ord_id DESC',
                    null,
                    $startDate,
                    $endDate
                );

                for ($i = 0 ; $i < count($ordersData); $i++){
                    // Checking if Date is same as the Param data
                    if ($date == date('Y-m-d', strtotime($ordersData[$i]->getOrder()->ord_date_creation))){
                        $count++;
                    }
                }
                break;
            case 'monthly':
                $year = date('Y', strtotime($date));
                $month = date('m', strtotime($date));
                $lastDateOfMonth = date('t', strtotime($date));

                $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year));
                $endDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $lastDateOfMonth, $year));

                $ordersData = $this->getOrderList(
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    0,
                    null,
                    'ord_id DESC',
                    null,
                    $startDate,
                    $endDate
                );

                for ($i = 0 ; $i < count($ordersData); $i++){
                    // Checking if Date is same as the Param data
                    if (date('Y-m',strtotime($date)) == date('Y-m',strtotime($ordersData[$i]->getOrder()->ord_date_creation))){
                        $count++;
                    }
                }
                break;
            default:
                break;

        }

        return $count;
    }

    public function getSalesRevenueDataByDate($type = 'hourly', $date)
    {
        $value = [];
        $value['totalOrderPrice'] = 0;
        $value['totalShippingPrice'] = 0;

            switch ($type) {
                case 'hourly':
                    $year = date('Y', strtotime($date));
                    $month = date('m', strtotime($date));
                    $today = date('j', strtotime($date));
                    $yesterday = date('j', strtotime($date)) - 1;

                    $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $yesterday, $year));
                    $endDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $today, $year));

                    $ordersData = $this->getOrderList(
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        0,
                        null,
                        'ord_id DESC',
                        null,
                        $startDate,
                        $endDate
                    );
                    
                    for ($i = 0 ; $i < count($ordersData); $i++){
                        // Checking if Date is same as the Param data
                        if (date('Y-m-d H',strtotime($date)) == date('Y-m-d H',strtotime($ordersData[$i]->getOrder()->ord_date_creation))){
                            foreach($ordersData[$i]->getPayment() as $payment)
                            {
                                $value['totalOrderPrice'] += $payment->opay_price_order;
                                $value['totalShippingPrice'] += $payment->opay_price_shipping;
                            }
                        }
                    }
                    break;
                case 'weekly':
                    $year = date('Y', strtotime($date));
                    $month = date('m', strtotime($date));
                    $lastDateOfMonth = date('t', strtotime($date));

                    $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year));
                    $endDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $lastDateOfMonth, $year));

                    $ordersData = $this->getOrderList(
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        0,
                        null,
                        'ord_id DESC',
                        null,
                        $startDate,
                        $endDate
                    );
                    
                    for ($i = 0 ; $i < count($ordersData); $i++){
                        // Checking if Date is same as the Param data
                        if (date('W',strtotime($date)) == date('W',strtotime($ordersData[$i]->getOrder()->ord_date_creation))){
                            foreach($ordersData[$i]->getPayment() as $payment)
                            {
                                $value['totalOrderPrice'] += $payment->opay_price_order;
                                $value['totalShippingPrice'] += $payment->opay_price_shipping;
                            }
                        }
                    }
                    break;
                case 'daily':
                    $year = date('Y', strtotime($date));
                    $month = date('m', strtotime($date));
                    $today = date('j', strtotime($date));
                    $yesterday = date('j', strtotime($date)) - 1;

                    $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $yesterday, $year));
                    $endDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $today, $year));

                    $ordersData = $this->getOrderList(
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        0,
                        null,
                        'ord_id DESC',
                        null,
                        $startDate,
                        $endDate
                    );

                    for ($i = 0 ; $i < count($ordersData); $i++){
                        // Checking if Date is same as the Param data
                        if ($date == date('Y-m-d',strtotime($ordersData[$i]->getOrder()->ord_date_creation))){
                            foreach($ordersData[$i]->getPayment() as $payment)
                            {
                                $value['totalOrderPrice'] += $payment->opay_price_order;
                                $value['totalShippingPrice'] += $payment->opay_price_shipping;
                            }
                        }
                    }
                    break;
                case 'monthly':
                    $year = date('Y', strtotime($date));
                    $month = date('m', strtotime($date));
                    $lastDateOfMonth = date('t', strtotime($date));

                    $startDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year));
                    $endDate = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $lastDateOfMonth, $year));

                    $ordersData = $this->getOrderList(
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        0,
                        null,
                        'ord_id DESC',
                        null,
                        $startDate,
                        $endDate
                    );

                    for ($i = 0 ; $i < count($ordersData); $i++){
                        // Checking if Date is same as the Param data
                        if (date('Y-m',strtotime($date)) == date('Y-m',strtotime($ordersData[$i]->getOrder()->ord_date_creation))){
                            foreach($ordersData[$i]->getPayment() as $payment)
                            {
                                $value['totalOrderPrice'] += $payment->opay_price_order;
                                $value['totalShippingPrice'] += $payment->opay_price_shipping;
                            }
                        }
                    }
                    break;
                default:
                    break;
            }

        return $value;
    }
}