import { Fragment, useEffect, useRef, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import {
  fetchOrderReturns, saveOrderAddress, saveOrderShipping, saveOrderMessage,
  type OrderBasketItem, type OrderAddress, type OrderPayment, type OrderPaymentCoupon, type OrderShipping, type OrderMessage, type OrderReturn,
} from './api'
import { card, inputCss, th, td, label, btnPrimary, btnGhost } from '../../shared/styles'
import { fmtDate, currentLang, fmtMoney, type T } from '../../shared/i18n'
import { AccountLink, ProductLink } from '../../shared/openAccount'
import { PackageIcon, MapPinIcon, TruckIcon, MessageSquareIcon, ChevronDownIcon, UserIcon } from '../../shared/icons'
import { DatePicker } from '../../shared/DatePicker'
import { ExpandToggle, HiddenColsRow } from '../../shared/ExpandableRow'
import { useIsNarrow } from '../../shared/useIsNarrow'

const sectionTitle = { fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 16px', display: 'flex', alignItems: 'center', gap: 6 } as const
const hint = { fontSize: 12, color: 'var(--color-muted-foreground)', marginTop: 4 } as const
const labelRow = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 } as const

// ── Tooltip d'aide (icône (i) à côté d'un champ) ────────────────────────────────
function InfoDot({ text }: { text: string }) {
  if (!text) return null
  return (
    <span title={text} style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 15, height: 15, borderRadius: 999, background: 'var(--color-muted-foreground)', color: 'var(--color-background,#fff)', fontSize: 10, fontWeight: 700, cursor: 'help', flexShrink: 0 }}>i</span>
  )
}

function MailIcon() {
  return <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ width: 12, height: 12, flexShrink: 0 }}><rect x="2" y="4" width="20" height="16" rx="2" /><path d="m2 7 10 7 10-7" /></svg>
}
function ClockIcon() {
  return <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ width: 12, height: 12, flexShrink: 0 }}><circle cx="12" cy="12" r="10" /><path d="M12 6v6l4 2" /></svg>
}

function fmtTime(value: string | null | undefined): string {
  if (!value) return ''
  const d = new Date(value.replace(' ', 'T'))
  if (isNaN(d.getTime())) return ''
  return d.toLocaleTimeString(currentLang() === 'fr' ? 'fr-FR' : 'en-GB', { hour: '2-digit', minute: '2-digit', hour12: false })
}

function dateParts(value: string | null | undefined): { day: string; month: string; key: string } {
  const d = value ? new Date(value.replace(' ', 'T')) : new Date(NaN)
  if (isNaN(d.getTime())) return { day: '--', month: '---', key: 'unknown' }
  return {
    day: String(d.getDate()).padStart(2, '0'),
    month: d.toLocaleDateString(currentLang() === 'fr' ? 'fr-FR' : 'en-GB', { month: 'short' }).toUpperCase(),
    key: `${d.getFullYear()}-${d.getMonth()}-${d.getDate()}`,
  }
}

