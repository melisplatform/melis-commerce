import { useEffect, useRef, useState, type ChangeEvent } from 'react'
import { useNavigate } from 'react-router-dom'
import {
  fetchAccountContacts, fetchAccountOrders, fetchAccountFiles,
  uploadAccountFile, deleteAccountFile, fetchAddressOptions, fetchFileOptions, createFileType,
  fetchOrderStatuses, fetchAllContactOptions, linkAccountContact, unlinkAccountContact, setAccountContactDefault,
  type CompanyData, type AccountContact, type AccountAddress, type AddressOptions, type AccountOrder, type AccountFile, type FileOptions,
} from './api'
import { card, inputCss, btnGhost, iconBtn, actionBtn, label } from '../../shared/styles'
import { StatusBadge } from '../../shared/widgets'
import { SimpleTable } from '../../shared/SimpleTable'
import { LinkHeader } from '../../shared/LinkHeader'
import { ConfirmModal } from '../../shared/ConfirmModal'
import { DateRangeFilter } from '../../shared/DateRangeFilter'
import { AddressEditor } from '../../shared/AddressEditor'
import { TrashIcon, PencilIcon, StarIcon, CheckIcon, UserPlusIcon, PaperclipIcon, PlusIcon } from '../../shared/icons'
import { fmtDate, type T } from '../../shared/i18n'
import { notify } from '../../shared/notify'
import type { Option } from '../../shared/api'

const CONTACT_ROUTE = '/melis-commerce/contact-list'

