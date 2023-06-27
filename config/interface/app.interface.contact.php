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
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_contact_page_content_tab_association_content_add_account' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_contact_page_content_tab_association_content_add_account',
                                                                'melisKey' => 'meliscommerce_contact_page_content_tab_association_content_add_account',
                                                                'name' => 'tr_meliscommerce_contact_page_content_tab_association_content_add_account',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComContact',
                                                                'action' => 'render-contact-page-content-tab-association-content-add-account',
                                                            ],
                                                        ],
                                                        'meliscommerce_contact_page_content_tab_association_content_list' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_contact_page_content_tab_association_content_list',
                                                                'melisKey' => 'meliscommerce_contact_page_content_tab_association_content_list',
                                                                'name' => 'tr_meliscommerce_contact_page_content_tab_association_content_list',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComContact',
                                                                'action' => 'render-contact-page-content-tab-association-content-list',
                                                            ],
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];