import { useEffect, useState } from 'react'
import { makeT } from '../../../shared/i18n'
import { DICT } from '../dict'
import { checkoutValidateAddresses } from '../api'
import { fetchAccountAddresses, saveAccountAddresses, fetchAddressOptions } from '../../accounts/api'
import { AddressEditor } from '../../../shared/AddressEditor'
import type { Address, AddressOptions } from '../../../shared/address'
import { card, inputCss, btnPrimary, btnGhost, label } from '../../../shared/styles'
import { notify } from '../../../shared/notify'

export function WizardAddressesStep({ clientId, onBack, onNext }: {
  clientId: number; onBack: () => void; onNext: (billingId: number, deliveryId: number) => void
}) {
  const t = makeT(DICT)
  const [addresses, setAddresses] = useState<Address[]>([])
  const [options, setOptions] = useState<AddressOptions>({ types: [], civilities: [] })
  const [loading, setLoading] = useState(false)
  const [saving, setSaving] = useState(false)
  const [billingId, setBillingId] = useState<number | null>(null)
  const [deliveryId, setDeliveryId] = useState<number | null>(null)
  const [sameAsDelivery, setSameAsDelivery] = useState(true)
  const [validating, setValidating] = useState(false)

  useEffect(() => {
    setLoading(true)
    Promise.all([fetchAccountAddresses(clientId), fetchAddressOptions()])
      .then(([a, o]) => { setAddresses(a.items); setOptions(o) })
      .catch(() => null)
      .finally(() => setLoading(false))
  }, [clientId])

  const savedAddresses = addresses.filter((a) => a.id > 0)

  useEffect(() => {
    if (savedAddresses.length && deliveryId === null) setDeliveryId(savedAddresses[0].id)
  }, [savedAddresses, deliveryId])

  async function saveAddresses() {
    setSaving(true)
    try {
      await saveAccountAddresses(clientId, addresses)
      const r = await fetchAccountAddresses(clientId)
      setAddresses(r.items)
      notify('ok', t('checkout_step_addresses'), t('save'))
    } catch (e) {
      notify('ko', t('checkout_step_addresses'), e instanceof Error ? e.message : 'Error')
    } finally {
      setSaving(false)
    }
  }

  async function next() {
    const billing = sameAsDelivery ? deliveryId : billingId
    if (!deliveryId || !billing) { notify('ko', t('checkout_step_addresses'), t('checkout_addresses_required')); return }
    setValidating(true)
    try {
      await checkoutValidateAddresses(billing, deliveryId)
      onNext(billing, deliveryId)
    } catch (e) {
      notify('ko', t('checkout_step_addresses'), e instanceof Error ? e.message : 'Error')
    } finally {
      setValidating(false)
    }
  }

  return (
    <div>
      <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 4px' }}>{t('checkout_step_addresses')}</h3>
      <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: '0 0 16px' }}>{t('checkout_step_addresses_hint')}</p>

      {loading ? (
        <div style={{ padding: 24, textAlign: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
      ) : (
        <>
          <AddressEditor addresses={addresses} onChange={setAddresses} options={options} t={t} />
          <div style={{ marginTop: 12 }}>
            <button style={btnGhost} disabled={saving} onClick={saveAddresses}>{saving ? '…' : t('checkout_save_addresses')}</button>
          </div>

          {savedAddresses.length > 0 && (
            <div style={{ ...card, padding: 16, marginTop: 20, display: 'flex', flexDirection: 'column', gap: 12 }}>
              <div>
                <label style={label}>{t('checkout_delivery_address')}</label>
                <select style={inputCss} value={deliveryId ?? ''} onChange={(e) => setDeliveryId(Number(e.target.value))}>
                  {savedAddresses.map((a) => <option key={a.id} value={a.id}>{a.addressName}</option>)}
                </select>
              </div>
              <label style={{ display: 'inline-flex', alignItems: 'center', gap: 8, fontSize: 13, cursor: 'pointer' }}>
                <input type="checkbox" checked={sameAsDelivery} onChange={(e) => setSameAsDelivery(e.target.checked)} />
                {t('checkout_same_as_delivery')}
              </label>
              {!sameAsDelivery && (
                <div>
                  <label style={label}>{t('checkout_billing_address')}</label>
                  <select style={inputCss} value={billingId ?? ''} onChange={(e) => setBillingId(Number(e.target.value))}>
                    <option value="">{t('checkout_choose_address')}</option>
                    {savedAddresses.map((a) => <option key={a.id} value={a.id}>{a.addressName}</option>)}
                  </select>
                </div>
              )}
            </div>
          )}
        </>
      )}

      <div style={{ marginTop: 20, display: 'flex', justifyContent: 'space-between' }}>
        <button style={btnGhost} onClick={onBack}>{t('checkout_back')}</button>
        <button style={btnPrimary} disabled={validating || savedAddresses.length === 0} onClick={next}>{validating ? '…' : t('checkout_next')}</button>
      </div>
    </div>
  )
}
