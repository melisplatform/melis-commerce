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

export type OrderStatusListResult = ListResult<OrderStatusItem>

export function fetchOrderStatuses(params: {
  search?: string; status?: number | null; page?: number; limit?: number
} = {}): Promise<OrderStatusListResult> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  qs.set('page', String(params.page ?? 1))
  qs.set('limit', String(params.limit ?? 50))
  return apiFetch<OrderStatusListResult>(`/melis/react-api/order-statuses?${qs}`)
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
