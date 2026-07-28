import { useMemo, useState, type CSSProperties, type ReactNode } from 'react'
import type { CatNode, LangOption } from './api'
import { card, inputCss } from '../../shared/styles'
import { PlusIcon, TrashIcon, ChevronDownIcon, GripIcon, RefreshIcon } from '../../shared/icons'

/* Arbre catalogues / catégories — calqué visuellement sur le treeview de melis-cms-category2
 * (carte pleine hauteur, toolbar interne, poignée de glissement, pastille de statut, drop 3-zones).
 * Adapté au modèle MelisCommerce : distinction catalogue (racine) / catégorie + nombre de produits.
 * La logique métier (écritures) reste côté services Laminas via l'API — ce composant est présentation. */

type TFunc = (k: string, v?: Record<string, string | number>) => string

interface Props {
  nodes: CatNode[]
  languages: LangOption[]
  currentLangId: number
  onLangChange: (id: number) => void
  selectedId: number | null
  loading: boolean
  can: (cap: string) => boolean
  t: TFunc
  onSelect: (n: CatNode) => void
  onAddChild: (n: CatNode) => void
  onAddCatalog: () => void
  onDelete: (n: CatNode) => void
  onContext: (n: CatNode, x: number, y: number) => void
  onReorder: (move: { catId: number; fatherId: number; order: number; oldParent: number }) => void
  onRefresh: () => void
}

// ── Helpers arbre ────────────────────────────────────────────────────────────────
function matchesSearch(node: CatNode, q: string): boolean {
  if (!q) return true
  if (node.name.toLowerCase().includes(q) || String(node.id).includes(q)) return true
  return node.children.some((c) => matchesSearch(c, q))
}
function findNode(nodes: CatNode[], id: number): CatNode | null {
  for (const n of nodes) { if (n.id === id) return n; const r = findNode(n.children, id); if (r) return r }
  return null
}
function collectIds(node: CatNode, acc: Set<number>) { acc.add(node.id); node.children.forEach((c) => collectIds(c, acc)) }
function allIds(nodes: CatNode[]): number[] { const a: number[] = []; const w = (l: CatNode[]) => l.forEach((n) => { a.push(n.id); w(n.children) }); w(nodes); return a }

/** Surligne (rouge) la 1re occurrence de `query` dans `text` — insensible à la casse. */
function highlightMatch(text: string, query: string): ReactNode {
  if (!query) return text
  const i = text.toLowerCase().indexOf(query.toLowerCase())
  if (i === -1) return text
  return <>{text.slice(0, i)}<span style={{ color: '#dc2626' }}>{text.slice(i, i + query.length)}</span>{text.slice(i + query.length)}</>
}

// ── Petites primitives (même rendu que le treeview category2) ──────────────────────
function StatusDot({ active }: { active: boolean }) {
  return <span style={{ width: 8, height: 8, borderRadius: 999, flexShrink: 0, background: active ? '#22c55e' : '#ef4444' }} />
}
function SearchIcon() {
  return <svg style={{ width: 16, height: 16, flexShrink: 0 }} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" /></svg>
}

