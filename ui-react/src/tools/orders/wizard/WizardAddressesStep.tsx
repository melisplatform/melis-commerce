import { useEffect, useState } from 'react'
import type { ReactNode } from 'react'
import { makeT } from '../../../shared/i18n'
import type { T } from '../../../shared/i18n'
import { DICT } from '../dict'
import { checkoutValidateAddresses } from '../api'
import { fetchAccountAddresses, saveAccountAddresses, fetchAddressOptions } from '../../accounts/api'
import { AddressForm } from '../../../shared/AddressEditor'
import { newAddress, type Address, type AddressOptions } from '../../../shared/address'
import { card, inputCss, btnPrimary, btnGhost, label } from '../../../shared/styles'
import { PlusIcon } from '../../../shared/icons'
import { notify } from '../../../shared/notify'

const headerCss = { background: 'var(--color-primary)', color: 'var(--color-primary-foreground,#fff)', padding: '10px 16px', fontWeight: 600, fontSize: 14 } as const
const bodyCss = { padding: 16, display: 'flex', flexDirection: 'column', gap: 12 } as const
const createBtnCss = { ...btnGhost, alignSelf: 'flex-end' } as const

type Mode = 'none' | 'existing' | 'new'

/** Carte "sélection" : titre + (contenu additionnel, ex. la case "même adresse") + dropdown
 *  d'adresses existantes + bouton "créer une nouvelle adresse". Reproduit le zonage legacy. */
function PickerCard({
  title, extra, hideSelector, selectLabel, choosePh, createLabel, addresses, mode, selectedId, onSelect, onClear, onCreateNew,
}: {
  title: string; extra?: ReactNode; hideSelector?: boolean; selectLabel: string; choosePh: string; createLabel: string
  addresses: Address[]; mode: Mode; selectedId: number | null
  onSelect: (id: number) => void; onClear: () => void; onCreateNew: () => void
}) {
  return (
    <div style={{ ...card, overflow: 'hidden' }}>
      <div style={headerCss}>{title}</div>
      <div style={bodyCss}>
        {extra}
        {!hideSelector && (
          <>
            <div>
              <label style={label}>{selectLabel}</label>
              <select style={inputCss} value={mode === 'existing' ? (selectedId ?? '') : ''}
                onChange={(e) => (e.target.value ? onSelect(Number(e.target.value)) : onClear())}>
                <option value="">{choosePh}</option>
                {addresses.map((a) => <option key={a.id} value={a.id}>{a.addressName}</option>)}
              </select>
            </div>
            <button style={createBtnCss} onClick={onCreateNew}><PlusIcon />{createLabel}</button>
          </>
        )}
      </div>
    </div>
  )
}

/** Carte "détails" séparée : les champs de l'adresse choisie (lecture seule) ou du brouillon
 *  en création (éditable). N'apparaît qu'une fois une sélection/création lancée. */
function AddressFormCard({ mode, draft, options, saving, t, onDraftChange, onSaveDraft }: {
  mode: Mode; draft: Address | null; options: AddressOptions; saving: boolean; t: T
  onDraftChange: (a: Address) => void; onSaveDraft: () => void
}) {
  if (mode === 'none' || !draft) return null
  return (
    <div style={{ ...card, padding: 20, display: 'flex', flexDirection: 'column', gap: 14 }}>
      <fieldset disabled={mode === 'existing'} style={{ border: 0, padding: 0, margin: 0, opacity: mode === 'existing' ? 0.7 : 1, display: 'flex', flexDirection: 'column', gap: 14 }}>
        <AddressForm value={draft} onChange={onDraftChange} opts={options} t={t} />
      </fieldset>
      {mode === 'new' && (
        <button style={{ ...btnPrimary, alignSelf: 'flex-start' }} disabled={saving} onClick={onSaveDraft}>
          {saving ? '…' : t('checkout_save_addresses')}
        </button>
      )}
    </div>
  )
}

