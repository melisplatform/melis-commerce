/**
 * Gestion des colonnes (masquer + réordonner par glisser-déposer), persistée en localStorage.
 * `makeColStore` fabrique un loader/saver pour un outil donné (ordre + visibilité).
 */
export type ColDef = { id: string; visible: boolean }

export const visibleCols = (c: ColDef[]) => c.filter((x) => x.visible)

/**
 * A Hidden column disappears entirely, never reachable anywhere — same rule on desktop AND
 * mobile, matching melis-core's Users tool: what's Hidden in ColManager simply isn't part of the
 * table. Desktop then shows every Visible column inline (`!narrow`, unchanged). Mobile keeps the
 * per-row "+" expand (see shared/ExpandableRow.tsx), but repurposed: since a narrow screen can't
 * fit many columns inline, only the FIRST Visible column (by the user's dragged order) anchors
 * inline as the row's identity; every OTHER Visible column surfaces behind "+", in that same
 * order — never Hidden ones, those are already gone by the filter above. Reordering "Visible" in
 * ColManager changes both which column anchors inline and what/which order appears behind "+".
 * `essential` is intentionally unused (kept so the ~12 call sites don't need touching again).
 */
export function effectiveCols(cols: ColDef[], _essential: ReadonlySet<string>, narrow: boolean): ColDef[] {
  const shown = cols.filter((c) => c.visible)
  if (!narrow) return shown
  return shown.map((c, i) => ({ ...c, visible: i === 0 }))
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