/** Sélecteur de langue avec drapeaux (un <select> natif ne peut pas afficher d'images). */
function LangDropdown({ langs, currentLangId, onChange }: { langs: LangOption[]; currentLangId: number; onChange: (id: number) => void }) {
  const [open, setOpen] = useState(false)
  const current = langs.find((l) => l.id === currentLangId) ?? langs[0]
  const flag = (l: LangOption) => (l.flag ? <img src={`data:image/png;base64,${l.flag}`} alt="" style={{ width: 18, height: 12, borderRadius: 2, objectFit: 'cover', boxShadow: '0 0 0 1px rgba(0,0,0,.1)', flexShrink: 0 }} /> : null)
  return (
    <div style={{ position: 'relative' }} onBlur={() => setTimeout(() => setOpen(false), 120)}>
      <button type="button" onClick={() => setOpen((o) => !o)}
        style={{ ...inputCss, height: 36, width: 'auto', display: 'inline-flex', alignItems: 'center', gap: 6, cursor: 'pointer' }}>
        {current && flag(current)}
        <span style={{ fontSize: 14 }}>{current?.name ?? ''}</span>
        <span style={{ color: 'var(--color-muted-foreground)', fontSize: 10, marginLeft: 2 }}>▾</span>
      </button>
      {open && (
        <div style={{ ...card, position: 'absolute', top: 40, left: 0, zIndex: 30, minWidth: 170, padding: 4, display: 'flex', flexDirection: 'column', gap: 2 }}>
          {langs.map((l) => (
            <button key={l.id} type="button" onMouseDown={() => { onChange(l.id); setOpen(false) }}
              style={{ display: 'flex', alignItems: 'center', gap: 8, padding: '7px 8px', borderRadius: 6, border: 0, cursor: 'pointer', fontSize: 14, textAlign: 'left', color: 'var(--color-foreground)',
                background: l.id === currentLangId ? 'color-mix(in srgb, var(--color-primary) 12%, transparent)' : 'transparent' }}>
              {flag(l)}{l.name}
            </button>
          ))}
        </div>
      )}
    </div>
  )
}

