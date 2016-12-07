<?php 
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_duplications' => array(
                    'meliscommerce_duplications_sku_form' => array(
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
                                    'name' => 'var_sku',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_duplication_Var_sku'
                                    ),
                                    'attributes' => array(
                                        'id' => 'var_sku',
                                    )
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'var_id',
                                    'type' => 'hidden',
                                    'options' => array(),
                                    'attributes' => array(
                                        'id' => 'var_id',
                                    )
                                )
                            ),
                        ),
                        'input_filter' => array(
                            'var_sku' => array(
                                'name'     => 'var_sku',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_duplication_input_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_duplication_input_too_long_100',
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
                )
            )
        )
    )
);