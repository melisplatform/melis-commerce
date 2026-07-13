import { useEffect, useMemo, useRef, useState } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  deleteContact, fetchContactById, fetchContactOptions, fetchContacts, fetchContactStats,
  saveContact, fetchContactAddresses, saveContactAddresses,
  type ContactItem, type ContactStats, type ContactAddress,
} from './api'
import { makeCache } from '../../shared/listCache'
import { DICT } from './dict'
import { makeT, fmtDate } from '../../shared/i18n'
import { card, inputCss, btnPrimary, btnGhost, iconBtn, th, td, label, hint } from '../../shared/styles'
import { PencilIcon, TrashIcon, PlusIcon, GripIcon, FileDownIcon, UsersKpiIcon, ToggleRightIcon, KeyIcon, ResetIcon } from '../../shared/icons'
import { StatusBadge, Kpi, ViewModeToggle, LegacyFrame, ConfirmModal, TagsInput, TagsDisplay } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { useCaps } from '../../shared/useCaps'
import { ColManager } from '../../shared/ColManager'
import { ExportModal } from '../../shared/ExportModal'
import { makeColStore, visibleCols, type ColDef } from '../../shared/columns'
import { openSubTab, closeSubTab, updateSubLabel } from '../../shared/subtabs'
import type { Option } from '../../shared/api'
import { Tabs, type TabDef } from '../../shared/Tabs'
import { TagIcon, MapPinIcon, LinkIcon, UserIcon } from '../../shared/icons'
import { AddressTab, AssociationTab } from './ContactTabs'

/* Brique « Contacts » (MelisCommerce) — full React, montée à /melis-commerce/contact-list
 * (et /:id pour le formulaire, en sous-onglet in-tool). Toggle New/Old → iframe legacy. */
const TOOL_MELIS_KEY = 'meliscommerce_contact_list_page'
const COL_ORDER = ['id', 'status', 'firstname', 'name', 'account', 'email', 'type', 'tags', 'created'] as const
const COL_LABEL: Record<string, string> = {
  id: 'col_id', status: 'col_status', firstname: 'col_firstname', name: 'col_name', account: 'col_account',
  email: 'col_email', type: 'col_type', tags: 'col_tags', created: 'col_created',
}
const cols$ = makeColStore('melis-contact-cols-v1', COL_ORDER)

const listCache = makeCache<{
  items: ContactItem[]; stats: ContactStats | null
  search: string; searchInput: string; status: number | null; filterAccountId: number; filterType: string
  sortCol: string; sortAsc: boolean; mode: 'react' | 'old'
}>()

function getCellExport(c: ContactItem, id: string, t: (k: string) => string): string | number {
  switch (id) {
    case 'id': return c.id
    case 'status': return c.status === 1 ? t('status_active') : t('status_inactive')
    case 'firstname': return c.firstname || ''
    case 'name': return c.name || ''
    case 'account': return c.accountName || ''
    case 'email': return c.email || ''
    case 'type': return c.type || ''
    case 'tags': return c.tags || ''
    case 'created': return c.dateCreation || ''
    default: return ''
  }
}
const fullName = (c: ContactItem) => `${c.firstname} ${c.name}`.trim() || c.email || `#${c.id}`

export default function ContactPage() {
  const { id } = useParams()
  const location = useLocation()
  const base = id ? location.pathname.slice(0, location.pathname.length - id.length - 1) : location.pathname
  if (id) return <ContactForm id={id} base={base} />
  return <ContactList base={base} />
}

