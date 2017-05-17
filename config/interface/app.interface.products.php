<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_products_Products',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/product.tool.js',
                    '/MelisCommerce/js/tools/seo.tool.js',
                    '/MelisCommerce/assets/select2/js/select2.min.js',
                ),
                'css' => array(
                    '/MelisCommerce/css/products.css',
                    '/MelisCommerce/assets/select2/css/select2.min.css',
                ),
            ),
            'datas' => array(
            
            ),
            'interface' => array(
                  'meliscommerce_product_list' => array(
                      'interface' => array(
                          'meliscommerce_product_list_leftmenu' => array(
                              'conf' => array(
                                  'id' => 'id_meliscommerce_product_list_container',
                                  'melisKey' => 'meliscommerce_product_list_container',
                                  'name' => 'tr_meliscommerce_products_Products',
                                  'icon' => 'icon-shippment',
                              ),
                          ),
                          'meliscommerce_product_list_container' => array(
                              'conf' => array(
                                  'id' => 'id_meliscommerce_product_list_container',
                                  'melisKey' => 'meliscommerce_product_list_container',
                                  'name' => 'tr_meliscommerce_products_Products',
                              ),
                              'forward' => array(
                                  'module' => 'MelisCommerce',
                                  'controller' => 'MelisComProductList',
                                  'action' => 'render-product-list-page',
                                  'jscallback' => '',
                                  'jsdatas' => array()
                              ),
                              'interface' => array(
                                  'meliscommerce_product_list_header' => array(
                                      'conf' => array(
                                          'id' => 'id_meliscommerce_product_list_header',
                                          'melisKey' => 'meliscommerce_product_list_header',
                                          'name' => 'tr_meliscommerce_products_Products header',
                                      ),
                                      'forward' => array(
                                          'module' => 'MelisCommerce',
                                          'controller' => 'MelisComProductList',
                                          'action' => 'render-product-list-header',
                                          'jscallback' => '',
                                          'jsdatas' => array()
                                      ),
                                      'interface' => array(
                                            'meliscommerce_product_list_header_add' => array(
                                                'conf' => array(
                                                    'id' => 'id_meliscommerce_product_list_header_add',
                                                    'melisKey' => 'meliscommerce_product_list_header_add',
                                                    'name' => 'tr_meliscommerce_products_Products header add',
                                                ),
                                                'forward' => array(
                                                    'module' => 'MelisCommerce',
                                                    'controller' => 'MelisComProductList',
                                                    'action' => 'render-product-list-header-add',
                                                    'jscallback' => '',
                                                    'jsdatas' => array()
                                                ),
                                            ),
                                      ),
                                  ),
                                  
                                  'meliscommerce_product_list_content' => array(
                                      'conf' => array(
                                          'id' => 'id_meliscommerce_product_list_content',
                                          'melisKey' => 'meliscommerce_product_list_content',
                                          'name' => 'tr_meliscommerce_products_Products content',
                                      ),
                                      'forward' => array(
                                          'module' => 'MelisCommerce',
                                          'controller' => 'MelisComProductList',
                                          'action' => 'render-product-list-content',
                                          'jscallback' => '',
                                          'jsdatas' => array()
                                      ),
                                      'interface' => array(

                                      ),
                                  ),
                          
                              ),
                          ),
                      ), 
                  ),
                  'meliscommerce_products' => array(
                      'interface' => array(
                            'meliscommerce_products_leftmenu' => array(
                                'conf' => array(
                                    'id' => 'id_meliscommerce_products_page',
                                    'melisKey' => 'meliscommerce_products_page',
                                    'name' => 'tr_meliscommerce_products_Products',
                                    'icon' => 'fa fa-dribbble',
                                ),
                            ),
                            'meliscommerce_products_page' => array(
                                'conf' => array(
                                    'id' => 'id_meliscommerce_products_page',
                                    'melisKey' => 'meliscommerce_products_page',
                                    'name' => 'tr_meliscommerce_products_Products',
                                ),
                                'forward' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProduct',
                                    'action' => 'render-products-page',
                                ),
                                'interface' => array(
                                    'meliscommerce_products_page_header_container' => array(
                                        'conf' => array(
                                            'id' => 'id_meliscommerce_products_page_header_container',
                                            'melisKey' => 'meliscommerce_products_page_header_container',
                                            'name' => 'tr_meliscommerce_products_page_header_container'
                                        ),
                                        'forward' => array(
                                            'module' => 'MelisCommerce',
                                            'controller' => 'MelisComProduct',
                                            'action' => 'render-products-page-header-container',
                                        ),
                                        'interface' => array(
                                            'meliscommerce_products_page_header_container_left' => array(
                                                'conf' => array(
                                                    'id' => 'id_meliscommerce_products_page_header_container_left',
                                                    'melisKey' => 'meliscommerce_products_page_header_container_left',
                                                    'name' => 'tr_meliscommerce_products_page_header_container_left'
                                                ),
                                                'forward' => array(
                                                    'module' => 'MelisCommerce',
                                                    'controller' => 'MelisComProduct',
                                                    'action' => 'render-products-page-header-left',
                                                ),
                                                'interface' => array(
                                                      'meliscommerce_products_page_header_title' => array(
                                                          'conf' => array(
                                                              'id' => 'id_meliscommerce_products_page_header_title',
                                                              'melisKey' => 'meliscommerce_products_page_header_title',
                                                              'name' => 'tr_meliscommerce_products_Products'
                                                          ),
                                                          'forward' => array(
                                                              'module' => 'MelisCommerce',
                                                              'controller' => 'MelisComProduct',
                                                              'action' => 'render-products-page-header-title',
                                                          ),
                                                      ),
                                                ),
                                            ),
                                            'meliscommerce_products_page_header_container_right' => array(
                                                'conf' => array(
                                                    'id' => 'id_meliscommerce_products_page_header_container_right',
                                                    'melisKey' => 'meliscommerce_products_page_header_container_right',
                                                    'name' => 'tr_meliscommerce_products_page_header_container_right'
                                                ),
                                                'forward' => array(
                                                    'module' => 'MelisCommerce',
                                                    'controller' => 'MelisComProduct',
                                                    'action' => 'render-products-page-header-right',
                                                ),
                                                'interface' => array(
                                                    'meliscommerce_products_page_header_save_product' => array(
                                                        'conf' => array(
                                                            'id' => 'id_meliscommerce_products_page_header_save_product',
                                                            'melisKey' => 'meliscommerce_products_page_header_save_product',
                                                            'name' => 'tr_meliscommerce_products_page_header_save_product',
                                                        ),
                                                        'forward' => array(
                                                            'module' => 'MelisCommerce',
                                                            'controller' => 'MelisComProduct',
                                                            'action' => 'render-products-page-header-product-save',
                                                        ),
                                                    ),
                                                ),
                                            ),                                            
                                        ),
                                    ),// END OF PRODUCTS PAGE HEADER
                                    'meliscommerce_products_page_content' => array(
                                        'conf' => array(
                                            'id' => 'id_meliscommerce_products_page_content',
                                            'melisKey' => 'meliscommerce_products_page_content',
                                            'name' => 'tr_meliscommerce_products_page_content',
                                        ),
                                        'forward' => array(
                                            'module' => 'MelisCommerce',
                                            'controller' => 'MelisComProduct',
                                            'action' => 'render-products-page-content',
                                        ),
                                        'interface' => array(
                                             'meliscommerce_products_page_content_tabs' => array(
                                                 'conf' => array(
                                                     'id' => 'id_meliscommerce_products_page_content_tabs',
                                                     'melisKey' => 'meliscommerce_products_page_content_tabs',
                                                     'name' => 'tr_meliscommerce_products_page_content_tabs',
                                                 ),
                                                 'forward' => array(
                                                     'module' => 'MelisCommerce',
                                                     'controller' => 'MelisComProduct',
                                                     'action' => 'render-products-page-content-tabs',
                                                 ),
                                                 'interface' => array(
                                                     'meliscommerce_products_page_content_tab_main' => array(
                                                         'conf' => array(
                                                             'id' => 'id_meliscommerce_products_page_content_tab_main',
                                                             'melisKey' => 'meliscommerce_products_page_content_tab_main',
                                                             'name' => 'tr_meliscommerce_products_page_tab_content_main',
                                                             'active' => 'active',
                                                             'href' => 'id_meliscommerce_products_page_content_tab_main_container',
                                                             'icon' => 'glyphicons tag',
                                                         ),
                                                         'forward' => array(
                                                             'module' => 'MelisCommerce',
                                                             'controller' => 'MelisComProduct',
                                                             'action' => 'render-products-page-content-generic-tab-head',
                                                         ),
                                                     ),
                                                     'meliscommerce_products_page_content_tab_text' => array(
                                                         'conf' => array(
                                                             'id' => 'id_meliscommerce_products_page_content_tab_text',
                                                             'melisKey' => 'meliscommerce_products_page_content_tab_text',
                                                             'name' => 'tr_meliscommerce_products_page_content_tab_text',
                                                             'href' => 'id_meliscommerce_products_page_content_tab_text_container',
                                                             'icon' => 'glyphicons pencil',
                                                         ),
                                                         'forward' => array(
                                                             'module' => 'MelisCommerce',
                                                             'controller' => 'MelisComProduct',
                                                             'action' => 'render-products-page-content-generic-tab-head',
                                                         ),
                                                     ),
                                                     'meliscommerce_products_page_content_tab_variants' => array(
                                                         'conf' => array(
                                                             'id' => 'id_meliscommerce_products_page_content_tab_variants',
                                                             'melisKey' => 'meliscommerce_products_page_content_tab_variants',
                                                             'name' => 'tr_meliscommerce_products_page_content_tab_variants',
                                                             'href' => 'id_meliscommerce_products_page_content_tab_variants_container',
                                                             'icon' => 'glyphicons list',
                                                         ),
                                                         'forward' => array(
                                                             'module' => 'MelisCommerce',
                                                             'controller' => 'MelisComProduct',
                                                             'action' => 'render-products-page-content-generic-tab-head',
                                                         ),
                                                     ),
                                                     'meliscommerce_products_page_content_tab_seo' => array(
                                                         'conf' => array(
                                                             'id' => 'id_meliscommerce_products_page_content_tab_seo',
                                                             'melisKey' => 'meliscommerce_products_page_content_tab_seo',
                                                             'name' => 'tr_meliscommerce_products_page_content_tab_seo',
                                                             'href' => 'id_meliscommerce_products_page_content_tab_seo_container',
                                                             'icon' => 'glyphicons search',
                                                         ),
                                                         'forward' => array(
                                                             'module' => 'MelisCommerce',
                                                             'controller' => 'MelisComProduct',
                                                             'action' => 'render-products-page-content-generic-tab-head',
                                                         ),
                                                     ),
                                                     'meliscommerce_products_page_content_tab_price' => array(
                                                         'conf' => array(
                                                            'type' => 'meliscommerce/interface/meliscommerce_prices_tab',
                                                         ),                                                          
                                                     ),
                                                 ),
                                             ),// END of meliscommerce_products_page_content_tabs
                                             'meliscommerce_products_page_content_tab_container' => array(
                                                 'conf' => array(
                                                     'id' => 'id_meliscommerce_products_page_content_tab_container',
                                                     'melisKey' => 'meliscommerce_products_page_content_tab_container',
                                                     'name' => 'tr_meliscommerce_products_page_content_tab_container',
                                                 ),
                                                 'forward' => array(
                                                     'module' => 'MelisCommerce',
                                                     'controller' => 'MelisComProduct',
                                                     'action' => 'render-products-page-content-tab-container',
                                                 ),
                                                 'interface' => array(
                                                    'meliscommerce_products_page_content_tab_main_container' => array(
                                                        'conf' => array(
                                                            'id' => 'id_meliscommerce_products_page_content_tab_main_container',
                                                            'melisKey' => 'meliscommerce_products_page_content_tab_main_container',
                                                            'name' => 'tr_meliscommerce_products_page_content_tab_main_container',
                                                            'active' => 'active',
                                                        ),
                                                        'forward' => array(
                                                            'module' => 'MelisCommerce',
                                                            'controller' => 'MelisComProduct',
                                                            'action' => 'render-products-page-content-tab-main-container',
                                                        ),
                                                        'interface' => array(
                                                            // start Product tab Main Header
                                                            'meliscommerce_products_page_content_tab_main_header_container' => array(
                                                                'conf' => array(
                                                                    'id' => 'id_meliscommerce_products_page_content_tab_main_header_container',
                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_main_header_container',
                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_main_header_container',
                                                                ),
                                                                'forward' => array(
                                                                    'module' => 'MelisCommerce',
                                                                    'controller' => 'MelisComProduct',
                                                                    'action' => 'render-products-page-content-tab-main-header-container',
                                                                ),
                                                                'interface' => array(
                                                                     'meliscommerce_products_page_content_tab_main_header_left' => array(
                                                                         'conf' => array(
                                                                             'id' => 'id_meliscommerce_products_page_content_tab_main_header_left',
                                                                             'melisKey' => 'meliscommerce_products_page_content_tab_main_header_left',
                                                                             'name' => 'tr_meliscommerce_products_page_content_tab_main_header_left',
                                                                         ),
                                                                         'forward' => array(
                                                                             'module' => 'MelisCommerce',
                                                                             'controller' => 'MelisComProduct',
                                                                             'action' => 'render-products-page-content-tab-main-header-left',
                                                                             'jscallback' => 'initProductSwitch();'
                                                                         ),
                                                                         'interface' => array(
                                                                             'meliscommerce_products_page_content_tab_main_header' => array(
                                                                                 'conf' => array(
                                                                                     'id' => 'id_meliscommerce_products_page_content_tab_main_header',
                                                                                     'melisKey' => 'meliscommerce_products_page_content_tab_main_header',
                                                                                     'name' => 'tr_meliscommerce_products_page_content_tab_main_header',
                                                                                 ),
                                                                                 'forward' => array(
                                                                                     'module' => 'MelisCommerce',
                                                                                     'controller' => 'MelisComProduct',
                                                                                     'action' => 'render-products-page-content-tab-main-header',
                                                                                 ),
                                                                              ),
                                                                         ),
                                                                     ),
                                                                    'meliscommerce_products_page_content_tab_main_header_right' => array(
                                                                        'conf' => array(
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_main_header_right',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_main_header_right',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_main_header_right',
                                                                        ),
                                                                        'forward' => array(
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-main-header-right',
                                                                            
                                                                        ),
                                                                        'interface' => array(
                                                                            'meliscommerce_products_page_content_tab_main_header_switch' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_main_header_switch',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_main_header_switch',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_main_header_switch',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-main-header-switch',
                                                                                ),
                                                                            ),
                                                                        ),
                                                                    ),
                                                                ),
                                                             ),// end product tab main header
                                                             // start product main details container
                                                             'meliscommerce_products_page_content_tab_main_content_container' => array(
                                                                 'conf' => array(
                                                                     'id' => 'id_meliscommerce_products_page_content_tab_main_content_container',
                                                                     'melisKey' => 'meliscommerce_products_page_content_tab_main_content_container',
                                                                     'name' => 'tr_meliscommerce_products_page_content_tab_main_content_container',
                                                                 ),
                                                                 'forward' => array(
                                                                     'module' => 'MelisCommerce',
                                                                     'controller' => 'MelisComProduct',
                                                                     'action' => 'render-products-page-content-tab-main-content-container',
                                                                 ),
                                                                 'interface' => array(
                                                                    'meliscommerce_products_main_tab_left_container' => array(
                                                                        'conf' => array(
                                                                            'id' => 'id_meliscommerce_products_main_tab_left_categories_container',
                                                                            'melisKey' => 'meliscommerce_products_main_tab_left_container',
                                                                            'name' => 'tr_meliscommerce_products_main_tab_left_container',
                                                                        ),
                                                                        'forward' => array(
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-main-tab-left-container',
                                                                        ),
                                                                        'interface' => array(
                                                                            'meliscommerce_products_main_tab_product_reference' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_main_tab_files_container',
                                                                                    'melisKey' => 'meliscommerce_products_main_tab_files_container',
                                                                                    'name' => 'tr_meliscommerce_products_main_tab_files_container',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-info-container',
                                                                                ),
                                                                                'interface' => array(
                                                                                    'meliscommerce_products_main_tab_product_reference_form' => array(
                                                                                        'conf' => array(
                                                                                            'id' => 'id_meliscommerce_products_main_tab_product_reference_form',
                                                                                            'melisKey' => 'meliscommerce_products_main_tab_product_reference_form',
                                                                                            'name' => 'tr_meliscommerce_products_main_tab_product_reference_form',
                                                                                        ),
                                                                                        'forward' => array(
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-product-reference-form',
                                                                                        ),
                                                                                    ),
                                                                                ),
                                                                            ),
                                                                            'meliscommerce_products_main_tab_categories_container' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_main_tab_categories_container',
                                                                                    'melisKey' => 'meliscommerce_products_main_tab_categories_container',
                                                                                    'name' => 'tr_meliscommerce_products_main_tab_categories_container',
                                                                                ),
                                                                                'forward' => array(
                                                                                     'module' => 'MelisCommerce',
                                                                                     'controller' => 'MelisComProduct',
                                                                                     'action' => 'render-products-main-tab-left-child-container',
                                                                                ),
                                                                                'interface' => array(
                                                                                     'meliscommerce_products_main_tab_categories_header' => array(
                                                                                         'conf' => array(
                                                                                             'id' => 'id_meliscommerce_products_main_tab_categories_header',
                                                                                             'melisKey' => 'meliscommerce_products_main_tab_categories_header',
                                                                                             'name' => 'tr_meliscommerce_products_main_tab_categories_header',
                                                                                             'icon' => 'fa fa-tags',
                                                                                         ),
                                                                                         'forward' => array(
                                                                                             'module' => 'MelisCommerce',
                                                                                             'controller' => 'MelisComProduct',
                                                                                             'action' => 'render-products-main-tab-left-child-header',
                                                                                         ),
                                                                                         'interface' => array(
                                                                                            'meliscommerce_products_main_tab_categories_header_all' => array(
                                                                                                'conf' => array(
                                                                                                    'id' => 'id_meliscommerce_products_main_tab_categories_header_all',
                                                                                                    'melisKey' => 'meliscommerce_products_main_tab_categories_header_all',
                                                                                                    'name' => 'tr_meliscommerce_products_main_tab_categories_header_all',
                                                                                                ),
                                                                                                'forward' => array(
                                                                                                    'module' => 'MelisCommerce',
                                                                                                    'controller' => 'MelisComProduct',
                                                                                                    'action' => 'render-products-main-tab-categories-header-all',
                                                                                                    'jscallback' => '',
                                                                                                ),
                                                                                                'interface' => array(
                                                                                                    'meliscommerce_products_main_tab_categories_modal' => array(
                                                                                                        'conf' => array(
                                                                                                            'id' => 'id_meliscommerce_products_main_tab_categories_modal',
                                                                                                            'melisKey' => 'meliscommerce_products_main_tab_categories_modal',
                                                                                                            'name' => 'tr_meliscommerce_products_main_tab_categories_modal',
                                                                                                        ),
                                                                                                        'forward' => array(
                                                                                                            'module' => 'MelisCommerce',
                                                                                                            'controller' => 'MelisComProduct',
                                                                                                            'action' => 'render-products-main-tab-categories-modal',
                                                                                                            'jscallback' => '',
                                                                                                        ),
                                                                                                    ),
                                                                                                )
                                                                                            ),  
                                                                                         ),
                                                                                     ),
                                                                                    'meliscommerce_products_main_tab_categories_conent' => array(
                                                                                        'conf' => array(
                                                                                            'id' => 'id_meliscommerce_products_main_tab_categories_content',
                                                                                            'melisKey' => 'meliscommerce_products_main_tab_categories_content',
                                                                                            'name' => 'tr_meliscommerce_products_main_tab_categories_content',
                                                                                        ),
                                                                                        'forward' => array(
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-main-tab-left-content',
                                                                                        ),
                                                                                        'interface' => array(
                                                                                            'meliscommerce_products_main_tab_categories_list' => array(
                                                                                                'conf' => array(
                                                                                                    'id' => 'id_meliscommerce_products_main_tab_categories_list',
                                                                                                    'melisKey' => 'meliscommerce_products_main_tab_categories_list',
                                                                                                    'name' => 'tr_meliscommerce_products_main_tab_categories_list',
                                                                                                ),
                                                                                                'forward' => array(
                                                                                                    'module' => 'MelisCommerce',
                                                                                                    'controller' => 'MelisComProduct',
                                                                                                    'action' => 'render-products-main-tab-categories-list',
                                                                                                ),
                                                                                            ),
                                                                                        )
                                                                                    ),
                                                                                    
                                                                                ),
                                                                            ),
                                                                            'meliscommerce_products_main_tab_files_container' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_main_tab_files_container',
                                                                                    'melisKey' => 'meliscommerce_products_main_tab_files_container',
                                                                                    'name' => 'tr_meliscommerce_products_main_tab_files_container',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-main-tab-left-child-container',
                                                                                ),
                                                                                'interface' => array(
                                                                                    'meliscommerce_product_main_file_attachments' => array(
                                                                                        'conf' => array(
                                                                                            'type' => 'meliscommerce/interface/meliscommerce_documents_file_attachments_conf',
                                                                                            'docRelationType' => 'product',
                                                                                        )
                                                                                    ),         
                                                                                ),
                                                                            ),
                                                                            'meliscommerce_products_main_tab_attributes_container' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_main_tab_attributes_container',
                                                                                    'melisKey' => 'meliscommerce_products_main_tab_attributes_container',
                                                                                    'name' => 'tr_meliscommerce_products_main_tab_attributes_container',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-main-tab-left-child-container',
                                                                                ),
                                                                                'interface' => array(
                                                                                    'meliscommerce_products_main_tab_attributes_header' => array(
                                                                                        'conf' => array(
                                                                                            'id' => 'id_meliscommerce_products_main_tab_attributes_container',
                                                                                            'melisKey' => 'meliscommerce_products_main_tab_attributes_container',
                                                                                            'name' => 'tr_meliscommerce_products_main_tab_attributes_container',
                                                                                            'icon' => 'fa fa-cubes',
                                                                                        ),
                                                                                        'forward' => array(
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-main-tab-left-child-header',
                                                                                        ),
                                                                                        'interface' => array(
                                                                                            'meliscommerce_products_header_add_attribute_button' => array(
                                                                                                'conf' => array(
                                                                                                    'id' => 'id_meliscommerce_products_header_add_attribute_button',
                                                                                                    'melisKey' => 'meliscommerce_products_header_add_attribute_button',
                                                                                                    'name' => 'tr_meliscommerce_products_main_tab_attributes_content_label',
                                                                                                ),
                                                                                                'forward' => array(
                                                                                                    'module' => 'MelisCommerce',
                                                                                                    'controller' => 'MelisComProduct',
                                                                                                    'action' => 'render-header-add-attribute-button',
                                                                                                ),
                                                                                            ),
                                                                                        ),
                                                                                    ),
                                                                                    'meliscommerce_products_main_tab_attributes_content' => array(
                                                                                        'conf' => array(
                                                                                            'id' => 'id_meliscommerce_products_main_tab_attributes_content',
                                                                                            'melisKey' => 'meliscommerce_products_main_tab_attributes_content',
                                                                                            'name' => 'tr_meliscommerce_products_main_tab_attributes_content',                                                                                           
                                                                                        ),
                                                                                        'forward' => array(
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-main-tab-left-content-no-cont',
                                                                                        ),
                                                                                        'interface' => array(
                                                                                              'meliscommerce_products_main_tab_attributes_add' => array(
                                                                                                  'conf' => array(
                                                                                                      'id' => 'id_meliscommerce_products_main_tab_attributes_add',
                                                                                                      'melisKey' => 'meliscommerce_products_main_tab_attributes_add',
                                                                                                      'name' => 'tr_meliscommerce_products_main_tab_attributes_content',
                                                                                                      'icon' => 'fa fa-cubes',
                                                                                                  ),
                                                                                                  'forward' => array(
                                                                                                      'module' => 'MelisCommerce',
                                                                                                      'controller' => 'MelisComProduct',
                                                                                                      'action' => 'render-products-main-tab-attributes-add',
                                                                                                      'jscallback' => 'initAttribute();',
                                                                                                  ),
                                                                                              ),
                                                                                            'meliscommerce_products_main_tab_attributes_content' => array(
                                                                                                'conf' => array(
                                                                                                    'id' => 'id_meliscommerce_products_main_tab_attributes_content',
                                                                                                    'melisKey' => 'meliscommerce_products_main_tab_attributes_content',
                                                                                                    'name' => 'tr_meliscommerce_products_main_tab_attributes_content',
                                                                                                    'icon' => 'fa fa-cubes',
                                                                                                ),
                                                                                                'forward' => array(
                                                                                                    'module' => 'MelisCommerce',
                                                                                                    'controller' => 'MelisComProduct',
                                                                                                    'action' => 'render-products-main-tab-attributes-content',
                                                                                                    'jscallback' => '',
                                                                                                ),
                                                                                            ),
                                                                                        ),
                                                                                    ),
                                                                                ),
                                                                            ),
                                                                        ),
                                                                    ),
                                                                    'meliscommerce_products_main_tab_right_container' => array(
                                                                        'conf' => array(
                                                                            'id' => 'id_meliscommerce_products_main_tab_right_container',
                                                                            'melisKey' => 'meliscommerce_products_main_tab_right_container',
                                                                            'name' => 'tr_meliscommerce_products_main_tab_right_container',
                                                                        ),
                                                                        'forward' => array(
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-main-tab-left-container',
                                                                        ),
                                                                        'interface' => array(
                                                                            'meliscommerce_product_main_product_imgs' => array(
                                                                                'conf' => array(
                                                                                    'type' => 'meliscommerce/interface/meliscommerce_documents_image_attachments_conf',
                                                                                    'docRelationType' => 'product',
                                                                                )
                                                                            )
                                                                        ),
                                                                     ), 
                                                                 ),
                                                             ),
                                                        ),
                                                    ),
                                                    //End of products MAIN tab
                                                    'meliscommerce_products_page_content_tab_text_container' => array(
                                                        'conf' => array(
                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_container',
                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_container',
                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_container',
                                                        ),
                                                        'forward' => array(
                                                            'module' => 'MelisCommerce',
                                                            'controller' => 'MelisComProduct',
                                                            'action' => 'render-products-page-content-tab-main-container',
                                                        ),
                                                        'interface' => array(
                                                            'meliscommerce_products_page_content_tab_text_header_container' => array(
                                                                'conf' => array(
                                                                    'id' => 'id_meliscommerce_products_page_content_tab_text_header_container',
                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_text_header_container',
                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_text_header_container',
                                                                ),
                                                                'forward' => array(
                                                                    'module' => 'MelisCommerce',
                                                                    'controller' => 'MelisComProduct',
                                                                    'action' => 'render-products-page-content-tab-main-header-container',
                                                                ),
                                                                'interface' => array(
                                                                    'meliscommerce_products_page_content_tab_text_header_left' => array(
                                                                        'conf' => array(
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_header_left',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_header_left',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_header_left',
                                                                        ),
                                                                        'forward' => array(
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-main-header-left',
                                                                        ),
                                                                        'interface' => array(
                                                                            'meliscommerce_products_page_content_tab_text_header' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_text_header',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_text_header',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_text_header',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-main-header',
                                                                                ),
                                                                            ),
                                                                        ),
                                                                    ),
                                                                    'meliscommerce_products_page_content_tab_text_header_right' => array(
                                                                        'conf' => array(
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_header_right',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_header_right',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_header_right',
                                                                        ),
                                                                        'forward' => array(
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-main-header-right',
                                                                        ),
                                                                        'interface' => array(
                                                                            'meliscommerce_products_page_content_tab_main_header_add_button' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_main_header_add_button',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_main_header_add_button',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_main_header_add_button',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-text-header-add-button',
                                                                                ),
                                                                            ),
                                                                            'meliscommerce_products_page_content_tab_product_text_modal_form' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_product_text_modal_form',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_product_text_modal_form',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_product_text_modal_form',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-text-modal-form',
                                                                                ),
                                                                                'interface' => array(
                                                                                    'meliscommerce_products_page_content_tab_product_text_modal_form_product_text' => array(
                                                                                        'conf' => array(
                                                                                            'id' => 'id_meliscommerce_products_page_content_tab_product_text_modal_form',
                                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_product_text_modal_form',
                                                                                            'name' => 'tr_meliscommerce_product_text',
                                                                                        ),
                                                                                        'forward' => array(
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-content-tab-text-modal-form-text',
                                                                                        ),
                                                                                    ),
                                                                                    'meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text' => array(
                                                                                        'conf' => array(
                                                                                            'id' => 'id_meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text',
                                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text',
                                                                                            'name' => 'tr_meliscommerce_products_text_type',
                                                                                        ),
                                                                                        'forward' => array(
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-content-tab-text-modal-form-type-text',
                                                                                            'jscallback' => 'reInitProductTextTypeSelect();',
                                                                                        ),
                                                                                    ),
                                                                                     'meliscommerce_products_page_content_tab_text_product_modal_close' => array(
                                                                                        'conf' => array(
                                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_product_modal_close',
                                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_product_modal_close',
                                                                                            'name' => 'tr_meliscommerce_products_text_close',
                                                                                        ),
                                                                                        'forward' => array(
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-content-tab-text-modal-close',
                                                                                            'jscallback' => 'reInitProductTextTypeSelect();',
                                                                                        ),
                                                                                    ),
                                                                                ),
                                                                            ),
                                                                        ),
                                                                    ),
                                                                ),
                                                            ),                                                            
                                                            'meliscommerce_products_page_content_tab_text_content_container' => array(
                                                                'conf' => array(
                                                                    'id' => 'id_meliscommerce_products_page_content_tab_text_content_container',
                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_text_content_container',
                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_text_content_container',
                                                                ),
                                                                'forward' => array(
                                                                    'module' => 'MelisCommerce',
                                                                    'controller' => 'MelisComProduct',
                                                                    'action' => 'render-products-page-content-tab-text-content-container',
                                                                ),
                                                                'interface' => array(
                                                                    'meliscommerce_products_page_content_tab_text_content_left_container'  => array(
                                                                        'conf' => array(
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_content_left_container',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_content_left_container',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_content_left_container',
                                                                        ),
                                                                        'forward' => array(
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-text-content-left-container',
                                                                        ),
                                                                        'interface' => array(
                                                                            'meliscommerce_products_page_content_tab_text_languages_container' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_text_languages_container',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_text_languages_container',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_text_languages_container',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-text-languages-container',
                                                                                ),
                                                                                'interface' => array(
                                                                                    'meliscommerce_products_page_content_tab_text_language_list' => array(
                                                                                        'conf' => array(
                                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_language_list',
                                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_language_list',
                                                                                            'name' => 'meliscommerce_products_page_content_tab_text_language_list',
                                                                                            'icon' => 'fa fa-times',
                                                                                        ),
                                                                                        'forward' => array(
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-content-tab-text-language',
                                                                                        ),
                                                                                    ),
                                                                                ),
                                                                            ),
                                                                        ),
                                                                    ),
                                                                    'meliscommerce_products_page_content_tab_text_content_right_container' => array(
                                                                        'conf' => array(
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_content_right_container',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_content_right_container',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_content_right_container',                                                                            
                                                                        ),
                                                                        'forward' => array(
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-text-content-right-container',
                                                                        ),
                                                                        'interface' => array(
                                                                            'meliscommerce_products_page_content_tab_text_language_text_field_container' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_text_language_text_field_container',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_text_language_text_field_container',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_text_language_text_field_container',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-text-content-lang-form-cont',
                                                                                ),
                                                                                'interface' => array(
                                                                                    'meliscommerce_products_page_content_tab_text_language_list_field' => array(
                                                                                        'conf' => array(
                                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_language_list_field',
                                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_language_list_field',
                                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_language_list_field',
                                                                                        ),
                                                                                        'forward' => array(
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-content-tab-text-content-language-form',
                                                                                        ),
                                                                                    ),
                                                                                ),
                                                                            ), 
                                                                        ),
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                    ),
                                                    //END of Text Tab
                                                    'meliscommerce_products_page_content_tab_variants_container' => array(
                                                        'conf' => array(
                                                            'id' => 'id_meliscommerce_products_page_content_tab_variants_container',
                                                            'melisKey' => 'meliscommerce_products_page_content_tab_variants_container',
                                                            'name' => 'tr_meliscommerce_products_page_content_tab_variants_container',
                                                        ),
                                                        'forward' => array(
                                                            'module' => 'MelisCommerce',
                                                            'controller' => 'MelisComProduct',
                                                            'action' => 'render-products-page-content-tab-main-container',
                                                        ),
                                                        'interface' => array(
                                                            'meliscommerce_products_page_content_tab_variants_header_container' => array(
                                                                'conf' => array(
                                                                    'id' => 'id_meliscommerce_products_page_content_tab_variants_header_container',
                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_variants_header_container',
                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_variants_header_container',
                                                                ),
                                                                'forward' => array(
                                                                    'module' => 'MelisCommerce',
                                                                    'controller' => 'MelisComProduct',
                                                                    'action' => 'render-products-page-content-tab-main-header-container',
                                                                ),
                                                                'interface' => array(
                                                                    'meliscommerce_products_page_content_tab_variants_header_left' => array(
                                                                        'conf' => array(
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_variants_header_left',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_variants_header_left',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_variants_header_left',
                                                                        ),
                                                                        'forward' => array(
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-main-header-left',
                                                                        ),
                                                                        'interface' => array(
                                                                            'meliscommerce_products_page_content_tab_variants_header' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_variants_header',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_variants_header',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_variants_header',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-main-header',
                                                                                ),
                                                                            ),
                                                                        ),
                                                                    ),
                                                                    'meliscommerce_products_page_content_tab_variants_header_right' => array(
                                                                        'conf' => array(
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_variants_header_right',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_variants_header_right',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_variants_header_right',
                                                                        ),
                                                                        'forward' => array(
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-main-header-right',
                                                                        ),
                                                                        'interface' => array(
                                                                            'meliscommerce_products_page_content_tab_variants_header_add' => array(
                                                                                'conf' => array(
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_variants_header_add',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_variants_header_add',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_variants_header_add',
                                                                                    'icon' => 'fa fa-plus',
                                                                                    'href' => '',
                                                                                ),
                                                                                'forward' => array(
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-variants-header-add',
                                                                                ),
                                                                            ),
                                                                        ),
                                                                    ),
                                                                ),
                                                            ),                                                            
                                                            'meliscommerce_products_page_content_tab_variant_content_container' => array(
                                                                'conf' => array(
                                                                    'id' => 'id_meliscommerce_products_page_content_tab_variant_content_container',
                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_variant_content_container',
                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_variant_content_container',
                                                                ),
                                                                'forward' => array(
                                                                    'module' => 'MelisCommerce',
                                                                    'controller' => 'MelisComVariantList',
                                                                    'action' => 'render-products-page-content-tab-variant-content-container',
                                                                ),
                                                                'interface' => array(
                                                                      
                                                                ),
                                                            ),
                                                        ),
                                                    ),
                                                    //End of variants tab
                                                    'meliscommerce_products_page_content_tab_seo_container' => array(
                                                        'conf' => array(
                                                            'id' => 'id_meliscommerce_products_page_content_tab_seo_container',
                                                            'melisKey' => 'meliscommerce_products_page_content_tab_seo_container',
                                                            'name' => 'tr_meliscommerce_products_page_content_tab_seo_container',
                                                         ),
                                                         'forward' => array(
                                                            'module' => 'MelisCommerce',
                                                            'controller' => 'MelisComProduct',
                                                            'action' => 'render-products-page-content-tab-main-container',
                                                         ),
                                                        'interface' => array(
                                                            'meliscommerce_products_page_seo_form' => array(
                                                                'conf' => array(
                                                                    'type' => 'meliscommerce/interface/meliscommerce_seo_conf',
                                                                    'formType' => 'product',
                                                                )
                                                            )
                                                        ),
                                                     ),
                                                     // End of SEO tab
                                                     'meliscommerce_products_page_content_tab_price_container' => array(
                                                         'conf' => array(
                                                             'type' => 'meliscommerce/interface/meliscommerce_prices_tab_content',
                                                         ),
                                                     ),
                                                     // End of Price tab
                                                 ),
                                             ),
                                        ),
                                    ), 
                                ),
                            ),
                             
                      ),
                  ),  
            ),
        ),
    ),
);