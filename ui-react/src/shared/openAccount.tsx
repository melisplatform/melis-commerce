import type { CSSProperties, ReactNode } from 'react'

/** Route de l'outil « Comptes clients » (même clé d'onglet que le menu). */
const ACCOUNTS_ROUTE = '/melis-commerce/clients-list'
/** Route de l'outil « Produits ». */
const PRODUCTS_ROUTE = '/melis-commerce/product-list'

/**
 * Ouvre le compte client associé (outil Comptes) comme onglet de shell, puis y navigue.
 * - `window.__melisOpenTab` (exposé par le host) crée/active l'onglet Comptes avec un libellé propre ;
 * - `navigate` (react-router du host, partagé par les briques) affiche le formulaire du compte.
 * Sans host (dev standalone), on retombe sur la simple navigation.
 */
export function openClientAccount(navigate: (to: string) => void, clientId: number, label?: string): void {
  if (!clientId) return
  const path = `${ACCOUNTS_ROUTE}/${clientId}`
  const w = window as unknown as { __melisOpenTab?: (t: { id: string; label: string; path: string }) => void }
  try { w.__melisOpenTab?.({ id: ACCOUNTS_ROUTE, label: label || 'Compte client', path }) } catch { /* host absent */ }
  navigate(path)
}

/** Ouvre le produit (outil Produits) comme onglet de shell, puis y navigue. */
export function openProduct(navigate: (to: string) => void, productId: number, label?: string): void {
  if (!productId) return
  const path = `${PRODUCTS_ROUTE}/${productId}`
  const w = window as unknown as { __melisOpenTab?: (t: { id: string; label: string; path: string }) => void }
  try { w.__melisOpenTab?.({ id: PRODUCTS_ROUTE, label: label || 'Produit', path }) } catch { /* host absent */ }
  navigate(path)
}

/** Nom/SKU cliquable → ouvre le produit. Rendu neutre (sans lien) si le produit n'existe plus. */
export function ProductLink({ navigate, productId, label, title, children, style }: {
  navigate: (to: string) => void; productId: number; label?: string; title?: string
  children: ReactNode; style?: CSSProperties
}) {
  if (!productId) return <>{children}</>
  const path = `${PRODUCTS_ROUTE}/${productId}`
  return (
    <a href={path} title={title ?? 'Ouvrir le produit'}
      onClick={(e) => { e.preventDefault(); e.stopPropagation(); openProduct(navigate, productId, label) }}
      style={{ color: 'var(--color-primary)', textDecoration: 'none', cursor: 'pointer', ...style }}
      onMouseEnter={(e) => { e.currentTarget.style.textDecoration = 'underline' }}
      onMouseLeave={(e) => { e.currentTarget.style.textDecoration = 'none' }}>
      {children}
    </a>
  )
}

/** Nom cliquable → ouvre le compte client. Rendu neutre (sans lien) si aucun compte associé. */
export function AccountLink({ navigate, clientId, label, title, children, style }: {
  navigate: (to: string) => void; clientId: number; label?: string; title?: string
  children: ReactNode; style?: CSSProperties
}) {
  if (!clientId) return <>{children}</>
  const path = `${ACCOUNTS_ROUTE}/${clientId}`
  return (
    <a href={path} title={title ?? 'Ouvrir le compte client'}
      onClick={(e) => { e.preventDefault(); e.stopPropagation(); openClientAccount(navigate, clientId, label) }}
      style={{ color: 'var(--color-primary)', textDecoration: 'none', cursor: 'pointer', ...style }}
      onMouseEnter={(e) => { e.currentTarget.style.textDecoration = 'underline' }}
      onMouseLeave={(e) => { e.currentTarget.style.textDecoration = 'none' }}>
      {children}
    </a>
  )
}
