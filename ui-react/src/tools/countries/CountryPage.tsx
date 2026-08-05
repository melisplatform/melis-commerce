import { Fragment, useEffect, useRef, useState, type ReactNode } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  fetchCountries, fetchAllCountries, fetchCountryStats, fetchCountryOptions, fetchCountryById, saveCountry, deleteCountry,
  type CountryItem, type CountryStats, type CountrySortKey, type CountryOptions,
} from './api'
import { useKeysetList } from '../../shared/use-keyset-list'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT } from '../../shared/i18n'
import { card, inputCss, label, btnGhost, btnPrimary, th, td, iconBtn } from '../../shared/styles'
import { GlobeIcon, CheckIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon, ListIcon, ResetIcon, SortIcon, Spinner } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { makeColStore, visibleCols, effectiveCols, type ColDef } from '../../shared/columns'
import { ExpandToggle, HiddenColsRow } from '../../shared/ExpandableRow'
import { useIsNarrow } from '../../shared/useIsNarrow'
import { ExportModal } from '../../shared/ExportModal'
import { ColManager } from '../../shared/ColManager'
import { openSubTab, updateSubLabel } from '../../shared/subtabs'
import { useCaps } from '../../shared/useCaps'

const TOOL_MELIS_KEY = 'meliscommerce_country_list_container'

const COL_ORDER = ['id', 'flag', 'status', 'name', 'currency'] as const
const COL_LABEL: Record<string, string> = {
  id: 'col_id', flag: 'col_flag', status: 'col_status', name: 'col_name', currency: 'col_currency',
}
const cols$ = makeColStore('melis-country-cols-v2', COL_ORDER)
const ESSENTIAL_COLS = new Set(['name'])

const SORTABLE = new Set<CountrySortKey>(['id', 'status', 'name', 'currency'])

const listCache = makeCache<{
  items: CountryItem[]; stats: CountryStats | null; total: number; cursor: string | null; hasMore: boolean
  search: string; searchInput: string; filterStatus: number | null; sortCol: string; sortDir: 'asc' | 'desc'; mode: 'react' | 'old'
}>()

const fieldSectionTitle = { display: 'flex', alignItems: 'center', gap: 8, fontSize: 15, fontWeight: 500, color: 'var(--color-muted-foreground)', margin: '0 0 20px' } as const
const fieldGap = { display: 'flex', flexDirection: 'column', gap: 16 } as const

/** Convertit un fichier en base64 brut (sans le préfixe `data:...;base64,`). */
function fileToBase64(file: File): Promise<string> {
  return new Promise((resolve, reject) => {
    const reader = new FileReader()
    reader.onload = () => {
      const result = String(reader.result ?? '')
      const comma = result.indexOf(',')
      resolve(comma >= 0 ? result.slice(comma + 1) : result)
    }
    reader.onerror = () => reject(reader.error)
    reader.readAsDataURL(file)
  })
}

function flagDataUrl(b64: string | null | undefined): string | null {
  return b64 ? `data:image/jpeg;base64,${b64}` : null
}

function FlagThumb({ src, size = 24 }: { src: string | null; size?: number }) {
  if (src) return <img src={src} alt="" style={{ width: size, height: size, borderRadius: 4, objectFit: 'cover', border: '1px solid var(--color-border)' }} />
  return <span style={{ display: 'inline-flex', color: 'var(--color-muted-foreground)' }}><GlobeIcon /></span>
}

