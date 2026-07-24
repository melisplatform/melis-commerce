import { useEffect, useState } from 'react'
import { makeT } from '../../../shared/i18n'
import { DICT } from '../dict'
import { fetchCheckoutSummary, checkoutApplyCoupon, checkoutRemoveCoupon, checkoutBasketSetQty, type CheckoutSummary } from '../api'
import { fetchAccountAddresses, fetchAddressOptions } from '../../accounts/api'
import type { Address, AddressOptions } from '../../../shared/address'
import { card, inputCss, btnPrimary, btnGhost } from '../../../shared/styles'
import { CartIcon } from '../../../shared/icons'
import { notify } from '../../../shared/notify'

const smallOutlineBtn = { ...btnGhost, height: 34 }

function AddressCard({ title, address, options, t }: { title: string; address: Address | null; options: AddressOptions; t: (k: string) => string }) {
  const civilityName = address ? options.civilities.find((c) => c.id === address.civility)?.name ?? '' : ''
  const rows: [string, string][] = address ? [
    [t('checkout_addr_title'), address.addressName],
    [t('checkout_addr_civility'), civilityName],
    [t('checkout_addr_firstname'), address.firstname],
    [t('checkout_addr_name'), address.name],
    [t('checkout_addr_num'), address.num],
    [t('checkout_addr_street'), address.street],
    [t('checkout_addr_city'), address.city],
    [t('checkout_addr_zipcode'), address.zipcode],
    [t('checkout_addr_phone'), address.phoneMobile],
  ] : []
  return (
    <div style={{ ...card, overflow: 'hidden', flex: 1, minWidth: 240 }}>
      <div style={{ background: 'var(--color-primary)', color: 'var(--color-primary-foreground,#fff)', padding: '10px 16px', fontWeight: 600, fontSize: 14 }}>{title}</div>
      <div style={{ padding: 16, display: 'flex', flexDirection: 'column', gap: 10 }}>
        {!address ? (
          <span style={{ fontSize: 13, color: 'var(--color-muted-foreground)' }}>—</span>
        ) : rows.map(([label, value]) => (
          <div key={label} style={{ display: 'flex', justifyContent: 'space-between', gap: 12, fontSize: 13, paddingBottom: 8, borderBottom: '1px solid var(--color-border)' }}>
            <span style={{ color: 'var(--color-muted-foreground)' }}>{label}</span>
            <span style={{ textAlign: 'right' }}>{value || '—'}</span>
          </div>
        ))}
      </div>
    </div>
  )
}

