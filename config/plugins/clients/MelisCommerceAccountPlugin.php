<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceAccountPlugin' => array(
                    'front' => array(
                        // Template path
                        'template_path' => 'MelisCommerce/ClientAccount',
                        // Parameters for the MelisCommerceProfilePlugin() 
                        'profile_parameter' => array(),
                        // Parameters for the MelisCommerceDeliveryAddressPlugin()
                        'delivery_address_parameter' => array(),
                        // Parameters for the MelisCommerceBillingAddressPlugin()
                        'billing_address_parameter' => array(),
                        // Parameters for the MelisCommerceCartMenuPlugin()
                        'include_mycart_parameter' => array(),
                        // Parameters for the MelisCommerceOrderListPlugin()
                        'order_history_paremeter' => array(),
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);