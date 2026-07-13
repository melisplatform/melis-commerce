import { useEffect, useRef, useState } from 'react'
import type React from 'react'
import {
  saveProductVariant, fetchVariant, fetchVariantMedia,
  fetchVariantPrices, saveVariantPrice,
  fetchVariantStocks, saveVariantStock, saveVariantSeo,
  fetchVariantAssoc, saveVariantAssoc, deleteVariantAssoc,
  fetchVariantAttributes, saveVariantAttribute,
  uploadVariantMedia, updateVariantMedia, deleteVariantMedia,
  type VariantSeo, type VariantPrice, type VariantStock, type MediaItem, type CountryOption,
  type AssocVariant, type AvailVariant, type VariantAttrGroup,
} from './api'
import { card, inputCss, btnPrimary, btnGhost, iconBtn, th, td, label, hint } from '../../shared/styles'
import { PlusIcon, TrashIcon, PencilIcon, EyeIcon, ToggleRightIcon, ArrowLeftIcon, ImageIcon, SettingsIcon, PaperclipIcon, CubesIcon } from '../../shared/icons'
import { StatusBadge, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { Tabs, type TabDef } from '../../shared/Tabs'
import { InfoDot, FlagSwitcher, MediaModal, Lightbox, hoverCircle, ImageFilters, passesImageFilter, type PendingMedia } from './ProductTabs'
import type { Option } from '../../shared/api'
import type { LangOption } from '../catalog/api'

type TFn = (k: string, v?: Record<string, string | number>) => string
const seg = (on: boolean) => ({ padding: '6px 18px', borderRadius: 6, border: 0, cursor: 'pointer', fontSize: 14, fontWeight: 600, background: on ? 'var(--color-primary)' : 'transparent', color: on ? 'var(--color-primary-foreground,#fff)' : 'var(--color-foreground)' } as const)
const labelRow = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 } as const
const num = (v: string) => v === '' ? null : (parseFloat(v) || 0)
const str = (v: number | null) => v == null ? '' : String(v)