// ── Basket ────────────────────────────────────────────────────────────────────
export function BasketTab({ basket, t }: { basket: OrderBasketItem[]; t: T }) {
  const narrow = useIsNarrow()
  const navigate = useNavigate()
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }
  const total = basket.reduce((s, i) => s + i.qty * i.priceGross, 0)
  const BASKET_COLS: { id: string; label: string; render: (row: OrderBasketItem) => import('react').ReactNode; style?: import('react').CSSProperties }[] = [
    { id: 'sku', label: t('basket_col_sku'), render: (row) => <code style={{ fontSize: 12 }}>{row.sku}</code> },
    { id: 'name', label: t('basket_col_name'), render: (row) => row.productId ? <ProductLink navigate={navigate} productId={row.productId} label={row.name || row.sku} style={{ fontWeight: 500 }}>{row.name}</ProductLink> : row.name },
    { id: 'category', label: t('basket_col_category'), render: (row) => row.category || '—' },
    { id: 'qty', label: t('basket_col_qty'), render: (row) => row.qty, style: { ...td, textAlign: 'center' } },
    { id: 'price_net', label: t('basket_col_price_net'), render: (row) => fmtMoney(row.priceNet, row.currency), style: { ...td, textAlign: 'right' } },
    { id: 'price_gross', label: t('basket_col_price_gross'), render: (row) => fmtMoney(row.priceGross, row.currency), style: { ...td, textAlign: 'right', fontWeight: 600 } },
  ]
  const visibleBasketCols = narrow ? BASKET_COLS.filter((c) => c.id === 'name') : BASKET_COLS
  const hasHiddenBasketCols = narrow && visibleBasketCols.length < BASKET_COLS.length
  const basketColSpan = visibleBasketCols.length + (hasHiddenBasketCols ? 1 : 0)
  const basketColDefs = BASKET_COLS.map((c) => ({ id: c.id, visible: visibleBasketCols.includes(c) }))
  return (
    <div style={{ ...card, padding: 28 }}>
      <h3 style={sectionTitle}><PackageIcon />{t('section_basket')}</h3>
      <div style={{ overflowX: 'auto' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr>
              {hasHiddenBasketCols && <th style={{ ...th, width: 32 }} />}
              {visibleBasketCols.map((c) => <th key={c.id} style={c.style ? { ...th, textAlign: c.style.textAlign } : th}>{c.label}</th>)}
            </tr>
          </thead>
          <tbody>
            {basket.length === 0 ? (
              <tr><td colSpan={basketColSpan} style={{ ...td, textAlign: 'center', padding: '28px 16px', color: 'var(--color-muted-foreground)' }}>{t('no_basket_items')}</td></tr>
            ) : basket.map((row) => (
              <Fragment key={row.id}>
                <tr>
                  {hasHiddenBasketCols && (
                    <td style={td}><ExpandToggle expanded={expanded.has(row.id)} onClick={() => toggleExpand(row.id)} /></td>
                  )}
                  {visibleBasketCols.map((c) => <td key={c.id} style={c.style ?? td}>{c.render(row)}</td>)}
                </tr>
                {hasHiddenBasketCols && expanded.has(row.id) && (
                  <HiddenColsRow cols={basketColDefs} labelFor={(id) => BASKET_COLS.find((c) => c.id === id)?.label ?? id} renderValue={(id) => BASKET_COLS.find((c) => c.id === id)?.render(row)} colSpan={basketColSpan} narrow={narrow} />
                )}
              </Fragment>
            ))}
          </tbody>
        </table>
      </div>
      {basket.length > 0 && (
        <div style={{ marginTop: 16, display: 'flex', justifyContent: 'flex-end', fontSize: 15, fontWeight: 700 }}>
          Total: {fmtMoney(total, basket[0]?.currency)}
        </div>
      )}
    </div>
  )
}

// ── Address editor ─────────────────────────────────────────────────────────────
function AddressEditor({ addr, onChange, t, locked }: {
  addr: Omit<OrderAddress, 'id' | 'type'>; onChange: (a: Omit<OrderAddress, 'id' | 'type'>) => void; t: T; locked: boolean
}) {
  const narrow = useIsNarrow()
  const set = <K extends keyof typeof addr>(k: K, v: typeof addr[K]) => onChange({ ...addr, [k]: v })
  const civOptions = [['', t('field_civility')], ['1', 'Mr'], ['2', 'Mrs'], ['3', 'Ms']]
  const field = (k: keyof typeof addr, lbl: string) => (
    <div>
      <label style={label}>{lbl}</label>
      <input style={inputCss} value={String(addr[k] ?? '')} disabled={locked}
        onChange={(e) => set(k, e.target.value as never)} autoComplete="off" />
    </div>
  )
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
      {field('company', t('field_company'))}
      <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '160px 1fr 1fr', gap: 12 }}>
        <div>
          <label style={label}>{t('field_civility')}</label>
          <select style={{ ...inputCss }} value={String(addr.civility ?? '')} disabled={locked}
            onChange={(e) => set('civility', Number(e.target.value) as never)}>
            {civOptions.map(([v, lbl]) => <option key={v} value={v}>{lbl}</option>)}
          </select>
        </div>
        {field('firstname', t('field_firstname'))}
        {field('name', t('field_name'))}
      </div>
      {field('middleName', t('field_middle_name'))}
      <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '140px 1fr', gap: 12 }}>
        {field('num', t('field_num'))}{field('street', t('field_street'))}
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 12 }}>
        {field('building', t('field_building'))}{field('stairs', t('field_stairs'))}
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 140px 1fr', gap: 12 }}>
        {field('city', t('field_city'))}{field('zipcode', t('field_zipcode'))}{field('country', t('field_country'))}
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 12 }}>
        {field('phoneMobile', t('field_phone_mobile'))}{field('phoneLandline', t('field_phone_landline'))}
      </div>
      {field('complementary', t('field_complementary'))}
    </div>
  )
}

