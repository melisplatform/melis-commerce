<?php 

return [
	'plugins' => [
		'microservice' => [
			//Module Name
			'MelisCommerce' => [ 
				//MelisCmsSliderService.php
				'MelisComClientService' => [
					/**
					 *  method getClientList
					 * 	@param countryId
					 * 	@param dateCreationMin
					 * 	@param dateCreationMax
					 * 	@param onlyValid
					 * 	@param start
					 * 	@param limit
					 * 	@param order
					 * 	@param search
					 */
					'getClientList' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'countryId',
									'type' => 'Text',
									'options' => [
										'label' => 'countryId',
									],
									'attributes' => [
										'id' => 'countryId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Enter Country ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'dateCreationMin',
									'type' => 'Text',
									'options' => [
										'label' => 'dateCreationMin',
									],
									'attributes' => [
										'id' => 'dateCreationMin',
										'value' => '',
										'class' => '',
										'placeholder' => 'Enter the minimum Date creation',
										'style' => 'width: 10%',
										'data-type' => 'mm/dd/yyyy',
									],
								],
							],
							[
								'spec' => [
									'name' => 'dateCreationMax',
									'type' => 'Text',
									'options' => [
										'label' => 'dateCreationMax',
									],
									'attributes' => [
										'id' => 'dateCreationMax',
										'value' => '',
										'class' => '',
										'placeholder' => 'Enter the maximum Date creation',
										'style' => 'width: 10%',
										'data-type' => 'mm/dd/yyyy',
									],
								],
							],
							[
								'spec' => [
									'name' => 'onlyValid',
									'type' => 'Select',
									'options' => [
										'label' => 'onlyValid',
										'value_options' => [
											1 => 'True',
											0 => 'False',
										],
										'empty_option' => '-Select-',
										'disable_inarray_validator' => true,
									],
									'attributes' => [
										'id' => 'onlyValid',
										'value' => null,
										'class' => '',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'start',
									'type' => 'Text',
									'options' => [
										'label' => 'start',
									],
									'attributes' => [
										'id' => 'start',
										'value' => '',
										'class' => '',
										'placeholder' => 'Starting position of the list',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'limit',
									'type' => 'Text',
									'options' => [
										'label' => 'limit',
									],
									'attributes' => [
										'id' => 'limit',
										'value' => '',
										'class' => '',
										'placeholder' => 'Limit number of the list',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'order',
									'type' => 'Select',
									'options' => [
										'label' => 'order',
										'value_options' => [
											'asc' => 'Ascending',
											'desc' => 'Descending',
										],
										'empty_option' => '-Select-',
									],
									'attributes' => [
										'id' => 'order',
										'value' => null,
										'class' => '',
										'style' => 'width: 10%',
										'data-type' => 'string',
									],
								],
							],
							[
								'spec' => [
									'name' => 'search',
									'type' => 'Text',
									'options' => [
										'label' => 'search',
									],
									'attributes' => [
										'id' => 'search',
										'value' => '',
										'class' => '',
										'placeholder' => 'Search',
										'style' => 'width: 10%',
										'data-type' => 'string',
									],
								],
							],
						],
						'input_filter' => [
							'countryId' => [
								'name' => 'countryId',
								'required' => false,
								'validators' => [
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Country ID should be in numeric or empty'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'dateCreationMin' => [
								'name' => 'dateCreationMin',
								'required' => false,
								'validators' => [
									[
										'name' => 'Regex',
										'options' => [
											'pattern' => '/^(((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01])\/((19|[2-9]\d)\d{2}))|((0[13456789]|1[012])\/(0[1-9]|[12]\d|30)\/((19|[2-9]\d)\d{2}))|(02\/(0[1-9]|1\d|2[0-8])\/((19|[2-9]\d)\d{2}))|(02\/29\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/',
											'messages'=> [
												\Laminas\Validator\Regex::NOT_MATCH => 'Date format must be "mm/dd/yyyy"'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'dateCreationMax' => [
								'name' => 'dateCreationMax',
								'required' => false,
								'validators' => [
									[
										'name' => 'Regex',
										'options' => [
											'pattern' => '/^(((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01])\/((19|[2-9]\d)\d{2}))|((0[13456789]|1[012])\/(0[1-9]|[12]\d|30)\/((19|[2-9]\d)\d{2}))|(02\/(0[1-9]|1\d|2[0-8])\/((19|[2-9]\d)\d{2}))|(02\/29\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/',
											'messages'=> [
												\Laminas\Validator\Regex::NOT_MATCH => 'Date format must be "mm/dd/yyyy"'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'onlyValid' => [
								'name' => 'onlyValid',
								'required' => false,
								'validators' => [
									[
										'name' => 'inArray',
										'options' => [
											'haystack' => [null, 1, 0, ''],
											'messages'=> [
												\Laminas\Validator\InArray::NOT_IN_ARRAY => 'Invalid input, must be 1, 2 or empty'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'start' => [
								'name' => 'start',
								'required' => false,
								'validators' => [
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Start should be in numeric or empty'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'limit' => [
								'name' => 'limit',
								'required' => false,
								'validators' => [
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Limit should be in numeric or empty'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'order' => [
								'name' => 'order',
								'required' => false,
								'validators' => [
									[
										'name' => 'inArray',
										'options' => [
											'haystack' => ['asc', 'desc'],
											'messages'=> [
												\Laminas\Validator\InArray::NOT_IN_ARRAY => 'Order value must in "asc" in "desc"'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'search' => [
								'name' => 'search',
								'required' => false,
								'validators' => [],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method getClientByIdAndClientPerson
					 * 	@param clientId
					 * 	@param personId
					 * 	@param personEmail
					 */
					'getClientByIdAndClientPerson' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'clientId',
									'type' => 'Text',
									'options' => [
										'label' => 'clientId',
									],
									'attributes' => [
										'id' => 'clientId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Client ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'personId',
									'type' => 'Text',
									'options' => [
										'label' => 'personId',
									],
									'attributes' => [
										'id' => 'personId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Contact ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'personEmail',
									'type' => 'Text',
									'options' => [
										'label' => 'personEmail',
									],
									'attributes' => [
										'id' => 'personEmail',
										'value' => '',
										'class' => '',
										'placeholder' => 'Contact Email Address',
										'style' => 'width: 10%',
										'data-type' => 'string',
									],
								],
							],
						],
						'input_filter' => [
							'clientId' => [
								'name' => 'clientId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Client ID should not be empty'
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
							'personId' => [
								'name' => 'personId',
								'required' => false,
								'validators' => [
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Contact ID should be in numeric or empty'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'personEmail' => [
								'name' => 'personEmail',
								'required' => false,
								'validators' => [
									[
										'name' => 'EmailAddress',
										'options' => [
											'domain'   => 'true',
											'hostname' => 'true',
											'mx'       => 'true',
											'deep'     => 'true',
											'message'  => 'Email Address is invalid',
										]
									]
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method getClientPersonById
					 * 	@param personId
					 */
					'getClientPersonById' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'personId',
									'type' => 'Text',
									'options' => [
										'label' => 'personId',
									],
									'attributes' => [
										'id' => 'personId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Contact ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'personId' => [
								'name' => 'personId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact ID should not be empty'
											],
										],
									],
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Contact ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method getClientMainPersonByClientId
					 * 	@param clientId
					 * 	@param langId
					 */
					'getClientMainPersonByClientId' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'clientId',
									'type' => 'Text',
									'options' => [
										'label' => 'clientId',
									],
									'attributes' => [
										'id' => 'clientId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Client ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'langId',
									'type' => 'Text',
									'options' => [
										'label' => 'langId',
									],
									'attributes' => [
										'id' => 'langId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Language ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'clientId' => [
								'name' => 'clientId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Client ID should not be empty'
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
							'langId' => [
								'name' => 'langId',
								'required' => false,
								'validators' => [
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Language ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method getClientAddressesByClientId
					 * 	@param clientId
					 * 	@param addressType
					 */
					'getClientAddressesByClientId' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'clientId',
									'type' => 'Text',
									'options' => [
										'label' => 'clientId',
									],
									'attributes' => [
										'id' => 'clientId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Client ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'addressType',
									'type' => 'Select',
									'options' => [
										'label' => 'addressType',
										'value_options' => [
											1 => 'Billing',
											2 => 'Delivery',
										],
										'empty_option' => '-Select-',
										'disable_inarray_validator' => true,
									],
									'attributes' => [
										'id' => 'addressType',
										'value' => null,
										'class' => '',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'clientId' => [
								'name' => 'clientId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Client ID should not be empty'
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
							'addressType' => [
								'name' => 'addressType',
								'required' => false,
								'validators' => [
									[
										'name' => 'inArray',
										'options' => [
											'haystack' => [null, 1, 2, ''],
											'messages'=> [
												\Laminas\Validator\InArray::NOT_IN_ARRAY => 'Invalid input, must be 1, 2 or empty'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method getClientAddressesByClientPersonId
					 * 	@param clientId
					 * 	@param addressType
					 * 	@param caddId
					 */
					'getClientAddressesByClientPersonId' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'clientId',
									'type' => 'Text',
									'options' => [
										'label' => 'clientId',
									],
									'attributes' => [
										'id' => 'clientId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Client ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'addressType',
									'type' => 'Select',
									'options' => [
										'label' => 'addressType',
										'value_options' => [
											1 => 'Billing',
											2 => 'Delivery',
										],
										'empty_option' => '-Select-',
										'disable_inarray_validator' => true,
									],
									'attributes' => [
										'id' => 'addressType',
										'value' => null,
										'class' => '',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'caddId',
									'type' => 'Text',
									'options' => [
										'label' => 'caddId',
									],
									'attributes' => [
										'id' => 'caddId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Client Address ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'clientId' => [
								'name' => 'clientId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Client ID should not be empty'
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
							'addressType' => [
								'name' => 'addressType',
								'required' => false,
								'validators' => [
									[
										'name' => 'inArray',
										'options' => [
											'haystack' => [null, 1, 2, ''],
											'messages'=> [
												\Laminas\Validator\InArray::NOT_IN_ARRAY => 'Invalid input, must be 1, 2 or empty'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'caddId' => [
								'name' => 'caddId',
								'required' => false,
								'validators' => [
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Client Address ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method getClientPersonAddressByAddressId
					 * 	@param personId
					 * 	@param addrId
					 */
					'getClientPersonAddressByAddressId' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'personId',
									'type' => 'Text',
									'options' => [
										'label' => 'personId',
									],
									'attributes' => [
										'id' => 'personId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Contact ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'addrId',
									'type' => 'Text',
									'options' => [
										'label' => 'addrId',
									],
									'attributes' => [
										'id' => 'addrId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Contact Address ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'personId' => [
								'name' => 'personId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact ID should not be empty'
											],
										],
									],
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Contact ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'addrId' => [
								'name' => 'addrId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact Address ID should not be empty'
											],
										],
									],
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Contact Address ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method getAddressTransByAddressTypeIdAndLangId
					 * 	@param addTypeId
					 * 	@param langId
					 */
					'getAddressTransByAddressTypeIdAndLangId' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'addTypeId',
									'type' => 'Select',
									'options' => [
										'label' => 'addTypeId',
										'value_options' => [
											1 => 'Billing',
											2 => 'Delivery',
										],
										'empty_option' => '-Select-',
										'disable_inarray_validator' => true,
									],
									'attributes' => [
										'id' => 'addTypeId',
										'value' => null,
										'class' => '',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],

							[
								'spec' => [
									'name' => 'langId',
									'type' => 'Text',
									'options' => [
										'label' => 'langId',
									],
									'attributes' => [
										'id' => 'langId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Language ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'addTypeId' => [
								'name' => 'addTypeId',
								'required' => true,
								'validators' => [
									[
										'name' => 'inArray',
										'options' => [
											'haystack' => [null, 1, 2, ''],
											'messages'=> [
												\Laminas\Validator\InArray::NOT_IN_ARRAY => 'Invalid input, must be 1, 2'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'langId' => [
								'name' => 'langId',
								'required' => false,
								'validators' => [
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Language ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method getCompanyByClientId
					 * 	@param clientId
					 */
					'getCompanyByClientId' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'clientId',
									'type' => 'Text',
									'options' => [
										'label' => 'clientId',
									],
									'attributes' => [
										'id' => 'clientId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Client ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'clientId' => [
								'name' => 'clientId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Client ID should not be empty'
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
						],
					],

					/**
					 *  method getCivilityList
					 * 	@param langId
					 */
					'getCivilityList' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'langId',
									'type' => 'Text',
									'options' => [
										'label' => 'langId',
									],
									'attributes' => [
										'id' => 'langId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Language ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'langId' => [
								'name' => 'langId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Language ID should not be empty'
											],
										],
									],
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Language ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method getCivilityTransByCivilityIdAndLangId
					 * 	@param civilityId
					 * 	@param langId
					 */
					'getCivilityTransByCivilityIdAndLangId' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'civilityId',
									'type' => 'Text',
									'options' => [
										'label' => 'civilityId',
									],
									'attributes' => [
										'id' => 'civilityId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Civility ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
							[
								'spec' => [
									'name' => 'langId',
									'type' => 'Text',
									'options' => [
										'label' => 'langId',
									],
									'attributes' => [
										'id' => 'langId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Language ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'civilityId' => [
								'name' => 'civilityId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Civility ID should not be empty'
											],
										],
									],
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Civility ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'langId' => [
								'name' => 'langId',
								'required' => false,
								'validators' => [
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Language ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method getAddressTypesList
					 * 	@param langId
					 */
					'getAddressTypesList' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'langId',
									'type' => 'Text',
									'options' => [
										'label' => 'langId',
									],
									'attributes' => [
										'id' => 'langId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Language ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'langId' => [
								'name' => 'langId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Language ID should not be empty'
											],
										],
									],
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Language ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					 *  method checkEmailExist
					 * 	@param email
					 * 	@param personId
					 */
					'checkEmailExist' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'email',
									'type' => 'Text',
									'options' => [
										'label' => 'email',
									],
									'attributes' => [
										'id' => 'email',
										'value' => '',
										'class' => '',
										'placeholder' => 'Email Address',
										'style' => 'width: 10%',
										'data-type' => 'string',
									],
								],
							],
							[
								'spec' => [
									'name' => 'personId',
									'type' => 'Text',
									'options' => [
										'label' => 'personId',
									],
									'attributes' => [
										'id' => 'personId',
										'value' => '',
										'placeholder' => 'Contact ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'email' => [
								'name' => 'email',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact Email Address should not be empty'
											],
										],
									],
									[
										'name' => 'EmailAddress',
										'options' => [
											'domain'   => 'true',
											'hostname' => 'true',
											'mx'       => 'true',
											'deep'     => 'true',
											'message'  => 'Email Address is invalid',
										]
									]
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'personId' => [
								'name' => 'personId',
								'required' => true,
								'validators' => [
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Contact ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
						*  method getClientPersonByEmail
						* 	@param email
						*/
					'getClientPersonByEmail' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'email',
									'type' => 'Text',
									'options' => [
										'label' => 'email',
									],
									'attributes' => [
										'id' => 'email',
										'value' => '',
										'class' => '',
										'placeholder' => 'Email Address',
										'style' => 'width: 10%',
										'data-type' => 'string',
									],
								],
							],
						],
						'input_filter' => [
							'email' => [
								'name' => 'email',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact Email Address should not be empty'
											],
										],
									],
									[
										'name' => 'EmailAddress',
										'options' => [
											'domain'   => 'true',
											'hostname' => 'true',
											'mx'       => 'true',
											'deep'     => 'true',
											'message'  => 'Email Address is invalid',
										]
									]
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
						*  method getClientGroup
						* 	@param clientId
						*/
					'getClientGroup' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'clientId',
									'type' => 'Text',
									'options' => [
										'label' => 'clientId',
									],
									'attributes' => [
										'id' => 'clientId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Client ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'clientId' => [
								'name' => 'clientId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Client ID should not be empty'
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
						],
					],

					/**
						*  method getPersonEmailsByPersonId
						* 	@param personId
						*/
					'getPersonEmailsByPersonId' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'personId',
									'type' => 'Text',
									'options' => [
										'label' => 'personId',
									],
									'attributes' => [
										'id' => 'personId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Contact ID',
										'style' => 'width: 10%',
										'data-type' => 'int',
									],
								],
							],
						],
						'input_filter' => [
							'personId' => [
								'name' => 'personId',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact ID should not be empty'
											],
										],
									],
									[
										'name' => 'IsInt',
										'options' => [
											'messages' => [
												\Laminas\I18n\Validator\IsInt::NOT_INT => 'Contact ID should be in numeric'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
						*  method getPersonsByEmail
						* 	@param email
						*/
					'getPersonsByEmail' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'email',
									'type' => 'Text',
									'options' => [
										'label' => 'email',
									],
									'attributes' => [
										'id' => 'email',
										'value' => '',
										'class' => '',
										'placeholder' => 'Email Address',
										'style' => 'width: 10%',
										'data-type' => 'string',
									],
								],
							],
						],
						'input_filter' => [
							'email' => [
								'name' => 'email',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact Email Address should not be empty'
											],
										],
									],
									[
										'name' => 'EmailAddress',
										'options' => [
											'domain'   => 'true',
											'hostname' => 'true',
											'mx'       => 'true',
											'deep'     => 'true',
											'message'  => 'Email Address is invalid',
										]
									]
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
						*  method getPersonsByEmail
						* 	@param email
						*/
					'getPersonsByEmail' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'email',
									'type' => 'Text',
									'options' => [
										'label' => 'email',
									],
									'attributes' => [
										'id' => 'email',
										'value' => '',
										'class' => '',
										'placeholder' => 'Email Address',
										'style' => 'width: 10%',
										'data-type' => 'string',
									],
								],
							],
						],
						'input_filter' => [
							'email' => [
								'name' => 'email',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact Email Address should not be empty'
											],
										],
									],
									[
										'name' => 'EmailAddress',
										'options' => [
											'domain'   => 'true',
											'hostname' => 'true',
											'mx'       => 'true',
											'deep'     => 'true',
											'message'  => 'Email Address is invalid',
										]
									]
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
						*  method getPersonsByEmail
						* 	@param email
						*/
					'getPersonsByEmail' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => [
									'name' => 'email',
									'type' => 'MelisText',
									'options' => [
										'label' => 'email',
									],
									'attributes' => [
										'id' => 'email',
										'value' => '',
										'class' => '',
										'placeholder' => 'Email Address',
										'style' => 'width: 10%',
										'data-type' => 'string',
									],
								],
							],
						],
						'input_filter' => [
							'email' => [
								'name' => 'email',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact Email Address should not be empty'
											],
										],
									],
									[
										'name' => 'EmailAddress',
										'options' => [
											'domain'   => 'true',
											'hostname' => 'true',
											'mx'       => 'true',
											'deep'     => 'true',
											'message'  => 'Email Address is invalid',
										]
									]
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
						],
					],

					/**
					*  method saveClient
					* 	@param email
					*/
					'saveClient' => [
						'attributes' => [
							'name'	=> 'microservice_form',
							'id'	=> 'microservice_form',
							'method'=> 'POST',
							'action'=> $_SERVER['REQUEST_URI'],
						],
						'hydrator' => 'Laminas\Hydrator\ArraySerializable',
						'elements' => [
							[
								'spec' => ['type' => 'EcomClientFieldset']
							],
							[
								'spec' => [
									'type' => \Laminas\Form\Element\Collection::class,
									'name' => 'persons',
									'options' => [
										'label' => 'Client Contacts (click here to add another contact)',
										// 'count' => 2,
										'should_create_template' => true,
										'allow_add' => true,
										'target_element' => [
											'type' => 'EcomPeronFieldset',
										],
									],
									'attribute' =>  [
										'id' => 'add-client-person',
										'class' => 'add-client-person',
									]
								]
							],

							// [
							// 	// 'spec' => [
							// 	// 	'name' => 'client[cl_id]',
							// 	// 	'type' => 'Text',
							// 	// 	'options' => [
							// 	// 		'label' => 'client',
							// 	// 	],
							// 	// 	'attributes' => [
							// 	// 		'id' => 'client',
							// 	// 		'value' => '',
							// 	// 		'class' => '',
							// 	// 		'placeholder' => 'client test',
							// 	// 		'style' => 'width: 10%',
							// 	// 		'data-type' => 'string',
							// 	// 	],
							// 	// ],
							// 	'spec' => [
							// 		'type' => \MelisCommerce\Form\ProductFieldset::class,
							// 		'name' => 'client',
							// 		'options' => [
							// 			// 'use_as_base_fieldset' => true,
							// 			'label' => 'client',
							// 		],
							// 		'attributes' => [
							// 			'id' => 'client',
							// 			'value' => '',
							// 			'class' => '',
							// 			'placeholder' => 'client test',
							// 			'style' => 'width: 10%',
							// 			'data-type' => 'string',
							// 		],
							// 	],

							// 	// 'spec' => [
							// 	// 	'type' => \Laminas\Form\Element\Collection::class,
							// 	// 	'name' => 'client[cl_id]',
							// 	// 	'options' => [
							// 	// 		'label' => 'Client ID',
							// 	// 		'count' => 5,
							// 	// 		'should_create_template' => true,
							// 	// 		'allow_add' => true,
							// 	// 		'target_element' => [
							// 	// 			'type' => \MelisCommerce\Form\ClientFieldset::class,
							// 	// 		],
							// 	// 	],
							// 	// ],
							// ],
							// [
							// 	'spec' => [
							// 		'name' => 'client[cl_group]',
							// 		'type' => 'Text',
							// 		'options' => [
							// 			'label' => 'client 2',
							// 		],
							// 		'attributes' => [
							// 			'id' => 'client',
							// 			'value' => '',
							// 			'class' => '',
							// 			'placeholder' => 'client test',
							// 			'style' => 'width: 10%',
							// 			'data-type' => 'string',
							// 		],
							// 	],
							// ],
						],
						// 'validation_group' => [
						// 	'cper_id'
							// 'client' => [
							// 	'cli_id' => [
							// 		// 'name' => 'client',
							// 		'required' => true,
							// 		// 'name' => 'client[cl_id]',
							// 		'validators' => [
							// 			[
							// 				'name' => 'NotEmpty',
							// 				'options' => [
							// 					'message' => [
							// 						\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact Email Address should not be empty'
							// 					],
							// 				],
							// 			],
							// 		],
							// 		'filters' => [
							// 			['name' => 'StripTags'],
							// 			['name' => 'StringTrim']
							// 		],
							// 	]
								
							// ],
							// 'client[cl_id]' => [
							// 	// 'name' => 'client',
							// 	'required' => true,
							// 	// 'name' => 'client[cl_id]',
							// 	'validators' => [
							// 		[
							// 			'name' => 'NotEmpty',
							// 			'options' => [
							// 				'message' => [
							// 					\Laminas\Validator\NotEmpty::IS_EMPTY => 'Contact Email Address should not be empty'
							// 				],
							// 			],
							// 		],
							// 	],
							// 	'filters' => [
							// 		['name' => 'StripTags'],
							// 		['name' => 'StringTrim']
							// 	],
							// ]
						// ],
						// 'input_filter' => [
						// 	'client' => [
						// 		// 'name' => 'client',
						// 		'required' => true,
						// 		// 'name' => 'client[cl_id]',
						// 		'validators' => [
						// 			[
						// 				'name' => MelisCommerce\Validator\ClientValidator::class,
						// 				// 'options' => [
						// 				// 	'message' => [
						// 				// 		\MelisCommerce\Validator\ClientValidator::FLOAT => 'Test 1 Address should not be empty'
						// 				// 	],
						// 				// ],
						// 			],
						// 		],
						// 		'filters' => [
						// 			['name' => 'StripTags'],
						// 			['name' => 'StringTrim']
						// 		],
						// 	],
						// 	// 'client[test2]' => [
						// 	// 	// 'name' => 'client',
						// 	// 	'required' => false,
						// 	// 	'validators' => [
						// 	// 		[
						// 	// 			'name' => 'IsInt',
						// 	// 			'options' => [
						// 	// 				'messages' => [
						// 	// 					\Laminas\I18n\Validator\IsInt::NOT_INT => 'Test 2 should be in numeric'
						// 	// 				],
						// 	// 			],
						// 	// 		],
						// 	// 	],
						// 	// 	'filters' => [
						// 	// 		['name' => 'StripTags'],
						// 	// 		['name' => 'StringTrim']
						// 	// 	],
						// 	// ],
						// ],
					],
				],
			],
		],
	],
];