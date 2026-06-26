import type { CSSProperties } from 'react'
import { card, vmBtn } from './styles'
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
export function Kpi({ label, value }: { label: string; value: number | null }) {
  return (
    <div style={{ ...card, display: 'flex', flexDirection: 'column', gap: 2, padding: 16, flex: 1, minWidth: 140 }}>
      <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>{label}</span>
      <span style={{ fontSize: 22, fontWeight: 700 }}>{value == null ? '…' : value}</span>
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
