import { Fragment, useEffect, useRef, useState, type ReactNode } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  fetchCoupons, fetchAllCoupons, fetchCouponStats, fetchCouponById, saveCoupon, deleteCoupon,
  fetchClientsDirectory, fetchProductsDirectory, fetchCouponClients, fetchCouponProducts,
  saveCouponClient, deleteCouponClient, saveCouponProduct, deleteCouponProduct,
  type CouponItem, type CouponStats, type CouponDetail, type CouponClientOption, type CouponProductOption, type CouponSortKey,
} from './api'
import { useKeysetList } from '../../shared/use-keyset-list'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT, fmtDate, fmtMoney } from '../../shared/i18n'
import { DatePicker } from '../../shared/DatePicker'
import { card, inputCss, label, btnGhost, btnPrimary, th, td, iconBtn } from '../../shared/styles'
import { TagIcon, CheckIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon, UsersIcon, CartIcon, ResetIcon, SortIcon, Spinner } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { makeColStore, visibleCols, effectiveCols, type ColDef } from '../../shared/columns'
import { ExpandToggle, HiddenColsRow } from '../../shared/ExpandableRow'
import { useIsNarrow } from '../../shared/useIsNarrow'
import { ExportModal } from '../../shared/ExportModal'
import { ColManager } from '../../shared/ColManager'
import { openSubTab, updateSubLabel } from '../../shared/subtabs'
import { useCaps } from '../../shared/useCaps'
import { Tabs } from '../../shared/Tabs'
import { CouponClientsTab, CouponProductsTab, CouponOrdersTab } from './CouponTabs'

const TOOL_MELIS_KEY = 'meliscommerce_coupon_list_page'

const COL_ORDER = ['id', 'code', 'status', 'discount', 'uses', 'valid'] as const
const COL_LABEL: Record<string, string> = {
  id: 'col_id', code: 'col_code', status: 'col_status', discount: 'col_discount', uses: 'col_uses', valid: 'col_valid',
}
const cols$ = makeColStore('melis-coupon-cols-v1', COL_ORDER)
const ESSENTIAL_COLS = new Set(['code'])
// Colonnes triables côté serveur — doit matcher le sortMap backend.
const SORTABLE = new Set<CouponSortKey>(['id', 'code', 'status', 'discount', 'uses', 'valid'])

const listCache = makeCache<{
  items: CouponItem[]; stats: CouponStats | null; total: number; cursor: string | null; hasMore: boolean
  search: string; searchInput: string; filterStatus: number | null; sortCol: string; sortDir: 'asc' | 'desc'; mode: 'react' | 'old'
}>()

const labelRow = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 } as const
const fieldSectionTitle = { display: 'flex', alignItems: 'center', gap: 8, fontSize: 15, fontWeight: 500, color: 'var(--color-muted-foreground)', margin: '0 0 20px' } as const
const fieldGap = { display: 'flex', flexDirection: 'column', gap: 16 } as const

// ── Tooltip d'aide (icône (i) à côté d'un champ) ────────────────────────────────
function InfoDot({ text }: { text: string }) {
  if (!text) return null
  return (
    <span title={text} style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 15, height: 15, borderRadius: 999, background: 'var(--color-muted-foreground)', color: 'var(--color-background,#fff)', fontSize: 10, fontWeight: 700, cursor: 'help', flexShrink: 0 }}>i</span>
  )
}

function GearIcon() {
  return <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ width: 16, height: 16, flexShrink: 0 }}><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" /></svg>
}

function ValuesIcon() {
  return <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ width: 16, height: 16, flexShrink: 0 }}><line x1="3" y1="6" x2="21" y2="6" /><line x1="3" y1="12" x2="17" y2="12" /><line x1="3" y1="18" x2="13" y2="18" /></svg>
}

