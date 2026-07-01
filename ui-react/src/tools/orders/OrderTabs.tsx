import { useEffect, useRef, useState } from 'react'
import {
  fetchOrderReturns, saveOrderAddress, saveOrderShipping, saveOrderMessage,
  type OrderBasketItem, type OrderAddress, type OrderPayment, type OrderShipping, type OrderMessage, type OrderReturn,
} from './api'
import { card, inputCss, th, td, label, btnPrimary, btnGhost } from '../../shared/styles'
import { fmtDate, type T } from '../../shared/i18n'
import { PackageIcon, MapPinIcon, CreditCardIcon, TruckIcon, MessageSquareIcon } from '../../shared/icons'

const sectionTitle = { fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 16px', display: 'flex', alignItems: 'center', gap: 6 } as const
const hint = { fontSize: 12, color: 'var(--color-muted-foreground)', marginTop: 4 } as const

// ── Basket ────────────────────────────────────────────────────────────────────
export function BasketTab({ basket, t }: { basket: OrderBasketItem[]; t: T }) {
  const fmtPrice = (v: number) => v.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
  const total = basket.reduce((s, i) => s + i.qty * i.priceGross, 0)
  const headers = [t('basket_col_sku'), t('basket_col_name'), t('basket_col_category'), t('basket_col_qty'), t('basket_col_price_net'), t('basket_col_price_gross'), t('basket_col_currency')]
  return (
    <div style={{ ...card, padding: 28 }}>
      <h3 style={sectionTitle}><PackageIcon />{t('section_basket')}</h3>
      <table style={{ width: '100%', borderCollapse: 'collapse' }}>
        <thead>
          <tr>{headers.map((h) => <th key={h} style={th}>{h}</th>)}</tr>
        </thead>
        <tbody>
          {basket.length === 0 ? (
            <tr><td colSpan={headers.length} style={{ ...td, textAlign: 'center', padding: '28px 16px', color: 'var(--color-muted-foreground)' }}>{t('no_basket_items')}</td></tr>
          ) : basket.map((row) => (
            <tr key={row.id}>
              <td style={td}><code style={{ fontSize: 12 }}>{row.sku}</code></td>
              <td style={td}>{row.name}</td>
              <td style={td}>{row.category || '—'}</td>
              <td style={{ ...td, textAlign: 'center' }}>{row.qty}</td>
              <td style={{ ...td, textAlign: 'right' }}>{fmtPrice(row.priceNet)}</td>
              <td style={{ ...td, textAlign: 'right', fontWeight: 600 }}>{fmtPrice(row.priceGross)}</td>
              <td style={td}>{row.currency}</td>
            </tr>
          ))}
        </tbody>
      </table>
      {basket.length > 0 && (
        <div style={{ marginTop: 16, display: 'flex', justifyContent: 'flex-end', fontSize: 15, fontWeight: 700 }}>
          Total: {fmtPrice(total)} {basket[0]?.currency ?? ''}
        </div>
      )}
    </div>
  )
}

// ── Address editor ─────────────────────────────────────────────────────────────
function AddressEditor({ addr, onChange, t, locked }: {
  addr: Omit<OrderAddress, 'id' | 'type'>; onChange: (a: Omit<OrderAddress, 'id' | 'type'>) => void; t: T; locked: boolean
}) {
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
      <div style={{ display: 'grid', gridTemplateColumns: '160px 1fr 1fr', gap: 12 }}>
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
      <div style={{ display: 'grid', gridTemplateColumns: '140px 1fr', gap: 12 }}>
        {field('num', t('field_num'))}{field('street', t('field_street'))}
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
        {field('building', t('field_building'))}{field('stairs', t('field_stairs'))}
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 140px 1fr', gap: 12 }}>
        {field('city', t('field_city'))}{field('zipcode', t('field_zipcode'))}{field('country', t('field_country'))}
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
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
export function AddressesTab({ orderId, billing, delivery, locked, t, onSaved }: {
  orderId: number; billing: OrderAddress | null; delivery: OrderAddress | null;
  locked: boolean; t: T; onSaved?: () => void
}) {
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
    <div style={{ display: 'grid', gridTemplateColumns: '200px 1fr', gap: 16, alignItems: 'start' }}>
      {/* Left sidebar — chips */}
      <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
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
        {!locked && (
          <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginTop: 20, paddingTop: 16, borderTop: '1px solid var(--color-border)' }}>
            <button style={btnPrimary} onClick={save} disabled={saving}>{saving ? '…' : t('save')}</button>
            {notice && <span style={{ fontSize: 13, color: notice.includes('Error') ? '#ef4444' : '#059669' }}>{notice}</span>}
          </div>
        )}
      </div>
    </div>
  )
}

