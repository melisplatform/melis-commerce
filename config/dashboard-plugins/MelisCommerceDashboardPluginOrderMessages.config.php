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