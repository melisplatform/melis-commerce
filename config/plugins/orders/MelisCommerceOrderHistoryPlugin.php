<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceOrderHistoryPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceOrder/order-history'),
                        'id' => 'orderHistoryPlugin',
                        'm_order_sort' => null,

                        // pagination config
                        'pagination' => array(
                            'order_history_current' => 1,
                            'order_history_per_page' => 10,
                        ),
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'CART',
                            'title' => 'tr_meliscommerce_car_Cart'
                        ),
                        'name' => 'tr_meliscommerce_order_history_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceOrderHistoryPlugin.jpg',
                        'description' => 'tr_meliscommerce_order_history_description',
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
                            'melis_commerce_plugin_order_history_config' => array(
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
                                            'name' => 'm_order_sort',
                                            'type' => 'Select',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_order_history_order',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'value_options' => array(
                                                    'ord_date_creation ASC' => 'Date Ascending',
                                                    'ord_date_creation DESC' => 'Date Descending',
                                                ),
                                                'tooltip' => 'tr_meliscommerce_order_history_order_tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_order_sort',
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
                                    'm_order_sort' => array(
                                        'name'     => 'm_order_sort',
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