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
                'meliscommerce_assoc_var' => [
                    'conf' => [
                        'title' => 'tr_meliscommerce_avar_title',
                    ],
                    'table' => [
                        'target' => '',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComAssociateVariant/getAssocVariantList',
                        'dataFunction' => 'loadAssocVariantList',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [
                                'variants-list-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-assoc-var-list-filter-limit',
                                ],
                            ],
                            'center' => [
                                'variants-list-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-assoc-var-list-filter-search',
                                ],
                            ],
                            'right' => [
                                'variants-list-filter-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-assoc-var-list-filter-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'var_id' => [
                                'text' => 'tr_meliscommerce_assoc_var_col_id',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,

                            ],
                            'var_status' => [
                                'text' => 'tr_meliscommerce_assoc_var_col_status',
                                'css' => ['width' => '4%', 'padding-right' => '0'],
                                'sortable' => true,

                            ],
                            'var_product_name' => [
                                'text' => 'tr_meliscommerce_assoc_var_product_name',
                                'css' => ['width' => '20%', 'padding-right' => '0'],
                                'sortable' => false,

                            ],
                            'var_sku' => [
                                'text' => 'tr_meliscommerce_assoc_var_col_sku',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,

                            ],
                            'var_attributes' => [
                                'text' => 'tr_meliscommerce_assoc_var_col_attributes',
                                'css' => ['width' => '50%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],

                        ],
                        'searchables' => ['var_id', 'var_sku'],
                        'actionButtons' => [
                            'view-var' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-assoc-var-list-action-view',
                            ],
                            'remove' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-assoc-var-list-action-remove',
                            ],
                        ],
                    ],
                    'modals' => [],
                    'forms' => [],
                ],
                
                'meliscommerce_assoc_var2' => [
                    'conf' => [
                        'title' => 'tr_meliscommerce_avar_title',
                    ],
                    'table' => [
                        'target' => '',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComAssociateVariant/getProductList',
                        'dataFunction' => 'loadAssocVariantList',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [
                                'assoc-variants-list-filter-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-var-list-filter-limit',
                                ],
                            ],
                            'center' => [
                                'assoc-variants-list-filter-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-var-list-filter-search',
                                ],
                            ],
                            'right' => [
                                'assoc-variants-list-filter-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-var-list-filter-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'prd_id' => [
                                'text' => 'ID',
                                'css' => ['width' => '1%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'var_status' => [
                                'text' => 'Status',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'prd_name' => [
                                'text' => 'tr_meliscommerce_assoc_var_product_name',
                                'css' => ['width' => '60%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                        ],
                        'searchables' => [],
                        'actionButtons' => [
                            'assoc-view-var' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-var-list-action-view',
                            ],
                        ],
                    ],
                    'modals' => [],
                    'forms' => [],
                ],
            ],
        ],
    ],
];