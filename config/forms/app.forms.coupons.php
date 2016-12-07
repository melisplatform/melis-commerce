<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_coupon' => array(
                    'meliscommerce_coupon_general_data' => array(
                        'attributes' => array(
                            'name' => 'coupon',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(                           
                            array(
                                'spec' => array(
                                    'name' => 'coup_code',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_coupon_code',
                                    ),
                                    'attributes' => array(
                                        'id' => 'coup_code',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'coup_date_valid_start',
                                    'type' => 'EcomDateField',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_coupon_date_start',
                                    ),
                                    'attributes' => array(
                                        'dateId' => 'couponStart',
                                        'dateLabel' => 'tr_meliscommerce_coupon_date_start',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'coup_id',
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
                                    'name' => 'coup_date_valid_end',
                                    'type' => 'EcomDateField',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_coupon_date_end',
                                    ),
                                    'attributes' => array(
                                        'dateId' => 'couponEnd',
                                        'dateLabel' => 'tr_meliscommerce_coupon_date_end',
                                    ),
                                ),
                            ),                           
                        ),
                        'input_filter' => array(
                            'coup_code' => array(
                                'name'     => 'coup_code',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_coupon_input_empty',
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
                    'meliscommerce_coupon_values' => array(
                        'attributes' => array(
                            'name' => 'couponValues',
                            'id' => '',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(                            
                            array(
                                'spec' => array(
                                    'name' => 'coup_percentage',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_coupon_percent',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'coup_discount_value',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_coupon_discount',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'coup_current_use_number',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_coupon_used',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'coup_max_use_number',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_coupon_max',
                                    ),
                                    'attributes' => array(
                                        'id' => '',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'coup_percentage' => array(
                                'name'     => 'coup_percentage',
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
                            'coup_discount_value' => array(
                                'name'     => 'coup_discount_value',
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
                            'coup_current_use_number' => array(
                                'name'     => 'coup_current_use_number',
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
                            'coup_max_use_number' => array(
                                'name'     => 'coup_max_use_number',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name'    => '\Zend\I18n\Validator\IsInt',
                                        'options' => array(
                                            'messages'=> array(
                                                \Zend\I18n\Validator\IsInt::NOT_INT   => 'tr_meliscommerce_coupon_input_invalid_digit',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_coupon_input_empty',
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
