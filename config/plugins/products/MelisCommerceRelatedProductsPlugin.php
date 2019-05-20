<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceRelatedProductsPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceProduct/related-products'),
                        'id' => 'productRelatedPlugin',
                        // product id
                        'm_product_id' => NULl,
                    ),
                    'melis' => array(
                        /*
                        * if set this plugin will belong to a specific marketplace section,
                        * if not it will go directly to ( Others ) section
                        *  - available section for templating plugins as of 2019-05-16
                        *    - MelisCms
                        *    - MelisMarketing
                        *    - MelisSite
                        *    - MelisCommerce
                        *    - Others
                        *    - CustomProjects
                        */
                        'section' => 'MelisCommerce',
                        'subcategory' => array(
                            'id' => 'PRODUCTS',
                            'title' => 'tr_meliscommerce_products_Products'
                        ),
                        'name' => 'tr_meliscommerce_plugin_related_product_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceRelatedProductsPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_related_product_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                            ),
                            'js' => array(

                            ),
                        ),
                        'js_initialization' => array(),
                        'modal_form' => array(
                            'melis_commerce_plugin_related_products_config' => array(
                                'tab_title' => 'tr_meliscommerce_general_common_configuration',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => array(
                                                'label' => 'tr_melis_Plugins_Template',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_plugin_template_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                                'required' => true,
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_product_id',
                                            'type' => 'EcomPluginProductListSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_related_product_default_product',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'tooltip' => 'tr_meliscommerce_plugin_related_product_default_product_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_product_id',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'template_path' => array(
                                        'name'     => 'template_path',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_front_template_path_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_product_id' => array(
                                        'name'     => 'm_product_id',
                                        'required' => false,
                                        'validators' => array(
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            ),
                        ),
                    )
                ),
            ),
        ),
     ),
);