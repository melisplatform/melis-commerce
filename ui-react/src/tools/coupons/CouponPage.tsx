import { useEffect, useMemo, useRef, useState } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  fetchCoupons, fetchCouponStats, fetchCouponById, saveCoupon, deleteCoupon,
  fetchClientsDirectory, fetchProductsDirectory, fetchCouponClients, fetchCouponProducts,
  saveCouponClient, deleteCouponClient, saveCouponProduct, deleteCouponProduct,
  type CouponItem, type CouponStats, type CouponDetail, type CouponClientOption, type CouponProductOption,
} from './api'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT, fmtDate } from '../../shared/i18n'
import { DatePicker } from '../../shared/DatePicker'
import { card, inputCss, label, btnGhost, btnPrimary, th, td, iconBtn } from '../../shared/styles'
import { TagIcon, CheckIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon, UsersIcon, CartIcon, ResetIcon } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { makeColStore, visibleCols, type ColDef } from '../../shared/columns'
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

const listCache = makeCache<{
  items: CouponItem[]; stats: CouponStats | null; total: number; page: number
  search: string; searchInput: string; filterStatus: number | null; sortCol: string; sortAsc: boolean; mode: 'react' | 'old'
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
  if (c.percentage !== null) return `${c.percentage}%`
  if (c.discountValue !== null) return String(c.discountValue)
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
      display: 'inline-flex', alignItems: 'center', gap: 5, padding: '2px 8px',
      borderRadius: 20, fontSize: 11, fontWeight: 600, color: '#fff',
      background: active ? '#16a34a' : '#6b7280', whiteSpace: 'nowrap',
    }}>
      {active ? t('status_active') : t('status_inactive')}
    </span>
  )
}

// ── COUPON LIST ─────────────────────────────────────────────────────────────────
function CouponList({ base }: { base: string }) {
  const t = makeT(DICT)
  const navigate = useNavigate()
  const { can } = useCaps(TOOL_MELIS_KEY)
  const [mode, setMode] = useState<'react' | 'old'>(listCache.get()?.mode ?? 'react')
  // Non-persistent bricks get fully unmounted when the tab isn't active and rebuilt from
  // scratch when revisited — only `mode` survives that via listCache. If `oldLoaded`
  // reinitialized to false regardless, coming back to a tab left in "Old" view rendered
  // neither the (gated-by-oldLoaded) LegacyFrame nor the (gated-by-mode==='react') React
  // view: a blank tab. Derive the initial value from `mode` so a remount picks the legacy
  // iframe back up immediately, same as `mode` itself does.
  const [oldLoaded, setOldLoaded] = useState(() => (listCache.get()?.mode ?? 'react') === 'old')
  const [items, setItems] = useState<CouponItem[]>(listCache.get()?.items ?? [])
  const [stats, setStats] = useState<CouponStats | null>(listCache.get()?.stats ?? null)
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
  const [toDelete, setToDelete] = useState<CouponItem | null>(null)

  const cacheRef = useRef({ items, stats, total, page, search, searchInput, filterStatus, sortCol, sortAsc, mode })
  useEffect(() => { cacheRef.current = { items, stats, total, page, search, searchInput, filterStatus, sortCol, sortAsc, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchCouponStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => {
    setLoading(true)
    fetchCoupons({ search, status: filterStatus, page, limit: LIMIT })
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
      if (sortCol === 'code')     { va = a.code; vb = b.code }
      if (sortCol === 'status')   { va = a.status; vb = b.status }
      if (sortCol === 'discount') { va = a.percentage ?? a.discountValue ?? 0; vb = b.percentage ?? b.discountValue ?? 0 }
      if (sortCol === 'uses')     { va = a.currentUseNumber; vb = b.currentUseNumber }
      if (sortCol === 'valid')    { va = a.dateValidEnd ?? ''; vb = b.dateValidEnd ?? '' }
      if (va < vb) return sortAsc ? -1 : 1
      if (va > vb) return sortAsc ? 1 : -1
      return 0
    })
    return arr
  }, [items, sortCol, sortAsc])

  const onSort = (col: string) => { if (sortCol === col) setSortAsc((p) => !p); else { setSortCol(col); setSortAsc(true) } }
  const arrow  = (col: string) => sortCol === col ? (sortAsc ? ' ↑' : ' ↓') : ''
  const totalPages = Math.max(1, Math.ceil(total / LIMIT))

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
          {can('create') && <button style={btnPrimary} onClick={() => navigate(`${base}/new`)}><PlusIcon />{t('new')}</button>}
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 20, flex: 1, minHeight: 0 }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 12 }}>
          <Kpi icon={<TagIcon />}    label={t('kpi_total')} value={stats?.total ?? null} iconBg="rgba(59,130,246,0.1)" iconColor="#3b82f6" />
          <Kpi icon={<CheckIcon />} label={t('kpi_active')} value={stats?.active ?? null} iconBg="rgba(16,185,129,0.1)" iconColor="#10b981" />
          <Kpi icon={<RefreshIcon />} label={t('kpi_uses')} value={stats?.uses ?? null} iconBg="rgba(139,92,246,0.1)" iconColor="#8b5cf6" />
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
                  onClick={() => openCoupon(c)}
                  onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                  onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                  {visible.map((col) => {
                    if (col.id === 'id')       return <td key={col.id} style={{ ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' }}>{c.id}</td>
                    if (col.id === 'code')     return <td key={col.id} style={td}><code style={{ fontSize: 12 }}>{c.code}</code></td>
                    if (col.id === 'status')   return <td key={col.id} style={td}><StatusPill active={c.status === 1} t={t} /></td>
                    if (col.id === 'discount') return <td key={col.id} style={{ ...td, fontWeight: 600 }}>{discountLabel(c)}</td>
                    if (col.id === 'uses')     return <td key={col.id} style={{ ...td, textAlign: 'center' }}>{c.maxUseNumber !== null ? `${c.currentUseNumber}/${c.maxUseNumber}` : c.currentUseNumber}</td>
                    if (col.id === 'valid')    return <td key={col.id} style={{ ...td, color: 'var(--color-muted-foreground)' }}>{c.dateValidEnd ? fmtDate(c.dateValidEnd) : '—'}</td>
                    return <td key={col.id} style={td}>—</td>
                  })}
                  <td style={{ ...td, textAlign: 'center', width: 90 }} onClick={(e) => e.stopPropagation()}>
                    <div style={{ display: 'inline-flex', gap: 6 }}>
                      {can('edit') && <button onClick={(e) => { e.stopPropagation(); openCoupon(c) }}
                        style={iconBtn} title={t('edit')}><PencilIcon /></button>}
                      {can('delete') && <button onClick={(e) => { e.stopPropagation(); setToDelete(c) }}
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
        <ExportModal cols={cols} items={sorted} getCell={(c, id) => getCellExport(c, id)}
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
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><TagIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>
            {isEdit ? (coupon ? coupon.code : t('loading')) : t('new')}
          </h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <button style={btnPrimary} onClick={submit} disabled={saving || (isEdit && !coupon)}>
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
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 260px', gap: 16, alignItems: 'start' }}>
                <div style={{ ...card, padding: 24 }}>
                  <h3 style={fieldSectionTitle}><GearIcon />{t('section_general_data')}</h3>
                  <div style={fieldGap}>
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

                <div style={{ ...card, padding: 24 }}>
                  <h3 style={fieldSectionTitle}>{t('status_label')}</h3>
                  <StatusBadge active={status} onClick={() => setStatus((s) => !s)} t={t} />
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
