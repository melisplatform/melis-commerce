<?php

/**
 * Melis Technology (http://www.melistechnology.com]
 *
 * @copyright Copyright (c] 2016 Melis Technology (http://www.melistechnology.com]
 *
 */

return [
    'plugins' => [
        'meliscommerce' => [
            'conf' => [
                'id' => '',
                'name' => '',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/settings.js',
                ],
                'css' => []
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_settings' => [
                    'interface' => [
                        'meliscommerce_settings_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_settings_page',
                                'melisKey' => 'meliscommerce_settings_page',
                                'name' => 'tr_meliscommerce_settings',
                                'icon' => 'fa fa-wrench',
                            ],
                        ],
                        'meliscommerce_settings_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_settings_page',
                                'melisKey' => 'meliscommerce_settings_page',
                                'name' => 'tr_meliscommerce_settings',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComSettings',
                                'action' => 'render-settings-page',
                                'jscallback' => '',
                                'jsdatas' => []
                            ],
                            'interface' => [
                                'meliscommerce_settings_header_container' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_settings_header_container',
                                        'melisKey' => 'meliscommerce_settings_header_container',
                                        'name' => 'tr_meliscommerce_settings_header_container',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComSettings',
                                        'action' => 'render-settings-header-container',
                                    ],
                                    'interface' => [
                                        'meliscommerce_settings_header_left_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_settings_header_left_container',
                                                'melisKey' => 'meliscommerce_settings_header_left_container',
                                                'name' => 'tr_meliscommerce_settings_header_left_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComSettings',
                                                'action' => 'render-settings-header-left-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_settings_header_title' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_settings_header_title',
                                                        'melisKey' => 'meliscommerce_settings_header_title',
                                                        'name' => 'tr_meliscommerce_settings',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComSettings',
                                                        'action' => 'render-settings-header-title',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_settings_header_right_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_settings_header_right_container',
                                                'melisKey' => 'meliscommerce_settings_header_right_container',
                                                'name' => 'tr_meliscommerce_settings_header_right_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComSettings',
                                                'action' => 'render-settings-header-right-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_settings_header_right_container_save' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_settings_header_right_container_save',
                                                        'melisKey' => 'meliscommerce_settings_header_right_container_save',
                                                        'name' => 'tr_meliscommerce_settings_header_right_container_save',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComSettings',
                                                        'action' => 'render-settings-header-save',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'meliscommerce_settings_page_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_settings_page_content',
                                        'melisKey' => 'meliscommerce_settings_page_content',
                                        'name' => 'tr_meliscommerce_settings_page_content'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComSettings',
                                        'action' => 'render-settings-page-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_settings_page_tabs_main' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_settings_page_tabs_main',
                                                'melisKey' => 'meliscommerce_settings_page_tabs_main',
                                                'name' => 'tr_meliscommerce_settings_page_tabs_main',
                                                'icon' => 'glyphicons settings',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComSettings',
                                                'action' => 'render-settings-page-tabs-main',
                                            ],
                                            'interface' => [
                                                'meliscommerce_settings_tabs_content_main_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_settings_tabs_content_main_header',
                                                        'melisKey' => 'meliscommerce_settings_tabs_content_main_header',
                                                        'name' => 'tr_meliscommerce_settings_tabs_content_main_header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComSettings',
                                                        'action' => 'render-settings-tabs-content-header',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_settings_tabs_content_main_header_left' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_settings_tabs_content_main_header_left',
                                                                'melisKey' => 'meliscommerce_settings_tabs_content_main_header_left',
                                                                'name' => 'tr_meliscommerce_settings_tabs_content_main_header_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComSettings',
                                                                'action' => 'render-settings-tabs-content-header-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_settings_tabs_content_main_header_title' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_settings_tabs_content_main_header_title',
                                                                        'melisKey' => 'meliscommerce_settings_tabs_content_main_header_title',
                                                                        'name' => 'tr_meliscommerce_settings_page_tabs_main',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComSettings',
                                                                        'action' => 'render-settings-tabs-content-header-title',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_settings_tabs_content_main_header_right' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_settings_tabs_content_main_header_right',
                                                                'melisKey' => 'meliscommerce_settings_tabs_content_main_header_right',
                                                                'name' => 'tr-meliscommerce_settings_tabs_content_main_header_right',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComSettings',
                                                                'action' => 'render-settings-tabs-content-header-right',
                                                            ],
                                                            'interface' => [],
                                                        ],
                                                    ],
                                                ],
                                                'meliscommerce_settings_tabs_content_main_details' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_settings_tabs_content_main_details',
                                                        'melisKey' => 'meliscommerce_settings_tabs_content_main_details',
                                                        'name' => 'tr_meliscommerce_settings_tabs_content_main_details',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComSettings',
                                                        'action' => 'render-settings-tabs-content-details',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_settings_tabs_content_main_details_left' => [
                                                            'conf' => [
                                                              'id' => 'id_meliscommerce_settings_tabs_content_main_details_left',
                                                              'melisKey' => 'meliscommerce_settings_tabs_content_main_details_left',
                                                              'name' => 'tr_meliscommerce_settings_tabs_content_main_details_left',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComSettings',
                                                                'action' => 'render-settings-tabs-content-details-left',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_settings_tabs_content_details_general' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_settings_tabs_content_details_general',
                                                                        'melisKey' => 'meliscommerce_settings_tabs_content_details_general',
                                                                        'name' => 'tr-meliscommerce_settings_tabs_content_details_general',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComSettings',
                                                                        'action' => 'render-settings-tabs-content-details-general',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_settings_page_tabs_accounts' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_settings_page_tabs_accounts',
                                                'melisKey' => 'meliscommerce_settings_page_tabs_accounts',
                                                'name' => 'tr_meliscommerce_settings_page_tabs_accounts',
                                                'icon' => 'glyphicons parents',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComSettings',
                                                'action' => 'render-settings-page-tabs-accounts',
                                            ],
                                            'interface' => [
                                                'meliscommerce_settings_page_tabs_accounts_header' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_settings_page_tabs_accounts_header',
                                                        'melisKey' => 'meliscommerce_settings_page_tabs_accounts_header',
                                                        'name' => 'tr_meliscommerce_settings_page_tabs_accounts_header',
                                                        'icon' => 'glyphicons parents',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComSettings',
                                                        'action' => 'render-settings-page-tabs-accounts-header',
                                                    ],
                                                ],
                                                'meliscommerce_settings_page_tabs_accounts_content' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_settings_page_tabs_accounts_content',
                                                        'melisKey' => 'meliscommerce_settings_page_tabs_accounts_content',
                                                        'name' => 'tr_meliscommerce_settings_page_tabs_accounts_content',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComSettings',
                                                        'action' => 'render-settings-page-tabs-accounts-content',
                                                    ],
                                                ]
                                            ]
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'meliscommerce_settings_tabs_content_details_general' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_settings_tabs_content_main_details_left',
                                'melisKey' => 'meliscommerce_settings_tabs_content_main_details_left',
                                'name' => 'tr_meliscommerce_settings_tabs_content_main_details_left',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComSettings',
                                'action' => 'render-settings-tabs-content-details-general',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];