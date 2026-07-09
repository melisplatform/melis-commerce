import { useEffect, useMemo, useRef, useState } from 'react'
import {
  fetchClientsGroups, fetchClientsGroupStats, saveClientsGroup, deleteClientsGroup,
  type ClientsGroupItem, type ClientsGroupStats,
} from './api'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT } from '../../shared/i18n'
import { card, inputCss, label, btnGhost, btnPrimary, th, td } from '../../shared/styles'
import { UsersIcon, CheckIcon, ListIcon, RefreshIcon, PlusIcon, PencilIcon, TrashIcon, FileDownIcon, GripIcon } from '../../shared/icons'
import { Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { makeColStore, visibleCols, type ColDef } from '../../shared/columns'
import { ExportModal } from '../../shared/ExportModal'
import { ColManager } from '../../shared/ColManager'
import { useCaps } from '../../shared/useCaps'

const TOOL_MELIS_KEY = 'meliscommerce_clients_group_tool_container'

/** Le groupe #1 ("General") est le groupe par défaut : jamais supprimable (règle appliquée
 *  aussi côté serveur — voir MelisComReactApiClientsGroupController::deleteAction). */
const GENERAL_GROUP_ID = 1

const COL_ORDER = ['id', 'name', 'status'] as const
const COL_LABEL: Record<string, string> = { id: 'col_id', name: 'col_name', status: 'col_status' }
const cols$ = makeColStore('melis-clients-group-cols-v1', COL_ORDER)

const listCache = makeCache<{
  items: ClientsGroupItem[]; stats: ClientsGroupStats | null; total: number; page: number
  search: string; searchInput: string; filterStatus: number | null; sortCol: string; sortAsc: boolean; mode: 'react' | 'old'
}>()

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

/** Toggle rond (piste + poignée) utilisé dans le modal de formulaire. */
function StatusToggle({ value, onChange, t }: { value: boolean; onChange: (v: boolean) => void; t: (k: string) => string }) {
  return (
    <div onClick={() => onChange(!value)} style={{ display: 'inline-flex', alignItems: 'center', gap: 10, cursor: 'pointer' }}>
      <span style={{ position: 'relative', display: 'inline-block', width: 44, height: 24, borderRadius: 12, background: value ? '#22c55e' : 'var(--color-border)', transition: 'background .15s', flexShrink: 0 }}>
        <span style={{ position: 'absolute', top: 2, left: value ? 22 : 2, width: 20, height: 20, borderRadius: '50%', background: '#fff', boxShadow: '0 1px 3px rgba(0,0,0,.3)', transition: 'left .15s' }} />
      </span>
      <span style={{ fontSize: 13, fontWeight: 600, color: value ? '#22c55e' : 'var(--color-muted-foreground)' }}>
        {value ? t('status_active') : t('status_inactive')}
      </span>
    </div>
  )
}

function getCellExport(g: ClientsGroupItem, id: string): string | number {
  switch (id) {
    case 'id':     return g.id
    case 'name':   return g.name
    case 'status': return g.status === 1 ? 'Active' : 'Inactive'
    default:       return ''
  }
}

/**
 * Outil "Groupes de clients" — le plus simple de la migration MelisCommerce : une liste
 * (ID / Statut / Nom / Action) + un formulaire à 2 champs (nom + statut) ouvert en MODALE
 * (pas de route dédiée / pas de sous-onglets, contrairement à Attributs ou Coupons — la
 * fiche est trop simple pour justifier une page séparée).
 */
export default function ClientsGroupPage() {
  const t = makeT(DICT)
  const { can } = useCaps(TOOL_MELIS_KEY)
  const [mode, setMode] = useState<'react' | 'old'>(listCache.get()?.mode ?? 'react')
  const [oldLoaded, setOldLoaded] = useState(false)
  const [items, setItems] = useState<ClientsGroupItem[]>(listCache.get()?.items ?? [])
  const [stats, setStats] = useState<ClientsGroupStats | null>(listCache.get()?.stats ?? null)
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
  const [toDelete, setToDelete] = useState<ClientsGroupItem | null>(null)
  const [editing, setEditing] = useState<ClientsGroupItem | 'new' | null>(null)

  const cacheRef = useRef({ items, stats, total, page, search, searchInput, filterStatus, sortCol, sortAsc, mode })
  useEffect(() => { cacheRef.current = { items, stats, total, page, search, searchInput, filterStatus, sortCol, sortAsc, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchClientsGroupStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => {
    setLoading(true)
    fetchClientsGroups({ search, status: filterStatus, page, limit: LIMIT })
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

  async function confirmDelete() {
    if (!toDelete) return
    // Garde-fou côté client en plus du bouton masqué (défense en profondeur — le serveur
    // rejette de toute façon la suppression du groupe General, voir deleteAction).
    if (toDelete.id === GENERAL_GROUP_ID) {
      notify('ko', t('title'), t('err_general_delete'))
      setToDelete(null)
      return
    }
    try {
      await deleteClientsGroup(toDelete.id)
      notify('ok', t('title'), t('saved'))
      setToDelete(null)
      setTick((x) => x + 1)
    } catch (e) {
      notify('ko', t('title'), e instanceof Error ? e.message : 'Error')
      setToDelete(null)
    }
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
          <Kpi icon={<UsersIcon />} label={t('kpi_total')} value={stats?.total ?? null} iconBg="rgba(59,130,246,0.1)" iconColor="#3b82f6" />
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
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 500 }}>
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
              {sorted.map((g) => (
                <tr key={g.id} style={{ cursor: 'pointer' }}
                  onClick={() => setEditing(g)}
                  onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-accent)')}
                  onMouseLeave={(e) => (e.currentTarget.style.background = '')}>
                  {visible.map((col) => {
                    if (col.id === 'id')     return <td key={col.id} style={{ ...td, color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' }}>{g.id}</td>
                    if (col.id === 'name')   return <td key={col.id} style={{ ...td, fontWeight: 600 }}>{g.name}</td>
                    if (col.id === 'status') return <td key={col.id} style={td}><StatusPill active={g.status === 1} t={t} /></td>
                    return <td key={col.id} style={td}>—</td>
                  })}
                  <td style={{ ...td, textAlign: 'center', width: 90 }} onClick={(e) => e.stopPropagation()}>
                    <div style={{ display: 'inline-flex', gap: 6 }}>
                      {can('edit') && <button onClick={(e) => { e.stopPropagation(); setEditing(g) }}
                        style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 28, height: 28, borderRadius: 6, border: '1px solid #5cb85c', background: 'transparent', color: '#5cb85c', cursor: 'pointer', padding: 0 }}
                        title={t('edit')}><PencilIcon /></button>}
                      {/* Le groupe General (#1) ne peut jamais être supprimé — bouton masqué, comme en legacy (JS), et rejeté serveur si contourné. */}
                      {can('delete') && g.id !== GENERAL_GROUP_ID && <button onClick={(e) => { e.stopPropagation(); setToDelete(g) }}
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
        <ExportModal cols={cols} items={sorted} getCell={(g, id) => getCellExport(g, id)}
          labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')}
          sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />
      )}

      {toDelete && (
        <ConfirmModal title={t('del_title')} message={t('del_confirm')}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
      )}

      {editing && (
        <ClientsGroupFormModal t={t} initial={editing === 'new' ? null : editing}
          onClose={() => setEditing(null)}
          onSaved={() => { setEditing(null); setTick((x) => x + 1) }} />
      )}
    </div>
  )
}

// ── FORM MODAL (nom + statut, 2 champs — pas de page/route dédiée) ──────────────
function ClientsGroupFormModal({ t, initial, onClose, onSaved }: {
  t: (key: string, vars?: Record<string, string | number>) => string
  initial: ClientsGroupItem | null
  onClose: () => void
  onSaved: () => void
}) {
  const isEdit = initial !== null
  const [name, setName] = useState(initial?.name ?? '')
  const [status, setStatus] = useState(initial ? initial.status === 1 : true)
  const [saving, setSaving] = useState(false)
  const [errName, setErrName] = useState(false)
  const [formError, setFormError] = useState(false)

  async function submit() {
    if (!name.trim()) { setErrName(true); setFormError(true); return }
    setFormError(false)
    setSaving(true)
    try {
      await saveClientsGroup({ id: initial?.id, name: name.trim(), status })
      notify('ok', t('title'), t('saved'))
      onSaved()
    } catch (e) {
      notify('ko', t('title'), e instanceof Error ? e.message : 'Error')
    } finally {
      setSaving(false)
    }
  }

  return (
    <div onClick={(e) => { if (e.target === e.currentTarget) onClose() }}
      style={{ position: 'fixed', inset: 0, zIndex: 50, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
      <div style={{ ...card, padding: 24, width: '100%', maxWidth: 420 }}>
        <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 20px' }}>
          {isEdit ? t('modal_edit_title') : t('modal_new_title')}
        </h3>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
          {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14 }}>{t('err_required_fields')}</div>}
          <div>
            <label style={label}>{t('field_name')} *</label>
            <input style={inputCss} value={name} autoFocus
              onChange={(e) => { setName(e.target.value); setErrName(false) }}
              onKeyDown={(e) => { if (e.key === 'Enter') submit() }} />
            {errName && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_name_required')}</p>}
          </div>
          <div>
            <label style={label}>{t('status_label')}</label>
            <StatusToggle value={status} onChange={setStatus} t={t} />
          </div>
        </div>
        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 24 }}>
          <button style={btnGhost} onClick={onClose} disabled={saving}>{t('cancel')}</button>
          <button style={btnPrimary} onClick={submit} disabled={saving}>{saving ? '…' : t('save')}</button>
        </div>
      </div>
    </div>
  )
}
