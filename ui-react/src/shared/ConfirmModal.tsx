import { card, btnGhost } from './styles'

/** Modale de confirmation générique (suppression / déliaison…). */
export function ConfirmModal({ title, message, confirmLabel, cancelLabel, onConfirm, onClose }: {
  title: string; message: string; confirmLabel: string; cancelLabel: string; onConfirm: () => void; onClose: () => void
}) {
  return (
    <div onClick={(e) => { if (e.target === e.currentTarget) onClose() }}
      style={{ position: 'fixed', inset: 0, zIndex: 60, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
      <div style={{ ...card, padding: 24, width: '100%', maxWidth: 380 }}>
        <h3 style={{ fontSize: 16, fontWeight: 600, margin: 0 }}>{title}</h3>
        <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', marginTop: 8 }}>{message}</p>
        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 20 }}>
          <button style={btnGhost} onClick={onClose}>{cancelLabel}</button>
          <button style={{ ...btnGhost, borderColor: '#fca5a5', color: '#dc2626' }} onClick={onConfirm}>{confirmLabel}</button>
        </div>
      </div>
    </div>
  )
}
