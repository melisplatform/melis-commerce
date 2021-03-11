<?php 

return [
	'plugins' => [
		'microservice' => [
			//Module Name
			'MelisCmsCommerce' => [ 
				//MelisCmsSliderService.php
				'MelisCmsProspectsService' => [
					/**
					 *  method getProspectsDataForWidgets
					 * 	@param widgetId
					 */
					'getProspectsDataForWidgets' => [
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
									'name' => 'widgetId',
									'type' => 'Select',
									'options' => [
										'label' => 'widgetId',
										'value_options' => [
											'numPropects' => 'numPropects',
											'numPropectsMonth' => 'numPropectsMonth',
											'numPropectsMonthAvg' => 'numPropectsMonthAvg',
										],
									],
									'attributes' => [
										'id' => 'widgetId',
										'value' => '',
										'class' => '',
										'placeholder' => 'Enter widgetId',
										'style' 	=> 'width : 10%'
									],
								],
							],
						],
						'input_filter' => [
							'widgetId' => [
								'name' => 'widgetId',
								'required' => false,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'option' => [
											'messages' => [
												\Laminas\Validator\NotEmpty::INTEGER => 'Please enter widgetId'
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
					 *  method getProspectsDataByDate
					 * 	@param type
					 * 	@param date (required]
					 */
					'getProspectsDataByDate' => [
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
									'name' => 'type',
									'type' => 'Select',
									'options' => [
										'label' => 'type',
										'value_options' => [
											'daily' => 'daily',
											'monthly' => 'monthly',
											'yearly' => 'yearly',
										],
									],
									'attributes' => [
										'id' => 'type',
										'value' => '',
										'class' => '',
										'placeholder' => 'Enter type',
										'style' => 'width: 10%' 
									],
								],
							],
							[
								'spec' => [
									'name' => 'date',
									'type' => 'Text',
									'options' => [
										'label' => 'date',
									],
									'attributes' => [
										'id' => 'date',
										'value' => '',
										'class' => '',
										'placeholder' => '2017-03-24',
										'data-type' => 'date'
									],
								],
							],
						],
						'input_filter' => [
							'widgetId' => [
								'name' => 'widgetId',
								'required' => false,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Please enter widgetId'
											],
										],
									],
								],
								'filters' => [
									['name' => 'StripTags'],
									['name' => 'StringTrim']
								],
							],
							'date' => [
								'name' => 'date',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'message' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Please enter date'
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
					 *  method getWidgetProspects
					 * 	@param identifier (required]
					 */
					'getWidgetProspects' => [
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
									'name' => 'identifier',
									'type' => 'Select',
									'options' => [
										'label' => 'identifier',
										'value_options' => [
											'curMonth' => 'curMonth',
											'avgMonth' => 'avgMonth',
										],
									],
									'attributes' => [
										'id' => 'identifier',
										'value' => '',
										'class' => '',
										'placeholder' => 'Enter identifier',
										'style' => 'width: 10%' 
									],
								],
							],
						],
						'input_filter' => [
							'identifier' => [
								'name' => 'identifier',
								'required' => true,
								'validators' => [
									[
										'name' => 'NotEmpty',
										'options' => [
											'messages' => [
												\Laminas\Validator\NotEmpty::IS_EMPTY => 'Please enter identifier'
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
				],
			],
		],
	],
];