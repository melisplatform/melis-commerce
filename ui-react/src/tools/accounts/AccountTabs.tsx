import { useEffect, useRef, useState, type ChangeEvent, type ReactNode } from 'react'
import { useNavigate } from 'react-router-dom'
import {
  fetchAccountContacts, fetchAccountOrders, fetchAccountFiles,
  uploadAccountFile, deleteAccountFile, fetchAddressOptions, fetchFileOptions, createFileType,
  fetchOrderStatuses, fetchAllContactOptions, linkAccountContact, unlinkAccountContact, setAccountContactDefault,
  type CompanyData, type AccountContact, type AccountAddress, type AddressOptions, type AccountOrder, type AccountOrderSortKey, type AccountFile, type FileOptions,
} from './api'
import { fetchContactById } from '../contacts/api'
import { useKeysetList } from '../../shared/use-keyset-list'
import { card, inputCss, btnGhost, iconBtn, label, th, td } from '../../shared/styles'
import { StatusBadge } from '../../shared/widgets'
import { SimpleTable } from '../../shared/SimpleTable'
import { LinkHeader } from '../../shared/LinkHeader'
import { ConfirmModal } from '../../shared/ConfirmModal'
import { DateRangeFilter } from '../../shared/DateRangeFilter'
import { AddressEditor } from '../../shared/AddressEditor'
import { DatePicker } from '../../shared/DatePicker'
import { TrashIcon, PencilIcon, StarIcon, CheckIcon, UserPlusIcon, PaperclipIcon, PlusIcon, ImageIcon, SortIcon, Spinner } from '../../shared/icons'
import { fmtDate, type T } from '../../shared/i18n'
import { notify } from '../../shared/notify'
import { useIsNarrow } from '../../shared/useIsNarrow'
import type { Option } from '../../shared/api'
import { FormErrorBanner } from '../../shared/melis-form-errors'

/** Lit un fichier en data URL base64 (pour le logo société). */
function readFileDataUrl(file: File): Promise<string> {
  return new Promise((res, rej) => { const r = new FileReader(); r.onload = () => res(String(r.result)); r.onerror = rej; r.readAsDataURL(file) })
}

const CONTACT_ROUTE = '/melis-commerce/contact-list'

const sectionTitle = { fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)', margin: '0 0 12px' } as const

// ── Société (éditable, CONTRÔLÉE par AccountForm) — sauvée par le bouton du haut ──
export const EMPTY_CO: Omit<CompanyData, 'id'> = {
  name: '', numberId: '', vatNumber: '', group: '', employees: 0, compCreationDate: '',
  addNumber: '', addStreet: '', addBuilding: '', addZip: '', addCity: '', addState: '', addCountry: '', phone: '', website: '', logo: '',
}
export function CompanyTab({ company, onChange, loading, t, nameRequired }: {
  company: Omit<CompanyData, 'id'>; onChange: (c: Omit<CompanyData, 'id'>) => void; loading: boolean; t: T; nameRequired?: boolean
}) {
  const narrow = useIsNarrow()
  const set = <K extends keyof typeof company>(k: K, v: (typeof company)[K]) => onChange({ ...company, [k]: v })
  const field = (k: keyof typeof company, lbl: string, type: 'text' | 'number' = 'text', required?: boolean) => (
    <div>
      <label style={{ ...label, display: 'flex', alignItems: 'center', gap: 4 }}>{lbl} {required && <span style={{ color: '#ef4444' }}>*</span>}</label>
      <input style={{ ...inputCss, ...(required && !String(company[k] ?? '').trim() ? { borderColor: '#fca5a5' } : {}) }} type={type} value={String(company[k] ?? '')}
        onChange={(e) => set(k, (type === 'number' ? Number(e.target.value) : e.target.value) as never)} autoComplete="off" />
    </div>
  )
  if (loading) return <div style={{ display: 'flex', justifyContent: 'center', minHeight: '30vh', alignItems: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
  return (
    <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 20, alignItems: 'start' }}>
      <div style={{ ...card, padding: 28, display: 'flex', flexDirection: 'column', gap: 16 }}>
        <h3 style={sectionTitle}>{t('co_section_id')}</h3>
        {field('name', t('co_name'), 'text', nameRequired)}
        <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 14 }}>{field('numberId', t('co_number'))}{field('vatNumber', t('co_vat'))}</div>
        <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 14 }}>{field('group', t('co_group'))}{field('employees', t('co_employees'), 'number')}</div>
        <div>
          <label style={label}>{t('co_creation_date')}</label>
          <DatePicker t={t} value={company.compCreationDate} onChange={(v) => set('compCreationDate', v)} />
        </div>
        {field('website', t('co_website'))}
      </div>
      <div style={{ ...card, padding: 28, display: 'flex', flexDirection: 'column', gap: 16 }}>
        <h3 style={sectionTitle}>{t('co_section_addr')}</h3>
        <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '90px 1fr', gap: 14 }}>{field('addNumber', t('co_address'))}{field('addStreet', t('co_street'))}</div>
        {field('addBuilding', t('co_building'))}
        <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '120px 1fr', gap: 14 }}>{field('addZip', t('co_zip'))}{field('addCity', t('co_city'))}</div>
        <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 14 }}>{field('addState', t('co_state'))}{field('addCountry', t('co_country'))}</div>
        {field('phone', t('co_phone'))}
      </div>
      <div style={{ ...card, padding: 28, gridColumn: '1 / -1' }}>
        <h3 style={sectionTitle}>{t('co_logo')}</h3>
        <CompanyLogoField value={company.logo} onChange={(v) => set('logo', v)} t={t} />
      </div>
    </div>
  )
}

