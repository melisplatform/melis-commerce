---
title: MelisCommerce module — React back-office
package: melisplatform/melis-commerce
doc_type: module-documentation-react
audience: [users, developers, ai]
language: en
module_version: unversioned
last_reviewed: 2026-08-20
maintainer: Melis Technology
keywords: [react, brick, back-office, react-api, commerce, ecommerce, catalog, product, variant, attribute, order, checkout, coupon, account, contact, country, currency, order-status, clients-group, settings, capabilities]
screenshots_dir: ./images/react
related_docs: [./MelisCommerce.md]
---

# MelisCommerce (React back-office) — Functional & Technical Documentation (for AI)

> **What this is.** MelisCommerce is the full **e-commerce suite** of Melis: catalog
> (categories, products, variants, attributes, prices, stock), customers (B2B **accounts**
> + **contacts**), the **order** pipeline (with a guided **checkout wizard**), **coupons**,
> and the commerce reference tools (**countries**, **commerce languages**, **currencies**,
> **order statuses**, **client's groups**, **settings**). This document covers it **in the
> new React back-office** (`/melis-react`). The brick is a **native full-React,
> multi-tool bundle**: **one** IIFE bundle registers **13 separate React tools**, each a
> real React page calling `/melis/react-api/…` JSON endpoints defined in
> `config/react-api.php` and declared (advanced rights) in `config/react.capabilities.php`.
> Every tool also carries a per-tool **New (React) / Old (legacy iframe)** toggle. For the
> underlying data model, services, front-office shop and plugins, see the
> [legacy doc](./MelisCommerce.md).
>
> **How this document is organised — two clearly separated parts:**
> - **[Part A — Functional Guide](#part-a--functional-guide)** — for everyday users (and the
>   chat assistant) using the React back-office. Plain language.
> - **[Part B — Technical Reference](#part-b--technical-reference)** — for developers and AI
>   building inside the React UI, with code (brick manifest, endpoints, capabilities).
>
> **Audience**: consumed by the **MelisAI** MCP. **Status**: reviewed 2026-08-20.

---

## 0. Where this lives in the React back-office — read this first

**Brick kind: native full-React, multi-tool bundle.** MelisCommerce ships **one** Vite
IIFE bundle (`public/ui-react/brick.js`) that registers **13 independent tools** via
`window.__melisRegisterBrick(...)` — one call per tool id (`commerce-accounts`,
`commerce-contacts`, `commerce-catalog`, …). The host (MelisCore) discovers these ids from
`public/ui-react/brick.manifest.json` (a `bricks: [...]` array) and mounts each tool at its
own tree route under the sidebar section **MelisCommerce**. There is **no iframe** in the
default "New" view — the legacy tool only appears through each tool's **New / Old** toggle.

- **Sidebar section → tools:** everything appears under **MelisCommerce**. The 13 routes
  are `/melis-commerce/clients-list` (Accounts), `/melis-commerce/contact-list` (Contacts),
  `/melis-commerce/categories` (Catalogs), `/melis-commerce/product-list` (Products),
  `/melis-commerce/order-list` (Orders), `/melis-commerce/coupon-list` (Coupons),
  `/melis-commerce/attribute-list` (Attributes), `/melis-commerce/country-list`
  (Countries), `/melis-commerce/language-list` (Commerce languages),
  `/melis-commerce/currency-lists` (Currencies), `/melis-commerce/order-status-lists`
  (Order status), `/melis-commerce/clients-group-list` (Client's groups) and
  `/melis-commerce/settings` (Commerce settings).
- **Activation-gated:** like every brick, the tools are discovered via
  `GET /melis/react-api/react-modules` and only appear when MelisCommerce is active.
- **Sub-tabs / persistence:** the seven "entity" tools (Accounts, Contacts, Catalogs,
  Products, Orders, Coupons, Attributes, Order status) set `subTabs: true` + `persistent:
  true` — opening a record adds a sub-tab and the tool stays mounted across navigation. The
  "settings-style" tools (Countries, Commerce languages, Currencies, Client's groups,
  Settings) are `persistent: true` only.
- **New / Old toggle:** every one of the 13 tools renders the shared `ViewModeToggle` +
  `LegacyFrame`; "Old" is the classic tool served in an iframe at
  `/melis/react-tool-page?key=<melisKey>`.

![The MelisCommerce section in the /melis-react sidebar — the tools selector.](./images/react/meliscommerce-menu-tools-selector.png)
*The MelisCommerce tools listed in the React sidebar (13 tools).*

Legacy tool doc (data model, services, front-office shop, plugins):
[MelisCommerce.md](./MelisCommerce.md).

---
---

# PART A — Functional Guide

## A1. What you can do with MelisCommerce in the new back-office

A 13-tool commerce workbench, all in React:

- **Accounts** — B2B customer accounts (company, contacts, addresses, orders, files).
- **Contacts** — individual contacts (information, address, account associations).
- **Catalogs** — the category tree (properties, SEO, products in category).
- **Products** — products with rich editors (properties, text, **variants**, SEO, prices).
- **Orders** — the order list, the per-order editor, and the **New Order checkout wizard**.
- **Coupons** — discount coupons (properties, assigned accounts, assigned products, orders).
- **Attributes** — product attributes (properties, labels, values).
- **Countries** / **Commerce languages** / **Currencies** — commerce reference lists.
- **Order status** — the order-status list (properties + per-language labels).
- **Client's groups** — customer groups.
- **Commerce settings** — the single-page commerce configuration (properties + accounts).

> Rule of thumb: build the **catalog** (attributes → catalogs → products/variants), manage
> **customers** (accounts + contacts), then run the **order** pipeline (orders + checkout
> wizard + coupons), all backed by the commerce reference lists.

## A2. Finding it in /melis-react

**Where:** sidebar → **MelisCommerce** → pick a tool. Each tool opens at its own tree route
(see §0). The tools that support sub-tabs open a record (an account, product, order…) as a
sub-tab at the top so you can jump back to the list without losing your place.

> **Tip:** every tool has a top **New / Old** switch. "New" is the React view; "Old" shows
> the classic legacy tool in an iframe so you can compare — the React view is the default.

## A3. Key words explained

- **Account** — a B2B customer (a company) grouping one or more **contacts**, with
  addresses and an order history. Backed by the accounts service (see legacy doc).
- **Contact** — an individual person, optionally **associated** to one or more accounts.
- **Catalog / category** — a node in the category tree; products belong to categories.
- **Product / variant** — a product has one or more **variants**, each with its own prices,
  stock, SEO, media, attributes and associations.
- **Attribute** — a typed product characteristic (with values and per-language labels).
- **Order** — a customer order in the pipeline; created from an account/contact via the
  **checkout wizard**, then edited (status, addresses, shipping, messages, returns…).
- **Coupon** — a discount code, optionally assigned to specific accounts/products.
- **Order status** — a state in the order lifecycle, with a colour and per-language labels.

For the full data model and services defer to the [legacy doc](./MelisCommerce.md).

## A4. Accounts

**Where:** sidebar → MelisCommerce → **Accounts** (`/melis-commerce/clients-list`). The list
supports search, status/group filters, a column manager, export and CSV **import**. Opening
an account is a sub-tab with tabs:

![The Accounts list.](./images/react/meliscommerce-tools-accounts-list.png)
*The Accounts list (React) with filters, column manager and import.*

- **Properties** — status, name (manual / from company / from contact), group, country, tags.

  ![Account editor — Properties.](./images/react/meliscommerce-tools-accounts-new-tab-properties.png)
  *Account editor — Properties tab.*

- **Company** — company details.

  ![Account editor — Company.](./images/react/meliscommerce-tools-accounts-new-tab-company.png)
  *Account editor — Company tab.*

- **Contacts** — link/unlink contacts to the account and set the default.

  ![Account editor — Contacts.](./images/react/meliscommerce-tools-accounts-new-tab-contacts.png)
  *Account editor — Contacts tab (link / unlink / default).*

- **Addresses** — the account's addresses (address editor).
- **Orders** — the account's order history.

  ![Account editor — Orders.](./images/react/meliscommerce-tools-accounts-new-tab-orders.png)
  *Account editor — Orders tab (order history).*

- **Files** — upload/delete account files with a file type.

  ![Account editor — Files.](./images/react/meliscommerce-tools-accounts-new-tab-files.png)
  *Account editor — Files tab (upload / delete).*

The Addresses tab is shown here:

![Account editor — Addresses.](./images/react/meliscommerce-tools-accounts-new-tab-adresses.png)
*Account editor — Addresses tab.*

## A5. Contacts

**Where:** MelisCommerce → **Contacts** (`/melis-commerce/contact-list`). List with filters,
export; the editor has **Information**, **Address** and **Association** tabs (link/unlink a
contact to accounts, set the default).

![The Contacts list.](./images/react/meliscommerce-tools-contacts-list.png)
*The Contacts list (React).*

![Contact editor — Information.](./images/react/meliscommerce-tools-contacts-edit-tab-information.png)
*Contact editor — Information tab.*

![Contact editor — Address.](./images/react/meliscommerce-tools-contacts-edit-tab-address.png)
*Contact editor — Address tab.*

![Contact editor — Association.](./images/react/meliscommerce-tools-contacts-edit-tab-association.png)
*Contact editor — Association tab (link / unlink accounts).*

## A6. Catalogs (categories)

**Where:** MelisCommerce → **Catalogs** (`/melis-commerce/categories`). A **tree view** of
categories (drag to reorder). Editing a category has **Properties**, **SEO** and
**Products** (the products assigned to the category, reorderable).

![The Catalog tree.](./images/react/meliscommerce-tools-catalogs-treeview.png)
*The Catalog category tree (React).*

![Category editor — Properties.](./images/react/meliscommerce-tools-catalogs-edit-tab-properties.png)
*Category editor — Properties tab.*

![Category editor — SEO.](./images/react/meliscommerce-tools-catalogs-edit-tab-seo.png)
*Category editor — SEO tab.*

![Category editor — Products.](./images/react/meliscommerce-tools-catalogs-edit-tab-products.png)
*Category editor — Products tab (products in category, reorderable).*

## A7. Products & variants

**Where:** MelisCommerce → **Products** (`/melis-commerce/product-list`). List with filters,
duplicate, export. The product editor has **Properties**, **Text**, **Variants**, **SEO**
and **Prices** tabs. The **Variants** tab is the richest: each variant has its own editor
(Properties, SEO, Prices, Stocks, Associations), plus media.

![The Products list.](./images/react/meliscommerce-tools-products-list.png)
*The Products list (React).*

![Product editor — Properties.](./images/react/meliscommerce-tools-products-edit-tab-properties.png)
*Product editor — Properties tab.*

![Product editor — Text.](./images/react/meliscommerce-tools-products-edit-tab-text.png)
*Product editor — Text tab (per-language texts).*

![Product editor — Variants.](./images/react/meliscommerce-tools-products-edit-tab-variants.png)
*Product editor — Variants tab (variant editor with prices/stocks/SEO/associations).*

![Product editor — SEO.](./images/react/meliscommerce-tools-products-edit-tab-seo.png)
*Product editor — SEO tab.*

![Product editor — Prices.](./images/react/meliscommerce-tools-products-edit-tab-prices.png)
*Product editor — Prices tab.*

## A8. Orders & the checkout wizard

**Where:** MelisCommerce → **Orders** (`/melis-commerce/order-list`). The list has filters,
status filters and export. The per-order editor tabs are **Properties**, **Basket**,
**Addresses**, **Payment**, **Shipping**, **Messages** and **Returns** (Invoices when
MelisCommerceOrderInvoice is active).

![The Orders list.](./images/react/meliscommerce-tools-order-list.png)
*The Orders list (React).*

![Order editor — Properties.](./images/react/meliscommerce-tools-order-edit-tab-properties.png)
*Order editor — Properties tab.*

![Order editor — Basket.](./images/react/meliscommerce-tools-order-edit-tab-basket.png)
*Order editor — Basket tab (read-only).*

![Order editor — Addresses.](./images/react/meliscommerce-tools-order-edit-tab-addresses.png)
*Order editor — Addresses tab.*

![Order editor — Payment.](./images/react/meliscommerce-tools-order-edit-tab-payment.png)
*Order editor — Payment tab (read-only).*

![Order editor — Shipping.](./images/react/meliscommerce-tools-order-edit-tab-shipping.png)
*Order editor — Shipping tab.*

![Order editor — Messages.](./images/react/meliscommerce-tools-order-edit-tab-messasges.png)
*Order editor — Messages tab.*

![Order editor — Returns.](./images/react/meliscommerce-tools-order-edit-tab-returns.png)
*Order editor — Returns tab (read-only).*

**New Order — the checkout wizard.** From the Orders list, **New Order** opens a guided
**7-step wizard** (`contact → account → products → addresses → summary → payment →
confirmation`), backed by a server-side checkout session that resumes where you left off.

![Checkout wizard — step 1.](./images/react/meliscommerce-tools-order-newordertunnel-1.png)
*The New Order checkout wizard — first steps (contact / account / products).*

![Checkout wizard — step 2.](./images/react/meliscommerce-tools-order-newordertunnel-2.png)
*The checkout wizard — addresses / summary.*

![Checkout wizard — step 3.](./images/react/meliscommerce-tools-order-newordertunnel-3.png)
*The checkout wizard — payment / confirmation.*

## A9. Coupons

**Where:** MelisCommerce → **Coupons** (`/melis-commerce/coupon-list`). Editor tabs:
**Properties**, **Assign account** (clients), **Assign product**, **Orders** (usage history).

![The Coupons list.](./images/react/meliscommerce-tools-coupons-list.png)
*The Coupons list (React).*

![Coupon editor — Properties.](./images/react/meliscommerce-tools-coupons-edit-tab-properties.png)
*Coupon editor — Properties tab.*

![Coupon editor — Assigned clients.](./images/react/meliscommerce-tools-coupons-edit-tab-assignedclients.png)
*Coupon editor — Assign account tab.*

![Coupon editor — Assigned products.](./images/react/meliscommerce-tools-coupons-edit-tab-assignedproducts.png)
*Coupon editor — Assign product tab.*

![Coupon editor — Orders.](./images/react/meliscommerce-tools-coupons-edit-tab-orders.png)
*Coupon editor — Orders tab (usage history).*

## A10. Attributes

**Where:** MelisCommerce → **Attributes** (`/melis-commerce/attribute-list`). Editor tabs:
**Properties** (reference, type, status, visible, searchable), **Labels** (per-language
name/description) and **Values** (the attribute's values, each with typed translations).

![The Attributes list.](./images/react/meliscommerce-tools-attributes-list.png)
*The Attributes list (React).*

![Attribute editor — Properties.](./images/react/meliscommerce-tools-attributes-edit-tab-properties.png)
*Attribute editor — Properties tab.*

![Attribute editor — Labels.](./images/react/meliscommerce-tools-attributes-edit-tab-labels.png)
*Attribute editor — Labels tab (translations).*

![Attribute editor — Values.](./images/react/meliscommerce-tools-attributes-edit-tab-values.png)
*Attribute editor — Values tab (values with typed translations).*

## A11. Commerce reference tools (Countries, Languages, Currencies, Order status, Client's groups, Settings)

Small "settings-style" tools — single-page lists with add/edit modals or a single form:

- **Countries** (`/melis-commerce/country-list`) — the commerce country list.

  ![The Countries list.](./images/react/meliscommerce-tools-countries-list.png)
  *The Countries list (React).*

  ![Country editor.](./images/react/meliscommerce-tools-countries-edit.png)
  *Country add/edit.*

- **Commerce languages** (`/melis-commerce/language-list`) — commerce languages list + modal.

  ![The Commerce languages list.](./images/react/meliscommerce-tools-commercelanguages-list.png)
  *The Commerce languages list (React).*

  ![Commerce language edit modal.](./images/react/meliscommerce-tools-commercelanguages-edit-modal.png)
  *Add/edit a commerce language (modal).*

- **Currencies** (`/melis-commerce/currency-lists`) — currency list + modal, with a
  **set default** action.

  ![The Currencies list.](./images/react/meliscommerce-tools-currencies-list.png)
  *The Currencies list (React).*

  ![Currency edit modal.](./images/react/meliscommerce-tools-currencies-edit-modal.png)
  *Add/edit a currency (modal).*

- **Order status** (`/melis-commerce/order-status-lists`) — status list; editor has
  **Properties** (status/colour) and **Labels** (per-language).

  ![The Order status list.](./images/react/meliscommerce-tools-orderstatus-list.png)
  *The Order status list (React).*

  ![Order status editor — Properties.](./images/react/meliscommerce-tools-orderstatus-edit-properties.png)
  *Order status editor — Properties tab.*

  ![Order status editor — Labels.](./images/react/meliscommerce-tools-orderstatus-edit-labels.png)
  *Order status editor — Labels tab (translations).*

- **Client's groups** (`/melis-commerce/clients-group-list`) — customer-group list + modal.

  ![The Client's groups list.](./images/react/meliscommerce-tools-clientsgroups-list.png)
  *The Client's groups list (React).*

  ![Client's group edit modal.](./images/react/meliscommerce-tools-clientsgroups-edit-modal.png)
  *Add/edit a client's group (modal).*

- **Commerce settings** (`/melis-commerce/settings`) — a single configuration page with
  **Properties** and **Accounts** tabs.

  ![Commerce settings — Properties.](./images/react/meliscommerce-tools-settings-tab-properties.png)
  *Commerce settings — Properties tab.*

  ![Commerce settings — Accounts.](./images/react/meliscommerce-tools-settings-tab-accounts.png)
  *Commerce settings — Accounts tab.*

## A12. Common tasks — "How do I…?"

- **…create an account?** Accounts → **Add** → Properties (name/group/country/tags) → link
  contacts / addresses / files as needed (§A4).
- **…add a product with variants?** Products → **Add** → Properties/Text → **Variants** tab
  → add a variant → set its Prices/Stocks/SEO/Associations (§A7).
- **…build a category tree?** Catalogs → add/reorder nodes in the tree → set
  Properties/SEO/Products (§A6).
- **…create a new order manually?** Orders → **New Order** → follow the 7-step wizard
  (contact → account → products → addresses → summary → payment → confirmation) (§A8).
- **…change an order's status / add a shipping / message?** Orders → open the order →
  Properties / Shipping / Messages tabs (§A8).
- **…create a coupon assigned to some accounts/products?** Coupons → **Add** → Properties →
  Assign account / Assign product (§A9).
- **…add an attribute with values?** Attributes → **Add** → Properties/Labels → **Values**
  (§A10).
- **…compare with the old tool?** Toggle **New / Old** on any tool (§A2).

---
---

# PART B — Technical Reference

## B1. React presence at a glance

One IIFE bundle, **13 registered tools**. Brick kind: **native full-React** (each tool has a
per-tool New/Old iframe toggle). All tools are `persistent`; the entity tools also set
`subTabs`. Manifest: `public/ui-react/brick.manifest.json` (`entry: "brick.js"`, a
`bricks: [...]` array).

| Brick id | Route | Label | forwardKey | melisKey | subTabs | persistent |
|---|---|---|---|---|---|---|
| `commerce-accounts` | `/melis-commerce/clients-list` | Accounts | `MelisCommerce/MelisComClientList` | `meliscommerce_clients_list_page` | ✔ | ✔ |
| `commerce-contacts` | `/melis-commerce/contact-list` | Contacts | `MelisCommerce/MelisComContact` | `meliscommerce_contact_list_page` | ✔ | ✔ |
| `commerce-catalog` | `/melis-commerce/categories` | Catalogs | `MelisCommerce/MelisComCategoryList` | `meliscommerce_categories_page` | ✔ | ✔ |
| `commerce-products` | `/melis-commerce/product-list` | Products | `MelisCommerce/MelisComProductList` | `meliscommerce_product_list_container` | ✔ | ✔ |
| `commerce-orders` | `/melis-commerce/order-list` | Orders | `MelisCommerce/MelisComOrderList` | `meliscommerce_order_list_page` | ✔ | ✔ |
| `commerce-coupons` | `/melis-commerce/coupon-list` | Coupons | `MelisCommerce/MelisComCouponList` | `meliscommerce_coupon_list_page` | ✔ | ✔ |
| `commerce-attributes` | `/melis-commerce/attribute-list` | Attributes | `MelisCommerce/MelisComAttributeList` | `meliscommerce_attribute_list_page` | ✔ | ✔ |
| `commerce-countries` | `/melis-commerce/country-list` | Countries | `MelisCommerce/MelisComCountry` | `meliscommerce_country_list_container` | ✔ | ✔ |
| `commerce-languages` | `/melis-commerce/language-list` | Commerce languages | `MelisCommerce/MelisComLanguage` | `meliscommerce_language_list_container` | — | ✔ |
| `commerce-currencies` | `/melis-commerce/currency-lists` | Currencies | `MelisCommerce/MelisComCurrency` | `meliscommerce_currency_conf` | — | ✔ |
| `commerce-order-status` | `/melis-commerce/order-status-lists` | Order status | `MelisCommerce/MelisComOrderStatus` | `meliscommerce_order_status_tool_page` | ✔ | ✔ |
| `commerce-clients-groups` | `/melis-commerce/clients-group-list` | Client's groups | `MelisCommerce/MelisComClientsGroup` | `meliscommerce_clients_group_tool_container` | — | ✔ |
| `commerce-settings` | `/melis-commerce/settings` | Commerce settings | `MelisCommerce/MelisComSettings` | `meliscommerce_settings_page` | — | ✔ |

- **Brick kind:** native full-React; each tool page also renders a `ViewModeToggle` +
  `LegacyFrame` (New/Old, "Old" = iframe to `/melis/react-tool-page?key=<melisKey>`).
- **Activation-gated:** yes (discovered via `GET /melis/react-api/react-modules`).
- **react-api.php:** yes — **13 invokable controllers**, **184 routes** under
  `/melis/react-api/…`.
- **react.capabilities.php:** yes — keyed under the 13 melisKeys above.

## B2. The brick — anatomy

`ui-react/` is a Vite **IIFE** library (`vite.config.ts`: `formats: ['iife']`, name
`MelisCommerceBrick`, `entry: 'src/brick.tsx'`, `outDir: '../public/ui-react'`,
`emptyOutDir: false` so the hand-authored `brick.manifest.json` survives). `react`,
`react-dom`, `react/jsx-runtime` and `react-router-dom` are **externalised to the host
globals** (`MelisReact`, `MelisReactDOM`, `MelisReactJsxRuntime`, `MelisReactRouterDOM`) —
the brick reuses the host's React instance so hooks, context and the Router work across the
boundary.

Entry `src/brick.tsx` (runs on load) registers **13** tools by id, e.g.:

```ts
import AccountPage from './tools/accounts/AccountPage'
// … 12 more imports …
window.__melisRegisterBrick?.({ id: 'commerce-accounts', Component: AccountPage })
window.__melisRegisterBrick?.({ id: 'commerce-contacts', Component: ContactPage })
window.__melisRegisterBrick?.({ id: 'commerce-catalog',  Component: CatalogPage })
// … one call per tool, ids matching brick.manifest.json …
```

Source layout (`ui-react/src/`):

| Path | Role |
|---|---|
| `brick.tsx` | Registers all 13 tool pages by id (ids match the manifest). |
| `tools/accounts/` | `AccountPage.tsx`, `AccountTabs.tsx`, `api.ts`, `dict.ts` — Accounts tool. |
| `tools/contacts/` | `ContactPage.tsx`, `ContactTabs.tsx`, `api.ts`, `dict.ts` — Contacts tool. |
| `tools/catalog/` | `CatalogPage.tsx`, `CatalogTree.tsx`, `api.ts`, `dict.ts` — Catalog tree + editor. |
| `tools/products/` | `ProductPage.tsx`, `ProductTabs.tsx`, `ProductVariantEditor.tsx`, `api.ts`, `dict.ts` — Products + variants. |
| `tools/orders/` | `OrderPage.tsx`, `OrderTabs.tsx`, `api.ts`, `dict.ts` + `wizard/` (the 7-step checkout). |
| `tools/coupons/` | `CouponPage.tsx`, `CouponTabs.tsx`, `AssignTable.tsx`, `api.ts`, `dict.ts`. |
| `tools/attributes/` | `AttributePage.tsx`, `AttributeTabs.tsx`, `api.ts`, `dict.ts`. |
| `tools/countries/` `tools/languages/` `tools/currencies/` `tools/orderStatus/` `tools/clientsGroups/` `tools/settings/` | The reference/settings tools (`*Page.tsx` + `api.ts` + `dict.ts`). |
| `shared/` | Shared kit: `api.ts` (the `{success,data,error}` `apiFetch` helper), `widgets.tsx` (`ViewModeToggle`, `LegacyFrame`, `Kpi`, `StatusBadge`, `TagsInput`…), `AddressEditor.tsx`, `ColManager.tsx`, `Tabs.tsx`, `ExportModal.tsx`, `ConfirmModal.tsx`, `SearchableSelect.tsx`, `DatePicker.tsx`/`DateRangeFilter.tsx`, `SimpleTable.tsx`, `use-keyset-list.ts`, `useCaps.ts`, `subtabs.ts`, `i18n.ts`, `columns.ts`, `listCache.ts`, `notify.ts`, `icons.tsx`. |

The Orders **checkout wizard** (`tools/orders/wizard/`): `OrderCheckoutWizard.tsx` drives a
`STEPS` array `[contact, account, products, addresses, summary, payment, confirmation]`,
each rendered by `WizardContactStep`, `WizardAccountStep`, `WizardProductsStep`,
`WizardAddressesStep`, `WizardSummaryStep`, `WizardPaymentStep`, `WizardConfirmationStep`.

Built bundle: `public/ui-react/brick.js` + `public/ui-react/brick.manifest.json`.

> The "Old" view is a `LegacyFrame` iframe to `/melis/react-tool-page?key=<melisKey>`,
> hidden (`visible` prop) — never destroyed — when the tool is in React mode.

## B3. React API — endpoints

All routes are child routes of `melis-react-api` (merged from `config/react-api.php`),
served by **13 invokable controllers** in `src/Controller/ReactApi/`. Response contract
everywhere (via `shared/api.ts` `apiFetch`): `{ success: bool, data: T, error?: string }`.
Every action calls `denyUnlessAccess()` first — auth + `MelisCoreRights::canAccess(self::MELIS_KEY)`
(401/403). Each controller declares its own `private const MELIS_KEY` (= the manifest
melisKey of its tool). **184 routes** total (~14 per tool on average). Controllers only
validate input and shape JSON; the real work is in the module's Laminas commerce services
(see [legacy doc](./MelisCommerce.md)).

Controllers, melisKey, controller alias and route prefix:

| Tool | Controller (`…\Controller\ReactApi\…`) | Invokable alias | melisKey | Route prefix |
|---|---|---|---|---|
| Accounts | `MelisComReactApiAccountController` | `MelisCommerce\Controller\MelisComReactApiAccount` | `meliscommerce_clients_list_page` | `/accounts…` |
| Contacts | `MelisComReactApiContactController` | `…MelisComReactApiContact` | `meliscommerce_contact_list_page` | `/contacts…` |
| Catalogs | `MelisComReactApiCatalogController` | `…MelisComReactApiCatalog` | `meliscommerce_categories_page` | `/catalog…` |
| Products | `MelisComReactApiProductController` | `…MelisComReactApiProduct` | `meliscommerce_product_list_container` | `/products…` |
| Orders | `MelisComReactApiOrderController` | `…MelisComReactApiOrder` | `meliscommerce_order_list_page` | `/orders…` (+ `/orders/checkout/…`) |
| Coupons | `MelisComReactApiCouponController` | `…MelisComReactApiCoupon` | `meliscommerce_coupon_list_page` | `/coupons…` |
| Attributes | `MelisComReactApiAttributeController` | `…MelisComReactApiAttribute` | `meliscommerce_attribute_list_page` | `/attributes…` |
| Countries | `MelisComReactApiCountryController` | `…MelisComReactApiCountry` | `meliscommerce_country_list_container` | `/countries…` |
| Commerce languages | `MelisComReactApiLanguageController` | `…MelisComReactApiLanguage` | `meliscommerce_language_list_container` | `/commerce-languages…` |
| Currencies | `MelisComReactApiCurrencyController` | `…MelisComReactApiCurrency` | `meliscommerce_currency_conf` | `/currencies…` |
| Order status | `MelisComReactApiOrderStatusController` | `…MelisComReactApiOrderStatus` | `meliscommerce_order_status_tool_page` | `/order-statuses…` |
| Client's groups | `MelisComReactApiClientsGroupController` | `…MelisComReactApiClientsGroup` | `meliscommerce_clients_group_tool_container` | `/clients-groups…` |
| Settings | `MelisComReactApiSettingsController` | `…MelisComReactApiSettings` | `meliscommerce_settings_page` | `/settings…` |

> ⚠ **Commerce languages are namespaced** as `/commerce-languages` (not `/languages`):
> `/languages` is already taken by the core BO Languages tool. Two child routes of the same
> key under the shared `melis-react-api` parent would merge and one would shadow the other.

**Common per-tool shape** — every entity tool exposes roughly: `GET /<tool>` (keyset list),
`GET /<tool>/stats` (KPI cards), `GET /<tool>/options` (selector reference data),
`POST /<tool>/save`, `DELETE /<tool>/delete/:id`, `GET /<tool>/:id` (detail). On top of that:

- **Accounts** — `/accounts/import-test`, `/accounts/import` (CSV), and per-account
  sub-resources: `:id/company[/save]`, `:id/contacts[/link]` + `:id/contacts/:contactId[/default]`,
  `:id/addresses[/save]` + `address-options`, `:id/orders`, `:id/files[/upload]` +
  `:id/files/:fileId`, `file-options`, `file-types`, `order-statuses`, `contact-options`.
- **Contacts** — `address-options`, `:id/addresses[/save]`, `:id/associations[/link]` +
  `:id/associations/:accountId[/default]`.
- **Catalogs** — `/catalog/tree`, `/catalog/reorder`, `:id/seo[/save]`,
  `:id/products[/reorder]`.
- **Products** — a large variant subtree: `:id/variants[/save|/:variantId[/detail|/duplicate]]`,
  and per-variant `prices`, `stocks`, `seo/save`, `media[/upload|/:docId[/update]]`,
  `assoc[/save|/:avarId]`, `attributes[/save|/:vatvId]`; plus product-level `prices`,
  `attributes`, `media`, `alert` + `alert/recipients`, `duplicate`, `tooltip`, `page-tree`,
  `document-types`, `text-types`.
- **Orders** — the editor endpoints (`:id/save`, `:id/status`, `:id/address`, `:id/shipping`,
  `:id/message`, `:id/returns`, `:id/attachments[…]`) **and the full checkout wizard** under
  `/orders/checkout/*` (`start`, `state`, `step`, `abandon`, `contacts`, `select-contact`,
  `select-account`, `country`, `products` + `products/:productId/variants`, `basket[/add|/qty|/remove]`,
  `addresses`, `summary`, `coupon[/remove]`, `confirm`).
- **Coupons** — `clients-directory`, `products-directory`, `:id/clients[/save|/:ccliId/delete]`,
  `:id/products[/save|/:cprodId/delete]`, `:id/orders`.
- **Attributes** — `:id/values[/save|/:valId[/binary]]`.
- **Currencies** — `:id/set-default`.
- **Settings** — single page: `GET /settings`, `/settings/options`, `POST /settings/save`
  (no list/delete).

Example — fetch the accounts list (as `AccountPage` does via `api.ts`):

```ts
// shared/api.ts apiFetch already sends X-Requested-With + credentials:'include'
const items = await apiFetch<AccountListResult>(
  '/melis/react-api/accounts?limit=25&sort=id&dir=desc',
) // -> { items: AccountItem[], total, nextCursor }
```

## B4. Capabilities (advanced rights)

`config/react.capabilities.php` (merged into `getConfig()` via `ArrayUtils::merge`, read by
`MelisReactApi\Service\Capabilities`) declares one `melisReactToolCapabilities` entry per
tool, **keyed under that tool's rights-bearing melisKey** — the same melisKey the controller
guards and the manifest carries. These caps are **declarative default-allow**: they drive
the checkbox tree in Users → Rights and let the React UI hide tabs/buttons; there is
**no server-side `denyUnlessCan()` enforcement yet** (only `denyUnlessAccess()` on the
tool's melisKey gates the API). A user/role without the capabilities section keeps
everything.

Each entry is an `{ actions, tabs }` tree; `Capabilities::flatten()` emits `tab` and
`tab.action` strings. Per tool:

- **`meliscommerce_clients_list_page`** (Accounts) — actions `list, create, edit, delete,
  export`; tabs `properties`, `contacts` (+`list,create,delete`), `company`, `addresses`,
  `orders`, `files` (+`list,create,delete`).
- **`meliscommerce_contact_list_page`** (Contacts) — actions `list, create, edit, delete,
  export`; tabs `information`, `address`, `association` (+`list,create,delete`).
- **`meliscommerce_categories_page`** (Catalogs) — actions `list, create, edit, delete` (no
  export); tabs `properties`, `seo`, `products`, `price_discount` (the last brought by
  **MelisCommerceGroupDiscountPerCategory** if active).
- **`meliscommerce_product_list_container`** (Products) — actions `list, create, edit,
  delete, duplicate, export`; tabs `main`, `text`, `variants`
  (+`list,create,edit,delete,duplicate`, with nested tabs `properties`, `seo`, `prices`,
  `stocks`, `assoc`), `seo`, `prices`.
- **`meliscommerce_order_list_page`** (Orders) — actions `list, create, edit, export` (no
  delete); tabs `information`, `basket`, `addresses` (+`edit`), `payment`, `shipping`
  (+`list,create`), `messages` (+`list,create`), `returns`, `invoices` (brought by
  **MelisCommerceOrderInvoice** if active).
- **`meliscommerce_coupon_list_page`** (Coupons) — actions `list, create, edit, delete,
  export`; tabs `information`, `clients`, `products`, `orders`.
- **`meliscommerce_attribute_list_page`** (Attributes) — actions `list, create, edit,
  delete, export`; tabs `main`, `labels`, `values` (+`list,create,edit,delete`).
- **`meliscommerce_country_list_container`**, **`meliscommerce_language_list_container`**,
  **`meliscommerce_currency_conf`**, **`meliscommerce_clients_group_tool_container`** —
  flat `list, create, edit, delete, export` (single-form tools, no tabs).
- **`meliscommerce_order_status_tool_page`** (Order status) — actions `list, create, edit,
  delete, export`; tabs `main`, `labels`.
- **`meliscommerce_settings_page`** (Settings) — action `edit` only; tabs `main`, `accounts`.

React reads them via `useCaps(...)` (`shared/useCaps.ts`) to mask UI. Because there is no
controller-side capability guard, treat these as **UI hints**, not security.

## B5. Host integration

- **Discovery / load:** standard brick flow — `GET /melis/react-api/react-modules` lists the
  module, the host loads `brick.js`, `brick.tsx` registers the 13 pages by id.
- **Menu mapping:** the host maps each tool's `forwardKey` (e.g.
  `MelisCommerce/MelisComClientList`) → its route (`/melis-commerce/clients-list`), falling
  back to the manifest `route`.
- **Sub-tabs / persistence:** the entity tools declare `subTabs: true` + `persistent: true`;
  the brick uses `shared/subtabs.ts` (`openSubTab`) to open a record as a host sub-tab and
  keeps panes mounted (filters / half-typed forms survive host navigation).
- **New / Old iframe:** `shared/widgets.tsx` `ViewModeToggle` + `LegacyFrame` mount an iframe
  to `/melis/react-tool-page?key=<melisKey>` for the "Old" view (hidden, not destroyed, in
  React mode).
- **i18n:** each tool has its own `dict.ts` (+ `shared/i18n.ts`); server-side error strings
  come back translated in the `error` field.
- **Config merge:** `src/Module.php::getConfig()` `include`s both `config/react-api.php` and
  `config/react.capabilities.php` and `ArrayUtils::merge`s them into the module config.

> **In short:** React owns all 13 commerce tool UIs + their JSON API; the legacy PHP tools
> are untouched and only reachable through each tool's New/Old toggle. The commerce data
> model, services and front-office shop live in the module's Laminas layer — see the
> [legacy doc](./MelisCommerce.md).

## B6. Quick code map

```
melis-commerce/
├─ ui-react/                                   # brick SOURCE (Vite IIFE, React externalised)
│  ├─ vite.config.ts                           # name MelisCommerceBrick → public/ui-react/brick.js
│  └─ src/
│     ├─ brick.tsx                             # 13× __melisRegisterBrick({ id: 'commerce-*', Component })
│     ├─ tools/
│     │  ├─ accounts/  (AccountPage, AccountTabs, api, dict)
│     │  ├─ contacts/  (ContactPage, ContactTabs, api, dict)
│     │  ├─ catalog/   (CatalogPage, CatalogTree, api, dict)
│     │  ├─ products/  (ProductPage, ProductTabs, ProductVariantEditor, api, dict)
│     │  ├─ orders/    (OrderPage, OrderTabs, api, dict + wizard/OrderCheckoutWizard + 7 Wizard*Step)
│     │  ├─ coupons/   (CouponPage, CouponTabs, AssignTable, api, dict)
│     │  ├─ attributes/(AttributePage, AttributeTabs, api, dict)
│     │  ├─ countries/ languages/ currencies/ orderStatus/ clientsGroups/ settings/  (*Page + api + dict)
│     └─ shared/       (api.ts {success,data,error}, widgets.tsx ViewModeToggle/LegacyFrame,
│                       AddressEditor, ColManager, Tabs, ExportModal, useCaps, subtabs, i18n, …)
├─ public/ui-react/{brick.js, brick.manifest.json}   # BUILD (13 bricks)
├─ config/
│  ├─ react-api.php                            # 184 routes /melis/react-api/* + 13 invokable controllers
│  └─ react.capabilities.php                   # melisReactToolCapabilities, 13 melisKeys
└─ src/
   ├─ Module.php                               # getConfig() merges react-api.php + react.capabilities.php
   └─ Controller/ReactApi/
      ├─ MelisComReactApiAccountController.php  (meliscommerce_clients_list_page)
      ├─ MelisComReactApiContactController.php  (meliscommerce_contact_list_page)
      ├─ MelisComReactApiCatalogController.php  (meliscommerce_categories_page)
      ├─ MelisComReactApiProductController.php  (meliscommerce_product_list_container)
      ├─ MelisComReactApiOrderController.php    (meliscommerce_order_list_page + checkout wizard)
      ├─ MelisComReactApiCouponController.php   (meliscommerce_coupon_list_page)
      ├─ MelisComReactApiAttributeController.php (meliscommerce_attribute_list_page)
      ├─ MelisComReactApiCountryController.php  (meliscommerce_country_list_container)
      ├─ MelisComReactApiLanguageController.php (meliscommerce_language_list_container)
      ├─ MelisComReactApiCurrencyController.php (meliscommerce_currency_conf)
      ├─ MelisComReactApiOrderStatusController.php (meliscommerce_order_status_tool_page)
      ├─ MelisComReactApiClientsGroupController.php (meliscommerce_clients_group_tool_container)
      └─ MelisComReactApiSettingsController.php (meliscommerce_settings_page)
```

The commerce data layer (tables, services, front-office shop, plugins) is documented in the
[legacy doc](./MelisCommerce.md).

---

## Screenshot index

Filename → content lookup for the MelisAI MCP. All under `./images/react/`.

| Image file | Content |
|---|---|
| `meliscommerce-menu-tools-selector.png` | The MelisCommerce tools listed in the React sidebar (13 tools). |
| `meliscommerce-tools-accounts-list.png` | The **Accounts** list (React) — filters, column manager, import. |
| `meliscommerce-tools-accounts-new-tab-properties.png` | Account editor — **Properties** tab. |
| `meliscommerce-tools-accounts-new-tab-company.png` | Account editor — **Company** tab. |
| `meliscommerce-tools-accounts-new-tab-contacts.png` | Account editor — **Contacts** tab (link / unlink / default). |
| `meliscommerce-tools-accounts-new-tab-adresses.png` | Account editor — **Addresses** tab. |
| `meliscommerce-tools-accounts-new-tab-orders.png` | Account editor — **Orders** tab (order history). |
| `meliscommerce-tools-accounts-new-tab-files.png` | Account editor — **Files** tab (upload / delete). |
| `meliscommerce-tools-contacts-list.png` | The **Contacts** list (React). |
| `meliscommerce-tools-contacts-edit-tab-information.png` | Contact editor — **Information** tab. |
| `meliscommerce-tools-contacts-edit-tab-address.png` | Contact editor — **Address** tab. |
| `meliscommerce-tools-contacts-edit-tab-association.png` | Contact editor — **Association** tab (link / unlink accounts). |
| `meliscommerce-tools-catalogs-treeview.png` | The **Catalog** category tree (React). |
| `meliscommerce-tools-catalogs-edit-tab-properties.png` | Category editor — **Properties** tab. |
| `meliscommerce-tools-catalogs-edit-tab-seo.png` | Category editor — **SEO** tab. |
| `meliscommerce-tools-catalogs-edit-tab-products.png` | Category editor — **Products** tab (products in category, reorderable). |
| `meliscommerce-tools-products-list.png` | The **Products** list (React). |
| `meliscommerce-tools-products-edit-tab-properties.png` | Product editor — **Properties** tab. |
| `meliscommerce-tools-products-edit-tab-text.png` | Product editor — **Text** tab (per-language texts). |
| `meliscommerce-tools-products-edit-tab-variants.png` | Product editor — **Variants** tab (variant editor). |
| `meliscommerce-tools-products-edit-tab-seo.png` | Product editor — **SEO** tab. |
| `meliscommerce-tools-products-edit-tab-prices.png` | Product editor — **Prices** tab. |
| `meliscommerce-tools-order-list.png` | The **Orders** list (React). |
| `meliscommerce-tools-order-edit-tab-properties.png` | Order editor — **Properties** tab. |
| `meliscommerce-tools-order-edit-tab-basket.png` | Order editor — **Basket** tab (read-only). |
| `meliscommerce-tools-order-edit-tab-addresses.png` | Order editor — **Addresses** tab. |
| `meliscommerce-tools-order-edit-tab-payment.png` | Order editor — **Payment** tab (read-only). |
| `meliscommerce-tools-order-edit-tab-shipping.png` | Order editor — **Shipping** tab. |
| `meliscommerce-tools-order-edit-tab-messasges.png` | Order editor — **Messages** tab. |
| `meliscommerce-tools-order-edit-tab-returns.png` | Order editor — **Returns** tab (read-only). |
| `meliscommerce-tools-order-newordertunnel-1.png` | New Order checkout wizard — first steps (contact / account / products). |
| `meliscommerce-tools-order-newordertunnel-2.png` | New Order checkout wizard — addresses / summary. |
| `meliscommerce-tools-order-newordertunnel-3.png` | New Order checkout wizard — payment / confirmation. |
| `meliscommerce-tools-coupons-list.png` | The **Coupons** list (React). |
| `meliscommerce-tools-coupons-edit-tab-properties.png` | Coupon editor — **Properties** tab. |
| `meliscommerce-tools-coupons-edit-tab-assignedclients.png` | Coupon editor — **Assign account** tab. |
| `meliscommerce-tools-coupons-edit-tab-assignedproducts.png` | Coupon editor — **Assign product** tab. |
| `meliscommerce-tools-coupons-edit-tab-orders.png` | Coupon editor — **Orders** tab (usage history). |
| `meliscommerce-tools-attributes-list.png` | The **Attributes** list (React). |
| `meliscommerce-tools-attributes-edit-tab-properties.png` | Attribute editor — **Properties** tab. |
| `meliscommerce-tools-attributes-edit-tab-labels.png` | Attribute editor — **Labels** tab (translations). |
| `meliscommerce-tools-attributes-edit-tab-values.png` | Attribute editor — **Values** tab (values with typed translations). |
| `meliscommerce-tools-countries-list.png` | The **Countries** list (React). |
| `meliscommerce-tools-countries-edit.png` | Country add/edit. |
| `meliscommerce-tools-commercelanguages-list.png` | The **Commerce languages** list (React). |
| `meliscommerce-tools-commercelanguages-edit-modal.png` | Add/edit a commerce language (modal). |
| `meliscommerce-tools-currencies-list.png` | The **Currencies** list (React). |
| `meliscommerce-tools-currencies-edit-modal.png` | Add/edit a currency (modal). |
| `meliscommerce-tools-orderstatus-list.png` | The **Order status** list (React). |
| `meliscommerce-tools-orderstatus-edit-properties.png` | Order status editor — **Properties** tab. |
| `meliscommerce-tools-orderstatus-edit-labels.png` | Order status editor — **Labels** tab (translations). |
| `meliscommerce-tools-clientsgroups-list.png` | The **Client's groups** list (React). |
| `meliscommerce-tools-clientsgroups-edit-modal.png` | Add/edit a client's group (modal). |
| `meliscommerce-tools-settings-tab-properties.png` | **Commerce settings** — Properties tab. |
| `meliscommerce-tools-settings-tab-accounts.png` | **Commerce settings** — Accounts tab. |

---

*Document for AI consumption (MelisAI MCP) — React back-office of
`melisplatform/melis-commerce`. Part A = functional guide for users; Part B = technical
reference with examples for developers/AI. Legacy tool doc:
[./MelisCommerce.md](./MelisCommerce.md). The commerce data model, services and
front-office shop live in the module's Laminas layer. Last reviewed 2026-08-20.*
