<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceOrderAddressPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceOrder/show-client-order-address',
                        // order id
                        'm_order_id' => null,
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);