import { useEffect, useState } from 'react'
import { makeT } from '../../../shared/i18n'
import { DICT } from '../dict'
import { fetchCheckoutContacts, checkoutSelectContact, type CheckoutContact } from '../api'
import { SimpleTable, type SimpleCol } from '../../../shared/SimpleTable'
import { btnPrimary } from '../../../shared/styles'
import { notify } from '../../../shared/notify'

export function WizardContactStep({ onNext }: { onNext: (contactId: number) => void }) {
  const t = makeT(DICT)
  const [items, setItems] = useState<CheckoutContact[]>([])
  const [loading, setLoading] = useState(false)
  const [selecting, setSelecting] = useState<number | null>(null)

  useEffect(() => {
    setLoading(true)
    fetchCheckoutContacts().then((r) => setItems(r.items)).catch(() => null).finally(() => setLoading(false))
  }, [])

  async function pick(c: CheckoutContact) {
    if (selecting) return
    setSelecting(c.id)
    try {
      await checkoutSelectContact(c.id)
      onNext(c.id)
    } catch (e) {
      notify('ko', t('checkout_step_contact'), e instanceof Error ? e.message : 'Error')
    } finally {
      setSelecting(null)
    }
  }

  const cols: SimpleCol<CheckoutContact>[] = [
    { key: 'id', label: t('col_id'), width: 60, render: (r) => <span style={{ color: 'var(--color-muted-foreground)' }}>{r.id}</span> },
    {
      key: 'status', label: t('col_status'), width: 70,
      render: (r) => <span style={{ display: 'inline-flex', width: 10, height: 10, borderRadius: 999, background: r.status === 1 ? '#16a34a' : '#dc2626' }} />,
    },
    { key: 'contact', label: t('checkout_col_contact'), render: (r) => `${r.firstname} ${r.name}`.trim() },
    { key: 'group', label: t('checkout_col_group'), render: (r) => r.groupName || '—' },
    { key: 'email', label: t('col_email'), render: (r) => r.email },
    { key: 'orders', label: t('checkout_col_orders'), width: 90, render: (r) => r.numOrders },
    { key: 'lastOrder', label: t('checkout_col_last_order'), render: (r) => (r.lastOrder ? r.lastOrder.slice(0, 10) : '—') },
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
      <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 4px' }}>{t('checkout_step_contact')}</h3>
      <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: '0 0 16px' }}>{t('checkout_step_contact_hint')}</p>
      <SimpleTable cols={cols} rows={items} rowKey={(r) => r.id} empty={t('checkout_no_contacts')} loading={loading} loadingText={t('loading')} t={t}
        search={(r) => `${r.firstname} ${r.name} ${r.email} ${r.groupName}`} searchPlaceholder={t('checkout_search')} />
    </div>
  )
}
