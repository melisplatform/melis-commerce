<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

return [
    'plugins' => [
        'meliscommerce' => [
            'plugins' => [
                'MelisCommerceCategoryTreePlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerceCategory/category-tree'],
                        'id' =>'categoryTree',
                        // Category option
                        'm_category_tree_option' => [
                            'm_box_root_category_tree_id' => null,
                            'm_box_category_tree_ids_selected' => [],
                            'm_box_include_root_category_tree' => null,
                        ],
                    ],
                    'melis' => [
                        /*
                        * if set this plugin will belong to a specific marketplace section,
                        * if not it will go directly to ( Others ] section
                        *  - available section for templating plugins as of 2019-05-16
                        *    - MelisCms
                        *    - MelisMarketing
                        *    - MelisSite
                        *    - MelisCommerce
                        *    - Others
                        *    - CustomProjects
                        */
                        'section' => 'MelisCommerce',
                        'subcategory' => [
                            'id' => 'CATEGORIES',
                            'title' => 'tr_meliscommerce_plugin_sub_categories_title'
                        ],
                        'name' => '\tr_meliscommerce_plugin_category_tree_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCategoryTreePlugin.jpg',
                        'description' => 'tr_meliscommerce_plugin_category_tree_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => [
                            'css' => [
                                '/MelisCommerce/plugins/css/MelisCommerceCategoryTreePlugin.css'
                            ],
                            'js' => [
                                '/MelisCommerce/plugins/js/MelisCommerce.MelisCommerceCategoryTreePlugin.init.js'
                            ],
                        ],

                        'modal_form' => [
                            'melis_commerce_plugin_category_tree_template_config' => [
                                'tab_title' => 'tr_meliscommerce_plugin_category_tree_template',
                                'tab_icon'  => 'fa fa-cogs',
                                'tab_form_layout' => 'MelisCommerce/plugin-common-form-config',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => [
                                                'label' => 'tr_melis_Plugins_Template',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                                'tooltip' => 'tr_meliscommerce_plugin_template_tooltip',
                                            ],
                                            'attributes' => [
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                            ],
                                        ],
                                    ],
                                ],
                                'input_filter' => [
                                    'template_path' => [
                                        'name'     => 'template_path',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_front_template_path_empty',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                ]
                            ],
                            'melis_commerce_plugin_category_tree_config' => [
                                'tab_title' => 'tr_meliscommerce_plugin_filter_menu_category_list_root_category_tree',
                                'tab_icon'  => 'fa fa-book',
                                'tab_form_layout' => 'MelisCommerce/category-tree-config',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_box_include_root_category_tree',
                                            'type' => 'Select',
                                            'options' => [
                                                'tooltip' => 'tr_meliscommerce_plugin_include_root_category_tooltip',
                                                'label' => 'tr_meliscommerce_plugin_include_root_category',
                                                'checked_value' => '1',
                                                'unchecked_value' => '0',
                                                'switchOptions' => [
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                ],
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_box_include_root_category_tree',
                                            ],
                                        ],
                                    ],
                                ],
                                'input_filter' => [
                                    'm_box_include_root_category_tree' => [
                                        'name'     => 'm_box_include_root_category_tree',
                                        'required' => false,
                                        'validators' => [
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    ],
                ],
            ],
        ],
     ],
];