<?php 
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_categories' => array(
                    'meliscommerce_categories_search_input' => array(
                        'attributes' => array(
                            'name' => '',
                            'id' => 'categoryTreeViewSearchForm',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'categoryTreeViewSearchInput',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => '',
                                    ),
                                    'attributes' => array(
                                        'id' => 'categoryTreeViewSearchInput',
                                        'placeholder' => 'tr_meliscommerce_categories_list_tree_view_search_input',
                                    )
                                )
                            ),
                        )
                    ),
                    'meliscommerce_categories_category_information_form' => array(
                        'attributes' => array(
                            'name' => '',
                            'id' => 'categoryMainInformationForm',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'catt_id',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'catt_lang_id',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'catt_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_categories_category_information_form_cat_name',
                                        'tooltip' => 'tr_meliscommerce_categories_category_information_form_cat_name tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'catt_name',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'catt_description',
                                    'type' => 'Textarea',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_categories_category_information_form_cat_desc',
                                        'tooltip' => 'tr_meliscommerce_categories_category_information_form_cat_desc tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'catt_description',
                                        'class' => 'form-control editme',
                                        'rows' => 10
                                    )
                                )
                            ),
                        ),
                        'input_filter' => array(
                            'catt_lang_id' => array(
                                'name'     => 'catt_lang_id',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_categories_input_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                        )
                    ),
                    'meliscommerce_categories_date_validty_form' => array(
                        'attributes' => array(
                            'name' => '',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'cat_date_valid_start',
                                    'type' => 'DateField',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_categories_category_valid_from',
                                        'tooltip' => 'tr_meliscommerce_categories_category_valid_from tooltip',
                                        'class' => 'd-flex flex-row justify-content-between'
                                    ),
                                    'attributes' => array(
                                        'dateId' => 'categoryValidateDates',
                                        'dateLabel' => 'tr_meliscommerce_categories_category_valid_from',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cat_date_valid_end',
                                    'type' => 'DateField',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_categories_category_valid_to',
                                        'tooltip' => 'tr_meliscommerce_categories_category_valid_to tooltip',
                                        'class' => 'd-flex flex-row justify-content-between'
                                    ),
                                    'attributes' => array(
                                        'dateId' => 'categoryValidateDates',
                                        'dateLabel' => 'tr_meliscommerce_categories_category_valid_to',
                                    )
                                )
                            ),
                        )
                    ),
                ),
            ),
        ),
    ),
);