// ── Payment tab ───────────────────────────────────────────────────────────────
export function PaymentTab({ payments, t }: { payments: OrderPayment[]; t: T }) {
  const fmt = (v: number) => v.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
  const headers = [t('payment_col_type'), t('payment_col_transac'), t('payment_col_total'), t('payment_col_order'), t('payment_col_shipping'), t('payment_col_currency'), t('payment_col_date')]
  return (
    <div style={{ ...card, padding: 28 }}>
      <h3 style={sectionTitle}><CreditCardIcon />{t('section_payment')}</h3>
      <table style={{ width: '100%', borderCollapse: 'collapse' }}>
        <thead><tr>{headers.map((h) => <th key={h} style={th}>{h}</th>)}</tr></thead>
        <tbody>
          {payments.length === 0 ? (
            <tr><td colSpan={headers.length} style={{ ...td, textAlign: 'center', padding: '28px', color: 'var(--color-muted-foreground)' }}>{t('no_payments')}</td></tr>
          ) : payments.map((p) => (
            <tr key={p.id}>
              <td style={td}>{p.paymentType || '—'}</td>
              <td style={td}><code style={{ fontSize: 12 }}>{p.transacId || '—'}</code></td>
              <td style={{ ...td, textAlign: 'right', fontWeight: 700 }}>{fmt(p.total)}</td>
              <td style={{ ...td, textAlign: 'right' }}>{fmt(p.orderPrice)}</td>
              <td style={{ ...td, textAlign: 'right' }}>{fmt(p.shipping)}</td>
              <td style={td}>{p.currency}</td>
              <td style={td}>{p.datePay ? fmtDate(p.datePay) : '—'}</td>
            </tr>
          ))}
        </tbody>
      </table>
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
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.55)', zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
      <div style={{ background: '#fff', borderRadius: 6, width: 520, maxWidth: '95vw', overflow: 'hidden', boxShadow: '0 8px 40px rgba(0,0,0,.3)', color: '#333' }}>
        <div style={{ background: '#337ab7', padding: '12px 18px', display: 'flex', alignItems: 'center', gap: 8 }}>
          <span style={{ background: '#d9534f', color: '#fff', borderRadius: 4, padding: '2px 8px', fontWeight: 700, fontSize: 15 }}>+</span>
          <span style={{ color: '#fff', fontWeight: 600, fontSize: 15 }}>{t('tab_shipping')}</span>
        </div>
        <div style={{ padding: '20px 22px 18px' }}>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 14, marginBottom: 18 }}>
            <div>
              <label style={{ display: 'block', fontWeight: 700, fontSize: 13, color: '#333', marginBottom: 4 }}>{t('field_tracking')} *</label>
              <input style={{ width: '100%', boxSizing: 'border-box', padding: '7px 10px', fontSize: 13, border: `1px solid ${err ? '#d9534f' : '#ccc'}`, borderRadius: 4, background: '#fff', color: '#333' }}
                value={form.trackingCode} onChange={(e) => setForm({ ...form, trackingCode: e.target.value })} autoComplete="off" />
              {err && <div style={{ color: '#d9534f', fontSize: 12, marginTop: 3 }}>{err}</div>}
            </div>
            <div>
              <label style={{ display: 'block', fontWeight: 700, fontSize: 13, color: '#333', marginBottom: 4 }}>{t('field_content')} *</label>
              <textarea rows={4} style={{ width: '100%', boxSizing: 'border-box', padding: '7px 10px', fontSize: 13, border: '1px solid #ccc', borderRadius: 4, background: '#fff', color: '#333', resize: 'vertical', fontFamily: 'inherit' }}
                value={form.content} onChange={(e) => setForm({ ...form, content: e.target.value })} />
            </div>
            <div>
              <label style={{ display: 'block', fontWeight: 700, fontSize: 13, color: '#333', marginBottom: 4 }}>{t('field_date_sent')} *</label>
              <input type="date" style={{ width: '100%', boxSizing: 'border-box', padding: '7px 10px', fontSize: 13, border: '1px solid #ccc', borderRadius: 4, background: '#fff', color: '#333' }}
                value={form.dateSent} onChange={(e) => setForm({ ...form, dateSent: e.target.value })} />
            </div>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', paddingTop: 6, borderTop: '1px solid #eee' }}>
            <button onClick={onClose} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '6px 18px', fontSize: 13, borderRadius: 4, border: '1px solid #d9534f', background: '#fff', color: '#d9534f', cursor: 'pointer', fontWeight: 500 }}>
              ✕ {t('cancel')}
            </button>
            <button onClick={submit} disabled={saving} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '6px 18px', fontSize: 13, borderRadius: 4, border: '1px solid #5cb85c', background: '#fff', color: '#5cb85c', cursor: 'pointer', fontWeight: 500, opacity: saving ? 0.6 : 1 }}>
              💾 {saving ? '…' : t('save')}
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}

