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
            'ressources' => [
                'css' => [
                    '/MelisCommerce/css/contacts.css',
                ],
                'js' => [
                    '/MelisCommerce/js/tools/contact.tool.js',
                ],
            ],
            'datas' => [
                'import_sample_template' => [
                    [
                        'tr_contact_export_col_cper_lang_id' => 'English',
                        'tr_contact_export_col_cper_type' => 'person',
                        'tr_contact_export_col_cper_status' => '1',
                        'tr_contact_export_col_cper_email' => 'johndoe@test.com',
                        'tr_contact_export_col_cper_civility' => 'Mr',
                        'tr_contact_export_col_cper_name' => 'Doe',
                        'tr_contact_export_col_cper_middle_name' => 'Smith',
                        'tr_contact_export_col_cper_firstname' => 'John',
                        'tr_contact_export_col_cper_job_title' => 'Programmer',
                        'tr_contact_export_col_cper_job_service' => 'Website creation',
                        'tr_contact_export_col_cper_tel_mobile' => '091234567',
                        'tr_contact_export_col_cper_tel_landline' => '000-111-222',
                        'tr_contact_export_col_address_name' => 'John Doe Billing Address',
                        'tr_contact_export_col_address_type' => 'Billing',
                        'tr_contact_export_col_address_civility' => 'Mr',
                        'tr_contact_export_col_address_firstname' => 'Juan',
                        'tr_contact_export_col_address_middlename' => 'Smith',
                        'tr_contact_export_col_address_lastname' => 'Dela cruz',
                        'tr_contact_export_col_street_number' => '452',
                        'tr_contact_export_col_street_name' => 'Urgello',
                        'tr_contact_export_col_building' => 'Building 44',
                        'tr_contact_export_col_floor' => '3rd',
                        'tr_contact_export_col_city' => 'Cebu',
                        'tr_contact_export_col_state' => '',
                        'tr_contact_export_col_country' => 'Philippines',
                        'tr_contact_export_col_postal_code' => '6000',
                        'tr_contact_export_col_add_company_name' => 'My company',
                        'tr_contact_export_col_add_mobile' => '091234567',
                        'tr_contact_export_col_add_landline' => '000-111-222',
                        'tr_contact_export_col_add_additional' => 'Blue house with yellow gate',
                    ],
                    [
                        'tr_contact_export_col_cper_lang_id' => 'Français',
                        'tr_contact_export_col_cper_type' => 'person',
                        'tr_contact_export_col_cper_status' => '1',
                        'tr_contact_export_col_cper_email' => 'janedoe@test.com',
                        'tr_contact_export_col_cper_civility' => 'Mlle',
                        'tr_contact_export_col_cper_name' => 'Doe',
                        'tr_contact_export_col_cper_middle_name' => 'White',
                        'tr_contact_export_col_cper_firstname' => 'Jane',
                        'tr_contact_export_col_cper_job_title' => 'Programmer',
                        'tr_contact_export_col_cper_job_service' => 'Website creation',
                        'tr_contact_export_col_cper_tel_mobile' => '091234567',
                        'tr_contact_export_col_cper_tel_landline' => '000-111-222',
                        'tr_contact_export_col_address_name' => 'Jane Doe Delivery Address',
                        'tr_contact_export_col_address_type' => 'Delivery',
                        'tr_contact_export_col_address_civility' => 'Mme',
                        'tr_contact_export_col_address_firstname' => 'John',
                        'tr_contact_export_col_address_middlename' => 'Black',
                        'tr_contact_export_col_address_lastname' => 'Perfecto',
                        'tr_contact_export_col_street_number' => '344',
                        'tr_contact_export_col_street_name' => 'Colon',
                        'tr_contact_export_col_building' => 'Building 34',
                        'tr_contact_export_col_floor' => '5th',
                        'tr_contact_export_col_city' => 'Cebu',
                        'tr_contact_export_col_state' => '',
                        'tr_contact_export_col_country' => 'Philippines',
                        'tr_contact_export_col_postal_code' => '6000',
                        'tr_contact_export_col_add_company_name' => 'The company',
                        'tr_contact_export_col_add_mobile' => '091234567',
                        'tr_contact_export_col_add_landline' => '000-111-222',
                        'tr_contact_export_col_add_additional' => 'Red house with green gate',
                    ],
                    [
                        'tr_contact_export_col_cper_lang_id' => 'English',
                        'tr_contact_export_col_cper_type' => 'company',
                        'tr_contact_export_col_cper_status' => '1',
                        'tr_contact_export_col_cper_email' => 'company@test.com',
                        'tr_contact_export_col_cper_civility' => '',
                        'tr_contact_export_col_cper_name' => '',
                        'tr_contact_export_col_cper_middle_name' => '',
                        'tr_contact_export_col_cper_firstname' => 'Company name',
                        'tr_contact_export_col_cper_job_title' => '',
                        'tr_contact_export_col_cper_job_service' => '',
                        'tr_contact_export_col_cper_tel_mobile' => '091234567',
                        'tr_contact_export_col_cper_tel_landline' => '000-111-222',
                        'tr_contact_export_col_address_name' => 'Company Name Billing Address',
                        'tr_contact_export_col_address_type' => 'Billing',
                        'tr_contact_export_col_address_civility' => 'Mr',
                        'tr_contact_export_col_address_firstname' => 'Pedro',
                        'tr_contact_export_col_address_middlename' => 'Black',
                        'tr_contact_export_col_address_lastname' => 'White',
                        'tr_contact_export_col_street_number' => '100',
                        'tr_contact_export_col_street_name' => 'Basak',
                        'tr_contact_export_col_building' => 'Building 23',
                        'tr_contact_export_col_floor' => '7th',
                        'tr_contact_export_col_city' => 'Cebu',
                        'tr_contact_export_col_state' => '',
                        'tr_contact_export_col_country' => 'Philippines',
                        'tr_contact_export_col_postal_code' => '6000',
                        'tr_contact_export_col_add_company_name' => 'Address Company name',
                        'tr_contact_export_col_add_mobile' => '091234567',
                        'tr_contact_export_col_add_landline' => '000-111-222',
                        'tr_contact_export_col_add_additional' => 'Green house with red gate',
                    ]
                ]
            ],
            'interface' => [
                'meliscommerce_contact_list' => [
                    'interface' => [
                        'meliscommerce_contact_list_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_contact_list_page',
                                'melisKey' => 'meliscommerce_contact_list_page',
                                'name' => 'tr_meliscommerce_contact',
                                'icon' => 'fa fa-user',
                            ],
                            'interface' => [
                                'meliscommerce_contacts_list_add_contact_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contacts_list_add_contact_button',
                                        'melisKey' => 'meliscommerce_contacts_list_add_contact_button',
                                        'name' => 'tr_meliscommerce_contacts_list_add_contact_button',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_contacts_list_export_contact_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contacts_list_export_contact_button',
                                        'melisKey' => 'meliscommerce_contacts_list_export_contact_button',
                                        'name' => 'tr_meliscommerce_contacts_list_export_contact_button',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_contacts_list_import_contact_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contacts_list_import_contact_button',
                                        'melisKey' => 'meliscommerce_contacts_list_import_contact_button',
                                        'name' => 'tr_meliscommerce_contacts_list_import_contact_button',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_contacts_save_contact_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contacts_save_contact_button',
                                        'melisKey' => 'meliscommerce_contacts_save_contact_button',
                                        'name' => 'tr_meliscommerce_contacts_save_contact_button',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_contact_page_content_tab_association_header_add_account' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contact_page_content_tab_association_header_add_account',
                                        'melisKey' => 'meliscommerce_contact_page_content_tab_association_header_add_account',
                                        'name' => 'tr_meliscommerce_contact_page_content_tab_association_header_add_account',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_contact_page_content_tab_address_header_button_ad' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contact_page_content_tab_address_header_button_ad',
                                        'melisKey' => 'meliscommerce_contact_page_content_tab_address_header_button_ad',
                                        'name' => 'tr_meliscommerce_contact_page_content_tab_address_header_button_ad',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_contact_page_content_tab_address_header_button_delete_address' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contact_page_content_tab_address_header_button_delete_address',
                                        'melisKey' => 'meliscommerce_contact_page_content_tab_address_header_button_delete_address',
                                        'name' => 'tr_meliscommerce_contact_page_content_tab_address_header_button_delete_address',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_contacts_set_default_account_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contacts_set_default_account_button',
                                        'melisKey' => 'meliscommerce_contacts_set_default_account_button',
                                        'name' => 'tr_meliscommerce_contact_set_default',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                                'meliscommerce_contacts_unlink_account_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contacts_unlink_account_button',
                                        'melisKey' => 'meliscommerce_contacts_unlink_account_button',
                                        'name' => 'tr_meliscommerce_contact_unlink_account',
                                        'left_menu_display' => false,
                                        'user_rights_interface_group' => \MelisCore\Service\MelisCoreRightsService::MELISCORE_PREFIX_INTERFACE_GROUP_NAME_EXCLUSION,
                                    ],
                                ],
                            ]
                        ],
                        'meliscommerce_contact_list_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_contact_list_page',
                                'melisKey' => 'meliscommerce_contact_list_page',
                                'name' => 'tr_meliscommerce_contact_list_page'
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComContact',
                                'action' => 'render-account-list-page',
                            ],
                            'interface' => [
                                'meliscommerce_contact_list_page_header' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contact_list_page_header',
                                        'melisKey' => 'meliscommerce_contact_list_page_header',
                                        'name' => 'tr_meliscommerce_contact_list_page_header'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComContact',
                                        'action' => 'render-account-list-page-header',
                                    ],
                                    'interface' => [
                                        'meliscommerce_contact_list_page_header_button_add_contact' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_contact_list_page_header_button_add_contact',
                                                'melisKey' => 'meliscommerce_contact_list_page_header_button_add_contact',
                                                'name' => 'tr_meliscommerce_contact_list_page_header_button_add_contact'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComContact',
                                                'action' => 'render-account-list-page-header-button-add-contact',
                                            ],
                                        ]
                                    ]
                                ],
                                'meliscommerce_contact_list_page_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contact_list_page_content',
                                        'melisKey' => 'meliscommerce_contact_list_page_content',
                                        'name' => 'tr_meliscommerce_contact_list_page_content'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComContact',
                                        'action' => 'render-account-list-page-content',
                                    ],
                                ]
                            ]
                        ]
                    ]
                ],
                'meliscommerce_contact' => [
                    'interface' => [
                        'meliscommerce_contact_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_contact_page',
                                'melisKey' => 'meliscommerce_contact_page',
                                'name' => 'tr_meliscommerce_contact_page'
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComContact',
                                'action' => 'render-contact-page',
                            ],
                            'interface' => [
                                'meliscommerce_contact_page_header' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contact_page_header',
                                        'melisKey' => 'meliscommerce_contact_page_header',
                                        'name' => 'tr_meliscommerce_contact_page_header'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComContact',
                                        'action' => 'render-contact-page-header',
                                    ],
                                    'interface' => [
                                        'meliscommerce_contact_page_header_button_save' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_contact_page_header_button_save',
                                                'melisKey' => 'meliscommerce_contact_page_header_button_save',
                                                'name' => 'tr_meliscommerce_contact_page_header_button_save'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComContact',
                                                'action' => 'render-contact-page-header-button-save',
                                            ]
                                        ],
                                    ]
                                ],
                                'meliscommerce_contact_page_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contact_page_content',
                                        'melisKey' => 'meliscommerce_contact_page_content',
                                        'name' => 'tr_meliscommerce_contact_page_content'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComContact',
                                        'action' => 'render-contact-page-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_contact_page_content_tab_information' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_contact_page_content_tab_information',
                                                'melisKey' => 'meliscommerce_contact_page_content_tab_information',
                                                'name' => 'tr_meliscommerce_contact_page_content_tab_information',
                                                'icon' => 'glyphicons tag'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComContact',
                                                'action' => 'render-contact-page-content-tab-information',
                                            ],
                                            'interface' => [
                                                'meliscommerce_contact_page_content_tab_information_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_contact_page_content_tab_information_header',
                                                        'melisKey' => 'meliscommerce_contact_page_content_tab_information_header',
                                                        'name' => 'tr_meliscommerce_contact_page_content_tab_information_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComContact',
                                                        'action' => 'render-contact-page-content-tab-information-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_contact_page_content_tab_information_header_status' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_contact_page_content_tab_information_header_status',
                                                                'melisKey' => 'meliscommerce_contact_page_content_tab_information_header_status',
                                                                'name' => 'tr_meliscommerce_contact_page_content_tab_information_header_status',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComContact',
                                                                'action' => 'render-contact-page-content-tab-information-header-status',
                                                            ],
                                                        ]
                                                    ]
                                                ],
                                                'meliscommerce_contact_page_content_tab_information_content' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_contact_page_content_tab_information_content',
                                                        'melisKey' => 'meliscommerce_contact_page_content_tab_information_content',
                                                        'name' => 'tr_meliscommerce_contact_page_content_tab_information_content',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComContact',
                                                        'action' => 'render-contact-page-content-tab-information-content',
                                                    ],
                                                ]
                                            ]
                                        ],
                                        'meliscommerce_contact_page_content_tab_address' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_contact_page_content_tab_address',
                                                'melisKey' => 'meliscommerce_contact_page_content_tab_address',
                                                'name' => 'tr_meliscommerce_contact_page_content_tab_address',
                                                'icon' => 'glyphicons google_maps'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComContact',
                                                'action' => 'render-contact-page-content-tab-address',
                                            ],
                                            'interface' => [
                                                'meliscommerce_contact_page_content_tab_address_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_contact_page_content_tab_address_header',
                                                        'melisKey' => 'meliscommerce_contact_page_content_tab_address_header',
                                                        'name' => 'tr_meliscommerce_contact_page_content_tab_address_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComContact',
                                                        'action' => 'render-contact-page-content-tab-address-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_contact_page_content_tab_address_header_button_ad' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_contact_page_content_tab_address_header_button_add',
                                                                'melisKey' => 'meliscommerce_contact_page_content_tab_address_header_button_ad',
                                                                'name' => 'tr_meliscommerce_contact_page_content_tab_address_header_button_ad',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComContact',
                                                                'action' => 'render-contact-page-content-tab-address-header-button-add',
                                                            ],
                                                        ]
                                                    ]
                                                ],
                                                'meliscommerce_contact_page_content_tab_address_content' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_contact_page_content_tab_address_content',
                                                        'melisKey' => 'meliscommerce_contact_page_content_tab_address_content',
                                                        'name' => 'tr_meliscommerce_contact_page_content_tab_address_content',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComContact',
                                                        'action' => 'render-contact-page-content-tab-address-content',
                                                    ],
                                                ]
                                            ]
                                        ],
                                        'meliscommerce_contact_page_content_tab_association' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_contact_page_content_tab_association',
                                                'melisKey' => 'meliscommerce_contact_page_content_tab_association',
                                                'name' => 'tr_meliscommerce_contact_page_content_tab_association',
                                                'icon' => 'glyphicons link'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComContact',
                                                'action' => 'render-contact-page-content-tab-association',
                                            ],
                                            'interface' => [
                                                'meliscommerce_contact_page_content_tab_association_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_contact_page_content_tab_association_header',
                                                        'melisKey' => 'meliscommerce_contact_page_content_tab_association_header',
                                                        'name' => 'tr_meliscommerce_contact_page_content_tab_association_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComContact',
                                                        'action' => 'render-contact-page-content-tab-association-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_contact_page_content_tab_association_header_add_account' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_contact_page_content_tab_association_header_add_account',
                                                                'melisKey' => 'meliscommerce_contact_page_content_tab_association_header_add_account',
                                                                'name' => 'tr_meliscommerce_contact_page_content_tab_association_header_add_account',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComContact',
                                                                'action' => 'render-contact-page-content-tab-association-header-add-account',
                                                            ],
                                                            'interface' => [

                                                            ]
                                                        ],
                                                    ]
                                                ],
                                                'meliscommerce_contact_page_content_tab_association_content' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_contact_page_content_tab_association_content',
                                                        'melisKey' => 'meliscommerce_contact_page_content_tab_association_content',
                                                        'name' => 'tr_meliscommerce_contact_page_content_tab_association_content',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComContact',
                                                        'action' => 'render-contact-page-content-tab-association-content',
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'meliscommerce_contact_list_modal' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_contact_list_modal',
                        'name' => 'tr_meliscommerce_contact_list_modal',
                        'melisKey' => 'meliscommerce_contact_list_modal',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComContact',
                        'action' => 'render-contact-list-modal',
                    ],
                    'interface' => [
                        'meliscommerce_contact_list_export_contacts_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_contact_list_export_contacts_form',
                                'name' => 'tr_meliscommerce_contact_list_export_contacts_form',
                                'melisKey' => 'meliscommerce_contact_list_export_contacts_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComContact',
                                'action' => 'render-contact-list-content-export-contacts-form',
                            ],
                        ],
                        'meliscommerce_contact_list_import_contacts_form' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_contact_list_import_contacts_form',
                                'name' => 'tr_meliscommerce_contact_list_import_contacts_form',
                                'melisKey' => 'meliscommerce_contact_list_import_contacts_form',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComContact',
                                'action' => 'render-contact-list-content-import-contacts-form',
                            ],
                        ],
                    ],
                ]
            ]
        ]
    ]
];