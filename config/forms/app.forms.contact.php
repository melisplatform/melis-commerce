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
                    'meliscommerce_contact_list_import_contacts_form' => [
                        'attributes' => [
                            'name' => 'contact-list-import-contacts',
                            'id' => 'contact-list-import-contacts',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
//                            [
//                                'spec' => [
//                                    'name' => 'separator',
//                                    'type' => 'MelisText',
//                                    'options' => [
//                                        'label' => 'tr_meliscommerce_orders_sperator',
//                                    ],
//                                    'attributes' => [
//                                        'id' => '',
//                                        'value' => ';',
//                                        'maxlength' => '1'
//                                    ],
//                                ],
//                            ],
                            [
                                'spec' => [
                                    'type' => 'File',
                                    'name' => 'contact_file',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_contact_import_csv_file',
                                        'tooltip' => '',
                                        'label_options' => [
                                            'disable_html_escape' => true,
                                        ],
                                        'filestyle_options' => [
                                            'buttonBefore' => true,
                                            'buttonText' => 'tr_meliscommerce_contact_import_choose_file',
                                        ]
                                    ],
                                    'attributes' => [
                                        'id' => 'contact_file',
                                        'required' => true,
                                        'class' => 'form-control'
                                    ],
                                ]
                            ],
                        ],
                        'input_filter' => [
                            'contact_file' => [
                                'name' => 'contact_file',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_clients_common_empty_file',
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