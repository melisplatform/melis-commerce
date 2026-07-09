import { useEffect, useMemo, useRef, useState } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  fetchCountries, fetchCountryStats, fetchCountryOptions, fetchCountryById, saveCountry, deleteCountry,
  type CountryItem, type CountryStats, type CountryOptions,
} from './api'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT } from '../../shared/i18n'
import { card, inputCss, label, btnGhost, btnPrimary, th, td } from '../../shared/styles'
import { GlobeIcon, CheckIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon, ListIcon } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { makeColStore, visibleCols, type ColDef } from '../../shared/columns'
import { ExportModal } from '../../shared/ExportModal'
import { ColManager } from '../../shared/ColManager'
import { openSubTab, updateSubLabel } from '../../shared/subtabs'
import { useCaps } from '../../shared/useCaps'

const TOOL_MELIS_KEY = 'meliscommerce_country_list_container'

const COL_ORDER = ['id', 'flag', 'status', 'name', 'currency'] as const
const COL_LABEL: Record<string, string> = {
  id: 'col_id', flag: 'col_flag', status: 'col_status', name: 'col_name', currency: 'col_currency',
}
const cols$ = makeColStore('melis-country-cols-v1', COL_ORDER)

const listCache = makeCache<{
  items: CountryItem[]; stats: CountryStats | null; total: number; page: number
  search: string; searchInput: string; filterStatus: number | null; sortCol: string; sortAsc: boolean; mode: 'react' | 'old'
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
      display: 'inline-flex', alignItems: 'center', gap: 5, padding: '2px 8px',
      borderRadius: 20, fontSize: 11, fontWeight: 600, color: '#fff',
      background: active ? '#16a34a' : '#6b7280', whiteSpace: 'nowrap',
    }}>
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
  const [mode, setMode] = useState<'react' | 'old'>(listCache.get()?.mode ?? 'react')
  const [oldLoaded, setOldLoaded] = useState(false)
  const [items, setItems] = useState<CountryItem[]>(listCache.get()?.items ?? [])
  const [stats, setStats] = useState<CountryStats | null>(listCache.get()?.stats ?? null)
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
  const [toDelete, setToDelete] = useState<CountryItem | null>(null)

  const cacheRef = useRef({ items, stats, total, page, search, searchInput, filterStatus, sortCol, sortAsc, mode })
  useEffect(() => { cacheRef.current = { items, stats, total, page, search, searchInput, filterStatus, sortCol, sortAsc, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchCountryStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => {
    setLoading(true)
    fetchCountries({ search, status: filterStatus, page, limit: LIMIT })
      .then((r) => { setItems(r.items); setTotal(r.total) })
      .catch(() => null)
      .finally(() => setLoading(false))
  }, [search, filterStatus, page, tick])

  const visible = visibleCols(cols)
  const sorted = useMemo(() => {
    const arr = [...items]
    arr.sort((a, b) => {
      let va: string | number = '', vb: string | number = ''
      if (sortCol === 'id')       { va = a.id; vb = b.id }
      if (sortCol === 'flag')     { va = a.flag ? 1 : 0; vb = b.flag ? 1 : 0 }
      if (sortCol === 'status')   { va = a.status; vb = b.status }
      if (sortCol === 'name')     { va = a.name; vb = b.name }
      if (sortCol === 'currency') { va = a.currencyName; vb = b.currencyName }
      if (va < vb) return sortAsc ? -1 : 1
      if (va > vb) return sortAsc ? 1 : -1
      return 0
    })
    return arr
  }, [items, sortCol, sortAsc])

  const onSort = (col: string) => { if (sortCol === col) setSortAsc((p) => !p); else { setSortCol(col); setSortAsc(true) } }
  const arrow  = (col: string) => sortCol === col ? (sortAsc ? ' ↑' : ' ↓') : ''
  const totalPages = Math.max(1, Math.ceil(total / LIMIT))

  const openCountry = (c: CountryItem) => {
    const path = `${base}/${c.id}`
    navigate(path)
    openSubTab(base, { id: path, label: c.name, path })
  }

  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteCountry(toDelete.id); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) }
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
          {can('create') && <button style={btnPrimary} onClick={() => navigate(`${base}/new`)}><PlusIcon />{t('new')}</button>}
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
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 700 }}>
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
              {sorted.map((c) => (
                <tr key={c.id} style={{ cursor: 'pointer' }}
                  onClick={() => openCountry(c)}
                  onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                  onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                  {visible.map((col) => {
                    if (col.id === 'id')       return <td key={col.id} style={{ ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' }}>{c.id}</td>
                    if (col.id === 'flag')     return <td key={col.id} style={td}><FlagThumb src={flagDataUrl(c.flag)} /></td>
                    if (col.id === 'status')   return <td key={col.id} style={td}><StatusPill active={c.status === 1} t={t} /></td>
                    if (col.id === 'name')     return <td key={col.id} style={{ ...td, fontWeight: 600 }}>{c.name}</td>
                    if (col.id === 'currency') return <td key={col.id} style={{ ...td, color: 'var(--color-muted-foreground)' }}>{c.currencyName}{c.currencySymbol ? ` (${c.currencySymbol})` : ''}</td>
                    return <td key={col.id} style={td}>—</td>
                  })}
                  <td style={{ ...td, textAlign: 'center', width: 90 }} onClick={(e) => e.stopPropagation()}>
                    <div style={{ display: 'inline-flex', gap: 6 }}>
                      {can('edit') && <button onClick={(e) => { e.stopPropagation(); openCountry(c) }}
                        style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 28, height: 28, borderRadius: 6, border: '1px solid #5cb85c', background: 'transparent', color: '#5cb85c', cursor: 'pointer', padding: 0 }}
                        title={t('edit')}><PencilIcon /></button>}
                      {can('delete') && <button onClick={(e) => { e.stopPropagation(); setToDelete(c) }}
                        style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 28, height: 28, borderRadius: 6, border: '1px solid #dc2626', background: 'transparent', color: '#dc2626', cursor: 'pointer', padding: 0 }}
                        title={t('del')}><TrashIcon /></button>}
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
        <ExportModal cols={cols} items={sorted} getCell={(c, id) => getCellExport(c, id)}
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
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><GlobeIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>
            {isEdit ? (country ? country.name : t('loading')) : t('new')}
          </h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <button style={btnPrimary} onClick={submit} disabled={saving || (isEdit && !country) || !can(isEdit ? 'edit' : 'create')}>
            {saving ? '…' : t('save')}
          </button>
        </div>
      </div>

      {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14 }}>{t('err_required_fields')}</div>}

      {isEdit && !country ? (
        <div style={{ padding: 40, display: 'flex', justifyContent: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 260px', gap: 16, alignItems: 'start' }}>
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
