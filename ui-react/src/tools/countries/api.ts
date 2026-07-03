import { apiFetch, type ListResult } from '../../shared/api'

export interface CountryItem {
  id: number; name: string; status: number
  currencyId: number; currencyName: string; currencySymbol: string
  flag: string | null
}

export interface CountryStats { total: number; active: number; inactive: number }

export interface CurrencyOption { id: number; name: string; symbol: string }
export interface CountryOptions { currencies: CurrencyOption[] }

export type CountryListResult = ListResult<CountryItem>

export function fetchCountries(params: {
  search?: string; status?: number | null; page?: number; limit?: number
} = {}): Promise<CountryListResult> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  qs.set('page', String(params.page ?? 1))
  qs.set('limit', String(params.limit ?? 50))
  return apiFetch<CountryListResult>(`/melis/react-api/countries?${qs}`)
}

export const fetchCountryStats   = () => apiFetch<CountryStats>('/melis/react-api/countries/stats')
export const fetchCountryOptions = () => apiFetch<CountryOptions>('/melis/react-api/countries/options')
export const fetchCountryById    = (id: number) => apiFetch<CountryItem>(`/melis/react-api/countries/${id}`)

export const saveCountry = (payload: {
  id?: number; name: string; currencyId: number; status: boolean; flag?: string | null
}) =>
  apiFetch<{ id: number }>('/melis/react-api/countries/save', {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const deleteCountry = (id: number) =>
  apiFetch<null>(`/melis/react-api/countries/delete/${id}`, { method: 'DELETE' })