const sectionTitle = { fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 12px' } as const

// ── Société (éditable, CONTRÔLÉE par AccountForm) — sauvée par le bouton du haut ──
export const EMPTY_CO: Omit<CompanyData, 'id'> = {
  name: '', numberId: '', vatNumber: '', group: '', employees: 0,
  addNumber: '', addStreet: '', addZip: '', addCity: '', addState: '', addCountry: '', phone: '', website: '',
}
export function CompanyTab({ company, onChange, loading, t }: {
  company: Omit<CompanyData, 'id'>; onChange: (c: Omit<CompanyData, 'id'>) => void; loading: boolean; t: T
}) {
  const set = <K extends keyof typeof company>(k: K, v: (typeof company)[K]) => onChange({ ...company, [k]: v })
  const field = (k: keyof typeof company, lbl: string, type: 'text' | 'number' = 'text') => (
    <div>
      <label style={label}>{lbl}</label>
      <input style={inputCss} type={type} value={String(company[k] ?? '')}
        onChange={(e) => set(k, (type === 'number' ? Number(e.target.value) : e.target.value) as never)} autoComplete="off" />
    </div>
  )
  if (loading) return <div style={{ display: 'flex', justifyContent: 'center', minHeight: '30vh', alignItems: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
  return (
    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 20, alignItems: 'start' }}>
      <div style={{ ...card, padding: 28, display: 'flex', flexDirection: 'column', gap: 16 }}>
        <h3 style={sectionTitle}>{t('co_section_id')}</h3>
        {field('name', t('co_name'))}
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>{field('numberId', t('co_number'))}{field('vatNumber', t('co_vat'))}</div>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>{field('group', t('co_group'))}{field('employees', t('co_employees'), 'number')}</div>
        {field('website', t('co_website'))}
      </div>
      <div style={{ ...card, padding: 28, display: 'flex', flexDirection: 'column', gap: 16 }}>
        <h3 style={sectionTitle}>{t('co_section_addr')}</h3>
        <div style={{ display: 'grid', gridTemplateColumns: '90px 1fr', gap: 14 }}>{field('addNumber', t('co_address'))}{field('addStreet', t('co_street'))}</div>
        <div style={{ display: 'grid', gridTemplateColumns: '120px 1fr', gap: 14 }}>{field('addZip', t('co_zip'))}{field('addCity', t('co_city'))}</div>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>{field('addState', t('co_state'))}{field('addCountry', t('co_country'))}</div>
        {field('phone', t('co_phone'))}
      </div>
    </div>
  )
}

// ── Contacts (lecture seule) ──────────────────────────────────────────────────
export function ContactsTab({ accountId, t, can }: { accountId: number; t: T; can?: (cap: string) => boolean }) {
  const allow = (cap: string) => (can ? can(cap) : true)
  const navigate = useNavigate()
  const [rows, setRows] = useState<AccountContact[]>([])
  const [opts, setOpts] = useState<Option[]>([])
  const [loading, setLoading] = useState(true)
  const [tick, setTick] = useState(0)
  const [toUnlink, setToUnlink] = useState<AccountContact | null>(null)
  useEffect(() => { setLoading(true); fetchAccountContacts(accountId).then((r) => setRows(r.items)).catch(() => null).finally(() => setLoading(false)) }, [accountId, tick])
  useEffect(() => { fetchAllContactOptions().then(setOpts).catch(() => null) }, [])
  const linkedIds = new Set(rows.map((r) => r.id))
  const available = opts.filter((o) => !linkedIds.has(o.id))
  const link = async (id: number) => { await linkAccountContact(accountId, id); setTick((x) => x + 1) }
  const setDefault = async (id: number) => { try { await setAccountContactDefault(accountId, id); setTick((x) => x + 1) } catch { /* */ } }
  async function confirmUnlink() {
    if (!toUnlink) return
    try { await unlinkAccountContact(accountId, toUnlink.id); setToUnlink(null); setTick((x) => x + 1) } catch { setToUnlink(null) }
  }
  return (
    <div>
      {allow('contacts.create') && <LinkHeader options={available} placeholder={t('c_link_ph')} linkLabel={t('c_link')} onLink={link} />}
      <SimpleTable<AccountContact> rows={rows} rowKey={(r) => r.id} empty={t('c_empty')} loading={loading} loadingText={t('loading')}
        t={t} search={(r) => `${r.firstname} ${r.name} ${r.email}`} searchPlaceholder={t('ph_search_contact')} onRefresh={() => setTick((x) => x + 1)}
        onAdd={allow('contacts.create') ? () => navigate(`${CONTACT_ROUTE}/new`) : undefined} addIcon={<UserPlusIcon />} addTitle={t('c_add')}
        cols={[
          { key: 'firstname', label: t('c_firstname'), render: (r) => r.firstname || '—' },
          { key: 'name', label: t('c_name'), render: (r) => <span style={{ fontWeight: 500 }}>{r.name || '—'}</span> },
          { key: 'email', label: t('c_email'), render: (r) => r.email || '—' },
          { key: 'status', label: t('col_status'), render: (r) => <StatusBadge active={r.status === 1} t={t} /> },
          { key: 'def_contact', label: t('c_def_contact'), render: (r) => (r.isMain ? <StarIcon /> : '—') },
          { key: 'def_account', label: t('c_def_account'), render: (r) => (r.isDefaultAccount ? <StarIcon /> : '—') },
          { key: 'action', label: t('action'), width: 120, render: (r) => (
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 6 }}>
              <button style={actionBtn('#16a34a')} title={t('edit')} onClick={() => navigate(`${CONTACT_ROUTE}/${r.id}`)}><PencilIcon /></button>
              {!r.isMain && <button style={actionBtn('#2563eb')} title={t('set_default')} onClick={() => setDefault(r.id)}><CheckIcon /></button>}
              {allow('contacts.delete') && <button style={actionBtn('#ef4444')} title={t('unlink')} onClick={() => setToUnlink(r)}><TrashIcon /></button>}
            </div>
          ) },
        ]} />
      {toUnlink && (
        <ConfirmModal title={t('c_unlink_title')} message={t('c_unlink_confirm', { u: `${toUnlink.firstname} ${toUnlink.name}`.trim() || `#${toUnlink.id}` })}
          confirmLabel={t('unlink')} cancelLabel={t('cancel')} onConfirm={confirmUnlink} onClose={() => setToUnlink(null)} />
      )}
    </div>
  )
}

// Onglet Adresses — éditeur master-détail STAGED partagé (cf. shared/AddressEditor).
export function AddressesTab({ addresses, onChange, t }: { addresses: AccountAddress[]; onChange: (a: AccountAddress[]) => void; t: T }) {
  const [opts, setOpts] = useState<AddressOptions>({ types: [], civilities: [] })
  useEffect(() => { fetchAddressOptions().then(setOpts).catch(() => null) }, [])
  return <AddressEditor addresses={addresses} onChange={onChange} options={opts} t={t} />
}

