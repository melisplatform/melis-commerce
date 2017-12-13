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
            'MelisEcomStockEmailAlertTable' => 'MelisCommerce\Model\Tables\MelisEcomStockEmailAlertTable',
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
            'MelisEcomOrderAddressTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderAddressTable',
            'MelisEcomCouponTable' => 'MelisCommerce\Model\Tables\MelisEcomCouponTable',
            'MelisEcomCouponOrderTable' => 'MelisCommerce\Model\Tables\MelisEcomCouponOrderTable',
            'MelisEcomCouponClientTable' => 'MelisCommerce\Model\Tables\MelisEcomCouponClientTable',
            'MelisEcomCouponProductTable' => 'MelisCommerce\Model\Tables\MelisEcomCouponProductTable',
            'MelisEcomOrderPaymentTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTable',
            'MelisEcomOrderPaymentTypeTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTypeTable',
            'MelisEcomOrderStatusTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderStatusTable',
            'MelisEcomOrderStatusTransTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderStatusTransTable',
            'MelisEcomOrderMessageTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderMessageTable',
            'MelisEcomOrderShippingTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderShippingTable',
            'MelisEcomOrderBasketTable' => 'MelisCommerce\Model\Tables\MelisEcomOrderBasketTable',
            'MelisEcomCurrencyTable' => 'MelisCommerce\Model\Tables\MelisEcomCurrencyTable',
            'MelisEcomAssocVariantTable'      => 'MelisCommerce\Model\Tables\MelisEcomAssocVariantTable',
            'MelisEcomAssocVariantTypeTable'  => 'MelisCommerce\Model\Tables\MelisEcomAssocVariantTypeTable',
            
        ),
        'factories' => array(
            //services
            'MelisComAuthenticationService'=> 'MelisCommerce\Service\Factory\MelisComAuthenticationServiceFactory',
            'MelisComAttributeService'     => 'MelisCommerce\Service\Factory\MelisComAttributeServiceFactory',
            'MelisComBasketService'        => 'MelisCommerce\Service\Factory\MelisComBasketServiceFactory',
            'MelisComCategoryService'      => 'MelisCommerce\Service\Factory\MelisComCategoryServiceFactory',
            'MelisComClientService'        => 'MelisCommerce\Service\Factory\MelisComClientServiceFactory',
            'MelisComCouponService'        => 'MelisCommerce\Service\Factory\MelisComCouponServiceFactory',
            'MelisComDocumentService'      => 'MelisCommerce\Service\Factory\MelisComDocumentServiceFactory',
            'MelisComHead'                 => 'MelisCommerce\Service\Factory\MelisComHeadServiceFactory',
            'MelisComOrderCheckoutService' => 'MelisCommerce\Service\Factory\MelisComOrderCheckoutServiceFactory',
            'MelisComOrderService'         => 'MelisCommerce\Service\Factory\MelisComOrderServiceFactory',
            'MelisComPostPaymentService'   => 'MelisCommerce\Service\Factory\MelisComPostPaymentServiceFactory',
            'MelisComProductSearchService' => 'MelisCommerce\Service\Factory\MelisComProductSearchServiceFactory',
            'MelisComProductService'       => 'MelisCommerce\Service\Factory\MelisComProductServiceFactory',
            'MelisComSeoService'           => 'MelisCommerce\Service\Factory\MelisComSeoServiceFactory',
            'MelisComShipmentCostService'  => 'MelisCommerce\Service\Factory\MelisComShipmentCostServiceFactory',
            'MelisComStockEmailAlertService'=> 'MelisCommerce\Service\Factory\MelisComStockEmailAlertServiceFactory',
            'MelisComVariantService'       => 'MelisCommerce\Service\Factory\MelisComVariantServiceFactory',
            'MelisComDuplicationService'   => 'MelisCommerce\Service\Factory\MelisComDuplicationServiceFactory',
            'MelisComCurrencyService'      => 'MelisCommerce\Service\Factory\MelisComCurrencyServiceFactory',
            
            'MelisComLinksService'   => 'MelisCommerce\Service\Factory\MelisComLinksServiceFactory',
            
            //db tables
            'MelisCommerce\Model\Tables\MelisEcomAttributeTable'             => 'MelisCommerce\Model\Tables\Factory\MelisEcomAttributeTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomAttributeTransTable'        => 'MelisCommerce\Model\Tables\Factory\MelisEcomAttributeTransTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomAttributeTypeTable'         => 'MelisCommerce\Model\Tables\Factory\MelisEcomAttributeTypeTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomAttributeValueTable'        => 'MelisCommerce\Model\Tables\Factory\MelisEcomAttributeValueTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomAttributeValueTransTable'   => 'MelisCommerce\Model\Tables\Factory\MelisEcomAttributeValueTransTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomAssocVariantTable'          => 'MelisCommerce\Model\Tables\Factory\MelisEcomAssocVariantTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomAssocVariantTypeTable'      => 'MelisCommerce\Model\Tables\Factory\MelisEcomAssocVariantTypeTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomBasketAnonymousTable'       => 'MelisCommerce\Model\Tables\Factory\MelisEcomBasketAnonymousTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomBasketPersistentTable'      => 'MelisCommerce\Model\Tables\Factory\MelisEcomBasketPersistentTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCategoryTable'              => 'MelisCommerce\Model\Tables\Factory\MelisEcomCategoryTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCategoryTransTable'         => 'MelisCommerce\Model\Tables\Factory\MelisEcomCategoryTransTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCivilityTable'              => 'MelisCommerce\Model\Tables\Factory\MelisEcomCivilityTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCivilityTransTable'         => 'MelisCommerce\Model\Tables\Factory\MelisEcomCivilityTransTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomClientAddressTable'         => 'MelisCommerce\Model\Tables\Factory\MelisEcomClientAddressTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTable'     => 'MelisCommerce\Model\Tables\Factory\MelisEcomClientAddressTypeTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTransTable'=> 'MelisCommerce\Model\Tables\Factory\MelisEcomClientAddressTypeTransTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomClientCompanyTable'         => 'MelisCommerce\Model\Tables\Factory\MelisEcomClientCompanyTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomClientPersonTable'          => 'MelisCommerce\Model\Tables\Factory\MelisEcomClientPersonTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomClientTable'                => 'MelisCommerce\Model\Tables\Factory\MelisEcomClientTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCountryCategoryTable'       => 'MelisCommerce\Model\Tables\Factory\MelisEcomCountryCategoryTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCountryTable'               => 'MelisCommerce\Model\Tables\Factory\MelisEcomCountryTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCouponClientTable'          => 'MelisCommerce\Model\Tables\Factory\MelisEcomCouponClientTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCouponOrderTable'           => 'MelisCommerce\Model\Tables\Factory\MelisEcomCouponOrderTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCouponProductTable'         => 'MelisCommerce\Model\Tables\Factory\MelisEcomCouponProductTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCouponTable'                => 'MelisCommerce\Model\Tables\Factory\MelisEcomCouponTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomCurrencyTable'              => 'MelisCommerce\Model\Tables\Factory\MelisEcomCurrencyTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomDocRelationsTable'          => 'MelisCommerce\Model\Tables\Factory\MelisEcomDocRelationsTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomDocTypeTable'               => 'MelisCommerce\Model\Tables\Factory\MelisEcomDocTypeTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomDocumentTable'              => 'MelisCommerce\Model\Tables\Factory\MelisEcomDocumentTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomLangTable'                  => 'MelisCommerce\Model\Tables\Factory\MelisEcomLangTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomOrderAddressTable'          => 'MelisCommerce\Model\Tables\Factory\MelisEcomOrderAddressTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomOrderBasketTable'           => 'MelisCommerce\Model\Tables\Factory\MelisEcomOrderBasketTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomOrderMessageTable'          => 'MelisCommerce\Model\Tables\Factory\MelisEcomOrderMessageTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTable'          => 'MelisCommerce\Model\Tables\Factory\MelisEcomOrderPaymentTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomOrderPaymentTypeTable'      => 'MelisCommerce\Model\Tables\Factory\MelisEcomOrderPaymentTypeTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomOrderShippingTable'         => 'MelisCommerce\Model\Tables\Factory\MelisEcomOrderShippingTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomOrderStatusTable'           => 'MelisCommerce\Model\Tables\Factory\MelisEcomOrderStatusTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomOrderStatusTransTable'      => 'MelisCommerce\Model\Tables\Factory\MelisEcomOrderStatusTransTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomOrderTable'                 => 'MelisCommerce\Model\Tables\Factory\MelisEcomOrderTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomPriceTable'                 => 'MelisCommerce\Model\Tables\Factory\MelisEcomPriceTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomProductAttributeTable'      => 'MelisCommerce\Model\Tables\Factory\MelisEcomProductAttributeTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomProductCategoryTable'       => 'MelisCommerce\Model\Tables\Factory\MelisEcomProductCategoryTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomProductTable'               => 'MelisCommerce\Model\Tables\Factory\MelisEcomProductTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomProductTextTable'           => 'MelisCommerce\Model\Tables\Factory\MelisEcomProductTextTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomProductTextTypeTable'       => 'MelisCommerce\Model\Tables\Factory\MelisEcomProductTextTypeTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomSeoTable'                   => 'MelisCommerce\Model\Tables\Factory\MelisEcomSeoTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomStockEmailAlertTable'       => 'MelisCommerce\Model\Tables\Factory\MelisEcomStockEmailAlertTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomVariantAttributeValueTable' => 'MelisCommerce\Model\Tables\Factory\MelisEcomVariantAttributeValueTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomVariantStockTable'          => 'MelisCommerce\Model\Tables\Factory\MelisEcomVariantStockTableFactory',
            'MelisCommerce\Model\Tables\MelisEcomVariantTable'               => 'MelisCommerce\Model\Tables\Factory\MelisEcomVariantTableFactory',
            
            //listeners
            'MelisCommerce\Listener\MelisCommerceSEODispatchRouterCommerceUrlListener' => 'MelisCommerce\Listener\Factory\MelisCommerceSEODispatchRouterCommerceUrlListenerFactory',
            'MelisCommerce\Listener\MelisCommerceSEOReformatToRoutePageUrlListener'    => 'MelisCommerce\Listener\Factory\MelisCommerceSEOReformatToRoutePageUrlListenerFactory',
            
        ),
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
        )
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
            'MelisCommerce\Controller\MelisComCountry' => 'MelisCommerce\Controller\MelisComCountryController',
            'MelisCommerce\Controller\Tester' => 'MelisCommerce\Controller\TesterController',
            'MelisCommerce\Controller\Diagnostic' => 'MelisCommerce\Controller\DiagnosticController',
            'MelisCommerce\Controller\MelisComAssociateVariant' => 'MelisCommerce\Controller\MelisComAssociateVariantController',
            'MelisCommerce\Controller\MelisComPrdVarDuplication' => 'MelisCommerce\Controller\MelisComPrdVarDuplicationController',
            'MelisCommerce\Controller\MelisComOrderStatus' => 'MelisCommerce\Controller\MelisComOrderStatusController',
            'MelisCommerce\Controller\MelisComSettings' => 'MelisCommerce\Controller\MelisComSettingsController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'MelisCommerceCategoryProductListPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceCategoryProductListPlugin',
            'MelisCommerceFullCategoryProductListPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceFullCategoryProductListPlugin',
            
            'MelisCommerceRegisterPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceRegisterPlugin',
            'MelisCommerceLoginPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceLoginPlugin',
            'MelisCommerceFilterMenuPriceValueBoxPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceFilterMenuPriceValueBoxPlugin',
            'MelisCommerceFilterMenuAttributeValueBoxPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceFilterMenuAttributeValueBoxPlugin',
            'MelisCommerceProductShowPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceProductShowPlugin',
            'MelisCommerceAttributesShowPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceAttributesShowPlugin',
            'MelisCommerceProductsRelatedPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceProductsRelatedPlugin',
            'MelisCommerceAddToCartPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceAddToCartPlugin',
            'MelisCommerceFilterMenuProductSearchBoxPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceFilterMenuProductSearchBoxPlugin',
            'MelisCommerceFilterMenuCategoryListPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceFilterMenuCategoryListPlugin',
            'MelisCommerceAccountPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceAccountPlugin',
            'MelisCommerceProfilePlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceProfilePlugin',
            'MelisCommerceDeliveryAddressPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceDeliveryAddressPlugin',
            'MelisCommerceBillingAddressPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceBillingAddressPlugin',
            'MelisCommerceLostPasswordGetEmailPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceLostPasswordGetEmailPlugin',
            'MelisCommerceLostPasswordResetPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceLostPasswordResetPlugin',
            'MelisCommerceSelectAddressPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceSelectAddressPlugin',
            'MelisCommerceCheckoutPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceCheckoutPlugin',
            'MelisCommerceCheckoutCartPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceCheckoutCartPlugin',
            'MelisCommerceCheckoutCouponAddPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceCheckoutCouponAddPlugin',
            'MelisCommerceCheckoutAddressesPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceCheckoutAddressesPlugin',
            'MelisCommerceCheckoutSummaryPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceCheckoutSummaryPlugin',
            'MelisCommerceCheckoutConfirmSummaryPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceCheckoutConfirmSummaryPlugin',
            'MelisCommerceCheckoutConfirmPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceCheckoutConfirmPlugin',
            'MelisCommerceCartMenuPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceCartMenuPlugin',
            'MelisCommerceOrderListPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceOrderListPlugin',
            'MelisCommerceOrderPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceOrderPlugin',
            'MelisCommerceOrderAddressPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceOrderAddressPlugin',
            'MelisCommerceOrderShippingDetailsPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceOrderShippingDetailsPlugin',
            'MelisCommerceOrderMessagesPlugin' => 'MelisCommerce\Controller\Plugin\MelisCommerceOrderMessagesPlugin',
        )
    ),
    'form_elements' => array(
        'factories' => array(
            'EcomSelectFactory'                     => 'MelisCommerce\Form\Factory\EcomSelectFactory',
            'EcomCountriesSelect'                   => 'MelisCommerce\Form\Factory\EcomCountriesSelectFactory',
            'EcomCountriesNoAllCountriesSelect'     => 'MelisCommerce\Form\Factory\EcomCountriesNoAllCountriesSelectFactory',
            'EcomProductTextTypeSelect'             => 'MelisCommerce\Form\Factory\EcomProductTextTypeSelectFactory',
            'EcomLanguageSelect'                    => 'MelisCommerce\Form\Factory\EcomLanguageSelectFactory',
            'EcomDocumentFileSelect'                => 'MelisCommerce\Form\Factory\EcomDocumentFileSelectFactory',
            'EcomDocumentImageTypeSelect'           => 'MelisCommerce\Form\Factory\EcomDocumentImageTypeSelectFactory',
            'EcomCivilitySelect'                    => 'MelisCommerce\Form\Factory\EcomCivilitySelectFactory',
            'EcomAddressTypeSelect'                 => 'MelisCommerce\Form\Factory\EcomAddressTypeSelectFactory',
            'EcomOrderStatusSelect'                 => 'MelisCommerce\Form\Factory\EcomOrderStatusSelectFactory',
            'EcomOrderStatusAllSelect'              => 'MelisCommerce\Form\Factory\EcomOrderStatusAllSelectFactory',
            'EcomDateField'                         => 'MelisCommerce\Form\Factory\EcomDateFieldFactory',
            'EcomAttributeTypeSelect'               => 'MelisCommerce\Form\Factory\EcomAttributeTypeSelectFactory',
            'EcomCurrencySelect'                    => 'MelisCommerce\Form\Factory\EcomCurrencySelectFactory',
            'EcomCheckoutBillingAddressSelect'      => 'MelisCommerce\Form\Factory\EcomCheckoutBillingAddressSelectFactory',
            'EcomCheckoutDeliveryAddressSelect'     => 'MelisCommerce\Form\Factory\EcomCheckoutDeliveryAddressSelectFactory',

            'EcomCountriesAllStatusSelect'          => 'MelisCommerce\Form\Factory\EcomCountriesAllStatusSelectFactory',
            'EcomCurrencyAllStatusSelect'           => 'MelisCommerce\Form\Factory\EcomCurrencyAllStatusSelectFactory',
            'EcomLanguageAllStatusSelect'           => 'MelisCommerce\Form\Factory\EcomLanguageAllStatusSelectFactory',
            'EcomColorPicker'                       => 'MelisCommerce\Form\Factory\EcomColorPicker',
            
            // Plugins
            'EcomPluginCivilitySelect'              => 'MelisCommerce\Form\Factory\Plugin\EcomPluginCivilitySelectFactory',
            'EcomPluginCountriesSelect'             => 'MelisCommerce\Form\Factory\Plugin\EcomPluginCountriesSelectFactory',
            'EcomPluginLanguageSelect'              => 'MelisCommerce\Form\Factory\Plugin\EcomPluginLanguageSelectFactory',
            'EcomPluginAddressTypeSelect'           => 'MelisCommerce\Form\Factory\Plugin\EcomPluginAddressTypeSelectFactory',
            'EcomPluginDeliveryAddressSelect'       => 'MelisCommerce\Form\Factory\Plugin\EcomPluginDeliveryAddressSelectFactory',
            'EcomPluginBillingAddressSelect'        => 'MelisCommerce\Form\Factory\Plugin\EcomPluginBillingAddressSelectFactory',
            'EcomPluginCategoryListSelect'          => 'MelisCommerce\Form\Factory\Plugin\EcomPluginCategoryListSelectFactory',
            'EcomPluginAttributeSelectFactory'      => 'MelisCommerce\Form\Factory\Plugin\EcomPluginAttributeSelectFactory',
            'EcomPluginPriceCountriesSelect'        => 'MelisCommerce\Form\Factory\Plugin\EcomPluginPriceCountriesSelectFactory',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'ToolTipTable' => 'MelisCommerce\View\Helper\ToolTipTableHelper',
        ),
        'factories' => array(
            'MelisCommerceLink' => 'MelisCommerce\View\Helper\Factory\MelisCommerceLinksHelperFactory',
        ),    
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/default.phtml',
            // Email Layout
            'MelisCommerce/emailLayout'                             => __DIR__ . '/../view/layout/email-layout.phtml',
            // Plugins layout
            'MelisCommerce/ClientRegister'                          => __DIR__ . '/../view/plugins/clients/registration.phtml',
            'MelisCommerce/ClientLogin'                             => __DIR__ . '/../view/plugins/clients/login.phtml',
            'MelisCommerce/ClientLostPassword'                      => __DIR__ . '/../view/plugins/clients/lost-password.phtml',
            'MelisCommerce/ClientLostPasswordReset'                 => __DIR__ . '/../view/plugins/clients/lost-password-reset.phtml',
            'MelisCommerce/ClientAccount'                           => __DIR__ . '/../view/plugins/clients/account.phtml',
            'MelisCommerce/ClientProfile'                           => __DIR__ . '/../view/plugins/clients/profile.phtml',
            'MelisCommerce/ClientDeliveryAddress'                   => __DIR__ . '/../view/plugins/clients/delivery-address.phtml',
            'MelisCommerce/ClientBillingAddress'                    => __DIR__ . '/../view/plugins/clients/billing-address.phtml',
            'MelisCommerce/ClientMyCart'                            => __DIR__ . '/../view/plugins/clients/my-cart.phtml',
            'MelisCommerceProduct/show-product'                     => __DIR__ . '/../view/plugins/products/show-product.phtml',
            'MelisCommerceProduct/show-attributes'                  => __DIR__ . '/../view/plugins/products/show-attributes.phtml',
            'MelisCommerceProduct/show-related-products'            => __DIR__ . '/../view/plugins/products/show-related-products.phtml',
            
            'MelisCommerceOrder/add-to-cart'                         => __DIR__ . '/../view/plugins/order/add-to-cart.phtml',
            
            'MelisCommerceCheckout/show-check-out'                  => __DIR__ . '/../view/plugins/checkout/show-check-out.phtml',
            'MelisCommerceCheckout/show-check-out-cart'             => __DIR__ . '/../view/plugins/checkout/show-check-out-cart.phtml',
            'MelisCommerceCheckout/show-check-out-coupon'           => __DIR__ . '/../view/plugins/checkout/show-check-out-coupon.phtml',
            'MelisCommerceCheckout/show-check-out-address'          => __DIR__ . '/../view/plugins/checkout/show-check-out-address.phtml',
            'MelisCommerceCheckout/show-check-out-summary'          => __DIR__ . '/../view/plugins/checkout/show-check-out-summary.phtml',
            'MelisCommerceCheckout/show-check-out-confirm-summary'  => __DIR__ . '/../view/plugins/checkout/show-check-out-confirm-summary.phtml',
            'MelisCommerceCheckout/show-check-out-confirm'          => __DIR__ . '/../view/plugins/checkout/show-check-out-confirm.phtml',
            
            'MelisCommerceCartMenuPlugin/show-cart-menu'            => __DIR__ . '/../view/plugins/checkout/show-cart-menu.phtml',
            
            'MelisCommerceCategory/category-product-list'           => __DIR__ . '/../view/plugins/categories/category-product-list.phtml',
            'MelisCommerceCategory/category-list-filter'            => __DIR__ . '/../view/plugins/categories/category-list-filter.phtml',
            'MelisCommerceCategory/category-product-search-input'   => __DIR__ . '/../view/plugins/categories/category-product-search-input.phtml',
            'MelisCommerceCategory/category-price-filter'           => __DIR__ . '/../view/plugins/categories/category-price-filter.phtml',
            'MelisCommerceCategory/category-attribute-filter'       => __DIR__ . '/../view/plugins/categories/category-attribute-filterr.phtml',
            'MelisCommerceCategory/full-category-product-list'      => __DIR__ . '/../view/plugins/categories/full-category-product-list.phtml',
            
            'MelisCommerceOrder/show-client-order-list'             => __DIR__ . '/../view/plugins/order/show-client-order-list.phtml',
            'MelisCommerceOrder/show-client-order'                  => __DIR__ . '/../view/plugins/order/show-client-order.phtml',
            'MelisCommerceOrder/show-client-order-address'          => __DIR__ . '/../view/plugins/order/show-client-order-address.phtml',
            'MelisCommerceOrder/show-client-shipping-details'       => __DIR__ . '/../view/plugins/order/show-client-shipping-details.phtml',
            
            // Plugin config layout
            'MelisCommerce/category-product-list-config'            => __DIR__ . '/../view/plugins/categories/category-product-list-config.phtml',
            'MelisCommerce/category-product-list-tree-config'       => __DIR__ . '/../view/plugins/categories/category-product-list-tree-config.phtml',
            'MelisCommerce/category-product-list-product-config'    => __DIR__ . '/../view/plugins/categories/category-product-list-product-config.phtml',
            'MelisCommerce/full-category-product-list-config'       => __DIR__ . '/../view/plugins/categories/full-category-product-list-config.phtml',
            'MelisCommerce/full-category-product-list-tree-config'  => __DIR__ . '/../view/plugins/categories/full-category-product-list-tree-config.phtml',
            'MelisCommerce/full-category-product-list-pagination-config'  => __DIR__ . '/../view/plugins/categories/full-category-product-list-pagination-config.phtml',
            'MelisCommerce/full-category-product-list-attributes-config'  => __DIR__ . '/../view/plugins/categories/full-category-product-list-attributes-config.phtml',
            'MelisCommerce/full-category-product-list-text-types-config'  => __DIR__ . '/../view/plugins/categories/full-category-product-list-text-types-config.phtml',
            'MelisCommerce/category-list-filter-config'             => __DIR__ . '/../view/plugins/categories/category-list-filter-config.phtml',
            'MelisCommerce/category-list-filter-tree-config'        => __DIR__ . '/../view/plugins/categories/category-list-filter-tree-config.phtml',
            'MelisCommerce/category-product-search-box-config'      => __DIR__ . '/../view/plugins/categories/category-product-search-box-config.phtml',
            
            // Plugin common form config layout
            'MelisCommerce/plugin-common-form-config'                      => __DIR__ . '/../view/plugins/common/plugin-common-form-config.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'caches' => array(
        'commerce_memory_services' => array( 
            'active' => false, // activate or deactivate Melis Cache for this conf
            'adapter' => array(
                'name'    => 'Memory',
                'options' => array('ttl' => 0, 'namespace' => 'meliscommerce'),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
            ),
            'ttls' => array(
                // add a specific ttl for a specific cache key (found via regexp)
                // 'my_cache_key' => 60,
            )
        ),
        'commerce_big_services' => array( 
            'active' => false, // activate or deactivate Melis Cache for this conf
            'adapter' => array(
                'name'    => 'Filesystem',
                'options' => array(
                    'ttl' => 60 * 60 * 24, // 24hrs 
                    'namespace' => 'meliscommerce',
                    'cache_dir' => $_SERVER['DOCUMENT_ROOT'] . '/../cache'
                ),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
                'Serializer'
            ),
            'ttls' => array(
                // add a specific ttl for a specific cache key (found via regexp)
                // 'my_cache_key' => 60,
            )
        ),
    ),
);