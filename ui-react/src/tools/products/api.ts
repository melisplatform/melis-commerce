import { apiFetch, type Stats, type Option } from '../../shared/api'
import type { LangOption } from '../catalog/api'

export interface ProductItem {
  id: number; reference: string; status: number; name: string; image: string
  categories: string[]; dateCreation: string | null; dateEdit: string | null
}
export interface ProductText { langId: number; langName: string; name: string; description: string }
// Type de texte additionnel (« Ajouter un type de texte », legacy meliscommerce_product_text_type_form) —
// fieldType 1 = court (ptxt_field_short, <input>), 2 = long (ptxt_field_long, éditeur riche).
export interface ProductExtraText { typeId: number; code: string; name: string; fieldType: 1 | 2; values: { langId: number; ptxtId: number | null; value: string }[] }
export interface TextType { id: number; code: string; name: string; fieldType: 1 | 2 }
export interface ProductSeo { langId: number; langName: string; pageId: string; url: string; urlRedirect: string; url301: string; metaTitle: string; metaDescription: string }
export interface ProductVariant { id: number; sku: string; status: number; isMain: number; image: string; attributes: string; dateCreation: string | null }
export interface ProductDetail extends ProductItem {
  stockLow: number | null; texts: ProductText[]; extraTexts: ProductExtraText[]; seo: ProductSeo[]; categoryIds: number[]; variants: ProductVariant[]; pageLinks: PageLinks
}
export interface ProductPrice { id: number; countryId: number; currency: number; net: number | null; gross: number | null; vatPercent: number | null; vatPrice: number | null; otherTax: number | null }
export interface ProductAttribute { pattId: number; attributeId: number; name: string }
export interface MediaItem { id: number; name: string; path: string; typeId: number | null; typeName: string | null; countryId: number }
export interface DocType { id: number; code: string; name: string }
export interface UserOption { id: number; name: string; email: string }
export interface AlertRecipient { seaId: number; userId: number; email: string; name: string }
export interface PageLinks { link1: string; link2: string; link3: string }
export interface CountryOption { id: number; name: string; flag: string }
export type ProductStats = Stats
export interface ProductOptions { categories: Option[]; languages: LangOption[]; countries: CountryOption[]; currencies: Option[]; attributes: Option[]; users: UserOption[] }
export interface ProductSavePayload {
  id?: number | null; reference: string; status: boolean; stockLow: number | null
  texts: { langId: number; name: string; description: string }[]
  extraTexts?: ProductExtraText[]
  seo: { langId: number; url: string; metaTitle: string; metaDescription: string }[]
  categoryIds: number[]
  pageLinks?: PageLinks
}

export type ProductSortKey = 'id' | 'status' | 'reference' | 'name' | 'created'
export interface ProductListResult { items: ProductItem[]; total: number; nextCursor: string | null }

export function fetchProducts(
  params: { search?: string; status?: number | null; categoryId?: number | null
            limit?: number; sort?: ProductSortKey; dir?: 'asc' | 'desc'; after?: string | null } = {},
): Promise<ProductListResult> {
  const qs = new URLSearchParams()
  if (params.limit) qs.set('limit', String(params.limit))
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.categoryId) qs.set('categoryId', String(params.categoryId))
  if (params.sort) qs.set('sort', params.sort)
  if (params.dir) qs.set('dir', params.dir)
  if (params.after) qs.set('after', params.after)
  return apiFetch<ProductListResult>(`/melis/react-api/products?${qs}`)
}

/** Tous les produits filtrés (export) via boucle de curseur keyset. */
export async function fetchAllProducts(
  params: { search?: string; status?: number | null; categoryId?: number | null } = {},
): Promise<ProductItem[]> {
  const all: ProductItem[] = []
  let after: string | null = null
  do {
    const r: ProductListResult = await fetchProducts({ ...params, limit: 200, after })
    all.push(...r.items)
    after = r.nextCursor
  } while (after)
  return all
}
export const fetchProductById = (id: number) => apiFetch<ProductDetail>(`/melis/react-api/products/${id}`)
export interface TooltipVariant { id: number; sku: string; image: string; attributes: string; price: number | null; stock: number | null }
export const fetchProductTooltip = (id: number) => apiFetch<{ items: TooltipVariant[] }>(`/melis/react-api/products/${id}/tooltip`)
export const fetchProductStats = () => apiFetch<ProductStats>('/melis/react-api/products/stats')
export const fetchProductOptions = () => apiFetch<ProductOptions>('/melis/react-api/products/options')
export const saveProduct = (payload: ProductSavePayload) =>
  apiFetch<{ id: number }>('/melis/react-api/products/save', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) })
export const deleteProduct = (id: number) => apiFetch<null>(`/melis/react-api/products/delete/${id}`, { method: 'DELETE' })
export interface DuplicateOpts { duplicateImages: boolean; duplicateDocuments: boolean; putOnline: boolean }
export const duplicateProduct = (id: number, opts: DuplicateOpts & { reference: string }) =>
  apiFetch<{ id: number }>(`/melis/react-api/products/${id}/duplicate`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(opts) })

// ── Variants ──────────────────────────────────────────────────────────────────
export type ProductVariantSortKey = 'id' | 'main' | 'status' | 'sku'
export const fetchProductVariants = (
  id: number,
  params: { limit?: number; sort?: ProductVariantSortKey; dir?: 'asc' | 'desc'; after?: string | null; search?: string } = {},
) => {
  const qs = new URLSearchParams()
  if (params.limit) qs.set('limit', String(params.limit))
  if (params.sort) qs.set('sort', params.sort)
  if (params.dir) qs.set('dir', params.dir)
  if (params.after) qs.set('after', params.after)
  if (params.search) qs.set('search', params.search)
  const q = qs.toString()
  return apiFetch<{ items: ProductVariant[]; total: number; nextCursor: string | null }>(`/melis/react-api/products/${id}/variants${q ? `?${q}` : ''}`)
}
export const saveProductVariant = (id: number, v: { variantId?: number | null; sku: string; status: boolean; isMain: boolean }) =>
  apiFetch<{ id: number }>(`/melis/react-api/products/${id}/variants/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(v) })
export const deleteProductVariant = (id: number, variantId: number) =>
  apiFetch<null>(`/melis/react-api/products/${id}/variants/${variantId}`, { method: 'DELETE' })

// ── Prix ─────────────────────────────────────────────────────────────────────
export const fetchProductPrices = (id: number) => apiFetch<{ items: ProductPrice[] }>(`/melis/react-api/products/${id}/prices`)
export const saveProductPrice = (id: number, p: { id?: number | null; countryId: number; currency: number; net: number | null; gross: number | null; vatPercent: number | null; vatPrice: number | null; otherTax: number | null }) =>
  apiFetch<null>(`/melis/react-api/products/${id}/prices/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(p) })
export const deleteProductPrice = (id: number, priceId: number) =>
  apiFetch<null>(`/melis/react-api/products/${id}/prices/${priceId}`, { method: 'DELETE' })

// ── Attributs ──────────────────────────────────────────────────────────────────
export const fetchProductAttributes = (id: number) => apiFetch<{ items: ProductAttribute[] }>(`/melis/react-api/products/${id}/attributes`)
export const saveProductAttribute = (id: number, attributeId: number) =>
  apiFetch<null>(`/melis/react-api/products/${id}/attributes/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ attributeId }) })
export const deleteProductAttribute = (id: number, pattId: number) =>
  apiFetch<null>(`/melis/react-api/products/${id}/attributes/${pattId}`, { method: 'DELETE' })

// ── Médias (images + fichiers) — lecture + upload + suppression ─────────────────
export const fetchProductMedia = (id: number) => apiFetch<{ images: MediaItem[]; files: MediaItem[] }>(`/melis/react-api/products/${id}/media`)
export const uploadProductMedia = (id: number, m: { name: string; data: string; kind: 'image' | 'file'; typeId?: number | null; countryId?: number }) =>
  apiFetch<null>(`/melis/react-api/products/${id}/media/upload`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(m) })
export const updateProductMedia = (id: number, docId: number, m: { name: string; typeId?: number | null; countryId?: number; data?: string }) =>
  apiFetch<null>(`/melis/react-api/products/${id}/media/${docId}/update`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(m) })
export const deleteProductMedia = (id: number, docId: number) =>
  apiFetch<null>(`/melis/react-api/products/${id}/media/${docId}`, { method: 'DELETE' })
export const fetchDocumentTypes = () => apiFetch<{ imageTypes: DocType[]; fileTypes: DocType[] }>('/melis/react-api/products/document-types')
export const addDocumentType = (kind: 'image' | 'file', code: string, name: string) =>
  apiFetch<null>('/melis/react-api/products/document-types', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ kind, code, name }) })

// ── Types de texte (« Ajouter un type de texte ») ───────────────────────────────
export const fetchTextTypes = () => apiFetch<TextType[]>('/melis/react-api/products/text-types')
export const addTextType = (data: { code?: string; name: string; fieldType: 1 | 2 }) =>
  apiFetch<TextType>('/melis/react-api/products/text-types', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) })

// ── Alerte stock — destinataires ────────────────────────────────────────────────
export const fetchProductAlert = (id: number) => apiFetch<{ recipients: AlertRecipient[] }>(`/melis/react-api/products/${id}/alert`)
export const saveProductRecipient = (id: number, recipient: { userId: number } | { email: string }, stockLevel: number | null) =>
  apiFetch<null>(`/melis/react-api/products/${id}/alert/recipients/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ ...recipient, stockLevel }) })
export const deleteProductRecipient = (id: number, seaId: number) =>
  apiFetch<null>(`/melis/react-api/products/${id}/alert/recipients/${seaId}`, { method: 'DELETE' })

// ── Éditeur de variant (détail + sous-ressources) ──────────────────────────────
export interface VariantSeo { langId: number; langName: string; pageId: string; url: string; urlRedirect: string; url301: string; metaTitle: string; metaDescription: string }
export interface VariantDetail { id: number; sku: string; status: number; isMain: number; seo: VariantSeo[] }
export interface VariantPrice { id: number; countryId: number; currency: number; net: number | null; gross: number | null; vatPercent: number | null; vatPrice: number | null; otherTax: number | null }
export interface VariantStock { id: number; countryId: number; quantity: number; low: number | null; nextFillUp: string | null }
export const fetchVariant = (prdId: number, varId: number) => apiFetch<VariantDetail>(`/melis/react-api/products/${prdId}/variants/${varId}/detail`)
export const fetchVariantMedia = (prdId: number, varId: number) => apiFetch<{ images: MediaItem[]; files: MediaItem[] }>(`/melis/react-api/products/${prdId}/variants/${varId}/media`)
export const uploadVariantMedia = (prdId: number, varId: number, m: { name: string; data: string; kind: 'image' | 'file' }) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/media/upload`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(m) })
export const updateVariantMedia = (prdId: number, varId: number, docId: number, m: { name: string; typeId?: number | null; countryId?: number; data?: string }) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/media/${docId}/update`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(m) })
export const deleteVariantMedia = (prdId: number, varId: number, docId: number) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/media/${docId}`, { method: 'DELETE' })
export const duplicateProductVariant = (prdId: number, varId: number, opts: DuplicateOpts & { sku: string }) =>
  apiFetch<{ id: number }>(`/melis/react-api/products/${prdId}/variants/${varId}/duplicate`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(opts) })
export const fetchVariantPrices = (prdId: number, varId: number) => apiFetch<{ items: VariantPrice[] }>(`/melis/react-api/products/${prdId}/variants/${varId}/prices`)
export const saveVariantPrice = (prdId: number, varId: number, p: { id?: number | null; countryId: number; currency: number; net: number | null; gross: number | null; vatPercent: number | null; vatPrice: number | null; otherTax: number | null }) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/prices/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(p) })
export const deleteVariantPrice = (prdId: number, varId: number, priceId: number) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/prices/${priceId}`, { method: 'DELETE' })
export const fetchVariantStocks = (prdId: number, varId: number) => apiFetch<{ items: VariantStock[] }>(`/melis/react-api/products/${prdId}/variants/${varId}/stocks`)
export const saveVariantStock = (prdId: number, varId: number, s: { id?: number | null; countryId: number; quantity: number; low: number | null; nextFillUp: string | null }) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/stocks/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(s) })
export const deleteVariantStock = (prdId: number, varId: number, stockId: number) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/stocks/${stockId}`, { method: 'DELETE' })
export const saveVariantSeo = (prdId: number, varId: number, items: { langId: number; pageId: string; url: string; urlRedirect: string; url301: string; metaTitle: string; metaDescription: string }[]) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/seo/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ items }) })

// Association de variants
export interface AssocVariant { avarId: number; variantId: number; sku: string; status: number; productName: string; attributes: string }
export const fetchVariantAssoc = (prdId: number, varId: number) => apiFetch<{ associated: AssocVariant[]; excludeIds: number[] }>(`/melis/react-api/products/${prdId}/variants/${varId}/assoc`)
export const saveVariantAssoc = (prdId: number, varId: number, variantId: number) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/assoc/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ variantId }) })
export const deleteVariantAssoc = (prdId: number, varId: number, avarId: number) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/assoc/${avarId}`, { method: 'DELETE' })

// Valeurs d'attribut du variant — une entrée par attribut ASSIGNÉ AU PRODUIT, avec sa
// valeur actuellement sélectionnée (0/1 par attribut, comme le legacy) et ses valeurs possibles.
export interface VariantAttrGroup { attributeId: number; attributeName: string; selectedValueId: number | null; selectedVatvId: number | null; values: Option[] }
export const fetchVariantAttributes = (prdId: number, varId: number) => apiFetch<{ attributes: VariantAttrGroup[] }>(`/melis/react-api/products/${prdId}/variants/${varId}/attributes`)
export const saveVariantAttribute = (prdId: number, varId: number, attributeId: number, valueId: number) =>
  apiFetch<null>(`/melis/react-api/products/${prdId}/variants/${varId}/attributes/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ attributeId, valueId }) })

// ── Arbre des pages CMS (sélecteur d'association de page) ────────────────────────
export interface PageNode { id: number; father: number; name: string; type: string; children: PageNode[] }
export const fetchPageTree = () => apiFetch<{ items: PageNode[] }>('/melis/react-api/products/page-tree')
