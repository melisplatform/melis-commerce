import { useEffect, useState } from 'react'
import { makeT } from '../../../shared/i18n'
import { DICT } from '../dict'
import { fetchCheckoutSummary, checkoutApplyCoupon, checkoutBasketSetQty, type CheckoutSummary } from '../api'
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
  const [couponApplied, setCouponApplied] = useState(false)

  useEffect(() => { refresh() }, [])

  function refresh() {
    setLoading(true)
    Promise.all([fetchCheckoutSummary(), fetchAccountAddresses(clientId), fetchAddressOptions()])
      .then(([s, a, o]) => { setSummary(s); setAddresses(a.items); setAddrOptions(o) })
      .catch(() => null)
      .finally(() => setLoading(false))
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
      setCouponApplied(true)
      notify('ok', t('checkout_step_summary'), t('checkout_coupon_applied'))
    } catch (e) {
      notify('ko', t('checkout_step_summary'), e instanceof Error ? e.message : 'Error')
    } finally {
      setApplying(false)
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
                    <input style={{ ...inputCss, height: 28, width: 50, textAlign: 'center', padding: 0 }} value={l.quantity} readOnly />
                    <button style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 28, height: 28, borderRadius: 6, border: '1px solid #dc2626', background: 'transparent', color: '#dc2626', cursor: 'pointer', padding: 0 }}
                      onClick={() => setQty(l.variantId, l.quantity + 1)}>+</button>
                    <span style={{ marginLeft: 'auto', fontWeight: 700, color: '#dc2626' }}>{l.lineTotal != null ? l.lineTotal.toFixed(2) : '—'}</span>
                  </div>
                </div>
              ))}

              <div style={{ display: 'flex', gap: 8 }}>
                <input style={{ ...inputCss, height: 34, flex: 1 }} value={couponCode} onChange={(e) => setCouponCode(e.target.value)}
                  placeholder={t('checkout_coupon_ph')} disabled={couponApplied} />
                <button style={smallOutlineBtn} disabled={applying || couponApplied || !couponCode.trim()} onClick={applyCoupon}>
                  {applying ? '…' : couponApplied ? t('checkout_coupon_applied') : t('checkout_apply_coupon')}
                </button>
              </div>

              <div style={{ display: 'flex', flexDirection: 'column', gap: 4, paddingTop: 8 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 14, fontWeight: 600 }}>
                  <span>{t('checkout_subtotal')}</span><span>{summary.subTotal.toFixed(2)}</span>
                </div>
                <div style={{ fontSize: 14, fontWeight: 600, marginTop: 4 }}>{t('checkout_reduction')}</div>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13, color: 'var(--color-muted-foreground)', paddingLeft: 8 }}>
                  <span>{t('checkout_reduction_products')}</span><span>{(0).toFixed(2)}</span>
                </div>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 13, color: 'var(--color-muted-foreground)', paddingLeft: 8 }}>
                  <span>{t('checkout_reduction_order')}</span><span>{(0).toFixed(2)}</span>
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
