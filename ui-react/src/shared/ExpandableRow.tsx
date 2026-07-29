import type { ReactNode } from 'react'
import { PlusIcon, MinusIcon } from './icons'
import type { ColDef } from './columns'

/**
 * Per-row "+" toggle (first column of a table) that reveals the columns currently hidden
 * via ColManager — same visibility source, just surfaced per-row for narrow viewports
 * instead of only in the Columns popover. Pair with <HiddenColsRow>. Inline styles only —
 * a brick can't use the host's Tailwind classes (see shared/styles.ts).
 */
export function ExpandToggle({ expanded, onClick }: { expanded: boolean; onClick: () => void }) {
  return (
    <button type="button" onClick={onClick} aria-expanded={expanded}
      style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 24, height: 24, borderRadius: 6, border: '1px solid var(--color-border)', background: 'transparent', color: 'var(--color-muted-foreground)', cursor: 'pointer', padding: 0 }}>
      {expanded ? <MinusIcon /> : <PlusIcon />}
    </button>
  )
}

/**
 * Detail row shown under an expanded row — one label/value pair per hidden column.
 * Two columns side by side on desktop (saves vertical space); a single stacked column on
 * narrow viewports — a 2-col grid there fights for width against wrapped long values (e.g. a
 * multi-line reference) and pushes the second column past the visible edge.
 */
export function HiddenColsRow({ cols, labelFor, renderValue, colSpan, narrow }: {
  cols: ColDef[]
  labelFor: (id: string) => string
  renderValue: (id: string) => ReactNode
  colSpan: number
  narrow?: boolean
}) {
  const hidden = cols.filter((c) => !c.visible)
  if (hidden.length === 0) return null
  return (
    <tr>
      <td colSpan={colSpan} style={{ padding: '10px 16px', borderTop: '1px solid var(--color-border)', background: 'var(--color-muted,rgba(0,0,0,.02))', width: 0 }}>
        <div style={{ display: 'grid', gridTemplateColumns: !narrow && hidden.length > 1 ? 'repeat(2, minmax(0, 1fr))' : 'minmax(0, 1fr)', columnGap: 24, rowGap: 10 }}>
          {hidden.map((c) => (
            <div key={c.id} style={{ display: 'grid', gridTemplateColumns: 'auto minmax(0, 1fr)', alignItems: 'baseline', gap: 8, fontSize: 13 }}>
              <span style={{ fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.04em', color: 'var(--color-muted-foreground)' }}>{labelFor(c.id)}:</span>
              <span style={{ minWidth: 0, maxWidth: 220, overflowWrap: 'break-word', display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>{renderValue(c.id)}</span>
            </div>
          ))}
        </div>
      </td>
    </tr>
  )
}
