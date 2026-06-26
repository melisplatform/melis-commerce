import type { CSSProperties } from 'react'

/**
 * Styles inline partagés (variables CSS du thème de l'hôte → suit le light/dark).
 * Une brique ne peut PAS utiliser les classes Tailwind de l'hôte (non compilées hors
 * de son arbre de sources) → tout est en style inline.
 */
export const card: CSSProperties = { border: '1px solid var(--color-border)', background: 'var(--color-card)', borderRadius: 12, boxShadow: '0 1px 2px rgba(0,0,0,.04)' }
export const inputCss: CSSProperties = { height: 40, width: '100%', boxSizing: 'border-box', borderRadius: 8, border: '1px solid var(--color-input,var(--color-border))', background: 'var(--color-card)', color: 'var(--color-foreground)', padding: '0 12px', fontSize: 14, outline: 'none' }
export const btnPrimary: CSSProperties = { display: 'inline-flex', alignItems: 'center', gap: 6, height: 36, padding: '0 14px', borderRadius: 8, border: 0, background: 'var(--color-primary)', color: 'var(--color-primary-foreground,#fff)', fontSize: 14, fontWeight: 500, cursor: 'pointer' }
export const btnGhost: CSSProperties = { display: 'inline-flex', alignItems: 'center', gap: 6, height: 36, padding: '0 12px', borderRadius: 8, border: '1px solid var(--color-border)', background: 'var(--color-card)', color: 'var(--color-foreground)', fontSize: 14, cursor: 'pointer' }
export const iconBtn: CSSProperties = { display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 28, height: 28, borderRadius: 6, border: 0, background: 'transparent', color: 'var(--color-muted-foreground)', cursor: 'pointer' }
export const th: CSSProperties = { textAlign: 'left', padding: '10px 16px', fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.04em', color: 'var(--color-muted-foreground)', whiteSpace: 'nowrap' }
export const td: CSSProperties = { padding: '10px 16px', fontSize: 14, color: 'var(--color-foreground)', borderTop: '1px solid var(--color-border)' }
export const label: CSSProperties = { display: 'block', fontSize: 13, fontWeight: 500, marginBottom: 4, color: 'var(--color-foreground)' }
export const hint: CSSProperties = { marginTop: 4, fontSize: 12, color: 'var(--color-muted-foreground)' }
export const panelCss: CSSProperties = { display: 'flex', flexDirection: 'column', gap: 2, minHeight: 130, borderRadius: 8, border: '1px dashed var(--color-border)', padding: 6 }
export const panelTitle: CSSProperties = { padding: '0 6px 4px', fontSize: 10, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)' }

/** Bouton d'action encadré (édit / défaut / délier) — visible comme le back-office. */
export const actionBtn = (color: string, active = false): CSSProperties => ({
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 30, height: 30, borderRadius: 6,
  border: `1px solid ${color}`, background: active ? color : 'transparent', color: active ? '#fff' : color, cursor: 'pointer',
})

/** Bouton segmenté (filtres de statut). */
export const segBtn = (active: boolean): CSSProperties => ({
  height: 30, padding: '0 12px', borderRadius: 6, border: 0, fontSize: 13, fontWeight: 500, cursor: 'pointer',
  background: active ? 'var(--color-primary)' : 'transparent', color: active ? 'var(--color-primary-foreground,#fff)' : 'var(--color-muted-foreground)',
})

/** Bouton du toggle New/Old (fond carte + ombre sur l'actif). */
export const vmBtn = (sel: boolean): CSSProperties => ({
  display: 'inline-flex', alignItems: 'center', gap: 6, height: 28, padding: '0 12px', borderRadius: 7, border: 0, fontSize: 12, fontWeight: 500, cursor: 'pointer',
  background: sel ? 'var(--color-card)' : 'transparent', color: sel ? 'var(--color-foreground)' : 'var(--color-muted-foreground)',
  boxShadow: sel ? '0 1px 2px rgba(0,0,0,.1)' : 'none',
})
