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
                                    'MelisCommerceDashboardPluginSalesRevenue' => [
                                        'conf' => [
                                            'type' => '/meliscommerce/interface/MelisCommerceDashboardPluginSalesRevenue'
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
                        '/MelisCommerce/plugins/js/MelisCommerceDashboardPluginSalesRevenue.js',
                    ),
                ),
                'interface' => array(
                    'MelisCommerceDashboardPluginSalesRevenue' => array(
                        'conf' => array(
                            'name' => 'tr_melis_commerce_dashboard_plugin_sales_revenue',
                            'melisKey' => 'MelisCommerceDashboardPluginSalesRevenue'
                        ),
                        'datas' => array(
                            'plugin_id' => 'MelisCommerceDashboardPluginSalesRevenue',
                            'name' => 'tr_melis_commerce_dashboard_plugin_sales_revenue',
                            'description' => 'tr_melis_commerce_dashboard_plugin_sales_revenue_description',
                            'icon' => 'fa fa-dollar',
                            'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceDashboardPluginSalesRevenue.jpg',
                            'jscallback' => 'commerceDashboardPluginSalesRevenueChartStackedBarsInit()',
                            'max_lines' => 8,
                            'height' => 4,
                            'width' => 4,
                            'x-axis' => 0,
                            'y-axis' => 0,
                            'activeFilter' => 'hourly',
                        ),
                        'forward' => array(
                            'module' => 'MelisCommerce',
                            'plugin' => 'MelisCommerceDashboardPluginSalesRevenue',
                            'function' => 'commerceSalesRevenue',
                            'jscallback' => '',
                            'jsdatas' => array()
                        ),
                    ),
                ),
            ),
        ),
    );