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
                    '/MelisCommerce/js/tools/clients-group.tool.js',
                ],
                'css' => [],
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_clients_group_tool' => [
                    'interface' => [
                        // 'meliscommerce_clients_group_tool_leftmenu' => [
                        //     'conf' => [
                        //         // 'id' => 'id_meliscommerce_clients_group_tool_container',
                        //         // 'name' => 'Client Groups',
                        //         // 'melisKey' => 'meliscommerce_clients_group_tool_leftmenu',
                        //         'rightsDisplay' => 'referencesonly',
                        //     ],
                        //     'interface' => [
                                
                        //     ]
                        // ],
                        'meliscommerce_clients_group_tool_container' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_clients_group_tool_container',
                                'name' => 'tr_meliscommerce_clients_group',
                                'melisKey' => 'meliscommerce_clients_group_tool_container',
                                'icon' => 'fa fa-user-times',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComClientsGroup',
                                'action' => 'render-clients-group-tool-container',
                                'jscallback' => '',
                                'jsdatas' => []
                            ],
                            'interface' => [
                                'meliscommerce_clients_group_tool_header_container' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_clients_group_tool_header_container',
                                        'name' => 'tr_meliscommerce_clients_group_tool_header_container',
                                        'melisKey' => 'meliscommerce_clients_group_tool_header_container',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClientsGroup',
                                        'action' => 'render-clients-group-tool-header-container',
                                        'jscallback' => '',
                                        'jsdatas' => []
                                    ],
                                    'interface' => [
                                        'meliscommerce_clients_group_tool_header_add_button' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_clients_group_tool_header_add_button',
                                                'name' => 'tr_meliscommerce_clients_group_add_group',
                                                'melisKey' => 'meliscommerce_clients_group_tool_header_add_button',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClientsGroup',
                                                'action' => 'render-clients-group-tool-header-add-button',
                                                'jscallback' => '',
                                                'jsdatas' => []
                                            ],
                                        ],
                                    ]
                                ],
                                'meliscommerce_clients_group_tool_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_clients_group_tool_content',
                                        'name' => 'tr_meliscommerce_clients_group_tool_content',
                                        'melisKey' => 'meliscommerce_clients_group_tool_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComClientsGroup',
                                        'action' => 'render-clients-group-tool-content',
                                        'jscallback' => '',
                                        'jsdatas' => []
                                    ],
                                    'interface' => [
                                        'meliscommerce_clients_group_tool_content_modal' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_clients_group_tool_content_modal',
                                                'melisKey' => 'meliscommerce_clients_group_tool_content_modal',
                                                'name' => 'tr_meliscommerce_clients_group_tool_content_modal'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComClientsGroup',
                                                'action' => 'render-clients-group-tool-content-modal',
                                            ],
                                            'interface' => [
                                                'meliscommerce_clients_group_tool_content_modal_form' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_clients_group_tool_content_modal_form',
                                                        'melisKey' => 'meliscommerce_clients_group_tool_content_modal_form',
                                                        'name' => 'tr_meliscommerce_clients_group_add_group'
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComClientsGroup',
                                                        'action' => 'render-clients-group-tool-content-modal-form',
                                                        'jscallback' => ''
                                                    ],
                                                ]
                                            ]
                                        ],
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];