export function WizardSummaryStep({ clientId, billingId, deliveryId, onBack, onNext }: {
  clientId: number; billingId: number | null; deliveryId: number | null; onBack: () => void; onNext: () => void
}) {
  const t = makeT(DICT)
  const [summary, setSummary] = useState<CheckoutSummary | null>(null)
  const [addresses, setAddresses] = useState<Address[]>([])
  const [addrOptions, setAddrOptions] = useState<AddressOptions>({ types: [], civilities: [] })
  const [loading, setLoading] = useState(false)
  const [couponCode, setCouponCode] = useState('')
  const [applying, setApplying] = useState(false)
  const [removingId, setRemovingId] = useState<number | null>(null)
  // Lets typing a big value (100, 1000) directly instead of only +/- one at a time (Mantis
  // 0010749) — draft text kept locally while editing, committed (one API call) on blur/Enter.
  const [qtyDraft, setQtyDraft] = useState<Record<number, string>>({})

  useEffect(() => { refresh() }, [])

  function refresh() {
    setLoading(true)
    Promise.all([fetchCheckoutSummary(), fetchAccountAddresses(clientId), fetchAddressOptions()])
      .then(([s, a, o]) => { setSummary(s); setAddresses(a.items); setAddrOptions(o) })
      .catch(() => null)
      .finally(() => setLoading(false))
  }

  function commitQtyDraft(variantId: number, raw: string) {
    setQtyDraft((d) => { const c = { ...d }; delete c[variantId]; return c })
    const n = parseInt(raw, 10)
    if (!isNaN(n)) setQty(variantId, n)
  }

  async function setQty(variantId: number, qty: number) {
    try {
      await checkoutBasketSetQty(variantId, qty)
      const s = await fetchCheckoutSummary()
      setSummary(s)
    } catch (e) {
      notify('ko', t('checkout_step_summary'), e instanceof Error ? e.message : 'Error')
    }
  }

  async function applyCoupon() {
    if (!couponCode.trim() || applying) return
    setApplying(true)
    try {
      await checkoutApplyCoupon(couponCode.trim())
      setCouponCode('')
      notify('ok', t('checkout_step_summary'), t('checkout_coupon_applied'))
      const s = await fetchCheckoutSummary()
      setSummary(s)
    } catch (e) {
      notify('ko', t('checkout_step_summary'), e instanceof Error ? e.message : 'Error')
    } finally {
      setApplying(false)
    }
  }

  async function removeCoupon(couponId: number) {
    setRemovingId(couponId)
    try {
      await checkoutRemoveCoupon(couponId)
      const s = await fetchCheckoutSummary()
      setSummary(s)
    } catch (e) {
      notify('ko', t('checkout_step_summary'), e instanceof Error ? e.message : 'Error')
    } finally {
      setRemovingId(null)
    }
  }

  const isEmpty = summary ? summary.items.length === 0 : false
  const hasErrors = isEmpty || (summary ? Object.keys(summary.errors ?? {}).length > 0 : false)
  const billingAddress = addresses.find((a) => a.id === billingId) ?? null
  const deliveryAddress = addresses.find((a) => a.id === deliveryId) ?? null

  return (
    <div>
      <h3 style={{ fontSize: 20, fontWeight: 600, margin: '0 0 4px' }}>{t('checkout_step_summary')}</h3>
      <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: '0 0 20px' }}>{t('checkout_step_summary_hint')}</p>

      {loading || !summary ? (
        <div style={{ padding: 24, textAlign: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <div style={{ display: 'flex', gap: 20, alignItems: 'flex-start', flexWrap: 'wrap' }}>
          <div style={{ ...card, overflow: 'hidden', flex: 1.4, minWidth: 280 }}>
            <div style={{ background: 'var(--color-primary)', color: 'var(--color-primary-foreground,#fff)', padding: '10px 16px', fontWeight: 600, fontSize: 14, display: 'flex', alignItems: 'center', gap: 8 }}>
              <CartIcon />{t('checkout_basket_title')}
            </div>
            <div style={{ padding: 16, display: 'flex', flexDirection: 'column', gap: 14 }}>
              {summary.items.length === 0 ? (
                <span style={{ fontSize: 13, color: 'var(--color-muted-foreground)' }}>{t('checkout_basket_empty')}</span>
              ) : summary.items.map((l) => (
                <div key={l.variantId} style={{ paddingBottom: 10, borderBottom: '1px solid var(--color-border)' }}>
                  <div style={{ fontWeight: 600, fontSize: 14 }}>{l.productName || l.sku}</div>
                  <div style={{ fontSize: 12, color: 'var(--color-muted-foreground)', marginBottom: 6 }}>{l.sku} — {l.price != null ? l.price.toFixed(2) : '—'}</div>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                    <button style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 28, height: 28, borderRadius: 6, border: '1px solid #dc2626', background: 'transparent', color: '#dc2626', cursor: 'pointer', padding: 0 }}
                      onClick={() => setQty(l.variantId, l.quantity - 1)}>−</button>
                    <input type="number" min={0} inputMode="numeric"
                      style={{ ...inputCss, height: 28, width: 56, textAlign: 'center', padding: '0 4px' }}
                      value={qtyDraft[l.variantId] ?? String(l.quantity)}
                      onChange={(e) => setQtyDraft((d) => ({ ...d, [l.variantId]: e.target.value }))}
                      onBlur={(e) => commitQtyDraft(l.variantId, e.target.value)}
                      onKeyDown={(e) => { if (e.key === 'Enter') (e.target as HTMLInputElement).blur() }} />
                    <button style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 28, height: 28, borderRadius: 6, border: '1px solid #dc2626', background: 'transparent', color: '#dc2626', cursor: 'pointer', padding: 0 }}
                      onClick={() => setQty(l.variantId, l.quantity + 1)}>+</button>
                    <span style={{ marginLeft: 'auto', fontWeight: 700, color: '#dc2626' }}>{l.lineTotal != null ? l.lineTotal.toFixed(2) : '—'}</span>
                  </div>
                </div>
              ))}

              {summary.coupons.length > 0 && (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
                  {summary.coupons.map((c) => (
                    <div key={c.id} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, padding: '4px 10px', borderRadius: 6, background: 'var(--color-accent)', fontSize: 13 }}>
                      <code style={{ fontWeight: 600 }}>{c.code}</code>
                      <button style={{ ...smallOutlineBtn, height: 26, padding: '0 10px', fontSize: 12 }}
                        disabled={removingId === c.id} onClick={() => removeCoupon(c.id)}>
                        {removingId === c.id ? '…' : t('checkout_coupon_remove')}
                      </button>
                    </div>
                  ))}
                </div>
              )}

              <div style={{ display: 'flex', gap: 8 }}>
                <input style={{ ...inputCss, height: 34, flex: 1 }} value={couponCode} onChange={(e) => setCouponCode(e.target.value)}
                  placeholder={t('checkout_coupon_ph')}
                  onKeyDown={(e) => { if (e.key === 'Enter') applyCoupon() }} />
                <button style={smallOutlineBtn} disabled={applying || !couponCode.trim()} onClick={applyCoupon}>
                  {applying ? '…' : t('checkout_apply_coupon')}
                </button>
              </div>

              <div style={{ display: 'flex', flexDirection: 'column', gap: 4, paddingTop: 8 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 14, fontWeight: 600 }}>
                  <span>{t('checkout_subtotal')}</span><span>{summary.subTotal.toFixed(2)}</span>
                </div>
                <div style={{ fontSize: 14, fontWeight: 600, marginTop: 4 }}>{t('checkout_reduction')}</div>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13, color: 'var(--color-muted-foreground)', paddingLeft: 8 }}>
                  <span>{t('checkout_reduction_products')}</span><span>{summary.productDiscount.toFixed(2)}</span>
                </div>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13, color: 'var(--color-muted-foreground)', paddingLeft: 8 }}>
                  <span>{t('checkout_reduction_order')}</span><span>{summary.orderDiscount.toFixed(2)}</span>
                </div>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 16, fontWeight: 700, marginTop: 6, paddingTop: 6, borderTop: '1px solid var(--color-border)' }}>
                  <span>{t('checkout_basket_total')}</span><span>{summary.total.toFixed(2)}</span>
                </div>
              </div>

              {hasErrors && (
                <div style={{ padding: 10, borderRadius: 8, background: 'rgba(220,38,38,.08)', color: '#dc2626', fontSize: 13 }}>
                  {isEmpty ? t('checkout_basket_empty') : t('checkout_summary_errors')}
                </div>
              )}
            </div>
          </div>

          <AddressCard title={t('checkout_delivery_address')} address={deliveryAddress} options={addrOptions} t={t} />
          <AddressCard title={t('checkout_billing_address')} address={billingAddress} options={addrOptions} t={t} />
        </div>
      )}

      <div style={{ marginTop: 24, display: 'flex', justifyContent: 'space-between' }}>
        <button style={btnGhost} onClick={onBack}>{t('checkout_back')}</button>
        <button style={btnPrimary} disabled={!summary || hasErrors} onClick={onNext}>{t('checkout_next')}</button>
      </div>
    </div>
  )
}
