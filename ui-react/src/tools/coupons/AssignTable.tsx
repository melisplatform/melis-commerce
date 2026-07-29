import { Fragment, useMemo, useState, type ReactNode } from 'react'
import { card, inputCss, th, td, btnGhost, iconBtn } from '../../shared/styles'
import { RefreshIcon } from '../../shared/icons'
import { ExpandToggle, HiddenColsRow } from '../../shared/ExpandableRow'
import { useIsNarrow } from '../../shared/useIsNarrow'

/**
 * Table d'assignation (clients/produits) façon design system de l'app — carte, recherche,
 * en-tête thémée, pagination. Tri/recherche/pagination 100% côté client (jeux de données courts).
 */
export interface AssignCol<T> {
  key: string
  label: string
  value: (row: T) => string | number
  render?: (row: T) => ReactNode
  align?: 'left' | 'center' | 'right'
  width?: number | string
  /** Reste visible sur mobile (sinon consultable via le "+" par ligne). */
  essential?: boolean
}

export function AssignTable<T>({ title, columns, rows, rowKey, emptyText, onRefresh, actions, t }: {
  title?: string
  columns: AssignCol<T>[]
  rows: T[]
  rowKey: (row: T) => string | number
  emptyText: string
  onRefresh?: () => void
  actions?: (row: T) => ReactNode
  t: (k: string) => string
}) {
  const narrow = useIsNarrow()
  const [search, setSearch] = useState('')
  const [page, setPage] = useState(1)
  const [sortKey, setSortKey] = useState<string | null>(null)
  const [sortAsc, setSortAsc] = useState(true)
  const [expanded, setExpanded] = useState<Set<string | number>>(new Set())
  function toggleExpand(id: string | number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }
  const LIMIT = 30

  const filtered = useMemo(() => {
    let out = rows
    if (search.trim()) {
      const q = search.trim().toLowerCase()
      out = out.filter((r) => columns.some((c) => String(c.value(r) ?? '').toLowerCase().includes(q)))
    }
    if (sortKey) {
      const col = columns.find((c) => c.key === sortKey)
      if (col) {
        out = [...out].sort((a, b) => {
          const va = col.value(a), vb = col.value(b)
          if (va < vb) return sortAsc ? -1 : 1
          if (va > vb) return sortAsc ? 1 : -1
          return 0
        })
      }
    }
    return out
  }, [rows, search, sortKey, sortAsc, columns])

  const total = filtered.length
  const totalPages = Math.max(1, Math.ceil(total / LIMIT))
  const curPage = Math.min(page, totalPages)
  const paged = filtered.slice((curPage - 1) * LIMIT, curPage * LIMIT)

  function onSort(key: string) {
    if (sortKey === key) setSortAsc((a) => !a)
    else { setSortKey(key); setSortAsc(true) }
    setPage(1)
  }
  const arrow = (key: string) => sortKey === key ? (sortAsc ? ' ↑' : ' ↓') : ''

  const hasEssentialFlag = columns.some((c) => c.essential)
  const visibleCols = narrow ? columns.filter((c, i) => (hasEssentialFlag ? c.essential : i === 0)) : columns
  const hasHidden = narrow && visibleCols.length < columns.length
  const colSpan = visibleCols.length + (actions ? 1 : 0) + (hasHidden ? 1 : 0)
  const eColDefs = columns.map((c) => ({ id: c.key, visible: visibleCols.includes(c) }))
  const labelFor = (id: string) => columns.find((c) => c.key === id)?.label ?? id
  const cellFor = (row: T, id: string) => { const c = columns.find((x) => x.key === id); return c ? (c.render ? c.render(row) : String(c.value(row))) : '—' }

  return (
    <div style={{ ...card, padding: 20 }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 10, marginBottom: 14, flexWrap: narrow ? 'wrap' : 'nowrap' }}>
        {title && <h3 style={{ fontSize: 15, fontWeight: 600, margin: 0 }}>{title}</h3>}
        <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginLeft: narrow ? 0 : 'auto', flexBasis: narrow ? '100%' : undefined }}>
          <input style={{ ...inputCss, height: 32, width: narrow ? '100%' : 160, flex: narrow ? 1 : undefined }} placeholder={t('search')}
            value={search} onChange={(e) => { setSearch(e.target.value); setPage(1) }} />
          {onRefresh && (
            <button style={iconBtn} title={t('refresh')} onClick={onRefresh}><RefreshIcon /></button>
          )}
        </div>
      </div>

      <div style={{ border: '1px solid var(--color-border)', borderRadius: 8, overflowX: 'auto' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
            <tr>
              {hasHidden && <th style={{ ...th, width: 32 }} />}
              {visibleCols.map((c) => (
                <th key={c.key} style={{ ...th, textAlign: c.align ?? 'left', width: narrow ? undefined : c.width, cursor: 'pointer', userSelect: 'none' }} onClick={() => onSort(c.key)}>
                  {c.label}{arrow(c.key)}
                </th>
              ))}
              {actions && <th style={{ ...th, textAlign: 'right' }}>{t('action')}</th>}
            </tr>
          </thead>
          <tbody>
            {paged.length === 0 ? (
              <tr><td colSpan={colSpan} style={{ ...td, textAlign: 'center', padding: '24px', color: 'var(--color-muted-foreground)' }}>{emptyText}</td></tr>
            ) : paged.map((row) => {
              const key = rowKey(row)
              return (
                <Fragment key={key}>
                  <tr>
                    {hasHidden && (
                      <td style={td}><ExpandToggle expanded={expanded.has(key)} onClick={() => toggleExpand(key)} /></td>
                    )}
                    {visibleCols.map((c) => (
                      <td key={c.key} style={{ ...td, textAlign: c.align ?? 'left' }}>{c.render ? c.render(row) : String(c.value(row))}</td>
                    ))}
                    {actions && <td style={{ ...td, textAlign: 'right' }}>{actions(row)}</td>}
                  </tr>
                  {hasHidden && expanded.has(key) && (
                    <HiddenColsRow cols={eColDefs} labelFor={labelFor} renderValue={(id) => cellFor(row, id)} colSpan={colSpan} narrow={narrow} />
                  )}
                </Fragment>
              )
            })}
          </tbody>
        </table>
      </div>

      {totalPages > 1 && (
        <div style={{ display: 'flex', gap: 6, justifyContent: 'center', alignItems: 'center', marginTop: 14, flexWrap: 'wrap' }}>
          <button style={btnGhost} disabled={curPage <= 1} onClick={() => setPage((p) => p - 1)}>←</button>
          {Array.from({ length: Math.min(totalPages, 10) }, (_, i) => i + 1).map((p) => (
            <button key={p} style={{ ...btnGhost, fontWeight: p === curPage ? 700 : 400, opacity: p === curPage ? 1 : 0.6 }}
              onClick={() => setPage(p)}>{p}</button>
          ))}
          <button style={btnGhost} disabled={curPage >= totalPages} onClick={() => setPage((p) => p + 1)}>→</button>
        </div>
      )}
    </div>
  )
}
