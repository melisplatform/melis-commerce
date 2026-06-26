import type { ReactNode } from 'react'

export interface TabDef { key: string; label: string; icon?: ReactNode; disabled?: boolean }

/** Barre d'onglets de fiche (Properties / Contacts / Company / …) façon back-office. */
export function Tabs({ tabs, active, onChange }: { tabs: TabDef[]; active: string; onChange: (k: string) => void }) {
  return (
    <div style={{ display: 'flex', gap: 2, borderBottom: '1px solid var(--color-border)', flexWrap: 'wrap' }}>
      {tabs.map((tb) => {
        const on = tb.key === active
        return (
          <button key={tb.key} disabled={tb.disabled} onClick={() => !tb.disabled && onChange(tb.key)}
            title={tb.disabled ? '' : tb.label}
            style={{
              display: 'inline-flex', alignItems: 'center', gap: 7, padding: '10px 16px', border: 0, background: 'transparent',
              cursor: tb.disabled ? 'not-allowed' : 'pointer', fontSize: 14, fontWeight: 500, marginBottom: -1,
              color: on ? 'var(--color-primary)' : 'var(--color-muted-foreground)', opacity: tb.disabled ? 0.4 : 1,
              borderBottom: on ? '2px solid var(--color-primary)' : '2px solid transparent',
            }}>
            {tb.icon}{tb.label}
          </button>
        )
      })}
    </div>
  )
}