const EMPTY_ADDR: Omit<OrderAddress, 'id' | 'type'> = {
  company: '', civility: 0, firstname: '', name: '', middleName: '',
  num: '', street: '', building: '', stairs: '', city: '', state: '', country: '', zipcode: '',
  phoneMobile: '', phoneLandline: '', complementary: '',
}

// ── Addresses tab — master-detail matching shared AddressEditor style ──────────
export function AddressesTab({ orderId, billing, delivery, locked, t, onSaved, can }: {
  orderId: number; billing: OrderAddress | null; delivery: OrderAddress | null;
  locked: boolean; t: T; onSaved?: () => void; can?: (cap: string) => boolean
}) {
  const narrow = useIsNarrow()
  const allow = (cap: string) => (can ? can(cap) : true)
  const [active, setActive] = useState<'billing' | 'delivery'>('billing')
  const [bAddr, setBAddr] = useState<Omit<OrderAddress, 'id' | 'type'>>(billing ?? EMPTY_ADDR)
  const [dAddr, setDAddr] = useState<Omit<OrderAddress, 'id' | 'type'>>(delivery ?? EMPTY_ADDR)
  const [saving, setSaving] = useState(false)
  const [notice, setNotice] = useState('')

  useEffect(() => { setBAddr(billing ?? EMPTY_ADDR) }, [billing])
  useEffect(() => { setDAddr(delivery ?? EMPTY_ADDR) }, [delivery])

  const save = async () => {
    setSaving(true); setNotice('')
    try {
      await Promise.all([
        saveOrderAddress(orderId, { ...bAddr, type: 1 }),
        saveOrderAddress(orderId, { ...dAddr, type: 2 }),
      ])
      setNotice(t('address_saved')); onSaved?.()
    } catch { setNotice('Error saving.') }
    finally { setSaving(false) }
  }

  const items: { key: 'billing' | 'delivery'; label: string }[] = [
    { key: 'billing',  label: 'Billing' },
    { key: 'delivery', label: 'Delivery' },
  ]
  const addr    = active === 'billing' ? bAddr : dAddr
  const setAddr = active === 'billing' ? setBAddr : setDAddr

  return (
    <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '200px 1fr', gap: 16, alignItems: 'start' }}>
      {/* Left sidebar — chips */}
      <div style={{ display: 'flex', flexDirection: narrow ? 'row' : 'column', flexWrap: narrow ? 'wrap' : 'nowrap', gap: 6 }}>
        {items.map(({ key, label: lbl }) => {
          const sel = active === key
          return (
            <div key={key} onClick={() => setActive(key)} style={{
              display: 'flex', alignItems: 'center', gap: 8, cursor: 'pointer',
              borderRadius: 8, padding: '10px 14px', fontSize: 14, fontWeight: 500,
              background: sel ? 'var(--color-primary)' : 'var(--color-muted,rgba(0,0,0,.04))',
              color: sel ? 'var(--color-primary-foreground,#fff)' : 'var(--color-foreground)',
              transition: 'background .15s',
            }}>
              <MapPinIcon />
              <span>{lbl}</span>
            </div>
          )
        })}
      </div>

      {/* Right — form card */}
      <div style={{ ...card, padding: 24 }}>
        <h3 style={{ ...sectionTitle, marginBottom: 20 }}>
          <MapPinIcon />
          {active === 'billing' ? t('section_billing') : t('section_delivery')}
        </h3>
        <AddressEditor addr={addr} onChange={setAddr} t={t} locked={locked} />
        {!locked && allow('addresses.edit') && (
          <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginTop: 20, paddingTop: 16, borderTop: '1px solid var(--color-border)' }}>
            <button style={btnPrimary} onClick={save} disabled={saving}>{saving ? '…' : t('save')}</button>
            {notice && <span style={{ fontSize: 13, color: notice.includes('Error') ? '#ef4444' : '#059669' }}>{notice}</span>}
          </div>
        )}
      </div>
    </div>
  )
}

