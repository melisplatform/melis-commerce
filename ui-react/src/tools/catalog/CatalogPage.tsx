import { useEffect, useMemo, useState } from 'react'
import {
  deleteCategory, fetchCatalogOptions, fetchCatalogTree, fetchCategoryById, fetchCategoryProducts,
  fetchCategorySeo, reorderCategory, saveCategory, saveCategorySeo,
  type CatNode, type CatText, type CatSeo, type CatProduct, type LangOption,
} from './api'
import { DICT } from './dict'
import { makeT } from '../../shared/i18n'
import { card, inputCss, btnPrimary, btnGhost, iconBtn, label, hint, th, td } from '../../shared/styles'
import { PlusIcon, PencilIcon, TrashIcon, ChevronDownIcon, LayoutIcon, TagIcon, FileTextIcon, MapPinIcon, CartIcon, RefreshIcon } from '../../shared/icons'
import { ViewModeToggle, LegacyFrame, StatusBadge, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { Tabs, type TabDef } from '../../shared/Tabs'
import type { Option } from '../../shared/api'

/* Brique « Catalogues / Catégories » (MelisCommerce) — arbre full React (drag-and-drop) +
 * formulaire d'édition INLINE sous l'arbre (même page), comme l'outil legacy.
 * Logique métier inchangée : les écritures réutilisent les services Laminas (cf. contrôleur API). */
const TOOL_MELIS_KEY = 'meliscommerce_categories_page'

type Selection =
  | { mode: 'new-catalog' }
  | { mode: 'new-category'; fatherId: number }
  | { mode: 'edit'; catId: number; type: 'catalog' | 'category' }

// ── Helpers arbre ──────────────────────────────────────────────────────────────
function collectIds(node: CatNode, acc: Set<number>) { acc.add(node.id); node.children.forEach((c) => collectIds(c, acc)) }
function allIds(nodes: CatNode[]): number[] { const a: number[] = []; const w = (l: CatNode[]) => l.forEach((n) => { a.push(n.id); w(n.children) }); w(nodes); return a }
function findNode(nodes: CatNode[], id: number): CatNode | null {
  for (const n of nodes) { if (n.id === id) return n; const f = findNode(n.children, id); if (f) return f }
  return null
}
function filterTree(nodes: CatNode[], q: string): CatNode[] {
  if (!q) return nodes
  const lq = q.toLowerCase()
  const walk = (list: CatNode[]): CatNode[] => list.flatMap((n) => {
    const kids = walk(n.children)
    if (n.name.toLowerCase().includes(lq) || String(n.id).includes(lq) || kids.length) return [{ ...n, children: kids }]
    return []
  })
  return walk(nodes)
}

export default function CatalogPage() {
  const t = makeT(DICT)
  const [mode, setMode] = useState<'react' | 'old'>('react')
  const [oldLoaded, setOldLoaded] = useState(false)
  const [tree, setTree] = useState<CatNode[]>([])
  const [loading, setLoading] = useState(false)
  const [search, setSearch] = useState('')
  const [languages, setLanguages] = useState<LangOption[]>([])
  const [countries, setCountries] = useState<Option[]>([])
  const [langId, setLangId] = useState<number>(0)
  const [expanded, setExpanded] = useState<Set<number>>(new Set())
  const [sel, setSel] = useState<Selection | null>(null)
  const [highlightId, setHighlightId] = useState<number | null>(null)
  const [ctxMenu, setCtxMenu] = useState<{ node: CatNode; x: number; y: number } | null>(null)
  const [toDelete, setToDelete] = useState<CatNode | null>(null)
  const [drag, setDrag] = useState<{ id: number; fatherId: number; type: string; descendants: Set<number> } | null>(null)
  const [dropHint, setDropHint] = useState<string | null>(null)
  const [err, setErr] = useState<string | null>(null)
  const [tick, setTick] = useState(0)

  useEffect(() => { fetchCatalogOptions().then((o) => { setLanguages(o.languages); setCountries(o.countries); setLangId((v) => v || o.languages[0]?.id || 0) }).catch(() => null) }, [])
  useEffect(() => {
    setLoading(true)
    fetchCatalogTree(langId || undefined).then((r) => {
      setTree(r.items)
      setExpanded((prev) => prev.size ? prev : new Set(r.items.map((n) => n.id)))
    }).catch(() => null).finally(() => setLoading(false))
  }, [langId, tick])

  const view = useMemo(() => filterTree(tree, search.trim()), [tree, search])
  const refresh = () => setTick((x) => x + 1)

  // Nœud surligné (simple clic) = ancre pour « Ajouter une catégorie » et la surbrillance.
  const anchorId = highlightId

  function toggle(id: number) { setExpanded((prev) => { const n = new Set(prev); n.has(id) ? n.delete(id) : n.add(id); return n }) }
  function openEdit(n: CatNode) { setErr(null); setHighlightId(n.id); setSel({ mode: 'edit', catId: n.id, type: n.type }) }
  function addChild(n: CatNode) { setErr(null); setHighlightId(n.id); setSel({ mode: 'new-category', fatherId: n.id }) }
  function addCategory() {
    // Parent = nœud surligné dans l'arbre, sinon 1er catalogue.
    const parent = highlightId ?? tree[0]?.id ?? -1
    setErr(null); setSel({ mode: 'new-category', fatherId: parent })
  }
  async function confirmDelete() {
    if (!toDelete) return
    const wasSel = anchorId === toDelete.id
    try { await deleteCategory(toDelete.id); setToDelete(null); if (wasSel) { setSel(null); setHighlightId(null) } refresh() } catch { setToDelete(null) }
  }

  // Drag-and-drop : réordonnancement / changement de parent.
  function startDrag(node: CatNode) { const d = new Set<number>(); collectIds(node, d); setDrag({ id: node.id, fatherId: node.fatherId, type: node.type, descendants: d }); setErr(null) }
  function canDrop(targetFatherId: number): boolean {
    if (!drag) return false
    if (drag.descendants.has(targetFatherId)) return false
    if (drag.type === 'catalog' && targetFatherId !== -1) return false
    if (drag.type === 'category' && targetFatherId === -1) return false
    return true
  }
  async function doMove(fatherId: number, order: number) {
    if (!drag) return
    if (!canDrop(fatherId)) { setErr(drag.type === 'category' && fatherId === -1 ? t('move_cat_to_root') : t('err_move')); setDrag(null); setDropHint(null); return }
    const d = drag; setDrag(null); setDropHint(null)
    try { await reorderCategory({ catId: d.id, fatherId, order, oldParent: d.fatherId }); refresh() }
    catch (e) { setErr(e instanceof Error ? e.message : t('err_move')) }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: 24, height: '100%', boxSizing: 'border-box', overflow: mode === 'old' ? 'hidden' : 'auto' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16 }}>
        <div>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('title')}</h1>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('subtitle')}</p>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <ViewModeToggle mode={mode} onReact={() => setMode('react')} onOld={() => { setMode('old'); setOldLoaded(true) }} />
          <button style={btnGhost} onClick={() => { setErr(null); setSel({ mode: 'new-catalog' }) }}><PlusIcon />{t('add_catalog')}</button>
          <button style={btnPrimary} onClick={addCategory}><PlusIcon />{t('add_category')}</button>
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 16, flexShrink: 0 }}>
        {/* Toolbar : recherche + langue + Effacer / Réduire / Déplier / Rafraîchir (même largeur que l'arbre) */}
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', alignItems: 'center', maxWidth: 760 }}>
          <input style={{ ...inputCss, height: 36, flex: 1, minWidth: 220 }} value={search} onChange={(e) => setSearch(e.target.value)} placeholder={t('search')} />
          <select style={{ ...inputCss, height: 36, width: 'auto', minWidth: 120 }} value={langId} onChange={(e) => setLangId(Number(e.target.value))}>
            {languages.map((l) => <option key={l.id} value={l.id}>{l.name}</option>)}
          </select>
          <button style={{ ...btnGhost, height: 36 }} onClick={() => setSearch('')}>{t('clear')}</button>
          <button style={{ ...btnGhost, height: 36 }} onClick={() => setExpanded(new Set())}>{t('collapse_all')}</button>
          <button style={{ ...btnGhost, height: 36 }} onClick={() => setExpanded(new Set(allIds(tree)))}>{t('expand_all')}</button>
          <button style={{ ...btnGhost, height: 36 }} onClick={refresh}><RefreshIcon />{t('refresh')}</button>
        </div>

        {err && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14 }}>{err}</div>}

        {/* Arbre — largeur limitée (pas plein écran), toujours visible, scroll interne si grand */}
        <div style={{ ...card, padding: 8, minHeight: 90, maxHeight: '45vh', maxWidth: 760, overflow: 'auto', flexShrink: 0 }}>
          {loading && !view.length ? (
            <div style={{ padding: 40, textAlign: 'center', color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
          ) : !view.length ? (
            <div style={{ padding: 40, textAlign: 'center', color: 'var(--color-muted-foreground)' }}>{t('empty')}</div>
          ) : (
            <div onDragEnd={() => { setDrag(null); setDropHint(null) }}>
              <NodeList
                nodes={view} depth={0} parentId={-1} t={t} anchorId={anchorId}
                expanded={expanded} toggle={toggle}
                onHighlight={(n) => setHighlightId(n.id)} onEdit={openEdit} onAddChild={addChild} onDelete={setToDelete}
                onContext={(n, x, y) => { setHighlightId(n.id); setCtxMenu({ node: n, x, y }) }}
                drag={drag} dropHint={dropHint} setDropHint={setDropHint} startDrag={startDrag} doMove={doMove} canDrop={canDrop}
              />
            </div>
          )}
          <p style={{ ...hint, margin: '8px 4px 2px' }}>{t('reorder_hint')}</p>
        </div>

        {/* Formulaire inline (sous l'arbre) */}
        {sel && (
          <CategoryForm
            key={sel.mode === 'edit' ? `e${sel.catId}` : sel.mode === 'new-category' ? `nc${sel.fatherId}` : 'ncat'}
            sel={sel} tree={tree} languages={languages} countries={countries}
            onSaved={(id, type) => { refresh(); setSel({ mode: 'edit', catId: id, type }) }} onClose={() => setSel(null)} t={t}
          />
        )}
      </div>

      {/* Menu contextuel (clic droit sur un nœud) */}
      {ctxMenu && (
        <>
          <div style={{ position: 'fixed', inset: 0, zIndex: 60 }} onClick={() => setCtxMenu(null)} onContextMenu={(e) => { e.preventDefault(); setCtxMenu(null) }} />
          <div style={{ position: 'fixed', top: ctxMenu.y, left: ctxMenu.x, zIndex: 61, ...card, padding: 4, minWidth: 160, boxShadow: '0 8px 24px rgba(0,0,0,.18)' }}>
            {[
              { icon: <PlusIcon />, label: t('ctx_add'), on: () => addChild(ctxMenu.node) },
              { icon: <PencilIcon />, label: t('ctx_update'), on: () => openEdit(ctxMenu.node) },
              { icon: <TrashIcon />, label: t('ctx_delete'), on: () => setToDelete(ctxMenu.node), danger: true },
            ].map((it) => (
              <button key={it.label} onClick={() => { it.on(); setCtxMenu(null) }}
                style={{ display: 'flex', alignItems: 'center', gap: 10, width: '100%', padding: '8px 12px', border: 0, background: 'transparent', cursor: 'pointer', fontSize: 14, borderRadius: 6, color: it.danger ? '#dc2626' : 'inherit', textAlign: 'left' }}
                onMouseEnter={(e) => (e.currentTarget.style.background = 'var(--color-muted,rgba(0,0,0,.06))')} onMouseLeave={(e) => (e.currentTarget.style.background = 'transparent')}>
                {it.icon}{it.label}
              </button>
            ))}
          </div>
        </>
      )}

      {toDelete && (
        <ConfirmModal title={t('del_title')} message={t('del_confirm', { u: `${toDelete.id} - ${toDelete.name}` })}
          extra={toDelete.type === 'catalog' && toDelete.children.length > 0 && <p style={{ fontSize: 13, color: '#b45309', marginTop: 6 }}>{t('del_catalog_warn')}</p>}
          onConfirm={confirmDelete} onCancel={() => setToDelete(null)} t={t} />
      )}
    </div>
  )
}

