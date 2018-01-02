<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceProductSearchPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerce/product-search'),
                        'id' => 'porductSearchInput',
                        // Search box filter
                        'm_box_product_search' => '',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                            ),
                            'js' => array(
                            ),
                        ),

                        // pagination config
                        'pagination' => array(
                            'm_page_current' => 1,
                            'm_page_nb_per_page' => 10,
                            'm_page_nb_page_before_after' => 3,

                        ),
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'PRODUCTS',
                            'title' => 'tr_meliscommerce_products_Products'
                        ),
                        'name' => 'tr_meliscommerce_plugin_product_search_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceProductSearchPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_product_search_description',
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
                            'melis_commerce_plugin_category_product_search_box_config' => array(
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
                        ),
                        'product_search_form' => array(
                            'melis_commerce_plugin_product_search_form' => array(
                                'attributes' => array(
                                    'name' => 'productSearchForm',
                                    'method' => 'GET',
                                    'action' => '',
                                    'id' => 'catalogueSearchForm',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_product_search',
                                            'type' => 'text',
                                            'attributes' => array(
                                                'id' => 'm_box_product_search',
                                                'class' => 'form-control',
                                                'placeholder' => 'Search',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_box_product_search' => array(
                                        'name'     => 'm_box_product_search',
                                        'required' => false,
                                        'validators' => array(
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            ),
                        ),
                    ),
                ),
            ),
        ),
     ),
);