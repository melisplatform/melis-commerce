import { Fragment, useEffect, useMemo, useRef, useState, type ReactNode } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  fetchAttributes, fetchAttributeStats, fetchAttributeOptions, fetchAttributeById, saveAttribute, deleteAttribute,
  type AttributeItem, type AttributeStats, type AttributeOptions, type AttributeDetail, type AttributeTranslation, type LangOption,
} from './api'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT } from '../../shared/i18n'
import { card, inputCss, label, btnGhost, btnPrimary, th, td, iconBtn } from '../../shared/styles'
import { TagIcon, CheckIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon, GlobeIcon, ListIcon, ResetIcon } from '../../shared/icons'
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
import { AttributeValuesTab } from './AttributeTabs'

const TOOL_MELIS_KEY = 'meliscommerce_attribute_list_page'

const COL_ORDER = ['id', 'name', 'reference', 'type', 'status', 'visible', 'searchable', 'values'] as const
const COL_LABEL: Record<string, string> = {
  id: 'col_id', name: 'col_name', reference: 'col_reference', type: 'col_type',
  status: 'col_status', visible: 'col_visible', searchable: 'col_searchable', values: 'col_values',
}
const cols$ = makeColStore('melis-attribute-cols-v1', COL_ORDER)
const ESSENTIAL_COLS = new Set(['name'])

const listCache = makeCache<{
  items: AttributeItem[]; stats: AttributeStats | null; total: number; page: number
  search: string; searchInput: string; filterStatus: number | null; filterType: number | null; sortCol: string; sortAsc: boolean; mode: 'react' | 'old'
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
      display: 'inline-flex', alignItems: 'center', gap: 5, padding: '2px 8px',
      borderRadius: 20, fontSize: 11, fontWeight: 600, color: '#fff',
      background: active ? '#16a34a' : '#6b7280', whiteSpace: 'nowrap',
    }}>
      {active ? t('status_active') : t('status_inactive')}
    </span>
  )
}

