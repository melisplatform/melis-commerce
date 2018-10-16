<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'ressources' => array(
                'css' => array(

                ),
                'js' => array(
                    '/MelisCommerce/plugins/js/MelisCommerceDashboardPluginOrderMessages.js',
                ),
            ),
            'dashboard_plugins' => array(
                'MelisCommerceDashboardPluginOrderMessages' => array(
                    'plugin_id' => 'MelisCommerceDashboardPluginOrderMessages',
                    'name' => 'tr_melis_commerce_dashboard_plugin_order_messages',
                    'description' => 'tr_melis_commerce_dashboard_plugin_order_messages_description',
                    'icon' => 'fa fa-inbox',
                    'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceDashboardPluginOrderMessages.jpg',
                    'jscallback' => 'commerceDashboardPluginOrderMessagesInit()',
                    'height' => 6,
                    'width' => 4,
                    'deleteCallback' => 'commerceDasboardPluginOrderMessagesDelete',

                    'interface' => array(
                        'melis_commerce_dashboard_plugin_order_messages' => array(
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'plugin' => 'MelisCommerceDashboardPluginOrderMessages',
                                'function' => 'orderMessages'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);