import { useEffect, useState } from 'react'
import { makeT } from '../../../shared/i18n'
import { DICT } from '../dict'
import { checkoutSelectAccount } from '../api'
import { fetchContactAssociations, type ContactAssociation } from '../../contacts/api'
import { SimpleTable, type SimpleCol } from '../../../shared/SimpleTable'
import { btnPrimary, btnGhost } from '../../../shared/styles'
import { notify } from '../../../shared/notify'

export function WizardAccountStep({ contactId, onBack, onNext }: {
  contactId: number; onBack: () => void; onNext: (clientId: number) => void
}) {
  const t = makeT(DICT)
  const [items, setItems] = useState<ContactAssociation[]>([])
  const [loading, setLoading] = useState(false)
  const [selecting, setSelecting] = useState<number | null>(null)

  useEffect(() => {
    setLoading(true)
    fetchContactAssociations(contactId).then((r) => setItems(r.items)).catch(() => null).finally(() => setLoading(false))
  }, [contactId])

  async function pick(a: ContactAssociation) {
    if (selecting) return
    setSelecting(a.id)
    try {
      await checkoutSelectAccount(a.id)
      onNext(a.id)
    } catch (e) {
      notify('ko', t('checkout_step_account'), e instanceof Error ? e.message : 'Error')
    } finally {
      setSelecting(null)
    }
  }

  const cols: SimpleCol<ContactAssociation>[] = [
    { key: 'id', label: t('col_id'), width: 60, render: (r) => <span style={{ color: 'var(--color-muted-foreground)' }}>{r.id}</span> },
    {
      key: 'status', label: t('col_status'), width: 70,
      render: (r) => <span style={{ display: 'inline-flex', width: 10, height: 10, borderRadius: 999, background: r.status === 1 ? '#16a34a' : '#dc2626' }} />,
    },
    { key: 'name', label: t('checkout_col_account'), render: (r) => r.name },
    { key: 'default', label: t('checkout_col_default_account'), width: 100, render: (r) => (r.isDefaultAccount ? '★' : '') },
    {
      key: 'action', label: '', width: 60,
      render: (r) => (
        <button style={{ ...btnPrimary, height: 30, padding: '0 10px' }} disabled={selecting === r.id} onClick={() => pick(r)}>
          {selecting === r.id ? '…' : '→'}
        </button>
      ),
    },
  ]

  return (
    <div>
      <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 4px' }}>{t('checkout_step_account')}</h3>
      <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: '0 0 16px' }}>{t('checkout_step_account_hint')}</p>
      <SimpleTable cols={cols} rows={items} rowKey={(r) => r.id} empty={t('checkout_no_accounts')} loading={loading} loadingText={t('loading')} t={t} paginate={false} />
      <div style={{ marginTop: 16 }}>
        <button style={btnGhost} onClick={onBack}>{t('checkout_back')}</button>
      </div>
    </div>
  )
}
