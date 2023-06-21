<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

return [
    'router' => [
        'routes' => [
            'melis-backoffice' => [
                'child_routes' => [
                    'application-MelisCommerce' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => 'MelisCommerce',
                            'defaults' => [
                                '__NAMESPACE__' => 'MelisCommerce\Controller',
                                'controller'    => 'Index',
                                'action'        => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'default' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/[:controller[/:action]]',
                                    'constraints' => [
                                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ],
                                    'defaults' => [
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'melis-front' => [
                'child_routes' => [
                    'melis_front_commerce_category' => [
                        'type'    => 'regex',
                        'options' => [
                            'regex'    => '/cid/(?<categoryId>[0-9]+)',
                            'defaults' => [
                                'controller' => 'MelisFront\Controller\Index',
                                'action'     => 'index',
                                'renderType' => 'melis_zf2_mvc',
                                'renderMode' => 'front',
                                'preview'	 => false,
                            ],
                            'spec' => '%categoryId'
                        ],
                    ],
                    'melis_front_commerce_product' => [
                        'type'    => 'regex',
                        'options' => [
                            'regex'    => '/pid/(?<productId>[0-9]+)',
                            'defaults' => [
                                'controller' => 'MelisFront\Controller\Index',
                                'action'     => 'index',
                                'renderType' => 'melis_zf2_mvc',
                                'renderMode' => 'front',
                                'preview'	 => false,
                            ],
                            'spec' => '%productId'
                        ],
                    ],
                    'melis_front_commerce_variant' => [
                        'type'    => 'regex',
                        'options' => [
                            'regex'    => '/vid/(?<variantId>[0-9]+)',
                            'defaults' => [
                                'controller' => 'MelisFront\Controller\Index',
                                'action'     => 'index',
                                'renderType' => 'melis_zf2_mvc',
                                'renderMode' => 'front',
                                'preview'	 => false,
                            ],
                            'spec' => '%variantId'
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
            // Services
            'MelisComAuthenticationService'                 => \MelisCommerce\Service\MelisComAuthenticationService::class,
            'MelisComAttributeService'                      => \MelisCommerce\Service\MelisComAttributeService::class,
            'MelisComBasketService'                         => \MelisCommerce\Service\MelisComBasketService::class,
            'MelisComCategoryService'                       => \MelisCommerce\Service\MelisComCategoryService::class,
            'MelisComClientService'                         => \MelisCommerce\Service\MelisComClientService::class,
            'MelisComCouponService'                         => \MelisCommerce\Service\MelisComCouponService::class,
            'MelisComDocumentService'                       => \MelisCommerce\Service\MelisComDocumentService::class,
            'MelisComHead'                                  => \MelisCommerce\Service\MelisComHeadService::class,
            'MelisComOrderCheckoutService'                  => \MelisCommerce\Service\MelisComOrderCheckoutService::class,
            'MelisComOrderService'                          => \MelisCommerce\Service\MelisComOrderService::class,
            'MelisComPostPaymentService'                    => \MelisCommerce\Service\MelisComPostPaymentService::class,
            'MelisComProductSearchService'                  => \MelisCommerce\Service\MelisComProductSearchService::class,
            'MelisComProductService'                        => \MelisCommerce\Service\MelisComProductService::class,
            'MelisComSeoService'                            => \MelisCommerce\Service\MelisComSeoService::class,
            'MelisComShipmentCostService'                   => \MelisCommerce\Service\MelisComShipmentCostService::class,
            'MelisComStockEmailAlertService'                => \MelisCommerce\Service\MelisComStockEmailAlertService::class,
            'MelisComVariantService'                        => \MelisCommerce\Service\MelisComVariantService::class,
            'MelisComDuplicationService'                    => \MelisCommerce\Service\MelisComDuplicationService::class,
            'MelisComCurrencyService'                       => \MelisCommerce\Service\MelisComCurrencyService::class,
            'MelisComLinksService'                          => \MelisCommerce\Service\MelisComLinksService::class,
            'MelisComClientGroupsService'                   => \MelisCommerce\Service\MelisComClientGroupsService::class,
            'MelisComPriceService'                          => \MelisCommerce\Service\MelisComPriceService::class,
            'MelisComOrderProductReturnService'             => \MelisCommerce\Service\MelisComOrderProductReturnService::class,
            'MelisComCacheService'                          => \MelisCommerce\Service\MelisComCacheService::class,
            'MelisComContactService'                          => \MelisCommerce\Service\MelisComContactService::class,

            // Tables
            'MelisEcomAttributeTable'                       => \MelisCommerce\Model\Tables\MelisEcomAttributeTable::class,
            'MelisEcomAttributeTransTable'                  => \MelisCommerce\Model\Tables\MelisEcomAttributeTransTable::class,
            'MelisEcomAttributeTypeTable'                   => \MelisCommerce\Model\Tables\MelisEcomAttributeTypeTable::class,
            'MelisEcomAttributeValueTable'                  => \MelisCommerce\Model\Tables\MelisEcomAttributeValueTable::class,
            'MelisEcomAttributeValueTransTable'             => \MelisCommerce\Model\Tables\MelisEcomAttributeValueTransTable::class,
            'MelisEcomAssocVariantTable'                    => \MelisCommerce\Model\Tables\MelisEcomAssocVariantTable::class,
            'MelisEcomAssocVariantTypeTable'                => \MelisCommerce\Model\Tables\MelisEcomAssocVariantTypeTable::class,
            'MelisEcomBasketAnonymousTable'                 => \MelisCommerce\Model\Tables\MelisEcomBasketAnonymousTable::class,
            'MelisEcomBasketPersistentTable'                => \MelisCommerce\Model\Tables\MelisEcomBasketPersistentTable::class,
            'MelisEcomCategoryTable'                        => \MelisCommerce\Model\Tables\MelisEcomCategoryTable::class,
            'MelisEcomCategoryTransTable'                   => \MelisCommerce\Model\Tables\MelisEcomCategoryTransTable::class,
            'MelisEcomCivilityTable'                        => \MelisCommerce\Model\Tables\MelisEcomCivilityTable::class,
            'MelisEcomCivilityTransTable'                   => \MelisCommerce\Model\Tables\MelisEcomCivilityTransTable::class,
            'MelisEcomClientAddressTable'                   => \MelisCommerce\Model\Tables\MelisEcomClientAddressTable::class,
            'MelisEcomClientAddressTypeTable'               => \MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTable::class,
            'MelisEcomClientAddressTypeTransTable'          => \MelisCommerce\Model\Tables\MelisEcomClientAddressTypeTransTable::class,
            'MelisEcomClientCompanyTable'                   => \MelisCommerce\Model\Tables\MelisEcomClientCompanyTable::class,
            'MelisEcomClientPersonTable'                    => \MelisCommerce\Model\Tables\MelisEcomClientPersonTable::class,
            'MelisEcomClientPersonEmailsTable'                    => \MelisCommerce\Model\Tables\MelisEcomClientPersonEmailsTable::class,
            'MelisEcomClientTable'                          => \MelisCommerce\Model\Tables\MelisEcomClientTable::class,
            'MelisEcomCountryCategoryTable'                 => \MelisCommerce\Model\Tables\MelisEcomCountryCategoryTable::class,
            'MelisEcomCountryTable'                         => \MelisCommerce\Model\Tables\MelisEcomCountryTable::class,
            'MelisEcomCouponClientTable'                    => \MelisCommerce\Model\Tables\MelisEcomCouponClientTable::class,
            'MelisEcomCouponOrderTable'                     => \MelisCommerce\Model\Tables\MelisEcomCouponOrderTable::class,
            'MelisEcomCouponProductTable'                   => \MelisCommerce\Model\Tables\MelisEcomCouponProductTable::class,
            'MelisEcomCouponTable'                          => \MelisCommerce\Model\Tables\MelisEcomCouponTable::class,
            'MelisEcomCurrencyTable'                        => \MelisCommerce\Model\Tables\MelisEcomCurrencyTable::class,
            'MelisEcomDocRelationsTable'                    => \MelisCommerce\Model\Tables\MelisEcomDocRelationsTable::class,
            'MelisEcomDocTypeTable'                         => \MelisCommerce\Model\Tables\MelisEcomDocTypeTable::class,
            'MelisEcomDocumentTable'                        => \MelisCommerce\Model\Tables\MelisEcomDocumentTable::class,
            'MelisEcomLangTable'                            => \MelisCommerce\Model\Tables\MelisEcomLangTable::class,
            'MelisEcomOrderAddressTable'                    => \MelisCommerce\Model\Tables\MelisEcomOrderAddressTable::class,
            'MelisEcomOrderBasketTable'                     => \MelisCommerce\Model\Tables\MelisEcomOrderBasketTable::class,
            'MelisEcomOrderMessageTable'                    => \MelisCommerce\Model\Tables\MelisEcomOrderMessageTable::class,
            'MelisEcomOrderPaymentTable'                    => \MelisCommerce\Model\Tables\MelisEcomOrderPaymentTable::class,
            'MelisEcomOrderPaymentTypeTable'                => \MelisCommerce\Model\Tables\MelisEcomOrderPaymentTypeTable::class,
            'MelisEcomOrderShippingTable'                   => \MelisCommerce\Model\Tables\MelisEcomOrderShippingTable::class,
            'MelisEcomOrderStatusTable'                     => \MelisCommerce\Model\Tables\MelisEcomOrderStatusTable::class,
            'MelisEcomOrderStatusTransTable'                => \MelisCommerce\Model\Tables\MelisEcomOrderStatusTransTable::class,
            'MelisEcomOrderTable'                           => \MelisCommerce\Model\Tables\MelisEcomOrderTable::class,
            'MelisEcomPriceTable'                           => \MelisCommerce\Model\Tables\MelisEcomPriceTable::class,
            'MelisEcomProductAttributeTable'                => \MelisCommerce\Model\Tables\MelisEcomProductAttributeTable::class,
            'MelisEcomProductCategoryTable'                 => \MelisCommerce\Model\Tables\MelisEcomProductCategoryTable::class,
            'MelisEcomProductTable'                         => \MelisCommerce\Model\Tables\MelisEcomProductTable::class,
            'MelisEcomProductTextTable'                     => \MelisCommerce\Model\Tables\MelisEcomProductTextTable::class,
            'MelisEcomProductTextTypeTable'                 => \MelisCommerce\Model\Tables\MelisEcomProductTextTypeTable::class,
            'MelisEcomSeoTable'                             => \MelisCommerce\Model\Tables\MelisEcomSeoTable::class,
            'MelisEcomStockEmailAlertTable'                 => \MelisCommerce\Model\Tables\MelisEcomStockEmailAlertTable::class,
            'MelisEcomProductVariantAttributeValueTable'    => \MelisCommerce\Model\Tables\MelisEcomVariantAttributeValueTable::class,
            'MelisEcomVariantStockTable'                    => \MelisCommerce\Model\Tables\MelisEcomVariantStockTable::class,
            'MelisEcomVariantTable'                         => \MelisCommerce\Model\Tables\MelisEcomVariantTable::class,
            'MelisEcomClientGroupsTable'                    => \MelisCommerce\Model\Tables\MelisEcomClientGroupsTable::class,
            'MelisEcomOrderProductReturnTable'              => \MelisCommerce\Model\Tables\MelisEcomOrderProductReturnTable::class,
            'MelisEcomOrderProductReturnDetailsTable'       => \MelisCommerce\Model\Tables\MelisEcomOrderProductReturnDetailsTable::class,
            'MelisEcomProductLinksTable'                    => \MelisCommerce\Model\Tables\MelisEcomProductLinksTable::class,
            'MelisEcomClientPersonRelTable'                 => \MelisCommerce\Model\Tables\MelisEcomClientPersonRelTable::class,
        ],
        'abstract_factories' => [
            'Laminas\Cache\Service\StorageCacheAbstractServiceFactory',
        ]
    ],
    'controllers' => [
        'invokables' => [
            'MelisCommerce\Controller\MelisComCategoryList'         => \MelisCommerce\Controller\MelisComCategoryListController::class,
            'MelisCommerce\Controller\MelisComCategory'             => \MelisCommerce\Controller\MelisComCategoryController::class,
            'MelisCommerce\Controller\MelisComProductList'          => \MelisCommerce\Controller\MelisComProductListController::class,
            'MelisCommerce\Controller\MelisComProduct'              => \MelisCommerce\Controller\MelisComProductController::class,
            'MelisCommerce\Controller\MelisComVariantList'          => \MelisCommerce\Controller\MelisComVariantListController::class,
            'MelisCommerce\Controller\MelisComVariant'              => \MelisCommerce\Controller\MelisComVariantController::class,
            'MelisCommerce\Controller\MelisComDocument'             => \MelisCommerce\Controller\MelisComDocumentController::class,
            'MelisCommerce\Controller\MelisComClientList'           => \MelisCommerce\Controller\MelisComClientListController::class,
            'MelisCommerce\Controller\MelisComClient'               => \MelisCommerce\Controller\MelisComClientController::class,
            'MelisCommerce\Controller\MelisComOrder'                => \MelisCommerce\Controller\MelisComOrderController::class,
            'MelisCommerce\Controller\MelisComOrderCheckout'        => \MelisCommerce\Controller\MelisComOrderCheckoutController::class,
            'MelisCommerce\Controller\MelisComOrderList'            => \MelisCommerce\Controller\MelisComOrderListController::class,
            'MelisCommerce\Controller\MelisComPrice'                => \MelisCommerce\Controller\MelisComPriceController::class,
            'MelisCommerce\Controller\MelisComSeo'                  => \MelisCommerce\Controller\MelisComSeoController::class,
            'MelisCommerce\Controller\MelisComCouponList'           => \MelisCommerce\Controller\MelisComCouponListController::class,
            'MelisCommerce\Controller\MelisComCoupon'               => \MelisCommerce\Controller\MelisComCouponController::class,
            'MelisCommerce\Controller\MelisComCurrency'             => \MelisCommerce\Controller\MelisComCurrencyController::class,
            'MelisCommerce\Controller\MelisComAttributeList'        => \MelisCommerce\Controller\MelisComAttributeListController::class,
            'MelisCommerce\Controller\MelisComAttribute'            => \MelisCommerce\Controller\MelisComAttributeController::class,
            'MelisCommerce\Controller\MelisComLanguage'             => \MelisCommerce\Controller\MelisComLanguageController::class,
            'MelisCommerce\Controller\MelisComCountry'              => \MelisCommerce\Controller\MelisComCountryController::class,
            'MelisCommerce\Controller\Tester'                       => \MelisCommerce\Controller\TesterController::class,
            'MelisCommerce\Controller\Diagnostic'                   => \MelisCommerce\Controller\DiagnosticController::class,
            'MelisCommerce\Controller\MelisComAssociateVariant'     => \MelisCommerce\Controller\MelisComAssociateVariantController::class,
            'MelisCommerce\Controller\MelisComPrdVarDuplication'    => \MelisCommerce\Controller\MelisComPrdVarDuplicationController::class,
            'MelisCommerce\Controller\MelisComOrderStatus'          => \MelisCommerce\Controller\MelisComOrderStatusController::class,
            'MelisCommerce\Controller\MelisComSettings'             => \MelisCommerce\Controller\MelisComSettingsController::class,
            'MelisCommerce\Controller\MelisComClientsGroup'         => \MelisCommerce\Controller\MelisComClientsGroupController::class,
            'MelisCommerce\Controller\MelisComOrderProductReturn'   => \MelisCommerce\Controller\MelisComOrderProductReturnController::class,
            'MelisCommerce\Controller\MelisComContact'              => \MelisCommerce\Controller\MelisComContactController::class,
        ],
    ],
    'controller_plugins' => [
        'invokables' => [
            // Category plugins
            'MelisCommerceCategoryProductListPlugin'            => \MelisCommerce\Controller\Plugin\MelisCommerceCategoryProductListPlugin::class,
            'MelisCommerceCategoryTreePlugin'                   => \MelisCommerce\Controller\Plugin\MelisCommerceCategoryTreePlugin::class,

            // Product plugins
            'MelisCommerceProductSearchPlugin'                  => \MelisCommerce\Controller\Plugin\MelisCommerceProductSearchPlugin::class,
            'MelisCommerceProductPriceRangePlugin'              => \MelisCommerce\Controller\Plugin\MelisCommerceProductPriceRangePlugin::class,
            'MelisCommerceProductAttributePlugin'               => \MelisCommerce\Controller\Plugin\MelisCommerceProductAttributePlugin::class,
            'MelisCommerceProductListPlugin'                    => \MelisCommerce\Controller\Plugin\MelisCommerceProductListPlugin::class,
            'MelisCommerceRelatedProductsPlugin'                => \MelisCommerce\Controller\Plugin\MelisCommerceRelatedProductsPlugin::class,
            'MelisCommerceProductShowPlugin'                    => \MelisCommerce\Controller\Plugin\MelisCommerceProductShowPlugin::class,
            'MelisCommerceAttributesShowPlugin'                 => \MelisCommerce\Controller\Plugin\MelisCommerceAttributesShowPlugin::class,

            // Client plugins
            'MelisCommerceRegisterPlugin'                       => \MelisCommerce\Controller\Plugin\MelisCommerceRegisterPlugin::class,
            'MelisCommerceLoginPlugin'                          => \MelisCommerce\Controller\Plugin\MelisCommerceLoginPlugin::class,
            'MelisCommerceLostPasswordGetEmailPlugin'           => \MelisCommerce\Controller\Plugin\MelisCommerceLostPasswordGetEmailPlugin::class,
            'MelisCommerceLostPasswordResetPlugin'              => \MelisCommerce\Controller\Plugin\MelisCommerceLostPasswordResetPlugin::class,
            'MelisCommerceAccountPlugin'                        => \MelisCommerce\Controller\Plugin\MelisCommerceAccountPlugin::class,
            'MelisCommerceProfilePlugin'                        => \MelisCommerce\Controller\Plugin\MelisCommerceProfilePlugin::class,
            'MelisCommerceDeliveryAddressPlugin'                => \MelisCommerce\Controller\Plugin\MelisCommerceDeliveryAddressPlugin::class,
            'MelisCommerceBillingAddressPlugin'                 => \MelisCommerce\Controller\Plugin\MelisCommerceBillingAddressPlugin::class,

            // Orders
            'MelisCommerceAddToCartPlugin'                      => \MelisCommerce\Controller\Plugin\MelisCommerceAddToCartPlugin::class,
            'MelisCommerceCheckoutPlugin'                       => \MelisCommerce\Controller\Plugin\MelisCommerceCheckoutPlugin::class,
            'MelisCommerceCheckoutCartPlugin'                   => \MelisCommerce\Controller\Plugin\MelisCommerceCheckoutCartPlugin::class,
            'MelisCommerceCheckoutCouponPlugin'                 => \MelisCommerce\Controller\Plugin\MelisCommerceCheckoutCouponPlugin::class,
            'MelisCommerceCheckoutAddressesPlugin'              => \MelisCommerce\Controller\Plugin\MelisCommerceCheckoutAddressesPlugin::class,
            'MelisCommerceCheckoutSummaryPlugin'                => \MelisCommerce\Controller\Plugin\MelisCommerceCheckoutSummaryPlugin::class,
            'MelisCommerceCheckoutConfirmSummaryPlugin'         => \MelisCommerce\Controller\Plugin\MelisCommerceCheckoutConfirmSummaryPlugin::class,
            'MelisCommerceCheckoutConfirmPlugin'                => \MelisCommerce\Controller\Plugin\MelisCommerceCheckoutConfirmPlugin::class,
            'MelisCommerceCartPlugin'                           => \MelisCommerce\Controller\Plugin\MelisCommerceCartPlugin::class,
            'MelisCommerceOrderHistoryPlugin'                   => \MelisCommerce\Controller\Plugin\MelisCommerceOrderHistoryPlugin::class,
            'MelisCommerceOrderPlugin'                          => \MelisCommerce\Controller\Plugin\MelisCommerceOrderPlugin::class,
            'MelisCommerceOrderAddressPlugin'                   => \MelisCommerce\Controller\Plugin\MelisCommerceOrderAddressPlugin::class,
            'MelisCommerceOrderShippingDetailsPlugin'           => \MelisCommerce\Controller\Plugin\MelisCommerceOrderShippingDetailsPlugin::class,
            'MelisCommerceOrderMessagesPlugin'                  => \MelisCommerce\Controller\Plugin\MelisCommerceOrderMessagesPlugin::class,
            'MelisCommerceOrderReturnProductPlugin'             => \MelisCommerce\Controller\Plugin\MelisCommerceOrderReturnProductPlugin::class,

            // Dashboard plugins
            'MelisCommerceDashboardPluginOrdersNumber'          => \MelisCommerce\Controller\DashboardPlugins\MelisCommerceDashboardPluginOrdersNumber::class,
            'MelisCommerceDashboardPluginSalesRevenue'          => \MelisCommerce\Controller\DashboardPlugins\MelisCommerceDashboardPluginSalesRevenue::class,
            'MelisCommerceDashboardPluginOrderMessages'         => \MelisCommerce\Controller\DashboardPlugins\MelisCommerceDashboardPluginOrderMessages::class,
        ]
    ],
    'form_elements' => [
        'factories' => [
            'EcomCountriesSelect'                   => \MelisCommerce\Form\Factory\EcomCountriesSelectFactory::class,
            'EcomCountriesNoAllCountriesSelect'     => \MelisCommerce\Form\Factory\EcomCountriesNoAllCountriesSelectFactory::class,
            'EcomProductTextTypeSelect'             => \MelisCommerce\Form\Factory\EcomProductTextTypeSelectFactory::class,
            'EcomLanguageSelect'                    => \MelisCommerce\Form\Factory\EcomLanguageSelectFactory::class,
            'EcomDocumentFileSelect'                => \MelisCommerce\Form\Factory\EcomDocumentFileSelectFactory::class,
            'EcomDocumentImageTypeSelect'           => \MelisCommerce\Form\Factory\EcomDocumentImageTypeSelectFactory::class,
            'EcomCivilitySelect'                    => \MelisCommerce\Form\Factory\EcomCivilitySelectFactory::class,
            'EcomAddressTypeSelect'                 => \MelisCommerce\Form\Factory\EcomAddressTypeSelectFactory::class,
            'EcomOrderStatusSelect'                 => \MelisCommerce\Form\Factory\EcomOrderStatusSelectFactory::class,
            'EcomOrderStatusAllSelect'              => \MelisCommerce\Form\Factory\EcomOrderStatusAllSelectFactory::class,
            'EcomOrderClientsGroupSelect'           => \MelisCommerce\Form\Factory\EcomClientsGroupSelectFactory::class,

            'EcomAttributeTypeSelect'               => \MelisCommerce\Form\Factory\EcomAttributeTypeSelectFactory::class,
            'EcomCurrencySelect'                    => \MelisCommerce\Form\Factory\EcomCurrencySelectFactory::class,
            'EcomCheckoutBillingAddressSelect'      => \MelisCommerce\Form\Factory\EcomCheckoutBillingAddressSelectFactory::class,
            'EcomCheckoutDeliveryAddressSelect'     => \MelisCommerce\Form\Factory\EcomCheckoutDeliveryAddressSelectFactory::class,
            'EcomCatalogListSelect'                 => \MelisCommerce\Form\Factory\EcomCatalogListSelectFactory::class,

            'EcomCountriesAllStatusSelect'          => \MelisCommerce\Form\Factory\EcomCountriesAllStatusSelectFactory::class,
            'EcomCurrencyAllStatusSelect'           => \MelisCommerce\Form\Factory\EcomCurrencyAllStatusSelectFactory::class,
            'EcomLanguageAllStatusSelect'           => \MelisCommerce\Form\Factory\EcomLanguageAllStatusSelectFactory::class,
            'EcomColorPicker'                       => \MelisCommerce\Form\Factory\EcomColorPicker::class,

            // Plugins
            'EcomPluginCivilitySelect'              => \MelisCommerce\Form\Factory\Plugin\EcomPluginCivilitySelectFactory::class,
            'EcomPluginCountriesSelect'             => \MelisCommerce\Form\Factory\Plugin\EcomPluginCountriesSelectFactory::class,
            'EcomPluginLanguageSelect'              => \MelisCommerce\Form\Factory\Plugin\EcomPluginLanguageSelectFactory::class,
            'EcomPluginAddressTypeSelect'           => \MelisCommerce\Form\Factory\Plugin\EcomPluginAddressTypeSelectFactory::class,
            'EcomPluginDeliveryAddressSelect'       => \MelisCommerce\Form\Factory\Plugin\EcomPluginDeliveryAddressSelectFactory::class,
            'EcomPluginBillingAddressSelect'        => \MelisCommerce\Form\Factory\Plugin\EcomPluginBillingAddressSelectFactory::class,
            'EcomPluginCategoryListSelect'          => \MelisCommerce\Form\Factory\Plugin\EcomPluginCategoryListSelectFactory::class,
            'EcomPluginAttributeSelect'             => \MelisCommerce\Form\Factory\Plugin\EcomPluginAttributeSelectFactory::class,
            'EcomPluginPriceCountriesSelect'        => \MelisCommerce\Form\Factory\Plugin\EcomPluginPriceCountriesSelectFactory::class,
            'EcomPluginProductListSelect'           => \MelisCommerce\Form\Factory\Plugin\EcomPluginProductListSelectFactory::class,
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'ToolTipTable' => \MelisCommerce\View\Helper\ToolTipTableHelper::class,
        ],
        'aliases' => [
            'MelisCommerceLink' => \MelisCommerce\View\Helper\MelisCommerceLinksHelper::class
        ]
    ],
    'view_manager' => [
        'template_map' => [
            'layout/layout'                                         => __DIR__ . '/../view/layout/default.phtml',
            // Email Layout
            'MelisCommerce/emailLayout'                             => __DIR__ . '/../view/layout/email-layout.phtml',
            // Plugins layout
            //clients
            'MelisCommerce/ClientRegister'                          => __DIR__ . '/../view/plugins/clients/registration.phtml',
            'MelisCommerce/ClientLogin'                             => __DIR__ . '/../view/plugins/clients/login.phtml',
            'MelisCommerce/ClientLostPassword'                      => __DIR__ . '/../view/plugins/clients/lost-password.phtml',
            'MelisCommerce/ClientLostPasswordReset'                 => __DIR__ . '/../view/plugins/clients/lost-password-reset.phtml',
            'MelisCommerce/ClientAccount'                           => __DIR__ . '/../view/plugins/clients/account.phtml',
            'MelisCommerce/ClientProfile'                           => __DIR__ . '/../view/plugins/clients/profile.phtml',
            'MelisCommerce/ClientDeliveryAddress'                   => __DIR__ . '/../view/plugins/clients/delivery-address.phtml',
            'MelisCommerce/ClientBillingAddress'                    => __DIR__ . '/../view/plugins/clients/billing-address.phtml',
            'MelisCommerce/ClientMyCart'                            => __DIR__ . '/../view/plugins/clients/my-cart.phtml',

            //products
            'MelisCommerceProduct/show-product'                     => __DIR__ . '/../view/plugins/products/show-product.phtml',
            'MelisCommerceProduct/show-attributes'                  => __DIR__ . '/../view/plugins/products/show-attributes.phtml',
            'MelisCommerceProduct/related-products'                 => __DIR__ . '/../view/plugins/products/related-products.phtml',
            'MelisCommerceProduct/product-price-range'              => __DIR__ . '/../view/plugins/products/product-price-range.phtml',
            'MelisCommerce/product-list'                            => __DIR__ . '/../view/plugins/products/product-list.phtml',
            'MelisCommerce/product-list-attributes-config'          => __DIR__ . '/../view/plugins/products/product-list-attributes-config.phtml',
            'MelisCommerce/product-list-text-types-config'          => __DIR__ . '/../view/plugins/products/product-list-text-types-config.phtml',
            'MelisCommerce/product-list-paginator'                  => __DIR__ . '/../view/plugins/products/product-list-paginator.phtml',
            'MelisCommerce/product-attribute'                       => __DIR__ . '/../view/plugins/products/product-attribute.phtml',
            'MelisCommerce/product-search'                          => __DIR__ . '/../view/plugins/products/product-search.phtml',
            'MelisCommerce/product-list-tree-config'                => __DIR__ . '/../view/plugins/products/product-list-tree-config.phtml',

            //checkout
            'MelisCommerceCheckout/checkout'                        => __DIR__ . '/../view/plugins/checkout/checkout.phtml',
            'MelisCommerceCheckout/checkout-cart'                   => __DIR__ . '/../view/plugins/checkout/checkout-cart.phtml',
            'MelisCommerceCheckout/checkout-coupon'                 => __DIR__ . '/../view/plugins/checkout/checkout-coupon.phtml',
            'MelisCommerceCheckout/checkout-addresses'              => __DIR__ . '/../view/plugins/checkout/checkout-addresses.phtml',
            'MelisCommerceCheckout/checkout-summary'                => __DIR__ . '/../view/plugins/checkout/checkout-summary.phtml',
            'MelisCommerceCheckout/checkout-confirm-summary'        => __DIR__ . '/../view/plugins/checkout/checkout-confirm-summary.phtml',
            'MelisCommerceCheckout/checkout-confirm'                => __DIR__ . '/../view/plugins/checkout/checkout-confirm.phtml',

            'MelisCommerceCategory/category-product-list'           => __DIR__ . '/../view/plugins/categories/category-product-list.phtml',
            'MelisCommerceCategory/category-tree'                   => __DIR__ . '/../view/plugins/categories/category-tree.phtml',
            'MelisCommerce/category-product-list-tree-config'       => __DIR__ . '/../view/plugins/categories/category-product-list-tree-config.phtml',
            'MelisCommerce/category-tree-config'                    => __DIR__ . '/../view/plugins/categories/category-tree-config.phtml',

            'MelisCommerceCart/cart'                                => __DIR__ . '/../view/plugins/cart/cart.phtml',

            'MelisCommerceOrder/add-to-cart'                        => __DIR__ . '/../view/plugins/order/add-to-cart.phtml',
            'MelisCommerceOrder/order-history'                      => __DIR__ . '/../view/plugins/order/order-history.phtml',
            'MelisCommerceOrder/order-history-paginator'            => __DIR__ . '/../view/plugins/order/order-history-paginator.phtml',
            'MelisCommerceOrder/order-details'                      => __DIR__ . '/../view/plugins/order/order-details.phtml',
            'MelisCommerceOrder/order-addresses'                    => __DIR__ . '/../view/plugins/order/order-addresses.phtml',
            'MelisCommerceOrder/order-shipping-details'             => __DIR__ . '/../view/plugins/order/order-shipping-details.phtml',
            'MelisCommerceOrder/order-messages'                     => __DIR__ . '/../view/plugins/order/order-messages.phtml',
            'MelisCommerceOrder/order-return-product'               => __DIR__ . '/../view/plugins/order/order-return-product.phtml',

            // Plugin common form config layout
            'MelisCommerce/plugin-common-form-config'               => __DIR__ . '/../view/plugins/common/plugin-common-form-config.phtml',
            'MelisCommerce/plugin-common-pagination'                => __DIR__ . '/../view/plugins/common/plugin-common-pagination.phtml',

            // Dashboard plugins
            'MelisCommerceDashboardPluginOrdersNumber/dashboard/commerce-orders'            => __DIR__ . '/../view/dashboard-plugins/commerce-dashboard-plugin-orders-number.phtml',
            'MelisCommerceDashboardPluginSalesRevenue/dashboard/commerce-sales-revenue'     => __DIR__ . '/../view/dashboard-plugins/commerce-dashboard-plugin-sales-revenue.phtml',
            'MelisCommerceDashboardPluginOrderMessages/commerce-orders-messages'            => __DIR__ . '/../view/dashboard-plugins/commerce-dashboard-plugin-order-messages.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'caches' => [
        'commerce_memory_services' => [ 
            'active' => true, // activate or deactivate Melis Cache for this conf
            'adapter' => [
                'name'    => 'Memory',
                'options' => ['ttl' => 0, 'namespace' => 'meliscommerce'],
            ],
            'plugins' => [
                'exception_handler' => ['throw_exceptions' => false],
            ],
            'ttls' => [
                // add a specific ttl for a specific cache key (found via regexp]
                // 'my_cache_key' => 60,
            ]
        ],
        'commerce_big_services' => [
            'active' => true, // activate or deactivate Melis Cache for this conf
            'adapter' => [
                'name'    => 'Filesystem',
                'options' => [
                    'ttl' => 60 * 60 * 24, // 24hrs
                    'namespace' => 'meliscommerce',
                    'cache_dir' => $_SERVER['DOCUMENT_ROOT'] . '/../cache'
                ],
            ],
            'plugins' => [
                'exception_handler' => ['throw_exceptions' => false],
                'Serializer'
            ],
            'ttls' => [
                // add a specific ttl for a specific cache key (found via regexp]
                // 'my_cache_key' => 60,
            ]
        ],
    ],
];