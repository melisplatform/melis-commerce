---
title: MelisCommerce module
package: melisplatform/melis-commerce
doc_type: module-documentation
audience: [users, developers, ai]
language: en
module_version: unversioned
last_reviewed: 2026-06-10
maintainer: Melis Technology
keywords: [commerce, ecommerce, catalog, product, variant, attribute, category, price, stock, basket, cart, checkout, order, payment, shipping, coupon, currency, client, account, contact, b2b, melis]
screenshots_dir: ./images
---

# MelisCommerce — AI & developer guide

> **Module:** `melisplatform/melis-commerce` · **Namespace:** `MelisCommerce` · `melis-site: false`
> **What it is:** a **full e-commerce framework** for MelisPlatform — not a single tool but an entire commerce
> layer: catalog (products, variants, attributes, categories, prices, stock), customers (B2B accounts +
> contacts), the cart/checkout/order pipeline, coupons, currencies, shipping, returns, documents/invoices and
> SEO. It ships a **back-office management suite** and a **front-office shop built entirely from templating
> plugins**, over **59 `melis_ecom_*` tables**, **26 services**, **10 rich entities** and **33 listeners**. It
> even bundles its own ORM. No screenshots yet — **images expected later**.

---

## 0. Where it sits

MelisCommerce depends only on **`melisplatform/melis-core`** (`^5.2`) — but it is built to run *inside* a Melis
CMS site: its front office is a set of droppable plugins you place on CMS pages
([MelisCms](../../../melis-cms/etc/MelisAI/doc/MelisCms.md) / MelisFront), its tables install via DbDeploy
([MelisDbDeploy](../../../melis-dbdeploy/etc/MelisAI/doc/MelisDbDeploy.md)), and it is one of the optional
modules offered by the [installer](../../../melis-installer/etc/MelisAI/doc/MelisInstaller.md).

> **Architectural note:** it bundles **`illuminate/database` (Laravel Eloquent)** as its ORM plus
> `laminas/laminas-cache` and `laravel/serializable-closure`. So it is a **self-contained framework** that
> happens to live in Melis — its data access is Eloquent under the hood, wrapped by Melis-style services.

**The mental model:** *a **product** has one or more sellable **variants** (each with SKU, **stock** and
**price**); products live in a **category** tree and carry **attributes**; a visitor fills a **basket** that
becomes an **order** through the **checkout** pipeline; orders belong to **clients** (B2B accounts that contain
**contacts/persons**). Every read returns a **rich entity** (e.g. `MelisProduct`) assembled by a **service**
from many tables, and every service method is wrapped in `*_start`/`*_end` events you can hook.*

---

# PART A — Functional guide

## A0. The commerce object model, in plain words

Before the tools, the vocabulary — because MelisCommerce makes a few deliberate choices that surprise people
coming from simpler shops:

- A **catalog** is a top-level container; **categories** are the branches inside it. Together they form the
  navigable tree a shopper browses. A product can sit in several categories at once.
- A **product** is *not* the thing you sell — it is a **container for variants**. The sellable unit, the thing
  with a **SKU**, its own **stock** and its own **price**, is the **variant**. A T-shirt is a product; *“red /
  large”* is a variant. Even a product with no real options still has **one (main) variant** behind the scenes.
  This is why stock and price live on the variant, not the product.
- **Attributes** (Color, Size…) describe products and, through their **values** (Red, Large…), *define* the
  variants. A product *declares* which attributes it uses; each variant *picks one value per attribute*.
- A **price** is never a single number: it is resolved for a **(country, client-group)** pair, with VAT, and
  falls back gracefully (see §B4). The same variant can legitimately cost different amounts to a French retail
  visitor and a German B2B account.
- A customer is modelled for **B2B first**: an **account** (an organisation, with a company record, group and
  addresses) that **contains one or more contacts** (the actual people who log in). One person can belong to
  several accounts and switch between them. A pure B2C shop simply has one contact per account.
- A shopper fills a **basket** (anonymous until they log in, then persistent); checkout turns the basket into
  an **order** — first a *temporary* order, then a real one once payment is confirmed.

**The lifecycle of a sale, end to end:** *browse the catalog → open a product → pick a variant (attribute
values) → **add to basket** → log in or register (the anonymous basket merges into the account’s) → **checkout**
(addresses → coupon → summary) → an order is created at status `-1` → pay → payment confirmed → order moves to
`New order` → back office fulfils it (status → shipped → delivered), possibly handling **returns** and
**messages** along the way.* Every step above is a service call wrapped in events you can observe or override
(§B). The rest of Part A is the back-office side of that story.

## A1. The back-office suite — the Commerce menu

The whole commerce back office lives under one **MelisCommerce** left-menu section. Note the labels — a couple
differ from the internal class names: **Catalogs** is the category/catalog tree, and **Accounts** is the client
(B2B) tool.

![The MelisCommerce back-office menu](./images/meliscommerce-menu-tools-selector.png)

| Menu entry | Controller | Manages |
|---|---|---|
| **Catalogs** | `MelisComCategory(List)` | the **catalog / category tree** (a catalog is the root; categories nest under it), translations, per-country availability, SEO. |
| **Products** | `MelisComProduct(List)` | products → their **variants**, **prices**, **texts**, **SEO**, images, associated variants. |
| **Accounts** | `MelisComClient(List)` | B2B **accounts** (company, group, addresses, linked contacts, orders, files). |
| **Contacts** | `MelisComContact` | the **people** — managed independently and linked to accounts; CSV import/export. |
| **Orders** | `MelisComOrder(List)` + `MelisComOrderCheckout` | orders + a BO **“Create an order” tunnel**; statuses; returns. |
| **Coupons** | `MelisComCoupon(List)` | discount coupons (% or amount; assignable to accounts / products). |
| **Attributes** | `MelisComAttribute(List)` | attributes (Color, Size…) + typed, translatable **values**. |
| **Countries** / **Commerce languages** / **Currencies** / **Order status** / **Client’s groups** | `MelisComCountry` / `MelisComLanguage` / `MelisComCurrency` / `MelisComOrderStatus` / `MelisComClientsGroup` | the reference data (each a list + an edit modal). |
| **Commerce settings** | `MelisComSettings` | global stock-alert + the account-name strategy. |