/** Éditeur d'une variante — onglets Propriétés / SEO / Prix / Stocks / Association. */
export function VariantEditor({ productId, variantId, countries, currencies, languages, t, onClose, onSaved, can }: {
  productId: number; variantId: number | null; countries: CountryOption[]; currencies: Option[]; languages: LangOption[]
  t: TFn; onClose: () => void; onSaved: () => void; can?: (cap: string) => boolean
}) {
  const [vid, setVid] = useState<number | null>(variantId)
  const isEdit = vid != null
  const pricesSaveRef = useRef<((id: number) => Promise<void>) | null>(null)
  const stocksSaveRef = useRef<((id: number) => Promise<void>) | null>(null)
  const [sku, setSku] = useState('')
  const [status, setStatus] = useState(true)
  const [isMain, setIsMain] = useState(false)
  const [seo, setSeo] = useState<VariantSeo[]>([])
  const [seoLang, setSeoLang] = useState<number>(languages[0]?.id ?? 0)
  const [tab, setTab] = useState('properties')
  const [loading, setLoading] = useState(false)
  const [saving, setSaving] = useState(false)
  const [err, setErr] = useState<string | null>(null)
  const [errSku, setErrSku] = useState(false)

  // Onglets de la variante masqués selon les droits (accès onglet = capacité `variants.<clé>`).
  const allow = (cap: string) => (can ? can(cap) : true)
  const TABS: TabDef[] = ([
    { key: 'properties', label: t('tab_main') }, { key: 'seo', label: t('tab_seo') }, { key: 'prices', label: t('tab_prices') },
    { key: 'stocks', label: t('var_tab_stocks') }, { key: 'assoc', label: t('var_tab_assoc') },
  ] as TabDef[]).filter((tb) => allow(`variants.${tb.key}`))

  useEffect(() => {
    if (TABS.length && !TABS.some((tb) => tb.key === tab)) setTab(TABS[0].key)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [can])
  // « Général » (pays -1, comme le legacy) + pays — pour le sélecteur des onglets Prix / Stocks.
  const countryItems = [{ id: -1, name: t('price_general'), flag: '' }, ...countries]

  useEffect(() => {
    setSeo((p) => p.length ? p : languages.map((l) => ({ langId: l.id, langName: l.name, pageId: '', url: '', urlRedirect: '', url301: '', metaTitle: '', metaDescription: '' })))
    setSeoLang((v) => v || languages[0]?.id || 0)
  }, [languages])
  useEffect(() => {
    if (vid == null) return
    setLoading(true)
    fetchVariant(productId, vid).then((v) => { setSku(v.sku); setStatus(v.status === 1); setIsMain(v.isMain === 1); if (v.seo?.length) setSeo(v.seo) }).catch(() => null).finally(() => setLoading(false))
  }, [productId, vid])

  // Auto-saves the variant (without SEO) and returns its id. Used by sub-sections so they never block.
  async function ensureVid(): Promise<number | null> {
    if (vid != null) return vid
    if (!sku.trim()) { setErrSku(true); setErr(t('var_err_sku')); setTab('properties'); return null }
    setSaving(true); setErr(null)
    try {
      const r = await saveProductVariant(productId, { variantId: null, sku: sku.trim(), status, isMain })
      if (r?.id) { setVid(r.id); onSaved(); return r.id }
      return null
    } catch (e) { setErr(e instanceof Error ? e.message : t('err_save')); return null }
    finally { setSaving(false) }
  }

  async function save() {
    if (!sku.trim()) { setErrSku(true); setErr(t('var_err_sku')); setTab('properties'); return }
    setSaving(true); setErr(null)
    try {
      const r = await saveProductVariant(productId, { variantId: vid, sku: sku.trim(), status, isMain })
      const id = vid ?? r?.id
      if (vid == null && r?.id) setVid(r.id)
      if (id) {
        try { await saveVariantSeo(productId, id, seo.map((s) => ({ langId: s.langId, pageId: s.pageId, url: s.url, urlRedirect: s.urlRedirect, url301: s.url301, metaTitle: s.metaTitle, metaDescription: s.metaDescription }))) } catch { /* */ }
        try { if (pricesSaveRef.current) await pricesSaveRef.current(id) } catch { /* */ }
        try { if (stocksSaveRef.current) await stocksSaveRef.current(id) } catch { /* */ }
      }
      notify('ok', t('title'), t('saved')); onSaved()
    } catch (e) { const msg = e instanceof Error ? e.message : t('err_save'); setErr(msg); notify('ko', t('title'), msg) } finally { setSaving(false) }
  }
  function setSeoF(lid: number, f: keyof VariantSeo, val: string) { setSeo((p) => p.map((s) => s.langId === lid ? { ...s, [f]: val } : s)) }

  return (
    <div>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, marginBottom: 16 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          {/* Vraie navigation arrière (pas juste décorative) : ce formulaire est niché DANS l'onglet
              Variants du produit — le SubTabBar de l'hôte ne connaît que le niveau Produit, donc cette
              flèche reste nécessaire pour revenir à la LISTE des variantes. */}
          <button type="button" style={{ ...iconBtn, width: 32, height: 32 }} onClick={onClose} title={t('back')}><ArrowLeftIcon /></button>
          <h2 style={{ fontSize: 18, fontWeight: 700, margin: 0 }}>{isEdit ? t('var_edit') : t('var_new')}</h2>
        </div>
        <div style={{ display: 'flex', gap: 10, alignItems: 'center' }}>
          <button style={btnPrimary} onClick={save} disabled={saving || loading}>{saving ? '…' : t('save')}</button>
        </div>
      </div>

      <Tabs tabs={TABS} active={tab} onChange={setTab} />
      {err && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, marginBottom: 12 }}>{err}</div>}

      {loading ? <div style={{ padding: 30, color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      : tab === 'properties' ? (
        <div>
          <div style={{ borderBottom: '1px solid var(--color-border)', paddingBottom: 12, marginBottom: 20 }}>
            <h3 style={{ fontSize: 16, fontWeight: 600, margin: 0 }}>{t('tab_main')}</h3>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr minmax(200px,220px)', gap: 20, alignItems: 'start' }}>
            <div style={{ ...card, padding: 24, display: 'flex', flexDirection: 'column', gap: 24 }}>
              <section>
                <h3 style={secT}><SettingsIcon />{t('var_information')}</h3>
                <div style={labelRow}><label style={{ ...label, color: errSku ? '#dc2626' : undefined }}>{t('var_sku')}</label><InfoDot text={t('tip_sku')} /></div>
                <input style={{ ...inputCss, ...(errSku ? { borderColor: '#dc2626' } : {}) }} value={sku} onChange={(e) => { setSku(e.target.value); setErr(null); setErrSku(false) }} placeholder={t('var_sku_ph')} autoComplete="off" />
                <div style={{ marginTop: 16, fontSize: 14, fontWeight: 500 }}>{t('var_main_label')}</div>
                <div style={{ display: 'inline-flex', gap: 2, padding: 2, borderRadius: 8, border: '1px solid var(--color-border)', marginTop: 6 }}>
                  <button style={seg(isMain)} onClick={() => setIsMain(true)}>{t('yes')}</button>
                  <button style={seg(!isMain)} onClick={() => setIsMain(false)}>{t('no')}</button>
                </div>
              </section>
              <VarFiles productId={productId} variantId={vid} t={t} countries={countries} onNeedVid={ensureVid} />
              <VarAttributes productId={productId} variantId={vid} t={t} onNeedVid={ensureVid} />
            </div>
            <div style={{ ...card, padding: 24 }}><VarImages productId={productId} variantId={vid} t={t} countries={countries} /></div>
            <div style={{ ...card, padding: 16 }}>
              <h3 style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 12px' }}>
                <ToggleRightIcon />{t('status_label')}
              </h3>
              <button type="button" onClick={() => setStatus((v) => !v)} style={{ display: 'flex', alignItems: 'center', gap: 10, background: 'none', border: 0, padding: 0, cursor: 'pointer' }}>
                <div style={{ position: 'relative', width: 36, height: 20, borderRadius: 999, background: status ? '#22c55e' : 'var(--color-muted-foreground)', transition: 'background .15s', flexShrink: 0 }}>
                  <span style={{ position: 'absolute', top: 2, left: status ? 18 : 2, width: 16, height: 16, borderRadius: 999, background: '#fff', transition: 'left .15s', boxShadow: '0 1px 2px rgba(0,0,0,.3)' }} />
                </div>
                <span style={{ fontSize: 14, fontWeight: 500, color: status ? '#16a34a' : 'var(--color-muted-foreground)' }}>
                  {status ? t('online') : t('offline')}
                </span>
              </button>
            </div>
          </div>
        </div>
      ) : tab === 'seo' ? (
        (
          <div>
            <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 16px' }}>{t('tab_seo')}</h3>
            <div style={{ display: 'grid', gridTemplateColumns: '160px 1fr', gap: 18, maxWidth: 900 }}>
              <FlagSwitcher items={languages} value={seoLang} onChange={setSeoLang} />
              {seo.filter((s) => s.langId === seoLang).map((s) => {
              const SEO_TIPS: Record<string, string> = { pageId: 'tip_var_seo_page_id', metaTitle: 'tip_var_seo_meta_title', metaDescription: 'tip_var_seo_meta_desc', url: 'tip_var_seo_url', urlRedirect: 'tip_var_seo_url_redirect', url301: 'tip_var_seo_url_301' }
              return (
                <div key={s.langId} style={{ ...card, padding: 18 }}>
                  {([['pageId', 'seo_page_id'], ['metaTitle', 'seo_meta_title'], ['metaDescription', 'seo_meta_desc'], ['url', 'seo_url'], ['urlRedirect', 'seo_url_redirect'], ['url301', 'seo_url_301']] as const).map(([k, lbl], i) => (
                    <div key={k} style={{ marginTop: i === 0 ? 0 : 14 }}>
                      <div style={labelRow}><label style={label}>{t(lbl)}</label><InfoDot text={t(SEO_TIPS[k])} /></div>
                      {k === 'metaDescription'
                        ? <textarea style={{ ...inputCss, minHeight: 90, resize: 'vertical', paddingTop: 8 }} value={s[k]} onChange={(e) => setSeoF(s.langId, k, e.target.value)} />
                        : <input style={inputCss} value={s[k]} onChange={(e) => setSeoF(s.langId, k, e.target.value)} autoComplete="off" />}
                    </div>
                  ))}

                </div>
              )})}
            </div>
          </div>
        )
      ) : tab === 'prices' ? (
        <VarPrices productId={productId} variantId={vid} countryItems={countryItems} currency={currencies[0]?.id ?? 0} t={t} saveRef={pricesSaveRef} />
      ) : tab === 'stocks' ? (
        <VarStocks productId={productId} variantId={vid} countryItems={countryItems} t={t} saveRef={stocksSaveRef} />
      ) : (
        <VarAssoc productId={productId} variantId={vid} t={t} onNeedVid={ensureVid} />
      )}
    </div>
  )
}

const secT = { fontSize: 12, fontWeight: 600, color: 'var(--color-foreground)', margin: '0 0 12px', display: 'flex', alignItems: 'center', gap: 6 } as const

// ── Prix de la variante (formulaire par pays : Net/Brut/TVA%/Montant TVA/Autre taxe) ──
function VarPrices({ productId, variantId, countryItems, currency, t, saveRef }: { productId: number; variantId: number | null; countryItems: { id: number; name: string; flag?: string }[]; currency: number; t: TFn; saveRef?: React.MutableRefObject<((id: number) => Promise<void>) | null> }) {
  const [prices, setPrices] = useState<VariantPrice[]>([])
  const [country, setCountry] = useState<number>(-1)
  const [tick, setTick] = useState(0)
  // Per-country draft, keyed by countryId — switching the country tab must never discard an
  // unsaved edit, so edits live here (not in a single shared field-state reset on every switch)
  // and Save flushes every touched country, not just whichever tab happens to be active.
  const [drafts, setDrafts] = useState<Record<number, { net: string; gross: string; vatPercent: string; vatPrice: string; otherTax: string }>>({})
  useEffect(() => { if (variantId == null) return; fetchVariantPrices(productId, variantId).then((r) => setPrices(r.items)).catch(() => null) }, [productId, variantId, tick])
  const cur = prices.find((p) => p.countryId === country) ?? null
  const f = drafts[country] ?? { net: str(cur?.net ?? null), gross: str(cur?.gross ?? null), vatPercent: str(cur?.vatPercent ?? null), vatPrice: str(cur?.vatPrice ?? null), otherTax: str(cur?.otherTax ?? null) }
  function setF(nf: typeof f) { setDrafts((d) => ({ ...d, [country]: nf })) }
  async function doSave(id: number) {
    for (const cid of new Set([...Object.keys(drafts).map(Number), country])) {
      const draft = drafts[cid]; if (!draft) continue
      const orig = prices.find((p) => p.countryId === cid) ?? null
      await saveVariantPrice(productId, id, { id: orig?.id ?? null, countryId: cid, currency, net: num(draft.net), gross: num(draft.gross), vatPercent: num(draft.vatPercent), vatPrice: num(draft.vatPrice), otherTax: num(draft.otherTax) })
    }
    setDrafts({}); setTick((x) => x + 1)
  }
  if (saveRef) saveRef.current = doSave
  const PRICE_TIPS: Record<string, string> = { net: 'tip_price_net', gross: 'tip_price_gross', vatPercent: 'tip_price_vat', vatPrice: 'tip_price_vat_amount', otherTax: 'tip_price_other_tax' }
  const fields: [keyof typeof f, string][] = [['net', 'price_net'], ['gross', 'price_gross'], ['vatPercent', 'price_vat'], ['vatPrice', 'price_vat_amount'], ['otherTax', 'price_other_tax']]
  return (
    <div>
      <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 16px' }}>{t('tab_prices')}</h3>
      <div style={{ display: 'grid', gridTemplateColumns: '160px 1fr', gap: 18, maxWidth: 900 }}>
        <FlagSwitcher items={countryItems} value={country} onChange={setCountry} />
        <div style={{ ...card, padding: 18 }}>
          <h4 style={{ fontSize: 14, fontWeight: 600, margin: '0 0 14px', color: 'var(--color-primary)' }}>$ {t('price_general_pricing')}</h4>
          {fields.map(([k, lbl], i) => (
            <div key={k} style={{ marginTop: i === 0 ? 0 : 14 }}>
              <div style={labelRow}><label style={label}>{t(lbl)}</label><InfoDot text={t(PRICE_TIPS[k])} /></div>
              <input style={inputCss} type="number" step="0.01" value={f[k]} onChange={(e) => setF({ ...f, [k]: e.target.value })} />
            </div>
          ))}
        </div>
      </div>
    </div>
  )
}

// ── Stocks de la variante (par pays : Quantité + Date de réapprovisionnement) ────
function VarStocks({ productId, variantId, countryItems, t, saveRef }: { productId: number; variantId: number | null; countryItems: { id: number; name: string; flag?: string }[]; t: TFn; saveRef?: React.MutableRefObject<((id: number) => Promise<void>) | null> }) {
  const [stocks, setStocks] = useState<VariantStock[]>([])
  const [country, setCountry] = useState<number>(-1)
  const [tick, setTick] = useState(0)
  // Per-country draft — see VarPrices for why (switching tabs must not discard unsaved edits).
  const [drafts, setDrafts] = useState<Record<number, { qty: string; next: string }>>({})
  useEffect(() => { if (variantId == null) return; fetchVariantStocks(productId, variantId).then((r) => setStocks(r.items)).catch(() => null) }, [productId, variantId, tick])
  const cur = stocks.find((s) => s.countryId === country) ?? null
  const draft = drafts[country] ?? { qty: cur ? String(cur.quantity) : '', next: cur?.nextFillUp ? String(cur.nextFillUp).slice(0, 10) : '' }
  function setDraft(nd: typeof draft) { setDrafts((d) => ({ ...d, [country]: nd })) }
  async function doSave(id: number) {
    for (const cid of new Set([...Object.keys(drafts).map(Number), country])) {
      const d = drafts[cid]; if (!d) continue
      const orig = stocks.find((s) => s.countryId === cid) ?? null
      await saveVariantStock(productId, id, { id: orig?.id ?? null, countryId: cid, quantity: d.qty === '' ? 0 : parseInt(d.qty, 10) || 0, low: null, nextFillUp: d.next || null })
    }
    setDrafts({}); setTick((x) => x + 1)
  }
  if (saveRef) saveRef.current = doSave
  return (
    <div>
      <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 16px' }}>{t('var_tab_stocks')}</h3>
      <div style={{ display: 'grid', gridTemplateColumns: '160px 1fr', gap: 18, maxWidth: 900 }}>
        <FlagSwitcher items={countryItems} value={country} onChange={setCountry} />
        <div style={{ ...card, padding: 18 }}>
          <h4 style={{ fontSize: 14, fontWeight: 600, margin: '0 0 14px' }}>{t('stock_general')}</h4>
          <div style={labelRow}><label style={label}>{t('stock_qty')}</label><InfoDot text={t('tip_stock_qty')} /></div>
          <input style={inputCss} type="number" value={draft.qty} onChange={(e) => setDraft({ ...draft, qty: e.target.value })} />
          <div style={{ ...labelRow, marginTop: 14 }}><label style={label}>{t('stock_next')}</label><InfoDot text={t('tip_stock_next')} /></div>
          <input style={inputCss} type="date" value={draft.next} onChange={(e) => setDraft({ ...draft, next: e.target.value })} />
        </div>
      </div>
    </div>
  )
}

// ── Fichiers / Images de la variante (lecture ; upload via Old) ─────────────────
function VarFiles({ productId, variantId, t, countries, onNeedVid }: { productId: number; variantId: number | null; t: TFn; countries: CountryOption[]; onNeedVid?: () => Promise<number | null> }) {
  const [files, setFiles] = useState<MediaItem[]>([])
  const [tick, setTick] = useState(0)
  const [modal, setModal] = useState<{ doc?: MediaItem } | null>(null)
  useEffect(() => { if (variantId == null) return; fetchVariantMedia(productId, variantId).then((r) => setFiles(r.files)).catch(() => null) }, [productId, variantId, tick])
  async function del(docId: number) { const id = variantId ?? await onNeedVid?.(); if (!id) return; try { await deleteVariantMedia(productId, id, docId); setTick((x) => x + 1) } catch { /* */ } }
  return (
    <section>
      <h3 style={secT}>
        <PaperclipIcon />{t('sec_files')}
        <button style={{ ...btnGhost, fontSize: 12, padding: '2px 8px' }} onClick={() => setModal({})}>{t('file_add')}</button>
      </h3>
      {files.length === 0
        ? <div style={{ padding: '12px 14px', borderRadius: 8, background: 'var(--color-muted,rgba(0,0,0,.04))', color: 'var(--color-muted-foreground)', fontSize: 14 }}>{t('files_empty')}</div>
        : <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
            {files.map((f) => (
              <span key={f.id} style={{ display: 'inline-flex', alignItems: 'center', gap: 4, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
                <span style={{ color: 'var(--color-foreground)' }}>{f.name || f.path}</span>
                <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-muted-foreground)' }} title={t('file_edit')} onClick={() => setModal({ doc: f })}><PencilIcon /></button>
                <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-muted-foreground)' }} title={t('del')} onClick={() => del(f.id)}>✕</button>
              </span>
            ))}
          </div>}
      {modal !== null && (
        <MediaModal kind="file" doc={modal.doc} countries={countries} t={t}
          onClose={() => setModal(null)}
          onSaved={() => setTick(x => x + 1)}
          onUpdate={async (docId, m) => { const id = variantId ?? await onNeedVid?.(); if (id) await updateVariantMedia(productId, id, docId, m) }}
          onAddPending={async (m) => { const id = variantId ?? await onNeedVid?.(); if (id) { await uploadVariantMedia(productId, id, { ...m, kind: 'file' }); setTick(x => x + 1) } }} />
      )}
    </section>
  )
}
function VarImages({ productId, variantId, t, countries }: { productId: number; variantId: number | null; t: TFn; countries: CountryOption[] }) {
  const [images, setImages] = useState<MediaItem[]>([])
  // Images added while the variant doesn't exist yet (still on "Nouvelle variante") are queued
  // here instead of eagerly auto-saving the variant to get an id — auto-saving would run the same
  // full-form validation (e.g. SKU required) as the main Save button, which must not fire just
  // because the user picked an image. Flushed once `variantId` becomes real (see effect below).
  const [pendingImages, setPendingImages] = useState<PendingMedia[]>([])
  const [tick, setTick] = useState(0)
  const [hovered, setHovered] = useState<number | null>(null)
  const [lightbox, setLightbox] = useState<string | null>(null)
  const [modal, setModal] = useState<{ doc?: MediaItem; pendingIdx?: number } | null>(null)
  const [countryFilter, setCountryFilter] = useState(0)
  const [typeFilter, setTypeFilter] = useState(0)
  const [toDelete, setToDelete] = useState<{ id: number; pendingIdx?: number } | null>(null)
  useEffect(() => { if (variantId == null) return; fetchVariantMedia(productId, variantId).then((r) => setImages(r.images)).catch(() => null) }, [productId, variantId, tick])
  useEffect(() => {
    if (variantId == null || pendingImages.length === 0) return
    let cancelled = false
    ;(async () => {
      for (const m of pendingImages) { try { await uploadVariantMedia(productId, variantId, { ...m, kind: 'image' }) } catch { /* */ } }
      if (!cancelled) { setPendingImages([]); setTick((x) => x + 1) }
    })()
    return () => { cancelled = true }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [variantId])
  async function del(docId: number) { if (variantId == null) return; try { await deleteVariantMedia(productId, variantId, docId); setTick((x) => x + 1) } catch { /* */ } }
  function confirmDelete() {
    if (!toDelete) return
    if (toDelete.pendingIdx !== undefined) setPendingImages((p) => p.filter((_, i) => i !== toDelete.pendingIdx))
    else del(toDelete.id)
    setToDelete(null)
  }
  const allImages: { id: number; path: string; name: string; isPending: boolean; countryId: number | null; typeId: number | null }[] = [
    ...images.map(im => ({ id: im.id, path: im.path, name: im.name, isPending: false, countryId: im.countryId, typeId: im.typeId })),
    ...pendingImages.map((m, i) => ({ id: -(i + 1), path: m.data, name: m.name, isPending: true, countryId: m.countryId ?? null, typeId: m.typeId ?? null })),
  ].filter((im) => passesImageFilter(im.countryId, countryFilter) && passesImageFilter(im.typeId, typeFilter))
  return (
    <section>
      <h3 style={secT}>
        <ImageIcon />{t('sec_images')}
        <button style={{ ...btnGhost, fontSize: 12, padding: '2px 8px' }} onClick={() => setModal({})}>{t('img_add')}</button>
      </h3>
      <ImageFilters countries={countries} countryFilter={countryFilter} onCountryChange={setCountryFilter}
        typeFilter={typeFilter} onTypeChange={setTypeFilter} t={t} />
      {allImages.length === 0
        ? <p style={{ ...hint, margin: 0 }}>{t('img_empty')}</p>
        : <div style={{ display: 'flex', flexWrap: 'wrap', gap: 12 }}>
            {allImages.map((im) => (
              <div key={im.id} style={{ position: 'relative', width: 160, height: 140, borderRadius: 8, overflow: 'hidden', border: '1px solid var(--color-border)', flexShrink: 0 }}
                onMouseEnter={() => setHovered(im.id)} onMouseLeave={() => setHovered(null)}>
                <img src={im.path} alt={im.name} title={im.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} onError={(e) => { (e.target as HTMLImageElement).style.opacity = '.25' }} />
                {hovered === im.id && (
                  <div style={{ position: 'absolute', inset: 0, background: 'rgba(0,0,0,.48)', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 10 }}>
                    <button style={hoverCircle} title={t('img_view')} onClick={() => setLightbox(im.path)}><EyeIcon /></button>
                    <button style={hoverCircle} title={t('img_edit')}
                      onClick={() => setModal(im.isPending ? { pendingIdx: (-im.id) - 1 } : { doc: images.find(x => x.id === im.id) })}>
                      <PencilIcon />
                    </button>
                    <button style={{ ...hoverCircle, color: '#ef4444' }} title={t('img_del')}
                      onClick={() => setToDelete(im.isPending ? { id: im.id, pendingIdx: (-im.id) - 1 } : { id: im.id })}>
                      ✕
                    </button>
                  </div>
                )}
              </div>
            ))}
          </div>}
      {lightbox && <Lightbox src={lightbox} onClose={() => setLightbox(null)} />}
      {modal !== null && (
        <MediaModal kind="image" doc={modal.doc} pendingDoc={modal.pendingIdx !== undefined ? pendingImages[modal.pendingIdx] : undefined} countries={countries} t={t}
          onClose={() => setModal(null)}
          onSaved={() => setTick(x => x + 1)}
          onUpdate={variantId != null ? async (docId, m) => { await updateVariantMedia(productId, variantId, docId, m) } : undefined}
          onAddPending={async (m) => {
            if (variantId != null) { try { await uploadVariantMedia(productId, variantId, { ...m, kind: 'image' }) } catch { /* */ } setTick((x) => x + 1); return }
            setPendingImages((p) => [...p, m])
          }}
          onUpdatePending={(m) => { if (modal.pendingIdx !== undefined) setPendingImages((p) => p.map((x, i) => i === modal.pendingIdx ? m : x)) }} />
      )}
      {toDelete !== null && (
        <ConfirmModal t={t} title={t('del_title')} message={t('img_del_confirm')}
          onCancel={() => setToDelete(null)}
          onConfirm={confirmDelete} />
      )}
    </section>
  )
}

// ── Attributs de la variante : une ligne par attribut assigné au produit (Color, Shoe size…),
// chacune avec son propre sélecteur de valeur (une seule valeur par attribut, comme le legacy) ──
function VarAttributes({ productId, variantId, t, onNeedVid }: { productId: number; variantId: number | null; t: TFn; onNeedVid?: () => Promise<number | null> }) {
  const [groups, setGroups] = useState<VariantAttrGroup[]>([])
  const [tick, setTick] = useState(0)
  const [saving, setSaving] = useState<number | null>(null)
  useEffect(() => { if (variantId == null) return; fetchVariantAttributes(productId, variantId).then((r) => setGroups(r.attributes)).catch(() => null) }, [productId, variantId, tick])
  async function pick(attributeId: number, valueId: number) {
    const id = variantId ?? await onNeedVid?.(); if (!id) return
    setSaving(attributeId)
    try { await saveVariantAttribute(productId, id, attributeId, valueId); setTick((x) => x + 1) }
    catch { /* */ } finally { setSaving(null) }
  }
  return (
    <section>
      <h3 style={secT}><CubesIcon />{t('sec_attributes')}</h3>
      {groups.length === 0 ? <p style={{ ...hint, margin: 0 }}>{t('attr_empty')}</p> : (
        <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
          {groups.map((g) => (
            <div key={g.attributeId} style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
              <label style={{ ...label, flex: '0 0 120px', margin: 0 }}>{g.attributeName}</label>
              <select style={{ ...inputCss, flex: 1 }} value={g.selectedValueId ?? 0} disabled={saving === g.attributeId}
                onChange={(e) => pick(g.attributeId, Number(e.target.value))}>
                <option value={0}>{t('attr_value_ph')}</option>
                {g.values.map((v) => <option key={v.id} value={v.id}>{v.name}</option>)}
              </select>
            </div>
          ))}
        </div>
      )}
    </section>
  )
}

// ── Association : variantes associées + variantes disponibles à associer ────────
function VarAssoc({ productId, variantId, t, onNeedVid }: { productId: number; variantId: number | null; t: TFn; onNeedVid?: () => Promise<number | null> }) {
  const [associated, setAssociated] = useState<AssocVariant[]>([])
  const [available, setAvailable] = useState<AvailVariant[]>([])
  const [tick, setTick] = useState(0)
  const [search, setSearch] = useState('')
  useEffect(() => { if (variantId == null) return; fetchVariantAssoc(productId, variantId).then((r) => { setAssociated(r.associated); setAvailable(r.available) }).catch(() => null) }, [productId, variantId, tick])
  async function add(vId: number) { const id = variantId ?? await onNeedVid?.(); if (!id) return; try { await saveVariantAssoc(productId, id, vId); setTick((x) => x + 1) } catch { /* */ } }
  async function del(avarId: number) { const id = variantId ?? await onNeedVid?.(); if (!id) return; try { await deleteVariantAssoc(productId, id, avarId); setTick((x) => x + 1) } catch { /* */ } }
  const filt = available.filter((v) => !search || v.productName.toLowerCase().includes(search.toLowerCase()) || v.sku.toLowerCase().includes(search.toLowerCase()) || String(v.variantId).includes(search))
  const tbl = { width: '100%', borderCollapse: 'collapse' as const }
  return (
    <div>
      <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 12px' }}>{t('var_tab_assoc')}</h3>
      <div style={{ border: '1px solid var(--color-border)', borderRadius: 8, overflow: 'hidden', marginBottom: 24 }}>
        <table style={tbl}>
          <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}><tr><th style={{ ...th, width: 50 }}>{t('var_col_id')}</th><th style={{ ...th, width: 80 }}>{t('col_status')}</th><th style={th}>{t('assoc_product')}</th><th style={th}>{t('var_col_sku')}</th><th style={{ ...th, width: 70 }} /></tr></thead>
          <tbody>
            {associated.length === 0 ? <tr><td colSpan={5} style={{ ...td, textAlign: 'center', color: 'var(--color-muted-foreground)', padding: 24 }}>{t('assoc_empty')}</td></tr>
              : associated.map((v) => (<tr key={v.avarId}><td style={td}>{v.variantId}</td><td style={td}><StatusBadge active={v.status === 1} t={t} /></td><td style={{ ...td, fontWeight: 500 }}>{v.productName}</td><td style={td}>{v.sku || '—'}</td><td style={td}><button style={{ ...iconBtn, color: '#ef4444' }} title={t('del')} onClick={() => del(v.avarId)}><TrashIcon /></button></td></tr>))}
          </tbody>
        </table>
      </div>

      <h3 style={{ fontSize: 15, fontWeight: 600, margin: '0 0 10px' }}>{t('assoc_variants')}</h3>
      <input style={{ ...inputCss, height: 34, maxWidth: 320, marginBottom: 10 }} value={search} onChange={(e) => setSearch(e.target.value)} placeholder={t('search')} />
      <div style={{ border: '1px solid var(--color-border)', borderRadius: 8, overflow: 'hidden' }}>
        <table style={tbl}>
          <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}><tr><th style={{ ...th, width: 50 }}>{t('var_col_id')}</th><th style={{ ...th, width: 80 }}>{t('col_status')}</th><th style={th}>{t('assoc_product')}</th><th style={th}>{t('var_col_sku')}</th><th style={{ ...th, width: 70 }} /></tr></thead>
          <tbody>
            {filt.length === 0 ? <tr><td colSpan={5} style={{ ...td, textAlign: 'center', color: 'var(--color-muted-foreground)', padding: 24 }}>{t('empty')}</td></tr>
              : filt.map((v) => (<tr key={v.variantId}><td style={td}>{v.variantId}</td><td style={td}><StatusBadge active={v.status === 1} t={t} /></td><td style={{ ...td, fontWeight: 500 }}>{v.productName}</td><td style={td}>{v.sku || '—'}</td><td style={td}><button style={iconBtn} title={t('assoc_add')} onClick={() => add(v.variantId)}><PlusIcon /></button></td></tr>))}
          </tbody>
        </table>
      </div>
    </div>
  )
}
