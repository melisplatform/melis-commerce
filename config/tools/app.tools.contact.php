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
                'meliscommerce_contact_list' => [
                    'table' => [
                        // table ID
                        'target' => '#contactList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComContact/getContactList',
                        'dataFunction' => 'contactListTableDataFunction',
                        'ajaxCallback' => '',
                        'filters' => [
                            'left' => [
                                'meliscommerce-contact-list-tbl-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-list-table-limit',
                                ],
                                'meliscommerce-contact-list-tbl-account-select' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-list-table-account-select',
                                ],
                            ],
                            'center' => [
                                'meliscommerce-contact-list-tbl-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-list-table-search',
                                ],
                                'meliscommerce-contact-list-tbl-type-select' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-list-table-type-select',
                                ],
                            ],
                            'right' => [
                                'meliscommerce-contact-list-tbl-import' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-list-table-import',
                                ],
                                'meliscommerce-contact-list-tbl-export' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-list-table-export',
                                ],
                                'meliscommerce-contact-list-tbl-refresh' => [
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
                            'cper_firstname' => [
                                'text' => 'tr_meliscommerce_contact_firstname',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cper_name' => [
                                'text' => 'tr_meliscommerce_contact_name',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cli_name' => [
                                'text' => 'tr_meliscommerce_contact_default_account_name',
                                'css' => ['width' => '20%', 'padding-right' => '0'],
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
                            'cper_email',
                            'client.cli_name'
                        ],
                        'actionButtons' => [
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComContact',
                                'action' => 'render-account-contact-list-table-edit',
                            ],
                        ]
                    ],
                ],
                'meliscommerce_contact_associated_account_list' => [
                    'table' => [
                        // table ID
                        'target' => '#contactAssociatedAccountList',
                        'ajaxUrl' => '/melis/MelisCommerce/MelisComContact/getContactAssociatedAccountList',
                        'dataFunction' => 'setContactId',
                        'ajaxCallback' => 'contactAssociatedAccountCallback();',
                        'filters' => [
                            'left' => [
                                'meliscommerce-account-contact-assoc-account-list-tbl-limit' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-assoc-account-list-table-limit',
                                ],
                            ],
                            'center' => [
                                'meliscommerce-account-contact-assoc-account-list-tbl-search' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-assoc-account-list-table-search',
                                ],
                            ],
                            'right' => [
                                'meliscommerce-account-contact-assoc-account-list-tbl-refresh' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComContact',
                                    'action' => 'render-account-contact-assoc-account-list-table-refresh',
                                ],
                            ],
                        ],
                        'columns' => [
                            'cli_id' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_id',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cli_status' => [
                                'text' => 'tr_meliscommerce_clients_table_Client_status',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'cli_name' => [
                                'text' => 'tr_meliscommerce_contact_account_name',
                                'css' => ['width' => '40%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'default_account' => [
                                'text' => 'tr_meliscommerce_contact_is_default',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                            'default_contact' => [
                                'text' => 'tr_meliscommerce_contact_is_default_contact',
                                'css' => ['width' => '10%', 'padding-right' => '0'],
                                'sortable' => true,
                            ],
                        ],
                        // define what columns can be used in searching
                        'searchables' => [
                            'cli_id',
                            'cli_name'
                        ],
                        'actionButtons' => [
                            'edit' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComContact',
                                'action' => 'render-account-contact-assoc-account-list-table-edit',
                            ],
                            'set_default' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComContact',
                                'action' => 'render-account-contact-assoc-account-list-table-set-default',
                            ],
                            'unlink' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComContact',
                                'action' => 'render-account-contact-assoc-account-list-table-unlink',
                            ],
                        ]
                    ],
                ],
            ]
        ]
    ]
];