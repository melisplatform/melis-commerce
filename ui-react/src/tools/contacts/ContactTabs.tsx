import { useEffect, useState, type ReactNode } from 'react'
import { useNavigate } from 'react-router-dom'
import {
  fetchContactAddressOptions, fetchContactAssociations, fetchContactOptions,
  linkContactAssociation, unlinkContactAssociation, setContactAssociationDefault,
  type ContactAddress, type AddressOptions, type ContactAssociation, type ContactAssocSortKey,
} from './api'
import { useKeysetList } from '../../shared/use-keyset-list'
import { StatusBadge } from '../../shared/widgets'
import { LinkHeader } from '../../shared/LinkHeader'
import { ConfirmModal } from '../../shared/ConfirmModal'
import { AddressEditor } from '../../shared/AddressEditor'
import { iconBtn, card, inputCss, btnGhost, th, td } from '../../shared/styles'
import { TrashIcon, PencilIcon, StarIcon, CheckIcon, UserPlusIcon, RefreshIcon, SortIcon, Spinner } from '../../shared/icons'
import type { T } from '../../shared/i18n'
import type { Option } from '../../shared/api'

const ACCOUNT_ROUTE = '/melis-commerce/clients-list'

// ── Adresses du contact (lecture seule) ─────────────────────────────────────────
// Onglet Adresse — éditeur master-détail STAGED partagé (cf. shared/AddressEditor), contrôlé par ContactForm.
export function AddressTab({ addresses, onChange, t }: { addresses: ContactAddress[]; onChange: (a: ContactAddress[]) => void; t: T }) {
  const [opts, setOpts] = useState<AddressOptions>({ types: [], civilities: [] })
  useEffect(() => { fetchContactAddressOptions().then(setOpts).catch(() => null) }, [])
  return <AddressEditor addresses={addresses} onChange={onChange} options={opts} t={t} />
}

// ── Comptes associés — scroll infini keyset + tri server-side ─────────────────────
const ASSOC_SORTABLE = new Set<ContactAssocSortKey>(['id', 'account', 'status', 'def_account', 'def_contact'])

