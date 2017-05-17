<?php 
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'conf' => array(
                'id' => '',
                'name' => 'tr_meliscommerce_avar_title',
                'rightsDisplay' => 'none',                
            ),
            'ressources' => array(
                'js' => array(
                    '/MelisCommerce/js/tools/ecomAssocVar.js',
                ),
                'css' => array(
                    '/MelisCommerce/css/variant-assoc.css',
                ),
            ),
            'datas' => array(),
            'interface' => array(
                'meliscommerce_avar_tab' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_avar_tab',
                        'melisKey' => 'meliscommerce_avar_tab',
                        'name' => 'tr_meliscommerce_avar_title',
                        'icon' => 'glyphicons list',
                        'href' => 'id_meliscommerce_avar_tab_content',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComAssociateVariant',
                        'action' => 'render-tab',
                    ),
                ),
                'meliscommerce_avar_tab_content' => array(
                    'conf' => array(
                        'id' => 'id_meliscommerce_avar_tab_content',
                        'melisKey' => 'meliscommerce_avar_tab_content',
                        'name' => 'tr_meliscommerce_avar_content_title',
                    ),
                    'forward' => array(
                        'module' => 'MelisCommerce',
                        'controller' => 'MelisComAssociateVariant',
                        'action' => 'render-tab-content',
                    ),
                    'interface' => array(
                        'meliscommerce_avar_tab_header_container' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_avar_tab_header_container',
                                'melisKey' => 'meliscommerce_avar_tab_header_container',
                                'name' => 'tr_meliscommerce_avar_content_header',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-header',
                            ),
                            'interface' => array(

                            )
                        ),

                        'meliscommerce_avar_tab_content_container' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_avar_tab_content_container',
                                'melisKey' => 'meliscommerce_avar_tab_content_container',
                                'name' => 'tr_meliscommerce_avar_content_title',
                            ),
                            'forward' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComAssociateVariant',
                                'action' => 'render-tab-content-container',
                            ),
                            'interface' => array(
                                'meliscommerce_avar_tab_assoc_vars_list' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_avar_tab_assoc_vars_list',
                                        'melisKey' => 'meliscommerce_avar_tab_assoc_vars_list',
                                        'name' => 'tr_meliscommerce_avar_title',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAssociateVariant',
                                        'action' => 'render-tab-content-assoc-var-list',
                                    ),
                                ),
                                'meliscommerce_avar_tab_var_lists' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_avar_tab_var_lists',
                                        'melisKey' => 'meliscommerce_avar_tab_var_lists',
                                        'name' => 'tr_meliscommerce_avar_title',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCommerce',
                                        'controller' => 'MelisComAssociateVariant',
                                        'action' => 'render-tab-content-var-list',
                                    ),
                                    'interface' => array(
                                        'meliscommerce_avar_product_variants' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscommerce_avar_product_variants',
                                                'name' => 'tr_meliscommerce_avar_product_variants',
                                                'melisKey' => 'meliscommerce_avar_product_variants',
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCommerce',
                                                'controller' => 'MelisComAssociateVariant',
                                                'action' => 'render-variant-assoc-product-variants',
                                            ),
                                        )
                                    )
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);