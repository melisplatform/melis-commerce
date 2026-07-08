import { useMemo, useState, type ReactNode } from 'react'
import { card, th, td, inputCss, iconBtn } from './styles'
import { RefreshIcon } from './icons'
import type { T } from './i18n'

export interface SimpleCol<TRow> { key: string; label: string; render: (row: TRow) => ReactNode; width?: number }

/**
 * Tableau relationnel générique (onglets Contacts/Adresses/Commandes/Fichiers/Association)
 * avec la barre d'outils DataTable du back-office : Afficher (limite) · Recherche · Ajouter · Rafraîchir,
 * + pagination. Recherche/limite/pagination côté client. Tous les contrôles sont opt-in.
 */
export function SimpleTable<TRow>({
  cols, rows, rowKey, empty, loading, loadingText, t,
  search, searchPlaceholder, filters, onAdd, addIcon, addTitle, onRefresh, paginate = true, toolbarEnd,
}: {
  cols: SimpleCol<TRow>[]; rows: TRow[]; rowKey: (r: TRow) => string | number
  empty: string; loading?: boolean; loadingText?: string; t?: T
  /** Active la recherche : renvoie le texte indexable d'une ligne. */
  search?: (r: TRow) => string
  searchPlaceholder?: string
  /** Filtres additionnels (statut, date…) rendus dans la barre d'outils, à droite de la recherche. */
  filters?: ReactNode
  onAdd?: () => void; addIcon?: ReactNode; addTitle?: string
  onRefresh?: () => void
  paginate?: boolean
  /** Actions additionnelles (ex. gestionnaire de colonnes) rendues à droite, à côté d'Ajouter/Actualiser. */
  toolbarEnd?: ReactNode
}) {
  const [q, setQ] = useState('')
  const [limit, setLimit] = useState(10)
  const [page, setPage] = useState(1)
  const tr = (k: string, v?: Record<string, string | number>) => (t ? t(k, v) : k)

  const filtered = useMemo(() => {
    if (!search || !q.trim()) return rows
    const needle = q.trim().toLowerCase()
    return rows.filter((r) => search(r).toLowerCase().includes(needle))
  }, [rows, q, search])

  const total = filtered.length
  const pages = paginate ? Math.max(1, Math.ceil(total / limit)) : 1
  const cur = Math.min(page, pages)
  const view = paginate ? filtered.slice((cur - 1) * limit, cur * limit) : filtered
  const from = total === 0 ? 0 : (cur - 1) * limit + 1
  const to = paginate ? Math.min(cur * limit, total) : total

  const hasToolbar = !!(search || filters || onAdd || onRefresh || paginate || toolbarEnd)

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
      {hasToolbar && (
        <div style={{ display: 'flex', alignItems: 'center', gap: 12, flexWrap: 'wrap' }}>
          {paginate && (
            <label style={{ display: 'inline-flex', alignItems: 'center', gap: 8, fontSize: 13, color: 'var(--color-muted-foreground)' }}>
              {tr('tbl_show')}
              <select style={{ ...inputCss, height: 34, width: 'auto', padding: '0 8px' }} value={limit}
                onChange={(e) => { setLimit(Number(e.target.value)); setPage(1) }}>
                {[10, 25, 50, 100].map((n) => <option key={n} value={n}>{n}</option>)}
              </select>
            </label>
          )}
          {search && (
            <input style={{ ...inputCss, height: 34, flex: 1, minWidth: 180, maxWidth: 360 }} value={q}
              onChange={(e) => { setQ(e.target.value); setPage(1) }} placeholder={searchPlaceholder ?? tr('search')} />
          )}
          {filters}
          <div style={{ marginLeft: 'auto', display: 'inline-flex', gap: 6 }}>
            {toolbarEnd}
            {onAdd && <button style={{ ...iconBtn, width: 'auto', height: 34, padding: '0 12px', gap: 6, border: '1px solid var(--color-border)' }} title={addTitle ?? tr('tbl_add')} onClick={onAdd}>{addIcon}<span>{addTitle ?? tr('tbl_add')}</span></button>}
            {onRefresh && <button style={{ ...iconBtn, width: 'auto', height: 34, padding: '0 12px', gap: 6, border: '1px solid var(--color-border)' }} title={tr('refresh')} onClick={onRefresh}><RefreshIcon /><span>{tr('refresh')}</span></button>}
          </div>
        </div>
      )}

      <div style={{ ...card, overflow: 'hidden' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 520 }}>
          <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
            <tr>{cols.map((c) => <th key={c.key} style={{ ...th, ...(c.width ? { width: c.width } : {}) }}>{c.label}</th>)}</tr>
          </thead>
          <tbody>
            {view.length === 0 ? (
              <tr><td style={{ ...td, textAlign: 'center', color: 'var(--color-muted-foreground)', padding: '32px 16px' }} colSpan={cols.length}>{loading ? (loadingText ?? '…') : empty}</td></tr>
            ) : view.map((r) => (
              <tr key={rowKey(r)}>{cols.map((c) => <td key={c.key} style={td}>{c.render(r)}</td>)}</tr>
            ))}
          </tbody>
        </table>
      </div>

      {paginate && total > 0 && (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 12, fontSize: 12, color: 'var(--color-muted-foreground)' }}>
          <span>{tr('showing', { a: from, b: to, n: total })}</span>
          <div style={{ display: 'inline-flex', gap: 6 }}>
            <button style={{ ...iconBtn, width: 'auto', padding: '0 10px', height: 30, border: '1px solid var(--color-border)', opacity: cur <= 1 ? 0.5 : 1 }} disabled={cur <= 1} onClick={() => setPage(cur - 1)}>{tr('prev')}</button>
            <button style={{ ...iconBtn, width: 'auto', padding: '0 10px', height: 30, border: '1px solid var(--color-border)', opacity: cur >= pages ? 0.5 : 1 }} disabled={cur >= pages} onClick={() => setPage(cur + 1)}>{tr('next')}</button>
          </div>
        </div>
      )}
    </div>
  )
}
