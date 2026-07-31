import { Fragment, useEffect, useRef, useState, type ReactNode } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  fetchOrderStatuses, fetchAllOrderStatuses, fetchOrderStatusStats, fetchOrderStatusOptions, fetchOrderStatusById, saveOrderStatus, deleteOrderStatus,
  type OrderStatusItem, type OrderStatusStats, type OrderStatusSortKey, type OrderStatusOptions, type OrderStatusDetail, type OrderStatusTranslation, type LangOption,
} from './api'
import { useKeysetList } from '../../shared/use-keyset-list'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT } from '../../shared/i18n'
import { card, inputCss, label, btnGhost, btnPrimary, th, td, iconBtn } from '../../shared/styles'
import { TagIcon, CheckIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon, GlobeIcon, ListIcon, ResetIcon, SortIcon, Spinner } from '../../shared/icons'
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

const TOOL_MELIS_KEY = 'meliscommerce_order_status_tool_page'

const COL_ORDER = ['id', 'color', 'name', 'status'] as const
const COL_LABEL: Record<string, string> = { id: 'col_id', color: 'col_color', name: 'col_name', status: 'col_status' }
const cols$ = makeColStore('melis-order-status-cols-v1', COL_ORDER)
const ESSENTIAL_COLS = new Set(['name'])

const SORTABLE = new Set<OrderStatusSortKey>(['id', 'name', 'status'])

const listCache = makeCache<{
  items: OrderStatusItem[]; stats: OrderStatusStats | null; total: number; cursor: string | null; hasMore: boolean
  search: string; searchInput: string; filterStatus: number | null; sortCol: string; sortDir: 'asc' | 'desc'; mode: 'react' | 'old'
}>()

const labelRow = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 } as const
const fieldSectionTitle = { display: 'flex', alignItems: 'center', gap: 8, fontSize: 15, fontWeight: 500, color: 'var(--color-muted-foreground)', margin: '0 0 20px' } as const
const fieldGap = { display: 'flex', flexDirection: 'column', gap: 16 } as const

function InfoDot({ text }: { text: string }) {
  if (!text) return null
  return (
    <span title={text} style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 15, height: 15, borderRadius: 999, background: 'var(--color-muted-foreground)', color: 'var(--color-background,#fff)', fontSize: 10, fontWeight: 700, cursor: 'help', flexShrink: 0 }}>i</span>
  )
}

function GearIcon() {
  return <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ width: 16, height: 16, flexShrink: 0 }}><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" /></svg>
}

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

function ColorSwatch({ color }: { color: string }) {
  return (
    <span style={{ display: 'inline-flex', alignItems: 'center', gap: 8 }}>
      <span style={{ width: 16, height: 16, borderRadius: 4, background: color || '#6b7280', border: '1px solid var(--color-border)', flexShrink: 0 }} />
      <code style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>{color}</code>
    </span>
  )
}

function PermanentBadge({ t }: { t: (k: string) => string }) {
  return (
    <span title={t('permanent_badge')} style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 20, height: 20, borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.06))', color: 'var(--color-muted-foreground)' }}>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ width: 12, height: 12 }}><rect x="3" y="11" width="18" height="11" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" /></svg>
    </span>
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

function getCellExport(s: OrderStatusItem, id: string): string | number {
  switch (id) {
    case 'id':     return s.id
    case 'color':  return s.colorCode
    case 'name':   return s.name
    case 'status': return s.status === 1 ? 'Active' : 'Inactive'
    default:       return ''
  }
}

export default function OrderStatusPage() {
  const { id } = useParams()
  const location = useLocation()
  const base = id ? location.pathname.slice(0, location.pathname.length - id.length - 1) : location.pathname
  if (id) return <OrderStatusForm key={id} id={id} base={base} />
  return <OrderStatusList base={base} />
}

