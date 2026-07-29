import React, { Fragment, useEffect, useMemo, useRef, useState, type ReactNode } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  fetchOrders, fetchOrderStats, fetchOrderStatuses, fetchOrderById,
  createOrder, saveOrderInfo,
  fetchOrderAttachments, uploadOrderAttachment, deleteOrderAttachment,
  type OrderItem, type OrderStats, type OrderStatus, type OrderDetail, type OrderAttachment,
} from './api'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT, fmtDate, fmtMoney } from '../../shared/i18n'
import { card, inputCss, btnGhost, btnPrimary, th, td, iconBtn } from '../../shared/styles'
import { CartIcon, ShoppingCartKpiIcon, PackageIcon, CalendarIcon, PlusIcon, RefreshIcon, PencilIcon, ResetIcon, TrashIcon } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { DateRangeFilter } from '../../shared/DateRangeFilter'
import { makeColStore, visibleCols, effectiveCols, type ColDef } from '../../shared/columns'
import { ExpandToggle, HiddenColsRow } from '../../shared/ExpandableRow'
import { useIsNarrow } from '../../shared/useIsNarrow'
import { ExportModal } from '../../shared/ExportModal'
import { ColManager } from '../../shared/ColManager'
import { openSubTab, updateSubLabel } from '../../shared/subtabs'
import { Tabs } from '../../shared/Tabs'
import { AccountLink } from '../../shared/openAccount'
import { BasketTab, AddressesTab, PaymentTab, ShippingTab, MessagesTab, ReturnsTab } from './OrderTabs'
import OrderCheckoutWizard from './wizard/OrderCheckoutWizard'
import { FileDownIcon, GripIcon } from '../../shared/icons'
import { useCaps } from '../../shared/useCaps'
import { useExternalBrickComponent } from '../../shared/externalBricks'

const TOOL_MELIS_KEY = 'meliscommerce_order_list_page'
const CANCELLED_STATUS = 5

const COL_ORDER = ['id', 'reference', 'status', 'products', 'total', 'firstname', 'name', 'company', 'date'] as const
const COL_LABEL: Record<string, string> = {
  id: 'col_id', reference: 'col_reference', status: 'col_status',
  products: 'col_products', total: 'col_total',
  firstname: 'col_firstname', name: 'col_name', company: 'col_company', date: 'col_date',
}
const cols$ = makeColStore('melis-order-cols-v2', COL_ORDER)
const ESSENTIAL_COLS = new Set(['reference'])

const listCache = makeCache<{
  items: OrderItem[]; stats: OrderStats | null; total: number; page: number
  search: string; searchInput: string; filterStatus: number | null; dateStart: string; dateEnd: string
  sortCol: string; sortAsc: boolean; mode: 'react' | 'old'
}>()

function getCellExport(o: OrderItem, id: string): string | number {
  switch (id) {
    case 'id':        return o.id
    case 'reference': return o.reference
    case 'status':    return o.statusName
    case 'products':  return o.productCount
    case 'total':     return o.totalAmount
    case 'firstname': return o.firstname
    case 'name':      return o.name
    case 'company':   return o.company
    case 'date':      return o.dateCreation ?? ''
    default:          return ''
  }
}

export default function OrderPage() {
  const { id } = useParams()
  const location = useLocation()
  const base = id ? location.pathname.slice(0, location.pathname.length - id.length - 1) : location.pathname
  if (id === 'new') return <OrderCheckoutWizard base={base} />
  if (id) return <OrderForm id={id} base={base} />
  return <OrderList base={base} />
}

// ── Status pill ────────────────────────────────────────────────────────────────
function StatusPill({ color, name, size = 'sm' }: { color: string; name: string; size?: 'sm' | 'xs' }) {
  const pad = size === 'xs' ? '2px 8px' : '3px 10px'
  const fs  = size === 'xs' ? 11 : 12
  return (
    <span style={{
      display: 'inline-flex', alignItems: 'center', gap: 5, padding: pad,
      borderRadius: 20, fontSize: fs, fontWeight: 600, color: '#fff',
      background: color || '#6b7280', whiteSpace: 'nowrap',
    }}>
      {name}
    </span>
  )
}

