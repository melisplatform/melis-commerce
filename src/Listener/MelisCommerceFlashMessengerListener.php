<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use MelisCore\Listener\MelisCoreGeneralListener;

/**
 * The flash messenger will add logs by
 * listening to a lot of events
 * 
 */
class MelisCommerceFlashMessengerListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{
	
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
        	'MelisCommerce',
        	array(
    	        'meliscommerce_category_save_end',
        	    'meliscommerce_category_delete_end',
        	    'meliscommerce_product_save_end',
        	    'meliscommerce_product_add_text_type_end',
        	    'meliscommerce_product_add_text_save_end',
        	    'meliscommerce_product_attr_remove_end',
        	    'meliscommerce_document_delete_end',
        	    'meliscommerce_variant_save_end',
        	    'meliscommerce_variant_delete_end',
        	    'meliscommerce_product_delete_end',
        	    'meliscommerce_order_save_end',
        	    'meliscommerce_document_add_image_type_end',
        	    'meliscommerce_document_save_end',
        	    'meliscommerce_order_status_save_end',
        	    'meliscommerce_document_add_file_type_end',
        	    'meliscommerce_document_save_file_end',
        	    'meliscommerce_document_save_image_end',
        	    'meliscommerce_coupon_save_end',
        	    'meliscommerce_language_end',
                'meliscommerce_language_delete_end',
        	    'meliscommerce_country_end',
        	    'meliscommerce_country_delete_end',
        	    'meliscommerce_attribute_save_end',
        	    'meliscommerce_attribute_delete_end',
        	    'meliscommerce_order_message_save_end',
        	    'meliscommerce_coupon_delete_end',
        	    'meliscommerce_currency_save_end',
        	    'meliscommerce_currency_delete_end',
        	    'meliscommerce_coupon_remove_from_client_end',
        	    'meliscommerce_coupon_client_management_end',
        	    'meliscommerce_category_product_remove_end',
        	    'meliscommerce_currency_set_default_end',
                'meliscommerce_assoc_var_remove_assoc_end',
                'meliscommerce_assoc_var_assoc_end',
        	    'meliscommerce_duplicate_variant_end',
        	    'meliscommerce_attribute_value_save_end',
        	    'meliscommerce_attribute_value_delete_end',
        	    'meliscommerce_country_save_end',
        	    'meliscommerce_language_save_end',
        	    'meliscommerce_checkout_order_add',
        	),
        	function($e){

        		$sm = $e->getTarget()->getServiceLocator();
        		
        		$flashMessenger = $sm->get('MelisCoreFlashMessenger');
        		$params = $e->getParams();
        		$results = $e->getTarget()->forward()->dispatch(
        		    'MelisCore\Controller\MelisFlashMessenger',
        		    array_merge(array('action' => 'log'), $params))->getVariables();
        	},
        -1000);
        
        $this->listeners[] = $callBackHandler;
    }
}