// ── Badge de statut (cliquable) ─────────────────────────────────────────────────
function StatusBadge({ active, onClick, t }: { active: boolean; onClick: () => void; t: (k: string) => string }) {
  return (
    <div onClick={onClick} style={{ display: 'inline-flex', alignItems: 'center', gap: 10, cursor: 'pointer' }}>
      <span style={{ position: 'relative', display: 'inline-block', width: 44, height: 24, borderRadius: 12, background: active ? '#22c55e' : 'var(--color-border)', transition: 'background .15s', flexShrink: 0 }}>
        <span style={{ position: 'absolute', top: 2, left: active ? 22 : 2, width: 20, height: 20, borderRadius: '50%', background: '#fff', boxShadow: '0 1px 3px rgba(0,0,0,.3)', transition: 'left .15s' }} />
      </span>
      <span style={{ fontSize: 13, fontWeight: 600, color: active ? '#22c55e' : 'var(--color-muted-foreground)' }}>
        {active ? t('status_active') : t('status_inactive')}
      </span>
    </div>
  )
}

// ── Toggle switch (piste arrondie + poignée ronde) + libellé coloré ───────────
function YesNoToggle({ value, onChange, t }: { value: boolean; onChange: (v: boolean) => void; t: (k: string) => string }) {
  return (
    <div onClick={() => onChange(!value)} style={{ display: 'inline-flex', alignItems: 'center', gap: 10, cursor: 'pointer' }}>
      <span style={{ position: 'relative', display: 'inline-block', width: 44, height: 24, borderRadius: 12, background: value ? '#22c55e' : 'var(--color-border)', transition: 'background .15s', flexShrink: 0 }}>
        <span style={{ position: 'absolute', top: 2, left: value ? 22 : 2, width: 20, height: 20, borderRadius: '50%', background: '#fff', boxShadow: '0 1px 3px rgba(0,0,0,.3)', transition: 'left .15s' }} />
      </span>
      <span style={{ fontSize: 13, fontWeight: 600, color: value ? '#22c55e' : 'var(--color-muted-foreground)' }}>
        {value ? t('yes') : t('no')}
      </span>
    </div>
  )
}

function discountLabel(c: CouponItem): string {
  // Pourcentage → tel quel avec « % » ; réduction fixe → prix formaté dans la langue du BO
  // avec la devise par défaut de la plateforme (currencySymbol renvoyé par l'API).
  if (c.percentage !== null) return `${c.percentage}%`
  if (c.discountValue !== null) return fmtMoney(c.discountValue, c.currencySymbol)
  return '—'
}

function getCellExport(c: CouponItem, id: string): string | number {
  switch (id) {
    case 'id':       return c.id
    case 'code':     return c.code
    case 'status':   return c.status === 1 ? 'Active' : 'Inactive'
    case 'discount': return discountLabel(c)
    case 'uses':     return c.maxUseNumber !== null ? `${c.currentUseNumber}/${c.maxUseNumber}` : c.currentUseNumber
    case 'valid':    return c.dateValidEnd ?? ''
    default:         return ''
  }
}

export default function CouponPage() {
  const { id } = useParams()
  const location = useLocation()
  const base = id ? location.pathname.slice(0, location.pathname.length - id.length - 1) : location.pathname
  if (id) return <CouponForm key={id} id={id} base={base} />
  return <CouponList base={base} />
}

// ── Status pill ────────────────────────────────────────────────────────────────
function StatusPill({ active, t }: { active: boolean; t: (k: string) => string }) {
  return (
    <span style={{
      display: 'inline-flex', alignItems: 'center', gap: 6, padding: '2px 8px 2px 7px',
      borderRadius: 999, fontSize: 12, fontWeight: 500, whiteSpace: 'nowrap',
      background: active ? 'color-mix(in srgb, #10b981 14%, transparent)' : 'var(--color-muted,rgba(0,0,0,.05))',
      color: active ? '#059669' : 'var(--color-muted-foreground)',
    }}>
      <span style={{ width: 6, height: 6, borderRadius: '50%', flexShrink: 0, background: active ? '#10b981' : 'var(--color-muted-foreground)' }} />
      {active ? t('status_active') : t('status_inactive')}
    </span>
  )
}

