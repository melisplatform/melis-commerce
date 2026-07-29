import { Fragment, useEffect, useMemo, useRef, useState, type ReactNode } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  deleteAccount, fetchAccountById, fetchAccountOptions, fetchAccounts, fetchAccountStats,
  saveAccount, fetchCompany, saveCompany, fetchAccountAddresses, saveAccountAddresses,
  fetchAccountContacts, linkAccountContact, testImportAccounts, importAccounts, ACCOUNT_IMPORT_TEMPLATE_URL,
  type AccountItem, type AccountStats, type CompanyData, type AccountAddress, type AccountContact, type AccountNameMode,
} from './api'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT, fmtDate, type T } from '../../shared/i18n'
import { card, inputCss, btnPrimary, btnGhost, iconBtn, th, td, label, hint } from '../../shared/styles'
import { PencilIcon, TrashIcon, PlusIcon, GripIcon, FileDownIcon, FileUpIcon, BuildingKpiIcon, ResetIcon } from '../../shared/icons'
import { StatusBadge, Kpi, ViewModeToggle, LegacyFrame, ConfirmModal, TagsInput } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { useCaps } from '../../shared/useCaps'
import { ColManager } from '../../shared/ColManager'
import { ExportModal } from '../../shared/ExportModal'
import { makeColStore, visibleCols, effectiveCols, type ColDef } from '../../shared/columns'
import { ExpandToggle, HiddenColsRow } from '../../shared/ExpandableRow'
import { useIsNarrow } from '../../shared/useIsNarrow'
import { openSubTab, closeSubTab, updateSubLabel } from '../../shared/subtabs'
import type { Option as AccountOptionT } from '../../shared/api'
import { Tabs, type TabDef } from '../../shared/Tabs'
import { TagIcon, UsersIcon, BuildingIcon, MapPinIcon, CartIcon, PaperclipIcon, UserIcon, GlobeIcon, ToggleRightIcon } from '../../shared/icons'
import { CompanyTab, ContactsTab, AddressesTab, OrdersTab, FilesTab, EMPTY_CO } from './AccountTabs'

/* Brique « Comptes clients » (MelisCommerce) — full React, montée à /melis-commerce/clients-list
 * (et /:id pour le formulaire, en sous-onglet in-tool). Toggle New/Old → iframe legacy. */
const TOOL_MELIS_KEY = 'meliscommerce_clients_list_page'
const COL_ORDER = ['id', 'name', 'status', 'group', 'country', 'created'] as const
const COL_LABEL: Record<string, string> = { id: 'col_id', name: 'col_name', status: 'col_status', group: 'col_group', country: 'col_country', created: 'col_created' }
const cols$ = makeColStore('melis-account-cols-v1', COL_ORDER)
const ESSENTIAL_COLS = new Set(['name'])

const listCache = makeCache<{
  items: AccountItem[]; stats: AccountStats | null
  search: string; searchInput: string; status: number | null; filterGroupId: number; sortCol: string; sortAsc: boolean; mode: 'react' | 'old'
}>()

function getCellExport(a: AccountItem, id: string, t: (k: string) => string): string | number {
  switch (id) {
    case 'id': return a.id
    case 'name': return a.name || ''
    case 'status': return a.status === 1 ? t('status_active') : t('status_inactive')
    case 'group': return a.groupName || ''
    case 'country': return a.countryName || ''
    case 'created': return a.dateCreation || ''
    default: return ''
  }
}