// ── ORDER LIST ─────────────────────────────────────────────────────────────────
function OrderList({ base }: { base: string }) {
  const t = makeT(DICT)
  const navigate = useNavigate()
  const { can } = useCaps(TOOL_MELIS_KEY)
  const narrow = useIsNarrow()
  // Brique optionnelle : n'apparaît que si le module MelisCommerceOrderInvoice est actif
  // (voir shared/externalBricks.ts) — melis-commerce ignore tout du contenu du bouton.
  const OrderInvoiceButton = useExternalBrickComponent<{ orderId: number }>('MelisCommerceOrderInvoiceBrick', 'OrderRowButton')
  const [mode, setMode] = useState<'react' | 'old'>(listCache.get()?.mode ?? 'react')
  // Non-persistent bricks (this one included, see brick.manifest.json) get fully unmounted
  // when the tab isn't active and rebuilt from scratch when revisited — only `mode` survives
  // that via listCache. If `oldLoaded` reinitialized to false regardless, coming back to a
  // tab left in "Old" view rendered neither the (gated-by-oldLoaded) LegacyFrame nor the
  // (gated-by-mode==='react') React view: a blank tab. Derive the initial value from `mode`
  // so a remount picks the legacy iframe back up immediately, same as `mode` itself does.
  const [oldLoaded, setOldLoaded] = useState(() => (listCache.get()?.mode ?? 'react') === 'old')
  const [items, setItems] = useState<OrderItem[]>(listCache.get()?.items ?? [])
  const [stats, setStats] = useState<OrderStats | null>(listCache.get()?.stats ?? null)
  const [statuses, setStatuses] = useState<OrderStatus[]>([])
  const [loading, setLoading] = useState(false)
  const [total, setTotal] = useState(listCache.get()?.total ?? 0)
  const [page, setPage] = useState(listCache.get()?.page ?? 1)
  const LIMIT = 25
  const [searchInput, setSearchInput] = useState(listCache.get()?.searchInput ?? '')
  const [search, setSearch] = useState(listCache.get()?.search ?? '')
  const [filterStatus, setFilterStatus] = useState<number | null>(listCache.get()?.filterStatus ?? null)
  const [dateStart, setDateStart] = useState(listCache.get()?.dateStart ?? '')
  const [dateEnd, setDateEnd] = useState(listCache.get()?.dateEnd ?? '')
  const [sortCol, setSortCol] = useState(listCache.get()?.sortCol ?? 'id')
  const [sortAsc, setSortAsc] = useState(listCache.get()?.sortAsc ?? false)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }

  const cacheRef = useRef({ items, stats, total, page, search, searchInput, filterStatus, dateStart, dateEnd, sortCol, sortAsc, mode })
  useEffect(() => { cacheRef.current = { items, stats, total, page, search, searchInput, filterStatus, dateStart, dateEnd, sortCol, sortAsc, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchOrderStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => { fetchOrderStatuses().then(setStatuses).catch(() => null) }, [])
  useEffect(() => {
    setLoading(true)
    fetchOrders({ search, status: filterStatus, dateStart, dateEnd, page, limit: LIMIT })
      .then((r) => { setItems(r.items); setTotal(r.total) })
      .catch(() => null)
      .finally(() => setLoading(false))
  }, [search, filterStatus, dateStart, dateEnd, page, tick])

  const eCols = effectiveCols(cols, ESSENTIAL_COLS, narrow)
  const visible = visibleCols(eCols)
  const hasHidden = eCols.some((c) => !c.visible)
  const totalCols = visible.length + 1 + (hasHidden ? 1 : 0)
  function cellStyle(id: string) {
    if (id === 'id') return { ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' as const }
    if (id === 'products') return { ...td, textAlign: 'center' as const }
    if (id === 'total') return { ...td, textAlign: 'right' as const, fontWeight: 600 }
    if (id === 'date') return { ...td, color: 'var(--color-muted-foreground)' }
    return td
  }
  function cellContent(o: OrderItem, id: string): ReactNode {
    switch (id) {
      case 'id': return o.id
      case 'reference': return <code style={{ fontSize: 12 }}>{o.reference}</code>
      case 'status': return <StatusPill color={o.statusColor} name={o.statusName} size="xs" />
      case 'products': return o.productCount
      case 'total': return fmtMoney(o.totalAmount, o.currency)
      case 'firstname': return o.firstname ? <AccountLink navigate={navigate} clientId={o.clientId} label={`${o.firstname} ${o.name}`.trim() || o.company}>{o.firstname}</AccountLink> : '—'
      case 'name': return o.name ? <AccountLink navigate={navigate} clientId={o.clientId} label={`${o.firstname} ${o.name}`.trim() || o.company}>{o.name}</AccountLink> : '—'
      case 'company': return o.company ? <AccountLink navigate={navigate} clientId={o.clientId} label={o.company}>{o.company}</AccountLink> : '—'
      case 'date': return o.dateCreation ? fmtDate(o.dateCreation) : '—'
      default: return '—'
    }
  }
  const sorted = useMemo(() => {
    const arr = [...items]
    arr.sort((a, b) => {
      let va: string | number = '', vb: string | number = ''
      if (sortCol === 'id')        { va = a.id; vb = b.id }
      if (sortCol === 'reference') { va = a.reference; vb = b.reference }
      if (sortCol === 'status')    { va = a.statusName; vb = b.statusName }
      if (sortCol === 'products')  { va = a.productCount; vb = b.productCount }
      if (sortCol === 'total')     { va = a.totalAmount; vb = b.totalAmount }
      if (sortCol === 'firstname') { va = a.firstname; vb = b.firstname }
      if (sortCol === 'name')      { va = a.name; vb = b.name }
      if (sortCol === 'company')   { va = a.company; vb = b.company }
      if (sortCol === 'date')      { va = a.dateCreation ?? ''; vb = b.dateCreation ?? '' }
      if (va < vb) return sortAsc ? -1 : 1
      if (va > vb) return sortAsc ? 1 : -1
      return 0
    })
    return arr
  }, [items, sortCol, sortAsc])

  const onSort = (col: string) => { if (sortCol === col) setSortAsc((p) => !p); else { setSortCol(col); setSortAsc(true) } }
  const arrow  = (col: string) => sortCol === col ? (sortAsc ? ' ↑' : ' ↓') : ''
  const totalPages = Math.max(1, Math.ceil(total / LIMIT))

  const openOrder = (o: OrderItem) => {
    const path = `${base}/${o.id}`
    navigate(path)
    openSubTab(base, { id: path, label: o.reference || `#${o.id}`, path })
  }

  // Réinitialiser les filtres : recherche + statut + plage de dates + tri par défaut (id desc),
  // retour page 1, puis refetch. On vide `items` : sinon le stale-while-revalidate garde les
  // lignes affichées et le clic paraît sans effet quand aucun filtre n'était posé.
  function resetFilters() {
    setSearchInput(''); setSearch('')
    setFilterStatus(null)
    setDateStart(''); setDateEnd('')
    setSortCol('id'); setSortAsc(false)
    setItems([])
    setPage(1)
    setTick((x) => x + 1)
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box', ...(mode === 'old' ? { height: '100%', overflow: 'hidden' } : {}) }}>
      {/* Title row */}
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
        {/* KPIs — 3 cards */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(130px, 1fr))', gap: 12 }}>
          <Kpi icon={<ShoppingCartKpiIcon />} label={t('kpi_total')} value={stats?.total ?? null} iconBg="rgba(59,130,246,0.1)" iconColor="#3b82f6" />
          <Kpi icon={<CalendarIcon />}        label={t('kpi_today')} value={stats?.today ?? null} iconBg="rgba(16,185,129,0.1)" iconColor="#10b981" />
          <Kpi icon={<PackageIcon />}          label={t('kpi_month')} value={stats?.thisMonth ?? null} iconBg="rgba(139,92,246,0.1)" iconColor="#8b5cf6" />
        </div>

        {/* Filters — search + status on same line, then dates + tools */}
        <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8 }}>
          <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 180, maxWidth: narrow ? undefined : 360, flexBasis: narrow ? '100%' : undefined }}
            placeholder={t('search_placeholder')} value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)}
            onKeyDown={(e) => { if (e.key === 'Enter') { setSearch(searchInput); setPage(1) } }} />
          <select style={{ ...inputCss, height: 36, width: narrow ? '100%' : 'auto', minWidth: 160, flexBasis: narrow ? '100%' : undefined }}
            value={filterStatus ?? ''} onChange={(e) => { setFilterStatus(e.target.value ? Number(e.target.value) : null); setPage(1) }}>
            <option value="">{t('filter_status')}</option>
            {statuses.map((s) => <option key={s.id} value={s.id}>{s.name}</option>)}
          </select>
          <div style={{ flexBasis: narrow ? '100%' : undefined }}>
            <DateRangeFilter from={dateStart} to={dateEnd}
              onChange={(f, to) => { setDateStart(f); setDateEnd(to); setPage(1) }} t={t} />
          </div>
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

        {/* Table */}
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
                <th style={{ ...th, width: OrderInvoiceButton ? 100 : 60, textAlign: 'center', position: 'sticky', right: 0, background: 'var(--color-muted,rgba(0,0,0,.03))' }}>{t('col_action')}</th>
              </tr>
            </thead>
            <tbody>
              {loading && sorted.length === 0 && (
                <tr><td colSpan={totalCols} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('loading')}</td></tr>
              )}
              {!loading && sorted.length === 0 && (
                <tr><td colSpan={totalCols} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('no_items')}</td></tr>
              )}
              {sorted.map((o) => (
                <Fragment key={o.id}>
                  <tr style={{ cursor: 'pointer' }}
                    onClick={() => openOrder(o)}
                    onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                    onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                    {hasHidden && (
                      <td style={td} onClick={(e) => e.stopPropagation()}>
                        <ExpandToggle expanded={expanded.has(o.id)} onClick={() => toggleExpand(o.id)} />
                      </td>
                    )}
                    {visible.map((c) => (
                      <td key={c.id} style={cellStyle(c.id)}>{cellContent(o, c.id)}</td>
                    ))}
                    <td style={{ ...td, textAlign: 'center', width: OrderInvoiceButton ? 100 : 60, position: 'sticky', right: 0, background: 'var(--color-card,#fff)' }} onClick={(e) => e.stopPropagation()}>
                      {can('edit') && (
                        <button
                          onClick={(e) => { e.stopPropagation(); openOrder(o) }}
                          style={iconBtn}
                          title={t('col_action')}>
                          <PencilIcon />
                        </button>
                      )}
                      {OrderInvoiceButton && <OrderInvoiceButton orderId={o.id} />}
                    </td>
                  </tr>
                  {expanded.has(o.id) && (
                    <HiddenColsRow cols={eCols} labelFor={(id) => t(COL_LABEL[id])} renderValue={(id) => cellContent(o, id)} colSpan={totalCols} narrow={narrow} />
                  )}
                </Fragment>
              ))}
            </tbody>
          </table>
          <div style={{ padding: '10px 16px', textAlign: 'center', fontSize: 12, color: 'var(--color-muted-foreground)' }}>
            {loading ? t('loading') : t('count', { n: total })}
          </div>
        </div>

        {/* Pagination */}
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
        <ExportModal cols={cols} items={sorted} getCell={(o, id) => getCellExport(o, id)}
          labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')}
          sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />
      )}
    </div>
  )
}

