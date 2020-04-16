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
                'name' => 'tr_meliscommerce_avar_title',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/ecomAssocVar.js',
                ]
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_avar_tab' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_avar_tab',
                        'melisKey' => 'meliscommerce_avar_tab',
                        'name' => 'tr_meliscommerce_avar_title',
                        'icon' => 'glyphicons list',
                        'href' => 'id_meliscommerce_avar_tab_content',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComAssociateVariant',
                        'action' => 'render-tab',
                    ],
                ],
                'meliscommerce_avar_tab_content' => [
                    'conf' => [
                        'id' => 'id_meliscommerce_avar_tab_content',
                        'melisKey' => 'meliscommerce_avar_tab_content',
                        'name' => 'tr_meliscommerce_avar_content_title',
                    ],
                    'forward' => [
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComAssociateVariant',
                        'action' => 'render-tab-content',
                    ],
                    'interface' => [
                        'meliscommerce_avar_tab_header_container' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_avar_tab_header_container',
                                'melisKey' => 'meliscommerce_avar_tab_header_container',
                                'name' => 'tr_meliscommerce_avar_content_header',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-header',
                            ],
                            'interface' => [

                            ]
                        ],

                        'meliscommerce_avar_tab_content_container' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_avar_tab_content_container',
                                'melisKey' => 'meliscommerce_avar_tab_content_container',
                                'name' => 'tr_meliscommerce_avar_content_title',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-container',
                            ],
                            'interface' => [
                                'meliscommerce_avar_tab_assoc_vars_list' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_avar_tab_assoc_vars_list',
                                        'melisKey' => 'meliscommerce_avar_tab_assoc_vars_list',
                                        'name' => 'tr_meliscommerce_avar_title',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAssociateVariant',
                                        'action' => 'render-tab-content-assoc-var-list',
                                    ],
                                ],
                                'meliscommerce_avar_tab_var_lists' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_avar_tab_var_lists',
                                        'melisKey' => 'meliscommerce_avar_tab_var_lists',
                                        'name' => 'tr_meliscommerce_avar_title',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAssociateVariant',
                                        'action' => 'render-tab-content-var-list',
                                    ],
                                    'interface' => [
                                        'meliscommerce_avar_product_variants' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_avar_product_variants',
                                                'name' => 'tr_meliscommerce_avar_product_variants',
                                                'melisKey' => 'meliscommerce_avar_product_variants',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAssociateVariant',
                                                'action' => 'render-variant-assoc-product-variants',
                                            ],
                                        ]
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