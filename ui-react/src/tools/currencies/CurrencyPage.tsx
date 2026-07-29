import { Fragment, useEffect, useMemo, useRef, useState, type ReactNode } from 'react'
import {
  fetchCurrencies, fetchCurrencyStats, fetchCurrencyById, saveCurrency, deleteCurrency, setDefaultCurrency,
  type CurrencyItem, type CurrencyStats,
} from './api'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT, type T } from '../../shared/i18n'
import { card, inputCss, label, btnGhost, btnPrimary, th, td, iconBtn } from '../../shared/styles'
import { CoinsKpiIcon, CheckIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon, ListIcon, StarIcon, ResetIcon } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { makeColStore, visibleCols, effectiveCols, type ColDef } from '../../shared/columns'
import { ExpandToggle, HiddenColsRow } from '../../shared/ExpandableRow'
import { useIsNarrow } from '../../shared/useIsNarrow'
import { ExportModal } from '../../shared/ExportModal'
import { ColManager } from '../../shared/ColManager'
import { useCaps } from '../../shared/useCaps'

const TOOL_MELIS_KEY = 'meliscommerce_currency_conf'

// Outil "plat" (liste + formulaire en modale) : pas de sous-routes /new ou /:id, pas
// d'onglets — une seule section de formulaire, cf. brief de migration.
const COL_ORDER = ['id', 'default', 'status', 'symbol', 'code', 'name'] as const
const COL_LABEL: Record<string, string> = {
  id: 'col_id', default: 'col_default', status: 'col_status', symbol: 'col_symbol', code: 'col_code', name: 'col_name',
}
const cols$ = makeColStore('melis-currency-cols-v1', COL_ORDER)
const ESSENTIAL_COLS = new Set(['code'])

const listCache = makeCache<{
  items: CurrencyItem[]; stats: CurrencyStats | null; total: number; page: number
  search: string; searchInput: string; filterStatus: number | null; sortCol: string; sortAsc: boolean; mode: 'react' | 'old'
}>()

