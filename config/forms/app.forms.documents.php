<?php
return array(
    'plugins' => array(
        'meliscommerce' => array(
            'forms' => array(
                'meliscommerce_documents' => array(
                    'meliscommerce_documents_file_upload_form' => array(
                        'attributes' => array(
                            'name' => 'frmDocAddFile',
                            'id' => '',
                            'class' => 'frmDocAddFile',
                            'method' => 'POST',
                            'enctype' => 'multipart/form-data',
                            'data-upload-type' => 'file',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'doc_id',
                                    'type' => 'text',
                                    'attributes' => array(
                                        'id' => 'doc_id',
                                        'style' => 'display:none',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'doc_path',
                                    'type' => 'file',
                                    'attributes' => array(
                                        'id' => 'upload',
                                        'value' => '',
                                        'class' => 'filestyle',
                                        'label' => 'Upload',
                                        //'required' => 'required',
                                    ),
                                ),
                            ), 
                            array(
                                'spec' => array(
                                    'name' => 'doc_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_documents_upload_doc_name',
                                    ),
                                    'attributes' => array(
                                        'id' => 'doc_name',
                                        'placeholder' => 'tr_meliscommerce_documents_upload_doc_name',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'doc_type_id',
                                    'type' => 'EcomDocumentFileSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_documents_main_information_upload_select_type',
                                        'empty_option' => 'tr_meliscommerce_categories_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'doc_type_id',
                                        //'required' => 'required',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'rdoc_country_id',
                                    'type' => 'EcomCountriesSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_documents_main_information_update_file_country',
                                        'empty_option' => 'tr_meliscommerce_categories_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'rdoc_country_id',
                                        //'required' => 'required',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'doc_path' => array(
                                'name'     => 'doc_path',
                                'required' => false,
                                'validators' => array(
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'doc_name' => array(
                                'name' => 'doc_name',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_upload_doc_name_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_upload_doc_name_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters' => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'doc_type_id' => array(
                                'name'     => 'doc_type_id',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_form_type_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'rdoc_country_id' => array(
                                'name'     => 'rdoc_country_id',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_form_country_empty',
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
                    ),
                    'meliscommerce_documents_image_upload_form' => array(
                        'attributes' => array(
                            'name' => 'frmDocAddImage',
                            'class' => 'frmDocAddFile',
                            'method' => 'POST',
                            'enctype' => 'multipart/form-data',
                            'data-upload-type' => 'image',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'doc_id',
                                    'type' => 'text',
                                    'attributes' => array(
                                        'id' => 'doc_id',
                                        'style' => 'display:none',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'doc_path',
                                    'type' => 'file',
                                    'attributes' => array(
                                        'id' => 'doc_path',
                                        'value' => '',
                                        'class' => 'filestyle',
                                        'onchange' => 'imagePreview(".imgDocThumbnail", this);',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'doc_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_documents_upload_doc_name',
                                    ),
                                    'attributes' => array(
                                        'id' => 'doc_name',
                                        'placeholder' => 'tr_meliscommerce_documents_upload_doc_name',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'doc_subtype_id',
                                    'type' => 'EcomDocumentImageTypeSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_documents_main_information_upload_select_type',
                                        'empty_option' => 'tr_meliscommerce_documents_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'doc_subtype_id',
                                        'value' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'rdoc_country_id',
                                    'type' => 'EcomCountriesSelect',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_documents_main_information_update_file_country',
                                        'empty_option' => 'tr_meliscommerce_categories_common_label_choose',
                                        'disable_inarray_validator' => true,
                                    ),
                                    'attributes' => array(
                                        'id' => 'rdoc_country_id',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'upload' => array(
                                'name'     => 'doc_path',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_form_file_type_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'doc_name' => array(
                                'name' => 'doc_name',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_upload_doc_name_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_upload_doc_name_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters' => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'type' => array(
                                'name'     => 'doc_subtype_id',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_form_type_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'country' => array(
                                'name'     => 'rdoc_country_id',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_form_country_empty',
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
                    ),
                    // image type form
                    'meliscommerce_documents_image_type_form' => array(
                        'attributes' => array(
                            'name' => 'frmDocAddImageType',
                            'class' => 'frmDocAddImageType',
                            'method' => 'POST',
                            'action' => '',
                        ),
                        'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'dtype_code',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_documents_image_type_code',
                                    ),
                                    'attributes' => array(
                                        'id' => 'dtype_code',
                                        'value' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'dtype_name',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscommerce_documents_image_type_name',
                                    ),
                                    'attributes' => array(
                                        'id' => 'dtype_name',
                                        'value' => '',
                                    ),
                                ),
                            ),
                        ),
                        'input_filter' => array(
                            'dtype_code' => array(
                                'name'     => 'dtype_code',
                                'required' => true,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_image_type_code_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 10,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_image_type_code_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'dtype_name' => array(
                                'name'     => 'dtype_name',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_documents_image_type_name_empty',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 45,
                                            'messages' => array(
                                                \Zend\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_documents_image_type_name_long',
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
                    ),
                ),
            ),
        ),
    ),
);
