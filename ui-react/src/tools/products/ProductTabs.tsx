import { createPortal } from 'react-dom'
import { type ChangeEvent, useEffect, useRef, useState } from 'react'
import {
  fetchProductVariants, saveProductVariant, deleteProductVariant, duplicateProductVariant,
  fetchProductPrices, saveProductPrice, deleteProductPrice,
  fetchProductAttributes, saveProductAttribute, deleteProductAttribute,
  fetchProductMedia, uploadProductMedia, updateProductMedia, deleteProductMedia,
  fetchDocumentTypes, addDocumentType,
  fetchProductAlert, saveProductRecipient, deleteProductRecipient, fetchProductTooltip,
  type ProductVariant, type ProductPrice, type ProductAttribute, type MediaItem, type DocType, type AlertRecipient, type UserOption, type CountryOption, type TooltipVariant,
} from './api'
import { card, inputCss, btnPrimary, btnGhost, iconBtn, th, td, label, hint } from '../../shared/styles'
import { PencilIcon, EyeIcon, TrashIcon, PlusIcon, GlobeIcon, ImageIcon, ChevronDownIcon, PaperclipIcon, CubesIcon } from '../../shared/icons'
import { StatusBadge } from '../../shared/widgets'
import type { Option } from '../../shared/api'
import { notify } from '../../shared/notify'
import { fetchCatalogTree, type CatNode, type LangOption } from '../catalog/api'
import { fetchPageTree, type PageNode } from './api'

type TFn = (k: string, v?: Record<string, string | number>) => string

// ── Bascule de statut Actif/Inactif (libellé STATUS) ────────────────────────────
export function StatusToggle({ active, onChange, t }: { active: boolean; onChange: (v: boolean) => void; t: TFn }) {
  return (
    <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
      <span style={{ fontSize: 14, fontWeight: 600, color: active ? '#16a34a' : 'var(--color-muted-foreground)' }}>{active ? t('online') : t('offline')}</span>
      <button type="button" onClick={() => onChange(!active)}
        style={{ position: 'relative', width: 46, height: 24, borderRadius: 999, border: 0, cursor: 'pointer', background: active ? '#22c55e' : 'var(--color-muted-foreground)', flexShrink: 0, transition: 'background .15s' }}>
        <span style={{ position: 'absolute', top: 2, left: active ? 24 : 2, width: 20, height: 20, borderRadius: 999, background: '#fff', transition: 'left .15s', boxShadow: '0 1px 2px rgba(0,0,0,.3)' }} />
      </button>
      <span style={{ fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)' }}>{t('status_label')}</span>
    </div>
  )
}

// ── Sélecteur vertical à drapeaux (langue ou pays) ──────────────────────────────
export function FlagSwitcher({ items, value, onChange }: { items: { id: number; name: string; flag?: string; hasPending?: boolean }[]; value: number; onChange: (id: number) => void }) {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 4, minWidth: 150 }}>
      {items.map((l) => {
        const on = l.id === value
        return (
          <button key={l.id} onClick={() => onChange(l.id)}
            style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, padding: '8px 10px', borderRadius: 6, cursor: 'pointer', textAlign: 'left',
              border: '1px solid ' + (on ? 'var(--color-primary)' : 'var(--color-border)'), background: on ? 'var(--color-primary)' : 'transparent',
              color: on ? 'var(--color-primary-foreground,#fff)' : 'inherit', fontWeight: on ? 600 : 400, fontSize: 14 }}>
            <span style={{ display: 'flex', alignItems: 'center', gap: 5 }}>
              {l.hasPending && <span style={{ width: 6, height: 6, borderRadius: 999, background: '#f59e0b', flexShrink: 0 }} />}
              {l.name}
            </span>
            {l.flag ? <img src={`data:image/png;base64,${l.flag}`} alt="" style={{ width: 22, height: 22, borderRadius: 3 }} /> : <span style={{ opacity: .5 }}>🌐</span>}
          </button>
        )
      })}
    </div>
  )
}

// ── Tooltip d'aide (icône (i) à côté d'un champ) ────────────────────────────────
export function InfoDot({ text }: { text: string }) {
  if (!text) return null
  return (
    <span title={text} style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 15, height: 15, borderRadius: 999, background: 'var(--color-muted-foreground)', color: 'var(--color-background,#fff)', fontSize: 10, fontWeight: 700, cursor: 'help', flexShrink: 0 }}>i</span>
  )
}

// Icône hiérarchie (bouton « ouvrir l'arbre du site » + titre de la modale).
export function SitemapIcon() {
  return (
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
      <rect x="9" y="2" width="6" height="6" rx="1" /><rect x="2" y="16" width="6" height="6" rx="1" /><rect x="16" y="16" width="6" height="6" rx="1" />
      <path d="M12 8v4M5 16v-2h14v2M12 12v2" />
    </svg>
  )
}

// Icône d'un nœud de page selon son type (SITE / FOLDER / PAGE / NEWS_DETAIL).
function PageTypeIcon({ type }: { type: string }) {
  const c = { width: 15, height: 15, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: 2, strokeLinecap: 'round' as const, strokeLinejoin: 'round' as const }
  switch ((type || '').toUpperCase()) {
    case 'SITE': return <svg {...c}><path d="M3 11l9-8 9 8" /><path d="M5 10v10h14V10" /><path d="M10 20v-6h4v6" /></svg>
    case 'FOLDER': return <svg {...c}><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /></svg>
    case 'NEWS_DETAIL': return <svg {...c}><path d="M4 4h13v16H6a2 2 0 0 1-2-2z" /><path d="M17 8h3v10a2 2 0 0 1-2 2" /><path d="M8 8h6M8 12h6M8 16h4" /></svg>
    default: return <svg {...c}><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><path d="M14 2v6h6" /></svg>
  }
}