export function AssociationTab({ contactId, t, can }: { contactId: number; t: T; can?: (cap: string) => boolean }) {
  const allow = (cap: string) => (can ? can(cap) : true)
  const navigate = useNavigate()
  const [opts, setOpts] = useState<Option[]>([])
  const [searchInput, setSearchInput] = useState('')
  const [search, setSearch] = useState('')
  const [tick, setTick] = useState(0)
  const [toUnlink, setToUnlink] = useState<ContactAssociation | null>(null)

  const { items, total, loading, hasMore, sentinelRef, sortCol, sortDir, toggleSort } = useKeysetList<ContactAssociation>({
    fetcher: (a) => fetchContactAssociations(contactId, { ...a, sort: a.sort as ContactAssocSortKey, search }),
    deps: [contactId, search, tick],
    defaultSort: 'account',
    defaultDir: 'asc',
  })
  useEffect(() => { fetchContactOptions().then((o) => setOpts(o.accounts)).catch(() => null) }, [])
  const linkedIds = new Set(items.map((r) => r.id))
  const available = opts.filter((o) => !linkedIds.has(o.id))
  const link = async (id: number) => { await linkContactAssociation(contactId, id); setTick((x) => x + 1) }
  const setDefault = async (id: number) => { try { await setContactAssociationDefault(contactId, id); setTick((x) => x + 1) } catch { /* */ } }
  async function confirmUnlink() {
    if (!toUnlink) return
    try { await unlinkContactAssociation(contactId, toUnlink.id); setToUnlink(null); setTick((x) => x + 1) } catch { setToUnlink(null) }
  }

  const COLS: { id: ContactAssocSortKey; label: string; render: (r: ContactAssociation) => ReactNode; width?: number; center?: boolean }[] = [
    { id: 'id', label: t('col_id'), width: 70, render: (r) => <span style={{ color: 'var(--color-muted-foreground)' }}>{r.id}</span> },
    { id: 'account', label: t('as_account'), render: (r) => <span style={{ fontWeight: 500 }}>{r.name || '—'}</span> },
    { id: 'status', label: t('as_status'), render: (r) => <StatusBadge active={r.status === 1} t={t} /> },
    { id: 'def_account', label: t('as_def_account'), center: true, render: (r) => (r.isDefaultAccount ? <StarIcon /> : '—') },
    { id: 'def_contact', label: t('as_def_contact'), center: true, render: (r) => (r.isMain ? <StarIcon /> : '—') },
  ]

  return (
    <div>
      {/* Barre d'outils unique : recherche à gauche ; à droite = lier un compte + ajouter + rafraîchir. */}
      <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 8, marginBottom: 12 }}>
        <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 180, maxWidth: 320 }} value={searchInput}
          onChange={(e) => { const v = e.target.value; setSearchInput(v); if (v === '') setSearch('') }}
          onKeyDown={(e) => { if (e.key === 'Enter') setSearch(searchInput) }} placeholder={t('ph_search_account')} />
        <div style={{ marginLeft: 'auto', display: 'flex', alignItems: 'center', gap: 8, flexWrap: 'wrap' }}>
          {allow('association.create') && <LinkHeader inline options={available} placeholder={t('as_link_ph')} linkLabel={t('as_link')} onLink={link} />}
          <button style={{ ...btnGhost, height: 36 }} onClick={() => navigate(`${ACCOUNT_ROUTE}/new`)} title={t('as_add')}><UserPlusIcon />{t('as_add')}</button>
          <button style={{ ...btnGhost, height: 36 }} onClick={() => setTick((x) => x + 1)} title={t('refresh')}><RefreshIcon /></button>
        </div>
      </div>

      <div style={{ ...card, overflowX: 'auto' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 640 }}>
          <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
            <tr>
              {COLS.map((c) => {
                const sortable = ASSOC_SORTABLE.has(c.id)
                return (
                  <th key={c.id} style={{ ...th, ...(c.width ? { width: c.width } : {}), ...(c.center ? { textAlign: 'center' as const } : {}), cursor: sortable ? 'pointer' : 'default', userSelect: 'none' }} onClick={sortable ? () => toggleSort(c.id) : undefined}>
                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: 4, ...(c.center ? { justifyContent: 'center' } : {}) }}>{c.label}{sortable && <SortIcon dir={sortCol === c.id ? sortDir : null} />}</span>
                  </th>
                )
              })}
              <th style={{ ...th, width: 120, textAlign: 'right' }}>{t('action')}</th>
            </tr>
          </thead>
          <tbody>
            {loading && items.length === 0 ? (
              <tr><td colSpan={COLS.length + 1} style={{ ...td, textAlign: 'center', padding: '30px 16px', color: 'var(--color-muted-foreground)' }}>{t('loading')}</td></tr>
            ) : items.length === 0 ? (
              <tr><td colSpan={COLS.length + 1} style={{ ...td, textAlign: 'center', padding: '30px 16px', color: 'var(--color-muted-foreground)' }}>{t('as_empty')}</td></tr>
            ) : items.map((r) => (
              <tr key={r.id}>
                {COLS.map((c) => <td key={c.id} style={{ ...td, ...(c.center ? { textAlign: 'center' as const } : {}) }}>{c.render(r)}</td>)}
                <td style={{ ...td, textAlign: 'right' }}>
                  <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 6 }}>
                    <button style={iconBtn} title={t('edit')} onClick={() => navigate(`${ACCOUNT_ROUTE}/${r.id}`)}><PencilIcon /></button>
                    {!r.isDefaultAccount && <button style={iconBtn} title={t('set_default')} onClick={() => setDefault(r.id)}><CheckIcon /></button>}
                    {allow('association.delete') && <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('unlink')} onClick={() => setToUnlink(r)}><TrashIcon /></button>}
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
        <div ref={sentinelRef} style={{ height: 1 }} />
        {loading && items.length > 0 && (
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8, padding: '12px 16px', fontSize: 12, color: 'var(--color-muted-foreground)' }}><Spinner />{t('loading')}</div>
        )}
        {!hasMore && items.length > 0 && (
          <div style={{ padding: '10px 16px', textAlign: 'center', fontSize: 12, color: 'var(--color-muted-foreground)' }}>{t('as_count', { n: total })}</div>
        )}
      </div>

      {toUnlink && (
        <ConfirmModal title={t('as_unlink_title')} message={t('as_unlink_confirm', { u: toUnlink.name || `#${toUnlink.id}` })}
          confirmLabel={t('unlink')} cancelLabel={t('cancel')} onConfirm={confirmUnlink} onClose={() => setToUnlink(null)} />
      )}
    </div>
  )
}