It also adds three **dashboard widgets**: latest orders (`OrdersNumber`), order messages (`OrderMessages`) and
a sales-revenue chart (`SalesRevenue`).

## A2. Catalogs (the catalog / category tree)

The **Catalogs** tool manages a drag-and-drop **catalog → categories** tree (a *catalog* is the root container;
*categories* nest beneath it):

![Catalogs / Categories tree view](./images/meliscommerce-tools-catalogs-treeview.png)

Adding a catalog or category has three tabs — **Properties** (name + rich description per language, validity
dates, and the **countries** it’s available in), **SEO**, and **Products**:

![Catalog — Properties tab](./images/meliscommerce-tools-catalogs-edit-tab-properties.png)

## A3. Products (the catalog item editor)

The **Product list** (ID / Status / Image / Name / Categories), filterable by catalog, with **+ Add product**:

![Product list](./images/meliscommerce-tools-products-list.png)

A product has five tabs — **Properties / Text / Variants / SEO / Prices**:

- **Properties** — reference, categories, **attributes**, a **stock alert** threshold + email recipients, and
  up to three CMS **page associations**.

  ![Product → Properties](./images/meliscommerce-tools-products-edit-tab-properties.png)

- **Text** — the typed, per-language texts (e.g. *Title (TITLE)*; add more text types):

  ![Product → Text](./images/meliscommerce-tools-products-edit-tab-text.png)

- **Variants** — the sellable units (ID / Main / Image / Status / **SKU** / Attribute(s)); each product needs
  at least one. Drilling into a variant gives it its own stock, prices and attribute values.

  ![Product → Variants](./images/meliscommerce-tools-products-edit-tab-variants.png)

- **Prices** — a **Country × client-Group matrix**; per cell you set **Net price**, **Gross price**, **VAT
  percentage**, **VAT amount** and **Other tax amount** (this is what the price-resolution hierarchy in §B4
  reads):

  ![Product → Prices](./images/meliscommerce-tools-products-edit-tab-prices.png)

- **SEO** — the product’s URL + meta per language:

  ![Product → SEO](./images/meliscommerce-tools-products-edit-tab-seo.png)

## A4. Accounts & Contacts (the B2B model)

**Accounts** is the customer tool — *“List of all acquired customers”* — with KPI cards (total / this month /
active vs inactive) and columns Account / Default contact / Company / Orders:

![Account list](./images/meliscommerce-tools-accounts-list.png)

An account has six tabs — **Properties / Contacts / Company / Addresses / Orders / Files** — which *is* the B2B
model in §B5: an account (name, group, country, **main contact**) that **links contacts**, owns a **company**
record (VAT, registration…), addresses, and its order history.

![Account → Properties](./images/meliscommerce-tools-accounts-new-tab-properties.png)
![Account → Contacts (Link contact)](./images/meliscommerce-tools-accounts-new-tab-contacts.png)
![Account → Company](./images/meliscommerce-tools-accounts-new-tab-company.png)

**Contacts** is the *people* tool — individuals managed independently of accounts (filter by account/type/
status), linkable to one or many accounts:

![Contact list](./images/meliscommerce-tools-contacts-list.png)

A contact opens in a three-tab editor — **Information / Address / Association**. The **Association** tab is the
contact side of the B2B link (§B5): **Link account**, with **default-account** and **default-contact** stars
marking each side’s primary — exactly the `cpr_default_client` / `car_default_person` flags in the data model.

![Contact → Association (link to accounts)](./images/meliscommerce-tools-contacts-edit-tab-association.png)

## A5. Orders (incl. the back-office order tunnel)

The **Order list** (*“Create a new order from this tool”*) with KPI cards and columns Reference / Status /
Price / customer / Company:

![Order list](./images/meliscommerce-tools-order-list.png)

**Create an order** opens a **7-step tunnel** — **Contact → Account → Products → Addresses → Summary →
Payment → Confirmation** — the back-office mirror of the front-office checkout (the `MelisComOrderCheckout`
tool, site id `-1`):

![Create an order — the 7-step tunnel](./images/meliscommerce-tools-order-newordertunnelt.png)

Opening an existing order is a **seven-tab editor** — **Properties / Basket / Addresses / Payment / Shipping /
Messages / Returns** — one tab per sub-object of the `MelisOrder` entity (§B2):

- **Properties** — the **order status** as clickable buttons (*New order · On hold · Shipped · Delivered ·
  Cancelled*, the current one highlighted), the order reference, and **invoice / file attachments**.
- **Basket** — the ordered line items (Image / Product / SKU / QTY / Price / Categories).
- **Addresses / Payment / Shipping** — the billing+delivery addresses, the recorded transaction, and the
  shipping/tracking; **Messages** — the order conversation; **Returns** — RMA items.

![Order → Properties (status, invoice, attachments)](./images/meliscommerce-tools-order-edit-tab-properties.png)
![Order → Basket (line items)](./images/meliscommerce-tools-order-edit-tab-basket.png)

## A6. Coupons

**Coupon list** (*a coupon can be deleted only if it has never been used*) — Code / % / Price / Uses:

![Coupon list](./images/meliscommerce-tools-coupons-list.png)

A coupon’s **Properties**: a **Code**, validity dates, **Assign to accounts / Assign to products** toggles, and
the discount as a **Percentage** *or* an **Amount**, with a **Max use limit**:

