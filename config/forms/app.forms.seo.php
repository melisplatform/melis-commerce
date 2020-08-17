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
                'meliscommerce_seo' => [
                    'meliscommerce_seo_form' => [
                        'attributes' => [
                            'name' => 'seoForm',
                            'id' => 'seoForm',
                            'method' => '',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'eseo_id',
                                    'type' => 'hidden',
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'eseo_lang_id',
                                    'type' => 'hidden',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_seo_Lang_id',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'eseo_page_id',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_seo_Page_id',
                                        'tooltip' => 'tr_meliscommerce_seo_Page_id tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'eseo_page_id',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'eseo_meta_title',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_seo_Meta_title',
                                        'tooltip' => 'tr_meliscommerce_seo_Meta_title tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'eseo_meta_title',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'eseo_meta_description',
                                    'type' => 'Textarea',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_seo_Meta_description',
                                        'tooltip' => 'tr_meliscommerce_seo_Meta_description tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'eseo_meta_description',
                                        'rows' => 5,
                                        'class' => 'melis-seo-desc form-control'
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'eseo_url',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_seo_Url',
                                        'tooltip' => 'tr_meliscommerce_seo_Url tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'eseo_url',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'eseo_url_redirect',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_seo_Url_redirect',
                                        'tooltip' => 'tr_meliscommerce_seo_Url_redirect tooltip',
                                        'label_options' => [
                                            'disable_html_escape' => true,
                                        ],
                                    ],
                                    'attributes' => [
                                        'id' => 'eseo_url_redirect',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'eseo_url_301',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_seo_Url_301',
                                        'tooltip' => 'tr_meliscommerce_seo_Url_301 tooltip',
                                        'label_options' => [
                                            'disable_html_escape' => true,
                                        ],
                                    ],
                                    'attributes' => [
                                        'id' => 'eseo_url_301',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'eseo_lang_id' => [
                                'name'     => 'eseo_lang_id',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_seo_Seo_input_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'eseo_page_id' => [
                                'name'     => 'eseo_page_id',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'IsInt',
                                        'options' => [
                                            'messages' => [
                                                Laminas\I18n\Validator\IsInt::NOT_INT => 'tr_meliscommerce_seo_Page_id_invalid',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'eseo_meta_title' => [
                                'name'     => 'eseo_meta_title',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_seo_err_title_too_long_255',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'eseo_meta_description' => [
                                'name'     => 'eseo_meta_description',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_seo_err_desc_too_long_255',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'eseo_url' => [
                                'name'     => 'eseo_url',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_seo_err_url_too_long_255',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'eseo_url_redirect' => [
                                'name'     => 'eseo_url_redirect',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_seo_err_url_redirect_too_long_255',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'eseo_url_301' => [
                                'name'     => 'eseo_url_301',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_seo_err_url_301_too_long_255',
                                            ],
                                        ],
                                    ],
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