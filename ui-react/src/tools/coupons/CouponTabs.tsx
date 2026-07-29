import { Fragment, useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import {
  fetchCouponOrders,
  type CouponClientOption, type CouponProductOption,
  type CouponOrderUsage, type CouponOrderStatus,
} from './api'
import { card, inputCss, th, td, iconBtn } from '../../shared/styles'
import { fmtDate, currentLang, type T } from '../../shared/i18n'
import { CartIcon, TrashIcon, PencilIcon, PlusIcon, RefreshIcon } from '../../shared/icons'
import { AssignTable, type AssignCol } from './AssignTable'
import { DateRangeFilter } from '../../shared/DateRangeFilter'
import { ExpandToggle, HiddenColsRow } from '../../shared/ExpandableRow'
import { useIsNarrow } from '../../shared/useIsNarrow'

const sectionTitle = { fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 16px', display: 'flex', alignItems: 'center', gap: 6 } as const

/**
 * Onglets Assign clients / Assign products : composants 100% contrôlés — aucun appel
 * réseau ici. Le parent (CouponForm) détient la liste brouillon (draftIds) et ne
 * persiste les assignations qu'au clic sur le bouton Save principal du formulaire.
 */

// ── Assign clients tab — 2 cards: Assigned clients / Client list ──────────────
export function CouponClientsTab({ directory, draftIds, onChange, t }: {
  directory: CouponClientOption[]; draftIds: number[]; onChange: (ids: number[]) => void; t: T
}) {
  const narrow = useIsNarrow()
  const navigate = useNavigate()
  const assignedSet = new Set(draftIds)
  const assigned = directory.filter((c) => assignedSet.has(c.clientId))
  const available = directory.filter((c) => !assignedSet.has(c.clientId))

  function add(clientId: number) { onChange([...draftIds, clientId]) }
  function remove(clientId: number) { onChange(draftIds.filter((id) => id !== clientId)) }

  const cols: AssignCol<CouponClientOption>[] = [
    { key: 'id', label: t('col_client_id'), value: (c) => c.clientId, width: 70 },
    { key: 'name', label: t('col_account_name'), value: (c) => c.name, essential: true },
    { key: 'company', label: t('col_company'), value: (c) => c.company },
  ]

  return (
    <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 20, alignItems: 'start' }}>
      <AssignTable title={t('assign_clients_title')} columns={cols} rows={assigned} rowKey={(c) => c.clientId}
        emptyText={t('assign_clients_empty')} t={t}
        actions={(c) => (
          <button onClick={() => remove(c.clientId)} style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('unlink')}><TrashIcon /></button>
        )} />
      <AssignTable title={t('client_list_title')} columns={cols} rows={available} rowKey={(c) => c.clientId}
        emptyText={t('empty')} t={t}
        actions={(c) => (
          <div style={{ display: 'inline-flex', gap: 6 }}>
            <button onClick={() => navigate(`/melis-commerce/clients-list/${c.clientId}`)} style={iconBtn} title={t('edit')}><PencilIcon /></button>
            <button onClick={() => add(c.clientId)} style={iconBtn} title={t('assign_clients_add')}><PlusIcon /></button>
          </div>
        )} />
    </div>
  )
}

// ── Assign products tab — 2 cards: Assigned products / Product list ───────────
export function CouponProductsTab({ directory, draftIds, onChange, t }: {
  directory: CouponProductOption[]; draftIds: number[]; onChange: (ids: number[]) => void; t: T
}) {
  const narrow = useIsNarrow()
  const navigate = useNavigate()
  const assignedSet = new Set(draftIds)
  const assigned = directory.filter((p) => assignedSet.has(p.productId))
  const available = directory.filter((p) => !assignedSet.has(p.productId))

  function add(productId: number) { onChange([...draftIds, productId]) }
  function remove(productId: number) { onChange(draftIds.filter((id) => id !== productId)) }

  const cols: AssignCol<CouponProductOption>[] = [
    { key: 'id', label: t('col_client_id'), value: (p) => p.productId, width: 70 },
    { key: 'ref', label: t('col_product_ref'), value: (p) => p.reference },
    { key: 'name', label: t('col_product_name'), value: (p) => p.name, essential: true },
  ]

  return (
    <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 20, alignItems: 'start' }}>
      <AssignTable title={t('assign_products_title')} columns={cols} rows={assigned} rowKey={(p) => p.productId}
        emptyText={t('assign_products_empty')} t={t}
        actions={(p) => (
          <button onClick={() => remove(p.productId)} style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('unlink')}><TrashIcon /></button>
        )} />
      <AssignTable title={t('product_list_title')} columns={cols} rows={available} rowKey={(p) => p.productId}
        emptyText={t('empty')} t={t}
        actions={(p) => (
          <div style={{ display: 'inline-flex', gap: 6 }}>
            <button onClick={() => navigate(`/melis-commerce/product-list/${p.productId}`)} style={iconBtn} title={t('edit')}><PencilIcon /></button>
            <button onClick={() => add(p.productId)} style={iconBtn} title={t('assign_products_add')}><PlusIcon /></button>
          </div>
        )} />
    </div>
  )
}

// ── Orders (usage history) tab — filters + full columns ────────────────────────
const ORDER_ESSENTIAL_COLS = new Set(['order'])