![Coupon → Properties](./images/meliscommerce-tools-coupons-edit-tab-properties.png)

Its **Orders** tab lists the orders the coupon has been used on (which is why a used coupon can’t be deleted):

![Coupon → Orders](./images/meliscommerce-tools-coupons-edit-tab-orders.png)

## A7. Attributes

**Attributes** define filterable/variant-defining properties, in three tabs — **Properties** (reference,
**Type**, **Visible**, **Searchable**), **Labels** (the attribute’s name per language) and **Values** (the
list of possible values, e.g. shoe sizes 38–47, that variants then pick from):

![Attribute list](./images/meliscommerce-tools-attributes-list.png)
![Attribute → Properties](./images/meliscommerce-tools-attributes-edit-tab-properties.png)
![Attribute → Labels](./images/meliscommerce-tools-attributes-edit-tab-labels.png)
![Attribute → Values](./images/meliscommerce-tools-attributes-edit-tab-values.png)

## A8. Settings & reference data

**Commerce settings** has two tabs: **Properties** (a global stock-alert threshold + recipients) and
**Accounts** — where the **account-name strategy** is chosen: **Manual input / Company name / Contact name**
(the `sa_type` of §B5), behind a *“Check to edit”* guard warning that changes have *irreversible critical
consequences*:

![Settings → Properties](./images/meliscommerce-tools-settings-tab-properties.png)
![Settings → Accounts (account-name strategy)](./images/meliscommerce-tools-settings-tab-accounts.png)

The **reference-data** tools are simple list + edit-modal CRUD: **Countries**, **Commerce languages**,
**Currencies**, **Order status**, **Client’s groups**.

![Countries](./images/meliscommerce-tools-countries-list.png)
![Country edit modal](./images/meliscommerce-tools-countries-edit-modal.png)
![Commerce languages](./images/meliscommerce-tools-commercelanguages-list.png)
![Commerce language edit modal](./images/meliscommerce-tools-commercelanguages-edit-modal.png)
![Currencies](./images/meliscommerce-tools-currencies-list.png)
![Currency edit modal](./images/meliscommerce-tools-currencies-edit-modal.png)
![Order status list](./images/meliscommerce-tools-orderstatus-list.png)
![Order status edit modal](./images/meliscommerce-tools-orderstatus-edit-modal.png)
![Client’s groups](./images/meliscommerce-tools-clientsgroups-list.png)
![Client’s group edit modal](./images/meliscommerce-tools-clientsgroups-edit-modal.png)

> The remaining sub-tabs (catalog SEO/Products; account Addresses/Orders/Files; contact Information/Address; and
> the order Addresses/Payment/Shipping/Messages/Returns tabs) are catalogued in the Screenshot index:
> ![Catalog → SEO](./images/meliscommerce-tools-catalogs-edit-tab-seo.png)
> ![Catalog → Products](./images/meliscommerce-tools-catalogs-edit-tab-products.png)
> ![Account → Addresses](./images/meliscommerce-tools-accounts-new-tab-adresses.png)
> ![Account → Orders](./images/meliscommerce-tools-accounts-new-tab-orders.png)
> ![Account → Files](./images/meliscommerce-tools-accounts-new-tab-files.png)
> ![Contact → Information](./images/meliscommerce-tools-contacts-edit-tab-information.png)
> ![Contact → Address](./images/meliscommerce-tools-contacts-edit-tab-address.png)
> ![Order → Addresses](./images/meliscommerce-tools-order-edit-tab-addresses.png)
> ![Order → Payment](./images/meliscommerce-tools-order-edit-tab-payment.png)
> ![Order → Shipping](./images/meliscommerce-tools-order-edit-tab-shipping.png)
> ![Order → Messages](./images/meliscommerce-tools-order-edit-tab-messasges.png)
> ![Order → Returns](./images/meliscommerce-tools-order-edit-tab-returns.png)

## A9. The front office (a shop made of plugins)

The storefront is assembled from droppable **templating plugins** (`config/plugins/{products,categories,
clients,orders}`):

- **Catalog:** `ProductShowPlugin`, `ProductListPlugin`, `ProductSearchPlugin`, `CategoryTreePlugin`,
  `CategoryProductListPlugin`, `RelatedProductsPlugin`, `AttributesShowPlugin`, `ProductAttributePlugin`
  (filter), `ProductPriceRangePlugin` (filter).
- **Cart & checkout:** `AddToCartPlugin`, `CartPlugin`, and the checkout chain `CheckoutPlugin` →
  `CheckoutCartPlugin` → `CheckoutAddressesPlugin` → `CheckoutCouponPlugin` → `CheckoutSummaryPlugin` →
  `CheckoutConfirmSummaryPlugin` → `CheckoutConfirmPlugin`.
- **Account:** `LoginPlugin`, `RegisterPlugin`, `AccountPlugin` (dashboard), `ProfilePlugin`,
  `BillingAddressPlugin`, `DeliveryAddressPlugin`, `LostPasswordGetEmailPlugin`, `LostPasswordResetPlugin`.
- **Orders (customer):** `OrderPlugin`, `OrderHistoryPlugin`, `OrderMessagesPlugin`,
  `OrderShippingDetailsPlugin`, `OrderReturnProductPlugin`, `OrderAddressPlugin`.

## A10. How do I…? (functional recipes)

- **…stand up a shop from scratch?** Work the catalog top-down: **Commerce settings** (currencies, countries,
  the account-name strategy) → **Attributes** (define Color/Size and their values) → **Catalogs** (build the
  category tree) → **Products** (create a product, declare its attributes, add **variants** for each combination,
  set **stock** and a **price** per country/group, write **texts** and **SEO**). Then build the storefront by
  dropping the front-office plugins on CMS pages (§A9).
