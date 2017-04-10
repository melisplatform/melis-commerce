<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceFilterMenuPriceValueBoxPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceCategory/categories-price-filter',
                
                        // filtering
                        'm_box_filter_price_min' => null,
                        'm_box_filter_price_max' => null,
                
                        // Optional parameters
                        'type' => 'variant',
                        // price type are : price_net, price_gross
                        'price_type' => 'price_net',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                            ),
                            'js' => array(
                            ),
                        ),
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);