import { useState } from 'react'
import { inputCss, btnGhost } from './styles'
import { LinkIcon } from './icons'
import type { Option } from './api'

/**
 * En-tête « lier » d'un onglet relationnel (Association / Contacts) : un sélecteur
 * d'entités + un bouton « Lier », façon back-office (input « Account name » + « Link account »).
 */
export function LinkHeader({ options, placeholder, linkLabel, onLink }: {
  options: Option[]; placeholder: string; linkLabel: string; onLink: (id: number) => Promise<void> | void
}) {
  const [sel, setSel] = useState(0)
  const [busy, setBusy] = useState(false)
  async function link() {
    if (!sel) return
    setBusy(true)
    try { await onLink(sel); setSel(0) } finally { setBusy(false) }
  }
  return (
    <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginBottom: 12, flexWrap: 'wrap' }}>
      <select style={{ ...inputCss, height: 36, width: 'auto', minWidth: 220 }} value={sel} onChange={(e) => setSel(Number(e.target.value))}>
        <option value={0}>{placeholder}</option>
        {options.map((o) => <option key={o.id} value={o.id}>{o.name}</option>)}
      </select>
      <button style={{ ...btnGhost, height: 36, color: 'var(--color-primary)', borderColor: 'var(--color-primary)' }}
        onClick={link} disabled={!sel || busy}>
        <LinkIcon />{linkLabel}
      </button>
    </div>
  )
}
