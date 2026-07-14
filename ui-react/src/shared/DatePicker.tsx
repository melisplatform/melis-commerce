import { useEffect, useRef, useState, type CSSProperties } from 'react'
import { card, inputCss, btnGhost } from './styles'
import { CalendarIcon } from './icons'
import { currentLang, type T } from './i18n'

function parseValue(value: string): Date | null {
  if (!value) return null
  const d = new Date(value + 'T00:00:00')
  return isNaN(d.getTime()) ? null : d
}
function toValue(d: Date): string {
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
}
function sameDay(a: Date, b: Date): boolean {
  return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate()
}

/**
 * Sélecteur de date custom — remplace <input type="date"> : le calendrier natif de Chrome/Edge
 * suit la langue du NAVIGATEUR (ignore l'attribut lang de la page), pas celle du back-office.
 * Ce composant est entièrement piloté par currentLang(), donc toujours dans la langue du BO.
 * Contrat de valeur identique à <input type="date"> (yyyy-mm-dd, '' = vide) → drop-in replacement.
 */
export function DatePicker({ value, onChange, t, style, disabled }: {
  value: string; onChange: (v: string) => void; t: T; style?: CSSProperties; disabled?: boolean
}) {
  const [open, setOpen] = useState(false)
  const selected = parseValue(value)
  const [viewDate, setViewDate] = useState<Date>(selected ?? new Date())
  const ref = useRef<HTMLDivElement>(null)
  const locale = currentLang() === 'fr' ? 'fr-FR' : 'en-GB'

  useEffect(() => {
    if (!open) return
    setViewDate(selected ?? new Date())
    const onDown = (e: MouseEvent) => { if (ref.current && !ref.current.contains(e.target as Node)) setOpen(false) }
    document.addEventListener('mousedown', onDown)
    return () => document.removeEventListener('mousedown', onDown)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [open])

  const y = viewDate.getFullYear(), m = viewDate.getMonth()
  const monthLabel = viewDate.toLocaleDateString(locale, { month: 'long', year: 'numeric' })
  const weekdays = Array.from({ length: 7 }, (_, i) => new Date(2024, 0, 1 + i).toLocaleDateString(locale, { weekday: 'short' }))
  const leadDays = (new Date(y, m, 1).getDay() + 6) % 7
  const gridStart = new Date(y, m, 1 - leadDays)
  const days = Array.from({ length: 42 }, (_, i) => new Date(gridStart.getFullYear(), gridStart.getMonth(), gridStart.getDate() + i))
  const today = new Date()

  function pick(d: Date) { onChange(toValue(d)); setOpen(false) }

  return (
    <div ref={ref} style={{ position: 'relative', width: style?.width ?? '100%' }}>
      <button type="button" disabled={disabled} onClick={() => setOpen((o) => !o)}
        style={{ ...inputCss, ...style, display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, cursor: disabled ? 'default' : 'pointer', textAlign: 'left', opacity: disabled ? 0.6 : 1 }}>
        <span style={{ color: selected ? 'var(--color-foreground)' : 'var(--color-muted-foreground)' }}>
          {selected ? selected.toLocaleDateString(locale, { year: 'numeric', month: '2-digit', day: '2-digit' }) : ''}
        </span>
        <CalendarIcon />
      </button>
      {open && !disabled && (
        <div style={{ ...card, position: 'absolute', top: '100%', left: 0, marginTop: 6, zIndex: 60, padding: 10, width: 260 }}>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 8 }}>
            <button type="button" onClick={() => setViewDate(new Date(y, m - 1, 1))} style={navBtn}>‹</button>
            <span style={{ fontSize: 13, fontWeight: 600, textTransform: 'capitalize' }}>{monthLabel}</span>
            <button type="button" onClick={() => setViewDate(new Date(y, m + 1, 1))} style={navBtn}>›</button>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(7, 1fr)', gap: 2, marginBottom: 2 }}>
            {weekdays.map((w, i) => (
              <span key={i} style={{ fontSize: 11, textAlign: 'center', color: 'var(--color-muted-foreground)', textTransform: 'capitalize' }}>{w}</span>
            ))}
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(7, 1fr)', gap: 2 }}>
            {days.map((d, i) => {
              const inMonth = d.getMonth() === m
              const isToday = sameDay(d, today)
              const isSelected = !!selected && sameDay(d, selected)
              return (
                <button key={i} type="button" onClick={() => pick(d)}
                  style={{
                    height: 28, borderRadius: 6, border: isToday && !isSelected ? '1px solid var(--color-primary)' : '1px solid transparent',
                    background: isSelected ? 'var(--color-primary)' : 'transparent',
                    color: isSelected ? 'var(--color-primary-foreground,#fff)' : 'var(--color-foreground)',
                    opacity: inMonth ? 1 : 0.4, fontSize: 12, cursor: 'pointer',
                  }}>
                  {d.getDate()}
                </button>
              )
            })}
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: 8, paddingTop: 8, borderTop: '1px solid var(--color-border)' }}>
            <button type="button" style={{ ...btnGhost, height: 26, fontSize: 12, padding: '0 8px' }} onClick={() => { onChange(''); setOpen(false) }}>{t('cal_clear')}</button>
            <button type="button" style={{ ...btnGhost, height: 26, fontSize: 12, padding: '0 8px' }} onClick={() => pick(today)}>{t('dr_today')}</button>
          </div>
        </div>
      )}
    </div>
  )
}

const navBtn: CSSProperties = { width: 24, height: 24, display: 'inline-flex', alignItems: 'center', justifyContent: 'center', border: '1px solid var(--color-border)', borderRadius: 6, background: 'transparent', color: 'var(--color-foreground)', cursor: 'pointer', fontSize: 14, lineHeight: 1 }