// ── Payment tab — one collapsible-style card per payment (label/value rows),
// matching the legacy accordion layout instead of a wide data table ───────────
function PaymentCard({ p, t }: { p: OrderPayment; t: T }) {
  const fmt = (v: number) => fmtMoney(v, p.currency)
  const couponLabel = (c: OrderPaymentCoupon) => `${c.code} (${c.percentage != null ? c.percentage + '%' : fmt(c.discountValue ?? 0)}) x ${c.qtyUsed}`
  const rows: [string, string][] = [
    [t('payment_col_order'), fmt(p.orderPrice)],
    [t('payment_col_shipping'), fmt(p.shipping)],
    [t('payment_col_total'), fmt(p.total)],
    [t('payment_col_paid'), fmt(p.confirmed)],
    [t('payment_col_type'), p.paymentType || '—'],
    [t('payment_col_transac'), p.transacId || '—'],
  ]
  return (
    <div style={{ ...card, overflow: 'hidden' }}>
      <div style={{ background: 'var(--color-primary)', color: 'var(--color-primary-foreground,#fff)', padding: '10px 16px', fontWeight: 600, fontSize: 14, display: 'flex', justifyContent: 'space-between', gap: 12 }}>
        <span>{t('section_payment')}{p.paymentType ? ' — ' + p.paymentType : ''}</span>
        <span>{p.datePay ? fmtDate(p.datePay) : '—'}</span>
      </div>
      <div style={{ padding: 16, display: 'flex', flexDirection: 'column', gap: 10 }}>
        {rows.map(([lbl, value]) => (
          <div key={lbl} style={{ display: 'flex', justifyContent: 'space-between', gap: 12, fontSize: 13, paddingBottom: 8, borderBottom: '1px solid var(--color-border)' }}>
            <span style={{ color: 'var(--color-muted-foreground)' }}>{lbl}</span>
            <span style={{ textAlign: 'right' }}>{value}</span>
          </div>
        ))}
        <div style={{ display: 'flex', justifyContent: 'space-between', gap: 12, fontSize: 13 }}>
          <span style={{ color: 'var(--color-muted-foreground)' }}>{t('payment_col_coupons')}</span>
          <span style={{ textAlign: 'right' }}>
            {p.coupons.length === 0 ? '—' : p.coupons.map((c) => <div key={c.code}>{couponLabel(c)}</div>)}
          </span>
        </div>
      </div>
    </div>
  )
}

export function PaymentTab({ payments, t }: { payments: OrderPayment[]; t: T }) {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
      {payments.length === 0 ? (
        <div style={{ ...card, padding: 28, textAlign: 'center', color: 'var(--color-muted-foreground)' }}>{t('no_payments')}</div>
      ) : payments.map((p) => <PaymentCard key={p.id} p={p} t={t} />)}
    </div>
  )
}