// ── Arbre (liste de nœuds d'un niveau + barres de drop) ──────────────────────────
type RowProps = {
  depth: number; parentId: number; t: (k: string, v?: Record<string, string | number>) => string; anchorId: number | null
  expanded: Set<number>; toggle: (id: number) => void
  onHighlight: (n: CatNode) => void; onEdit: (n: CatNode) => void; onAddChild: (n: CatNode) => void; onDelete: (n: CatNode) => void
  onContext: (n: CatNode, x: number, y: number) => void
  drag: { id: number; type: string } | null; dropHint: string | null; setDropHint: (s: string | null) => void
  startDrag: (n: CatNode) => void; doMove: (fatherId: number, order: number) => void; canDrop: (fatherId: number) => boolean
}

function NodeList(props: RowProps & { nodes: CatNode[] }) {
  const { nodes, depth, parentId, drag, dropHint, setDropHint, doMove, canDrop } = props
  return (
    <div>
      {nodes.map((n) => (
        <div key={n.id}>
          <DropBar active={!!drag && dropHint === `before-${parentId}-${n.id}` && canDrop(parentId)} depth={depth}
            onOver={() => setDropHint(`before-${parentId}-${n.id}`)} onDrop={() => doMove(parentId, n.order)} />
          <NodeRow {...props} node={n} />
        </div>
      ))}
      <DropBar active={!!drag && dropHint === `end-${parentId}` && canDrop(parentId)} depth={depth}
        onOver={() => setDropHint(`end-${parentId}`)} onDrop={() => doMove(parentId, nodes.length + 1)} />
    </div>
  )
}

