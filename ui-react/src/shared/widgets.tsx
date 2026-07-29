import { useState, type CSSProperties, type ReactNode } from 'react'
import { card, vmBtn, btnGhost } from './styles'
import { MelisMIcon, LayoutIcon } from './icons'
import { currentLang } from './i18n'
import type { T } from './i18n'
import { useIsNarrow } from './useIsNarrow'

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

/**
 * Toggle segmenté New/Old — mêmes libellés/icône que melis-core (MelisClassicView.tsx).
 * Sur mobile (viewport étroit), les libellés textuels disparaissent (icône seule) : le mot
 * "Nouveau"/"Ancien" à côté du titre + refresh + bouton Créer force sinon un débordement
 * horizontal — l'icône seule suffit à distinguer les 2 boutons.
 */
export function ViewModeToggle({ mode, onReact, onOld }: { mode: 'react' | 'old'; onReact: () => void; onOld: () => void }) {
  const isFr = currentLang() === 'fr'
  const narrow = useIsNarrow(480)
  return (
    <div style={{ display: 'inline-flex', alignItems: 'center', gap: 4, borderRadius: 10, border: '1px solid var(--color-border)', background: 'var(--color-muted,rgba(0,0,0,.04))', padding: 4, flexShrink: 0 }}>
      <button style={vmBtn(mode === 'react')} onClick={onReact} title={isFr ? 'Nouveau' : 'New'}><MelisMIcon />{!narrow && (isFr ? 'Nouveau' : 'New')}</button>
      <button style={vmBtn(mode === 'old')} onClick={onOld} title={isFr ? 'Ancien' : 'Old'}><LayoutIcon />{!narrow && (isFr ? 'Ancien' : 'Old')}</button>
    </div>
  )
}

/**
 * Champ de tags (chips) façon legacy — saisie libre, Entrée/virgule pour ajouter, croix pour
 * retirer, Retour arrière sur champ vide pour retirer le dernier. Valeur = string CSV (le
 * contrat existant côté formulaire/API), pour un remplacement direct d'un `<input>` texte.
 */
export function TagsInput({ value, onChange, placeholder }: { value: string; onChange: (v: string) => void; placeholder?: string }) {
  const tags = value.split(',').map((s) => s.trim()).filter(Boolean)
  const [draft, setDraft] = useState('')

  function commit(next: string[]) { onChange(next.join(',')) }
  function addTag(raw: string) {
    const t = raw.trim()
    if (!t || tags.includes(t)) { setDraft(''); return }
    commit([...tags, t]); setDraft('')
  }
  function removeTag(t: string) { commit(tags.filter((x) => x !== t)) }

  return (
    <div style={{
      display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 6, minHeight: 40, width: '100%', boxSizing: 'border-box',
      borderRadius: 8, border: '1px solid var(--color-input,var(--color-border))', background: 'var(--color-card)', padding: '6px 8px',
    }}>
      {tags.map((t) => (
        <span key={t} style={{
          display: 'inline-flex', alignItems: 'center', gap: 5, padding: '2px 6px 2px 10px', borderRadius: 999, fontSize: 13,
          background: 'var(--color-primary)', color: 'var(--color-primary-foreground,#fff)', whiteSpace: 'nowrap',
        }}>
          {t}
          <button type="button" onClick={() => removeTag(t)}
            style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 16, height: 16, borderRadius: 999, border: 0, background: 'rgba(255,255,255,.25)', color: 'inherit', cursor: 'pointer', fontSize: 11, lineHeight: 1, padding: 0 }}>✕</button>
        </span>
      ))}
      <input
        value={draft}
        onChange={(e) => setDraft(e.target.value)}
        onKeyDown={(e) => {
          if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); addTag(draft) }
          else if (e.key === 'Backspace' && !draft && tags.length) { removeTag(tags[tags.length - 1]) }
        }}
        onBlur={() => { if (draft.trim()) addTag(draft) }}
        placeholder={tags.length ? '' : placeholder}
        style={{ flex: 1, minWidth: 100, border: 0, outline: 'none', background: 'transparent', color: 'var(--color-foreground)', fontSize: 14, height: 26 }}
      />
    </div>
  )
}

/** Rendu en lecture seule des tags (chips) dans une cellule de tableau — même style que TagsInput. */
export function TagsDisplay({ value }: { value: string }) {
  const tags = value.split(',').map((s) => s.trim()).filter(Boolean)
  if (!tags.length) return <>—</>
  return (
    <div style={{ display: 'flex', flexWrap: 'wrap', gap: 4 }}>
      {tags.map((t) => (
        <span key={t} style={{
          display: 'inline-flex', alignItems: 'center', padding: '2px 8px', borderRadius: 999, fontSize: 12,
          background: 'var(--color-primary)', color: 'var(--color-primary-foreground,#fff)', whiteSpace: 'nowrap',
        }}>{t}</span>
      ))}
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
