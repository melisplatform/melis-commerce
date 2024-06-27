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
        'meliscommerce' => [
            'ressources' => [
                'css' => [

                ],
                'js' => [
                    '/MelisCommerce/plugins/js/MelisCommerceDashboardPluginSalesRevenue.js',
                ],
            ],
            'interface' => [
                'MelisCommerceDashboardPluginSalesRevenue' => [
                    'conf' => [
                        'name' => 'MelisCommerceDashboardPluginSalesRevenue',
                        'melisKey' => 'MelisCommerceDashboardPluginSalesRevenue'
                    ],
                    'datas' => [
                        'plugin_id' => 'MelisCommerceDashboardPluginSalesRevenue',
                        'name' => 'tr_melis_commerce_dashboard_plugin_sales_revenue',
                        'description' => 'tr_melis_commerce_dashboard_plugin_sales_revenue_description',
                        'icon' => 'fa fa-dollar',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceDashboardPluginSalesRevenue.jpg',
                        'jscallback' => 'commerceDashboardPluginSalesRevenue.loadChart()',
                        'max_lines' => 8,
                        'height' => 4,
                        'width' => 6,
                        'x-axis' => 0,
                        'y-axis' => 0,
                        'activeFilter' => 'hourly',
                        /*
                         * if set this plugin will belong to a specific marketplace section,
                           * if not it will go directly to ( Others ] section
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
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'plugin' => 'MelisCommerceDashboardPluginSalesRevenue',
                        'function' => 'commerceSalesRevenue',
                        'jscallback' => '',
                        'jsdatas' => []
                    ],
                    'modal_form' => [
                        'revenue_plugin_tab_01' => array(
                            'tab_title' => 'tr_meliscommerce_general_common_configuration',
                            'tab_icon'  => 'fa fa-cog',
                            'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                            'attributes' => array(
                                'name' => 'revenue_plugin_tab_01',
                                'id' => 'revenue_plugin_tab_01',
                                'method' => '',
                                'action' => '',
                            ),
                            'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                            'elements' => array(
                                array(
                                    'spec' => array(
                                        'name' => 'activeFilter',
                                        'type' => 'Select',
                                        'options' => [
                                            'label' => 'tr_meliscommerce_general_default_filter',
                                            'disable_inarray_validator' => true,
                                            'value_options' => [
                                                'hourly' => 'tr_melis_commerce_orders_dashboard_statistics_hourly',
                                                'daily' => 'tr_melis_commerce_orders_dashboard_statistics_daily',
                                                'weekly' => 'tr_melis_commerce_orders_dashboard_statistics_weekly',
                                                'monthly' => 'tr_melis_commerce_orders_dashboard_statistics_monthly',
                                            ],
                                        ],
                                        'attributes' => array(
                                            'id' => 'activeFilter',
                                            'class' => 'form-control'
                                        ),
                                    ),
                                ),
                            ),
                            'input_filter' => array(
                                'activeFilter' => array(
                                    'name'     => 'activeFilter',
                                    'required' => false,
                                    'validators' => array(
                                        
                                    ),
                                    'filters'  => array(
                                    ),
                                ),
                            )
                        )
                    ]
                ],
            ],
        ],
    ],
];