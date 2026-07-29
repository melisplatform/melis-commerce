import { useState } from 'react'
import { makeT, currentLang } from '../../../shared/i18n'
import { DICT } from '../dict'
import { checkoutConfirm } from '../api'
import { card, inputCss, label, btnGhost } from '../../../shared/styles'

const CURRENT_YEAR = new Date().getFullYear()
const CARD_YEARS = Array.from({ length: 12 }, (_, i) => CURRENT_YEAR + i)
const MONTH_NAMES: Record<'fr' | 'en', string[]> = {
  en: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
  fr: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
}

/** Étape Paiement : legacy affiche ici un iframe de gateway FACTICE (retrunCode aléatoire, aucune
 *  vraie intégration dans ce déploiement — cf. MelisComOrderCheckoutController::renderOrderCheckoutPaymentIframeAction).
 *  On reproduit le même habillage visuel (formulaire carte) pour la parité, mais les champs sont
 *  purement décoratifs : "Pay Now" déclenche directement la confirmation réelle de la commande
 *  (checkoutConfirmAction, qui fait aussi l'équivalent du step2 post-paiement en une seule requête). */
export function WizardPaymentStep({ onBack, onConfirmed }: {
  onBack: () => void; onConfirmed: (orderId: number, reference: string) => void
}) {
  const t = makeT(DICT)
  const [confirming, setConfirming] = useState(false)
  const [errors, setErrors] = useState<string[]>([])

  async function payNow() {
    if (confirming) return
    setConfirming(true)
    setErrors([])
    try {
      const r = await checkoutConfirm()
      if (r.success === false) {
        const msgs: string[] = []
        if (r.errors?.basket?.empty) msgs.push(t('checkout_basket_empty'))
        else if (r.errors?.basket && Object.keys(r.errors.basket).length) msgs.push(t('checkout_error_basket'))
        if (r.errors?.addresses && Object.keys(r.errors.addresses as object).length) msgs.push(t('checkout_error_addresses'))
        if (r.errors?.costs && Object.keys(r.errors.costs as object).length) msgs.push(t('checkout_error_costs'))
        setErrors(msgs.length ? msgs : [t('checkout_error_generic')])
        return
      }
      if (r.orderId) onConfirmed(r.orderId, r.reference ?? '')
    } catch {
      setErrors([t('checkout_error_generic')])
    } finally {
      setConfirming(false)
    }
  }

  return (
    <div>
      <h3 style={{ fontSize: 20, fontWeight: 600, margin: '0 0 4px' }}>{t('checkout_step_payment')}</h3>
      <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: '0 0 20px' }}>{t('checkout_step_payment_hint')}</p>

      <div style={{ ...card, padding: 20, maxWidth: 460 }}>
        <h4 style={{ fontSize: 14, fontWeight: 600, margin: '0 0 16px', paddingBottom: 10, borderBottom: '1px solid var(--color-border)' }}>{t('checkout_payment_api')}</h4>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
          <div>
            <label style={label}>{t('checkout_card_name')}</label>
            <input style={inputCss} placeholder={t('checkout_card_name_ph')} disabled={confirming} />
          </div>
          <div>
            <label style={label}>{t('checkout_card_number')}</label>
            <input style={inputCss} placeholder={t('checkout_card_number_ph')} disabled={confirming} />
          </div>
          <div style={{ display: 'flex', gap: 12 }}>
            <div style={{ flex: 1 }}>
              <label style={label}>{t('checkout_card_expiry')}</label>
              <div style={{ display: 'flex', gap: 8 }}>
                <select style={inputCss} disabled={confirming}>
                  <option value="">{t('checkout_card_month')}</option>
                  {MONTH_NAMES[currentLang()].map((name, i) => <option key={name} value={i + 1}>{name}</option>)}
                </select>
                <select style={inputCss} disabled={confirming}>
                  <option value="">{t('checkout_card_year')}</option>
                  {CARD_YEARS.map((y) => <option key={y} value={y}>{y}</option>)}
                </select>
              </div>
            </div>
            <div style={{ width: 110 }}>
              <label style={label}>{t('checkout_card_cvv')}</label>
              <input style={inputCss} placeholder={t('checkout_card_cvv_ph')} disabled={confirming} />
            </div>
          </div>
          <button style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 6, height: 40, borderRadius: 6, border: 0, background: '#16a34a', color: '#fff', fontSize: 14, fontWeight: 600, cursor: 'pointer', marginTop: 4 }}
            disabled={confirming} onClick={payNow}>
            {confirming ? '…' : t('checkout_pay_now')}
          </button>
        </div>
      </div>

      {errors.length > 0 && (
        <div style={{ marginTop: 16, padding: 12, borderRadius: 8, background: 'rgba(220,38,38,.08)', color: '#dc2626', fontSize: 13, maxWidth: 460 }}>
          {errors.map((e) => <div key={e}>{e}</div>)}
        </div>
      )}

      <div style={{ marginTop: 24, display: 'flex', justifyContent: 'space-between', maxWidth: 460 }}>
        <button style={btnGhost} onClick={onBack} disabled={confirming}>{t('checkout_back')}</button>
      </div>
    </div>
  )
}
