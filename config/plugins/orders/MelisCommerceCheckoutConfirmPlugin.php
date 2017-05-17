<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCheckoutConfirmPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out-confirm',
                        'address_parameters' => array(),
                        'shipping_parameters' => array(),
                        'm_image_type' => '',
                        'm_custom_image' => '',
                        'm_c_order' => ''
                    ),
                    'melis' => array(),
                )
            )
        )
    )
);