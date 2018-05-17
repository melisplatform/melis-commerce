<?php

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(   
                'meliscommerce_products' => array(   
                    
                    'table' => array(
                        'target' => '#tableProductVariantList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComVariantList/renderProductsVariantData',
                        'dataFunction' => 'initProductVariant',
                        'ajaxCallback' => 'melisCommerce.initTooltipVarTable();initVariantSwitch();',
                        'filters' => array(
                            'left' => array(
                                'productvariant-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComVariantList',
                                    'action' => 'render-products-variant-tab-table-limit'
                                ),
                            ),
                            'center' => array(
                                'productvariant-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComVariantList',
                                    'action' => 'render-products-variant-tab-table-search'
                                ),  
                            ),
                            'right' => array(

//                                 'productvariant-grid' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComVariantList',
//                                     'action' => 'render-products-variant-tab-table-grid'
//                                 ),
//                                 'productvariant-list' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComVariantList',
//                                     'action' => 'render-products-variant-tab-table-list'
//                                 ),
                                'productvariant-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComVariantList',
                                    'action' => 'render-products-variant-tab-table-refresh'
                                ),
                            ),
                        ),
                        'columns' => array(
//                             'select' => array(
//                                 'text' => '',
//                                 'css' => array('width' => '3%', 'padding-right' => '0'),
//                                 'sortable' => false,
                               
//                             ),
                            'var_id' => array(
                                'text' => 'tr_meliscommerce_variant_list_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'var_main_variant' => array(
                                'text' => 'tr_meliscommerce_variant_main_information_main_variant_label',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => false,                            
                            ),
                            'var_image' => array(
                                'text' => 'tr_meliscommerce_variant_list_image',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'var_status' => array(
                                'text' => 'tr_meliscommerce_variant_main_information_status_label',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'var_sku' => array(
                                'text' => 'tr_meliscommerce_variant_list_sku',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'var_attributes' => array(
                                'text' => 'tr_meliscommerce_variant_main_attributes_col',
                                'css' => array('width' => '40%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                    
                        ),
                        'searchables' => array(),
                        'actionButtons' => array(
                            'duplicate' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrdVarDuplication',
                                'action' => 'render-duplicate-variant-button',
                            ),
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComVariantList',
                                'action' => 'render-tool-variant-action-edit',
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComVariantList',
                                'action' => 'render-tool-variant-action-delete',
                            ),
                        ),
                    ),                   
                ),
                'meliscommerce_products_list' => array(
                    'table' => array(
                        'target' => '#tableProductList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComProductList/getProductsList',
                        'dataFunction' => '',
                        'ajaxCallback' => 'melisCommerce.initTooltipTable();',
                        'filters' => array(
                            'left' => array(
//                                 'product-list-table-filter-bulk' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComProductList',
//                                     'action' => 'render-product-list-content-filter-bulk'
//                                 ),
                                'product-list-table-filter-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-limit'
                                ),
                            ),
                            
                            'center' => array(
                                'product-list-table-filter-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-search'
                                ),
                                
//                                 'product-list-table-filter-search' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComProductList',
//                                     'action' => 'render-product-list-content-filter-search'
//                                 ),
//                                 'product-list-table-filter-limit' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComProductList',
//                                     'action' => 'render-product-list-content-filter-limit'
//                                 ),
                            ),
                            
                            'right' => array(
//                                 'product-list-table-filter-grid-view' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComProductList',
//                                     'action' => 'render-product-list-content-filter-grid-view'
//                                 ),
//                                 'product-list-table-filter-list-view' => array(
//                                     'module' => 'MelisCommerce',
//                                     'controller' => 'MelisComProductList',
//                                     'action' => 'render-product-list-content-filter-list-view'
//                                 ),
                                'product-list-table-filter-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProductList',
                                    'action' => 'render-product-list-content-filter-refresh'
                                ),
                            ),
                        ),
                        
                        'columns' => array(
//                             'product_table_checkbox' => array(
//                                 'text' => '',
//                                 'css' => array('width' => '1%', 'padding-right' => '0'),
//                                 'sortable' => false,
//                             ),
                            
                            'prd_id' => array(
                                'text' => 'tr_meliscommerce_product_list_col_id',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),

                            'prd_status' => array(
                                'text' => 'tr_meliscommerce_product_list_col_status',
                                'css' => array('width' => '1%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),

                            'product_image' => array(
                                'text' => 'tr_meliscommerce_product_list_col_image',
                                'css' => array('width' => '5%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            'product_name' => array(
                                'text' => 'tr_meliscommerce_product_list_col_name',
                                'css' => array('width' => '20%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                            
                            'product_categories' => array(
                                'text' => 'tr_meliscommerce_product_list_col_categories',
                                'css' => array('width' => '30%', 'padding-right' => '0'),
                                'sortable' => false,
                            ),
                        ),
                        
                        'searchables' => array(),
                        'actionButtons' => array(
                            'duplicate' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComPrdVarDuplication',
                                'action' => 'render-duplicate-product-button',
                            ),
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComProductList',
                                'action' => 'render-product-list-content-action-edit'
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComProductList',
                                'action' => 'render-product-list-content-action-delete'
                            ),
                        ),
                    )
                ),
                'forms' => array(
                    
                ),
            ),
        ),
    ),
);