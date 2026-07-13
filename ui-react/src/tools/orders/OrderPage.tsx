import React, { useEffect, useMemo, useRef, useState } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  fetchOrders, fetchOrderStats, fetchOrderStatuses, fetchOrderById,
  createOrder, saveOrderInfo,
  fetchOrderAttachments, uploadOrderAttachment, deleteOrderAttachment,
  type OrderItem, type OrderStats, type OrderStatus, type OrderDetail, type OrderAttachment,
} from './api'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT, fmtDate, currentLang } from '../../shared/i18n'
import { card, inputCss, btnGhost, btnPrimary, th, td } from '../../shared/styles'
import { CartIcon, ShoppingCartKpiIcon, PackageIcon, CalendarIcon, PlusIcon, RefreshIcon, PencilIcon, ResetIcon } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { DateRangeFilter } from '../../shared/DateRangeFilter'
import { makeColStore, visibleCols, type ColDef } from '../../shared/columns'
import { ExportModal } from '../../shared/ExportModal'
import { ColManager } from '../../shared/ColManager'
import { openSubTab, updateSubLabel } from '../../shared/subtabs'
import { Tabs } from '../../shared/Tabs'
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
  // Brique optionnelle : n'apparaît que si le module MelisCommerceOrderInvoice est actif
  // (voir shared/externalBricks.ts) — melis-commerce ignore tout du contenu du bouton.
  const OrderInvoiceButton = useExternalBrickComponent<{ orderId: number }>('MelisCommerceOrderInvoiceBrick', 'OrderRowButton')
  const [mode, setMode] = useState<'react' | 'old'>(listCache.get()?.mode ?? 'react')
  const [oldLoaded, setOldLoaded] = useState(false)
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

  const visible = visibleCols(cols)
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
  const fmtAmt = (v: number) => v.toLocaleString(currentLang() === 'fr' ? 'fr-FR' : 'en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
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
        {/* KPIs — 3 cards */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 12 }}>
          <Kpi icon={<ShoppingCartKpiIcon />} label={t('kpi_total')} value={stats?.total ?? null} iconBg="rgba(59,130,246,0.1)" iconColor="#3b82f6" />
          <Kpi icon={<CalendarIcon />}        label={t('kpi_today')} value={stats?.today ?? null} iconBg="rgba(16,185,129,0.1)" iconColor="#10b981" />
          <Kpi icon={<PackageIcon />}          label={t('kpi_month')} value={stats?.thisMonth ?? null} iconBg="rgba(139,92,246,0.1)" iconColor="#8b5cf6" />
        </div>

        {/* Filters — search + status on same line, then dates + tools */}
        <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8 }}>
          <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 180, maxWidth: 360 }}
            placeholder={t('search_placeholder')} value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)}
            onKeyDown={(e) => { if (e.key === 'Enter') { setSearch(searchInput); setPage(1) } }} />
          <select style={{ ...inputCss, height: 36, width: 'auto', minWidth: 160 }}
            value={filterStatus ?? ''} onChange={(e) => { setFilterStatus(e.target.value ? Number(e.target.value) : null); setPage(1) }}>
            <option value="">{t('filter_status')}</option>
            {statuses.map((s) => <option key={s.id} value={s.id}>{s.name}</option>)}
          </select>
          <DateRangeFilter from={dateStart} to={dateEnd}
            onChange={(f, to) => { setDateStart(f); setDateEnd(to); setPage(1) }} t={t} />
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

        {/* Table */}
        <div style={{ ...card, overflow: 'hidden' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 700 }}>
            <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
              <tr>
                {visible.map((c) => (
                  <th key={c.id} style={{ ...th, cursor: 'pointer', userSelect: 'none' }} onClick={() => onSort(c.id)}>
                    {t(COL_LABEL[c.id])}{arrow(c.id)}
                  </th>
                ))}
                <th style={{ ...th, width: OrderInvoiceButton ? 100 : 60, textAlign: 'center' }}>{t('col_action')}</th>
              </tr>
            </thead>
            <tbody>
              {loading && sorted.length === 0 && (
                <tr><td colSpan={visible.length} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('loading')}</td></tr>
              )}
              {!loading && sorted.length === 0 && (
                <tr><td colSpan={visible.length} style={{ ...td, textAlign: 'center', padding: '40px 16px', color: 'var(--color-muted-foreground)' }}>{t('no_items')}</td></tr>
              )}
              {sorted.map((o) => (
                <tr key={o.id} style={{ cursor: 'pointer' }}
                  onClick={() => openOrder(o)}
                  onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                  onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                  {visible.map((c) => {
                    if (c.id === 'id')        return <td key={c.id} style={{ ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' }}>{o.id}</td>
                    if (c.id === 'reference') return <td key={c.id} style={td}><code style={{ fontSize: 12 }}>{o.reference}</code></td>
                    if (c.id === 'status')    return <td key={c.id} style={td}><StatusPill color={o.statusColor} name={o.statusName} size="xs" /></td>
                    if (c.id === 'products')  return <td key={c.id} style={{ ...td, textAlign: 'center' }}>{o.productCount}</td>
                    if (c.id === 'total')     return <td key={c.id} style={{ ...td, textAlign: 'right', fontWeight: 600 }}>{fmtAmt(o.totalAmount)}</td>
                    if (c.id === 'firstname') return <td key={c.id} style={td}>{o.firstname || '—'}</td>
                    if (c.id === 'name')      return <td key={c.id} style={td}>{o.name || '—'}</td>
                    if (c.id === 'company')   return <td key={c.id} style={td}>{o.company || '—'}</td>
                    if (c.id === 'date')      return <td key={c.id} style={{ ...td, color: 'var(--color-muted-foreground)' }}>{o.dateCreation ? fmtDate(o.dateCreation) : '—'}</td>
                    return <td key={c.id} style={td}>—</td>
                  })}
                  <td style={{ ...td, textAlign: 'center', width: OrderInvoiceButton ? 100 : 60 }} onClick={(e) => e.stopPropagation()}>
                    {can('edit') && (
                      <button
                        onClick={(e) => { e.stopPropagation(); openOrder(o) }}
                        style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 28, height: 28, borderRadius: 6, border: '1px solid #5cb85c', background: 'transparent', color: '#5cb85c', cursor: 'pointer', padding: 0 }}
                        title={t('col_action')}>
                        <PencilIcon />
                      </button>
                    )}
                    {OrderInvoiceButton && <OrderInvoiceButton orderId={o.id} />}
                  </td>
                </tr>
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
  const navigate = useNavigate()
  const isEdit  = id !== 'new'
  const orderId = isEdit ? Number(id) : null
  const subTabPath = `${base}/${id}`

  const { can, loaded: capsLoaded } = useCaps(TOOL_MELIS_KEY)

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
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><ShoppingCartKpiIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>
            {isEdit ? (order ? `${t('title')} ${order.reference || `#${order.id}`}` : t('loading')) : t('new')}
          </h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <button style={btnPrimary} onClick={submit} disabled={saving || (isEdit && !order)}>
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
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.55)', zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
      <div style={{ background: '#fff', borderRadius: 6, width: 520, maxWidth: '95vw', overflow: 'hidden', boxShadow: '0 8px 40px rgba(0,0,0,.35)', color: '#333', fontFamily: 'inherit' }}>
        {/* Title bar */}
        <div style={{ background: '#337ab7', padding: '12px 18px', display: 'flex', alignItems: 'center', gap: 8 }}>
          <span style={{ background: '#d9534f', color: '#fff', borderRadius: 4, padding: '2px 8px', fontWeight: 700, fontSize: 15, lineHeight: 1.4 }}>+</span>
          <span style={{ color: '#fff', fontWeight: 600, fontSize: 15 }}>{t('upload_title')}</span>
        </div>

        <div style={{ padding: '20px 22px 18px', background: '#fff' }}>
          <p style={{ margin: '0 0 18px', fontSize: 13, color: '#666' }}>{t('upload_subtitle')}</p>

          {/* File picker */}
          <div style={{ background: '#f5f5f5', border: '1px solid #e0e0e0', borderRadius: 4, padding: '10px 14px', marginBottom: 18 }}>
            <input ref={inputRef} type="file" style={{ display: 'none' }} onChange={(e) => { if (e.target.files?.[0]) pickFile(e.target.files[0]) }} />
            <button onClick={() => inputRef.current?.click()}
              style={{ display: 'inline-flex', alignItems: 'center', gap: 7, padding: '5px 14px', fontSize: 13, border: '1px solid #ccc', borderRadius: 4, background: '#fff', color: '#333', cursor: 'pointer' }}>
              <svg viewBox="0 0 24 24" fill="none" stroke="#555" strokeWidth="2" style={{ width: 16, height: 16 }}><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
              <span style={{ color: '#333' }}>{file ? file.name : t('upload_select')}</span>
            </button>
          </div>

          {/* Name */}
          <div style={{ marginBottom: 18 }}>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 6 }}>
              <label style={{ fontWeight: 700, fontSize: 13, color: '#333' }}>{t('upload_name_label')}</label>
              <span style={{ width: 16, height: 16, borderRadius: '50%', border: '1.5px solid #999', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', fontSize: 10, color: '#999', fontWeight: 700, flexShrink: 0 }}>i</span>
            </div>
            <input value={name} onChange={(e) => setName(e.target.value)} placeholder={t('upload_name_label')}
              style={{ width: '100%', boxSizing: 'border-box', padding: '7px 10px', fontSize: 13, border: '1px solid #ccc', borderRadius: 4, outline: 'none', background: '#fff', color: '#333' }} />
          </div>

          {err && <p style={{ color: '#d9534f', fontSize: 12, margin: '0 0 12px' }}>{err}</p>}

          {/* Footer */}
          <div style={{ display: 'flex', justifyContent: 'space-between', paddingTop: 6, borderTop: '1px solid #eee', marginTop: 4 }}>
            <button onClick={onClose}
              style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '6px 18px', fontSize: 13, borderRadius: 4, border: '1px solid #d9534f', background: '#fff', color: '#d9534f', cursor: 'pointer', fontWeight: 500 }}>
              ✕ {t('upload_close')}
            </button>
            <button onClick={save} disabled={saving}
              style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '6px 18px', fontSize: 13, borderRadius: 4, border: '1px solid #5cb85c', background: '#fff', color: '#5cb85c', cursor: 'pointer', fontWeight: 500, opacity: saving ? 0.6 : 1 }}>
              💾 {saving ? t('upload_saving') : t('upload_save')}
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}

