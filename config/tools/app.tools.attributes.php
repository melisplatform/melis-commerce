<?php

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'tools' => array(
                'meliscommerce_attribute_list' => array(
                    'table' => array(
                        'target' => '#tableAttributeList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComAttributeList/getAttributeListData',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'attribute-list-table-filter-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttributeList',
                                    'action' => 'render-attribute-list-content-filter-limit'
                                ),
                            ),
                        
                            'center' => array(
                                'attribute-list-table-filter-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttributeList',
                                    'action' => 'render-attribute-list-content-filter-search'
                                ),
                            ),
                        
                            'right' => array(
                                'attribute-list-table-filter-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttributeList',
                                    'action' => 'render-attribute-list-content-filter-refresh'
                                ),
                            ),
                        ),
                        
                        'columns' => array(
                            'attr_id' => array(
                                'text' => 'tr_meliscommerce_attribute_list_col_id',
                                'css' => array('width' => '3%'),
                                'sortable' => true,
                            ),
                            'attr_status' => array(
                                'text' => 'tr_meliscommerce_attribute_list_col_status',
                                'css' => array('width' => '3%'),
                                'sortable' => true,
                            ),
                        
                            'attr_visible' => array(
                                'text' => 'tr_meliscommerce_attribute_list_col_visible',
                                'css' => array('width' => '3%'),
                                'sortable' => true,
                            ),
                        
                            'attr_searchable' => array(
                                'text' => 'tr_meliscommerce_attribute_list_col_searchable',
                                'css' => array('width' => '3%'),
                                'sortable' => true,
                            ),
                        
                            'atrans_name' => array(
                                'text' => 'tr_meliscommerce_attribute_list_col_name',
                                'css' => array('width' => '20%'),
                                'sortable' => false,
                            ),
                        
                            'attr_reference' => array(
                                'text' => 'tr_meliscommerce_attribute_list_col_reference',
                                'css' => array('width' => '20%'),
                                'sortable' => false,
                            ),
                            
                            'atype_name' => array(
                                'text' => 'tr_meliscommerce_attribute_list_col_type',
                                'css' => array('width' => '20%'),
                                'sortable' => true,
                            ),
                        ),
                        
                        'searchables' => array(),
                        'actionButtons' => array(
                            'info' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttributeList',
                                'action' => 'render-attribute-list-content-action-info'
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttributeList',
                                'action' => 'render-attribute-list-content-action-delete'
                            ),
                        ),
                    ),
                ),
                'meliscommerce_attribute' => array(
                    'table' => array(
                        'target' => '#tableAttributeValue',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComAttribute/getAttributeValueData',
                        'dataFunction' => 'initAttributeValue',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(
                                'attribute-table-filter-limit' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttribute',
                                    'action' => 'render-attribute-content-filter-limit'
                                ),
                            ),
                
                            'center' => array(
                                'attribute-table-filter-search' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttribute',
                                    'action' => 'render-attribute-content-filter-search'
                                ),
                            ),
                
                            'right' => array(
                                'attribute-table-filter-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComAttribute',
                                    'action' => 'render-attribute-content-filter-refresh'
                                ),
                            ),
                        ),
                
                        'columns' => array(
                            'atval_id' => array(
                                'text' => 'tr_meliscommerce_attribute_col_id',
                                'css' => array('width' => '10%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                            'value' => array(
                                'text' => 'tr_meliscommerce_attribute_col_value',
                                'css' => array('width' => '60%', 'padding-right' => '0'),
                                'sortable' => true,
                            ),
                        ),
                
                        'searchables' => array(),
                        'actionButtons' => array(
                            'info' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttribute',
                                'action' => 'render-attribute-content-action-info'
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAttribute',
                                'action' => 'render-attribute-content-action-delete'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