export default function CatalogTree(p: Props) {
  const { t, can } = p
  const [search, setSearch] = useState('')
  const [collapsed, setCollapsed] = useState<Set<number>>(new Set())
  const [drag, setDrag] = useState<{ id: number; fatherId: number; type: string; descendants: Set<number> } | null>(null)
  const [dropInfo, setDropInfo] = useState<{ id: number; pos: 'before' | 'inside' | 'after' } | null>(null)

  const q = search.trim().toLowerCase()
  // Le glisser-déposer n'a de sens que dans la vue COMPLÈTE non filtrée (ordre des frères réel).
  const dndEnabled = !q

  // On ne garde que les branches qui matchent (ancêtres préservés).
  const visible = useMemo(() => {
    const prune = (list: CatNode[]): CatNode[] =>
      list.filter((n) => matchesSearch(n, q)).map((n) => ({ ...n, children: prune(n.children) }))
    return prune(p.nodes)
  }, [p.nodes, q])

  const toggle = (id: number) =>
    setCollapsed((prev) => { const s = new Set(prev); s.has(id) ? s.delete(id) : s.add(id); return s })

  // Un nœud ne peut pas être déposé sur lui-même / dans son propre sous-arbre, ni violer la règle
  // catalogue=racine / catégorie=non-racine.
  const canDropAt = (newFather: number): boolean => {
    if (!drag) return false
    if (drag.descendants.has(newFather)) return false
    if (drag.type === 'catalog' && newFather !== -1) return false
    if (drag.type === 'category' && newFather === -1) return false
    return true
  }

  const onDrop = (target: CatNode) => {
    if (drag && dropInfo && dropInfo.id === target.id && target.id !== drag.id) {
      if (dropInfo.pos === 'inside') {
        if (canDropAt(target.id)) {
          setCollapsed((prev) => { const s = new Set(prev); s.delete(target.id); return s }) // révèle l'enfant déplacé
          p.onReorder({ catId: drag.id, fatherId: target.id, order: target.children.length + 1, oldParent: drag.fatherId })
        }
      } else if (canDropAt(target.fatherId)) {
        const order = dropInfo.pos === 'after' ? target.order + 1 : target.order
        p.onReorder({ catId: drag.id, fatherId: target.fatherId, order, oldParent: drag.fatherId })
      }
    }
    setDrag(null); setDropInfo(null)
  }

  const renderNode = (node: CatNode, depth: number) => {
    const hasChildren = node.children.length > 0
    const open = q ? true : !collapsed.has(node.id) // en recherche : tout ouvert pour voir les matches
    const selected = p.selectedId === node.id
    const isDropTarget = dropInfo?.id === node.id && node.id !== drag?.id
    const rowStyle: CSSProperties = {
      display: 'flex', alignItems: 'center', gap: 6, height: 34, paddingRight: 8,
      paddingLeft: 8 + depth * 18, borderRadius: 8, cursor: 'pointer', userSelect: 'none',
      background: selected ? 'color-mix(in srgb, var(--color-primary) 12%, transparent)' : 'transparent',
      color: 'var(--color-foreground)', opacity: drag?.id === node.id ? 0.4 : 1,
      // before/after → ligne d'insertion en haut/bas ; inside → contour complet (devient enfant).
      boxShadow: isDropTarget
        ? (dropInfo!.pos === 'before' ? 'inset 0 2px 0 0 var(--color-primary)'
          : dropInfo!.pos === 'after' ? 'inset 0 -2px 0 0 var(--color-primary)'
          : 'inset 0 0 0 2px var(--color-primary)')
        : 'none',
      ...(isDropTarget && dropInfo!.pos === 'inside' ? { background: 'color-mix(in srgb, var(--color-primary) 8%, transparent)' } : null),
    }
    return (
      <div key={node.id}>
        <div style={rowStyle}
          onDragOver={(e) => {
            if (!drag) return
            e.preventDefault()
            const r = e.currentTarget.getBoundingClientRect()
            const y = e.clientY - r.top
            // 3 zones : ¼ haut = avant, ¼ bas = après, ½ milieu = dedans (reparentage).
            const pos = y < r.height * 0.25 ? 'before' : y > r.height * 0.75 ? 'after' : 'inside'
            setDropInfo({ id: node.id, pos })
          }}
          onDragLeave={() => setDropInfo((d) => (d?.id === node.id ? null : d))}
          onDrop={(e) => { e.preventDefault(); e.stopPropagation(); onDrop(node) }}
          onMouseEnter={(e) => { if (!selected) e.currentTarget.style.background = 'color-mix(in srgb, var(--color-muted,#888) 10%, transparent)' }}
          onMouseLeave={(e) => { if (!selected) e.currentTarget.style.background = 'transparent' }}
          onClick={() => p.onSelect(node)}
          onContextMenu={(e) => { e.preventDefault(); p.onContext(node, e.clientX, e.clientY) }}>
          {/* Seule la poignée est draggable — un clic sur les boutons +/✎/🗑 reste un clic. */}
          {dndEnabled ? (
            <span style={{ opacity: 0.5, cursor: 'grab', display: 'inline-flex' }} title={t('drag_hint')} draggable
              onDragStart={(e) => { e.stopPropagation(); const d = new Set<number>(); collectIds(node, d); setDrag({ id: node.id, fatherId: node.fatherId, type: node.type, descendants: d }); e.dataTransfer.effectAllowed = 'move' }}
              onDragEnd={() => { setDrag(null); setDropInfo(null) }}
              onClick={(e) => e.stopPropagation()}>
              <GripIcon />
            </span>
          ) : <span style={{ width: 13 }} />}
          <span style={{ width: 18, display: 'inline-flex', justifyContent: 'center', color: 'var(--color-muted-foreground)' }}
            onClick={(e) => { e.stopPropagation(); if (hasChildren) toggle(node.id) }}>
            {hasChildren ? <span style={{ display: 'inline-flex', transform: open ? 'none' : 'rotate(-90deg)', transition: 'transform .12s' }}><ChevronDownIcon /></span> : null}
          </span>
          <StatusDot active={node.status === 1} />
          <span style={{ flex: 1, minWidth: 0, fontSize: 14, fontWeight: node.type === 'catalog' || selected ? 600 : 400,
            color: selected ? 'var(--color-primary)' : 'inherit', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
            {node.name ? highlightMatch(node.name, search.trim()) : <span style={{ fontStyle: 'italic', color: 'var(--color-muted-foreground)' }}>{t('no_name')}</span>}
          </span>
          <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)', flexShrink: 0 }}>{t('products_n', { n: node.productCount })}</span>
          {can('create') && (
            <button title={t('add_child')} onClick={(e) => { e.stopPropagation(); p.onAddChild(node) }} style={iconBtn}><PlusIcon /></button>
          )}
          {can('delete') && (
            <button title={t('del')} onClick={(e) => { e.stopPropagation(); p.onDelete(node) }} style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }}><TrashIcon /></button>
          )}
        </div>
        {hasChildren && open ? node.children.map((c) => renderNode(c, depth + 1)) : null}
      </div>
    )
  }

  return (
    <div style={{ ...card, display: 'flex', flexDirection: 'column', minHeight: 0, height: '100%', overflow: 'hidden' }}
      onDragEnd={() => { setDrag(null); setDropInfo(null) }}>
      {/* toolbar */}
      <div style={{ display: 'flex', flexDirection: 'column', gap: 8, padding: 12, borderBottom: '1px solid var(--color-border)' }}>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <LangDropdown langs={p.languages} currentLangId={p.currentLangId} onChange={p.onLangChange} />
          <div style={{ flex: 1 }} />
          <button title={t('collapse_all')} onClick={() => setCollapsed(new Set(allIds(p.nodes)))} style={iconBtnBox}>
            <span style={{ display: 'inline-flex', transform: 'rotate(-90deg)' }}><ChevronDownIcon /></span>
          </button>
          <button title={t('expand_all')} onClick={() => setCollapsed(new Set())} style={iconBtnBox}>
            <ChevronDownIcon />
          </button>
          <button title={t('refresh')} onClick={p.onRefresh} style={{ ...iconBtnBox, opacity: p.loading ? 0.5 : 1 }}><RefreshIcon /></button>
        </div>
        <div style={{ position: 'relative' }}>
          <span style={{ position: 'absolute', left: 10, top: 10, color: 'var(--color-muted-foreground)' }}><SearchIcon /></span>
          <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder={t('search')} style={{ ...inputCss, height: 36, paddingLeft: 34 }} />
        </div>
        {can('create') && (
          <button onClick={p.onAddCatalog} style={{ ...primaryBtn, height: 38 }}><PlusIcon />{t('add_catalog_root')}</button>
        )}
      </div>

      {/* arbre */}
      <div style={{ flex: 1, overflow: 'auto', padding: 8 }}>
        {p.loading && p.nodes.length === 0 ? (
          <div style={{ padding: 40, textAlign: 'center', fontSize: 13, color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
        ) : p.nodes.length === 0 ? (
          <div style={{ padding: 40, textAlign: 'center', fontSize: 13, color: 'var(--color-muted-foreground)' }}>{t('empty')}</div>
        ) : visible.length === 0 ? (
          <div style={{ padding: 40, textAlign: 'center', fontSize: 13, color: 'var(--color-muted-foreground)' }}>{t('search_not_found', { q: search.trim() })}</div>
        ) : (
          visible.map((n) => renderNode(n, 0))
        )}
      </div>
    </div>
  )
}

const iconBtn: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 26, height: 26,
  borderRadius: 6, border: 0, background: 'transparent', color: 'var(--color-muted-foreground)', cursor: 'pointer', flexShrink: 0,
}
const iconBtnBox: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 36, height: 36,
  borderRadius: 8, border: '1px solid var(--color-border)', background: 'var(--color-card)',
  color: 'var(--color-foreground)', cursor: 'pointer', flexShrink: 0,
}
const primaryBtn: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 6, height: 38,
  padding: '0 16px', borderRadius: 8, border: 0, background: 'var(--color-primary)',
  color: 'var(--color-primary-foreground,#fff)', fontSize: 14, fontWeight: 600, cursor: 'pointer',
}
