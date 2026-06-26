import { useEffect, useMemo, useState } from 'react'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import {
  deleteContact, fetchContactById, fetchContactOptions, fetchContacts, fetchContactStats,
  saveContact, fetchContactAddresses, saveContactAddresses,
  type ContactItem, type ContactStats, type ContactAddress,
} from './api'
import { DICT } from './dict'
import { makeT, fmtDate } from '../../shared/i18n'
import { card, inputCss, btnPrimary, btnGhost, iconBtn, th, td, label, hint, segBtn } from '../../shared/styles'
import { PencilIcon, TrashIcon, PlusIcon, GripIcon, FileDownIcon } from '../../shared/icons'
import { StatusBadge, Kpi, ViewModeToggle, LegacyFrame } from '../../shared/widgets'
import { ColManager } from '../../shared/ColManager'
import { ExportModal } from '../../shared/ExportModal'
import { makeColStore, visibleCols, type ColDef } from '../../shared/columns'
import { openSubTab, closeSubTab, updateSubLabel } from '../../shared/subtabs'
import type { Option } from '../../shared/api'
import { Tabs, type TabDef } from '../../shared/Tabs'
import { TagIcon, MapPinIcon, LinkIcon } from '../../shared/icons'
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
  const [mode, setMode] = useState<'react' | 'old'>('react')
  const [oldLoaded, setOldLoaded] = useState(false)
  const [items, setItems] = useState<ContactItem[]>([])
  const [stats, setStats] = useState<ContactStats | null>(null)
  const [loading, setLoading] = useState(false)
  const [searchInput, setSearchInput] = useState('')
  const [search, setSearch] = useState('')
  const [status, setStatus] = useState<number | null>(null)
  const [sortCol, setSortCol] = useState<string>('id')
  const [sortAsc, setSortAsc] = useState(false)
  const [toDelete, setToDelete] = useState<ContactItem | null>(null)
  const [tick, setTick] = useState(0)
  const [cols, setCols] = useState<ColDef[]>(cols$.load)
  const [showCols, setShowCols] = useState(false)
  const [showExport, setShowExport] = useState(false)

  useEffect(() => { fetchContactStats().then(setStats).catch(() => null) }, [tick])
  useEffect(() => {
    setLoading(true)
    fetchContacts({ search, status }).then((r) => setItems(r.items)).catch(() => null).finally(() => setLoading(false))
  }, [search, status, tick])

  const sortVal = (c: ContactItem): string | number => {
    switch (sortCol) {
      case 'status': return c.status; case 'firstname': return c.firstname; case 'name': return c.name
      case 'account': return c.accountName; case 'email': return c.email; case 'type': return c.type
      case 'tags': return c.tags; case 'created': return c.dateCreation ?? ''; default: return c.id
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
    try { await deleteContact(toDelete.id); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) }
  }
  const FILTERS: { k: string; v: number | null }[] = [{ k: 'f_all', v: null }, { k: 'f_active', v: 1 }, { k: 'f_inactive', v: 0 }]

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
        <div style={{ display: 'flex', gap: 12, flexWrap: 'wrap' }}>
          <Kpi label={t('kpi_total')} value={stats?.total ?? null} />
          <Kpi label={t('kpi_active')} value={stats?.active ?? null} />
          <Kpi label={t('kpi_inactive')} value={stats?.inactive ?? null} />
        </div>
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
          <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 220 }} value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)} onKeyDown={(e) => e.key === 'Enter' && setSearch(searchInput.trim())} placeholder={t('search')} />
          <div style={{ display: 'inline-flex', gap: 2, padding: 2, borderRadius: 8, border: '1px solid var(--color-border)' }}>
            {FILTERS.map((f) => <button key={f.k} style={segBtn(status === f.v)} onClick={() => setStatus(f.v)}>{t(f.k)}</button>)}
          </div>
          <div style={{ position: 'relative' }}>
            <button style={{ ...btnGhost, height: 36 }} onClick={() => setShowCols((v) => !v)}><GripIcon />{t('columns')}</button>
            {showCols && <ColManager cols={cols} labelFor={(id) => t(COL_LABEL[id])} onChange={setCols} onClose={() => setShowCols(false)} save={cols$.save} defaults={cols$.DEFAULT} t={t} />}
          </div>
          <button style={{ ...btnGhost, height: 36 }} onClick={() => setShowExport(true)}><FileDownIcon />{t('export')}</button>
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
                      {id === 'tags' && (c.tags || '—')}
                      {id === 'created' && <span style={{ color: 'var(--color-muted-foreground)' }}>{fmtDate(c.dateCreation)}</span>}
                    </td>
                  ))}
                  <td style={td}>
                    <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 4 }}>
                      <button style={iconBtn} title={t('edit')} onClick={() => navigate(`${base}/${c.id}`)}><PencilIcon /></button>
                      <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => setToDelete(c)}><TrashIcon /></button>
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
        <div style={{ position: 'fixed', inset: 0, zIndex: 50, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
          <div style={{ ...card, padding: 24, width: '100%', maxWidth: 360 }}>
            <h3 style={{ fontSize: 16, fontWeight: 600, margin: 0 }}>{t('del_title')}</h3>
            <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', marginTop: 8 }}>{t('del_confirm', { u: fullName(toDelete) })}</p>
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 20 }}>
              <button style={btnGhost} onClick={() => setToDelete(null)}>{t('cancel')}</button>
              <button style={{ ...btnGhost, borderColor: '#fca5a5', color: '#dc2626' }} onClick={confirmDelete}>{t('del')}</button>
            </div>
          </div>
        </div>
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
  const [civility, setCivility] = useState<number>(0)
  const [firstname, setFirstname] = useState('')
  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [jobTitle, setJobTitle] = useState('')
  const [telMobile, setTelMobile] = useState('')
  const [accountId, setAccountId] = useState<number>(0)
  const [type, setType] = useState('person')
  const [tags, setTags] = useState('')
  const [active, setActive] = useState(true)
  const [loading, setLoading] = useState(false)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [saved, setSaved] = useState(false)
  const [tab, setTab] = useState('information')
  const [addresses, setAddresses] = useState<ContactAddress[]>([])
  const [addressesLoaded, setAddressesLoaded] = useState(false)

  const TABS: TabDef[] = [
    { key: 'information', label: t('tab_information'), icon: <TagIcon /> },
    { key: 'address',     label: t('tab_address'),     icon: <MapPinIcon />, disabled: !isEdit },
    { key: 'association', label: t('tab_association'), icon: <LinkIcon />,   disabled: !isEdit },
  ]

  useEffect(() => { fetchContactOptions().then((o) => { setAccounts(o.accounts); setCivilities(o.civilities) }).catch(() => null) }, [])
  useEffect(() => {
    if (!contactId) return
    setLoading(true)
    fetchContactById(contactId)
      .then((c) => {
        setCivility(c.civility); setFirstname(c.firstname); setName(c.name); setEmail(c.email)
        setJobTitle(c.jobTitle); setTelMobile(c.telMobile); setAccountId(c.accountId)
        setType(c.type); setTags(c.tags); setActive(c.status === 1)
      })
      .catch(() => navigate(base)).finally(() => setLoading(false))
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

  async function submit() {
    if (!name.trim()) { setError(t('err_name')); return }
    if (!email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setError(t('err_email')); return }
    setSaving(true); setError(null)
    try {
      await saveContact({
        id: contactId, status: active, type: type.trim(), civility,
        firstname: firstname.trim(), name: name.trim(), email: email.trim(),
        accountId, jobTitle: jobTitle.trim(), telMobile: telMobile.trim(), tags: tags.trim(),
      })
      if (isEdit && contactId && addressesLoaded) { try { await saveContactAddresses(contactId, addresses) } catch { /* */ } }
      setSaved(true)
      if (!isEdit) closeSubTab(base, `${base}/new`)
      setTimeout(() => navigate(base), 500)
    } catch (e) { setError(e instanceof Error ? e.message : t('err_save')) } finally { setSaving(false) }
  }

  const fieldTitle = { fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 12px' } as const

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, height: '100%', boxSizing: 'border-box', overflow: 'auto' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <button style={{ ...iconBtn, width: 32, height: 32 }} onClick={() => navigate(base)} title={t('back')}>←</button>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{isEdit ? t('edit_title') : t('new_title')}</h1>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          {saved && <span style={{ fontSize: 14, color: '#059669' }}>{t('saved')}</span>}
          <button style={btnPrimary} onClick={submit} disabled={saving || loading}>{saving ? '…' : t('save')}</button>
        </div>
      </div>

      <Tabs tabs={TABS} active={tab} onChange={setTab} />

      {tab === 'address' && contactId ? <AddressTab addresses={addresses} onChange={setAddresses} t={t} /> :
       tab === 'association' && contactId ? <AssociationTab contactId={contactId} t={t} /> : (
      <>
      {error && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14 }}>{error}</div>}

      {loading ? (
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', minHeight: '40vh', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit,minmax(280px,1fr))', gap: 16, maxWidth: 880, alignItems: 'start' }}>
          <div style={{ ...card, padding: 20 }}>
            <h3 style={fieldTitle}>{t('f_identity')}</h3>
            <label style={label}>{t('f_civility')}</label>
            <select style={inputCss} value={civility} onChange={(e) => setCivility(Number(e.target.value))}>
              <option value={0}>{t('f_civility_ph')}</option>
              {civilities.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
            </select>
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginTop: 16 }}>
              <div>
                <label style={label}>{t('f_firstname')}</label>
                <input style={inputCss} value={firstname} onChange={(e) => setFirstname(e.target.value)} placeholder={t('f_firstname_ph')} autoComplete="off" />
              </div>
              <div>
                <label style={label}>{t('f_name')}</label>
                <input style={inputCss} value={name} onChange={(e) => { setName(e.target.value); setError(null) }} placeholder={t('f_name_ph')} autoComplete="off" />
              </div>
            </div>
            <label style={{ ...label, marginTop: 16 }}>{t('f_email')}</label>
            <input style={inputCss} value={email} onChange={(e) => { setEmail(e.target.value); setError(null) }} placeholder={t('f_email_ph')} autoComplete="off" />
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12, marginTop: 16 }}>
              <div>
                <label style={label}>{t('f_job')}</label>
                <input style={inputCss} value={jobTitle} onChange={(e) => setJobTitle(e.target.value)} placeholder={t('f_job_ph')} autoComplete="off" />
              </div>
              <div>
                <label style={label}>{t('f_mobile')}</label>
                <input style={inputCss} value={telMobile} onChange={(e) => setTelMobile(e.target.value)} placeholder={t('f_mobile_ph')} autoComplete="off" />
              </div>
            </div>
          </div>
          <div style={{ ...card, padding: 20 }}>
            <h3 style={fieldTitle}>{t('f_options')}</h3>
            <label style={label}>{t('f_account')}</label>
            <select style={inputCss} value={accountId} onChange={(e) => setAccountId(Number(e.target.value))}>
              <option value={0}>{t('f_account_ph')}</option>
              {accounts.map((a) => <option key={a.id} value={a.id}>{a.name}</option>)}
            </select>
            <label style={{ ...label, marginTop: 16 }}>{t('f_type_label')}</label>
            <div style={{ display: 'flex', gap: 18, alignItems: 'center', height: 40 }}>
              {([['person', t('type_person')], ['company', t('type_company')]] as const).map(([val, lbl]) => (
                <label key={val} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, fontSize: 14, cursor: 'pointer' }}>
                  <input type="radio" name="cper_type" value={val} checked={type === val} onChange={() => setType(val)} style={{ accentColor: 'var(--color-primary)' }} />
                  {lbl}
                </label>
              ))}
            </div>
            <label style={{ ...label, marginTop: 16 }}>{t('f_tags')}</label>
            <input style={inputCss} value={tags} onChange={(e) => setTags(e.target.value)} placeholder={t('f_tags_ph')} autoComplete="off" />
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, marginTop: 18, borderTop: '1px solid var(--color-border)', paddingTop: 14 }}>
              <div>
                <p style={{ margin: 0, fontSize: 14, fontWeight: 500 }}>{t('f_active')}</p>
                <p style={hint}>{t('f_active_hint')}</p>
              </div>
              <button type="button" onClick={() => setActive((v) => !v)}
                style={{ position: 'relative', width: 44, height: 24, borderRadius: 999, border: 0, cursor: 'pointer', background: active ? 'var(--color-primary)' : 'var(--color-muted-foreground)', flexShrink: 0 }}>
                <span style={{ position: 'absolute', top: 2, left: active ? 22 : 2, width: 20, height: 20, borderRadius: 999, background: '#fff', transition: 'left .15s' }} />
              </button>
            </div>
          </div>
        </div>
      )}
      </>
      )}
    </div>
  )
}
