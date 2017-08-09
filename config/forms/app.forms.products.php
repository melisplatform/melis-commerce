<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_products' => array(
                    'meliscommerce_product_text_form' => array(
                        'attributes' => array(
                            'name' => 'productTextForm',
                            'id' => '',
                            'class' => 'productTextForm',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'ptxt_id',
                                    'type' => 'hidden',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_product_text_lang_id',
                                    ),
                                    'attributes' => array(
                                        'id' => 'ptxt_id',
                                        'readonly' => 'readonly',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ptxt_lang_id',
                                    'type' => 'hidden',
//                                     'type' => 'EcomLanguageSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_product_text_lang',
                                    ),
                                    'attributes' => array(
                                        'id' => 'ptxt_lang_id',
                                        'readonly' => 'readonly',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ptxt_type',
                                    'type' => 'EcomProductTextTypeSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_product_text_type',
                                        'tooltip' => 'tr_meliscommerce_product_text_type tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'ptxt_type',
                                        'readonly' => 'readonly',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ptxt_field_short',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        
                                        'label_options' => array(
                                            'disable_html_escape' => true,
                                        )
                                    ),
                                    'attributes' => array(
                                        'id' => 'ptxt_field_short',
                                        'style' => 'display:none;',
                                        'maxlength' => 45
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ptxt_field_long',
                                    'type' => 'Textarea',
                                    'options' => array(
                                        'label_options' => array(
                                            'disable_html_escape' => true,
                                        )
                                    ),
                                    'attributes' => array(
                                         'class' => 'form-control hidden',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ptt_id',
                                    'type' => 'Hidden',
                                    'options' => array(),
                                    'attributes' => array(
                                        'id' => 'ptt_id',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ptt_code',
                                    'type' => 'Hidden',
                                    'options' => array(),                                    
                                    'attributes' => array(
                                        'id' => 'ptt_code',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ptt_name',
                                    'type' => 'Hidden',
                                    'options' => array(),
                                    'attributes' => array(
                                        'id' => 'ptt_name',
                                    )
                                )
                            ),
                        ),
                        'input_filter' => array(    
                            'ptxt_field_short' => array(
                                'name'     => 'ptxt_field_short',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_product_text_short_too_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ptxt_type' => array(
                                'name'     => 'ptxt_type',
                                'required' => false,
                                'validators' => array(
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ptxt_field_long' => array(
                                'name'     => 'ptxt_field_long',
                                'required' => true,
                                'validators' => array(
                                ),
                                'filters'  => array(
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ptxt_lang_id' => array(
                                'name'     => 'ptxt_lang_id',
                                'required' => false,
                                'validators' => array(
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ptt_id' => array(
                                'name'     => 'ptt_id',
                                'required' => false,
                                'validators' => array(
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ptt_code' => array(
                                'name'     => 'ptt_code',
                                'required' => false,
                                'validators' => array(
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ptt_code' => array(
                                'name'     => 'ptt_code',
                                'required' => false,
                                'validators' => array(
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                        ),
                    ),
                    'meliscommerce_product_text_type_form' => array(
                        'attributes' => array(
                            'name' => '',
                            'id' => 'productTextTypeForm',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'ptt_code',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_product_text_type_code',
                                        'tooltip' => 'tr_meliscommerce_product_text_type_code tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'ptt_code',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ptt_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_product_text_type_name',
                                        'tooltip' => 'tr_meliscommerce_product_text_type_name tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'ptt_name',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'ptt_field_type',
                                    'type' => 'Zend\Form\Element\Select',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_product_text_type_field',
                                        'tooltip' => 'tr_meliscommerce_product_text_type_field tooltip',
                                        'value_options' => array(
                                            '1' => 'tr_meliscommerce_product_text_short',
                                            '2' => 'tr_meliscommerce_product_text_long',
                                        ),
                                    ),
                                    'attributes' => array(
                                        'id' => 'ptt_field_type',
                                        'value' => '',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'ptt_code' => array(
                                'name'     => 'ptt_code',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_product_text_type_code_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_product_text_type_code_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'ptt_name' => array(
                                'name'     => 'ptt_name',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_product_text_type_name_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_product_text_type_name_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                        ),
                    ),                    
                    'meliscommerce_products_reference_form' => array(
                        'attributes' => array(
                            'name' => 'product',
                            'id' => 'product',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'prd_reference',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'tooltip' => 'tr_meliscommerce_products_reference tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'prd_reference',
                                        'value' => '',
                                        'placeholder' => 'tr_meliscommerce_products_reference',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'prd_reference' => array(
                                'name'     => 'prd_reference',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_products_reference_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_products_reference_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
