import { useRef, useState } from 'react'

/**
 * Touch-and-mouse drag-to-reorder via the Pointer Events API.
 *
 * Native HTML5 drag-and-drop (`draggable` + `dragstart`/`dragover`/`drop`) never fires from a
 * touch gesture on mobile browsers — a list/tree built on it (the previous implementation of
 * every reorderable list in this brick) simply cannot be reordered on a phone (Mantis #10843).
 * Pointer Events unify mouse + touch + pen, so this single implementation drives both; desktop
 * dragging keeps working exactly as before.
 *
 * The dragged row is tracked via `pointerdown` on its handle, then `pointermove`/`pointerup`
 * listeners on `window` (not the handle) so the drag keeps tracking even once the finger/cursor
 * leaves the handle's bounds. The row currently under the pointer is found via
 * `document.elementFromPoint` + `closest('[data-*]')` — NOT the hovered row's own event, since
 * only the handle (and window) has listeners attached.
 *
 * Callers own all reorder semantics (zone math, what counts as a valid drop) — this hook only
 * reports "here's the row id + DOM element currently under the pointer".
 */
export function usePointerDrag<T extends string | number>(rowAttr: string, parseId: (raw: string) => T) {
  const [dragId, setDragId] = useState<T | null>(null)
  const handlersRef = useRef<{
    onHover: (id: T, el: HTMLElement, e: PointerEvent) => void
    onDrop: (id: T, el: HTMLElement, e: PointerEvent) => void
    onLeave: () => void
    onCancel: () => void
  } | null>(null)

  function hitTest(x: number, y: number): { id: T; el: HTMLElement } | null {
    const el = document.elementFromPoint(x, y)
    const row = el?.closest(`[${rowAttr}]`) as HTMLElement | null
    if (!row) return null
    const raw = row.getAttribute(rowAttr)
    return raw == null ? null : { id: parseId(raw), el: row }
  }

  function startDrag(id: T, handlers: {
    onHover: (id: T, el: HTMLElement, e: PointerEvent) => void
    onDrop: (id: T, el: HTMLElement, e: PointerEvent) => void
    onLeave: () => void
    onCancel: () => void
  }) {
    setDragId(id)
    handlersRef.current = handlers

    function move(e: PointerEvent) {
      const hit = hitTest(e.clientX, e.clientY)
      if (hit) handlersRef.current?.onHover(hit.id, hit.el, e)
      else handlersRef.current?.onLeave()
    }
    function up(e: PointerEvent) {
      const hit = hitTest(e.clientX, e.clientY)
      if (hit) handlersRef.current?.onDrop(hit.id, hit.el, e)
      else handlersRef.current?.onCancel()
      finish()
    }
    function cancel() { handlersRef.current?.onCancel(); finish() }
    function finish() {
      window.removeEventListener('pointermove', move)
      window.removeEventListener('pointerup', up)
      window.removeEventListener('pointercancel', cancel)
      handlersRef.current = null
      setDragId(null)
    }
    window.addEventListener('pointermove', move)
    window.addEventListener('pointerup', up)
    window.addEventListener('pointercancel', cancel)
  }

  return { dragId, startDrag }
}
