import { useEffect, useMemo, useState } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  fetchOrderStatuses, fetchOrderStatusStats, fetchOrderStatusOptions, fetchOrderStatusById, saveOrderStatus, deleteOrderStatus,
  type OrderStatusItem, type OrderStatusStats, type OrderStatusOptions, type OrderStatusDetail, type OrderStatusTranslation, type LangOption,
} from './api'
import { DICT } from './dict'
import { makeT } from '../../shared/i18n'
import { card, inputCss, label, btnGhost, btnPrimary, th, td } from '../../shared/styles'
import { TagIcon, CheckIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon, GlobeIcon, ListIcon } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { makeColStore, visibleCols, type ColDef } from '../../shared/columns'
import { ExportModal } from '../../shared/ExportModal'
import { ColManager } from '../../shared/ColManager'
import { openSubTab, updateSubLabel } from '../../shared/subtabs'
import { useCaps } from '../../shared/useCaps'
import { Tabs } from '../../shared/Tabs'

const TOOL_MELIS_KEY = 'meliscommerce_order_status_tool_page'

const COL_ORDER = ['id', 'color', 'name', 'status'] as const
const COL_LABEL: Record<string, string> = { id: 'col_id', color: 'col_color', name: 'col_name', status: 'col_status' }
const cols$ = makeColStore('melis-order-status-cols-v1', COL_ORDER)

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
      display: 'inline-flex', alignItems: 'center', gap: 5, padding: '2px 8px',
      borderRadius: 20, fontSize: 11, fontWeight: 600, color: '#fff',
      background: active ? '#16a34a' : '#6b7280', whiteSpace: 'nowrap',
    }}>
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
  const [mode, setMode] = useState<'react' | 'old'>('react')
  const [oldLoaded, setOldLoaded] = useState(false)
  const [items, setItems] = useState<OrderStatusItem[]>([])
  const [stats, setStats] = useState<OrderStatusStats | null>(null)
  const [loading, setLoading] = useState(false)
  const [total, setTotal] = useState(0)
  const [page, setPage] = useState(1)
  const LIMIT = 50
  const [searchInput, setSearchInput] = useState('')
  const [search, setSearch] = useState('')
  const [filterStatus, setFilterStatus] = useState<number | null>(null)
  const [sortCol, setSortCol] = useState('id')
  const [sortAsc, setSortAsc] = useState(true)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [toDelete, setToDelete] = useState<OrderStatusItem | null>(null)

  useEffect(() => { fetchOrderStatusStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => {
    setLoading(true)
    fetchOrderStatuses({ search, status: filterStatus, page, limit: LIMIT })
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
      if (sortCol === 'color')  { va = a.colorCode; vb = b.colorCode }
      if (sortCol === 'name')   { va = a.name; vb = b.name }
      if (sortCol === 'status') { va = a.status; vb = b.status }
      if (va < vb) return sortAsc ? -1 : 1
      if (va > vb) return sortAsc ? 1 : -1
      return 0
    })
    return arr
  }, [items, sortCol, sortAsc])

  const onSort = (col: string) => { if (sortCol === col) setSortAsc((p) => !p); else { setSortCol(col); setSortAsc(true) } }
  const arrow  = (col: string) => sortCol === col ? (sortAsc ? ' ↑' : ' ↓') : ''
  const totalPages = Math.max(1, Math.ceil(total / LIMIT))

  const openStatus = (s: OrderStatusItem) => {
    const path = `${base}/${s.id}`
    navigate(path)
    openSubTab(base, { id: path, label: s.name, path })
  }

  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteOrderStatus(toDelete.id); setToDelete(null); setTick((x) => x + 1) }
    catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : 'Error'); setToDelete(null) }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, height: '100%', boxSizing: 'border-box', overflow: mode === 'old' ? 'hidden' : 'auto' }}>
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
          <Kpi icon={<TagIcon />}  label={t('kpi_total')} value={stats?.total ?? null} iconBg="rgba(59,130,246,0.1)" iconColor="#3b82f6" />
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
              {loading && (
                <tr><td colSpan={visible.length + 1} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('loading')}</td></tr>
              )}
              {!loading && sorted.length === 0 && (
                <tr><td colSpan={visible.length + 1} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('no_items')}</td></tr>
              )}
              {!loading && sorted.map((s) => (
                <tr key={s.id} style={{ cursor: 'pointer' }}
                  onClick={() => openStatus(s)}
                  onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                  onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                  {visible.map((col) => {
                    if (col.id === 'id')     return <td key={col.id} style={{ ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' }}>{s.id}</td>
                    if (col.id === 'color')  return <td key={col.id} style={td}><ColorSwatch color={s.colorCode} /></td>
                    if (col.id === 'name')   return <td key={col.id} style={{ ...td, fontWeight: 600, display: 'flex', alignItems: 'center', gap: 8 }}>{s.name}{s.isPermanent && <PermanentBadge t={t} />}</td>
                    if (col.id === 'status') return <td key={col.id} style={td}><StatusPill active={s.status === 1} t={t} /></td>
                    return <td key={col.id} style={td}>—</td>
                  })}
                  <td style={{ ...td, textAlign: 'center', width: 90 }} onClick={(e) => e.stopPropagation()}>
                    <div style={{ display: 'inline-flex', gap: 6 }}>
                      {can('edit') && <button onClick={(e) => { e.stopPropagation(); openStatus(s) }}
                        style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 28, height: 28, borderRadius: 6, border: '1px solid #5cb85c', background: 'transparent', color: '#5cb85c', cursor: 'pointer', padding: 0 }}
                        title={t('edit')}><PencilIcon /></button>}
                      {can('delete') && !s.isPermanent && <button onClick={(e) => { e.stopPropagation(); setToDelete(s) }}
                        style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 28, height: 28, borderRadius: 6, border: '1px solid #dc2626', background: 'transparent', color: '#dc2626', cursor: 'pointer', padding: 0 }}
                        title={t('del')}><TrashIcon /></button>}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          <div style={{ padding: '10px 16px', textAlign: 'center', fontSize: 12, color: 'var(--color-muted-foreground)' }}>
            {loading ? t('loading') : `${total}`}
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
        <ExportModal cols={cols} items={sorted} getCell={(s, id) => getCellExport(s, id)}
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
    if (!colorCode.trim()) { notify('ko', t('title'), t('err_color_required')); return }
    if (!translations.some((tr) => tr.name.trim())) { notify('ko', t('title'), t('err_name_required')); return }
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
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, height: '100%', boxSizing: 'border-box', overflow: 'auto' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><TagIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0, display: 'flex', alignItems: 'center', gap: 8 }}>
            {isEdit ? (orderStatus ? (translations.find((tr) => tr.name.trim())?.name || `#${orderStatus.id}`) : t('loading')) : t('new')}
            {isPermanent && <PermanentBadge t={t} />}
          </h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <button style={btnPrimary} onClick={submit} disabled={saving || (isEdit && !orderStatus)}>
            {saving ? '…' : t('save')}
          </button>
        </div>
      </div>

      {isEdit && !orderStatus ? (
        <div style={{ padding: 40, display: 'flex', justifyContent: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <>
          <Tabs tabs={TABS} active={activeTab} onChange={setActiveTab} />
          <div>
            {activeTab === 'main' && (
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 260px', gap: 16, alignItems: 'start' }}>
                <div style={{ ...card, padding: 24 }}>
                  <h3 style={fieldSectionTitle}><GearIcon />{t('section_general_data')}</h3>
                  <div style={fieldGap}>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_color')} *</label><InfoDot text={t('tip_color')} /></div>
                      <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                        <input type="color" value={/^#[0-9a-fA-F]{6}$/.test(colorCode) ? colorCode : '#6b7280'}
                          onChange={(e) => setColorCode(e.target.value)}
                          style={{ width: 40, height: 36, padding: 2, border: '1px solid var(--color-border)', borderRadius: 6, cursor: 'pointer', background: 'transparent' }} />
                        <input style={{ ...inputCss, flex: 1 }} value={colorCode} onChange={(e) => setColorCode(e.target.value)} placeholder="#0696cb" />
                      </div>
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
              <div style={{ display: 'grid', gridTemplateColumns: '200px 1fr', gap: 16, alignItems: 'start' }}>
                <div style={{ ...card, padding: 12 }}>
                  <LangSwitcher languages={options?.languages ?? []} value={lang} onChange={setLang} />
                </div>
                <div style={{ ...card, padding: 24 }}>
                  <div style={fieldGap}>
                    <div>
                      <div style={labelRow}><label style={label}>{t('field_name')} *</label><InfoDot text={t('tip_name')} /></div>
                      <input style={inputCss} value={currentTrans?.name ?? ''} onChange={(e) => setTrans(lang, e.target.value)} />
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
