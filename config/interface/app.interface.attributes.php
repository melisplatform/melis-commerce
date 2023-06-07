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
                    '/MelisCommerce/js/tools/attribute.tool.js',
                ],
                'css' => [],
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_attribute_list' => [
                    'interface' => [
                        'meliscommerce_attribute_list_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_attribute_list_page',
                                'melisKey' => 'meliscommerce_attribute_list_page',
                                'name' => 'tr_meliscommerce_attribute_list_page',
                                'icon' => 'fa fa-cubes',
                            ],
                            'interface' => [
                                'meliscommerce_attribute_list_page' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_attribute_list/interface/meliscommerce_attribute_list_page',
                                        
                                    ],
                                ]
                            ]
                        ],
                        'meliscommerce_attribute_list_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_attribute_list_page',
                                'melisKey' => 'meliscommerce_attribute_list_page',
                                'name' => 'tr_meliscommerce_attribute_list_page',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttributeList',
                                'action' => 'render-attribute-list-page',
                                'jscallback' => '',
                                'jsdatas' => []
                            ],
                            'interface' => [
                                'meliscommerce_attribute_list_header_container' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_attribute_list_header_container',
                                        'melisKey' => 'meliscommerce_attribute_list_header_container',
                                        'name' => 'tr_meliscommerce_attribute_list_header_container',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAttributeList',
                                        'action' => 'render-attribute-list-header-container',
                                    ],
                                    'interface' => [
                                        'meliscommerce_attribute_list_header_left_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_attribute_list_header_left_container',
                                                'melisKey' => 'meliscommerce_attribute_list_header_left_container',
                                                'name' => 'tr_meliscommerce_attribute_list_header_left_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttributeList',
                                                'action' => 'render-attribute-list-header-left-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_attribute_list_header_title' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_attribute_list_header_title',
                                                        'melisKey' => 'meliscommerce_attribute_list_header_title',
                                                        'name' => 'tr_meliscommerce_attribute_list_header_title',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttributeList',
                                                        'action' => 'render-attribute-list-header-title',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_attribute_list_header_right_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_attribute_list_header_right_container',
                                                'melisKey' => 'meliscommerce_attribute_list_header_right_container',
                                                'name' => 'tr_meliscommerce_attribute_list_header_right_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttributeList',
                                                'action' => 'render-attribute-list-header-right-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_attribute_list_add_attribute' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_attribute_list_add_attribute',
                                                        'melisKey' => 'meliscommerce_attribute_list_add_attribute',
                                                        'name' => 'tr_meliscommerce_attribute_list_add_attribute',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttributeList',
                                                        'action' => 'render-attribute-list-add-attribute',
                                                    ],
                                                ]
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_attribute_list_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_attribute_list_content',
                                        'melisKey' => 'meliscommerce_attribute_list_content',
                                        'name' => 'tr_meliscommerce_attribute_list_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAttributeList',
                                        'action' => 'render-attribute-list-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_attribute_list_content_table' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_attribute_list_content_table',
                                                'melisKey' => 'meliscommerce_attribute_list_content_table',
                                                'name' => 'tr_meliscommerce_attribute_list_content_table',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttributeList',
                                                'action' => 'render-attribute-list-content-table',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'meliscommerce_attribute' => [
                    'interface' => [
                        'meliscommerce_attribute_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_attribute_page',
                                'melisKey' => 'meliscommerce_attribute_page',
                                'name' => 'tr_meliscommerce_attribute_page',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttribute',
                                'action' => 'render-attribute-page',
                                'jscallback' => '',
                                'jsdatas' => []
                            ],
                            'interface' => [
                                'meliscommerce_attribute_header' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_attribute_header',
                                        'melisKey' => 'meliscommerce_attribute_header',
                                        'name' => 'tr_meliscommerce_attribute_header',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAttribute',
                                        'action' => 'render-attribute-header',
                                    ],
                                    'interface' => [
                                        'meliscommerce_attribute_header_left' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_attribute_header_left',
                                                'melisKey' => 'meliscommerce_attribute_header_left',
                                                'name' => 'tr_meliscommerce_attribute_header_left',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttribute',
                                                'action' => 'render-attribute-header-left',
                                            ],
                                            'interface' => [
                                                'meliscommerce_attribute_header_title' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_attribute_header_title',
                                                        'melisKey' => 'meliscommerce_attribute_header_title',
                                                        'name' => 'tr_meliscommerce_attribute_page',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-header-title',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_attribute_header_right' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_attribute_header_right',
                                                'melisKey' => 'meliscommerce_attribute_header_right',
                                                'name' => 'tr_meliscommerce_attribute_header_right',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttribute',
                                                'action' => 'render-attribute-header-right',
                                            ],
                                            'interface' => [
                                                'meliscommerce_attribute_header_right_save' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_attribute_header_right_save',
                                                        'melisKey' => 'meliscommerce_attribute_header_right_save',
                                                        'name' => 'tr_meliscommerce_attribute_header_right_save',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-header-save',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_attribute_page_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_attribute_page_content',
                                        'melisKey' => 'meliscommerce_attribute_page_content',
                                        'name' => 'tr_meliscommerce_attribute_page_content'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAttribute',
                                        'action' => 'render-attribute-page-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_attribute_page_tabs_main' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_attribute_page_tabs_main',
                                                'melisKey' => 'meliscommerce_attribute_page_tabs_main',
                                                'name' => 'tr_meliscommerce_attribute_page_tabs_main',
                                                'icon' => 'glyphicons tag',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttribute',
                                                'action' => 'render-attribute-page-tabs-main',
                                            ],
                                            'interface' => [
                                                'meliscommerce_attribute_tabs_content_main_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_attribute_tabs_content_main_header',
                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_main_header',
                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_main_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_attribute_tabs_content_main_header_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_attribute_tabs_content_main_header_left',
                                                                'melisKey' => 'meliscommerce_attribute_tabs_content_main_header_left',
                                                                'name' => 'tr_meliscommerce_attribute_tabs_content_main_header_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_attribute_tabs_content_main_header_title' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_attribute_tabs_content_main_header_title',
                                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_main_header_title',
                                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_main_header_title',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-header-title',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_attributes_tabs_content_main_header_right' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_attributes_tabs_content_main_header_right',
                                                                'melisKey' => 'meliscommerce_attributes_tabs_content_main_header_right',
                                                                'name' => 'tr_meliscommerce_attributes_tabs_content_main_header_right',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_attribute_tabs_content_main_header_status' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_attribute_tabs_content_main_header_status',
                                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_main_header_status',
                                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_main_header_status',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-header-status',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_attribute_tabs_content_main_details' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_attribute_tabs_content_main_details',
                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_main_details',
                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_main_details',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-details',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_attribute_tabs_content_main_details_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_attribute_tabs_content_main_details_left',
                                                                'melisKey' => 'meliscommerce_attribute_tabs_content_main_details_left',
                                                                'name' => 'tr_meliscommerce_attribute_tabs_content_main_details_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-details-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_attribute_tabs_content_general_data_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id-meliscommerce_attribute_tabs_content_general_data_header',
                                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_general_data_header',
                                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_general_data_header',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-sub-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_attribute_tabs_content_general_data_header_left' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_attribute_tabs_content_general_data_header_left',
                                                                                'melisKey' => 'meliscommerce_attribute_tabs_content_general_data_header_left',
                                                                                'name' => 'tr_meliscommerce_attribute_tabs_content_general_data_header_left',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComAttribute',
                                                                                'action' => 'render-attribute-tabs-content-sub-header-left',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_attribute_tabs_content_general_data_header_title' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_attribute_tabs_content_general_data_header_title',
                                                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_general_data_header_title',
                                                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_general_data_header_title',
                                                                                        'icon' => 'fa fa-cogs'
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComAttribute',
                                                                                        'action' => 'render-attribute-tabs-content-sub-header-title',
                                                                                    ],
                                                                                    'inteface' => [

                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_attribute_tabs_content_general_data_details' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_attribute_tabs_content_general_data_details',
                                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_general_data_details',
                                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_general_data_details',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-sub-details',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_attribute_tabs_content_general_data_form' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_attribute_tabs_content_general_data_form',
                                                                                'melisKey' => 'meliscommerce_attribute_tabs_content_general_data_form',
                                                                                'name' => 'tr_meliscommerce_attribute_tabs_content_general_data_form',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComAttribute',
                                                                                'action' => 'render-attribute-form-general-data',
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
                                        'meliscommerce_attribute_page_tabs_labels' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_attribute_page_tabs_labels',
                                                'melisKey' => 'meliscommerce_attribute_page_tabs_labels',
                                                'name' => 'tr_meliscommerce_attribute_page_tabs_labels',
                                                'icon' => 'glyphicons notes',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttribute',
                                                'action' => 'render-attribute-page-tabs-main',
                                            ],
                                            'interface' => [
                                                'meliscommerce_attribute_page_tabs_labels_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_attribute_page_tabs_labels_header',
                                                        'melisKey' => 'meliscommerce_attribute_page_tabs_labels_header',
                                                        'name' => 'tr_meliscommerce_attribute_page_tabs_labels_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_attribute_page_tabs_labels_header_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_attribute_page_tabs_labels_header_left',
                                                                'melisKey' => 'meliscommerce_attribute_page_tabs_labels_header_left',
                                                                'name' => 'tr_meliscommerce_attribute_page_tabs_labels_header_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_attribute_page_tabs_labels_header_title' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_attribute_page_tabs_labels_header_title',
                                                                        'melisKey' => 'meliscommerce_attribute_page_tabs_labels_header_title',
                                                                        'name' => 'tr_meliscommerce_attribute_page_tabs_labels',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-header-title',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_attributes_tabs_content_labels_details' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_attributes_tabs_content_labels_details',
                                                        'melisKey' => 'meliscommerce_attributes_tabs_content_labels_details',
                                                        'name' => 'tr_meliscommerce_attributes_tabs_content_labels_details',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-details',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_attributes_tabs_content_labels_details_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_attributes_tabs_content_labels_details_left',
                                                                'melisKey' => 'meliscommerce_attributes_tabs_content_labels_details_left',
                                                                'name' => 'tr_meliscommerce_attributes_tabs_content_labels_details_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-details-labels',
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_attribute_page_tabs_values' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_attribute_page_tabs_values',
                                                'melisKey' => 'meliscommerce_attribute_page_tabs_values',
                                                'name' => 'tr_meliscommerce_attribute_page_tabs_values',
                                                'icon' => 'glyphicons list',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttribute',
                                                'action' => 'render-attribute-page-tabs-main',
                                            ],
                                            'interface' => [
                                                'meliscommerce_attribute_page_tabs_values_header' => [
                                                    'conf' => [
                                                        'id' => 'id-meliscommerce_attribute_page_tabs_values_header',
                                                        'melisKey' => 'meliscommerce_attribute_page_tabs_values_header',
                                                        'name' => 'tr_meliscommerce_attribute_page_tabs_values_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_attribute_page_tabs_values_header_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_attribute_page_tabs_values_header_left',
                                                                'melisKey' => 'meliscommerce_attribute_page_tabs_values_header_left',
                                                                'name' => 'tr_meliscommerce_attribute_page_tabs_values_header_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_attribute_page_tabs_values_header_title' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_attribute_page_tabs_values_header_title',
                                                                        'melisKey' => 'meliscommerce_attribute_page_tabs_values_header_title',
                                                                        'name' => 'tr_meliscommerce_attribute_page_tabs_values',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-header-title',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_attribute_page_tabs_values_header_right' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_attribute_page_tabs_values_header_right',
                                                                'melisKey' => 'meliscommerce_attribute_page_tabs_values_header_right',
                                                                'name' => 'tr_meliscommerce_attribute_page_tabs_values_header_right',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_attribute_page_tabs_values_header_add' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_attribute_page_tabs_values_header_add',
                                                                        'melisKey' => 'meliscommerce_attribute_page_tabs_values_header_add',
                                                                        'name' => 'tr_meliscommerce_attribute_page_tabs_values_header_add',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-header-values-add',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_attributes_tabs_content_values_details' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_attributes_tabs_content_values_details',
                                                        'melisKey' => 'meliscommerce_attributes_tabs_content_values_details',
                                                        'name' => 'tr-meliscommerce_attributes_tabs_content_values_details',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-details',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_attributes_tabs_content_values_details_table' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_attributes_tabs_content_values_details_table',
                                                                'melisKey' => 'meliscommerce_attributes_tabs_content_values_details_table',
                                                                'name' => 'tr_meliscommerce_attributes_tabs_content_values_details_table',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-details-values-table',
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
                'meliscommerce_attribute_value_modal' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_attribute_value_modal',
                        'name' => 'tr_meliscommerce_attribute_value_modal',
                        'melisKey' => 'meliscommerce_attribute_value_modal',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComAttribute',
                        'action' => 'render-attribute-modal',
                    ],
                    'interface' => [
                        'meliscommerce_attribute_value_modal_value_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_attribute_value_modal_value_form',
                                'name' => 'tr_meliscommerce_attribute_value_modal_value_form',
                                'melisKey' => 'meliscommerce_attribute_value_modal_value_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttribute',
                                'action' => 'render-attribute-modal-value-form',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];