// ── Modale « Site tree view » (arbre des pages CMS, sélection simple) ────────────
function filterPages(nodes: PageNode[], q: string): PageNode[] {
  if (!q) return nodes
  const lq = q.toLowerCase()
  const walk = (list: PageNode[]): PageNode[] => list.flatMap((n) => {
    const kids = walk(n.children)
    if (n.name.toLowerCase().includes(lq) || String(n.id).includes(lq) || kids.length) return [{ ...n, children: kids }]
    return []
  })
  return walk(nodes)
}
export function SiteTreeModal({ t, onClose, onInsert }: { t: TFn; onClose: () => void; onInsert: (pageId: number) => void }) {
  const [tree, setTree] = useState<PageNode[]>([])
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  const [search, setSearch] = useState('')
  const [selected, setSelected] = useState<number | null>(null)
  const [tick, setTick] = useState(0)

  useEffect(() => { fetchPageTree().then((r) => { setTree(r.items); setExpanded((p) => p.size ? p : new Set(r.items.map((n) => n.id))) }).catch(() => null) }, [tick])
  const view = filterPages(tree, search.trim())
  function toggleExp(id: number) { setExpanded((p) => { const n = new Set(p); n.has(id) ? n.delete(id) : n.add(id); return n }) }

  const renderNodes = (nodes: PageNode[], depth: number) => nodes.map((n) => (
    <div key={n.id}>
      <div onClick={() => setSelected(n.id)} style={{ display: 'flex', alignItems: 'center', gap: 8, marginLeft: depth * 20, padding: '5px 6px', borderRadius: 6, cursor: 'pointer', background: selected === n.id ? 'color-mix(in srgb, var(--color-primary) 16%, transparent)' : 'transparent' }}>
        <button onClick={(e) => { e.stopPropagation(); n.children.length && toggleExp(n.id) }} style={{ ...iconBtn, width: 18, height: 18, visibility: n.children.length ? 'visible' : 'hidden' }}>{expanded.has(n.id) ? '−' : '+'}</button>
        <span style={{ display: 'inline-flex', color: 'var(--color-muted-foreground)' }}><PageTypeIcon type={n.type} /></span>
        <span style={{ fontWeight: depth === 0 ? 600 : 400, color: selected === n.id ? 'var(--color-primary)' : 'inherit' }}>{n.id} - {n.name}</span>
      </div>
      {expanded.has(n.id) && n.children.length > 0 && renderNodes(n.children, depth + 1)}
    </div>
  ))

  return (
    <div style={{ position: 'fixed', inset: 0, zIndex: 70, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
      <div style={{ ...card, padding: 0, width: '100%', maxWidth: 540, maxHeight: '82vh', display: 'flex', flexDirection: 'column' }}>
        <div style={{ padding: '16px 20px', borderBottom: '1px solid var(--color-border)' }}>
          <h3 style={{ fontSize: 16, fontWeight: 700, margin: 0, display: 'flex', alignItems: 'center', gap: 8, color: 'var(--color-primary)' }}><SitemapIcon />{t('sitetree_title')}</h3>
        </div>
        <div style={{ padding: '14px 20px', display: 'flex', flexDirection: 'column', gap: 10, overflow: 'auto', flex: 1 }}>
          <div style={{ display: 'flex', gap: 8 }}>
            <input style={{ ...inputCss, height: 36 }} value={search} onChange={(e) => setSearch(e.target.value)} placeholder={t('sitetree_search')} />
            <button style={{ ...btnGhost, height: 36 }} onClick={() => setTick((x) => x + 1)} title={t('refresh')}>↻</button>
          </div>
          <div style={{ border: '1px solid var(--color-border)', borderRadius: 8, padding: 8, minHeight: 120 }}>
            {view.length === 0 ? <p style={{ ...hint, margin: 8 }}>{t('empty')}</p> : renderNodes(view, 0)}
          </div>
        </div>
        <div style={{ padding: '14px 20px', borderTop: '1px solid var(--color-border)', display: 'flex', justifyContent: 'space-between' }}>
          <button style={btnGhost} onClick={onClose}>✕ {t('cancel')}</button>
          <button style={btnPrimary} disabled={selected == null} onClick={() => { if (selected != null) { onInsert(selected); onClose() } }}>✓ {t('sitetree_insert')}</button>
        </div>
      </div>
    </div>
  )
}

// ── Modale « Liste des catégories » (arbre catalogues/catégories à cocher) ──────
function flattenCatNames(nodes: CatNode[], map: Record<number, string>) {
  for (const n of nodes) { map[n.id] = n.name; flattenCatNames(n.children, map) }
}
function filterCatTree(nodes: CatNode[], q: string): CatNode[] {
  if (!q) return nodes
  const lq = q.toLowerCase()
  const walk = (list: CatNode[]): CatNode[] => list.flatMap((n) => {
    const kids = walk(n.children)
    if (n.name.toLowerCase().includes(lq) || String(n.id).includes(lq) || kids.length) return [{ ...n, children: kids }]
    return []
  })
  return walk(nodes)
}

export function CategoryPickerModal({ initialSelected, languages, t, onClose, onApply }: {
  initialSelected: number[]; languages: LangOption[]; t: TFn; onClose: () => void; onApply: (ids: number[]) => void
}) {
  const [tree, setTree] = useState<CatNode[]>([])
  const [checked, setChecked] = useState<Set<number>>(new Set(initialSelected))
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  const [search, setSearch] = useState('')
  const [langId, setLangId] = useState<number>(languages[0]?.id ?? 0)
  const [tick, setTick] = useState(0)

  useEffect(() => {
    fetchCatalogTree(langId || undefined).then((r) => { setTree(r.items); setExpanded((p) => p.size ? p : new Set(r.items.map((n) => n.id))) }).catch(() => null)
  }, [langId, tick])

  const view = filterCatTree(tree, search.trim())
  const allIds = (nodes: CatNode[]): number[] => nodes.flatMap((n) => [n.id, ...allIds(n.children)])
  function toggleCheck(id: number) { setChecked((p) => { const n = new Set(p); n.has(id) ? n.delete(id) : n.add(id); return n }) }
  function toggleExp(id: number) { setExpanded((p) => { const n = new Set(p); n.has(id) ? n.delete(id) : n.add(id); return n }) }

  const renderNodes = (nodes: CatNode[], depth: number) => nodes.map((n) => (
    <div key={n.id}>
      <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginLeft: depth * 20, padding: '4px 2px' }}>
        <button onClick={() => n.children.length && toggleExp(n.id)} style={{ ...iconBtn, width: 18, height: 18, visibility: n.children.length ? 'visible' : 'hidden' }}>{expanded.has(n.id) ? '▾' : '▸'}</button>
        <input type="checkbox" checked={checked.has(n.id)} onChange={() => toggleCheck(n.id)} style={{ accentColor: 'var(--color-primary)' }} />
        <span style={{ display: 'inline-flex', color: n.status === 1 ? '#16a34a' : '#dc2626', fontSize: 10 }}>●</span>
        <span style={{ fontWeight: n.type === 'catalog' ? 600 : 400 }}>{n.id} - {n.name}</span>
      </div>
      {expanded.has(n.id) && n.children.length > 0 && renderNodes(n.children, depth + 1)}
    </div>
  ))

  return (
    <div style={{ position: 'fixed', inset: 0, zIndex: 60, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
      <div style={{ ...card, padding: 0, width: '100%', maxWidth: 560, maxHeight: '85vh', display: 'flex', flexDirection: 'column' }}>
        <div style={{ padding: '16px 20px', borderBottom: '1px solid var(--color-border)' }}>
          <h3 style={{ fontSize: 16, fontWeight: 700, margin: 0 }}>{t('cat_modal_title')}</h3>
          <p style={{ fontSize: 13, color: 'var(--color-muted-foreground)', margin: '4px 0 0' }}>{t('cat_modal_subtitle')}</p>
        </div>
        <div style={{ padding: '14px 20px', display: 'flex', flexDirection: 'column', gap: 10, overflow: 'auto', flex: 1 }}>
          <input style={{ ...inputCss, height: 36 }} value={search} onChange={(e) => setSearch(e.target.value)} placeholder={t('cat_search')} />
          <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', alignItems: 'center' }}>
            <select style={{ ...inputCss, height: 34, width: 'auto', minWidth: 110 }} value={langId} onChange={(e) => setLangId(Number(e.target.value))}>
              {languages.map((l) => <option key={l.id} value={l.id}>{l.name}</option>)}
            </select>
            <button style={{ ...btnGhost, height: 34 }} onClick={() => setSearch('')}>{t('clear')}</button>
            <button style={{ ...btnGhost, height: 34 }} onClick={() => setExpanded(new Set())}>{t('collapse_all')}</button>
            <button style={{ ...btnGhost, height: 34 }} onClick={() => setExpanded(new Set(allIds(tree)))}>{t('expand_all')}</button>
            <button style={{ ...btnGhost, height: 34 }} onClick={() => setTick((x) => x + 1)}>{t('refresh')}</button>
          </div>
          <div style={{ border: '1px solid var(--color-border)', borderRadius: 8, padding: 8, minHeight: 120 }}>
            {view.length === 0 ? <p style={{ ...hint, margin: 8 }}>{t('empty')}</p> : renderNodes(view, 0)}
          </div>
        </div>
        <div style={{ padding: '14px 20px', borderTop: '1px solid var(--color-border)', display: 'flex', justifyContent: 'space-between' }}>
          <button style={btnGhost} onClick={onClose}>✕ {t('cancel')}</button>
          <button style={btnPrimary} onClick={() => { onApply([...checked]); onClose() }}><PlusIcon />{t('attr_add')}</button>
        </div>
      </div>
    </div>
  )
}
export { flattenCatNames }

const sectionTitle = { fontSize: 12, fontWeight: 600, display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, color: 'var(--color-foreground)', margin: '0 0 12px' } as const

/** Lit un fichier en data URL base64 (pour l'upload média React). */
export function readFileDataUrl(file: File): Promise<string> {
  return new Promise((res, rej) => { const r = new FileReader(); r.onload = () => res(String(r.result)); r.onerror = rej; r.readAsDataURL(file) })
}
/** Modale de duplication (produit ou variante) : options images/documents/en ligne + SKU/référence. */
const ynBtn = (on: boolean, color: string) => ({ padding: '6px 16px', borderRadius: 6, border: 0, cursor: 'pointer', fontSize: 14, fontWeight: 600, minWidth: 56, background: on ? color : 'transparent', color: on ? '#fff' : 'var(--color-foreground)' } as const)
export function DuplicateModal({ title, intro, fieldLabel, fieldPlaceholder, t, onClose, onConfirm }: {
  title: string; intro: string; fieldLabel: string; fieldPlaceholder: string; t: TFn
  onClose: () => void; onConfirm: (o: { duplicateImages: boolean; duplicateDocuments: boolean; putOnline: boolean; field: string }) => void
}) {
  const [images, setImages] = useState(true)
  const [docs, setDocs] = useState(true)
  const [online, setOnline] = useState(false)
  const [field, setField] = useState('')
  const [err, setErr] = useState(false)
  const [busy, setBusy] = useState(false)
  const YesNo = ({ v, set }: { v: boolean; set: (b: boolean) => void }) => (
    <div style={{ display: 'inline-flex', gap: 2, padding: 2, borderRadius: 8, border: '1px solid var(--color-border)' }}>
      <button style={ynBtn(v, '#16a34a')} onClick={() => set(true)}>{t('yes')}</button>
      <button style={ynBtn(!v, '#dc2626')} onClick={() => set(false)}>{t('no')}</button>
    </div>
  )
  const row = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, marginTop: 14 } as const
  return (
    <div style={{ position: 'fixed', inset: 0, zIndex: 70, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
      <div style={{ ...card, padding: 0, width: '100%', maxWidth: 480 }}>
        <div style={{ padding: '16px 20px', borderBottom: '1px solid var(--color-border)', display: 'flex', alignItems: 'center', gap: 8, color: 'var(--color-primary)' }}>
          <PlusIcon /><h3 style={{ fontSize: 16, fontWeight: 700, margin: 0 }}>{title}</h3>
        </div>
        <div style={{ padding: '16px 20px' }}>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', marginTop: 0 }}>{intro}</p>
          <div style={row}><span style={{ fontSize: 14, fontWeight: 500 }}>{t('dup_images')}</span><YesNo v={images} set={setImages} /></div>
          <div style={row}><span style={{ fontSize: 14, fontWeight: 500 }}>{t('dup_documents')}</span><YesNo v={docs} set={setDocs} /></div>
          <div style={row}><span style={{ fontSize: 14, fontWeight: 500 }}>{t('dup_online')}</span><YesNo v={online} set={setOnline} /></div>
          <label style={{ ...label, marginTop: 18, color: err ? '#dc2626' : undefined }}>{fieldLabel}</label>
          <input style={{ ...inputCss, ...(err ? { borderColor: '#dc2626' } : {}) }} value={field} onChange={(e) => { setField(e.target.value); setErr(false) }} autoComplete="off" />
        </div>
        <div style={{ padding: '14px 20px', borderTop: '1px solid var(--color-border)', display: 'flex', justifyContent: 'space-between' }}>
          <button style={btnGhost} onClick={onClose}>✕ {t('cancel')}</button>
          <button style={btnPrimary} disabled={busy} onClick={() => { if (!field.trim()) { setErr(true); return } setBusy(true); onConfirm({ duplicateImages: images, duplicateDocuments: docs, putOnline: online, field: field.trim() }) }}>{busy ? '…' : t('var_duplicate')}</button>
        </div>
      </div>
    </div>
  )
}

/** Bouton d'upload (label stylé + input file caché). */
export function UploadBtn({ label, accept, busy, onPick }: { label: string; accept?: string; busy?: boolean; onPick: (f: File) => void }) {
  return (
    <label style={{ ...btnGhost, cursor: busy ? 'wait' : 'pointer', opacity: busy ? 0.6 : 1 }}>
      <PlusIcon />{busy ? '…' : label}
      <input type="file" accept={accept} style={{ display: 'none' }} disabled={busy} onChange={(e) => { const f = e.target.files?.[0]; if (f) onPick(f); e.target.value = '' }} />
    </label>
  )
}

// ── Section Attributs (Propriétés) — ajout / suppression ────────────────────────
export function AttributesSection({ productId, options, t, pendingAttrIds = [], onTogglePendingAttr, onMarkDeleteAttr }: {
  productId: number | null; options: Option[]; t: TFn
  pendingAttrIds?: number[]; onTogglePendingAttr?: (id: number) => void
  onMarkDeleteAttr?: (pattId: number) => void
}) {
  const [items, setItems] = useState<ProductAttribute[]>([])
  const [tick, setTick] = useState(0)
  const [pick, setPick] = useState<number>(0)
  const [toDelete, setToDelete] = useState<ProductAttribute | null>(null)

  useEffect(() => { if (!productId) return; fetchProductAttributes(productId).then((r) => setItems(r.items)).catch(() => null) }, [productId, tick])

  function add() {
    if (pick <= 0) return
    onTogglePendingAttr?.(pick); setPick(0)
  }
  function confirmDelete() {
    if (!toDelete) return
    setItems((p) => p.filter((x) => x.pattId !== toDelete.pattId))
    onMarkDeleteAttr?.(toDelete.pattId)
    setToDelete(null)
  }
  const pendingItems = options.filter((o) => pendingAttrIds.includes(o.id))
  const avail = options.filter((o) => !items.some((it) => it.attributeId === o.id) && !pendingAttrIds.includes(o.id))
  const allEmpty = items.length === 0 && pendingItems.length === 0

  return (
    <section>
      <h3 style={sectionTitle}><span style={{ display: 'flex', alignItems: 'center', gap: 6 }}><CubesIcon />{t('sec_attributes')}</span></h3>
      <div style={{ display: 'flex', gap: 8, marginBottom: 12 }}>
        <select style={{ ...inputCss, flex: 1 }} value={pick} onChange={(e) => setPick(Number(e.target.value))}>
          <option value={0}>{t('attr_add_ph')}</option>
          {avail.map((o) => <option key={o.id} value={o.id}>{o.name}</option>)}
        </select>
        <button style={btnGhost} onClick={add} disabled={pick <= 0}><PlusIcon />{t('attr_add')}</button>
      </div>
      {allEmpty ? <p style={{ ...hint, margin: 0 }}>{t('attr_empty')}</p> : (
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
          {items.map((it) => (
            <span key={it.pattId} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
              {it.name}
              <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => setToDelete(it)}><TrashIcon /></button>
            </span>
          ))}
          {pendingItems.map((o) => (
            <span key={`p-${o.id}`} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
              {o.name}
              <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => onTogglePendingAttr?.(o.id)}><TrashIcon /></button>
            </span>
          ))}
        </div>
      )}
      {toDelete && <ConfirmModal t={t} title={t('attr_del_title')} message={t('attr_del_confirm', { u: toDelete.name })} onCancel={() => setToDelete(null)} onConfirm={confirmDelete} />}
    </section>
  )
}

const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

// ── Section Destinataires d'alerte stock (ajout / suppression) ──────────────────
export function RecipientsSection({ productId, users, stockLevel, t, pendingRecipientIds = [], onTogglePendingRecipient, pendingRecipientEmails = [], onAddPendingRecipientEmail, onRemovePendingRecipientEmail, onMarkDeleteRecipient }: {
  productId: number | null; users: UserOption[]; stockLevel: number | null; t: TFn
  pendingRecipientIds?: number[]; onTogglePendingRecipient?: (id: number) => void
  pendingRecipientEmails?: string[]; onAddPendingRecipientEmail?: (email: string) => void; onRemovePendingRecipientEmail?: (email: string) => void
  onMarkDeleteRecipient?: (seaId: number) => void
}) {
  const [items, setItems] = useState<AlertRecipient[]>([])
  const [tick, setTick] = useState(0)
  const [pick, setPick] = useState<number>(0)
  const [emailInput, setEmailInput] = useState('')
  const [toDelete, setToDelete] = useState<AlertRecipient | null>(null)

  useEffect(() => { if (!productId) return; fetchProductAlert(productId).then((r) => setItems(r.recipients)).catch(() => null) }, [productId, tick])

  function add() {
    if (pick <= 0) return
    onTogglePendingRecipient?.(pick); setPick(0)
  }
  function addEmail() {
    const email = emailInput.trim()
    if (!EMAIL_RE.test(email)) return
    if (items.some((it) => it.email === email) || pendingRecipientEmails.includes(email)) { setEmailInput(''); return }
    onAddPendingRecipientEmail?.(email); setEmailInput('')
  }
  function confirmDelete() {
    if (!toDelete) return
    setItems((p) => p.filter((x) => x.seaId !== toDelete.seaId))
    onMarkDeleteRecipient?.(toDelete.seaId)
    setToDelete(null)
  }
  const pendingUsers = users.filter((u) => pendingRecipientIds.includes(u.id))
  const avail = users.filter((u) => !items.some((it) => it.userId === u.id) && !pendingRecipientIds.includes(u.id))
  const allEmpty = items.length === 0 && pendingUsers.length === 0 && pendingRecipientEmails.length === 0
  const emailValid = EMAIL_RE.test(emailInput.trim())

  return (
    <section>
      <h3 style={sectionTitle}>{t('sec_recipients')}</h3>
      <div style={{ display: 'flex', gap: 8, marginBottom: 8 }}>
        <select style={{ ...inputCss, flex: 1 }} value={pick} onChange={(e) => setPick(Number(e.target.value))}>
          <option value={0}>{t('recip_add_ph')}</option>
          {avail.map((u) => <option key={u.id} value={u.id}>{u.name}</option>)}
        </select>
        <button style={btnGhost} onClick={add} disabled={pick <= 0}><PlusIcon />{t('recip_add')}</button>
      </div>
      <div style={{ display: 'flex', gap: 8, marginBottom: 12 }}>
        <input type="email" style={{ ...inputCss, flex: 1 }} value={emailInput} onChange={(e) => setEmailInput(e.target.value)}
          onKeyDown={(e) => { if (e.key === 'Enter') { e.preventDefault(); addEmail() } }}
          placeholder={t('recip_email_ph')} autoComplete="off" />
        <button style={btnGhost} onClick={addEmail} disabled={!emailValid}><PlusIcon />{t('recip_email_add')}</button>
      </div>
      {emailInput.trim() !== '' && !emailValid && <p style={{ ...hint, color: '#dc2626', margin: '-6px 0 12px' }}>{t('err_invalid_email')}</p>}
      {allEmpty ? <p style={{ ...hint, margin: 0 }}>{t('recip_empty')}</p> : (
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
          {items.map((it) => (
            <span key={it.seaId} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
              {it.name || it.email}
              <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => setToDelete(it)}><TrashIcon /></button>
            </span>
          ))}
          {pendingUsers.map((u) => (
            <span key={`p-${u.id}`} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
              {u.name}
              <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => onTogglePendingRecipient?.(u.id)}><TrashIcon /></button>
            </span>
          ))}
          {pendingRecipientEmails.map((email) => (
            <span key={`pe-${email}`} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
              {email}
              <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => onRemovePendingRecipientEmail?.(email)}><TrashIcon /></button>
            </span>
          ))}
        </div>
      )}
      {toDelete && <ConfirmModal t={t} title={t('recip_del_title')} message={t('recip_del_confirm', { u: toDelete.name || toDelete.email })} onCancel={() => setToDelete(null)} onConfirm={confirmDelete} />}
    </section>
  )
}

