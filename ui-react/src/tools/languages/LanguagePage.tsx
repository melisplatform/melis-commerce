import { Fragment, useEffect, useRef, useState, type ReactNode } from 'react'
import {
  fetchLanguages, fetchAllLanguages, fetchLanguageStats, saveLanguage, deleteLanguage,
  type LanguageItem, type LanguageStats, type LanguageSortKey,
} from './api'
import { useKeysetList } from '../../shared/use-keyset-list'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT, type T } from '../../shared/i18n'
import { card, inputCss, label, btnGhost, btnPrimary, th, td, iconBtn } from '../../shared/styles'
import { CheckIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon, GlobeIcon, ListIcon, ResetIcon, SortIcon, Spinner } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { makeColStore, visibleCols, effectiveCols, type ColDef } from '../../shared/columns'
import { ExpandToggle, HiddenColsRow } from '../../shared/ExpandableRow'
import { useIsNarrow } from '../../shared/useIsNarrow'
import { ExportModal } from '../../shared/ExportModal'
import { ColManager } from '../../shared/ColManager'
import { useCaps } from '../../shared/useCaps'

const TOOL_MELIS_KEY = 'meliscommerce_language_list_container'

const COL_ORDER = ['id', 'flag', 'status', 'locale', 'name'] as const
const COL_LABEL: Record<string, string> = {
  id: 'col_id', flag: 'col_flag', status: 'col_status', locale: 'col_locale', name: 'col_name',
}
const cols$ = makeColStore('melis-language-cols-v1', COL_ORDER)
const ESSENTIAL_COLS = new Set(['name'])

const SORTABLE = new Set<LanguageSortKey>(['id', 'status', 'locale', 'name'])

const listCache = makeCache<{
  items: LanguageItem[]; stats: LanguageStats | null; total: number; cursor: string | null; hasMore: boolean
  search: string; searchInput: string; filterStatus: number | null; sortCol: string; sortDir: 'asc' | 'desc'; mode: 'react' | 'old'
}>()

const fieldGap = { display: 'flex', flexDirection: 'column', gap: 16 } as const
const labelRow = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 } as const

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