function DropBar({ active, depth, onOver, onDrop }: { active: boolean; depth: number; onOver: () => void; onDrop: () => void }) {
  return (
    <div onDragOver={(e) => { e.preventDefault(); onOver() }} onDrop={(e) => { e.preventDefault(); onDrop() }}
      style={{ height: active ? 22 : 6, marginLeft: 12 + depth * 22, borderRadius: 4, transition: 'height .08s',
        background: active ? 'color-mix(in srgb, var(--color-primary) 25%, transparent)' : 'transparent',
        border: active ? '1px dashed var(--color-primary)' : '1px solid transparent' }} />
  )
}

function NodeRow(props: RowProps & { node: CatNode }) {
  const { node, depth, t, anchorId, expanded, toggle, onHighlight, onEdit, onAddChild, onDelete, onContext, drag, dropHint, setDropHint, startDrag, doMove, canDrop } = props
  const isOpen = expanded.has(node.id)
  const hasKids = node.children.length > 0
  const intoActive = !!drag && drag.id !== node.id && dropHint === `into-${node.id}` && canDrop(node.id)
  const active = anchorId === node.id
  const [hover, setHover] = useState(false)

  return (
    <>
      <div
        draggable
        onDragStart={(e) => { e.stopPropagation(); e.dataTransfer.effectAllowed = 'move'; startDrag(node) }}
        onDragOver={(e) => { if (drag && drag.id !== node.id) { e.preventDefault(); e.stopPropagation(); setDropHint(`into-${node.id}`) } }}
        onDrop={(e) => { if (drag && drag.id !== node.id) { e.preventDefault(); e.stopPropagation(); doMove(node.id, node.children.length + 1) } }}
        onMouseEnter={() => setHover(true)} onMouseLeave={() => setHover(false)}
        onClick={() => onHighlight(node)}
        onDoubleClick={() => onEdit(node)}
        onContextMenu={(e) => { e.preventDefault(); onContext(node, e.clientX, e.clientY) }}
        style={{
          display: 'flex', alignItems: 'center', gap: 8, padding: '7px 10px', borderRadius: 6, cursor: 'pointer',
          marginLeft: depth * 22, userSelect: 'none',
          background: active ? 'color-mix(in srgb, var(--color-primary) 16%, transparent)' : intoActive ? 'color-mix(in srgb, var(--color-primary) 14%, transparent)' : hover ? 'var(--color-muted,rgba(0,0,0,.03))' : 'transparent',
          outline: intoActive ? '1px dashed var(--color-primary)' : 'none', opacity: drag?.id === node.id ? 0.4 : 1,
        }}
      >
        <button onClick={(e) => { e.stopPropagation(); hasKids && toggle(node.id) }} style={{ ...iconBtn, width: 22, height: 22, visibility: hasKids ? 'visible' : 'hidden' }}>
          <span style={{ display: 'inline-flex', transform: isOpen ? 'none' : 'rotate(-90deg)', transition: 'transform .1s' }}><ChevronDownIcon /></span>
        </button>
        <span style={{ display: 'inline-flex', color: node.status === 1 ? '#16a34a' : '#dc2626', fontSize: 10 }} title={node.status === 1 ? t('online') : t('offline')}>●</span>
        <span style={{ display: 'inline-flex', color: 'var(--color-muted-foreground)' }}>{node.type === 'catalog' ? <LayoutIcon /> : <TagIcon />}</span>
        <span style={{ fontWeight: node.type === 'catalog' || active ? 600 : 400, color: active ? 'var(--color-primary)' : 'inherit' }}>{node.id} - {node.name}</span>
        <span style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>{t('products_n', { n: node.productCount })}</span>
        <div style={{ marginLeft: 'auto', display: 'flex', gap: 4, opacity: hover || active ? 1 : 0, transition: 'opacity .1s' }}>
          <button style={iconBtn} title={t('add_child')} onClick={(e) => { e.stopPropagation(); onAddChild(node) }}><PlusIcon /></button>
          <button style={iconBtn} title={t('edit')} onClick={(e) => { e.stopPropagation(); onEdit(node) }}><PencilIcon /></button>
          <button style={{ ...iconBtn, color: 'var(--color-destructive,#ef4444)' }} title={t('del')} onClick={(e) => { e.stopPropagation(); onDelete(node) }}><TrashIcon /></button>
        </div>
      </div>
      {isOpen && hasKids && <NodeList {...props} nodes={node.children} depth={depth + 1} parentId={node.id} />}
    </>
  )
}