- **…model a product with options (e.g. size & colour)?** Create the product, add *Color* and *Size* attributes
  on its **Properties** tab, then add one **variant per combination** under the **Variants** tab — each variant
  carries its own SKU, stock and price. Mark one as the **main** variant (used when the shop shows the product
  without a selection).
- **…charge B2B customers different prices?** Put the account in a **Client’s group**, then on the product’s
  **Prices** tab fill the row for that **(country, group)**; retail visitors fall back to the *General* group
  (§B4 explains the fallback order).
- **…discount with a coupon?** Create a **Coupon** (code, validity, a **%** *or* a fixed **amount**, a max-use
  limit), optionally restrict it to specific **accounts** or **products**. It’s applied during checkout and can
  only be deleted while unused.
- **…take an order on a customer’s behalf?** Use **Orders → Create an order**: the 7-step back-office tunnel
  (Contact → Account → Products → Addresses → Summary → Payment → Confirmation) — the same engine as the
  storefront checkout.
- **…get notified when stock runs low?** Set a **Stock alert** threshold and **recipients** on the product (or
  globally in **Commerce settings**); a low-stock email (`VARIANTSLOWSTOCK`) is sent after orders drop the level.
- **…handle a return?** Open the order → the **product-returns** flow records returned items and a message
  timeline.
- **…read a product / price / order in code, or hook into the shop’s logic?** That’s Part B — every read returns
  a rich entity and every service method is an event you can observe or override (§B1, §B10).

---

# PART B — Technical reference

## B1. The architecture — Table gateway → Service → Entity (with event wrapping)

Three layers, one convention:

1. **Table gateways** (`src/Model/Tables/`, 59 classes) wrap the `melis_ecom_*` tables (Eloquent under the
   hood). Obtained from the service manager, e.g. `$sm->get('MelisEcomProductTable')`.
2. **Services** (`src/Service/`, 26 classes, all extending **`MelisComGeneralService`** → MelisCore’s
   `MelisGeneralService`) load rows via the gateways and **assemble a rich entity**. Every public method is
   wrapped in events using the inherited convention:

   ```php
   public function getProductById($productId, $langId, …) {
       $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());     // reflect args → named array
       $arrayParameters = $this->sendEvent('meliscommerce_service_product_byid_start', $arrayParameters);
       // …build the entity from table gateways…
       $entProd = new MelisProduct();  /* …populate… */
       $arrayParameters['results'] = $entProd;
       $arrayParameters = $this->sendEvent('meliscommerce_service_product_byid_end', $arrayParameters);
       return $arrayParameters['results'];     // listeners may have mutated args OR results
   }
   ```

   So **every method has a `meliscommerce_service_*_start` and `_end` hook** — the basis of the whole extension
   model (a listener can change inputs before, or the result after). `MelisComGeneralService` adds helpers:
   `getTableColumns($tableName)`, `getEcomLang($locale)`, `getFrontPluginLangId()`, `getFrontPluginLangLocale()`.
3. **Entities** (`src/Entity/`, 10 classes, `#[AllowDynamicProperties]`) are plain rich objects instantiated
   directly (`new MelisProduct()`) and filled with the raw row **plus** its sub-objects.

**Access pattern:** there is **no central “head” façade** — you `get()` each service by its alias. (The one
`MelisComHead` alias is a small SEO helper — `updateTitleAndDescription()` — not a service locator.) **Caching**
is centralised in `MelisComCacheService` (cache `commerce_big_services`, key prefixes `product-/category-/
variant-/document-/attribute-`); listeners call `deleteCache()` on saves to invalidate the right keys.

**Why the event wrapping matters (the whole extension story).** Because *every* service method emits a
`_start` and an `_end` event carrying the **named arguments** and the **`results`**, you can change almost any
behaviour without touching the module: a `_start` listener mutates the inputs before the work runs; an `_end`
listener mutates the returned entity/array before the caller sees it. The arguments arrive keyed by their
parameter name (that is what `makeArrayFromParameters` builds via reflection), and the return value is
`$params['results']`. This is exactly how the built-in coupon discount works — it does **not** live inside the
price service; it is a listener on the price service’s `_end` event:

```php
// MelisCommerceCouponProductPriceListener — applies a coupon to a just-computed price
$this->attachEventListener(
    $events, '*', 'meliscommerce_service_get_item_price_end',
    function ($e) {
        $params = $e->getParams();
        $price  = $params['results'];   // the array MelisComPriceService::getItemPrice() is about to return
        $data   = $params['data'];      // an input argument, by name
        if (empty($params['data']) || empty($price['price'])) return;
        // …reduce $price['price'] by the coupon, then write it back…
        $params['results'] = $price;    // the caller receives the discounted price
        return $params;
    }
);
```

So to **extend MelisCommerce you almost never subclass** — you attach a listener to the right
`meliscommerce_service_*_{start,end}` event. Use `_start` to validate/normalise inputs (the `…Save*Listener`
family does this), and `_end` to enrich or rewrite results (pricing, SEO, stock). See §B10 for the read/write
recipes and §B7 for the full catalogue of listeners.

## B2. The entity + service façade (the public API)

The **10 entities** and the services that return them:

| Entity | Holds (sub-objects) | Returned by |
|---|---|---|
| `MelisProduct` | product row + categories, attributes, texts, prices, documents, page associations | `MelisComProductService::getProductById/getProductList` |
| `MelisVariant` | variant row + attribute values, stocks, prices, documents | `MelisComVariantService::getVariantById/getVariantListByProductId/getVariantBySKU/getMainVariantByProductId` |
| `MelisCategory` | category row + translations, SEO, countries, children | `MelisComCategoryService::getCategoryById/getCategoryListById(Recursive)` |
| `MelisAttribute` | attribute row (+translations) + values | `MelisComAttributeService::getAttributeById/getAttributes` |
| `MelisClient` | account row + persons, addresses, company | `MelisComClientService::getClientById/getClientByIdAndClientPerson/getClientList` |
| `MelisClientPerson` | person row + addresses, emails | `MelisComClientService::getClientPersonById` / `MelisComContactService` |
| `MelisBasket` | basket line + the `MelisVariant`, quantity | `MelisComBasketService::getBasket/getPersistentBasket/getAnonymousBasket` |
| `MelisOrder` | order row + client, person, addresses, basket, payment, shipping, messages, documents | `MelisComOrderService::getOrderById/getOrderList` |
| `MelisCoupon` | coupon row | `MelisComCouponService::getCouponById/getCouponList` |
| `MelisDocument` | document + type/subtype | `MelisComDocumentService::getDocumentById/getDocumentsByRelation` |

The **26 services** (aliases verbatim from `config/module.config.php`): `MelisComProductService`,
`MelisComVariantService`, `MelisComCategoryService`, `MelisComAttributeService`, `MelisComPriceService`,
`MelisComProductSearchService`, `MelisComSeoService`, `MelisComLinksService`, `MelisComDuplicationService`,
`MelisComStockEmailAlertService`, `MelisComClientService`, `MelisComContactService`,
`MelisComClientGroupsService`, `MelisComAuthenticationService`, `MelisComBasketService`,
`MelisComOrderService`, `MelisComOrderCheckoutService`, `MelisComPostPaymentService`,
`MelisComOrderProductReturnService`, `MelisComCouponService`, `MelisComCurrencyService`,
`MelisComShipmentCostService`, `MelisComDocumentService`, `MelisComCacheService`, `MelisComHead`
(`MelisComHeadService`), `MelisComGeneralService`.

## B3. Data model — the 59 `melis_ecom_*` tables (verified verbatim)

Grouped by subsystem (PK prefix in parentheses):

**Catalog — products & variants:** `melis_ecom_product` (`prd_`) · `melis_ecom_product_text` (`ptxt_`) +
`melis_ecom_product_text_type` (`ptt_`) · `melis_ecom_product_attribute` (`patt_`) ·
`melis_ecom_product_category` (`pcat_`) · `melis_ecom_product_links` · `melis_ecom_variant` (`var_`) ·
`melis_ecom_variant_attribute_value` (`vatv_`) · `melis_ecom_variant_stock` (`stock_`) ·
`melis_ecom_assoc_variant` (`avar_`) + `melis_ecom_assoc_variants_type` (`avart_`).
*Relationship:* product **1→N** variant; variant **1→N** stock (per country) and price; product **N↔N** category.

**Attributes:** `melis_ecom_attribute` (`attr_`) + `_trans` · `melis_ecom_attribute_type` (`atype_`, defines the
storage column) · `melis_ecom_attribute_value` (`atval_`) + `_value_trans` (typed value per language). Products
*declare* attributes (`product_attribute`); variants *pick values* (`variant_attribute_value`).

**Categories & geo/lang:** `melis_ecom_category` (`cat_`, self-referencing `cat_father_cat_id`, -1 = root) +
`_trans` · `melis_ecom_country_category` (`ccat_`) · `melis_ecom_country` · `melis_ecom_lang` (`elang_`).

**Pricing & currency:** `melis_ecom_price` (`price_`, FK to *either* `price_prd_id` or `price_var_id`, plus
country/currency/group) · `melis_ecom_currency` (`cur_`).

**Clients (B2B):** `melis_ecom_client` (`cli_`) · `melis_ecom_client_person` (`cper_`) +
`_person_emails` (`cpmail_`) · `melis_ecom_client_company` (`ccomp_`) · the two link tables
`melis_ecom_client_account_rel` (`car_`) and `melis_ecom_client_person_rel` (`cpr_`) · `melis_ecom_client_address`
(`cadd_`) + `_address_type` (+`_trans`) · `melis_ecom_client_groups` (`cgroup_`) · `melis_ecom_civility`
(`civ_`) + `_trans` · `melis_ecom_settings_account` (`sa_`).

**Baskets & orders:** `melis_ecom_basket_anonymous` (`bano_`) · `melis_ecom_basket_persistent` (`bper_`) ·
`melis_ecom_order` (`ord_`) · `_order_basket` (`obas_`) · `_order_address` (`oadd_`) · `_order_payment`
(`opay_`) + `_payment_type` (`opty_`) · `_order_shipping` (`oship_`) · `_order_message` (`omsg_`) ·
`_order_status` (`osta_`) + `_status_trans` · `_order_product_return` (`pret_`) + `_details` (`pretd_`).

**Coupons:** `melis_ecom_coupon` (`coup_`) + `_coupon_client` (`ccli_`) / `_coupon_order` (`cord_`) /
`_coupon_product` (`cprod_`).

**Documents & SEO:** `melis_ecom_document` (`doc_`) + `_doc_type` (`dtype_`) + `_doc_relations` (`rdoc_`, links a
doc to a product/variant/category/order/country) · `melis_ecom_seo` (`eseo_`, the catalog URL + meta per
language) · `melis_ecom_stock_email_alert` (`sea_`).

## B4. Pricing & stock resolution (worth knowing)

`MelisComPriceService::getItemPrice($itemId, $countryId, $groupId, $type)` resolves a price by a **fallback
hierarchy**: specific-country+specific-group → specific-country+general-group → general-country+specific-group →
general-country+general-group → (for a variant) fall back to the **product** price. `price_country_id = 0`
means “general”. Stock is per **variant per country** (`melis_ecom_variant_stock`); low stock triggers the
`VARIANTSLOWSTOCK` email and the stock-email-alert recipients.

## B5. The customer model — account vs contact (the B2B core)

This is the part people get wrong, so be precise:

