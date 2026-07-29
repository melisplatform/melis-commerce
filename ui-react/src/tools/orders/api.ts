import { apiFetch, type ListResult } from '../../shared/api'

export interface OrderStatus { id: number; name: string; color: string }
export interface OrderStats { total: number; today: number; thisMonth: number; pending: number; revenueMonth: number }

export interface OrderItem {
  id: number; reference: string
  status: number; statusName: string; statusColor: string
  clientId: number; personId: number
  company: string; firstname: string; name: string; civility: string
  productCount: number; totalAmount: number; currency: string
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
  id: number; sku: string; name: string; category: string; variantId: number; productId: number
  qty: number; priceNet: number; priceGross: number; attributes: string; currency: string
}

export interface OrderPaymentCoupon { code: string; percentage: number | null; discountValue: number | null; qtyUsed: number }

export interface OrderPayment {
  id: number; total: number; orderPrice: number; shipping: number
  currency: string; paymentType: string; transacId: string; confirmed: number; datePay: string | null
  coupons: OrderPaymentCoupon[]
}

export interface OrderShipping { id: number; trackingCode: string; content: string; dateSent: string | null }

export interface OrderMessage {
  id: number; message: string; userId: number; userName: string; userEmail: string; clientId: number; dateCreation: string | null
  fromAdmin?: boolean; type?: string
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

// ── Checkout wizard ("New Order") — wraps the legacy MelisComOrderCheckoutService /
// MelisComBasketService via /melis/react-api/orders/checkout/*. State lives server-side
// in the PHP session (same mechanism as the legacy BO checkout), keyed to a distinct
// site id — see MelisComReactApiOrderController.php's checkoutSvc()/WIZARD_SITE_ID.
const CHECKOUT_BASE = '/melis/react-api/orders/checkout'
const postJson = <T,>(url: string, body: unknown) =>
  apiFetch<T>(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) })

export interface CheckoutContact {
  id: number; status: number; firstname: string; name: string; email: string
  groupName: string; numOrders: number; lastOrder: string | null
}
export const fetchCheckoutContacts = (params: { search?: string } = {}) => {
  const qs = new URLSearchParams()
  qs.set('page', '1')
  qs.set('limit', '200')
  if (params.search) qs.set('search', params.search)
  return apiFetch<ListResult<CheckoutContact>>(`${CHECKOUT_BASE}/contacts?${qs}`)
}

export const checkoutStart = () => postJson<{ started: boolean }>(`${CHECKOUT_BASE}/start`, {})

export interface CheckoutState {
  step: number
  contactId: number | null; clientId: number | null; countryId: number | null
  billingId: number | null; deliveryId: number | null
  orderId: number | null; reference: string
}
export const fetchCheckoutState = () => apiFetch<CheckoutState>(`${CHECKOUT_BASE}/state`)
export const checkoutAbandon = () => postJson<{ abandoned: boolean }>(`${CHECKOUT_BASE}/abandon`, {})
export const checkoutSetStep = (step: number) => postJson<{ step: number }>(`${CHECKOUT_BASE}/step`, { step })
export const checkoutSelectContact = (contactId: number) => postJson<{ contactId: number }>(`${CHECKOUT_BASE}/select-contact`, { contactId })
export const checkoutSelectAccount = (clientId: number) => postJson<{ clientId: number }>(`${CHECKOUT_BASE}/select-account`, { clientId })
export const checkoutSetCountry = (countryId: number) => postJson<{ countryId: number }>(`${CHECKOUT_BASE}/country`, { countryId })

export interface CheckoutProduct { id: number; reference: string; name: string; image: string; variantCount: number }
export const fetchCheckoutProducts = (params: { page?: number; search?: string } = {}) => {
  const qs = new URLSearchParams()
  qs.set('page', String(params.page ?? 1))
  qs.set('limit', '25')
  if (params.search) qs.set('search', params.search)
  return apiFetch<ListResult<CheckoutProduct>>(`${CHECKOUT_BASE}/products?${qs}`)
}

export interface CheckoutVariant { id: number; sku: string; image: string; attributes: string; price: number | null; stock: number | null }
export const fetchCheckoutVariants = (productId: number) =>
  apiFetch<{ items: CheckoutVariant[] }>(`${CHECKOUT_BASE}/products/${productId}/variants`)

export interface CheckoutBasketLine { variantId: number; sku: string; productName: string; quantity: number; stock: number | null; price: number | null; lineTotal: number | null }
export const fetchCheckoutBasket = () => apiFetch<{ items: CheckoutBasketLine[] }>(`${CHECKOUT_BASE}/basket`)
export const checkoutBasketAdd = (variantId: number, quantity: number) => postJson<{ items: CheckoutBasketLine[] }>(`${CHECKOUT_BASE}/basket/add`, { variantId, quantity })
export const checkoutBasketSetQty = (variantId: number, quantity: number) => postJson<{ items: CheckoutBasketLine[] }>(`${CHECKOUT_BASE}/basket/qty`, { variantId, quantity })
export const checkoutBasketRemove = (variantId: number) => postJson<{ items: CheckoutBasketLine[] }>(`${CHECKOUT_BASE}/basket/remove`, { variantId })

export const checkoutValidateAddresses = (billingId: number, deliveryId: number) =>
  postJson<{ success: boolean }>(`${CHECKOUT_BASE}/addresses`, { billingId, deliveryId })

export interface AppliedCoupon { id: number; code: string; type: 'general' | 'product' }
export interface CheckoutSummary {
  success: boolean; subTotal: number; total: number
  productDiscount: number; orderDiscount: number; coupons: AppliedCoupon[]
  errors: Record<string, string>; items: CheckoutBasketLine[]
}
export const fetchCheckoutSummary = () => apiFetch<CheckoutSummary>(`${CHECKOUT_BASE}/summary`)
export const checkoutApplyCoupon = (code: string) => postJson<{ couponId: number; code: string; type: string | null }>(`${CHECKOUT_BASE}/coupon`, { code })
export const checkoutRemoveCoupon = (couponId: number) => postJson<{ couponId: number }>(`${CHECKOUT_BASE}/coupon/remove`, { couponId })

export interface CheckoutConfirmResult {
  success?: boolean; orderId?: number; reference?: string
  errors?: { basket?: Record<string, unknown>; addresses?: unknown; costs?: unknown }
}
export const checkoutConfirm = () => postJson<CheckoutConfirmResult>(`${CHECKOUT_BASE}/confirm`, {})
