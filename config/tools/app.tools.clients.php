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
                'meliscommerce_clients_list' => [
                    'conf' => [
                        'title' => 'tr_meliscommerce_clients_list',
                        'id' => 'id_meliscommerce_clients_list',
                    ],
                    'table' => [
                        // table ID
                        'target' => '#clientListTbl',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComClientList/getClientList',
                        'dataFunction' => 'initClientsFilters',
                        'ajaxCallback' => 'accountsTableCallback(); ',
                        'filters' => [
                            'left' => [
                                'meliscommerce-clients-list-tbl-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientList',
                                    'action' => 'render-client-list-table-limit',
                                ],
                                'meliscommerce-clients-group-filter' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientList',
                                    'action' => 'render-client-list-table-group-filter',
                                ],
                                'meliscommerce-clients-status-filter' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientList',
                                    'action' => 'render-client-list-table-status-filter',
                                ],
                            ],
                            'center' => [
                                'meliscommerce-clients-list-tbl-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientList',
                                    'action' => 'render-client-list-table-search',
                                ],
                            ],
                            'right' => [
                                'meliscommerce-clients-list-tbl-export' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientList',
                                    'action' => 'render-client-list-table-export',
                                ],
                                'meliscommerce-clients-list-tbl-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClientList',
                                    'action' => 'render-client-list-table-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'cli_id' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_id',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cli_status' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_status',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cgroup_name' => [
                                'text' => 'tr_meliscommerce_clients_group_common_group',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cli_person' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_person',
                                'css' => ['width' => '20%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cli_company' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_company',
                                'css' => ['width' => '20%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cli_num_orders' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_num_orders',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            'cli_last_order' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_last_order',
                                'css' => ['width' => '15%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cli_date_creation' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_date_created',
                                'css' => ['width' => '15%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                        ],
                        // define what columns can be used in searching
                        'searchables' => [
                            'cli_id',
                            'cper_name',
                            'cper_firstname',
                            'ccomp_name',
                            'cli_date_creation',
                        ],
                        'actionButtons' => [
                            'delete' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientList',
                                'action' => 'render-client-list-table-delete',
                            ],
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientList',
                                'action' => 'render-client-list-table-view',
                            ],
                        ]
                    ],
                ],
                'meliscommerce_client_order_list' => [
                    'table' => [
                        'target' => '',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComOrderList/getOrderListData',
                        'dataFunction' => 'initClientOrderList',
                        'ajaxCallback' => 'initClientListTitle()',
                        'filters' => [
                            'left' => [
                                'meliscommerce-clients-tbl-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClient',
                                    'action' => 'render-client-table-limit',
                                ],
                                'order-list-table-filter-date' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-date'
                                ],
                            ],
                            'center' => [
                                'meliscommerce-clients-tbl-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClient',
                                    'action' => 'render-client-table-search',
                                ],
                                'order-list-table-filter-bulk' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComOrderList',
                                    'action' => 'render-order-list-content-filter-bulk'
                                ],
                            ],
                            'right' => [
                                'meliscommerce-clients-tbl-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComClient',
                                    'action' => 'render-client-table-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'ord_id' => [
                                'text' => 'tr_meliscommerce_order_list_col_id',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'ord_reference' => [
                                'text' => 'tr_meliscommerce_order_list_col_reference',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'ord_status' => [
                                'text' => 'tr_meliscommerce_order_list_col_status',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'products' => [
                                'text' => '<i class="fa icon-shippment fa-lg"></i>',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'price' => [
                                'text' => 'tr_meliscommerce_order_list_col_price',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            /* 'ccomp_name' => [
                                'text' => 'tr_meliscommerce_order_list_col_company',
                                'css' => ['width' => '15%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            'civt_min_name' => [
                                'text' => 'tr_meliscommerce_order_list_col_civility',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => false,
                            ], */
                            'cper_firstname' => [
                                'text' => 'tr_meliscommerce_order_list_col_firstname',
                                'css' => ['width' => '15%', 'padding-right' => '0'],
                                'sortable' => false,
                            ],
                            'cper_name' => [
                                'text' => 'tr_meliscommerce_order_list_col_name',
                                'css' => ['width' => '15%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'ord_date_creation' => [
                                'text' => 'tr_meliscommerce_order_list_col_date',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                        ],
                        'searchables' => [],
                        'actionButtons' => [
                            'info' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClient',
                                'action' => 'render-client-table-view'
                            ],
                        ],
                    ],
                ],
                'meliscommerce_clients_contact_list' => [
                    'conf' => [
                        'title' => 'tr_meliscommerce_clients_contact_list',
                        'id' => 'id_meliscommerce_clients_contact_list',
                    ],
                    'table' => [
                        // table ID
                        'target' => '#accountContactList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComContact/getContactList',
                        'dataFunction' => '',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [
                                'meliscommerce-account-contact-list-tbl-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-list-table-limit',
                                ],
                            ],
                            'center' => [
                                'meliscommerce-account-contact-list-tbl-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-list-table-search',
                                ],
                            ],
                            'right' => [
                                'meliscommerce-account-contact-list-tbl-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-list-table-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'cper_id' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_id',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cper_status' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_status',
                                'css' => ['width' => '5%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'contact_name' => [
                                'text' => 'tr_meliscommerce_contact_name',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cper_email' => [
                                'text' => 'tr_meliscommerce_contact_email',
                                'css' => ['width' => '20%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                        ],
                        // define what columns can be used in searching
                        'searchables' => [
                            'cper_id',
                            'cper_name',
                            'cper_firstname',
                            'cper_email'
                        ],
                        'actionButtons' => [
                            'unlink' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClient',
                                'action' => 'render-account-contact-list-table-unlink',
                            ],
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComContact',
                                'action' => 'render-account-contact-list-table-edit',
                            ],
                        ]
                    ],
                ],
            ]
        ]
    ]
];