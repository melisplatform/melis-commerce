<?php

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(
                'meliscommerce_assoc_var' => array(
                    'conf' => array(
                        'title' => 'tr_meliscommerce_avar_title',
                    ),
                    'table' => array(
                        'target' => '',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComAssociateVariant/getAssocVariantList',
                        'dataFunction' => 'loadAssocVariantList',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'variants-list-filter-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-assoc-var-list-filter-limit',
                                ),
                            ),
                            'center' => array(
                                'variants-list-filter-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-assoc-var-list-filter-search',
                                ),
                            ),
                            'right' => array(
                                'variants-list-filter-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-assoc-var-list-filter-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'var_id' => array(
                                'text' => 'tr_meliscommerce_assoc_var_col_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,

                            ),
                            'var_status' => array(
                                'text' => 'tr_meliscommerce_assoc_var_col_status',
                                'css' => array('width' => '4%', 'padding-right' => '0'),
                                'sortable' => true,

                            ),
                            'var_product_name' => array(
                                'text' => 'tr_meliscommerce_assoc_var_product_name',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => false,

                            ),
                            'var_sku' => array(
                                'text' => 'tr_meliscommerce_assoc_var_col_sku',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,

                            ),
                            'var_attributes' => array(
                                'text' => 'tr_meliscommerce_assoc_var_col_attributes',
                                'css' => array('width' => '50%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),

                        ),
                        'searchables' => array('var_id', 'var_sku'),
                        'actionButtons' => array(
                            'view-var' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-assoc-var-list-action-view',
                            ),
                            'remove' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-assoc-var-list-action-remove',
                            ),
                        ),
                    ),
                    'modals' => array(),
                    'forms' => array(),
                ),

                'meliscommerce_assoc_var2' => array(
                    'conf' => array(
                        'title' => 'tr_meliscommerce_avar_title',
                    ),
                    'table' => array(
                        'target' => '',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComAssociateVariant/getVariantsList',
                        'dataFunction' => 'loadAssocVariantList',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'assoc-variants-list-filter-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-var-list-filter-limit',
                                ),
                            ),
                            'center' => array(
                                'assoc-variants-list-filter-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-var-list-filter-search',
                                ),
                            ),
                            'right' => array(
                                'assoc-variants-list-filter-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAssociateVariant',
                                    'action' => 'render-tab-content-var-list-filter-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'var_id' => array(
                                'text' => 'tr_meliscommerce_assoc_var_col_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                        
                            ),
                            'var_status' => array(
                                'text' => 'tr_meliscommerce_assoc_var_col_status',
                                'css' => array('width' => '4%', 'padding-right' => '0'),
                                'sortable' => true,

                            ),
                            'var_assigned' => array(
                                'text' => 'tr_meliscommerce_assoc_var_col_assigned',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => false,

                            ),
                            'var_product_name' => array(
                                'text' => 'tr_meliscommerce_assoc_var_product_name',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => false,

                            ),
                            'var_sku' => array(
                                'text' => 'tr_meliscommerce_assoc_var_col_sku',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,

                            ),
                            'var_attributes' => array(
                                'text' => 'tr_meliscommerce_assoc_var_col_attributes',
                                'css' => array('width' => '50%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),

                        ),
                        'searchables' => array('var_id', 'var_sku'),
                        'actionButtons' => array(
                            'assoc-view-var' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-var-list-action-view',
                            ),
                            'assign' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-var-list-action-assign',
                            ),
                        ),
                    ),
                    'modals' => array(),
                    'forms' => array(),
                ),
            ),
        ),
    ),
);