export function CouponOrdersTab({ couponId, t }: { couponId: number; t: T }) {
  const narrow = useIsNarrow()
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }
  const [items, setItems] = useState<CouponOrderUsage[]>([])
  const [statuses, setStatuses] = useState<CouponOrderStatus[]>([])
  const [searchInput, setSearchInput] = useState('')
  const [search, setSearch] = useState('')
  const [status, setStatus] = useState<number | null>(null)
  const [dateStart, setDateStart] = useState('')
  const [dateEnd, setDateEnd] = useState('')
  const [tick, setTick] = useState(0)

  useEffect(() => {
    fetchCouponOrders(couponId, { search, status, dateStart, dateEnd })
      .then((r) => { setItems(r.items); setStatuses(r.statuses) }).catch(() => null)
  }, [couponId, search, status, dateStart, dateEnd, tick])

  const fmtAmt = (v: number) => v.toLocaleString(currentLang() === 'fr' ? 'fr-FR' : 'en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 })

  const ORDER_COLS: { id: string; label: string; render: (o: CouponOrderUsage) => import('react').ReactNode; style?: import('react').CSSProperties }[] = [
    { id: 'id', label: t('col_id'), render: (o) => o.orderId, style: { ...td, color: 'var(--color-muted-foreground)' } },
    { id: 'order', label: t('orders_col_order'), render: (o) => <code style={{ fontSize: 12 }}>{o.orderReference}</code> },
    { id: 'status', label: t('orders_col_status'), render: (o) => <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '2px 8px', borderRadius: 20, fontSize: 11, fontWeight: 600, color: '#fff', background: o.statusColor || '#6b7280' }}>{o.statusName || '—'}</span> },
    { id: 'products', label: t('orders_col_products'), render: (o) => o.productCount, style: { ...td, textAlign: 'center' } },
    { id: 'price', label: t('orders_col_price'), render: (o) => fmtAmt(o.price), style: { ...td, textAlign: 'right', fontWeight: 600 } },
    { id: 'civility', label: t('orders_col_civility'), render: (o) => o.civility || '—' },
    { id: 'firstname', label: t('orders_col_firstname'), render: (o) => o.firstname || '—' },
    { id: 'lastname', label: t('orders_col_lastname'), render: (o) => o.lastname || '—' },
    { id: 'date', label: t('orders_col_date'), render: (o) => o.dateCreation ? fmtDate(o.dateCreation) : '—', style: { ...td, color: 'var(--color-muted-foreground)' } },
  ]
  const visibleOrderCols = narrow ? ORDER_COLS.filter((c) => ORDER_ESSENTIAL_COLS.has(c.id)) : ORDER_COLS
  const hasHiddenOrderCols = narrow && visibleOrderCols.length < ORDER_COLS.length
  const orderColSpan = visibleOrderCols.length + (hasHiddenOrderCols ? 1 : 0)
  const orderColDefs = ORDER_COLS.map((c) => ({ id: c.id, visible: visibleOrderCols.includes(c) }))

  return (
    <div style={{ ...card, padding: 28 }}>
      <h3 style={sectionTitle}><CartIcon />{t('tab_orders')}</h3>

      <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8, marginBottom: 16 }}>
        <input style={{ ...inputCss, height: 34, flex: 1, minWidth: 160, maxWidth: 280 }}
          placeholder={t('orders_search_ph')} value={searchInput}
          onChange={(e) => setSearchInput(e.target.value)}
          onKeyDown={(e) => { if (e.key === 'Enter') setSearch(searchInput) }} />
        <DateRangeFilter from={dateStart} to={dateEnd} onChange={(f, e) => { setDateStart(f); setDateEnd(e) }} t={t} />
        <select style={{ ...inputCss, height: 34, width: 'auto', minWidth: 150 }}
          value={status ?? ''} onChange={(e) => setStatus(e.target.value === '' ? null : Number(e.target.value))}>
          <option value="">{t('orders_filter_status')}</option>
          {statuses.map((s) => <option key={s.id} value={s.id}>{s.name}</option>)}
        </select>
        <button style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 34, height: 34, borderRadius: 6, border: '1px solid var(--color-border)', background: 'transparent', color: 'var(--color-muted-foreground)', cursor: 'pointer' }}
          title={t('refresh')} onClick={() => setTick((x) => x + 1)}><RefreshIcon /></button>
      </div>

      <div style={{ overflowX: 'auto' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr>
              {hasHiddenOrderCols && <th style={{ ...th, width: 32 }} />}
              {visibleOrderCols.map((c) => <th key={c.id} style={c.style ? { ...th, textAlign: c.style.textAlign } : th}>{c.label}</th>)}
            </tr>
          </thead>
          <tbody>
            {items.length === 0 ? (
              <tr><td colSpan={orderColSpan} style={{ ...td, textAlign: 'center', padding: '28px', color: 'var(--color-muted-foreground)' }}>{t('orders_empty')}</td></tr>
            ) : items.map((o) => (
              <Fragment key={o.id}>
                <tr>
                  {hasHiddenOrderCols && (
                    <td style={td}><ExpandToggle expanded={expanded.has(o.id)} onClick={() => toggleExpand(o.id)} /></td>
                  )}
                  {visibleOrderCols.map((c) => <td key={c.id} style={c.style ?? td}>{c.render(o)}</td>)}
                </tr>
                {hasHiddenOrderCols && expanded.has(o.id) && (
                  <HiddenColsRow cols={orderColDefs} labelFor={(id) => ORDER_COLS.find((c) => c.id === id)?.label ?? id} renderValue={(id) => ORDER_COLS.find((c) => c.id === id)?.render(o)} colSpan={orderColSpan} narrow={narrow} />
                )}
              </Fragment>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}
