import AccountPage from './tools/accounts/AccountPage'
import ContactPage from './tools/contacts/ContactPage'
import CatalogPage from './tools/catalog/CatalogPage'
import ProductPage from './tools/products/ProductPage'

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
