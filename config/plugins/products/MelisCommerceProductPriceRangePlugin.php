<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceProductPriceRangePlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceProduct/product-price-range'),
                        'id' => 'productPriceRange',
                        // filtering
                        'm_box_product_price_min' => null,
                        'm_box_product_price_max' => null,
                
                        // price column are : price_net, price_gross
                        'm_box_product_price_column' => 'price_net',
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'PRODUCTS',
                            'title' => 'tr_meliscommerce_products_Products'
                        ),
                        'name' => 'tr_meliscommerce_plugin_product_price_range_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceProductPriceRangePlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_product_price_range_description',
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
                            'melis_commerce_plugin_category_product_price_config' => array(
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
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_product_price_column',
                                            'type' => 'select',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_price_column',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_plugin_product_price_column_tooltip',
                                                'value_options' => array(
                                                    'price_net' => 'tr_meliscommerce_plugin_product_price_column_price_net',
                                                    'price_gross' => 'tr_meliscommerce_plugin_product_price_column_price_gross',
                                                    'price_vat_price' => 'tr_meliscommerce_plugin_product_price_column_price_vat_price',
                                                    'price_other_tax_price' => 'tr_meliscommerce_plugin_product_price_column_price_other_tax_price',
                                                ),
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_product_price_column',
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
                        'product_price_range_form' => array(
                            'melis_commerce_plugin_category_product_price_range_form' => array(
                                'attributes' => array(
                                    'name' => 'productPriceRangeForm',
                                    'method' => 'POST',
                                    'action' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_product_price_min',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_price_range_min',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_product_price_min',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_product_price_max',
                                            'type' => 'text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_product_price_range_max',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_product_price_max',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_box_product_price_min' => array(
                                        'name'     => 'm_box_product_price_min',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_product_price_min_price_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'm_box_product_price_max' => array(
                                        'name'     => 'm_box_product_price_max',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_product_price_max_price_empty',
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
                    ),
                ),
            ),
        ),
    ),
);