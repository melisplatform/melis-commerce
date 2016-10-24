<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_orders_Orders',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/coupon.tool.js',
                ),
                'css' => array(
                    '/MelisCommerce/css/coupons.css',
                ),
            ),
            'datas' => array(),
            'interface' => array(
                'meliscommerce_coupon_list' => array(
                    'interface' => array(
                        'meliscommerce_coupon_list_leftmenu' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_coupon_list_page',
                                'melisKey' => 'meliscommerce_coupon_list_page',
                                'name' => 'tr_meliscommerce_coupon_list_page',
                                'icon' => 'fa fa-gift',
                            ),
                        ),
                        'meliscommerce_coupon_list_page' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_coupon_list_page',
                                'melisKey' => 'meliscommerce_coupon_list_page',
                                'name' => 'tr_meliscommerce_coupon_list_page',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCouponList',
                                'action' => 'render-coupon-list-page',
                                'jscallback' => '',
                                'jsdatas' => array()
                            ),
                            'interface' => array(
                                'meliscommerce_coupon_list_header_container' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_coupon_list_header_container',
                                        'melisKey' => 'meliscommerce_coupon_list_header_container',
                                        'name' => 'tr_meliscommerce_coupon_list_header_container',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCouponList',
                                        'action' => 'render-coupon-list-header-container',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_coupon_list_header_left_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_coupon_list_header_left_container',
                                                'melisKey' => 'meliscommerce_coupon_list_header_left_container',
                                                'name' => 'tr_meliscommerce_coupon_list_header_left_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCouponList',
                                                'action' => 'render-coupon-list-header-left-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_coupon_list_header_title' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_coupon_list_header_title',
                                                        'melisKey' => 'meliscommerce_coupon_list_header_title',
                                                        'name' => 'tr_meliscommerce_coupon_list_header_title',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCouponList',
                                                        'action' => 'render-coupon-list-header-title',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_coupon_list_header_right_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_coupon_list_header_right_container',
                                                'melisKey' => 'meliscommerce_coupon_list_header_right_container',
                                                'name' => 'tr_meliscommerce_coupon_list_header_right_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCouponList',
                                                'action' => 'render-coupon-list-header-right-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_coupon_list_add_coupon' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_coupon_list_add_coupon',
                                                        'melisKey' => 'meliscommerce_coupon_list_add_coupon',
                                                        'name' => 'tr_meliscommerce_coupon_list_add_coupon',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCouponList',
                                                        'action' => 'render-coupon-list-add-coupon',
                                                    ),
                                                )
                                            ),
                                        ),
                                    ),
                                ),
                                'meliscommerce_coupon_list_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_coupon_list_content',
                                        'melisKey' => 'meliscommerce_coupon_list_content',
                                        'name' => 'tr_meliscommerce_coupon_list_content',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCouponList',
                                        'action' => 'render-coupon-list-content',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_coupon_list_content_table' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_coupon_list_content_table',
                                                'melisKey' => 'meliscommerce_coupon_list_content_table',
                                                'name' => 'tr_meliscommerce_coupon_list_content_table',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCouponList',
                                                'action' => 'render-coupon-list-content-table',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'meliscommerce_coupon' => array(
                    'interface' => array(
                        'meliscommerce_coupon_page' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_coupon_page',
                                'melisKey' => 'meliscommerce_coupon_page',
                                'name' => 'tr_meliscommerce_coupon_page',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-page',
                                'jscallback' => '',
                                'jsdatas' => array()
                            ),
                            'interface' => array(
                                'meliscommerce_coupon_header_container' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_coupon_header_container',
                                        'melisKey' => 'meliscommerce_coupon_header_container',
                                        'name' => 'tr_meliscommerce_coupon_header_container',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCoupon',
                                        'action' => 'render-coupon-header-container',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_coupon_header_left_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_coupon_header_left_container',
                                                'melisKey' => 'meliscommerce_coupon_header_left_container',
                                                'name' => 'tr_meliscommerce_coupon_header_left_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-header-left-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_coupon_header_title' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_coupon_header_title',
                                                        'melisKey' => 'meliscommerce_coupon_header_title',
                                                        'name' => 'tr_meliscommerce_coupon_header_title',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-header-title',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_coupon_header_right_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_coupon_header_right_container',
                                                'melisKey' => 'meliscommerce_coupon_header_right_container',
                                                'name' => 'tr_meliscommerce_coupon_header_right_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-header-right-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_coupon_header_right_container_save' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_coupon_header_right_container_save',
                                                        'melisKey' => 'meliscommerce_coupon_header_right_container_save',
                                                        'name' => 'tr_meliscommerce_coupon_header_right_container_save',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-header-save',
                                                    ),
                                                ),                                                  
                                            ),
                                        ),
                                    ),
                                ),
                                'meliscommerce_coupon_page_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_coupon_page_content',
                                        'melisKey' => 'meliscommerce_coupon_page_content',
                                        'name' => 'tr_meliscommerce_coupon_page_content'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCoupon',
                                        'action' => 'render-coupon-page-content',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_coupon_page_tabs_main' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_coupon_page_tabs_main',
                                                'melisKey' => 'meliscommerce_coupon_page_tabs_main',
                                                'name' => 'tr_meliscommerce_coupon_page_tabs_main',
                                                'icon' => 'glyphicons tag',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-page-tabs-main',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_coupon_tabs_content_main_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_main_header',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_main_header',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_main_header',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-header',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_coupon_tabs_content_main_header_left' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_main_header_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_main_header_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_main_header_left',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_coupon_tabs_content_main_header_title' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_main_header_title',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_main_header_title',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_main_header_title',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-title',
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                        'meliscommerce_coupon_tabs_content_main_header_right' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_main_header_right',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_main_header_right',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_main_header_right',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_coupon_tabs_content_main_header_status' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_main_header_status',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_main_header_status',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_main_header_status',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-status',
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                    ),
                                                ),
                                                'meliscommerce_coupon_tabs_content_main_details' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_main_details',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_main_details',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_main_details',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-details',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_coupon_tabs_content_main_details_left' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_main_details_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_main_details_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_main_details_left',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_coupon_tabs_content_general_data_header' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_general_data_header',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_header',
                                                                        'name' => 'tr_id_meliscommerce_coupon_tabs_content_general_data_header',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-sub-header',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_coupon_tabs_content_general_data_header_left' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_coupon_tabs_content_general_data_header_left',
                                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_header_left',
                                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_general_data_header_left',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCoupon',
                                                                                'action' => 'render-coupon-tabs-content-sub-header-left',
                                                                            ),
                                                                            'interface' => array(
                                                                                'meliscommerce_coupon_tabs_content_general_data_header_title' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_general_data_header_title',
                                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_header_title',
                                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_general_data_header_title',
                                                                                        'icon' => 'fa fa-cogs'
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComCoupon',
                                                                                        'action' => 'render-coupon-tabs-content-sub-header-title',
                                                                                    ),
                                                                                    'inteface' => array(
                                                                                
                                                                                    ),
                                                                                ),
                                                                            ),
                                                                        ),
                                                                        'meliscommerce_coupon_tabs_content_general_data_header_right' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_coupon_tabs_content_general_data_header_right',
                                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_header_right',
                                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_general_data_header_right',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCoupon',
                                                                                'action' => 'render-coupon-tabs-content-sub-header-right',
                                                                            ),
                                                                            'interface' => array(),
                                                                        ),
                                                                    ),
                                                                ),
                                                                'meliscommerce_coupon_tabs_content_general_data_details' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_general_data_details',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_details',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_general_data_details',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-sub-details',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_coupon_tabs_content_general_data_form' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_coupon_tabs_content_general_data_form',
                                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_form',
                                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_general_data_form',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCoupon',
                                                                                'action' => 'render-coupon-form-general-data',
                                                                            ),
                                                                        ),
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                        'meliscommerce_coupon_tabs_content_main_details_right' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_main_details_right',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_main_details_right',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_main_details_right',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_coupon_tabs_content_values_header' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_values_header',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_values_header',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_values_header',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-sub-header',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_coupon_tabs_content_values_header_left' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_coupon_tabs_content_values_header_left',
                                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_values_header_left',
                                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_values_header_left',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCoupon',
                                                                                'action' => 'render-coupon-tabs-content-sub-header-left',
                                                                            ),
                                                                            'interface' => array(
                                                                                'meliscommerce_coupon_tabs_content_values_header_title' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_values_header_title',
                                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_values_header_title',
                                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_values_header_title',
                                                                                        'icon' => 'fa fa-tasks'
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComCoupon',
                                                                                        'action' => 'render-coupon-tabs-content-sub-header-title',
                                                                                    ),
                                                                                    'inteface' => array(
                                                                
                                                                                    ),
                                                                                ),
                                                                            ),
                                                                        ),                                                                        
                                                                    ),
                                                                ),
                                                                'meliscommerce_coupon_tabs_content_values_details' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_values_details',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_values_details',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_values_details',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-sub-details',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_coupon_tabs_content_values_form' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_coupon_tabs_content_values_form',
                                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_values_form',
                                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_values_form',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCoupon',
                                                                                'action' => 'render-coupon-form-values',
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
                                        'meliscommerce_coupon_page_tabs_assign' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_coupon_page_tabs_assign',
                                                'melisKey' => 'meliscommerce_coupon_page_tabs_assign',
                                                'name' => 'tr_meliscommerce_coupon_page_tabs_assign',
                                                'icon' => 'glyphicons user_add',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-page-tabs-main',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_coupon_tabs_content_assign_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assign_header',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assign_header',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assign_header',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-header',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_coupon_tabs_content_assign_header_left' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_assign_header_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_assign_header_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_assign_header_left',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_coupon_tabs_content_assign_header_title' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assign_header_title',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assign_header_title',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assign_header_title',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-title',
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                    ),
                                                ),
                                                'meliscommerce_coupon_tabs_content_assign_details' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assign_details',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assign_details',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assign_details',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-details',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_coupon_tabs_content_assign_details_table' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_assign_details_table',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_assign_details_table',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_assign_details_table',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-assign-table',
                                                            ),
                                                        ),
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_coupon_page_tabs_orders' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_coupon_page_tabs_orders',
                                                'melisKey' => 'meliscommerce_coupon_page_tabs_orders',
                                                'name' => 'tr_meliscommerce_coupon_page_tabs_orders',
                                                'icon' => 'glyphicons notes_2',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-page-tabs-main',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_coupon_tabs_content_orders_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_orders_header',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_orders_header',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_orders_header',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-header',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_coupon_tabs_content_orders_header_left' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_orders_header_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_orders_header_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_orders_header_left',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_coupon_tabs_content_orders_header_title' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_orders_header_title',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_orders_header_title',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_orders_header_title',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-title',
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                    ),
                                                ),
                                                'meliscommerce_coupon_tabs_content_orders_details' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_orders_details',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_orders_details',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_orders_details',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-details',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_coupon_tabs_content_orders_details_table' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_orders_details_table',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_orders_details_table',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_orders_details_table',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-orders-table',
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
);