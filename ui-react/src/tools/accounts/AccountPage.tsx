import { useEffect, useMemo, useState } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  deleteAccount, fetchAccountById, fetchAccountOptions, fetchAccounts, fetchAccountStats,
  saveAccount, fetchCompany, saveCompany, fetchAccountAddresses, saveAccountAddresses,
  fetchAccountContacts,
  type AccountItem, type AccountStats, type CompanyData, type AccountAddress, type AccountContact,
} from './api'
import { DICT } from './dict'
import { makeT, fmtDate } from '../../shared/i18n'
import { card, inputCss, btnPrimary, btnGhost, iconBtn, th, td, label, hint } from '../../shared/styles'
import { PencilIcon, TrashIcon, PlusIcon, GripIcon, FileDownIcon, BuildingKpiIcon, ArrowLeftIcon } from '../../shared/icons'
import { StatusBadge, Kpi, ViewModeToggle, LegacyFrame, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { ColManager } from '../../shared/ColManager'
import { ExportModal } from '../../shared/ExportModal'
import { makeColStore, visibleCols, type ColDef } from '../../shared/columns'
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
  const [mode, setMode] = useState<'react' | 'old'>('react')
  const [oldLoaded, setOldLoaded] = useState(false)
  const [items, setItems] = useState<AccountItem[]>([])
  const [stats, setStats] = useState<AccountStats | null>(null)
  const [loading, setLoading] = useState(false)
  const [searchInput, setSearchInput] = useState('')
  const [search, setSearch] = useState('')
  const [status, setStatus] = useState<number | null>(null)
  const [filterGroupId, setFilterGroupId] = useState(0)
  const [groups, setGroups] = useState<{ id: number; name: string }[]>([])
  const [sortCol, setSortCol] = useState<string>('id')
  const [sortAsc, setSortAsc] = useState(false)
  const [toDelete, setToDelete] = useState<AccountItem | null>(null)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)

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
  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteAccount(toDelete.id); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) }
  }
  const FILTERS: { k: string; v: number | null; dot?: string }[] = [{ k: 'f_all', v: null }, { k: 'f_active', v: 1, dot: '#10b981' }, { k: 'f_inactive', v: 0, dot: '#ef4444' }]

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, height: '100%', boxSizing: 'border-box', overflow: mode === 'old' ? 'hidden' : 'auto' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('title')}</h1>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('subtitle')}</p>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <ViewModeToggle mode={mode} onReact={() => setMode('react')} onOld={() => { setMode('old'); setOldLoaded(true) }} />
          <button style={{ ...btnGhost, width: 36, padding: 0, justifyContent: 'center' }} onClick={() => setTick((x) => x + 1)} title={t('refresh')}>↻</button>
          <button style={btnPrimary} onClick={() => navigate(`${base}/new`)}><PlusIcon />{t('new')}</button>
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 20, flex: 1, minHeight: 0 }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 12 }}>
          <Kpi label={t('kpi_total')}    value={stats?.total    ?? null} icon={<BuildingKpiIcon />} iconBg="rgba(59,130,246,0.1)"  iconColor="#3b82f6" />
          <Kpi label={t('kpi_active')}   value={stats?.active   ?? null} icon={<BuildingKpiIcon />} iconBg="rgba(16,185,129,0.1)"  iconColor="#10b981" />
          <Kpi label={t('kpi_inactive')} value={stats?.inactive ?? null} icon={<BuildingKpiIcon />} iconBg="rgba(239,68,68,0.1)"   iconColor="#ef4444" />
        </div>
        <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8 }}>
          <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 180, maxWidth: 384 }} value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)} onKeyDown={(e) => e.key === 'Enter' && setSearch(searchInput.trim())} placeholder={t('search')} />
          <div style={{ display: 'inline-flex', alignItems: 'center', gap: 4, padding: 4, borderRadius: 8, border: '1px solid var(--color-border)', background: 'var(--color-muted,rgba(0,0,0,.04))' }}>
            {FILTERS.map((f) => (
              <button key={f.k} onClick={() => setStatus(f.v)} style={{ display: 'flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 6, border: 0, cursor: 'pointer', fontSize: 12, fontWeight: 500, background: status === f.v ? 'var(--color-card)' : 'transparent', color: status === f.v ? 'var(--color-foreground)' : 'var(--color-muted-foreground)', boxShadow: status === f.v ? '0 1px 2px rgba(0,0,0,.08)' : 'none' }}>
                {f.dot && <span style={{ width: 6, height: 6, borderRadius: '50%', background: f.dot, flexShrink: 0 }} />}
                {t(f.k)}
              </button>
            ))}
          </div>
          <select style={{ ...inputCss, height: 36, width: 'auto', minWidth: 150 }} value={filterGroupId} onChange={(e) => setFilterGroupId(Number(e.target.value))}>
            <option value={0}>{t('filter_group')}</option>
            {groups.map((g) => <option key={g.id} value={g.id}>{g.name}</option>)}
          </select>
          <div style={{ marginLeft: 'auto', display: 'flex', gap: 8 }}>
            <div style={{ position: 'relative' }}>
              <button style={{ ...btnGhost, height: 36 }} onClick={() => setShowCols((v) => !v)}><GripIcon />{t('columns')}</button>
              {showCols && <ColManager cols={cols} labelFor={(id) => t(COL_LABEL[id])} onChange={setCols} onClose={() => setShowCols(false)} save={cols$.save} defaults={cols$.DEFAULT} t={t} />}
            </div>
            <button style={{ ...btnGhost, height: 36 }} onClick={() => setShowExport(true)}><FileDownIcon />{t('export')}</button>
          </div>
        </div>

        <div style={{ ...card, overflow: 'hidden' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 680 }}>
            <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
              <tr>
                {visibleCols(cols).map(({ id }) => (
                  <th key={id} style={{ ...th, cursor: 'pointer', ...(id === 'id' ? { width: 70 } : {}) }} onClick={() => toggleSort(id)}>
                    {t(COL_LABEL[id])}{sortCol === id ? ` ${sortAsc ? '↑' : '↓'}` : ''}
                  </th>
                ))}
                <th style={{ ...th, width: 80 }} />
              </tr>
            </thead>
            <tbody>
              {sorted.length === 0 && !loading ? (
                <tr><td style={{ ...td, textAlign: 'center', color: 'var(--color-muted-foreground)', padding: '40px 16px' }} colSpan={visibleCols(cols).length + 1}>{t('empty')}</td></tr>
              ) : sorted.map((a) => (
                <tr key={a.id}>
                  {visibleCols(cols).map(({ id }) => (
                    <td key={id} style={{ ...td, ...(id === 'id' ? { color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' } : {}) }}>
                      {id === 'id' && a.id}
                      {id === 'name' && <span style={{ fontWeight: 500 }}>{a.name || '—'}</span>}
                      {id === 'status' && <StatusBadge active={a.status === 1} t={t} />}
                      {id === 'group' && (a.groupName || '—')}
                      {id === 'country' && (a.countryName || '—')}
                      {id === 'created' && <span style={{ color: 'var(--color-muted-foreground)' }}>{fmtDate(a.dateCreation)}</span>}
                    </td>
                  ))}
                  <td style={td}>
                    <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 4 }}>
                      <button style={iconBtn} title={t('edit')} onClick={() => navigate(`${base}/${a.id}`)}><PencilIcon /></button>
                      <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => setToDelete(a)}><TrashIcon /></button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          <div style={{ padding: '10px 16px', textAlign: 'center', fontSize: 12, color: 'var(--color-muted-foreground)' }}>
            {loading ? t('loading') : t('count', { n: items.length })}
          </div>
        </div>
      </div>

      {showExport && <ExportModal cols={cols} items={items} getCell={(a, id) => getCellExport(a, id, t)} labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')} sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />}

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
  const navigate = useNavigate()
  const isEdit = id !== 'new'
  const accountId = isEdit ? parseInt(id) : null
  const subTabPath = `${base}/${id}`

  const [groups, setGroups] = useState<AccountOptionT[]>([])
  const [countries, setCountries] = useState<AccountOptionT[]>([])
  const [name, setName] = useState('')
  const [active, setActive] = useState(true)
  const [groupId, setGroupId] = useState<number>(0)
  const [countryId, setCountryId] = useState<number>(0)
  const [tags, setTags] = useState('')
  const [loading, setLoading] = useState(false)
  const [saving, setSaving] = useState(false)
  const [nameError, setNameError] = useState('')
  const [countryError, setCountryError] = useState('')
  const [tab, setTab] = useState('properties')
  const [visitedTabs, setVisitedTabs] = useState(new Set<string>())
  // Données de l'onglet Société, remontées ici pour être sauvées par le bouton du HAUT (un seul Save).
  const [company, setCompany] = useState<Omit<CompanyData, 'id'>>(EMPTY_CO)
  const [companyLoaded, setCompanyLoaded] = useState(false)
  const [addresses, setAddresses] = useState<AccountAddress[]>([])
  const [addressesLoaded, setAddressesLoaded] = useState(false)
  const [mainContact, setMainContact] = useState<AccountContact | null>(null)

  const TABS: TabDef[] = [
    { key: 'properties', label: t('tab_properties'), icon: <TagIcon /> },
    { key: 'contacts',   label: t('tab_contacts'),   icon: <UsersIcon />,     disabled: !isEdit },
    { key: 'company',    label: t('tab_company'),    icon: <BuildingIcon /> },
    { key: 'addresses',  label: t('tab_addresses'),  icon: <MapPinIcon /> },
    { key: 'orders',     label: t('tab_orders'),     icon: <CartIcon />,      disabled: !isEdit },
    { key: 'files',      label: t('tab_files'),      icon: <PaperclipIcon />, disabled: !isEdit },
  ]

  function handleTabChange(newTab: string) {
    setVisitedTabs((s) => { const n = new Set(s); n.add(newTab); return n })
    setTab(newTab)
  }

  useEffect(() => { fetchAccountOptions().then((o) => { setGroups(o.groups); setCountries(o.countries) }).catch(() => null) }, [])
  // Reset derived/lazy state when switching accounts so stale data from a previous account isn't saved.
  useEffect(() => {
    setCompany(EMPTY_CO); setCompanyLoaded(false)
    setAddresses([]); setAddressesLoaded(false)
    setNameError(''); setCountryError('')
    setMainContact(null)
  }, [accountId])
  useEffect(() => {
    if (!accountId) return
    setLoading(true)
    fetchAccountById(accountId)
      .then((a) => { setName(a.name); setActive(a.status === 1); setGroupId(a.groupId); setCountryId(a.countryId); setTags(a.tags) })
      .catch(() => navigate(base)).finally(() => setLoading(false))
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
    if (isEdit && name) updateSubLabel(base, subTabPath, name || `#${accountId}`)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [name])

  async function submit() {
    let hasError = false
    if (!name.trim()) { setNameError(t('err_name')); hasError = true } else setNameError('')
    if (!countryId) { setCountryError(t('err_country')); hasError = true } else setCountryError('')
    if (hasError) return
    setSaving(true)
    try {
      const r = await saveAccount({ id: accountId, name: name.trim(), status: active, groupId, countryId, tags: tags.trim() })
      const savedId = accountId ?? r?.id
      if (savedId) {
        if (companyLoaded || visitedTabs.has('company')) { try { await saveCompany(savedId, company) } catch { /* */ } }
        if (addressesLoaded || visitedTabs.has('addresses')) { try { await saveAccountAddresses(savedId, addresses) } catch { /* */ } }
      }
      notify('ok', t('title'), t('saved'))
      if (!isEdit) closeSubTab(base, `${base}/new`)
      setTimeout(() => navigate(base), 500)
    } catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : t('err_save')) } finally { setSaving(false) }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, height: '100%', boxSizing: 'border-box', overflow: 'auto' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <button type="button" style={{ ...iconBtn, width: 32, height: 32 }} onClick={() => navigate(base)} title={t('back')}><ArrowLeftIcon /></button>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{isEdit ? t('edit_title') : t('new_title')}</h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <button style={btnPrimary} onClick={submit} disabled={saving || loading}>{saving ? '…' : t('save')}</button>
        </div>
      </div>

      <Tabs tabs={TABS} active={tab} onChange={handleTabChange} />

      {tab === 'company' ? (
        <CompanyTab company={company} onChange={setCompany} loading={!companyLoaded && !!accountId} t={t} />
      ) : tab === 'addresses' ? (
        <AddressesTab addresses={addresses} onChange={setAddresses} t={t} />
      ) : tab === 'contacts' && accountId ? (
        <ContactsTab accountId={accountId} t={t} />
      ) : tab === 'orders' && accountId ? (
        <OrdersTab accountId={accountId} t={t} />
      ) : tab === 'files' && accountId ? (
        <FilesTab accountId={accountId} t={t} />
      ) : tab === 'properties' ? (
        loading ? (
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', minHeight: '40vh', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
        ) : (
          <div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr minmax(260px,320px)', gap: 24, alignItems: 'start' }}>
            <div style={{ ...card, padding: 32, display: 'flex', flexDirection: 'column', gap: 18 }}>
              <h3 style={{ fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: 0 }}>{t('f_identity')}</h3>
              <div>
                <label style={{ ...label, display: 'flex', alignItems: 'center', gap: 5, ...(nameError ? { color: '#ef4444' } : {}) }}>
                  <UserIcon /><span>{t('f_name')} <span style={{ color: '#ef4444' }}>*</span></span>
                </label>
                <input style={inputCss} value={name} onChange={(e) => { setName(e.target.value); if (nameError && e.target.value.trim()) setNameError('') }} placeholder={t('f_name_ph')} autoComplete="off" />
                {nameError && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{nameError}</p>}
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
                <label style={{ ...label, display: 'flex', alignItems: 'center', gap: 5, ...(countryError ? { color: '#ef4444' } : {}) }}>
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
                <input style={inputCss} value={tags} onChange={(e) => setTags(e.target.value)} placeholder={t('f_tags_ph')} autoComplete="off" />
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
              {mainContact ? (
                <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                  {mainContact.civilityName && (
                    <div style={{ display: 'flex', gap: 8, fontSize: 13 }}>
                      <span style={{ color: 'var(--color-muted-foreground)', minWidth: 64 }}>{t('c_civility')}</span>
                      <span style={{ fontWeight: 500 }}>{mainContact.civilityName}</span>
                    </div>
                  )}
                  {(mainContact.firstname || mainContact.name) && (
                    <div style={{ display: 'flex', gap: 8, fontSize: 13 }}>
                      <span style={{ color: 'var(--color-muted-foreground)', minWidth: 64 }}>{t('c_name')}</span>
                      <span style={{ fontWeight: 500 }}>{`${mainContact.firstname} ${mainContact.name}`.trim()}</span>
                    </div>
                  )}
                  {mainContact.email && (
                    <div style={{ display: 'flex', gap: 8, fontSize: 13 }}>
                      <span style={{ color: 'var(--color-muted-foreground)', minWidth: 64 }}>{t('c_email')}</span>
                      <span>{mainContact.email}</span>
                    </div>
                  )}
                </div>
              ) : (
                <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: 0 }}>
                  {accountId ? t('c_empty') : t('save_first')}
                </p>
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
