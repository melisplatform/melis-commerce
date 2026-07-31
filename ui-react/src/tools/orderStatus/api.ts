import { apiFetch, type ListResult } from '../../shared/api'

export interface OrderStatusItem {
  id: number; name: string; colorCode: string; status: number; isPermanent: boolean
}

export interface OrderStatusStats { total: number; active: number; inactive: number }

export interface LangOption { id: number; name: string; flag: string }
export interface OrderStatusOptions { languages: LangOption[] }

export interface OrderStatusTranslation { langId: number; name: string }

export interface OrderStatusDetail {
  id: number; colorCode: string; status: number; isPermanent: boolean
  translations: OrderStatusTranslation[]
}

export type OrderStatusSortKey = 'id' | 'name' | 'status'
export interface OrderStatusListResult { items: OrderStatusItem[]; total: number; nextCursor: string | null }

export function fetchOrderStatuses(params: {
  search?: string; status?: number | null; limit?: number; sort?: OrderStatusSortKey; dir?: 'asc' | 'desc'; after?: string | null
} = {}): Promise<OrderStatusListResult> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.limit) qs.set('limit', String(params.limit))
  if (params.sort) qs.set('sort', params.sort)
  if (params.dir) qs.set('dir', params.dir)
  if (params.after) qs.set('after', params.after)
  return apiFetch<OrderStatusListResult>(`/melis/react-api/order-statuses?${qs}`)
}

/** Tous les éléments filtrés (export) via boucle de curseur keyset. */
export async function fetchAllOrderStatuses(params: { search?: string; status?: number | null } = {}): Promise<OrderStatusItem[]> {
  const all: OrderStatusItem[] = []
  let after: string | null = null
  do {
    const rr: OrderStatusListResult = await fetchOrderStatuses({ ...params, limit: 200, after })
    all.push(...rr.items)
    after = rr.nextCursor
  } while (after)
  return all
}

export const fetchOrderStatusStats   = () => apiFetch<OrderStatusStats>('/melis/react-api/order-statuses/stats')
export const fetchOrderStatusOptions = () => apiFetch<OrderStatusOptions>('/melis/react-api/order-statuses/options')
export const fetchOrderStatusById    = (id: number) => apiFetch<OrderStatusDetail>(`/melis/react-api/order-statuses/${id}`)

export const saveOrderStatus = (payload: {
  id?: number; colorCode: string; status: boolean
  translations: OrderStatusTranslation[]
}) =>
  apiFetch<{ id: number }>('/melis/react-api/order-statuses/save', {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const deleteOrderStatus = (id: number) =>
  apiFetch<null>(`/melis/react-api/order-statuses/delete/${id}`, { method: 'DELETE' })
