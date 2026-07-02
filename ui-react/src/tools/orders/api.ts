import { apiFetch, type ListResult } from '../../shared/api'

export interface OrderStatus { id: number; name: string; color: string }
export interface OrderStats { total: number; today: number; thisMonth: number; pending: number; revenueMonth: number }

export interface OrderItem {
  id: number; reference: string
  status: number; statusName: string; statusColor: string
  clientId: number; personId: number
  company: string; firstname: string; name: string; civility: string
  productCount: number; totalAmount: number
  dateCreation: string | null
}

export interface OrderAddress {
  id: number; type: number
  company: string; civility: number; firstname: string; name: string; middleName: string
  num: string; street: string; building: string; stairs: string
  city: string; state: string; country: string; zipcode: string
  phoneMobile: string; phoneLandline: string; complementary: string
}

export interface OrderBasketItem {
  id: number; sku: string; name: string; category: string; variantId: number
  qty: number; priceNet: number; priceGross: number; attributes: string; currency: string
}

export interface OrderPayment {
  id: number; total: number; orderPrice: number; shipping: number
  currency: string; paymentType: string; transacId: string; confirmed: number; datePay: string | null
}

export interface OrderShipping { id: number; trackingCode: string; content: string; dateSent: string | null }

export interface OrderMessage {
  id: number; message: string; userId: number; userName: string; userEmail: string; clientId: number; dateCreation: string | null
}

export interface OrderAttachment { id: number; name: string; path: string; url: string }

export interface OrderReturn {
  returnId: number; detailId: number; sku: string; product: string; qty: number; variantId: number; date: string | null
}

export interface OrderDetail {
  id: number; reference: string; status: number; statusName: string; statusColor: string
  clientId: number; personId: number; company: string; firstname: string; name: string
  dateCreation: string | null
  basket: OrderBasketItem[]; billing: OrderAddress | null; delivery: OrderAddress | null
  payments: OrderPayment[]; shippings: OrderShipping[]; messages: OrderMessage[]
}

export type OrderListResult = ListResult<OrderItem>

export function fetchOrders(params: {
  search?: string; status?: number | null; dateStart?: string; dateEnd?: string; page?: number; limit?: number
} = {}): Promise<OrderListResult> {
  const qs = new URLSearchParams()
  if (params.search)  qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.dateStart) qs.set('dateStart', params.dateStart)
  if (params.dateEnd)   qs.set('dateEnd', params.dateEnd)
  qs.set('page',  String(params.page  ?? 1))
  qs.set('limit', String(params.limit ?? 25))
  return apiFetch<OrderListResult>(`/melis/react-api/orders?${qs}`)
}

export const fetchOrderStats    = () => apiFetch<OrderStats>('/melis/react-api/orders/stats')
export const fetchOrderStatuses = () => apiFetch<OrderStatus[]>('/melis/react-api/orders/statuses')
export const fetchOrderById     = (id: number) => apiFetch<OrderDetail>(`/melis/react-api/orders/${id}`)

export const createOrder = (payload: { reference: string; status?: number; clientId?: number }) =>
  apiFetch<{ id: number; reference: string }>('/melis/react-api/orders/create', {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const saveOrderInfo = (id: number, payload: { status?: number; reference?: string }) =>
  apiFetch<{ id: number }>(`/melis/react-api/orders/${id}/save`, {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const saveOrderStatus = (id: number, status: number) =>
  apiFetch<{ id: number; status: number }>(`/melis/react-api/orders/${id}/status`, {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ status }),
  })

export const saveOrderAddress = (id: number, payload: Omit<OrderAddress, 'id'>) =>
  apiFetch<{ id: number }>(`/melis/react-api/orders/${id}/address`, {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const saveOrderShipping = (id: number, payload: { trackingCode: string; content: string; dateSent: string }) =>
  apiFetch<{ id: number }>(`/melis/react-api/orders/${id}/shipping`, {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const saveOrderMessage = (id: number, message: string) =>
  apiFetch<{ id: number; dateCreation: string }>(`/melis/react-api/orders/${id}/message`, {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ message }),
  })

export const fetchOrderReturns = (id: number) =>
  apiFetch<OrderReturn[]>(`/melis/react-api/orders/${id}/returns`)

export const fetchOrderAttachments = (id: number) =>
  apiFetch<OrderAttachment[]>(`/melis/react-api/orders/${id}/attachments`)

export const deleteOrderAttachment = (orderId: number, docId: number) =>
  apiFetch<{ id: number }>(`/melis/react-api/orders/${orderId}/attachments/${docId}/delete`, { method: 'POST' })

export const uploadOrderAttachment = (orderId: number, file: File, name: string): Promise<OrderAttachment> => {
  const fd = new FormData()
  fd.append('file', file)
  fd.append('name', name)
  return apiFetch<OrderAttachment>(`/melis/react-api/orders/${orderId}/attachments/upload`, { method: 'POST', body: fd })
}