// ── Shipping modal ─────────────────────────────────────────────────────────────
function ShippingModal({ orderId, onSaved, onClose, t }: {
  orderId: number; onSaved: (s: OrderShipping) => void; onClose: () => void; t: T
}) {
  const [form, setForm] = useState({ trackingCode: '', content: '', dateSent: '' })
  const [saving, setSaving] = useState(false)
  const [err, setErr] = useState('')

  const submit = async () => {
    if (!form.trackingCode.trim()) { setErr(t('err_tracking_required')); return }
    setSaving(true); setErr('')
    try {
      await saveOrderShipping(orderId, { ...form, dateSent: form.dateSent || new Date().toISOString().slice(0, 10) })
      onSaved({ id: Date.now(), ...form, dateSent: form.dateSent || null })
      onClose()
    } catch { setErr('Error.') }
    finally { setSaving(false) }
  }

  return (
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.5)', backdropFilter: 'blur(2px)', zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 16 }}
      onClick={onClose}>
      <div onClick={(e) => e.stopPropagation()}
        style={{ ...card, width: 480, maxWidth: '95vw', overflow: 'visible', boxShadow: '0 12px 40px rgba(0,0,0,.35)' }}>
        {/* Header */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '16px 20px', borderBottom: '1px solid var(--color-border)' }}>
          <span style={{ display: 'inline-flex', width: 30, height: 30, borderRadius: 8, alignItems: 'center', justifyContent: 'center', background: 'color-mix(in srgb, var(--color-primary) 14%, transparent)', color: 'var(--color-primary)', flexShrink: 0 }}>
            <TruckIcon />
          </span>
          <span style={{ fontWeight: 600, fontSize: 15, color: 'var(--color-foreground)' }}>{t('tab_shipping')}</span>
        </div>
        <div style={{ padding: 20 }}>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 14, marginBottom: 18 }}>
            <div>
              <label style={{ display: 'block', fontWeight: 600, fontSize: 13, color: 'var(--color-foreground)', marginBottom: 5 }}>{t('field_tracking')} *</label>
              <input style={{ ...inputCss, width: '100%', boxSizing: 'border-box', border: err ? '1px solid #ef4444' : inputCss.border }}
                value={form.trackingCode} onChange={(e) => setForm({ ...form, trackingCode: e.target.value })} autoComplete="off" />
              {err && <div style={{ color: '#ef4444', fontSize: 12, marginTop: 4 }}>{err}</div>}
            </div>
            <div>
              <label style={{ display: 'block', fontWeight: 600, fontSize: 13, color: 'var(--color-foreground)', marginBottom: 5 }}>{t('field_content')} *</label>
              <textarea rows={4} style={{ ...inputCss, width: '100%', boxSizing: 'border-box', height: 'auto', minHeight: 90, padding: '8px 12px', resize: 'vertical', fontFamily: 'inherit' }}
                value={form.content} onChange={(e) => setForm({ ...form, content: e.target.value })} />
            </div>
            <div>
              <div style={{ ...labelRow, marginBottom: 5 }}>
                <label style={{ fontWeight: 600, fontSize: 13, color: 'var(--color-foreground)' }}>{t('field_date_sent')} *</label>
                <InfoDot text={t('tip_date_sent')} />
              </div>
              <DatePicker t={t} value={form.dateSent} onChange={(v) => setForm({ ...form, dateSent: v })} />
            </div>
          </div>
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, paddingTop: 14, borderTop: '1px solid var(--color-border)' }}>
            <button onClick={onClose} style={{ ...btnGhost }}>{t('cancel')}</button>
            <button onClick={submit} disabled={saving} style={{ ...btnPrimary, opacity: saving ? 0.6 : 1 }}>{saving ? '…' : t('save')}</button>
          </div>
        </div>
      </div>
    </div>
  )
}

