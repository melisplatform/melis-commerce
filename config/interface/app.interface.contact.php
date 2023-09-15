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
                                        'left_menu_display' => false
                                    ],
                                ],
                                'meliscommerce_contacts_list_export_contact_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contacts_list_export_contact_button',
                                        'melisKey' => 'meliscommerce_contacts_list_export_contact_button',
                                        'name' => 'tr_meliscommerce_contacts_list_export_contact_button',
                                        'left_menu_display' => false
                                    ],
                                ],
                                'meliscommerce_contacts_list_import_contact_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contacts_list_import_contact_button',
                                        'melisKey' => 'meliscommerce_contacts_list_import_contact_button',
                                        'name' => 'tr_meliscommerce_contacts_list_import_contact_button',
                                        'left_menu_display' => false
                                    ],
                                ],
                                'meliscommerce_contacts_save_contact_button' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contacts_save_contact_button',
                                        'melisKey' => 'meliscommerce_contacts_save_contact_button',
                                        'name' => 'tr_meliscommerce_contacts_save_contact_button',
                                        'left_menu_display' => false
                                    ],
                                ],
                                'meliscommerce_contact_page_content_tab_association_header_add_account' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contact_page_content_tab_association_header_add_account',
                                        'melisKey' => 'meliscommerce_contact_page_content_tab_association_header_add_account',
                                        'name' => 'tr_meliscommerce_contact_page_content_tab_association_header_add_account',
                                        'left_menu_display' => false
                                    ],
                                ],
                                'meliscommerce_contact_page_content_tab_address_header_button_ad' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contact_page_content_tab_address_header_button_ad',
                                        'melisKey' => 'meliscommerce_contact_page_content_tab_address_header_button_ad',
                                        'name' => 'tr_meliscommerce_contact_page_content_tab_address_header_button_ad',
                                        'left_menu_display' => false
                                    ],
                                ],
                                'meliscommerce_contact_page_content_tab_address_header_button_delete_address' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_contact_page_content_tab_address_header_button_delete_address',
                                        'melisKey' => 'meliscommerce_contact_page_content_tab_address_header_button_delete_address',
                                        'name' => 'tr_meliscommerce_contact_page_content_tab_address_header_button_delete_address',
                                        'left_menu_display' => false
                                    ],
                                ]
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