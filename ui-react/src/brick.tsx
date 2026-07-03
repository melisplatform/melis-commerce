import AccountPage from './tools/accounts/AccountPage'
import ContactPage from './tools/contacts/ContactPage'
import CatalogPage from './tools/catalog/CatalogPage'
import ProductPage from './tools/products/ProductPage'
import OrderPage from './tools/orders/OrderPage'
import CouponPage from './tools/coupons/CouponPage'
import AttributePage from './tools/attributes/AttributePage'
import CountryPage from './tools/countries/CountryPage'
import LanguagePage from './tools/languages/LanguagePage'
import CurrencyPage from './tools/currencies/CurrencyPage'
import ClientsGroupPage from './tools/clientsGroups/ClientsGroupPage'
import OrderStatusPage from './tools/orderStatus/OrderStatusPage'
import SettingsPage from './tools/settings/SettingsPage'

/**
 * Point d'entrée de la brique MelisCommerce. Un seul bundle IIFE livre PLUSIEURS outils
 * React (un par dossier dans src/tools/) ; chacun s'auto-enregistre par son id. L'hôte
 * (MelisCore) découvre ces ids via public/ui-react/brick.manifest.json (tableau `bricks`)
 * et monte chaque outil à sa route d'arbre. React/Router sont EXTERNES (globals de l'hôte).
 */
declare global {
  interface Window {
    __melisRegisterBrick?: (b: { id: string; Component?: unknown }) => void
  }
}

window.__melisRegisterBrick?.({ id: 'commerce-accounts', Component: AccountPage })
window.__melisRegisterBrick?.({ id: 'commerce-contacts', Component: ContactPage })
window.__melisRegisterBrick?.({ id: 'commerce-catalog', Component: CatalogPage })
window.__melisRegisterBrick?.({ id: 'commerce-products', Component: ProductPage })
window.__melisRegisterBrick?.({ id: 'commerce-orders', Component: OrderPage })
window.__melisRegisterBrick?.({ id: 'commerce-coupons', Component: CouponPage })
window.__melisRegisterBrick?.({ id: 'commerce-attributes', Component: AttributePage })
window.__melisRegisterBrick?.({ id: 'commerce-countries', Component: CountryPage })
window.__melisRegisterBrick?.({ id: 'commerce-languages', Component: LanguagePage })
window.__melisRegisterBrick?.({ id: 'commerce-currencies', Component: CurrencyPage })
window.__melisRegisterBrick?.({ id: 'commerce-clients-groups', Component: ClientsGroupPage })
window.__melisRegisterBrick?.({ id: 'commerce-order-status', Component: OrderStatusPage })
window.__melisRegisterBrick?.({ id: 'commerce-settings', Component: SettingsPage })
