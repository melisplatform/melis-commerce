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
                    'conf' => [
                        'title' => 'tr_meliscommerce_contact_list',
                        'id' => 'id_meliscommerce_contact_list',
                    ],
                    'table' => [
                        // table ID
                        'target' => '#contactList',
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