// ── Commandes (lecture seule) ─────────────────────────────────────────────────
export function OrdersTab({ accountId, t }: { accountId: number; t: T }) {
  const [rows, setRows] = useState<AccountOrder[]>([])
  const [statuses, setStatuses] = useState<Option[]>([])
  const [statusFilter, setStatusFilter] = useState<number | null>(null)
  const [dateFrom, setDateFrom] = useState('')
  const [dateTo, setDateTo] = useState('')
  const [loading, setLoading] = useState(true)
  const [tick, setTick] = useState(0)
  useEffect(() => { setLoading(true); fetchAccountOrders(accountId).then((r) => setRows(r.items)).catch(() => null).finally(() => setLoading(false)) }, [accountId, tick])
  useEffect(() => { fetchOrderStatuses().then(setStatuses).catch(() => null) }, [])

  const filtered = rows.filter((r) => {
    if (statusFilter !== null && r.status !== statusFilter) return false
    const d = (r.dateCreation || '').slice(0, 10)
    if (dateFrom && d < dateFrom) return false
    if (dateTo && d > dateTo) return false
    return true
  })

  const ctl = { ...inputCss, height: 34, width: 'auto', padding: '0 8px' } as const
  const lbl = { display: 'inline-flex', alignItems: 'center', gap: 6, fontSize: 13, color: 'var(--color-muted-foreground)' } as const
  const filters = (
    <>
      <label style={lbl}>{t('o_status_label')}
        <select style={ctl} value={statusFilter ?? ''} onChange={(e) => setStatusFilter(e.target.value === '' ? null : Number(e.target.value))}>
          <option value="">{t('o_status_all')}</option>
          {statuses.map((s) => <option key={s.id} value={s.id}>{s.name}</option>)}
        </select>
      </label>
      <DateRangeFilter from={dateFrom} to={dateTo} onChange={(f, dt) => { setDateFrom(f); setDateTo(dt) }} t={t} />
    </>
  )

  return (
    <SimpleTable<AccountOrder> rows={filtered} rowKey={(r) => r.id} empty={t('o_empty')} loading={loading} loadingText={t('loading')}
      t={t} search={(r) => `${r.reference} ${r.statusName}`} searchPlaceholder={t('ph_search_order')} filters={filters} onRefresh={() => setTick((x) => x + 1)}
      cols={[
        { key: 'id', label: t('col_id'), width: 70, render: (r) => <span style={{ color: 'var(--color-muted-foreground)' }}>{r.id}</span> },
        { key: 'ref', label: t('o_ref'), render: (r) => <span style={{ fontWeight: 500 }}>{r.reference || '—'}</span> },
        { key: 'status', label: t('o_status'), render: (r) => r.statusName || `#${r.status}` },
        { key: 'date', label: t('o_date'), render: (r) => fmtDate(r.dateCreation) },
      ]} />
  )
}