// ── ORDER STATUS LIST ────────────────────────────────────────────────────────────
function OrderStatusList({ base }: { base: string }) {
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
  const [stats, setStats] = useState<OrderStatusStats | null>(cached?.stats ?? null)
  const [searchInput, setSearchInput] = useState(cached?.searchInput ?? '')
  const [search, setSearch] = useState(cached?.search ?? '')
  const [filterStatus, setFilterStatus] = useState<number | null>(cached?.filterStatus ?? null)
  const [exportItems, setExportItems] = useState<OrderStatusItem[]>([])
  const [exporting, setExporting] = useState(false)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [toDelete, setToDelete] = useState<OrderStatusItem | null>(null)
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }

  const {
    items, setItems, total, loading, hasMore, sentinelRef, sortCol, sortDir, setSortCol, setSortDir, toggleSort, snapshot,
  } = useKeysetList<OrderStatusItem>({
    fetcher: (a) => fetchOrderStatuses({ ...a, sort: a.sort as OrderStatusSortKey, search, status: filterStatus }),
    deps: [search, filterStatus, tick],
    defaultSort: 'id',
    defaultDir: 'asc',
    initial: cached ? { items: cached.items, total: cached.total, cursor: cached.cursor, hasMore: cached.hasMore, sortCol: cached.sortCol, sortDir: cached.sortDir } : undefined,
    skipInitial: !!(cached && cached.items.length),
  })

  const cacheRef = useRef({ ...snapshot(), stats, search, searchInput, filterStatus, mode })
  useEffect(() => { cacheRef.current = { ...snapshot(), stats, search, searchInput, filterStatus, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchOrderStatusStats().then(setStats).catch(() => null) }, [tick])

  const eCols = effectiveCols(cols, ESSENTIAL_COLS, narrow)
  const visible = visibleCols(eCols)
  const hasHidden = eCols.some((c) => !c.visible)
  const totalCols = visible.length + 1 + (hasHidden ? 1 : 0)
  function cellStyle(id: string) {
    if (id === 'id') return { ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' as const }
    if (id === 'name') return { ...td, fontWeight: 600, display: 'flex' as const, alignItems: 'center' as const, gap: 8 }
    return td
  }
  function cellContent(s: OrderStatusItem, id: string): ReactNode {
    switch (id) {
      case 'id': return s.id
      case 'color': return <ColorSwatch color={s.colorCode} />
      case 'name': return <>{s.name}{s.isPermanent && <PermanentBadge t={t} />}</>
      case 'status': return <StatusPill active={s.status === 1} t={t} />
      default: return '—'
    }
  }
  // Export : le keyset ne charge qu'une page → on récupère TOUT le jeu filtré via curseur.
  async function openExport() {
    setExporting(true)
    try { const all = await fetchAllOrderStatuses({ search, status: filterStatus }); setExportItems(all); setShowExport(true) }
    catch { /* ignore */ } finally { setExporting(false) }
  }

  const openStatus = (s: OrderStatusItem) => {
    const path = `${base}/${s.id}`
    navigate(path)
    openSubTab(base, { id: path, label: s.name, path })
  }

  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteOrderStatus(toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) }
    catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error'); setToDelete(null) }
  }


  // Réinitialiser les filtres : recherche + statut + tri par défaut (id asc), retour page 1, puis refetch.
  // On vide `items` : sinon les lignes restent affichées pendant le refetch et le clic paraît sans effet.
  function resetFilters() {
    setSearchInput(''); setSearch('')
    setFilterStatus(null)
    setSortCol('id'); setSortDir('asc')
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
          <Kpi icon={<TagIcon />}  label={t('kpi_total')} value={stats?.total ?? null} iconBg="rgba(59,130,246,0.1)" iconColor="#3b82f6" />
          <Kpi icon={<CheckIcon />} label={t('kpi_active')} value={stats?.active ?? null} iconBg="rgba(16,185,129,0.1)" iconColor="#10b981" />
          <Kpi icon={<ListIcon />} label={t('kpi_inactive')} value={stats?.inactive ?? null} iconBg="rgba(107,114,128,0.1)" iconColor="#6b7280" />
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
          <div style={{ marginLeft: narrow ? 0 : 'auto', display: 'flex', gap: 8, flexBasis: narrow ? '100%' : undefined }}>
            <button style={{ ...btnGhost, height: narrow ? 'auto' : 36, minHeight: narrow ? 36 : undefined, flex: narrow ? 1 : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} onClick={resetFilters}><ResetIcon />{t('reset_filters')}</button>
            <div style={{ position: 'relative', flex: narrow ? 1 : undefined, minWidth: narrow ? 0 : undefined, display: narrow ? 'flex' : undefined }}>
              <button style={{ ...btnGhost, height: narrow ? '100%' : 36, minHeight: narrow ? 36 : undefined, width: narrow ? '100%' : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} onClick={() => setShowCols((v) => !v)}><GripIcon />{t('btn_col_manager')}</button>
              {showCols && (
                <ColManager cols={cols} labelFor={(id) => t(COL_LABEL[id])} onChange={setCols}
                  onClose={() => setShowCols(false)} save={cols$.save} defaults={cols$.DEFAULT} t={t} />
              )}
            </div>
            {can('export') && <button style={{ ...btnGhost, height: narrow ? 'auto' : 36, minHeight: narrow ? 36 : undefined, opacity: exporting || items.length === 0 ? 0.6 : 1, flex: narrow ? 1 : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} disabled={exporting || items.length === 0} onClick={openExport}>{exporting ? <Spinner /> : <FileDownIcon />}{t('btn_export')}</button>}
          </div>
        </div>

        <div style={{ ...card, overflowX: 'auto' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: narrow ? undefined : 700 }}>
            <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
              <tr>
                {hasHidden && <th style={{ ...th, width: 32 }} />}
                {visible.map((c) => {
                  const sortable = SORTABLE.has(c.id as OrderStatusSortKey)
                  return (
                    <th key={c.id} style={{ ...th, cursor: sortable ? 'pointer' : 'default', userSelect: 'none' }} onClick={sortable ? () => toggleSort(c.id) : undefined}>
                      <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4 }}>{t(COL_LABEL[c.id])}{sortable && <SortIcon dir={sortCol === c.id ? sortDir : null} />}</span>
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
              {items.map((s) => (
                <Fragment key={s.id}>
                  <tr style={{ cursor: 'pointer' }}
                    onClick={() => openStatus(s)}
                    onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                    onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                    {hasHidden && (
                      <td style={td} onClick={(e) => e.stopPropagation()}>
                        <ExpandToggle expanded={expanded.has(s.id)} onClick={() => toggleExpand(s.id)} />
                      </td>
                    )}
                    {visible.map((col) => (
                      <td key={col.id} style={cellStyle(col.id)}>{cellContent(s, col.id)}</td>
                    ))}
                    <td style={{ ...td, textAlign: 'center', width: 90, position: 'sticky', right: 0, background: 'var(--color-card,#fff)' }} onClick={(e) => e.stopPropagation()}>
                      <div style={{ display: 'inline-flex', gap: 6 }}>
                        {can('edit') && <button onClick={(e) => { e.stopPropagation(); openStatus(s) }}
                          style={iconBtn} title={t('edit')}><PencilIcon /></button>}
                        {can('delete') && !s.isPermanent && <button onClick={(e) => { e.stopPropagation(); setToDelete(s) }}
                          style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')}><TrashIcon /></button>}
                      </div>
                    </td>
                  </tr>
                  {expanded.has(s.id) && (
                    <HiddenColsRow cols={eCols} labelFor={(id) => t(COL_LABEL[id])} renderValue={(id) => cellContent(s, id)} colSpan={totalCols} narrow={narrow} />
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
        <ExportModal cols={cols} items={exportItems} getCell={(s, id) => getCellExport(s, id)}
          labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')}
          sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />
      )}

      {toDelete && (
        <ConfirmModal title={t('del_title')} message={t('del_confirm', { u: toDelete.name })}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
      )}
    </div>
  )
}

// ── ORDER STATUS FORM ────────────────────────────────────────────────────────────
function OrderStatusForm({ id, base }: { id: string; base: string }) {
  const t = makeT(DICT)
  const narrow = useIsNarrow()
  const navigate = useNavigate()
  const isEdit = id !== 'new'
  const statusId = isEdit ? Number(id) : null
  const subTabPath = `${base}/${id}`
  const { can, loaded: capsLoaded } = useCaps(TOOL_MELIS_KEY)

  const [orderStatus, setOrderStatus] = useState<OrderStatusDetail | null>(null)
  const [options, setOptions] = useState<OrderStatusOptions | null>(null)
  const [activeTab, setActiveTab] = useState('main')
  const [saving, setSaving] = useState(false)
  const [lang, setLang] = useState<number>(0)

  const [colorCode, setColorCode] = useState('#0696cb')
  const [status, setStatus] = useState(true)
  const [translations, setTranslations] = useState<OrderStatusTranslation[]>([])
  const [errColor, setErrColor] = useState(false)
  const [errName, setErrName] = useState(false)
  const [formError, setFormError] = useState(false)

  useEffect(() => {
    openSubTab(base, { id: subTabPath, label: isEdit ? t('loading') : t('new'), path: subTabPath })
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  useEffect(() => {
    fetchOrderStatusOptions().then((o) => {
      setOptions(o)
      setLang((v) => v || o.languages[0]?.id || 0)
      setTranslations((prev) => prev.length ? prev : o.languages.map((l) => ({ langId: l.id, name: '' })))
    }).catch(() => null)
  }, [])

  useEffect(() => {
    if (statusId === null) return
    fetchOrderStatusById(statusId).then((s) => {
      setOrderStatus(s)
      setColorCode(s.colorCode); setStatus(s.status === 1)
      setTranslations((prev) => {
        const byLang = new Map(s.translations.map((tr) => [tr.langId, tr]))
        return prev.map((tr) => byLang.get(tr.langId) ?? tr)
      })
      const name = s.translations.find((tr) => tr.name.trim())?.name || `#${s.id}`
      updateSubLabel(base, subTabPath, name)
    }).catch(() => null)
  }, [statusId])

  function setTrans(langId: number, value: string) {
    setTranslations((prev) => prev.map((x) => x.langId === langId ? { ...x, name: value } : x))
  }

  // Onglets masqués selon les droits (l'accès à un onglet = capacité de même clé : main/labels).
  const TABS = [
    { key: 'main',   label: t('tab_main'),   icon: <GearIcon /> },
    { key: 'labels', label: t('tab_labels'), icon: <GlobeIcon /> },
  ].filter((tb) => can(tb.key))

  // Si l'onglet actif vient d'être retiré par les droits, basculer sur le premier autorisé.
  useEffect(() => {
    if (capsLoaded && TABS.length && !TABS.some((tb) => tb.key === activeTab)) setActiveTab(TABS[0].key)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [capsLoaded])

  async function submit() {
    if (!colorCode.trim()) { setErrColor(true); setFormError(true); return }
    if (!translations.some((tr) => tr.name.trim())) { setErrName(true); setFormError(true); return }
    setFormError(false)
    setSaving(true)
    try {
      const payload = { colorCode: colorCode.trim(), status, translations }
      if (isEdit && statusId !== null) {
        await saveOrderStatus({ ...payload, id: statusId })
        const name = translations.find((tr) => tr.name.trim())?.name || `#${statusId}`
        updateSubLabel(base, subTabPath, name)
        notify('ok', t('title'), t('saved'))
      } else {
        const r = await saveOrderStatus(payload)
        notify('ok', t('title'), t('saved'))
        navigate(`${base}/${r.id}`)
      }
    } catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error') }
    finally { setSaving(false) }
  }

  const currentTrans = translations.find((tr) => tr.langId === lang)
  const isPermanent = orderStatus?.isPermanent ?? false

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box' }}>
      <div style={{ display: 'flex', alignItems: narrow ? 'flex-start' : 'center', justifyContent: 'space-between', gap: 16, flexWrap: narrow ? 'wrap' : 'nowrap' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><TagIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0, display: 'flex', alignItems: 'center', gap: 8 }}>
            {isEdit ? (orderStatus ? (translations.find((tr) => tr.name.trim())?.name || `#${orderStatus.id}`) : t('loading')) : t('new')}
            {isPermanent && <PermanentBadge t={t} />}
          </h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexBasis: narrow ? '100%' : undefined }}>
          <button style={{ ...btnPrimary, flex: narrow ? 1 : undefined, justifyContent: narrow ? 'center' : undefined }} onClick={submit} disabled={saving || (isEdit && !orderStatus)}>
            {saving ? '…' : t('save')}
          </button>
        </div>
      </div>

      {isEdit && !orderStatus ? (
        <div style={{ padding: 40, display: 'flex', justifyContent: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <>
          <Tabs tabs={TABS} active={activeTab} onChange={setActiveTab} />
          {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, marginBottom: 16 }}>{t('err_required_fields')}</div>}
          <div>
            {activeTab === 'main' && (
              <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 260px', gap: 16, alignItems: 'start' }}>
                <div style={{ ...card, padding: 24 }}>
                  <h3 style={fieldSectionTitle}><GearIcon />{t('section_general_data')}</h3>
                  <div style={fieldGap}>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_color')} *</label><InfoDot text={t('tip_color')} /></div>
                      <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                        <input type="color" value={/^#[0-9a-fA-F]{6}$/.test(colorCode) ? colorCode : '#6b7280'}
                          onChange={(e) => { setColorCode(e.target.value); setErrColor(false) }}
                          style={{ width: 40, height: 36, padding: 2, border: '1px solid var(--color-border)', borderRadius: 6, cursor: 'pointer', background: 'transparent' }} />
                        <input style={{ ...inputCss, flex: 1 }} value={colorCode}
                          onChange={(e) => { setColorCode(e.target.value); setErrColor(false) }} placeholder="#0696cb" />
                      </div>
                      {errColor && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_color_required')}</p>}
                    </div>
                  </div>
                </div>

                <div style={{ ...card, padding: 24 }}>
                  <h3 style={fieldSectionTitle}>{t('status_label')}</h3>
                  <StatusBadge active={status} onClick={() => setStatus((s) => !s)} t={t} />
                </div>
              </div>
            )}

            {activeTab === 'labels' && (
              <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '200px 1fr', gap: 16, alignItems: 'start' }}>
                <div style={{ ...card, padding: 12 }}>
                  <LangSwitcher languages={options?.languages ?? []} value={lang} onChange={setLang} narrow={narrow} />
                </div>
                <div style={{ ...card, padding: 24 }}>
                  <div style={fieldGap}>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_name')} *</label><InfoDot text={t('tip_name')} /></div>
                      <input style={inputCss} value={currentTrans?.name ?? ''}
                        onChange={(e) => { setTrans(lang, e.target.value); setErrName(false) }} />
                      {errName && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_name_required')}</p>}
                    </div>
                  </div>
                </div>
              </div>
            )}
          </div>
        </>
      )}
    </div>
  )
}
