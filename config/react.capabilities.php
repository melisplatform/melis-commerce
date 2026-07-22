<?php

/**
 * Capacités d'outils — droits avancés du back-office React (déclaration plate, par module).
 *
 * Fichier VOLONTAIREMENT SÉPARÉ (pas dans module.config.php), même convention que
 * melis-core/config/react.capabilities.php : chaque module déclare ICI les capacités de
 * SES outils, par `melisKey`. Indépendant de `app.interface.php` et du rendu legacy.
 *
 * Sémantique : ces capacités gouvernent les COMPOSANTS INTERNES d'un outil déjà autorisé
 * (liste / créer / éditer / supprimer / dupliquer / exporter). Default-allow — déclaratif
 * uniquement, pilote l'affichage des cases à cocher dans l'onglet Rights (aucune application
 * côté contrôleur pour l'instant). Lu par MelisReactApi\Service\Capabilities via la config
 * mergée (clé `melisReactToolCapabilities`). Mergé dans MelisCommerce\Module::getConfig().
 *
 * Deux formes de valeur par melisKey :
 *   1. Liste plate historique   : ['list', 'create', 'edit', 'delete']
 *   2. Arbre (onglets imbriqués) : [
 *        'actions' => ['list', ...],                        // capacités de l'outil lui-même
 *        'tabs' => [
 *          ['label' => 'tr_...'],                            // onglet SANS droits (repère visuel :
 *                                                            // pas d'action propre — sauvé avec l'outil)
 *          ['key' => 'variants', 'label' => 'tr_...', 'actions' => [...], 'tabs' => [...]],  // avec droits
 *        ],
 *      ]
 * CHAQUE onglet portant une `key` a SA PROPRE case à cocher (accès à l'onglet) ; s'il déclare en
 * plus des `actions`, elles s'affichent en sous-cases sous son bloc. Un onglet SANS `key` (rétro-
 * compat) reste un simple repère textuel. Profondeur illimitée.
 *
 * ⚠️ N'inclure QUE les actions réellement présentes dans l'onglet (ex. Variants n'a PAS d'export
 * → pas de case export). `Capabilities::flatten()` (melis-react-api) aplati l'arbre en clés
 * `chemin` (l'onglet lui-même) et `chemin.action` (ses actions), ex. `variants` + `variants.list`.
 *
 * 🌐 Les `label` sont des CLÉS de traduction Melis existantes (`tr_...`, celles-là mêmes que les
 * onglets du legacy) — PAS du texte en dur. `rightsCapabilitiesAction` les résout dans la locale
 * courante via MelisCoreTranslation avant de répondre. Les libellés d'ACTIONS (list/create/…) sont
 * traduits côté React (dictionnaire melis-core `caps.*`).
 */

