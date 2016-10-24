<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

return array(
    'router' => array(
        'routes' => array(
        	'melis-backoffice' => array(
                'child_routes' => array(
                    'application-MelisCommerce' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => 'MelisCommerce',
                            'defaults' => array(
                                '__NAMESPACE__' => 'MelisCommerce\Controller',
                                'controller'    => 'Index',
                                'action'        => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'default' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:controller[/:action]]',
                                    'constraints' => array(
                                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),    
        	),
        	'melis-front' => array(
        	    'child_routes' => array(
					'melis_front_commerce_category' => array(
						'type'    => 'regex',
						'options' => array(
							'regex'    => '/cid/(?<categoryId>[0-9]+)',
							'defaults' => array(
        						'controller' => 'MelisFront\Controller\Index',
        						'action'     => 'index',
        						'renderType' => 'melis_zf2_mvc',
        						'renderMode' => 'front',
        						'preview'	 => false,
							),
					       'spec' => '%categoryId'
						),
					),
					'melis_front_commerce_product' => array(
						'type'    => 'regex',
						'options' => array(
							'regex'    => '/pid/(?<productId>[0-9]+)',
							'defaults' => array(
        						'controller' => 'MelisFront\Controller\Index',
        						'action'     => 'index',
        						'renderType' => 'melis_zf2_mvc',
        						'renderMode' => 'front',
        						'preview'	 => false,
							),
					       'spec' => '%productId'
						),
					),
					'melis_front_commerce_variant' => array(
						'type'    => 'regex',
						'options' => array(
							'regex'    => '/vid/(?<variantId>[0-9]+)',
							'defaults' => array(
        						'controller' => 'MelisFront\Controller\Index',
        						'action'     => 'index',
        						'renderType' => 'melis_zf2_mvc',
        						'renderMode' => 'front',
        						'preview'	 => false,
							),
					       'spec' => '%variantId'
						),
					),
        	    ),
        	),    
        ),
    ),
    'translator' => array(
        'locale' => 'en_EN',
    ),
    'service_manager' => array(
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'MelisEcomCategoryTable' => 'MelisCommerce\Model\Tables\MelisEcomCategoryTable',
            'MelisEcomCategoryTransTable' => 'MelisCommerce\Model\Tables\MelisEcomCategoryTransTable',
            'MelisEcomCountryCategoryTable' => 'MelisCommerce\Model\Tables\MelisEcomCountryCategoryTable',
            'MelisEcomLangTable' => 'MelisCommerce\Model\Tables\MelisEcomLangTable',
            'MelisEcomDocumentTable' => 'MelisCommerce\Model\Tables\MelisEcomDocumentTable',
            'MelisEcomDocTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomDocTypeTable',
            'MelisEcomProductTable' => 'MelisCommerce\Model\Tables\MelisEcomProductTable',
            'MelisEcomProductTextTable' => 'MelisCommerce\Model\Tables\MelisEcomProductTextTable',
            'MelisEcomProductTextTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomProductTextTypeTable',
            'MelisEcomVariantTable' => 'MelisCommerce\Model\Tables\MelisEcomVariantTable',
            'MelisEcomCountryTable' => 'MelisCommerce\Model\Tables\MelisEcomCountryTable',
            'MelisEcomPriceTable' => 'MelisCommerce\Model\Tables\MelisEcomPriceTable',
            'MelisEcomVariantStockTable' => 'MelisCommerce\Model\Tables\MelisEcomVariantStockTable',
            'MelisEcomProductCategoryTable' => 'MelisCommerce\Model\Tables\MelisEcomProductCategoryTable',
            'MelisEcomProductAttributeTable' => 'MelisCommerce\Model\Tables\MelisEcomProductAttributeTable',
            'MelisEcomProductCategoryTable' => 'MelisCommerce\Model\Tables\MelisEcomProductCategoryTable',
            'MelisEcomProductVariantAttributeValueTable' => 'MelisCommerce\Model\Tables\MelisEcomVariantAttributeValueTable',
            'MelisEcomLang' => 'MelisCommerce\Model\Tables\MelisEcomLangTable',
            'MelisEcomDocRelationsTable' => 'MelisCommerce\Model\Tables\MelisEcomDocRelationsTable',
            'MelisEcomAttributeTrans' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTransTable',
            'MelisEcomSeoTable' => 'MelisCommerce\Model\Tables\MelisEcomSeoTable',
            'MelisEcomAttributeTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTypeTable',
            'MelisEcomAttributeTable' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTable',
            'MelisEcomAttributeTransTable' => 'MelisCommerce\Model\Tables\MelisEcomAttributeTransTable',
            'MelisEcomAttributeValueTable' => 'MelisCommerce\Model\Tables\MelisEcomAttributeValueTable',
            'MelisEcomAttributeValueTransTable' => 'MelisCommerce\Model\Tables\MelisEcomAttributeValueTransTable',
            'MelisEcomClientTable' => 'MelisCommerce\Model\Tables\MelisEcomClientTable',
            'MelisEcomClientPersonTable' => 'MelisCommerce\Model\Tables\MelisEcomClientPersonTable',
            'MelisEcomClientAddressTable' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTable',
            'MelisEcomClientCompanyTable' => 'MelisCommerce\Model\Tables\MelisEcomClientCompanyTable',
            'MelisEcomCivilityTransTable' => 'MelisCommerce\Model\Tables\MelisEcomCivilityTransTable',
            'MelisEcomClientAddressTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTable',
            'MelisEcomClientAddressTypeTransTable' => 'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTransTable',
            'MelisEcomBasketPersistentTable' => 'MelisCommerce\Model\Tables\MelisEcomBasketPersistentTable',
            'MelisEcomBasketAnonymousTable' => 'MelisCommerce\Model\Tables\MelisEcomBasketAnonymousTable',
            'MelisEcomOrderTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderTable',
            'MelisEcomOrderTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderTable',
            'MelisEcomOrderAddressTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderAddressTable',
            'MelisEcomCouponTable' => 'MelisCommerce\Model\Tables\MelisEcomCouponTable',
            'MelisEcomCouponOrderTable' => 'MelisCommerce\Model\Tables\MelisEcomCouponOrderTable',
            'MelisEcomCouponClientTable' => 'MelisCommerce\Model\Tables\MelisEcomCouponClientTable',
            'MelisEcomOrderPaymentTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTable',
            'MelisEcomOrderPaymentTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTypeTable',
            'MelisEcomOrderStatusTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderStatusTable',
            'MelisEcomOrderStatusTransTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderStatusTransTable',
            'MelisEcomOrderMessageTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderMessageTable',
            'MelisEcomOrderShippingTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderShippingTable',
            'MelisEcomOrderBasketTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderBasketTable',
            'MelisEcomCurrencyTable' => 'MelisCommerce\Model\Tables\MelisEcomCurrencyTable',
            
            'MelisComCategoryService' => 'MelisCommerce\Service\MelisComCategoryService',
            'MelisComDocumentService' => 'MelisCommerce\Service\MelisComDocumentService',
            'MelisComProductService' => 'MelisCommerce\Service\MelisComProductService',
            'MelisComVariantService' => 'MelisCommerce\Service\MelisComVariantService',
            'MelisComAttributeService' => 'MelisCommerce\Service\MelisComAttributeService',
            'MelisComClientService' => 'MelisCommerce\Service\MelisComClientService',
            'MelisComOrderService' => 'MelisCommerce\Service\MelisComOrderService',
            'MelisComSeoService' => 'MelisCommerce\Service\MelisComSeoService',
            'MelisComCouponService' => 'MelisCommerce\Service\MelisComCouponService',
            'MelisComHead' => 'MelisCommerce\Service\MelisComHeadService',
            'MelisComBasketService' => 'MelisCommerce\Service\MelisComBasketService',
            'MelisComOrderCheckoutService' => 'MelisCommerce\Service\MelisComOrderCheckoutService',
            'MelisComShipmentCostService' => 'MelisCommerce\Service\MelisComShipmentCostService',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'MelisCommerce\Controller\MelisComCategoryList' => 'MelisCommerce\Controller\MelisComCategoryListController',
            'MelisCommerce\Controller\MelisComCategory' => 'MelisCommerce\Controller\MelisComCategoryController',
            'MelisCommerce\Controller\MelisComProductList' => 'MelisCommerce\Controller\MelisComProductListController',
            'MelisCommerce\Controller\MelisComProduct' => 'MelisCommerce\Controller\MelisComProductController',
            'MelisCommerce\Controller\MelisComVariantList' => 'MelisCommerce\Controller\MelisComVariantListController',
            'MelisCommerce\Controller\MelisComVariant' => 'MelisCommerce\Controller\MelisComVariantController',
            'MelisCommerce\Controller\MelisComDocument' => 'MelisCommerce\Controller\MelisComDocumentController',
            'MelisCommerce\Controller\MelisComClientList' => 'MelisCommerce\Controller\MelisComClientListController',
            'MelisCommerce\Controller\MelisComClient' => 'MelisCommerce\Controller\MelisComClientController',
            'MelisCommerce\Controller\MelisComOrder' => 'MelisCommerce\Controller\MelisComOrderController',
            'MelisCommerce\Controller\MelisComOrderCheckout' => 'MelisCommerce\Controller\MelisComOrderCheckoutController',
            'MelisCommerce\Controller\MelisComOrderList' => 'MelisCommerce\Controller\MelisComOrderListController',
            'MelisCommerce\Controller\MelisComPrice' => 'MelisCommerce\Controller\MelisComPriceController',
            'MelisCommerce\Controller\MelisComSeo' => 'MelisCommerce\Controller\MelisComSeoController',
            'MelisCommerce\Controller\MelisComCouponList' => 'MelisCommerce\Controller\MelisComCouponListController',
            'MelisCommerce\Controller\MelisComCoupon' => 'MelisCommerce\Controller\MelisComCouponController',
            'MelisCommerce\Controller\MelisComCurrency' => 'MelisCommerce\Controller\MelisComCurrencyController',
            'MelisCommerce\Controller\MelisComAttributeList' => 'MelisCommerce\Controller\MelisComAttributeListController',
            'MelisCommerce\Controller\MelisComAttribute' => 'MelisCommerce\Controller\MelisComAttributeController',
            'MelisCommerce\Controller\MelisComLanguage' => 'MelisCommerce\Controller\MelisComLanguageController',
            'MelisCommerce\Controller\Tester' => 'MelisCommerce\Controller\TesterController',
        ),
    ),
    'form_elements' => array(
        'factories' => array(
            'EcomCountriesSelect' => 'MelisCommerce\Form\Factory\EcomCountriesSelectFactory',
            'EcomCountriesNoAllCountriesSelect' => 'MelisCommerce\Form\Factory\EcomCountriesNoAllCountriesSelectFactory',
            'EcomProductTextTypeSelect' => 'MelisCommerce\Form\Factory\EcomProductTextTypeSelectFactory',
            'EcomLanguageSelect' => 'MelisCommerce\Form\Factory\EcomLanguageSelectFactory',
            'EcomDocumentFileSelect' => 'MelisCommerce\Form\Factory\EcomDocumentFileSelectFactory',
            'EcomDocumentImageTypeSelect' => 'MelisCommerce\Form\Factory\EcomDocumentImageTypeSelectFactory',
            'EcomCivilitySelect' => 'MelisCommerce\Form\Factory\EcomCivilitySelectFactory',
            'EcomAddressTypeSelect' => 'MelisCommerce\Form\Factory\EcomAddressTypeSelectFactory',
            'EcomOrderStatusSelect' => 'MelisCommerce\Form\Factory\EcomOrderStatusSelectFactory',
            'EcomDateField' => 'MelisCommerce\Form\Factory\EcomDateFieldFactory',
            'EcomCheckoutBillingAddressSelect' => 'MelisCommerce\Form\Factory\EcomCheckoutBillingAddressSelectFactory',
            'EcomCheckoutDeliveryAddressSelect' => 'MelisCommerce\Form\Factory\EcomCheckoutDeliveryAddressSelectFactory',
            'EcomAttributeTypeSelect' => 'MelisCommerce\Form\Factory\EcomAttributeTypeSelectFactory',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'ToolTipTable' => 'MelisCommerce\View\Helper\ToolTipTableHelper',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/default.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'aliases' => array(
                'MelisCommerce/' => __DIR__ . '/../public/',
            ),
        ),
    ),
);
