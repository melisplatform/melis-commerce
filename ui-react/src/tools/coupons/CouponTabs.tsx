import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import {
  fetchCouponOrders,
  type CouponClientOption, type CouponProductOption,
  type CouponOrderUsage, type CouponOrderStatus,
} from './api'
import { card, inputCss, th, td, actionBtn } from '../../shared/styles'
import { fmtDate, currentLang, type T } from '../../shared/i18n'
import { CartIcon, TrashIcon, PencilIcon, PlusIcon, RefreshIcon } from '../../shared/icons'
import { AssignTable, type AssignCol } from './AssignTable'
import { DateRangeFilter } from '../../shared/DateRangeFilter'

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
  const navigate = useNavigate()
  const assignedSet = new Set(draftIds)
  const assigned = directory.filter((c) => assignedSet.has(c.clientId))
  const available = directory.filter((c) => !assignedSet.has(c.clientId))

  function add(clientId: number) { onChange([...draftIds, clientId]) }
  function remove(clientId: number) { onChange(draftIds.filter((id) => id !== clientId)) }

  const cols: AssignCol<CouponClientOption>[] = [
    { key: 'id', label: t('col_client_id'), value: (c) => c.clientId, width: 70 },
    { key: 'name', label: t('col_account_name'), value: (c) => c.name },
    { key: 'company', label: t('col_company'), value: (c) => c.company },
  ]

  return (
    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 20, alignItems: 'start' }}>
      <AssignTable title={t('assign_clients_title')} columns={cols} rows={assigned} rowKey={(c) => c.clientId}
        emptyText={t('assign_clients_empty')} t={t}
        actions={(c) => (
          <button onClick={() => remove(c.clientId)} style={actionBtn('#dc2626')} title={t('unlink')}><TrashIcon /></button>
        )} />
      <AssignTable title={t('client_list_title')} columns={cols} rows={available} rowKey={(c) => c.clientId}
        emptyText={t('empty')} t={t}
        actions={(c) => (
          <div style={{ display: 'inline-flex', gap: 6 }}>
            <button onClick={() => navigate(`/melis-commerce/clients-list/${c.clientId}`)} style={actionBtn('#5cb85c')} title={t('edit')}><PencilIcon /></button>
            <button onClick={() => add(c.clientId)} style={actionBtn('#337ab7')} title={t('assign_clients_add')}><PlusIcon /></button>
          </div>
        )} />
    </div>
  )
}

// ── Assign products tab — 2 cards: Assigned products / Product list ───────────
export function CouponProductsTab({ directory, draftIds, onChange, t }: {
  directory: CouponProductOption[]; draftIds: number[]; onChange: (ids: number[]) => void; t: T
}) {
  const navigate = useNavigate()
  const assignedSet = new Set(draftIds)
  const assigned = directory.filter((p) => assignedSet.has(p.productId))
  const available = directory.filter((p) => !assignedSet.has(p.productId))

  function add(productId: number) { onChange([...draftIds, productId]) }
  function remove(productId: number) { onChange(draftIds.filter((id) => id !== productId)) }

  const cols: AssignCol<CouponProductOption>[] = [
    { key: 'id', label: t('col_client_id'), value: (p) => p.productId, width: 70 },
    { key: 'ref', label: t('col_product_ref'), value: (p) => p.reference },
    { key: 'name', label: t('col_product_name'), value: (p) => p.name },
  ]

  return (
    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 20, alignItems: 'start' }}>
      <AssignTable title={t('assign_products_title')} columns={cols} rows={assigned} rowKey={(p) => p.productId}
        emptyText={t('assign_products_empty')} t={t}
        actions={(p) => (
          <button onClick={() => remove(p.productId)} style={actionBtn('#dc2626')} title={t('unlink')}><TrashIcon /></button>
        )} />
      <AssignTable title={t('product_list_title')} columns={cols} rows={available} rowKey={(p) => p.productId}
        emptyText={t('empty')} t={t}
        actions={(p) => (
          <div style={{ display: 'inline-flex', gap: 6 }}>
            <button onClick={() => navigate(`/melis-commerce/product-list/${p.productId}`)} style={actionBtn('#5cb85c')} title={t('edit')}><PencilIcon /></button>
            <button onClick={() => add(p.productId)} style={actionBtn('#337ab7')} title={t('assign_products_add')}><PlusIcon /></button>
          </div>
        )} />
    </div>
  )
}

// ── Orders (usage history) tab — filters + full columns ────────────────────────
export function CouponOrdersTab({ couponId, t }: { couponId: number; t: T }) {
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

      <table style={{ width: '100%', borderCollapse: 'collapse' }}>
        <thead>
          <tr>
            <th style={th}>{t('col_id')}</th>
            <th style={th}>{t('orders_col_order')}</th>
            <th style={th}>{t('orders_col_status')}</th>
            <th style={{ ...th, textAlign: 'center' }}>{t('orders_col_products')}</th>
            <th style={{ ...th, textAlign: 'right' }}>{t('orders_col_price')}</th>
            <th style={th}>{t('orders_col_civility')}</th>
            <th style={th}>{t('orders_col_firstname')}</th>
            <th style={th}>{t('orders_col_lastname')}</th>
            <th style={th}>{t('orders_col_date')}</th>
          </tr>
        </thead>
        <tbody>
          {items.length === 0 ? (
            <tr><td colSpan={9} style={{ ...td, textAlign: 'center', padding: '28px', color: 'var(--color-muted-foreground)' }}>{t('orders_empty')}</td></tr>
          ) : items.map((o) => (
            <tr key={o.id}>
              <td style={{ ...td, color: 'var(--color-muted-foreground)' }}>{o.orderId}</td>
              <td style={td}><code style={{ fontSize: 12 }}>{o.orderReference}</code></td>
              <td style={td}>
                <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5, padding: '2px 8px', borderRadius: 20, fontSize: 11, fontWeight: 600, color: '#fff', background: o.statusColor || '#6b7280' }}>{o.statusName || '—'}</span>
              </td>
              <td style={{ ...td, textAlign: 'center' }}>{o.productCount}</td>
              <td style={{ ...td, textAlign: 'right', fontWeight: 600 }}>{fmtAmt(o.price)}</td>
              <td style={td}>{o.civility || '—'}</td>
              <td style={td}>{o.firstname || '—'}</td>
              <td style={td}>{o.lastname || '—'}</td>
              <td style={{ ...td, color: 'var(--color-muted-foreground)' }}>{o.dateCreation ? fmtDate(o.dateCreation) : '—'}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}
