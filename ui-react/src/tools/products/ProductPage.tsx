import { Fragment, useEffect, useRef, useState, type ReactNode } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  deleteProduct, duplicateProduct, fetchProductById, fetchProductOptions, fetchProducts, fetchAllProducts, fetchProductStats, saveProduct, fetchProductTooltip,
  uploadProductMedia, deleteProductMedia, saveProductAttribute, deleteProductAttribute, saveProductRecipient, deleteProductRecipient, saveProductPrice,
  type ProductItem, type ProductStats, type ProductText, type ProductExtraText, type ProductSeo, type TooltipVariant, type ProductSortKey, type TextType,
} from './api'
import { useKeysetList } from '../../shared/use-keyset-list'
import { makeCache } from '../../shared/listCache'
import type { T } from '../../shared/i18n'
import { VariantsTab, PricesTab, AttributesSection, FilesSection, ImagesSection, RecipientsSection, CategoryPickerModal, SiteTreeModal, DuplicateModal, AddTextTypeModal, InfoDot, SitemapIcon, StatusToggle, flattenCatNames, VariantTooltipTable, type PendingPrice, type PendingMedia } from './ProductTabs'
import { VariantEditor } from './ProductVariantEditor'
import { MelisToolEditor } from '../../shared/MelisToolEditor'
import type { UserOption, CountryOption } from './api'
import { fetchCatalogTree } from '../catalog/api'
import { DICT } from './dict'
import { makeT, fmtDate } from '../../shared/i18n'
import { card, inputCss, btnPrimary, btnGhost, iconBtn, th, td, label, hint, segBtn } from '../../shared/styles'
import { PencilIcon, TrashIcon, PlusIcon, GripIcon, FileDownIcon, TagIcon, FileTextIcon, LayoutIcon, CartIcon, PackageIcon, PackageCheckIcon, PackageXIcon, ResetIcon, SortIcon, Spinner } from '../../shared/icons'
import { StatusBadge, Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { useCaps } from '../../shared/useCaps'
import { ColManager } from '../../shared/ColManager'
import { ExportModal } from '../../shared/ExportModal'
import { makeColStore, visibleCols, effectiveCols, type ColDef } from '../../shared/columns'
import { ExpandToggle, HiddenColsRow } from '../../shared/ExpandableRow'
import { useIsNarrow } from '../../shared/useIsNarrow'
import { openSubTab, closeSubTab, updateSubLabel } from '../../shared/subtabs'
import type { Option } from '../../shared/api'
import type { LangOption } from '../catalog/api'
import { Tabs, type TabDef } from '../../shared/Tabs'

/* Brique « Produits » (MelisCommerce) — full React, montée à /melis-commerce/product-list
 * (et /:id pour le formulaire en sous-onglet). Toggle New/Old → iframe legacy.
 * Logique inchangée : enregistrement via MelisComProductService::saveProduct (cf. contrôleur API).
 * Onglets repris du legacy : Principal / Textes / Variants / SEO. */
const TOOL_MELIS_KEY = 'meliscommerce_product_list_container'
const COL_ORDER = ['id', 'status', 'image', 'reference', 'name', 'categories', 'created'] as const
const COL_LABEL: Record<string, string> = { id: 'col_id', status: 'col_status', image: 'col_image', reference: 'col_reference', name: 'col_name', categories: 'col_categories', created: 'col_created' }
const cols$ = makeColStore('melis-products-cols-v2', COL_ORDER)
const ESSENTIAL_COLS = new Set(['name'])
// Colonnes triables côté serveur — doit matcher le sortMap backend (« image »/« categories » exclus).
const SORTABLE = new Set<ProductSortKey>(['id', 'status', 'reference', 'name', 'created'])

const listCache = makeCache<{
  items: ProductItem[]; stats: ProductStats | null; total: number; cursor: string | null; hasMore: boolean
  search: string; searchInput: string; status: number | null; categoryId: number; sortCol: string; sortDir: 'asc' | 'desc'; mode: 'react' | 'old'
}>()

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

// Nom de produit avec tooltip au survol : détails des variants (image/SKU/attributs/pays/prix/stocks).
function ProductNameCell({ id, name, t, onOpenVariant }: { id: number; name: string; t: T; onOpenVariant?: (productId: number, variantId: number) => void }) {
  const [show, setShow] = useState(false)
  const [data, setData] = useState<TooltipVariant[] | null>(null)
  const [pos, setPos] = useState({ x: 0, y: 0 })
  const ref = useRef<HTMLSpanElement>(null)
  // Le tooltip flotte SOUS le nom (pas un enfant DOM) : quitter le déclencheur pour l'atteindre
  // traverse un instant "hors survol" — un délai court laisse la souris arriver dessus avant
  // fermeture, pour pouvoir cliquer un variant dedans au lieu qu'il disparaisse aussitôt.
  const closeTimer = useRef<number | null>(null)
  function clearCloseTimer() { if (closeTimer.current) { window.clearTimeout(closeTimer.current); closeTimer.current = null } }
  function scheduleClose() { clearCloseTimer(); closeTimer.current = window.setTimeout(() => setShow(false), 150) }
  function enter() {
    clearCloseTimer()
    const r = ref.current?.getBoundingClientRect()
    setPos({ x: r ? r.left : 0, y: r ? r.bottom + 6 : 0 }); setShow(true)
    if (data === null) fetchProductTooltip(id).then((res) => setData(res.items)).catch(() => setData([]))
  }
  return (
    <span ref={ref} onMouseEnter={enter} onMouseLeave={scheduleClose} title={name || undefined}
      style={{ fontWeight: 500, cursor: 'default', display: 'inline-block', maxWidth: 220, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', verticalAlign: 'middle' }}>
      {name || '—'}
      {show && data && data.length > 0 && (
        <VariantTooltipTable items={data} pos={pos} t={t} onMouseEnter={clearCloseTimer} onMouseLeave={scheduleClose}
          onRowClick={onOpenVariant ? (variantId) => { clearCloseTimer(); setShow(false); onOpenVariant(id, variantId) } : undefined} />
      )}
    </span>
  )
}

// ── Liste ─────────────────────────────────────────────────────────────────────
function ProductList({ base }: { base: string }) {
  const t = makeT(DICT)
  const navigate = useNavigate()
  const { can } = useCaps(TOOL_MELIS_KEY)
  const narrow = useIsNarrow()
  const [mode, setMode] = useState<'react' | 'old'>(listCache.get()?.mode ?? 'react')
  // Non-persistent bricks get fully unmounted when the tab isn't active and rebuilt from
  // scratch when revisited — only `mode` survives that via listCache. If `oldLoaded`
  // reinitialized to false regardless, coming back to a tab left in "Old" view rendered
  // neither the (gated-by-oldLoaded) LegacyFrame nor the (gated-by-mode==='react') React
  // view: a blank tab. Derive the initial value from `mode` so a remount picks the legacy
  // iframe back up immediately, same as `mode` itself does.
  const [oldLoaded, setOldLoaded] = useState(() => (listCache.get()?.mode ?? 'react') === 'old')
  const cached = listCache.get()
  const [stats, setStats] = useState<ProductStats | null>(cached?.stats ?? null)
  const [searchInput, setSearchInput] = useState(cached?.searchInput ?? '')
  const [search, setSearch] = useState(cached?.search ?? '')
  const [status, setStatus] = useState<number | null>(cached?.status ?? null)
  const [categoryId, setCategoryId] = useState<number>(cached?.categoryId ?? 0)
  const [categories, setCategories] = useState<Option[]>([])
  const [toDelete, setToDelete] = useState<ProductItem | null>(null)
  const [toDup, setToDup] = useState<ProductItem | null>(null)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [exportItems, setExportItems] = useState<ProductItem[]>([])
  const [exporting, setExporting] = useState(false)
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }

  // Scroll infini + tri server-side + keyset (mutualisé). Ordre legacy = id desc.
  const {
    items, setItems, total, loading, hasMore, sentinelRef, sortCol, sortDir, setSortCol, setSortDir, toggleSort, snapshot,
  } = useKeysetList<ProductItem>({
    fetcher: (a) => fetchProducts({ ...a, sort: a.sort as ProductSortKey, search, status, categoryId: categoryId || null }),
    deps: [search, status, categoryId, tick],
    defaultSort: 'id',
    defaultDir: 'desc',
    initial: cached ? { items: cached.items, total: cached.total, cursor: cached.cursor, hasMore: cached.hasMore, sortCol: cached.sortCol, sortDir: cached.sortDir } : undefined,
    skipInitial: !!(cached && cached.items.length),
  })

  const cacheRef = useRef({ ...snapshot(), stats, search, searchInput, status, categoryId, mode })
  useEffect(() => { cacheRef.current = { ...snapshot(), stats, search, searchInput, status, categoryId, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchProductStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => { fetchProductOptions().then((o) => setCategories(o.categories)).catch(() => null) }, [])

  // Réinitialiser les filtres : recherche + statut + catégorie + tri par défaut (id desc), puis refetch.
  // On vide `items` : sinon les lignes restent affichées pendant le refetch et le clic paraît sans effet.
  function resetFilters() {
    setSearchInput(''); setSearch('')
    setStatus(null)
    setCategoryId(0)
    setSortCol('id'); setSortDir('desc')
    setItems([])
    setTick((x) => x + 1)
  }
  async function confirmDelete() { if (!toDelete) return; try { await deleteProduct(toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) } }
  // Export : le keyset ne charge qu'une page → on récupère TOUT le jeu filtré via curseur avant d'ouvrir.
  async function openExport() {
    setExporting(true)
    try { const all = await fetchAllProducts({ search, status, categoryId: categoryId || null }); setExportItems(all); setShowExport(true) }
    catch { /* ignore */ } finally { setExporting(false) }
  }
  // Depuis le tooltip variants (survol du nom) : ouvre le produit sur l'onglet Variants, ce variant édité.
  function openVariant(productId: number, variantId: number) { navigate(`${base}/${productId}`, { state: { tab: 'variants', openVariantEditor: variantId } }) }
  const FILTERS: { k: string; v: number | null; dot: string | null }[] = [
    { k: 'f_all', v: null, dot: null },
    { k: 'f_active', v: 1, dot: '#10b981' },
    { k: 'f_inactive', v: 0, dot: '#ef4444' },
  ]

  const eCols = effectiveCols(cols, ESSENTIAL_COLS, narrow)
  const visible = visibleCols(eCols)
  const hasHidden = eCols.some((c) => !c.visible)
  const totalCols = visible.length + 1 + (hasHidden ? 1 : 0)
  function cellContent(p: ProductItem, id: string): ReactNode {
    switch (id) {
      case 'id': return p.id
      case 'status': return <StatusBadge active={p.status === 1} t={t} />
      case 'image': return <ProductThumb src={p.image} alt={p.name} />
      case 'reference': return p.reference || '—'
      case 'name': return <ProductNameCell id={p.id} name={p.name} t={t} onOpenVariant={openVariant} />
      case 'categories': return p.categories.length ? p.categories.join(', ') : '—'
      case 'created': return <span style={{ color: 'var(--color-muted-foreground)' }}>{fmtDate(p.dateCreation)}</span>
      default: return null
    }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box', ...(mode === 'old' ? { height: '100%', overflow: 'hidden' } : {}) }}>
      <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 16 }}>
        <div>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('title')}</h1>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('subtitle')}</p>
        </div>
        <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', justifyContent: 'flex-end', gap: 8 }}>
          <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
            <ViewModeToggle mode={mode} onReact={() => setMode('react')} onOld={() => { setMode('old'); setOldLoaded(true) }} />
            <button style={{ ...btnGhost, width: 36, padding: 0, justifyContent: 'center', flexShrink: 0 }} onClick={() => setTick((x) => x + 1)} title={t('refresh')}>↻</button>
          </div>
          {can('create') && <button style={btnPrimary} onClick={() => navigate(`${base}/new`)}><PlusIcon />{t('new')}</button>}
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 20, flex: 1, minHeight: 0 }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(130px, 1fr))', gap: 12 }}>
          <Kpi label={t('kpi_total')}    value={stats?.total    ?? null} icon={<PackageIcon />}      iconBg="rgba(59,130,246,0.1)"  iconColor="#3b82f6" />
          <Kpi label={t('kpi_active')}   value={stats?.active   ?? null} icon={<PackageCheckIcon />}  iconBg="rgba(16,185,129,0.1)"  iconColor="#10b981" />
          <Kpi label={t('kpi_inactive')} value={stats?.inactive ?? null} icon={<PackageXIcon />}      iconBg="rgba(239,68,68,0.1)"   iconColor="#ef4444" />
        </div>
        <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8 }}>
          <input style={{ ...inputCss, height: 36, flex: narrow ? undefined : '0 1 200px', minWidth: narrow ? undefined : 120, maxWidth: narrow ? undefined : 200, flexBasis: narrow ? '100%' : undefined }} value={searchInput} onChange={(e) => setSearchInput(e.target.value)} onKeyDown={(e) => e.key === 'Enter' && setSearch(searchInput.trim())} placeholder={t('search')} />
          <div style={{ display: 'flex', alignItems: 'center', gap: 4, padding: 4, borderRadius: 8, border: '1px solid var(--color-border)', background: 'var(--color-muted,rgba(0,0,0,.04))', width: narrow ? '100%' : undefined, flexBasis: narrow ? '100%' : undefined }}>
            {FILTERS.map((f) => (
              <button key={f.k} onClick={() => setStatus(f.v)} style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6, padding: '5px 10px', borderRadius: 6, border: 0, cursor: 'pointer', fontSize: 12, fontWeight: 500, flex: narrow ? 1 : undefined, background: status === f.v ? 'var(--color-card)' : 'transparent', color: status === f.v ? 'var(--color-foreground)' : 'var(--color-muted-foreground)', boxShadow: status === f.v ? '0 1px 2px rgba(0,0,0,.08)' : 'none' }}>
                {f.dot && <span style={{ width: 6, height: 6, borderRadius: '50%', background: f.dot, flexShrink: 0 }} />}
                {t(f.k)}
              </button>
            ))}
          </div>
          <select style={{ ...inputCss, height: 36, width: narrow ? '100%' : 'auto', minWidth: 180, flexBasis: narrow ? '100%' : undefined }} value={categoryId} onChange={(e) => setCategoryId(Number(e.target.value))}>
            <option value={0}>{t('filter_category')}</option>
            {categories.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
          </select>
          <div style={{ marginLeft: narrow ? 0 : 'auto', display: 'flex', flexWrap: narrow ? 'wrap' : 'nowrap', gap: 8, flexBasis: narrow ? '100%' : undefined }}>
            <button style={{ ...btnGhost, height: narrow ? 'auto' : 36, minHeight: narrow ? 36 : undefined, flex: narrow ? '1 1 calc(50% - 4px)' : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} onClick={resetFilters}><ResetIcon />{t('reset_filters')}</button>
            <div style={{ position: 'relative', flex: narrow ? '1 1 calc(50% - 4px)' : undefined, minWidth: narrow ? 0 : undefined, display: narrow ? 'flex' : undefined }}>
              <button style={{ ...btnGhost, height: narrow ? '100%' : 36, minHeight: narrow ? 36 : undefined, width: narrow ? '100%' : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} onClick={() => setShowCols((v) => !v)}><GripIcon />{t('columns')}</button>
              {showCols && <ColManager cols={cols} labelFor={(id) => t(COL_LABEL[id])} onChange={setCols} onClose={() => setShowCols(false)} save={cols$.save} defaults={cols$.DEFAULT} t={t} />}
            </div>
            {can('export') && <button disabled={exporting} style={{ ...btnGhost, height: narrow ? 'auto' : 36, minHeight: narrow ? 36 : undefined, flex: narrow ? '1 1 calc(50% - 4px)' : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px', opacity: exporting ? 0.6 : 1 }} onClick={openExport}>{exporting ? <Spinner /> : <FileDownIcon />}{t('export')}</button>}
          </div>
        </div>

        <div style={{ ...card, overflowX: 'auto' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: narrow ? undefined : 760 }}>
            <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
              <tr>
                {hasHidden && <th style={{ ...th, width: 32 }} />}
                {visible.map(({ id }) => {
                  const sortable = SORTABLE.has(id as ProductSortKey)
                  return (
                    <th key={id} style={{ ...th, cursor: sortable ? 'pointer' : 'default', ...(id === 'id' ? { width: 70 } : {}) }} onClick={sortable ? () => toggleSort(id) : undefined}>
                      <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                        {t(COL_LABEL[id])}
                        {sortable && <SortIcon dir={sortCol === id ? sortDir : null} />}
                      </span>
                    </th>
                  )
                })}
                <th style={{ ...th, width: 80, position: 'sticky', right: 0, background: 'var(--color-muted,rgba(0,0,0,.03))' }} />
              </tr>
            </thead>
            <tbody>
              {items.length === 0 && !loading ? (
                <tr><td style={{ ...td, textAlign: 'center', color: 'var(--color-muted-foreground)', padding: '40px 16px' }} colSpan={totalCols}>{t('empty')}</td></tr>
              ) : items.map((p) => (
                <Fragment key={p.id}>
                  <tr>
                    {hasHidden && (
                      <td style={td}>
                        <ExpandToggle expanded={expanded.has(p.id)} onClick={() => toggleExpand(p.id)} />
                      </td>
                    )}
                    {visible.map(({ id }) => (
                      <td key={id} style={{ ...td, ...(id === 'id' ? { color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' } : {}) }}>
                        {cellContent(p, id)}
                      </td>
                    ))}
                    <td style={{ ...td, position: 'sticky', right: 0, background: 'var(--color-card,#fff)' }}>
                      <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 4 }}>
                        {can('duplicate') && <button style={iconBtn} title={t('var_duplicate')} onClick={() => setToDup(p)}>⧉</button>}
                        {can('edit') && <button style={iconBtn} title={t('edit')} onClick={() => navigate(`${base}/${p.id}`)}><PencilIcon /></button>}
                        {can('delete') && <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => setToDelete(p)}><TrashIcon /></button>}
                      </div>
                    </td>
                  </tr>
                  {expanded.has(p.id) && (
                    <HiddenColsRow cols={eCols} labelFor={(id) => t(COL_LABEL[id])} renderValue={(id) => cellContent(p, id)} colSpan={totalCols} narrow={narrow} />
                  )}
                </Fragment>
              ))}
            </tbody>
          </table>
          <div ref={sentinelRef} style={{ height: 1 }} />
          {loading && (
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, padding: '14px 16px', fontSize: 12, color: 'var(--color-muted-foreground)' }}>
              <Spinner />{t('loading')}
            </div>
          )}
          {!hasMore && items.length > 0 && (
            <div style={{ padding: '10px 16px', textAlign: 'center', fontSize: 12, color: 'var(--color-muted-foreground)' }}>{t('count', { n: total })}</div>
          )}
        </div>
      </div>

      {showExport && <ExportModal cols={cols} items={exportItems} getCell={(p, id) => getCellExport(p, id, t)} labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')} sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />}

      {toDelete && (
        <ConfirmModal title={t('del_title')} message={t('del_confirm', { u: prodLabel(toDelete) })}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
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
function LangSwitcher({ languages, value, onChange, narrow }: { languages: LangOption[]; value: number; onChange: (id: number) => void; narrow?: boolean }) {
  return (
    <div style={{ display: 'flex', flexDirection: narrow ? 'row' : 'column', flexWrap: narrow ? 'wrap' : 'nowrap', gap: 4 }}>
      {languages.map((l) => {
        const on = l.id === value
        return (
          <button key={l.id} onClick={() => onChange(l.id)}
            style={{ display: 'flex', alignItems: 'center', justifyContent: narrow ? 'center' : 'space-between', gap: 8, padding: '8px 10px', borderRadius: 6, cursor: 'pointer', textAlign: 'left',
              flex: narrow ? '1 1 auto' : undefined,
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

// ── Formulaire (sous-onglet) — onglets Principal / Textes / Variants / SEO ──────
function ProductForm({ id, base }: { id: string; base: string }) {
  const t = makeT(DICT)
  const narrow = useIsNarrow()
  const navigate = useNavigate()
  const location = useLocation()
  const isEdit = id !== 'new'
  const productId = isEdit ? parseInt(id) : null
  const subTabPath = `${base}/${id}`

  const [reference, setReference] = useState('')
  const [stockLow, setStockLow] = useState('')
  const [active, setActive] = useState(true)
  const [texts, setTexts] = useState<ProductText[]>([])
  const [extraTexts, setExtraTexts] = useState<ProductExtraText[]>([])
  const [showAddTextType, setShowAddTextType] = useState(false)
  const [removeTextTypeId, setRemoveTextTypeId] = useState<number | null>(null)
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
  // Bumped after a save so Images/Files sections re-fetch and newly-uploaded media becomes editable
  // right away (their own local fetch only reruns on mount otherwise).
  const [mediaTick, setMediaTick] = useState(0)
  const [catNames, setCatNames] = useState<Record<number, string>>({})
  const [categoryIds, setCategoryIds] = useState<number[]>([])
  const [languages, setLanguages] = useState<LangOption[]>([])
  const [lang, setLang] = useState<number>(0)
  const [loading, setLoading] = useState(false)
  const [saving, setSaving] = useState(false)
  const [errRef, setErrRef] = useState(false)
  const [formError, setFormError] = useState(false)
  const [tab, setTab] = useState('main')
  const { can, loaded: capsLoaded } = useCaps(TOOL_MELIS_KEY)

  // Onglets masqués selon les droits (l'accès à un onglet = capacité de même clé : main/text/…).
  const TABS: TabDef[] = ([
    { key: 'main',     label: t('tab_main'),     icon: <TagIcon /> },
    { key: 'text',     label: t('tab_text'),     icon: <FileTextIcon /> },
    { key: 'variants', label: t('tab_variants'), icon: <LayoutIcon /> },
    { key: 'seo',      label: t('tab_seo'),      icon: <TagIcon /> },
    { key: 'prices',   label: t('tab_prices'),   icon: <span style={{ fontWeight: 700 }}>€</span> },
  ] as TabDef[]).filter((tb) => can(tb.key))

  // Si l'onglet actif vient d'être retiré par les droits, basculer sur le premier autorisé.
  useEffect(() => {
    if (capsLoaded && TABS.length && !TABS.some((tb) => tb.key === tab)) setTab(TABS[0].key)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [capsLoaded])

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
  }, [productId])

  useEffect(() => {
    if (!productId) return
    // Garde de montage : la brique reste montée (Shell.tsx isActive) le temps que React
    // réconcilie — un fetch en vol au moment où l'onglet se ferme n'est PAS annulé par le
    // démontage ; sans ce garde, son .catch() pouvait naviguer vers `base` APRÈS coup et
    // écraser une navigation par ailleurs légitime (ex. fermeture d'onglet → retour Dashboard).
    let cancelled = false
    setLoading(true)
    fetchProductById(productId).then((p) => {
      if (cancelled) return
      setReference(p.reference); setStockLow(p.stockLow === null ? '' : String(p.stockLow)); setActive(p.status === 1)
      setTexts(p.texts); setExtraTexts(p.extraTexts ?? []); setSeo(p.seo); setCategoryIds(p.categoryIds)
      if (p.pageLinks) setPageLinks(p.pageLinks)
    }).catch(() => { if (!cancelled) navigate(base) }).finally(() => { if (!cancelled) setLoading(false) })
    return () => { cancelled = true }
  }, [productId, navigate, base])

  useEffect(() => {
    if (isEdit) closeSubTab(base, `${base}/new`)
    openSubTab(base, { id: subTabPath, label: isEdit ? t('loading') : t('new'), path: subTabPath })
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])
  const primaryName = texts.find((x) => x.name.trim())?.name ?? ''
  useEffect(() => {
    const lbl = (primaryName || reference).trim()
    if (isEdit) updateSubLabel(base, subTabPath, lbl || `#${productId}`)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [primaryName, reference])

  function setText(langId: number, field: 'name' | 'description', value: string) { setTexts((p) => p.map((x) => x.langId === langId ? { ...x, [field]: value } : x)) }
  function setSeoField(langId: number, field: keyof ProductSeo, value: string) { setSeo((p) => p.map((x) => x.langId === langId ? { ...x, [field]: value } : x)) }
  // « Ajouter un type de texte » (onglet Textes) — cf. AddTextTypeModal / legacy addProductTextTypeAction.
  function setExtraTextValue(typeId: number, langId: number, value: string) {
    setExtraTexts((p) => p.map((et) => et.typeId !== typeId ? et : {
      ...et, values: et.values.map((v) => v.langId === langId ? { ...v, value } : v),
    }))
  }
  function addExtraTextType(type: TextType) {
    setExtraTexts((p) => p.some((et) => et.typeId === type.id) ? p : [
      ...p,
      { typeId: type.id, code: type.code, name: type.name, fieldType: type.fieldType, values: languages.map((l) => ({ langId: l.id, ptxtId: null, value: '' })) },
    ])
  }
  // Retire seulement de CETTE session d'édition (comme le legacy product.tool.js .deleteTextInput) —
  // le contenu déjà enregistré n'est pas supprimé en base tant que ce type n'est pas ré-ajouté et
  // re-sauvegardé vide ; ré-ouvrir le produit fera réapparaître le texte existant.
  function removeExtraText(typeId: number) { setExtraTexts((p) => p.filter((et) => et.typeId !== typeId)) }
  function toggleCategory(cid: number) { setCategoryIds((p) => p.includes(cid) ? p.filter((x) => x !== cid) : [...p, cid]) }

  async function submit() {
    if (!reference.trim()) { setErrRef(true); setFormError(true); setTab('main'); return }
    setFormError(false)
    setSaving(true)
    try {
      const result = await saveProduct({
        id: productId, reference: reference.trim(), status: active,
        stockLow: stockLow.trim() === '' ? null : parseInt(stockLow, 10) || 0,
        texts: texts.map((x) => ({ langId: x.langId, name: x.name.trim(), description: x.description })),
        extraTexts,
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
          ...pendingRecipientIds.map((uid) => saveProductRecipient(pid, { userId: uid }, stockLow.trim() === '' ? null : parseInt(stockLow, 10) || 0)),
          ...meaningfulPrices.map((p) => saveProductPrice(pid, { id: null, countryId: p.countryId, currency: p.currency, net: p.net, gross: p.gross, vatPercent: p.vatPercent, vatPrice: p.vatPrice, otherTax: p.otherTax })),
        ])
        setPendingImages([]); setPendingFiles([])
        setMediaTick((x) => x + 1)
        notify('ok', t('title'), t('saved'))
        closeSubTab(base, `${base}/new`)
        const newPath = `${base}/${pid}`
        openSubTab(base, { id: newPath, label: reference.trim(), path: newPath })
        navigate(newPath, { replace: true, state: { tab } })
      } else {
        const stockLevelNum = stockLow.trim() === '' ? null : parseInt(stockLow, 10) || 0
        // Unlike the new-product path, every pendingPrices entry here corresponds to an existing
        // DB row the user actively edited (buffered onChange) — send it even when all fields end up
        // null, otherwise clearing every field back to empty silently no-ops and the old values stick.
        await Promise.allSettled([
          ...pendingPrices.map((p) => saveProductPrice(productId!, { id: p.id ?? null, countryId: p.countryId, currency: p.currency, net: p.net, gross: p.gross, vatPercent: p.vatPercent, vatPrice: p.vatPrice, otherTax: p.otherTax })),
          ...pendingAttrIds.map((aid) => saveProductAttribute(productId!, aid)),
          ...pendingRecipientIds.map((uid) => saveProductRecipient(productId!, { userId: uid }, stockLevelNum)),
          ...pendingImages.map((m) => uploadProductMedia(productId!, { ...m, kind: 'image' })),
          ...pendingFiles.map((m) => uploadProductMedia(productId!, { ...m, kind: 'file' })),
          ...pendingDeleteAttrPattIds.map((pattId) => deleteProductAttribute(productId!, pattId)),
          ...pendingDeleteRecipientSeaIds.map((seaId) => deleteProductRecipient(productId!, seaId)),
          ...pendingDeleteFileIds.map((id) => deleteProductMedia(productId!, id)),
          ...pendingDeleteImageIds.map((id) => deleteProductMedia(productId!, id)),
        ])
        setPendingImages([]); setPendingFiles([]); setPendingDeleteFileIds([]); setPendingDeleteImageIds([])
        setMediaTick((x) => x + 1)
        notify('ok', t('title'), t('saved'))
      }
    } catch (e) { const msg = e instanceof Error ? e.message : t('err_save'); notify('ko', t('title'), msg) } finally { setSaving(false) }
  }

  // After save navigates new→edit, apply the intended tab/state.
  // Applique l'état de navigation (onglet + éditeur de variant à ouvrir) à CHAQUE navigation
  // (clé de location), pas une seule fois : les liens « nom produit / SKU » du picker
  // d'association naviguent produit→produit et doivent rouvrir l'onglet/variant ciblé.
  useEffect(() => {
    if (!isEdit) return
    const s = (location.state as { tab?: string; openVariantEditor?: boolean | number }) ?? {}
    if (s.tab) setTab(s.tab)
    // Naviguer sans variant ciblé (ex. lien « nom produit ») ferme tout éditeur de variant
    // resté ouvert du produit précédent → on retombe sur la LISTE des variants.
    if (typeof s.openVariantEditor === 'number') setVariantEdit(s.openVariantEditor)
    else if (s.openVariantEditor) setVariantEdit('new')
    else setVariantEdit(null)
  }, [isEdit, location.key]) // eslint-disable-line react-hooks/exhaustive-deps

  const secTitle = { fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 12px' } as const
  const labelRow = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 } as const

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box' }}>
      <div style={{ display: 'flex', alignItems: narrow ? 'flex-start' : 'center', justifyContent: 'space-between', gap: 16, flexWrap: narrow ? 'wrap' : 'nowrap' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><PackageIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{isEdit ? t('edit_title') : t('new_title')}</h1>
        </div>
        <div style={{ display: 'flex', gap: 10, alignItems: 'center', flexBasis: narrow ? '100%' : undefined }}>
          <button style={{ ...btnPrimary, flex: narrow ? 1 : undefined, justifyContent: narrow ? 'center' : undefined }} onClick={submit} disabled={saving || loading}>{saving ? '…' : t('save_product')}</button>
        </div>
      </div>

      <Tabs tabs={TABS} active={tab} onChange={setTab} />

      {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, marginBottom: 16 }}>{t('err_required_fields')}</div>}

      {loading ? (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', minHeight: '40vh', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : tab === 'main' ? (
        <div>
        {/* En-tête : titre + statut (remonté ici, plus de carte minuscule isolée) */}
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, borderBottom: '1px solid var(--color-border)', paddingBottom: 12, marginBottom: 20 }}>
          <h2 style={{ fontSize: 18, fontWeight: 600, margin: 0 }}>{t('tab_main')}</h2>
          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
            <span style={{ fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)' }}>{t('status_label')}</span>
            <button type="button" onClick={() => setActive((v) => !v)} style={{ display: 'flex', alignItems: 'center', gap: 10, background: 'none', border: 0, padding: 0, cursor: 'pointer' }}>
              <div style={{ position: 'relative', width: 40, height: 22, borderRadius: 999, background: active ? '#22c55e' : 'var(--color-muted-foreground)', transition: 'background .15s', flexShrink: 0 }}>
                <span style={{ position: 'absolute', top: 2, left: active ? 20 : 2, width: 18, height: 18, borderRadius: 999, background: '#fff', transition: 'left .15s', boxShadow: '0 1px 2px rgba(0,0,0,.3)' }} />
              </div>
              <span style={{ fontSize: 14, fontWeight: 600, color: active ? '#16a34a' : 'var(--color-muted-foreground)' }}>{active ? t('online') : t('offline')}</span>
            </button>
          </div>
        </div>

        {/* 2 colonnes équilibrées, chaque section dans sa propre carte */}
        <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : 'repeat(auto-fit, minmax(360px, 1fr))', gap: 20, alignItems: 'start' }}>
          {/* Colonne gauche */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 20, minWidth: 0 }}>
            <div style={{ ...card, padding: 20 }}>
              <h3 style={secTitle}>{t('sec_general')}</h3>
              <div style={labelRow}><label style={label}>{t('f_reference')} *</label><InfoDot text={t('tip_reference')} /></div>
              <input style={inputCss} value={reference} onChange={(e) => { setReference(e.target.value); setErrRef(false); setFormError(false) }} placeholder={t('f_reference_ph')} autoComplete="off" />
              {errRef && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_reference')}</p>}
            </div>

            <div style={{ ...card, padding: 20 }}>
              <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 12 }}>
                <h3 style={{ ...secTitle, margin: 0 }}>{t('sec_categories')}</h3>
                <button style={btnGhost} onClick={() => setShowCatModal(true)}>{t('cat_see_all')}</button>
              </div>
              {categoryIds.length === 0 ? <p style={{ ...hint, margin: 0 }}>{t('cat_none_selected')}</p> : (
                <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
                  {categoryIds.map((cid) => (
                    <span key={cid} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
                      {catNames[cid] ?? categories.find((x) => x.id === cid)?.name ?? `#${cid}`}
                      <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => toggleCategory(cid)}><TrashIcon /></button>
                    </span>
                  ))}
                </div>
              )}
            </div>

            <div style={{ ...card, padding: 20 }}>
              <AttributesSection productId={productId} options={attrOptions} t={t} pendingAttrIds={pendingAttrIds} onTogglePendingAttr={(id) => setPendingAttrIds((p) => p.includes(id) ? p.filter((x) => x !== id) : [...p, id])} onMarkDeleteAttr={(pattId) => setPendingDeleteAttrPattIds((p) => [...p, pattId])} />
            </div>

            <div style={{ ...card, padding: 20 }}>
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
            </div>
          </div>

          {/* Colonne droite */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 20, minWidth: 0 }}>
            <div style={{ ...card, padding: 20 }}>
              <ImagesSection productId={productId} t={t} countries={countries} pendingImages={pendingImages} onAddPendingImage={(m) => setPendingImages((p) => [...p, m])} onRemovePendingImage={(i) => setPendingImages((p) => p.filter((_, idx) => idx !== i))} onMarkDeleteImage={(id) => setPendingDeleteImageIds((p) => [...p, id])} onUpdatePendingImage={(i, m) => setPendingImages((p) => p.map((x, idx) => idx === i ? m : x))} refreshSignal={mediaTick} />
            </div>

            <div style={{ ...card, padding: 20 }}>
              <FilesSection productId={productId} t={t} countries={countries} pendingFiles={pendingFiles} onAddPendingFile={(m) => setPendingFiles((p) => [...p, m])} onRemovePendingFile={(i) => setPendingFiles((p) => p.filter((_, idx) => idx !== i))} onMarkDeleteFile={(id) => setPendingDeleteFileIds((p) => [...p, id])} refreshSignal={mediaTick} />
            </div>

            <div style={{ ...card, padding: 20 }}>
              <h3 style={secTitle}>{t('sec_alert')}</h3>
              <div style={labelRow}><label style={label}>{t('f_stock_alert')}</label><InfoDot text={t('tip_stock_alert')} /></div>
              <input style={inputCss} type="number" min={0} value={stockLow} onChange={(e) => setStockLow(e.target.value)} placeholder={t('f_stock_ph')} autoComplete="off" />
              <div style={{ marginTop: 16 }}>
                <RecipientsSection productId={productId} users={users} t={t}
                  pendingRecipientIds={pendingRecipientIds} onTogglePendingRecipient={(id) => setPendingRecipientIds((p) => p.includes(id) ? p.filter((x) => x !== id) : [...p, id])}
                  onMarkDeleteRecipient={(seaId) => setPendingDeleteRecipientSeaIds((p) => [...p, seaId])} />
              </div>
            </div>
          </div>
        </div>
        </div>
      ) : tab === 'text' ? (
        <div style={{ display: 'flex', flexDirection: 'column', gap: 14, maxWidth: 1080 }}>
          <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
            <button style={btnGhost} onClick={() => setShowAddTextType(true)}><PlusIcon />{t('texttype_add_btn')}</button>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '150px minmax(0, 1fr)', gap: 14 }}>
            <LangSwitcher languages={languages} value={lang} onChange={setLang} narrow={narrow} />
            <div style={{ display: 'flex', flexDirection: 'column', gap: 14, minWidth: 0 }}>
              {texts.filter((x) => x.langId === lang).map((tx) => (
                <div key={tx.langId} style={{ ...card, padding: 18, minWidth: 0 }}>
                  <div style={labelRow}><label style={label}>{t('f_name')}</label></div>
                  <input style={inputCss} value={tx.name} onChange={(e) => setText(tx.langId, 'name', e.target.value)} placeholder={t('f_name_ph')} autoComplete="off" />
                  <div style={{ ...labelRow, marginTop: 14, marginBottom: 6 }}><label style={label}>{t('f_description')}</label></div>
                  {/* Éditeur riche TinyMCE (config 'tool' de melis-core), comme la gestion des emails. */}
                  <MelisToolEditor value={tx.description} onChange={(html) => setText(tx.langId, 'description', html)} minHeight={300} />
                </div>
              ))}
              {/* Types de texte additionnels (« Ajouter un type de texte ») — un par type assigné,
                  court → <input>, long → éditeur riche, comme le legacy. */}
              {extraTexts.map((et) => {
                const v = et.values.find((x) => x.langId === lang)
                if (!v) return null
                return (
                  // Keyed on typeId+lang (not typeId alone) so switching language REMOUNTS this card —
                  // MelisToolEditor/TinyMCE only reads its `value` prop once at mount (see that
                  // component's own comment); without a lang-keyed remount, switching language kept
                  // showing/editing the PREVIOUS language's stale in-editor content instead of loading
                  // the new one, so an edit looked like it silently failed to save (it saved, into the
                  // wrong place, or got overwritten by stale content). Same pattern the Title/Description
                  // card above already relies on (key={tx.langId}), which is why that one was unaffected.
                  <div key={`${et.typeId}-${lang}`} style={{ ...card, padding: 18, minWidth: 0 }}>
                    <div style={labelRow}>
                      <label style={label}>{et.name} <span style={{ fontWeight: 400, color: 'var(--color-muted-foreground)' }}>({et.code})</span></label>
                      <button title={t('texttype_del_title')} onClick={() => setRemoveTextTypeId(et.typeId)} style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }}><TrashIcon /></button>
                    </div>
                    {et.fieldType === 2
                      ? <MelisToolEditor value={v.value} onChange={(html) => setExtraTextValue(et.typeId, lang, html)} minHeight={300} />
                      : <input style={inputCss} value={v.value} onChange={(e) => setExtraTextValue(et.typeId, lang, e.target.value)} autoComplete="off" />}
                  </div>
                )
              })}
            </div>
          </div>
        </div>
      ) : tab === 'variants' ? (
        variantEdit !== null && productId ? (
          <VariantEditor productId={productId} variantId={variantEdit === 'new' ? null : variantEdit} countries={countries} currencies={currencies} languages={languages} t={t} onClose={() => setVariantEdit(null)} onSaved={() => { /* list reloads on close */ }} can={can} />
        ) : (
          <VariantsTab productId={productId} t={t} onAdd={() => setVariantEdit('new')} onEdit={(id) => setVariantEdit(id)} can={can} />
        )
      ) : tab === 'prices' ? (
        <PricesTab productId={productId} countries={countries} currencies={currencies} t={t} pendingPrices={pendingPrices} onBufferPrice={(p) => setPendingPrices((prev) => [...prev.filter((x) => x.countryId !== p.countryId), p])} />
      ) : (
        <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '150px 1fr', gap: 14, maxWidth: narrow ? undefined : 760 }}>
            <LangSwitcher languages={languages} value={lang} onChange={setLang} narrow={narrow} />
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
                <div key={s.langId} style={{ ...card, padding: 18 }}>
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
      {showAddTextType && (
        <AddTextTypeModal excludeTypeIds={extraTexts.map((et) => et.typeId)} t={t} onClose={() => setShowAddTextType(false)} onAdd={addExtraTextType} />
      )}
      {removeTextTypeId != null && (
        <ConfirmModal t={t} title={t('texttype_del_title')}
          message={t('texttype_del_confirm', { u: extraTexts.find((et) => et.typeId === removeTextTypeId)?.name ?? '' })}
          onCancel={() => setRemoveTextTypeId(null)}
          onConfirm={() => { removeExtraText(removeTextTypeId); setRemoveTextTypeId(null) }} />
      )}
    </div>
  )
}