// ── Types médias en attente (nouveau produit non encore sauvegardé) ─────────────
export interface PendingMedia { name: string; data: string; typeId?: number | null; countryId?: number }

// ── Modal ajout / édition d'un média (image ou fichier) ────────────────────────
export function MediaModal({ kind, doc, pendingDoc, countries, t, onClose, onSaved, onAddPending, onUpdate, onUpdatePending }: {
  kind: 'image' | 'file'; doc?: MediaItem | null
  /** Edit an already-added-but-not-yet-saved item (no server docId yet) — e.g. re-picking the file
   * or changing country/type right after upload, without needing a product save first. */
  pendingDoc?: PendingMedia | null
  countries: CountryOption[]; t: TFn
  onClose: () => void; onSaved?: () => void
  onAddPending?: (m: PendingMedia) => Promise<void> | void
  onUpdate?: (docId: number, m: { name: string; typeId?: number | null; countryId?: number; data?: string }) => Promise<void>
  onUpdatePending?: (m: PendingMedia) => void
}) {
  const isEdit = !!doc
  const isEditPending = !!pendingDoc
  const [imgTypes, setImgTypes] = useState<DocType[]>([])
  const [fileTypes, setFileTypes] = useState<DocType[]>([])
  const [name, setName] = useState(doc?.name ?? pendingDoc?.name ?? '')
  const [typeId, setTypeId] = useState<number | null>(doc?.typeId ?? pendingDoc?.typeId ?? null)
  const [countryId, setCountryId] = useState<number | null>(doc?.countryId ?? pendingDoc?.countryId ?? null)
  const [fileData, setFileData] = useState<string | null>(null)
  const [preview, setPreview] = useState<string | null>(kind === 'image' ? (doc?.path ?? pendingDoc?.data ?? null) : null)
  const [saving, setSaving] = useState(false)
  const [showAddType, setShowAddType] = useState(false)
  const [newCode, setNewCode] = useState('')
  const [newTypeName, setNewTypeName] = useState('')
  const [addingType, setAddingType] = useState(false)
  const [typeError, setTypeError] = useState('')
  const [countryError, setCountryError] = useState('')
  const fileRef = useRef<HTMLInputElement>(null)

  useEffect(() => {
    fetchDocumentTypes().then(d => { setImgTypes(d.imageTypes); setFileTypes(d.fileTypes) }).catch(() => null)
  }, [])

  const types = kind === 'image' ? imgTypes : fileTypes

  async function onFileChange(e: ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0]; if (!file) return
    const data = await readFileDataUrl(file)
    setFileData(data)
    setName(file.name.replace(/\.[^.]+$/, ''))
    if (kind === 'image') setPreview(data)
  }

  async function save() {
    if (!isEdit && !isEditPending && !fileData) return
    let ok = true
    if (!typeId) { setTypeError(t('modal_err_type')); ok = false } else setTypeError('')
    if (countryId == null) { setCountryError(t('modal_err_country')); ok = false } else setCountryError('')
    if (!ok) return
    setSaving(true)
    try {
      if (isEdit && doc) {
        if (onUpdate) await onUpdate(doc.id, { name, typeId, countryId: countryId ?? undefined, ...(fileData ? { data: fileData } : {}) })
        onSaved?.(); onClose()
      } else if (isEditPending && pendingDoc) {
        onUpdatePending?.({ name: name || 'file', data: fileData ?? pendingDoc.data, typeId, countryId: countryId ?? undefined })
        onClose()
      } else if (fileData) {
        await onAddPending?.({ name: name || 'file', data: fileData, typeId, countryId: countryId ?? undefined })
        onClose()
      }
    } catch { /* */ } finally { setSaving(false) }
  }

  async function doAddType() {
    if (!newCode.trim() || !newTypeName.trim()) return
    setAddingType(true)
    try {
      await addDocumentType(kind, newCode.trim().toUpperCase(), newTypeName.trim())
      const d = await fetchDocumentTypes()
      setImgTypes(d.imageTypes); setFileTypes(d.fileTypes)
      setNewCode(''); setNewTypeName(''); setShowAddType(false)
    } catch { /* */ } finally { setAddingType(false) }
  }

  return createPortal(
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.45)', zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 20 }}
      onMouseDown={(e) => { if (e.target === e.currentTarget) onClose() }}>
      <div style={{ background: 'var(--color-background,#fff)', borderRadius: 8, width: '100%', maxWidth: 480, boxShadow: '0 8px 32px rgba(0,0,0,.22)', overflow: 'hidden' }}>
        <div style={{ background: '#35a8e0', color: '#fff', padding: '12px 20px', fontWeight: 600, fontSize: 15 }}>
          {kind === 'image' ? t('modal_add_image') : t('modal_add_file')}
        </div>
        <div style={{ padding: 20, display: 'flex', flexDirection: 'column', gap: 14 }}>
          <p style={{ margin: 0, fontSize: 13, color: 'var(--color-muted-foreground)' }}>
            {kind === 'image' ? t('modal_text_prod_image') : t('modal_text_prod_file')}
          </p>
          {kind === 'image' && (
            <div style={{ width: '100%', height: 180, background: 'var(--color-muted,rgba(0,0,0,.04))', borderRadius: 6, border: '1px solid var(--color-border)', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden', cursor: 'pointer' }}
              onClick={() => fileRef.current?.click()}>
              {preview
                ? <img src={preview} alt="preview" style={{ width: '100%', height: '100%', objectFit: 'contain' }} />
                : <span style={{ fontSize: 13, color: 'var(--color-muted-foreground)' }}>{t('modal_select_image')}</span>}
            </div>
          )}
          <input ref={fileRef} type="file" accept={kind === 'image' ? 'image/*' : '*'} style={{ display: 'none' }} onChange={onFileChange} />
          <button style={{ ...btnGhost, fontSize: 13, padding: '6px 12px', alignSelf: 'flex-start' }} onClick={() => fileRef.current?.click()}>
            {kind === 'image' ? t('modal_select_image') : t('modal_select_file')}
            {fileData && <span style={{ marginLeft: 6, color: '#22c55e' }}>✓</span>}
          </button>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
            <span style={label}>{t('modal_doc_name')}</span>
            <input style={inputCss} value={name} onChange={e => setName(e.target.value)} />
          </div>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
            <span style={label}>{t('modal_doc_type')} <span style={{ color: '#ef4444' }}>*</span></span>
            <select style={inputCss} value={typeId ?? ''} onChange={e => { setTypeId(e.target.value ? Number(e.target.value) : null); if (typeError) setTypeError('') }}>
              <option value="">{t('modal_choose')}</option>
              {types.map(tp => <option key={tp.id} value={tp.id}>{tp.name}</option>)}
            </select>
            {typeError && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{typeError}</p>}
          </div>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
            <span style={label}>{t('modal_doc_country')} <span style={{ color: '#ef4444' }}>*</span></span>
            <select style={inputCss} value={countryId ?? ''} onChange={e => { setCountryId(e.target.value ? Number(e.target.value) : null); if (countryError) setCountryError('') }}>
              <option value="">{t('modal_choose')}</option>
              <option value={-1}>{t('modal_all_countries')}</option>
              {countries.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
            </select>
            {countryError && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{countryError}</p>}
          </div>
          <div style={{ borderRadius: 6, border: '1px solid var(--color-border)', overflow: 'hidden' }}>
            <button style={{ width: '100%', display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '8px 12px', background: 'var(--color-muted,rgba(0,0,0,.03))', border: 0, cursor: 'pointer', fontSize: 13, fontWeight: 500 }}
              onClick={() => setShowAddType(v => !v)}>
              {kind === 'image' ? t('modal_add_img_type') : t('modal_add_file_type')}
              <span style={{ fontSize: 11, color: 'var(--color-muted-foreground)' }}>{showAddType ? '▲' : '▼'}</span>
            </button>
            {showAddType && (
              <div style={{ padding: 12, display: 'flex', gap: 8 }}>
                <input style={{ ...inputCss, flex: 1 }} placeholder={t('modal_type_code')} value={newCode} onChange={e => setNewCode(e.target.value)} />
                <input style={{ ...inputCss, flex: 2 }} placeholder={t('modal_type_name')} value={newTypeName} onChange={e => setNewTypeName(e.target.value)} />
                <button style={{ ...btnPrimary, whiteSpace: 'nowrap' }} disabled={addingType || !newCode.trim() || !newTypeName.trim()} onClick={doAddType}>
                  {addingType ? t('modal_saving') : t('modal_type_add_btn')}
                </button>
              </div>
            )}
          </div>
        </div>
        <div style={{ padding: '12px 20px', display: 'flex', justifyContent: 'flex-end', gap: 10, borderTop: '1px solid var(--color-border)' }}>
          <button style={btnGhost} onClick={onClose}>{t('modal_close')}</button>
          <button style={btnPrimary} disabled={saving || (!isEdit && !isEditPending && !fileData)} onClick={save}>
            {saving ? t('modal_saving') : t('modal_save')}
          </button>
        </div>
      </div>
    </div>,
    document.body
  )
}

// ── Lightbox plein écran ────────────────────────────────────────────────────────
export function Lightbox({ src, onClose }: { src: string; onClose: () => void }) {
  return createPortal(
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,.88)', zIndex: 10000, display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'zoom-out' }}
      onClick={onClose}>
      <img src={src} alt="" style={{ maxWidth: '90vw', maxHeight: '90vh', objectFit: 'contain', borderRadius: 8, boxShadow: '0 4px 40px rgba(0,0,0,.6)' }} onClick={e => e.stopPropagation()} />
    </div>,
    document.body
  )
}

