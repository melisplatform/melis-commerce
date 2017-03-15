<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceProductShowPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceProduct/show-product',
                        'template_path_attributes_view' => 'MelisCommerceProduct/show-attributes',
                        'template_path_add_to_cart_view' => 'MelisCommerceProduct/show-add-to-cart',
//                         'javascript_files' => array(
//                             'path1',
//                             'path2',
//                         ),
                        'm_p_id' => null,
                        'm_p_var_id' => null,
                        'm_p_country' => null,
                        'm_p_lang' => null,
                        'm_p_timage' => null,
                        'm_p_timage_ok' => array(),
                    ),
                    'melis' => array()
                ),
                'MelisCommerceAttributesShowPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceProduct/show-attributes',
                        'm_p_id' => null,
                        'm_action' => array(),
                        'm_attrSelection' => array(),
                        'm_p_country' => null,
                        'm_is_submit' => false,
                    ),
                    'melis' => array()
                ),
                'MelisCommerceProductsRelatedPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceProduct/show-related-products',
                        
                        // Optional Filtering
                        'm_p_id' => NULl,
                    ),
                    'melis' => array()
                ),
                'MelisCommerceCartAddPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceProduct/show-add-to-cart',

                        'm_p_quantity' => 1,
                        'm_p_stock' => null,
                    ),
                    
                    'melis' => array()
                ),
            ),
        ),
    ),
);