<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

return [
    'plugins' => [
        'meliscore' => [
            'interface' => [
                'meliscore_leftmenu' => [
                    'interface' => [
                        'meliscommerce_toolstree_section' => [
                            'interface' => [
                                'meliscommerce_categories' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_categories/interface/meliscommerce_categories_leftmenu'
                                    ],
                                ],
                                'meliscommerce_product_list' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_product_list/interface/meliscommerce_product_list_leftmenu'
                                    ],
                                ],
                                'meliscommerce_clients_list' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_clients_list/interface/meliscommerce_clients_list_leftmenu'
                                    ],
                                ],
                                'meliscommerce_contact_list' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_contact_list/interface/meliscommerce_contact_list_leftmenu'
                                    ],
                                ],
                                'meliscommerce_order_list' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_order_list/interface/meliscommerce_order_list_leftmenu'
                                    ],
                                ],
                                'meliscommerce_coupon_list' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_coupon_list/interface/meliscommerce_coupon_list_leftmenu'
                                    ],
                                ],
                                'meliscommerce_attribute_list' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_attribute_list/interface/meliscommerce_attribute_list_leftmenu'
                                    ],
                                ],
                                'meliscommerce_country_list' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_country_list/interface/meliscommerce_country_list_leftmenu'
                                    ],
                                ],
                                'meliscommerce_language_list' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_language_list/interface/meliscommerce_language_list_leftmenu'
                                    ],
                                ],
                                'meliscommerce_currency_lists' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_currency_lists/interface/meliscommerce_currency_left_menu'
                                    ],
                                ],
                                'meliscommerce_order_status_lists' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_order_status_tool/interface/meliscommerce_order_status_tool_leftmenu'
                                    ],
                                ],
                                'meliscommerce_clients_group_list' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_clients_group_tool/interface/meliscommerce_clients_group_tool_leftmenu'
                                    ],
                                ],

                                'meliscommerce_settings' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_settings/interface/meliscommerce_settings_leftmenu'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'meliscommerce' => [
            'conf' => [
                'id' => '',
                'name' => 'tr_meliscommerce_title',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/ecommerce-common.js',
                    '/MelisCommerce/assets/common/fuelux-checkbox.js',
                    '/MelisCommerce/assets/common/jquery.qtip.min.js',
                    '/MelisCommerce/assets/auto-complete/jquery.easy-autocomplete.min.js',
                    // '/MelisCommerce/assets/common/bootstrap-datetimepicker.min.js',
                    //'/MelisCommerce/assets/common/bootstrap3-typeahead.min.js',
                    '/MelisCommerce/assets/common/awesomplete.min.js',
                    '/MelisCommerce/plugins/js/common/category-jstree.js',
                    '/MelisCommerce/js/widget-collapsible.init.js',
                    '/MelisCommerce/js/tools/ecom.globals.js',
                ],
                'css' => [
                    '/MelisCommerce/assets/jstree/dist/themes/default/style.min.css',
                    '/MelisCommerce/css/easy-autocomplete.min.css',
                    '/MelisCommerce/css/commerce-style.css',
                ],
                /**
                 * the "build" configuration compiles all assets into one file to make
                 * lesser requests
                 */
                'build' => [
                    'disable_bundle' => true,
                    // lists of assets that will be loaded in the layout
                    'css' => [
                        '/MelisCommerce/build/css/bundle.css',

                    ],
                    'js' => [
                        '/MelisCommerce/build/js/bundle.js',
                    ]
                ]
            ],
            'datas' => [
                'seo_default_pages' => [
                    'category' => 1,
                    'product' => 1,
                    'variant' => 1,
                ]
            ],
            'interface' => [
            ],
        ],
    ],
];
