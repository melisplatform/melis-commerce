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
                'name' => 'tr_meliscommerce_categories_Categories',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/assets/jstree/dist/jstree.min.js',
                    '/MelisCommerce/assets/switch/bootstrap-switch.js',
                    '/MelisCommerce/js/tools/category.tool.js',
                    '/MelisCommerce/js/tools/seo.tool.js',
                    '/MelisCommerce/plugins/js/MelisCommerceCategoryTreePlugin.js',
                ],
            ],
            'datas' => [

            ],
            'interface' => [
                'meliscommerce_categories' => [
                    'interface' => [
                        'meliscommerce_categories_leftmenu' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_categories_page',
                                'melisKey' => 'meliscommerce_categories_page',
                                'name' => 'tr_meliscommerce_categories_Categories',
                                'icon' => 'fa fa-book',
                            ],
                            'interface' => [
                                'meliscommerce_categories_page' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_categories/interface/meliscommerce_categories_page',
                                        
                                    ],
                                ]
                            ]
                        ],
                        'meliscommerce_categories_page' => [
                            'conf' => [
                                'id' => 'id_meliscommerce_categories_page',
                                'melisKey' => 'meliscommerce_categories_page',
                                'name' => 'tr_meliscommerce_categories_page'
                            ],
                            'forward' => [
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCategoryList',
                                'action' => 'render-categories-page',
                            ],
                            'interface' => [
                                'meliscommerce_categories_list' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_categories_list',
                                        'melisKey' => 'meliscommerce_categories_list',
                                        'name' => 'tr_meliscommerce_categories_list',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCategoryList',
                                        'action' => 'render-category-list',
                                    ],
                                    'interface' => [
                                        'meliscommerce_categories_list_header' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_categories_list_header',
                                                'melisKey' => 'meliscommerce_categories_list_header',
                                                'name' => 'tr_meliscommerce_categories_list_header'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCategoryList',
                                                'action' => 'render-category-list-header',
                                            ],
                                            'interface' => [
                                                'meliscommerce_categories_list_header_add_catalog' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_categories_list_header_add_catalog',
                                                        'melisKey' => 'meliscommerce_categories_list_header_add_catalog',
                                                        'name' => 'tr_meliscommerce_categories_list_header_add_catalog'
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategoryList',
                                                        'action' => 'render-category-list-header-add-catalog'
                                                    ]
                                                ],
                                                'meliscommerce_categories_list_header_add_category' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_categories_list_header_add_category',
                                                        'melisKey' => 'meliscommerce_categories_list_header_add_category',
                                                        'name' => 'tr_meliscommerce_categories_list_header_add_category'
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategoryList',
                                                        'action' => 'render-category-list-header-add-category'
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'meliscommerce_categories_list_content' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_categories_list_content',
                                                'melisKey' => 'meliscommerce_categories_list_content',
                                                'name' => 'tr_meliscommerce_categories_list_content'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCategoryList',
                                                'action' => 'render-category-list-content',
                                            ],
                                            'interface' => [
                                                // content
                                                'meliscommerce_categories_list_search_input' => [
                                                    'conf' => [
                                                        'id' => 'meliscommerce_categories_list_search_input',
                                                        'melisKey' => 'meliscommerce_categories_list_search_input',
                                                        'name' => 'tr_meliscommerce_categories_list_search_input'
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategoryList',
                                                        'action' => 'render-category-list-search-input',
                                                    ]
                                                ],
                                                'meliscommerce_categories_list_categories_tree' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_catergories_list_categories_tree',
                                                        'melisKey' => 'meliscommerce_categories_list_categories_tree',
                                                        'name' => 'tr_meliscommerce_categories_list_categories_tree'
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategoryList',
                                                        'action' => 'render-category-list-tree-view',
                                                        'jscallback' => 'initCategoryTreeView();'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                'meliscommerce_categories_category' => [
                                    'conf' => [
                                        'id' => 'id_meliscommerce_categories_category',
                                        'melisKey' => 'meliscommerce_categories_category',
                                        'name' => 'tr_meliscommerce_categories_category'
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCategory',
                                        'action' => 'render-category',
                                    ],
                                    'interface' => [
                                        'meliscommerce_categories_category_header' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_categories_category_header',
                                                'melisKey' => 'meliscommerce_categories_category_header',
                                                'name' => 'tr_meliscommerce_categories_category_header'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCategory',
                                                'action' => 'render-category-header',
                                            ],
                                            'interface' => [
                                                'meliscommerce_categories_category_header_save_category' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_categories_category_header_save_category',
                                                        'melisKey' => 'meliscommerce_categories_category_header_save_category',
                                                        'name' => 'tr_meliscommerce_categories_category_header_save_category'
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategory',
                                                        'action' => 'render-category-header-save-category',
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'meliscommerce_categories_category_content' => [
                                            'conf' => [
                                                'id' => 'id_meliscommerce_categories_category_content',
                                                'melisKey' => 'meliscommerce_categories_category_content',
                                                'name' => 'tr_meliscommerce_categories_category_content'
                                            ],
                                            'forward' => [
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCategory',
                                                'action' => 'render-category-content',
                                            ],
                                            'interface' => [
                                                'meliscommerce_categories_category_tabs' => [
                                                    'conf' => [
                                                        'id' => 'id_meliscommerce_categories_category_tabs',
                                                        'melisKey' => 'meliscommerce_categories_category_tabs',
                                                        'name' => 'tr_meliscommerce_categories_category_tab_content'
                                                    ],
                                                    'forward' => [
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategory',
                                                        'action' => 'render-category-tab-content',
                                                    ],
                                                    'interface' => [
                                                        'meliscommerce_categories_category_tab_main' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_categories_category_tab_main',
                                                                'melisKey' => 'meliscommerce_categories_category_tab_main',
                                                                'name' => 'tr_meliscommerce_categories_common_label_main',
                                                                'icon' => 'glyphicons tag'
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCategory',
                                                                'action' => 'render-category-tab-main',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_categories_category_tab_main_header' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_categories_category_tab_main_header',
                                                                        'melisKey' => 'meliscommerce_categories_category_tab_main_header',
                                                                        'name' => 'tr_meliscommerce_categories_category_tab_main_header'
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCategory',
                                                                        'action' => 'render-category-tab-main-header',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_categories_category_form_status' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_categories_category_form_status',
                                                                                'melisKey' => 'meliscommerce_categories_category_form_status',
                                                                                'name' => 'tr_meliscommerce_categories_category_form_status',
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCategory',
                                                                                'action' => 'render-category-form-status',
                                                                                'jscallback' => 'initCategoryStatus();'
                                                                            ]
                                                                        ]
                                                                    ]
                                                                ],
                                                                'meliscommerce_categories_category_tab_main_content' => [
                                                                    'conf' => [
                                                                        'id' => 'id_meliscommerce_categories_category_tab_main_header',
                                                                        'melisKey' => 'meliscommerce_categories_category_tab_main_header',
                                                                        'name' => 'tr_meliscommerce_categories_category_tab_main_header'
                                                                    ],
                                                                    'forward' => [
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCategory',
                                                                        'action' => 'render-category-tab-main-content',
                                                                    ],
                                                                    'interface' => [
                                                                        'meliscommerce_categories_category_tab_main_left' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_categories_category_tab_main_left',
                                                                                'melisKey' => 'meliscommerce_categories_category_tab_main_left',
                                                                                'name' => 'tr_meliscommerce_categories_category_tab_main_left'
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCategory',
                                                                                'action' => 'render-category-tab-main-left',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_categories_category_form_transalations' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_categories_category_form_transalations',
                                                                                        'melisKey' => 'meliscommerce_categories_category_form_transalations',
                                                                                        'name' => 'tr_meliscommerce_categories_category_form_transalations',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComCategory',
                                                                                        'action' => 'render-category-form-translations',
                                                                                    ]
                                                                                ],
                                                                                'meliscommerce_categories_category_form_date_validity' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_categories_category_form_date_validity',
                                                                                        'melisKey' => 'meliscommerce_categories_category_form_date_validity',
                                                                                        'name' => 'tr_meliscommerce_categories_category_form_date_validity',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComCategory',
                                                                                        'action' => 'render-category-form-date-validity',
                                                                                    ]
                                                                                ],
                                                                                'meliscommerce_categories_category_form_countries' => [
                                                                                    'conf' => [
                                                                                        'id' => 'id_meliscommerce_categories_category_form_countries',
                                                                                        'melisKey' => 'meliscommerce_categories_category_form_countries',
                                                                                        'name' => 'tr_meliscommerce_categories_category_form_countries',
                                                                                    ],
                                                                                    'forward' => [
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComCategory',
                                                                                        'action' => 'render-category-form-countries',
                                                                                    ]
                                                                                ],
                                                                                'meliscommerce_categories_category_main_file_attachments' => [
                                                                                    'conf' => [
                                                                                        'type' => 'meliscommerce/interface/meliscommerce_documents_file_attachments_conf',
                                                                                        'docRelationType' => 'category',
                                                                                    ]
                                                                                ]
                                                                            ]
                                                                        ],
                                                                        'meliscommerce_categories_category_tab_main_right' => [
                                                                            'conf' => [
                                                                                'id' => 'id_meliscommerce_categories_category_tab_main_right',
                                                                                'melisKey' => 'meliscommerce_categories_category_tab_main_right',
                                                                                'name' => 'tr_meliscommerce_categories_category_tab_main_right'
                                                                            ],
                                                                            'forward' => [
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCategory',
                                                                                'action' => 'render-category-tab-main-right',
                                                                            ],
                                                                            'interface' => [
                                                                                'meliscommerce_categories_category_main_product_imgs' => [
                                                                                    'conf' => [
                                                                                        'type' => 'meliscommerce/interface/meliscommerce_documents_image_attachments_conf',
                                                                                        'docRelationType' => 'category',
                                                                                    ]
                                                                                ]
                                                                            ]
                                                                        ]
                                                                    ]
                                                                ],
                                                            ]
                                                        ],
                                                        'meliscommerce_categories_category_tab_seo' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_categories_category_tab_seo',
                                                                'melisKey' => 'meliscommerce_categories_category_tab_seo',
                                                                'name' => 'tr_meliscommerce_categories_common_label_seo',
                                                                'icon' => 'glyphicons search'
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCategory',
                                                                'action' => 'render-category-tab-seo',
                                                            ],
                                                            'interface' => [
                                                                'meliscommerce_categories_category_tab_seo_content' => [
                                                                    'conf' => [
                                                                        'type' => 'meliscommerce/interface/meliscommerce_seo_conf',
                                                                        'formType' => 'category',
                                                                    ]
                                                                ]
                                                            ]
                                                        ],
                                                        'meliscommerce_categories_category_tab_products' => [
                                                            'conf' => [
                                                                'id' => 'id_meliscommerce_categories_category_tab_products',
                                                                'melisKey' => 'meliscommerce_categories_category_tab_products',
                                                                'name' => 'tr_meliscommerce_categories_common_label_producs',
                                                                'icon' => 'glyphicons shopping_cart'
                                                            ],
                                                            'forward' => [
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCategory',
                                                                'action' => 'render-category-tab-products',
                                                            ],
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
];