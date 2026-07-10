import { useNavigate } from 'react-router-dom'
import { makeT } from '../../../shared/i18n'
import { DICT } from '../dict'
import { btnPrimary } from '../../../shared/styles'
import { CheckIcon } from '../../../shared/icons'
import { openSubTab, closeSubTab } from '../../../shared/subtabs'

export function WizardConfirmationStep({ base, orderId, reference }: { base: string; orderId: number; reference: string }) {
  const t = makeT(DICT)
  const navigate = useNavigate()

  function viewOrder() {
    const path = `${base}/${orderId}`
    closeSubTab(base, `${base}/new`)
    openSubTab(base, { id: path, label: reference || `#${orderId}`, path })
    navigate(path)
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 16, padding: '40px 0' }}>
      <span style={{ display: 'inline-flex', width: 56, height: 56, borderRadius: '50%', alignItems: 'center', justifyContent: 'center', background: 'rgba(22,163,74,.1)', color: '#16a34a' }}>
        <CheckIcon />
      </span>
      <h3 style={{ fontSize: 18, fontWeight: 700, margin: 0 }}>{t('checkout_success_title')}</h3>
      <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: 0, textAlign: 'center' }}>
        {t('checkout_success_hint', { ref: reference })}
      </p>
      <button style={btnPrimary} onClick={viewOrder}>{t('checkout_view_order')}</button>
    </div>
  )
}
