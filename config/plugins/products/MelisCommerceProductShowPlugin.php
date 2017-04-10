<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceProductShowPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceProduct/show-product',
                        // template for the MelisCommerceAttributesShowPlugin() plugin
                        'template_path_attributes_view' => 'MelisCommerceProduct/show-attributes',
                        // template for the MelisCommerceAddToCartShowPlugin() plugin
                        'template_path_add_to_cart_view' => 'MelisCommerceProduct/show-add-to-cart',
                        // product id
                        'm_p_id' => null,
                        // variant id
                        'm_p_var_id' => null,
                        // country id
                        'm_p_country' => null,
                        // language id
                        'm_p_lang' => null,
                        // default image to be shown
                        'm_p_timage' => null,
                        // other images
                        'm_p_timage_ok' => array(),
                    ),
                    'melis' => array()
                ),
            ),
        ),
     ),
);