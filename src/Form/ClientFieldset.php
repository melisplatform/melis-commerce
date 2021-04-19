<?php

namespace MelisCommerce\Form;

use MelisCommerce\Entity\Form\Client;
use Laminas\Form\Element;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Hydrator\ClassMethods as ClassMethodsHydrator;
use Laminas\ServiceManager\ServiceManager;

class ClientFieldset extends Fieldset implements InputFilterProviderInterface
{
    private $serviceManager;

    public function __construct(ServiceManager $serviceManager)
    {
        parent::__construct('client');

        $this->serviceManager = $serviceManager;

        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setObject(new Client());

        // Form Factory set service FormElementManager to retrieve 
        // form elements factories on config
        $formManager = $serviceManager->get('FormElementManager');
        $this->getFormFactory()->setFormElementManager($formManager);

        $this->add([
            'name' => 'cli_id',
            'options' => [
                'label' => 'cli_id',
            ],
            'attributes' => [
                'id' => 'cli_id',
                'placeholder' => 'Client ID',
                'style' => 'width: 10%',
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cli_status',
            'type' => 'Select',
            'options' => [
                'label' => 'cli_status',
                'value_options' => [
                    1 => 'Active',
                    0 => 'Inactive',
                ],
                'empty_option' => '- Select Client Status -',
            ],
            'attributes' => [
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cli_group_id',
            // 'type' => 'EcomClientsGroupSelect',
            'options' => [
                'label' => 'cli_group_id',
                'empty_option' => '- Select Client Group -',
            ],
            'attributes' => [
                'data-type' => 'int',
            ],
        ]);

        $this->add([
            'name' => 'cli_country_id',
            'type' => 'EcomCountriesNoAllCountriesSelect',
            'options' => [
                'label' => 'cli_country_id',
                'empty_option' => '- Select Client Country -',
            ],
            'attributes' => [
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
        return [
            'cli_id' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => 'IsInt',
                        'options' => [
                            'messages' => [
                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID should be in numeric'
                            ],
                        ],
                    ],
                    [
                        'name' => 'IsInt',
                        'options' => [
                            'messages' => [
                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client ID should be in numeric'
                            ],
                        ],
                    ],
                ],
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim']
                ],
            ],
            'cli_status' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'message' => [
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'Client status should not be empty'
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
                    ],
                ],
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim']
                ],
            ],
            'cli_group_id' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'message' => [
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'Client Group ID should not be empty'
                            ],
                        ],
                    ],
                    [
                        'name' => 'IsInt',
                        'options' => [
                            'messages' => [
                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client Group ID should be in numeric'
                            ],
                        ],
                    ],
                    [
                        'name' => 'DbRecordExists',
                        'options' => [
                            'table' => 'melis_ecom_client_groups',
                            'field' => 'cgroup_id',
                            'adapter' => $this->serviceManager->get(\Laminas\Db\Adapter\Adapter::class),
                            'messages'=> [
                                \Laminas\Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'You entered an invalid value'
                            ],
                        ],
                    ]
                ],
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim']
                ],
            ],
            'cli_country_id' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'message' => [
                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'Client Country ID should not be empty'
                            ],
                        ],
                    ],
                    [
                        'name' => 'IsInt',
                        'options' => [
                            'messages' => [
                                \Laminas\I18n\Validator\IsInt::NOT_INT => 'Client Country ID should be in numeric'
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
                                \Laminas\Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'You entered an invalid value'
                            ],
                        ],
                    ]
                ],
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim']
                ],
            ],
        ];
    }
}