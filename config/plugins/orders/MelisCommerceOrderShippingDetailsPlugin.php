<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceOrderShippingDetailsPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceOrder/show-client-shipping-details',
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);