- A **client** (`melis_ecom_client`, `cli_`) is a **B2B account** (an organisation): name, status, group,
  country, plus a 1:1 **company** record (`ccomp_*`: VAT, registration…).
- A **client person** (`melis_ecom_client_person`, `cper_`) is an **individual/contact** — managed
  independently (the *Contact* tool), with login email + password, civility, job, phones, and extra emails.
- They are linked **many-to-many** through **two mirrored tables** kept in sync: `client_person_rel` (`cpr_`,
  contact’s view, `cpr_default_client` = the contact’s default account) and `client_account_rel` (`car_`,
  account’s view, `car_default_person` = the account’s default contact). `linkAccountContact()` writes both.
- So **one person can belong to several accounts** and switch between them. Addresses live at either level
  (`cadd_client_id` for account-level, `cadd_client_person` for person-level), typed billing/delivery.

**Front-office auth:** `MelisComAuthenticationService::login($email,$password,$rememberMe)` finds the person by
email, verifies the password, loads their associated accounts, and stores the session identity (person id +
selected account id + group + a `clientKey` = session id). `getClientId()/getPersonId()/setClientId()` (switch
account), `logout()`, `hasIdentity()`. Lost-password uses `cper_password_recovery_key`.

## B6. The cart → checkout → order pipeline (the most important flow)

**Front-office plugin chain:** `AddToCartPlugin` (validates stock, `MelisComBasketService::addVariantToBasket`)
→ `CartPlugin` → the **`CheckoutPlugin`** orchestrating: cart → **addresses** → **coupon** → **summary** →
**confirm-summary** (triggers order creation) → **confirm**. Anonymous carts live in `basket_anonymous`
(keyed by `clientKey`); on login they’re merged into `basket_persistent`
(`transferAnonymousBasketToPersistentBasket`).

**Backend — two phases in `MelisComOrderCheckoutService`:**

1. **`checkoutStep1_prePayment($clientId)`** — `validateBasket()` → `validateAddresses()` →
   `computeAllCosts()` → `computeShipmentCost()` → `generateOrderReferenceCode()` → **`MelisComOrderService::
   saveOrder(...)`** (which saves order + basket lines + addresses + payment + shipping). The order is created
   with **`ord_status = -1` (temporary)**. Fires `meliscommerce_service_checkout_step1_prepayment_start/_end`
   and `…_save_success` (with the new `orderId`), plus the per-part `meliscommerce_service_order_*_save_start/
   _end` events.
2. **`checkoutStep2_postPayment()`** — after the payment gateway returns, `MelisComPostPaymentService::
   processPostPayment()` records the transaction in `melis_ecom_order_payment` and the order **transitions from
   -1 to a real status** (e.g. 1 = *New order*). Fires
   `meliscommerce_service_checkout_step2_postpayment_start/_end`.

**Order statuses** (`melis_ecom_order_status`): `-1` temporary · `1` New order · `2` On hold · `3` Shipped ·
`4` Delivered · `5` Cancelled · `6` Payment error. **Payment types** (`opty_code`): VISA / MASTERCARD / AMEX /
PAYPAL / TRANSFERT / CB. To plug in a real **payment gateway**, drive `checkoutStep2_postPayment` with the
gateway’s response values and/or listen on the post-payment events.

## B7. Events & listeners (33) — the extension surface

Listeners are attached in `Module::onBootstrap()`; **back-office-only** ones attach only on `melis-backoffice`
routes, the rest always. By purpose:

- **Save hooks** (validate/transform on save): `…SaveProductListener`, `…SaveOrderListener`,
  `…SaveClientListener`, `…SaveAttributeListener`, `…CategoryListener`, `…ValidateVariantListener`,
  `…PrdVarDuplicationListener` — on `meliscommerce_*_save_start` / duplication events.
- **Cascade cleanups** when a country/language is removed: `…ProductPriceCountryDeletedListener`,
  `…ProductStockCountryDeletedListener`, `…CategoryCountryLink…`, `…DocumentCountryDeletedListener` (on
  `meliscommerce_country_deleted`); `…AttributeLanguageDeletedListener`, `…CategoryLanguageDeletedListener`,
  `…SEOLanguageDeletedListener`, `…ProductTextLanguage{Add,Delete}Listener` (on `meliscommerce_language_*`).
- **Checkout/pricing/stock:** `…CheckoutCouponListener` + `…PostCheckoutCouponListener` +
  `…CouponProductPriceListener` (apply coupon discount — hooks `meliscommerce_service_get_item_price_end`),
  `…ShipmentCostListener`, `…ComputeOrderCostListener`, `…PostPaymentListener`,
  `…VariantCheckLowStockListener` (after payment), `…VariantRestockListener` (reset the stock-email flag).
- **Front-office SEO routing:** `…SEOReformatToRoutePageUrlListener`, `…SEODispatchRouterCommerceUrlListener`,
  `…SEOMetaPageListener` (pretty product/category URLs + meta tags on the FO page).
- **UI:** `…FlashMessengerListener`, `…DataTableTranslationsListener`, `…ProductPriceLogsTranslationListener`.

**To extend:** listen on the relevant `meliscommerce_service_*_start` (mutate inputs) or `_end` (mutate the
returned entity) — every service method gives you both.

## B8. Front-office plugins & config

FO plugins are registered under `config/plugins/{products,categories,clients,orders}/` (each a config defining
the `front` params + the `melis` BO drop-in metadata) and as Laminas `controller_plugins` in
`config/module.config.php`. They follow the standard Melis plugin pattern — drop on a page, configure params
(prefixed `m_…`), the plugin renders a view from a service entity.

## B9. Config, deps & code map

