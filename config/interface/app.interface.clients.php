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
                'name' => 'tr_meliscommerce_clients_Clients',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/client.tool.js',
                ],
            ],
            'datas' => [
                'default' => [
                    'accounts' => [
                        'hash_method' => 'sha256',
                        'salt' => 'salt_#{3xamPle;',
                        'length' => 25,
                    ],
                    'session' => [
                        'default_ttl' => 0, // 1 day, TTL (in seconds] for the session cookie expiry
                        'remember_me_ttl' => 1209600, // 14 days, TTL (in seconds] for the session cookie expiry
                    ],
                    'export' => [
                        'csv' => [
                            'clientFileName' => 'melis_client_export.csv',
                            'clientLimit' => 100,
                            'dir' => $_SERVER['DOCUMENT_ROOT'] . '/csv/'
                        ],
                    ],
                ]
            ],
            'interface' => [
                'meliscommerce_clients_list' => [
                    'interface' => [
                        'meliscommerce_clients_list_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_clients_list_page',
                                'melisKey' => 'meliscommerce_clients_list_page',
                                'name' => 'tr_meliscommerce_clients_Clients',
                                'icon' => 'fa fa-users',
                            ],
                        ],
                        'meliscommerce_clients_list_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_clients_list_page',
                                'melisKey' => 'meliscommerce_clients_list_page',
                                'name' => 'tr_meliscommerce_clients_list_page'
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientList',
                                'action' => 'render-client-list-page',
                            ],
                            'interface' => [
                                'meliscommerce_clients_list_header' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_clients_list_header',
                                        'melisKey' => 'meliscommerce_clients_list_header',
                                        'name' => 'tr_meliscommerce_clients_list_header'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClientList',
                                        'action' => 'render-client-list-header',
                                    ],
                                    'interface' => [
                                        'meliscommerce_clients_list_add_client' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_clients_list_add_client',
                                                'melisKey' => 'meliscommerce_clients_list_add_client',
                                                'name' => 'tr_meliscommerce_clients_list_add_client'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClientList',
                                                'action' => 'render-client-list-add-client',
                                            ]
                                        ]
                                    ]
                                ],
                                'meliscommerce_clients_list_widgets' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_clients_list_widgets',
                                        'melisKey' => 'meliscommerce_clients_list_widgets',
                                        'name' => 'tr_meliscommerce_clients_list_widgets',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClientList',
                                        'action' => 'render-client-list-widgets',
                                    ],
                                    'interface' => [
                                        'meliscommerce_clients_list_widgets_num_clients' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_clients_list_widgets_num_clients',
                                                'melisKey' => 'meliscommerce_clients_list_widgets_num_clients',
                                                'name' => 'tr_meliscommerce_clients_list_widgets_num_clients',
                                                'width' => '4'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClientList',
                                                'action' => 'render-client-list-widgets-num-clients',
                                            ],
                                        ],
                                        'meliscommerce_clients_list_widgets_month_clients' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_clients_list_widgets_month_clients',
                                                'melisKey' => 'meliscommerce_clients_list_widgets_month_clients',
                                                'name' => 'tr_meliscommerce_clients_list_widgets_month_clients',
                                                'width' => '4'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClientList',
                                                'action' => 'render-client-list-widgets-month-clients',
                                            ],
                                        ],
                                        'meliscommerce_clients_list_widgets_active_inactive' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_clients_list_widgets_avg_clients',
                                                'melisKey' => 'meliscommerce_clients_list_widgets_avg_clients',
                                                'name' => 'tr_meliscommerce_clients_list_widgets_avg_clients',
                                                'width' => '4'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClientList',
                                                'action' => 'render-client-list-widgets-active-inactive',
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_clients_list_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_clients_list_content',
                                        'melisKey' => 'meliscommerce_clients_list_content',
                                        'name' => 'tr_meliscommerce_clients_list_content'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClientList',
                                        'action' => 'render-client-list-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_clients_list_table' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_clients_list_table',
                                                'melisKey' => 'meliscommerce_clients_list_table',
                                                'name' => 'tr_meliscommerce_clients_list_table'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClientList',
                                                'action' => 'render-client-list-table',
                                            ],
                                        ]
                                    ]
                                ]
                            ]
                        ],
                    ]
                ],
                'meliscommerce_client' => [
                    'interface' => [
                        'meliscommerce_client_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_client_page',
                                'melisKey' => 'meliscommerce_client_page',
                                'name' => 'tr_meliscommerce_client_page'
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClient',
                                'action' => 'render-client-page',
                            ],
                            'interface' => [
                                'meliscommerce_client_page_header' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_client_page_header',
                                        'melisKey' => 'meliscommerce_client_page_header',
                                        'name' => 'tr_meliscommerce_client_page_header'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClient',
                                        'action' => 'render-client-page-header',
                                    ],
                                    'interface' => [
                                        'meliscommerce_client_save' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_client_save',
                                                'melisKey' => 'meliscommerce_client_save',
                                                'name' => 'tr_meliscommerce_client_save'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-save',
                                            ]
                                        ],
                                    ]
                                ],
                                'meliscommerce_client_page_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_client_page_content',
                                        'melisKey' => 'meliscommerce_client_page_content',
                                        'name' => 'tr_meliscommerce_client_page_content'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClient',
                                        'action' => 'render-client-page-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_client_page_tab_main' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_client_page_tab_main',
                                                'melisKey' => 'meliscommerce_client_page_tab_main',
                                                'name' => 'tr_meliscommerce_client_page_tab_main',
                                                'icon' => 'glyphicons tag'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-main',
                                            ],
                                            'interface' => [
                                                'meliscommerce_client_page_tab_main_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_client_page_tab_main_header',
                                                        'melisKey' => 'meliscommerce_client_page_tab_main_header',
                                                        'name' => 'tr_meliscommerce_client_page_tab_main_header',
                                                        'icon' => 'glyphicons tag'
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-main-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_client_status' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_client_status',
                                                                'melisKey' => 'meliscommerce_client_status',
                                                                'name' => 'tr_meliscommerce_client_status',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComClient',
                                                                'action' => 'render-client-status',
                                                            ],
                                                        ]
                                                    ]
                                                ],
                                                'meliscommerce_client_page_tab_main_content' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_client_page_tab_main_content',
                                                        'melisKey' => 'meliscommerce_client_page_tab_main_content',
                                                        'name' => 'tr_meliscommerce_client_page_tab_main_content',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-main-content',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_client_page_tab_main_content_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_client_page_tab_main_content_left',
                                                                'melisKey' => 'meliscommerce_client_page_tab_main_content_left',
                                                                'name' => 'tr_meliscommerce_client_page_tab_main_content_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComClient',
                                                                'action' => 'render-client-page-tab-main-content-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_client_main_form' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_client_main_form',
                                                                        'melisKey' => 'meliscommerce_client_main_form',
                                                                        'name' => 'tr_meliscommerce_client_main_form',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComClient',
                                                                        'action' => 'render-client-main-form',
                                                                    ],
                                                                ],
                                                            ]
                                                        ],
                                                        'meliscommerce_client_page_tab_main_content_right' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_client_page_tab_main_content_right',
                                                                'melisKey' => 'meliscommerce_client_page_tab_main_content_right',
                                                                'name' => 'tr_meliscommerce_client_page_tab_main_content_right',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComClient',
                                                                'action' => 'render-client-page-tab-main-content-right',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_client_page_main_contact' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_client_page_main_contact',
                                                                        'melisKey' => 'meliscommerce_client_page_main_contact',
                                                                        'name' => 'tr_meliscommerce_client_page_main_contact',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComClient',
                                                                        'action' => 'render-client-main-contact',
                                                                    ],
                                                                ]
                                                            ]
                                                        ],
                                                    ]
                                                ],
                                            ]
                                        ],
                                        'meliscommerce_client_page_tab_contact' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_client_page_tab_contact',
                                                'melisKey' => 'meliscommerce_client_page_tab_contact',
                                                'name' => 'tr_meliscommerce_client_page_tab_contact',
                                                'icon' => 'glyphicons parents'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-contact',
                                            ],
                                            'interface' => [
                                                'meliscommerce_client_page_tab_contact_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_client_page_tab_contact_header',
                                                        'melisKey' => 'meliscommerce_client_page_tab_contact_header',
                                                        'name' => 'tr_meliscommerce_client_page_tab_contact_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-contact-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_client_add_contact' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_client_add_contact',
                                                                'melisKey' => 'meliscommerce_client_add_contact',
                                                                'name' => 'tr_meliscommerce_client_add_contact',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComClient',
                                                                'action' => 'render-client-add-contact',
                                                            ],
                                                        ]
                                                    ]
                                                ],
                                                'meliscommerce_client_page_tab_contact_content' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_client_page_tab_contact_content',
                                                        'melisKey' => 'meliscommerce_client_page_tab_contact_content',
                                                        'name' => 'tr_meliscommerce_client_page_tab_contact_content',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-contact-content',
                                                    ],
                                                ],
                                            ]
                                        ],
                                        'meliscommerce_client_page_tab_company' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_client_page_tab_company',
                                                'melisKey' => 'meliscommerce_client_page_tab_company',
                                                'name' => 'tr_meliscommerce_client_page_tab_company',
                                                'icon' => 'glyphicons building'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-company',
                                            ],
                                        ],
                                        'meliscommerce_client_page_tab_address' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_client_page_tab_address',
                                                'melisKey' => 'meliscommerce_client_page_tab_address',
                                                'name' => 'tr_meliscommerce_client_page_tab_address',
                                                'icon' => 'glyphicons google_maps'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-address',
                                            ],
                                            'interface' => [
                                                'meliscommerce_client_page_tab_address_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_client_page_tab_address_header',
                                                        'melisKey' => 'meliscommerce_client_page_tab_address_header',
                                                        'name' => 'tr_meliscommerce_client_page_tab_address_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-address-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_client_add_address' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_client_add_address',
                                                                'melisKey' => 'meliscommerce_client_add_address',
                                                                'name' => 'tr_meliscommerce_client_add_address',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComClient',
                                                                'action' => 'render-client-add-address',
                                                            ],
                                                        ],
                                                    ]
                                                ],
                                                'meliscommerce_client_page_tab_address_content' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_client_page_tab_address_content',
                                                        'melisKey' => 'meliscommerce_client_page_tab_address_content',
                                                        'name' => 'tr_meliscommerce_client_page_tab_address_content',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClient',
                                                        'action' => 'render-client-page-tab-address-content',
                                                    ],
                                                ]
                                            ]
                                        ],
                                        'meliscommerce_client_page_tab_orders' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_client_page_tab_orders',
                                                'melisKey' => 'meliscommerce_client_page_tab_orders',
                                                'name' => 'tr_meliscommerce_client_page_tab_orders',
                                                'icon' => 'glyphicons shopping_cart'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-orders',
                                            ],
                                        ],
                                        'meliscommerce_client_page_tab_files' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_client_page_tab_files',
                                                'melisKey' => 'meliscommerce_client_page_tab_files',
                                                'name' => 'tr_meliscommerce_clients_Contact_tab_files',
                                                'icon' => 'glyphicons paperclip'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClient',
                                                'action' => 'render-client-page-tab-files',
                                            ],
                                            'interface' => [
                                                'meliscommerce_client_page_tab_files_attachments' => [
                                                    'conf' => [
                                                        'type' => 'meliscommerce/interface/meliscommerce_documents_file_attachments_conf',
                                                        'docRelationType' => 'client'
                                                    ]
                                                ]
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'meliscommerce_client_list_modal' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_client_list_modal',
                        'name' => 'tr_meliscommerce_client_list_modal',
                        'melisKey' => 'meliscommerce_client_list_modal',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComClientList',
                        'action' => 'render-client-list-modal',
                    ],
                    'interface' => [
                        'meliscommerce_client_list_content_export_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_client_list_content_export_form',
                                'name' => 'tr_meliscommerce_client_list_content_export_form',
                                'melisKey' => 'meliscommerce_client_list_content_export_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientList',
                                'action' => 'render-client-list-content-export-form',
                            ],
                        ],
                    ],
                ],
                'meliscommerce_client_modal' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_client_modal',
                        'melisKey' => 'meliscommerce_client_modal',
                        'name' => 'tr_meliscommerce_client_modal',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComClient',
                        'action' => 'render-client-modal',
                    ],
                    'interface' => [
                        'meliscommerce_client_modal_contact_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_client_modal_contact_form',
                                'melisKey' => 'meliscommerce_client_modal_contact_form',
                                'name' => 'tr_meliscommerce_client_modal_contact_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClient',
                                'action' => 'render-client-modal-contact-form',
                            ],
                        ],
                        'meliscommerce_client_modal_contact_address_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_client_modal_contact_address_form',
                                'melisKey' => 'meliscommerce_client_modal_contact_address_form',
                                'name' => 'tr_meliscommerce_client_modal_contact_address_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComContact',
                                'action' => 'render-contact-modal-contact-address-form',
                                'jscallback' => 'initClientContactAddressForm();'
                            ],
                        ],
                        'meliscommerce_client_modal_address_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_client_modal_address_form',
                                'melisKey' => 'meliscommerce_client_modal_address_form',
                                'name' => 'tr_meliscommerce_client_modal_address_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClient',
                                'action' => 'render-client-modal-address-form',
                            ],
                        ]
                    ]
                ]
            ]
        ]
    ]
];