// ── COUPON LIST ─────────────────────────────────────────────────────────────────
function CouponList({ base }: { base: string }) {
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
  const [stats, setStats] = useState<CouponStats | null>(cached?.stats ?? null)
  const [searchInput, setSearchInput] = useState(cached?.searchInput ?? '')
  const [search, setSearch] = useState(cached?.search ?? '')
  const [filterStatus, setFilterStatus] = useState<number | null>(cached?.filterStatus ?? null)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [exportItems, setExportItems] = useState<CouponItem[]>([])
  const [exporting, setExporting] = useState(false)
  const [toDelete, setToDelete] = useState<CouponItem | null>(null)
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }

  // Scroll infini + tri server-side + keyset (mutualisé). Ordre legacy = id desc.
  const {
    items, setItems, total, loading, hasMore, sentinelRef, sortCol, sortDir, setSortCol, setSortDir, toggleSort, snapshot,
  } = useKeysetList<CouponItem>({
    fetcher: (a) => fetchCoupons({ ...a, sort: a.sort as CouponSortKey, search, status: filterStatus }),
    deps: [search, filterStatus, tick],
    defaultSort: 'id',
    defaultDir: 'desc',
    initial: cached ? { items: cached.items, total: cached.total, cursor: cached.cursor, hasMore: cached.hasMore, sortCol: cached.sortCol, sortDir: cached.sortDir } : undefined,
    skipInitial: !!(cached && cached.items.length),
  })

  const cacheRef = useRef({ ...snapshot(), stats, search, searchInput, filterStatus, mode })
  useEffect(() => { cacheRef.current = { ...snapshot(), stats, search, searchInput, filterStatus, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchCouponStats().then(setStats).catch(() => null) }, [tick])

  const eCols = effectiveCols(cols, ESSENTIAL_COLS, narrow)
  const visible = visibleCols(eCols)
  const hasHidden = eCols.some((c) => !c.visible)
  const totalCols = visible.length + 1 + (hasHidden ? 1 : 0)
  function cellStyle(id: string) {
    if (id === 'id') return { ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' as const }
    if (id === 'discount') return { ...td, fontWeight: 600 }
    if (id === 'uses') return { ...td, textAlign: 'center' as const }
    if (id === 'valid') return { ...td, color: 'var(--color-muted-foreground)' }
    return td
  }
  function cellContent(c: CouponItem, id: string): ReactNode {
    switch (id) {
      case 'id': return c.id
      case 'code': return <code style={{ fontSize: 12 }}>{c.code}</code>
      case 'status': return <StatusPill active={c.status === 1} t={t} />
      case 'discount': return discountLabel(c)
      case 'uses': return c.maxUseNumber !== null ? `${c.currentUseNumber}/${c.maxUseNumber}` : c.currentUseNumber
      case 'valid': return c.dateValidEnd ? fmtDate(c.dateValidEnd) : '—'
      default: return '—'
    }
  }
  // Export : le keyset ne charge qu'une page → on récupère TOUT le jeu filtré via curseur.
  async function openExport() {
    setExporting(true)
    try { const all = await fetchAllCoupons({ search, status: filterStatus }); setExportItems(all); setShowExport(true) }
    catch { /* ignore */ } finally { setExporting(false) }
  }

  const openCoupon = (c: CouponItem) => {
    const path = `${base}/${c.id}`
    navigate(path)
    openSubTab(base, { id: path, label: c.code, path })
  }

  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteCoupon(toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) }
  }

  // Réinitialiser les filtres : recherche + statut + tri par défaut (id desc), retour page 1, puis refetch.
  // On vide `items` : sinon les lignes restent affichées pendant le refetch et le clic paraît sans effet.
  function resetFilters() {
    setSearchInput(''); setSearch('')
    setFilterStatus(null)
    setSortCol('id'); setSortDir('desc')
    setItems([])
    setTick((x) => x + 1)
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
            <button style={{ ...btnGhost, width: 36, padding: 0, justifyContent: 'center', flexShrink: 0 }} title={t('refresh')} onClick={() => setTick((x) => x + 1)}><RefreshIcon /></button>
          </div>
          {can('create') && <button style={btnPrimary} onClick={() => navigate(`${base}/new`)}><PlusIcon />{t('new')}</button>}
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 20, flex: 1, minHeight: 0 }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(130px, 1fr))', gap: 12 }}>
          <Kpi icon={<TagIcon />}    label={t('kpi_total')} value={stats?.total ?? null} iconBg="rgba(59,130,246,0.1)" iconColor="#3b82f6" />
          <Kpi icon={<CheckIcon />} label={t('kpi_active')} value={stats?.active ?? null} iconBg="rgba(16,185,129,0.1)" iconColor="#10b981" />
          <Kpi icon={<RefreshIcon />} label={t('kpi_uses')} value={stats?.uses ?? null} iconBg="rgba(139,92,246,0.1)" iconColor="#8b5cf6" />
        </div>

        <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8 }}>
          <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 180, maxWidth: narrow ? undefined : 360, flexBasis: narrow ? '100%' : undefined }}
            placeholder={t('search_placeholder')} value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)}
            onKeyDown={(e) => { if (e.key === 'Enter') setSearch(searchInput) }} />
          <select style={{ ...inputCss, height: 36, width: narrow ? '100%' : 'auto', minWidth: 160, flexBasis: narrow ? '100%' : undefined }}
            value={filterStatus ?? ''} onChange={(e) => setFilterStatus(e.target.value === '' ? null : Number(e.target.value))}>
            <option value="">{t('filter_status')}</option>
            <option value={1}>{t('status_active')}</option>
            <option value={0}>{t('status_inactive')}</option>
          </select>
          <div style={{ marginLeft: narrow ? 0 : 'auto', display: 'flex', flexWrap: narrow ? 'wrap' : 'nowrap', gap: 8, flexBasis: narrow ? '100%' : undefined }}>
            <button style={{ ...btnGhost, height: narrow ? 'auto' : 36, minHeight: narrow ? 36 : undefined, flex: narrow ? '1 1 calc(50% - 4px)' : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} onClick={resetFilters}><ResetIcon />{t('reset_filters')}</button>
            <div style={{ position: 'relative', flex: narrow ? '1 1 calc(50% - 4px)' : undefined, minWidth: narrow ? 0 : undefined, display: narrow ? 'flex' : undefined }}>
              <button style={{ ...btnGhost, height: narrow ? '100%' : 36, minHeight: narrow ? 36 : undefined, width: narrow ? '100%' : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} onClick={() => setShowCols((v) => !v)}><GripIcon />{t('btn_col_manager')}</button>
              {showCols && (
                <ColManager cols={cols} labelFor={(id) => t(COL_LABEL[id])} onChange={setCols}
                  onClose={() => setShowCols(false)} save={cols$.save} defaults={cols$.DEFAULT} t={t} />
              )}
            </div>
            {can('export') && <button style={{ ...btnGhost, height: narrow ? 'auto' : 36, minHeight: narrow ? 36 : undefined, opacity: exporting || items.length === 0 ? 0.6 : 1, flex: narrow ? '1 1 calc(50% - 4px)' : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} disabled={exporting || items.length === 0} onClick={openExport}>{exporting ? <Spinner /> : <FileDownIcon />}{t('btn_export')}</button>}
          </div>
        </div>

        <div style={{ ...card, overflowX: 'auto' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: narrow ? undefined : 700 }}>
            <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
              <tr>
                {hasHidden && <th style={{ ...th, width: 32 }} />}
                {visible.map((c) => {
                  const sortable = SORTABLE.has(c.id as CouponSortKey)
                  return (
                    <th key={c.id} style={{ ...th, cursor: sortable ? 'pointer' : 'default', userSelect: 'none' }} onClick={sortable ? () => toggleSort(c.id) : undefined}>
                      <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4 }}>
                        {t(COL_LABEL[c.id])}
                        {sortable && <SortIcon dir={sortCol === c.id ? sortDir : null} />}
                      </span>
                    </th>
                  )
                })}
                <th style={{ ...th, width: 90, textAlign: 'center', position: 'sticky', right: 0, background: 'var(--color-muted,rgba(0,0,0,.03))' }}>{t('col_action')}</th>
              </tr>
            </thead>
            <tbody>
              {loading && items.length === 0 && (
                <tr><td colSpan={totalCols} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('loading')}</td></tr>
              )}
              {!loading && items.length === 0 && (
                <tr><td colSpan={totalCols} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('no_items')}</td></tr>
              )}
              {items.map((c) => (
                <Fragment key={c.id}>
                  <tr style={{ cursor: 'pointer' }}
                    onClick={() => openCoupon(c)}
                    onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                    onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                    {hasHidden && (
                      <td style={td} onClick={(e) => e.stopPropagation()}>
                        <ExpandToggle expanded={expanded.has(c.id)} onClick={() => toggleExpand(c.id)} />
                      </td>
                    )}
                    {visible.map((col) => (
                      <td key={col.id} style={cellStyle(col.id)}>{cellContent(c, col.id)}</td>
                    ))}
                    <td style={{ ...td, textAlign: 'center', width: 90, position: 'sticky', right: 0, background: 'var(--color-card,#fff)' }} onClick={(e) => e.stopPropagation()}>
                      <div style={{ display: 'inline-flex', gap: 6 }}>
                        {can('edit') && <button onClick={(e) => { e.stopPropagation(); openCoupon(c) }}
                          style={iconBtn} title={t('edit')}><PencilIcon /></button>}
                        {can('delete') && <button onClick={(e) => { e.stopPropagation(); setToDelete(c) }}
                          style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')}><TrashIcon /></button>}
                      </div>
                    </td>
                  </tr>
                  {expanded.has(c.id) && (
                    <HiddenColsRow cols={eCols} labelFor={(id) => t(COL_LABEL[id])} renderValue={(id) => cellContent(c, id)} colSpan={totalCols} narrow={narrow} />
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

      {showExport && (
        <ExportModal cols={cols} items={exportItems} getCell={(c, id) => getCellExport(c, id)}
          labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')}
          sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />
      )}

      {toDelete && (
        <ConfirmModal title={t('del_title')} message={t('del_confirm', { u: toDelete.code })}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
      )}
    </div>
  )
}

// ── COUPON FORM ─────────────────────────────────────────────────────────────────
function CouponForm({ id, base }: { id: string; base: string }) {
  const t = makeT(DICT)
  const narrow = useIsNarrow()
  const navigate = useNavigate()
  const isEdit = id !== 'new'
  const couponId = isEdit ? Number(id) : null
  const subTabPath = `${base}/${id}`
  const { can, loaded: capsLoaded } = useCaps(TOOL_MELIS_KEY)

  const [coupon, setCoupon] = useState<CouponDetail | null>(null)
  const [activeTab, setActiveTab] = useState('information')
  const [saving, setSaving] = useState(false)

  const [code, setCode] = useState('')
  const [status, setStatus] = useState(true)
  const [assignClients, setAssignClients] = useState(false)
  const [assignProducts, setAssignProducts] = useState(false)
  const [percentage, setPercentage] = useState('')
  const [discountValue, setDiscountValue] = useState('')
  const [maxUseNumber, setMaxUseNumber] = useState('')
  const [dateValidStart, setDateValidStart] = useState('')
  const [dateValidEnd, setDateValidEnd] = useState('')
  const [errCode, setErrCode] = useState(false)
  const [errDiscount, setErrDiscount] = useState(false)
  const [formError, setFormError] = useState(false)

  // Assign clients/products — brouillon local uniquement ; rien n'est persisté tant
  // que le bouton Save principal n'a pas été cliqué (voir submit() → reconcile*).
  const [clientDirectory, setClientDirectory] = useState<CouponClientOption[]>([])
  const [productDirectory, setProductDirectory] = useState<CouponProductOption[]>([])
  const [draftClientIds, setDraftClientIds] = useState<number[]>([])
  const [draftProductIds, setDraftProductIds] = useState<number[]>([])
  const [originalClientIds, setOriginalClientIds] = useState<number[]>([])
  const [originalProductIds, setOriginalProductIds] = useState<number[]>([])
  const clientCcliMapRef = useRef<Record<number, number>>({})
  const productCprodMapRef = useRef<Record<number, number>>({})

  useEffect(() => {
    openSubTab(base, { id: subTabPath, label: isEdit ? t('loading') : t('new'), path: subTabPath })
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  useEffect(() => {
    fetchClientsDirectory().then((r) => setClientDirectory(r.items)).catch(() => null)
    fetchProductsDirectory().then((r) => setProductDirectory(r.items)).catch(() => null)
  }, [])

  function loadAssignments(cid: number) {
    fetchCouponClients(cid).then((r) => {
      const ids = r.assigned.map((a) => a.clientId)
      setDraftClientIds(ids); setOriginalClientIds(ids)
      const map: Record<number, number> = {}
      r.assigned.forEach((a) => { map[a.clientId] = a.ccliId })
      clientCcliMapRef.current = map
    }).catch(() => null)
    fetchCouponProducts(cid).then((r) => {
      const ids = r.assigned.map((a) => a.productId)
      setDraftProductIds(ids); setOriginalProductIds(ids)
      const map: Record<number, number> = {}
      r.assigned.forEach((a) => { map[a.productId] = a.cprodId })
      productCprodMapRef.current = map
    }).catch(() => null)
  }

  useEffect(() => {
    if (!couponId) return
    fetchCouponById(couponId).then((c) => {
      setCoupon(c)
      setCode(c.code); setStatus(c.status === 1)
      setAssignClients(c.assignClients); setAssignProducts(c.assignProducts)
      setPercentage(c.percentage !== null ? String(c.percentage) : '')
      setDiscountValue(c.discountValue !== null ? String(c.discountValue) : '')
      setMaxUseNumber(c.maxUseNumber !== null ? String(c.maxUseNumber) : '')
      setDateValidStart(c.dateValidStart ? c.dateValidStart.slice(0, 10) : '')
      setDateValidEnd(c.dateValidEnd ? c.dateValidEnd.slice(0, 10) : '')
      updateSubLabel(base, subTabPath, c.code)
    }).catch(() => null)
    loadAssignments(couponId)
  }, [couponId])

  async function reconcileAssignments(targetId: number) {
    const clientAdds    = draftClientIds.filter((cid) => !originalClientIds.includes(cid))
    const clientRemoves = originalClientIds.filter((cid) => !draftClientIds.includes(cid))
    for (const cid of clientAdds)    { await saveCouponClient(targetId, cid) }
    for (const cid of clientRemoves) { const ccliId = clientCcliMapRef.current[cid]; if (ccliId) await deleteCouponClient(targetId, ccliId) }

    const productAdds    = draftProductIds.filter((pid) => !originalProductIds.includes(pid))
    const productRemoves = originalProductIds.filter((pid) => !draftProductIds.includes(pid))
    for (const pid of productAdds)    { await saveCouponProduct(targetId, pid) }
    for (const pid of productRemoves) { const cprodId = productCprodMapRef.current[pid]; if (cprodId) await deleteCouponProduct(targetId, cprodId) }
  }

  // Onglets masqués selon les droits (l'accès à un onglet = capacité de même clé).
  // Clients/Products restent en plus verrouillés tant que le toggle "Assign to
  // accounts/products" correspondant n'est pas activé (Mantis 0010792).
  const TABS = [
    { key: 'information', label: t('tab_information'), icon: <GearIcon /> },
    { key: 'clients',     label: t('tab_clients'),  icon: <UsersIcon />, disabled: !assignClients },
    { key: 'products',    label: t('tab_products'), icon: <TagIcon />, disabled: !assignProducts },
    { key: 'orders',      label: t('tab_orders'),   disabled: !isEdit, icon: <CartIcon /> },
  ].filter((tb) => can(tb.key))

  // Si l'onglet actif vient d'être retiré par les droits, ou verrouillé par un toggle
  // qu'on vient de désactiver, basculer sur le premier onglet disponible.
  useEffect(() => {
    if (capsLoaded && TABS.length && !TABS.some((tb) => tb.key === activeTab && !tb.disabled)) setActiveTab(TABS[0].key)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [capsLoaded, assignClients, assignProducts])

  async function submit() {
    if (!code.trim()) { setErrCode(true); setFormError(true); return }
    if (percentage.trim() === '' && discountValue.trim() === '') { setErrDiscount(true); setFormError(true); return }
    setFormError(false)
    setSaving(true)
    try {
      const payload = {
        code: code.trim(), status, assignClients, assignProducts,
        percentage, discountValue, maxUseNumber, dateValidStart, dateValidEnd,
      }
      if (isEdit && couponId) {
        await saveCoupon({ ...payload, id: couponId })
        await reconcileAssignments(couponId)
        loadAssignments(couponId)
        updateSubLabel(base, subTabPath, code.trim())
        notify('ok', t('title'), t('saved'))
      } else {
        const r = await saveCoupon(payload)
        await reconcileAssignments(r.id)
        notify('ok', t('title'), t('saved'))
        navigate(`${base}/${r.id}`)
      }
    } catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error') }
    finally { setSaving(false) }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box' }}>
      <div style={{ display: 'flex', alignItems: narrow ? 'flex-start' : 'center', justifyContent: 'space-between', gap: 16, flexWrap: narrow ? 'wrap' : 'nowrap' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><TagIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>
            {isEdit ? (coupon ? coupon.code : t('loading')) : t('new')}
          </h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexBasis: narrow ? '100%' : undefined }}>
          <button style={{ ...btnPrimary, flex: narrow ? 1 : undefined, justifyContent: narrow ? 'center' : undefined }} onClick={submit} disabled={saving || (isEdit && !coupon)}>
            {saving ? '…' : t('save')}
          </button>
        </div>
      </div>

      {isEdit && !coupon ? (
        <div style={{ padding: 40, display: 'flex', justifyContent: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <>
          <Tabs tabs={TABS} active={activeTab} onChange={setActiveTab} />
          {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, marginBottom: 16 }}>{t('err_required_fields')}</div>}
          <div>
            {activeTab === 'information' && (
              <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 16, alignItems: 'start' }}>
                <div style={{ ...card, padding: 24 }}>
                  <h3 style={fieldSectionTitle}><GearIcon />{t('section_general_data')}</h3>
                  <div style={fieldGap}>
                    <div>
                      <div style={labelRow}><label style={label}>{t('status_label')}</label></div>
                      <StatusBadge active={status} onClick={() => setStatus((s) => !s)} t={t} />
                    </div>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_code')} *</label><InfoDot text={t('tip_code')} /></div>
                      <input style={{ ...inputCss, textTransform: 'uppercase' }} value={code}
                        onChange={(e) => { setCode(e.target.value); setErrCode(false) }} />
                      {errCode && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_code_required')}</p>}
                    </div>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_valid_start')}</label><InfoDot text={t('tip_valid_start')} /></div>
                      <DatePicker t={t} value={dateValidStart} onChange={setDateValidStart} />
                    </div>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_valid_end')}</label><InfoDot text={t('tip_valid_end')} /></div>
                      <DatePicker t={t} value={dateValidEnd} onChange={setDateValidEnd} />
                    </div>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_assign_clients')}</label><InfoDot text={t('tip_assign_clients')} /></div>
                      <YesNoToggle value={assignClients} onChange={setAssignClients} t={t} />
                    </div>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_assign_products')}</label><InfoDot text={t('tip_assign_products')} /></div>
                      <YesNoToggle value={assignProducts} onChange={setAssignProducts} t={t} />
                    </div>
                  </div>
                </div>

                <div style={{ ...card, padding: 24 }}>
                  <h3 style={fieldSectionTitle}><ValuesIcon />{t('section_values')}</h3>
                  <div style={fieldGap}>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_percentage')}</label><InfoDot text={t('tip_percentage')} /></div>
                      <input style={inputCss} type="number" step="0.01" value={percentage}
                        onChange={(e) => { setPercentage(e.target.value); setErrDiscount(false) }} />
                    </div>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_discount_value')}</label><InfoDot text={t('tip_discount_value')} /></div>
                      <input style={inputCss} type="number" step="0.01" value={discountValue}
                        onChange={(e) => { setDiscountValue(e.target.value); setErrDiscount(false) }} />
                      {errDiscount && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_discount_required')}</p>}
                    </div>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_current_use')}</label><InfoDot text={t('tip_current_use')} /></div>
                      <div style={{ ...inputCss, display: 'flex', alignItems: 'center', background: 'var(--color-muted,var(--color-secondary))', color: 'var(--color-muted-foreground)' }}>
                        {coupon?.currentUseNumber ?? 0}
                      </div>
                    </div>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_max_use')} *</label><InfoDot text={t('tip_max_use')} /></div>
                      <input style={inputCss} type="number" value={maxUseNumber} onChange={(e) => setMaxUseNumber(e.target.value)} />
                    </div>
                  </div>
                </div>
              </div>
            )}
            {activeTab === 'clients' && (
              <CouponClientsTab directory={clientDirectory} draftIds={draftClientIds} onChange={setDraftClientIds} t={t} />
            )}
            {activeTab === 'products' && (
              <CouponProductsTab directory={productDirectory} draftIds={draftProductIds} onChange={setDraftProductIds} t={t} />
            )}
            {activeTab === 'orders' && couponId && <CouponOrdersTab couponId={couponId} t={t} />}
          </div>
        </>
      )}
    </div>
  )
}
