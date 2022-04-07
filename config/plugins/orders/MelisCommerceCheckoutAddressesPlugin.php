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
                'MelisCommerceCheckoutAddressesPlugin' => [
                    'front' => [
                        'template_path' => ['MelisCommerceCheckout/checkout-addresses'],
                        'id' => 'checkoutAddresses',
                        
                        // site id
                        'm_add_site_id' => 1,
                        // Override submitted data existing data
                        'm_add_override_data' => false,
                        
                        // delivery form fields
                        'm_add_delivery_id' => '',
                        'm_add_delivery_type' => '',
                        'm_add_delivery_address_name' => '',
                        'm_add_delivery_civility' => '',
                        'm_add_delivery_firstname' => '',
                        'm_add_delivery_name' => '',
                        'm_add_delivery_middle_name' => '',
                        'm_add_delivery_num' => '',
                        'm_add_delivery_street' => '',
                        'm_add_delivery_building_name' => '',
                        'm_add_delivery_stairs' => '',
                        'm_add_delivery_city' => '',
                        'm_add_delivery_state' => '',
                        'm_add_delivery_country' => '',
                        'm_add_delivery_zipcode' => '',
                        'm_add_delivery_company' => '',
                        'm_add_delivery_phone_mobile' => '',
                        'm_add_delivery_phone_landline' => '',
                        'm_add_delivery_complementary' => '',
                        
                        // use same address flag
                        'm_add_use_same_address' => null,

                        'm_add_order_method' => '',

                        /**
                         * This field is used to determine which address
                         * should the plugin will validate first (billing, delivery]
                         * If this field is not include inside the form or in
                         * ajax request, the default of this is delivery
                         *
                         * The value of this is either billing or delivery
                         */
                        'm_add_first_form_to_validate' => '',
                        
                        // billing form fields
                        'm_add_billing_id' => '',
                        'm_add_billing_type' => '',
                        'm_add_billing_address_name' => '',
                        'm_add_billing_civility' => '',
                        'm_add_billing_firstname' => '',
                        'm_add_billing_name' => '',
                        'm_add_billing_middle_name' => '',
                        'm_add_billing_num' => '',
                        'm_add_billing_street' => '',
                        'm_add_billing_building_name' => '',
                        'm_add_billing_stairs' => '',
                        'm_add_billing_city' => '',
                        'm_add_billing_state' => '',
                        'm_add_billing_country' => '',
                        'm_add_billing_zipcode' => '',
                        'm_add_billing_company' => '',
                        'm_add_billing_phone_mobile' => '',
                        'm_add_billing_phone_landline' => '',
                        'm_add_billing_complementary' => '',
                        
                        // flag true if a form is submitted
                        'm_add_is_submit' => false,
                        
                        // Form setup, elements and validators
                        'forms' => [
                            'delivery_address' => [
                                'attributes' => [
                                    'name' => 'delivery_address',
                                    'id' => '',
                                    'method' => 'POST',
                                    'action' => '',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_add_is_submit',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_type',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_id',
                                            'type' => 'EcomPluginDeliveryAddressSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_select_address',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_id',
                                                'class' => 'form-control'
                                            ],
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_address_name',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_title',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_address_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_civility',
                                            'type' => 'EcomPluginCivilitySelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_civility',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_civility',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_firstname',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_fname',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_firstname',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_name',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_middle_name',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_mname',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_middle_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_num',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_street_num',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_num',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_street',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_street_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_street',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_building_name',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_building_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_building_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_stairs',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_stairs',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_stairs',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_city',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_city',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_city',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_state',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_state',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_state',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_country',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_country',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_country',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_zipcode',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_zipcode',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_zipcode',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_company',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_company_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_company',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_phone_mobile',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_mobile_number',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_phone_mobile',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_phone_landline',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_phone_landline',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_phone_landline',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_delivery_complementary',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_additional_information',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_delivery_complementary',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                ],
                                'input_filter' => [
                                    'm_add_delivery_id' => [
                                        'name'     => 'm_add_delivery_id',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_select_deliver_address',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters' => [
                                            ['name' => 'StripTags'],
                                            ['name' => 'StringTrim'],
                                        ],
                                    ],
                                    'm_add_delivery_address_name' => [
                                        'name'     => 'm_add_delivery_address_name',
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
                                    'm_add_delivery_civility' => [
                                        'name'     => 'm_add_delivery_civility',
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
                                    'm_add_delivery_firstname' => [
                                        'name'     => 'm_add_delivery_firstname',
                                        'required' => true,
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
                                    'm_add_delivery_name' => [
                                        'name'     => 'm_add_delivery_name',
                                        'required' => true,
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
                                    'm_add_delivery_num' => [
                                        'name'     => 'm_add_delivery_num',
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
                                    'm_add_delivery_stairs' => [
                                        'name'     => 'm_add_delivery_stairs',
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
                                    'm_add_delivery_building_name' => [
                                        'name'     => 'm_add_delivery_building_name',
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
                                    'm_add_delivery_company' => [
                                        'name'     => 'm_add_delivery_company',
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
                                    'm_add_delivery_street' => [
                                        'name'     => 'm_add_delivery_street',
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
                                    'm_add_delivery_zipcode' => [
                                        'name'     => 'm_add_delivery_zipcode',
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
                                    'm_add_delivery_city' => [
                                        'name'     => 'm_add_delivery_city',
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
                                    'm_add_delivery_state' => [
                                        'name'     => 'm_add_delivery_state',
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
                                    'm_add_delivery_country' => [
                                        'name'     => 'm_add_delivery_country',
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
                                    'm_add_delivery_phone_mobile' => [
                                        'name'     => 'm_add_delivery_phone_mobile',
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
                                    'm_add_delivery_phone_landline' => [
                                        'name'     => 'm_add_delivery_phone_landline',
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
                                    'm_add_delivery_complementary' => [
                                        'name'     => 'm_add_delivery_complementary',
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
                            'billing_address' => [
                                'attributes' => [
                                    'name' => 'billing_address',
                                    'id' => '',
                                    'method' => 'POST',
                                    'action' => '',
                                ],
                                'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                                'elements' => [
                                    [
                                        'spec' => [
                                            'name' => 'm_add_is_submit',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_type',
                                            'type' => 'hidden',
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_id',
                                            'type' => 'EcomPluginBillingAddressSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_select_address',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_id',
                                                'class' => 'form-control'
                                            ],
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_address_name',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_title',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_address_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_civility',
                                            'type' => 'EcomPluginCivilitySelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_civility',
                                                'empty_option' => 'tr_meliscommerce_clients_common_label_choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_civility',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_firstname',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_fname',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_firstname',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_name',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_middle_name',
                                            'type' => 'Text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_mname',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_middle_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_num',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_street_num',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_num',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_street',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_street_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_street',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_building_name',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_building_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_building_name',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_stairs',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_stairs',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_stairs',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_city',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_city',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_city',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_state',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_state',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_state',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_country',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_country',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_country',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_zipcode',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_zipcode',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_zipcode',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_company',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_company_name',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_company',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_phone_mobile',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_mobile_number',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_phone_mobile',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_phone_landline',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_phone_landline',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_phone_landline',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_billing_complementary',
                                            'type' => 'text',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_client_Contact_address_additional_information',
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_billing_complementary',
                                                'class' => 'form-control'
                                            ]
                                        ]
                                    ],
                                ],
                                'input_filter' => [
                                    'm_add_billing_id' => [
                                        'name'     => 'm_add_billing_id',
                                        'required' => true,
                                        'validators' => [
                                            [
                                                'name' => 'NotEmpty',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_client_select_billing_address',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters' => [
                                            ['name' => 'StripTags'],
                                            ['name' => 'StringTrim'],
                                        ],
                                    ],
                                    'm_add_billing_address_name' => [
                                        'name'     => 'm_add_billing_address_name',
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
                                    'm_add_billing_civility' => [
                                        'name'     => 'm_add_billing_civility',
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
                                    'm_add_billing_firstname' => [
                                        'name'     => 'm_add_billing_firstname',
                                        'required' => true,
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
                                    'm_add_billing_name' => [
                                        'name'     => 'm_add_billing_name',
                                        'required' => true,
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
                                    'm_add_billing_num' => [
                                        'name'     => 'm_add_billing_num',
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
                                    'm_add_billing_stairs' => [
                                        'name'     => 'm_add_billing_stairs',
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
                                    'm_add_billing_building_name' => [
                                        'name'     => 'm_add_billing_building_name',
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
                                    'm_add_billing_company' => [
                                        'name'     => 'm_add_billing_company',
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
                                    'm_add_billing_street' => [
                                        'name'     => 'm_add_billing_street',
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
                                    'm_add_billing_zipcode' => [
                                        'name'     => 'm_add_billing_zipcode',
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
                                    'm_add_billing_city' => [
                                        'name'     => 'm_add_billing_city',
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
                                    'm_add_billing_state' => [
                                        'name'     => 'm_add_billing_state',
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
                                    'm_add_billing_country' => [
                                        'name'     => 'm_add_billing_country',
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
                                    'm_add_billing_phone_mobile' => [
                                        'name'     => 'm_add_billing_phone_mobile',
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
                                    'm_add_billing_phone_landline' => [
                                        'name'     => 'm_add_billing_phone_landline',
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
                                    'm_add_billing_complementary' => [
                                        'name'     => 'm_add_billing_complementary',
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
                            ]
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
                            'id' => 'ORDERS',
                            'title' => 'tr_meliscommerce_orders_Orders'
                        ],
                        'name' => '\tr_meliscommerce_plugin_checkout_addresses_name',
                        'thumbnail' => '/MelisCommerce/plugins/images/MelisCommerceCheckoutAddressesPlugin.jpg',
                        'description' => '\tr_meliscommerce_plugin_checkout_addresses_description',
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => [
                            'css' => [
                            ],
                            'js' => [
                                '/MelisCommerce/plugins/js/checkout.js'
                            ],
                        ],
                        'js_initialization' => [],
                        'modal_form' => [
                            'melis_commerce_plugin_checkout_addresses_config' => [
                                'tab_title' => 'tr_meliscommerce_general_plugin_properties_title',
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
                                                'required' => true,
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_site_id',
                                            'type' => 'MelisCoreSiteSelect',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_general_common_site',
                                                'tooltip' => 'tr_meliscommerce_general_common_site tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_site_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'spec' => [
                                            'name' => 'm_add_use_same_address',
                                            'type' => 'Select',
                                            'options' => [
                                                'label' => 'tr_meliscommerce_plugin_checkout_addresses_use_same_address',
                                                'tooltip' => 'tr_meliscommerce_plugin_checkout_addresses_use_same_address tooltip',
                                                'checked_value' => 1,
                                                'unchecked_value' => 0,
                                                'switchOptions' => [
                                                    'label-on' => 'tr_meliscommerce_categories_common_label_yes',
                                                    'label-off' => 'tr_meliscommerce_categories_common_label_no',
                                                    'label' => "<i class='glyphicon glyphicon-resize-horizontal'></i>",
                                                ],
                                                'disable_inarray_validator' => true
                                            ],
                                            'attributes' => [
                                                'id' => 'm_add_use_same_address',
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
                                    'm_add_site_id' => [
                                        'name'     => 'm_add_site_id',
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
                                    'm_add_use_same_address' => [
                                        'name'     => 'm_add_use_same_address',
                                        'required' => false,
                                        'validators' => [
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];