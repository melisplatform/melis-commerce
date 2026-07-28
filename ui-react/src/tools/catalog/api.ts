import { apiFetch, type Option } from '../../shared/api'

/** Nœud de l'arbre catalogues/catégories. */
export interface CatNode {
  id: number; fatherId: number; order: number; status: number
  name: string; type: 'catalog' | 'category'; productCount: number
  children: CatNode[]
}
export interface CatText { langId: number; langName: string; name: string; description: string }
export interface CatDetail {
  id: number; fatherId: number; type: 'catalog' | 'category'; status: number
  reference: string; validStart: string | null; validEnd: string | null
  texts: CatText[]; countryIds: number[]
}
export interface LangOption { id: number; name: string; flag: string }
export interface CatalogOptions { languages: LangOption[]; countries: Option[] }
export interface CatSavePayload {
  id?: number | null; fatherId: number; status: boolean; reference: string
  validStart: string | null; validEnd: string | null
  texts: { langId: number; name: string; description: string }[]; countryIds: number[]
}

export interface CatSeo { langId: number; langName: string; pageId: string; url: string; urlRedirect: string; url301: string; metaTitle: string; metaDescription: string }
export interface CatProduct { id: number; reference: string; name: string; status: number }

export const fetchCatalogTree = (langId?: number) =>
  apiFetch<{ items: CatNode[] }>(`/melis/react-api/catalog/tree${langId ? `?langId=${langId}` : ''}`)
export const fetchCatalogOptions = () => apiFetch<CatalogOptions>('/melis/react-api/catalog/options')
export const fetchCategoryById = (id: number) => apiFetch<CatDetail>(`/melis/react-api/catalog/${id}`)
export const saveCategory = (payload: CatSavePayload) =>
  apiFetch<{ id: number }>('/melis/react-api/catalog/save', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) })
export const reorderCategory = (payload: { catId: number; fatherId: number; order: number; oldParent: number }) =>
  apiFetch<null>('/melis/react-api/catalog/reorder', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) })
export const deleteCategory = (id: number) => apiFetch<null>(`/melis/react-api/catalog/delete/${id}`, { method: 'DELETE' })
export const fetchCategorySeo = (id: number) => apiFetch<{ items: CatSeo[] }>(`/melis/react-api/catalog/${id}/seo`)
export const saveCategorySeo = (id: number, items: { langId: number; pageId: string; url: string; urlRedirect: string; url301: string; metaTitle: string; metaDescription: string }[]) =>
  apiFetch<null>(`/melis/react-api/catalog/${id}/seo/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ items }) })
export const fetchCategoryProducts = (id: number) => apiFetch<{ items: CatProduct[] }>(`/melis/react-api/catalog/${id}/products`)
export const reorderCategoryProducts = (id: number, productIds: number[]) =>
  apiFetch<null>(`/melis/react-api/catalog/${id}/products/reorder`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ productIds }) })
