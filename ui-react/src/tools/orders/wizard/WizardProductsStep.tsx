import { Fragment, useEffect, useState } from 'react'
import { makeT } from '../../../shared/i18n'
import { DICT } from '../dict'
import {
  fetchCheckoutProducts, fetchCheckoutVariants, fetchCheckoutBasket,
  checkoutSetCountry, checkoutBasketAdd, checkoutBasketSetQty, checkoutBasketRemove,
  type CheckoutProduct, type CheckoutVariant, type CheckoutBasketLine,
} from '../api'
import { fetchCountries, type CountryItem } from '../../countries/api'
import { card, inputCss, btnPrimary, btnGhost, th, td } from '../../../shared/styles'
import { ChevronDownIcon, TrashIcon } from '../../../shared/icons'
import { notify } from '../../../shared/notify'
import type { CSSProperties } from 'react'

const qtyBtn: CSSProperties = { ...btnGhost, width: 24, height: 24, padding: 0, justifyContent: 'center' }

export function WizardProductsStep({ countryId, onBack, onNext }: {
  countryId: number | null; onBack: () => void; onNext: (countryId: number) => void
}) {
  const t = makeT(DICT)
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
    try { const r = await checkoutBasketAdd(v.id, 1); setBasket(r.items) }
    catch (e) { notify('ko', t('checkout_step_products'), e instanceof Error ? e.message : 'Error') }
    finally { setAdding(null) }
  }

  async function setQty(variantId: number, qty: number) {
    try {
      const r = qty <= 0 ? await checkoutBasketRemove(variantId) : await checkoutBasketSetQty(variantId, qty)
      setBasket(r.items)
    } catch (e) { notify('ko', t('checkout_step_products'), e instanceof Error ? e.message : 'Error') }
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
        <div style={{ display: 'flex', gap: 20, alignItems: 'flex-start' }}>
          <div style={{ flex: 1, minWidth: 0 }}>
            <div style={{ ...card, overflow: 'hidden' }}>
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
                                  <div key={v.id} style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '6px 0' }}>
                                    <code style={{ fontSize: 12, minWidth: 90 }}>{v.sku}</code>
                                    <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)', flex: 1 }}>{v.attributes || '—'}</span>
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

          <div style={{ width: 320, flexShrink: 0, display: 'flex', flexDirection: 'column', gap: 12, position: 'sticky', top: 0 }}>
            <div style={{ ...card, padding: 16 }}>
              <h4 style={{ fontSize: 14, fontWeight: 600, margin: '0 0 12px' }}>{t('checkout_basket_title')}</h4>
              {basket.length === 0 ? (
                <div style={{ fontSize: 13, color: 'var(--color-muted-foreground)' }}>{t('checkout_basket_empty')}</div>
              ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                  {basket.map((l) => (
                    <div key={l.variantId} style={{ display: 'flex', flexDirection: 'column', gap: 4, paddingBottom: 8, borderBottom: '1px solid var(--color-border)' }}>
                      <div style={{ display: 'flex', justifyContent: 'space-between', gap: 6 }}>
                        <span style={{ fontSize: 13, fontWeight: 500 }}>{l.productName || l.sku}</span>
                        <button style={{ ...qtyBtn, border: 0 }} onClick={() => setQty(l.variantId, 0)}><TrashIcon /></button>
                      </div>
                      <code style={{ fontSize: 11, color: 'var(--color-muted-foreground)' }}>{l.sku}</code>
                      <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                        <button style={qtyBtn} onClick={() => setQty(l.variantId, l.quantity - 1)}>−</button>
                        <span style={{ fontSize: 13, minWidth: 20, textAlign: 'center' }}>{l.quantity}</span>
                        <button style={qtyBtn} onClick={() => setQty(l.variantId, l.quantity + 1)}>+</button>
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
              <button style={btnPrimary} disabled={!country || basket.length === 0} onClick={() => country && onNext(country)}>{t('checkout_next')}</button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