- **Composer:** `php ^8.1|^8.3`, **`melisplatform/melis-core ^5.2`**, **`illuminate/database ^11.7`**
  (Eloquent), `laminas/laminas-cache ^3.12`, `laravel/serializable-closure ^1.3.5`.
- **Config:** `config/module.config.php` (services/tables/controllers/controller_plugins),
  `config/interface/*` (the BO menu + tools), `config/plugins/*` (FO plugins), `config/app.emails.php`
  (`VARIANTSLOWSTOCK`), `config/diagnostic.config.php`.

```
src/
  Entity/        ← 10 rich entities (MelisProduct/Variant/Category/Attribute/Client/ClientPerson/Basket/Order/Coupon/Document)
  Service/       ← 26 services (extend MelisComGeneralService; event-wrapped) — §B2
  Model/Tables/  ← 59 melis_ecom_* table gateways (Eloquent) — §B3
  Controller/    ← 35 BO tools (MelisCom*) + Plugin/ (30 FO plugins) + DashboardPlugins/
  Listener/      ← 33 listeners — §B7
config/
  module.config.php · interface/* (BO menu) · plugins/* (FO) · app.emails.php · diagnostic.config.php
```

## B10. Developer recipes (copy-paste, verbatim signatures)

All services are reached from the service manager by alias; every call returns an entity (or array) and is
event-wrapped. Signatures below are verbatim from the source.

**Read a product, its main variant and a resolved price**

```php
$prdSrv   = $sm->get('MelisComProductService');
$varSrv   = $sm->get('MelisComVariantService');
$priceSrv = $sm->get('MelisComPriceService');

// getProductById($productId, $langId = null, $countryId = null, $groupId = -1, $docType = null, $docSubType = array())
$product  = $prdSrv->getProductById($prdId, $langId, $countryId);          // → MelisProduct entity
$texts    = $product->getTexts();        // typed texts (TITLE, …) per language
$cats     = $product->getCategories();   // the categories it belongs to

// getMainVariantByProductId($productId, $langId = null, $countryId = null, $groupId = 1)
$variant  = $varSrv->getMainVariantByProductId($prdId, $langId, $countryId);   // → MelisVariant (sellable unit)

// getItemPrice($itemId, $countryId, $groupId, $type = 'variant', Array $data = [])
$price    = $priceSrv->getItemPrice($variant->getId(), $countryId, $groupId, 'variant');
// $price['price'] (net), $price['price_currency'], $price['price_details']… (see §B4)
```

`getVariantBySKU($variantSKU, $langId = null)` looks a variant up directly by SKU;
`getProductList($langId, $categoryIds = [], $countryId, $onlyValid, $start, $limit, …)` returns a page of
`MelisProduct[]` for a category listing.

**Add to basket, then run the two-phase checkout**

```php
$basketSrv   = $sm->get('MelisComBasketService');
$checkoutSrv = $sm->get('MelisComOrderCheckoutService');
$authSrv     = $sm->get('MelisComAuthenticationService');

// addVariantToBasket($variantId, $quantity, $clientId, $clientKey = null)
$basketSrv->addVariantToBasket($variantId, 1, $clientId, $clientKey);   // anonymous uses $clientKey only

// when a guest logs in, merge their anonymous cart into the account's persistent one
$basketSrv->transferAnonymousBasketToPersistentBasket($clientKey, $clientId);

// Phase 1 — create the order (status -1, "temporary"): validates basket+addresses, computes costs+shipping
$step1 = $checkoutSrv->checkoutStep1_prePayment($clientId);   // ['success'=>bool, 'orderId'=>int, …]

// …hand the customer to the payment gateway with $step1['orderId']…

// Phase 2 — after the gateway returns: record the transaction and move the order off status -1
$step2 = $checkoutSrv->checkoutStep2_postPayment();           // reads the posted payment values
```

The events `meliscommerce_service_checkout_step1_prepayment_save_success` (carries the new `orderId`) and
`meliscommerce_service_checkout_step2_postpayment_end` are the natural places to plug **emails, ERP sync or a
real payment gateway** (the built-in `…PostPaymentListener` and `…VariantCheckLowStockListener` already hook
the latter).

**Authenticate a front-office customer**

```php
$auth = $sm->get('MelisComAuthenticationService');
$res  = $auth->login($email, $password, $rememberMe);   // login($email, $password, $rememberMe = false)
if ($res['success']) {
    $personId = $auth->getPersonId();     // the logged-in contact (cper_id)
    $clientId = $auth->getClientId();     // the currently-selected account (cli_id)
    $group    = $auth->getClientGroup();  // that account's pricing group → feeds getItemPrice()
    // $auth->setClientId($otherAccountId)  // switch account for a multi-account contact
}
```

**Read orders for the customer dashboard**

```php
$orderSrv = $sm->get('MelisComOrderService');
// getOrderList($orderStatusId, $onlyValid, $langId, $clientId, $clientPersonId, …)
$orders   = $orderSrv->getOrderList(null, null, $langId, $clientId);     // → MelisOrder[]
$order    = $orderSrv->getOrderById($orderId, $langId);                  // one fully-assembled MelisOrder
$basket   = $order->getBasket();   // line items;  $order->getAddresses(), getPayment(), getShipping(), getMessages()
```

**Hook the shop without subclassing** — attach a listener to any `meliscommerce_service_*_{start,end}` event
(see the verbatim coupon example in §B1): `_start` to alter inputs, `_end` to alter `$params['results']`.

---

## Screenshot index

