<?php
    return array(
        'plugins' => array(
            'meliscommerce' => array(
                'ressources' => array(
                    'css' => array(

                    ),
                    'js' => array(
                        '/MelisCommerce/plugins/js/MelisCommerceDashboardPluginOrdersNumber.js',
                    ),
                ),
                'dashboard_plugins' => array(
                    'MelisCommerceDashboardPluginOrdersNumber' => array(
                        'plugin_id' => 'MelisCommerceDashboardPluginOrdersNumber',
                        'name' => 'tr_melis_commerce_dashboard_plugin_orders_number',
                        'description' => 'tr_melis_commerce_dashboard_plugin_orders_number_description',
                        'icon' => 'fa fa-shopping-cart',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceOrdersPlugin.jpg',
                        'jscallback' => 'commerceDashboardOrdersLineGraphInit()',
                        'height' => 7,
                        'activeFilter' => 'hourly',
                        /*
                         * if set this plugin will belong to a specific marketplace section,
                         * if not it will go directly to ( Others ) section
                         *  - available section for templating plugins as of 2019-05-16
                         *    - MelisCore
                         *    - MelisCms
                         *    - MelisMarketing
                         *    - MelisSite
                         *    - MelisCommerce
                         *    - Others
                         *    - CustomProjects
                         */
                        'section' => 'MelisCommerce',
                        'interface' => array(
                            'meliscommerce_dashboard_orders_number' => array(
                                'forward' => array(
                                    'module' => 'MelisCommerce',
                                    'plugin' => 'MelisCommerceDashboardPluginOrdersNumber',
                                    'function' => 'commerceOrders'
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );