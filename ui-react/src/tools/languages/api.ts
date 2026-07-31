import { apiFetch, type ListResult } from '../../shared/api'

export interface LanguageItem {
  id: number; locale: string; name: string
  status: number; flag: string | null
}

export interface LanguageStats { total: number; active: number; inactive: number }

export type LanguageSortKey = 'id' | 'status' | 'locale' | 'name'
export interface LanguageListResult { items: LanguageItem[]; total: number; nextCursor: string | null }

export function fetchLanguages(params: {
  search?: string; status?: number | null; limit?: number; sort?: LanguageSortKey; dir?: 'asc' | 'desc'; after?: string | null
} = {}): Promise<LanguageListResult> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.limit) qs.set('limit', String(params.limit))
  if (params.sort) qs.set('sort', params.sort)
  if (params.dir) qs.set('dir', params.dir)
  if (params.after) qs.set('after', params.after)
  return apiFetch<LanguageListResult>(`/melis/react-api/commerce-languages?${qs}`)
}

/** Tous les éléments filtrés (export) via boucle de curseur keyset. */
export async function fetchAllLanguages(params: { search?: string; status?: number | null } = {}): Promise<LanguageItem[]> {
  const all: LanguageItem[] = []
  let after: string | null = null
  do {
    const rr: LanguageListResult = await fetchLanguages({ ...params, limit: 200, after })
    all.push(...rr.items)
    after = rr.nextCursor
  } while (after)
  return all
}

export const fetchLanguageStats = () => apiFetch<LanguageStats>('/melis/react-api/commerce-languages/stats')
export const fetchLanguageById  = (id: number) => apiFetch<LanguageItem>(`/melis/react-api/commerce-languages/${id}`)

export const saveLanguage = (payload: {
  id?: number; locale: string; name: string; status: boolean; flag?: string | null
}) =>
  apiFetch<{ id: number }>('/melis/react-api/commerce-languages/save', {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const deleteLanguage = (id: number) =>
  apiFetch<null>(`/melis/react-api/commerce-languages/delete/${id}`, { method: 'DELETE' })
