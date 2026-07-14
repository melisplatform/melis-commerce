import { useEffect, useMemo, useRef, useState } from 'react'
import {
  fetchLanguages, fetchLanguageStats, saveLanguage, deleteLanguage,
  type LanguageItem, type LanguageStats,
} from './api'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT, type T } from '../../shared/i18n'
import { card, inputCss, label, btnGhost, btnPrimary, th, td, iconBtn } from '../../shared/styles'
import { CheckIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon, GlobeIcon, ListIcon, ResetIcon } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { makeColStore, visibleCols, type ColDef } from '../../shared/columns'
import { ExportModal } from '../../shared/ExportModal'
import { ColManager } from '../../shared/ColManager'
import { useCaps } from '../../shared/useCaps'

const TOOL_MELIS_KEY = 'meliscommerce_language_list_container'

const COL_ORDER = ['id', 'flag', 'status', 'locale', 'name'] as const
const COL_LABEL: Record<string, string> = {
  id: 'col_id', flag: 'col_flag', status: 'col_status', locale: 'col_locale', name: 'col_name',
}
const cols$ = makeColStore('melis-language-cols-v1', COL_ORDER)

const listCache = makeCache<{
  items: LanguageItem[]; stats: LanguageStats | null; total: number; page: number
  search: string; searchInput: string; filterStatus: number | null; sortCol: string; sortAsc: boolean; mode: 'react' | 'old'
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
      display: 'inline-flex', alignItems: 'center', gap: 5, padding: '2px 8px',
      borderRadius: 20, fontSize: 11, fontWeight: 600, color: '#fff',
      background: active ? '#16a34a' : '#6b7280', whiteSpace: 'nowrap',
    }}>
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
  const [mode, setMode] = useState<'react' | 'old'>(listCache.get()?.mode ?? 'react')
  // Non-persistent bricks get fully unmounted when the tab isn't active and rebuilt from
  // scratch when revisited — only `mode` survives that via listCache. If `oldLoaded`
  // reinitialized to false regardless, coming back to a tab left in "Old" view rendered
  // neither the (gated-by-oldLoaded) LegacyFrame nor the (gated-by-mode==='react') React
  // view: a blank tab. Derive the initial value from `mode` so a remount picks the legacy
  // iframe back up immediately, same as `mode` itself does.
  const [oldLoaded, setOldLoaded] = useState(() => (listCache.get()?.mode ?? 'react') === 'old')
  const [items, setItems] = useState<LanguageItem[]>(listCache.get()?.items ?? [])
  const [stats, setStats] = useState<LanguageStats | null>(listCache.get()?.stats ?? null)
  const [loading, setLoading] = useState(false)
  const [total, setTotal] = useState(listCache.get()?.total ?? 0)
  const [page, setPage] = useState(listCache.get()?.page ?? 1)
  const LIMIT = 50
  const [searchInput, setSearchInput] = useState(listCache.get()?.searchInput ?? '')
  const [search, setSearch] = useState(listCache.get()?.search ?? '')
  const [filterStatus, setFilterStatus] = useState<number | null>(listCache.get()?.filterStatus ?? null)
  const [sortCol, setSortCol] = useState(listCache.get()?.sortCol ?? 'id')
  const [sortAsc, setSortAsc] = useState(listCache.get()?.sortAsc ?? false)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [toDelete, setToDelete] = useState<LanguageItem | null>(null)
  const [editing, setEditing] = useState<LanguageItem | 'new' | null>(null)

  const cacheRef = useRef({ items, stats, total, page, search, searchInput, filterStatus, sortCol, sortAsc, mode })
  useEffect(() => { cacheRef.current = { items, stats, total, page, search, searchInput, filterStatus, sortCol, sortAsc, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchLanguageStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => {
    setLoading(true)
    fetchLanguages({ search, status: filterStatus, page, limit: LIMIT })
      .then((r) => { setItems(r.items); setTotal(r.total) })
      .catch(() => null)
      .finally(() => setLoading(false))
  }, [search, filterStatus, page, tick])

  const visible = visibleCols(cols)
  const sorted = useMemo(() => {
    const arr = [...items]
    arr.sort((a, b) => {
      let va: string | number = '', vb: string | number = ''
      if (sortCol === 'id')     { va = a.id; vb = b.id }
      if (sortCol === 'flag')   { va = a.flag ? 1 : 0; vb = b.flag ? 1 : 0 }
      if (sortCol === 'status') { va = a.status; vb = b.status }
      if (sortCol === 'locale') { va = a.locale; vb = b.locale }
      if (sortCol === 'name')   { va = a.name; vb = b.name }
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
    try { await deleteLanguage(toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) }
    catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error'); setToDelete(null) }
  }


  // Réinitialiser les filtres : recherche + statut + tri par défaut (id desc), retour page 1, puis refetch.
  // On vide `items` : sinon les lignes restent affichées pendant le refetch et le clic paraît sans effet.
  function resetFilters() {
    setSearchInput(''); setSearch('')
    setFilterStatus(null)
    setSortCol('id'); setSortAsc(false)
    setItems([])
    setPage(1)
    setTick((x) => x + 1)
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box', ...(mode === 'old' ? { height: '100%', overflow: 'hidden' } : {}) }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('title')}</h1>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('subtitle')}</p>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <ViewModeToggle mode={mode} onReact={() => setMode('react')} onOld={() => { setMode('old'); setOldLoaded(true) }} />
          <button style={{ ...btnGhost, width: 36, padding: 0, justifyContent: 'center' }} title={t('refresh')} onClick={() => setTick((x) => x + 1)}><RefreshIcon /></button>
          {can('create') && <button style={btnPrimary} onClick={() => setEditing('new')}><PlusIcon />{t('new')}</button>}
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 20, flex: 1, minHeight: 0 }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 12 }}>
          <Kpi icon={<GlobeIcon />} label={t('kpi_total')} value={stats?.total ?? null} iconBg="rgba(59,130,246,0.1)" iconColor="#3b82f6" />
          <Kpi icon={<CheckIcon />} label={t('kpi_active')} value={stats?.active ?? null} iconBg="rgba(16,185,129,0.1)" iconColor="#10b981" />
          <Kpi icon={<ListIcon />} label={t('kpi_inactive')} value={stats?.inactive ?? null} iconBg="rgba(107,114,128,0.1)" iconColor="#6b7280" />
        </div>

        <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8 }}>
          <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 180, maxWidth: 360 }}
            placeholder={t('search_placeholder')} value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)}
            onKeyDown={(e) => { if (e.key === 'Enter') { setSearch(searchInput); setPage(1) } }} />
          <select style={{ ...inputCss, height: 36, width: 'auto', minWidth: 160 }}
            value={filterStatus ?? ''} onChange={(e) => { setFilterStatus(e.target.value === '' ? null : Number(e.target.value)); setPage(1) }}>
            <option value="">{t('filter_status')}</option>
            <option value={1}>{t('status_active')}</option>
            <option value={0}>{t('status_inactive')}</option>
          </select>
          <div style={{ marginLeft: 'auto', display: 'flex', gap: 8 }}>
            <button style={{ ...btnGhost, height: 36 }} onClick={resetFilters}><ResetIcon />{t('reset_filters')}</button>
            <div style={{ position: 'relative' }}>
              <button style={{ ...btnGhost, height: 36 }} onClick={() => setShowCols((v) => !v)}><GripIcon />{t('btn_col_manager')}</button>
              {showCols && (
                <ColManager cols={cols} labelFor={(id) => t(COL_LABEL[id])} onChange={setCols}
                  onClose={() => setShowCols(false)} save={cols$.save} defaults={cols$.DEFAULT} t={t} />
              )}
            </div>
            {can('export') && <button style={{ ...btnGhost, height: 36, opacity: sorted.length === 0 ? 0.4 : 1 }} disabled={sorted.length === 0} onClick={() => setShowExport(true)}><FileDownIcon />{t('btn_export')}</button>}
          </div>
        </div>

        <div style={{ ...card, overflow: 'hidden' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 600 }}>
            <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
              <tr>
                {visible.map((c) => (
                  <th key={c.id} style={{ ...th, cursor: 'pointer', userSelect: 'none' }} onClick={() => onSort(c.id)}>
                    {t(COL_LABEL[c.id])}{arrow(c.id)}
                  </th>
                ))}
                <th style={{ ...th, width: 90, textAlign: 'center' }}>{t('col_action')}</th>
              </tr>
            </thead>
            <tbody>
              {loading && sorted.length === 0 && (
                <tr><td colSpan={visible.length + 1} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('loading')}</td></tr>
              )}
              {!loading && sorted.length === 0 && (
                <tr><td colSpan={visible.length + 1} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('no_items')}</td></tr>
              )}
              {sorted.map((l) => (
                <tr key={l.id} style={{ cursor: 'pointer' }}
                  onClick={() => setEditing(l)}
                  onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                  onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                  {visible.map((col) => {
                    if (col.id === 'id')     return <td key={col.id} style={{ ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' }}>{l.id}</td>
                    if (col.id === 'flag')   return <td key={col.id} style={td}><FlagThumb flag={l.flag} /></td>
                    if (col.id === 'status') return <td key={col.id} style={td}><StatusPill active={l.status === 1} t={t} /></td>
                    if (col.id === 'locale') return <td key={col.id} style={td}><code style={{ fontSize: 12 }}>{l.locale}</code></td>
                    if (col.id === 'name')   return <td key={col.id} style={{ ...td, fontWeight: 600 }}>{l.name}</td>
                    return <td key={col.id} style={td}>—</td>
                  })}
                  <td style={{ ...td, textAlign: 'center', width: 90 }} onClick={(e) => e.stopPropagation()}>
                    <div style={{ display: 'inline-flex', gap: 6 }}>
                      {can('edit') && <button onClick={(e) => { e.stopPropagation(); setEditing(l) }}
                        style={iconBtn} title={t('edit')}><PencilIcon /></button>}
                      {can('delete') && <button onClick={(e) => { e.stopPropagation(); setToDelete(l) }}
                        style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')}><TrashIcon /></button>}
                    </div>
                  </td>
                </tr>
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
        <ExportModal cols={cols} items={sorted} getCell={(l, id) => getCellExport(l, id)}
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
