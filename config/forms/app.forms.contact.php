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
                'meliscommerce_contact' => [
                    'meliscommerce_contact_list_export_contacts_form' => [
                        'attributes' => [
                            'name' => 'contact-list-export-contacts',
                            'id' => 'contact-list-export-contacts',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'separator',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_orders_sperator',
                                    ],
                                    'attributes' => [
                                        'id' => '',
                                        'value' => ';',
                                        'maxlength' => '1'
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'separator' => [
                                'name' => 'separator',
                                'require' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_address_error_empty',
                                            ],
                                        ],
                                    ]
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                        ],
                    ],
                ]
            ]
        ]
    ]
];