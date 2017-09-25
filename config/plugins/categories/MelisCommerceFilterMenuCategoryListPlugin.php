<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceFilterMenuCategoryListPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCommerceCategory/category-list-filter'),
                        'id' =>'filterMenuCategoryList',
                        // base parent id of the category
                        'm_box_filter_root_category_id' => null,
                        // Inclue parent on list
                        'm_box_filter_include_root_category' => true,
                        // returns selected category ids
                        'm_box_filter_categories_ids_selected' => array(),
                        // only valid categories will be filtered
                        'm_box_filter_only_valid' => false,
                    ),
                    'melis' => array(
                        'subcategory' => array(
                            'id' => 'CATEGORIES',
                            'title' => 'tr_MelisCommerceFilterMenuCategoryListPlugin_Title'
                        ),
                        'name' => 'tr_meliscommerce_plugin_filter_menu_category_list_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceFilterMenuCategoryListPlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_filter_menu_category_list_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => array(
                                '/MelisCommerce/plugins/css/MelisCommerceFilterMenuCategoryListPlugin.css'
                            ),
                            'js' => array(
                                '/MelisCommerce/plugins/js/MelisCommerce.MelisCommerceFilterMenuCategoryListPlugin.init.js'
                            ),
                        ),
                        'js_initialization' => array(),
                        'modal_form' => array(
                            'melis_commerce_plugin_filter_menu_category_list_config' => array(
                                'tab_title' => 'tr_meliscommerce_plugin_filter_menu_category_list_config',
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
                                            ),
                                            'attributes' => array(
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_box_filter_root_category_id',
                                            'type' => 'EcomPluginCategoryListSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_plugin_filter_menu_category_list_root_category',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_box_filter_root_category_id',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    )
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
                                    'm_box_filter_root_category_id' => array(
                                        'name'     => 'm_box_filter_root_category_id',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_plugin_filter_menu_category_list_root_category_empty',
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
                                'tab_form_layout' => 'MelisCommerce/category-list-filter-tree-config',
                            ),
                        )
                    ),
                ),
            ),
        ),
     ),
);