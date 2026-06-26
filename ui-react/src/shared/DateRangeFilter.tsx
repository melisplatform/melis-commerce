import { useEffect, useRef, useState, type CSSProperties } from 'react'
import { card, inputCss, btnGhost } from './styles'
import { CalendarIcon, ChevronDownIcon } from './icons'
import type { T } from './i18n'

/** yyyy-mm-dd (heure locale) */
function fmt(d: Date): string {
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
}

/**
 * Filtre de date à présets (Aujourd'hui / Hier / 7 derniers jours / … / Plage personnalisée),
 * calqué sur le daterangepicker du back-office. Renvoie une plage `from`/`to` en yyyy-mm-dd
 * ('' = pas de borne). Le bouton affiche le préset actif.
 */
export function DateRangeFilter({ from, to, onChange, t }: {
  from: string; to: string; onChange: (from: string, to: string) => void; t: T
}) {
  const [open, setOpen] = useState(false)
  const [custom, setCustom] = useState(false)
  const ref = useRef<HTMLDivElement>(null)

  useEffect(() => {
    if (!open) return
    const onDown = (e: MouseEvent) => { if (ref.current && !ref.current.contains(e.target as Node)) setOpen(false) }
    document.addEventListener('mousedown', onDown)
    return () => document.removeEventListener('mousedown', onDown)
  }, [open])

  const today = new Date(); today.setHours(0, 0, 0, 0)
  const shift = (n: number) => { const x = new Date(today); x.setDate(x.getDate() + n); return x }
  const y = today.getFullYear(), m = today.getMonth()

  const presets: { key: string; label: string; from: string; to: string }[] = [
    { key: 'all', label: t('dr_all'), from: '', to: '' },
    { key: 'today', label: t('dr_today'), from: fmt(today), to: fmt(today) },
    { key: 'yesterday', label: t('dr_yesterday'), from: fmt(shift(-1)), to: fmt(shift(-1)) },
    { key: 'last7', label: t('dr_last7'), from: fmt(shift(-6)), to: fmt(today) },
    { key: 'last30', label: t('dr_last30'), from: fmt(shift(-29)), to: fmt(today) },
    { key: 'thismonth', label: t('dr_thismonth'), from: fmt(new Date(y, m, 1)), to: fmt(new Date(y, m + 1, 0)) },
    { key: 'lastmonth', label: t('dr_lastmonth'), from: fmt(new Date(y, m - 1, 1)), to: fmt(new Date(y, m, 0)) },
  ]

  const activePreset = presets.find((p) => p.from === from && p.to === to)
  const buttonLabel = activePreset && activePreset.key !== 'all'
    ? activePreset.label
    : (from || to ? `${from || '…'} → ${to || '…'}` : t('dr_label'))

  function pick(p: { from: string; to: string }) { onChange(p.from, p.to); setCustom(false); setOpen(false) }

  const itemStyle = (active: boolean): CSSProperties => ({
    display: 'block', width: '100%', textAlign: 'left', padding: '8px 12px', border: 0, borderRadius: 6,
    background: active ? 'var(--color-primary)' : 'transparent', color: active ? 'var(--color-primary-foreground,#fff)' : 'var(--color-foreground)',
    fontSize: 13, cursor: 'pointer', whiteSpace: 'nowrap',
  })

  return (
    <div ref={ref} style={{ position: 'relative', display: 'inline-flex' }}>
      <button style={{ ...btnGhost, height: 34, gap: 8 }} onClick={() => setOpen((o) => !o)}>
        <CalendarIcon /><span style={{ maxWidth: 160, overflow: 'hidden', textOverflow: 'ellipsis' }}>{buttonLabel}</span><ChevronDownIcon />
      </button>
      {open && (
        <div style={{ ...card, position: 'absolute', top: '100%', left: 0, marginTop: 6, zIndex: 60, padding: 6, minWidth: 200 }}>
          {presets.map((p) => (
            <button key={p.key} style={itemStyle(!!activePreset && activePreset.key === p.key && !custom)} onClick={() => pick(p)}>{p.label}</button>
          ))}
          <button style={itemStyle(custom)} onClick={() => setCustom(true)}>{t('dr_custom')}</button>
          {custom && (
            <div style={{ borderTop: '1px solid var(--color-border)', marginTop: 6, paddingTop: 8, display: 'flex', flexDirection: 'column', gap: 8 }}>
              <label style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, fontSize: 12, color: 'var(--color-muted-foreground)' }}>
                {t('dr_from')}<input type="date" style={{ ...inputCss, height: 32, width: 150 }} value={from} onChange={(e) => onChange(e.target.value, to)} />
              </label>
              <label style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, fontSize: 12, color: 'var(--color-muted-foreground)' }}>
                {t('dr_to')}<input type="date" style={{ ...inputCss, height: 32, width: 150 }} value={to} onChange={(e) => onChange(from, e.target.value)} />
              </label>
              <button style={{ ...btnGhost, height: 32, justifyContent: 'center' }} onClick={() => setOpen(false)}>{t('dr_apply')}</button>
            </div>
          )}
        </div>
      )}
    </div>
  )
}
