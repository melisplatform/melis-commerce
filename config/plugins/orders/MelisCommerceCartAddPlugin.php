<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceCartAddPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceAddToCartShowPlugin/show-add-to-cart',
                        
                        // Id of the variant
                        'm_v_id' => null,
                        // country id of the variant,
                        'm_v_country' => 1,
                        // Quantity of the variant added
                        'm_v_quantity' => 1,
                        // flag true if a form is submitted
                        'm_is_submit' => false,
                        
                        // Form setup, elements and validators
                        'forms' => array(
                            'meliscommerce_add_to_cart_form' => array(
                                'attributes' => array(
                                    'name' => '',
                                    'id' => '',
                                    'method' => '',
                                    'action' => '',
                                    'class' => '',
                                ),
                                'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'm_v_id',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_v_country',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_v_quantity',
                                            'type' => 'Text',
                                            'options' => array(
                                                'label' => 'tr_meliscommerce_add_to_cart_quantity',
                                            ),
                                            'attributes' => array(
                                                'id' => 'm_v_quantity',
                                            )
                                        )
                                    ),
                
                                ),
                                'input_filter' => array(
                                    'm_v_id' => array(
                                        'name'     => 'm_v_id',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_add_to_cart_variant_id_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_v_country' => array(
                                        'name'     => 'm_v_country',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_add_to_cart_variant_country_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                            array('name' => 'StripTags'),
                                            array('name' => 'StringTrim'),
                                        ),
                                    ),
                                    'm_v_quantity' => array(
                                        'name'     => 'm_v_quantity',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'Digits',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\Digits::NOT_DIGITS => 'tr_meliscommerce_add_to_cart_variant_quantity_invalid',
                                                        \Zend\Validator\Digits::STRING_EMPTY => '',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_add_to_cart_variant_quantity_empty',
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
                            )
                        )
                    ),
                    'melis' => array(),
                ),
            ),
        ),
     ),
);