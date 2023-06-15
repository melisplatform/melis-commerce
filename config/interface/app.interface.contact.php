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
                ]
            ]
        ]
    ]
];