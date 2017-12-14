<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCategoryTreePlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceCategory/category-tree'),
                        'id' =>'categoryTree',
                        // Category option
                        'm_category_tree_filter_option' => array(
                            'm_box_filter_root_category_id' => null,
                            'm_box_filter_categories_ids_selected' => array(),
                            'm_box_filter_include_root_category' => null,
                        ),
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'CATEGORIES',
                            'title' => 'tr_MelisCommerceFilterMenuCategoryListPlugin_Title'
                        ),
                        'name' => 'tr_meliscommerce_plugin_category_tree_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCategoryTreePlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_category_tree_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                                '/MelisCommerce/plugins/css/MelisCommerceCategoryTreePlugin.css'
                            ),
                            'js' => array(
                                '/MelisCommerce/plugins/js/MelisCommerce.MelisCommerceCategoryTreePlugin.init.js'
                            ),
                        ),

                        'modal_form' => array(
                            'melis_commerce_plugin_category_tree_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_category_tree_template',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/category-list-filter-config',
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
                            'melis_commerce_plugin_filter_menu_category_list_tree_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_filter_menu_category_list_root_category_tree',
                                'tab_icon'  => 'fa fa-book',
                                'tab_form_layout' => 'MelisCommerce/category-tree-config',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_filter_include_root_category',
                                            'type' => 'Checkbox',
                                            'options' => array(
                                                'tooltip' => 'tr_meliscommerce_plugin_include_root_category_tooltip',
                                                'label' => 'tr_meliscommerce_plugin_include_root_category',
                                                'checked_value' => 1,
                                                'unchecked_value' => 0,
                                                'switchOptions' => array(
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                )
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_filter_include_root_category',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_box_filter_include_root_category' => array(
                                        'name'     => 'm_box_filter_include_root_category',
                                        'required' => false,
                                        'validators' => array(
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                ),
                            ),
                        )
                    ),
                ),
            ),
        ),
     ),
);