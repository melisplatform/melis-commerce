<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_categories_Categories',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/assets/jstree/dist/jstree.min.js',
                    '/MelisCommerce/assets/switch/bootstrap-switch.js',
                    '/MelisCommerce/assets/switch/bootstrap-switch.init.js',
                    '/MelisCommerce/js/tools/category.tool.js',
                    '/MelisCommerce/js/tools/documents.tool.js',
                    '/MelisCommerce/js/tools/seo.tool.js',
                ),
                'css' => array(
                    '/MelisCommerce/css/categories.css', 
                )
            ),
            'datas' => array(
            
            ),
            'interface' => array(
                'meliscommerce_categories' => array(
                    'interface' => array(
                        'meliscommerce_categories_leftmenu' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_categories_page',
                                'melisKey' => 'meliscommerce_categories_page',
                                'name' => 'tr_meliscommerce_categories_Categories',
                                'icon' => 'fa fa-list-ul',
                            ),
                        ),
                        'meliscommerce_categories_page' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_categories_page',
                                'melisKey' => 'meliscommerce_categories_page',
                                'name' => 'tr_meliscommerce_categories_page'
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCategoryList',
                                'action' => 'render-categories-page',
                            ),
                            'interface' => array(
                                'meliscommerce_categories_list' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_categories_list',
                                        'melisKey' => 'meliscommerce_categories_list',
                                        'name' => 'tr_meliscommerce_categories_list',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCategoryList',
                                        'action' => 'render-category-list',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_categories_list_header' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_categories_list_header',
                                                'melisKey' => 'meliscommerce_categories_list_header',
                                                'name' => 'tr_meliscommerce_categories_list_header'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCategoryList',
                                                'action' => 'render-category-list-header',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_categories_list_header_add_category' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_categories_list_header_add_category',
                                                        'melisKey' => 'meliscommerce_categories_list_header_add_category',
                                                        'name' => 'tr_meliscommerce_categories_list_header_add_category'
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategoryList',
                                                        'action' => 'render-category-list-header-add-category'
                                                    )
                                                )
                                            )
                                        ),
                                        'meliscommerce_categories_list_content' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_categories_list_content',
                                                'melisKey' => 'meliscommerce_categories_list_content',
                                                'name' => 'tr_meliscommerce_categories_list_content'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCategoryList',
                                                'action' => 'render-category-list-content',
                                            ),
                                            'interface' => array(
                                                // content
                                                'meliscommerce_categories_list_search_input' => array(
                                                    'conf' => array(
                                                        'id' => 'meliscommerce_categories_list_search_input',
                                                        'melisKey' => 'meliscommerce_categories_list_search_input',
                                                        'name' => 'tr_meliscommerce_categories_list_search_input'
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategoryList',
                                                        'action' => 'render-category-list-search-input',
                                                    )
                                                ),
                                                'meliscommerce_categories_list_categories_tree' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_catergories_list_categories_tree',
                                                        'melisKey' => 'meliscommerce_categories_list_categories_tree',
                                                        'name' => 'tr_meliscommerce_categories_list_categories_tree'
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategoryList',
                                                        'action' => 'render-category-list-tree-view',
                                                        'jscallback' => 'initCategoryTreeView();'
                                                    )
                                                )
                                            )
                                        )
                                    )
                                ),
                                'meliscommerce_categories_category' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_categories_category',
                                        'melisKey' => 'meliscommerce_categories_category',
                                        'name' => 'tr_meliscommerce_categories_category'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComCategory',
                                        'action' => 'render-category',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_categories_category_header' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_categories_category_header',
                                                'melisKey' => 'meliscommerce_categories_category_header',
                                                'name' => 'tr_meliscommerce_categories_category_header'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCategory',
                                                'action' => 'render-category-header',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_categories_category_header_save_category' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_categories_category_header_save_category',
                                                        'melisKey' => 'meliscommerce_categories_category_header_save_category',
                                                        'name' => 'tr_meliscommerce_categories_category_header_save_category'
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategory',
                                                        'action' => 'render-category-header-save-category',
                                                    )
                                                )
                                            )
                                        ),
                                        'meliscommerce_categories_category_content' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_categories_category_content',
                                                'melisKey' => 'meliscommerce_categories_category_content',
                                                'name' => 'tr_meliscommerce_categories_category_content'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComCategory',
                                                'action' => 'render-category-content',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_categories_category_tabs' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_categories_category_tabs',
                                                        'melisKey' => 'meliscommerce_categories_category_tabs',
                                                        'name' => 'tr_meliscommerce_categories_category_tab_content'
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComCategory',
                                                        'action' => 'render-category-tab-content',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_categories_category_tab_main' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_categories_category_tab_main',
                                                                'melisKey' => 'meliscommerce_categories_category_tab_main',
                                                                'name' => 'tr_meliscommerce_categories_common_label_main',
                                                                'icon' => 'glyphicons tag'
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCategory',
                                                                'action' => 'render-category-tab-main',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_categories_category_tab_main_header' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_categories_category_tab_main_header',
                                                                        'melisKey' => 'meliscommerce_categories_category_tab_main_header',
                                                                        'name' => 'tr_meliscommerce_categories_category_tab_main_header'
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCategory',
                                                                        'action' => 'render-category-tab-main-header',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_categories_category_form_status' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_categories_category_form_status',
                                                                                'melisKey' => 'meliscommerce_categories_category_form_status',
                                                                                'name' => 'tr_meliscommerce_categories_category_form_status',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCategory',
                                                                                'action' => 'render-category-form-status',
                                                                                'jscallback' => 'initCategoryStatus();'
                                                                            )
                                                                        )
                                                                    )
                                                                ),
                                                                'meliscommerce_categories_category_tab_main_content' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_categories_category_tab_main_header',
                                                                        'melisKey' => 'meliscommerce_categories_category_tab_main_header',
                                                                        'name' => 'tr_meliscommerce_categories_category_tab_main_header'
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComCategory',
                                                                        'action' => 'render-category-tab-main-content',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_categories_category_tab_main_left' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_categories_category_tab_main_left',
                                                                                'melisKey' => 'meliscommerce_categories_category_tab_main_left',
                                                                                'name' => 'tr_meliscommerce_categories_category_tab_main_left'
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCategory',
                                                                                'action' => 'render-category-tab-main-left',
                                                                            ),
                                                                            'interface' => array(
                                                                                'meliscommerce_categories_category_form_transalations' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_categories_category_form_transalations',
                                                                                        'melisKey' => 'meliscommerce_categories_category_form_transalations',
                                                                                        'name' => 'tr_meliscommerce_categories_category_form_transalations',
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComCategory',
                                                                                        'action' => 'render-category-form-translations',
                                                                                    )
                                                                                ),
                                                                                'meliscommerce_categories_category_form_countries' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_categories_category_form_countries',
                                                                                        'melisKey' => 'meliscommerce_categories_category_form_countries',
                                                                                        'name' => 'tr_meliscommerce_categories_category_form_countries',
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComCategory',
                                                                                        'action' => 'render-category-form-countries',
                                                                                    )
                                                                                ),
                                                                                'meliscommerce_categories_category_main_file_attachments' => array(
                                                                                    'conf' => array(
                                                                                        'type' => 'meliscommerce/interface/meliscommerce_documents_file_attachments_conf',
                                                                                        'docRelationType' => 'category',
                                                                                    )
                                                                                )
                                                                            )
                                                                        ),
                                                                        'meliscommerce_categories_category_tab_main_right' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_categories_category_tab_main_right',
                                                                                'melisKey' => 'meliscommerce_categories_category_tab_main_right',
                                                                                'name' => 'tr_meliscommerce_categories_category_tab_main_right'
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComCategory',
                                                                                'action' => 'render-category-tab-main-right',
                                                                            ),
                                                                            'interface' => array(
                                                                                'meliscommerce_categories_category_main_product_imgs' => array(
                                                                                    'conf' => array(
                                                                                        'type' => 'meliscommerce/interface/meliscommerce_documents_image_attachments_conf',
                                                                                        'docRelationType' => 'category',
                                                                                    )
                                                                                )
                                                                            )
                                                                        )
                                                                    )
                                                                ),
                                                            )
                                                        ),
                                                        'meliscommerce_categories_category_tab_seo' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_categories_category_tab_seo',
                                                                'melisKey' => 'meliscommerce_categories_category_tab_seo',
                                                                'name' => 'tr_meliscommerce_categories_common_label_seo',
                                                                'icon' => 'glyphicons search'
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCategory',
                                                                'action' => 'render-category-tab-seo',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_categories_category_tab_seo_content' => array(
                                                                    'conf' => array(
                                                                        'type' => 'meliscommerce/interface/meliscommerce_seo_conf',
                                                                        'formType' => 'category',
                                                                    )
                                                                )
                                                            )
                                                        ),
                                                        'meliscommerce_categories_category_tab_products' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_categories_category_tab_products',
                                                                'melisKey' => 'meliscommerce_categories_category_tab_products',
                                                                'name' => 'tr_meliscommerce_categories_common_label_producs',
                                                                'icon' => 'glyphicons shopping_cart'
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComCategory',
                                                                'action' => 'render-category-tab-products',
                                                            ),
//                                                             'interface' => array(
//                                                                 'meliscommerce_categories_category_products' => array(
//                                                                     'conf' => array(
//                                                                         'type' => 'meliscommerce/interface/meliscommerce_product_list/interface/meliscommerce_product_list_container/interface/meliscommerce_product_list_content'
//                                                                     )
//                                                                 )
//                                                             )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    )
);