function StatusPill({ active, t }: { active: boolean; t: T }) {
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

function FlagThumb({ flag }: { flag: string | null }) {
  if (flag) return <img src={`data:image/png;base64,${flag}`} alt="" style={{ width: 22, height: 22, borderRadius: 3, objectFit: 'cover' }} />
  return <span style={{ color: 'var(--color-muted-foreground)', display: 'inline-flex' }}><GlobeIcon /></span>
}

function getCellExport(l: LanguageItem, id: string): string | number {
  switch (id) {
    case 'id':     return l.id
    case 'flag':   return l.flag ? 'Yes' : 'No'
    case 'status': return l.status === 1 ? 'Active' : 'Inactive'
    case 'locale': return l.locale
    case 'name':   return l.name
    default:       return ''
  }
}

export default function LanguagePage() {
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
  const cached = listCache.get()
  const [stats, setStats] = useState<LanguageStats | null>(cached?.stats ?? null)
  const [searchInput, setSearchInput] = useState(cached?.searchInput ?? '')
  const [search, setSearch] = useState(cached?.search ?? '')
  const [filterStatus, setFilterStatus] = useState<number | null>(cached?.filterStatus ?? null)
  const [exportItems, setExportItems] = useState<LanguageItem[]>([])
  const [exporting, setExporting] = useState(false)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [toDelete, setToDelete] = useState<LanguageItem | null>(null)
  const [editing, setEditing] = useState<LanguageItem | 'new' | null>(null)
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }

  const {
    items, setItems, total, loading, hasMore, sentinelRef, sortCol, sortDir, setSortCol, setSortDir, toggleSort, snapshot,
  } = useKeysetList<LanguageItem>({
    fetcher: (a) => fetchLanguages({ ...a, sort: a.sort as LanguageSortKey, search, status: filterStatus }),
    deps: [search, filterStatus, tick],
    defaultSort: 'id',
    defaultDir: 'desc',
    initial: cached ? { items: cached.items, total: cached.total, cursor: cached.cursor, hasMore: cached.hasMore, sortCol: cached.sortCol, sortDir: cached.sortDir } : undefined,
    skipInitial: !!(cached && cached.items.length),
  })

  const cacheRef = useRef({ ...snapshot(), stats, search, searchInput, filterStatus, mode })
  useEffect(() => { cacheRef.current = { ...snapshot(), stats, search, searchInput, filterStatus, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchLanguageStats().then(setStats).catch(() => null) }, [tick])

  const eCols = effectiveCols(cols, ESSENTIAL_COLS, narrow)
  const visible = visibleCols(eCols)
  const hasHidden = eCols.some((c) => !c.visible)
  const totalCols = visible.length + 1 + (hasHidden ? 1 : 0)
  function cellStyle(id: string) {
    if (id === 'id') return { ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' as const }
    if (id === 'name') return { ...td, fontWeight: 600 }
    return td
  }
  function cellContent(l: LanguageItem, id: string): ReactNode {
    switch (id) {
      case 'id': return l.id
      case 'flag': return <FlagThumb flag={l.flag} />
      case 'status': return <StatusPill active={l.status === 1} t={t} />
      case 'locale': return <code style={{ fontSize: 12 }}>{l.locale}</code>
      case 'name': return l.name
      default: return '—'
    }
  }
  // Export : le keyset ne charge qu'une page → on récupère TOUT le jeu filtré via curseur.
  async function openExport() {
    setExporting(true)
    try { const all = await fetchAllLanguages({ search, status: filterStatus }); setExportItems(all); setShowExport(true) }
    catch { /* ignore */ } finally { setExporting(false) }
  }

  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteLanguage(toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) }
    catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error'); setToDelete(null) }
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
          {can('create') && <button style={btnPrimary} onClick={() => setEditing('new')}><PlusIcon />{t('new')}</button>}
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
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: narrow ? undefined : 600 }}>
            <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
              <tr>
                {hasHidden && <th style={{ ...th, width: 32 }} />}
                {visible.map((c) => {
                  const sortable = SORTABLE.has(c.id as LanguageSortKey)
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
              {items.map((l) => (
                <Fragment key={l.id}>
                  <tr style={{ cursor: 'pointer' }}
                    onClick={() => setEditing(l)}
                    onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                    onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                    {hasHidden && (
                      <td style={td} onClick={(e) => e.stopPropagation()}>
                        <ExpandToggle expanded={expanded.has(l.id)} onClick={() => toggleExpand(l.id)} />
                      </td>
                    )}
                    {visible.map((col) => (
                      <td key={col.id} style={cellStyle(col.id)}>{cellContent(l, col.id)}</td>
                    ))}
                    <td style={{ ...td, textAlign: 'center', width: 90, position: 'sticky', right: 0, background: 'var(--color-card,#fff)' }} onClick={(e) => e.stopPropagation()}>
                      <div style={{ display: 'inline-flex', gap: 6 }}>
                        {can('edit') && <button onClick={(e) => { e.stopPropagation(); setEditing(l) }}
                          style={iconBtn} title={t('edit')}><PencilIcon /></button>}
                        {can('delete') && <button onClick={(e) => { e.stopPropagation(); setToDelete(l) }}
                          style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')}><TrashIcon /></button>}
                      </div>
                    </td>
                  </tr>
                  {expanded.has(l.id) && (
                    <HiddenColsRow cols={eCols} labelFor={(id) => t(COL_LABEL[id])} renderValue={(id) => cellContent(l, id)} colSpan={totalCols} narrow={narrow} />
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
        <ExportModal cols={cols} items={exportItems} getCell={(l, id) => getCellExport(l, id)}
          labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')}
          sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />
      )}

      {toDelete && (
        <ConfirmModal title={t('del_title')} message={t('del_confirm')}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
      )}

      {editing && (
        <LanguageEditor language={editing === 'new' ? null : editing} t={t}
          onClose={() => setEditing(null)}
          onSaved={() => { setEditing(null); setTick((x) => x + 1) }} />
      )}
    </div>
  )
}

// ── Éditeur de langue (modale) — nom, locale, statut, drapeau ──────────────────
function LanguageEditor({ language, t, onClose, onSaved }: {
  language: LanguageItem | null; t: T
  onClose: () => void; onSaved: () => void
}) {
  const isEdit = !!language
  const [name, setName] = useState(language?.name ?? '')
  const [locale, setLocale] = useState(language?.locale ?? '')
  const [status, setStatus] = useState(language ? language.status === 1 : true)
  const [newFlag, setNewFlag] = useState<string | null>(null)
  const [saving, setSaving] = useState(false)
  const [errName, setErrName] = useState(false)
  const [errLocale, setErrLocale] = useState(false)
  const [formError, setFormError] = useState(false)

  async function onPickFlag(file: File | null) {
    if (!file) return
    const b64 = await fileToBase64(file)
    setNewFlag(b64)
  }

  async function submit() {
    if (!name.trim()) { setErrName(true); setFormError(true); return }
    if (!locale.trim()) { setErrLocale(true); setFormError(true); return }
    setFormError(false)
    setSaving(true)
    try {
      const payload = { name: name.trim(), locale: locale.trim(), status, flag: newFlag }
      if (isEdit && language) {
        await saveLanguage({ ...payload, id: language.id })
      } else {
        await saveLanguage(payload)
      }
      notify('ok', t('title'), t('saved'))
      onSaved()
    } catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error') }
    finally { setSaving(false) }
  }

  const previewFlag = newFlag ?? language?.flag ?? null

  return (
    <div onClick={(e) => { if (e.target === e.currentTarget) onClose() }}
      style={{ position: 'fixed', inset: 0, zIndex: 60, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)', padding: 20 }}>
      <div style={{ ...card, padding: 24, width: '100%', maxWidth: 440, maxHeight: '85vh', overflow: 'auto' }}>
        <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 16px' }}>{isEdit ? t('edit') : t('new')}</h3>
        <div style={fieldGap}>
          {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14 }}>{t('err_required_fields')}</div>}
          <div>
            <label style={label}>{t('field_name')} *</label>
            <input style={inputCss} maxLength={45} value={name} onChange={(e) => { setName(e.target.value); setErrName(false) }} />
            {errName && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_name_required')}</p>}
          </div>
          <div>
            <label style={label}>{t('field_locale')} *</label>
            <input style={inputCss} maxLength={45} placeholder="en_EN" value={locale} onChange={(e) => { setLocale(e.target.value); setErrLocale(false) }} />
            {errLocale && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_locale_required')}</p>}
          </div>
          <div>
            <div style={labelRow}><label style={label}>{t('status_active')}</label></div>
            <div onClick={() => setStatus((s) => !s)} style={{ display: 'inline-flex', alignItems: 'center', gap: 10, cursor: 'pointer' }}>
              <span style={{ position: 'relative', display: 'inline-block', width: 44, height: 24, borderRadius: 12, background: status ? '#22c55e' : 'var(--color-border)', transition: 'background .15s', flexShrink: 0 }}>
                <span style={{ position: 'absolute', top: 2, left: status ? 22 : 2, width: 20, height: 20, borderRadius: '50%', background: '#fff', boxShadow: '0 1px 3px rgba(0,0,0,.3)', transition: 'left .15s' }} />
              </span>
              <span style={{ fontSize: 13, fontWeight: 600, color: status ? '#22c55e' : 'var(--color-muted-foreground)' }}>
                {status ? t('status_active') : t('status_inactive')}
              </span>
            </div>
          </div>
          <div>
            <label style={label}>{t('field_flag')}</label>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
              {previewFlag
                ? <img src={`data:image/png;base64,${previewFlag}`} alt="" style={{ width: 32, height: 32, borderRadius: 4, objectFit: 'cover', border: '1px solid var(--color-border)' }} />
                : <span style={{ display: 'inline-flex', color: 'var(--color-muted-foreground)' }}><GlobeIcon /></span>}
              <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>
                {previewFlag ? (newFlag ? t('flag_replace') : t('flag_current')) : t('flag_none')}
              </span>
            </div>
            <input type="file" accept="image/*" onChange={(e) => onPickFlag(e.target.files?.[0] ?? null)} style={{ fontSize: 12, marginTop: 8 }} />
          </div>
        </div>
        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 20 }}>
          <button style={btnGhost} onClick={onClose}>{t('cancel')}</button>
          <button style={btnPrimary} onClick={submit} disabled={saving}>{saving ? '…' : t('save')}</button>
        </div>
      </div>
    </div>
  )
}
