import { useEffect, useMemo, useRef, useState } from 'react'
import { createPortal } from 'react-dom'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  deleteProduct, duplicateProduct, fetchProductById, fetchProductOptions, fetchProducts, fetchProductStats, saveProduct, fetchProductTooltip,
  uploadProductMedia, deleteProductMedia, saveProductAttribute, deleteProductAttribute, saveProductRecipient, deleteProductRecipient, saveProductPrice,
  type ProductItem, type ProductStats, type ProductText, type ProductSeo, type TooltipVariant,
} from './api'
import type { T } from '../../shared/i18n'
import { VariantsTab, PricesTab, AttributesSection, FilesSection, ImagesSection, RecipientsSection, CategoryPickerModal, SiteTreeModal, DuplicateModal, InfoDot, SitemapIcon, StatusToggle, flattenCatNames, type PendingPrice, type PendingMedia } from './ProductTabs'
import { VariantEditor } from './ProductVariantEditor'
import type { UserOption, CountryOption } from './api'
import { fetchCatalogTree } from '../catalog/api'
import { DICT } from './dict'
import { makeT, fmtDate } from '../../shared/i18n'
import { card, inputCss, btnPrimary, btnGhost, iconBtn, th, td, label, hint, segBtn } from '../../shared/styles'
import { PencilIcon, TrashIcon, PlusIcon, GripIcon, FileDownIcon, TagIcon, FileTextIcon, LayoutIcon, CartIcon, PackageIcon, PackageCheckIcon, PackageXIcon, ToggleRightIcon } from '../../shared/icons'
import { StatusBadge, Kpi, ViewModeToggle, LegacyFrame } from '../../shared/widgets'
import { ColManager } from '../../shared/ColManager'
import { ExportModal } from '../../shared/ExportModal'
import { makeColStore, visibleCols, type ColDef } from '../../shared/columns'
import { openSubTab, closeSubTab, updateSubLabel } from '../../shared/subtabs'
import type { Option } from '../../shared/api'
import type { LangOption } from '../catalog/api'
import { Tabs, type TabDef } from '../../shared/Tabs'

/* Brique « Produits » (MelisCommerce) — full React, montée à /melis-commerce/product-list
 * (et /:id pour le formulaire en sous-onglet). Toggle New/Old → iframe legacy.
 * Logique inchangée : enregistrement via MelisComProductService::saveProduct (cf. contrôleur API).
 * Onglets repris du legacy : Principal / Textes / Variantes / SEO. */
const TOOL_MELIS_KEY = 'meliscommerce_product_list_container'
const COL_ORDER = ['id', 'status', 'image', 'reference', 'name', 'categories', 'created'] as const
const COL_LABEL: Record<string, string> = { id: 'col_id', status: 'col_status', image: 'col_image', reference: 'col_reference', name: 'col_name', categories: 'col_categories', created: 'col_created' }
const cols$ = makeColStore('melis-products-cols-v1', COL_ORDER)

function getCellExport(p: ProductItem, id: string, t: (k: string) => string): string | number {
  switch (id) {
    case 'id': return p.id
    case 'status': return p.status === 1 ? t('status_active') : t('status_inactive')
    case 'image': return p.image || ''
    case 'reference': return p.reference || ''
    case 'name': return p.name || ''
    case 'categories': return p.categories.join(', ')
    case 'created': return p.dateCreation || ''
    default: return ''
  }
}
const prodLabel = (p: ProductItem) => p.name || p.reference || `#${p.id}`

export default function ProductPage() {
  const { id } = useParams()
  const location = useLocation()
  const base = id ? location.pathname.slice(0, location.pathname.length - id.length - 1) : location.pathname
  if (id) return <ProductForm id={id} base={base} />
  return <ProductList base={base} />
}