// ── Logo société (upload/aperçu/suppression) ────────────────────────────────────
function CompanyLogoField({ value, onChange, t }: { value: string; onChange: (v: string) => void; t: T }) {
  const inputRef = useRef<HTMLInputElement>(null)
  const [err, setErr] = useState('')
  const [confirmRemove, setConfirmRemove] = useState(false)

  async function onPick(e: ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0]
    e.target.value = ''
    if (!file) return
    setErr('')
    if (!file.type.startsWith('image/')) { setErr(t('co_logo_err_type')); return }
    if (file.size > 500 * 1024) { setErr(t('co_logo_err_size')); return }
    onChange(await readFileDataUrl(file))
  }

  return (
    <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
      <div style={{ width: 96, height: 96, borderRadius: 8, border: '1px solid var(--color-border)', background: 'var(--color-muted)', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden', flexShrink: 0 }}>
        {value ? <img src={value} alt="" style={{ width: '100%', height: '100%', objectFit: 'contain' }} /> : <ImageIcon />}
      </div>
      <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
        <div style={{ display: 'flex', gap: 8 }}>
          <button type="button" style={btnGhost} onClick={() => inputRef.current?.click()}>{t(value ? 'co_logo_change' : 'co_logo_upload')}</button>
          {value && <button type="button" style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('co_logo_remove')} onClick={() => setConfirmRemove(true)}><TrashIcon /></button>}
        </div>
        <input ref={inputRef} type="file" accept="image/*" style={{ display: 'none' }} onChange={onPick} />
        {err ? <span style={{ fontSize: 12, color: 'var(--color-destructive,#ef4444)' }}>{err}</span> : <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>{t('co_logo_hint')}</span>}
      </div>
      {confirmRemove && (
        <ConfirmModal title={t('co_logo_remove')} message={t('co_logo_remove_confirm')}
          confirmLabel={t('del')} cancelLabel={t('cancel')}
          onConfirm={() => { onChange(''); setConfirmRemove(false) }} onClose={() => setConfirmRemove(false)} />
      )}
    </div>
  )
}

// ── Contacts ───────────────────────────────────────────────────────────────────
/**
 * accountId === null → STAGED mode: the account doesn't exist yet (New Account form). Picks are held
 * in `pending` (owned by the parent, AccountPage.tsx) instead of hitting the link/unlink API, and get
 * committed once the account is actually created (first pending = the account's default contact,
 * created atomically with it — see AccountPage.tsx submit() — required in contact_name mode since the
 * account's name is derived from that contact and the tab used to be disabled pre-save, silently
 * allowing a permanently nameless/contact-less account through).
 */
