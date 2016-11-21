<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use MelisCore\Service\MelisCoreGeneralService;
/**
 *
 * This service handles the coupon system of MelisCommerce.
 *
 */
class MelisComCouponService extends MelisCoreGeneralService
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
        $couponTable = $this->getServiceLocator()->get('MelisEcomCouponTable');
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
    
    /**
     * Returns a coupon by id from melis_ecom_coupon_table
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
        $couponTable = $this->getServiceLocator()->get('MelisEcomCouponTable');
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
        $couponTable = $this->getServiceLocator()->get('MelisEcomCouponTable');
        try{
            $results = $couponTable->save($arrayParameters['coupon'], $arrayParameters['couponId']);
        }catch(\Exception $e){
            echo $e->getMessage();
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
        $couponClientTable = $this->getServiceLocator()->get('MelisEcomCouponClientTable');
        try{
            $results = $couponClientTable->save($arrayParameters['couponClient'], $arrayParameters['ccli_id']);
        }catch(\Exception $e){
            echo $e->getMessage();
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
        $couponTable = $this->getServiceLocator()->get('MelisEcomCouponTable');
        $couponClientTable = $this->getServiceLocator()->get('MelisEcomCouponClientTable');
        try {
            $results = $couponClientTable->deleteByField('ccli_coupon_id', $arrayParameters['couponId']);
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
    
}