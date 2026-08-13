import { Fragment, useEffect, useRef, useState } from 'react'
import { makeT } from '../../../shared/i18n'
import { DICT } from '../dict'
import {
  fetchCheckoutProducts, fetchCheckoutVariants, fetchCheckoutBasket,
  checkoutSetCountry, checkoutBasketAdd, checkoutBasketSetQty, checkoutBasketRemove,
  type CheckoutProduct, type CheckoutVariant, type CheckoutBasketLine,
} from '../api'
import { fetchCountries, type CountryItem } from '../../countries/api'
import { card, inputCss, btnPrimary, btnGhost, iconBtn, th, td } from '../../../shared/styles'
import { ChevronDownIcon, TrashIcon } from '../../../shared/icons'
import { notify } from '../../../shared/notify'
import { useIsNarrow } from '../../../shared/useIsNarrow'
import type { CSSProperties } from 'react'

const qtyBtn: CSSProperties = { ...btnGhost, width: 24, height: 24, padding: 0, justifyContent: 'center' }

export function WizardProductsStep({ countryId, onBack, onNext }: {
  countryId: number | null; onBack: () => void; onNext: (countryId: number) => void
}) {
  const t = makeT(DICT)
  const narrow = useIsNarrow()
  const [countries, setCountries] = useState<CountryItem[]>([])
  const [country, setCountry] = useState<number | null>(countryId)
  const [products, setProducts] = useState<CheckoutProduct[]>([])
  const [page, setPage] = useState(1)
  const [total, setTotal] = useState(0)
  const [searchInput, setSearchInput] = useState('')
  const [search, setSearch] = useState('')
  const [loading, setLoading] = useState(false)
  const [expanded, setExpanded] = useState<number | null>(null)
  const [variants, setVariants] = useState<CheckoutVariant[]>([])
  const [variantsLoading, setVariantsLoading] = useState(false)
  const [basket, setBasket] = useState<CheckoutBasketLine[]>([])
  const [adding, setAdding] = useState<number | null>(null)
  // Lets typing a big value (100, 1000) directly instead of only +/- one at a time (Mantis
  // 0010749) — draft text kept locally while editing, committed (one API call) on blur/Enter.
  const [qtyDraft, setQtyDraft] = useState<Record<number, string>>({})
  // Debounce/sequence guard for setQty's network call (see below) — rapid +/- clicks each fired
  // their own request whose responses could arrive out of order and clobber a newer quantity
  // with a stale one (visible as the number flickering up/down).
  const qtyTimer = useRef<Record<number, ReturnType<typeof setTimeout>>>({})
  const qtySeq = useRef<Record<number, number>>({})

  useEffect(() => { fetchCountries({ status: 1, limit: 200 }).then((r) => setCountries(r.items)).catch(() => null) }, [])
  useEffect(() => { refreshBasket() }, [])

  useEffect(() => {
    if (!country) return
    setLoading(true)
    fetchCheckoutProducts({ page, search }).then((r) => { setProducts(r.items); setTotal(r.total) }).catch(() => null).finally(() => setLoading(false))
  }, [country, page, search])

  function refreshBasket() {
    fetchCheckoutBasket().then((r) => setBasket(r.items)).catch(() => null)
  }

  async function onCountryChange(id: number) {
    try {
      await checkoutSetCountry(id)
      setCountry(id)
      setExpanded(null)
      refreshBasket()
    } catch (e) { notify('ko', t('checkout_step_products'), e instanceof Error ? e.message : 'Error') }
  }

  async function toggleExpand(p: CheckoutProduct) {
    if (expanded === p.id) { setExpanded(null); return }
    setExpanded(p.id)
    setVariantsLoading(true)
    try { const r = await fetchCheckoutVariants(p.id); setVariants(r.items) }
    catch { setVariants([]) }
    finally { setVariantsLoading(false) }
  }

  async function add(v: CheckoutVariant) {
    if (adding) return
    setAdding(v.id)
    // `extra` (when a module added this row) rides along so the write lands on ITS line.
    try { const r = await checkoutBasketAdd(v.id, 1, v.extra); setBasket(r.items); notify('ok', t('checkout_step_products'), t('checkout_added_to_basket', { sku: v.sku })) }
    catch (e) { notify('ko', t('checkout_step_products'), e instanceof Error ? e.message : 'Error') }
    finally { setAdding(null) }
  }

  // Keyed on the basket ROW, not the variant: a module may keep several lines for the same
  // variant (different `extra`), which shared drafts/timers would otherwise mix up.
  function setQty(line: CheckoutBasketLine, qty: number) {
    // Server clamps to available stock too (source of truth) — capping here just avoids a
    // round-trip flicker when the typed value is already known to be over the limit.
    const key = line.lineId
    const capped = line.stock != null ? Math.min(qty, line.stock) : qty
    if (capped < qty) notify('ko', t('checkout_step_products'), t('checkout_qty_capped', { n: capped }))
    // Optimistic preview: the qty/line total update instantly instead of waiting on the round-trip.
    if (capped > 0) {
      setBasket((p) => p.map((b) => b.lineId === key
        ? { ...b, quantity: capped, lineTotal: b.price != null ? b.price * capped : b.lineTotal }
        : b))
    }
    // Debounce the actual request and only apply whichever response corresponds to the LAST
    // request issued for this line — rapid +/- clicks each used to fire their own request, and
    // out-of-order responses could clobber a newer quantity with a stale one.
    if (qtyTimer.current[key]) clearTimeout(qtyTimer.current[key])
    const seq = (qtySeq.current[key] = (qtySeq.current[key] ?? 0) + 1)
    qtyTimer.current[key] = setTimeout(async () => {
      try {
        const r = capped <= 0 ? await checkoutBasketRemove(line.variantId, line.extra) : await checkoutBasketSetQty(line.variantId, capped, line.extra)
        if (qtySeq.current[key] === seq) setBasket(r.items)
      } catch (e) {
        if (qtySeq.current[key] === seq) {
          notify('ko', t('checkout_step_products'), e instanceof Error ? e.message : 'Error')
          refreshBasket()
        }
      }
    }, 300)
  }

  function commitQtyDraft(line: CheckoutBasketLine, raw: string) {
    setQtyDraft((d) => { const c = { ...d }; delete c[line.lineId]; return c })
    const n = parseInt(raw, 10)
    if (!isNaN(n)) setQty(line, n)
  }

  function next() {
    if (!country) { notify('ko', t('checkout_step_products'), t('checkout_country_required')); return }
    if (basket.length === 0) { notify('ko', t('checkout_step_products'), t('checkout_basket_empty')); return }
    onNext(country)
  }

  const totalPages = Math.max(1, Math.ceil(total / 25))
  const basketTotal = basket.reduce((s, l) => s + (l.lineTotal ?? 0), 0)

  return (
    <div>
      <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 4px' }}>{t('checkout_step_products')}</h3>
      <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: '0 0 16px' }}>{t('checkout_step_products_hint')}</p>

      <div style={{ display: 'flex', gap: 8, marginBottom: 16, flexWrap: 'wrap' }}>
        <select style={{ ...inputCss, height: 36, width: 'auto', minWidth: 200 }} value={country ?? ''}
          onChange={(e) => e.target.value && onCountryChange(Number(e.target.value))}>
          <option value="">{t('checkout_choose_country')}</option>
          <option value={-1}>{t('checkout_country_general')}</option>
          {countries.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
        </select>
        {country && (
          <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 200, maxWidth: 320 }} value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)}
            onKeyDown={(e) => { if (e.key === 'Enter') { setSearch(searchInput); setPage(1) } }}
            placeholder={t('checkout_search')} />
        )}
      </div>

      {!country ? (
        <div style={{ padding: 30, textAlign: 'center', color: 'var(--color-muted-foreground)' }}>{t('checkout_choose_country_hint')}</div>
      ) : (
        <div style={{ display: 'flex', flexDirection: narrow ? 'column' : 'row', gap: 20, alignItems: 'flex-start' }}>
          <div style={{ flex: 1, minWidth: 0, width: narrow ? '100%' : undefined }}>
            <div style={{ ...card, overflowX: 'auto' }}>
              <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
                  <tr><th style={{ ...th, width: 30 }} /><th style={th}>{t('checkout_col_product')}</th><th style={{ ...th, width: 90, textAlign: 'center' }}>{t('checkout_col_variants')}</th></tr>
                </thead>
                <tbody>
                  {loading && <tr><td colSpan={3} style={{ ...td, textAlign: 'center', padding: 24, color: 'var(--color-muted-foreground)' }}>{t('loading')}</td></tr>}
                  {!loading && products.length === 0 && <tr><td colSpan={3} style={{ ...td, textAlign: 'center', padding: 24, color: 'var(--color-muted-foreground)' }}>{t('checkout_no_products')}</td></tr>}
                  {products.map((p) => (
                    <Fragment key={p.id}>
                      <tr style={{ cursor: 'pointer' }} onClick={() => toggleExpand(p)}>
                        <td style={td}><span style={{ display: 'inline-flex', transform: expanded === p.id ? 'none' : 'rotate(-90deg)', transition: 'transform .1s' }}><ChevronDownIcon /></span></td>
                        <td style={td}>{p.name}</td>
                        <td style={{ ...td, textAlign: 'center' }}>{p.variantCount}</td>
                      </tr>
                      {expanded === p.id && (
                        <tr key={`${p.id}-variants`}>
                          <td colSpan={3} style={{ ...td, background: 'var(--color-muted,rgba(0,0,0,.02))' }}>
                            {variantsLoading ? (
                              <div style={{ padding: 8, color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
                            ) : variants.length === 0 ? (
                              <div style={{ padding: 8, color: 'var(--color-muted-foreground)' }}>{t('checkout_no_variants')}</div>
                            ) : (
                              <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
                                {variants.map((v) => (
                                  <div key={`${v.id}-${v.extra ?? ''}`} style={{ display: 'flex', flexWrap: narrow ? 'wrap' : 'nowrap', alignItems: 'center', gap: 10, padding: '6px 0' }}>
                                    <code style={{ fontSize: 12, minWidth: narrow ? undefined : 90 }}>{v.sku}</code>
                                    {/* Colonne attributs : vide plutôt qu'un « — » (une variante sans
                                        attribut est la norme ; le tiret n'apportait que du bruit). */}
                                    <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)', flex: 1 }}>{v.attributes}</span>
                                    {v.extraLabel && (
                                      <span style={{ fontSize: 11, fontWeight: 600, padding: '2px 8px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.06))', color: 'var(--color-primary)', whiteSpace: 'nowrap' }}>{v.extraLabel}</span>
                                    )}
                                    <span style={{ fontSize: 12, color: v.stock && v.stock > 0 ? '#16a34a' : '#dc2626' }}>{v.stock ?? '—'} {t('checkout_in_stock')}</span>
                                    <span style={{ fontSize: 13, fontWeight: 600, minWidth: 60, textAlign: 'right' }}>{v.price != null ? v.price.toFixed(2) : '—'}</span>
                                    <button style={{ ...btnPrimary, height: 28, padding: '0 10px' }} disabled={adding === v.id || !v.stock} onClick={(e) => { e.stopPropagation(); add(v) }}>
                                      {adding === v.id ? '…' : t('checkout_add')}
                                    </button>
                                  </div>
                                ))}
                              </div>
                            )}
                          </td>
                        </tr>
                      )}
                    </Fragment>
                  ))}
                </tbody>
              </table>
            </div>
            {totalPages > 1 && (
              <div style={{ display: 'flex', justifyContent: 'center', gap: 6, marginTop: 12 }}>
                <button style={{ ...btnGhost, height: 30, opacity: page <= 1 ? 0.5 : 1 }} disabled={page <= 1} onClick={() => setPage((p) => p - 1)}>{t('checkout_prev')}</button>
                <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)', alignSelf: 'center' }}>{page} / {totalPages}</span>
                <button style={{ ...btnGhost, height: 30, opacity: page >= totalPages ? 0.5 : 1 }} disabled={page >= totalPages} onClick={() => setPage((p) => p + 1)}>{t('checkout_next')}</button>
              </div>
            )}
          </div>

          <div style={{ width: narrow ? '100%' : 320, flexShrink: 0, display: 'flex', flexDirection: 'column', gap: 12, position: narrow ? undefined : 'sticky', top: narrow ? undefined : 0 }}>
            <div style={{ ...card, padding: 16 }}>
              <h4 style={{ fontSize: 14, fontWeight: 600, margin: '0 0 12px' }}>{t('checkout_basket_title')}</h4>
              {basket.length === 0 ? (
                <div style={{ fontSize: 13, color: 'var(--color-muted-foreground)' }}>{t('checkout_basket_empty')}</div>
              ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                  {basket.map((l) => (
                    <div key={l.lineId} style={{ display: 'flex', flexDirection: 'column', gap: 4, paddingBottom: 8, borderBottom: '1px solid var(--color-border)' }}>
                      <div style={{ display: 'flex', justifyContent: 'space-between', gap: 6 }}>
                        <span style={{ fontSize: 13, fontWeight: 500 }}>{l.productName || l.sku}</span>
                        <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} onClick={() => setQty(l, 0)}><TrashIcon /></button>
                      </div>
                      <code style={{ fontSize: 11, color: 'var(--color-muted-foreground)' }}>{l.sku}</code>
                      {l.extraLabel && (
                        <span style={{ fontSize: 11, fontWeight: 600, color: 'var(--color-primary)' }}>{l.extraLabel}</span>
                      )}
                      <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                        <button style={qtyBtn} onClick={() => setQty(l, l.quantity - 1)}>−</button>
                        <input type="text" inputMode="numeric" pattern="[0-9]*"
                          style={{ ...inputCss, height: 24, width: 56, textAlign: 'center', padding: '0 4px' }}
                          value={qtyDraft[l.lineId] ?? String(l.quantity)}
                          onChange={(e) => setQtyDraft((d) => ({ ...d, [l.lineId]: e.target.value.replace(/\D/g, '') }))}
                          onBlur={(e) => commitQtyDraft(l, e.target.value)}
                          onKeyDown={(e) => { if (e.key === 'Enter') (e.target as HTMLInputElement).blur() }} />
                        <button style={{ ...qtyBtn, opacity: l.stock != null && l.quantity >= l.stock ? 0.4 : 1 }}
                          disabled={l.stock != null && l.quantity >= l.stock}
                          onClick={() => setQty(l, l.quantity + 1)}>+</button>
                        <span style={{ marginLeft: 'auto', fontSize: 13, fontWeight: 600 }}>{l.lineTotal != null ? l.lineTotal.toFixed(2) : '—'}</span>
                      </div>
                    </div>
                  ))}
                  <div style={{ display: 'flex', justifyContent: 'space-between', paddingTop: 8, fontWeight: 700, fontSize: 14 }}>
                    <span>{t('checkout_basket_total')}</span>
                    <span>{basketTotal.toFixed(2)}</span>
                  </div>
                </div>
              )}
            </div>

            <div style={{ display: 'flex', justifyContent: 'space-between' }}>
              <button style={btnGhost} onClick={onBack}>{t('checkout_back')}</button>
              <button style={btnPrimary} onClick={next}>{t('checkout_next')}</button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
