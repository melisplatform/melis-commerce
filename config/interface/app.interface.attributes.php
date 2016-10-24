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
                    '/MelisCommerce/js/tools/attribute.tool.js',
                ),
                'css' => array(),
            ),
            'datas' => array(),
            'interface' => array(
                'meliscommerce_attribute_list' => array(
                    'interface' => array(
                        'meliscommerce_attribute_list_leftmenu' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_attribute_list_page',
                                'melisKey' => 'meliscommerce_attribute_list_page',
                                'name' => 'tr_meliscommerce_attribute_list_page',
                                'icon' => 'fa fa-cubes',
                            ),
                        ),
                        'meliscommerce_attribute_list_page' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_attribute_list_page',
                                'melisKey' => 'meliscommerce_attribute_list_page',
                                'name' => 'tr_meliscommerce_attribute_list_page',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttributeList',
                                'action' => 'render-attribute-list-page',
                                'jscallback' => '',
                                'jsdatas' => array()
                            ),
                            'interface' => array(
                                'meliscommerce_attribute_list_header_container' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_attribute_list_header_container',
                                        'melisKey' => 'meliscommerce_attribute_list_header_container',
                                        'name' => 'tr_meliscommerce_attribute_list_header_container',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAttributeList',
                                        'action' => 'render-attribute-list-header-container',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_attribute_list_header_left_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_attribute_list_header_left_container',
                                                'melisKey' => 'meliscommerce_attribute_list_header_left_container',
                                                'name' => 'tr_meliscommerce_attribute_list_header_left_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttributeList',
                                                'action' => 'render-attribute-list-header-left-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_attribute_list_header_title' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_attribute_list_header_title',
                                                        'melisKey' => 'meliscommerce_attribute_list_header_title',
                                                        'name' => 'tr_meliscommerce_attribute_list_header_title',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttributeList',
                                                        'action' => 'render-attribute-list-header-title',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_attribute_list_header_right_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_attribute_list_header_right_container',
                                                'melisKey' => 'meliscommerce_attribute_list_header_right_container',
                                                'name' => 'tr_meliscommerce_attribute_list_header_right_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttributeList',
                                                'action' => 'render-attribute-list-header-right-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_attribute_list_add_attribute' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_attribute_list_add_attribute',
                                                        'melisKey' => 'meliscommerce_attribute_list_add_attribute',
                                                        'name' => 'tr_meliscommerce_attribute_list_add_attribute',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttributeList',
                                                        'action' => 'render-attribute-list-add-attribute',
                                                    ),
                                                )
                                            ),
                                        ),
                                    ),
                                ),
                                'meliscommerce_attribute_list_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_attribute_list_content',
                                        'melisKey' => 'meliscommerce_attribute_list_content',
                                        'name' => 'tr_meliscommerce_attribute_list_content',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAttributeList',
                                        'action' => 'render-attribute-list-content',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_attribute_list_content_table' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_attribute_list_content_table',
                                                'melisKey' => 'meliscommerce_attribute_list_content_table',
                                                'name' => 'tr_meliscommerce_attribute_list_content_table',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttributeList',
                                                'action' => 'render-attribute-list-content-table',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'meliscommerce_attribute' => array(
                    'interface' => array(
                        'meliscommerce_attribute_page' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_attribute_page',
                                'melisKey' => 'meliscommerce_attribute_page',
                                'name' => 'tr_meliscommerce_attribute_page',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttribute',
                                'action' => 'render-attribute-page',
                                'jscallback' => '',
                                'jsdatas' => array()
                            ),
                            'interface' => array(
                                'meliscommerce_attribute_header' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_attribute_header',
                                        'melisKey' => 'meliscommerce_attribute_header',
                                        'name' => 'tr_meliscommerce_attribute_header',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAttribute',
                                        'action' => 'render-attribute-header',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_attribute_header_left' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_attribute_header_left',
                                                'melisKey' => 'meliscommerce_attribute_header_left',
                                                'name' => 'tr_meliscommerce_attribute_header_left',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttribute',
                                                'action' => 'render-attribute-header-left',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_attribute_header_title' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_attribute_header_title',
                                                        'melisKey' => 'meliscommerce_attribute_header_title',
                                                        'name' => 'tr_meliscommerce_attribute_page',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-header-title',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_attribute_header_right' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_attribute_header_right',
                                                'melisKey' => 'meliscommerce_attribute_header_right',
                                                'name' => 'tr_meliscommerce_attribute_header_right',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttribute',
                                                'action' => 'render-attribute-header-right',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_attribute_header_right_save' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_attribute_header_right_save',
                                                        'melisKey' => 'meliscommerce_attribute_header_right_save',
                                                        'name' => 'tr_meliscommerce_attribute_header_right_save',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-header-save',
                                                    ),
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                                'meliscommerce_attribute_page_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_attribute_page_content',
                                        'melisKey' => 'meliscommerce_attribute_page_content',
                                        'name' => 'tr_meliscommerce_attribute_page_content'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAttribute',
                                        'action' => 'render-attribute-page-content',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_attribute_page_tabs_main' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_attribute_page_tabs_main',
                                                'melisKey' => 'meliscommerce_attribute_page_tabs_main',
                                                'name' => 'tr_meliscommerce_attribute_page_tabs_main',
                                                'icon' => 'glyphicons tag',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttribute',
                                                'action' => 'render-attribute-page-tabs-main',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_attribute_tabs_content_main_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_attribute_tabs_content_main_header',
                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_main_header',
                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_main_header',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-header',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_attribute_tabs_content_main_header_left' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_attribute_tabs_content_main_header_left',
                                                                'melisKey' => 'meliscommerce_attribute_tabs_content_main_header_left',
                                                                'name' => 'tr_meliscommerce_attribute_tabs_content_main_header_left',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-header-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_attribute_tabs_content_main_header_title' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_attribute_tabs_content_main_header_title',
                                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_main_header_title',
                                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_main_header_title',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-header-title',
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                        'meliscommerce_attributes_tabs_content_main_header_right' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_attributes_tabs_content_main_header_right',
                                                                'melisKey' => 'meliscommerce_attributes_tabs_content_main_header_right',
                                                                'name' => 'tr_meliscommerce_attributes_tabs_content_main_header_right',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-header-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_attribute_tabs_content_main_header_status' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_attribute_tabs_content_main_header_status',
                                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_main_header_status',
                                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_main_header_status',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-header-status',
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                    ),
                                                ),
                                                'meliscommerce_attribute_tabs_content_main_details' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_attribute_tabs_content_main_details',
                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_main_details',
                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_main_details',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-details',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_attribute_tabs_content_main_details_left' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_attribute_tabs_content_main_details_left',
                                                                'melisKey' => 'meliscommerce_attribute_tabs_content_main_details_left',
                                                                'name' => 'tr_meliscommerce_attribute_tabs_content_main_details_left',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-details-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_attribute_tabs_content_general_data_header' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id-meliscommerce_attribute_tabs_content_general_data_header',
                                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_general_data_header',
                                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_general_data_header',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-sub-header',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_attribute_tabs_content_general_data_header_left' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_attribute_tabs_content_general_data_header_left',
                                                                                'melisKey' => 'meliscommerce_attribute_tabs_content_general_data_header_left',
                                                                                'name' => 'tr_meliscommerce_attribute_tabs_content_general_data_header_left',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComAttribute',
                                                                                'action' => 'render-attribute-tabs-content-sub-header-left',
                                                                            ),
                                                                            'interface' => array(
                                                                                'meliscommerce_attribute_tabs_content_general_data_header_title' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_attribute_tabs_content_general_data_header_title',
                                                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_general_data_header_title',
                                                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_general_data_header_title',
                                                                                        'icon' => 'fa fa-cogs'
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComAttribute',
                                                                                        'action' => 'render-attribute-tabs-content-sub-header-title',
                                                                                    ),
                                                                                    'inteface' => array(
                                                        
                                                                                    ),
                                                                                ),
                                                                            ),
                                                                        ),                                                                        
                                                                    ),
                                                                ),
                                                                'meliscommerce_attribute_tabs_content_general_data_details' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_attribute_tabs_content_general_data_details',
                                                                        'melisKey' => 'meliscommerce_attribute_tabs_content_general_data_details',
                                                                        'name' => 'tr_meliscommerce_attribute_tabs_content_general_data_details',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-sub-details',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_attribute_tabs_content_general_data_form' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_attribute_tabs_content_general_data_form',
                                                                                'melisKey' => 'meliscommerce_attribute_tabs_content_general_data_form',
                                                                                'name' => 'tr_meliscommerce_attribute_tabs_content_general_data_form',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComAttribute',
                                                                                'action' => 'render-attribute-form-general-data',
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
                                        'meliscommerce_attribute_page_tabs_labels' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_attribute_page_tabs_labels',
                                                'melisKey' => 'meliscommerce_attribute_page_tabs_labels',
                                                'name' => 'tr_meliscommerce_attribute_page_tabs_labels',
                                                'icon' => 'glyphicons notes',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttribute',
                                                'action' => 'render-attribute-page-tabs-main',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_attribute_page_tabs_labels_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_attribute_page_tabs_labels_header',
                                                        'melisKey' => 'meliscommerce_attribute_page_tabs_labels_header',
                                                        'name' => 'tr_meliscommerce_attribute_page_tabs_labels_header',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-header',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_attribute_page_tabs_labels_header_left' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_attribute_page_tabs_labels_header_left',
                                                                'melisKey' => 'meliscommerce_attribute_page_tabs_labels_header_left',
                                                                'name' => 'tr_meliscommerce_attribute_page_tabs_labels_header_left',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-header-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_attribute_page_tabs_labels_header_title' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_attribute_page_tabs_labels_header_title',
                                                                        'melisKey' => 'meliscommerce_attribute_page_tabs_labels_header_title',
                                                                        'name' => 'tr_meliscommerce_attribute_page_tabs_labels',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-header-title',
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                    ),
                                                ),
                                                'meliscommerce_attributes_tabs_content_labels_details' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_attributes_tabs_content_labels_details',
                                                        'melisKey' => 'meliscommerce_attributes_tabs_content_labels_details',
                                                        'name' => 'tr_meliscommerce_attributes_tabs_content_labels_details',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-details',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_attributes_tabs_content_labels_details_left' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_attributes_tabs_content_labels_details_left',
                                                                'melisKey' => 'meliscommerce_attributes_tabs_content_labels_details_left',
                                                                'name' => 'tr_meliscommerce_attributes_tabs_content_labels_details_left',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-details-labels',
                                                            ),                                                            
                                                        ),
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_attribute_page_tabs_values' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_attribute_page_tabs_values',
                                                'melisKey' => 'meliscommerce_attribute_page_tabs_values',
                                                'name' => 'tr_meliscommerce_attribute_page_tabs_values',
                                                'icon' => 'glyphicons list',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAttribute',
                                                'action' => 'render-attribute-page-tabs-main',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_attribute_page_tabs_values_header' => array(
                                                    'conf' => array(
                                                        'id' => 'id-meliscommerce_attribute_page_tabs_values_header',
                                                        'melisKey' => 'meliscommerce_attribute_page_tabs_values_header',
                                                        'name' => 'tr_meliscommerce_attribute_page_tabs_values_header',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-header',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_attribute_page_tabs_values_header_left' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_attribute_page_tabs_values_header_left',
                                                                'melisKey' => 'meliscommerce_attribute_page_tabs_values_header_left',
                                                                'name' => 'tr_meliscommerce_attribute_page_tabs_values_header_left',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-header-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_attribute_page_tabs_values_header_title' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_attribute_page_tabs_values_header_title',
                                                                        'melisKey' => 'meliscommerce_attribute_page_tabs_values_header_title',
                                                                        'name' => 'tr_meliscommerce_attribute_page_tabs_values',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-header-title',
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                        'meliscommerce_attribute_page_tabs_values_header_right' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_attribute_page_tabs_values_header_right',
                                                                'melisKey' => 'meliscommerce_attribute_page_tabs_values_header_right',
                                                                'name' => 'tr_meliscommerce_attribute_page_tabs_values_header_right',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-header-left',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_attribute_page_tabs_values_header_add' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_attribute_page_tabs_values_header_add',
                                                                        'melisKey' => 'meliscommerce_attribute_page_tabs_values_header_add',
                                                                        'name' => 'tr_meliscommerce_attribute_page_tabs_values_header_add',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComAttribute',
                                                                        'action' => 'render-attribute-tabs-content-header-values-add',
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                    ),
                                                ),
                                                'meliscommerce_attributes_tabs_content_values_details' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_attributes_tabs_content_values_details',
                                                        'melisKey' => 'meliscommerce_attributes_tabs_content_values_details',
                                                        'name' => 'tr-meliscommerce_attributes_tabs_content_values_details',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComAttribute',
                                                        'action' => 'render-attribute-tabs-content-details',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_attributes_tabs_content_values_details_table' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_attributes_tabs_content_values_details_table',
                                                                'melisKey' => 'meliscommerce_attributes_tabs_content_values_details_table',
                                                                'name' => 'tr_meliscommerce_attributes_tabs_content_values_details_table',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComAttribute',
                                                                'action' => 'render-attribute-tabs-content-details-values-table',
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
                'meliscommerce_attribute_value_modal' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_attribute_value_modal',
                        'name' => 'tr_meliscommerce_attribute_value_modal',
                        'melisKey' => 'meliscommerce_attribute_value_modal',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComAttribute',
                        'action' => 'render-attribute-modal',
                    ),
                    'interface' => array(
                        'meliscommerce_attribute_value_modal_value_form' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_attribute_value_modal_value_form',
                                'name' => 'tr_meliscommerce_attribute_value_modal_value_form',
                                'melisKey' => 'meliscommerce_attribute_value_modal_value_form',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttribute',
                                'action' => 'render-attribute-modal-value-form',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);