function StatusPill({ active, t }: { active: boolean; t: T }) {
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

/** Toggle switch (piste arrondie + poignée ronde) + libellé coloré, pour le champ Statut du formulaire. */
function StatusToggle({ value, onChange, t }: { value: boolean; onChange: (v: boolean) => void; t: T }) {
  return (
    <div onClick={() => onChange(!value)} style={{ display: 'inline-flex', alignItems: 'center', gap: 10, cursor: 'pointer' }}>
      <span style={{ position: 'relative', display: 'inline-block', width: 44, height: 24, borderRadius: 12, background: value ? '#22c55e' : 'var(--color-border)', transition: 'background .15s', flexShrink: 0 }}>
        <span style={{ position: 'absolute', top: 2, left: value ? 22 : 2, width: 20, height: 20, borderRadius: '50%', background: '#fff', boxShadow: '0 1px 3px rgba(0,0,0,.3)', transition: 'left .15s' }} />
      </span>
      <span style={{ fontSize: 13, fontWeight: 600, color: value ? '#22c55e' : 'var(--color-muted-foreground)' }}>
        {value ? t('status_active') : t('status_inactive')}
      </span>
    </div>
  )
}

/** Étoile "devise par défaut" — pleine = défaut ; contour cliquable = définir par défaut (si autorisé). */
function DefaultStar({ isDefault, canEdit, onSetDefault, t }: { isDefault: boolean; canEdit: boolean; onSetDefault: () => void; t: T }) {
  const clickable = canEdit && !isDefault
  return (
    <button type="button" disabled={!clickable}
      onClick={(e) => { e.stopPropagation(); if (clickable) onSetDefault() }}
      title={isDefault ? t('col_default') : (clickable ? t('make_default') : undefined)}
      style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', background: 'transparent', border: 0, padding: 0, cursor: clickable ? 'pointer' : 'default', opacity: isDefault ? 1 : 0.35 }}>
      <StarIcon filled={isDefault} />
    </button>
  )
}

function getCellExport(c: CurrencyItem, id: string, t: T): string | number {
  switch (id) {
    case 'id':      return c.id
    case 'default': return c.isDefault ? t('yes') : t('no')
    case 'status':  return c.status === 1 ? t('status_active') : t('status_inactive')
    case 'symbol':  return c.symbol
    case 'code':    return c.code
    case 'name':    return c.name
    default:        return ''
  }
}

export default function CurrencyPage() {
  const t = makeT(DICT)
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
  const [items, setItems] = useState<CurrencyItem[]>(listCache.get()?.items ?? [])
  const [stats, setStats] = useState<CurrencyStats | null>(listCache.get()?.stats ?? null)
  const [loading, setLoading] = useState(false)
  const [total, setTotal] = useState(listCache.get()?.total ?? 0)
  const [page, setPage] = useState(listCache.get()?.page ?? 1)
  const LIMIT = 50
  const [searchInput, setSearchInput] = useState(listCache.get()?.searchInput ?? '')
  const [search, setSearch] = useState(listCache.get()?.search ?? '')
  const [filterStatus, setFilterStatus] = useState<number | null>(listCache.get()?.filterStatus ?? null)
  const [sortCol, setSortCol] = useState(listCache.get()?.sortCol ?? 'id')
  const [sortAsc, setSortAsc] = useState(listCache.get()?.sortAsc ?? true)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [toDelete, setToDelete] = useState<CurrencyItem | null>(null)
  // null = fermée, 'new' = création, number = édition de la devise cur_id
  const [formId, setFormId] = useState<number | 'new' | null>(null)
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }

  const cacheRef = useRef({ items, stats, total, page, search, searchInput, filterStatus, sortCol, sortAsc, mode })
  useEffect(() => { cacheRef.current = { items, stats, total, page, search, searchInput, filterStatus, sortCol, sortAsc, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchCurrencyStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => {
    setLoading(true)
    fetchCurrencies({ search, status: filterStatus, page, limit: LIMIT })
      .then((r) => { setItems(r.items); setTotal(r.total) })
      .catch(() => null)
      .finally(() => setLoading(false))
  }, [search, filterStatus, page, tick])

  const eCols = effectiveCols(cols, ESSENTIAL_COLS, narrow)
  const visible = visibleCols(eCols)
  const hasHidden = eCols.some((c) => !c.visible)
  const totalCols = visible.length + 1 + (hasHidden ? 1 : 0)
  function cellStyle(id: string) {
    if (id === 'id') return { ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' as const }
    if (id === 'default') return { ...td, textAlign: 'center' as const }
    if (id === 'symbol') return { ...td, fontWeight: 600 }
    return td
  }
  function cellContent(c: CurrencyItem, id: string): ReactNode {
    switch (id) {
      case 'id': return c.id
      case 'default': return <DefaultStar isDefault={c.isDefault} canEdit={can('edit')} onSetDefault={() => handleSetDefault(c)} t={t} />
      case 'status': return <StatusPill active={c.status === 1} t={t} />
      case 'symbol': return c.symbol
      case 'code': return <code style={{ fontSize: 12 }}>{c.code}</code>
      case 'name': return c.name
      default: return '—'
    }
  }
  const sorted = useMemo(() => {
    const arr = [...items]
    arr.sort((a, b) => {
      let va: string | number = '', vb: string | number = ''
      if (sortCol === 'id')      { va = a.id; vb = b.id }
      if (sortCol === 'default') { va = a.isDefault ? 1 : 0; vb = b.isDefault ? 1 : 0 }
      if (sortCol === 'status')  { va = a.status; vb = b.status }
      if (sortCol === 'symbol')  { va = a.symbol; vb = b.symbol }
      if (sortCol === 'code')    { va = a.code; vb = b.code }
      if (sortCol === 'name')    { va = a.name; vb = b.name }
      if (va < vb) return sortAsc ? -1 : 1
      if (va > vb) return sortAsc ? 1 : -1
      return 0
    })
    return arr
  }, [items, sortCol, sortAsc])

  const onSort = (col: string) => { if (sortCol === col) setSortAsc((p) => !p); else { setSortCol(col); setSortAsc(true) } }
  const arrow  = (col: string) => sortCol === col ? (sortAsc ? ' ↑' : ' ↓') : ''
  const totalPages = Math.max(1, Math.ceil(total / LIMIT))

  async function confirmDelete() {
    if (!toDelete) return
    const target = toDelete
    setToDelete(null)
    try { await deleteCurrency(target.id); notify('ok', t('title'), t('deleted')); setTick((x) => x + 1) }
    catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error') }
  }

  async function handleSetDefault(c: CurrencyItem) {
    try { await setDefaultCurrency(c.id); notify('ok', t('title'), t('saved')); setTick((x) => x + 1) }
    catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error') }
  }


  // Réinitialiser les filtres : recherche + statut + tri par défaut (id asc), retour page 1, puis refetch.
  // On vide `items` : sinon les lignes restent affichées pendant le refetch et le clic paraît sans effet.
  function resetFilters() {
    setSearchInput(''); setSearch('')
    setFilterStatus(null)
    setSortCol('id'); setSortAsc(true)
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
        <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
          <div style={{ display: 'flex', gap: 8, alignItems: 'center', justifyContent: 'flex-end' }}>
            <ViewModeToggle mode={mode} onReact={() => setMode('react')} onOld={() => { setMode('old'); setOldLoaded(true) }} />
            <button style={{ ...btnGhost, width: 36, padding: 0, justifyContent: 'center', flexShrink: 0 }} title={t('refresh')} onClick={() => setTick((x) => x + 1)}><RefreshIcon /></button>
          </div>
          {can('create') && <button style={{ ...btnPrimary, width: '100%', justifyContent: 'center', whiteSpace: 'normal', textAlign: 'center', height: 'auto', minHeight: 36, padding: '8px 14px' }} onClick={() => setFormId('new')}><PlusIcon />{t('new')}</button>}
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 20, flex: 1, minHeight: 0 }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(130px, 1fr))', gap: 12 }}>
          <Kpi icon={<CoinsKpiIcon />} label={t('kpi_total')} value={stats?.total ?? null} iconBg="rgba(59,130,246,0.1)" iconColor="#3b82f6" />
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
              {sorted.map((c) => (
                <Fragment key={c.id}>
                  <tr style={{ cursor: can('edit') ? 'pointer' : 'default' }}
                    onClick={() => can('edit') && setFormId(c.id)}
                    onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                    onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                    {hasHidden && (
                      <td style={td} onClick={(e) => e.stopPropagation()}>
                        <ExpandToggle expanded={expanded.has(c.id)} onClick={() => toggleExpand(c.id)} />
                      </td>
                    )}
                    {visible.map((col) => (
                      <td key={col.id} style={cellStyle(col.id)} onClick={col.id === 'default' ? (e) => e.stopPropagation() : undefined}>{cellContent(c, col.id)}</td>
                    ))}
                    <td style={{ ...td, textAlign: 'center', width: 90, position: 'sticky', right: 0, background: 'var(--color-card,#fff)' }} onClick={(e) => e.stopPropagation()}>
                      <div style={{ display: 'inline-flex', gap: 6 }}>
                        {can('edit') && <button onClick={(e) => { e.stopPropagation(); setFormId(c.id) }}
                          style={iconBtn} title={t('edit')}><PencilIcon /></button>}
                        {can('delete') && <button onClick={(e) => { e.stopPropagation(); if (!c.isDefault) setToDelete(c) }}
                          disabled={c.isDefault}
                          style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)', cursor: c.isDefault ? 'not-allowed' : 'pointer', opacity: c.isDefault ? 0.35 : 1 }}
                          title={c.isDefault ? t('err_default_delete') : t('del')}><TrashIcon /></button>}
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
        <ExportModal cols={cols} items={sorted} getCell={(c, id) => getCellExport(c, id, t)}
          labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')}
          sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />
      )}

      {toDelete && (
        <ConfirmModal title={t('del_title')} message={t('del_confirm')}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
      )}

      {formId !== null && (
        <CurrencyFormModal id={formId === 'new' ? null : formId}
          onClose={() => setFormId(null)}
          onSaved={() => { setFormId(null); setTick((x) => x + 1) }}
          t={t} />
      )}
    </div>
  )
}

// ── FORMULAIRE (modale) ──────────────────────────────────────────────────────────
function CurrencyFormModal({ id, onClose, onSaved, t }: {
  id: number | null; onClose: () => void; onSaved: () => void; t: T
}) {
  const narrow = useIsNarrow()
  const isEdit = id !== null
  const [loading, setLoading] = useState(isEdit)
  const [saving, setSaving] = useState(false)
  const [name, setName] = useState('')
  const [code, setCode] = useState('')
  const [symbol, setSymbol] = useState('')
  const [status, setStatus] = useState(true)
  const [errName, setErrName] = useState(false)
  const [errCode, setErrCode] = useState(false)
  const [errSymbol, setErrSymbol] = useState(false)
  const [formError, setFormError] = useState(false)

  useEffect(() => {
    if (!isEdit || id === null) return
    fetchCurrencyById(id).then((c) => {
      setName(c.name); setCode(c.code); setSymbol(c.symbol); setStatus(c.status === 1)
    }).catch((e) => { notify('ko', t('title'), e instanceof Error ? e.message : 'Error'); onClose() })
      .finally(() => setLoading(false))
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [id])

  async function submit() {
    if (!name.trim())   { setErrName(true);   setFormError(true); return }
    if (!code.trim())   { setErrCode(true);   setFormError(true); return }
    if (!symbol.trim()) { setErrSymbol(true); setFormError(true); return }
    setFormError(false)
    setSaving(true)
    try {
      await saveCurrency({ id: id ?? undefined, name: name.trim(), code: code.trim().toUpperCase(), symbol: symbol.trim(), status })
      notify('ok', t('title'), t('saved'))
      onSaved()
    } catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error') }
    finally { setSaving(false) }
  }

  return (
    <div onClick={(e) => { if (e.target === e.currentTarget) onClose() }}
      style={{ position: 'fixed', inset: 0, zIndex: 50, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
      <div style={{ ...card, width: '100%', maxWidth: 440 }}>
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '16px 20px', borderBottom: '1px solid var(--color-border)' }}>
          <h3 style={{ fontSize: 16, fontWeight: 600, margin: 0 }}>{isEdit ? t('edit') : t('new')}</h3>
          <button style={{ ...iconBtn, width: 22, height: 22 }} onClick={onClose}>✕</button>
        </div>

        {loading ? (
          <div style={{ padding: 40, textAlign: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
        ) : (
          <div style={{ padding: 20, display: 'flex', flexDirection: 'column', gap: 16 }}>
            {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14 }}>{t('err_required_fields')}</div>}
            <div>
              <label style={label}>{t('field_name')} *</label>
              <input style={inputCss} value={name}
                onChange={(e) => { setName(e.target.value); setErrName(false) }} maxLength={45} />
              {errName && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_name_required')}</p>}
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 16 }}>
              <div>
                <label style={label}>{t('field_code')} *</label>
                <input style={{ ...inputCss, textTransform: 'uppercase' }} value={code}
                  onChange={(e) => { setCode(e.target.value); setErrCode(false) }} maxLength={8} />
                {errCode && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_code_required')}</p>}
              </div>
              <div>
                <label style={label}>{t('field_symbol')} *</label>
                <input style={inputCss} value={symbol}
                  onChange={(e) => { setSymbol(e.target.value); setErrSymbol(false) }} maxLength={5} />
                {errSymbol && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_symbol_required')}</p>}
              </div>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
              <span style={{ fontSize: 13, fontWeight: 500 }}>{t('status_label')}</span>
              <StatusToggle value={status} onChange={setStatus} t={t} />
            </div>
          </div>
        )}

        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, padding: '12px 16px', borderTop: '1px solid var(--color-border)' }}>
          <button style={btnGhost} onClick={onClose} disabled={saving}>{t('cancel')}</button>
          <button style={btnPrimary} onClick={submit} disabled={saving || loading}>{saving ? '…' : t('save')}</button>
        </div>
      </div>
    </div>
  )
}
