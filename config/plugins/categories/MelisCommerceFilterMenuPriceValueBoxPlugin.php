<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceFilterMenuPriceValueBoxPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceCategory/category-price-filter'),
                
                        // filtering
                        'm_box_filter_price_min' => null,
                        'm_box_filter_price_max' => null,
                
                        // price type are : price_net, price_gross
                        'price_type' => 'price_net',
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'CATEGORIES',
                            'title' => 'tr_MelisCommerceFilterMenuCategoryListPlugin_Title'
                        ),
                        'name' => 'tr_meliscommerce_plugin_category_price_filter_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceFilterMenuPriceValueBoxPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_category_price_filter_description',
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
                                'tab_title' => 'tr_meliscommerce_plugin_common_configuration',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/category-product-search-box-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => array(
                                                'label' => 'tr_melis_Plugins_Template',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
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
                        )
                    ),
                ),
            ),
        ),
    ),
);