// ── Liste ─────────────────────────────────────────────────────────────────────
function ContactList({ base }: { base: string }) {
  const t = makeT(DICT)
  const navigate = useNavigate()
  const { can } = useCaps(TOOL_MELIS_KEY)
  const [mode, setMode] = useState<'react' | 'old'>(listCache.get()?.mode ?? 'react')
  const [oldLoaded, setOldLoaded] = useState(false)
  const [items, setItems] = useState<ContactItem[]>(listCache.get()?.items ?? [])
  const [stats, setStats] = useState<ContactStats | null>(listCache.get()?.stats ?? null)
  const [loading, setLoading] = useState(false)
  const [searchInput, setSearchInput] = useState(listCache.get()?.searchInput ?? '')
  const [search, setSearch] = useState(listCache.get()?.search ?? '')
  const [status, setStatus] = useState<number | null>(listCache.get()?.status ?? null)
  const [filterAccountId, setFilterAccountId] = useState(listCache.get()?.filterAccountId ?? 0)
  const [filterType, setFilterType] = useState(listCache.get()?.filterType ?? '')
  const [accounts, setAccounts] = useState<{ id: number; name: string }[]>([])
  const [sortCol, setSortCol] = useState<string>(listCache.get()?.sortCol ?? 'id')
  const [sortAsc, setSortAsc] = useState(listCache.get()?.sortAsc ?? false)
  const [toDelete, setToDelete] = useState<ContactItem | null>(null)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)

  const cacheRef = useRef({ items, stats, search, searchInput, status, filterAccountId, filterType, sortCol, sortAsc, mode })
  useEffect(() => { cacheRef.current = { items, stats, search, searchInput, status, filterAccountId, filterType, sortCol, sortAsc, mode } })
  useEffect(() => () => listCache.set(cacheRef.current), [])

  useEffect(() => { fetchContactStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => { fetchContactOptions().then((o) => setAccounts(o.accounts)).catch(() => null) }, [])
  useEffect(() => {
    setLoading(true)
    fetchContacts({ search, status, accountId: filterAccountId || null }).then((r) => setItems(r.items)).catch(() => null).finally(() => setLoading(false))
  }, [search, status, filterAccountId, tick])

  const sortVal = (c: ContactItem): string | number => {
    switch (sortCol) {
      case 'status': return c.status; case 'firstname': return c.firstname; case 'name': return c.name
      case 'account': return c.accountName; case 'email': return c.email; case 'type': return c.type
      case 'tags': return c.tags; case 'created': return c.dateCreation ?? ''; default: return c.id
    }
  }
  const sorted = useMemo(() => {
    let list = filterType ? items.filter((c) => c.type === filterType) : [...items]
    return list.sort((a, b) => {
      const va = sortVal(a), vb = sortVal(b)
      const cmp = typeof va === 'number' && typeof vb === 'number' ? va - vb : String(va).localeCompare(String(vb))
      return sortAsc ? cmp : -cmp
    })
  }, [items, sortCol, sortAsc, filterType])

  function toggleSort(id: string) { if (sortCol === id) setSortAsc((v) => !v); else { setSortCol(id); setSortAsc(true) } }
  // Réinitialiser les filtres : recherche + statut + compte + type + tri par défaut (id desc), puis refetch.
  // On vide `items` : sinon les lignes restent affichées pendant le refetch et le clic paraît sans effet.
  function resetFilters() {
    setSearchInput(''); setSearch('')
    setStatus(null)
    setFilterAccountId(0)
    setFilterType('')
    setSortCol('id'); setSortAsc(false)
    setItems([])
    setTick((x) => x + 1)
  }
  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteContact(toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) }
  }
  const FILTERS: { k: string; v: number | null; dot?: string }[] = [{ k: 'f_all', v: null }, { k: 'f_active', v: 1, dot: '#10b981' }, { k: 'f_inactive', v: 0, dot: '#ef4444' }]

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box', ...(mode === 'old' ? { height: '100%', overflow: 'hidden' } : {}) }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('title')}</h1>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('subtitle')}</p>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <ViewModeToggle mode={mode} onReact={() => setMode('react')} onOld={() => { setMode('old'); setOldLoaded(true) }} />
          <button style={{ ...btnGhost, width: 36, padding: 0, justifyContent: 'center' }} onClick={() => setTick((x) => x + 1)} title={t('refresh')}>↻</button>
          {can('create') && <button style={btnPrimary} onClick={() => navigate(`${base}/new`)}><PlusIcon />{t('new')}</button>}
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 20, flex: 1, minHeight: 0 }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 12 }}>
          <Kpi label={t('kpi_total')}    value={stats?.total    ?? null} icon={<UsersKpiIcon />} iconBg="rgba(59,130,246,0.1)"  iconColor="#3b82f6" />
          <Kpi label={t('kpi_active')}   value={stats?.active   ?? null} icon={<UsersKpiIcon />} iconBg="rgba(16,185,129,0.1)"  iconColor="#10b981" />
          <Kpi label={t('kpi_inactive')} value={stats?.inactive ?? null} icon={<UsersKpiIcon />} iconBg="rgba(239,68,68,0.1)"   iconColor="#ef4444" />
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
          <select style={{ ...inputCss, height: 36, width: 'auto', minWidth: 170 }} value={filterAccountId} onChange={(e) => setFilterAccountId(Number(e.target.value))}>
            <option value={0}>{t('filter_account')}</option>
            {accounts.map((a) => <option key={a.id} value={a.id}>{a.name}</option>)}
          </select>
          <select style={{ ...inputCss, height: 36, width: 'auto', minWidth: 130 }} value={filterType} onChange={(e) => setFilterType(e.target.value)}>
            <option value="">{t('filter_type')}</option>
            <option value="person">{t('type_person')}</option>
            <option value="company">{t('type_company')}</option>
          </select>
          <div style={{ marginLeft: 'auto', display: 'flex', gap: 8 }}>
            <button style={{ ...btnGhost, height: 36 }} onClick={resetFilters}><ResetIcon />{t('reset_filters')}</button>
            <div style={{ position: 'relative' }}>
              <button style={{ ...btnGhost, height: 36 }} onClick={() => setShowCols((v) => !v)}><GripIcon />{t('columns')}</button>
              {showCols && <ColManager cols={cols} labelFor={(id) => t(COL_LABEL[id])} onChange={setCols} onClose={() => setShowCols(false)} save={cols$.save} defaults={cols$.DEFAULT} t={t} />}
            </div>
            {can('export') && <button style={{ ...btnGhost, height: 36 }} onClick={() => setShowExport(true)}><FileDownIcon />{t('export')}</button>}
          </div>
        </div>

        <div style={{ ...card, overflow: 'hidden' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 760 }}>
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
              ) : sorted.map((c) => (
                <tr key={c.id}>
                  {visibleCols(cols).map(({ id }) => (
                    <td key={id} style={{ ...td, ...(id === 'id' ? { color: 'var(--color-muted-foreground)', fontVariantNumeric: 'tabular-nums' } : {}) }}>
                      {id === 'id' && c.id}
                      {id === 'status' && <StatusBadge active={c.status === 1} t={t} />}
                      {id === 'firstname' && (c.firstname || '—')}
                      {id === 'name' && <span style={{ fontWeight: 500 }}>{c.name || '—'}</span>}
                      {id === 'account' && (c.accountName || '—')}
                      {id === 'email' && (c.email || '—')}
                      {id === 'type' && (c.type || '—')}
                      {id === 'tags' && <TagsDisplay value={c.tags} />}
                      {id === 'created' && <span style={{ color: 'var(--color-muted-foreground)' }}>{fmtDate(c.dateCreation)}</span>}
                    </td>
                  ))}
                  <td style={td}>
                    <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 4 }}>
                      {can('edit') && <button style={iconBtn} title={t('edit')} onClick={() => navigate(`${base}/${c.id}`)}><PencilIcon /></button>}
                      {can('delete') && <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => setToDelete(c)}><TrashIcon /></button>}
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

      {showExport && <ExportModal cols={cols} items={items} getCell={(c, id) => getCellExport(c, id, t)} labelFor={(id) => t(COL_LABEL[id])} filename={t('exp_filename')} sheetTitle={t('title')} t={t} onClose={() => setShowExport(false)} />}

      {toDelete && (
        <ConfirmModal title={t('del_title')} message={t('del_confirm', { u: fullName(toDelete) })}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
      )}
    </div>
  )
}