export function ContactsTab({ accountId, pending, onPendingChange, t, can }: {
  accountId: number | null; pending?: AccountContact[]; onPendingChange?: (rows: AccountContact[]) => void
  t: T; can?: (cap: string) => boolean
}) {
  const allow = (cap: string) => (can ? can(cap) : true)
  const navigate = useNavigate()
  const staged = accountId === null
  const [liveRows, setLiveRows] = useState<AccountContact[]>([])
  const [opts, setOpts] = useState<Option[]>([])
  const [loading, setLoading] = useState(!staged)
  const [tick, setTick] = useState(0)
  const [toUnlink, setToUnlink] = useState<AccountContact | null>(null)
  useEffect(() => {
    if (staged) return
    setLoading(true)
    fetchAccountContacts(accountId).then((r) => setLiveRows(r.items)).catch(() => null).finally(() => setLoading(false))
  }, [staged, accountId, tick])
  useEffect(() => { fetchAllContactOptions().then(setOpts).catch(() => null) }, [])
  const rows = staged ? (pending ?? []) : liveRows
  const linkedIds = new Set(rows.map((r) => r.id))
  const available = opts.filter((o) => !linkedIds.has(o.id))
  async function link(id: number) {
    if (staged) {
      const c = await fetchContactById(id)
      onPendingChange?.([
        ...rows,
        { id: c.id, status: c.status, firstname: c.firstname, name: c.name, email: c.email, civility: c.civility, civilityName: c.civilityName, isMain: rows.length === 0 ? 1 : 0, isDefaultAccount: 0 },
      ])
      return
    }
    await linkAccountContact(accountId, id); setTick((x) => x + 1)
  }
  async function setDefault(id: number) {
    if (staged) { onPendingChange?.(rows.map((r) => ({ ...r, isMain: r.id === id ? 1 : 0 }))); return }
    try { await setAccountContactDefault(accountId, id); setTick((x) => x + 1) } catch { /* */ }
  }
  async function confirmUnlink() {
    if (!toUnlink) return
    if (staged) {
      const left = rows.filter((r) => r.id !== toUnlink.id)
      if (toUnlink.isMain && left.length) left[0] = { ...left[0], isMain: 1 }
      onPendingChange?.(left)
      setToUnlink(null)
      return
    }
    try { await unlinkAccountContact(accountId, toUnlink.id); setToUnlink(null); setTick((x) => x + 1) } catch { setToUnlink(null) }
  }
  return (
    <div>
      <SimpleTable<AccountContact> rows={rows} rowKey={(r) => r.id} empty={t('c_empty')} loading={loading} loadingText={t('loading')}
        t={t} onRefresh={() => setTick((x) => x + 1)}
        onAdd={allow('contacts.create') ? () => navigate(`${CONTACT_ROUTE}/new`) : undefined} addIcon={<UserPlusIcon />} addTitle={t('c_add')}
        toolbarEnd={allow('contacts.create') ? <LinkHeader inline options={available} placeholder={t('c_link_ph')} linkLabel={t('c_link')} onLink={link} /> : undefined}
        cols={[
          { key: 'firstname', label: t('c_firstname'), render: (r) => r.firstname || '—' },
          { key: 'name', label: t('c_name'), essential: true, render: (r) => <span style={{ fontWeight: 500 }}>{r.name || '—'}</span> },
          { key: 'email', label: t('c_email'), render: (r) => r.email || '—' },
          { key: 'status', label: t('col_status'), render: (r) => <StatusBadge active={r.status === 1} t={t} /> },
          { key: 'def_contact', label: t('c_def_contact'), render: (r) => (r.isMain ? <StarIcon /> : '—') },
          { key: 'def_account', label: t('c_def_account'), render: (r) => (r.isDefaultAccount ? <StarIcon /> : '—') },
          { key: 'action', label: t('action'), width: 120, essential: true, render: (r) => (
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 6 }}>
              <button style={iconBtn} title={t('edit')} onClick={() => navigate(`${CONTACT_ROUTE}/${r.id}`)}><PencilIcon /></button>
              {!r.isMain && <button style={iconBtn} title={t('set_default')} onClick={() => setDefault(r.id)}><CheckIcon /></button>}
              {allow('contacts.delete') && <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('unlink')} onClick={() => setToUnlink(r)}><TrashIcon /></button>}
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

// ── Commandes (lecture seule) — scroll infini keyset + tri server-side ────────
const ORDER_SORTABLE = new Set<AccountOrderSortKey>(['id', 'ref', 'status', 'date'])

export function OrdersTab({ accountId, t }: { accountId: number; t: T }) {
  const [statuses, setStatuses] = useState<Option[]>([])
  const [statusFilter, setStatusFilter] = useState<number | null>(null)
  const [dateFrom, setDateFrom] = useState('')
  const [dateTo, setDateTo] = useState('')
  const [searchInput, setSearchInput] = useState('')
  const [search, setSearch] = useState('')
  const [tick, setTick] = useState(0)
  useEffect(() => { fetchOrderStatuses().then(setStatuses).catch(() => null) }, [])

  const { items, total, loading, hasMore, sentinelRef, sortCol, sortDir, toggleSort } = useKeysetList<AccountOrder>({
    fetcher: (a) => fetchAccountOrders(accountId, { ...a, sort: a.sort as AccountOrderSortKey, search, status: statusFilter, from: dateFrom || undefined, to: dateTo || undefined }),
    deps: [accountId, search, statusFilter, dateFrom, dateTo, tick],
    defaultSort: 'id',
    defaultDir: 'desc',
  })

  const COLS: { id: AccountOrderSortKey; label: string; render: (r: AccountOrder) => ReactNode; width?: number }[] = [
    { id: 'id', label: t('col_id'), width: 70, render: (r) => <span style={{ color: 'var(--color-muted-foreground)' }}>{r.id}</span> },
    { id: 'ref', label: t('o_ref'), render: (r) => <span style={{ fontWeight: 500 }}>{r.reference || '—'}</span> },
    { id: 'status', label: t('o_status'), render: (r) => r.statusName || `#${r.status}` },
    { id: 'date', label: t('o_date'), render: (r) => fmtDate(r.dateCreation) },
  ]

  const ctl = { ...inputCss, height: 34, width: 'auto', padding: '0 8px' } as const
  const lbl = { display: 'inline-flex', alignItems: 'center', gap: 6, fontSize: 13, color: 'var(--color-muted-foreground)' } as const

  return (
    <div>
      <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8, marginBottom: 12 }}>
        <input style={{ ...inputCss, height: 34, minWidth: 180 }} value={searchInput}
          onChange={(e) => { const v = e.target.value; setSearchInput(v); if (v === '') setSearch('') }}
          onKeyDown={(e) => { if (e.key === 'Enter') setSearch(searchInput) }} placeholder={t('ph_search_order')} />
        <label style={lbl}>{t('o_status_label')}
          <select style={ctl} value={statusFilter ?? ''} onChange={(e) => setStatusFilter(e.target.value === '' ? null : Number(e.target.value))}>
            <option value="">{t('o_status_all')}</option>
            {statuses.map((s) => <option key={s.id} value={s.id}>{s.name}</option>)}
          </select>
        </label>
        <DateRangeFilter from={dateFrom} to={dateTo} onChange={(f, dt) => { setDateFrom(f); setDateTo(dt) }} t={t} />
        <button style={{ ...btnGhost, height: 34, marginLeft: 'auto' }} onClick={() => setTick((x) => x + 1)} title={t('refresh')}>↻</button>
      </div>

      <div style={{ ...card, overflowX: 'auto' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 520 }}>
          <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
            <tr>
              {COLS.map((c) => {
                const sortable = ORDER_SORTABLE.has(c.id)
                return (
                  <th key={c.id} style={{ ...th, ...(c.width ? { width: c.width } : {}), cursor: sortable ? 'pointer' : 'default', userSelect: 'none' }} onClick={sortable ? () => toggleSort(c.id) : undefined}>
                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4 }}>{c.label}{sortable && <SortIcon dir={sortCol === c.id ? sortDir : null} />}</span>
                  </th>
                )
              })}
            </tr>
          </thead>
          <tbody>
            {loading && items.length === 0 ? (
              <tr><td colSpan={COLS.length} style={{ ...td, textAlign: 'center', padding: '30px 16px', color: 'var(--color-muted-foreground)' }}>{t('loading')}</td></tr>
            ) : items.length === 0 ? (
              <tr><td colSpan={COLS.length} style={{ ...td, textAlign: 'center', padding: '30px 16px', color: 'var(--color-muted-foreground)' }}>{t('o_empty')}</td></tr>
            ) : items.map((r) => (
              <tr key={r.id}>
                {COLS.map((c) => <td key={c.id} style={td}>{c.render(r)}</td>)}
              </tr>
            ))}
          </tbody>
        </table>
        <div ref={sentinelRef} style={{ height: 1 }} />
        {loading && items.length > 0 && (
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, padding: '12px 16px', fontSize: 12, color: 'var(--color-muted-foreground)' }}><Spinner />{t('loading')}</div>
        )}
        {!hasMore && items.length > 0 && (
          <div style={{ padding: '10px 16px', textAlign: 'center', fontSize: 12, color: 'var(--color-muted-foreground)' }}>{t('o_count', { n: total })}</div>
        )}
      </div>
    </div>
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
              {allow('files.delete') && <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => setToDelete(r)}><TrashIcon /></button>}
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
              {merr && <FormErrorBanner title={merr} />}
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
