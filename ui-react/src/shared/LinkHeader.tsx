import { useEffect, useMemo, useRef, useState } from 'react'
import { card, inputCss, btnGhost } from './styles'
import { LinkIcon } from './icons'
import { useIsNarrow } from './useIsNarrow'
import type { Option } from './api'

/**
 * En-tête « lier » d'un onglet relationnel (Association / Contacts) : une AUTOCOMPLETE
 * (filtrage local, façon back-office `easyAutocomplete` — cf. client.tool.js
 * `initContactAutoSuggesst`) + un bouton « Lier ». Les options sont déjà chargées
 * intégralement par l'appelant (petites listes) : le filtrage se fait donc côté client,
 * pas de nouvel appel réseau par frappe.
 */
export function LinkHeader({ options, placeholder, linkLabel, onLink, inline }: {
  options: Option[]; placeholder: string; linkLabel: string; onLink: (id: number) => Promise<void> | void
  /** true → pas de wrapper flottant (display:contents) : s'intègre dans une barre d'outils parente. */
  inline?: boolean
}) {
  const narrow = useIsNarrow()
  const [query, setQuery] = useState('')
  const [selectedId, setSelectedId] = useState(0)
  const [open, setOpen] = useState(false)
  const [busy, setBusy] = useState(false)
  const ref = useRef<HTMLDivElement>(null)

  useEffect(() => {
    if (!open) return
    const onDown = (e: MouseEvent) => { if (ref.current && !ref.current.contains(e.target as Node)) setOpen(false) }
    document.addEventListener('mousedown', onDown)
    return () => document.removeEventListener('mousedown', onDown)
  }, [open])

  const matches = useMemo(() => {
    const q = query.trim().toLowerCase()
    if (!q) return []
    return options.filter((o) => o.name.toLowerCase().includes(q)).slice(0, 20)
  }, [options, query])

  function pick(o: Option) {
    setQuery(o.name); setSelectedId(o.id); setOpen(false)
  }

  function onQueryChange(v: string) {
    setQuery(v); setSelectedId(0); setOpen(true)
  }

  async function link() {
    if (!selectedId) return
    setBusy(true)
    try { await onLink(selectedId); setQuery(''); setSelectedId(0) } finally { setBusy(false) }
  }

  return (
    <div style={inline ? { display: 'contents' } : { display: 'flex', justifyContent: 'flex-end', gap: 8, marginBottom: 12, flexWrap: 'wrap' }}>
      <div ref={ref} style={{ position: 'relative', width: narrow ? '100%' : inline ? 220 : 260 }}>
        <input style={{ ...inputCss, height: 36 }} value={query} placeholder={placeholder}
          onChange={(e) => onQueryChange(e.target.value)}
          onFocus={() => { if (query.trim()) setOpen(true) }} />
        {open && matches.length > 0 && (
          <div style={{ ...card, position: 'absolute', top: '100%', left: 0, right: 0, marginTop: 4, zIndex: 60, maxHeight: 240, overflow: 'auto', padding: 4 }}>
            {matches.map((o) => (
              <button key={o.id} type="button" onClick={() => pick(o)}
                style={{ display: 'block', width: '100%', textAlign: 'left', padding: '8px 10px', border: 0, borderRadius: 6, background: 'transparent', color: 'var(--color-foreground)', fontSize: 13, cursor: 'pointer' }}
                onMouseEnter={(e) => (e.currentTarget.style.background = 'color-mix(in srgb, var(--color-primary) 10%, transparent)')}
                onMouseLeave={(e) => (e.currentTarget.style.background = 'transparent')}>
                {o.name}
              </button>
            ))}
          </div>
        )}
      </div>
      <button style={{ ...btnGhost, height: 36, color: 'var(--color-primary)', borderColor: 'var(--color-primary)', ...(narrow ? { flex: '1 1 100%', justifyContent: 'center' } : {}) }}
        onClick={link} disabled={!selectedId || busy}>
        <LinkIcon />{linkLabel}
      </button>
    </div>
  )
}