// ── Import CSV (modale « Importer » de la liste) ────────────────────────────────
function ImportAccountsModal({ t, onClose, onImported }: { t: T; onClose: () => void; onImported: () => void }) {
  const [file, setFile] = useState<File | null>(null)
  const [tested, setTested] = useState(false)
  const [busy, setBusy] = useState<'test' | 'import' | null>(null)
  const [errors, setErrors] = useState<string[]>([])
  const [message, setMessage] = useState<string | null>(null)

  function pickFile(f: File | null) {
    setFile(f); setTested(false); setErrors([]); setMessage(null)
  }

  async function runTest() {
    if (!file) { setErrors([t('imp_err_file')]); return }
    if (!file.name.toLowerCase().endsWith('.csv')) { setErrors([t('imp_err_not_csv')]); return }
    setBusy('test'); setErrors([]); setMessage(null)
    try {
      const r = await testImportAccounts(file)
      setTested(!!r.valid)
      setErrors(r.errors ?? [])
      setMessage(r.valid ? t('imp_test_ok') : t('imp_test_ko'))
    } catch (e) {
      setTested(false)
      setMessage(e instanceof Error ? e.message : t('imp_test_ko'))
    } finally { setBusy(null) }
  }

  async function runImport() {
    if (!file) return
    setBusy('import'); setErrors([]); setMessage(null)
    try {
      const r = await importAccounts(file)
      if (r.imported) {
        notify('ok', t('imp_title'), t('imp_import_ok'))
        onImported()
      } else {
        setTested(false)
        setErrors(r.errors ?? [])
        setMessage(t('imp_import_ko'))
      }
    } catch (e) {
      setTested(false)
      setMessage(e instanceof Error ? e.message : t('imp_import_ko'))
    } finally { setBusy(null) }
  }

  return (
    <div style={{ position: 'fixed', inset: 0, zIndex: 50, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
      <div style={{ ...card, padding: 24, width: '100%', maxWidth: 480, maxHeight: '85vh', overflow: 'auto' }}>
        <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 12px', display: 'flex', alignItems: 'center', gap: 8 }}><FileUpIcon />{t('imp_title')}</h3>

        <p style={{ fontSize: 14, margin: '0 0 8px' }}>{t('imp_desc1')}</p>
        <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: '0 0 4px' }}>{t('imp_desc2')}</p>
        <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: '0 0 4px' }}>{t('imp_desc3')}</p>
        <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: '0 0 12px' }}>{t('imp_sections')}</p>
        <p style={{ fontSize: 13, margin: '0 0 12px' }}>{t('imp_required')}</p>

        <a href={ACCOUNT_IMPORT_TEMPLATE_URL} style={{ fontSize: 13, color: 'var(--color-primary)', fontWeight: 500, textDecoration: 'underline' }}>
          {t('imp_download')}
        </a>

        <div style={{ marginTop: 16 }}>
          <label style={label}>{t('imp_choose_file')}</label>
          <input type="file" accept=".csv" style={inputCss}
            onChange={(e) => pickFile(e.target.files?.[0] ?? null)} />
        </div>

        {message && (
          <p style={{ fontSize: 13, marginTop: 12, color: errors.length ? '#dc2626' : '#16a34a' }}>{message}</p>
        )}
        {errors.length > 0 && (
          <ul style={{ margin: '8px 0 0', paddingLeft: 18, fontSize: 12, color: '#dc2626', display: 'flex', flexDirection: 'column', gap: 2 }}>
            {errors.map((e, i) => <li key={i}>{e}</li>)}
          </ul>
        )}

        <div style={{ display: 'flex', justifyContent: 'space-between', gap: 8, marginTop: 24 }}>
          <button style={btnGhost} onClick={onClose}>{t('imp_close')}</button>
          <div style={{ display: 'flex', gap: 8 }}>
            <button style={btnGhost} disabled={!file || busy !== null} onClick={runTest}>
              {busy === 'test' ? '…' : t('imp_test')}
            </button>
            <button style={btnPrimary} disabled={!tested || busy !== null} onClick={runImport}>
              {busy === 'import' ? '…' : t('imp_import')}
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}

export default function AccountPage() {
  const { id } = useParams()
  const location = useLocation()
  const base = id ? location.pathname.slice(0, location.pathname.length - id.length - 1) : location.pathname
  if (id) return <AccountForm id={id} base={base} />
  return <AccountList base={base} />
}

