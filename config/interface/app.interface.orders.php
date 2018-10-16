<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_orders_Orders',
                'rightsDisplay' => 'none',
                'orderStatus' => array(
                    'new' => 1,
                    'onHold' => 2,
                    'shipped' => 3,
                    'delivered' => 4,
                    'cancelled' => 5,
                    'errorPayment' => 6,
                    'temporary' => -1
                ),
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/order.tool.js',
                    '/MelisCommerce/assets/bootstrap-colorpicker-master/dist/js/bootstrap-colorpicker.js',
                ),
                'css' => array(
                    '/MelisCommerce/assets/bootstrap-colorpicker-master/dist/css/bootstrap-colorpicker.css',
 					'/MelisCommerce/css/order-steps.css',
                ),
            ),
            'datas' => array(
                'default' => array(
                    'export' => array(
                        'csv' => array(
                            'orderFileName' => 'melis_order_export.csv', 
                            'orderLimit' => 100,
                            'dir' => $_SERVER['DOCUMENT_ROOT'] . '/csv/'
                        ),     
                    ),  
                    'permanent_order_status' => array(1,2,3,4,5,6,-1),
                ),
            ),
            'interface' => array(
                'meliscommerce_order_status_tool' => array(
                    'interface' => array(
                        'meliscommerce_order_status_tool_leftmenu' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_order_status_tool_page',
                                'melisKey' => 'meliscommerce_order_status_tool_page',
                                'name' => 'tr_meliscommerce_order_status_tool_leftmenu',
                                'icon' => 'fa fa-plus-square',
                            ),
                        ),  
                        'meliscommerce_order_status_tool_page' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_order_status_tool_page',
                                'melisKey' => 'meliscommerce_order_status_tool_page',
                                'name' => 'tr_meliscommerce_order_status_tool_page',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderStatus',
                                'action' => 'render-order-status-page',
                                'jscallback' => '',
                                'jsdatas' => array()
                            ),
                            'interface' => array(
                                'meliscommerce_order_status_header_container' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_status_header_container',
                                        'melisKey' => 'meliscommerce_order_status_header_container',
                                        'name' => 'tr_meliscommerce_order_status_header_container',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderStatus',
                                        'action' => 'render-order-status-header-container',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_order_status_header_left_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_status_header_left_container',
                                                'melisKey' => 'meliscommerce_order_status_header_left_container',
                                                'name' => 'tr_meliscommerce_order_status_header_left_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderStatus',
                                                'action' => 'render-order-status-header-left-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_order_status_header_title' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_status_header_title',
                                                        'melisKey' => 'meliscommerce_order_status_header_title',
                                                        'name' => 'tr_meliscommerce_order_status_header_title',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderStatus',
                                                        'action' => 'render-order-status-header-title',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_order_status_header_right_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_status_header_right_container',
                                                'melisKey' => 'meliscommerce_order_status_header_right_container',
                                                'name' => 'tr_meliscommerce_order_status_header_right_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderStatus',
                                                'action' => 'render-order-status-header-right-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_order_status_add_order' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_status_add_order',
                                                        'melisKey' => 'meliscommerce_order_status_add_order',
                                                        'name' => 'tr_meliscommerce_order_status_add_order',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderStatus',
                                                        'action' => 'render-order-status-add',
                                                    ),
                                                )
                                            ),
                                        ),
                                    ),
                                ),
                                'meliscommerce_order_status_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_status_content',
                                        'melisKey' => 'meliscommerce_order_status_content',
                                        'name' => 'tr_meliscommerce_order_status_content',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderStatus',
                                        'action' => 'render-order-status-content',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_order_status_content_table' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_status_content_table',
                                                'melisKey' => 'meliscommerce_order_status_content_table',
                                                'name' => 'tr_meliscommerce_order_status_content_table',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderStatus',
                                                'action' => 'render-order-status-content-table',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),  
                    ),    
                ),
                'meliscommerce_order_list' => array(
                    'interface' => array(
                        'meliscommerce_order_list_leftmenu' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_order_list_page',
                                'melisKey' => 'meliscommerce_order_list_page',
                                'name' => 'tr_meliscommerce_orders_Orders',
                                'icon' => 'fa fa-cart-plus',
                            ),
                        ),
                        'meliscommerce_order_list_page' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_order_list_page',
                                'melisKey' => 'meliscommerce_order_list_page',
                                'name' => 'tr_meliscommerce_orders_Orders',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderList',
                                'action' => 'render-order-list-page',
                                'jscallback' => '',
                                'jsdatas' => array()
                            ),
                            'interface' => array(
                                'meliscommerce_order_list_header_container' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_list_header_container',
                                        'melisKey' => 'meliscommerce_order_list_header_container',
                                        'name' => 'tr_meliscommerce_order_list_header_container',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderList',
                                        'action' => 'render-order-list-header-container',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_order_list_header_left_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_list_header_left_container',
                                                'melisKey' => 'meliscommerce_order_list_header_left_container',
                                                'name' => 'tr_meliscommerce_order_list_header_left_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-header-left-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_order_list_header_title' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_list_header_title',
                                                        'melisKey' => 'meliscommerce_order_list_header_title',
                                                        'name' => 'tr_meliscommerce_order_list_header_title',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderList',
                                                        'action' => 'render-order-list-header-title',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_order_list_header_right_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_list_header_right_container',
                                                'melisKey' => 'meliscommerce_order_list_header_right_container',
                                                'name' => 'tr_meliscommerce_order_list_header_right_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-header-right-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_order_list_add_order' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_order_list_add_order',
                                                        'melisKey' => 'meliscommerce_order_list_add_order',
                                                        'name' => 'tr_meliscommerce_order_list_add_order',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComOrderList',
                                                        'action' => 'render-order-list-add-order',
                                                    ),
                                                )
                                            ),
                                        ),
                                    ),
                                ),
                                'meliscommerce_order_list_widgets' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_list_widgets',
                                        'melisKey' => 'meliscommerce_order_list_widgets',
                                        'name' => 'tr_meliscommerce_order_list_widgets',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderList',
                                        'action' => 'render-order-list-widgets',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_order_list_widgets_num_orders' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_list_widgets_num_orders',
                                                'melisKey' => 'meliscommerce_order_list_widgets_num_orders',
                                                'name' => 'tr_meliscommerce_order_list_widgets_num_orders',
                                                'width' => '4'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-widgets-num-orders',
                                            ),
                                        ),
                                        'meliscommerce_order_list_widgets_month_orders' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_list_widgets_month_orders',
                                                'melisKey' => 'meliscommerce_order_list_widgets_month_orders',
                                                'name' => 'tr_meliscommerce_order_list_widgets_month_orders',
                                                'width' => '4'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-widgets-month-orders',
                                            ),
                                        ),
                                        'meliscommerce_order_list_widgets_avg_orders' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_list_widgets_avg_orders',
                                                'melisKey' => 'meliscommerce_order_list_widgets_avg_orders',
                                                'name' => 'tr_meliscommerce_order_list_widgets_avg_orders',
                                                'width' => '4'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-widgets-avg-orders',
                                            ),
                                        ),
                                    ),
                                ),
                                'meliscommerce_order_list_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_order_list_content',
                                        'melisKey' => 'meliscommerce_order_list_content',
                                        'name' => 'tr_meliscommerce_order_list_content',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComOrderList',
                                        'action' => 'render-order-list-content',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_order_list_content_table' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_order_list_content_table',
                                                'melisKey' => 'meliscommerce_order_list_content_table',
                                                'name' => 'tr_meliscommerce_order_list_content_table',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComOrderList',
                                                'action' => 'render-order-list-content-table',
                                            ),
                                        ),
                                    ),
                                ),                                
                            ),                            
                        ),                        
                    ),
                ),
                'meliscommerce_orders' => array(
                      'interface' => array(
                          'meliscommerce_orders_leftmenu' => array(
                              'conf' => array(
                                  'id' => '_id_meliscommerce_orders_page',
                                  'melisKey' => 'meliscommerce_orders_page',
                                  'name' => 'tr_meliscommerce_orders_Orders',
                                  'icon' => 'fa fa-cart-plus',
                              ),
                          ),
                          'meliscommerce_orders_page' => array(
                              'conf' => array(
                                  'id' => '_id_meliscommerce_orders_page',
                                  'melisKey' => 'meliscommerce_orders_page',
                                  'name' => 'tr_meliscommerce_orders_Orders',
                              ),
                              'forward' => array(
                                  'module' => 'MelisCommerce',
                                  'controller' => 'MelisComOrder',
                                  'action' => 'render-orders-page',
                                  'jscallback' => '',
                                  'jsdatas' => array()
                              ),
                              'interface' => array(
                                  'meliscommerce_orders_header_container' => array(
                                      'conf' => array(
                                          'id' => 'id_meliscommerce_orders_header_container',
                                          'melisKey' => 'meliscommerce_orders_header_container',
                                          'name' => 'tr_meliscommerce_orders_header_container',
                                      ),
                                      'forward' => array(
                                          'module' => 'MelisCommerce',
                                          'controller' => 'MelisComOrder',
                                          'action' => 'render-orders-header-container',
                                      ),
                                      'interface' => array(
                                          'meliscommerce_orders_header_left_container' => array(
                                              'conf' => array(
                                                  'id' => 'id_meliscommerce_orders_header_left_container',
                                                  'melisKey' => 'meliscommerce_orders_header_left_container',
                                                  'name' => 'tr_meliscommerce_orders_header_left_container',
                                              ),
                                              'forward' => array(
                                                  'module' => 'MelisCommerce',
                                                  'controller' => 'MelisComOrder',
                                                  'action' => 'render-orders-header-left-container',
                                              ),
                                              'interface' => array(
                                                  'meliscommerce_orders_header_title' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_header_title',
                                                          'melisKey' => 'meliscommerce_orders_header_title',
                                                          'name' => 'tr_meliscommerce_orders_header_title',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-header-title',
                                                      ),
                                                  ),
                                              ),
                                          ),
                                          'meliscommerce_orders_header_right_container' => array(
                                              'conf' => array(
                                                  'id' => 'id_meliscommerce_orders_header_right_container',
                                                  'melisKey' => 'meliscommerce_orders_header_right_container',
                                                  'name' => 'tr_meliscommerce_orders_header_right_container',
                                              ),
                                              'forward' => array(
                                                  'module' => 'MelisCommerce',
                                                  'controller' => 'MelisComOrder',
                                                  'action' => 'render-orders-header-right-container',
                                              ),
                                              'interface' => array(
                                                  'meliscommerce_orders_header_right_container_save' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_header_right_container_save',
                                                          'melisKey' => 'meliscommerce_orders_header_right_container_save',
                                                          'name' => 'tr_meliscommerce_orders_header_right_container_save',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-header-right-container-save',
                                                      ),
                                                  ),
//                                                   'meliscommerce_orders_header_right_container_cancel' => array(
//                                                       'conf' => array(
//                                                           'id' => 'id_meliscommerce_orders_header_right_container_cancel',
//                                                           'melisKey' => 'meliscommerce_orders_header_right_container_cancel',
//                                                           'name' => 'tr_meliscommerce_orders_header_right_container_cancel',
//                                                       ),
//                                                       'forward' => array(
//                                                           'module' => 'MelisCommerce',
//                                                           'controller' => 'MelisComOrder',
//                                                           'action' => 'render-orders-header-right-container-cancel',
//                                                       ),
//                                                   ),
                                              ),
                                          ),
                                      ),
                                  ),
                                  'meliscommerce_orders_content' => array(
                                      'conf' => array(
                                          'id' => 'id_meliscommerce_orders_content',
                                          'melisKey' => 'meliscommerce_orders_content',
                                          'name' => 'tr_meliscommerce_orders_content',
                                      ),
                                      'forward' => array(
                                          'module' => 'MelisCommerce',
                                          'controller' => 'MelisComOrder',
                                          'action' => 'render-orders-content',
                                      ),
                                      'interface' => array(
                                          'meliscommerce_orders_content_tabs' => array(
                                              'conf' => array(
                                                  'id' => 'id_meliscommerce_orders_content_tabs',
                                                  'melisKey' => 'meliscommerce_orders_content_tabs',
                                                  'name' => 'tr_meliscommerce_orders_content_tabs',
                                              ),
                                              'forward' => array(
                                                  'module' => 'MelisCommerce',
                                                  'controller' => 'MelisComOrder',
                                                  'action' => 'render-orders-content-tabs',
                                              ),
                                              'interface' => array(
                                                  'meliscommerce_orders_content_tab_main' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tab_main',
                                                          'melisKey' => 'meliscommerce_orders_content_tab_main',
                                                          'name' => 'tr_meliscommerce_orders_content_tab_main',
                                                          'href' => 'id_meliscommerce_orders_content_tabs_content_main',
                                                          'icon' => 'glyphicons tag',
                                                          'active' => 'active',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tab',
                                                      ),
                                                  ),
                                                  'meliscommerce_orders_content_tab_basket' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tab_basket',
                                                          'melisKey' => 'meliscommerce_orders_content_tab_basket',
                                                          'name' => 'tr_meliscommerce_orders_content_tab_basket',
                                                          'href' => 'id_meliscommerce_orders_content_tabs_content_baskets',
                                                          'icon' => 'glyphicons shopping_bag',
                                                          'active' => '',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tab',
                                                      ),
                                                  ),
                                                  'meliscommerce_orders_content_tab_address' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tab_address',
                                                          'melisKey' => 'meliscommerce_orders_content_tab_address',
                                                          'name' => 'tr_meliscommerce_orders_content_tab_address',
                                                          'href' => 'id_meliscommerce_orders_content_tabs_content_address',
                                                          'icon' => 'glyphicons google_maps',
                                                          'active' => '',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tab',
                                                      ),
                                                  ),
                                                  'meliscommerce_orders_content_tab_paymnet' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tab_paymnet',
                                                          'melisKey' => 'meliscommerce_orders_content_tab_paymnet',
                                                          'name' => 'tr_meliscommerce_orders_content_tab_paymnet',
                                                          'href' => 'id_meliscommerce_orders_content_tabs_content_payment',
                                                          'icon' => 'glyphicons money',
                                                          'active' => '',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tab',
                                                      ),
                                                  ),
                                                  'meliscommerce_orders_content_tab_shipping' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tab_shipping',
                                                          'melisKey' => 'meliscommerce_orders_content_tab_shipping',
                                                          'name' => 'tr_meliscommerce_orders_content_tab_shipping',
                                                          'href' => 'id_meliscommerce_orders_content_tabs_content_shipping',
                                                          'icon' => 'glyphicons boat',
                                                          'active' => '',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tab',
                                                      ),
                                                  ),
                                                  'meliscommerce_orders_content_tab_messages' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tab_messages',
                                                          'melisKey' => 'meliscommerce_orders_content_tab_messages',
                                                          'name' => 'tr_meliscommerce_orders_content_tab_messages',
                                                          'href' => 'id_meliscommerce_orders_content_tabs_content_messages',
                                                          'icon' => 'glyphicons chat',
                                                          'active' => '',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tab',
                                                      ),
                                                  ),
                                              ),
                                          ),
                                          'meliscommerce_orders_content_tabs_content' => array(
                                              'conf' => array(
                                                  'id' => 'id_meliscommerce_orders_content_tabs_content',
                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content',
                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content',
                                              ),
                                              'forward' => array(
                                                  'module' => 'MelisCommerce',
                                                  'controller' => 'MelisComOrder',
                                                  'action' => 'render-orders-content-tabs-content',
                                              ),
                                              'interface' => array(
                                                  'meliscommerce_orders_content_tabs_content_main' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_main',
                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_main',
                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_main',
                                                          'active' => 'active',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tabs-content-container',
                                                      ),
                                                      'interface' => array(
                                                          'meliscommerce_orders_content_tabs_content_main_header' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_main_header',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_main_header',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_main_header',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-header',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_main_left_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_main_left_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_main_left_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_main_left_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-left-header',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_main_left_header_title' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_main_left_header_title',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_main_left_header_title',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_main_left_header_title',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                                  'meliscommerce_orders_content_tabs_content_main_right_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_main_right_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_main_right_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_main_right_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-right-header',
                                                                      ),
                                                                      'interface' => array(
                                                                          
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                          'meliscommerce_orders_content_tabs_content_main_details' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_main_details',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-details',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_main_details_left' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_left',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_left',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details_left',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-main-details-left',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_main_details_sub_header' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_sub_header',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_sub_header',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details_sub_header',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-details-sub-header',
                                                                              ),
                                                                              'interface' => array(
                                                                                  'meliscommerce_orders_content_tabs_content_main_details_sub_header_left' => array(
                                                                                      'conf' => array(
                                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_sub_header_left',
                                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_sub_header_left',
                                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details_sub_header_left',
                                                                                      ),
                                                                                      'forward' => array(
                                                                                          'module' => 'MelisCommerce',
                                                                                          'controller' => 'MelisComOrder',
                                                                                          'action' => 'render-orders-content-tabs-content-details-sub-header-left',
                                                                                      ),
                                                                                      'interface' => array(
                                                                                          'meliscommerce_orders_content_tabs_content_main_details_sub_header_title' => array(
                                                                                              'conf' => array(
                                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_sub_header_title',
                                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_sub_header_title',
                                                                                                  'name' => 'tr_meliscommerce_orders_Order',
                                                                                                  'icon' => 'fa fa-shopping-cart',
                                                                                              ),
                                                                                              'forward' => array(
                                                                                                  'module' => 'MelisCommerce',
                                                                                                  'controller' => 'MelisComOrder',
                                                                                                  'action' => 'render-orders-content-tabs-content-details-sub-header-title',
                                                                                              ),
                                                                                              'interface' => array(),
                                                                                          ),
                                                                                      ),
                                                                                  ),
                                                                                  'meliscommerce_orders_content_tabs_content_main_details_sub_header_right' => array(
                                                                                      'conf' => array(
                                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_sub_header_right',
                                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_sub_header_right',
                                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details_sub_header_right',
                                                                                      ),
                                                                                      'forward' => array(
                                                                                          'module' => 'MelisCommerce',
                                                                                          'controller' => 'MelisComOrder',
                                                                                          'action' => 'render-orders-content-tabs-content-details-sub-header-right',
                                                                                      ),
                                                                                      'interface' => array(),
                                                                                  ),
                                                                              ),                                                                              
                                                                          ),
                                                                          'meliscommerce_orders_content_tabs_content_main_details_sub_content' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_main_details_sub_content',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_main_details_sub_content',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_main_details_sub_content',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-details-sub-content',
                                                                              ),
                                                                              'interface' => array(
                                                                                  'meliscommerce_orders_content_tabs_content_main_orderform' => array(
                                                                                      'conf' => array(
                                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_main_orderform',
                                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_main_orderform',
                                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_main_orderform',
                                                                                      ),
                                                                                      'forward' => array(
                                                                                          'module' => 'MelisCommerce',
                                                                                          'controller' => 'MelisComOrder',
                                                                                          'action' => 'render-orders-content-tabs-content-main-order-form',
                                                                                      ),
                                                                                      'interface' => array(),
                                                                                  ),
                                                                                  'meliscommerce_orders_content_tabs_content_file_attachments' => array(
                                                                                      'conf' => array(
                                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_file_attachments',
                                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_file_attachments',
                                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_file_attachments',
                                                                                      ),
                                                                                      'forward' => array(
                                                                                          'module' => 'MelisCommerce',
                                                                                          'controller' => 'MelisComOrder',
                                                                                          'action' => 'render-orders-content-tabs-content-file-attachments',
                                                                                      ),
                                                                                      'interface' => array(
                                                                                           'meliscommerce_orders_content_tabs_content_file_attachments_plugin' => array(
                                                                                               'conf' => array(
                                                                                                   'type' => 'meliscommerce/interface/meliscommerce_documents_file_attachments_conf',
                                                                                                   'docRelationType' => 'order',
                                                                                               )
                                                                                           )
                                                                                      ),
                                                                                   ),
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                      ),                                                      
                                                  ),
                                                  'meliscommerce_orders_content_tabs_content_baskets' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_baskets',
                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets',
                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets',
                                                          'active' => '',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tabs-content-container',
                                                      ),
                                                      'interface' => array(
                                                          'meliscommerce_orders_content_tabs_content_baskets_header' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_header',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_header',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_header',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-header',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_baskets_left_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_left_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_left_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_left_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-left-header',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_baskets_left_header_title' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_left_header_title',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_left_header_title',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_left_header_title',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                                  'meliscommerce_orders_content_tabs_content_baskets_right_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_right_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_right_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_right_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-right-header',
                                                                      ),
                                                                      'interface' => array(
                                                          
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                          'meliscommerce_orders_content_tabs_content_baskets_details' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_details',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_details',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_details',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-details',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_baskets_details_list' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_baskets_details_list',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_baskets_details_list',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_baskets_details_list',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-basket-list',
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                      ),
                                                  ),
                                                  'meliscommerce_orders_content_tabs_content_address' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_address',
                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_address',
                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_address',
                                                          'active' => '',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tabs-content-container',
                                                      ),
                                                      'interface' => array(
                                                          'meliscommerce_orders_content_tabs_content_header' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_header',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_header',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_header',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-header',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_address_left_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_address_left_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_address_left_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_address_left_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-left-header',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_address_left_header_title' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_address_left_header_title',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_address_left_header_title',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_address_left_header_title',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                                  'meliscommerce_orders_content_tabs_content_address_right_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_address_right_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_address_right_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_address_right_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-right-header',
                                                                      ),
                                                                      'interface' => array(
                                                          
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                          'meliscommerce_orders_content_tabs_content_address_details' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_address_details',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_address_details',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_address_details',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-details',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_address_details_left' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_address_details_left',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_address_details_left',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_address_details_left',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-address-details-left',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_address_details_tabs' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_address_details_tabs',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_address_details_tabs',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_address_details_tabs',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-address-details-tabs',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                                  'meliscommerce_orders_content_tabs_content_address_details_right' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_address_details_right',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_address_details_right',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_address_details_right',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-address-details-right',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_address_details_address_form' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_address_details_address_form',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_address_details_address_form',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_address_details_address_form',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-address-details-address-form',
                                                                              ),                                                                              
                                                                          ),
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                      ),
                                                  ),
                                                  'meliscommerce_orders_content_tabs_content_payment' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_payment',
                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_payment',
                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_payment',
                                                          'active' => '',
                                                          'accord' => 'accordion-list',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tabs-content-container',
                                                      ),
                                                      'interface' => array(
                                                          'meliscommerce_orders_content_tabs_content_payment_header' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_payment_header',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_header',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_header',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-header',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_payment_left_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_payment_left_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_left_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_left_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-left-header',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_payment_left_header_title' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_payment_left_header_title',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_left_header_title',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_left_header_title',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                                  'meliscommerce_orders_content_tabs_content_payment_right_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_payment_right_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_right_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_right_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-right-header',
                                                                      ),
                                                                      'interface' => array(
                                                          
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                          'meliscommerce_orders_content_tabs_content_payment_details' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_payment_details',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_details',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_details',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-details',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_payment_details_content' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_payment_details_content',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_details_content',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_details_content',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-details-large',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_payment_details_content_list' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_payment_details_content_list',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_payment_details_content_list',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_payment_details_content_list',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-payment-details-content-list',
                                                                              ),                                                                              
                                                                          ),
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                      ),
                                                  ),
                                                  'meliscommerce_orders_content_tabs_content_shipping' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_shipping',
                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping',
                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping',
                                                          'active' => '',
                                                          'accord' => 'accordion-list',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tabs-content-container',
                                                      ),
                                                      'interface' => array(
                                                          'meliscommerce_orders_content_tabs_content_shipping_header' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_header',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_header',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_header',                                                                  
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-header',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_shipping_left_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_left_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_left_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_left_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-left-header',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_shipping_left_header_title' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_left_header_title',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_left_header_title',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_left_header_title',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                                  'meliscommerce_orders_content_tabs_content_shipping_right_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_right_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_right_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_right_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-right-header',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_shipping_right_header_add' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_right_header_add',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_right_header_add',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_right_header_add',
                                                                                  'icon' => 'fa fa-plus'
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-shipping-right-header-add',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                          'meliscommerce_orders_content_tabs_content_shipping_details' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_details',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_details',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_details',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-details',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_shipping_details_content' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-details-large',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_shipping_details_content_list' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_shipping_details_content',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-shipping-details-content-list',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                      ),
                                                  ),
                                                  'meliscommerce_orders_content_tabs_content_messages' => array(
                                                      'conf' => array(
                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_messages',
                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_messages',
                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_messages',
                                                          'active' => '',
                                                      ),
                                                      'forward' => array(
                                                          'module' => 'MelisCommerce',
                                                          'controller' => 'MelisComOrder',
                                                          'action' => 'render-orders-content-tabs-content-container',
                                                      ),
                                                      'interface' => array(
                                                          'meliscommerce_orders_content_tabs_content_messages_header' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_messages_header',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_header',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_header',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-header',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_messages_left_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_messages_left_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_left_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_left_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-left-header',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_messages_left_header_title' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_messages_left_header_title',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_left_header_title',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_left_header_title',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-left-header-title',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                                  'meliscommerce_orders_content_tabs_content_messages_right_header' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_messages_right_header',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_right_header',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_right_header',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-right-header',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_messages_right_header_add' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_messages_right_header_add',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_right_header_add',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_right_header_add',
                                                                                  'icon' => 'fa fa-plus'
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-messages-right-header-add',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                          'meliscommerce_orders_content_tabs_content_messages_details' => array(
                                                              'conf' => array(
                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_messages_details',
                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_details',
                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_details',
                                                              ),
                                                              'forward' => array(
                                                                  'module' => 'MelisCommerce',
                                                                  'controller' => 'MelisComOrder',
                                                                  'action' => 'render-orders-content-tabs-content-messages-details',
                                                              ),
                                                              'interface' => array(
                                                                  'meliscommerce_orders_content_tabs_content_messages_message_form' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_messages_message_form',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_message_form',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_message_form',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-messages-message-form',
                                                                      ),
                                                                  ),
                                                                  'meliscommerce_orders_content_tabs_content_messages_timeline_container' => array(
                                                                      'conf' => array(
                                                                          'id' => 'id_meliscommerce_orders_content_tabs_content_messages_timeline_container',
                                                                          'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_timeline_container',
                                                                          'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_timeline_container',
                                                                      ),
                                                                      'forward' => array(
                                                                          'module' => 'MelisCommerce',
                                                                          'controller' => 'MelisComOrder',
                                                                          'action' => 'render-orders-content-tabs-content-messages-timeline-container',
                                                                      ),
                                                                      'interface' => array(
                                                                          'meliscommerce_orders_content_tabs_content_messages_timeline' => array(
                                                                              'conf' => array(
                                                                                  'id' => 'id_meliscommerce_orders_content_tabs_content_messages_timeline',
                                                                                  'melisKey' => 'meliscommerce_orders_content_tabs_content_messages_timeline',
                                                                                  'name' => 'tr_meliscommerce_orders_content_tabs_content_messages_timeline',
                                                                              ),
                                                                              'forward' => array(
                                                                                  'module' => 'MelisCommerce',
                                                                                  'controller' => 'MelisComOrder',
                                                                                  'action' => 'render-orders-content-tabs-content-messages-timeline',
                                                                              ),
                                                                          ),
                                                                      ),
                                                                  ),
                                                              ),
                                                          ),
                                                      ),
                                                  ),
                                              ),
                                          ),
                                      ),
                                  ),
                              ),
                        ),
                    ),       
                ),
                'meliscommerce_order_list_modal' => array( 
                    'conf' => array(
                        'id' => 'id_meliscommerce_order_list_modal',
                        'name' => 'tr_meliscommerce_order_list_modal',
                        'melisKey' => 'meliscommerce_order_list_modal',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComOrderList',
                        'action' => 'render-order-list-modal',
                    ),
                    'interface' => array(
                        'meliscommerce_order_list_content_status_form' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_order_list_content_status_form',
                                'name' => 'tr_meliscommerce_order_list_content_status_form',
                                'melisKey' => 'meliscommerce_order_list_content_status_form',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderList',
                                'action' => 'render-order-list-content-status-form',
                            ),
                        ),
                        'meliscommerce_order_list_content_export_form' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_order_list_content_export_form',
                                'name' => 'tr_meliscommerce_order_list_content_export_form',
                                'melisKey' => 'meliscommerce_order_list_content_export_form',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderList',
                                'action' => 'render-order-list-content-export-form',
                            ),
                        ),
                    ),
                ),
                'meliscommerce_order_modal' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_order_modal',
                        'name' => 'tr_meliscommerce_order_modal',
                        'melisKey' => 'meliscommerce_order_modal',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComOrder',
                        'action' => 'render-order-modal',
                    ),
                    'interface' => array(
                        'meliscommerce_order_modal_content_shipping_form' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_order_modal_content_shipping_form',
                                'name' => 'tr_meliscommerce_order_modal_content_shipping_form',
                                'melisKey' => 'meliscommerce_order_modal_content_shipping_form',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrder',
                                'action' => 'render-order-modal-content-shipping-form',
                            ),
                        ),
                    ),
                ),
                'meliscommerce_order_status_modal' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_order_status_modal',
                        'name' => 'tr_meliscommerce_order_status_modal',
                        'melisKey' => 'meliscommerce_order_status_modal',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComOrderStatus',
                        'action' => 'render-order-status-modal',
                    ),
                    'interface' => array(
                        'meliscommerce_order_status_form' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_order_status_form',
                                'name' => 'tr_meliscommerce_order_status_form',
                                'melisKey' => 'meliscommerce_order_status_form',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComOrderStatus',
                                'action' => 'render-order-status-form',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);