// ── Shipping tab ──────────────────────────────────────────────────────────────
export function ShippingTab({ orderId, shippings, locked, t, onSaved, can }: {
  orderId: number; shippings: OrderShipping[]; locked: boolean; t: T; onSaved?: () => void; can?: (cap: string) => boolean
}) {
  const allow = (cap: string) => (can ? can(cap) : true)
  const [list, setList]         = useState(shippings)
  const [showModal, setShowModal] = useState(false)
  const [openId, setOpenId]     = useState<number | null>(shippings[0]?.id ?? null)
  useEffect(() => { setList(shippings); setOpenId(shippings[0]?.id ?? null) }, [shippings])

  const fieldBox = { ...inputCss, height: 'auto', minHeight: 40, display: 'flex', alignItems: 'center', background: 'var(--color-muted,var(--color-secondary))', color: 'var(--color-muted-foreground)' } as const

  return (
    <div style={{ ...card, padding: 28 }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 20 }}>
        <h3 style={{ ...sectionTitle, margin: 0 }}><TruckIcon />{t('section_shipping')}</h3>
        {!locked && allow('shipping.create') && (
          <button onClick={() => setShowModal(true)}
            style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '6px 14px', fontSize: 13, fontWeight: 600, border: '1.5px solid var(--color-primary)', borderRadius: 6, background: 'transparent', color: 'var(--color-primary)', cursor: 'pointer' }}>
            + {t('shipping_add')}
          </button>
        )}
      </div>
      {list.length === 0 ? (
        <div style={{ textAlign: 'center', padding: '28px', color: 'var(--color-muted-foreground)', fontSize: 14 }}>{t('no_shippings')}</div>
      ) : (
        <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
          {list.map((s) => {
            const isOpen = openId === s.id
            return (
              <div key={s.id} style={{ borderRadius: 8, overflow: 'hidden', border: '1px solid var(--color-border)' }}>
                <button
                  onClick={() => setOpenId(isOpen ? null : s.id)}
                  style={{
                    width: '100%', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 10,
                    padding: '10px 16px', background: 'var(--color-primary)', color: 'var(--color-primary-foreground,#fff)',
                    border: 0, cursor: 'pointer', fontSize: 13, fontWeight: 600,
                  }}
                >
                  <span style={{ display: 'inline-flex', alignItems: 'center', gap: 8 }}>
                    <span style={{ display: 'inline-flex', transform: isOpen ? 'none' : 'rotate(-90deg)', transition: 'transform .15s' }}><ChevronDownIcon /></span>
                    {s.trackingCode}
                  </span>
                  <span style={{ fontWeight: 400, opacity: .9 }}>{s.dateSent ? fmtDate(s.dateSent) : '—'}</span>
                </button>
                {isOpen && (
                  <div style={{ padding: '18px 16px', display: 'flex', flexDirection: 'column', gap: 14, background: 'var(--color-card)' }}>
                    <div>
                      <label style={label}>{t('field_tracking')}</label>
                      <div style={fieldBox}>{s.trackingCode}</div>
                    </div>
                    <div>
                      <label style={label}>{t('field_content')}</label>
                      <div style={{ ...fieldBox, minHeight: 70, alignItems: 'flex-start', padding: '8px 12px', whiteSpace: 'pre-wrap' }}>{s.content || '—'}</div>
                    </div>
                    <div>
                      <label style={label}>{t('field_date_sent')}</label>
                      <div style={fieldBox}>{s.dateSent ? fmtDate(s.dateSent) : '—'}</div>
                    </div>
                  </div>
                )}
              </div>
            )
          })}
        </div>
      )}
      {showModal && (
        <ShippingModal orderId={orderId} t={t}
          onSaved={(s) => { setList((prev) => [s, ...prev]); setOpenId(s.id); onSaved?.() }}
          onClose={() => setShowModal(false)} />
      )}
    </div>
  )
}