return [
    'melisReactToolCapabilities' => [
        // Accounts : onglets Contacts (link/unlink = create/delete) et Files (upload/delete) ont
        // leur propre CRUD ; Properties/Company/Addresses/Orders sont sauvés/liés au formulaire.
        'meliscommerce_clients_list_page' => [
            'actions' => ['list', 'create', 'edit', 'delete', 'export'],
            'tabs' => [
                ['key' => 'properties', 'label' => 'tr_meliscommerce_client_page_tab_main'],       // Properties
                ['key' => 'contacts',   'label' => 'tr_meliscommerce_client_page_tab_contact',      // Contacts
                    'actions' => ['list', 'create', 'delete']],                                      // link / unlink
                ['key' => 'company',    'label' => 'tr_meliscommerce_client_page_tab_company'],      // Company
                ['key' => 'addresses',  'label' => 'tr_meliscommerce_client_page_tab_address'],      // Addresses
                ['key' => 'orders',     'label' => 'tr_meliscommerce_client_page_tab_orders'],       // Orders
                ['key' => 'files',      'label' => 'tr_meliscommerce_clients_Contact_tab_files',     // Files
                    'actions' => ['list', 'create', 'delete']],                                      // upload / delete
            ],
        ],

        // Contacts : onglet Association (link/unlink) a son propre CRUD ; Information/Address avec le form.
        'meliscommerce_contact_list_page' => [
            'actions' => ['list', 'create', 'edit', 'delete', 'export'],
            'tabs' => [
                ['key' => 'information', 'label' => 'tr_meliscommerce_contact_page_content_tab_information'], // Information
                ['key' => 'address',     'label' => 'tr_meliscommerce_contact_page_content_tab_address'],     // Address
                ['key' => 'association',  'label' => 'tr_meliscommerce_contact_page_content_tab_association',  // Association
                    'actions' => ['list', 'create', 'delete']],                                               // link / unlink
            ],
        ],

        // Catalogs : tous les onglets sont sauvés avec le formulaire ou en lecture seule → repères sans droits.
        'meliscommerce_categories_page' => [
            'actions' => ['list', 'create', 'edit', 'delete'], // pas d'export
            'tabs' => [
                ['key' => 'properties', 'label' => 'tr_meliscommerce_categories_common_label_main'],    // Properties
                ['key' => 'seo',        'label' => 'tr_meliscommerce_categories_common_label_seo'],      // SEO
                ['key' => 'products',   'label' => 'tr_meliscommerce_categories_common_label_producs'],  // Products (lecture seule)
            ],
        ],

        // Products : outil (list/create/edit/delete/duplicate/export) + onglets. Seul Variants a une
        // CRUD propre (variantsAction/variantSaveAction/variantDeleteAction/variantDuplicateAction —
        // PAS d'export) ; Main/Text/SEO/Prices sont sauvés avec le produit → repères sans droits.
        'meliscommerce_product_list_container' => [
            'actions' => ['list', 'create', 'edit', 'delete', 'duplicate', 'export'],
            // Ordre des onglets = celui du formulaire (ProductPage.tsx : main/text/variants/seo/prices).
            // Chaque onglet a une `key` → il porte SA PROPRE case à cocher (accès à l'onglet).
            'tabs' => [
                ['key' => 'main',     'label' => 'tr_meliscommerce_products_page_tab_content_main'],   // Properties
                ['key' => 'text',     'label' => 'tr_meliscommerce_products_page_content_tab_text'],   // Text
                [
                    'key' => 'variants', 'label' => 'tr_meliscommerce_products_page_content_tab_variants',
                    'actions' => ['list', 'create', 'edit', 'delete', 'duplicate'], // pas d'export
                    // Clés = celles de l'éditeur de variante (ProductVariantEditor.tsx TABS) pour le gating.
                    'tabs' => [
                        ['key' => 'properties', 'label' => 'tr_meliscommerce_variant_tab_main'],    // Properties
                        ['key' => 'seo',        'label' => 'tr_meliscommerce_seo_Seo'],             // SEO
                        ['key' => 'prices',     'label' => 'tr_meliscommerce_prices_tab'],          // Prices
                        ['key' => 'stocks',     'label' => 'tr_meliscommerce_variant_tab_stocks'],  // Stocks
                        ['key' => 'assoc',      'label' => 'tr_meliscommerce_avar_title'],          // Associations
                    ],
                ],
                ['key' => 'seo',    'label' => 'tr_meliscommerce_products_page_content_tab_seo'],   // SEO
                ['key' => 'prices', 'label' => 'tr_meliscommerce_prices_tab'],                      // Prices
            ],
        ],

        // Orders (pas de suppression). Onglets à CRUD propre : Addresses (edit), Shipping (add),
        // Messages (send) ; Basket/Payment/Returns sont en lecture seule ; Information = form principal.
        'meliscommerce_order_list_page' => [
            'actions' => ['list', 'create', 'edit', 'export'], // pas de delete
            'tabs' => [
                ['key' => 'information', 'label' => 'tr_meliscommerce_orders_content_tab_main'],            // Properties
                ['key' => 'basket',      'label' => 'tr_meliscommerce_orders_content_tab_basket'],          // Basket (lecture seule)
                ['key' => 'addresses',   'label' => 'tr_meliscommerce_orders_content_tab_address',          // Addresses
                    'actions' => ['edit']],                                                                  // saveOrderAddress
                ['key' => 'payment',     'label' => 'tr_meliscommerce_orders_content_tab_paymnet'],         // Payment (lecture seule)
                ['key' => 'shipping',    'label' => 'tr_meliscommerce_orders_content_tab_shipping',         // Shipping
                    'actions' => ['list', 'create']],                                                        // saveOrderShipping
                ['key' => 'messages',    'label' => 'tr_meliscommerce_orders_content_tab_messages',         // Messages
                    'actions' => ['list', 'create']],                                                        // saveOrderMessage
                ['key' => 'returns',     'label' => 'tr_meliscommerce_orders_content_tab_return_products'],  // Returns (lecture seule)
                ['key' => 'invoices',    'label' => 'tr_meliscommerce_order_invoice_invoices'],              // Invoices (lecture seule) — onglet apporté par MelisCommerceOrderInvoice si actif
            ],
        ],

        // Coupons : onglets Assign account/product sont en brouillon (persistés par le Save principal),
        // Orders = historique lecture seule → tous des repères sans droits propres.
        'meliscommerce_coupon_list_page' => [
            'actions' => ['list', 'create', 'edit', 'delete', 'export'],
            'tabs' => [
                ['key' => 'information', 'label' => 'tr_meliscommerce_coupon_page_tabs_main'],                 // Properties
                ['key' => 'clients',     'label' => 'tr_meliscommerce_coupon_page_tabs_assign'],               // Assign account
                ['key' => 'products',    'label' => 'tr_meliscommerce_coupon_page_tabs_assign_product_tab'],   // Assign product
                ['key' => 'orders',      'label' => 'tr_meliscommerce_coupon_page_tabs_orders'],               // Orders (lecture seule)
            ],
        ],

        // Attributes : Main (reference/type/status/visible/searchable) et Labels (traductions
        // name/description) sont sauvés avec le formulaire ; Values a sa propre CRUD (liste des
        // valeurs de l'attribut, chacune avec ses traductions typées) → seul cet onglet a des actions.
        'meliscommerce_attribute_list_page' => [
            'actions' => ['list', 'create', 'edit', 'delete', 'export'],
            'tabs' => [
                ['key' => 'main',   'label' => 'tr_meliscommerce_attribute_page_tabs_main'],     // Properties
                ['key' => 'labels', 'label' => 'tr_meliscommerce_attribute_page_tabs_labels'],    // Labels (traductions)
                ['key' => 'values', 'label' => 'tr_meliscommerce_attribute_page_tabs_values',     // Values
                    'actions' => ['list', 'create', 'edit', 'delete']],
            ],
        ],

        // Countries / Commerce languages / Currencies / Client's groups : outils "réglages" à
        // formulaire unique (pas d'onglets — modale ou page à un seul bloc de champs).
        'meliscommerce_country_list_container' => [
            'actions' => ['list', 'create', 'edit', 'delete', 'export'],
        ],
        'meliscommerce_language_list_container' => [
            'actions' => ['list', 'create', 'edit', 'delete', 'export'],
        ],
        'meliscommerce_currency_conf' => [
            'actions' => ['list', 'create', 'edit', 'delete', 'export'],
        ],
        'meliscommerce_clients_group_tool_container' => [
            'actions' => ['list', 'create', 'edit', 'delete', 'export'],
        ],

        // Statuts de commande : Properties (couleur/statut) et Labels (traductions par langue)
        // sont tous deux sauvés avec le formulaire → repères sans droits propres.
        'meliscommerce_order_status_tool_page' => [
            'actions' => ['list', 'create', 'edit', 'delete', 'export'],
            'tabs' => [
                // Legacy n'a pas d'onglets pour ce formulaire (simple modale) — ces 2 onglets sont
                // une réorganisation React ; on réutilise les clés Properties/Labels d'Attributs
                // (même sens exact : propriétés générales vs traductions par langue).
                ['key' => 'main',   'label' => 'tr_meliscommerce_attribute_page_tabs_main'],   // Properties
                ['key' => 'labels', 'label' => 'tr_meliscommerce_attribute_page_tabs_labels'], // Labels (traductions)
            ],
        ],

        // Paramètres commerce : page UNIQUE (pas de liste/suppression) — seule l'action `edit`
        // a un sens ; les deux onglets (Properties/Accounts) sont des repères sans droits propres.
        'meliscommerce_settings_page' => [
            'actions' => ['edit'],
            'tabs' => [
                ['key' => 'main',     'label' => 'tr_meliscommerce_settings_page_tabs_main'],     // Properties
                ['key' => 'accounts', 'label' => 'tr_meliscommerce_settings_page_tabs_accounts'],  // Accounts
            ],
        ],
    ],
];