// ── ORDER FORM ─────────────────────────────────────────────────────────────────
function OrderForm({ id, base }: { id: string; base: string }) {
  const t       = makeT(DICT)
  const narrow  = useIsNarrow()
  const navigate = useNavigate()
  const isEdit  = id !== 'new'
  const orderId = isEdit ? Number(id) : null
  const subTabPath = `${base}/${id}`

  const { can, loaded: capsLoaded } = useCaps(TOOL_MELIS_KEY)
  // Onglet apporté par une brique optionnelle : n'existe que si MelisCommerceOrderInvoice
  // est actif (voir shared/externalBricks.ts) — melis-commerce ignore tout de la facturation.
  const InvoicesTabComp = useExternalBrickComponent<{ orderId: number }>('MelisCommerceOrderInvoiceBrick', 'InvoicesTab')

  const [order, setOrder]       = useState<OrderDetail | null>(null)
  const [statuses, setStatuses] = useState<OrderStatus[]>([])
  const [activeTab, setActiveTab] = useState('information')
  const [saving, setSaving]     = useState(false)

  // Controlled form state — only committed on Save
  const [localStatus, setLocalStatus]       = useState<number>(1)
  const [localReference, setLocalReference] = useState('')
  const [errReference, setErrReference]     = useState(false)
  const [formError, setFormError]           = useState(false)

  useEffect(() => {
    openSubTab(base, { id: subTabPath, label: isEdit ? t('loading') : t('new'), path: subTabPath })
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])
  useEffect(() => { fetchOrderStatuses().then(setStatuses).catch(() => null) }, [])
  useEffect(() => {
    if (!orderId) return
    fetchOrderById(orderId).then((o) => {
      setOrder(o)
      setLocalStatus(o.status)
      setLocalReference(o.reference)
      updateSubLabel(base, subTabPath, o.reference || `#${o.id}`)
    }).catch(() => null)
  }, [orderId])

  const locked = order?.status === CANCELLED_STATUS

  // Onglets masqués selon les droits (l'accès à un onglet = capacité de même clé).
  const TABS = [
    { key: 'information', label: t('tab_information') },
    { key: 'basket',      label: t('tab_basket'),     disabled: !isEdit },
    { key: 'addresses',   label: t('tab_addresses'),  disabled: !isEdit },
    { key: 'payment',     label: t('tab_payment'),    disabled: !isEdit },
    { key: 'shipping',    label: t('tab_shipping'),   disabled: !isEdit },
    { key: 'messages',    label: t('tab_messages'),   disabled: !isEdit },
    { key: 'returns',     label: t('tab_returns'),    disabled: !isEdit },
    ...(InvoicesTabComp ? [{ key: 'invoices', label: t('tab_invoices'), disabled: !isEdit }] : []),
  ].filter((tb) => can(tb.key))

  // Si l'onglet actif vient d'être retiré par les droits, basculer sur le premier autorisé.
  useEffect(() => {
    if (capsLoaded && TABS.length && !TABS.some((tb) => tb.key === activeTab)) setActiveTab(TABS[0].key)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [capsLoaded])

  async function submit() {
    if (!localReference.trim()) { setErrReference(true); setFormError(true); return }
    setFormError(false)
    setSaving(true)
    try {
      if (isEdit && orderId) {
        await saveOrderInfo(orderId, { status: localStatus, reference: localReference.trim() })
        setOrder((o) => o ? {
          ...o, status: localStatus, reference: localReference.trim(),
          statusName: statuses.find((s) => s.id === localStatus)?.name ?? o.statusName,
          statusColor: statuses.find((s) => s.id === localStatus)?.color ?? o.statusColor,
        } : o)
        updateSubLabel(base, subTabPath, localReference.trim())
        notify('ok', t('title'), t('status_changed'))
      } else {
        const r = await createOrder({ reference: localReference.trim(), status: localStatus })
        navigate(`${base}/${r.id}`)
      }
    } catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : t('err_cancelled')) }
    finally { setSaving(false) }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box' }}>
      {/* Header — back + title + Save (same pattern as AccountForm) */}
      <div style={{ display: 'flex', alignItems: narrow ? 'flex-start' : 'center', justifyContent: 'space-between', gap: 16, flexWrap: narrow ? 'wrap' : 'nowrap' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><ShoppingCartKpiIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>
            {isEdit ? (order ? `${t('title')} ${order.reference || `#${order.id}`}` : t('loading')) : t('new')}
          </h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexBasis: narrow ? '100%' : undefined }}>
          <button style={{ ...btnPrimary, flex: narrow ? 1 : undefined, justifyContent: narrow ? 'center' : undefined }} onClick={submit} disabled={saving || (isEdit && !order)}>
            {saving ? '…' : t('save')}
          </button>
        </div>
      </div>

      {isEdit && !order ? (
        <div style={{ padding: 40, display: 'flex', justifyContent: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <>
          <Tabs tabs={TABS} active={activeTab} onChange={setActiveTab} />
          {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, marginBottom: 16 }}>{t('err_required_fields')}</div>}
          <div>
            {activeTab === 'information' && (
              <InformationTab
                orderId={orderId} statuses={statuses} locked={locked}
                localStatus={localStatus} setLocalStatus={setLocalStatus}
                localReference={localReference} setLocalReference={setLocalReference}
                errReference={errReference} setErrReference={setErrReference}
                customer={order ? { clientId: order.clientId, company: order.company, firstname: order.firstname, name: order.name } : null}
                t={t}
              />
            )}
            {activeTab === 'basket'    && order && <BasketTab basket={order.basket} t={t} />}
            {activeTab === 'addresses' && order && (
              <AddressesTab orderId={orderId!} billing={order.billing} delivery={order.delivery}
                locked={locked} t={t} can={can} onSaved={() => fetchOrderById(orderId!).then(setOrder).catch(() => null)} />
            )}
            {activeTab === 'payment'   && order && <PaymentTab payments={order.payments} t={t} />}
            {activeTab === 'shipping'  && order && (
              <ShippingTab orderId={orderId!} shippings={order.shippings} locked={locked} t={t} can={can}
                onSaved={() => fetchOrderById(orderId!).then(setOrder).catch(() => null)} />
            )}
            {activeTab === 'messages'  && order && (
              <MessagesTab orderId={orderId!} messages={order.messages} t={t} can={can}
                onSaved={() => fetchOrderById(orderId!).then(setOrder).catch(() => null)} />
            )}
            {activeTab === 'returns'   && order && (
              <ReturnsTab orderId={orderId!} t={t} />
            )}
            {activeTab === 'invoices'  && order && InvoicesTabComp && (
              <InvoicesTabComp orderId={orderId!} />
            )}
          </div>
        </>
      )}
    </div>
  )
}

// ── Info card — titled section card ───────────────────────────────────────────
function InfoCard({ icon, title, action, children }: {
  icon: React.ReactNode; title: React.ReactNode; action?: React.ReactNode; children: React.ReactNode
}) {
  return (
    <div style={{ background: 'var(--color-card,#1a1f2e)', border: '1px solid var(--color-border)', borderRadius: 10, padding: '18px 20px', display: 'flex', flexDirection: 'column', gap: 14 }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
          <span style={{ opacity: 0.7, display: 'flex', alignItems: 'center' }}>{icon}</span>
          <span style={{ fontWeight: 700, fontSize: 13, letterSpacing: '.3px', textTransform: 'uppercase', color: 'var(--color-muted-foreground)' }}>{title}</span>
        </div>
        {action}
      </div>
      {children}
    </div>
  )
}

// ── Upload modal (matches legacy design) ─────────────────────────────────────
function UploadModal({ orderId, onUploaded, onClose, t }: {
  orderId: number; onUploaded: (a: OrderAttachment) => void; onClose: () => void
  t: (k: string) => string
}) {
  const [file, setFile]   = useState<File | null>(null)
  const [name, setName]   = useState('')
  const [saving, setSaving] = useState(false)
  const [err, setErr]     = useState('')
  const inputRef = React.useRef<HTMLInputElement>(null)

  const pickFile = (f: File) => {
    setFile(f)
    if (!name) setName(f.name)
  }

  async function save() {
    if (!file) { setErr('Please select a file.'); return }
    setSaving(true); setErr('')
    try {
      const att = await uploadOrderAttachment(orderId, file, name || file.name)
      onUploaded(att)
      onClose()
    } catch (e) { setErr(e instanceof Error ? e.message : 'Upload failed') }
    finally { setSaving(false) }
  }

  return (
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.5)', backdropFilter: 'blur(2px)', zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 16 }}
      onClick={onClose}>
      <div onClick={(e) => e.stopPropagation()}
        style={{ ...card, width: 480, maxWidth: '95vw', overflow: 'hidden', boxShadow: '0 12px 40px rgba(0,0,0,.35)' }}>
        {/* Header */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '16px 20px', borderBottom: '1px solid var(--color-border)' }}>
          <span style={{ display: 'inline-flex', width: 30, height: 30, borderRadius: 8, alignItems: 'center', justifyContent: 'center', background: 'color-mix(in srgb, var(--color-primary) 14%, transparent)', color: 'var(--color-primary)', flexShrink: 0 }}>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ width: 17, height: 17 }}><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
          </span>
          <span style={{ fontWeight: 600, fontSize: 15, color: 'var(--color-foreground)' }}>{t('upload_title')}</span>
        </div>

        <div style={{ padding: 20 }}>
          <p style={{ margin: '0 0 16px', fontSize: 13, color: 'var(--color-muted-foreground)' }}>{t('upload_subtitle')}</p>

          {/* File picker (drop-zone) */}
          <div style={{ marginBottom: 16 }}>
            <input ref={inputRef} type="file" style={{ display: 'none' }} onChange={(e) => { if (e.target.files?.[0]) pickFile(e.target.files[0]) }} />
            <button onClick={() => inputRef.current?.click()}
              style={{ width: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, padding: '14px', fontSize: 14, border: '1.5px dashed var(--color-border)', borderRadius: 8, background: 'var(--color-muted,rgba(0,0,0,.03))', color: 'var(--color-foreground)', cursor: 'pointer' }}>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ width: 17, height: 17, color: 'var(--color-muted-foreground)' }}><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
              <span style={{ color: file ? 'var(--color-foreground)' : 'var(--color-muted-foreground)', fontWeight: file ? 600 : 400, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{file ? file.name : t('upload_select')}</span>
            </button>
          </div>

          {/* Name */}
          <div style={{ marginBottom: 16 }}>
            <label style={{ display: 'block', fontWeight: 600, fontSize: 13, color: 'var(--color-foreground)', marginBottom: 6 }}>{t('upload_name_label')}</label>
            <input value={name} onChange={(e) => setName(e.target.value)} placeholder={t('upload_name_label')}
              style={{ ...inputCss, width: '100%', boxSizing: 'border-box' }} />
          </div>

          {err && <p style={{ color: '#ef4444', fontSize: 12, margin: '0 0 12px' }}>{err}</p>}

          {/* Footer */}
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, paddingTop: 14, borderTop: '1px solid var(--color-border)', marginTop: 4 }}>
            <button onClick={onClose} style={{ ...btnGhost }}>{t('upload_close')}</button>
            <button onClick={save} disabled={saving} style={{ ...btnPrimary, opacity: saving ? 0.6 : 1 }}>{saving ? t('upload_saving') : t('upload_save')}</button>
          </div>
        </div>
      </div>
    </div>
  )
}