// ── Messages tab ──────────────────────────────────────────────────────────────
export function MessagesTab({ orderId, messages, t, onSaved, can }: {
  orderId: number; messages: OrderMessage[]; t: T; onSaved?: () => void; can?: (cap: string) => boolean
}) {
  const navigate = useNavigate()
  const narrow = useIsNarrow()
  const allow = (cap: string) => (can ? can(cap) : true)
  const [list, setList] = useState(messages)
  const [text, setText] = useState('')
  const [sending, setSending] = useState(false)
  const [err, setErr] = useState('')
  const textRef = useRef<HTMLTextAreaElement>(null)
  useEffect(() => { setList(messages) }, [messages])

  const send = async () => {
    if (!text.trim()) { setErr(t('err_message_required')); return }
    setSending(true); setErr('')
    try {
      const res = await saveOrderMessage(orderId, text.trim())
      setList((prev) => [{ id: res.id, message: text.trim(), userId: 0, userName: 'Admin', userEmail: '', clientId: 0, dateCreation: res.dateCreation, fromAdmin: true, type: 'MSG' }, ...prev])
      setText(''); onSaved?.()
    } catch { setErr('Error.') }
    finally { setSending(false) }
  }

  const groups: { key: string; day: string; month: string; items: OrderMessage[] }[] = []
  for (const m of list) {
    const dp = dateParts(m.dateCreation)
    const last = groups[groups.length - 1]
    if (last && last.key === dp.key) last.items.push(m)
    else groups.push({ key: dp.key, day: dp.day, month: dp.month, items: [m] })
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
      <div style={{ ...card, padding: 28 }}>
        <h3 style={sectionTitle}><MessageSquareIcon />{t('section_messages')}</h3>
        <textarea ref={textRef} rows={4}
          style={{ ...inputCss, resize: 'vertical', fontFamily: 'inherit', lineHeight: 1.5, borderColor: err ? '#ef4444' : undefined }}
          placeholder={t('msg_placeholder')} value={text} onChange={(e) => setText(e.target.value)} />
        {err && <div style={{ color: '#ef4444', fontSize: 12, marginTop: 4 }}>{err}</div>}
        {allow('messages.create') && (
          <div style={{ marginTop: 12 }}>
            <button style={btnPrimary} onClick={send} disabled={sending}>{sending ? '…' : t('msg_send')}</button>
          </div>
        )}
      </div>
      <div style={{ ...card, padding: 28 }}>
        {list.length === 0 ? (
          <p style={{ color: 'var(--color-muted-foreground)', fontSize: 14 }}>{t('msg_empty')}</p>
        ) : (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>
            {groups.map((g) => (
              <div key={g.key} style={{ position: 'relative' }}>
                <div style={{ border: '1px solid var(--color-border)', borderRadius: 6, padding: '4px 8px', textAlign: 'center', lineHeight: 1.2, width: 40, marginLeft: narrow ? 0 : 46 }}>
                  <div style={{ fontSize: 13, fontWeight: 700 }}>{g.day}</div>
                  <div style={{ fontSize: 10, fontWeight: 600, color: 'var(--color-muted-foreground)' }}>{g.month}</div>
                </div>
                {/* Rail verticale reliant la pastille de date aux pastilles de message — n'a de sens
                    que quand la rail à gauche de chaque message existe (desktop) ; sur narrow cette
                    rail disparaît (voir plus bas), la ligne n'aurait donc plus rien à relier. */}
                {!narrow && <div style={{ position: 'absolute', left: 66, top: 38, bottom: 0, width: 2, background: 'var(--color-primary)' }} />}
                <div style={{ display: 'flex', flexDirection: 'column', gap: 20, marginTop: 12 }}>
                  {g.items.map((m) => {
                    // Distinction visuelle expéditeur : ADMIN (bleu) vs CLIENT (vert).
                    const isAdmin = !!m.fromAdmin
                    const rColor = isAdmin ? '#2563eb' : '#16a34a'
                    const rLabel = isAdmin ? t('msg_author_admin') : t('msg_author_client')
                    return (
                    <div key={m.id} style={{ display: 'flex', position: 'relative' }}>
                      {/* Rail gauche (étiquette + pastille + trait de liaison) : redondante sur narrow
                          avec le badge déjà affiché dans l'en-tête de la bulle (nom + rLabel un peu
                          plus bas) — un rail large fixe (150px) y laisserait trop peu de place pour le
                          message sur un écran étroit (Mantis #10850). */}
                      {!narrow && <div style={{ display: 'flex', alignItems: 'center', width: 150, flexShrink: 0, paddingTop: 12 }}>
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, width: 58, color: rColor, fontSize: 10, fontWeight: 700, letterSpacing: '.03em', whiteSpace: 'nowrap' }}>
                          {rLabel}<MessageSquareIcon />
                        </span>
                        <div style={{ width: 9, height: 9, background: rColor, flexShrink: 0, marginLeft: 4 }} />
                        <div style={{ flex: 1, height: 2, background: rColor }} />
                      </div>}
                      <div style={{ flex: 1, minWidth: 0, borderRadius: 8, padding: 14, borderLeft: `3px solid ${rColor}`,
                        border: `1px solid color-mix(in srgb, ${rColor} 30%, var(--color-border))`, borderLeftWidth: 3,
                        background: `color-mix(in srgb, ${rColor} 6%, transparent)` }}>
                        <div style={{ display: 'flex', gap: 10, alignItems: 'flex-start' }}>
                          <div style={{ width: 34, height: 34, borderRadius: '50%', background: `color-mix(in srgb, ${rColor} 16%, transparent)`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, color: rColor }}>
                            <UserIcon />
                          </div>
                          <div style={{ display: 'flex', flexDirection: 'column', gap: 3 }}>
                            <span style={{ display: 'inline-flex', alignItems: 'center', gap: 8, fontWeight: 600, fontSize: 13, color: 'var(--color-foreground)' }}>
                              {(!isAdmin && m.clientId) ? <AccountLink navigate={navigate} clientId={m.clientId} label={m.userName} style={{ color: rColor, fontWeight: 600 }}>{m.userName}</AccountLink> : m.userName}
                              <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '.03em', color: rColor, background: `color-mix(in srgb, ${rColor} 14%, transparent)`, border: `1px solid color-mix(in srgb, ${rColor} 45%, transparent)`, borderRadius: 999, padding: '1px 8px' }}>{rLabel}</span>
                              {m.type === 'RETURN' && <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '.03em', color: '#b45309', background: 'color-mix(in srgb, #f59e0b 16%, transparent)', border: '1px solid color-mix(in srgb, #f59e0b 45%, transparent)', borderRadius: 999, padding: '1px 8px' }}>{t('msg_tag_return')}</span>}
                            </span>
                            {m.userEmail && <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, color: 'var(--color-muted-foreground)' }}><MailIcon />{m.userEmail}</span>}
                            <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, color: 'var(--color-muted-foreground)' }}><ClockIcon />{fmtTime(m.dateCreation)}</span>
                          </div>
                        </div>
                        <div style={{ marginTop: 10, background: 'var(--color-card)', border: '1px solid var(--color-border)', borderRadius: 6, padding: '8px 12px', fontSize: 14, lineHeight: 1.6, whiteSpace: 'pre-wrap' }}>{m.message}</div>
                      </div>
                    </div>
                    )
                  })}
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}

