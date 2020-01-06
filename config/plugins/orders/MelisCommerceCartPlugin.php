<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCartPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceCart/cart'),
                        'id' => 'cartPlugin',
                        // Country id
                        'cart_country_id' => null,
                        // pagination config
                        'pagination' => array(
                            'cart_current' => 1,
                            'cart_per_page' => 10,
                            'cart_nb_page_before_after' => 2,
                        ),
                        // Cart item deletion
                        'cart_variant_remove' => null
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
                            'id' => 'CART',
                            'title' => 'tr_meliscommerce_car_Cart'
                        ),
                        'name' => '\tr_meliscommerce_plugin_cart_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCartPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_cart_description',
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
                            'melis_commerce_plugin_cart_config' => array(
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
                                )
                            ),
                            'melis_commerce_plugin_cart_pagination_config' => array(
                                'tab_title' => 'tr_meliscommerce_general_common_pagination',
                                'tab_icon'  => 'fa fa-forward',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'attributes' => array(
                                    'name' => 'melis_commerce_plugin_cart_pagination_config',
                                    'id' => 'melis_commerce_plugin_cart_pagination_config',
                                    'method' => '',
                                    'action' => '',
                                ),
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'cart_per_page',
                                            'type' => 'MelisText',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_per_page',
                                                'tooltip' => 'tr_meliscommerce_general_common_per_page tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'cart_per_page',
                                                'class' => 'form-control',
                                                'placeholder' => 'tr_meliscommerce_general_common_per_page',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'cart_nb_page_before_after',
                                            'type' => 'MelisText',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_general_common_num_page_before_after',
                                                'tooltip' => 'tr_meliscommerce_general_common_num_page_before_after tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'cart_nb_page_before_after',
                                                'class' => 'form-control',
                                                'placeholder' => 'tr_meliscommerce_general_common_num_page_before_after',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'cart_per_page' => array(
                                        'name'     => 'cart_per_page',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name'    => 'Digits',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\Digits::NOT_DIGITS => 'tr_front_common_input_not_digit',
                                                        \Zend\Validator\Digits::STRING_EMPTY => '',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_front_common_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'cart_nb_page_before_after' => array(
                                        'name'     => 'cart_nb_page_before_after',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name'    => 'Digits',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\Digits::NOT_DIGITS => 'tr_front_common_input_not_digit',
                                                        \Zend\Validator\Digits::STRING_EMPTY => '',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_front_common_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            )
                        ),
                    )
                ),
            ),
        ),
     ),
);