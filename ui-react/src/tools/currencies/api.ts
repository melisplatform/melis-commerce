import { apiFetch, type ListResult } from '../../shared/api'

export interface CurrencyItem {
  id: number; name: string; code: string; symbol: string
  status: number; isDefault: boolean
}

export interface CurrencyStats { total: number; active: number; inactive: number }

export type CurrencyListResult = ListResult<CurrencyItem>

export function fetchCurrencies(params: {
  search?: string; status?: number | null; page?: number; limit?: number
} = {}): Promise<CurrencyListResult> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  qs.set('page', String(params.page ?? 1))
  qs.set('limit', String(params.limit ?? 50))
  return apiFetch<CurrencyListResult>(`/melis/react-api/currencies?${qs}`)
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
