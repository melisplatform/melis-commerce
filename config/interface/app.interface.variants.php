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
                'name' => 'tr_meliscommerce_variants_Variants',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/variant.tool.js',
                    '/MelisCommerce/js/tools/seo.tool.js',
                ],
                'css' => [],
            ],
            'datas' => [],
            'interface' => [
                'meliscommerce_variants' => [
                    'interface' => [
                        /* 'meliscommerce_variants_leftmenu' => [                            'conf' => [                                'id' => 'id_meliscommerce_variants_page',
                                'melisKey' => 'meliscommerce_variants_page',
                                'name' => 'tr_meliscommerce_varaints',
                                'icon' => 'fa fa-user',
                            ],
                        ], */
                        'meliscommerce_variants_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_variants_page',
                                'melisKey' => 'meliscommerce_variants_page',
                                'name' => 'tr_meliscommerce_variants_page',
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComVariant',
                                'action' => 'render-variant-page',
                            ],
                            'interface' => [
                                'meliscommerce_variant_header' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_variant_header',
                                        'melisKey' => 'meliscommerce_variant_header',
                                        'name' => 'tr_meliscommerce_variant_header header',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComVariant',
                                        'action' => 'render-variant-header',
                                    ],
                                    'interface' => [
                                        'meliscommerce_variant_header_left' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_variant_header_left',
                                                'melisKey' => 'meliscommerce_variant_header_left',
                                                'name' => 'tr_meliscommerce_variant_header_left',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComVariant',
                                                'action' => 'render-variant-header-left',
                                            ],
                                            'interface' => [
                                                'meliscommerce_variant_header_heading' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_variant_header_heading',
                                                        'melisKey' => 'meliscommerce_variant_header_heading',
                                                        'name' => 'tr_meliscommerce_variant_header_heading header',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-header-heading',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_variant_header_right' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_variant_header_right',
                                                'melisKey' => 'meliscommerce_variant_header_right',
                                                'name' => 'tr_meliscommerce_variant_header_right',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComVariant',
                                                'action' => 'render-variant-header-right',
                                            ],
                                            'interface' => [
                                                'meliscommerce_variant_header_save' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_variant_header_save',
                                                        'melisKey' => 'meliscommerce_variant_header_save',
                                                        'name' => 'tr_meliscommerce_variant_header_save',
                                                        'icon' => 'fa fa-save',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-header-save',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                //end of header container
                                'meliscommerce_variant_content' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_variant_content',
                                        'melisKey' => 'meliscommerce_variant_content',
                                        'name' => 'tr_meliscommerce_variant_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComVariant',
                                        'action' => 'render-variant-content',
                                    ],
                                    'interface' => [
                                        'meliscommerce_variant_tab_head' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_variant_tab_head',
                                                'melisKey' => 'meliscommerce_variant_tab_head',
                                                'name' => 'tr_meliscommerce_variant_tab_head',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComVariant',
                                                'action' => 'render-variant-tab-head',
                                            ],
                                            'interface' => [
                                                'meliscommerce_variant_tab_main' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_variant_tab_main',
                                                        'melisKey' => 'meliscommerce_variant_tab_main',
                                                        'name' => 'tr_meliscommerce_variant_tab_main',
                                                        'icon' => 'glyphicons tag',
                                                        'active' => 'active',
                                                        'href' => 'id_meliscommerce_variant_tab_content_main',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab',
                                                    ],
                                                ],
                                                /* 'meliscommerce_variant_tab_text' => [                                                    'conf' => [                                                        'id' => 'id_meliscommerce_variant_tab_text',
                                                        'melisKey' => 'meliscommerce_variant_tab_text',
                                                        'name' => 'tr_meliscommerce_variant_tab_text',
                                                        'icon' => 'icon-paper-documents',
                                                        'href' => 'id_meliscommerce_variant_tab_content_text',
                                                    ],
                                                    'forward' => [                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab',
                                                    ],
                                                ], */
                                                'meliscommerce_variant_tab_seo' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_variant_tab_seo',
                                                        'melisKey' => 'meliscommerce_variant_tab_seo',
                                                        'name' => 'tr_meliscommerce_seo_Seo',
                                                        'icon' => 'glyphicons search',
                                                        'href' => 'id_meliscommerce_variant_tab_content_seo',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab',
                                                    ],
                                                ],
                                                'meliscommerce_variant_tab_prices' => [
                                                    'conf' => [
                                                        'type' => 'meliscommerce/interface/meliscommerce_prices_tab',
                                                    ],
                                                ],
                                                'meliscommerce_variant_tab_stocks' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_variant_tab_stocks',
                                                        'melisKey' => 'meliscommerce_variant_tab_stocks',
                                                        'name' => 'tr_meliscommerce_variant_tab_stocks',
                                                        'icon' => 'glyphicons stats',
                                                        'href' => 'id_meliscommerce_variant_tab_content_stocks',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab',
                                                    ],
                                                ],
                                                'meliscommerce_variant_tab_assoc_var' => [
                                                    'conf' => [
                                                        'type' => 'meliscommerce/interface/meliscommerce_avar_tab',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'meliscommerce_variant_tab_contents_container' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_variant_tab_contents_container',
                                                'melisKey' => 'meliscommerce_variant_tab_contents_container',
                                                'name' => 'tr_meliscommerce_variant_tab_contents_container',
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComVariant',
                                                'action' => 'render-variant-tab-contents-container',
                                            ],
                                            'interface' => [
                                                'meliscommerce_variant_tab_content_main' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_variant_tab_content_main',
                                                        'melisKey' => 'meliscommerce_variant_tab_content_main',
                                                        'name' => 'tr_meliscommerce_variant_tab_content_main',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab-content',
                                                        'active' => 'active',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_variant_tab_main_header_container' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_variant_tab_main_header_container',
                                                                'melisKey' => 'meliscommerce_variant_tab_main_header_container',
                                                                'name' => 'tr_meliscommerce_variant_tab_main_header_container',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComVariant',
                                                                'action' => 'render-variant-tab-content-header-container',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_variant_tab_main_header_left' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_variant_tab_main_header_left',
                                                                        'melisKey' => 'meliscommerce_variant_tab_main_header_left',
                                                                        'name' => 'tr_meliscommerce_variant_tab_main_header_left',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-content-header-left',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_variant_tab_main_header' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_variant_tab_main_header',
                                                                                'melisKey' => 'meliscommerce_variant_tab_main_header',
                                                                                'name' => 'tr_meliscommerce_variant_tab_main_header',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-content-header',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_variant_tab_main_header_right' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_variant_tab_main_header_left',
                                                                        'melisKey' => 'meliscommerce_variant_tab_main_header_left',
                                                                        'name' => 'tr_meliscommerce_variant_tab_main_header_left',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-content-header-right',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_variant_tab_main_status_switch' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_variant_tab_main_status_switch',
                                                                                'melisKey' => 'meliscommerce_variant_tab_main_status_switch',
                                                                                'name' => 'tr_meliscommerce_variant_tab_main_status_switch',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-main-header-switch',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_variant_tab_main_contents' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_variant_tab_main_contents',
                                                                'melisKey' => 'meliscommerce_variant_tab_main_contents',
                                                                'name' => 'tr_meliscommerce_variant_tab_main_contents',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComVariant',
                                                                'action' => 'render-variant-tab-content-general-container',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_variant_tab_main_left_content' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_variant_tab_main_left_content',
                                                                        'melisKey' => 'meliscommerce_variant_tab_main_left_content',
                                                                        'name' => 'tr_meliscommerce_variant_tab_main_left_content',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-main-sub-content',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_variant_main_information' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_variant_main_information',
                                                                                'melisKey' => 'meliscommerce_variant_main_information',
                                                                                'name' => 'tr_meliscommerce_variant_main_information',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-sub-container',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_variant_main_information_heading' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_variant_main_information_heading',
                                                                                        'melisKey' => 'meliscommerce_variant_main_information_heading',
                                                                                        'name' => 'tr_meliscommerce_variant_main_information_heading',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-heading',
                                                                                    ],
                                                                                    'interface' => [
                                                                                        'meliscommerce_variant_main_information_header' => [
                                                                                            'conf' => [
                                                                                                'id' => 'id_meliscommerce_variant_main_information_header',
                                                                                                'melisKey' => 'meliscommerce_variant_main_information_header',
                                                                                                'name' => 'tr_meliscommerce_variant_main_information_header',
                                                                                                'icon' => 'fa fa-cog',
                                                                                            ],
                                                                                            'forward' => [
                                                                                                'module' => 'MelisCommerce',
                                                                                                'controller' => 'MelisComVariant',
                                                                                                'action' => 'render-variant-tab-sub-header',
                                                                                            ],
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                                'meliscommerce_variant_main_information_content_container' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_variant_main_information_content_container',
                                                                                        'melisKey' => 'meliscommerce_variant_main_information_content_container',
                                                                                        'name' => 'tr_meliscommerce_variant_main_information_content_container',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-content',
                                                                                    ],
                                                                                    'interface' => [
                                                                                        'meliscommerce_variant_main_infromation_content' => [
                                                                                            'conf' => [
                                                                                                'id' => 'id_meliscommerce_variant_main_infromation_content',
                                                                                                'melisKey' => 'meliscommerce_variant_main_infromation_content',
                                                                                                'name' => 'tr_meliscommerce_variant_main_infromation_content',
                                                                                            ],
                                                                                            'forward' => [
                                                                                                'module' => 'MelisCommerce',
                                                                                                'controller' => 'MelisComVariant',
                                                                                                'action' => 'render-variant-tab-main-information-content',
                                                                                                'jscallback' => ''
                                                                                            ],
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                        'meliscommerce_variant_main_files' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_variant_main_information',
                                                                                'melisKey' => 'meliscommerce_variant_main_information',
                                                                                'name' => 'tr_meliscommerce_variant_main_information',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-sub-container',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_variant_main_file_attachments' => [
                                                                                    'conf' => [
                                                                                        'type' => 'meliscommerce/interface/meliscommerce_documents_file_attachments_conf',
                                                                                        'docRelationType' => 'variant',
                                                                                    ]
                                                                                ]
                                                                            ],
                                                                        ],
                                                                        'meliscommerce_variant_main_attributes' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_variant_main_attributes',
                                                                                'melisKey' => 'meliscommerce_variant_main_attributes',
                                                                                'name' => 'tr_meliscommerce_variant_main_attributes',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-sub-container',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_variant_main_attributes_heading' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_variant_main_attributes_heading',
                                                                                        'melisKey' => 'meliscommerce_variant_main_attributes_heading',
                                                                                        'name' => 'tr_meliscommerce_variant_main_attributes_heading',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-heading',
                                                                                    ],
                                                                                    'interface' => [
                                                                                        'meliscommerce_variant_main_attributes_header' => [
                                                                                            'conf' => [
                                                                                                'id' => 'id_meliscommerce_variant_main_attributes_header',
                                                                                                'melisKey' => 'meliscommerce_variant_main_attributes_header',
                                                                                                'name' => 'tr_meliscommerce_variant_main_attributes_header',
                                                                                                'icon' => 'fa fa-cubes',
                                                                                            ],
                                                                                            'forward' => [
                                                                                                'module' => 'MelisCommerce',
                                                                                                'controller' => 'MelisComVariant',
                                                                                                'action' => 'render-variant-tab-sub-header',
                                                                                            ],
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                                'meliscommerce_variant_main_attributes_content_container' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_variant_main_attributes_content_container',
                                                                                        'melisKey' => 'meliscommerce_variant_main_attributes_content_container',
                                                                                        'name' => 'tr_meliscommerce_variant_main_attributes_content_container',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-content',
                                                                                    ],
                                                                                    'interface' => [
                                                                                        'meliscommerce_variant_main_attributes_content' => [
                                                                                            'conf' => [
                                                                                                'id' => 'id_meliscommerce_variant_main_attributes_content_placeholder',
                                                                                                'melisKey' => 'meliscommerce_variant_main_attributes_content_placeholder',
                                                                                                'name' => 'tr_meliscommerce_variant_main_attributes_content_placeholder',
                                                                                            ],
                                                                                            'forward' => [
                                                                                                'module' => 'MelisCommerce',
                                                                                                'controller' => 'MelisComVariant',
                                                                                                // 'action' => 'render-variant-tab-main-attributes-content',
                                                                                                'action' => 'render-variant-tab-main-attributes-content-placeholder',
                                                                                            ],
                                                                                        ],
                                                                                    ],
                                                                                ]
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_variant_tab_main_right_content' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_variant_tab_main_right_content',
                                                                        'melisKey' => 'meliscommerce_variant_tab_main_right_content',
                                                                        'name' => 'tr_meliscommerce_variant_tab_main_right_content',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-main-sub-content',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_variant_main_product_imgs' => [
                                                                            'conf' => [
                                                                                'type' => 'meliscommerce/interface/meliscommerce_documents_image_attachments_conf',
                                                                                'docRelationType' => 'variant',
                                                                            ]
                                                                        ]
                                                                    ], // end
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ], // end of main tab contents //
                                                'meliscommerce_variant_tab_content_seo' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_variant_tab_content_seo',
                                                        'melisKey' => 'meliscommerce_variant_tab_content_seo',
                                                        'name' => 'tr_meliscommerce_variant_tab_content_seo',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab-content',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_varian_seo_form' => [
                                                            'conf' => [
                                                                'type' => 'meliscommerce/interface/meliscommerce_seo_conf',
                                                                'formType' => 'variant',
                                                            ]
                                                        ]
                                                    ]
                                                ],
                                                'meliscommerce_variant_tab_content_prices' => [
                                                    'conf' => [
                                                        'type' => 'meliscommerce/interface/meliscommerce_prices_tab_content',
                                                    ],
                                                ],
                                                'meliscommerce_variant_tab_content_avar' => [
                                                    'conf' => [
                                                        'type' => 'meliscommerce/interface/meliscommerce_avar_tab_content',
                                                    ],
                                                ],
                                                // end of price tab content.
                                                'meliscommerce_variant_tab_content_stocks' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_variant_tab_content_stocks',
                                                        'melisKey' => 'meliscommerce_variant_tab_content_stocks',
                                                        'name' => 'tr_meliscommerce_variant_tab_content_stocks',
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab-content',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_variant_tab_stocks_header_container' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_variant_tab_stocks_header_container',
                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_header_container',
                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_header_container',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComVariant',
                                                                'action' => 'render-variant-tab-content-header-container',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_variant_tab_stocks_header_left' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_header_left',
                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_header_left',
                                                                        'name' => 'tr_meliscommerce_variant_tab_stocks_header_left',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-content-header-left',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_variant_tab_stocks_header' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_header',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_header',
                                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_header',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-content-header',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_variant_tab_stocks_header_right' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_header_right',
                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_header_right',
                                                                        'name' => 'tr_meliscommerce_variant_tab_stocks_header_right',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-content-header-right',
                                                                    ],
                                                                    'interface' => [
                                                                        /* 'meliscommerce_variant_tab_stocks_header_add' => [                                                                            'conf' => [                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_header_add',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_header_add',
                                                                                'name' => 'tr_meliscommerce_variant_tab_prices_header_add',
                                                                                'icon' => 'fa fa-globe',
                                                                            ],
                                                                            'forward' => [                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-stocks-header-add',
                                                                            ],
                                                                        ], */],
                                                                ],
                                                            ],
                                                        ],
                                                        'meliscommerce_variant_tab_stocks_contents' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_variant_tab_stocks_contents',
                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_contents',
                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_contents',
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComVariant',
                                                                'action' => 'render-variant-tab-content-general-container',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_variant_tab_stocks_left_content' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_left_content',
                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_left_content',
                                                                        'name' => 'tr_meliscommerce_variant_tab_stocks_left_content',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-prices-content-left-container',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_variant_tab_stocks_country_heading' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_country_heading',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_country_heading',
                                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_country_heading',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-sub-heading',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_variant_tab_stocks_country_header' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_country_header',
                                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_country_header',
                                                                                        'name' => 'tr_meliscommerce_variant_tab_prices_country_header',
                                                                                        'icon' => 'fa fa-globe',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-header',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                        'meliscommerce_variant_tab_stocks_country_list' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_country_list',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_country_list',
                                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_country_list',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-stocks-country-list',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                'meliscommerce_variant_tab_stocks_right_content' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_right_content',
                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_right_content',
                                                                        'name' => 'tr_meliscommerce_variant_tab_stocks_right_content',
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-prices-content-right-container',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_variant_tab_stocks_country_form_heading' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_country_form_heading',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_country_form_heading',
                                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_country_form_heading',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-sub-heading',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_variant_tab_stocks_country_header' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_country_header',
                                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_country_header',
                                                                                        'name' => 'tr_meliscommerce_variant_tab_prices_country_header_general',
                                                                                        'icon' => 'fa fa-cubes',
                                                                                        'class' => 'country-stock-label',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-header',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                        'meliscommerce_variant_tab_stocks_country_form' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_country_form',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_country_form',
                                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_country_form',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-stocks-country-form',
                                                                                'jscallback' => 'variantLoaded();',
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ], // end of variants page
                    ],
                ],
            ],
        ],
    ],
];

