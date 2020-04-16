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
            'tools' => [
                'meliscommerce_attribute_list' => [
                    'table' => [
                        'target' => '#tableAttributeList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComAttributeList/getAttributeListData',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [
                                'attribute-list-table-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttributeList',
                                    'action' => 'render-attribute-list-content-filter-limit'
                                ],
                            ],
                        
                            'center' => [
                                'attribute-list-table-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttributeList',
                                    'action' => 'render-attribute-list-content-filter-search'
                                ],
                            ],
                        
                            'right' => [
                                'attribute-list-table-filter-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttributeList',
                                    'action' => 'render-attribute-list-content-filter-refresh'
                                ],
                            ],
                        ],
                        
                        'columns' => [
                            'attr_id' => [
                                'text' => 'tr_meliscommerce_attribute_list_col_id',
                                'css' => ['width' => '3%'],
                                'sortable' => true,
                            ],
                            'attr_status' => [
                                'text' => 'tr_meliscommerce_attribute_list_col_status',
                                'css' => ['width' => '3%'],
                                'sortable' => true,
                            ],
                        
                            'attr_visible' => [
                                'text' => 'tr_meliscommerce_attribute_list_col_visible',
                                'css' => ['width' => '3%'],
                                'sortable' => true,
                            ],
                        
                            'attr_searchable' => [
                                'text' => 'tr_meliscommerce_attribute_list_col_searchable',
                                'css' => ['width' => '3%'],
                                'sortable' => true,
                            ],
                        
                            'atrans_name' => [
                                'text' => 'tr_meliscommerce_attribute_list_col_name',
                                'css' => ['width' => '20%'],
                                'sortable' => false,
                            ],
                        
                            'attr_reference' => [
                                'text' => 'tr_meliscommerce_attribute_list_col_reference',
                                'css' => ['width' => '20%'],
                                'sortable' => false,
                            ],
                            
                            'atype_name' => [
                                'text' => 'tr_meliscommerce_attribute_list_col_type',
                                'css' => ['width' => '20%'],
                                'sortable' => true,
                            ],
                        ],
                        
                        'searchables' => [],
                        'actionButtons' => [
                            'info' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttributeList',
                                'action' => 'render-attribute-list-content-action-info'
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttributeList',
                                'action' => 'render-attribute-list-content-action-delete'
                            ],
                        ],
                    ],
                ],
                'meliscommerce_attribute' => [
                    'table' => [
                        'target' => '#tableAttributeValue',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComAttribute/getAttributeValueData',
                        'dataFunction' => 'initAttributeValue',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [
                                'attribute-table-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttribute',
                                    'action' => 'render-attribute-content-filter-limit'
                                ],
                            ],
                
                            'center' => [
                                'attribute-table-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttribute',
                                    'action' => 'render-attribute-content-filter-search'
                                ],
                            ],
                
                            'right' => [
                                'attribute-table-filter-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttribute',
                                    'action' => 'render-attribute-content-filter-refresh'
                                ],
                            ],
                        ],
                
                        'columns' => [
                            'atval_id' => [
                                'text' => 'tr_meliscommerce_attribute_col_id',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'value' => [
                                'text' => 'tr_meliscommerce_attribute_col_value',
                                'css' => ['width' => '60%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                        ],
                
                        'searchables' => [],
                        'actionButtons' => [
                            'info' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttribute',
                                'action' => 'render-attribute-content-action-info'
                            ],
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttribute',
                                'action' => 'render-attribute-content-action-delete'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
