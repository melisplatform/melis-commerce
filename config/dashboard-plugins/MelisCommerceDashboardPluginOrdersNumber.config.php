<?php
    return array(
        'plugins' => array(
            'meliscore' => [
                'interface' => [
                    'melis_dashboardplugin' => [
                        'conf' => [
                            'dashboard_plugin' => true
                        ],
                        'interface' => [
                            'melisdashboardplugin_section' => [
                                'interface' => [
                                    'MelisCommerceDashboardPluginOrdersNumber' => [
                                        'conf' => [
                                            'type' => '/meliscommerce/interface/MelisCommerceDashboardPluginOrdersNumber'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ],
            'meliscommerce' => array(
                'ressources' => array(
                    'css' => array(

                    ),
                    'js' => array(
                        '/MelisCommerce/plugins/js/MelisCommerceDashboardPluginOrdersNumber.js',
                    ),
                ),
                'interface' => array(
                    'MelisCommerceDashboardPluginOrdersNumber' => array(
                        'conf' => array(
                            'name' => 'MelisCommerceDashboardPluginOrdersNumber',
                            'melisKey' => 'MelisCommerceDashboardPluginOrdersNumber'
                        ),
                        'datas' => array(
                            'plugin_id' => 'MelisCommerceDashboardPluginOrderMessages',
                            'name' => 'tr_melis_commerce_dashboard_plugin_orders_number',
                            'description' => 'tr_melis_commerce_dashboard_plugin_orders_number_description',
                            'icon' => 'fa fa-shopping-cart',
                            'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceOrdersPlugin.jpg',
                            'jscallback' => 'commerceDashboardOrdersLineGraphInit()',
                            'max_lines' => 8,
                            'height' => 4,
                            'width' => 6,
                            'x-axis' => 0,
                            'y-axis' => 0,
                            'activeFilter' => 'hourly',
                            /*
                             * if set this plugin will belong to a specific marketplace section,
                               * if not it will go directly to ( Others ) section
                               *  - available section for dashboard plugins as of 2019-05-16
                               *    - MelisCore
                               *    - MelisCms
                               *    - MelisMarketing
                               *    - MelisSite
                               *    - MelisCommerce
                               *    - Others
                               *    - CustomProjects
                             */
                            'section' => 'MelisCommerce',
                        ),
                        'forward' => array(
                            'module' => 'MelisCommerce',
                            'plugin' => 'MelisCommerceDashboardPluginOrdersNumber',
                            'function' => 'commerceOrders',
                            'jscallback' => '',
                            'jsdatas' => array()
                        ),
                    ),
                ),
            ),
        ),
    );