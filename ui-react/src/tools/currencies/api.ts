import { apiFetch, type ListResult } from '../../shared/api'

export interface CurrencyItem {
  id: number; name: string; code: string; symbol: string
  status: number; isDefault: boolean
}

export interface CurrencyStats { total: number; active: number; inactive: number }

export type CurrencySortKey = 'id' | 'default' | 'status' | 'symbol' | 'code' | 'name'
export interface CurrencyListResult { items: CurrencyItem[]; total: number; nextCursor: string | null }

export function fetchCurrencies(params: {
  search?: string; status?: number | null; limit?: number; sort?: CurrencySortKey; dir?: 'asc' | 'desc'; after?: string | null
} = {}): Promise<CurrencyListResult> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.limit) qs.set('limit', String(params.limit))
  if (params.sort) qs.set('sort', params.sort)
  if (params.dir) qs.set('dir', params.dir)
  if (params.after) qs.set('after', params.after)
  return apiFetch<CurrencyListResult>(`/melis/react-api/currencies?${qs}`)
}

/** Tous les éléments filtrés (export) via boucle de curseur keyset. */
export async function fetchAllCurrencies(params: { search?: string; status?: number | null } = {}): Promise<CurrencyItem[]> {
  const all: CurrencyItem[] = []
  let after: string | null = null
  do {
    const rr: CurrencyListResult = await fetchCurrencies({ ...params, limit: 200, after })
    all.push(...rr.items)
    after = rr.nextCursor
  } while (after)
  return all
}

export const fetchCurrencyStats = () => apiFetch<CurrencyStats>('/melis/react-api/currencies/stats')
export const fetchCurrencyById  = (id: number) => apiFetch<CurrencyItem>(`/melis/react-api/currencies/${id}`)

export const saveCurrency = (payload: { id?: number; name: string; code: string; symbol: string; status: boolean }) =>
  apiFetch<{ id: number }>('/melis/react-api/currencies/save', {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const deleteCurrency = (id: number) =>
  apiFetch<null>(`/melis/react-api/currencies/delete/${id}`, { method: 'DELETE' })

export const setDefaultCurrency = (id: number) =>
  apiFetch<null>(`/melis/react-api/currencies/${id}/set-default`, { method: 'POST' })
