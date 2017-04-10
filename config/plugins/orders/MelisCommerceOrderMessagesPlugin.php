<?php
// Product plugins config
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'plugins' => array(
                'MelisCommerceOrderMessagesPlugin' => array(
                    'front' => array(
                        'template_path' => 'MelisCommerceOrder/show-client-shipping-details',
                        // flag true if form is submitted
                        'message_is_submit' => 0,
                        // order message
                        'm_c_message' => '',
                
                        'forms' => array(
                            'meliscommerce_order_add_message_form' => array(
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
                                            'name' => 'm_c_order',
                                            'type' => 'hidden',
                                        )
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'message_is_submit',
                                            'type' => 'hidden',
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'm_c_message',
                                            'type' => 'TextArea',
                                            'attributes' => array(
                                                'id' => 'm_c_message',
                                                'placeholder' => 'tr_meliscommerce_orders_message_your',
                                            ),
                                        )
                                    ),
                                ),
                                'input_filter' => array(
                                    'm_c_order' => array(
                                        'name'     => 'm_c_order',
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
                                    'm_c_message' => array(
                                        'name'     => 'm_c_message',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_orders_message_your_error',
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