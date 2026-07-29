/**
 * Gestion des colonnes (masquer + réordonner par glisser-déposer), persistée en localStorage.
 * `makeColStore` fabrique un loader/saver pour un outil donné (ordre + visibilité).
 */
export type ColDef = { id: string; visible: boolean }

export const visibleCols = (c: ColDef[]) => c.filter((x) => x.visible)

/**
 * On narrow viewports, force visibility to exactly `essential` — in BOTH directions — regardless
 * of the user's ColManager preference, so mobile always shows the essentials (even if the desktop
 * preference hid one) with the rest reachable via the per-row "+" expand (see
 * shared/ExpandableRow.tsx). Desktop-only preference (`cols` itself, persisted via ColManager) is
 * left untouched; this is a render-time overlay, never written back to localStorage.
 */
export function effectiveCols(cols: ColDef[], essential: ReadonlySet<string>, narrow: boolean): ColDef[] {
  if (!narrow) return cols
  return cols.map((c) => ({ ...c, visible: essential.has(c.id) }))
}

export function makeColStore(storageKey: string, order: readonly string[]) {
  const DEFAULT: ColDef[] = order.map((id) => ({ id, visible: true }))
  function load(): ColDef[] {
    try {
      const raw = localStorage.getItem(storageKey)
      if (!raw) return DEFAULT
      const saved: ColDef[] = JSON.parse(raw)
      const ordered = saved
        .map((s) => (DEFAULT.find((c) => c.id === s.id) ? { id: s.id, visible: s.visible } : null))
        .filter(Boolean) as ColDef[]
      const missing = DEFAULT.filter((d) => !saved.find((s) => s.id === d.id))
      return [...ordered, ...missing]
    } catch { return DEFAULT }
  }
  function save(c: ColDef[]) { try { localStorage.setItem(storageKey, JSON.stringify(c)) } catch { /* */ } }
  return { DEFAULT, load, save }
}
