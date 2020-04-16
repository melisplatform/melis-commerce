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
            'forms' => [
                'meliscommerce_order_checkout' => [
                    'meliscommerce_order_checkout_billing_address_form' => [
                        'attributes' => [
                            'id' => 'EcomCheckoutBillingAddressForm'
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'cadd_id',
                                    'type' => 'EcomCheckoutBillingAddressSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_order_checkout_address_billing_Select',
                                        'empty_option' => 'tr_meliscommerce_order_checkout_common_chooose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'orderCheckoutBillingSelect',
                                    ]
                                ]
                            ],
                        ]
                    ],
                    'meliscommerce_order_checkout_delivery_address_form' => [
                        'attributes' => [
                            'id' => 'EcomCheckoutDeliveryAddressForm'
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'cadd_id',
                                    'type' => 'EcomCheckoutDeliveryAddressSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_order_checkout_address_delivery_Select',
                                        'empty_option' => 'tr_meliscommerce_order_checkout_common_chooose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'orderCheckoutDeliverySelect',
                                    ]
                                ]
                            ],
                        ]
                    ],
                    'meliscommerce_order_checkout_address_form' => [
                        'attributes' => [
                            'name' => '',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'cadd_client_id',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_client_person',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_type',
                                    'type' => 'hidden',
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_address_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_name',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_address_name',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_civility',
                                    'type' => 'EcomCivilitySelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_civility',
                                        'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_civility',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_firstname',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_fname',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_firstname',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_name',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_name',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_middle_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_mname',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_middle_name',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_num',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_street_num',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_num',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_street',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_street_name',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_street',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_building_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_building_name',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_building_name',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_stairs',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_stairs',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_stairs',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_city',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_city',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_city',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_state',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_state',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_state',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_country',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_country',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_country',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_zipcode',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_zipcode',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_zipcode',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_company',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_company_name',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_company',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_phone_mobile',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_mobile_number',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_phone_mobile',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_phone_landline',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_phone_landline',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_phone_landline',
                                    ]
                                ]
                            ],
                            [
                                'spec' => [
                                    'name' => 'cadd_complementary',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_client_Contact_address_additional_information',
                                    ],
                                    'attributes' => [
                                        'id' => 'cadd_complementary',
                                    ]
                                ]
                            ],
                        ],
                        'input_filter' => [
                            'cadd_address_name' => [
                                'name'     => 'cadd_address_name',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_civility' => [
                                'name'     => 'cadd_civility',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_name' => [
                                'name'     => 'cadd_name',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_firstname' => [
                                'name'     => 'cadd_firstname',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_Contact_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_num' => [
                                'name'     => 'cadd_num',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_10',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_stairs' => [
                                'name'     => 'cadd_stairs',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_10',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_building_name' => [
                                'name'     => 'cadd_building_name',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_company' => [
                                'name'     => 'cadd_company',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_street' => [
                                'name'     => 'cadd_street',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_zipcode' => [
                                'name'     => 'cadd_zipcode',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 15,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_15',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_city' => [
                                'name'     => 'cadd_city',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 100,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_100',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_state' => [
                                'name'     => 'cadd_state',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 50,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_50',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_country' => [
                                'name'     => 'cadd_country',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 50,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_50',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_phone_mobile' => [
                                'name'     => 'cadd_phone_mobile',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_phone_landline' => [
                                'name'     => 'cadd_phone_landline',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_45',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'cadd_complementary' => [
                                'name'     => 'cadd_complementary',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_client_Contact_input_too_long_255',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ]
                    ],
                ]
            ]
        ]
    ]
];