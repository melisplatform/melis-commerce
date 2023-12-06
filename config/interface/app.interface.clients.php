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
                ],
                'import_sample_template_accounts' => [
                    [
                        'tr_client_accounts_import_template_name' => 'Nom du compte 1',
                        'tr_client_accounts_import_template_country' => 'France',
                        'tr_client_accounts_import_template_tags' => 'tag1,tag2',
                        'tr_client_accounts_import_template_group_id' => '1',
                        'tr_client_accounts_import_template_address_name' => 'Adresse de facturation',
                        'tr_client_accounts_import_template_address_type' => 'Billing',
                        'tr_client_accounts_import_template_address_civility' => 'Mr',
                        'tr_client_accounts_import_template_address_firstname' => 'Jean',
                        'tr_client_accounts_import_template_address_middlename' => 'François',
                        'tr_client_accounts_import_template_address_lastname' => 'Doe',
                        'tr_client_accounts_import_template_address_street_number' => '12',
                        'tr_client_accounts_import_template_address_street_name' => 'Avenue Montaigne',
                        'tr_client_accounts_import_template_address_building' => 'Bat A',
                        'tr_client_accounts_import_template_address_floor' => '4eme',
                        'tr_client_accounts_import_template_address_city' => 'Paris',
                        'tr_client_accounts_import_template_address_state' => '',
                        'tr_client_accounts_import_template_address_country' => 'France',
                        'tr_client_accounts_import_template_address_zipcode' => '75000',
                        'tr_client_accounts_import_template_address_company_name' => '',
                        'tr_client_accounts_import_template_address_mobile' => '123456789',
                        'tr_client_accounts_import_template_address_landline' => '987654321',
                        'tr_client_accounts_import_template_address_additional' => 'Portail noir',
                        'tr_client_accounts_import_template_company_name' => '',
                        'tr_client_accounts_import_template_company_registration_num' => '',
                        'tr_client_accounts_import_template_company_vat_num' => '',
                        'tr_client_accounts_import_template_company_group' => '',
                        'tr_client_accounts_import_template_company_employee_number' => '',
                        'tr_client_accounts_import_template_company_street_number' => '',
                        'tr_client_accounts_import_template_company_street_name' => '',
                        'tr_client_accounts_import_template_company_building' => '',
                        'tr_client_accounts_import_template_company_zipcode' => '',
                        'tr_client_accounts_import_template_company_city' => '',
                        'tr_client_accounts_import_template_company_state' => '',
                        'tr_client_accounts_import_template_company_country' => '',
                        'tr_client_accounts_import_template_company_phone_number' => '',
                        'tr_client_accounts_import_template_company_website' => '',
                        'tr_client_accounts_import_template_company_contact_email' => 'johndoe@test.com',
                    ],
                    [
                        'tr_client_accounts_import_template_name' => 'Nom du compte 2',
                        'tr_client_accounts_import_template_country' => 'France',
                        'tr_client_accounts_import_template_tags' => '',
                        'tr_client_accounts_import_template_group_id' => '1',
                        'tr_client_accounts_import_template_address_name' => 'Adresse de livraison',
                        'tr_client_accounts_import_template_address_type' => 'Delivery',
                        'tr_client_accounts_import_template_address_civility' => 'Mme',
                        'tr_client_accounts_import_template_address_firstname' => 'Jane',
                        'tr_client_accounts_import_template_address_middlename' => 'Isabelle',
                        'tr_client_accounts_import_template_address_lastname' => 'Doe',
                        'tr_client_accounts_import_template_address_street_number' => '36',
                        'tr_client_accounts_import_template_address_street_name' => 'Rue de la paix',
                        'tr_client_accounts_import_template_address_building' => '',
                        'tr_client_accounts_import_template_address_floor' => '',
                        'tr_client_accounts_import_template_address_city' => 'Lyon',
                        'tr_client_accounts_import_template_address_state' => '',
                        'tr_client_accounts_import_template_address_country' => 'France',
                        'tr_client_accounts_import_template_address_zipcode' => '69000',
                        'tr_client_accounts_import_template_address_company_name' => 'Société XYZ',
                        'tr_client_accounts_import_template_address_mobile' => '111222333',
                        'tr_client_accounts_import_template_address_landline' => '',
                        'tr_client_accounts_import_template_address_additional' => 'Maison jaune',
                        'tr_client_accounts_import_template_company_name' => 'Société XYZ',
                        'tr_client_accounts_import_template_company_registration_num' => '55555555555',
                        'tr_client_accounts_import_template_company_vat_num' => '555555',
                        'tr_client_accounts_import_template_company_group' => 'Groupe XYZ',
                        'tr_client_accounts_import_template_company_employee_number' => '200',
                        'tr_client_accounts_import_template_company_street_number' => '12',
                        'tr_client_accounts_import_template_company_street_name' => 'rue de la fontaine',
                        'tr_client_accounts_import_template_company_building' => 'Bat A',
                        'tr_client_accounts_import_template_company_zipcode' => '69000',
                        'tr_client_accounts_import_template_company_city' => 'Lyon',
                        'tr_client_accounts_import_template_company_state' => '',
                        'tr_client_accounts_import_template_company_country' => 'France',
                        'tr_client_accounts_import_template_company_phone_number' => '444555666',
                        'tr_client_accounts_import_template_company_website' => 'www.societexyz.com',
                        'tr_client_accounts_import_template_company_contact_email' => 'janedoe@test.com',
                    ],
                    [
                        'tr_client_accounts_import_template_name' => 'Nom du compte 3',
                        'tr_client_accounts_import_template_country' => 'France',
                        'tr_client_accounts_import_template_tags' => '',
                        'tr_client_accounts_import_template_group_id' => '1',
                        'tr_client_accounts_import_template_address_name' => '',
                        'tr_client_accounts_import_template_address_type' => '',
                        'tr_client_accounts_import_template_address_civility' => '',
                        'tr_client_accounts_import_template_address_firstname' => '',
                        'tr_client_accounts_import_template_address_middlename' => '',
                        'tr_client_accounts_import_template_address_lastname' => '',
                        'tr_client_accounts_import_template_address_street_number' => '',
                        'tr_client_accounts_import_template_address_street_name' => '',
                        'tr_client_accounts_import_template_address_building' => '',
                        'tr_client_accounts_import_template_address_floor' => '',
                        'tr_client_accounts_import_template_address_city' => '',
                        'tr_client_accounts_import_template_address_state' => '',
                        'tr_client_accounts_import_template_address_country' => '',
                        'tr_client_accounts_import_template_address_zipcode' => '',
                        'tr_client_accounts_import_template_address_company_name' => '',
                        'tr_client_accounts_import_template_address_mobile' => '',
                        'tr_client_accounts_import_template_address_landline' => '',
                        'tr_client_accounts_import_template_address_additional' => '',
                        'tr_client_accounts_import_template_company_name' => '',
                        'tr_client_accounts_import_template_company_registration_num' => '',
                        'tr_client_accounts_import_template_company_vat_num' => '',
                        'tr_client_accounts_import_template_company_group' => '',
                        'tr_client_accounts_import_template_company_employee_number' => '',
                        'tr_client_accounts_import_template_company_street_number' => '',
                        'tr_client_accounts_import_template_company_street_name' => '',
                        'tr_client_accounts_import_template_company_building' => '',
                        'tr_client_accounts_import_template_company_zipcode' => '',
                        'tr_client_accounts_import_template_company_city' => '',
                        'tr_client_accounts_import_template_company_state' => '',
                        'tr_client_accounts_import_template_company_country' => '',
                        'tr_client_accounts_import_template_company_phone_number' => '',
                        'tr_client_accounts_import_template_company_website' => '',
                        'tr_client_accounts_import_template_company_contact_email' => 'Johndoe@test.com',
                    ]
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
                            'interface' => [
                                'meliscommerce_clients_list_add_client_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_clients_list_add_client_button',
                                        'melisKey' => 'meliscommerce_clients_list_add_client_button',
                                        'name' => 'tr_meliscommerce_clients_list_add_client_button',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_clients_list_export_client_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_clients_list_export_client_button',
                                        'melisKey' => 'meliscommerce_clients_list_export_client_button',
                                        'name' => 'tr_meliscommerce_clients_list_export_client_button',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_clients_list_import_client_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_clients_list_import_client_button',
                                        'melisKey' => 'meliscommerce_clients_list_import_client_button',
                                        'name' => 'tr_meliscommerce_clients_list_import_client_button',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_clients_list_delete_client_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_clients_list_delete_client_button',
                                        'melisKey' => 'meliscommerce_clients_list_delete_client_button',
                                        'name' => 'tr_meliscommerce_clients_list_delete_client_button',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_clients_list_save_client_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_clients_list_save_client_button',
                                        'melisKey' => 'meliscommerce_clients_list_save_client_button',
                                        'name' => 'tr_meliscommerce_clients_list_save_client_button',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_client_add_contact' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_client_add_contact',
                                        'melisKey' => 'meliscommerce_client_add_contact',
                                        'name' => 'tr_meliscommerce_client_add_contact',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
//                                'meliscommerce_client_edit_contact' => [
//                                    'conf' => [
//                                        'id' => 'id_meliscommerce_client_edit_contact',
//                                        'melisKey' => 'meliscommerce_client_edit_contact',
//                                        'name' => 'tr_meliscommerce_client_edit_contact',
//                                        'left_menu_display' => false
//                                    ],
//                                ],
                                'meliscommerce_client_set_default_contact' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_client_set_default_contact',
                                        'melisKey' => 'meliscommerce_client_set_default_contact',
                                        'name' => 'tr_meliscommerce_client_set_default',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_client_unlink_contact' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_client_unlink_contact',
                                        'melisKey' => 'meliscommerce_client_unlink_contact',
                                        'name' => 'tr_meliscommerce_client_unlink_contact',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_client_contact_list_add_contact_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_client_contact_list_add_contact_button',
                                        'melisKey' => 'meliscommerce_client_contact_list_add_contact_button',
                                        'name' => 'tr_meliscommerce_client_contact_list_add_contact_button',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ]
                            ]
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
                        'rightsDisplay' => 'none',
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
                        'meliscommerce_client_list_export_accounts_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_client_list_export_accounts_form',
                                'name' => 'tr_meliscommerce_client_list_export_accounts_form',
                                'melisKey' => 'meliscommerce_client_list_export_accounts_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientList',
                                'action' => 'render-client-list-content-export-accounts-form',
                            ],
                        ],
                        'meliscommerce_client_list_import_accounts_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_client_list_import_accounts_form',
                                'name' => 'tr_meliscommerce_client_list_import_accounts_form',
                                'melisKey' => 'meliscommerce_client_list_import_accounts_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientList',
                                'action' => 'render-client-list-content-import-accounts-form',
                            ],
                        ],
                    ],
                ],
                'meliscommerce_client_modal' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_client_modal',
                        'melisKey' => 'meliscommerce_client_modal',
                        'name' => 'tr_meliscommerce_client_modal',
                        'rightsDisplay' => 'none',
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