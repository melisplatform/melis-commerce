import { apiFetch, type ListResult } from '../../shared/api'

export interface LanguageItem {
  id: number; locale: string; name: string
  status: number; flag: string | null
}

export interface LanguageStats { total: number; active: number; inactive: number }

export type LanguageListResult = ListResult<LanguageItem>

export function fetchLanguages(params: {
  search?: string; status?: number | null; page?: number; limit?: number
} = {}): Promise<LanguageListResult> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  qs.set('page', String(params.page ?? 1))
  qs.set('limit', String(params.limit ?? 50))
  return apiFetch<LanguageListResult>(`/melis/react-api/commerce-languages?${qs}`)
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
