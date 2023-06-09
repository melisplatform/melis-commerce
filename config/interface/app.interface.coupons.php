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
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/coupon.tool.js',
                ],
                'css' => [
                    '/MelisCommerce/css/coupons.css',
                ]
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_coupon_list' => [
                    'interface' => [
                        'meliscommerce_coupon_list_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_coupon_list_page',
                                'melisKey' => 'meliscommerce_coupon_list_page',
                                'name' => 'tr_meliscommerce_coupon_list_page',
                                'icon' => 'fa fa-ticket',
                            ],
                            'interface' => [
                                'meliscommerce_coupon_list_page' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_coupon_list/interface/meliscommerce_coupon_list_page',
                                        
                                    ],
                                ]
                            ]
                        ],
                        'meliscommerce_coupon_list_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_coupon_list_page',
                                'melisKey' => 'meliscommerce_coupon_list_page',
                                'name' => 'tr_meliscommerce_coupon_list_page',
                                'icon' => 'fa fa-ticket',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCouponList',
                                'action' => 'render-coupon-list-page',
                                'jscallback' => '',
                                'jsdatas' => []
                            ],
                            'interface' => [
                                'meliscommerce_coupon_list_header_container' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_coupon_list_header_container',
                                        'melisKey' => 'meliscommerce_coupon_list_header_container',
                                        'name' => 'tr_meliscommerce_coupon_list_header_container',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCouponList',
                                        'action' => 'render-coupon-list-header-container',
                                    ],
                                    'interface' => [
                                        'meliscommerce_coupon_list_header_left_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_coupon_list_header_left_container',
                                                'melisKey' => 'meliscommerce_coupon_list_header_left_container',
                                                'name' => 'tr_meliscommerce_coupon_list_header_left_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCouponList',
                                                'action' => 'render-coupon-list-header-left-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_coupon_list_header_title' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_list_header_title',
                                                        'melisKey' => 'meliscommerce_coupon_list_header_title',
                                                        'name' => 'tr_meliscommerce_coupon_list_header_title',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCouponList',
                                                        'action' => 'render-coupon-list-header-title',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_coupon_list_header_right_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_coupon_list_header_right_container',
                                                'melisKey' => 'meliscommerce_coupon_list_header_right_container',
                                                'name' => 'tr_meliscommerce_coupon_list_header_right_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCouponList',
                                                'action' => 'render-coupon-list-header-right-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_coupon_list_add_coupon' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_list_add_coupon',
                                                        'melisKey' => 'meliscommerce_coupon_list_add_coupon',
                                                        'name' => 'tr_meliscommerce_coupon_list_add_coupon',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCouponList',
                                                        'action' => 'render-coupon-list-add-coupon',
                                                    ],
                                                ]
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_coupon_list_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_coupon_list_content',
                                        'melisKey' => 'meliscommerce_coupon_list_content',
                                        'name' => 'tr_meliscommerce_coupon_list_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCouponList',
                                        'action' => 'render-coupon-list-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_coupon_list_content_table' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_coupon_list_content_table',
                                                'melisKey' => 'meliscommerce_coupon_list_content_table',
                                                'name' => 'tr_meliscommerce_coupon_list_content_table',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCouponList',
                                                'action' => 'render-coupon-list-content-table',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'meliscommerce_coupon' => [
                    'interface' => [
                        'meliscommerce_coupon_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_coupon_page',
                                'melisKey' => 'meliscommerce_coupon_page',
                                'name' => 'tr_meliscommerce_coupon_page',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCoupon',
                                'action' => 'render-coupon-page',
                                'jscallback' => '',
                                'jsdatas' => []
                            ],
                            'interface' => [
                                'meliscommerce_coupon_header_container' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_coupon_header_container',
                                        'melisKey' => 'meliscommerce_coupon_header_container',
                                        'name' => 'tr_meliscommerce_coupon_header_container',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCoupon',
                                        'action' => 'render-coupon-header-container',
                                    ],
                                    'interface' => [
                                        'meliscommerce_coupon_header_left_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_coupon_header_left_container',
                                                'melisKey' => 'meliscommerce_coupon_header_left_container',
                                                'name' => 'tr_meliscommerce_coupon_header_left_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-header-left-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_coupon_header_title' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_header_title',
                                                        'melisKey' => 'meliscommerce_coupon_header_title',
                                                        'name' => 'tr_meliscommerce_coupon_header_title',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-header-title',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_coupon_header_right_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_coupon_header_right_container',
                                                'melisKey' => 'meliscommerce_coupon_header_right_container',
                                                'name' => 'tr_meliscommerce_coupon_header_right_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-header-right-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_coupon_header_right_container_save' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_header_right_container_save',
                                                        'melisKey' => 'meliscommerce_coupon_header_right_container_save',
                                                        'name' => 'tr_meliscommerce_coupon_header_right_container_save',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-header-save',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_coupon_page_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_coupon_page_content',
                                        'melisKey' => 'meliscommerce_coupon_page_content',
                                        'name' => 'tr_meliscommerce_coupon_page_content'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCoupon',
                                        'action' => 'render-coupon-page-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_coupon_page_tabs_main' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_coupon_page_tabs_main',
                                                'melisKey' => 'meliscommerce_coupon_page_tabs_main',
                                                'name' => 'tr_meliscommerce_coupon_page_tabs_main',
                                                'icon' => 'glyphicons tag',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-page-tabs-main',
                                            ],
                                            'interface' => [
                                                'meliscommerce_coupon_tabs_content_main_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_main_header',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_main_header',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_main_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_main_header_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_main_header_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_main_header_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_main_header_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_coupon_tabs_content_main_header_title' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_main_header_title',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_main_header_title',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_main_header_title',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-title',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_coupon_tabs_content_main_header_right' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_main_header_right',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_main_header_right',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_main_header_right',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_coupon_tabs_content_main_header_status' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_main_header_status',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_main_header_status',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_main_header_status',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-status',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_coupon_tabs_content_main_details' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_main_details',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_main_details',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_main_details',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-details',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_main_details_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_main_details_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_main_details_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_main_details_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_coupon_tabs_content_general_data_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_general_data_header',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_header',
                                                                        'name' => 'tr_id_meliscommerce_coupon_tabs_content_general_data_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-sub-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_coupon_tabs_content_general_data_header_left' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_coupon_tabs_content_general_data_header_left',
                                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_header_left',
                                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_general_data_header_left',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCoupon',
                                                                                'action' => 'render-coupon-tabs-content-sub-header-left',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_coupon_tabs_content_general_data_header_title' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_general_data_header_title',
                                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_header_title',
                                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_general_data_header_title',
                                                                                        'icon' => 'fa fa-cogs'
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComCoupon',
                                                                                        'action' => 'render-coupon-tabs-content-sub-header-title',
                                                                                    ],
                                                                                    'inteface' => [
                                                                                
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                        'meliscommerce_coupon_tabs_content_general_data_header_right' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_coupon_tabs_content_general_data_header_right',
                                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_header_right',
                                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_general_data_header_right',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCoupon',
                                                                                'action' => 'render-coupon-tabs-content-sub-header-right',
                                                                            ],
                                                                            'interface' => [],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_coupon_tabs_content_general_data_details' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_general_data_details',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_details',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_general_data_details',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-sub-details',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_coupon_tabs_content_general_data_form' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_coupon_tabs_content_general_data_form',
                                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_general_data_form',
                                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_general_data_form',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCoupon',
                                                                                'action' => 'render-coupon-form-general-data',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_coupon_tabs_content_main_details_right' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_main_details_right',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_main_details_right',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_main_details_right',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_coupon_tabs_content_values_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_values_header',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_values_header',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_values_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-sub-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_coupon_tabs_content_values_header_left' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_coupon_tabs_content_values_header_left',
                                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_values_header_left',
                                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_values_header_left',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCoupon',
                                                                                'action' => 'render-coupon-tabs-content-sub-header-left',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_coupon_tabs_content_values_header_title' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_values_header_title',
                                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_values_header_title',
                                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_values_header_title',
                                                                                        'icon' => 'fa fa-tasks'
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComCoupon',
                                                                                        'action' => 'render-coupon-tabs-content-sub-header-title',
                                                                                    ],
                                                                                    'inteface' => [
                                                                
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_coupon_tabs_content_values_details' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_values_details',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_values_details',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_values_details',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-sub-details',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_coupon_tabs_content_values_form' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_coupon_tabs_content_values_form',
                                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_values_form',
                                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_values_form',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCoupon',
                                                                                'action' => 'render-coupon-form-values',
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
                                        'meliscommerce_coupon_page_tabs_assign' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_coupon_page_tabs_assign',
                                                'melisKey' => 'meliscommerce_coupon_page_tabs_assign',
                                                'name' => 'tr_meliscommerce_coupon_page_tabs_assign',
                                                'icon' => 'glyphicons user_add',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-page-tabs-main',
                                            ],
                                            'interface' => [
                                                'meliscommerce_coupon_tabs_content_assigned_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assigned_header',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assigned_header',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assigned_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_assigned_header_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_assigned_header_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_assigned_header_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_assigned_header_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_coupon_tabs_content_assigned_header_title' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assigned_header_title',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assigned_header_title',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assigned_header_title',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-title',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_coupon_tabs_content_assigned_details' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assigned_details',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assigned_details',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assigned_details',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-details',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_assigned_details_table' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_assigned_details_table',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_assigned_details_table',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_assigned_details_table',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-assigned-table',
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_coupon_tabs_content_assign_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assign_header',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assign_header',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assign_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_assign_header_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_assign_header_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_assign_header_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_assign_header_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_coupon_tabs_content_assign_header_title' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assign_header_title',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assign_header_title',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assign_header_title',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-title',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_coupon_tabs_content_assign_details' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assign_details',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assign_details',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assign_details',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-details',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_assign_details_table' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_assign_details_table',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_assign_details_table',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_assign_details_table',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-assign-table',
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_coupon_page_tabs_assign_product' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_coupon_page_tabs_assign_product',
                                                'melisKey' => 'meliscommerce_coupon_page_tabs_assign_product',
                                                'name' => 'tr_meliscommerce_coupon_page_tabs_assign_product_tab',
                                                'icon' => 'glyphicons more_items',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-page-tabs-main',
                                            ],
                                            'interface' => [
                                                'meliscommerce_coupon_tabs_content_assigned_product_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assigned_product_header',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assigned_product_header',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assigned_product_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_assigned_product_header_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_assigned_product_header_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_assigned_product_header_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_assigned_product_header_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_coupon_tabs_content_assigned_product_header_title' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assigned_product_header_title',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assigned_product_header_title',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assigned_product_header_title',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-title',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_coupon_tabs_content_assigned_product_details' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assigned_product_details',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assigned_product_details',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assigned_product_details',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-details',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_assigned_product_details_table' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_assigned_product_details_table',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_assigned_product_details_table',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_assigned_product_details_table',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-assigned-product-table',
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_coupon_tabs_content_assign_product_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assign_product_header',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assign_product_header',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assign_product_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_assign_product_header_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_assign_product_header_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_assign_product_header_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_assign_product_header_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_coupon_tabs_content_assign_product_header_title' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assign_product_header_title',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assign_product_header_title',
                                                                        'name' => 'tr_meliscommerce_products_Product_lists',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-title',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_coupon_tabs_content_assign_product_details' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_assign_product_details',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_assign_product_details',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_assign_product_details',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-details',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_assign_product_details_table' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_assign_product_details_table',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_assign_product_details_table',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_assign_product_details_table',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-assign-product-table',
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_coupon_page_tabs_orders' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_coupon_page_tabs_orders',
                                                'melisKey' => 'meliscommerce_coupon_page_tabs_orders',
                                                'name' => 'tr_meliscommerce_coupon_page_tabs_orders',
                                                'icon' => 'glyphicons shopping_cart',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCoupon',
                                                'action' => 'render-coupon-page-tabs-main',
                                            ],
                                            'interface' => [
                                                'meliscommerce_coupon_tabs_content_orders_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_orders_header',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_orders_header',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_orders_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_orders_header_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_orders_header_left',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_orders_header_left',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_orders_header_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_coupon_tabs_content_orders_header_title' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_coupon_tabs_content_orders_header_title',
                                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_orders_header_title',
                                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_orders_header_title',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCoupon',
                                                                        'action' => 'render-coupon-tabs-content-header-title',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_coupon_tabs_content_orders_details' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_coupon_tabs_content_orders_details',
                                                        'melisKey' => 'meliscommerce_coupon_tabs_content_orders_details',
                                                        'name' => 'tr_meliscommerce_coupon_tabs_content_orders_details',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCoupon',
                                                        'action' => 'render-coupon-tabs-content-details',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_coupon_tabs_content_orders_details_table' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_coupon_tabs_content_orders_details_table',
                                                                'melisKey' => 'meliscommerce_coupon_tabs_content_orders_details_table',
                                                                'name' => 'tr_meliscommerce_coupon_tabs_content_orders_details_table',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCoupon',
                                                                'action' => 'render-coupon-tabs-content-details-orders-table',
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
                ],
            ],
        ],
    ],
];