// ── Shipping tab ──────────────────────────────────────────────────────────────
export function ShippingTab({ orderId, shippings, locked, t, onSaved }: {
  orderId: number; shippings: OrderShipping[]; locked: boolean; t: T; onSaved?: () => void
}) {
  const [list, setList]         = useState(shippings)
  const [showModal, setShowModal] = useState(false)
  useEffect(() => { setList(shippings) }, [shippings])

  const headers = [t('shipping_col_tracking'), t('shipping_col_content'), t('shipping_col_date')]
  return (
    <div style={{ ...card, padding: 28 }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 20 }}>
        <h3 style={{ ...sectionTitle, margin: 0 }}><TruckIcon />{t('section_shipping')}</h3>
        {!locked && (
          <button onClick={() => setShowModal(true)}
            style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '6px 14px', fontSize: 13, fontWeight: 600, border: '1.5px solid #337ab7', borderRadius: 6, background: 'transparent', color: '#337ab7', cursor: 'pointer' }}>
            + {t('shipping_add')}
          </button>
        )}
      </div>
      <table style={{ width: '100%', borderCollapse: 'collapse' }}>
        <thead><tr>{headers.map((h) => <th key={h} style={th}>{h}</th>)}</tr></thead>
        <tbody>
          {list.length === 0 ? (
            <tr><td colSpan={3} style={{ ...td, textAlign: 'center', padding: '28px', color: 'var(--color-muted-foreground)' }}>{t('no_shippings')}</td></tr>
          ) : list.map((s) => (
            <tr key={s.id}>
              <td style={td}><code style={{ fontSize: 12 }}>{s.trackingCode}</code></td>
              <td style={td}>{s.content || '—'}</td>
              <td style={td}>{s.dateSent ? fmtDate(s.dateSent) : '—'}</td>
            </tr>
          ))}
        </tbody>
      </table>
      {showModal && (
        <ShippingModal orderId={orderId} t={t}
          onSaved={(s) => { setList((prev) => [s, ...prev]); onSaved?.() }}
          onClose={() => setShowModal(false)} />
      )}
    </div>
  )
}

// ── Messages tab ──────────────────────────────────────────────────────────────
export function MessagesTab({ orderId, messages, t, onSaved }: {
  orderId: number; messages: OrderMessage[]; t: T; onSaved?: () => void
}) {
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
      setList((prev) => [{ id: res.id, message: text.trim(), userId: 0, userName: 'Admin', clientId: 0, dateCreation: res.dateCreation }, ...prev])
      setText(''); onSaved?.()
    } catch { setErr('Error.') }
    finally { setSending(false) }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
      <div style={{ ...card, padding: 28 }}>
        <h3 style={sectionTitle}><MessageSquareIcon />{t('section_messages')}</h3>
        <textarea ref={textRef} rows={4}
          style={{ ...inputCss, resize: 'vertical', fontFamily: 'inherit', lineHeight: 1.5, borderColor: err ? '#ef4444' : undefined }}
          placeholder={t('msg_placeholder')} value={text} onChange={(e) => setText(e.target.value)} />
        {err && <div style={{ color: '#ef4444', fontSize: 12, marginTop: 4 }}>{err}</div>}
        <div style={{ marginTop: 12 }}>
          <button style={btnPrimary} onClick={send} disabled={sending}>{sending ? '…' : t('msg_send')}</button>
        </div>
      </div>
      <div style={{ ...card, padding: 28 }}>
        {list.length === 0 ? (
          <p style={{ color: 'var(--color-muted-foreground)', fontSize: 14 }}>{t('msg_empty')}</p>
        ) : (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
            {list.map((m) => (
              <div key={m.id} style={{ borderBottom: '1px solid var(--color-border)', paddingBottom: 16 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 6 }}>
                  <span style={{ fontWeight: 600, fontSize: 13 }}>{m.userName}</span>
                  <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>{m.dateCreation ? fmtDate(m.dateCreation) : ''}</span>
                </div>
                <p style={{ margin: 0, fontSize: 14, lineHeight: 1.6, whiteSpace: 'pre-wrap' }}>{m.message}</p>
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
  const [returns, setReturns] = useState<OrderReturn[]>([])
  useEffect(() => { fetchOrderReturns(orderId).then(setReturns).catch(() => null) }, [orderId])
  const headers = [t('return_col_id'), t('return_col_sku'), t('return_col_product'), t('return_col_qty'), t('return_col_date')]

  return (
    <div style={{ ...card, padding: 28 }}>
      <h3 style={sectionTitle}>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ width: 14, height: 14 }}><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        {t('section_returns')}
      </h3>
      <table style={{ width: '100%', borderCollapse: 'collapse' }}>
        <thead><tr>{headers.map((h) => <th key={h} style={th}>{h}</th>)}</tr></thead>
        <tbody>
          {returns.length === 0 ? (
            <tr><td colSpan={headers.length} style={{ ...td, textAlign: 'center', padding: '28px', color: 'var(--color-muted-foreground)' }}>{t('no_returns')}</td></tr>
          ) : returns.map((r) => (
            <tr key={r.detailId}>
              <td style={{ ...td, color: 'var(--color-muted-foreground)' }}>#{r.returnId}</td>
              <td style={td}><code style={{ fontSize: 12 }}>{r.sku || '—'}</code></td>
              <td style={td}>{r.product || '—'}</td>
              <td style={{ ...td, textAlign: 'center' }}>{r.qty}</td>
              <td style={td}>{r.date ? fmtDate(r.date) : '—'}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}
