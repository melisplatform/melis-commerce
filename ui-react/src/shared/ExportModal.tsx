import { useState, type CSSProperties } from 'react'
import { card, iconBtn, btnGhost, btnPrimary, panelCss, panelTitle } from './styles'
import { GripIcon, ExcelIcon, FileTextIcon, FileDownIcon } from './icons'
import { useIsNarrow } from './useIsNarrow'
import type { ColDef } from './columns'
import type { T } from './i18n'

/**
 * Modale d'export générique (CSV / Excel) — même design que l'outil Users natif.
 * Excel via le SheetJS partagé par l'hôte (window.MelisXLSX), repli CSV si absent.
 * L'appelant fournit les lignes (`items`), `getCell(item, colId)` et `labelFor(colId)`.
 */
export function ExportModal<TItem>({ cols, items, getCell, labelFor, filename, sheetTitle, t, onClose }: {
  cols: ColDef[]; items: TItem[]; getCell: (it: TItem, colId: string) => string | number
  labelFor: (colId: string) => string; filename: string; sheetTitle: string; t: T; onClose: () => void
}) {
  const narrow = useIsNarrow()
  const [included, setIncluded] = useState<ColDef[]>(() => cols.filter((c) => c.visible))
  const [excluded, setExcluded] = useState<ColDef[]>(() => cols.filter((c) => !c.visible))
  const [format, setFormat] = useState<'xlsx' | 'csv'>('xlsx')
  const [exporting, setExporting] = useState(false)
  const [dragId, setDragId] = useState<string | null>(null)
  const [over, setOver] = useState<{ id: string; panel: 'included' | 'excluded' } | null>(null)

  function drop(panel: 'included' | 'excluded') {
    if (!dragId) return
    const src = [...included, ...excluded].find((c) => c.id === dragId)!
    const inc = included.filter((c) => c.id !== dragId)
    const exc = excluded.filter((c) => c.id !== dragId)
    if (panel === 'included') {
      const dst = over?.id
      if (!dst || dst === '__panel__') setIncluded([...inc, src])
      else { const i = inc.findIndex((c) => c.id === dst); setIncluded(i === -1 ? [...inc, src] : [...inc.slice(0, i), src, ...inc.slice(i)]) }
      setExcluded(exc)
    } else { setIncluded(inc); setExcluded([...exc, src]) }
    setDragId(null); setOver(null)
  }

  function item(col: ColDef, panel: 'included' | 'excluded') {
    const isOver = over?.id === col.id && over?.panel === panel
    return (
      <div key={col.id} draggable
        onDragStart={() => setDragId(col.id)}
        onDragEnd={() => { setDragId(null); setOver(null) }}
        onDragOver={(e) => { e.preventDefault(); e.stopPropagation(); if (over?.id !== col.id || over?.panel !== panel) setOver({ id: col.id, panel }) }}
        onDrop={(e) => { e.preventDefault(); drop(panel) }}
        style={{ display: 'flex', alignItems: 'center', gap: 8, borderRadius: 8, padding: '6px 8px', fontSize: 14, cursor: 'grab', userSelect: 'none', opacity: dragId === col.id ? 0.4 : 1, background: isOver ? 'color-mix(in srgb, var(--color-primary) 12%, transparent)' : 'transparent' }}>
        <GripIcon /><span style={{ flex: 1, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{labelFor(col.id)}</span>
      </div>
    )
  }

  const fmtBtn = (sel: boolean): CSSProperties => ({
    display: 'flex', flex: 1, alignItems: 'center', justifyContent: 'center', gap: 8, height: 40, borderRadius: 8, border: 0, fontSize: 14, fontWeight: 500, cursor: 'pointer',
    background: sel ? 'var(--color-card)' : 'transparent', color: sel ? 'var(--color-foreground)' : 'var(--color-muted-foreground)', boxShadow: sel ? '0 1px 2px rgba(0,0,0,.1)' : 'none',
  })

  async function doExport() {
    if (included.length === 0) return
    setExporting(true)
    try {
      const header = included.map((c) => labelFor(c.id))
      const rows = items.map((it) => included.map((c) => getCell(it, c.id)))
      const dateStr = new Date().toISOString().slice(0, 10)
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      const XLSX = (window as unknown as { MelisXLSX?: any }).MelisXLSX
      if (format === 'xlsx' && XLSX) {
        const ws = XLSX.utils.aoa_to_sheet([header, ...rows])
        const wb = XLSX.utils.book_new()
        XLSX.utils.book_append_sheet(wb, ws, sheetTitle)
        XLSX.writeFile(wb, `${filename}-${dateStr}.xlsx`)
      } else {
        const csv = [header, ...rows].map((row) => row.map((v) => `"${String(v ?? '').replace(/"/g, '""')}"`).join(',')).join('\n')
        const blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8;' })
        const url = URL.createObjectURL(blob)
        const a = Object.assign(document.createElement('a'), { href: url, download: `${filename}-${dateStr}.csv` })
        document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url)
      }
      onClose()
    } catch { /* ignore */ } finally { setExporting(false) }
  }

  const empty: CSSProperties = { flex: 1, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 11, color: 'var(--color-muted-foreground)', opacity: 0.5, padding: '16px 0' }
  const pane = (which: 'included' | 'excluded', list: ColDef[]) => (
    <div style={panelCss}
      onDragOver={(e) => { e.preventDefault(); if (over?.id !== '__panel__' || over?.panel !== which) setOver({ id: '__panel__', panel: which }) }}
      onDrop={(e) => { e.preventDefault(); drop(which) }}>
      <p style={panelTitle}>{which === 'included' ? t('exp_included') : t('exp_excluded')}</p>
      {list.length === 0 ? <div style={empty}>{t('drag_here')}</div> : list.map((c) => item(c, which))}
    </div>
  )

  return (
    <div onClick={(e) => { if (e.target === e.currentTarget) onClose() }}
      style={{ position: 'fixed', inset: 0, zIndex: 50, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
      <div style={{ ...card, width: '100%', maxWidth: 480 }}>
        <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', padding: '16px 20px', borderBottom: '1px solid var(--color-border)' }}>
          <div>
            <h3 style={{ fontSize: 14, fontWeight: 600, margin: 0 }}>{t('exp_title')}</h3>
            <p style={{ fontSize: 12, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('exp_subtitle', { n: items.length })}</p>
          </div>
          <button style={{ ...iconBtn, width: 22, height: 22 }} onClick={onClose}>✕</button>
        </div>
        <div style={{ padding: 16, display: 'flex', flexDirection: 'column', gap: 16 }}>
          <div style={{ display: 'flex', gap: 4, padding: 4, borderRadius: 8, border: '1px solid var(--color-border)', background: 'var(--color-muted,rgba(0,0,0,.04))' }}>
            <button style={fmtBtn(format === 'xlsx')} onClick={() => setFormat('xlsx')}><ExcelIcon /> Excel (.xlsx)</button>
            <button style={fmtBtn(format === 'csv')} onClick={() => setFormat('csv')}><FileTextIcon /> CSV (.csv)</button>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 8 }}>
            {pane('excluded', excluded)}
            {pane('included', included)}
          </div>
        </div>
        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, padding: '12px 16px', borderTop: '1px solid var(--color-border)' }}>
          <button style={btnGhost} onClick={onClose} disabled={exporting}>{t('cancel')}</button>
          <button style={{ ...btnPrimary, opacity: included.length === 0 || exporting ? 0.6 : 1 }} onClick={doExport} disabled={exporting || included.length === 0}>
            <FileDownIcon />{exporting ? t('exp_exporting') : t('exp_download', { fmt: format.toUpperCase() })}
          </button>
        </div>
      </div>
    </div>
  )
}