// ── Formulaire (sous-onglet in-tool) ────────────────────────────────────────────
function ContactForm({ id, base }: { id: string; base: string }) {
  const t = makeT(DICT)
  const navigate = useNavigate()
  const isEdit = id !== 'new'
  const contactId = isEdit ? parseInt(id) : null
  const subTabPath = `${base}/${id}`

  const [accounts, setAccounts] = useState<Option[]>([])
  const [civilities, setCivilities] = useState<Option[]>([])
  const [languages, setLanguages] = useState<Option[]>([])
  const [civility, setCivility] = useState<number>(0)
  const [firstname, setFirstname] = useState('')
  const [name, setName] = useState('')
  const [middleName, setMiddleName] = useState('')
  const [langId, setLangId] = useState<number>(0)
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [confirmPassword, setConfirmPassword] = useState('')
  const [jobTitle, setJobTitle] = useState('')
  const [jobService, setJobService] = useState('')
  const [telMobile, setTelMobile] = useState('')
  const [telLandline, setTelLandline] = useState('')
  const [accountId, setAccountId] = useState<number>(0)
  const [type, setType] = useState('person')
  const [tags, setTags] = useState('')
  const [active, setActive] = useState(true)
  const [loading, setLoading] = useState(false)
  const [saving, setSaving] = useState(false)
  const [firstnameError, setFirstnameError] = useState('')
  const [nameError, setNameError] = useState('')
  const [langError, setLangError] = useState('')
  const [emailError, setEmailError] = useState('')
  const [passwordError, setPasswordError] = useState('')
  const [formError, setFormError] = useState(false)
  const [tab, setTab] = useState('information')
  const [visitedTabs, setVisitedTabs] = useState(new Set<string>())
  const [addresses, setAddresses] = useState<ContactAddress[]>([])
  const [addressesLoaded, setAddressesLoaded] = useState(false)
  const { can, loaded: capsLoaded } = useCaps(TOOL_MELIS_KEY)

  // Onglets masqués selon les droits (l'accès à un onglet = capacité de même clé : information/address/association).
  const TABS: TabDef[] = ([
    { key: 'information', label: t('tab_information'), icon: <TagIcon /> },
    { key: 'address',     label: t('tab_address'),     icon: <MapPinIcon /> },
    { key: 'association', label: t('tab_association'), icon: <LinkIcon />,   disabled: !isEdit },
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

  useEffect(() => { fetchContactOptions().then((o) => { setAccounts(o.accounts); setCivilities(o.civilities); setLanguages(o.languages) }).catch(() => null) }, [])
  // Reset derived/lazy state when switching contacts so stale data from a previous contact isn't saved.
  useEffect(() => {
    setAddresses([]); setAddressesLoaded(false)
    setFirstnameError(''); setNameError(''); setLangError(''); setEmailError(''); setPasswordError('')
  }, [contactId])
  useEffect(() => {
    if (!contactId) return
    // Garde de montage : un fetch en vol au moment où l'onglet se ferme n'est pas annulé par le
    // démontage — sans ce garde, son .catch() pouvait naviguer vers `base` APRÈS coup et écraser
    // une navigation par ailleurs légitime (ex. fermeture d'onglet → retour Dashboard).
    let cancelled = false
    setLoading(true)
    fetchContactById(contactId)
      .then((c) => {
        if (cancelled) return
        setCivility(c.civility); setFirstname(c.firstname); setName(c.name)
        setMiddleName(c.middleName ?? ''); setLangId(c.langId ?? 0)
        setEmail(c.email); setPassword(''); setConfirmPassword('')
        setJobTitle(c.jobTitle); setJobService(c.jobService ?? '')
        setTelMobile(c.telMobile); setTelLandline(c.telLandline ?? '')
        setAccountId(c.accountId); setType(c.type); setTags(c.tags); setActive(c.status === 1)
      })
      .catch(() => { if (!cancelled) navigate(base) }).finally(() => { if (!cancelled) setLoading(false) })
    return () => { cancelled = true }
  }, [contactId, navigate, base])
  useEffect(() => {
    if (!contactId) return
    fetchContactAddresses(contactId).then((r) => { setAddresses(r.items); setAddressesLoaded(true) }).catch(() => null)
  }, [contactId])

  useEffect(() => {
    if (isEdit) closeSubTab(base, `${base}/new`)
    openSubTab(base, { id: subTabPath, label: isEdit ? t('loading') : t('new'), path: subTabPath })
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])
  useEffect(() => {
    const lbl = `${firstname} ${name}`.trim()
    if (isEdit && lbl) updateSubLabel(base, subTabPath, lbl || `#${contactId}`)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [firstname, name])

  const isCompany = type === 'company'

  async function submit() {
    let firstError = ''
    const fail = (msg: string) => { firstError ||= msg; return msg }
    if (!firstname.trim()) setFirstnameError(fail(t('err_firstname'))); else setFirstnameError('')
    if (!isCompany && !name.trim()) setNameError(fail(t('err_name'))); else setNameError('')
    if (!langId) setLangError(fail(t('err_language'))); else setLangError('')
    if (!email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) setEmailError(fail(t('err_email'))); else setEmailError('')
    if (!isEdit && !password.trim()) setPasswordError(fail(t('err_password_required')))
    else if (password && password !== confirmPassword) setPasswordError(fail(t('err_password')))
    else setPasswordError('')
    setFormError(!!firstError)
    if (firstError) return
    setSaving(true)
    try {
      const r = await saveContact({
        id: contactId, status: active, type: type.trim(), civility,
        firstname: firstname.trim(), name: name.trim(), middleName: middleName.trim(),
        langId, email: email.trim(), password: password.trim(),
        accountId, jobTitle: jobTitle.trim(), jobService: jobService.trim(),
        telMobile: telMobile.trim(), telLandline: telLandline.trim(), tags: tags.trim(),
      })
      const savedId = contactId ?? r?.id
      if (savedId && (addressesLoaded || visitedTabs.has('address'))) {
        try { await saveContactAddresses(savedId, addresses) } catch { /* */ }
      }
      notify('ok', t('title'), t('saved'))
      if (!isEdit) { closeSubTab(base, `${base}/new`); setTimeout(() => navigate(base), 500) }
    } catch (e) { notify('ko', t('title'), e instanceof Error ? e.message : t('err_save')) } finally { setSaving(false) }
  }

  const fieldTitle = { fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 12px' } as const

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, boxSizing: 'border-box' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: 32, height: 32, borderRadius: 8, background: 'color-mix(in srgb, var(--color-primary) 10%, transparent)', color: 'var(--color-primary)' }}><UsersKpiIcon /></span>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{isEdit ? t('edit_title') : t('new_title')}</h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <button style={btnPrimary} onClick={submit} disabled={saving || loading}>{saving ? '…' : t('save')}</button>
        </div>
      </div>

      <Tabs tabs={TABS} active={tab} onChange={handleTabChange} />

      {formError && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, marginBottom: 16 }}>{t('err_required_fields')}</div>}

      {tab === 'address' ? <AddressTab addresses={addresses} onChange={setAddresses} t={t} /> :
       tab === 'association' && contactId ? <AssociationTab contactId={contactId} t={t} can={can} /> :
       tab === 'information' ? (
      <>
      {loading ? (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', minHeight: '40vh', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <div style={{ display: 'flex', gap: 20, alignItems: 'start' }}>
          {/* Main content */}
          <div style={{ flex: 1, display: 'flex', flexDirection: 'column', gap: 16 }}>
            <div style={{ ...card, padding: 24 }}>
              <h3 style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 20px' }}>
                <UserIcon />{t('f_identity')}
              </h3>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
                {/* Type (person/company) */}
                <div>
                  <label style={label}>{t('f_type_label')}</label>
                  <div style={{ display: 'flex', gap: 16, alignItems: 'center', paddingTop: 4 }}>
                    {([['person', t('type_person')], ['company', t('type_company')]] as const).map(([val, lbl]) => (
                      <label key={val} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, fontSize: 14, cursor: 'pointer' }}>
                        <input type="radio" name="cper_type" value={val} checked={type === val} onChange={() => setType(val)} style={{ accentColor: 'var(--color-primary)' }} />
                        {lbl}
                      </label>
                    ))}
                  </div>
                </div>
                {/* Civility (masquée pour un contact « Société ») */}
                {!isCompany && (
                  <div>
                    <label style={label}>{t('f_civility')}</label>
                    <select style={inputCss} value={civility} onChange={(e) => setCivility(Number(e.target.value))}>
                      <option value={0}>{t('f_civility_ph')}</option>
                      {civilities.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
                    </select>
                  </div>
                )}
                {/* Firstname (relabellisé « Société » en mode company) + Name (masqué en mode company) */}
                <div style={{ display: 'grid', gridTemplateColumns: isCompany ? '1fr' : '1fr 1fr', gap: 16 }}>
                  <div>
                    <label style={label}>{isCompany ? t('type_company') : t('f_firstname')} <span style={{ color: '#ef4444' }}>*</span></label>
                    <input style={inputCss} value={firstname} onChange={(e) => { setFirstname(e.target.value); if (firstnameError && e.target.value.trim()) setFirstnameError('') }} placeholder={isCompany ? t('type_company') : t('f_firstname_ph')} autoComplete="off" />
                    {firstnameError && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{firstnameError}</p>}
                  </div>
                  {!isCompany && (
                    <div>
                      <label style={label}>{t('f_name')} <span style={{ color: '#ef4444' }}>*</span></label>
                      <input style={inputCss} value={name} onChange={(e) => { setName(e.target.value); if (nameError && e.target.value.trim()) setNameError('') }} placeholder={t('f_name_ph')} autoComplete="off" />
                      {nameError && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{nameError}</p>}
                    </div>
                  )}
                </div>
                {/* Middle name (masqué en mode company) */}
                {!isCompany && (
                  <div>
                    <label style={label}>{t('f_middle_name')}</label>
                    <input style={inputCss} value={middleName} onChange={(e) => setMiddleName(e.target.value)} placeholder={t('f_middle_name_ph')} autoComplete="off" />
                  </div>
                )}
                {/* Language */}
                <div>
                  <label style={label}>{t('f_language')} <span style={{ color: '#ef4444' }}>*</span></label>
                  <select style={inputCss} value={langId} onChange={(e) => { setLangId(Number(e.target.value)); if (langError) setLangError('') }}>
                    <option value={0}>{t('f_language_ph')}</option>
                    {languages.map((l) => <option key={l.id} value={l.id}>{l.name}</option>)}
                  </select>
                  {langError && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{langError}</p>}
                </div>
                {/* Email */}
                <div>
                  <label style={label}>{t('f_email')} <span style={{ color: '#ef4444' }}>*</span></label>
                  <input style={inputCss} value={email} onChange={(e) => { setEmail(e.target.value); if (emailError) setEmailError('') }} placeholder={t('f_email_ph')} autoComplete="off" />
                  {emailError && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{emailError}</p>}
                </div>
                {/* Job title + Job service (masqués en mode company) */}
                {!isCompany && (
                  <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
                    <div>
                      <label style={label}>{t('f_job')}</label>
                      <input style={inputCss} value={jobTitle} onChange={(e) => setJobTitle(e.target.value)} placeholder={t('f_job_ph')} autoComplete="off" />
                    </div>
                    <div>
                      <label style={label}>{t('f_job_service')}</label>
                      <input style={inputCss} value={jobService} onChange={(e) => setJobService(e.target.value)} placeholder={t('f_job_service_ph')} autoComplete="off" />
                    </div>
                  </div>
                )}
                {/* Mobile + Landline */}
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
                  <div>
                    <label style={label}>{t('f_mobile')}</label>
                    <input style={inputCss} value={telMobile} onChange={(e) => setTelMobile(e.target.value)} placeholder={t('f_mobile_ph')} autoComplete="off" />
                  </div>
                  <div>
                    <label style={label}>{t('f_landline')}</label>
                    <input style={inputCss} value={telLandline} onChange={(e) => setTelLandline(e.target.value)} placeholder={t('f_landline_ph')} autoComplete="off" />
                  </div>
                </div>
                {/* Tags */}
                <div>
                  <label style={label}>{t('f_tags')}</label>
                  <TagsInput value={tags} onChange={setTags} placeholder={t('f_tags_ph')} />
                </div>
              </div>
            </div>

            {/* Password card */}
            <div style={{ ...card, padding: 24 }}>
              <h3 style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 20px' }}>
                <KeyIcon />{t('f_section_password')}
              </h3>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
                <div>
                  <label style={label}>{isEdit ? t('f_password_edit') : t('f_password')} <span style={{ color: '#ef4444' }}>*</span></label>
                  <input type="password" style={inputCss} value={password} onChange={(e) => { setPassword(e.target.value); if (passwordError) setPasswordError('') }} placeholder="******" autoComplete="new-password" />
                </div>
                <div>
                  <label style={label}>{t('f_confirm_password')} <span style={{ color: '#ef4444' }}>*</span></label>
                  <input type="password" style={inputCss} value={confirmPassword} onChange={(e) => { setConfirmPassword(e.target.value); if (passwordError) setPasswordError('') }} placeholder="******" autoComplete="new-password" />
                </div>
              </div>
              {passwordError && <p style={{ margin: '8px 0 0', fontSize: 12, color: '#ef4444' }}>{passwordError}</p>}
            </div>
          </div>

          {/* Right sidebar */}
          <div style={{ width: 256, flexShrink: 0, display: 'flex', flexDirection: 'column', gap: 16 }}>
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
          </div>
        </div>
      )}
      </>
      ) : null}
    </div>
  )
}