// ── Information tab ─── fully controlled, no internal saves ───────────────────
function InformationTab({ orderId, statuses, locked, localStatus, setLocalStatus, localReference, setLocalReference, errReference, setErrReference, customer, t }: {
  orderId: number | null; statuses: OrderStatus[]; locked: boolean
  localStatus: number; setLocalStatus: (s: number) => void
  localReference: string; setLocalReference: (r: string) => void
  errReference: boolean; setErrReference: (v: boolean) => void
  customer: { clientId: number; company: string; firstname: string; name: string } | null
  t: (k: string) => string
}) {
  const narrow = useIsNarrow()
  const navigate = useNavigate()
  const [attachments, setAttachments] = useState<OrderAttachment[]>([])
  const [showUpload, setShowUpload]   = useState(false)
  const [toDelete, setToDelete]       = useState<OrderAttachment | null>(null)

  useEffect(() => {
    if (!orderId) return
    fetchOrderAttachments(orderId).then(setAttachments).catch(() => null)
  }, [orderId])

  function confirmDeleteAttachment() {
    if (!orderId || !toDelete) return
    const docId = toDelete.id
    setToDelete(null)
    deleteOrderAttachment(orderId, docId).then(() => {
      setAttachments((prev) => prev.filter((a) => a.id !== docId))
    }).catch(() => null)
  }

  const activeStatus = statuses.find((s) => s.id === localStatus)
  const iconSvg = (d: string) => <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ width: 16, height: 16 }}>{d}</svg>

  return (
    <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 320px', gap: 14, paddingTop: 16, alignItems: 'start' }}>

      {/* ── Left column ── */}
      <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>

        {/* Reference */}
        <InfoCard icon={iconSvg(<><path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></>)}
          title={`${t('field_reference')} *`}>
          <input value={localReference} onChange={(e) => { setLocalReference(e.target.value); setErrReference(false) }}
            style={{ ...inputCss, width: '100%', fontSize: 14, fontWeight: 500 }}
            placeholder={t('field_reference')} disabled={locked} />
          {errReference && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_reference_required')}</p>}
        </InfoCard>

        {/* Client — infos minimales ; nom/prénom (et société) cliquables → compte client */}
        {customer && (customer.company || customer.firstname || customer.name) && (
          <InfoCard icon={iconSvg(<><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></>)} title={t('section_customer')}>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 8, fontSize: 14 }}>
              <div style={{ display: 'flex', gap: 8, alignItems: 'baseline' }}>
                <span style={{ color: 'var(--color-muted-foreground)', minWidth: 92, flexShrink: 0 }}>{t('cust_name')}</span>
                {(customer.firstname || customer.name)
                  ? <AccountLink navigate={navigate} clientId={customer.clientId} label={`${customer.firstname} ${customer.name}`.trim() || customer.company} style={{ fontWeight: 600 }}>{`${customer.firstname} ${customer.name}`.trim()}</AccountLink>
                  : <span>—</span>}
              </div>
              <div style={{ display: 'flex', gap: 8, alignItems: 'baseline' }}>
                <span style={{ color: 'var(--color-muted-foreground)', minWidth: 92, flexShrink: 0 }}>{t('cust_company')}</span>
                {customer.company
                  ? <AccountLink navigate={navigate} clientId={customer.clientId} label={customer.company} style={{ fontWeight: 500 }}>{customer.company}</AccountLink>
                  : <span style={{ color: 'var(--color-muted-foreground)' }}>—</span>}
              </div>
            </div>
          </InfoCard>
        )}

        {/* Documents — zone UNIQUE fourre-tout (factures, bons de livraison, et tout autre document) */}
        <InfoCard
          icon={iconSvg(<path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>)}
          title={t('section_documents')}
          action={orderId ? (
            <button onClick={() => setShowUpload(true)}
              style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '5px 12px', fontSize: 12, fontWeight: 600, borderRadius: 6, cursor: 'pointer', border: '1px solid color-mix(in srgb, var(--color-primary) 45%, transparent)', background: 'color-mix(in srgb, var(--color-primary) 12%, transparent)', color: 'var(--color-primary)' }}>
              <span style={{ fontSize: 16, lineHeight: 1 }}>+</span> {t('btn_add_file')}
            </button>
          ) : undefined}
        >
          <p style={{ margin: '0 0 12px', fontSize: 13, color: 'var(--color-muted-foreground)' }}>{t('documents_hint')}</p>
          {attachments.length === 0 ? (
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, padding: '10px 14px', borderRadius: 6, border: '1px dashed var(--color-border)', fontSize: 13, color: 'var(--color-muted-foreground)' }}>
              {t('no_attachments')}
            </div>
          ) : (
            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
              {attachments.map((a) => (
                <div key={a.id} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '8px 12px', background: 'var(--color-accent)', borderRadius: 6, fontSize: 13 }}>
                  <a href={`/melis/react-api/orders/${orderId}/attachments/${a.id}/download`}
                    style={{ color: 'var(--color-primary)', textDecoration: 'none', fontWeight: 500 }}
                    onMouseEnter={(e) => (e.currentTarget.style.textDecoration = 'underline')}
                    onMouseLeave={(e) => (e.currentTarget.style.textDecoration = 'none')}>
                    {a.name}
                  </a>
                  <button onClick={() => setToDelete(a)}
                    style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }}>
                    <TrashIcon />
                  </button>
                </div>
              ))}
            </div>
          )}
        </InfoCard>
      </div>

      {/* ── Right column — Status ── */}
      <InfoCard
        icon={iconSvg(<><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></>)}
        title={t('section_status')}
      >
        {/* Statut courant — pastille discrète (fond teinté, pas de couleur pleine) */}
        {activeStatus && (
          <div style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '6px 12px', borderRadius: 8, marginBottom: 10,
            background: `color-mix(in srgb, ${activeStatus.color || '#6b7280'} 12%, transparent)`,
            border: `1px solid color-mix(in srgb, ${activeStatus.color || '#6b7280'} 45%, transparent)` }}>
            <span style={{ width: 8, height: 8, borderRadius: '50%', background: activeStatus.color || '#6b7280', flexShrink: 0 }} />
            <span style={{ fontWeight: 600, fontSize: 12, color: 'var(--color-foreground)' }}>{activeStatus.name}</span>
          </div>
        )}
        {/* Boutons de statut — sobres mais colorés (fond carte, bordure fine, pastille colorée ;
            fond légèrement teinté + bordure colorée pour l'actif). */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
          {statuses.map((s) => {
            const isActive = s.id === localStatus
            const color = s.color || '#6b7280'
            return (
              <button key={s.id}
                type="button"
                disabled={locked && !isActive}
                onClick={() => !locked && setLocalStatus(s.id)}
                style={{
                  display: 'flex', alignItems: 'center', gap: 10, width: '100%',
                  padding: '9px 12px', borderRadius: 8, fontSize: 13, textAlign: 'left',
                  fontWeight: isActive ? 600 : 500,
                  border: `1px solid ${isActive ? color : 'var(--color-border)'}`,
                  background: isActive ? `color-mix(in srgb, ${color} 12%, transparent)` : 'var(--color-card)',
                  color: 'var(--color-foreground)',
                  cursor: locked ? 'default' : 'pointer',
                  opacity: locked && !isActive ? 0.4 : 1,
                  transition: 'background .12s, border-color .12s',
                }}
                onMouseEnter={(e) => { if (!isActive && !locked) e.currentTarget.style.background = 'var(--color-accent)' }}
                onMouseLeave={(e) => { if (!isActive && !locked) e.currentTarget.style.background = 'var(--color-card)' }}>
                <span style={{ width: 10, height: 10, borderRadius: '50%', background: color, flexShrink: 0,
                  boxShadow: isActive ? `0 0 0 3px color-mix(in srgb, ${color} 22%, transparent)` : 'none' }} />
                <span style={{ flex: 1 }}>{s.name}</span>
                {isActive && <span style={{ color, fontWeight: 700, fontSize: 13 }}>✓</span>}
              </button>
            )
          })}
        </div>
      </InfoCard>

      {showUpload && orderId && (
        <UploadModal orderId={orderId} t={t}
          onUploaded={(a) => setAttachments((prev) => [a, ...prev])}
          onClose={() => setShowUpload(false)} />
      )}

      {toDelete && (
        <ConfirmModal title={t('upload_delete_title')} message={t('upload_delete_confirm', { u: toDelete.name })}
          onConfirm={confirmDeleteAttachment} onCancel={() => setToDelete(null)} t={t} />
      )}

    </div>
  )
}
