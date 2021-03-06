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
 * This service handles the coupon system of MelisCommerce.
 *
 */
class MelisComCouponService extends MelisComGeneralService
{
    /**
     * Returns a list of coupons from the coupon table
     * Retrieves coupon list with optional orderId or clientId
     * 
     * @param int $orderId optional, retrieves coupon by orderId if provided
     * @param int $clientId optional, retrieves coupon by clientId if provided
     * 
     * @return coupon[] object | null
     */
    public function getCouponList($orderId = null, $clientId = null, $start = null, $limit = null, $order = null, $search = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_client_coupons_start', $arrayParameters);
        
        // Service implementation start
        $couponTable = $this->getServiceManager()->get('MelisEcomCouponTable');
        foreach($couponTable->getCouponList($arrayParameters['orderId'], $arrayParameters['clientId'], 
                                            $arrayParameters['start'], $arrayParameters['limit'], 
                                            $arrayParameters['order'], $arrayParameters['search']) as $coupon){
            $results[] = $this->getCouponById($coupon->coup_id);
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_client_coupons_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    
    public function getCouponProductList($couponId, $assigned = null, $start = null, $limit = null, $order = null, $search = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_client_coupons_start', $arrayParameters);
        
        // Service implementation start
        $productSvc = $this->getServiceManager()->get('MelisComProductService');
        $productTable = $this->getServiceManager()->get('MelisEcomProductTable');
        foreach($productTable->getCouponProductList($arrayParameters['couponId'], $arrayParameters['assigned'],
            $arrayParameters['start'], $arrayParameters['limit'],
            $arrayParameters['order'], $arrayParameters['search']) as $productCoupon){
                $results[] = $productSvc->getProductById($productCoupon->prd_id);
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_client_coupons_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * Returns a coupon by id from melis_ecom_coupon_
     * Retrieves data by coupon id
     * 
     * @param int $couponId the coupon id to look for
     * 
     * @return coupon object | null
     */
    public function getCouponById($couponId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_coupons_start', $arrayParameters);
        
        // Service implementation start
        $couponTable = $this->getServiceManager()->get('MelisEcomCouponTable');
        $melisCoupon = new \MelisCommerce\Entity\MelisCoupon();
        foreach($couponTable->getEntryById($arrayParameters['couponId']) as $coupon){
            $melisCoupon->setId($coupon->coup_id);
            $melisCoupon->setCoupon($coupon);
            $results = $melisCoupon;
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_coupons_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * Returns the coupon order table from melis_ecom_coupon_order
     * 
     * @param int $couponId Id of the coupon
     * @param int $orderId 
     * 
     * @return coupon order object | empty []
     */
    public function getCouponDiscountedBasketItems($couponId, $orderId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_coupon_discounted_basket_items_start', $arrayParameters);
        
        // Service implementation start
        $couponOrderTable = $this->getServiceManager()->get('MelisEcomCouponOrderTable');
        $data = $couponOrderTable->getCouponDiscountedBasketItems($arrayParameters['couponId'], $arrayParameters['orderId']);
        
        foreach($data as $couponOrder){
            $results[] = $couponOrder;
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_coupon_discounted_basket_items_start', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method retrieves coupon by type, the types are general and product
     * 
     * @param string $type general | product string
     * @param int $orderId order id
     * 
     * @return coupon array()
     */
    public function getCouponByType($type, $orderId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_coupons_by_type_start', $arrayParameters);
        
        // Service implementation start
        $couponTable = $this->getServiceManager()->get('MelisEcomCouponTable');
        $coupons = array();
        try{
           $coupons =  $couponTable->getCouponByType($arrayParameters['type'], $arrayParameters['orderId']);
        }catch(\Exception $e){
            
        }
        
        foreach($coupons as $coupon){
            $melisCoupon = new \MelisCommerce\Entity\MelisCoupon();
            $melisCoupon->setId($coupon->coup_id);
            $melisCoupon->setCoupon($coupon);
            $results[] = $melisCoupon;
        }
        // Service implementation end
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_get_coupons_by_type_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method saves a coupon in the database
     * 
     * @param array $coupon coupon reflecting the melis_com_coupon table
     * @param int $couponId If specified, an update will be done instead of an insert
     * 
     * @return int|null The coupon id created or updated, null if error occured
     */
    public function saveCoupon($coupon, $couponId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_save_start', $arrayParameters);
        
        // Service implementation start
        $couponTable = $this->getServiceManager()->get('MelisEcomCouponTable');
        try{
            $results = $couponTable->save($arrayParameters['coupon'], $arrayParameters['couponId']);
        }catch(\Exception $e){
            
        }
        // Service implementation end
         
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_save_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This method saves a coupon assigned to a client in the database
     * 
     * @param array $couponClient coupon reflecting the melis_ecom_coupon_client table
     * @param int $ccli_id If specified, an update will be done instead of an insert
     * 
     * @return int|null The coupon client id created or updated, null if error occured
     */
    public function saveCouponClient($couponClient, $ccli_id = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_client_save_start', $arrayParameters);
        
        // Service implementation start
        $couponClientTable = $this->getServiceManager()->get('MelisEcomCouponClientTable');
        try{
            $results = $couponClientTable->save($arrayParameters['couponClient'], $arrayParameters['ccli_id']);
        }catch(\Exception $e){
            
        }
        // Service implementation end
         
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_client_save_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This method saves a coupon assigned to a product in the database
     * 
     * @param array $couponClient coupon reflecting the melis_ecom_coupon_client table
     * @param int $ccli_id If specified, an update will be done instead of an insert
     * 
     * @return int|null The coupon client id created or updated, null if error occured
     */
    public function saveCouponProduct($couponProduct, $cprod_id = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_client_save_start', $arrayParameters);
        
        // Service implementation start
        $couponProductTable = $this->getServiceManager()->get('MelisEcomCouponProductTable');
        try{
            $results = $couponProductTable->save($arrayParameters['couponProduct'], $arrayParameters['cprod_id']);
        }catch(\Exception $e){
           
        }
        // Service implementation end
         
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_client_save_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This method deletes a coupon by ID
     * This method is only used when the coupon is unused
     * 
     * @param int $couponId Id of the coupon to be deleted
     * 
     * @return boolean true on success, otherwise false
     */
    public function deleteCouponById($couponId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_client_save_start', $arrayParameters);
        
        // Service implementation start
        $couponTable = $this->getServiceManager()->get('MelisEcomCouponTable');
        $couponClientTable = $this->getServiceManager()->get('MelisEcomCouponClientTable');
        $couponProductTable = $this->getServiceManager()->get('MelisEcomCouponProductTable');
        try {
            $results = $couponClientTable->deleteByField('ccli_coupon_id', $arrayParameters['couponId']);
            $results = $couponProductTable->deleteByField('cprod_coupon_id', $arrayParameters['couponId']);
            $results = $couponTable->deleteById($arrayParameters['couponId']);
        }catch(\Exception $e){
            
        }        
        // Service implementation end
         
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_client_save_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This method will validate Coupon
     * @param String $code, code of the Coupon
     * @param Int $clientId, Client Id
     * 
     *  Return value structure:
     *      array(
     *          'success' => true/false
     *          'coupon' => array()
     *          'error' => 'ERROR_CODE' // MELIS_COMMERCE_COUPON_NOT_AVAILABLE
     *      )
     * @return Array
     */
    public function validateCoupon($code, $clientId, $productId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array(
            'success' => false,
            'coupon' => array(),
        );
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_check_validity_start', $arrayParameters);
        
        // Service implementation start
        $couponTable = $this->getServiceManager()->get('MelisEcomCouponTable');
        $couponProductTable = $this->getServiceManager()->get('MelisEcomCouponProductTable');
        $coupon = $couponTable->getEntryByField('coup_code', $arrayParameters['code'])->current();
        
        if (!empty($coupon))
        {
            // Check coupon status
            if ($coupon->coup_status == 1)
            {
                
                // Check coupon date validity
                $dateValid = false;
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $validStart = ($coupon->coup_date_valid_start) ? strtotime($coupon->coup_date_valid_start) : null;
                $validEnd = ($coupon->coup_date_valid_end) ? strtotime($coupon->coup_date_valid_end) : null;
                if (!is_null($validStart)&& is_null($validEnd))
                {
                    if ($validStart <= $currentDate)
                    {
                        $dateValid = true;
                    }
                }
                elseif (is_null($validStart)&& !is_null($validEnd))
                {
                    if ($validEnd >= $currentDate)
                    {
                        $dateValid = true;
                    }
                }
                elseif (!is_null($validStart)&& !is_null($validEnd))
                {
                    if ($validStart <= $currentDate && $validEnd >= $currentDate)
                    {
                        $dateValid = true;
                    }
                }
                else
                {
                    // Else date are empty
                    $dateValid = true;
                }
                
            if ($dateValid)
                {
                    if(!empty($arrayParameters['productId']))
                    {
                        if($coupon->coup_product_assign){
                            $cliId = ($coupon->coup_type == '1')? $arrayParameters['clientId'] : null;
                            $couponProduct = $couponProductTable->checkCouponProductExist($coupon->coup_id, $cliId, $arrayParameters['productId'])->current();
                            
                            $results['success'] = !empty($couponProduct)? true : false;
                            if(!empty($couponProduct)){
                                // Checking if coupon still available base on number of used
                                if ($coupon->coup_current_use_number < $coupon->coup_max_use_number)
                                {
                                    // Result success
                                    $results['success'] = true;
                                    // Assign coupon data as result
                                    $results['coupon'] = $coupon;
                                }
                                else
                                {
                                    // Number of coupon used had reached the Limit
                                    $results['error'] = 'MELIS_COMMERCE_COUPON_REACHED_LIMIT';
                                    $results['success'] = false;
                                }
                            }
                        }
                    }else{
                        if ($arrayParameters['clientId'])
                        {
                            //check coupon type if assigned type
                            if ($coupon->coup_type == '1')
                            {
                                // Checking if Client is assigned to this couponId
                                $melisEcomCouponClientTable = $this->getServiceManager()->get('MelisEcomCouponClientTable');
                                $couponClient = $melisEcomCouponClientTable->checkCouponClientExist($coupon->coup_id, $arrayParameters['clientId'])->current();
                        
                                if (empty($couponClient))
                                {
                                    // Coupon is not assigned to the selected client
                                    $results['error'] = 'MELIS_COMMERCE_COUPON_CLIENT_NOT_ASSIGN';
                                }
                            }
                        
                        }
                        else
                        {
                            if ($coupon->coup_type == '1')
                            {
                                // Coupon is not assigned to the selected client
                                $results['error'] = 'MELIS_COMMERCE_COUPON_CLIENT_NOT_ASSIGN';
                            }
                        }
                        
                        if (empty($results['error']))
                        {
                            // Checking if coupon still available base on number of used
                            if ($coupon->coup_current_use_number < $coupon->coup_max_use_number)
                            {
                                // Result success
                                $results['success'] = true;
                                // Assign coupon data as result
                                $results['coupon'] = $coupon;
                            }
                            else
                            {
                                // Number of coupon used had reached the Limit
                                $results['error'] = 'MELIS_COMMERCE_COUPON_REACHED_LIMIT';
                            }
                        }
                    }
                    
                }
                else 
                {
                    // Coupon date validity is not match to the current date, otherwise coupon is not yet started or coupon was expired
                    $results['error'] = 'MELIS_COMMERCE_COUPON_DATE_VALIDITY_INVALID';
                }
            }
            else 
            {   
                // Coupon is deactivated/Coupon status is not Active status
                $results['error'] = 'MELIS_COMMERCE_COUPON_NOT_ACTIVE';
            }
        }
        else 
        {
            // Coupon not found from database/Coupon is not existing
            $results['error'] = 'MELIS_COMMERCE_COUPON_NOT_FOUND';
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_check_validity_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    public function saveCouponOrder($couponOrderData, $couponOrderId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_add_to_order_start', $arrayParameters);
        
        // Service implementation start
        $couponOrderTable = $this->getServiceManager()->get('MelisEcomCouponOrderTable');
        try{
            $results = $couponOrderTable->save($arrayParameters['couponOrderData'], $arrayParameters['couponOrderId']);
        }catch(\Exception $e){
            
        }
//         $results = $this->validateCoupon($arrayParameters['code'], $arrayParameters['clientId']);
        
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_add_to_order_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    /**
     * This method will add a Coupon to Client
     * 
     * @param String $code, Coupon code
     * @param Int $clientId, Client id
     * 
     * Return value structure:
     *      array(
     *          'success' => true/false
     *          'couponId' => xx,
     *          'coupon' => array()
     *          'error' => 'ERROR_CODE' // MELIS_COMMERCE_COUPON_NOT_AVAILABLE
     *      )
     * 
     * @return Array
     */
    public function useCoupon($code, $clientId, $orderId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_use_start', $arrayParameters);
        
        // Service implementation start
        $couponTable = $this->getServiceManager()->get('MelisEcomCouponTable');
        
        $results = $this->validateCoupon($arrayParameters['code'], $arrayParameters['clientId']);
        
        if ($results['success'])
        {
            $coupon = $results['coupon'];
            $couponTable = $this->getServiceManager()->get('MelisEcomCouponTable');
            
            $data = array(
                'coup_current_use_number' => $coupon->coup_current_use_number + 1
            );
            
            $results['couponId'] = $couponTable->save($data, $coupon->coup_id);
            
            $melisEcomCouponOrderTable = $this->getServiceManager()->get('MelisEcomCouponOrderTable');
            
            $data = array(
                'cord_coupon_id' => $coupon->coup_id,
                'cord_order_id' => $arrayParameters['orderId']
            );
            
            $melisEcomCouponOrderTable->save($data);
        }
        
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_coupon_use_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
}