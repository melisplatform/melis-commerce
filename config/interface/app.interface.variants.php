<?php 

return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_variants_Variants',
                'rightsDisplay' => 'none',
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/variant.tool.js',
                    '/MelisCommerce/js/tools/seo.tool.js',
                ),
                'css' => array(
            
                ),
            ),
            'datas' => array(
            
            ),
            'interface' => array(
                'meliscommerce_variants' => array(                     
                    'interface' => array(
//                         'meliscommerce_variants_leftmenu' => array(
//                             'conf' => array(
//                                 'id' => 'id_meliscommerce_variants_page',
//                                 'melisKey' => 'meliscommerce_variants_page',
//                                 'name' => 'tr_meliscommerce_varaints',
//                                 'icon' => 'fa fa-user',
//                             ),
//                         ),
                        'meliscommerce_variants_page' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_variants_page',
                                'melisKey' => 'meliscommerce_variants_page',
                                'name' => 'tr_meliscommerce_variants_page',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComVariant',
                                'action' => 'render-variant-page',
                            ),
                            'interface' => array(
                                'meliscommerce_variant_header' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_variant_header',
                                        'melisKey' => 'meliscommerce_variant_header',
                                        'name' => 'tr_meliscommerce_variant_header header',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComVariant',
                                        'action' => 'render-variant-header',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_variant_header_left' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_variant_header_left',
                                                'melisKey' => 'meliscommerce_variant_header_left',
                                                'name' => 'tr_meliscommerce_variant_header_left',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComVariant',
                                                'action' => 'render-variant-header-left',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_variant_header_heading' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_variant_header_heading',
                                                        'melisKey' => 'meliscommerce_variant_header_heading',
                                                        'name' => 'tr_meliscommerce_variant_header_heading header',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-header-heading',
                                                    ),
                                                ),
                                            ),
                                        ),                                        
                                        'meliscommerce_variant_header_right' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_variant_header_right',
                                                'melisKey' => 'meliscommerce_variant_header_right',
                                                'name' => 'tr_meliscommerce_variant_header_right',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComVariant',
                                                'action' => 'render-variant-header-right',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_variant_header_save' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_variant_header_save',
                                                        'melisKey' => 'meliscommerce_variant_header_save',
                                                        'name' => 'tr_meliscommerce_variant_header_save',
                                                        'icon' => 'fa fa-save',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-header-save',
                                                    ),
                                                ),                                                                                                
                                            ),
                                        ),
                                    ),                                    
                                ),
                                //end of header container
                                'meliscommerce_variant_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_variant_content',
                                        'melisKey' => 'meliscommerce_variant_content',
                                        'name' => 'tr_meliscommerce_variant_content',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComVariant',
                                        'action' => 'render-variant-content',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_variant_tab_head' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_variant_tab_head',
                                                'melisKey' => 'meliscommerce_variant_tab_head',
                                                'name' => 'tr_meliscommerce_variant_tab_head',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComVariant',
                                                'action' => 'render-variant-tab-head',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_variant_tab_main' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_variant_tab_main',
                                                        'melisKey' => 'meliscommerce_variant_tab_main',
                                                        'name' => 'tr_meliscommerce_variant_tab_main',
                                                        'icon' => 'glyphicons tag',
                                                        'active' => 'active',
                                                        'href' => 'id_meliscommerce_variant_tab_content_main',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab',
                                                    ),
                                                ),
