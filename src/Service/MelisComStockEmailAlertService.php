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
 * This service handles the variant system of MelisCommerce.
 *
 */
class MelisComStockEmailAlertService extends MelisComGeneralService
{
   
    /**
     * This method retrieves the email recipients of the stock alert
     * 
     * @param int $productId , Id of the product, result will be filtered by product id. General setting is set to product id -1
     * 
     * @return array() 
     * 
     */
    public function getStockEmailRecipients($productIds = array(-1))
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
         
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_stock_email_alert_recipients_start', $arrayParameters);
         
        // Service implementation start
        $emailAlertTable = $this->getServiceManager()->get('MelisEcomStockEmailAlertTable');
        $emailAlerts = $emailAlertTable->getStockEmailRecipients($arrayParameters['productIds']);

        foreach($emailAlerts as $emailAlert){
            
            $results[] = $emailAlert->getArrayCopy();
        }
        
        // Service implementation end
         
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_stock_email_alert_recipients_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
    
    /**
     * This method savesthe stock email alert
     * 
     * @param array $stockEmailAlert data to save
     * @param unknown $stockEmailAlertId id of the data, if provided an update will be performed
     * 
     * @return int|null returns the inserted or updated id
     */
    public function SaveStockEmailAlert($stockEmailAlert, $stockEmailAlertId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_stock_email_alert_save_start', $arrayParameters);
        
        // Service implementation start
        $emailAlertTable = $this->getServiceManager()->get('MelisEcomStockEmailAlertTable');
        try {
            $results = $emailAlertTable->save($arrayParameters['stockEmailAlert'], $arrayParameters['stockEmailAlertId']);
        }catch(\Exception $e){
            echo $e->getMessage(); die();
        }
        // Service implementation end
         
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_stock_email_alert_save_end', $arrayParameters);
    
        return $arrayParameters['results'];
    }
    
    /**
     * This method deletes an entry by id
     * 
     * @param int $seaId the id to be deleted
     * 
     * @return boolean
     */
    public function deleteStockEmailAlertById($seaId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = false;
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_stock_email_alert_delete_start', $arrayParameters);
        
        // Service implementation start
       $emailAlertTable = $this->getServiceManager()->get('MelisEcomStockEmailAlertTable');
        try {
            $results = $emailAlertTable->deleteById($arrayParameters['seaId']);
            $results = ($results)? true : false;
        }catch(\Exception $e){
        
        }
        // Service implementation end
         
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_stock_email_alert_delete_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    
    public function checkStockLevelByOrderId($orderId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = false;
    
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_check_stock_level_start', $arrayParameters);
    
        // Service implementation start
        $translator = $this->getServiceManager()->get('translator');
         
        $productSvc = $this->getServiceManager()->get('MelisComProductService');
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $orderSvc = $this->getServiceManager()->get('MelisComOrderService');
        $emailAlertTable = $this->getServiceManager()->get('MelisEcomStockEmailAlertTable');
        
        $order = $orderSvc->getOrderById($arrayParameters['orderId']);
        $orderBasket = $order->getBasket();
        $langId = !empty($variantSvc->getFrontPluginLangId())? $variantSvc->getFrontPluginLangId() : $variantSvc->getEcomLang()->elang_id;
        $langLocale = !empty($variantSvc->getFrontPluginLangLocale())? $variantSvc->getFrontPluginLangLocale() : $variantSvc->getEcomLang()->elang_locale;
        
        foreach($orderBasket as $item){
            $emails = array();
            $countryId =  $order->getOrder()->ord_country_id;
            $variant = $variantSvc->getVariantById($item->obas_variant_id, $langId, $countryId);
    
            $variantStock = $variant->getStocks();
    
            if(empty($variantStock)){
                $variantStock = $variantSvc->getVariantStocksById($item->obas_variant_id);
            }
    
            $currentQuantity = $variantStock[0]->stock_quantity;
            $mailSent = $variantStock[0]->stock_qty_email_sent;
            $stockLow = null;
            
            $product = $variantSvc->getProductByVariantId($item->obas_variant_id);
            
            //check if low level is set on variant or product or commerce settings
            if(!empty($variantStock[0]->stock_low)){
                
                $stockLow = $variantStock[0]->stock_low;
                
            }elseif(!empty($product->prd_stock_low)){
                
                $stockLow = $product->prd_stock_low;
                
            }else{
                
                $defaultStockSetting = $emailAlertTable->getEntryById(-1)->current();
                
                if(!empty($defaultStockSetting)){
                    
                    $stockLow = $defaultStockSetting->sea_stock_level_alert;
                }
            }
            
            // retreive emails in product or in settings
            $stockEmailAlerts = $this->getStockEmailRecipients($product->prd_id);
            
            foreach($stockEmailAlerts as $sea){
            
                if($sea['sea_id'] != -1){
                    $emails[] = $sea['sea_email'];
                }
            }
            
            if(empty($emails)){
                
                $stockEmailAlerts = $this->getStockEmailRecipients(-1);
                
                foreach($stockEmailAlerts as $sea){
                    
                    if($sea['sea_id'] != -1){
                        $emails[] = $sea['sea_email'];
                    }
                }
            }
            
            $prodText = $productSvc->getProductTextsById($product->prd_id, 'TITLE', $langId);
            $productName = !empty($prodText)? $prodText[0]->ptxt_field_short : $product->prd_reference;

            // if quanitity is equal or below stock low limit,
            // and no mail, send notification email
            if(!is_null($stockLow) && $currentQuantity <= $stockLow && empty($mailSent)){
                
                $sendMailSvc = $this->getServiceManager()->get('MelisEngineSendMail');
                $config = $this->getServiceManager()->get('config');
                $emailConfig = $config['plugins']['meliscommerce']['emails']['VARIANTSLOWSTOCK'];
    
                $emailCode = $emailConfig['code'];
                 
                $templatePath =  $emailConfig['layout'];
                $emailFrom = $emailConfig['headers']['from'];
                $nameFrom =  $emailConfig['headers']['from_name'];
                $emailTo = !empty($emails)? $emails : $emailConfig['headers']['to'];
                $nameTo  = !empty($emails)? '' : $emailConfig['headers']['name_to'];
                $replyTo = $emailConfig['headers']['replyTo'];
                $subject = sprintf($translator->translate($emailConfig['contents'][$langLocale]['subject']), $productName, $variant->getVariant()->var_sku);
                $content =  vsprintf($translator->translate($emailConfig['contents'][$langLocale]['html']), null);
                $contentTagReplace =  $emailConfig['headers']['tags'];
                $contentTagReplace = explode(',', $contentTagReplace);
                $contentTag['PRODUCT_TEXT'] = $productName;
                $contentTag['PRODUCT_ID'] = $product->prd_id;
                $contentTag['VARIANT_SKU'] = $variant->getVariant()->var_sku;
                $contentTag['VARIANT_ID'] = $variant->getId();
                $contentTag['STOCKS'] = $currentQuantity;
                
                // update stocks email flag
                $flagStocks['stock_qty_email_sent'] = 1;
                $variantSvc->saveVariantStocks($flagStocks, $variantStock[0]->stock_id);
                $sendMailSvc->sendEmail(
                    $templatePath, $emailFrom, $nameFrom,
                    $emailTo, $nameTo, $subject,
                    $content, $contentTag, $replyTo
                    );
            }
        }
    
        // Service implementation end
    
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_check_stock_level_end', $arrayParameters);
         
        return $arrayParameters['results'];
    }
}