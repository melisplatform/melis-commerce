import { useEffect, useRef, useState } from 'react'
import { makeT } from '../../../shared/i18n'
import { DICT } from '../dict'
import { UserIcon, UsersIcon, CartIcon, MapPinIcon, FileTextIcon, CreditCardIcon, CheckIcon } from '../../../shared/icons'
import { card } from '../../../shared/styles'
import { checkoutStart, checkoutAbandon, fetchCheckoutState } from '../api'
import { openSubTab } from '../../../shared/subtabs'
import { WizardContactStep } from './WizardContactStep'
import { WizardAccountStep } from './WizardAccountStep'
import { WizardProductsStep } from './WizardProductsStep'
import { WizardAddressesStep } from './WizardAddressesStep'
import { WizardSummaryStep } from './WizardSummaryStep'
import { WizardPaymentStep } from './WizardPaymentStep'
import { WizardConfirmationStep } from './WizardConfirmationStep'

const STEPS = [
  { key: 'contact', Icon: UserIcon },
  { key: 'account', Icon: UsersIcon },
  { key: 'products', Icon: CartIcon },
  { key: 'addresses', Icon: MapPinIcon },
  { key: 'summary', Icon: FileTextIcon },
  { key: 'payment', Icon: CreditCardIcon },
  { key: 'confirmation', Icon: CheckIcon },
] as const

interface WizardState {
  contactId: number | null
  clientId: number | null
  countryId: number | null
  billingId: number | null
  deliveryId: number | null
  orderId: number | null
  reference: string
}

/** Assistant "Nouvelle commande" — reconstruit le wizard 7 étapes legacy (Contact → Compte →
 *  Produits → Adresses → Récapitulatif → Paiement → Confirmation) en s'appuyant sur les MÊMES
 *  services PHP (MelisComOrderCheckoutService/MelisComBasketService) via /orders/checkout/*,
 *  état côté serveur (session), pas de duplication de la logique métier ici. */
export default function OrderCheckoutWizard({ base }: { base: string }) {
  const t = makeT(DICT)
  const subTabPath = `${base}/new`
  const [step, setStep] = useState(0)
  const [ready, setReady] = useState(false)
  const [state, setState] = useState<WizardState>({
    contactId: null, clientId: null, countryId: null, billingId: null, deliveryId: null, orderId: null, reference: '',
  })

  useEffect(() => {
    openSubTab(base, { id: subTabPath, label: t('new'), path: subTabPath })
    // checkoutStart() is now idempotent server-side: it primes the session bucket without
    // wiping an in-progress one. Resume step + selections from that bucket (Mantis 0010748) —
    // switching to a different tool tab and back unmounts/remounts this component, and without
    // this the wizard always restarted from step 0 with everything blank.
    checkoutStart()
      .then(() => fetchCheckoutState())
      .then((s) => {
        setState((p) => ({
          ...p,
          contactId: s.contactId, clientId: s.clientId, countryId: s.countryId,
          billingId: s.billingId, deliveryId: s.deliveryId,
          orderId: s.orderId, reference: s.reference,
        }))
        if (s.orderId) setStep(6)
        else if (s.billingId && s.deliveryId) setStep(4)
        else if (s.clientId) setStep(2)
        else if (s.contactId) setStep(1)
      })
      .finally(() => setReady(true))
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  // Distinguish "switched tabs" (resumable, handled above) from "explicitly closed the New
  // order tab/tool before confirming" (should clear — Mantis 0010748 follow-up). melis-core
  // (host, separate bundle) publishes two generic window events for exactly this: 'melis:tab-
  // closed' (detail.path = the closed TOP-LEVEL tab, fired synchronously — no race) when e.g.
  // the "Commandes" tab's × is clicked, and 'melis:subtabs-changed' (+ read-only
  // window.__melisSubTabs, already updated by the time this fires) when a SUB-tab like
  // "Nouvelle commande" is closed individually. Neither fires on a mere navigate-away.
  const orderIdRef = useRef<number | null>(null)
  useEffect(() => { orderIdRef.current = state.orderId }, [state.orderId])
  useEffect(() => {
    function onTopTabClosed(e: Event) {
      const path = (e as CustomEvent<{ path?: string }>).detail?.path
      if (path === base && !orderIdRef.current) checkoutAbandon().catch(() => null)
    }
    function onSubTabsChanged() {
      const w = window as unknown as { __melisSubTabs?: Record<string, { tabs: { id: string }[] }> }
      const stillOpen = (w.__melisSubTabs?.[base]?.tabs ?? []).some((tb) => tb.id === subTabPath)
      if (!stillOpen && !orderIdRef.current) checkoutAbandon().catch(() => null)
    }
    window.addEventListener('melis:tab-closed', onTopTabClosed)
    window.addEventListener('melis:subtabs-changed', onSubTabsChanged)
    return () => {
      window.removeEventListener('melis:tab-closed', onTopTabClosed)
      window.removeEventListener('melis:subtabs-changed', onSubTabsChanged)
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [base, subTabPath])

  const goNext = () => setStep((s) => Math.min(STEPS.length - 1, s + 1))
  const goBack = () => setStep((s) => Math.max(0, s - 1))

  if (!ready) {
    return <div style={{ padding: 40, textAlign: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24 }}>
      <div>
        <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('checkout_title')}</h1>
        <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('checkout_subtitle')}</p>
      </div>

      <div style={{ display: 'flex', ...card, padding: 0, overflow: 'hidden' }}>
        {STEPS.map((s, i) => {
          const active = i === step
          const done = i < step
          return (
            <div key={s.key} style={{
              flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 6, padding: '14px 8px',
              background: active ? 'var(--color-primary)' : done ? 'var(--color-muted)' : 'transparent',
              color: active ? 'var(--color-primary-foreground,#fff)' : 'var(--color-foreground)',
              borderRight: i < STEPS.length - 1 ? '1px solid var(--color-border)' : undefined,
            }}>
              <s.Icon />
              <span style={{ fontSize: 11, fontWeight: 600, textAlign: 'center' }}>{t(`checkout_step_${s.key}`)}</span>
            </div>
          )
        })}
      </div>

      <div style={{ ...card, padding: 24, minHeight: 300 }}>
        {step === 0 && (
          <WizardContactStep onNext={(contactId) => { setState((p) => ({ ...p, contactId })); goNext() }} />
        )}
        {step === 1 && state.contactId && (
          <WizardAccountStep contactId={state.contactId} onBack={goBack}
            onNext={(clientId) => { setState((p) => ({ ...p, clientId })); goNext() }} />
        )}
        {step === 2 && (
          <WizardProductsStep countryId={state.countryId} onBack={goBack}
            onNext={(countryId) => { setState((p) => ({ ...p, countryId })); goNext() }} />
        )}
        {step === 3 && state.clientId && (
          <WizardAddressesStep clientId={state.clientId} onBack={goBack}
            onNext={(billingId, deliveryId) => { setState((p) => ({ ...p, billingId, deliveryId })); goNext() }} />
        )}
        {step === 4 && state.clientId && (
          <WizardSummaryStep clientId={state.clientId} billingId={state.billingId} deliveryId={state.deliveryId} onBack={goBack} onNext={goNext} />
        )}
        {step === 5 && (
          <WizardPaymentStep onBack={goBack}
            onConfirmed={(orderId, reference) => { setState((p) => ({ ...p, orderId, reference })); goNext() }} />
        )}
        {step === 6 && state.orderId && <WizardConfirmationStep base={base} orderId={state.orderId} reference={state.reference} />}
      </div>
    </div>
  )
}