//                                                 'meliscommerce_variant_tab_text' => array(
//                                                     'conf' => array(
//                                                         'id' => 'id_meliscommerce_variant_tab_text',
//                                                         'melisKey' => 'meliscommerce_variant_tab_text',
//                                                         'name' => 'tr_meliscommerce_variant_tab_text',
//                                                         'icon' => 'icon-paper-documents',
//                                                         'href' => 'id_meliscommerce_variant_tab_content_text',
//                                                     ),
//                                                     'forward' => array(
//                                                         'module' => 'MelisCommerce',
//                                                         'controller' => 'MelisComVariant',
//                                                         'action' => 'render-variant-tab',
//                                                     ),
//                                                 ),
                                                'meliscommerce_variant_tab_seo' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_variant_tab_seo',
                                                        'melisKey' => 'meliscommerce_variant_tab_seo',
                                                        'name' => 'tr_meliscommerce_seo_Seo',
                                                        'icon' => 'glyphicons search',
                                                        'href' => 'id_meliscommerce_variant_tab_content_seo',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab',
                                                    ),
                                                ),
                                                'meliscommerce_variant_tab_prices' => array(
                                                    'conf' => array(
                                                        'type' => 'meliscommerce/interface/meliscommerce_prices_tab',
                                                    ),                                                    
                                                ),
                                                'meliscommerce_variant_tab_stocks' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_variant_tab_stocks',
                                                        'melisKey' => 'meliscommerce_variant_tab_stocks',
                                                        'name' => 'tr_meliscommerce_variant_tab_stocks',
                                                        'icon' => 'glyphicons stats',
                                                        'href' => 'id_meliscommerce_variant_tab_content_stocks',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab',
                                                    ),
                                                ),
                                                'meliscommerce_variant_tab_assoc_var' => array(
                                                    'conf' => array(
                                                        'type' => 'meliscommerce/interface/meliscommerce_avar_tab',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'meliscommerce_variant_tab_contents_container' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_variant_tab_contents_container',
                                                'melisKey' => 'meliscommerce_variant_tab_contents_container',
                                                'name' => 'tr_meliscommerce_variant_tab_contents_container',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComVariant',
                                                'action' => 'render-variant-tab-contents-container',
                                            ),
                                            'interface' => array(
                                                'meliscommerce_variant_tab_content_main' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_variant_tab_content_main',
                                                        'melisKey' => 'meliscommerce_variant_tab_content_main',
                                                        'name' => 'tr_meliscommerce_variant_tab_content_main',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab-content',
                                                        'active' => 'active',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_variant_tab_main_header_container' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_variant_tab_main_header_container',
                                                                'melisKey' => 'meliscommerce_variant_tab_main_header_container',
                                                                'name' => 'tr_meliscommerce_variant_tab_main_header_container',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComVariant',
                                                                'action' => 'render-variant-tab-content-header-container',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_variant_tab_main_header_left' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_variant_tab_main_header_left',
                                                                        'melisKey' => 'meliscommerce_variant_tab_main_header_left',
                                                                        'name' => 'tr_meliscommerce_variant_tab_main_header_left',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-content-header-left',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_variant_tab_main_header' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_variant_tab_main_header',
                                                                                'melisKey' => 'meliscommerce_variant_tab_main_header',
                                                                                'name' => 'tr_meliscommerce_variant_tab_main_header',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-content-header',
                                                                            ),
                                                                        ),
                                                                    ),
                                                                ),
                                                                'meliscommerce_variant_tab_main_header_right' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_variant_tab_main_header_left',
                                                                        'melisKey' => 'meliscommerce_variant_tab_main_header_left',
                                                                        'name' => 'tr_meliscommerce_variant_tab_main_header_left',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-content-header-right',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_variant_tab_main_status_switch' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_variant_tab_main_status_switch',
                                                                                'melisKey' => 'meliscommerce_variant_tab_main_status_switch',
                                                                                'name' => 'tr_meliscommerce_variant_tab_main_status_switch',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-main-header-switch',
                                                                            ),
                                                                        ),
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                        'meliscommerce_variant_tab_main_contents' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_variant_tab_main_contents',
                                                                'melisKey' => 'meliscommerce_variant_tab_main_contents',
                                                                'name' => 'tr_meliscommerce_variant_tab_main_contents',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComVariant',
                                                                'action' => 'render-variant-tab-content-general-container',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_variant_tab_main_left_content' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_variant_tab_main_left_content',
                                                                        'melisKey' => 'meliscommerce_variant_tab_main_left_content',
                                                                        'name' => 'tr_meliscommerce_variant_tab_main_left_content',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-main-sub-content',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_variant_main_information' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_variant_main_information',
                                                                                'melisKey' => 'meliscommerce_variant_main_information',
                                                                                'name' => 'tr_meliscommerce_variant_main_information',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-sub-container',
                                                                            ),
                                                                            'interface' => array(
                                                                                'meliscommerce_variant_main_information_heading' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_variant_main_information_heading',
                                                                                        'melisKey' => 'meliscommerce_variant_main_information_heading',
                                                                                        'name' => 'tr_meliscommerce_variant_main_information_heading',
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-heading',
                                                                                    ),
                                                                                    'interface' => array(
                                                                                        'meliscommerce_variant_main_information_header' => array(
                                                                                            'conf' => array(
                                                                                                'id' => 'id_meliscommerce_variant_main_information_header',
                                                                                                'melisKey' => 'meliscommerce_variant_main_information_header',
                                                                                                'name' => 'tr_meliscommerce_variant_main_information_header',
                                                                                                'icon' => 'fa fa-cog',
                                                                                            ),
                                                                                            'forward' => array(
                                                                                                'module' => 'MelisCommerce',
                                                                                                'controller' => 'MelisComVariant',
                                                                                                'action' => 'render-variant-tab-sub-header',
                                                                                            ),
                                                                                        ),
                                                                                    ),
                                                                                ),
                                                                                'meliscommerce_variant_main_information_content_container' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_variant_main_information_content_container',
                                                                                        'melisKey' => 'meliscommerce_variant_main_information_content_container',
                                                                                        'name' => 'tr_meliscommerce_variant_main_information_content_container',
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-content',
                                                                                    ),
                                                                                    'interface' => array(
                                                                                        'meliscommerce_variant_main_infromation_content' => array(
                                                                                            'conf' => array(
                                                                                                'id' => 'id_meliscommerce_variant_main_infromation_content',
                                                                                                'melisKey' => 'meliscommerce_variant_main_infromation_content',
                                                                                                'name' => 'tr_meliscommerce_variant_main_infromation_content',
                                                                                            ),
                                                                                            'forward' => array(
                                                                                                'module' => 'MelisCommerce',
                                                                                                'controller' => 'MelisComVariant',
                                                                                                'action' => 'render-variant-tab-main-information-content',
                                                                                                'jscallback' => ''
                                                                                            ),
                                                                                        ),
                                                                                    ),
                                                                                ),
                                                                            ),
                                                                        ),
                                                                        'meliscommerce_variant_main_files' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_variant_main_information',
                                                                                'melisKey' => 'meliscommerce_variant_main_information',
                                                                                'name' => 'tr_meliscommerce_variant_main_information',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-sub-container',
                                                                            ),
                                                                            'interface' => array(
                                                                                'meliscommerce_variant_main_file_attachments' => array(
                                                                                    'conf' => array(
                                                                                        'type' => 'meliscommerce/interface/meliscommerce_documents_file_attachments_conf',
                                                                                        'docRelationType' => 'variant',
                                                                                    )
                                                                                )
                                                                            ),
                                                                        ),
                                                                        'meliscommerce_variant_main_attributes' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_variant_main_attributes',
                                                                                'melisKey' => 'meliscommerce_variant_main_attributes',
                                                                                'name' => 'tr_meliscommerce_variant_main_attributes',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-sub-container',
                                                                            ),
                                                                            'interface' => array(
                                                                                'meliscommerce_variant_main_attributes_heading' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_variant_main_attributes_heading',
                                                                                        'melisKey' => 'meliscommerce_variant_main_attributes_heading',
                                                                                        'name' => 'tr_meliscommerce_variant_main_attributes_heading',
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-heading',
                                                                                    ),
                                                                                    'interface' => array(
                                                                                        'meliscommerce_variant_main_attributes_header' => array(
                                                                                            'conf' => array(
                                                                                                'id' => 'id_meliscommerce_variant_main_attributes_header',
                                                                                                'melisKey' => 'meliscommerce_variant_main_attributes_header',
                                                                                                'name' => 'tr_meliscommerce_variant_main_attributes_header',
                                                                                                'icon' => 'fa fa-cubes',
                                                                                            ),
                                                                                            'forward' => array(
                                                                                                'module' => 'MelisCommerce',
                                                                                                'controller' => 'MelisComVariant',
                                                                                                'action' => 'render-variant-tab-sub-header',
                                                                                            ),
                                                                                        ),
                                                                                    ),
                                                                                ),
                                                                                'meliscommerce_variant_main_attributes_content_container' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_variant_main_attributes_content_container',
                                                                                        'melisKey' => 'meliscommerce_variant_main_attributes_content_container',
                                                                                        'name' => 'tr_meliscommerce_variant_main_attributes_content_container',
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-content',
                                                                                    ),
                                                                                    'interface' => array(
                                                                                        'meliscommerce_variant_main_attributes_content' => array(
                                                                                            'conf' => array(
                                                                                                'id' => 'id_meliscommerce_variant_main_attributes_content',
                                                                                                'melisKey' => 'meliscommerce_variant_main_attributes_content',
                                                                                                'name' => 'tr_meliscommerce_variant_main_attributes_content',
                                                                                            ),
                                                                                            'forward' => array(
                                                                                                'module' => 'MelisCommerce',
                                                                                                'controller' => 'MelisComVariant',
                                                                                                'action' => 'render-variant-tab-main-attributes-content',
                                                                                            ),
                                                                                        ),
                                                                                    ),
                                                                                ),                                                                                
                                                                            ),
                                                                        ),                                                                        
                                                                    ),
                                                                ),
                                                                'meliscommerce_variant_tab_main_right_content' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_variant_tab_main_right_content',
                                                                        'melisKey' => 'meliscommerce_variant_tab_main_right_content',
                                                                        'name' => 'tr_meliscommerce_variant_tab_main_right_content',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-main-sub-content',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_variant_main_product_imgs' => array(
                                                                            'conf' => array(
                                                                                'type' => 'meliscommerce/interface/meliscommerce_documents_image_attachments_conf',
                                                                                'docRelationType' => 'variant',
                                                                            )
                                                                        )
                                                                    ), // end
                                                                ),
                                                            ),
                                                        ),
                                                    ),
                                                ),// end of main tab contents //
                                                'meliscommerce_variant_tab_content_seo' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_variant_tab_content_seo',
                                                        'melisKey' => 'meliscommerce_variant_tab_content_seo',
                                                        'name' => 'tr_meliscommerce_variant_tab_content_seo',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab-content',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_varian_seo_form' => array(
                                                            'conf' => array(
                                                                'type' => 'meliscommerce/interface/meliscommerce_seo_conf',
                                                                'formType' => 'variant',
                                                            )
                                                        )
                                                    )
                                                ),
                                                'meliscommerce_variant_tab_content_prices' => array(
                                                    'conf' => array(
                                                        'type' => 'meliscommerce/interface/meliscommerce_prices_tab_content',
                                                    ),                                                    
                                                ),
                                                'meliscommerce_variant_tab_content_avar' => array(
                                                    'conf' => array(
                                                        'type' => 'meliscommerce/interface/meliscommerce_avar_tab_content',
                                                    ),
                                                ),
                                                // end of price tab content.
                                                'meliscommerce_variant_tab_content_stocks' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscommerce_variant_tab_content_stocks',
                                                        'melisKey' => 'meliscommerce_variant_tab_content_stocks',
                                                        'name' => 'tr_meliscommerce_variant_tab_content_stocks',
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCommerce',
                                                        'controller' => 'MelisComVariant',
                                                        'action' => 'render-variant-tab-content',
                                                    ),
                                                    'interface' => array(
                                                        'meliscommerce_variant_tab_stocks_header_container' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_variant_tab_stocks_header_container',
                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_header_container',
                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_header_container',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComVariant',
                                                                'action' => 'render-variant-tab-content-header-container',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_variant_tab_stocks_header_left' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_header_left',
                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_header_left',
                                                                        'name' => 'tr_meliscommerce_variant_tab_stocks_header_left',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-content-header-left',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_variant_tab_stocks_header' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_header',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_header',
                                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_header',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-content-header',
                                                                            ),
                                                                        ),
                                                                    ),
                                                                ),
                                                                'meliscommerce_variant_tab_stocks_header_right' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_header_right',
                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_header_right',
                                                                        'name' => 'tr_meliscommerce_variant_tab_stocks_header_right',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-content-header-right',
                                                                    ),
                                                                    'interface' => array(
//                                                                         'meliscommerce_variant_tab_stocks_header_add' => array(
//                                                                             'conf' => array(
//                                                                                 'id' => 'id_meliscommerce_variant_tab_stocks_header_add',
//                                                                                 'melisKey' => 'meliscommerce_variant_tab_stocks_header_add',
//                                                                                 'name' => 'tr_meliscommerce_variant_tab_prices_header_add',
//                                                                                 'icon' => 'fa fa-globe',
//                                                                             ),
//                                                                             'forward' => array(
//                                                                                 'module' => 'MelisCommerce',
//                                                                                 'controller' => 'MelisComVariant',
//                                                                                 'action' => 'render-variant-tab-stocks-header-add',
//                                                                             ),
//                                                                         ),
                                                                    ),
                                                                ),
                                                            ),
                                                        ),
                                                        'meliscommerce_variant_tab_stocks_contents' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscommerce_variant_tab_stocks_contents',
                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_contents',
                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_contents',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCommerce',
                                                                'controller' => 'MelisComVariant',
                                                                'action' => 'render-variant-tab-content-general-container',
                                                            ),
                                                            'interface' => array(
                                                                'meliscommerce_variant_tab_stocks_left_content' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_left_content',
                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_left_content',
                                                                        'name' => 'tr_meliscommerce_variant_tab_stocks_left_content',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-prices-content-left-container',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_variant_tab_stocks_country_heading' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_country_heading',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_country_heading',
                                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_country_heading',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-sub-heading',
                                                                            ),
                                                                            'interface' => array(
                                                                                'meliscommerce_variant_tab_stocks_country_header' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_country_header',
                                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_country_header',
                                                                                        'name' => 'tr_meliscommerce_variant_tab_prices_country_header',
                                                                                        'icon' => 'fa fa-globe',
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-header',
                                                                                    ),
                                                                                ),
                                                                            ),
                                                                        ),
                                                                        'meliscommerce_variant_tab_stocks_country_list' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_country_list',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_country_list',
                                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_country_list',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-stocks-country-list',
                                                                            ),
                                                                        ),
                                                                    ),
                                                                ),
                                                                'meliscommerce_variant_tab_stocks_right_content' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_right_content',
                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_right_content',
                                                                        'name' => 'tr_meliscommerce_variant_tab_stocks_right_content',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCommerce',
                                                                        'controller' => 'MelisComVariant',
                                                                        'action' => 'render-variant-tab-prices-content-right-container',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscommerce_variant_tab_stocks_country_form_heading' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_country_form_heading',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_country_form_heading',
                                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_country_form_heading',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-sub-heading',
                                                                            ),
                                                                            'interface' => array(
                                                                                'meliscommerce_variant_tab_stocks_country_header' => array(
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscommerce_variant_tab_stocks_country_header',
                                                                                        'melisKey' => 'meliscommerce_variant_tab_stocks_country_header',
                                                                                        'name' => 'tr_meliscommerce_variant_tab_prices_country_header_general',
                                                                                        'icon' => 'fa fa-cubes',
                                                                                        'class' => 'country-stock-label',
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCommerce',
                                                                                        'controller' => 'MelisComVariant',
                                                                                        'action' => 'render-variant-tab-sub-header',
                                                                                    ),
                                                                                ),
                                                                            ),
                                                                        ),
                                                                        'meliscommerce_variant_tab_stocks_country_form' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscommerce_variant_tab_stocks_country_form',
                                                                                'melisKey' => 'meliscommerce_variant_tab_stocks_country_form',
                                                                                'name' => 'tr_meliscommerce_variant_tab_stocks_country_form',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCommerce',
                                                                                'controller' => 'MelisComVariant',
                                                                                'action' => 'render-variant-tab-stocks-country-form',
                                                                                'jscallback' => 'variantLoaded();',
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
                                ),
                            ),
                        ),// end of variants page                        
                    ),
                ),            
            ),
        ),
    ),
);