function ProductThumb({ src, alt }: { src: string; alt: string }) {
  const box = { width: 40, height: 40, borderRadius: 6, border: '1px solid var(--color-border)', objectFit: 'cover' as const, background: 'var(--color-muted,rgba(0,0,0,.03))', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', color: 'var(--color-muted-foreground)' }
  if (!src) return <span style={box}><CartIcon /></span>
  return <img src={src} alt={alt} style={box} onError={(e) => { (e.target as HTMLImageElement).style.display = 'none' }} />
}

// Nom de produit avec tooltip au survol : détails des variantes (image/SKU/attributs/pays/prix/stocks).
function ProductNameCell({ id, name, t }: { id: number; name: string; t: T }) {
  const [show, setShow] = useState(false)
  const [data, setData] = useState<TooltipVariant[] | null>(null)
  const [pos, setPos] = useState({ x: 0, y: 0 })
  const ref = useRef<HTMLSpanElement>(null)
  function enter() {
    const r = ref.current?.getBoundingClientRect()
    setPos({ x: r ? r.left : 0, y: r ? r.bottom + 6 : 0 }); setShow(true)
    if (data === null) fetchProductTooltip(id).then((res) => setData(res.items)).catch(() => setData([]))
  }
  const cellTh = { padding: '6px 12px', textAlign: 'left' as const, fontSize: 11, textTransform: 'uppercase' as const, letterSpacing: '.04em', color: 'rgba(255,255,255,.7)', whiteSpace: 'nowrap' as const }
  const cellTd = { padding: '6px 12px', fontSize: 13, color: '#fff', whiteSpace: 'nowrap' as const }
  return (
    <span ref={ref} onMouseEnter={enter} onMouseLeave={() => setShow(false)} style={{ fontWeight: 500, cursor: 'default' }}>
      {name || '—'}
      {show && data && data.length > 0 && createPortal(
        <div style={{ position: 'fixed', top: pos.y, left: pos.x, zIndex: 9999, background: '#4a4a4a', borderRadius: 8, boxShadow: '0 8px 24px rgba(0,0,0,.3)', padding: 4, maxWidth: 640, overflow: 'auto', pointerEvents: 'none' }}>
          <table style={{ borderCollapse: 'collapse' }}>
            <thead><tr>
              <th style={cellTh}>{t('var_col_id')}</th><th style={cellTh}>{t('col_image')}</th><th style={cellTh}>{t('var_col_sku')}</th>
              <th style={cellTh}>{t('var_col_attrs')}</th><th style={cellTh}>{t('tt_country')}</th><th style={cellTh}>{t('tt_price')}</th><th style={cellTh}>{t('tt_stocks')}</th>
            </tr></thead>
            <tbody>
              {data.map((v) => (
                <tr key={v.id}>
                  <td style={cellTd}>{v.id}</td>
                  <td style={cellTd}>{v.image ? <img src={v.image} alt="" style={{ width: 28, height: 28, objectFit: 'cover', borderRadius: 4 }} /> : '🖼'}</td>
                  <td style={cellTd}>{v.sku || '—'}</td>
                  <td style={cellTd}>{v.attributes || '—'}</td>
                  <td style={cellTd}>🌐 {t('price_general')}</td>
                  <td style={cellTd}>{v.price == null ? '—' : v.price.toFixed(2)}</td>
                  <td style={cellTd}>{v.stock == null ? '—' : v.stock}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>,
        document.body
      )}
    </span>
  )
}

// ── Liste ─────────────────────────────────────────────────────────────────────
function ProductList({ base }: { base: string }) {
  const t = makeT(DICT)
  const navigate = useNavigate()
  const [mode, setMode] = useState<'react' | 'old'>('react')
  const [oldLoaded, setOldLoaded] = useState(false)
  const [items, setItems] = useState<ProductItem[]>([])
  const [stats, setStats] = useState<ProductStats | null>(null)
  const [loading, setLoading] = useState(false)
  const [searchInput, setSearchInput] = useState('')
  const [search, setSearch] = useState('')
  const [status, setStatus] = useState<number | null>(null)
  const [categoryId, setCategoryId] = useState<number>(0)
  const [categories, setCategories] = useState<Option[]>([])
  const [sortCol, setSortCol] = useState<string>('id')
  const [sortAsc, setSortAsc] = useState(false)
  const [toDelete, setToDelete] = useState<ProductItem | null>(null)
  const [toDup, setToDup] = useState<ProductItem | null>(null)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)

  useEffect(() => { fetchProductStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => { fetchProductOptions().then((o) => setCategories(o.categories)).catch(() => null) }, [])
  useEffect(() => {
    setLoading(true)
    fetchProducts({ search, status, categoryId: categoryId || null }).then((r) => setItems(r.items)).catch(() => null).finally(() => setLoading(false))
  }, [search, status, categoryId, tick])

  const sortVal = (p: ProductItem): string | number => {
    switch (sortCol) {
      case 'status': return p.status; case 'reference': return p.reference; case 'name': return p.name
      case 'categories': return p.categories.join(', '); case 'created': return p.dateCreation ?? ''; default: return p.id
    }
  }
  const sorted = useMemo(() => [...items].sort((a, b) => {
    const va = sortVal(a), vb = sortVal(b)
    const cmp = typeof va === 'number' && typeof vb === 'number' ? va - vb : String(va).localeCompare(String(vb))
    return sortAsc ? cmp : -cmp
  }), [items, sortCol, sortAsc])

  function toggleSort(id: string) { if (sortCol === id) setSortAsc((v) => !v); else { setSortCol(id); setSortAsc(true) } }
  async function confirmDelete() { if (!toDelete) return; try { await deleteProduct(toDelete.id); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) } }
  const FILTERS: { k: string; v: number | null; dot: string | null }[] = [
    { k: 'f_all', v: null, dot: null },
    { k: 'f_active', v: 1, dot: '#10b981' },
    { k: 'f_inactive', v: 0, dot: '#ef4444' },
  ]

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, height: '100%', boxSizing: 'border-box', overflow: mode === 'old' ? 'hidden' : 'auto' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('title')}</h1>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('subtitle')}</p>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <ViewModeToggle mode={mode} onReact={() => setMode('react')} onOld={() => { setMode('old'); setOldLoaded(true) }} />
          <button style={{ ...btnGhost, width: 36, padding: 0, justifyContent: 'center' }} onClick={() => setTick((x) => x + 1)} title={t('refresh')}>↻</button>
          <button style={btnPrimary} onClick={() => navigate(`${base}/new`)}><PlusIcon />{t('new')}</button>
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 20, flex: 1, minHeight: 0 }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 12 }}>
          <Kpi label={t('kpi_total')}    value={stats?.total    ?? null} icon={<PackageIcon />}      iconBg="rgba(59,130,246,0.1)"  iconColor="#3b82f6" />
          <Kpi label={t('kpi_active')}   value={stats?.active   ?? null} icon={<PackageCheckIcon />}  iconBg="rgba(16,185,129,0.1)"  iconColor="#10b981" />
          <Kpi label={t('kpi_inactive')} value={stats?.inactive ?? null} icon={<PackageXIcon />}      iconBg="rgba(239,68,68,0.1)"   iconColor="#ef4444" />
        </div>
        <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8 }}>
          <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 180, maxWidth: 384 }} value={searchInput} onChange={(e) => setSearchInput(e.target.value)} onKeyDown={(e) => e.key === 'Enter' && setSearch(searchInput.trim())} placeholder={t('search')} />
          <div style={{ display: 'inline-flex', alignItems: 'center', gap: 4, padding: 4, borderRadius: 8, border: '1px solid var(--color-border)', background: 'var(--color-muted,rgba(0,0,0,.04))' }}>
            {FILTERS.map((f) => (
              <button key={f.k} onClick={() => setStatus(f.v)} style={{ display: 'flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 6, border: 0, cursor: 'pointer', fontSize: 12, fontWeight: 500, background: status === f.v ? 'var(--color-card)' : 'transparent', color: status === f.v ? 'var(--color-foreground)' : 'var(--color-muted-foreground)', boxShadow: status === f.v ? '0 1px 2px rgba(0,0,0,.08)' : 'none' }}>
                {f.dot && <span style={{ width: 6, height: 6, borderRadius: '50%', background: f.dot, flexShrink: 0 }} />}
                {t(f.k)}
              </button>
            ))}
          </div>
          <select style={{ ...inputCss, height: 36, width: 'auto', minWidth: 180 }} value={categoryId} onChange={(e) => setCategoryId(Number(e.target.value))}>
            <option value={0}>{t('filter_category')}</option>
            {categories.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
          </select>
          <div style={{ marginLeft: 'auto', display: 'flex', gap: 8 }}>
            <div style={{ position: 'relative' }}>
              <button style={{ ...btnGhost, height: 36 }} onClick={() => setShowCols((v) => !v)}><GripIcon />{t('columns')}</button>
              {showCols && <ColManager cols={cols} labelFor={(id) => t(COL_LABEL[id])} onChange={setCols} onClose={() => setShowCols(false)} save={cols$.save} defaults={cols$.DEFAULT} t={t} />}
            </div>
            <button style={{ ...btnGhost, height: 36 }} onClick={() => setShowExport(true)}><FileDownIcon />{t('export')}</button>
          </div>
        </div>

        <div style={{ ...card, overflow: 'hidden' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 760 }}>
            <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
              <tr>
                {visibleCols(cols).map(({ id }) => (
                  <th key={id} style={{ ...th, cursor: id === 'image' ? 'default' : 'pointer', ...(id === 'id' ? { width: 70 } : {}) }} onClick={() => id !== 'image' && toggleSort(id)}>
                    {t(COL_LABEL[id])}{sortCol === id ? ` ${sortAsc ? '↑' : '↓'}` : ''}
                  </th>
                ))}
                <th style={{ ...th, width: 80 }} />
              </tr>
            </thead>
            <tbody>
              {sorted.length === 0 && !loading ? (
                <tr><td style={{ ...td, textAlign: 'center', color: 'var(--color-muted-foreground)', padding: '40px 16px' }} colSpan={visibleCols(cols).length + 1}>{t('empty')}</td></tr>
              ) : sorted.map((p) => (
                <tr key={p.id}>
                  {visibleCols(cols).map(({ id }) => (
                    <td key={id} style={{ ...td, ...(id === 'id' ? { color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' } : {}) }}>
                      {id === 'id' && p.id}
                      {id === 'status' && <StatusBadge active={p.status === 1} t={t} />}
                      {id === 'image' && <ProductThumb src={p.image} alt={p.name} />}
                      {id === 'reference' && (p.reference || '—')}
                      {id === 'name' && <ProductNameCell id={p.id} name={p.name} t={t} />}
                      {id === 'categories' && (p.categories.length ? p.categories.join(', ') : '—')}
                      {id === 'created' && <span style={{ color: 'var(--color-muted-foreground)' }}>{fmtDate(p.dateCreation)}</span>}
                    </td>
                  ))}
                  <td style={td}>
                    <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 4 }}>
                      <button style={iconBtn} title={t('var_duplicate')} onClick={() => setToDup(p)}>⧉</button>
                      <button style={iconBtn} title={t('edit')} onClick={() => navigate(`${base}/${p.id}`)}><PencilIcon /></button>
                      <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => setToDelete(p)}><TrashIcon /></button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          <div style={{ padding: '10px 16px', textAlign: 'center', fontSize: 12, color: 'var(--color-muted-foreground)' }}>{loading ? t('loading') : t('count', { n: items.length })}</div>
        </div>
      </div>

      {showExport && <ExportModal cols={cols} items={items} getCell={(p, id) => getCellExport(p, id, t)} labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')} sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />}

      {toDelete && (
        <div style={{ position: 'fixed', inset: 0, zIndex: 50, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
          <div style={{ ...card, padding: 24, width: '100%', maxWidth: 360 }}>
            <h3 style={{ fontSize: 16, fontWeight: 600, margin: 0 }}>{t('del_title')}</h3>
            <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', marginTop: 8 }}>{t('del_confirm', { u: prodLabel(toDelete) })}</p>
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 20 }}>
              <button style={btnGhost} onClick={() => setToDelete(null)}>{t('cancel')}</button>
              <button style={{ ...btnGhost, borderColor: '#fca5a5', color: '#dc2626' }} onClick={confirmDelete}>{t('del')}</button>
            </div>
          </div>
        </div>
      )}

      {toDup && (
        <DuplicateModal t={t} title={t('dup_prd_title')} intro={t('dup_prd_intro')} fieldLabel={`${t('dup_ref')} (${toDup.reference})`} fieldPlaceholder={t('dup_ref_ph')}
          onClose={() => setToDup(null)}
          onConfirm={async (o) => { try { await duplicateProduct(toDup.id, { duplicateImages: o.duplicateImages, duplicateDocuments: o.duplicateDocuments, putOnline: o.putOnline, reference: o.field }) } catch { /* */ } setToDup(null); setTick((x) => x + 1) }} />
      )}
    </div>
  )
}

// ── Sélecteur de langue vertical (drapeau + nom) ────────────────────────────────
function LangSwitcher({ languages, value, onChange }: { languages: LangOption[]; value: number; onChange: (id: number) => void }) {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
      {languages.map((l) => {
        const on = l.id === value
        return (
          <button key={l.id} onClick={() => onChange(l.id)}
            style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, padding: '8px 10px', borderRadius: 6, cursor: 'pointer', textAlign: 'left',
              border: '1px solid ' + (on ? 'var(--color-primary)' : 'var(--color-border)'), background: on ? 'var(--color-primary)' : 'transparent',
              color: on ? 'var(--color-primary-foreground,#fff)' : 'inherit', fontWeight: on ? 600 : 400, fontSize: 14 }}>
            <span>{l.name}</span>
            {l.flag ? <img src={`data:image/png;base64,${l.flag}`} alt="" style={{ width: 22, height: 22, borderRadius: 3 }} /> : null}
          </button>
        )
      })}
    </div>
  )
}

// ── Formulaire (sous-onglet) — onglets Principal / Textes / Variantes / SEO ──────
function ProductForm({ id, base }: { id: string; base: string }) {
  const t = makeT(DICT)
  const navigate = useNavigate()
  const location = useLocation()
  const isEdit = id !== 'new'
  const productId = isEdit ? parseInt(id) : null
  const subTabPath = `${base}/${id}`

  const [reference, setReference] = useState('')
  const [stockLow, setStockLow] = useState('')
  const [active, setActive] = useState(true)
  const [texts, setTexts] = useState<ProductText[]>([])
  const [seo, setSeo] = useState<ProductSeo[]>([])
  const [categories, setCategories] = useState<Option[]>([])
  const [attrOptions, setAttrOptions] = useState<Option[]>([])
  const [countries, setCountries] = useState<CountryOption[]>([])
  const [currencies, setCurrencies] = useState<Option[]>([])
  const [users, setUsers] = useState<UserOption[]>([])
  const [pageLinks, setPageLinks] = useState({ link1: '', link2: '', link3: '' })
  const [showCatModal, setShowCatModal] = useState(false)
  const [treePick, setTreePick] = useState<'link1' | 'link2' | 'link3' | null>(null)
  const [variantEdit, setVariantEdit] = useState<number | 'new' | null>(null)
  const [pendingImages, setPendingImages] = useState<PendingMedia[]>([])
  const [pendingFiles, setPendingFiles] = useState<PendingMedia[]>([])
  const [pendingAttrIds, setPendingAttrIds] = useState<number[]>([])
  const [pendingRecipientIds, setPendingRecipientIds] = useState<number[]>([])
  const [pendingPrices, setPendingPrices] = useState<PendingPrice[]>([])
  const [pendingDeleteAttrPattIds, setPendingDeleteAttrPattIds] = useState<number[]>([])
  const [pendingDeleteRecipientSeaIds, setPendingDeleteRecipientSeaIds] = useState<number[]>([])
  const [pendingDeleteFileIds, setPendingDeleteFileIds] = useState<number[]>([])
  const [pendingDeleteImageIds, setPendingDeleteImageIds] = useState<number[]>([])
  const [catNames, setCatNames] = useState<Record<number, string>>({})
  const [categoryIds, setCategoryIds] = useState<number[]>([])
  const [languages, setLanguages] = useState<LangOption[]>([])
  const [lang, setLang] = useState<number>(0)
  const [loading, setLoading] = useState(false)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [errRef, setErrRef] = useState(false)
  const [errName, setErrName] = useState(false)
  const [saved, setSaved] = useState(false)
  const [tab, setTab] = useState('main')

  const TABS: TabDef[] = [
    { key: 'main',     label: t('tab_main'),     icon: <TagIcon /> },
    { key: 'text',     label: t('tab_text'),     icon: <FileTextIcon /> },
    { key: 'variants', label: t('tab_variants'), icon: <LayoutIcon /> },
    { key: 'seo',      label: t('tab_seo'),      icon: <TagIcon /> },
    { key: 'prices',   label: t('tab_prices'),   icon: <span style={{ fontWeight: 700 }}>€</span> },
  ]

  useEffect(() => {
    fetchProductOptions().then((o) => {
      setCategories(o.categories); setAttrOptions(o.attributes); setUsers(o.users); setLanguages(o.languages); setCountries(o.countries); setCurrencies(o.currencies); setLang((v) => v || o.languages[0]?.id || 0)
      setTexts((prev) => prev.length ? prev : o.languages.map((l) => ({ langId: l.id, langName: l.name, name: '', description: '' })))
      setSeo((prev) => prev.length ? prev : o.languages.map((l) => ({ langId: l.id, langName: l.name, pageId: '', url: '', urlRedirect: '', url301: '', metaTitle: '', metaDescription: '' })))
    }).catch(() => null)
    // Arbre catalogues/catégories → noms pour les chips (catalogues inclus, contrairement aux options).
    fetchCatalogTree().then((r) => { const m: Record<number, string> = {}; flattenCatNames(r.items, m); setCatNames(m) }).catch(() => null)
  }, [])
  // Reset all pending/delta state when switching between products (React Router reuses the component).
  useEffect(() => {
    setPendingImages([]); setPendingFiles([])
    setPendingAttrIds([]); setPendingRecipientIds([]); setPendingPrices([])
    setPendingDeleteAttrPattIds([]); setPendingDeleteRecipientSeaIds([])
    setPendingDeleteFileIds([]); setPendingDeleteImageIds([])
    setSaved(false); setError(null)
  }, [productId])

  useEffect(() => {
    if (!productId) return
    setLoading(true)
    fetchProductById(productId).then((p) => {
      setReference(p.reference); setStockLow(p.stockLow === null ? '' : String(p.stockLow)); setActive(p.status === 1)
      setTexts(p.texts); setSeo(p.seo); setCategoryIds(p.categoryIds)
      if (p.pageLinks) setPageLinks(p.pageLinks)
    }).catch(() => navigate(base)).finally(() => setLoading(false))
  }, [productId, navigate, base])

  useEffect(() => {
    if (isEdit) closeSubTab(base, `${base}/new`)
    openSubTab(base, { id: subTabPath, label: isEdit ? t('loading') : t('new'), path: subTabPath })
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])
  const primaryName = texts.find((x) => x.name.trim())?.name ?? ''
  useEffect(() => {
    const lbl = (primaryName || reference).trim()
    if (isEdit && lbl) updateSubLabel(base, subTabPath, lbl || `#${productId}`)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [primaryName, reference])

  function setText(langId: number, field: 'name' | 'description', value: string) { setTexts((p) => p.map((x) => x.langId === langId ? { ...x, [field]: value } : x)); setError(null) }
  function setSeoField(langId: number, field: keyof ProductSeo, value: string) { setSeo((p) => p.map((x) => x.langId === langId ? { ...x, [field]: value } : x)) }
  function toggleCategory(cid: number) { setCategoryIds((p) => p.includes(cid) ? p.filter((x) => x !== cid) : [...p, cid]) }

  async function submit() {
    if (!reference.trim()) { setErrRef(true); setError(t('err_reference')); setTab('main'); return }
    if (!texts.some((x) => x.name.trim())) { setErrName(true); setError(t('err_name')); setTab('text'); return }
    setSaving(true); setError(null)
    try {
      const result = await saveProduct({
        id: productId, reference: reference.trim(), status: active,
        stockLow: stockLow.trim() === '' ? null : parseInt(stockLow, 10) || 0,
        texts: texts.map((x) => ({ langId: x.langId, name: x.name.trim(), description: x.description })),
        seo: seo.map((s) => ({ langId: s.langId, pageId: s.pageId, url: s.url, urlRedirect: s.urlRedirect, url301: s.url301, metaTitle: s.metaTitle, metaDescription: s.metaDescription })),
        categoryIds, pageLinks,
      })
      const meaningfulPrices = pendingPrices.filter((p) => p.net !== null || p.gross !== null || p.vatPercent !== null || p.vatPrice !== null || p.otherTax !== null)
      if (!isEdit) {
        const pid = result.id
        await Promise.allSettled([
          ...pendingImages.map((m) => uploadProductMedia(pid, { ...m, kind: 'image' })),
          ...pendingFiles.map((m) => uploadProductMedia(pid, { ...m, kind: 'file' })),
          ...pendingAttrIds.map((aid) => saveProductAttribute(pid, aid)),
          ...pendingRecipientIds.map((uid) => saveProductRecipient(pid, uid, stockLow.trim() === '' ? null : parseInt(stockLow, 10) || 0)),
          ...meaningfulPrices.map((p) => saveProductPrice(pid, { id: null, countryId: p.countryId, currency: p.currency, net: p.net, gross: p.gross, vatPercent: p.vatPercent, vatPrice: p.vatPrice, otherTax: p.otherTax })),
        ])
        setSaved(true)
        closeSubTab(base, `${base}/new`)
        const newPath = `${base}/${pid}`
        openSubTab(base, { id: newPath, label: reference.trim(), path: newPath })
        navigate(newPath, { replace: true, state: { tab } })
      } else {
        const stockLevelNum = stockLow.trim() === '' ? null : parseInt(stockLow, 10) || 0
        await Promise.allSettled([
          ...meaningfulPrices.map((p) => saveProductPrice(productId!, { id: p.id ?? null, countryId: p.countryId, currency: p.currency, net: p.net, gross: p.gross, vatPercent: p.vatPercent, vatPrice: p.vatPrice, otherTax: p.otherTax })),
          ...pendingAttrIds.map((aid) => saveProductAttribute(productId!, aid)),
          ...pendingRecipientIds.map((uid) => saveProductRecipient(productId!, uid, stockLevelNum)),
          ...pendingImages.map((m) => uploadProductMedia(productId!, { ...m, kind: 'image' })),
          ...pendingFiles.map((m) => uploadProductMedia(productId!, { ...m, kind: 'file' })),
          ...pendingDeleteAttrPattIds.map((pattId) => deleteProductAttribute(productId!, pattId)),
          ...pendingDeleteRecipientSeaIds.map((seaId) => deleteProductRecipient(productId!, seaId)),
          ...pendingDeleteFileIds.map((id) => deleteProductMedia(productId!, id)),
          ...pendingDeleteImageIds.map((id) => deleteProductMedia(productId!, id)),
        ])
        setSaved(true)
        setTimeout(() => navigate(base), 500)
      }
    } catch (e) { setError(e instanceof Error ? e.message : t('err_save')) } finally { setSaving(false) }
  }

  // After save navigates new→edit, apply the intended tab/state.
  const stateApplied = useRef(false)
  useEffect(() => {
    if (!isEdit) { stateApplied.current = false; return }
    if (stateApplied.current) return
    stateApplied.current = true
    const s = (location.state as { tab?: string; openVariantEditor?: boolean }) ?? {}
    if (s.tab) setTab(s.tab)
    if (s.openVariantEditor) setVariantEdit('new')
  }, [isEdit]) // eslint-disable-line react-hooks/exhaustive-deps

  const secTitle = { fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 12px' } as const
  const labelRow = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 } as const

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, height: '100%', boxSizing: 'border-box', overflow: 'auto' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <button style={{ ...iconBtn, width: 32, height: 32 }} onClick={() => navigate(base)} title={t('back')}>←</button>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{isEdit ? t('edit_title') : t('new_title')}</h1>
        </div>
        <div style={{ display: 'flex', gap: 10, alignItems: 'center' }}>
          {saved && <span style={{ fontSize: 14, color: '#059669' }}>{t('saved')}</span>}
          <button style={btnPrimary} onClick={submit} disabled={saving || loading}>{saving ? '…' : t('save')}</button>
        </div>
      </div>

      <Tabs tabs={TABS} active={tab} onChange={setTab} />

      {error && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14 }}>{error}</div>}

      {loading ? (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', minHeight: '40vh', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : tab === 'main' ? (
        <div>
        <div style={{ borderBottom: '1px solid var(--color-border)', paddingBottom: 12, marginBottom: 20 }}>
          <h2 style={{ fontSize: 18, fontWeight: 600, margin: 0 }}>{t('tab_main')}</h2>
        </div>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr minmax(200px,220px)', gap: 20, alignItems: 'start' }}>
          {/* Col 1 : Info card */}
          <div style={{ ...card, padding: 24, display: 'flex', flexDirection: 'column', gap: 24 }}>
            <section>
              <h3 style={secTitle}>{t('sec_general')}</h3>
              <div style={labelRow}><label style={{ ...label, color: errRef ? '#dc2626' : undefined }}>{t('f_reference')}</label><InfoDot text={t('tip_reference')} /></div>
              <input style={{ ...inputCss, ...(errRef ? { borderColor: '#dc2626' } : {}) }} value={reference} onChange={(e) => { setReference(e.target.value); setError(null); setErrRef(false) }} placeholder={t('f_reference_ph')} autoComplete="off" />
            </section>
            <section>
              <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 12 }}>
                <h3 style={{ ...secTitle, margin: 0 }}>{t('sec_categories')}</h3>
                <button style={btnGhost} onClick={() => setShowCatModal(true)}>{t('cat_see_all')}</button>
              </div>
              {categoryIds.length === 0 ? <p style={{ ...hint, margin: 0 }}>{t('cat_none_selected')}</p> : (
                <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
                  {categoryIds.map((cid) => (
                    <span key={cid} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
                      {catNames[cid] ?? categories.find((x) => x.id === cid)?.name ?? `#${cid}`}
                      <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-muted-foreground)' }} title={t('del')} onClick={() => toggleCategory(cid)}>✕</button>
                    </span>
                  ))}
                </div>
              )}
            </section>
            <FilesSection productId={productId} t={t} countries={countries} pendingFiles={pendingFiles} onAddPendingFile={(m) => setPendingFiles((p) => [...p, m])} onRemovePendingFile={(i) => setPendingFiles((p) => p.filter((_, idx) => idx !== i))} onMarkDeleteFile={(id) => setPendingDeleteFileIds((p) => [...p, id])} />
            <AttributesSection productId={productId} options={attrOptions} t={t} pendingAttrIds={pendingAttrIds} onTogglePendingAttr={(id) => setPendingAttrIds((p) => p.includes(id) ? p.filter((x) => x !== id) : [...p, id])} onMarkDeleteAttr={(pattId) => setPendingDeleteAttrPattIds((p) => [...p, pattId])} />
            <section>
              <h3 style={secTitle}>{t('sec_alert')}</h3>
              <div style={labelRow}><label style={label}>{t('f_stock_alert')}</label><InfoDot text={t('tip_stock_alert')} /></div>
              <input style={inputCss} type="number" min={0} value={stockLow} onChange={(e) => setStockLow(e.target.value)} placeholder={t('f_stock_ph')} autoComplete="off" />
              <div style={{ marginTop: 16 }}>
                <RecipientsSection productId={productId} users={users} stockLevel={stockLow.trim() === '' ? null : parseInt(stockLow, 10) || 0} t={t} pendingRecipientIds={pendingRecipientIds} onTogglePendingRecipient={(id) => setPendingRecipientIds((p) => p.includes(id) ? p.filter((x) => x !== id) : [...p, id])} onMarkDeleteRecipient={(seaId) => setPendingDeleteRecipientSeaIds((p) => [...p, seaId])} />
              </div>
            </section>
            <section>
              <h3 style={secTitle}>{t('sec_page_assoc')}</h3>
              {([['link1', 1], ['link2', 2], ['link3', 3]] as const).map(([k, n]) => (
                <div key={k} style={{ marginTop: n === 1 ? 0 : 14 }}>
                  <div style={labelRow}><label style={label}>{t('f_assoc_page', { n })}</label><InfoDot text={t('tip_assoc_page')} /></div>
                  <div style={{ display: 'flex', gap: 6 }}>
                    <input style={{ ...inputCss, flex: 1 }} value={pageLinks[k]} onChange={(e) => setPageLinks((p) => ({ ...p, [k]: e.target.value }))} placeholder={t('f_assoc_page_ph')} autoComplete="off" />
                    <button style={{ ...btnGhost, width: 40, padding: 0, justifyContent: 'center' }} title={t('sitetree_pick')} onClick={() => setTreePick(k)}><SitemapIcon /></button>
                  </div>
                </div>
              ))}
            </section>
          </div>

          {/* Col 2 : Images card */}
          <div style={{ ...card, padding: 24 }}>
            <ImagesSection productId={productId} t={t} countries={countries} pendingImages={pendingImages} onAddPendingImage={(m) => setPendingImages((p) => [...p, m])} onRemovePendingImage={(i) => setPendingImages((p) => p.filter((_, idx) => idx !== i))} onMarkDeleteImage={(id) => setPendingDeleteImageIds((p) => [...p, id])} />
          </div>

          {/* Col 3 : Status card */}
          <div style={{ ...card, padding: 16 }}>
            <h3 style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 12px' }}>
              <ToggleRightIcon />{t('status_label')}
            </h3>
            <button type="button" onClick={() => setActive((v) => !v)} style={{ display: 'flex', alignItems: 'center', gap: 10, background: 'none', border: 0, padding: 0, cursor: 'pointer' }}>
              <div style={{ position: 'relative', width: 36, height: 20, borderRadius: 999, background: active ? '#22c55e' : 'var(--color-muted-foreground)', transition: 'background .15s', flexShrink: 0 }}>
                <span style={{ position: 'absolute', top: 2, left: active ? 18 : 2, width: 16, height: 16, borderRadius: 999, background: '#fff', transition: 'left .15s', boxShadow: '0 1px 2px rgba(0,0,0,.3)' }} />
              </div>
              <span style={{ fontSize: 14, fontWeight: 500, color: active ? '#16a34a' : 'var(--color-muted-foreground)' }}>
                {active ? t('online') : t('offline')}
              </span>
            </button>
          </div>
        </div>
        </div>
      ) : tab === 'text' ? (
        <div style={{ display: 'grid', gridTemplateColumns: '150px 1fr', gap: 14, maxWidth: 760 }}>
          <LangSwitcher languages={languages} value={lang} onChange={setLang} />
          {texts.filter((x) => x.langId === lang).map((tx) => (
            <div key={tx.langId}>
              <div style={labelRow}><label style={{ ...label, color: errName ? '#dc2626' : undefined }}>{t('f_name')}</label></div>
              <input style={{ ...inputCss, ...(errName ? { borderColor: '#dc2626' } : {}) }} value={tx.name} onChange={(e) => { setText(tx.langId, 'name', e.target.value); setErrName(false) }} placeholder={t('f_name_ph')} autoComplete="off" />
              <div style={{ ...labelRow, marginTop: 14 }}><label style={label}>{t('f_description')}</label></div>
              <textarea style={{ ...inputCss, minHeight: 140, resize: 'vertical', paddingTop: 8 }} value={tx.description} onChange={(e) => setText(tx.langId, 'description', e.target.value)} placeholder={t('f_description_ph')} />
            </div>
          ))}
        </div>
      ) : tab === 'variants' ? (
        variantEdit !== null && productId ? (
          <VariantEditor productId={productId} variantId={variantEdit === 'new' ? null : variantEdit} countries={countries} currencies={currencies} languages={languages} t={t} onClose={() => setVariantEdit(null)} onSaved={() => { /* list reloads on close */ }} />
        ) : (
          <VariantsTab productId={productId} t={t} onAdd={() => setVariantEdit('new')} onEdit={(id) => setVariantEdit(id)} />
        )
      ) : tab === 'prices' ? (
        <PricesTab productId={productId} countries={countries} currencies={currencies} t={t} pendingPrices={pendingPrices} onBufferPrice={(p) => setPendingPrices((prev) => [...prev.filter((x) => x.countryId !== p.countryId), p])} />
      ) : (
        <div style={{ display: 'grid', gridTemplateColumns: '150px 1fr', gap: 14, maxWidth: 760 }}>
            <LangSwitcher languages={languages} value={lang} onChange={setLang} />
            {seo.filter((s) => s.langId === lang).map((s) => {
              const SEO_FIELDS: [keyof ProductSeo, string, string][] = [
                ['pageId', 'seo_page_id', 'tip_prd_seo_page_id'],
                ['metaTitle', 'seo_meta_title', 'tip_prd_seo_meta_title'],
                ['metaDescription', 'seo_meta_desc', 'tip_prd_seo_meta_desc'],
                ['url', 'seo_url', 'tip_prd_seo_url'],
                ['urlRedirect', 'seo_url_redirect', 'tip_prd_seo_url_redirect'],
                ['url301', 'seo_url_301', 'tip_prd_seo_url_301'],
              ]
              return (
                <div key={s.langId}>
                  {SEO_FIELDS.map(([k, lbl, tip], i) => (
                    <div key={k} style={{ marginTop: i === 0 ? 0 : 14 }}>
                      <div style={labelRow}><label style={label}>{t(lbl)}</label><InfoDot text={t(tip)} /></div>
                      {k === 'metaDescription'
                        ? <textarea style={{ ...inputCss, minHeight: 90, resize: 'vertical', paddingTop: 8 }} value={s[k] as string} onChange={(e) => setSeoField(s.langId, k, e.target.value)} placeholder={t('seo_meta_desc_ph')} />
                        : <input style={inputCss} value={s[k] as string} onChange={(e) => setSeoField(s.langId, k, e.target.value)} autoComplete="off" />}
                    </div>
                  ))}
                </div>
              )
            })}
          </div>
      )}

      {showCatModal && (
        <CategoryPickerModal initialSelected={categoryIds} languages={languages} t={t} onClose={() => setShowCatModal(false)} onApply={(ids) => setCategoryIds(ids)} />
      )}
      {treePick && (
        <SiteTreeModal t={t} onClose={() => setTreePick(null)} onInsert={(pageId) => setPageLinks((p) => ({ ...p, [treePick]: String(pageId) }))} />
      )}
    </div>
  )
}
