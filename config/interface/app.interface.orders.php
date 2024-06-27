<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

return [
    'plugins' => [
        'meliscommerce' => [
            'conf' => [
                'id' => '',
                'name' => 'tr_meliscommerce_orders_Orders',
                'rightsDisplay' => 'none',
                'orderStatus' => [
                    'new' => 1,
                    'onHold' => 2,
                    'shipped' => 3,
                    'delivered' => 4,
                    'cancelled' => 5,
                    'errorPayment' => 6,
                    'temporary' => -1
                ],
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/order.tool.js',
                    '/MelisCommerce/assets/bootstrap-colorpicker-master/dist/js/bootstrap-colorpicker.js',
                ],
                'css' => [
                    '/MelisCommerce/assets/bootstrap-colorpicker-master/dist/css/bootstrap-colorpicker.css',
                    '/MelisCommerce/css/order-steps.css',
                ],
            ],
            'datas' => [
                'default' => [
                    'export' => [
                        'csv' => [
                            'orderFileName' => 'melis_order_export.csv',
                            'orderLimit' => 100,
                            'dir' => $_SERVER['DOCUMENT_ROOT'] . '/csv/'
                        ],
                    ],
                    'permanent_order_status' => [1,2,3,4,5,6,-1],
                ],
            ],
            'interface' => [
                'meliscommerce_order_status_tool' => [
                    'interface' => [
                        'meliscommerce_order_status_tool_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_order_status_tool_page',
                                'melisKey' => 'meliscommerce_order_status_tool_page',
                                'name' => 'tr_meliscommerce_order_status_tool_leftmenu',
                                'icon' => 'fa fa-plus-square',
                            ],
                        ],
                        'meliscommerce_order_status_tool_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_order_status_tool_page',
                                'melisKey' => 'meliscommerce_order_status_tool_page',
                                'name' => 'tr_meliscommerce_order_status_tool_page',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderStatus',
                                'action' => 'render-order-status-page',
                                'jscallback' => '',
                                'jsdatas' => []
                            ],
                            'interface' => [
                                'meliscommerce_order_status_header_container' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_status_header_container',
                                        'melisKey' => 'meliscommerce_order_status_header_container',
                                        'name' => 'tr_meliscommerce_order_status_header_container',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderStatus',
                                        'action' => 'render-order-status-header-container',
                                    ],
                                    'interface' => [
                                        'meliscommerce_order_status_header_left_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_status_header_left_container',
                                                'melisKey' => 'meliscommerce_order_status_header_left_container',
                                                'name' => 'tr_meliscommerce_order_status_header_left_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderStatus',
                                                'action' => 'render-order-status-header-left-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_order_status_header_title' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_status_header_title',
                                                        'melisKey' => 'meliscommerce_order_status_header_title',
                                                        'name' => 'tr_meliscommerce_order_status_header_title',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderStatus',
                                                        'action' => 'render-order-status-header-title',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_order_status_header_right_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_status_header_right_container',
                                                'melisKey' => 'meliscommerce_order_status_header_right_container',
                                                'name' => 'tr_meliscommerce_order_status_header_right_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderStatus',
                                                'action' => 'render-order-status-header-right-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_order_status_add_order' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_status_add_order',
                                                        'melisKey' => 'meliscommerce_order_status_add_order',
                                                        'name' => 'tr_meliscommerce_order_status_add_order',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderStatus',
                                                        'action' => 'render-order-status-add',
                                                    ],
                                                ]
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_order_status_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_status_content',
                                        'melisKey' => 'meliscommerce_order_status_content',
                                        'name' => 'tr_meliscommerce_order_status_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderStatus',
                                        'action' => 'render-order-status-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_order_status_content_table' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_status_content_table',
                                                'melisKey' => 'meliscommerce_order_status_content_table',
                                                'name' => 'tr_meliscommerce_order_status_content_table',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderStatus',
                                                'action' => 'render-order-status-content-table',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'meliscommerce_order_list' => [
                    'interface' => [
                        'meliscommerce_order_list_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_order_list_page',
                                'melisKey' => 'meliscommerce_order_list_page',
                                'name' => 'tr_meliscommerce_orders_Orders',
                                'icon' => 'fa fa-cart-plus',
                            ],
                        ],
                        'meliscommerce_order_list_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_order_list_page',
                                'melisKey' => 'meliscommerce_order_list_page',
                                'name' => 'tr_meliscommerce_orders_Orders',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderList',
                                'action' => 'render-order-list-page',
                                'jscallback' => '',
                                'jsdatas' => []
                            ],
                            'interface' => [
                                'meliscommerce_order_list_header_container' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_list_header_container',
                                        'melisKey' => 'meliscommerce_order_list_header_container',
                                        'name' => 'tr_meliscommerce_order_list_header_container',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderList',
                                        'action' => 'render-order-list-header-container',
                                    ],
                                    'interface' => [
                                        'meliscommerce_order_list_header_left_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_list_header_left_container',
                                                'melisKey' => 'meliscommerce_order_list_header_left_container',
                                                'name' => 'tr_meliscommerce_order_list_header_left_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-header-left-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_order_list_header_title' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_list_header_title',
                                                        'melisKey' => 'meliscommerce_order_list_header_title',
                                                        'name' => 'tr_meliscommerce_order_list_header_title',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderList',
                                                        'action' => 'render-order-list-header-title',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_order_list_header_right_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_list_header_right_container',
                                                'melisKey' => 'meliscommerce_order_list_header_right_container',
                                                'name' => 'tr_meliscommerce_order_list_header_right_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-header-right-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_order_list_add_order' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_order_list_add_order',
                                                        'melisKey' => 'meliscommerce_order_list_add_order',
                                                        'name' => 'tr_meliscommerce_order_list_add_order',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderList',
                                                        'action' => 'render-order-list-add-order',
                                                    ],
                                                ]
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_order_list_widgets' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_list_widgets',
                                        'melisKey' => 'meliscommerce_order_list_widgets',
                                        'name' => 'tr_meliscommerce_order_list_widgets',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderList',
                                        'action' => 'render-order-list-widgets',
                                    ],
                                    'interface' => [
                                        'meliscommerce_order_list_widgets_num_orders' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_list_widgets_num_orders',
                                                'melisKey' => 'meliscommerce_order_list_widgets_num_orders',
                                                'name' => 'tr_meliscommerce_order_list_widgets_num_orders',
                                                'width' => '4'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-widgets-num-orders',
                                            ],
                                        ],
                                        'meliscommerce_order_list_widgets_month_orders' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_list_widgets_month_orders',
                                                'melisKey' => 'meliscommerce_order_list_widgets_month_orders',
                                                'name' => 'tr_meliscommerce_order_list_widgets_month_orders',
                                                'width' => '4'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-widgets-month-orders',
                                            ],
                                        ],
                                        'meliscommerce_order_list_widgets_avg_orders' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_list_widgets_avg_orders',
                                                'melisKey' => 'meliscommerce_order_list_widgets_avg_orders',
                                                'name' => 'tr_meliscommerce_order_list_widgets_avg_orders',
                                                'width' => '4'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-widgets-avg-orders',
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_order_list_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_order_list_content',
                                        'melisKey' => 'meliscommerce_order_list_content',
                                        'name' => 'tr_meliscommerce_order_list_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderList',
                                        'action' => 'render-order-list-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_order_list_content_table' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_order_list_content_table',
                                                'melisKey' => 'meliscommerce_order_list_content_table',
                                                'name' => 'tr_meliscommerce_order_list_content_table',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-content-table',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'meliscommerce_orders' => [
                    'interface' => [
                        'meliscommerce_orders_leftmenu' => [
                            'conf' => [
                                'id' => '_id_meliscommerce_orders_page',
                                'melisKey' => 'meliscommerce_orders_page',
                                'name' => 'tr_meliscommerce_orders_Orders',
                                'icon' => 'fa fa-cart-plus',
                            ],
                        ],
                        'meliscommerce_orders_page' => [
                            'conf' => [
                                'id' => '_id_meliscommerce_orders_page',
                                'melisKey' => 'meliscommerce_orders_page',
                                'name' => 'tr_meliscommerce_orders_Orders',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrder',
                                'action' => 'render-orders-page',
                                'jscallback' => '',
                                'jsdatas' => []
                            ],
                            'interface' => [
                                'meliscommerce_orders_header_container' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_orders_header_container',
                                        'melisKey' => 'meliscommerce_orders_header_container',
                                        'name' => 'tr_meliscommerce_orders_header_container',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrder',
                                        'action' => 'render-orders-header-container',
                                    ],
                                    'interface' => [
                                        'meliscommerce_orders_header_left_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_orders_header_left_container',
                                                'melisKey' => 'meliscommerce_orders_header_left_container',
                                                'name' => 'tr_meliscommerce_orders_header_left_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrder',
                                                'action' => 'render-orders-header-left-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_orders_header_title' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_header_title',
                                                        'melisKey' => 'meliscommerce_orders_header_title',
                                                        'name' => 'tr_meliscommerce_orders_header_title',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-header-title',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_orders_header_right_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_orders_header_right_container',
                                                'melisKey' => 'meliscommerce_orders_header_right_container',
                                                'name' => 'tr_meliscommerce_orders_header_right_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrder',
                                                'action' => 'render-orders-header-right-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_orders_header_right_container_save' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_header_right_container_save',
                                                        'melisKey' => 'meliscommerce_orders_header_right_container_save',
                                                        'name' => 'tr_meliscommerce_orders_header_right_container_save',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-header-right-container-save',
                                                    ],
                                                ],
//                                                   'meliscommerce_orders_header_right_container_cancel' => [
//                                                       'conf' => [
//                                                           'id' => 'id_meliscommerce_orders_header_right_container_cancel',
//                                                           'melisKey' => 'meliscommerce_orders_header_right_container_cancel',
//                                                           'name' => 'tr_meliscommerce_orders_header_right_container_cancel',
//                                                       ],
//                                                       'forward' => [
//                                                           'module' => 'MelisCommerce',
//                                                           'controller' => 'MelisComOrder',
//                                                           'action' => 'render-orders-header-right-container-cancel',
//                                                       ],
//                                                   ],
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_orders_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_orders_content',
                                        'melisKey' => 'meliscommerce_orders_content',
                                        'name' => 'tr_meliscommerce_orders_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrder',
                                        'action' => 'render-orders-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_orders_content_tabs' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_orders_content_tabs',
                                                'melisKey' => 'meliscommerce_orders_content_tabs',
                                                'name' => 'tr_meliscommerce_orders_content_tabs',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrder',
                                                'action' => 'render-orders-content-tabs',
                                            ],
                                            'interface' => [
                                                'meliscommerce_orders_content_tab_main' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tab_main',
                                                        'melisKey' => 'meliscommerce_orders_content_tab_main',
                                                        'name' => 'tr_meliscommerce_orders_content_tab_main',
                                                        'href' => 'id_meliscommerce_orders_content_tabs_content_main',
                                                        'icon' => 'glyphicons tag',
                                                        'active' => 'active',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tab',
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tab_basket' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tab_basket',
                                                        'melisKey' => 'meliscommerce_orders_content_tab_basket',
                                                        'name' => 'tr_meliscommerce_orders_content_tab_basket',
                                                        'href' => 'id_meliscommerce_orders_content_tabs_content_baskets',
                                                        'icon' => 'glyphicons shopping_bag',
                                                        'active' => '',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tab',
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tab_address' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tab_address',
                                                        'melisKey' => 'meliscommerce_orders_content_tab_address',
                                                        'name' => 'tr_meliscommerce_orders_content_tab_address',
                                                        'href' => 'id_meliscommerce_orders_content_tabs_content_address',
                                                        'icon' => 'glyphicons google_maps',
                                                        'active' => '',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tab',
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tab_paymnet' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tab_paymnet',
                                                        'melisKey' => 'meliscommerce_orders_content_tab_paymnet',
                                                        'name' => 'tr_meliscommerce_orders_content_tab_paymnet',
                                                        'href' => 'id_meliscommerce_orders_content_tabs_content_payment',
                                                        'icon' => 'glyphicons money',
                                                        'active' => '',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tab',
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tab_shipping' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tab_shipping',
                                                        'melisKey' => 'meliscommerce_orders_content_tab_shipping',
                                                        'name' => 'tr_meliscommerce_orders_content_tab_shipping',
                                                        'href' => 'id_meliscommerce_orders_content_tabs_content_shipping',
                                                        'icon' => 'glyphicons boat',
                                                        'active' => '',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tab',
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tab_messages' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tab_messages',
                                                        'melisKey' => 'meliscommerce_orders_content_tab_messages',
                                                        'name' => 'tr_meliscommerce_orders_content_tab_messages',
                                                        'href' => 'id_meliscommerce_orders_content_tabs_content_messages',
                                                        'icon' => 'glyphicons chat',
                                                        'active' => '',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tab',
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tab_return_products' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tab_return_products',
                                                        'melisKey' => 'meliscommerce_orders_content_tab_return_products',
                                                        'name' => 'tr_meliscommerce_orders_content_tab_return_products',
                                                        'href' => 'id_meliscommerce_orders_content_tabs_content_return_products',
                                                        'icon' => 'glyphicons cart_out',
                                                        'active' => '',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tab',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_orders_content_tabs_content' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_orders_content_tabs_content',
                                                'melisKey' => 'meliscommerce_orders_content_tabs_content',
                                                'name' => 'tr_meliscommerce_orders_content_tabs_content',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrder',
                                                'action' => 'render-orders-content-tabs-content',
                                            ],
                                            'interface' => [
                                                'meliscommerce_orders_content_tabs_content_main' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_main',
                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_main',
                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_main',
                                                        'active' => 'active',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tabs-content-container',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_orders_content_tabs_content_main_header' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_main_header',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_main_header',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_main_header',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-header',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_main_left_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_main_left_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_main_left_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_main_left_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-left-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_main_left_header_title' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_main_left_header_title',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_main_left_header_title',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_main_left_header_title',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_orders_content_tabs_content_main_right_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_main_right_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_main_right_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_main_right_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-right-header',
                                                                    ],
                                                                    'interface' => [

                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_orders_content_tabs_content_main_details' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_main_details',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-details',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_main_details_left' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_left',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_left',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details_left',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-main-details-left',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_main_details_sub_header' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_sub_header',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_sub_header',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details_sub_header',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-details-sub-header',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_orders_content_tabs_content_main_details_sub_header_left' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_sub_header_left',
                                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_sub_header_left',
                                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details_sub_header_left',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComOrder',
                                                                                        'action' => 'render-orders-content-tabs-content-details-sub-header-left',
                                                                                    ],
                                                                                    'interface' => [
                                                                                        'meliscommerce_orders_content_tabs_content_main_details_sub_header_title' => [
                                                                                            'conf' => [
                                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_sub_header_title',
                                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_sub_header_title',
                                                                                                'name' => 'tr_meliscommerce_orders_Order',
                                                                                                'icon' => 'fa fa-shopping-cart',
                                                                                            ],
                                                                                            'forward' => [
                                                                                                'module' => 'MelisCommerce',
                                                                                                'controller' => 'MelisComOrder',
                                                                                                'action' => 'render-orders-content-tabs-content-details-sub-header-title',
                                                                                            ],
                                                                                            'interface' => [],
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                                'meliscommerce_orders_content_tabs_content_main_details_sub_header_right' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_sub_header_right',
                                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_sub_header_right',
                                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details_sub_header_right',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComOrder',
                                                                                        'action' => 'render-orders-content-tabs-content-details-sub-header-right',
                                                                                    ],
                                                                                    'interface' => [],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                        'meliscommerce_orders_content_tabs_content_main_details_sub_content' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_sub_content',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_sub_content',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details_sub_content',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-details-sub-content',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_orders_content_tabs_content_main_orderform' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_main_orderform',
                                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_main_orderform',
                                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_main_orderform',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComOrder',
                                                                                        'action' => 'render-orders-content-tabs-content-main-order-form',
                                                                                    ],
                                                                                    'interface' => [],
                                                                                ],
                                                                                'meliscommerce_orders_content_tabs_content_file_attachments' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_file_attachments',
                                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_file_attachments',
                                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_file_attachments',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComOrder',
                                                                                        'action' => 'render-orders-content-tabs-content-file-attachments',
                                                                                    ],
                                                                                    'interface' => [
                                                                                        'meliscommerce_orders_content_tabs_content_file_attachments_plugin' => [
                                                                                            'conf' => [
                                                                                                'type' => 'meliscommerce/interface/meliscommerce_documents_file_attachments_conf',
                                                                                                'docRelationType' => 'order',
                                                                                            ]
                                                                                        ]
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tabs_content_baskets' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_baskets',
                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets',
                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets',
                                                        'active' => '',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tabs-content-container',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_orders_content_tabs_content_baskets_header' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_header',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_header',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_header',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-header',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_baskets_left_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_left_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_left_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_left_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-left-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_baskets_left_header_title' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_left_header_title',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_left_header_title',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_left_header_title',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_orders_content_tabs_content_baskets_right_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_right_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_right_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_right_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-right-header',
                                                                    ],
                                                                    'interface' => [

                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_orders_content_tabs_content_baskets_details' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_details',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_details',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_details',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-details',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_baskets_details_list' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_details_list',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_details_list',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_details_list',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-basket-list',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tabs_content_address' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_address',
                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_address',
                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_address',
                                                        'active' => '',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tabs-content-container',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_orders_content_tabs_content_header' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_header',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_header',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_header',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-header',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_address_left_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_address_left_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_address_left_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_address_left_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-left-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_address_left_header_title' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_address_left_header_title',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_address_left_header_title',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_address_left_header_title',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_orders_content_tabs_content_address_right_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_address_right_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_address_right_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_address_right_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-right-header',
                                                                    ],
                                                                    'interface' => [

                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_orders_content_tabs_content_address_details' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_address_details',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_address_details',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_address_details',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-details',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_address_details_left' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_address_details_left',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_address_details_left',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_address_details_left',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-address-details-left',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_address_details_tabs' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_address_details_tabs',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_address_details_tabs',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_address_details_tabs',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-address-details-tabs',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_orders_content_tabs_content_address_details_right' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_address_details_right',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_address_details_right',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_address_details_right',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-address-details-right',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_address_details_address_form' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_address_details_address_form',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_address_details_address_form',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_address_details_address_form',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-address-details-address-form',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tabs_content_payment' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_payment',
                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_payment',
                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_payment',
                                                        'active' => '',
                                                        'accord' => 'accordion-list',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tabs-content-container',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_orders_content_tabs_content_payment_header' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_payment_header',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_header',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_header',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-header',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_payment_left_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_payment_left_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_left_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_left_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-left-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_payment_left_header_title' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_payment_left_header_title',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_left_header_title',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_left_header_title',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_orders_content_tabs_content_payment_right_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_payment_right_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_right_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_right_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-right-header',
                                                                    ],
                                                                    'interface' => [

                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_orders_content_tabs_content_payment_details' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_payment_details',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_details',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_details',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-details',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_payment_details_content' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_payment_details_content',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_details_content',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_details_content',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-details-large',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_payment_details_content_list' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_payment_details_content_list',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_details_content_list',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_details_content_list',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-payment-details-content-list',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tabs_content_shipping' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_shipping',
                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping',
                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping',
                                                        'active' => '',
                                                        'accord' => 'accordion-list',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tabs-content-container',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_orders_content_tabs_content_shipping_header' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_header',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_header',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_header',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-header',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_shipping_left_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_left_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_left_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_left_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-left-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_shipping_left_header_title' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_left_header_title',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_left_header_title',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_left_header_title',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_orders_content_tabs_content_shipping_right_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_right_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_right_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_right_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-right-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_shipping_right_header_add' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_right_header_add',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_right_header_add',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_right_header_add',
                                                                                'icon' => 'fa fa-plus'
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-shipping-right-header-add',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_orders_content_tabs_content_shipping_details' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_details',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_details',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_details',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-details',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_shipping_details_content' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-details-large',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_shipping_details_content_list' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-shipping-details-content-list',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tabs_content_messages' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_messages',
                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_messages',
                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_messages',
                                                        'active' => '',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tabs-content-container',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_orders_content_tabs_content_messages_header' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_messages_header',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_header',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_header',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-header',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_messages_left_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_messages_left_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_left_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_left_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-left-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_messages_left_header_title' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_messages_left_header_title',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_left_header_title',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_left_header_title',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_orders_content_tabs_content_messages_right_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_messages_right_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_right_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_right_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-right-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_messages_right_header_add' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_messages_right_header_add',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_right_header_add',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_right_header_add',
                                                                                'icon' => 'fa fa-plus'
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-messages-right-header-add',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_orders_content_tabs_content_messages_details' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_messages_details',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_details',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_details',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-messages-details',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_messages_message_form' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_messages_message_form',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_message_form',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_message_form',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-messages-message-form',
                                                                    ],
                                                                ],
                                                                'meliscommerce_orders_content_tabs_content_messages_timeline_container' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_messages_timeline_container',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_timeline_container',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_timeline_container',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-messages-timeline-container',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_messages_timeline' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_messages_timeline',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_timeline',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_timeline',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-messages-timeline',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_orders_content_tabs_content_return_products' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_return_products',
                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_return_products',
                                                        'name' => 'tr_meliscommerce_orders_content_tab_return_products',
                                                        'active' => '',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrder',
                                                        'action' => 'render-orders-content-tabs-content-container',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_orders_content_tabs_content_return_products_header' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_return_products_header',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_return_products_header',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_return_products_header',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrder',
                                                                'action' => 'render-orders-content-tabs-content-header',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_return_products_left_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_return_products_left_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_return_products_left_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_return_products_left_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-left-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_return_products_left_header_title' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_return_products_left_header_title',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_return_products_left_header_title',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_return_products_left_header_title',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrder',
                                                                                'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_orders_content_tabs_content_return_products_right_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_return_products_right_header',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_return_products_right_header',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_return_products_right_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrder',
                                                                        'action' => 'render-orders-content-tabs-content-right-header',
                                                                    ],
                                                                    'interface' => [

                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_orders_content_tabs_content_return_products_content' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_return_products_content',
                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_return_products_content',
                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_return_products_content',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComOrderProductReturn',
                                                                'action' => 'render-order-product-return-content',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_orders_content_tabs_content_return_products_content_list' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_return_products_content_list',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_return_products_content_list',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_return_products_content_list',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrderProductReturn',
                                                                        'action' => 'render-order-product-return-content-list',
                                                                    ],
                                                                ],
                                                                'meliscommerce_orders_content_tabs_content_return_products_content_message' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_orders_content_tabs_content_return_products_content_message',
                                                                        'melisKey' => 'meliscommerce_orders_content_tabs_content_return_products_content_message',
                                                                        'name' => 'tr_meliscommerce_orders_content_tabs_content_return_products_content_message',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComOrderProductReturn',
                                                                        'action' => 'render-order-product-return-content-message',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_orders_content_tabs_content_return_products_content_message_header' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_return_products_content_message_header',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_return_products_content_message_header',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_return_products_content_message_header',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrderProductReturn',
                                                                                'action' => 'render-order-product-return-content-message-header',
                                                                            ],
                                                                        ],
                                                                        'meliscommerce_orders_content_tabs_content_return_products_content_message_timeline' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_orders_content_tabs_content_return_products_content_message_timeline',
                                                                                'melisKey' => 'meliscommerce_orders_content_tabs_content_return_products_content_message_timeline',
                                                                                'name' => 'tr_meliscommerce_orders_content_tabs_content_return_products_content_message_timeline',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComOrderProductReturn',
                                                                                'action' => 'render-order-product-return-content-message-timeline',
                                                                            ],
                                                                        ],
                                                                    ]
                                                                ],
                                                            ]
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'meliscommerce_order_list_modal' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_order_list_modal',
                        'name' => 'tr_meliscommerce_order_list_modal',
                        'melisKey' => 'meliscommerce_order_list_modal',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComOrderList',
                        'action' => 'render-order-list-modal',
                    ],
                    'interface' => [
                        'meliscommerce_order_list_content_status_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_order_list_content_status_form',
                                'name' => 'tr_meliscommerce_order_list_content_status_form',
                                'melisKey' => 'meliscommerce_order_list_content_status_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderList',
                                'action' => 'render-order-list-content-status-form',
                            ],
                        ],
                        'meliscommerce_order_list_content_export_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_order_list_content_export_form',
                                'name' => 'tr_meliscommerce_order_list_content_export_form',
                                'melisKey' => 'meliscommerce_order_list_content_export_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderList',
                                'action' => 'render-order-list-content-export-form',
                            ],
                        ],
                    ],
                ],
                'meliscommerce_order_modal' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_order_modal',
                        'name' => 'tr_meliscommerce_order_modal',
                        'melisKey' => 'meliscommerce_order_modal',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComOrder',
                        'action' => 'render-order-modal',
                    ],
                    'interface' => [
                        'meliscommerce_order_modal_content_shipping_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_order_modal_content_shipping_form',
                                'name' => 'tr_meliscommerce_order_modal_content_shipping_form',
                                'melisKey' => 'meliscommerce_order_modal_content_shipping_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrder',
                                'action' => 'render-order-modal-content-shipping-form',
                            ],
                        ],
                    ],
                ],
                'meliscommerce_order_status_modal' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_order_status_modal',
                        'name' => 'tr_meliscommerce_order_status_modal',
                        'melisKey' => 'meliscommerce_order_status_modal',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComOrderStatus',
                        'action' => 'render-order-status-modal',
                    ],
                    'interface' => [
                        'meliscommerce_order_status_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_order_status_form',
                                'name' => 'tr_meliscommerce_order_status_form',
                                'melisKey' => 'meliscommerce_order_status_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderStatus',
                                'action' => 'render-order-status-form',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];