// ── Information tab ─── fully controlled, no internal saves ───────────────────
function InformationTab({ orderId, statuses, locked, localStatus, setLocalStatus, localReference, setLocalReference, errReference, setErrReference, t }: {
  orderId: number | null; statuses: OrderStatus[]; locked: boolean
  localStatus: number; setLocalStatus: (s: number) => void
  localReference: string; setLocalReference: (r: string) => void
  errReference: boolean; setErrReference: (v: boolean) => void
  t: (k: string) => string
}) {
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
    <div style={{ display: 'grid', gridTemplateColumns: '1fr 320px', gap: 14, paddingTop: 16, alignItems: 'start' }}>

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

        {/* Invoice */}
        <InfoCard icon={iconSvg(<><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></>)} title={t('section_invoice')}>
          <div style={{ fontSize: 13, color: 'var(--color-muted-foreground)', padding: '4px 0', fontStyle: 'italic' }}>
            {t('section_invoice')}
          </div>
        </InfoCard>

        {/* Attachments */}
        <InfoCard
          icon={iconSvg(<path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>)}
          title={t('section_attachments')}
          action={orderId ? (
            <button onClick={() => setShowUpload(true)}
              style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '5px 12px', fontSize: 12, fontWeight: 600, border: '1.5px solid #5cb85c', borderRadius: 6, background: 'transparent', color: '#5cb85c', cursor: 'pointer' }}>
              <span style={{ fontSize: 16, lineHeight: 1 }}>+</span> {t('btn_add_file')}
            </button>
          ) : undefined}
        >
          {attachments.length === 0 ? (
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, padding: '10px 14px', borderRadius: 6, border: '1px dashed var(--color-border)', fontSize: 13, color: 'var(--color-muted-foreground)' }}>
              {t('no_attachments')}
            </div>
          ) : (
            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
              {attachments.map((a) => (
                <div key={a.id} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '8px 12px', background: 'var(--color-accent)', borderRadius: 6, fontSize: 13 }}>
                  <a href={`/melis/react-api/orders/${orderId}/attachments/${a.id}/download`}
                    style={{ color: '#60a5fa', textDecoration: 'none', fontWeight: 500 }}
                    onMouseEnter={(e) => (e.currentTarget.style.textDecoration = 'underline')}
                    onMouseLeave={(e) => (e.currentTarget.style.textDecoration = 'none')}>
                    {a.name}
                  </a>
                  <button onClick={() => setToDelete(a)}
                    style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 26, height: 26, borderRadius: 5, border: '1px solid rgba(239,68,68,.4)', background: 'transparent', color: '#ef4444', cursor: 'pointer', fontSize: 12, padding: 0, flexShrink: 0 }}>
                    ✕
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
        {/* Active badge */}
        {activeStatus && (
          <div style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '5px 12px', borderRadius: 20, background: activeStatus.color || '#6b7280', marginBottom: 6 }}>
            <span style={{ width: 7, height: 7, borderRadius: '50%', background: 'rgba(255,255,255,.7)', flexShrink: 0 }} />
            <span style={{ color: '#fff', fontWeight: 700, fontSize: 12 }}>{activeStatus.name}</span>
          </div>
        )}
        {/* All status buttons */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
          {statuses.map((s) => {
            const isActive = s.id === localStatus
            return (
              <button key={s.id}
                disabled={locked && !isActive}
                onClick={() => !locked && setLocalStatus(s.id)}
                style={{
                  padding: '7px 14px', borderRadius: 6, fontSize: 13, fontWeight: 600, textAlign: 'left',
                  border: `2px solid ${s.color || '#6b7280'}`,
                  background: isActive ? s.color : 'transparent',
                  color: isActive ? '#fff' : (s.color || '#6b7280'),
                  cursor: locked ? 'default' : 'pointer',
                  opacity: locked && !isActive ? 0.35 : 1,
                  transition: 'all .15s',
                  boxShadow: isActive ? `0 0 0 3px ${s.color}33` : 'none',
                  display: 'flex', alignItems: 'center', gap: 8,
                }}>
                <span style={{ width: 8, height: 8, borderRadius: '50%', background: isActive ? 'rgba(255,255,255,.8)' : (s.color || '#6b7280'), flexShrink: 0 }} />
                {s.name}
                {isActive && <span style={{ marginLeft: 'auto', fontSize: 11 }}>✓</span>}
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
