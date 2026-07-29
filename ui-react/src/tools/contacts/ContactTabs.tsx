import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import {
  fetchContactAddressOptions, fetchContactAssociations, fetchContactOptions,
  linkContactAssociation, unlinkContactAssociation, setContactAssociationDefault,
  type ContactAddress, type AddressOptions, type ContactAssociation,
} from './api'
import { StatusBadge } from '../../shared/widgets'
import { SimpleTable } from '../../shared/SimpleTable'
import { LinkHeader } from '../../shared/LinkHeader'
import { ConfirmModal } from '../../shared/ConfirmModal'
import { AddressEditor } from '../../shared/AddressEditor'
import { iconBtn } from '../../shared/styles'
import { TrashIcon, PencilIcon, StarIcon, CheckIcon, UserPlusIcon } from '../../shared/icons'
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

// ── Comptes associés (lecture seule) ────────────────────────────────────────────
export function AssociationTab({ contactId, t, can }: { contactId: number; t: T; can?: (cap: string) => boolean }) {
  const allow = (cap: string) => (can ? can(cap) : true)
  const navigate = useNavigate()
  const [rows, setRows] = useState<ContactAssociation[]>([])
  const [opts, setOpts] = useState<Option[]>([])
  const [loading, setLoading] = useState(true)
  const [tick, setTick] = useState(0)
  const [toUnlink, setToUnlink] = useState<ContactAssociation | null>(null)
  useEffect(() => { setLoading(true); fetchContactAssociations(contactId).then((r) => setRows(r.items)).catch(() => null).finally(() => setLoading(false)) }, [contactId, tick])
  useEffect(() => { fetchContactOptions().then((o) => setOpts(o.accounts)).catch(() => null) }, [])
  const linkedIds = new Set(rows.map((r) => r.id))
  const available = opts.filter((o) => !linkedIds.has(o.id))
  const link = async (id: number) => { await linkContactAssociation(contactId, id); setTick((x) => x + 1) }
  const setDefault = async (id: number) => { try { await setContactAssociationDefault(contactId, id); setTick((x) => x + 1) } catch { /* */ } }
  async function confirmUnlink() {
    if (!toUnlink) return
    try { await unlinkContactAssociation(contactId, toUnlink.id); setToUnlink(null); setTick((x) => x + 1) } catch { setToUnlink(null) }
  }
  return (
    <div>
      {allow('association.create') && <LinkHeader options={available} placeholder={t('as_link_ph')} linkLabel={t('as_link')} onLink={link} />}
      <SimpleTable<ContactAssociation> rows={rows} rowKey={(r) => r.id} empty={t('as_empty')} loading={loading} loadingText={t('loading')}
        t={t} search={(r) => r.name} searchPlaceholder={t('ph_search_account')} onRefresh={() => setTick((x) => x + 1)}
        onAdd={() => navigate(`${ACCOUNT_ROUTE}/new`)} addIcon={<UserPlusIcon />} addTitle={t('as_add')}
        cols={[
          { key: 'id', label: t('col_id'), width: 70, render: (r) => <span style={{ color: 'var(--color-muted-foreground)' }}>{r.id}</span> },
          { key: 'account', label: t('as_account'), essential: true, render: (r) => <span style={{ fontWeight: 500 }}>{r.name || '—'}</span> },
          { key: 'status', label: t('as_status'), render: (r) => <StatusBadge active={r.status === 1} t={t} /> },
          { key: 'def_account', label: t('as_def_account'), render: (r) => (r.isDefaultAccount ? <StarIcon /> : '—') },
          { key: 'def_contact', label: t('as_def_contact'), render: (r) => (r.isMain ? <StarIcon /> : '—') },
          { key: 'action', label: t('action'), width: 120, essential: true, render: (r) => (
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 6 }}>
              <button style={iconBtn} title={t('edit')} onClick={() => navigate(`${ACCOUNT_ROUTE}/${r.id}`)}><PencilIcon /></button>
              {!r.isDefaultAccount && <button style={iconBtn} title={t('set_default')} onClick={() => setDefault(r.id)}><CheckIcon /></button>}
              {allow('association.delete') && <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('unlink')} onClick={() => setToUnlink(r)}><TrashIcon /></button>}
            </div>
          ) },
        ]} />
      {toUnlink && (
        <ConfirmModal title={t('as_unlink_title')} message={t('as_unlink_confirm', { u: toUnlink.name || `#${toUnlink.id}` })}
          confirmLabel={t('unlink')} cancelLabel={t('cancel')} onConfirm={confirmUnlink} onClose={() => setToUnlink(null)} />
      )}
    </div>
  )
}