// ── Toggle switch statut (formulaire) ────────────────────────────────────────
function StatusToggle({ active, onClick, t }: { active: boolean; onClick: () => void; t: (k: string) => string }) {
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

// ── Badge statut (cellule de tableau) ────────────────────────────────────────
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

function getCellExport(c: CountryItem, id: string): string | number {
  switch (id) {
    case 'id':       return c.id
    case 'flag':     return c.flag ? 'Yes' : 'No'
    case 'status':   return c.status === 1 ? 'Active' : 'Inactive'
    case 'name':     return c.name
    case 'currency': return c.currencySymbol ? `${c.currencyName} (${c.currencySymbol})` : c.currencyName
    default:         return ''
  }
}

export default function CountryPage() {
  const { id } = useParams()
  const location = useLocation()
  const base = id ? location.pathname.slice(0, location.pathname.length - id.length - 1) : location.pathname
  if (id) return <CountryForm key={id} id={id} base={base} />
  return <CountryList base={base} />
}

// ── COUNTRY LIST ─────────────────────────────────────────────────────────────────
function CountryList({ base }: { base: string }) {
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
  const [stats, setStats] = useState<CountryStats | null>(cached?.stats ?? null)
  const [searchInput, setSearchInput] = useState(cached?.searchInput ?? '')
  const [search, setSearch] = useState(cached?.search ?? '')
  const [filterStatus, setFilterStatus] = useState<number | null>(cached?.filterStatus ?? null)
  const [exportItems, setExportItems] = useState<CountryItem[]>([])
  const [exporting, setExporting] = useState(false)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [toDelete, setToDelete] = useState<CountryItem | null>(null)
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }

  const {
    items, setItems, total, loading, hasMore, sentinelRef, sortCol, sortDir, setSortCol, setSortDir, toggleSort, snapshot,
  } = useKeysetList<CountryItem>({
    fetcher: (a) => fetchCountries({ ...a, sort: a.sort as CountrySortKey, search, status: filterStatus }),
    deps: [search, filterStatus, tick],
    defaultSort: 'id',
    defaultDir: 'desc',
    initial: cached ? { items: cached.items, total: cached.total, cursor: cached.cursor, hasMore: cached.hasMore, sortCol: cached.sortCol, sortDir: cached.sortDir } : undefined,
    skipInitial: !!(cached && cached.items.length),
  })

  const cacheRef = useRef({ ...snapshot(), stats, search, searchInput, filterStatus, mode })
  useEffect(() => { cacheRef.current = { ...snapshot(), stats, search, searchInput, filterStatus, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchCountryStats().then(setStats).catch(() => null) }, [tick])

  const eCols = effectiveCols(cols, ESSENTIAL_COLS, narrow)
  const visible = visibleCols(eCols)
  const hasHidden = eCols.some((c) => !c.visible)
  const totalCols = visible.length + 1 + (hasHidden ? 1 : 0)
  function cellStyle(id: string) {
    if (id === 'id') return { ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' as const }
    if (id === 'name') return { ...td, fontWeight: 600 }
    if (id === 'currency') return { ...td, color: 'var(--color-muted-foreground)' }
    return td
  }
  function cellContent(c: CountryItem, id: string): ReactNode {
    switch (id) {
      case 'id': return c.id
      case 'flag': return <FlagThumb src={flagDataUrl(c.flag)} />
      case 'status': return <StatusPill active={c.status === 1} t={t} />
      case 'name': return c.name
      case 'currency': return `${c.currencyName}${c.currencySymbol ? ` (${c.currencySymbol})` : ''}`
      default: return '—'
    }
  }
  // Export : le keyset ne charge qu'une page → on récupère TOUT le jeu filtré via curseur.
  async function openExport() {
    setExporting(true)
    try { const all = await fetchAllCountries({ search, status: filterStatus }); setExportItems(all); setShowExport(true) }
    catch { /* ignore */ } finally { setExporting(false) }
  }

  const openCountry = (c: CountryItem) => {
    const path = `${base}/${c.id}`
    navigate(path)
    openSubTab(base, { id: path, label: c.name, path })
  }

  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteCountry(toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) }
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
          <Kpi icon={<GlobeIcon />} label={t('kpi_total')} value={stats?.total ?? null} iconBg="rgba(59,130,246,0.1)" iconColor="#3b82f6" />
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
                  const sortable = SORTABLE.has(c.id as CountrySortKey)
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
              {items.map((c) => (
                <Fragment key={c.id}>
                  <tr style={{ cursor: 'pointer' }}
                    onClick={() => openCountry(c)}
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
                        {can('edit') && <button onClick={(e) => { e.stopPropagation(); openCountry(c) }}
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
        <ConfirmModal title={t('del_title')} message={t('del_confirm')}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
      )}
    </div>
  )
}

// ── COUNTRY FORM ─────────────────────────────────────────────────────────────────
function CountryForm({ id, base }: { id: string; base: string }) {
  const t = makeT(DICT)
  const narrow = useIsNarrow()
  const navigate = useNavigate()
  const isEdit = id !== 'new'
  const countryId = isEdit ? Number(id) : null
  const subTabPath = `${base}/${id}`
  const { can } = useCaps(TOOL_MELIS_KEY)

  const [country, setCountry] = useState<CountryItem | null>(null)
  const [options, setOptions] = useState<CountryOptions | null>(null)
  const [saving, setSaving] = useState(false)

  const [name, setName] = useState('')
  const [currencyId, setCurrencyId] = useState(0)
  const [status, setStatus] = useState(true)
  const [existingFlag, setExistingFlag] = useState<string | null>(null)
  const [newFlagB64, setNewFlagB64] = useState<string | null>(null)
  const [newFlagPreview, setNewFlagPreview] = useState<string | null>(null)
  const [errName, setErrName] = useState(false)
  const [errCurrency, setErrCurrency] = useState(false)
  const [formError, setFormError] = useState(false)

  useEffect(() => {
    openSubTab(base, { id: subTabPath, label: isEdit ? t('loading') : t('new'), path: subTabPath })
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  useEffect(() => {
    fetchCountryOptions().then((o) => {
      setOptions(o)
      setCurrencyId((v) => v || o.currencies[0]?.id || 0)
    }).catch(() => null)
  }, [])

  useEffect(() => {
    if (!countryId) return
    fetchCountryById(countryId).then((c) => {
      setCountry(c)
      setName(c.name); setCurrencyId(c.currencyId); setStatus(c.status === 1)
      setExistingFlag(c.flag)
      updateSubLabel(base, subTabPath, c.name)
    }).catch(() => null)
  }, [countryId])

  async function onPickFlag(file: File | null) {
    if (!file) return
    const b64 = await fileToBase64(file)
    setNewFlagB64(b64)
    setNewFlagPreview(URL.createObjectURL(file))
  }

  async function submit() {
    const trimmed = name.trim()
    if (!trimmed) { setErrName(true); setFormError(true); return }
    if (!currencyId) { setErrCurrency(true); setFormError(true); return }
    setFormError(false)
    setSaving(true)
    try {
      const payload = {
        name: trimmed, currencyId, status,
        ...(newFlagB64 ? { flag: newFlagB64 } : {}),
      }
      if (isEdit && countryId) {
        await saveCountry({ ...payload, id: countryId })
        updateSubLabel(base, subTabPath, trimmed)
        notify('ok', t('title'), t('saved'))
      } else {
        const r = await saveCountry(payload)
        notify('ok', t('title'), t('saved'))
        navigate(`${base}/${r.id}`)
      }
    } catch (e) {
      const msg = e instanceof Error ? e.message : 'Error'
      notify('ko', t('title'), msg === 'Country name already exists' ? t('err_name_exists') : msg)
    } finally { setSaving(false) }
  }

  const flagPreviewSrc = newFlagPreview ?? flagDataUrl(existingFlag)

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box' }}>
      <div style={{ display: 'flex', alignItems: narrow ? 'flex-start' : 'center', justifyContent: 'space-between', gap: 16, flexWrap: narrow ? 'wrap' : 'nowrap' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><GlobeIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>
            {isEdit ? (country ? country.name : t('loading')) : t('new')}
          </h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexBasis: narrow ? '100%' : undefined }}>
          <button style={{ ...btnPrimary, flex: narrow ? 1 : undefined, justifyContent: narrow ? 'center' : undefined }} onClick={submit} disabled={saving || (isEdit && !country) || !can(isEdit ? 'edit' : 'create')}>
            {saving ? '…' : t('save')}
          </button>
        </div>
      </div>

      {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14 }}>{t('err_required_fields')}</div>}

      {isEdit && !country ? (
        <div style={{ padding: 40, display: 'flex', justifyContent: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr 260px', gap: 16, alignItems: 'start' }}>
          <div style={{ ...card, padding: 24 }}>
            <h3 style={fieldSectionTitle}><GlobeIcon />{t('section_general_data')}</h3>
            <div style={fieldGap}>
              <div>
                <label style={label}>{t('field_name')} *</label>
                <input style={inputCss} maxLength={45} value={name}
                  onChange={(e) => { setName(e.target.value); setErrName(false) }} />
                {errName && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_name_required')}</p>}
              </div>
              <div>
                <label style={label}>{t('field_currency')} *</label>
                <select style={inputCss} value={currencyId}
                  onChange={(e) => { setCurrencyId(Number(e.target.value)); setErrCurrency(false) }}>
                  <option value={0} disabled>—</option>
                  {(options?.currencies ?? []).map((cu) => (
                    <option key={cu.id} value={cu.id}>{cu.name}{cu.symbol ? ` (${cu.symbol})` : ''}</option>
                  ))}
                </select>
                {errCurrency && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_currency_required')}</p>}
              </div>
            </div>
          </div>

          <div style={{ ...card, padding: 24 }}>
            <h3 style={fieldSectionTitle}>{t('field_flag')}</h3>
            <div style={fieldGap}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
                <div style={{ width: 64, height: 64, borderRadius: 8, border: '1px solid var(--color-border)', display: 'grid', placeItems: 'center', overflow: 'hidden', flexShrink: 0 }}>
                  {flagPreviewSrc
                    ? <img src={flagPreviewSrc} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    : <span style={{ color: 'var(--color-muted-foreground)' }}><GlobeIcon /></span>}
                </div>
                <label style={{ ...btnGhost, cursor: 'pointer' }}>
                  {t('flag_choose')}
                  <input type="file" accept="image/*" style={{ display: 'none' }}
                    onChange={(e) => onPickFlag(e.target.files?.[0] ?? null)} />
                </label>
              </div>
              {!flagPreviewSrc && <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>{t('flag_none')}</span>}
            </div>
          </div>

          <div style={{ ...card, padding: 24 }}>
            <h3 style={fieldSectionTitle}>{t('status_label')}</h3>
            <StatusToggle active={status} onClick={() => setStatus((s) => !s)} t={t} />
          </div>
        </div>
      )}
    </div>
  )
}
