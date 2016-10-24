<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_attributes' => array(
                    'meliscommerce_attribute_general_data' => array(
                        'attributes' => array(
                            'name' => 'attribute',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'attr_reference',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_attribute_reference',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'max' => '45',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'attr_type_id',
                                    'type' => 'EcomAttributeTypeSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_attribute_type',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    )
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'attr_id',
                                    'type' => 'hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),                           
                        ),
                        'input_filter' => array(
                            'attr_reference' => array(
                                'name'     => 'attr_reference',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_45',
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
                    'meliscommerce_attribute_text_trans' => array(
                        'attributes' => array(
                            'name' => 'attributeTrans',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'atrans_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_attribute_label',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'max' => 100,
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'atrans_description',
                                    'type' => 'TextArea',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_attribute_label_description',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'class' => 'form-control',
                                        'style' => 'max-width:100%;',
                                        'rows' => '4',
                                        'maxlength' => 45,
                                    )
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'atrans_id',
                                    'type' => 'hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'atrans_lang_id',
                                    'type' => 'hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'atrans_name' => array(
                                'name'     => 'atrans_name',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_coupon_input_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_100',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'atrans_description' => array(
                                'name'     => 'atrans_description',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_coupon_input_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_45',
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
                    'meliscommerce_attribute_value' => array(
                        'attributes' => array(
                            'name' => 'attributeValue',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'avt_v_int',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_attribute_value_integer',
                                        'label_attributes' => array(
                                            'style' => 'display:none;',
                                        ),
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'max' => '11',
                                        'style' => 'display:none;',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'avt_v_float',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_attribute_value_decimal',
                                        'label_attributes' => array(
                                            'style' => 'display:none;',
                                        ),
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'max' => '11',
                                        'style' => 'display:none;',
                                    )
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'avt_v_bool',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_attribute_value_boolean',
                                        'label_attributes' => array(
                                            'style' => 'display:none;',
                                        ),
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'style' => 'display:none;',
                                    )
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'avt_v_varchar',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_attribute_value_varchar',
                                        'label_attributes' => array(
                                            'style' => 'display:none;',
                                            'label_attributes' => array(
                                                'style' => 'display:none;',
                                            ),
                                        ),
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'max' => '255',
                                        'style' => 'display:none;',
                                    )
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'avt_v_text',
                                    'type' => 'TextArea',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_attribute_value_text',
                                        'label_attributes' => array(
                                            'style' => 'display:none;',
                                        ),
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                        'class' => 'form-control',
                                        'style' => 'max-width:100%;',
                                        'rows' => '4',
                                        'maxlength' => 1200,
                                        'style' => 'display:none;',
                                    )
                                ),
                            ),
//                             array(
//                                 'spec' => array(
//                                     'name' => 'avt_v_datetime',
//                                     'type' => 'EcomDateField',
//                                     'options' => array(
//                                         'label' => 'tr_meliscommerce_attribute_value_date',
//                                     ),
//                                     'attributes' => array(
//                                         'dateId' => 'valueDate',
//                                         'dateLabel' => 'tr_meliscommerce_attribute_value_date',
//                                         'style' => 'display:none;',
//                                     ),
//                                 ),
//                             ),
                            array(
                                'spec' => array(
                                    'name' => 'avt_id',
                                    'type' => 'hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),                            
                            array(
                                'spec' => array(
                                    'name' => 'avt_lang_id',
                                    'type' => 'hidden',
                                    'options' => array(
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'avt_v_int' => array(
                                'name'     => 'avt_v_int',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => '\Zend\I18n\Validator\IsInt',
                                        'options' => array(
                                            'messages'=> array(
                                                \Zend\I18n\Validator\IsInt::NOT_INT   => 'tr_meliscommerce_coupon_input_invalid_digit',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'avt_v_float' => array(
                                'name'     => 'avt_v_float',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => '\Zend\I18n\Validator\IsFloat',
                                        'options' => array(
                                            'messages'=> array(
                                                \Zend\I18n\Validator\IsFloat::NOT_FLOAT  => 'tr_meliscommerce_coupon_input_invalid_decimal'
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'avt_v_varchar' => array(
                                'name'     => 'avt_v_varchar',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_255',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'avt_v_text' => array(
                                'name'     => 'avt_v_text',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 1200,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_address_error_long_1200',
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
