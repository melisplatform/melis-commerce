<?php
    return array(
        'plugins' => array(
            'meliscommerce' => array(
                'ressources' => array(
                    'css' => array(

                    ),
                    'js' => array(
                        '/MelisCommerce/plugins/js/MelisCommerceDashboardPluginSalesRevenue.js',
                    ),
                ),
                'dashboard_plugins' => array(
                    'MelisCommerceDashboardPluginSalesRevenue' => array(
                        'plugin_id' => 'MelisCommerceDashboardPluginSalesRevenue',
                        'name' => 'tr_melis_commerce_dashboard_plugin_sales_revenue',
                        'description' => 'tr_melis_commerce_dashboard_plugin_sales_revenue_description',
                        'icon' => 'fa fa-dollar',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceDashboardPluginSalesRevenue.jpg',
                        'jscallback' => 'commerceDashboardPluginSalesRevenueChartStackedBarsInit()',
                        'height' => 4,
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
                            'meliscommerce_dashboard_sales_revenue' => array(
                                'forward' => array(
                                    'module' => 'MelisCommerce',
                                    'plugin' => 'MelisCommerceDashboardPluginSalesRevenue',
                                    'function' => 'commerceSalesRevenue'
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );