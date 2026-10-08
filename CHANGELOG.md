## v6.0.7 - 2026-09-25
### Security
* **security:** declare a grantable tool key on the legacy controller (audit item 7.0)
* **security:** declare the tool key on this module's legacy controllers (audit item 7.0)
### Fixed
* **react-api:** return 400 with reason on rejected media upload

## v6.0.6 - 2026-09-23
### Security
* Revert(security): remove the audit 17.0 coupon rate limiting
* **security:** rate limiting on coupon validation (audit item 17.0)
* **security:** parameterised SQL for date filters, ORDER BY and hand-quoted values (audit item 11.0)

## v6.0.4 - 2026-08-20
### Docs
* **melisai:** React back-office AI documentation

## v6.0.3 - 2026-08-20
### Security
* **melis-commerce:** gate mutating/data actions in legacy tool controllers (CWE-862)
### Added
* **ui-react:** use </> code icon on the New view toggle
* **react:** generic seams so an active module can extend the checkout and the category form
### Fixed
* **router:** make the melis-front route self-sufficient (Mantis #0010921)
* **react:** read the discount tab's label from the brick itself

## v6.0.1 - 2026-08-10
### Added
* **composer:** add docs link and authors block, swap zf2 keyword for laminas, bump php constraint to ^8.3|^8.5

## v6.0.0 - 2026-08-10
### Security
* **security:** add SECURITY.md (private vulnerability reporting policy)
* Fix audit findings
* Apply user rights
### Added
* **webservices:** expose commerce catalog read microservices (category, product, variant, attribute, price, currency) + new MelisComCountryService; MelisCommerce WS section
* **commerce-react:** onglet Association du contact en scroll infini keyset + toolbar unifiee
* **commerce-react:** onglet Commandes du compte en scroll infini keyset (tri server-side)
* **commerce-react:** liens cliquables nom produit + SKU variant dans l'association
* **commerce-react:** onglet Variants produit en scroll infini keyset (tri + recherche server-side)
* **commerce-react:** unifier les listes sur la pagination keyset (scroll infini + tri server-side)
* **commerce-react:** badges statut uniformes + coupons (devise, 2-col, liens compte/commande/produit, pagination 30)
* **commerce-react:** bouton Nouveau sur la meme ligne que le toggle New/Old + refresh
* **commerce-orders:** basket product links, 2nd payment display, themed shipping modal, datepicker flip-up
* **commerce-react/orders:** devise+langue, statut sobre, messages client/admin, zone client liee, documents, fix upload
* **commerce-react:** selecteurs a drapeaux (pays comptes, langue contact) + redesign onglet info contact
* **commerce-react:** colonnes comptes alignees legacy + pagination 30 + largeur recherche
* **commerce-react:** config commerce alerte stock (design produit + cablage legacy, destinataires = comptes BO branches sur melis_core_user), refonte proprietes attribut (2 colonnes) + valeur booleenne en input 0/1, SearchableSelect mutualise
* **commerce-variants-react:** icone power (activer/desactiver un variant, title dynamique), refonte onglet Proprietes du variant (2 colonnes equilibrees + statut en entete), colonne Attribut(s) dans les 2 tableaux de l'onglet Association
* **commerce-products-react:** refonte onglet Proprietes (2 colonnes equilibrees + statut en entete), pays images (tous + drapeaux), combobox recherchable (attributs + destinataires), destinataires = comptes BO (tous, par email, retrait du champ email libre), pagination encart variants a hauteur verrouillee, description en editeur TinyMCE config 'tool'
* **commerce-react:** DnD ordre des produits d'une categorie (pcat_order via /catalog/:id/products/reorder) + pagination par 10 de l'encart variants (hauteur verrouillee, plus de fermeture au changement de page)
* **commerce-catalog-react:** tree facon melis-cms category (grip DnD, pastille statut, drop 3 zones), clic=edition + maitre-detail 2 colonnes, cases pays tri-etat (design droits melis-core)
* **react:** add a "Reset filters" button to the tool list page(s)
* **orders:** full-parity "New Order" checkout wizard + external-brick invoice button
* **commerce:** success notification on delete, tags chip widget, product tooltip hover fix, attribute value modal redesign
* Add toast
* Added new controllers, so it will be fully dependent
* **MelisAI:** add the frontmatter header block to MelisCommerce
* **MelisAI:** add MelisCommerce AI & developer guide
### Fixed
* **columns:** Hidden columns gone everywhere, mobile "+" folds the rest of Visible
* **dashboard-plugins:** silence AJAX failures (no alert/console) + prospects stats perf (0010871)
* **commerce-react:** touch-compatible drag for Columns popup + Export modal
* **security:** harden legacy file/dir creation & output escaping
* **commerce-react:** mobile toolbar buttons wrap 2-per-row (Mantis #10853)
* **commerce-react:** order messages mobile layout + checkout wizard validation messages
* **commerce-react:** contact/account association tables mobile + toolbar cleanup
* **commerce-react:** touch-compatible drag-and-drop, product text types, mobile image delete
* **commerce-react:** association de variants — picker par produit (comme le legacy)
* **commerce-orders:** quantite panier qui flickait lors de clics rapides sur +/-
* **commerce-products:** send all edited price rows so clearing every field persists
* **commerce-products:** prix non sauvegardes quand tous les champs sont vides + option Général au checkout
* **commerce-orders:** logger les echecs silencieux de sauvegarde de commande/panier
* **commerce-orders:** checkoutStart ne resume plus une session deja confirmee
* **commerce-orders:** panier quantite 0 fantome + lag optimiste + message erreur panier vide
* **commerce-react:** responsive mobile sur les listes, formulaires et le wizard commande
* **commerce-react:** n'afficher que les pays actifs (ctry_status=1) pour les affectations pays - edition catalogue + images/prix produit + images/prix/stocks variant
* **commerce-catalog-react:** liste des pays affichee en entier (retrait de l'ascenseur interne, la 2e colonne scrolle deja)
* Fixed order payment display
* Fixed applying quantity and coupons
* Fixed order step
* Fixed commerce order
* Fixed problem on making order (coupon, quantity)
* Fixed order message plugin error
* **commerce-react:** dashboard plugins open the Orders tool via the React host
* Fixed problem on making new order
* Fix plugin id used in orders numbers plugin
* Fixed problem on logging
* Fixed legacy design problem
* Fixed commerce settings problem
* Fixed text problem
* **rights:** align commerce toolstree node-keys to their melisKeys
* Fixed attribute
* Fixed legacy order
* Fixed tabibng problem
* Fixed tabbing issue and commerce settings
* Fixed catalog legacy errors
* Fixed design problem
* Fixed product and variant iamge problem
* Fixed problem validating and displays
* **catalog:** remove outer flex gap above the legacy iframe in Old view; fix category-countries SQL
* **contacts:** auto-default the first account association, matching legacy
* **checkout:** restore productNextButtonState on the Products basket zone
* Fixed legacy modal problem
* Fixed tabbing and spacing
* Fixed scroll problem and table designs
* **commerce:** namespace languages routes to /commerce-languages (route collision shadowed core BO Languages → empty list)
* Fixed product attributes and other design
* Fixed missing display from legacy
* Fixed designs an rendering
* **commerce:** leftmenu React en type-links purs (comme MelisCms)
* Fixed problem on tabbing
* Fixed contact filter alignment
* Fixed problem exporting accounts/contact
* Fixed special characters problem when exporting contact/account
### Changed
* Commerce legacy and react missing fields problem
* Legacy and react missing fields
* Update button designs and saving
* Update dates and fixed some design
* Update languages
* Update product tool cards
* Mgirate all tools
* Migrate other tools
* Migrate coupon tool
* Migrate orders tool
* Transfer react api controller to its own folder
* Partially migrate orders tool to react
* Migrate catalog and products tool
* Migrate to react
* Set utf function to public
* Changed isDefaultAccount func from private to public
### Dependencies & build
* **composer:** bump melis-core constraint to ^6.0
* **deps-dev:** bump postcss from 8.5.15 to 8.5.26 in /ui-react
* Local WIP snapshot before reconcile (20260806-114605)
* Rebuild brick.js after merge (reflect merged source)
### Docs
* **MelisAI:** re-wire the updated commerce screenshots (38 -> 50)
* **MelisAI:** substantially expand the MelisCommerce guide (both parts)
* **MelisAI:** wire 38 back-office screenshots into the commerce guide

## v5.3.5 - 2025-07-15
### Changed
* Type correction
* 101 updates

## v5.3.4 - 2025-03-10
### Added
* Added identifier if category or catalog
### Fixed
* Fix issue on orders table reference qtip pop over
* Fix mantis issue 8000
* Remove test text
* Redundant attribute values
* Fix issue on 7119 and 7118
* Variant
* Attributes display
* Product attributes error
* Fix not loading all product texts
* Fix issue on refresh after saving catalog or category
* Product texts
* Missing product reference on reference form
* Fixed issue on category and catalog sorting
* Product reference
* No default title on new product
* Fix release issue 7118
### Changed
* Updated jstree image icons on build/css folder
* Edit on hide modal
* Update: added default states on nodes
* Check catalog issue
* Checking issue on catalogs
* Update jstree theme used
* Updat: addeed updats on variants
### Dependencies & build
* Rebundle and remove logs

## v5.3.2 - 2024-09-25
### Added
* Add new product issue fixed

## v5.3.1 - 2024-09-25
### Dependencies & build
* Missing dependencies fixed

## v5.3.0 - 2024-09-25
### Fixed
* Fix issue 7017
* Fix issue 7030
* Fix issue 7009
* Fix issue on commerce client tool
* Fix qtip issue
* Fix issue 7019 and 7020
* Fix for 7017
* Fix issue 7010
* Fix issue on qtips js
* Fix issue 6641
* Fix issue on new order status
* Fix issue 6455
* Fix issue 6681
* Fix issue 6636
* Fix issue 6637 6639 6674
* Fix issue 6632
* Fix issue 6640
* Fix issue 6638
* Fix issue 6639
* Fix issue 6642
* Fix issues on melis-commerce status
* Fix issue 6561
* Fix issue 6489
* Fix issue 6502
* Fix issue 6466
* Fix issue 6458
* Fix issue 6454
* Fix issue 6450
* Fix issue 6446 select status
* Fix issue 6446
* Fix issue related to 6333
* Fix issue related to 6456
* Fix issue 6456
* Fix issue 6451
* Fix issue 6444
* Fix issue 6317
### Changed
* Updated html5 payment tab iframe
* Check fix issue 7030
* Jquery migration related
* Edit issue 6641 increased setTimeout
* Edit on issue 6641
* GetPagelink absolute false
* Page link update
* Change comments to asterisk: causing problem on bundling all
* Datatable draw issue fixed
* Client groups interface
* Interface roles translations
* RecordsFiltered and recordsTotal added to response
* Check issue 6502
* Related to 6455
* Notice and fixed the issue while fixing 6383
* Bs5 tab
* Update jQuery 3.7.1 migration
* JQuery 3.7.1 migration
* Update on jQuery migration
### Dependencies & build
* Rebundle remove logs
* Rebundle js
* Rebundle removed console logs

## v5.2.0 - 2024-06-06
### Dependencies & build
* Update melis platform version to 5.2

## v5.1.1 - 2024-04-08
### Added
* Add toolbar mode on tinymce option
### Fixed
* Fix issue 4906
* Fix issue 3568
* Fix issue for 6102
* Fixed problem on modal color in platform scheme
* Fix 6081 issue on product tinymce
### Changed
* Tinymce updates
* Change width on easy autocomplete js
* Tinymce type tool full toolbar buttons
* Checking products tinymce issue
* Checking issue on products with tinymce issue
* Edit on renamed tinymce toolbar item
* Update on tinymce.init with mini template
* Update on minitemplates
* Update on tinymce 6
* Update on tinymce 6.7.0
### Dependencies & build
* Rebundle js and css

## v5.1.0 - 2024-02-13
### Fixed
* Fix contact not saving on signup in site
* Fix bug duplicated discount
* Fix bccomp error
* Fixed datepicker cant read formatted date
* Fixed strftime deprecated problem
* Fixed problem creating dynamic properties
* Fixed problem inig on date and color picker
* Fixed problem deprecated utf8_encode function
### Changed
* Update melis packate to 5.1
* Update display of checkout summary basket
* Undefined couponId in order status tool
* Error on coupon tool when no client exists
* Order Status tool - add logs
* Laminas segment import
* Deprecated null on addslashes
* Deprecated null on strpos
* Deprecated strftime
* Deprecated null on mb_strtolower
* Change cache config
### Dependencies & build
* Update php version to 8.3

## v5.0.8 - 2024-02-13
### Added
* Add import logs to Account and Contact tools
* Add log for delete contact
* Added contact delete button to interface exclusion
* Added contact assoicated accounts in the export
* Added create contact button inside accounts contact lists
* Added profession field
* Added tag field in contact
* Added delete button on contact tool
### Fixed
* Fixed problem saving contact on empty account id
* Fixed problem assiging default account to contact
* Fixed problem associating contact accounts
* Fixed account association on account settings is set to company
### Changed
* Delete email also when deleting contact
* Make function public
* Reduce load time in contact tool
* Make some functions as public
* Remove contact when importing accounts
* Show contact account unlink button
* Update account/contact import - added option to override data
* Show account contact unlink button
* Set default account/contact on first association
* Update account export
* Update contact export
* Unlink contact accounts depending on commerce settings
* Remove contact as required when creating account
* Remove unneccessary column in export
* Sort contact account filter base on account commerce settings
* Some functions to public

## v5.0.7 - 2023-11-03
### Added
* Added type column in contact tool
* Added logs when saving accounts and contact
### Fixed
* Fixed problem contact filter with account
* Fixed problem reloading widgets
* Fixed problem on filter
### Changed
* Update contact login - checking associated accounts
* Update error message when saving contact
* Remove 0 in employee number account export
* Remove soring in account default contact

## v5.0.6 - 2023-10-26
### Added
* Added lock checkbox and warning when changing commerce settings
### Fixed
* Fixed problem soring default contact in account tool
* Fixed problem assigning contact depending on account commerce settings
* Fixed problem saving commerce settings
* Fixed problem showing contact lists in order
### Changed
* Check commerce settings for assigning associations

## v5.0.5 - 2023-10-24
### Fixed
* Fixed problem checking commerce settings when saving accounts
* Fixed problem creating new order
* Fixed problem showing default contact and add default selected on commerce settings

## v5.0.4 - 2023-10-24
### Added
* Added status filter in contact tool
* Added option to change contact selected account
* Added accounts tab in making order
* Added client tab when ordering
* Added tooltip to columns
* Added tooltip to account main forms
* Added button to remove company picture
* Added tooltip to table columns
* Added accounts/contacts association buttons to interface exclusion
* Added tooltip on association buttons
* Added datas on account export
* Added tooltip on table hover
* Added some buttons in the interface exclusion
* Added account import
* Added contacts import in contact tool
* Added contact tool
* Added new client export
### Fixed
* Fixed problem on duplicate calling of import
* Fixed import special char problem and checking mandatory fields
* Fixed problem importing accounts/contact
* Fixed problem on accounts/contact export/import
* Fixed problem counting total records
* Fixed pagination
* Fixed pagination problem
* Fixed problem counting accounts total records
* Fixed problem accounts interface exclusion
* Fixed error on going to next step in order
* Fixed problem on order sorting
* Fixed translation and filter width
* Fixed problem getting coupon data
* Fixed export html entities problem
* Fixed problem on saving company
* Fixed problem saving company
* Fixed saving company
* Fixed problem saving client company
* Fixed problem on double clicked and showing default contact/account
* Fixed problem updating other tool when changing on account/contact tool
* Fixed problem when unliking contact/account
* Fixed problem saving account and update translations
* Fixed problem saving company name and sort order problem
* Fixed problem on accordion toggle
* Fixed problem showing associations on both tool
* Fixed assigning assoication if user not selected and fixed some translations
* Fixed filter problem and assinging associations
* Fixed problem on contact search
* Fixed problem on empty account name
* Fixed problem importing/exporting account/contact
* Fixed contact/account association
* Fixed association problem and saving contact
### Changed
* Update contact/accounts import sample data
* Update accounts/contact import sample data
* Update translations
* Update column tooltip
* Update contact import sample data
* Update accounts sample import and add tooltip to table listing
* Check if imported file is csv
* Check encoding for contact import
* Check file encoding
* Check if country/group is active in the import
* Update import/export translations
* Update checkout address
* Rearange contact/accounts filters
* Rearange filters
* Update translation
* Update accounts/contact import/export and change import sample template
* Recount accounts total records
* Update accounts/contact interface display
* Update accounts/contact import
* Remove extra comma when inputting tags
* Update coupon translation
* Update account filter width
* Update rendering company empty values
* Reposition fields in export and update date formatting
* Group name table lenght
* Update company table and fixed sorting problem
* Update accounts/contact interface exclusion and type filter and limit table text
* Update contact/accounts association
* Update evo account tool account default contact
* Update order message
* Update account/contact tool
* Update client/account tool
* Update services to adopt to new account tool
* Update contacts import
* Update/added export on account and contact tool
* Update accounts depending on account settings
* Update commerce settings and contact address
* Update saving contact/address
* Update contact/account association
* Update client/contact tool association
* Update account tool(new evo)
* Update commerce client tool
* Update client export
* Update new client export

## v5.0.3 - 2023-05-24
### Added
* Added handling
* Added func and updated parameter in getBasketItemByFields
* Added getEntryByFields func
### Changed
* Renamed sql
* Updates table to utf8mb4
* Product basket deletion

## v5.0.2 - 2023-03-01
### Added
* Added default filter functionality
* Added new ecom company fields
### Fixed
* Fix activate/deactivate contact btn
### Dependencies & build
* Rebundled js

## v5.0.1 - 2022-10-26
### Added
* Added handling
* Added callback function when creating new order
* Added border to the order tab menu
* Added tooltip to order that will display the basket data, added new order button inside the order tab in the clients menu
### Fixed
* Fix ticket
* Fix tickets
### Changed
* Removed unused typeahead library
* Removed unused func
* Updated build
* Open also the Orders tab when opening the New order tab

## v5.0.0 - 2022-06-22
### Added
* Added setExtra func
* Added status filter on client lists
* Added client id on div id
### Fixed
* Fix for 3281
* Fixed order list slow loading
* Fixed slow loadding of client list
### Changed
* Updated accordion toggle in the add product text dialog
* Updated vsprint param
* Set checked/unchecked values to string
* Changed deprecated ArraySerializable to ArraySerializableHydrator and updated other functions affected by php 8
* Removed condition allowing to call listener if no price
### Dependencies & build
* Set melis package version to 5
* Updated pgp and laminas cache version
