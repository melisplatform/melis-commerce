import { useState, type CSSProperties, type PointerEvent as ReactPointerEvent } from 'react'
import { card, iconBtn, btnGhost, panelCss, panelTitle } from './styles'
import { GripIcon } from './icons'
import type { ColDef } from './columns'
import type { T } from './i18n'
import { useIsNarrow } from './useIsNarrow'
import { usePointerDrag } from './usePointerDrag'

// Sentinels for a pointer hit landing on empty panel space (no item underneath) — the panel
// container itself also carries `data-col-item` with one of these, so `closest()` still resolves
// to something reorder-relevant even when the panel is empty or the pointer is over its padding.
const HIDDEN_SENTINEL = '__panel_hidden__'
const VISIBLE_SENTINEL = '__panel_visible__'

/**
 * Gestionnaire de colonnes générique : popover 2 panneaux (Masquées / Visibles) avec
 * glisser-déposer pour réordonner + afficher/masquer. Persistance gérée par l'appelant.
 *
 * Glisser-déposer via Pointer Events (usePointerDrag), pas HTML5 `draggable` — ce dernier ne se
 * déclenche jamais sur un geste tactile mobile (même bug que CatalogTree, Mantis #10843).
 */
export function ColManager({ cols, labelFor, onChange, onClose, save, defaults, t }: {
  cols: ColDef[]; labelFor: (id: string) => string; onChange: (c: ColDef[]) => void
  onClose: () => void; save: (c: ColDef[]) => void; defaults: ColDef[]; t: T
}) {
  const narrow = useIsNarrow()
  const shown = cols.filter((c) => c.visible)
  const hidden = cols.filter((c) => !c.visible)
  const pointerDrag = usePointerDrag<string>('data-col-item', (raw) => raw)
  // Visual-only highlight state — the drop DECISION never reads this, it reads the local
  // `currentOverId` closure var in `startItemDrag` (avoids a stale-closure read of React state
  // from inside a callback set up once at drag-start — see CatalogTree.tsx for the same need).
  const [over, setOver] = useState<{ dragId: string; overId: string | null } | null>(null)

  function drop(panel: 'visible' | 'hidden', dragId: string, overId: string | null) {
    const src = cols.find((c) => c.id === dragId)
    if (!src) return
    const upd = { ...src, visible: panel === 'visible' }
    let vList = shown.filter((c) => c.id !== dragId)
    const hList = hidden.filter((c) => c.id !== dragId)
    if (panel === 'visible') {
      if (!overId || overId === VISIBLE_SENTINEL) vList = [...vList, upd]
      else { const i = vList.findIndex((c) => c.id === overId); vList = i === -1 ? [...vList, upd] : [...vList.slice(0, i), upd, ...vList.slice(i)] }
      const next = [...vList, ...hList]; onChange(next); save(next)
    } else { const next = [...vList, ...hList, upd]; onChange(next); save(next) }
  }

  function startItemDrag(colId: string) {
    return (e: ReactPointerEvent) => {
      e.stopPropagation(); e.preventDefault()
      let currentOverId: string | null = null
      setOver({ dragId: colId, overId: null })
      pointerDrag.startDrag(colId, {
        onHover: (targetId) => { currentOverId = targetId; setOver({ dragId: colId, overId: targetId }) },
        onLeave: () => { currentOverId = null; setOver({ dragId: colId, overId: null }) },
        onDrop: (targetId) => {
          const finalTarget = targetId ?? currentOverId
          const panel = finalTarget === HIDDEN_SENTINEL || hidden.some((c) => c.id === finalTarget) ? 'hidden' : 'visible'
          drop(panel, colId, finalTarget)
          setOver(null)
        },
        onCancel: () => setOver(null),
      })
    }
  }

  function item(col: ColDef) {
    const isOver = over?.overId === col.id
    return (
      <div key={col.id} data-col-item={col.id}
        onPointerDown={startItemDrag(col.id)}
        style={{ display: 'flex', alignItems: 'center', gap: 8, borderRadius: 8, padding: '6px 8px', fontSize: 14, cursor: 'grab', userSelect: 'none', touchAction: 'none', opacity: over?.dragId === col.id ? 0.4 : 1, background: isOver ? 'color-mix(in srgb, var(--color-primary) 12%, transparent)' : 'transparent' }}>
        <GripIcon /><span style={{ flex: 1, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{labelFor(col.id)}</span>
      </div>
    )
  }

  const empty: CSSProperties = { flex: 1, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 11, color: 'var(--color-muted-foreground)', opacity: 0.5, padding: '16px 0' }

  const panel = (
    <div style={{
      ...card, zIndex: 50, width: 380, maxWidth: narrow ? 'calc(100vw - 2rem)' : 'calc(100vw - 1rem)',
      ...(narrow
        ? { position: 'fixed', left: '50%', top: '50%', transform: 'translate(-50%, -50%)', maxHeight: '80vh', display: 'flex', flexDirection: 'column' }
        : { position: 'absolute', right: 0, top: '100%', marginTop: 6 }),
    }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '10px 12px', borderBottom: '1px solid var(--color-border)' }}>
        <span style={{ fontSize: 14, fontWeight: 600 }}>{t('columns')}</span>
        <button style={{ ...iconBtn, width: 22, height: 22 }} onClick={onClose}>✕</button>
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8, padding: 12, overflow: narrow ? 'auto' : undefined }}>
        <div data-col-item={HIDDEN_SENTINEL} style={{ ...panelCss, ...(over && over.overId === HIDDEN_SENTINEL ? { borderColor: 'color-mix(in srgb, var(--color-primary) 40%, transparent)', background: 'color-mix(in srgb, var(--color-primary) 5%, transparent)' } : {}) }}>
          <p style={panelTitle}>{t('cols_hidden')}</p>
          {hidden.length === 0 ? <div style={empty}>{t('drag_here')}</div> : hidden.map((c) => item(c))}
        </div>
        <div data-col-item={VISIBLE_SENTINEL} style={{ ...panelCss, ...(over && over.overId === VISIBLE_SENTINEL ? { borderColor: 'color-mix(in srgb, var(--color-primary) 40%, transparent)', background: 'color-mix(in srgb, var(--color-primary) 5%, transparent)' } : {}) }}>
          <p style={panelTitle}>{t('cols_visible')}</p>
          {shown.length === 0 ? <div style={empty}>{t('drag_here')}</div> : shown.map((c) => item(c))}
        </div>
      </div>
      <div style={{ borderTop: '1px solid var(--color-border)', padding: 6 }}>
        <button style={{ ...btnGhost, width: '100%', height: 30, border: 0, justifyContent: 'center', color: 'var(--color-muted-foreground)' }}
          onClick={() => { onChange(defaults); save(defaults) }}>{t('reset')}</button>
      </div>
    </div>
  )

  if (!narrow) return panel
  return (
    <div onClick={(e) => { if (e.target === e.currentTarget) onClose() }}
      style={{ position: 'fixed', inset: 0, zIndex: 49, background: 'rgba(0,0,0,.4)' }}>
      {panel}
    </div>
  )
}
