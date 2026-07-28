import { useEffect, useRef, useState } from 'react'
import { card, inputCss } from './styles'
import { ChevronDownIcon } from './icons'

/** Liste déroulante RECHERCHABLE (combobox) — remplace un <select> natif quand la liste est longue. */
export function SearchableSelect({ options, value, onChange, placeholder, searchPlaceholder, noResult }: {
  options: { id: number; name: string }[]; value: number; onChange: (id: number) => void
  placeholder: string; searchPlaceholder?: string; noResult?: string
}) {
  const [open, setOpen] = useState(false)
  const [q, setQ] = useState('')
  const ref = useRef<HTMLDivElement>(null)
  const inputRef = useRef<HTMLInputElement>(null)
  useEffect(() => {
    if (!open) return
    const onDown = (e: MouseEvent) => { if (ref.current && !ref.current.contains(e.target as Node)) setOpen(false) }
    document.addEventListener('mousedown', onDown)
    return () => document.removeEventListener('mousedown', onDown)
  }, [open])
  useEffect(() => { if (open) { setQ(''); const id = window.setTimeout(() => inputRef.current?.focus(), 0); return () => window.clearTimeout(id) } }, [open])
  const selected = options.find((o) => o.id === value)
  const ql = q.trim().toLowerCase()
  const filtered = ql ? options.filter((o) => o.name.toLowerCase().includes(ql)) : options
  return (
    <div ref={ref} style={{ position: 'relative', flex: 1, minWidth: 0 }}>
      <button type="button" onClick={() => setOpen((o) => !o)}
        style={{ ...inputCss, display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, cursor: 'pointer', textAlign: 'left' }}>
        <span style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', color: selected ? 'var(--color-foreground)' : 'var(--color-muted-foreground)' }}>{selected ? selected.name : placeholder}</span>
        <ChevronDownIcon />
      </button>
      {open && (
        <div style={{ ...card, position: 'absolute', top: '100%', left: 0, right: 0, marginTop: 6, zIndex: 60, padding: 6, maxHeight: 300, display: 'flex', flexDirection: 'column' }}>
          <input ref={inputRef} value={q} onChange={(e) => setQ(e.target.value)} placeholder={searchPlaceholder ?? ''}
            onKeyDown={(e) => { if (e.key === 'Escape') setOpen(false); else if (e.key === 'Enter' && filtered.length) { onChange(filtered[0].id); setOpen(false) } }}
            style={{ ...inputCss, height: 34, marginBottom: 6 }} />
          <div style={{ overflowY: 'auto' }}>
            {filtered.length === 0 ? (
              <div style={{ padding: '8px 10px', fontSize: 13, color: 'var(--color-muted-foreground)' }}>{noResult ?? '—'}</div>
            ) : filtered.map((o) => (
              <button key={o.id} type="button" onClick={() => { onChange(o.id); setOpen(false) }}
                style={{ display: 'block', width: '100%', textAlign: 'left', padding: '8px 10px', border: 0, borderRadius: 6, cursor: 'pointer', fontSize: 14, whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis',
                  background: o.id === value ? 'var(--color-primary)' : 'transparent', color: o.id === value ? 'var(--color-primary-foreground,#fff)' : 'var(--color-foreground)' }}
                onMouseOver={(e) => { if (o.id !== value) e.currentTarget.style.background = 'var(--color-muted,rgba(0,0,0,.06))' }}
                onMouseOut={(e) => { if (o.id !== value) e.currentTarget.style.background = 'transparent' }}>
                {o.name}
              </button>
            ))}
          </div>
        </div>
      )}
    </div>
  )
}
