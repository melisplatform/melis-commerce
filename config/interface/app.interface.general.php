<?php

return array(
    'plugins' => array(
        'meliscore' => array(
            'interface' => array(
                'meliscore_leftmenu' => array(
                    'interface' => array(
                        'meliscommerce_toolstree_section' => array(
                            'interface' => array(
                                'meliscommerce_categories' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_categories/interface/meliscommerce_categories_leftmenu'
                                    ),
                                ),
                                'meliscommerce_product_list' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_product_list/interface/meliscommerce_product_list_leftmenu'
                                    ),
                                ),
                                'meliscommerce_clients_list' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_clients_list/interface/meliscommerce_clients_list_leftmenu'
                                    ),
                                ),
                                'meliscommerce_order_list' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_order_list/interface/meliscommerce_order_list_leftmenu'
                                    ),
                                ),
                                'meliscommerce_coupon_list' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_coupon_list/interface/meliscommerce_coupon_list_leftmenu'
                                    ),
                                ),
                                'meliscommerce_attribute_list' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_attribute_list/interface/meliscommerce_attribute_list_leftmenu'
                                    ),
                                ),
                                'meliscommerce_country_list' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_country_list/interface/meliscommerce_country_list_leftmenu'
                                    ),
                                ),
                                'meliscommerce_language_list' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_language_list/interface/meliscommerce_language_list_leftmenu'
                                    ),
                                ),
                                'meliscommerce_currency_lists' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_currency_lists/interface/meliscommerce_currency_left_menu'
                                    ),
                                ),
                                'meliscommerce_order_status_lists' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_order_status_tool/interface/meliscommerce_order_status_tool_leftmenu'
                                    ),
                                ),
                                'meliscommerce_settings' => array(
                                    'conf' => array(
                                        'type' => 'meliscommerce/interface/meliscommerce_settings/interface/meliscommerce_settings_leftmenu'
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_title',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/ecommerce-common.js',
                    '/MelisCommerce/assets/common/fuelux-checkbox.js',
                    '/MelisCommerce/assets/common/jquery.qtip.min.js',
                    // '/MelisCommerce/assets/common/bootstrap-datetimepicker.min.js',
                    '/MelisCommerce/assets/common/bootstrap3-typeahead.min.js',
                    '/MelisCommerce/assets/common/awesomplete.min.js',
                    '/MelisCommerce/plugins/js/common/category-jstree.js'
                ),
                'css' => array(
                    '/MelisCommerce/assets/jstree/dist/themes/proton/style.min.css',
                    '/MelisCommerce/css/commerce-style.css',
                ),
                /**
                 * the "build" configuration compiles all assets into one file to make
                 * lesser requests
                 */
                'build' => [
                    //'disable_bundle' => true,
                    // lists of assets that will be loaded in the layout
                    'css' => [
                        '/MelisCommerce/build/css/bundle.css',

                    ],
                    'js' => [
                        '/MelisCommerce/build/js/bundle.js',
                    ]
                ]
            ),
            'datas' => array(
                'seo_default_pages' => array(
                    'category' => 1,
                    'product' => 1,
                    'variant' => 1,
                )
            ),
            'interface' => array(
            ),
        ),
    ),
);
