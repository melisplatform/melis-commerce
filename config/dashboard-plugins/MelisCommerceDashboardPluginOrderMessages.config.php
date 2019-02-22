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
        'meliscommerce' => array(
            'ressources' => array(
                'css' => array(

                ),
                'js' => array(
                    '/MelisCommerce/plugins/js/MelisCommerceDashboardPluginOrderMessages.js',
                ),
            ),
            'interface' => array(
                'MelisCommerceDashboardPluginOrderMessages' => array(
                    'conf' => array(
                        'name' => 'MelisCommerceDashboardPluginOrderMessages',
                        'melisKey' => 'MelisCommerceDashboardPluginOrderMessages'
                    ),
                    'datas' => array(
                        'plugin_id' => 'MelisCommerceDashboardPluginOrderMessages',
                        'name' => 'tr_melis_commerce_dashboard_plugin_order_messages',
                        'description' => 'tr_melis_commerce_dashboard_plugin_order_messages_description',
                        'icon' => 'fa fa-inbox',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceDashboardPluginOrderMessages.jpg',
                        'jscallback' => 'commerceDashboardPluginOrderMessagesInit()',
                        'max_lines' => 8,
                        'height' => 4,
                        'width' => 6,
                        'x-axis' => 0,
                        'y-axis' => 0,
                        'deleteCallback' => 'commerceDasboardPluginOrderMessagesDelete',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'plugin' => 'MelisCommerceDashboardPluginOrderMessages',
                        'function' => 'orderMessages',
                        'jscallback' => '',
                        'jsdatas' => array()
                    ),
                ),
            ),
        ),
    ),
);