| File | Shows |
|---|---|
| `images/meliscommerce-menu-tools-selector.png` | The **MelisCommerce** back-office menu (Catalogs/Products/Accounts/Contacts/Orders/Coupons/Attributes/Countries/Commerce languages/Currencies/Order status/Client’s groups/Commerce settings). |
| `images/meliscommerce-tools-catalogs-treeview.png` | **Catalogs** — the catalog/category drag-and-drop tree. |
| `images/meliscommerce-tools-catalogs-edit-tab-properties.png` | Catalog → **Properties** (name/description per lang, validity dates, countries). |
| `images/meliscommerce-tools-catalogs-edit-tab-seo.png` | Catalog → **SEO**. |
| `images/meliscommerce-tools-catalogs-edit-tab-products.png` | Catalog → **Products** (items in the catalog). |
| `images/meliscommerce-tools-products-list.png` | **Product list** (filter by catalog, add product). |
| `images/meliscommerce-tools-products-edit-tab-properties.png` | Product → **Properties** (reference, categories, attributes, stock alert, page association). |
| `images/meliscommerce-tools-products-edit-tab-text.png` | Product → **Text** (typed texts per language). |
| `images/meliscommerce-tools-products-edit-tab-variants.png` | Product → **Variants** (SKU/main/attributes list). |
| `images/meliscommerce-tools-products-edit-tab-prices.png` | Product → **Prices** (Country × Group matrix: net/gross/VAT). |
| `images/meliscommerce-tools-products-edit-tab-seo.png` | Product → **SEO** (URL + meta). |
| `images/meliscommerce-tools-accounts-list.png` | **Account list** (KPIs + Account/Default contact/Company/Orders). |
| `images/meliscommerce-tools-accounts-new-tab-properties.png` | Account → **Properties** (name/group/country/tags/main contact). |
| `images/meliscommerce-tools-accounts-new-tab-contacts.png` | Account → **Contacts** (Link contact). |
| `images/meliscommerce-tools-accounts-new-tab-company.png` | Account → **Company** (VAT/registration/address). |
| `images/meliscommerce-tools-accounts-new-tab-adresses.png` | Account → **Addresses**. |
| `images/meliscommerce-tools-accounts-new-tab-orders.png` | Account → **Orders** (the account’s order history). |
| `images/meliscommerce-tools-accounts-new-tab-files.png` | Account → **Files** (attached documents). |
| `images/meliscommerce-tools-contacts-list.png` | **Contacts** list (people, filterable by account/type/status). |
| `images/meliscommerce-tools-contacts-edit-tab-information.png` | Contact → **Information** (identity, email, job, phones). |
| `images/meliscommerce-tools-contacts-edit-tab-address.png` | Contact → **Address**. |
| `images/meliscommerce-tools-contacts-edit-tab-association.png` | Contact → **Association** (link to accounts; default-account/contact stars). |
| `images/meliscommerce-tools-order-list.png` | **Order list** (KPIs + Reference/Status/Price/customer). |
| `images/meliscommerce-tools-order-newordertunnelt.png` | **Create an order** — the 7-step BO tunnel (Contact→…→Confirmation). |
| `images/meliscommerce-tools-order-edit-tab-properties.png` | Order → **Properties** (status buttons, reference, invoice/file attachments). |
| `images/meliscommerce-tools-order-edit-tab-basket.png` | Order → **Basket** (line items: Image/Product/SKU/QTY/Price/Categories). |
| `images/meliscommerce-tools-order-edit-tab-addresses.png` | Order → **Addresses** (billing/delivery). |
| `images/meliscommerce-tools-order-edit-tab-payment.png` | Order → **Payment** (recorded transaction). |
| `images/meliscommerce-tools-order-edit-tab-shipping.png` | Order → **Shipping** (tracking/sent). |
| `images/meliscommerce-tools-order-edit-tab-messasges.png` | Order → **Messages** (order conversation). |
| `images/meliscommerce-tools-order-edit-tab-returns.png` | Order → **Returns** (RMA items). |
| `images/meliscommerce-tools-coupons-list.png` | **Coupon list**. |
| `images/meliscommerce-tools-coupons-edit-tab-properties.png` | Coupon → **Properties** (code, dates, assign to accounts/products, %/amount, max uses). |
| `images/meliscommerce-tools-coupons-edit-tab-orders.png` | Coupon → **Orders** (orders the coupon was used on). |
| `images/meliscommerce-tools-attributes-list.png` | **Attribute list**. |
| `images/meliscommerce-tools-attributes-edit-tab-properties.png` | Attribute → **Properties** (reference/type/visible/searchable). |
| `images/meliscommerce-tools-attributes-edit-tab-labels.png` | Attribute → **Labels** (name per language). |
| `images/meliscommerce-tools-attributes-edit-tab-values.png` | Attribute → **Values** (the possible values variants pick from). |
| `images/meliscommerce-tools-settings-tab-properties.png` | **Commerce settings** → Properties (global stock alert + recipients). |
| `images/meliscommerce-tools-settings-tab-accounts.png` | **Commerce settings** → Accounts (account-name strategy = `sa_type`). |
| `images/meliscommerce-tools-countries-list.png` | **Countries** list. |
| `images/meliscommerce-tools-countries-edit-modal.png` | Country edit modal. |
| `images/meliscommerce-tools-commercelanguages-list.png` | **Commerce languages** list. |
| `images/meliscommerce-tools-commercelanguages-edit-modal.png` | Commerce language edit modal. |
| `images/meliscommerce-tools-currencies-list.png` | **Currencies** list. |
| `images/meliscommerce-tools-currencies-edit-modal.png` | Currency edit modal. |
| `images/meliscommerce-tools-orderstatus-list.png` | **Order status** list. |
| `images/meliscommerce-tools-orderstatus-edit-modal.png` | Order status edit modal. |
| `images/meliscommerce-tools-clientsgroups-list.png` | **Client’s groups** list. |
| `images/meliscommerce-tools-clientsgroups-edit-modal.png` | Client’s group edit modal. |

*The promo images under `etc/MarketPlace/` are kept separate and are not referenced by this doc. Front-office
shop screenshots are not captured yet — add them under `./images/` and extend this index.*
