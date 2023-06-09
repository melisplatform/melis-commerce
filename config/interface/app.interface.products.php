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
                'name' => 'tr_meliscommerce_products_Products',
                'rightsDisplay' => 'none',
            ],
            'ressources' => [
                'js' => [
                    '/MelisCommerce/js/tools/product.tool.js',
                    '/MelisCommerce/js/tools/seo.tool.js',
                ],
                'css' => [
                ],
            ],
            'datas' => [
            
            ],
            'interface' => [
                  'meliscommerce_product_list' => [
                      'interface' => [
                          'meliscommerce_product_list_leftmenu' => [
                              'conf' => [
                                  'id' => 'id_meliscommerce_product_list_container',
                                  'melisKey' => 'meliscommerce_product_list_container',
                                  'name' => 'tr_meliscommerce_products_Products',
                                  'icon' => 'icon-shippment',
                              ],
                              'interface' => [
                                'meliscommerce_product_list_container' => [
                                    'conf' => [
                                        'type' => 'meliscommerce/interface/meliscommerce_product_list/interface/meliscommerce_product_list_container'
                                    ]
                                ]
                            ]
                          ],
                          'meliscommerce_product_list_container' => [
                              'conf' => [
                                  'id' => 'id_meliscommerce_product_list_container',
                                  'melisKey' => 'meliscommerce_product_list_container',
                                  'name' => 'tr_meliscommerce_products_Products',
                              ],
                              'forward' => [
                                  'module' => 'MelisCommerce',
                                  'controller' => 'MelisComProductList',
                                  'action' => 'render-product-list-page',
                                  'jscallback' => '',
                                  'jsdatas' => []
                              ],
                              'interface' => [
                                  'meliscommerce_product_list_header' => [
                                      'conf' => [
                                          'id' => 'id_meliscommerce_product_list_header',
                                          'melisKey' => 'meliscommerce_product_list_header',
                                          'name' => 'tr_meliscommerce_products_Products_header',
                                      ],
                                      'forward' => [
                                          'module' => 'MelisCommerce',
                                          'controller' => 'MelisComProductList',
                                          'action' => 'render-product-list-header',
                                          'jscallback' => '',
                                          'jsdatas' => []
                                      ],
                                      'interface' => [
                                            'meliscommerce_product_list_header_add' => [
                                                'conf' => [
                                                    'id' => 'id_meliscommerce_product_list_header_add',
                                                    'melisKey' => 'meliscommerce_product_list_header_add',
                                                    'name' => 'tr_meliscommerce_products_Products_header_add',
                                                ],
                                                'forward' => [
                                                    'module' => 'MelisCommerce',
                                                    'controller' => 'MelisComProductList',
                                                    'action' => 'render-product-list-header-add',
                                                    'jscallback' => '',
                                                    'jsdatas' => []
                                                ],
                                            ],
                                      ],
                                  ],
                                  
                                  'meliscommerce_product_list_content' => [
                                      'conf' => [
                                          'id' => 'id_meliscommerce_product_list_content',
                                          'melisKey' => 'meliscommerce_product_list_content',
                                          'name' => 'tr_meliscommerce_products_Products_content',
                                      ],
                                      'forward' => [
                                          'module' => 'MelisCommerce',
                                          'controller' => 'MelisComProductList',
                                          'action' => 'render-product-list-content',
                                          'jscallback' => '',
                                          'jsdatas' => []
                                      ],
                                      'interface' => [

                                      ],
                                  ],
                          
                              ],
                          ],
                      ],
                  ],
                  'meliscommerce_products' => [
                      'interface' => [
                            'meliscommerce_products_leftmenu' => [
                                'conf' => [
                                    'id' => 'id_meliscommerce_products_page',
                                    'melisKey' => 'meliscommerce_products_page',
                                    'name' => 'tr_meliscommerce_products_Products',
                                    'icon' => 'fa fa-dribbble',
                                ],
                            ],
                            'meliscommerce_products_page' => [
                                'conf' => [
                                    'id' => 'id_meliscommerce_products_page',
                                    'melisKey' => 'meliscommerce_products_page',
                                    'name' => 'tr_meliscommerce_products_Products',
                                ],
                                'forward' => [
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComProduct',
                                    'action' => 'render-products-page',
                                ],
                                'interface' => [
                                    'meliscommerce_products_page_header_container' => [
                                        'conf' => [
                                            'id' => 'id_meliscommerce_products_page_header_container',
                                            'melisKey' => 'meliscommerce_products_page_header_container',
                                            'name' => 'tr_meliscommerce_products_page_header_container'
                                        ],
                                        'forward' => [
                                            'module' => 'MelisCommerce',
                                            'controller' => 'MelisComProduct',
                                            'action' => 'render-products-page-header-container',
                                        ],
                                        'interface' => [
                                            'meliscommerce_products_page_header_container_left' => [
                                                'conf' => [
                                                    'id' => 'id_meliscommerce_products_page_header_container_left',
                                                    'melisKey' => 'meliscommerce_products_page_header_container_left',
                                                    'name' => 'tr_meliscommerce_products_page_header_container_left'
                                                ],
                                                'forward' => [
                                                    'module' => 'MelisCommerce',
                                                    'controller' => 'MelisComProduct',
                                                    'action' => 'render-products-page-header-left',
                                                ],
                                                'interface' => [
                                                      'meliscommerce_products_page_header_title' => [
                                                          'conf' => [
                                                              'id' => 'id_meliscommerce_products_page_header_title',
                                                              'melisKey' => 'meliscommerce_products_page_header_title',
                                                              'name' => 'tr_meliscommerce_products_Products'
                                                          ],
                                                          'forward' => [
                                                              'module' => 'MelisCommerce',
                                                              'controller' => 'MelisComProduct',
                                                              'action' => 'render-products-page-header-title',
                                                          ],
                                                      ],
                                                ],
                                            ],
                                            'meliscommerce_products_page_header_container_right' => [
                                                'conf' => [
                                                    'id' => 'id_meliscommerce_products_page_header_container_right',
                                                    'melisKey' => 'meliscommerce_products_page_header_container_right',
                                                    'name' => 'tr_meliscommerce_products_page_header_container_right'
                                                ],
                                                'forward' => [
                                                    'module' => 'MelisCommerce',
                                                    'controller' => 'MelisComProduct',
                                                    'action' => 'render-products-page-header-right',
                                                ],
                                                'interface' => [
                                                    'meliscommerce_products_page_header_save_product' => [
                                                        'conf' => [
                                                            'id' => 'id_meliscommerce_products_page_header_save_product',
                                                            'melisKey' => 'meliscommerce_products_page_header_save_product',
                                                            'name' => 'tr_meliscommerce_products_page_header_save_product',
                                                        ],
                                                        'forward' => [
                                                            'module' => 'MelisCommerce',
                                                            'controller' => 'MelisComProduct',
                                                            'action' => 'render-products-page-header-product-save',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],// END OF PRODUCTS PAGE HEADER
                                    'meliscommerce_products_page_content' => [
                                        'conf' => [
                                            'id' => 'id_meliscommerce_products_page_content',
                                            'melisKey' => 'meliscommerce_products_page_content',
                                            'name' => 'tr_meliscommerce_products_page_content',
                                        ],
                                        'forward' => [
                                            'module' => 'MelisCommerce',
                                            'controller' => 'MelisComProduct',
                                            'action' => 'render-products-page-content',
                                        ],
                                        'interface' => [
                                             'meliscommerce_products_page_content_tabs' => [
                                                 'conf' => [
                                                     'id' => 'id_meliscommerce_products_page_content_tabs',
                                                     'melisKey' => 'meliscommerce_products_page_content_tabs',
                                                     'name' => 'tr_meliscommerce_products_page_content_tabs',
                                                 ],
                                                 'forward' => [
                                                     'module' => 'MelisCommerce',
                                                     'controller' => 'MelisComProduct',
                                                     'action' => 'render-products-page-content-tabs',
                                                 ],
                                                 'interface' => [
                                                     'meliscommerce_products_page_content_tab_main' => [
                                                         'conf' => [
                                                             'id' => 'id_meliscommerce_products_page_content_tab_main',
                                                             'melisKey' => 'meliscommerce_products_page_content_tab_main',
                                                             'name' => 'tr_meliscommerce_products_page_tab_content_main',
                                                             'active' => 'active',
                                                             'href' => 'id_meliscommerce_products_page_content_tab_main_container',
                                                             'icon' => 'glyphicons tag',
                                                         ],
                                                         'forward' => [
                                                             'module' => 'MelisCommerce',
                                                             'controller' => 'MelisComProduct',
                                                             'action' => 'render-products-page-content-generic-tab-head',
                                                         ],
                                                     ],
                                                     'meliscommerce_products_page_content_tab_text' => [
                                                         'conf' => [
                                                             'id' => 'id_meliscommerce_products_page_content_tab_text',
                                                             'melisKey' => 'meliscommerce_products_page_content_tab_text',
                                                             'name' => 'tr_meliscommerce_products_page_content_tab_text',
                                                             'href' => 'id_meliscommerce_products_page_content_tab_text_container',
                                                             'icon' => 'glyphicons pencil',
                                                         ],
                                                         'forward' => [
                                                             'module' => 'MelisCommerce',
                                                             'controller' => 'MelisComProduct',
                                                             'action' => 'render-products-page-content-generic-tab-head',
                                                         ],
                                                     ],
                                                     'meliscommerce_products_page_content_tab_variants' => [
                                                         'conf' => [
                                                             'id' => 'id_meliscommerce_products_page_content_tab_variants',
                                                             'melisKey' => 'meliscommerce_products_page_content_tab_variants',
                                                             'name' => 'tr_meliscommerce_products_page_content_tab_variants',
                                                             'href' => 'id_meliscommerce_products_page_content_tab_variants_container',
                                                             'icon' => 'glyphicons list',
                                                         ],
                                                         'forward' => [
                                                             'module' => 'MelisCommerce',
                                                             'controller' => 'MelisComProduct',
                                                             'action' => 'render-products-page-content-generic-tab-head',
                                                         ],
                                                     ],
                                                     'meliscommerce_products_page_content_tab_seo' => [
                                                         'conf' => [
                                                             'id' => 'id_meliscommerce_products_page_content_tab_seo',
                                                             'melisKey' => 'meliscommerce_products_page_content_tab_seo',
                                                             'name' => 'tr_meliscommerce_products_page_content_tab_seo',
                                                             'href' => 'id_meliscommerce_products_page_content_tab_seo_container',
                                                             'icon' => 'glyphicons search',
                                                         ],
                                                         'forward' => [
                                                             'module' => 'MelisCommerce',
                                                             'controller' => 'MelisComProduct',
                                                             'action' => 'render-products-page-content-generic-tab-head',
                                                         ],
                                                     ],
                                                     'meliscommerce_products_page_content_tab_price' => [
                                                         'conf' => [
                                                            'type' => 'meliscommerce/interface/meliscommerce_prices_tab',
                                                         ],
                                                     ],
                                                 ],
                                             ],// END of meliscommerce_products_page_content_tabs
                                             'meliscommerce_products_page_content_tab_container' => [
                                                 'conf' => [
                                                     'id' => 'id_meliscommerce_products_page_content_tab_container',
                                                     'melisKey' => 'meliscommerce_products_page_content_tab_container',
                                                     'name' => 'tr_meliscommerce_products_page_content_tab_container',
                                                 ],
                                                 'forward' => [
                                                     'module' => 'MelisCommerce',
                                                     'controller' => 'MelisComProduct',
                                                     'action' => 'render-products-page-content-tab-container',
                                                 ],
                                                 'interface' => [
                                                    'meliscommerce_products_page_content_tab_main_container' => [
                                                        'conf' => [
                                                            'id' => 'id_meliscommerce_products_page_content_tab_main_container',
                                                            'melisKey' => 'meliscommerce_products_page_content_tab_main_container',
                                                            'name' => 'tr_meliscommerce_products_page_content_tab_main_container',
                                                            'active' => 'active',
                                                        ],
                                                        'forward' => [
                                                            'module' => 'MelisCommerce',
                                                            'controller' => 'MelisComProduct',
                                                            'action' => 'render-products-page-content-tab-main-container',
                                                        ],
                                                        'interface' => [
                                                            // start Product tab Main Header
                                                            'meliscommerce_products_page_content_tab_main_header_container' => [
                                                                'conf' => [
                                                                    'id' => 'id_meliscommerce_products_page_content_tab_main_header_container',
                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_main_header_container',
                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_main_header_container',
                                                                ],
                                                                'forward' => [
                                                                    'module' => 'MelisCommerce',
                                                                    'controller' => 'MelisComProduct',
                                                                    'action' => 'render-products-page-content-tab-main-header-container',
                                                                ],
                                                                'interface' => [
                                                                     'meliscommerce_products_page_content_tab_main_header_left' => [
                                                                         'conf' => [
                                                                             'id' => 'id_meliscommerce_products_page_content_tab_main_header_left',
                                                                             'melisKey' => 'meliscommerce_products_page_content_tab_main_header_left',
                                                                             'name' => 'tr_meliscommerce_products_page_content_tab_main_header_left',
                                                                         ],
                                                                         'forward' => [
                                                                             'module' => 'MelisCommerce',
                                                                             'controller' => 'MelisComProduct',
                                                                             'action' => 'render-products-page-content-tab-main-header-left',
                                                                             'jscallback' => 'initProductSwitch();'
                                                                         ],
                                                                         'interface' => [
                                                                             'meliscommerce_products_page_content_tab_main_header' => [
                                                                                 'conf' => [
                                                                                     'id' => 'id_meliscommerce_products_page_content_tab_main_header',
                                                                                     'melisKey' => 'meliscommerce_products_page_content_tab_main_header',
                                                                                     'name' => 'tr_meliscommerce_products_page_content_tab_main_header',
                                                                                 ],
                                                                                 'forward' => [
                                                                                     'module' => 'MelisCommerce',
                                                                                     'controller' => 'MelisComProduct',
                                                                                     'action' => 'render-products-page-content-tab-main-header',
                                                                                 ],
                                                                              ],
                                                                         ],
                                                                     ],
                                                                    'meliscommerce_products_page_content_tab_main_header_right' => [
                                                                        'conf' => [
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_main_header_right',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_main_header_right',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_main_header_right',
                                                                        ],
                                                                        'forward' => [
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-main-header-right',
                                                                            
                                                                        ],
                                                                        'interface' => [
                                                                            'meliscommerce_products_page_content_tab_main_header_switch' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_main_header_switch',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_main_header_switch',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_main_header_switch',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-main-header-switch',
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                             ],// end product tab main header
                                                             // start product main details container
                                                             'meliscommerce_products_page_content_tab_main_content_container' => [
                                                                 'conf' => [
                                                                     'id' => 'id_meliscommerce_products_page_content_tab_main_content_container',
                                                                     'melisKey' => 'meliscommerce_products_page_content_tab_main_content_container',
                                                                     'name' => 'tr_meliscommerce_products_page_content_tab_main_content_container',
                                                                 ],
                                                                 'forward' => [
                                                                     'module' => 'MelisCommerce',
                                                                     'controller' => 'MelisComProduct',
                                                                     'action' => 'render-products-page-content-tab-main-content-container',
                                                                 ],
                                                                 'interface' => [
                                                                    'meliscommerce_products_main_tab_left_container' => [
                                                                        'conf' => [
                                                                            'id' => 'id_meliscommerce_products_main_tab_left_categories_container',
                                                                            'melisKey' => 'meliscommerce_products_main_tab_left_container',
                                                                            'name' => 'tr_meliscommerce_products_main_tab_left_container',
                                                                        ],
                                                                        'forward' => [
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-main-tab-left-container',
                                                                        ],
                                                                        'interface' => [
                                                                            'meliscommerce_products_main_tab_product_reference' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_main_tab_files_container',
                                                                                    'melisKey' => 'meliscommerce_products_main_tab_files_container',
                                                                                    'name' => 'tr_meliscommerce_products_main_tab_files_container',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-info-container',
                                                                                ],
                                                                                'interface' => [
                                                                                    'meliscommerce_products_main_tab_product_reference_form' => [
                                                                                        'conf' => [
                                                                                            'id' => 'id_meliscommerce_products_main_tab_product_reference_form',
                                                                                            'melisKey' => 'meliscommerce_products_main_tab_product_reference_form',
                                                                                            'name' => 'tr_meliscommerce_products_main_tab_product_reference_form',
                                                                                        ],
                                                                                        'forward' => [
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-product-reference-form',
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                            'meliscommerce_products_main_tab_categories_container' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_main_tab_categories_container',
                                                                                    'melisKey' => 'meliscommerce_products_main_tab_categories_container',
                                                                                    'name' => 'tr_meliscommerce_products_main_tab_categories_container',
                                                                                ],
                                                                                'forward' => [
                                                                                     'module' => 'MelisCommerce',
                                                                                     'controller' => 'MelisComProduct',
                                                                                     'action' => 'render-products-main-tab-left-child-container',
                                                                                ],
                                                                                'interface' => [
                                                                                     'meliscommerce_products_main_tab_categories_header' => [
                                                                                         'conf' => [
                                                                                             'id' => 'id_meliscommerce_products_main_tab_categories_header',
                                                                                             'melisKey' => 'meliscommerce_products_main_tab_categories_header',
                                                                                             'name' => 'tr_meliscommerce_products_main_tab_categories_header',
                                                                                             'icon' => 'fa fa-tags',
                                                                                         ],
                                                                                         'forward' => [
                                                                                             'module' => 'MelisCommerce',
                                                                                             'controller' => 'MelisComProduct',
                                                                                             'action' => 'render-products-main-tab-left-child-header',
                                                                                         ],
                                                                                         'interface' => [
                                                                                            'meliscommerce_products_main_tab_categories_header_all' => [
                                                                                                'conf' => [
                                                                                                    'id' => 'id_meliscommerce_products_main_tab_categories_header_all',
                                                                                                    'melisKey' => 'meliscommerce_products_main_tab_categories_header_all',
                                                                                                    'name' => 'tr_meliscommerce_products_main_tab_categories_header_all',
                                                                                                ],
                                                                                                'forward' => [
                                                                                                    'module' => 'MelisCommerce',
                                                                                                    'controller' => 'MelisComProduct',
                                                                                                    'action' => 'render-products-main-tab-categories-header-all',
                                                                                                    'jscallback' => '',
                                                                                                ],
                                                                                                'interface' => [
                                                                                                    'meliscommerce_products_main_tab_categories_modal' => [
                                                                                                        'conf' => [
                                                                                                            'id' => 'id_meliscommerce_products_main_tab_categories_modal',
                                                                                                            'melisKey' => 'meliscommerce_products_main_tab_categories_modal',
                                                                                                            'name' => 'tr_meliscommerce_products_main_tab_categories_modal',
                                                                                                        ],
                                                                                                        'forward' => [
                                                                                                            'module' => 'MelisCommerce',
                                                                                                            'controller' => 'MelisComProduct',
                                                                                                            'action' => 'render-products-main-tab-categories-modal',
                                                                                                            'jscallback' => '',
                                                                                                        ],
                                                                                                    ],
                                                                                                ]
                                                                                            ],
                                                                                         ],
                                                                                     ],
                                                                                    'meliscommerce_products_main_tab_categories_conent' => [
                                                                                        'conf' => [
                                                                                            'id' => 'id_meliscommerce_products_main_tab_categories_content',
                                                                                            'melisKey' => 'meliscommerce_products_main_tab_categories_content',
                                                                                            'name' => 'tr_meliscommerce_products_main_tab_categories_content',
                                                                                        ],
                                                                                        'forward' => [
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-main-tab-left-content',
                                                                                        ],
                                                                                        'interface' => [
                                                                                            'meliscommerce_products_main_tab_categories_list' => [
                                                                                                'conf' => [
                                                                                                    'id' => 'id_meliscommerce_products_main_tab_categories_list',
                                                                                                    'melisKey' => 'meliscommerce_products_main_tab_categories_list',
                                                                                                    'name' => 'tr_meliscommerce_products_main_tab_categories_list',
                                                                                                ],
                                                                                                'forward' => [
                                                                                                    'module' => 'MelisCommerce',
                                                                                                    'controller' => 'MelisComProduct',
                                                                                                    'action' => 'render-products-main-tab-categories-list',
                                                                                                ],
                                                                                            ],
                                                                                        ]
                                                                                    ],
                                                                                    
                                                                                ],
                                                                            ],
                                                                            'meliscommerce_products_main_tab_files_container' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_main_tab_files_container',
                                                                                    'melisKey' => 'meliscommerce_products_main_tab_files_container',
                                                                                    'name' => 'tr_meliscommerce_products_main_tab_files_container',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-main-tab-left-child-container',
                                                                                ],
                                                                                'interface' => [
                                                                                    'meliscommerce_product_main_file_attachments' => [
                                                                                        'conf' => [
                                                                                            'type' => 'meliscommerce/interface/meliscommerce_documents_file_attachments_conf',
                                                                                            'docRelationType' => 'product',
                                                                                        ]
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                            'meliscommerce_products_main_tab_attributes_container' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_main_tab_attributes_container',
                                                                                    'melisKey' => 'meliscommerce_products_main_tab_attributes_container',
                                                                                    'name' => 'tr_meliscommerce_products_main_tab_attributes_container',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-main-tab-left-child-container',
                                                                                ],
                                                                                'interface' => [
                                                                                    'meliscommerce_products_main_tab_attributes_header' => [
                                                                                        'conf' => [
                                                                                            'id' => 'id_meliscommerce_products_main_tab_attributes_container',
                                                                                            'melisKey' => 'meliscommerce_products_main_tab_attributes_container',
                                                                                            'name' => 'tr_meliscommerce_products_main_tab_attributes_container',
                                                                                            'icon' => 'fa fa-cubes',
                                                                                        ],
                                                                                        'forward' => [
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-main-tab-left-child-header',
                                                                                        ],
                                                                                        'interface' => [
                                                                                            'meliscommerce_products_header_add_attribute_button' => [
                                                                                                'conf' => [
                                                                                                    'id' => 'id_meliscommerce_products_header_add_attribute_button',
                                                                                                    'melisKey' => 'meliscommerce_products_header_add_attribute_button',
                                                                                                    'name' => 'tr_meliscommerce_products_main_tab_attributes_content_label',
                                                                                                ],
                                                                                                'forward' => [
                                                                                                    'module' => 'MelisCommerce',
                                                                                                    'controller' => 'MelisComProduct',
                                                                                                    'action' => 'render-header-add-attribute-button',
                                                                                                ],
                                                                                            ],
                                                                                        ],
                                                                                    ],
                                                                                    'meliscommerce_products_main_tab_attributes_content' => [
                                                                                        'conf' => [
                                                                                            'id' => 'id_meliscommerce_products_main_tab_attributes_content',
                                                                                            'melisKey' => 'meliscommerce_products_main_tab_attributes_content',
                                                                                            'name' => 'tr_meliscommerce_products_main_tab_attributes_content',                                                                                           
                                                                                        ],
                                                                                        'forward' => [
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-main-tab-left-content-no-cont',
                                                                                        ],
                                                                                        'interface' => [
                                                                                              'meliscommerce_products_main_tab_attributes_add' => [
                                                                                                  'conf' => [
                                                                                                      'id' => 'id_meliscommerce_products_main_tab_attributes_add',
                                                                                                      'melisKey' => 'meliscommerce_products_main_tab_attributes_add',
                                                                                                      'name' => 'tr_meliscommerce_products_main_tab_attributes_content',
                                                                                                      'icon' => 'fa fa-cubes',
                                                                                                  ],
                                                                                                  'forward' => [
                                                                                                      'module' => 'MelisCommerce',
                                                                                                      'controller' => 'MelisComProduct',
                                                                                                      'action' => 'render-products-main-tab-attributes-add',
                                                                                                      'jscallback' => 'initAttribute();',
                                                                                                  ],
                                                                                              ],
                                                                                            'meliscommerce_products_main_tab_attributes_content' => [
                                                                                                'conf' => [
                                                                                                    'id' => 'id_meliscommerce_products_main_tab_attributes_content',
                                                                                                    'melisKey' => 'meliscommerce_products_main_tab_attributes_content',
                                                                                                    'name' => 'tr_meliscommerce_products_main_tab_attributes_content',
                                                                                                    'icon' => 'fa fa-cubes',
                                                                                                ],
                                                                                                'forward' => [
                                                                                                    'module' => 'MelisCommerce',
                                                                                                    'controller' => 'MelisComProduct',
                                                                                                    'action' => 'render-products-main-tab-attributes-content',
                                                                                                    'jscallback' => '',
                                                                                                ],
                                                                                            ],
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                            'meliscommerce_product_main_email_alert' => [
                                                                                'conf' => [
                                                                                    'type' => 'meliscommerce/interface/meliscommerce_settings/interface/meliscommerce_settings_tabs_content_details_general',
                                                                                ]
                                                                            ],
                                                                            'meliscommerce_product_main_tab_page_association' => [
                                                                                'conf' => [
                                                                                    'type' => 'meliscommerce/interface/meliscommerce_page_association/interface/meliscommerce_page_association',
                                                                                ]
                                                                            ],
                                                                        ],
                                                                    ],
                                                                    'meliscommerce_products_main_tab_right_container' => [
                                                                        'conf' => [
                                                                            'id' => 'id_meliscommerce_products_main_tab_right_container',
                                                                            'melisKey' => 'meliscommerce_products_main_tab_right_container',
                                                                            'name' => 'tr_meliscommerce_products_main_tab_right_container',
                                                                        ],
                                                                        'forward' => [
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-main-tab-left-container',
                                                                        ],
                                                                        'interface' => [
                                                                            'meliscommerce_product_main_product_imgs' => [
                                                                                'conf' => [
                                                                                    'type' => 'meliscommerce/interface/meliscommerce_documents_image_attachments_conf',
                                                                                    'docRelationType' => 'product',
                                                                                ]
                                                                            ]
                                                                        ],
                                                                     ],
                                                                 ],
                                                             ],
                                                        ],
                                                    ],
                                                    //End of products MAIN tab
                                                    'meliscommerce_products_page_content_tab_text_container' => [
                                                        'conf' => [
                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_container',
                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_container',
                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_container',
                                                        ],
                                                        'forward' => [
                                                            'module' => 'MelisCommerce',
                                                            'controller' => 'MelisComProduct',
                                                            'action' => 'render-products-page-content-tab-main-container',
                                                        ],
                                                        'interface' => [
                                                            'meliscommerce_products_page_content_tab_text_header_container' => [
                                                                'conf' => [
                                                                    'id' => 'id_meliscommerce_products_page_content_tab_text_header_container',
                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_text_header_container',
                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_text_header_container',
                                                                ],
                                                                'forward' => [
                                                                    'module' => 'MelisCommerce',
                                                                    'controller' => 'MelisComProduct',
                                                                    'action' => 'render-products-page-content-tab-main-header-container',
                                                                ],
                                                                'interface' => [
                                                                    'meliscommerce_products_page_content_tab_text_header_left' => [
                                                                        'conf' => [
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_header_left',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_header_left',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_header_left',
                                                                        ],
                                                                        'forward' => [
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-main-header-left',
                                                                        ],
                                                                        'interface' => [
                                                                            'meliscommerce_products_page_content_tab_text_header' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_text_header',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_text_header',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_text_header',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-main-header',
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                    'meliscommerce_products_page_content_tab_text_header_right' => [
                                                                        'conf' => [
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_header_right',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_header_right',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_header_right',
                                                                        ],
                                                                        'forward' => [
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-main-header-right',
                                                                        ],
                                                                        'interface' => [
                                                                            'meliscommerce_products_page_content_tab_main_header_add_button' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_main_header_add_button',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_main_header_add_button',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_main_header_add_button',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-text-header-add-button',
                                                                                ],
                                                                            ],
                                                                            'meliscommerce_products_page_content_tab_product_text_modal_form' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_product_text_modal_form',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_product_text_modal_form',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_product_text_modal_form',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-text-modal-form',
                                                                                ],
                                                                                'interface' => [
                                                                                    'meliscommerce_products_page_content_tab_product_text_modal_form_product_text' => [
                                                                                        'conf' => [
                                                                                            'id' => 'id_meliscommerce_products_page_content_tab_product_text_modal_form',
                                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_product_text_modal_form',
                                                                                            'name' => 'tr_meliscommerce_product_text',
                                                                                        ],
                                                                                        'forward' => [
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-content-tab-text-modal-form-text',
                                                                                        ],
                                                                                    ],
                                                                                    'meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text' => [
                                                                                        'conf' => [
                                                                                            'id' => 'id_meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text',
                                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text',
                                                                                            'name' => 'tr_meliscommerce_products_text_type',
                                                                                        ],
                                                                                        'forward' => [
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-content-tab-text-modal-form-type-text',
//                                                                                             'jscallback' => 'reInitProductTextTypeSelect();',
                                                                                        ],
                                                                                    ],
                                                                                     'meliscommerce_products_page_content_tab_text_product_modal_close' => [
                                                                                        'conf' => [
                                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_product_modal_close',
                                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_product_modal_close',
                                                                                            'name' => 'tr_meliscommerce_products_text_close',
                                                                                        ],
                                                                                        'forward' => [
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-content-tab-text-modal-close',
//                                                                                             'jscallback' => 'reInitProductTextTypeSelect();',
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                            'meliscommerce_products_page_content_tab_text_content_container' => [
                                                                'conf' => [
                                                                    'id' => 'id_meliscommerce_products_page_content_tab_text_content_container',
                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_text_content_container',
                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_text_content_container',
                                                                ],
                                                                'forward' => [
                                                                    'module' => 'MelisCommerce',
                                                                    'controller' => 'MelisComProduct',
                                                                    'action' => 'render-products-page-content-tab-text-content-container',
                                                                ],
                                                                'interface' => [
                                                                    'meliscommerce_products_page_content_tab_text_content_left_container'  => [
                                                                        'conf' => [
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_content_left_container',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_content_left_container',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_content_left_container',
                                                                        ],
                                                                        'forward' => [
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-text-content-left-container',
                                                                        ],
                                                                        'interface' => [
                                                                            'meliscommerce_products_page_content_tab_text_languages_container' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_text_languages_container',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_text_languages_container',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_text_languages_container',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-text-languages-container',
                                                                                ],
                                                                                'interface' => [
                                                                                    'meliscommerce_products_page_content_tab_text_language_list' => [
                                                                                        'conf' => [
                                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_language_list',
                                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_language_list',
                                                                                            'name' => 'meliscommerce_products_page_content_tab_text_language_list',
                                                                                            'icon' => 'fa fa-times',
                                                                                        ],
                                                                                        'forward' => [
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-content-tab-text-language',
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                    'meliscommerce_products_page_content_tab_text_content_right_container' => [
                                                                        'conf' => [
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_content_right_container',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_content_right_container',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_content_right_container',                                                                            
                                                                        ],
                                                                        'forward' => [
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-text-content-right-container',
                                                                        ],
                                                                        'interface' => [
                                                                            'meliscommerce_products_page_content_tab_text_language_text_field_container' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_text_language_text_field_container',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_text_language_text_field_container',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_text_language_text_field_container',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-text-content-lang-form-cont',
                                                                                ],
                                                                                'interface' => [
                                                                                    'meliscommerce_products_page_content_tab_text_language_list_field' => [
                                                                                        'conf' => [
                                                                                            'id' => 'id_meliscommerce_products_page_content_tab_text_language_list_field',
                                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_text_language_list_field',
                                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_text_language_list_field',
                                                                                        ],
                                                                                        'forward' => [
                                                                                            'module' => 'MelisCommerce',
                                                                                            'controller' => 'MelisComProduct',
                                                                                            'action' => 'render-products-page-content-tab-text-content-language-form',
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
                                                    //END of Text Tab
                                                    'meliscommerce_products_page_content_tab_variants_container' => [
                                                        'conf' => [
                                                            'id' => 'id_meliscommerce_products_page_content_tab_variants_container',
                                                            'melisKey' => 'meliscommerce_products_page_content_tab_variants_container',
                                                            'name' => 'tr_meliscommerce_products_page_content_tab_variants_container',
                                                        ],
                                                        'forward' => [
                                                            'module' => 'MelisCommerce',
                                                            'controller' => 'MelisComProduct',
                                                            'action' => 'render-products-page-content-tab-main-container',
                                                        ],
                                                        'interface' => [
                                                            'meliscommerce_products_page_content_tab_variants_header_container' => [
                                                                'conf' => [
                                                                    'id' => 'id_meliscommerce_products_page_content_tab_variants_header_container',
                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_variants_header_container',
                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_variants_header_container',
                                                                ],
                                                                'forward' => [
                                                                    'module' => 'MelisCommerce',
                                                                    'controller' => 'MelisComProduct',
                                                                    'action' => 'render-products-page-content-tab-main-header-container',
                                                                ],
                                                                'interface' => [
                                                                    'meliscommerce_products_page_content_tab_variants_header_left' => [
                                                                        'conf' => [
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_variants_header_left',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_variants_header_left',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_variants_header_left',
                                                                        ],
                                                                        'forward' => [
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-main-header-left',
                                                                        ],
                                                                        'interface' => [
                                                                            'meliscommerce_products_page_content_tab_variants_header' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_variants_header',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_variants_header',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_variants_header',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-main-header',
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                    'meliscommerce_products_page_content_tab_variants_header_right' => [
                                                                        'conf' => [
                                                                            'id' => 'id_meliscommerce_products_page_content_tab_variants_header_right',
                                                                            'melisKey' => 'meliscommerce_products_page_content_tab_variants_header_right',
                                                                            'name' => 'tr_meliscommerce_products_page_content_tab_variants_header_right',
                                                                        ],
                                                                        'forward' => [
                                                                            'module' => 'MelisCommerce',
                                                                            'controller' => 'MelisComProduct',
                                                                            'action' => 'render-products-page-content-tab-main-header-right',
                                                                        ],
                                                                        'interface' => [
                                                                            'meliscommerce_products_page_content_tab_variants_header_add' => [
                                                                                'conf' => [
                                                                                    'id' => 'id_meliscommerce_products_page_content_tab_variants_header_add',
                                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_variants_header_add',
                                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_variants_header_add',
                                                                                    'icon' => 'fa fa-plus',
                                                                                    'href' => '',
                                                                                ],
                                                                                'forward' => [
                                                                                    'module' => 'MelisCommerce',
                                                                                    'controller' => 'MelisComProduct',
                                                                                    'action' => 'render-products-page-content-tab-variants-header-add',
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                            'meliscommerce_products_page_content_tab_variant_content_container' => [
                                                                'conf' => [
                                                                    'id' => 'id_meliscommerce_products_page_content_tab_variant_content_container',
                                                                    'melisKey' => 'meliscommerce_products_page_content_tab_variant_content_container',
                                                                    'name' => 'tr_meliscommerce_products_page_content_tab_variant_content_container',
                                                                ],
                                                                'forward' => [
                                                                    'module' => 'MelisCommerce',
                                                                    'controller' => 'MelisComVariantList',
                                                                    'action' => 'render-products-page-content-tab-variant-content-container',
                                                                ],
                                                                'interface' => [
                                                                      
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                    //End of variants tab
                                                    'meliscommerce_products_page_content_tab_seo_container' => [
                                                        'conf' => [
                                                            'id' => 'id_meliscommerce_products_page_content_tab_seo_container',
                                                            'melisKey' => 'meliscommerce_products_page_content_tab_seo_container',
                                                            'name' => 'tr_meliscommerce_products_page_content_tab_seo_container',
                                                         ],
                                                         'forward' => [
                                                            'module' => 'MelisCommerce',
                                                            'controller' => 'MelisComProduct',
                                                            'action' => 'render-products-page-content-tab-main-container',
                                                         ],
                                                        'interface' => [
                                                            'meliscommerce_products_page_seo_form' => [
                                                                'conf' => [
                                                                    'type' => 'meliscommerce/interface/meliscommerce_seo_conf',
                                                                    'formType' => 'product',
                                                                ]
                                                            ]
                                                        ],
                                                     ],
                                                     // End of SEO tab
                                                     'meliscommerce_products_page_content_tab_price_container' => [
                                                         'conf' => [
                                                             'type' => 'meliscommerce/interface/meliscommerce_prices_tab_content',
                                                         ],
                                                     ],
                                                     // End of Price tab
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
];