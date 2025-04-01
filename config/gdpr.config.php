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
            'gdpr' => [
                'tags' => [
                    // 'EMAIL'  => 'cper_email',
                    // 'LAST_NAME'  => 'cper_name',
                    'FIRST_NAME'  => 'cper_firstname',
                    // 'MIDDLE_NAME'  => 'cper_middle_name',
                    'URL_VALIDATION'  => '%revalidation_link%',
                ],
                'override_columns' => [
                    'contact',
                    'address',
                ],
                'columns' => [
                    'contact' => [
                        'cper_email',
                        'cper_name',
                        'cper_middle_name',
                        'cper_firstname',
                        'cper_tel_mobile',
                        'cper_tel_landline'
                    ],
                    'address' => [
                        'cadd_address_name',
                        'cadd_name',
                        'cadd_middle_name',
                        'cadd_firstname',
                        'cadd_num',
                        'cadd_stairs',
                        'cadd_building_name',
                        'cadd_company',
                        'cadd_street',
                        'cadd_zipcode',
                        'cadd_city',
                        'cadd_state',
                        'cadd_country',
                        'cadd_complementary',
                        'cadd_phone_mobile',
                        'cadd_phone_landline',
                    ]
                ]
            ]
        ],
        'MelisCommerce' => [
            'gdpr' => [
                'getUserInfo' => [
                    'icon' => 'fa fa-user-plus',
                    'moduleName' => 'MelisCommerce',
                    'values' => [
                        'columns' => [
                            'cper_email' => [
                                'id' => 'meliscommerce_cper_email',
                                'class' => '',
                                'style' => 'width:1%;',
                                'text' => 'Email',
                                'sorting' => false
                            ],
                            'cper_fullname' => [
                                'id' => 'meliscommerce_cper_firstname',
                                'class' => '',
                                'style' => 'padding-left:70px !important;',
                                'text' => 'Name',
                                'sorting' => false
                            ]
                        ],
                    ],
                ],
                'extract' => [
                    'columns' => [
                        'cper_id' => [
                            'text' => 'id'
                        ],
                        'cper_email' => [
                            'text' => 'email'
                        ],
                        'cper_name' => [
                            'text' => 'name'
                        ],
                        'cper_firstname' => [
                            'text' => 'firtname'
                        ],
                        'cper_tel_mobile' => [
                            'text' => 'mobile number'
                        ],
                        'cper_tel_landline' => [
                            'text' => 'telephone number'
                        ]
                    ],
                ],
            ],
        ]
    ]
];
