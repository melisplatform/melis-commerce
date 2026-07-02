<?php
/**
 * Configuration des routes React API fournies par MelisCommerce.
 *
 * Ces routes s'ajoutent aux child_routes de `melis-react-api` (défini dans MelisReactApi).
 * Laminas::ArrayUtils::merge() les fusionnera automatiquement.
 * Les URLs ne changent pas : /melis/react-api/accounts/*, /contacts/*, /catalog/*, /products/*, /orders/*.
 */

return [
    'router' => [
        'routes' => [
            'melis-backoffice' => [
                'child_routes' => [
                    'melis-react-api' => [
                        'child_routes' => [

                            // ─── Accounts (MelisCommerce) ──────────────────────────────────────
                            'accounts-list' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/accounts[/]',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'MelisCommerce\Controller',
                                        'controller'    => 'MelisComReactApiAccount',
                                        'action'        => 'list',
                                    ],
                                ],
                            ],
                            'accounts-stats' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/accounts/stats[/]',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'MelisCommerce\Controller',
                                        'controller'    => 'MelisComReactApiAccount',
                                        'action'        => 'stats',
                                    ],
                                ],
                            ],
                            'accounts-options' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/accounts/options[/]',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'MelisCommerce\Controller',
                                        'controller'    => 'MelisComReactApiAccount',
                                        'action'        => 'options',
                                    ],
                                ],
                            ],
                            'accounts-save' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/accounts/save[/]',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'MelisCommerce\Controller',
                                        'controller'    => 'MelisComReactApiAccount',
                                        'action'        => 'save',
                                    ],
                                ],
                            ],
                            'accounts-delete' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/accounts/delete/:id',
                                    'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => [
                                        '__NAMESPACE__' => 'MelisCommerce\Controller',
                                        'controller'    => 'MelisComReactApiAccount',
                                        'action'        => 'delete',
                                    ],
                                ],
                            ],
                            'accounts-item' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/accounts/:id',
                                    'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => [
                                        '__NAMESPACE__' => 'MelisCommerce\Controller',
                                        'controller'    => 'MelisComReactApiAccount',
                                        'action'        => 'get',
                                    ],
                                ],
                            ],
                            'accounts-company' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/company[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'company'],
                                ],
                            ],
                            'accounts-company-save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/company/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'companySave'],
                                ],
                            ],
                            'accounts-contacts' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/contacts[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'contacts'],
                                ],
                            ],
                            'accounts-addresses' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/addresses[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'addresses'],
                                ],
                            ],
                            'accounts-orders' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/orders[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'orders'],
                                ],
                            ],
                            'accounts-files' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/files[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'files'],
                                ],
                            ],
                            'accounts-file-upload' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/files/upload[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'fileUpload'],
                                ],
                            ],
                            'accounts-file-delete' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/files/:fileId', 'constraints' => ['id' => '[0-9]+', 'fileId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'fileDelete'],
                                ],
                            ],
                            'accounts-file-options' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/file-options[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'fileOptions'],
                                ],
                            ],
                            'accounts-file-types' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/file-types[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'fileTypeCreate'],
                                ],
                            ],
                            'accounts-address-options' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/address-options[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'addressOptions'],
                                ],
                            ],
                            'accounts-addresses-save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/addresses/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'addressesSave'],
                                ],
                            ],
                            'accounts-order-statuses' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/order-statuses[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'orderStatuses'],
                                ],
                            ],
                            'accounts-contact-options' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/contact-options[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'contactOptions'],
                                ],
                            ],
                            'accounts-contact-link' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/contacts/link[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'contactLink'],
                                ],
                            ],
                            'accounts-contact-default' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/contacts/:contactId/default[/]', 'constraints' => ['id' => '[0-9]+', 'contactId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'contactDefault'],
                                ],
                            ],
                            'accounts-contact-unlink' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/accounts/:id/contacts/:contactId', 'constraints' => ['id' => '[0-9]+', 'contactId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiAccount', 'action' => 'contactUnlink'],
                                ],
                            ],

                            // ─── Contacts (MelisCommerce) ──────────────────────────────────────
                            'contacts-list' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/contacts[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'list'],
                                ],
                            ],
                            'contacts-stats' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/contacts/stats[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'stats'],
                                ],
                            ],
                            'contacts-options' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/contacts/options[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'options'],
                                ],
                            ],
                            'contacts-save' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/contacts/save[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'save'],
                                ],
                            ],
                            'contacts-delete' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/contacts/delete/:id',
                                    'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'delete'],
                                ],
                            ],
                            'contacts-address-options' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/contacts/address-options[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'addressOptions'],
                                ],
                            ],
                            'contacts-addresses-save' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/contacts/:id/addresses/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'addressesSave'],
                                ],
                            ],
                            'contacts-addresses' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/contacts/:id/addresses[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'addresses'],
                                ],
                            ],
                            'contacts-associations' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/contacts/:id/associations[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'associations'],
                                ],
                            ],
                            'contacts-assoc-link' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/contacts/:id/associations/link[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'associationLink'],
                                ],
                            ],
                            'contacts-assoc-default' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/contacts/:id/associations/:accountId/default[/]', 'constraints' => ['id' => '[0-9]+', 'accountId' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'associationDefault'],
                                ],
                            ],
                            'contacts-assoc-unlink' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/contacts/:id/associations/:accountId', 'constraints' => ['id' => '[0-9]+', 'accountId' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'associationUnlink'],
                                ],
                            ],
                            'contacts-item' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/contacts/:id',
                                    'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiContact', 'action' => 'get'],
                                ],
                            ],

                            // ─── Catalogues / Catégories (MelisCommerce) ───────────────────────
                            'catalog-tree' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/catalog/tree[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCatalog', 'action' => 'tree'],
                                ],
                            ],
                            'catalog-options' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/catalog/options[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCatalog', 'action' => 'options'],
                                ],
                            ],
                            'catalog-save' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/catalog/save[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCatalog', 'action' => 'save'],
                                ],
                            ],
                            'catalog-reorder' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/catalog/reorder[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCatalog', 'action' => 'reorder'],
                                ],
                            ],
                            'catalog-delete' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/catalog/delete/:id',
                                    'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCatalog', 'action' => 'delete'],
                                ],
                            ],
                            'catalog-seo' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/catalog/:id/seo[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCatalog', 'action' => 'seo'],
                                ],
                            ],
                            'catalog-seo-save' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/catalog/:id/seo/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCatalog', 'action' => 'seoSave'],
                                ],
                            ],
                            'catalog-products' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/catalog/:id/products[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCatalog', 'action' => 'products'],
                                ],
                            ],
                            'catalog-item' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/catalog/:id',
                                    'constraints' => ['id' => '[0-9]+'],
                                    'defaults'    => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCatalog', 'action' => 'get'],
                                ],
                            ],

                            // ─── Produits (MelisCommerce) ──────────────────────────────────────
                            'products-list' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'list'] ],
                            ],
                            'products-page-tree' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/page-tree[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'pageTree'] ],
                            ],
                            'products-stats' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/stats[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'stats'] ],
                            ],
                            'products-options' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/options[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'options'] ],
                            ],
                            'products-save' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/save[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'save'] ],
                            ],
                            'products-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/delete/:id', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'delete'] ],
                            ],
                            'products-variant-save' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantSave'] ],
                            ],
                            'products-variant-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantDelete'] ],
                            ],
                            'products-variants' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variants'] ],
                            ],
                            'products-variant-get' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/detail[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantGet'] ],
                            ],
                            'products-variant-price-save' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/prices/save[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantPriceSave'] ],
                            ],
                            'products-variant-price-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/prices/:priceId', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+', 'priceId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantPriceDelete'] ],
                            ],
                            'products-variant-prices' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/prices[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantPrices'] ],
                            ],
                            'products-variant-stock-save' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/stocks/save[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantStockSave'] ],
                            ],
                            'products-variant-stock-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/stocks/:stockId', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+', 'stockId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantStockDelete'] ],
                            ],
                            'products-variant-stocks' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/stocks[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantStocks'] ],
                            ],
                            'products-variant-seo-save' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/seo/save[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantSeoSave'] ],
                            ],
                            'products-variant-media' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/media[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantMedia'] ],
                            ],
                            'products-variant-assoc-save' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/assoc/save[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantAssocSave'] ],
                            ],
                            'products-variant-assoc-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/assoc/:avarId', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+', 'avarId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantAssocDelete'] ],
                            ],
                            'products-variant-assoc' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/assoc[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantAssoc'] ],
                            ],
                            'products-variant-attr-save' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/attributes/save[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantAttributeSave'] ],
                            ],
                            'products-variant-attr-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/attributes/:vatvId', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+', 'vatvId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantAttributeDelete'] ],
                            ],
                            'products-variant-attrs' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/attributes[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantAttributes'] ],
                            ],
                            'products-price-save' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/prices/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'priceSave'] ],
                            ],
                            'products-price-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/prices/:priceId', 'constraints' => ['id' => '[0-9]+', 'priceId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'priceDelete'] ],
                            ],
                            'products-prices' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/prices[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'prices'] ],
                            ],
                            'products-attr-save' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/attributes/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'attributeSave'] ],
                            ],
                            'products-attr-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/attributes/:pattId', 'constraints' => ['id' => '[0-9]+', 'pattId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'attributeDelete'] ],
                            ],
                            'products-attributes' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/attributes[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'attributes'] ],
                            ],
                            'products-document-types' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/document-types[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'documentTypes'] ],
                            ],
                            'products-media-update' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/media/:docId/update[/]', 'constraints' => ['id' => '[0-9]+', 'docId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'mediaUpdate'] ],
                            ],
                            'products-media-upload' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/media/upload[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'mediaUpload'] ],
                            ],
                            'products-media-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/media/:docId', 'constraints' => ['id' => '[0-9]+', 'docId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'mediaDelete'] ],
                            ],
                            'products-media' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/media[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'media'] ],
                            ],
                            'products-variant-media-upload' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/media/upload[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantMediaUpload'] ],
                            ],
                            'products-variant-media-update' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/media/:docId/update[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+', 'docId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantMediaUpdate'] ],
                            ],
                            'products-variant-media-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/media/:docId', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+', 'docId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantMediaDelete'] ],
                            ],
                            'products-variant-duplicate' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/variants/:variantId/duplicate[/]', 'constraints' => ['id' => '[0-9]+', 'variantId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'variantDuplicate'] ],
                            ],
                            'products-recipient-save' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/alert/recipients/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'recipientSave'] ],
                            ],
                            'products-recipient-delete' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/alert/recipients/:seaId', 'constraints' => ['id' => '[0-9]+', 'seaId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'recipientDelete'] ],
                            ],
                            'products-alert' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/alert[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'alert'] ],
                            ],
                            'products-duplicate' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/duplicate[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'duplicate'] ],
                            ],
                            'products-tooltip' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id/tooltip[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'tooltip'] ],
                            ],
                            'products-item' => [
                                'type' => 'Segment',
                                'options' => [ 'route' => '/products/:id', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiProduct', 'action' => 'get'] ],
                            ],

                            // ─── Orders (MelisCommerce) ────────────────────────────────────────
                            'orders-list' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'list'],
                                ],
                            ],
                            'orders-stats' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/stats[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'stats'],
                                ],
                            ],
                            'orders-statuses' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/statuses[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'statuses'],
                                ],
                            ],
                            'orders-create' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/create[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'create'],
                                ],
                            ],
                            'orders-save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'save'],
                                ],
                            ],
                            'orders-status-save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id/status[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'statusSave'],
                                ],
                            ],
                            'orders-address-save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id/address[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'addressSave'],
                                ],
                            ],
                            'orders-shipping-save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id/shipping[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'shippingSave'],
                                ],
                            ],
                            'orders-message-save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id/message[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'messageSave'],
                                ],
                            ],
                            'orders-returns' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id/returns[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'returns'],
                                ],
                            ],
                            'orders-attachments-list' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id/attachments[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'attachmentsList'],
                                ],
                            ],
                            'orders-attachment-upload' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id/attachments/upload[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'attachmentUpload'],
                                ],
                            ],
                            'orders-attachment-delete' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id/attachments/:docId/delete[/]', 'constraints' => ['id' => '[0-9]+', 'docId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'attachmentDelete'],
                                ],
                            ],
                            'orders-attachment-download' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id/attachments/:docId/download[/]', 'constraints' => ['id' => '[0-9]+', 'docId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'attachmentDownload'],
                                ],
                            ],
                            'orders-item' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/orders/:id', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiOrder', 'action' => 'get'],
                                ],
                            ],

                            // ─── Coupons (MelisCommerce) ───────────────────────────────────────
                            'coupons-list' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'list'],
                                ],
                            ],
                            'coupons-stats' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/stats[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'stats'],
                                ],
                            ],
                            'coupons-save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/save[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'save'],
                                ],
                            ],
                            'coupons-clients-directory' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/clients-directory[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'clientsDirectory'],
                                ],
                            ],
                            'coupons-products-directory' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/products-directory[/]',
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'productsDirectory'],
                                ],
                            ],
                            'coupons-delete' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/delete/:id[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'delete'],
                                ],
                            ],
                            'coupons-clients-list' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/:id/clients[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'clients'],
                                ],
                            ],
                            'coupons-clients-save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/:id/clients/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'clientSave'],
                                ],
                            ],
                            'coupons-clients-delete' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/:id/clients/:ccliId/delete[/]', 'constraints' => ['id' => '[0-9]+', 'ccliId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'clientDelete'],
                                ],
                            ],
                            'coupons-products-list' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/:id/products[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'products'],
                                ],
                            ],
                            'coupons-products-save' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/:id/products/save[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'productSave'],
                                ],
                            ],
                            'coupons-products-delete' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/:id/products/:cprodId/delete[/]', 'constraints' => ['id' => '[0-9]+', 'cprodId' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'productDelete'],
                                ],
                            ],
                            'coupons-orders' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/:id/orders[/]', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'orders'],
                                ],
                            ],
                            'coupons-item' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/coupons/:id', 'constraints' => ['id' => '[0-9]+'],
                                    'defaults' => ['__NAMESPACE__' => 'MelisCommerce\Controller', 'controller' => 'MelisComReactApiCoupon', 'action' => 'get'],
                                ],
                            ],

                        ], // end child_routes melis-react-api
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'invokables' => [
            'MelisCommerce\Controller\MelisComReactApiAccount' => \MelisCommerce\Controller\ReactApi\MelisComReactApiAccountController::class,
            'MelisCommerce\Controller\MelisComReactApiContact' => \MelisCommerce\Controller\ReactApi\MelisComReactApiContactController::class,
            'MelisCommerce\Controller\MelisComReactApiCatalog' => \MelisCommerce\Controller\ReactApi\MelisComReactApiCatalogController::class,
            'MelisCommerce\Controller\MelisComReactApiProduct' => \MelisCommerce\Controller\ReactApi\MelisComReactApiProductController::class,
            'MelisCommerce\Controller\MelisComReactApiOrder'   => \MelisCommerce\Controller\ReactApi\MelisComReactApiOrderController::class,
            'MelisCommerce\Controller\MelisComReactApiCoupon'  => \MelisCommerce\Controller\ReactApi\MelisComReactApiCouponController::class,
        ],
    ],
];