function CheckDot({ on }: { on: boolean }) {
  return on
    ? <span style={{ color: '#16a34a', display: 'inline-flex' }}><CheckIcon /></span>
    : <span style={{ color: 'var(--color-muted-foreground)' }}>—</span>
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

function getCellExport(a: AttributeItem, id: string): string | number {
  switch (id) {
    case 'id':         return a.id
    case 'name':       return a.name
    case 'reference':  return a.reference
    case 'type':       return a.typeName
    case 'status':     return a.status === 1 ? 'Active' : 'Inactive'
    case 'visible':    return a.visible ? 'Yes' : 'No'
    case 'searchable': return a.searchable ? 'Yes' : 'No'
    case 'values':     return a.valuesCount
    default:           return ''
  }
}

export default function AttributePage() {
  const { id } = useParams()
  const location = useLocation()
  const base = id ? location.pathname.slice(0, location.pathname.length - id.length - 1) : location.pathname
  if (id) return <AttributeForm key={id} id={id} base={base} />
  return <AttributeList base={base} />
}

// ── ATTRIBUTE LIST ───────────────────────────────────────────────────────────────
function AttributeList({ base }: { base: string }) {
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
  const [items, setItems] = useState<AttributeItem[]>(listCache.get()?.items ?? [])
  const [stats, setStats] = useState<AttributeStats | null>(listCache.get()?.stats ?? null)
  const [options, setOptions] = useState<AttributeOptions | null>(null)
  const [loading, setLoading] = useState(false)
  const [total, setTotal] = useState(listCache.get()?.total ?? 0)
  const [page, setPage] = useState(listCache.get()?.page ?? 1)
  const LIMIT = 50
  const [searchInput, setSearchInput] = useState(listCache.get()?.searchInput ?? '')
  const [search, setSearch] = useState(listCache.get()?.search ?? '')
  const [filterStatus, setFilterStatus] = useState<number | null>(listCache.get()?.filterStatus ?? null)
  const [filterType, setFilterType] = useState<number | null>(listCache.get()?.filterType ?? null)
  const [sortCol, setSortCol] = useState(listCache.get()?.sortCol ?? 'id')
  const [sortAsc, setSortAsc] = useState(listCache.get()?.sortAsc ?? false)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [toDelete, setToDelete] = useState<AttributeItem | null>(null)
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }

  const cacheRef = useRef({ items, stats, total, page, search, searchInput, filterStatus, filterType, sortCol, sortAsc, mode })
  useEffect(() => { cacheRef.current = { items, stats, total, page, search, searchInput, filterStatus, filterType, sortCol, sortAsc, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchAttributeStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => { fetchAttributeOptions().then(setOptions).catch(() => null) }, [])
  useEffect(() => {
    setLoading(true)
    fetchAttributes({ search, status: filterStatus, typeId: filterType, page, limit: LIMIT })
      .then((r) => { setItems(r.items); setTotal(r.total) })
      .catch(() => null)
      .finally(() => setLoading(false))
  }, [search, filterStatus, filterType, page, tick])

  const eCols = effectiveCols(cols, ESSENTIAL_COLS, narrow)
  const visible = visibleCols(eCols)
  const hasHidden = eCols.some((c) => !c.visible)
  const totalCols = visible.length + 1 + (hasHidden ? 1 : 0)
  function cellStyle(id: string) {
    if (id === 'id') return { ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' as const }
    if (id === 'name') return { ...td, fontWeight: 600 }
    if (id === 'type') return { ...td, color: 'var(--color-muted-foreground)' }
    if (id === 'visible' || id === 'searchable') return { ...td, textAlign: 'center' as const }
    if (id === 'values') return { ...td, textAlign: 'center' as const, color: 'var(--color-muted-foreground)' }
    return td
  }
  function cellContent(a: AttributeItem, id: string): ReactNode {
    switch (id) {
      case 'id': return a.id
      case 'name': return a.name
      case 'reference': return <code style={{ fontSize: 12 }}>{a.reference}</code>
      case 'type': return a.typeName
      case 'status': return <StatusPill active={a.status === 1} t={t} />
      case 'visible': return <CheckDot on={!!a.visible} />
      case 'searchable': return <CheckDot on={!!a.searchable} />
      case 'values': return a.valuesCount
      default: return '—'
    }
  }
  const sorted = useMemo(() => {
    const arr = [...items]
    arr.sort((a, b) => {
      let va: string | number = '', vb: string | number = ''
      if (sortCol === 'id')         { va = a.id; vb = b.id }
      if (sortCol === 'name')       { va = a.name; vb = b.name }
      if (sortCol === 'reference')  { va = a.reference; vb = b.reference }
      if (sortCol === 'type')       { va = a.typeName; vb = b.typeName }
      if (sortCol === 'status')     { va = a.status; vb = b.status }
      if (sortCol === 'visible')    { va = a.visible; vb = b.visible }
      if (sortCol === 'searchable') { va = a.searchable; vb = b.searchable }
      if (sortCol === 'values')     { va = a.valuesCount; vb = b.valuesCount }
      if (va < vb) return sortAsc ? -1 : 1
      if (va > vb) return sortAsc ? 1 : -1
      return 0
    })
    return arr
  }, [items, sortCol, sortAsc])

  const onSort = (col: string) => { if (sortCol === col) setSortAsc((p) => !p); else { setSortCol(col); setSortAsc(true) } }
  const arrow  = (col: string) => sortCol === col ? (sortAsc ? ' ↑' : ' ↓') : ''
  const totalPages = Math.max(1, Math.ceil(total / LIMIT))

  const openAttribute = (a: AttributeItem) => {
    const path = `${base}/${a.id}`
    navigate(path)
    openSubTab(base, { id: path, label: a.name, path })
  }

  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteAttribute(toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) }
  }


  // Réinitialiser les filtres : recherche + statut + type + tri par défaut (id desc), retour page 1, puis refetch.
  // On vide `items` : sinon les lignes restent affichées pendant le refetch et le clic paraît sans effet.
  function resetFilters() {
    setSearchInput(''); setSearch('')
    setFilterStatus(null)
    setFilterType(null)
    setSortCol('id'); setSortAsc(false)
    setItems([])
    setPage(1)
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
            onKeyDown={(e) => { if (e.key === 'Enter') { setSearch(searchInput); setPage(1) } }} />
          <select style={{ ...inputCss, height: 36, width: narrow ? '100%' : 'auto', minWidth: 160, flexBasis: narrow ? '100%' : undefined }}
            value={filterStatus ?? ''} onChange={(e) => { setFilterStatus(e.target.value === '' ? null : Number(e.target.value)); setPage(1) }}>
            <option value="">{t('filter_status')}</option>
            <option value={1}>{t('status_active')}</option>
            <option value={0}>{t('status_inactive')}</option>
          </select>
          <select style={{ ...inputCss, height: 36, width: narrow ? '100%' : 'auto', minWidth: 160, flexBasis: narrow ? '100%' : undefined }}
            value={filterType ?? ''} onChange={(e) => { setFilterType(e.target.value === '' ? null : Number(e.target.value)); setPage(1) }}>
            <option value="">{t('filter_type')}</option>
            {(options?.types ?? []).map((ty) => <option key={ty.id} value={ty.id}>{ty.name}</option>)}
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
            {can('export') && <button style={{ ...btnGhost, height: narrow ? 'auto' : 36, minHeight: narrow ? 36 : undefined, opacity: sorted.length === 0 ? 0.4 : 1, flex: narrow ? 1 : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} disabled={sorted.length === 0} onClick={() => setShowExport(true)}><FileDownIcon />{t('btn_export')}</button>}
          </div>
        </div>

        <div style={{ ...card, overflowX: 'auto' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: narrow ? undefined : 700 }}>
            <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
              <tr>
                {hasHidden && <th style={{ ...th, width: 32 }} />}
                {visible.map((c) => (
                  <th key={c.id} style={{ ...th, cursor: 'pointer', userSelect: 'none' }} onClick={() => onSort(c.id)}>
                    {t(COL_LABEL[c.id])}{arrow(c.id)}
                  </th>
                ))}
                <th style={{ ...th, width: 90, textAlign: 'center', position: 'sticky', right: 0, background: 'var(--color-muted,rgba(0,0,0,.03))' }}>{t('col_action')}</th>
              </tr>
            </thead>
            <tbody>
              {loading && sorted.length === 0 && (
                <tr><td colSpan={totalCols} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('loading')}</td></tr>
              )}
              {!loading && sorted.length === 0 && (
                <tr><td colSpan={totalCols} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('no_items')}</td></tr>
              )}
              {sorted.map((a) => (
                <Fragment key={a.id}>
                  <tr style={{ cursor: 'pointer' }}
                    onClick={() => openAttribute(a)}
                    onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                    onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                    {hasHidden && (
                      <td style={td} onClick={(e) => e.stopPropagation()}>
                        <ExpandToggle expanded={expanded.has(a.id)} onClick={() => toggleExpand(a.id)} />
                      </td>
                    )}
                    {visible.map((col) => (
                      <td key={col.id} style={cellStyle(col.id)}>{cellContent(a, col.id)}</td>
                    ))}
                    <td style={{ ...td, textAlign: 'center', width: 90, position: 'sticky', right: 0, background: 'var(--color-card,#fff)' }} onClick={(e) => e.stopPropagation()}>
                      <div style={{ display: 'inline-flex', gap: 6 }}>
                        {can('edit') && <button onClick={(e) => { e.stopPropagation(); openAttribute(a) }}
                          style={iconBtn} title={t('edit')}><PencilIcon /></button>}
                        {can('delete') && <button onClick={(e) => { e.stopPropagation(); setToDelete(a) }}
                          style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')}><TrashIcon /></button>}
                      </div>
                    </td>
                  </tr>
                  {expanded.has(a.id) && (
                    <HiddenColsRow cols={eCols} labelFor={(id) => t(COL_LABEL[id])} renderValue={(id) => cellContent(a, id)} colSpan={totalCols} narrow={narrow} />
                  )}
                </Fragment>
              ))}
            </tbody>
          </table>
          <div style={{ padding: '10px 16px', textAlign: 'center', fontSize: 12, color: 'var(--color-muted-foreground)' }}>
            {loading ? t('loading') : t('count', { n: total })}
          </div>
        </div>

        {totalPages > 1 && (
          <div style={{ display: 'flex', gap: 6, justifyContent: 'center', alignItems: 'center', flexWrap: 'wrap' }}>
            <button style={btnGhost} disabled={page <= 1} onClick={() => setPage((p) => p - 1)}>←</button>
            {Array.from({ length: Math.min(totalPages, 10) }, (_, i) => i + 1).map((p) => (
              <button key={p} style={{ ...btnGhost, fontWeight: p === page ? 700 : 400, opacity: p === page ? 1 : 0.6 }}
                onClick={() => setPage(p)}>{p}</button>
            ))}
            {totalPages > 10 && <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>…{totalPages}</span>}
            <button style={btnGhost} disabled={page >= totalPages} onClick={() => setPage((p) => p + 1)}>→</button>
          </div>
        )}
      </div>

      {showExport && (
        <ExportModal cols={cols} items={sorted} getCell={(a, id) => getCellExport(a, id)}
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

// ── ATTRIBUTE FORM ───────────────────────────────────────────────────────────────
function AttributeForm({ id, base }: { id: string; base: string }) {
  const t = makeT(DICT)
  const narrow = useIsNarrow()
  const navigate = useNavigate()
  const isEdit = id !== 'new'
  const attributeId = isEdit ? Number(id) : null
  const subTabPath = `${base}/${id}`
  const { can, loaded: capsLoaded } = useCaps(TOOL_MELIS_KEY)

  const [attribute, setAttribute] = useState<AttributeDetail | null>(null)
  const [options, setOptions] = useState<AttributeOptions | null>(null)
  const [activeTab, setActiveTab] = useState('main')
  const [saving, setSaving] = useState(false)
  const [lang, setLang] = useState<number>(0)

  const [reference, setReference] = useState('')
  const [typeId, setTypeId] = useState(0)
  const [status, setStatus] = useState(true)
  const [visible, setVisible] = useState(true)
  const [searchable, setSearchable] = useState(true)
  const [translations, setTranslations] = useState<AttributeTranslation[]>([])
  const [errReference, setErrReference] = useState(false)
  const [errType, setErrType] = useState(false)
  const [formError, setFormError] = useState(false)

  useEffect(() => {
    openSubTab(base, { id: subTabPath, label: isEdit ? t('loading') : t('new'), path: subTabPath })
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  useEffect(() => {
    fetchAttributeOptions().then((o) => {
      setOptions(o)
      setLang((v) => v || o.languages[0]?.id || 0)
      setTranslations((prev) => prev.length ? prev : o.languages.map((l) => ({ langId: l.id, name: '', description: '' })))
    }).catch(() => null)
  }, [])

  useEffect(() => {
    if (!attributeId) return
    fetchAttributeById(attributeId).then((a) => {
      setAttribute(a)
      setReference(a.reference); setTypeId(a.typeId)
      setStatus(a.status === 1); setVisible(a.visible === 1); setSearchable(a.searchable === 1)
      setTranslations((prev) => {
        const byLang = new Map(a.translations.map((tr) => [tr.langId, tr]))
        return prev.map((tr) => byLang.get(tr.langId) ?? tr)
      })
      const name = a.translations.find((tr) => tr.name.trim())?.name || a.reference
      updateSubLabel(base, subTabPath, name)
    }).catch(() => null)
  }, [attributeId])

  function setTrans(langId: number, field: 'name' | 'description', value: string) {
    setTranslations((prev) => prev.map((x) => x.langId === langId ? { ...x, [field]: value } : x))
  }

  // Onglets masqués selon les droits (l'accès à un onglet = capacité de même clé : main/labels/values).
  const TABS = [
    { key: 'main',   label: t('tab_main'),   icon: <GearIcon /> },
    { key: 'labels', label: t('tab_labels'), icon: <GlobeIcon /> },
    { key: 'values', label: t('tab_values'), icon: <ListIcon />, disabled: !isEdit },
  ].filter((tb) => can(tb.key))

  // Si l'onglet actif vient d'être retiré par les droits, basculer sur le premier autorisé.
  useEffect(() => {
    if (capsLoaded && TABS.length && !TABS.some((tb) => tb.key === activeTab)) setActiveTab(TABS[0].key)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [capsLoaded])

  async function submit() {
    if (!reference.trim()) { setErrReference(true); setFormError(true); return }
    if (!typeId) { setErrType(true); setFormError(true); return }
    setFormError(false)
    setSaving(true)
    try {
      const payload = { reference: reference.trim(), typeId, status, visible, searchable, translations }
      if (isEdit && attributeId) {
        await saveAttribute({ ...payload, id: attributeId })
        const name = translations.find((tr) => tr.name.trim())?.name || reference.trim()
        updateSubLabel(base, subTabPath, name)
        notify('ok', t('title'), t('saved'))
      } else {
        const r = await saveAttribute(payload)
        notify('ok', t('title'), t('saved'))
        navigate(`${base}/${r.id}`)
      }
    } catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error') }
    finally { setSaving(false) }
  }

  const currentTrans = translations.find((tr) => tr.langId === lang)

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box' }}>
      <div style={{ display: 'flex', alignItems: narrow ? 'flex-start' : 'center', justifyContent: 'space-between', gap: 16, flexWrap: narrow ? 'wrap' : 'nowrap' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><TagIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>
            {isEdit ? (attribute ? (translations.find((tr) => tr.name.trim())?.name || attribute.reference) : t('loading')) : t('new')}
          </h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexBasis: narrow ? '100%' : undefined }}>
          <button style={{ ...btnPrimary, flex: narrow ? 1 : undefined, justifyContent: narrow ? 'center' : undefined }} onClick={submit} disabled={saving || (isEdit && !attribute)}>
            {saving ? '…' : t('save')}
          </button>
        </div>
      </div>

      {isEdit && !attribute ? (
        <div style={{ padding: 40, display: 'flex', justifyContent: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <>
          <Tabs tabs={TABS} active={activeTab} onChange={setActiveTab} />
          {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, marginBottom: 16 }}>{t('err_required_fields')}</div>}
          <div>
            {activeTab === 'main' && (
              <div>
                {/* En-tête : titre + statut (remonté ici, plus de carte minuscule isolée) */}
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, borderBottom: '1px solid var(--color-border)', paddingBottom: 12, marginBottom: 20 }}>
                  <h2 style={{ fontSize: 18, fontWeight: 600, margin: 0 }}>{t('tab_main')}</h2>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                    <span style={{ fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)' }}>{t('status_label')}</span>
                    <StatusBadge active={status} onClick={() => setStatus((s) => !s)} t={t} />
                  </div>
                </div>

                {/* 2 colonnes équilibrées, chaque section dans sa propre carte */}
                <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : 'repeat(auto-fit, minmax(340px, 1fr))', gap: 16, alignItems: 'start' }}>
                  <div style={{ ...card, padding: 20 }}>
                    <h3 style={fieldSectionTitle}><GearIcon />{t('section_general_data')}</h3>
                    <div style={fieldGap}>
                      <div>
                        <div style={labelRow}><label style={label}>{t('field_reference')} *</label><InfoDot text={t('tip_reference')} /></div>
                        <input style={inputCss} value={reference}
                          onChange={(e) => { setReference(e.target.value); setErrReference(false) }} />
                        {errReference && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_reference_required')}</p>}
                      </div>
                      <div>
                        <div style={labelRow}><label style={label}>{t('field_type')} *</label><InfoDot text={t('tip_type')} /></div>
                        <select style={{ ...inputCss, opacity: isEdit ? 0.6 : 1, cursor: isEdit ? 'not-allowed' : 'pointer' }}
                          value={typeId} disabled={isEdit}
                          onChange={(e) => { setTypeId(Number(e.target.value)); setErrType(false) }}>
                          <option value={0} disabled>—</option>
                          {(options?.types ?? []).map((ty) => <option key={ty.id} value={ty.id}>{ty.name}</option>)}
                        </select>
                        {errType && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_type_required')}</p>}
                      </div>
                    </div>
                  </div>

                  <div style={{ ...card, padding: 20 }}>
                    <h3 style={fieldSectionTitle}>{t('section_display')}</h3>
                    <div style={fieldGap}>
                      <div>
                        <div style={labelRow}><label style={label}>{t('field_visible')}</label><InfoDot text={t('tip_visible')} /></div>
                        <YesNoToggle value={visible} onChange={setVisible} t={t} />
                      </div>
                      <div>
                        <div style={labelRow}><label style={label}>{t('field_searchable')}</label><InfoDot text={t('tip_searchable')} /></div>
                        <YesNoToggle value={searchable} onChange={setSearchable} t={t} />
                      </div>
                    </div>
                  </div>
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
                      <div style={labelRow}><label style={label}>{t('field_name')}</label><InfoDot text={t('tip_name')} /></div>
                      <input style={inputCss} value={currentTrans?.name ?? ''}
                        onChange={(e) => setTrans(lang, 'name', e.target.value)} />
                    </div>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_description')}</label><InfoDot text={t('tip_description')} /></div>
                      <textarea style={{ ...inputCss, height: 90, resize: 'vertical', paddingTop: 8 }} value={currentTrans?.description ?? ''} onChange={(e) => setTrans(lang, 'description', e.target.value)} />
                    </div>
                  </div>
                </div>
              </div>
            )}

            {activeTab === 'values' && attributeId && options && (
              <AttributeValuesTab attributeId={attributeId} languages={options.languages} can={can} t={t} />
            )}
          </div>
        </>
      )}
    </div>
  )
}
