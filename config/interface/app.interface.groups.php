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
                'name' => 'tr_meliscommerce_client_groups',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [

                ],
                'css' => [],
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_clients_group_tool' => [
                    'interface' => [
                        'meliscommerce_clients_group_tool_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_clients_group_tool_leftmenu',
                                'melisKey' => 'meliscommerce_clients_group_tool_leftmenu',
                                'name' => 'tr_meliscommerce_clients_group',
                                'icon' => 'fa fa-user-times',
                            ],
                        ],
//                        'meliscommerce_clients_group_list_container' => [
//                            'conf' => [
//                                'id' => 'id_meliscommerce_clients_group_list_container',
//                                'name' => 'tr_meliscommerce_clients_group',
//                                'melisKey' => 'meliscommerce_clients_group_list_container',
//                                'icon' => 'fa-clients_group',
//                                'rights_checkbox_disable' => true,
//                            ],
//                            'forward' => [
//                                'module' => 'MelisCommerce',
//                                'controller' => 'MelisComClientsGroup',
//                                'action' => 'render-clients-group-list-page',
//                                'jscallback' => '',
//                                'jsdatas' => []
//                            ],
//                            'interface' => [
//                                'meliscommerce_clients_group_list_page_header' => [
//                                    'conf' => [
//                                        'id' => 'id_meliscommerce_clients_group_list_page_header',
//                                        'name' => 'tr_meliscommerce_clients_group_header',
//                                        'melisKey' => 'meliscommerce_clients_group_list_page_header',
//                                    ],
//                                    'forward' => [
//                                        'module' => 'MelisCommerce',
//                                        'controller' => 'MelisComLanguage',
//                                        'action' => 'render-clients_group-list-page-header',
//                                        'jscallback' => '',
//                                        'jsdatas' => []
//                                    ],
//                                    'interface' => [
//                                        'meliscommerce_clients_group_list_page_header_add' => [
//                                            'conf' => [
//                                                'id' => 'id_meliscommerce_clients_group_list_page_header_add',
//                                                'name' => 'tr_meliscommerce_clients_group_header_add',
//                                                'melisKey' => 'meliscommerce_clients_group_list_page_header_add',
//                                            ],
//                                            'forward' => [
//                                                'module' => 'MelisCommerce',
//                                                'controller' => 'MelisComLanguage',
//                                                'action' => 'render-clients_group-list-page-header-add',
//                                                'jscallback' => '',
//                                                'jsdatas' => []
//                                            ],
//                                        ],
//                                    ],
//                                ],
//
//                                'meliscommerce_clients_group_list_page_content' => [
//                                    'conf' => [
//                                        'id' => 'id_meliscommerce_clients_group_list_page_content',
//                                        'name' => 'tr_meliscommerce_clients_group_content',
//                                        'melisKey' => 'render-clients_group-list-page-content',
//                                    ],
//                                    'forward' => [
//                                        'module' => 'MelisCommerce',
//                                        'controller' => 'MelisComLanguage',
//                                        'action' => 'render-clients_group-list-page-content',
//                                        'jscallback' => '',
//                                        'jsdatas' => []
//                                    ],
//                                ],
//
//                                'meliscommerce_clients_group_list_page_content_modal_container' => [
//                                    'conf' => [
//                                        'id' => 'id_meliscommerce_clients_group_list_page_content_modal_container',
//                                        'melisKey' => 'meliscommerce_clients_group_list_page_content_modal_container',
//                                        'name' => 'tr_meliscommerce_clients_group_modal'
//                                    ],
//                                    'forward' => [
//                                        'module' => 'MelisCommerce',
//                                        'controller' => 'MelisComLanguage',
//                                        'action' => 'render-clients_group-list-page-modal-container',
//
//                                    ],
//                                    'interface' => [
//                                        'meliscommerce_clients_group_list_page_content_modal_form' => [
//                                            'conf' => [
//                                                'id' => 'id_meliscommerce_clients_group_list_page_content_modal_form',
//                                                'melisKey' => 'meliscommerce_clients_group_list_page_content_modal_form',
//                                                'name' => 'tr_meliscommerce_clients_group_modal'
//                                            ],
//                                            'forward' => [
//                                                'module' => 'MelisCommerce',
//                                                'controller' => 'MelisComLanguage',
//                                                'action' => 'render-clients_group-list-page-modal-form',
//                                                'jscallback' => 'initProductSwitch();'
//                                            ],
//                                        ]
//                                    ]
//                                ],
//                            ],
//                        ],
                    ],
                ],
            ],
        ],
    ],
];