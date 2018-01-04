<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceProductAttributePlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerce/product-attribute'),
                        'id' => 'productAttribute',
                        // the ID of the attribute to be fetch
                        'attribute_id' => null,
                        // filtering
                        // array of attribute values
                        'm_box_product_attribute_values_ids_selected' => array(),
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'PRODUCTS',
                            'title' => 'tr_meliscommerce_products_Products'
                        ),
                        'name' => 'tr_meliscommerce_plugin_product_attribute_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceProductAttributePlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_product_attribute_description',
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
                            'melis_commerce_plugin_product_attribute_config' => array(
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
                                            'name' => 'attribute_id',
                                            'type' => 'EcomPluginAttributeSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_products_attribute_name',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_products_attribute_name_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'attribute_id',
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
                        )
                    ),
                ),
            ),
        ),
     ),
);