// ── Section Fichiers joints ─────────────────────────────────────────────────────
export function FilesSection({ productId, t, countries = [], pendingFiles = [], onAddPendingFile, onRemovePendingFile, onMarkDeleteFile, refreshSignal }: {
  productId: number | null; t: TFn; countries?: CountryOption[]
  pendingFiles?: PendingMedia[]
  onAddPendingFile?: (m: PendingMedia) => void
  onRemovePendingFile?: (index: number) => void
  onMarkDeleteFile?: (id: number) => void
  /** Bumped by the parent after a product save — see ImagesSection's refreshSignal. */
  refreshSignal?: number
}) {
  const [files, setFiles] = useState<MediaItem[]>([])
  const [tick, setTick] = useState(0)
  const [modal, setModal] = useState<{ doc?: MediaItem } | null>(null)
  useEffect(() => { if (!productId) return; fetchProductMedia(productId).then((r) => setFiles(r.files)).catch(() => null) }, [productId, tick, refreshSignal])
  function markDeleteFile(id: number) { setFiles((p) => p.filter((f) => f.id !== id)); onMarkDeleteFile?.(id) }
  const allEmpty = files.length === 0 && pendingFiles.length === 0
  return (
    <section>
      <h3 style={sectionTitle}>
        <span style={{ display: 'flex', alignItems: 'center', gap: 6 }}><PaperclipIcon />{t('sec_files')}</span>
        <button style={{ ...btnGhost, fontSize: 13, padding: '3px 10px' }} onClick={() => setModal({})}>
          {t('file_add')}
        </button>
      </h3>
      {allEmpty ? (
        <div style={{ padding: '12px 14px', borderRadius: 8, background: 'var(--color-muted,rgba(0,0,0,.04))', color: 'var(--color-muted-foreground)', fontSize: 14 }}>{t('files_empty')}</div>
      ) : (
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
          {files.map((f) => (
            <span key={f.id} style={{ display: 'inline-flex', alignItems: 'center', gap: 4, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
              <span style={{ color: 'var(--color-foreground)' }}>{f.name || f.path}</span>
              <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-muted-foreground)' }} title={t('file_edit')} onClick={() => setModal({ doc: f })}><PencilIcon /></button>
              <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => markDeleteFile(f.id)}><TrashIcon /></button>
            </span>
          ))}
          {pendingFiles.map((m, i) => (
            <span key={`p-${i}`} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '5px 10px', borderRadius: 999, background: 'var(--color-muted,rgba(0,0,0,.05))', fontSize: 14 }}>
              <span style={{ color: 'var(--color-muted-foreground)' }}>{m.name}</span>
              <button style={{ ...iconBtn, width: 18, height: 18, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => onRemovePendingFile?.(i)}><TrashIcon /></button>
            </span>
          ))}
        </div>
      )}
      {modal !== null && (
        <MediaModal kind="file" doc={modal.doc} countries={countries} t={t}
          onClose={() => setModal(null)}
          onSaved={() => setTick(x => x + 1)}
          onUpdate={productId ? async (docId, m) => { await updateProductMedia(productId, docId, m) } : undefined}
          onAddPending={(m) => { onAddPendingFile?.(m) }} />
      )}
    </section>
  )
}