// ── Formulaire inline (Propriétés / SEO / Produits) ──────────────────────────────
function CategoryForm({ sel, tree, languages, countries, onSaved, onClose, t }: {
  sel: Selection; tree: CatNode[]; languages: LangOption[]; countries: Option[]
  onSaved: (id: number, type: 'catalog' | 'category') => void; onClose: () => void
  t: (k: string, v?: Record<string, string | number>) => string
}) {
  const isEdit = sel.mode === 'edit'
  const isCatalog = sel.mode === 'new-catalog' || (sel.mode === 'edit' && sel.type === 'catalog')
  const catId = sel.mode === 'edit' ? sel.catId : null
  const fatherId0 = sel.mode === 'new-category' ? sel.fatherId : -1

  const [fatherId, setFatherId] = useState<number>(fatherId0)
  const [reference, setReference] = useState('')
  const [validStart, setValidStart] = useState('')
  const [validEnd, setValidEnd] = useState('')
  const [active, setActive] = useState(true)
  const [texts, setTexts] = useState<CatText[]>([])
  const [countryIds, setCountryIds] = useState<number[]>([])
  const [tab, setTab] = useState('properties')
  const [lang, setLang] = useState<number>(languages[0]?.id ?? 0)
  const [loading, setLoading] = useState(false)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [errName, setErrName] = useState(false)
  // SEO + Produits (chargés à la demande)
  const [seo, setSeo] = useState<CatSeo[]>([])
  const [seoLoaded, setSeoLoaded] = useState(false)
  const [products, setProducts] = useState<CatProduct[]>([])
  const [prodLoaded, setProdLoaded] = useState(false)

  useEffect(() => {
    setTexts(languages.map((l) => ({ langId: l.id, langName: l.name, name: '', description: '' })))
    setSeo(languages.map((l) => ({ langId: l.id, langName: l.name, url: '', metaTitle: '', metaDescription: '' })))
    setLang((v) => v || languages[0]?.id || 0)
  }, [languages])
  useEffect(() => {
    if (!isEdit || !catId) return
    setLoading(true)
    fetchCategoryById(catId).then((c) => {
      setFatherId(c.fatherId); setReference(c.reference)
      setValidStart(toDateInput(c.validStart)); setValidEnd(toDateInput(c.validEnd))
      setActive(c.status === 1); setTexts(c.texts); setCountryIds(c.countryIds)
    }).catch(() => null).finally(() => setLoading(false))
  }, [isEdit, catId])
  // Charge SEO / Produits quand on ouvre l'onglet (édition uniquement).
  useEffect(() => {
    if (tab === 'seo' && isEdit && catId && !seoLoaded) { fetchCategorySeo(catId).then((r) => { setSeo(r.items); setSeoLoaded(true) }).catch(() => null) }
    if (tab === 'products' && isEdit && catId && !prodLoaded) { fetchCategoryProducts(catId).then((r) => { setProducts(r.items); setProdLoaded(true) }).catch(() => null) }
  }, [tab, isEdit, catId, seoLoaded, prodLoaded])

  const TABS: TabDef[] = [
    { key: 'properties', label: t('tab_properties'), icon: <FileTextIcon /> },
    { key: 'seo',        label: t('tab_seo'),        icon: <TagIcon /> },
    { key: 'products',   label: t('tab_products'),   icon: <CartIcon />, disabled: !isEdit },
  ]

  function setText(langId: number, field: 'name' | 'description', value: string) { setTexts((p) => p.map((x) => x.langId === langId ? { ...x, [field]: value } : x)); setError(null) }
  function setSeoField(langId: number, field: 'url' | 'metaTitle' | 'metaDescription', value: string) { setSeo((p) => p.map((x) => x.langId === langId ? { ...x, [field]: value } : x)) }
  function toggleCountry(cid: number) { setCountryIds((p) => p.includes(cid) ? p.filter((x) => x !== cid) : [...p, cid]) }
  const allCountries = countries.length > 0 && countryIds.length === countries.length
  function toggleAllCountries() { setCountryIds(allCountries ? [] : countries.map((c) => c.id)) }

  const parentName = !isCatalog ? (findNode(tree, fatherId)?.name ?? '') : ''

  async function submit() {
    if (!texts.some((x) => x.name.trim())) { setErrName(true); setError(t('err_name')); setTab('properties'); return }
    if (validStart && validEnd && validStart > validEnd) { setError(t('err_dates')); setTab('properties'); return }
    setSaving(true); setError(null)
    try {
      const r = await saveCategory({
        id: catId, fatherId, status: active, reference: reference.trim(),
        validStart: validStart || null, validEnd: validEnd || null,
        texts: texts.map((x) => ({ langId: x.langId, name: x.name.trim(), description: x.description })),
        countryIds,
      })
      // SEO (si déjà chargé / modifié) — sauvé après que la catégorie ait un id.
      if (seoLoaded || seo.some((s) => s.url || s.metaTitle || s.metaDescription)) {
        try { await saveCategorySeo(r.id, seo.map((s) => ({ langId: s.langId, url: s.url, metaTitle: s.metaTitle, metaDescription: s.metaDescription }))) } catch { /* */ }
      }
      notify('ok', t('title'), t('saved'))
      onSaved(r.id, isCatalog ? 'catalog' : 'category')
    } catch (e) { const msg = e instanceof Error ? e.message : t('err_save'); setError(msg); notify('ko', t('title'), msg) } finally { setSaving(false) }
  }

  const headerTitle = isEdit ? (isCatalog ? t('edit_catalog_title') : t('edit_category_title')) : (isCatalog ? t('new_catalog_title') : t('new_category_title'))
  const secTitle = { fontSize: 12, fontWeight: 600, display: 'flex', alignItems: 'center', gap: 8, color: 'var(--color-foreground)', margin: '0 0 12px' } as const

  return (
    <div style={{ ...card, padding: 0 }}>
      {/* En-tête du formulaire */}
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, padding: '16px 20px', borderBottom: '1px solid var(--color-border)' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ display: 'inline-flex', color: 'var(--color-primary)' }}>{isCatalog ? <LayoutIcon /> : <TagIcon />}</span>
          <h2 style={{ fontSize: 17, fontWeight: 700, margin: 0 }}>{headerTitle}</h2>
          {sel.mode === 'new-category' && parentName && <span style={{ fontSize: 13, color: 'var(--color-muted-foreground)' }}>↳ {parentName}</span>}
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
          <button style={btnGhost} onClick={onClose}>{t('cancel')}</button>
          <button style={btnPrimary} onClick={submit} disabled={saving || loading}>{saving ? '…' : (isCatalog ? t('save_catalog') : t('save_category'))}</button>
        </div>
      </div>

      <div style={{ padding: '16px 20px' }}>
        <Tabs tabs={TABS} active={tab} onChange={setTab} />
        {error && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, marginBottom: 14 }}>{error}</div>}

        {loading ? (
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', minHeight: 200, color: 'var(--color-muted-foreground)' }}>{t('loading')}</div>
        ) : tab === 'properties' ? (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
            {/* Titre Propriétés + Statut Actif/Inactif */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, borderBottom: '1px solid var(--color-border)', paddingBottom: 12 }}>
              <h3 style={{ fontSize: 16, fontWeight: 600, margin: 0 }}>{t('tab_properties')}</h3>
              <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                <span style={{ fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.06em', color: 'var(--color-muted-foreground)' }}>{t('status_label')}</span>
                <button type="button" onClick={() => setActive((v) => !v)}
                  style={{ position: 'relative', width: 46, height: 24, borderRadius: 999, border: 0, cursor: 'pointer', background: active ? '#22c55e' : 'var(--color-muted-foreground)', flexShrink: 0, transition: 'background .15s' }}>
                  <span style={{ position: 'absolute', top: 2, left: active ? 24 : 2, width: 20, height: 20, borderRadius: 999, background: '#fff', transition: 'left .15s', boxShadow: '0 1px 2px rgba(0,0,0,.3)' }} />
                </button>
                <span style={{ fontSize: 14, fontWeight: 600, color: active ? '#16a34a' : 'var(--color-muted-foreground)' }}>{active ? t('online') : t('offline')}</span>
              </div>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit,minmax(320px,1fr))', gap: 24, alignItems: 'start' }}>
              {/* Texts (sélecteur de langue vertical) */}
              <div>
                <h4 style={secTitle}>⚙ {t('sec_texts')}</h4>
                <div style={{ display: 'grid', gridTemplateColumns: '150px 1fr', gap: 14 }}>
                  <LangSwitcher languages={languages} value={lang} onChange={setLang} />
                  {texts.filter((x) => x.langId === lang).map((tx) => (
                    <div key={tx.langId}>
                      <label style={{ ...label, color: errName ? '#dc2626' : undefined }}>{t('f_name')}</label>
                      <input style={{ ...inputCss, ...(errName ? { borderColor: '#dc2626' } : {}) }} value={tx.name} onChange={(e) => { setText(tx.langId, 'name', e.target.value); setErrName(false) }} placeholder={t('f_name_ph')} autoComplete="off" />
                      <label style={{ ...label, marginTop: 14 }}>{t('f_description')}</label>
                      <textarea style={{ ...inputCss, minHeight: 120, resize: 'vertical', paddingTop: 8 }} value={tx.description} onChange={(e) => setText(tx.langId, 'description', e.target.value)} placeholder={t('f_description_ph')} />
                    </div>
                  ))}
                </div>
              </div>

              {/* Validité + Pays (le parent = nœud surligné dans l'arbre, auto) */}
              <div style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
                <div>
                  <h4 style={secTitle}><span style={{ display: 'inline-flex' }}><MapPinIcon /></span> {t('sec_dates')}</h4>
                  <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
                    <div>
                      <label style={label}>{t('f_valid_start')}</label>
                      <input style={inputCss} type="date" value={validStart} onChange={(e) => setValidStart(e.target.value)} />
                    </div>
                    <div>
                      <label style={label}>{t('f_valid_end')}</label>
                      <input style={inputCss} type="date" value={validEnd} onChange={(e) => setValidEnd(e.target.value)} />
                    </div>
                  </div>
                  <label style={{ ...label, marginTop: 14 }}>{t('f_reference')}</label>
                  <input style={inputCss} value={reference} onChange={(e) => setReference(e.target.value)} placeholder={t('f_reference_ph')} autoComplete="off" />
                </div>
                <div>
                  <h4 style={secTitle}>🌐 {t('sec_countries')}</h4>
                  {countries.length === 0 ? <p style={{ ...hint, margin: 0 }}>{t('f_no_country')}</p> : (
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 6, maxHeight: 240, overflow: 'auto' }}>
                      <label style={{ display: 'flex', alignItems: 'center', gap: 10, fontSize: 14, cursor: 'pointer', padding: '5px 8px', borderRadius: 6, fontWeight: 600 }}>
                        <input type="checkbox" checked={allCountries} onChange={toggleAllCountries} style={{ accentColor: 'var(--color-primary)' }} />
                        {t('countries_all')}
                      </label>
                      {countries.map((c) => (
                        <label key={c.id} style={{ display: 'flex', alignItems: 'center', gap: 10, fontSize: 14, cursor: 'pointer', padding: '5px 8px', borderRadius: 6, border: '1px solid var(--color-border)' }}>
                          <input type="checkbox" checked={countryIds.includes(c.id)} onChange={() => toggleCountry(c.id)} style={{ accentColor: 'var(--color-primary)' }} />
                          {c.name}
                        </label>
                      ))}
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        ) : tab === 'seo' ? (
          !isEdit ? <p style={{ ...hint }}>{t('save_first_tab')}</p> : (
            <div style={{ display: 'grid', gridTemplateColumns: '150px 1fr', gap: 14, maxWidth: 760 }}>
              <LangSwitcher languages={languages} value={lang} onChange={setLang} />
              {seo.filter((s) => s.langId === lang).map((s) => (
                <div key={s.langId}>
                  <label style={label}>{t('seo_url')}</label>
                  <input style={inputCss} value={s.url} onChange={(e) => setSeoField(s.langId, 'url', e.target.value)} placeholder={t('seo_url_ph')} autoComplete="off" />
                  <label style={{ ...label, marginTop: 14 }}>{t('seo_meta_title')}</label>
                  <input style={inputCss} value={s.metaTitle} onChange={(e) => setSeoField(s.langId, 'metaTitle', e.target.value)} placeholder={t('seo_meta_title_ph')} autoComplete="off" />
                  <label style={{ ...label, marginTop: 14 }}>{t('seo_meta_desc')}</label>
                  <textarea style={{ ...inputCss, minHeight: 90, resize: 'vertical', paddingTop: 8 }} value={s.metaDescription} onChange={(e) => setSeoField(s.langId, 'metaDescription', e.target.value)} placeholder={t('seo_meta_desc_ph')} />
                </div>
              ))}
            </div>
          )
        ) : (
          !isEdit ? <p style={{ ...hint }}>{t('save_first_tab')}</p> : (
            <div>
              <h3 style={{ fontSize: 15, fontWeight: 600, margin: '0 0 4px' }}>{t('prod_title')}</h3>
              <p style={{ ...hint, marginTop: 0 }}>{t('products_n', { n: products.length })}</p>
              {products.length === 0 ? (
                <div style={{ padding: 30, textAlign: 'center', color: 'var(--color-muted-foreground)', border: '1px dashed var(--color-border)', borderRadius: 8 }}>{t('prod_empty')}</div>
              ) : (
                <div style={{ border: '1px solid var(--color-border)', borderRadius: 8, overflow: 'hidden' }}>
                  <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                    <thead style={{ background: 'var(--color-muted,rgba(0,0,0,.03))' }}>
                      <tr><th style={{ ...th, width: 60 }}>{t('prod_col_id')}</th><th style={th}>{t('prod_col_ref')}</th><th style={th}>{t('prod_col_name')}</th><th style={{ ...th, width: 100 }}>{t('prod_col_status')}</th></tr>
                    </thead>
                    <tbody>
                      {products.map((p) => (
                        <tr key={p.id}>
                          <td style={{ ...td, color: 'var(--color-muted-foreground)' }}>{p.id}</td>
                          <td style={td}>{p.reference || '—'}</td>
                          <td style={{ ...td, fontWeight: 500 }}>{p.name}</td>
                          <td style={td}><StatusBadge active={p.status === 1} t={t} /></td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}
            </div>
          )
        )}
      </div>
    </div>
  )
}

/** Sélecteur de langue vertical (drapeau + nom), comme l'outil legacy. */
function LangSwitcher({ languages, value, onChange }: { languages: LangOption[]; value: number; onChange: (id: number) => void }) {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
      {languages.map((l) => {
        const on = l.id === value
        return (
          <button key={l.id} onClick={() => onChange(l.id)}
            style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, padding: '8px 10px', borderRadius: 6, cursor: 'pointer', textAlign: 'left',
              border: '1px solid ' + (on ? 'var(--color-primary)' : 'var(--color-border)'),
              background: on ? 'var(--color-primary)' : 'transparent', color: on ? 'var(--color-primary-foreground,#fff)' : 'inherit', fontWeight: on ? 600 : 400, fontSize: 14 }}>
            <span>{l.name}</span>
            {l.flag ? <img src={`data:image/png;base64,${l.flag}`} alt="" style={{ width: 22, height: 22, borderRadius: 3 }} /> : null}
          </button>
        )
      })}
    </div>
  )
}

/** ISO/datetime → valeur d'input type=date (YYYY-MM-DD). */
function toDateInput(value: string | null): string {
  if (!value) return ''
  const m = String(value).match(/^(\d{4}-\d{2}-\d{2})/)
  return m ? m[1] : ''
}
