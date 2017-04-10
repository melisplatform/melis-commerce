<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCheckoutPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCheckout/show-check-out',
                        
                        // Sub plugin paramaters
                        'checkout_cart_parametesr' => array(),
                        'checkout_cart_addresses_parameters' => array(),
                        'checkout_cart_summary_parameters' => array(),
                        'checkout_cart_confirm_summary_parameters' => array(),
                        'checkout_cart_confirm_parameters' => array(),
                        
                        // checkout steps
                        'm_checkout_step' => '',
                        // country id
                        'm_checkout_country_id' => 1,
                        // site id
                        'm_checkout_site_id' => 1,
                        // page link
                        'm_checkout_page_link' => 'http://www.test.com',
                        // page link ro reroute user if not logged in
                        'm_login_page_link' => 'http://www.test.com',
                    ),
                    'melis' => array(),
                ),
            ),
        ),
    ),
);