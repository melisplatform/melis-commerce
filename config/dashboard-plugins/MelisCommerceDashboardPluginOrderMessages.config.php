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
                                'MelisCommerceDashboardPluginOrderMessages' => [
                                    'conf' => [
                                        'type' => '/meliscommerce/interface/MelisCommerceDashboardPluginOrderMessages'
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
                    '/MelisCommerce/plugins/js/MelisCommerceDashboardPluginOrderMessages.js',
                ],
            ],
            'interface' => [
                'MelisCommerceDashboardPluginOrderMessages' => [
                    'conf' => [
                        'name' => 'MelisCommerceDashboardPluginOrderMessages',
                        'melisKey' => 'MelisCommerceDashboardPluginOrderMessages'
                    ],
                    'datas' => [
                        'plugin_id' => 'MelisCommerceDashboardPluginOrderMessages',
                        'name' => 'tr_melis_commerce_dashboard_plugin_order_messages',
                        'description' => 'tr_melis_commerce_dashboard_plugin_order_messages_description',
                        'icon' => 'fa fa-inbox',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceDashboardPluginOrderMessages.jpg',
                        'jscallback' => 'commerceDashboardPluginOrderMessagesInit(]',
                        'max_lines' => 8,
                        'height' => 4,
                        'width' => 6,
                        'x-axis' => 0,
                        'y-axis' => 0,
                        'deleteCallback' => 'commerceDasboardPluginOrderMessagesDelete',
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
                        'plugin' => 'MelisCommerceDashboardPluginOrderMessages',
                        'function' => 'orderMessages',
                        'jscallback' => '',
                        'jsdatas' => []
                    ],
                ],
            ],
        ],
    ],
];