export function WizardAddressesStep({ clientId, onBack, onNext }: {
  clientId: number; onBack: () => void; onNext: (billingId: number, deliveryId: number) => void
}) {
  const t = makeT(DICT)
  const [addresses, setAddresses] = useState<Address[]>([])
  const [options, setOptions] = useState<AddressOptions>({ types: [], civilities: [] })
  const [loading, setLoading] = useState(false)
  const [validating, setValidating] = useState(false)

  const [deliveryMode, setDeliveryMode] = useState<Mode>('none')
  const [deliveryId, setDeliveryId] = useState<number | null>(null)
  const [deliveryDraft, setDeliveryDraft] = useState<Address | null>(null)
  const [savingDelivery, setSavingDelivery] = useState(false)

  const [sameAsDelivery, setSameAsDelivery] = useState(true)
  const [billingMode, setBillingMode] = useState<Mode>('none')
  const [billingId, setBillingId] = useState<number | null>(null)
  const [billingDraft, setBillingDraft] = useState<Address | null>(null)
  const [savingBilling, setSavingBilling] = useState(false)

  useEffect(() => {
    setLoading(true)
    Promise.all([fetchAccountAddresses(clientId), fetchAddressOptions()])
      .then(([a, o]) => { setAddresses(a.items); setOptions(o) })
      .catch(() => null)
      .finally(() => setLoading(false))
  }, [clientId])

  const savedAddresses = addresses.filter((a) => a.id > 0)

  // Persiste un brouillon (nouvelle adresse) et résout son id réel — une seule nouvelle
  // adresse enregistrée à la fois ici, donc pas d'ambiguïté sur laquelle vient d'apparaître.
  async function persistDraft(draft: Address): Promise<Address> {
    const prevIds = new Set(addresses.map((a) => a.id))
    await saveAccountAddresses(clientId, [...addresses, draft])
    const r = await fetchAccountAddresses(clientId)
    setAddresses(r.items)
    return r.items.find((a) => !prevIds.has(a.id)) ?? r.items[r.items.length - 1]
  }

  async function saveDeliveryDraft() {
    if (!deliveryDraft) return
    if (!deliveryDraft.addressName.trim()) { notify('ko', t('checkout_step_addresses'), t('ad_err_name')); return }
    setSavingDelivery(true)
    try {
      const created = await persistDraft(deliveryDraft)
      setDeliveryId(created.id); setDeliveryMode('existing')
    } catch (e) { notify('ko', t('checkout_step_addresses'), e instanceof Error ? e.message : 'Error') }
    finally { setSavingDelivery(false) }
  }

  async function saveBillingDraft() {
    if (!billingDraft) return
    if (!billingDraft.addressName.trim()) { notify('ko', t('checkout_step_addresses'), t('ad_err_name')); return }
    setSavingBilling(true)
    try {
      const created = await persistDraft(billingDraft)
      setBillingId(created.id); setBillingMode('existing')
    } catch (e) { notify('ko', t('checkout_step_addresses'), e instanceof Error ? e.message : 'Error') }
    finally { setSavingBilling(false) }
  }

  function selectExisting(id: number, which: 'delivery' | 'billing') {
    const addr = addresses.find((a) => a.id === id)
    if (!addr) return
    if (which === 'delivery') { setDeliveryId(id); setDeliveryDraft(addr); setDeliveryMode('existing') }
    else { setBillingId(id); setBillingDraft(addr); setBillingMode('existing') }
  }

  function createNew(which: 'delivery' | 'billing') {
    const draft = newAddress(options.types[0]?.id ?? 1)
    if (which === 'delivery') { setDeliveryId(null); setDeliveryDraft(draft); setDeliveryMode('new') }
    else { setBillingId(null); setBillingDraft(draft); setBillingMode('new') }
  }

  function clearSelection(which: 'delivery' | 'billing') {
    if (which === 'delivery') { setDeliveryId(null); setDeliveryDraft(null); setDeliveryMode('none') }
    else { setBillingId(null); setBillingDraft(null); setBillingMode('none') }
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
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 20, alignItems: 'start' }}>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
            <PickerCard
              title={t('checkout_delivery_address')} selectLabel={t('checkout_delivery_select')} choosePh={t('checkout_choose_ph')}
              createLabel={t('checkout_create_delivery_btn')} addresses={savedAddresses}
              mode={deliveryMode} selectedId={deliveryId}
              onSelect={(id) => selectExisting(id, 'delivery')} onClear={() => clearSelection('delivery')} onCreateNew={() => createNew('delivery')}
            />
            <AddressFormCard mode={deliveryMode} draft={deliveryDraft} options={options} saving={savingDelivery} t={t}
              onDraftChange={setDeliveryDraft} onSaveDraft={saveDeliveryDraft} />
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
            <PickerCard
              title={t('checkout_billing_address')}
              extra={
                <label style={{ display: 'inline-flex', alignItems: 'center', gap: 8, fontSize: 13, cursor: 'pointer' }}>
                  <input type="checkbox" checked={sameAsDelivery} onChange={(e) => setSameAsDelivery(e.target.checked)} />
                  {t('checkout_same_as_delivery')}
                </label>
              }
              hideSelector={sameAsDelivery}
              selectLabel={t('checkout_billing_select')} choosePh={t('checkout_choose_ph')} createLabel={t('checkout_create_billing_btn')}
              addresses={savedAddresses} mode={billingMode} selectedId={billingId}
              onSelect={(id) => selectExisting(id, 'billing')} onClear={() => clearSelection('billing')} onCreateNew={() => createNew('billing')}
            />
            {!sameAsDelivery && (
              <AddressFormCard mode={billingMode} draft={billingDraft} options={options} saving={savingBilling} t={t}
                onDraftChange={setBillingDraft} onSaveDraft={saveBillingDraft} />
            )}
          </div>
        </div>
      )}

      <div style={{ marginTop: 20, display: 'flex', justifyContent: 'space-between' }}>
        <button style={btnGhost} onClick={onBack}>{t('checkout_back')}</button>
        <button style={btnPrimary} disabled={validating || !deliveryId || (!sameAsDelivery && !billingId)} onClick={next}>
          {validating ? '…' : t('checkout_next')}
        </button>
      </div>
    </div>
  )
}
