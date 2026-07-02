import type { CSSProperties, ReactNode } from 'react'
import { card, vmBtn, btnGhost } from './styles'
import { SparklesIcon, LayoutIcon } from './icons'
import type { T } from './i18n'

/** Badge de statut Actif/Inactif. */
export function StatusBadge({ active, t }: { active: boolean; t: T }) {
  return (
    <span style={{
      display: 'inline-block', fontSize: 12, fontWeight: 500, padding: '2px 8px', borderRadius: 999,
      background: active ? 'color-mix(in srgb, #10b981 14%, transparent)' : 'var(--color-muted,rgba(0,0,0,.05))',
      color: active ? '#059669' : 'var(--color-muted-foreground)',
    }}>{active ? t('status_active') : t('status_inactive')}</span>
  )
}

/** Carte KPI. */
export function Kpi({ label, value, icon, iconBg, iconColor }: { label: string; value: number | null; icon?: ReactNode; iconBg?: string; iconColor?: string }) {
  return (
    <div style={{ ...card, display: 'flex', alignItems: 'center', gap: 12, padding: 16, minWidth: 0 }}>
      {icon && (
        <div style={{ width: 40, height: 40, borderRadius: 8, flexShrink: 0, display: 'grid', placeItems: 'center', background: iconBg ?? 'rgba(59,130,246,0.1)', color: iconColor ?? '#3b82f6' }}>
          {icon}
        </div>
      )}
      <div style={{ display: 'flex', flexDirection: 'column', gap: 2, minWidth: 0 }}>
        <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>{label}</span>
        <span style={{ fontSize: 20, fontWeight: 700 }}>{value == null ? '…' : value}</span>
      </div>
    </div>
  )
}

/** Toggle segmenté New/Old. */
export function ViewModeToggle({ mode, onReact, onOld }: { mode: 'react' | 'old'; onReact: () => void; onOld: () => void }) {
  return (
    <div style={{ display: 'inline-flex', alignItems: 'center', gap: 4, borderRadius: 10, border: '1px solid var(--color-border)', background: 'var(--color-muted,rgba(0,0,0,.04))', padding: 4 }}>
      <button style={vmBtn(mode === 'react')} onClick={onReact}><SparklesIcon />New</button>
      <button style={vmBtn(mode === 'old')} onClick={onOld}><LayoutIcon />Old</button>
    </div>
  )
}

/**
 * Iframe de l'outil legacy (vue « Old »), encadrée arrondie — calquée sur le
 * MelisClassicFrame de l'hôte (rounded + border + overflow-hidden).
 */
export function LegacyFrame({ melisKey, title, visible }: { melisKey: string; title: string; visible: boolean }) {
  const style: CSSProperties = {
    flex: 1, minHeight: 0, display: visible ? 'flex' : 'none',
    overflow: 'hidden', borderRadius: 12, border: '1px solid var(--color-border)',
  }
  return (
    <div style={style}>
      <iframe title={title} src={`/melis/react-tool-page?key=${encodeURIComponent(melisKey)}`}
        style={{ height: '100%', width: '100%', border: 0 }} />
    </div>
  )
}

/**
 * Modale de confirmation standard (suppression, etc.) — utilisée par tous les outils
 * MelisCommerce à la place du `confirm()` natif du navigateur.
 */
export function ConfirmModal({ title, message, extra, confirmLabel, danger = true, onConfirm, onCancel, t }: {
  title: string; message: ReactNode; extra?: ReactNode; confirmLabel?: string; danger?: boolean
  onConfirm: () => void; onCancel: () => void; t: T
}) {
  return (
    <div style={{ position: 'fixed', inset: 0, zIndex: 50, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
      <div style={{ ...card, padding: 24, width: '100%', maxWidth: 380 }}>
        <h3 style={{ fontSize: 16, fontWeight: 600, margin: 0 }}>{title}</h3>
        <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', marginTop: 8 }}>{message}</p>
        {extra}
        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 20 }}>
          <button style={btnGhost} onClick={onCancel}>{t('cancel')}</button>
          <button style={danger ? { ...btnGhost, borderColor: '#fca5a5', color: '#dc2626' } : btnGhost} onClick={onConfirm}>{confirmLabel ?? t('del')}</button>
        </div>
      </div>
    </div>
  )
}
