import { apiFetch, type ListResult } from '../../shared/api'

export interface CouponItem {
  id: number; code: string; status: number
  assignClients: boolean; assignProducts: boolean
  percentage: number | null; discountValue: number | null
  currencySymbol: string
  maxUseNumber: number | null; currentUseNumber: number
  dateValidStart: string | null; dateValidEnd: string | null
  dateCreation: string | null
}

export type CouponDetail = CouponItem

export interface CouponStats { total: number; active: number; inactive: number; uses: number }

export interface CouponClient { ccliId: number; clientId: number; name: string; company: string }
export interface CouponClientOption { clientId: number; name: string; company: string }

export interface CouponProduct { cprodId: number; productId: number; reference: string; name: string }
export interface CouponProductOption { productId: number; reference: string; name: string }

export interface CouponOrderUsage {
  id: number; orderId: number; orderReference: string
  status: number | null; statusName: string; statusColor: string
  civility: string; firstname: string; lastname: string
  clientId: number
  productCount: number; price: number; currency: string
  quantityUsed: number; dateCreation: string | null
}
export interface CouponOrderStatus { id: number; name: string; color: string }

export type CouponSortKey = 'id' | 'code' | 'status' | 'discount' | 'uses' | 'valid'
export interface CouponListResult { items: CouponItem[]; total: number; nextCursor: string | null }

export function fetchCoupons(params: {
  search?: string; status?: number | null
  limit?: number; sort?: CouponSortKey; dir?: 'asc' | 'desc'; after?: string | null
} = {}): Promise<CouponListResult> {
  const qs = new URLSearchParams()
  if (params.limit) qs.set('limit', String(params.limit))
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.sort) qs.set('sort', params.sort)
  if (params.dir) qs.set('dir', params.dir)
  if (params.after) qs.set('after', params.after)
  return apiFetch<CouponListResult>(`/melis/react-api/coupons?${qs}`)
}

/** Tous les coupons filtrés (export) via boucle de curseur keyset. */
export async function fetchAllCoupons(
  params: { search?: string; status?: number | null } = {},
): Promise<CouponItem[]> {
  const all: CouponItem[] = []
  let after: string | null = null
  do {
    const r: CouponListResult = await fetchCoupons({ ...params, limit: 200, after })
    all.push(...r.items)
    after = r.nextCursor
  } while (after)
  return all
}

export const fetchCouponStats = () => apiFetch<CouponStats>('/melis/react-api/coupons/stats')
export const fetchCouponById  = (id: number) => apiFetch<CouponDetail>(`/melis/react-api/coupons/${id}`)

export const saveCoupon = (payload: {
  id?: number; code: string; status: boolean
  assignClients: boolean; assignProducts: boolean
  percentage: string; discountValue: string; maxUseNumber: string
  dateValidStart: string; dateValidEnd: string
}) =>
  apiFetch<{ id: number }>('/melis/react-api/coupons/save', {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const deleteCoupon = (id: number) =>
  apiFetch<null>(`/melis/react-api/coupons/delete/${id}`, { method: 'DELETE' })

export const fetchClientsDirectory = () =>
  apiFetch<{ items: CouponClientOption[] }>('/melis/react-api/coupons/clients-directory')

export const fetchProductsDirectory = () =>
  apiFetch<{ items: CouponProductOption[] }>('/melis/react-api/coupons/products-directory')

export const fetchCouponClients = (id: number) =>
  apiFetch<{ assigned: CouponClient[]; available: CouponClientOption[] }>(`/melis/react-api/coupons/${id}/clients`)

export const saveCouponClient = (id: number, clientId: number) =>
  apiFetch<{ id: number }>(`/melis/react-api/coupons/${id}/clients/save`, {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ clientId }),
  })

export const deleteCouponClient = (id: number, ccliId: number) =>
  apiFetch<null>(`/melis/react-api/coupons/${id}/clients/${ccliId}/delete`, { method: 'DELETE' })

export const fetchCouponProducts = (id: number) =>
  apiFetch<{ assigned: CouponProduct[]; available: CouponProductOption[] }>(`/melis/react-api/coupons/${id}/products`)

export const saveCouponProduct = (id: number, productId: number) =>
  apiFetch<{ id: number }>(`/melis/react-api/coupons/${id}/products/save`, {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ productId }),
  })

export const deleteCouponProduct = (id: number, cprodId: number) =>
  apiFetch<null>(`/melis/react-api/coupons/${id}/products/${cprodId}/delete`, { method: 'DELETE' })

export function fetchCouponOrders(id: number, params: {
  search?: string; status?: number | null; dateStart?: string; dateEnd?: string
} = {}): Promise<{ items: CouponOrderUsage[]; statuses: CouponOrderStatus[] }> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.dateStart) qs.set('dateStart', params.dateStart)
  if (params.dateEnd)   qs.set('dateEnd', params.dateEnd)
  return apiFetch(`/melis/react-api/coupons/${id}/orders?${qs}`)
}