// ── Returns tab ───────────────────────────────────────────────────────────────
export function ReturnsTab({ orderId, t }: { orderId: number; t: T }) {
  const narrow = useIsNarrow()
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }
  const [returns, setReturns] = useState<OrderReturn[]>([])
  useEffect(() => { fetchOrderReturns(orderId).then(setReturns).catch(() => null) }, [orderId])
  const RETURN_COLS: { id: string; label: string; render: (r: OrderReturn) => import('react').ReactNode; style?: import('react').CSSProperties }[] = [
    { id: 'id', label: t('return_col_id'), render: (r) => `#${r.returnId}`, style: { ...td, color: 'var(--color-muted-foreground)' } },
    { id: 'sku', label: t('return_col_sku'), render: (r) => <code style={{ fontSize: 12 }}>{r.sku || '—'}</code> },
    { id: 'product', label: t('return_col_product'), render: (r) => r.product || '—' },
    { id: 'qty', label: t('return_col_qty'), render: (r) => r.qty, style: { ...td, textAlign: 'center' } },
    { id: 'date', label: t('return_col_date'), render: (r) => r.date ? fmtDate(r.date) : '—' },
  ]
  const visibleReturnCols = narrow ? RETURN_COLS.filter((c) => c.id === 'product') : RETURN_COLS
  const hasHiddenReturnCols = narrow && visibleReturnCols.length < RETURN_COLS.length
  const returnColSpan = visibleReturnCols.length + (hasHiddenReturnCols ? 1 : 0)
  const returnColDefs = RETURN_COLS.map((c) => ({ id: c.id, visible: visibleReturnCols.includes(c) }))

  return (
    <div style={{ ...card, padding: 28 }}>
      <h3 style={sectionTitle}>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ width: 14, height: 14 }}><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        {t('section_returns')}
      </h3>
      <div style={{ overflowX: 'auto' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr>
              {hasHiddenReturnCols && <th style={{ ...th, width: 32 }} />}
              {visibleReturnCols.map((c) => <th key={c.id} style={c.style ? { ...th, textAlign: c.style.textAlign } : th}>{c.label}</th>)}
            </tr>
          </thead>
          <tbody>
            {returns.length === 0 ? (
              <tr><td colSpan={returnColSpan} style={{ ...td, textAlign: 'center', padding: '28px', color: 'var(--color-muted-foreground)' }}>{t('no_returns')}</td></tr>
            ) : returns.map((r) => (
              <Fragment key={r.detailId}>
                <tr>
                  {hasHiddenReturnCols && (
                    <td style={td}><ExpandToggle expanded={expanded.has(r.detailId)} onClick={() => toggleExpand(r.detailId)} /></td>
                  )}
                  {visibleReturnCols.map((c) => <td key={c.id} style={c.style ?? td}>{c.render(r)}</td>)}
                </tr>
                {hasHiddenReturnCols && expanded.has(r.detailId) && (
                  <HiddenColsRow cols={returnColDefs} labelFor={(id) => RETURN_COLS.find((c) => c.id === id)?.label ?? id} renderValue={(id) => RETURN_COLS.find((c) => c.id === id)?.render(r)} colSpan={returnColSpan} narrow={narrow} />
                )}
              </Fragment>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}