// ── Fichiers (lecture seule) ──────────────────────────────────────────────────
export function FilesTab({ accountId, t, can }: { accountId: number; t: T; can?: (cap: string) => boolean }) {
  const allow = (cap: string) => (can ? can(cap) : true)
  const [rows, setRows] = useState<AccountFile[]>([])
  const [loading, setLoading] = useState(true)
  const [tick, setTick] = useState(0)
  const [toDelete, setToDelete] = useState<AccountFile | null>(null)
  // Modale d'ajout (Choisir un fichier + Nom + Type + Pays)
  const [modal, setModal] = useState(false)
  const [opts, setOpts] = useState<FileOptions>({ types: [], countries: [] })
  const [file, setFile] = useState<File | null>(null)
  const [fname, setFname] = useState('')
  const [ftype, setFtype] = useState(0)
  const [fcountry, setFcountry] = useState(0)
  const [merr, setMerr] = useState<string | null>(null)
  const [uploading, setUploading] = useState(false)
  const fileRef = useRef<HTMLInputElement>(null)
  // Section repliable « Ajouter un type de fichier »
  const [showType, setShowType] = useState(false)
  const [tcode, setTcode] = useState('')
  const [tname, setTname] = useState('')
  const [terr, setTerr] = useState<string | null>(null)

  useEffect(() => { setLoading(true); fetchAccountFiles(accountId).then((r) => setRows(r.items)).catch(() => null).finally(() => setLoading(false)) }, [accountId, tick])
  useEffect(() => { fetchFileOptions().then(setOpts).catch(() => null) }, [])

  function openModal() {
    setFile(null); setFname(''); setMerr(null)
    setFtype(opts.types.find((x) => x.name === 'File')?.id ?? opts.types[0]?.id ?? 2)
    setFcountry(0); setModal(true); setShowType(false); setTcode(''); setTname(''); setTerr(null)
  }
  async function addType() {
    if (!tcode.trim() || !tname.trim()) { setTerr(t('fi_err_type_fields')); return }
    try {
      const nt = await createFileType(tcode.trim(), tname.trim())
      setOpts((o) => ({ ...o, types: [...o.types, nt] }))
      setFtype(nt.id); setTcode(''); setTname(''); setTerr(null); setShowType(false)
    } catch { setTerr(t('err_save')) }
  }
  function onPick(e: ChangeEvent<HTMLInputElement>) {
    const f = e.target.files?.[0] ?? null
    setFile(f); if (f && !fname) setFname(f.name); setMerr(null)
  }
  async function save() {
    if (!file) { setMerr(t('fi_err_file')); return }
    if (!ftype) { setMerr(t('fi_err_type')); return }
    if (!fcountry) { setMerr(t('fi_err_country')); return }
    setUploading(true)
    try { await uploadAccountFile(accountId, file, { name: fname.trim() || file.name, typeId: ftype, countryId: fcountry }); setModal(false); setTick((x) => x + 1) }
    catch { setMerr(t('err_save')) } finally { setUploading(false) }
  }
  async function confirmDelete() {
    if (!toDelete) return
    try { await deleteAccountFile(accountId, toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) }
  }

  return (
    <div style={{ ...card, padding: 20 }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, marginBottom: 16 }}>
        <h3 style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 16, fontWeight: 600, margin: 0 }}><PaperclipIcon />{t('fi_section')}</h3>
        {allow('files.create') && <button style={{ ...btnGhost, height: 34, color: 'var(--color-primary)', borderColor: 'var(--color-primary)' }} onClick={openModal}><PlusIcon />{t('fi_add')}</button>}
      </div>
      {loading ? (
        <div style={{ textAlign: 'center', color: 'var(--color-muted-foreground)', padding: 16 }}>{t('loading')}</div>
      ) : rows.length === 0 ? (
        <div style={{ borderRadius: 8, background: 'var(--color-muted,rgba(0,0,0,.05))', color: 'var(--color-muted-foreground)', padding: '12px 16px', fontSize: 14 }}>{t('fi_empty')}</div>
      ) : (
        <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
          {rows.map((r) => (
            <div key={r.id} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 12, border: '1px solid var(--color-border)', borderRadius: 8, padding: '8px 12px' }}>
              <a href={r.path} target="_blank" rel="noreferrer" style={{ display: 'flex', alignItems: 'center', gap: 8, color: 'var(--color-foreground)', fontSize: 14, fontWeight: 500, textDecoration: 'none', overflow: 'hidden' }}>
                <PaperclipIcon /><span style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{r.name || r.path}</span>
              </a>
              {allow('files.delete') && <button style={actionBtn('#ef4444')} title={t('del')} onClick={() => setToDelete(r)}><TrashIcon /></button>}
            </div>
          ))}
        </div>
      )}

      {modal && (
        <div onClick={(e) => { if (e.target === e.currentTarget) setModal(false) }} style={{ position: 'fixed', inset: 0, zIndex: 60, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)', padding: 16 }}>
          <input ref={fileRef} type="file" style={{ display: 'none' }} onChange={onPick} />
          <div style={{ ...card, width: '100%', maxWidth: 560, maxHeight: '90vh', overflow: 'auto' }}>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '14px 20px', borderBottom: '1px solid var(--color-border)' }}>
              <h3 style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 16, fontWeight: 600, margin: 0 }}><PaperclipIcon />{t('fi_modal_title')}</h3>
              <button style={{ ...iconBtn, width: 24, height: 24 }} onClick={() => setModal(false)}>✕</button>
            </div>
            <div style={{ padding: 20, display: 'flex', flexDirection: 'column', gap: 14 }}>
              <p style={{ margin: 0, fontSize: 13, color: 'var(--color-muted-foreground)' }}>{t('fi_modal_desc')}</p>
              {merr && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14 }}>{merr}</div>}
              <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                <button style={{ ...btnGhost, height: 36 }} onClick={() => fileRef.current?.click()}>{t('fi_select')}</button>
                <span style={{ fontSize: 13, color: file ? 'var(--color-foreground)' : 'var(--color-muted-foreground)', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{file ? file.name : '—'}</span>
              </div>
              <div><label style={label}>{t('fi_modal_name')}</label><input style={inputCss} value={fname} onChange={(e) => setFname(e.target.value)} autoComplete="off" /></div>
              <div><label style={label}>{t('fi_type')}<span style={{ color: '#ef4444', marginLeft: 2 }}>*</span></label>
                <select style={inputCss} value={ftype} onChange={(e) => setFtype(Number(e.target.value))}>
                  <option value={0}>{t('fi_choose')}</option>
                  {opts.types.map((o) => <option key={o.id} value={o.id}>{o.name}</option>)}
                </select>
              </div>
              <div><label style={label}>{t('fi_country')}<span style={{ color: '#ef4444', marginLeft: 2 }}>*</span></label>
                <select style={inputCss} value={fcountry} onChange={(e) => setFcountry(Number(e.target.value))}>
                  <option value={0}>{t('fi_choose')}</option>
                  <option value={-1}>{t('fi_all_country')}</option>
                  {opts.countries.map((o) => <option key={o.id} value={o.id}>{o.name}</option>)}
                </select>
              </div>

              {/* Section repliable : Ajouter un type de fichier */}
              <div style={{ border: '1px solid var(--color-border)', borderRadius: 8, overflow: 'hidden' }}>
                <button onClick={() => setShowType((v) => !v)} style={{ display: 'flex', alignItems: 'center', gap: 8, width: '100%', textAlign: 'left', border: 0, background: 'var(--color-muted,rgba(0,0,0,.04))', padding: '10px 12px', fontSize: 13, fontWeight: 600, color: 'var(--color-foreground)', cursor: 'pointer' }}>
                  <span style={{ transform: showType ? 'rotate(90deg)' : 'none', transition: 'transform .15s' }}>›</span>{t('fi_add_type')}
                </button>
                {showType && (
                  <div style={{ padding: 12, display: 'flex', flexDirection: 'column', gap: 10 }}>
                    {terr && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '6px 12px', fontSize: 13 }}>{terr}</div>}
                    <div><label style={label}>{t('fi_type_code')}<span style={{ color: '#ef4444', marginLeft: 2 }}>*</span></label><input style={inputCss} value={tcode} maxLength={10} onChange={(e) => setTcode(e.target.value)} autoComplete="off" /></div>
                    <div><label style={label}>{t('fi_type_name')}<span style={{ color: '#ef4444', marginLeft: 2 }}>*</span></label><input style={inputCss} value={tname} maxLength={45} onChange={(e) => setTname(e.target.value)} autoComplete="off" /></div>
                    <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                      <button style={{ ...btnGhost, height: 34, color: 'var(--color-primary)', borderColor: 'var(--color-primary)' }} onClick={addType}><PlusIcon />{t('fi_type_add')}</button>
                    </div>
                  </div>
                )}
              </div>
            </div>
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, padding: '12px 20px', borderTop: '1px solid var(--color-border)' }}>
              <button style={btnGhost} onClick={() => setModal(false)} disabled={uploading}>{t('cancel')}</button>
              <button style={{ ...btnGhost, color: 'var(--color-primary)', borderColor: 'var(--color-primary)' }} onClick={save} disabled={uploading}>{uploading ? t('fi_uploading') : t('save')}</button>
            </div>
          </div>
        </div>
      )}

      {toDelete && (
        <ConfirmModal title={t('fi_del_title')} message={t('fi_del_confirm', { u: toDelete.name || `#${toDelete.id}` })}
          confirmLabel={t('del')} cancelLabel={t('cancel')} onConfirm={confirmDelete} onClose={() => setToDelete(null)} />
      )}
    </div>
  )
}
