<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceAttributesShowPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceProduct/show-attributes'),
                        'id' => 'productAttributes',
                        // Id of the attribute assigned to the product
                        'm_product_id' => null,
                        // Attribute values id pre-select
                        'm_selected_attr' => array(),
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
                        'name' => 'tr_meliscommerce_plugin_attribute_show_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceAttributesShowPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_attribute_show_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                            ),
                            'js' => array(
                            ),
                        ),
                        'modal_form' => array(
                            'melis_commerce_plugin_login_config' => array(
                                'tab_title' => 'tr_front_plugin_common_tab_properties',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => array(
                                                'label' => 'tr_melis_Plugins_Template',
                                                'tooltip' => 'tr_melis_Plugins_Template tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_product_id',
                                            'type' => 'MelisText',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_products_plugins_prd_id',
                                                'tooltip' => 'tr_meliscommerce_products_plugins_prd_id tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_product_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
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
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            )
                        )
                    )
                ),
            ),
        ),
     ),
);