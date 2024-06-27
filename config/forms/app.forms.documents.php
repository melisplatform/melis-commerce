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
                'meliscommerce_documents' => [
                    'meliscommerce_documents_file_upload_form' => [
                        'attributes' => [
                            'name' => 'frmDocAddFile',
                            'id' => '',
                            'class' => 'frmDocAddFile',
                            'method' => 'POST',
                            'enctype' => 'multipart/form-data',
                            'data-upload-type' => 'file',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'doc_id',
                                    'type' => 'text',
                                    'attributes' => [
                                        'id' => 'doc_id',
                                        'style' => 'display:none',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'doc_path',
                                    'type' => 'file',
                                    'attributes' => [
                                        'id' => 'upload',
                                        'value' => '',
                                        'class' => 'filestyle',
                                        'label' => 'Upload',
                                        //'required' => 'required',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'doc_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_documents_upload_doc_name',
                                        'tooltip' => 'tr_meliscommerce_documents_upload_doc_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'doc_name',
                                        'placeholder' => 'tr_meliscommerce_documents_upload_doc_name',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'doc_type_id',
                                    'type' => 'EcomDocumentFileSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_documents_main_information_upload_select_type',
                                        'tooltip' => 'tr_meliscommerce_documents_main_information_upload_select_type tooltip',
                                        'empty_option' => 'tr_meliscommerce_categories_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'doc_type_id',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'rdoc_country_id',
                                    'type' => 'EcomCountriesSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_documents_main_information_update_file_country',
                                        'tooltip' => 'tr_meliscommerce_documents_main_information_update_file_country tooltip',
                                        'empty_option' => 'tr_meliscommerce_categories_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'rdoc_country_id',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'doc_path' => [
                                'name'     => 'doc_path',
                                'required' => false,
                                'validators' => [
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'doc_name' => [
                                'name' => 'doc_name',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_upload_doc_name_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_upload_doc_name_long',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'doc_type_id' => [
                                'name'     => 'doc_type_id',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_form_type_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'rdoc_country_id' => [
                                'name'     => 'rdoc_country_id',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_form_country_empty',
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
                    'meliscommerce_documents_image_upload_form' => [
                        'attributes' => [
                            'name' => 'frmDocAddImage',
                            'class' => 'frmDocAddFile',
                            'method' => 'POST',
                            'enctype' => 'multipart/form-data',
                            'data-upload-type' => 'image',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'doc_id',
                                    'type' => 'text',
                                    'attributes' => [
                                        'id' => 'doc_id',
                                        'style' => 'display:none',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'doc_path',
                                    'type' => 'file',
                                    'attributes' => [
                                        'id' => 'doc_path',
                                        'value' => '',
                                        'class' => 'filestyle',
                                        'onchange' => 'imagePreview(".imgDocThumbnail", this);',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'doc_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_documents_upload_doc_name',
                                        'tooltip' => 'tr_meliscommerce_documents_upload_doc_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'doc_name',
                                        'placeholder' => 'tr_meliscommerce_documents_upload_doc_name',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'doc_subtype_id',
                                    'type' => 'EcomDocumentImageTypeSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_documents_main_information_upload_select_type',
                                        'tooltip' => 'tr_meliscommerce_documents_main_information_upload_select_type tooltip',
                                        'empty_option' => 'tr_meliscommerce_documents_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'doc_subtype_id',
                                        'value' => '',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'rdoc_country_id',
                                    'type' => 'EcomCountriesSelect',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_documents_main_information_update_file_country',
                                        'tooltip' => 'tr_meliscommerce_documents_main_information_update_file_country tooltip',
                                        'empty_option' => 'tr_meliscommerce_categories_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ],
                                    'attributes' => [
                                        'id' => 'rdoc_country_id',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'upload' => [
                                'name'     => 'doc_path',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_form_file_type_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'doc_name' => [
                                'name' => 'doc_name',
                                'required' => false,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_upload_doc_name_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_upload_doc_name_long',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters' => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'type' => [
                                'name'     => 'doc_subtype_id',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_form_type_empty',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'country' => [
                                'name'     => 'rdoc_country_id',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_form_country_empty',
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
                    // image type form
                    'meliscommerce_documents_image_type_form' => [
                        'attributes' => [
                            'name' => 'frmDocAddImageType',
                            'class' => 'frmDocAddImageType',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'dtype_code',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_documents_image_type_code',
                                        'tooltip' => 'tr_meliscommerce_documents_image_type_code tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'dtype_code',
                                        'value' => '',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                            [
                                'spec' => [
                                    'name' => 'dtype_name',
                                    'type' => 'MelisText',
                                    'options' => [
                                        'label' => 'tr_meliscommerce_documents_image_type_name',
                                        'tooltip' => 'tr_meliscommerce_documents_image_type_name tooltip',
                                    ],
                                    'attributes' => [
                                        'id' => 'dtype_name',
                                        'value' => '',
                                        'required' => 'required',
                                    ],
                                ],
                            ],
                        ],
                        'input_filter' => [
                            'dtype_code' => [
                                'name'     => 'dtype_code',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_image_type_code_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_image_type_code_long',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'dtype_name' => [
                                'name'     => 'dtype_name',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_image_type_name_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_image_type_name_long',
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
                    'custom_filters' => [
                        'file' => [
                            'dtype_name' => [
                                'name'     => 'dtype_name',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_image_type_name_empty_file',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_image_type_name_long',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'dtype_code' => [
                                'name'     => 'dtype_code',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_image_type_code_empty_file',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_image_type_code_long',
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
                        'image' => [
                            'dtype_name' => [
                                'name'     => 'dtype_name',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_image_type_name_empty_image',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_image_type_name_long',
                                            ],
                                        ],
                                    ],
                                ],
                                'filters'  => [
                                    ['name' => 'StripTags'],
                                    ['name' => 'StringTrim'],
                                ],
                            ],
                            'dtype_code' => [
                                'name'     => 'dtype_code',
                                'required' => true,
                                'validators' => [
                                    [
                                        'name' => 'NotEmpty',
                                        'options' => [
                                            'messages' => [
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_image_type_code_empty',
                                            ],
                                        ],
                                    ],
                                    [
                                        'name'    => 'StringLength',
                                        'options' => [
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => [
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_image_type_code_long',
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
                ],
            ],
        ],
    ],
];