// ── Liste ─────────────────────────────────────────────────────────────────────
function AccountList({ base }: { base: string }) {
  const t = makeT(DICT)
  const navigate = useNavigate()
  const { can } = useCaps(TOOL_MELIS_KEY)
  const narrow = useIsNarrow()
  const [mode, setMode] = useState<'react' | 'old'>(listCache.get()?.mode ?? 'react')
  // Non-persistent bricks get fully unmounted when the tab isn't active and rebuilt from
  // scratch when revisited — only `mode` survives that via listCache. If `oldLoaded`
  // reinitialized to false regardless, coming back to a tab left in "Old" view rendered
  // neither the (gated-by-oldLoaded) LegacyFrame nor the (gated-by-mode==='react') React
  // view: a blank tab. Derive the initial value from `mode` so a remount picks the legacy
  // iframe back up immediately, same as `mode` itself does.
  const [oldLoaded, setOldLoaded] = useState(() => (listCache.get()?.mode ?? 'react') === 'old')
  const [items, setItems] = useState<AccountItem[]>(listCache.get()?.items ?? [])
  const [stats, setStats] = useState<AccountStats | null>(listCache.get()?.stats ?? null)
  const [loading, setLoading] = useState(false)
  const [searchInput, setSearchInput] = useState(listCache.get()?.searchInput ?? '')
  const [search, setSearch] = useState(listCache.get()?.search ?? '')
  const [status, setStatus] = useState<number | null>(listCache.get()?.status ?? null)
  const [filterGroupId, setFilterGroupId] = useState(listCache.get()?.filterGroupId ?? 0)
  const [groups, setGroups] = useState<{ id: number; name: string }[]>([])
  const [sortCol, setSortCol] = useState<string>(listCache.get()?.sortCol ?? 'id')
  const [sortAsc, setSortAsc] = useState(listCache.get()?.sortAsc ?? false)
  const [toDelete, setToDelete] = useState<AccountItem | null>(null)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)
  const [showImport, setShowImport] = useState(false)
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  function toggleExpand(id: number) {
    setExpanded((prev) => { const next = new Set(prev); next.has(id) ? next.delete(id) : next.add(id); return next })
  }

  const cacheRef = useRef({ items, stats, search, searchInput, status, filterGroupId, sortCol, sortAsc, mode })
  useEffect(() => { cacheRef.current = { items, stats, search, searchInput, status, filterGroupId, sortCol, sortAsc, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchAccountStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => { fetchAccountOptions().then((o) => setGroups(o.groups)).catch(() => null) }, [])
  useEffect(() => {
    setLoading(true)
    fetchAccounts({ search, status, groupId: filterGroupId || null }).then((r) => setItems(r.items)).catch(() => null).finally(() => setLoading(false))
  }, [search, status, filterGroupId, tick])

  const sortVal = (a: AccountItem): string | number => {
    switch (sortCol) {
      case 'name': return a.name; case 'status': return a.status
      case 'group': return a.groupName; case 'country': return a.countryName
      case 'created': return a.dateCreation ?? ''; default: return a.id
    }
  }
  const sorted = useMemo(() => [...items].sort((a, b) => {
    const va = sortVal(a), vb = sortVal(b)
    const cmp = typeof va === 'number' && typeof vb === 'number' ? va - vb : String(va).localeCompare(String(vb))
    return sortAsc ? cmp : -cmp
  }), [items, sortCol, sortAsc])

  function toggleSort(id: string) { if (sortCol === id) setSortAsc((v) => !v); else { setSortCol(id); setSortAsc(true) } }
  // Réinitialiser les filtres : recherche + statut + groupe + tri par défaut (id desc), puis refetch.
  // On vide `items` : sinon les lignes restent affichées pendant le refetch et le clic paraît sans effet.
  function resetFilters() {
    setSearchInput(''); setSearch('')
    setStatus(null)
    setFilterGroupId(0)
    setSortCol('id'); setSortAsc(false)
    setItems([])
    setTick((x) => x + 1)
  }
  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteAccount(toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) }
  }
  const FILTERS: { k: string; v: number | null; dot?: string }[] = [{ k: 'f_all', v: null }, { k: 'f_active', v: 1, dot: '#10b981' }, { k: 'f_inactive', v: 0, dot: '#ef4444' }]

  const eCols = effectiveCols(cols, ESSENTIAL_COLS, narrow)
  const visible = visibleCols(eCols)
  const hasHidden = eCols.some((c) => !c.visible)
  const totalCols = visible.length + 1 + (hasHidden ? 1 : 0)
  function cellContent(a: AccountItem, id: string): ReactNode {
    switch (id) {
      case 'id': return a.id
      case 'name': return <span style={{ fontWeight: 500 }}>{a.name || '—'}</span>
      case 'status': return <StatusBadge active={a.status === 1} t={t} />
      case 'group': return a.groupName || '—'
      case 'country': return a.countryName || '—'
      case 'created': return <span style={{ color: 'var(--color-muted-foreground)' }}>{fmtDate(a.dateCreation)}</span>
      default: return null
    }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box', ...(mode === 'old' ? { height: '100%', overflow: 'hidden' } : {}) }}>
      <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 16 }}>
        <div>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('title')}</h1>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('subtitle')}</p>
        </div>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
          <div style={{ display: 'flex', gap: 8, alignItems: 'center', justifyContent: 'flex-end' }}>
            <ViewModeToggle mode={mode} onReact={() => setMode('react')} onOld={() => { setMode('old'); setOldLoaded(true) }} />
            <button style={{ ...btnGhost, width: 36, padding: 0, justifyContent: 'center', flexShrink: 0 }} onClick={() => setTick((x) => x + 1)} title={t('refresh')}>↻</button>
          </div>
          {can('create') && <button style={{ ...btnPrimary, width: '100%', justifyContent: 'center', whiteSpace: 'normal', textAlign: 'center', height: 'auto', minHeight: 36, padding: '8px 14px' }} onClick={() => navigate(`${base}/new`)}><PlusIcon />{t('new')}</button>}
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 20, flex: 1, minHeight: 0 }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(130px, 1fr))', gap: 12 }}>
          <Kpi label={t('kpi_total')}    value={stats?.total    ?? null} icon={<BuildingKpiIcon />} iconBg="rgba(59,130,246,0.1)"  iconColor="#3b82f6" />
          <Kpi label={t('kpi_active')}   value={stats?.active   ?? null} icon={<BuildingKpiIcon />} iconBg="rgba(16,185,129,0.1)"  iconColor="#10b981" />
          <Kpi label={t('kpi_inactive')} value={stats?.inactive ?? null} icon={<BuildingKpiIcon />} iconBg="rgba(239,68,68,0.1)"   iconColor="#ef4444" />
        </div>
        <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8 }}>
          <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 180, maxWidth: narrow ? undefined : 384, flexBasis: narrow ? '100%' : undefined }} value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)} onKeyDown={(e) => e.key === 'Enter' && setSearch(searchInput.trim())} placeholder={t('search')} />
          <div style={{ display: 'flex', alignItems: 'center', gap: 4, padding: 4, borderRadius: 8, border: '1px solid var(--color-border)', background: 'var(--color-muted,rgba(0,0,0,.04))', width: narrow ? '100%' : undefined, flexBasis: narrow ? '100%' : undefined }}>
            {FILTERS.map((f) => (
              <button key={f.k} onClick={() => setStatus(f.v)} style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6, padding: '5px 10px', borderRadius: 6, border: 0, cursor: 'pointer', fontSize: 12, fontWeight: 500, flex: narrow ? 1 : undefined, background: status === f.v ? 'var(--color-card)' : 'transparent', color: status === f.v ? 'var(--color-foreground)' : 'var(--color-muted-foreground)', boxShadow: status === f.v ? '0 1px 2px rgba(0,0,0,.08)' : 'none' }}>
                {f.dot && <span style={{ width: 6, height: 6, borderRadius: '50%', background: f.dot, flexShrink: 0 }} />}
                {t(f.k)}
              </button>
            ))}
          </div>
          <select style={{ ...inputCss, height: 36, width: narrow ? '100%' : 'auto', minWidth: 150, flexBasis: narrow ? '100%' : undefined }} value={filterGroupId} onChange={(e) => setFilterGroupId(Number(e.target.value))}>
            <option value={0}>{t('filter_group')}</option>
            {groups.map((g) => <option key={g.id} value={g.id}>{g.name}</option>)}
          </select>
          <div style={{ marginLeft: narrow ? 0 : 'auto', display: 'flex', flexWrap: narrow ? 'wrap' : 'nowrap', gap: 8, flexBasis: narrow ? '100%' : undefined }}>
            <button style={{ ...btnGhost, height: narrow ? 'auto' : 36, minHeight: narrow ? 36 : undefined, flex: narrow ? '1 1 calc(50% - 4px)' : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} onClick={resetFilters}><ResetIcon />{t('reset_filters')}</button>
            <div style={{ position: 'relative', flex: narrow ? '1 1 calc(50% - 4px)' : undefined, minWidth: narrow ? 0 : undefined, display: narrow ? 'flex' : undefined }}>
              <button style={{ ...btnGhost, height: narrow ? '100%' : 36, minHeight: narrow ? 36 : undefined, width: narrow ? '100%' : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} onClick={() => setShowCols((v) => !v)}><GripIcon />{t('columns')}</button>
              {showCols && <ColManager cols={cols} labelFor={(id) => t(COL_LABEL[id])} onChange={setCols} onClose={() => setShowCols(false)} save={cols$.save} defaults={cols$.DEFAULT} t={t} />}
            </div>
            {can('export') && <button style={{ ...btnGhost, height: narrow ? 'auto' : 36, minHeight: narrow ? 36 : undefined, flex: narrow ? '1 1 calc(50% - 4px)' : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} onClick={() => setShowExport(true)}><FileDownIcon />{t('export')}</button>}
            {can('create') && <button style={{ ...btnGhost, height: narrow ? 'auto' : 36, minHeight: narrow ? 36 : undefined, flex: narrow ? '1 1 calc(50% - 4px)' : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap', textAlign: narrow ? 'center' : undefined, padding: narrow ? '6px 8px' : '0 12px' }} onClick={() => setShowImport(true)}><FileUpIcon />{t('imp_btn')}</button>}
          </div>
        </div>

        <div style={{ ...card, overflowX: 'auto' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: narrow ? undefined : 680 }}>
            <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
              <tr>
                {hasHidden && <th style={{ ...th, width: 32 }} />}
                {visible.map(({ id }) => (
                  <th key={id} style={{ ...th, cursor: 'pointer', ...(id === 'id' ? { width: 70 } : {}) }} onClick={() => toggleSort(id)}>
                    {t(COL_LABEL[id])}{sortCol === id ? ` ${sortAsc ? '↑' : '↓'}` : ''}
                  </th>
                ))}
                <th style={{ ...th, width: 80, position: 'sticky', right: 0, background: 'var(--color-muted,rgba(0,0,0,.03))' }} />
              </tr>
            </thead>
            <tbody>
              {sorted.length === 0 && !loading ? (
                <tr><td style={{ ...td, textAlign: 'center', color: 'var(--color-muted-foreground)', padding: '40px 16px' }} colSpan={totalCols}>{t('empty')}</td></tr>
              ) : sorted.map((a) => (
                <Fragment key={a.id}>
                  <tr>
                    {hasHidden && (
                      <td style={td}>
                        <ExpandToggle expanded={expanded.has(a.id)} onClick={() => toggleExpand(a.id)} />
                      </td>
                    )}
                    {visible.map(({ id }) => (
                      <td key={id} style={{ ...td, ...(id === 'id' ? { color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' } : {}) }}>
                        {cellContent(a, id)}
                      </td>
                    ))}
                    <td style={{ ...td, position: 'sticky', right: 0, background: 'var(--color-card,#fff)' }}>
                      <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 4 }}>
                        {can('edit') && <button style={iconBtn} title={t('edit')} onClick={() => navigate(`${base}/${a.id}`)}><PencilIcon /></button>}
                        {can('delete') && <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => setToDelete(a)}><TrashIcon /></button>}
                      </div>
                    </td>
                  </tr>
                  {expanded.has(a.id) && (
                    <HiddenColsRow cols={eCols} labelFor={(id) => t(COL_LABEL[id])} renderValue={(id) => cellContent(a, id)} colSpan={totalCols} narrow={narrow} />
                  )}
                </Fragment>
              ))}
            </tbody>
          </table>
          <div style={{ padding: '10px 16px', textAlign: 'center', fontSize: 12, color: 'var(--color-muted-foreground)' }}>
            {loading ? t('loading') : t('count', { n: items.length })}
          </div>
        </div>
      </div>

      {showExport && <ExportModal cols={cols} items={items} getCell={(a, id) => getCellExport(a, id, t)} labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')} sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />}

      {showImport && (
        <ImportAccountsModal t={t} onClose={() => setShowImport(false)}
          onImported={() => { setShowImport(false); setTick((x) => x + 1) }} />
      )}

      {toDelete && (
        <ConfirmModal title={t('del_title')} message={t('del_confirm', { u: toDelete.name })}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
      )}
    </div>
  )
}

// ── Formulaire (sous-onglet in-tool) ────────────────────────────────────────────
function AccountForm({ id, base }: { id: string; base: string }) {
  const t = makeT(DICT)
  const narrow = useIsNarrow()
  const navigate = useNavigate()
  const isEdit = id !== 'new'
  const accountId = isEdit ? parseInt(id) : null
  const subTabPath = `${base}/${id}`

  const [groups, setGroups] = useState<AccountOptionT[]>([])
  const [countries, setCountries] = useState<AccountOptionT[]>([])
  const [accountNameMode, setAccountNameMode] = useState<AccountNameMode>('manual_input')
  const [name, setName] = useState('')
  const [active, setActive] = useState(true)
  const [groupId, setGroupId] = useState<number>(0)
  const [countryId, setCountryId] = useState<number>(0)
  const [tags, setTags] = useState('')
  const [loading, setLoading] = useState(false)
  const [saving, setSaving] = useState(false)
  const [nameError, setNameError] = useState('')
  const [countryError, setCountryError] = useState('')
  const [formError, setFormError] = useState(false)
  const [tab, setTab] = useState('properties')
  const [visitedTabs, setVisitedTabs] = useState(new Set<string>())
  // Données de l'onglet Société, remontées ici pour être sauvées par le bouton du HAUT (un seul Save).
  const [company, setCompany] = useState<Omit<CompanyData, 'id'>>(EMPTY_CO)
  const [companyLoaded, setCompanyLoaded] = useState(false)
  const [addresses, setAddresses] = useState<AccountAddress[]>([])
  const [addressesLoaded, setAddressesLoaded] = useState(false)
  const [mainContact, setMainContact] = useState<AccountContact | null>(null)
  // contact_name mode, NEW account only: the account's name is derived from its default contact, and
  // the Contacts tab is disabled until the account has an id — so without this, an account could be
  // created with no contact and no way back in. Picked here (before save) and sent alongside the
  // create payload so the account + its default-contact link are created atomically (mirrors legacy's
  // hard block at save time, see MelisComReactApiAccountController::saveAction()).
  const [pendingContacts, setPendingContacts] = useState<AccountContact[]>([])
  const { can, loaded: capsLoaded } = useCaps(TOOL_MELIS_KEY)

  // Onglets masqués selon les droits (l'accès à un onglet = capacité de même clé : properties/contacts/…).
  const TABS: TabDef[] = ([
    { key: 'properties', label: t('tab_properties'), icon: <TagIcon /> },
    // contact_name mode: kept open pre-save too — the account's name is derived from its default
    // contact, so it must be pickable BEFORE the account exists (staged locally, see pendingContacts).
    { key: 'contacts',   label: t('tab_contacts'),   icon: <UsersIcon />,     disabled: !isEdit && accountNameMode !== 'contact_name' },
    { key: 'company',    label: t('tab_company'),    icon: <BuildingIcon /> },
    { key: 'addresses',  label: t('tab_addresses'),  icon: <MapPinIcon /> },
    { key: 'orders',     label: t('tab_orders'),     icon: <CartIcon />,      disabled: !isEdit },
    { key: 'files',      label: t('tab_files'),      icon: <PaperclipIcon />, disabled: !isEdit },
  ] as TabDef[]).filter((tb) => can(tb.key))

  // Si l'onglet actif vient d'être retiré par les droits, basculer sur le premier autorisé.
  useEffect(() => {
    if (capsLoaded && TABS.length && !TABS.some((tb) => tb.key === tab)) setTab(TABS[0].key)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [capsLoaded])

  function handleTabChange(newTab: string) {
    setVisitedTabs((s) => { const n = new Set(s); n.add(newTab); return n })
    setTab(newTab)
  }

  useEffect(() => { fetchAccountOptions().then((o) => { setGroups(o.groups); setCountries(o.countries); setAccountNameMode(o.accountNameMode) }).catch(() => null) }, [])
  // Reset derived/lazy state when switching accounts so stale data from a previous account isn't saved.
  useEffect(() => {
    setCompany(EMPTY_CO); setCompanyLoaded(false)
    setAddresses([]); setAddressesLoaded(false)
    setNameError(''); setCountryError('')
    setMainContact(null)
  }, [accountId])
  useEffect(() => {
    if (!accountId) return
    // Garde de montage : un fetch en vol au moment où l'onglet se ferme n'est pas annulé par le
    // démontage — sans ce garde, son .catch() pouvait naviguer vers `base` APRÈS coup et écraser
    // une navigation par ailleurs légitime (ex. fermeture d'onglet → retour Dashboard).
    let cancelled = false
    setLoading(true)
    fetchAccountById(accountId)
      .then((a) => { if (cancelled) return; setName(a.name); setActive(a.status === 1); setGroupId(a.groupId); setCountryId(a.countryId); setTags(a.tags) })
      .catch(() => { if (!cancelled) navigate(base) }).finally(() => { if (!cancelled) setLoading(false) })
    return () => { cancelled = true }
  }, [accountId, navigate, base])
  useEffect(() => {
    if (!accountId) return
    fetchCompany(accountId).then((c) => { const { id: _id, ...rest } = c; setCompany(rest); setCompanyLoaded(true) }).catch(() => null)
    fetchAccountAddresses(accountId).then((r) => { setAddresses(r.items); setAddressesLoaded(true) }).catch(() => null)
    fetchAccountContacts(accountId).then((r) => { setMainContact(r.items.find((c) => c.isMain === 1) ?? null) }).catch(() => null)
  }, [accountId])

  useEffect(() => {
    if (isEdit) closeSubTab(base, `${base}/new`)
    openSubTab(base, { id: subTabPath, label: isEdit ? t('loading') : t('new'), path: subTabPath })
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])
  useEffect(() => {
    if (isEdit) updateSubLabel(base, subTabPath, name || `#${accountId}`)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [name])

  async function submit() {
    let firstError = ''
    const fail = (msg: string) => { firstError ||= msg; return msg }
    if (accountNameMode === 'manual_input') {
      if (!name.trim()) setNameError(fail(t('err_name'))); else setNameError('')
    } else {
      setNameError('')
      if (accountNameMode === 'company_name' && !company.name.trim()) { fail(t('err_company_name_required')); setTab('company') }
      if (!isEdit && accountNameMode === 'contact_name' && !pendingContacts.length) { fail(t('err_contact_required')); setTab('contacts') }
    }
    if (!countryId) setCountryError(fail(t('err_country'))); else setCountryError('')
    setFormError(!!firstError)
    if (firstError) return
    setSaving(true)
    // The pending contact marked isMain (or the first one) becomes the account's default contact,
    // created ATOMICALLY with it server-side (saveAction() requires + links it in one request when
    // accountNameMode is contact_name — never a nameless/contact-less account in between). Any
    // additional staged contacts are linked individually right after, same as the live Contacts tab.
    const defaultContact = pendingContacts.find((c) => c.isMain) ?? pendingContacts[0]
    try {
      const r = await saveAccount({
        id: accountId, name: name.trim(), status: active, groupId, countryId, tags: tags.trim(),
        ...(!isEdit && accountNameMode === 'contact_name' && defaultContact ? { contactId: defaultContact.id } : {}),
      })
      const savedId = accountId ?? r?.id
      if (savedId) {
        // company_name mode : la société pilote le nom du compte affiché partout → toujours sauvée,
        // même si l'onglet Société n'a jamais été ouvert (sinon la validation serveur ne se déclenche jamais).
        if (companyLoaded || visitedTabs.has('company') || accountNameMode === 'company_name') { try { await saveCompany(savedId, company) } catch { /* */ } }
        if (addressesLoaded || visitedTabs.has('addresses')) { try { await saveAccountAddresses(savedId, addresses) } catch { /* */ } }
        if (!isEdit && pendingContacts.length) {
          const rest = pendingContacts.filter((c) => c.id !== defaultContact?.id)
          await Promise.all(rest.map((c) => linkAccountContact(savedId, c.id).catch(() => null)))
        }
      }
      notify('ok', t('title'), t('saved'))
      if (!isEdit) { closeSubTab(base, `${base}/new`); setTimeout(() => navigate(base), 500) }
    } catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : t('err_save')) } finally { setSaving(false) }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box' }}>
      <div style={{ display: 'flex', alignItems: narrow ? 'flex-start' : 'center', justifyContent: 'space-between', gap: 16, flexWrap: narrow ? 'wrap' : 'nowrap' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><BuildingKpiIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{isEdit ? t('edit_title') : t('new_title')}</h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexBasis: narrow ? '100%' : undefined }}>
          <button style={{ ...btnPrimary, flex: narrow ? 1 : undefined, justifyContent: narrow ? 'center' : undefined }} onClick={submit} disabled={saving || loading}>{saving ? '…' : t('save')}</button>
        </div>
      </div>

      <Tabs tabs={TABS} active={tab} onChange={handleTabChange} />

      {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, marginBottom: 16 }}>{t('err_required_fields')}</div>}

      {tab === 'company' ? (
        <CompanyTab company={company} onChange={setCompany} loading={!companyLoaded && !!accountId} t={t} nameRequired={accountNameMode === 'company_name'} />
      ) : tab === 'addresses' ? (
        <AddressesTab addresses={addresses} onChange={setAddresses} t={t} />
      ) : tab === 'contacts' && (accountId || accountNameMode === 'contact_name') ? (
        <ContactsTab accountId={accountId} pending={pendingContacts} onPendingChange={setPendingContacts} t={t} can={can} />
      ) : tab === 'orders' && accountId ? (
        <OrdersTab accountId={accountId} t={t} />
      ) : tab === 'files' && accountId ? (
        <FilesTab accountId={accountId} t={t} can={can} />
      ) : tab === 'properties' ? (
        loading ? (
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', minHeight: '40vh', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
        ) : (
          <div>
          <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr minmax(260px,320px)', gap: 24, alignItems: 'start' }}>
            <div style={{ ...card, padding: 32, display: 'flex', flexDirection: 'column', gap: 18 }}>
              <h3 style={{ fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: 0 }}>{t('f_identity')}</h3>
              <div>
                <label style={{ ...label, display: 'flex', alignItems: 'center', gap: 5 }}>
                  <UserIcon /><span>{t('f_name')} {accountNameMode === 'manual_input' && <span style={{ color: '#ef4444' }}>*</span>}</span>
                </label>
                <input style={{ ...inputCss, ...(accountNameMode === 'manual_input' ? {} : { color: 'var(--color-muted-foreground)', background: 'var(--color-muted)' }) }}
                  value={name} readOnly={accountNameMode !== 'manual_input'}
                  onChange={(e) => { if (accountNameMode !== 'manual_input') return; setName(e.target.value); if (nameError && e.target.value.trim()) setNameError('') }}
                  placeholder={t('f_name_ph')} autoComplete="off" />
                {nameError && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{nameError}</p>}
                {accountNameMode === 'company_name' && <p style={hint}>{t('f_name_auto_company')}</p>}
                {accountNameMode === 'contact_name' && <p style={hint}>{t('f_name_auto_contact')}</p>}
              </div>
              <div>
                <label style={{ ...label, display: 'flex', alignItems: 'center', gap: 5 }}>
                  <UsersIcon /><span>{t('f_group')}</span>
                </label>
                <select style={inputCss} value={groupId} onChange={(e) => setGroupId(Number(e.target.value))}>
                  <option value={0}>{t('f_group_none')}</option>
                  {groups.map((g) => <option key={g.id} value={g.id}>{g.name}</option>)}
                </select>
              </div>
              <div>
                <label style={{ ...label, display: 'flex', alignItems: 'center', gap: 5 }}>
                  <GlobeIcon /><span>{t('f_country')} <span style={{ color: '#ef4444' }}>*</span></span>
                </label>
                <select style={inputCss} value={countryId} onChange={(e) => { setCountryId(Number(e.target.value)); if (countryError) setCountryError('') }}>
                  <option value={0}>{t('f_country_ph')}</option>
                  {countries.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
                </select>
                {countryError && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{countryError}</p>}
              </div>
              <div>
                <label style={{ ...label, display: 'flex', alignItems: 'center', gap: 5 }}>
                  <TagIcon /><span>{t('f_tags')}</span>
                </label>
                <TagsInput value={tags} onChange={setTags} placeholder={t('f_tags_ph')} />
              </div>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
            <div style={{ ...card, padding: 16 }}>
              <h3 style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 12px' }}>
                <ToggleRightIcon />{t('col_status')}
              </h3>
              <button type="button" onClick={() => setActive((v) => !v)} style={{ display: 'flex', alignItems: 'center', gap: 10, background: 'none', border: 0, padding: 0, cursor: 'pointer' }}>
                <div style={{ position: 'relative', width: 36, height: 20, borderRadius: 999, background: active ? '#22c55e' : 'var(--color-muted-foreground)', transition: 'background .15s', flexShrink: 0 }}>
                  <span style={{ position: 'absolute', top: 2, left: active ? 18 : 2, width: 16, height: 16, borderRadius: 999, background: '#fff', transition: 'left .15s', boxShadow: '0 1px 2px rgba(0,0,0,.3)' }} />
                </div>
                <span style={{ fontSize: 14, fontWeight: 500, color: active ? '#16a34a' : 'var(--color-muted-foreground)' }}>
                  {active ? t('status_active') : t('status_inactive')}
                </span>
              </button>
            </div>
            <div style={{ ...card, padding: 16 }}>
              <h3 style={{ fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 16px' }}>{t('c_def_contact')}</h3>
              {(() => {
                // Pre-save (contact_name mode), the tab's staged pick IS the future default contact —
                // show that here too instead of `mainContact` (only ever populated for existing accounts).
                const shown = isEdit ? mainContact : (pendingContacts.find((c) => c.isMain) ?? pendingContacts[0] ?? null)
                return shown ? (
                  <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                    {shown.civilityName && (
                      <div style={{ display: 'flex', gap: 8, fontSize: 13 }}>
                        <span style={{ color: 'var(--color-muted-foreground)', minWidth: 64 }}>{t('c_civility')}</span>
                        <span style={{ fontWeight: 500 }}>{shown.civilityName}</span>
                      </div>
                    )}
                    {(shown.firstname || shown.name) && (
                      <div style={{ display: 'flex', gap: 8, fontSize: 13 }}>
                        <span style={{ color: 'var(--color-muted-foreground)', minWidth: 64 }}>{t('c_name')}</span>
                        <span style={{ fontWeight: 500 }}>{`${shown.firstname} ${shown.name}`.trim()}</span>
                      </div>
                    )}
                    {shown.email && (
                      <div style={{ display: 'flex', gap: 8, fontSize: 13 }}>
                        <span style={{ color: 'var(--color-muted-foreground)', minWidth: 64 }}>{t('c_email')}</span>
                        <span>{shown.email}</span>
                      </div>
                    )}
                  </div>
                ) : (
                  <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: 0 }}>
                    {!isEdit && accountNameMode === 'contact_name' ? t('c_default_contact_hint') : accountId ? t('c_empty') : t('save_first')}
                  </p>
                )
              })()}
              {formError && !isEdit && accountNameMode === 'contact_name' && !pendingContacts.length && (
                <p style={{ margin: '8px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_contact_required')}</p>
              )}
            </div>
            </div>
          </div>
          </div>
        )
      ) : null}
    </div>
  )
}
