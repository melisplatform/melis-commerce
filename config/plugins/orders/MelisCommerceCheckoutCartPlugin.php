<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCheckoutCartPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out-cart',
                        
                        // Paramaters of MelisCommerceCheckoutCouponAddPlugin() plugin
                        'checkout_cart_coupon_parameters' => array(),
                        
                        'm_country_id' => null,
                        'm_site_id' => null,
                        'm_v_quantity' => array(),
                        'm_v_id_remove' => null,
                        'm_v_remove_link' => 'http://www.test.com',
                        'm_image_type' => 'DEFAULT',
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);