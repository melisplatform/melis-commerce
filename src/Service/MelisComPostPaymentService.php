<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use Laminas\View\Model\JsonModel;

/**
 *
 * This Service will process Checkout Post Payment 
 * 
 * This Service is created for a testing
 *
 */
class MelisComPostPaymentService extends MelisComGeneralService
{
    public function processPostPayment($payment, $postValues)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_proccess_post_payment_start', $arrayParameters);
        
        // Service implementation start
        if (!empty($arrayParameters['postValues']))
        {
            $payment = $arrayParameters['payment'];
            $postValues = $arrayParameters['postValues'];
            
            $orderId = $postValues['order-id'];
            // Getting Client Id
            $melisEcomOrderTable = $this->getServiceManager()->get('MelisEcomOrderTable');
            $orderData = $melisEcomOrderTable->getEntryById($orderId)->current();
            $clientId = $orderData->ord_client_id;
            
            $payment['clientId'] = $clientId;
            $payment['orderId'] = $orderId;
            
            // Payment Details
            $payment['payment_details'] = array(
                'paymentType' => $postValues['payment-type-value'],
                'transactionId' => $postValues['payment-transaction-id'],
                'transactionReturnCode' => $postValues['payment-transaction-return-code'],
                'transactionPricePaid' => $postValues['payment-transaction-price-paid'],
                'transactionFullRawResponse' => $postValues['payment-transaction-full-Raw-Response'] ?? json_encode($postValues),
                'transactionPricepaidConfirm' => $postValues['payment-transaction-price-paid-confirm'],
                'transactionDateTime' => $postValues['payment-transaction-date'],
                'transactionCountryId' => $postValues['payment-transaction-country-id'],
                'transactionCourrencyCode' => $postValues['payment-transaction-currency-code']
            );
            
            // Shipping Total Amount
            $total = 0;
            // Shipping errors
            $errors = array();
            // Generate Error here if needed
            /**
             * Example :
             * $errors = array(
             *      'error_code' => 'xxxx',
             *      'error_code' => 'xxxx',
             *      'error_code' => 'xxxx',
             * );
             */
            
            if (!empty($errors))
            {
                $payment['errors']['payment'] = $errors;
            }
            else 
            {
                $payment['success'] = true;
            }
        }
        // Service implementation end
        
        $arrayParameters['results'] = $payment;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_checkout_proccess_post_payment_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
}