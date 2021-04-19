<?php

namespace MelisCommerce\Form;

use MelisCommerce\Entity\Form\Person;
use Laminas\Form\Element;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Hydrator\ClassMethods as ClassMethodsHydrator;
use Laminas\ServiceManager\ServiceManager;

class PersonFieldset extends Fieldset implements InputFilterProviderInterface
{
    private $serviceManager;

    private $passwordRequired = false;

    public function __construct(ServiceManager $serviceManager)
    {
        parent::__construct('person');

        $this->serviceManager = $serviceManager;

        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setObject(new Person());

        // Form Factory set service FormElementManager to retrieve 
        // form elements factories on config
        $formManager = $serviceManager->get('FormElementManager');
        $this->getFormFactory()->setFormElementManager($formManager);

        $this->add([
            'name' => 'cper_id',
            'options' => [
                'label' => 'cper_id',
            ],
            'attributes' => [
                'id' => 'cper_id',
                'placeholder' => 'Person ID',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_lang_id',
            'type' => 'EcomLanguageSelect',
            'options' => [
                'label' => 'cper_lang_id',
                'empty_option' => '- Select Language -',
            ],
            'attributes' => [
                'id' => 'cper_lang_id',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_status',
            'type' => 'Select',
            'options' => [
                'label' => 'cper_status',
                'value_options' => [
                    1 => 'Active',
                    0 => 'Inactive',
                ],
                'empty_option' => '- Select Status -',
            ],
            'attributes' => [
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_is_main_person',
            'type' => 'Select',
            'options' => [
                'label' => 'cper_is_main_person',
                'value_options' => [
                    1 => 'Yes',
                    0 => 'No',
                ],
                'empty_option' => '- Select Status -',
            ],
            'attributes' => [
                'id' => 'cper_is_main_person',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_email',
            'options' => [
                'label' => 'cper_email',
            ],
            'attributes' => [
                'id' => 'cper_email',
                'placeholder' => 'Email Address',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);

        

        $this->add([
            'name' => 'cper_password',
            'options' => [
                'label' => 'cper_password',
            ],
            'attributes' => [
                'id' => 'cper_password',
                'type' => 'password',
                'placeholder' => 'Password',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_civility',
            'type' => 'EcomCivilitySelect',
            'options' => [
                'label' => 'cper_civility',
                'empty_option' => '- Select Civility -',
            ],
            'attributes' => [
                'id' => 'cper_civility',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_name',
            'options' => [
                'label' => 'Person Name',
            ],
            'attributes' => [
                'id' => 'cper_name',
                'placeholder' => 'Main Name',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_firstname',
            'options' => [
                'label' => 'Person First Name',
            ],
            'attributes' => [
                'id' => 'cper_firstname',
                'placeholder' => 'Main First Name',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_middle_name',
            'options' => [
                'label' => 'Person Middle Name',
            ],
            'attributes' => [
                'id' => 'cper_middle_name',
                'placeholder' => 'Main Middle Name',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_job_title',
            'options' => [
                'label' => 'Person Job Title',
            ],
            'attributes' => [
                'id' => 'cper_job_title',
                'placeholder' => 'Main Job Title',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_job_service',
            'options' => [
                'label' => 'Person Job Service',
            ],
            'attributes' => [
                'id' => 'cper_job_service',
                'placeholder' => 'Main Job Service',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_tel_mobile',
            'options' => [
                'label' => 'Person Mobile No.',
            ],
            'attributes' => [
                'id' => 'cper_tel_mobile',
                'placeholder' => 'Main Mobile No.',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cper_tel_landline',
            'options' => [
                'label' => 'Person Telephone No.',
            ],
            'attributes' => [
                'id' => 'cper_tel_landline',
                'placeholder' => 'Main Telephone No.',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);
    }

    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        // if (empty($this->get('cper_id')->getValue()) && $this->serviceManager->get('Request')->isPost()) {
        //     // dump($this->get('cper_id')->getValue());

        //     $this->passwordRequired = true;
        // }

        return [
            'cper_id' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => 'IsInt',
                        'options' => [
                            'messages' => [
                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'tr_meliscommerce_common_input_numeric'
                            ],
                        ],
                    ],
                    [
                        'name' => 'DbRecordExists',
                        'options' => [
                            'table' => 'melis_ecom_client_person',
                            'field' => 'cper_id',
                            'adapter' => $this->serviceManager->get(\Laminas\Db\Adapter\Adapter::class),
                            'messages'=> [
                                \Laminas\Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'You entered an invalid Person ID'
                            ],
                        ],
                    ],
                    [
                        // @TODO if person has ID the password would be optional 
                        'name' => \MelisCommerce\Validator\MelisClientNewPersonPasswordValidator::class,
                        'options' => []
                    ]
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cper_lang_id' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'message' => [
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_common_input_required'
                            ],
                        ],
                    ],
                    [
                        'name' => 'IsInt',
                        'options' => [
                            'messages' => [
                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'tr_meliscommerce_common_input_numeric'
                            ],
                        ],
                    ],
                    [
                        'name' => 'DbRecordExists',
                        'options' => [
                            'table' => 'melis_ecom_country',
                            'field' => 'ctry_id',
                            'adapter' => $this->serviceManager->get(\Laminas\Db\Adapter\Adapter::class),
                            'messages'=> [
                                \Laminas\Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'tr_meliscommerce_common_input_db_exists_invalid'
                            ],
                        ],
                    ]
                ],
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim']
                ],
            ],
            'cper_status' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'message' => [
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_common_input_required'
                            ],
                        ],
                    ],
                    [
                        'name' => 'inArray',
                        'options' => [
                            'haystack' => [1, 2],
                            'messages'=> [
                                \Laminas\Validator\InArray::NOT_IN_ARRAY => 'Invalid input, must be 1(active) or 0(inactive)'
                            ],
                        ],
                    ]
                ],
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim']
                ],
            ],
            'cper_is_main_person' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'message' => [
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_common_input_required'
                            ],
                            
                        ],
                    ],
                    [
                        'name' => 'inArray',
                        'options' => [
                            'haystack' => [1, 0],
                            'messages'=> [
                                \Laminas\Validator\InArray::NOT_IN_ARRAY => 'Invalid input, must be 1(Yes) or 0(No)'
                            ],
                        ],
                    ],
                    [
                        'name' => \MelisCommerce\Validator\MelisClientMainPersonValidator::class,
                        'options' => [
                            'dataIndex' => 'persons',
                            'dataIndexTarget' => 'cper_is_main_person',
                            'serviceManager' => $this->serviceManager
                        ],
                    ]
                ],
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim']
                ],
            ],
            'cper_email' => [
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
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_common_input_required',
                            ],
                        ],
                    ],
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cper_password' => [
                'required' => false,//$this->passwordRequired,
                'validators' => [
                    [
                        'name' => '\MelisCommerce\Validator\MelisPasswordValidator',
                        'options' => [
                            'token' => 'cper_id',
                            'min' => 8,
                            'messages' => [
                                \MelisCommerce\Validator\MelisPasswordValidator::TOO_SHORT => 'tr_meliscommerce_client_Contact_password_error_low',
                                \MelisCommerce\Validator\MelisPasswordValidator::NO_DIGIT => 'tr_meliscommerce_client_Contact_password_regex_not_match',
                                \MelisCommerce\Validator\MelisPasswordValidator::NO_LOWER => 'tr_meliscommerce_client_Contact_password_regex_not_match',
                            ],
                        ],
                    ],
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
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_common_input_required',
                            ],
                        ],
                    ],
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cper_civility' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'messages' => [
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_common_input_required',
                            ],
                        ],
                    ],
                    [
                        'name' => 'DbRecordExists',
                        'options' => [
                            'table' => 'melis_ecom_civility',
                            'field' => 'civ_id',
                            'adapter' => $this->serviceManager->get(\Laminas\Db\Adapter\Adapter::class),
                            'messages'=> [
                                \Laminas\Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'tr_meliscommerce_common_input_db_exists_invalid'
                            ],
                        ],
                    ]
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cper_name' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'messages' => [
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_common_input_required',
                            ],
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'max'      => 255,
                            'messages' => [
                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_common_input_long_255',
                            ],
                        ],
                    ],
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cper_firstname' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'messages' => [
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscommerce_common_input_required',
                            ],
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'max'      => 255,
                            'messages' => [
                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_common_input_long_255',
                            ],
                        ],
                    ],
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cper_middle_name' => [
                'required' => false,
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'max'      => 255,
                            'messages' => [
                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_common_input_long_255',
                            ],
                        ],
                    ],
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cper_job_title' => [
                'required' => false,
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'max'      => 80,
                            'messages' => [
                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_common_input_long_80',
                            ],
                        ],
                    ],
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cper_job_service' => [
                'required' => false,
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'max'      => 80,
                            'messages' => [
                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_common_input_long_80',
                            ],
                        ],
                    ],
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cper_tel_mobile' => [
                'required' => false,
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'max'      => 45,
                            'messages' => [
                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_common_input_long_45',
                            ],
                        ],
                    ],
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'cper_job_service' => [
                'required' => false,
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'max'      => 45,
                            'messages' => [
                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscommerce_common_input_long_45',
                            ],
                        ],
                    ],
                ],
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
        ];
    }
}