// ── Section Images (galerie avec survol 3 boutons) ──────────────────────────────
export const hoverCircle: import('react').CSSProperties = { width: 36, height: 36, borderRadius: 999, background: 'rgba(255,255,255,.9)', border: 0, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#333', flexShrink: 0 }

/** Un seul bouton de filtre : icône + libellé (statique, ou la valeur choisie une fois sélectionnée) +
 * chevron, ouvrant un menu « Tous » + options — calqué sur le dropdown Bootstrap legacy
 * (documents.tool.js : le clic met à jour le texte du bouton avec le libellé choisi). */
function FilterDropdownButton({ icon, label, allLabel, options, valueId, onChange }: {
  icon: import('react').ReactNode; label: string; allLabel: string
  options: { id: number; name: string }[]; valueId: number; onChange: (id: number) => void
}) {
  const [open, setOpen] = useState(false)
  const ref = useRef<HTMLDivElement>(null)
  useEffect(() => {
    if (!open) return
    const onDown = (e: MouseEvent) => { if (ref.current && !ref.current.contains(e.target as Node)) setOpen(false) }
    document.addEventListener('mousedown', onDown)
    return () => document.removeEventListener('mousedown', onDown)
  }, [open])
  const selected = options.find((o) => o.id === valueId)
  const buttonLabel = selected ? selected.name : label
  const itemStyle = (active: boolean): import('react').CSSProperties => ({
    display: 'block', width: '100%', textAlign: 'left', padding: '8px 12px', border: 0, borderRadius: 6,
    background: active ? 'var(--color-primary)' : 'transparent', color: active ? 'var(--color-primary-foreground,#fff)' : 'var(--color-foreground)',
    fontSize: 13, cursor: 'pointer', whiteSpace: 'nowrap',
  })
  function pick(id: number) { onChange(id); setOpen(false) }
  return (
    <div ref={ref} style={{ position: 'relative', display: 'inline-flex' }}>
      <button style={{ ...btnGhost, height: 32, gap: 6 }} onClick={() => setOpen((o) => !o)}>
        {icon}<span style={{ maxWidth: 160, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{buttonLabel}</span><ChevronDownIcon />
      </button>
      {open && (
        <div style={{ ...card, position: 'absolute', top: '100%', left: 0, marginTop: 6, zIndex: 60, padding: 6, minWidth: 200, maxHeight: 260, overflowY: 'auto' }}>
          <button style={itemStyle(valueId === 0)} onClick={() => pick(0)}>{allLabel}</button>
          {options.map((o) => <button key={o.id} style={itemStyle(valueId === o.id)} onClick={() => pick(o.id)}>{o.name}</button>)}
        </div>
      )}
    </div>
  )
}

/** Filtres Pays / Type d'image au-dessus de la galerie — même filtrage (et wording) que le
 * back-office legacy (render-document-image-lists.phtml : 2 dropdowns Pays/Type, « Tous » par défaut). */
export function ImageFilters({ countries, countryFilter, onCountryChange, typeFilter, onTypeChange, t }: {
  countries: CountryOption[]; countryFilter: number; onCountryChange: (v: number) => void
  typeFilter: number; onTypeChange: (v: number) => void; t: TFn
}) {
  const [types, setTypes] = useState<DocType[]>([])
  useEffect(() => { fetchDocumentTypes().then((d) => setTypes(d.imageTypes)).catch(() => null) }, [])
  return (
    <div style={{ display: 'flex', gap: 8, marginBottom: 12 }}>
      <FilterDropdownButton icon={<GlobeIcon />} label={t('img_filter_country')} allLabel={t('img_filter_all')}
        options={countries} valueId={countryFilter} onChange={onCountryChange} />
      <FilterDropdownButton icon={<ImageIcon />} label={t('img_filter_type')} allLabel={t('img_filter_all')}
        options={types} valueId={typeFilter} onChange={onTypeChange} />
    </div>
  )
}

/** Un item ne passe le filtre que si sa valeur correspond (ou si le filtre est « Tous » = 0). */
export function passesImageFilter(itemVal: number | null | undefined, filter: number): boolean {
  return filter === 0 || itemVal === filter
}

export function ImagesSection({ productId, t, countries = [], pendingImages = [], onAddPendingImage, onRemovePendingImage, onMarkDeleteImage, onUpdatePendingImage, refreshSignal }: {
  productId: number | null; t: TFn; countries?: CountryOption[]
  pendingImages?: PendingMedia[]
  onAddPendingImage?: (m: PendingMedia) => void
  onRemovePendingImage?: (index: number) => void
  onMarkDeleteImage?: (id: number) => void
  onUpdatePendingImage?: (index: number, m: PendingMedia) => void
  /** Bumped by the parent after a product save so newly-uploaded images (until then only in
   * `pendingImages`) get re-fetched as real, editable items — without this the Edit button stays
   * hidden on them until the tab is closed and reopened. */
  refreshSignal?: number
}) {
  const [images, setImages] = useState<MediaItem[]>([])
  const [tick, setTick] = useState(0)
  const [hovered, setHovered] = useState<number | null>(null)
  const [lightbox, setLightbox] = useState<string | null>(null)
  const [modal, setModal] = useState<{ doc?: MediaItem; pendingIdx?: number } | null>(null)
  const [countryFilter, setCountryFilter] = useState(0)
  const [typeFilter, setTypeFilter] = useState(0)
  const [toDelete, setToDelete] = useState<{ id: number; pendingIdx?: number } | null>(null)

  useEffect(() => { if (!productId) return; fetchProductMedia(productId).then((r) => setImages(r.images)).catch(() => null) }, [productId, tick, refreshSignal])
  function markDeleteImage(id: number) { setImages((p) => p.filter((im) => im.id !== id)); onMarkDeleteImage?.(id) }
  function confirmDelete() {
    if (!toDelete) return
    if (toDelete.pendingIdx !== undefined) onRemovePendingImage?.(toDelete.pendingIdx)
    else markDeleteImage(toDelete.id)
    setToDelete(null)
  }

  const allImages: { id: number; path: string; name: string; isPending: boolean; countryId: number | null; typeId: number | null }[] = [
    ...images.map(im => ({ id: im.id, path: im.path, name: im.name, isPending: false, countryId: im.countryId, typeId: im.typeId })),
    ...pendingImages.map((m, i) => ({ id: -(i + 1), path: m.data, name: m.name, isPending: true, countryId: m.countryId ?? null, typeId: m.typeId ?? null })),
  ].filter((im) => passesImageFilter(im.countryId, countryFilter) && passesImageFilter(im.typeId, typeFilter))

  return (
    <section>
      <h3 style={sectionTitle}>
        <span style={{ display: 'flex', alignItems: 'center', gap: 6 }}><ImageIcon />{t('sec_images')}</span>
        <button style={{ ...btnGhost, fontSize: 13, padding: '3px 10px' }} onClick={() => setModal({})}>
          {t('img_add')}
        </button>
      </h3>
      <ImageFilters countries={countries} countryFilter={countryFilter} onCountryChange={setCountryFilter}
        typeFilter={typeFilter} onTypeChange={setTypeFilter} t={t} />
      {allImages.length === 0 ? (
        <p style={{ ...hint, margin: 0 }}>{t('img_empty')}</p>
      ) : (
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 12 }}>
          {allImages.map((im) => (
            <div key={im.id} style={{ position: 'relative', width: 160, height: 140, borderRadius: 8, overflow: 'hidden', border: '1px solid var(--color-border)', flexShrink: 0 }}
              onMouseEnter={() => setHovered(im.id)} onMouseLeave={() => setHovered(null)}>
              <img src={im.path} alt={im.name} title={im.name}
                style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                onError={(e) => { (e.target as HTMLImageElement).style.opacity = '.25' }} />
              {hovered === im.id && (
                <div style={{ position: 'absolute', inset: 0, background: 'rgba(0,0,0,.48)', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 10 }}>
                  <button style={hoverCircle} title={t('img_view')} onClick={() => setLightbox(im.path)}><EyeIcon /></button>
                  <button style={hoverCircle} title={t('img_edit')}
                    onClick={() => setModal(im.isPending ? { pendingIdx: (-im.id) - 1 } : { doc: images.find(x => x.id === im.id) })}>
                    <PencilIcon />
                  </button>
                  <button style={{ ...hoverCircle, color: '#ef4444' }} title={t('img_del')}
                    onClick={() => setToDelete(im.isPending ? { id: im.id, pendingIdx: (-im.id) - 1 } : { id: im.id })}>
                    <TrashIcon />
                  </button>
                </div>
              )}
            </div>
          ))}
        </div>
      )}
      {lightbox && <Lightbox src={lightbox} onClose={() => setLightbox(null)} />}
      {modal !== null && (
        <MediaModal kind="image" doc={modal.doc} pendingDoc={modal.pendingIdx !== undefined ? pendingImages[modal.pendingIdx] : undefined} countries={countries} t={t}
          onClose={() => setModal(null)}
          onSaved={() => setTick(x => x + 1)}
          onUpdate={productId ? async (docId, m) => { await updateProductMedia(productId, docId, m) } : undefined}
          onAddPending={(m) => { onAddPendingImage?.(m) }}
          onUpdatePending={(m) => { if (modal.pendingIdx !== undefined) onUpdatePendingImage?.(modal.pendingIdx, m) }} />
      )}
      {toDelete && <ConfirmModal t={t} title={t('img_del_title')} message={t('img_del_confirm')} onCancel={() => setToDelete(null)} onConfirm={confirmDelete} />}
    </section>
  )
}

/** Table de tooltip variantes (ID/Image/SKU/Attributs/Pays/Prix/Stock), flottante via portal — calquée
 * sur le qTip legacy (MelisComProductListController::getToolTipAction()). Partagée entre le survol du
 * NOM produit (liste produits, toutes ses variantes) et le survol du SKU (onglet Variantes, 1 seule ligne
 * puisque legacy filtre alors `$vcontent` sur ce variantId — cf. la même action avec `variantId` posté). */
export function VariantTooltipTable({ items, pos, t, onMouseEnter, onMouseLeave, onRowClick }: {
  items: TooltipVariant[]; pos: { x: number; y: number }; t: TFn; onMouseEnter?: () => void; onMouseLeave?: () => void
  /** Rend chaque ligne cliquable → ouvre cette variante dans l'onglet Variantes. */
  onRowClick?: (variantId: number) => void
}) {
  const cellTh = { padding: '6px 12px', textAlign: 'left' as const, fontSize: 11, textTransform: 'uppercase' as const, letterSpacing: '.04em', color: 'rgba(255,255,255,.7)', whiteSpace: 'nowrap' as const }
  const cellTd = { padding: '6px 12px', fontSize: 13, color: '#fff', whiteSpace: 'nowrap' as const }
  return createPortal(
    <div onMouseEnter={onMouseEnter} onMouseLeave={onMouseLeave}
      style={{ position: 'fixed', top: pos.y, left: pos.x, zIndex: 9999, background: '#4a4a4a', borderRadius: 8, boxShadow: '0 8px 24px rgba(0,0,0,.3)', padding: 4, maxWidth: 'min(90vw, 900px)', maxHeight: '80vh', overflow: 'auto' }}>
      <table style={{ borderCollapse: 'collapse' }}>
        <thead><tr>
          <th style={cellTh}>{t('var_col_id')}</th><th style={cellTh}>{t('col_image')}</th><th style={cellTh}>{t('var_col_sku')}</th>
          <th style={cellTh}>{t('var_col_attrs')}</th><th style={cellTh}>{t('tt_country')}</th><th style={cellTh}>{t('tt_price')}</th><th style={cellTh}>{t('tt_stocks')}</th>
        </tr></thead>
        <tbody>
          {items.map((v) => (
            <tr key={v.id} onClick={onRowClick ? () => onRowClick(v.id) : undefined}
              style={onRowClick ? { cursor: 'pointer' } : undefined}
              onMouseOver={onRowClick ? (e) => { (e.currentTarget as HTMLElement).style.background = 'rgba(255,255,255,.1)' } : undefined}
              onMouseOut={onRowClick ? (e) => { (e.currentTarget as HTMLElement).style.background = 'transparent' } : undefined}>
              <td style={cellTd}>{v.id}</td>
              <td style={cellTd}>{v.image ? <img src={v.image} alt="" style={{ width: 28, height: 28, objectFit: 'cover', borderRadius: 4 }} /> : '🖼'}</td>
              <td style={cellTd}>{v.sku || '—'}</td>
              <td style={cellTd}>{v.attributes || '—'}</td>
              <td style={cellTd}>🌐 {t('price_general')}</td>
              <td style={cellTd}>{v.price == null ? '—' : v.price.toFixed(2)}</td>
              <td style={cellTd}>{v.stock == null ? '—' : v.stock}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>,
    document.body
  )
}

// ── Onglet Variantes (table type legacy : ID / Principale / Image / Statut / SKU / Attributs / Action) ──
export function VariantsTab({ productId, t, onAdd, onEdit, can }: { productId: number | null; t: TFn; onAdd: () => void; onEdit: (id: number) => void; can?: (cap: string) => boolean }) {
  const allow = (cap: string) => (can ? can(cap) : true)
  const [items, setItems] = useState<ProductVariant[]>([])
  const [tick, setTick] = useState(0)
  const [search, setSearch] = useState('')
  const [toDelete, setToDelete] = useState<ProductVariant | null>(null)
  // Tooltip au survol du SKU (calqué sur ProductNameCell / legacy toolTipVarHoverEvent, cf. VariantTooltipTable) —
  // chargée une fois pour toutes les lignes (déjà toutes les variantes du produit), pas par ligne survolée.
  const [tooltipItems, setTooltipItems] = useState<TooltipVariant[] | null>(null)
  const [hoveredSkuId, setHoveredSkuId] = useState<number | null>(null)
  const [tooltipPos, setTooltipPos] = useState({ x: 0, y: 0 })
  // Le tooltip flotte SOUS le SKU (pas un enfant DOM) : quitter le bouton pour l'atteindre
  // traverse un instant "hors survol" — un délai court (au lieu d'un clearHover immédiat)
  // laisse la souris arriver dessus avant fermeture, pour pouvoir cliquer une variante dedans.
  const tooltipCloseTimer = useRef<number | null>(null)
  function clearTooltipCloseTimer() { if (tooltipCloseTimer.current) { window.clearTimeout(tooltipCloseTimer.current); tooltipCloseTimer.current = null } }
  function scheduleTooltipClose() { clearTooltipCloseTimer(); tooltipCloseTimer.current = window.setTimeout(() => setHoveredSkuId(null), 150) }
  function hoverSku(e: import('react').MouseEvent<HTMLElement>, id: number) {
    clearTooltipCloseTimer()
    const r = e.currentTarget.getBoundingClientRect()
    setTooltipPos({ x: r.left, y: r.bottom + 6 })
    setHoveredSkuId(id)
    if (tooltipItems === null && productId) fetchProductTooltip(productId).then((res) => setTooltipItems(res.items)).catch(() => setTooltipItems([]))
  }
  const hoveredTooltipRow = tooltipItems?.filter((v) => v.id === hoveredSkuId) ?? []
  const [toDup, setToDup] = useState<ProductVariant | null>(null)

  useEffect(() => { if (!productId) return; fetchProductVariants(productId).then((r) => setItems(r.items)).catch(() => null) }, [productId, tick])
  async function confirmDelete() { if (!toDelete || !productId) return; try { await deleteProductVariant(productId, toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); setTick((x) => x + 1) } catch { setToDelete(null) } }
  async function toggleStatus(v: ProductVariant) { if (!productId) return; try { await saveProductVariant(productId, { variantId: v.id, sku: v.sku, status: v.status !== 1, isMain: v.isMain === 1 }); setTick((x) => x + 1) } catch { /* */ } }

  const view = items.filter((v) => !search || v.sku.toLowerCase().includes(search.toLowerCase()) || v.attributes.toLowerCase().includes(search.toLowerCase()) || String(v.id).includes(search))

  return (
    <div>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 12, gap: 12 }}>
        <h3 style={{ fontSize: 15, fontWeight: 600, margin: 0 }}>{t('var_title')}</h3>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <input style={{ ...inputCss, height: 34, width: 220 }} value={search} onChange={(e) => setSearch(e.target.value)} placeholder={t('search')} />
          <button style={{ ...btnGhost, height: 34 }} onClick={() => setTick((x) => x + 1)} title={t('refresh')}>↻</button>
          {productId && allow('variants.create') && <button style={btnPrimary} onClick={onAdd}><PlusIcon />{t('var_add')}</button>}
        </div>
      </div>

      <div style={{ border: '1px solid var(--color-border)', borderRadius: 8, overflow: 'hidden' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
            <tr>
              <th style={{ ...th, width: 50 }}>{t('var_col_id')}</th><th style={{ ...th, width: 60 }}>{t('var_col_main')}</th>
              <th style={{ ...th, width: 70 }}>{t('col_image')}</th><th style={{ ...th, width: 70 }}>{t('col_status')}</th>
              <th style={th}>{t('var_col_sku')}</th><th style={th}>{t('var_col_attrs')}</th><th style={{ ...th, width: 150, textAlign: 'right' }}>{t('action')}</th>
            </tr>
          </thead>
          <tbody>
            {view.length === 0 ? (
              <tr><td colSpan={7} style={{ ...td, textAlign: 'center', color: 'var(--color-muted-foreground)', padding: '30px 16px' }}>{t('var_empty')}</td></tr>
            ) : view.map((v) => (
              <tr key={v.id}>
                <td style={{ ...td, color: 'var(--color-muted-foreground)' }}>{v.id}</td>
                <td style={td}><span style={{ color: v.isMain ? '#f5a623' : 'var(--color-border)', fontSize: 16 }}>★</span></td>
                <td style={td}>
                  {v.image ? <img src={v.image} alt="" style={{ width: 38, height: 38, objectFit: 'cover', borderRadius: 6, border: '1px solid var(--color-border)' }} onError={(e) => { (e.target as HTMLImageElement).style.opacity = '.3' }} />
                    : <span style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 38, height: 38, borderRadius: 6, background: 'var(--color-muted,rgba(0,0,0,.05))', color: 'var(--color-muted-foreground)' }}>🖼</span>}
                </td>
                <td style={td}><span style={{ color: v.status === 1 ? '#16a34a' : '#dc2626', fontSize: 12 }}>●</span></td>
                <td style={td}>
                  <button onClick={() => onEdit(v.id)} onMouseEnter={(e) => hoverSku(e, v.id)} onMouseLeave={scheduleTooltipClose}
                    style={{ border: 0, background: 'transparent', color: 'var(--color-primary)', cursor: 'pointer', fontWeight: 500, fontSize: 14, padding: 0, textDecoration: 'underline' }}>
                    {v.sku || `#${v.id}`}
                  </button>
                  {hoveredSkuId === v.id && hoveredTooltipRow.length > 0 && (
                    <VariantTooltipTable items={hoveredTooltipRow} pos={tooltipPos} t={t} onMouseEnter={clearTooltipCloseTimer} onMouseLeave={scheduleTooltipClose}
                      onRowClick={(variantId) => { clearTooltipCloseTimer(); setHoveredSkuId(null); onEdit(variantId) }} />
                  )}
                </td>
                <td style={{ ...td, color: 'var(--color-muted-foreground)' }}>{v.attributes || '—'}</td>
                <td style={td}>
                  <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 5 }}>
                    {allow('variants.edit') && <button style={iconBtn} title={t('var_toggle_status')} onClick={() => toggleStatus(v)}>{v.status === 1 ? '↓' : '↑'}</button>}
                    {allow('variants.duplicate') && <button style={iconBtn} title={t('var_duplicate')} onClick={() => setToDup(v)}>⧉</button>}
                    {allow('variants.edit') && <button style={iconBtn} title={t('edit')} onClick={() => onEdit(v.id)}><PencilIcon /></button>}
                    {allow('variants.delete') && <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={() => setToDelete(v)}><TrashIcon /></button>}
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      <p style={{ ...hint, margin: '8px 2px 0' }}>{t('showing', { a: view.length ? 1 : 0, b: view.length, n: items.length })}</p>

      {toDelete && (
        <ConfirmModal t={t} title={t('var_del_title')} message={t('var_del_confirm', { u: toDelete.sku || `#${toDelete.id}` })} onCancel={() => setToDelete(null)} onConfirm={confirmDelete} />
      )}
      {toDup && (
        <DuplicateModal t={t} title={t('dup_var_title')} intro={t('dup_var_intro')} fieldLabel={`${t('dup_sku')} (${toDup.sku})`} fieldPlaceholder={t('dup_sku_ph')}
          onClose={() => setToDup(null)}
          onConfirm={async (o) => { try { await duplicateProductVariant(productId, toDup.id, { duplicateImages: o.duplicateImages, duplicateDocuments: o.duplicateDocuments, putOnline: o.putOnline, sku: o.field }); } catch { /* */ } setToDup(null); setTick((x) => x + 1) }} />
      )}
    </div>
  )
}

// ── Onglet Prix (ajout / modif / suppression) ───────────────────────────────────
export interface PendingPrice { id?: number | null; countryId: number; currency: number; net: number | null; gross: number | null; vatPercent: number | null; vatPrice: number | null; otherTax: number | null }

export function PricesTab({ productId, countries, currencies, t, pendingPrices = [], onBufferPrice }: { productId: number | null; countries: Option[]; currencies: Option[]; t: TFn; pendingPrices?: PendingPrice[]; onBufferPrice?: (p: PendingPrice) => void }) {
  const [items, setItems] = useState<ProductPrice[]>([])
  const [country, setCountry] = useState<number>(-1)
  const [f, setF] = useState({ net: '', gross: '', vatPercent: '', vatPrice: '', otherTax: '' })
  const currency = currencies[0]?.id ?? 0
  const countryItems = [{ id: -1, name: t('price_general'), flag: '' }, ...countries]

  useEffect(() => { if (!productId) return; fetchProductPrices(productId).then((r) => setItems(r.items)).catch(() => null) }, [productId])
  const cur = items.find((p) => p.countryId === country) ?? null
  const s = (v: number | null) => v == null ? '' : String(v)
  const n = (v: string) => v === '' ? null : (parseFloat(v) || 0)

  // A country switch must never discard an unsaved edit: prefer the buffered `pendingPrices` entry
  // (kept by the parent across tab switches) over the last-fetched server value for that country.
  useEffect(() => {
    const pf = pendingPrices.find((p) => p.countryId === country) ?? null
    if (pf) {
      setF({ net: s(pf.net), gross: s(pf.gross), vatPercent: s(pf.vatPercent), vatPrice: s(pf.vatPrice), otherTax: s(pf.otherTax) })
    } else {
      setF({ net: s(cur?.net ?? null), gross: s(cur?.gross ?? null), vatPercent: s(cur?.vatPercent ?? null), vatPrice: s(cur?.vatPrice ?? null), otherTax: s(cur?.otherTax ?? null) })
    }
  }, [country, items]) // eslint-disable-line react-hooks/exhaustive-deps

  // Called from onChange — buffers the current form state for save on main button.
  function bufferCurrent(nf: typeof f) {
    if (!onBufferPrice) return
    onBufferPrice({ id: productId ? (cur?.id ?? null) : null, countryId: country, currency, net: n(nf.net), gross: n(nf.gross), vatPercent: n(nf.vatPercent), vatPrice: n(nf.vatPrice), otherTax: n(nf.otherTax) })
  }

  const hasMeaningfulPending = pendingPrices.filter((p) => p.net !== null || p.gross !== null || p.vatPercent !== null || p.vatPrice !== null || p.otherTax !== null)
  const PRICE_TIPS: Record<string, string> = { net: 'tip_price_net', gross: 'tip_price_gross', vatPercent: 'tip_price_vat', vatPrice: 'tip_price_vat_amount', otherTax: 'tip_price_other_tax' }
  const fields: [keyof typeof f, string][] = [['net', 'price_net'], ['gross', 'price_gross'], ['vatPercent', 'price_vat'], ['vatPrice', 'price_vat_amount'], ['otherTax', 'price_other_tax']]

  return (
    <div>
      <h3 style={{ fontSize: 16, fontWeight: 600, margin: '0 0 16px' }}>{t('tab_prices')}</h3>
      {!productId && hasMeaningfulPending.length > 0 && (
        <p style={{ ...hint, marginBottom: 12 }}>{t('price_pending_hint', { n: hasMeaningfulPending.length })}</p>
      )}
      <div style={{ display: 'grid', gridTemplateColumns: '170px 1fr', gap: 18, maxWidth: 940 }}>
        <div>
          <div style={{ ...sectionTitle, marginBottom: 8 }}>🌐 {t('price_country')}</div>
          <FlagSwitcher
            items={countryItems.map((c) => ({ ...c, hasPending: !productId && pendingPrices.some((p) => p.countryId === c.id && (p.net !== null || p.gross !== null || p.vatPercent !== null || p.vatPrice !== null || p.otherTax !== null)) }))}
            value={country} onChange={setCountry}
          />
        </div>
        <div style={{ ...card, padding: 18 }}>
          <h4 style={{ fontSize: 14, fontWeight: 600, margin: '0 0 14px', color: 'var(--color-primary)' }}>$ {t('price_general_pricing')}</h4>
          {fields.map(([k, lbl], i) => (
            <div key={k} style={{ marginTop: i === 0 ? 0 : 14 }}>
              <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}><label style={label}>{t(lbl)}</label><InfoDot text={t(PRICE_TIPS[k])} /></div>
              <input style={inputCss} type="number" step="0.01" value={f[k]} onChange={(e) => { const nf = { ...f, [k]: e.target.value }; setF(nf); bufferCurrent(nf) }} />
            </div>
          ))}
        </div>
      </div>
    </div>
  )
}

function ConfirmModal({ t, title, message, onCancel, onConfirm }: { t: TFn; title: string; message: string; onCancel: () => void; onConfirm: () => void }) {
  return (
    <div style={{ position: 'fixed', inset: 0, zIndex: 50, display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.5)' }}>
      <div style={{ ...card, padding: 24, width: '100%', maxWidth: 360 }}>
        <h3 style={{ fontSize: 16, fontWeight: 600, margin: 0 }}>{title}</h3>
        <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', marginTop: 8 }}>{message}</p>
        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 20 }}>
          <button style={btnGhost} onClick={onCancel}>{t('cancel')}</button>
          <button style={{ ...btnGhost, borderColor: '#fca5a5', color: '#dc2626' }} onClick={onConfirm}>{t('del')}</button>
        </div>
      </div>
    </div>
  )
}
