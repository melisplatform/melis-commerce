<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Listener;

use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use MelisCore\Listener\MelisGeneralListener;

/**
 * The flash messenger will add logs by
 * listening to a lot of events
 * 
 */
class MelisCommerceFlashMessengerListener extends MelisGeneralListener implements ListenerAggregateInterface
{
	
    public function attach(EventManagerInterface $events, $priority = 1)
    {

        $sharedEvents      = $events->getSharedManager();
        /**
         * Attach a listener to an event emitted by components with specific identifiers.
         *
         * @param  string $identifier Identifier for event emitting component
         * @param  string $eventName
         * @param  callable $listener Listener that will handle the event.
         * @param  int $priority Priority at which listener should execute
         *
         * $sharedEvents->attach($identifier, $eventName, callable $listener, $priority);
         */
        $identifier = 'MelisCommerce';

        $eventsName = [
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
            'meliscommerce_settings_save_end',
            'meliscommerce_clients_group_flash_messenger',
            'meliscommerce_clients_delete_client_person_email_end',
            'meliscommerce_clients_save_end',
            'meliscommerce_clients_delete_end',
            'meliscommerce_contact_save_end',
            'meliscommerce_contact_delete_end'
        ];

        $priority = -1000;

        $this->attachEventListener($events, $identifier, $eventsName, [$this, 'logMessages'], $priority);
    }
}