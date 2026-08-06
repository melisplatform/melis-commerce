import { useEffect, useState } from 'react'
import {
  deleteCategory, fetchCatalogOptions, fetchCatalogTree, fetchCategoryById, fetchCategoryProducts,
  fetchCategorySeo, reorderCategory, reorderCategoryProducts, saveCategory, saveCategorySeo,
  type CatNode, type CatText, type CatSeo, type CatProduct, type LangOption,
} from './api'
import { DICT } from './dict'
import CatalogTree from './CatalogTree'
import { makeT } from '../../shared/i18n'
import { DatePicker } from '../../shared/DatePicker'
import { card, inputCss, btnPrimary, btnGhost, label, hint } from '../../shared/styles'
import { PlusIcon, PencilIcon, TrashIcon, LayoutIcon, TagIcon, FileTextIcon, MapPinIcon, CartIcon, GripIcon, SettingsIcon, GlobeIcon } from '../../shared/icons'
import { ViewModeToggle, LegacyFrame, StatusBadge, ConfirmModal } from '../../shared/widgets'
import { notify } from '../../shared/notify'
import { Tabs, type TabDef } from '../../shared/Tabs'
import { useCaps } from '../../shared/useCaps'
import { useIsNarrow } from '../../shared/useIsNarrow'
import { usePointerDrag } from '../../shared/usePointerDrag'
import type { Option } from '../../shared/api'
import { FormErrorBanner, type FormIssue } from '../../shared/melis-form-errors'

/* Brique « Catalogues / Catégories » (MelisCommerce) — arbre full React (drag-and-drop) +
 * formulaire d'édition INLINE sous l'arbre (même page), comme l'outil legacy.
 * Logique métier inchangée : les écritures réutilisent les services Laminas (cf. contrôleur API). */
const TOOL_MELIS_KEY = 'meliscommerce_categories_page'

type Selection =
  | { mode: 'new-catalog' }
  | { mode: 'new-category'; fatherId: number }
  | { mode: 'edit'; catId: number; type: 'catalog' | 'category' }

// ── Helpers arbre ──────────────────────────────────────────────────────────────
function findNode(nodes: CatNode[], id: number): CatNode | null {
  for (const n of nodes) { if (n.id === id) return n; const f = findNode(n.children, id); if (f) return f }
  return null
}

// ── Case tri-état (design melis-core Users / gestion de droits) ───────────────────
// all = case cochée · some = tiret (sélection partielle) · none = case vide.
type Tri = 'all' | 'some' | 'none'
function TriCheckbox({ state, onChange }: { state: Tri; onChange: (v: boolean) => void }) {
  const color = state === 'none' ? 'var(--color-muted-foreground)' : 'var(--color-primary)'
  const opacity = state === 'all' ? 1 : state === 'some' ? 0.7 : 0.6
  return (
    <button type="button" onClick={() => onChange(state !== 'all')}
      style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', border: 0, background: 'transparent', cursor: 'pointer', padding: 0, color, opacity, flexShrink: 0 }}>
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ pointerEvents: 'none' }}>
        <rect x="3" y="3" width="18" height="18" rx="2" />
        {state === 'all' && <path d="m8 12 3 3 5-6" />}
        {state === 'some' && <path d="M8 12h8" />}
      </svg>
    </button>
  )
}
/** Ligne cliquable (case tri-état + libellé) façon éditeur de droits melis-core. */
function CheckRow({ state, label, strong, onChange }: { state: Tri; label: string; strong?: boolean; onChange: (v: boolean) => void }) {
  const [hover, setHover] = useState(false)
  return (
    <div onMouseEnter={() => setHover(true)} onMouseLeave={() => setHover(false)}
      style={{ display: 'flex', alignItems: 'center', gap: 8, padding: '5px 8px', borderRadius: 6, background: hover ? 'var(--color-muted,rgba(0,0,0,.04))' : 'transparent' }}>
      <TriCheckbox state={state} onChange={onChange} />
      <button type="button" onClick={() => onChange(state !== 'all')}
        style={{ flex: 1, textAlign: 'left', border: 0, background: 'transparent', cursor: 'pointer', fontSize: 14, fontWeight: strong ? 600 : 400, color: 'var(--color-foreground)', padding: 0 }}>{label}</button>
    </div>
  )
}

export default function CatalogPage() {
  const t = makeT(DICT)
  const { can } = useCaps(TOOL_MELIS_KEY)
  const narrow = useIsNarrow()
  const [mode, setMode] = useState<'react' | 'old'>('react')
  const [oldLoaded, setOldLoaded] = useState(false)
  const [tree, setTree] = useState<CatNode[]>([])
  const [loading, setLoading] = useState(false)
  const [languages, setLanguages] = useState<LangOption[]>([])
  const [countries, setCountries] = useState<Option[]>([])
  const [langId, setLangId] = useState<number>(0)
  const [sel, setSel] = useState<Selection | null>(null)
  const [highlightId, setHighlightId] = useState<number | null>(null)
  const [ctxMenu, setCtxMenu] = useState<{ node: CatNode; x: number; y: number } | null>(null)
  const [toDelete, setToDelete] = useState<CatNode | null>(null)
  const [err, setErr] = useState<string | null>(null)
  const [tick, setTick] = useState(0)

  useEffect(() => { fetchCatalogOptions().then((o) => { setLanguages(o.languages); setCountries(o.countries); setLangId((v) => v || o.languages[0]?.id || 0) }).catch(() => null) }, [])
  useEffect(() => {
    setLoading(true)
    fetchCatalogTree(langId || undefined).then((r) => setTree(r.items)).catch(() => null).finally(() => setLoading(false))
  }, [langId, tick])

  const refresh = () => setTick((x) => x + 1)
  const anchorId = highlightId

  function openEdit(n: CatNode) { setErr(null); setHighlightId(n.id); setSel({ mode: 'edit', catId: n.id, type: n.type }) }
  function addChild(n: CatNode) { setErr(null); setHighlightId(n.id); setSel({ mode: 'new-category', fatherId: n.id }) }
  async function confirmDelete() {
    if (!toDelete) return
    const wasSel = anchorId === toDelete.id
    try { await deleteCategory(toDelete.id); notify('ok', t('title'), t('deleted')); setToDelete(null); if (wasSel) { setSel(null); setHighlightId(null) } refresh() } catch { setToDelete(null) }
  }

  // Réordonnancement / changement de parent (validation faite dans CatalogTree).
  async function doMove(move: { catId: number; fatherId: number; order: number; oldParent: number }) {
    setErr(null)
    try { await reorderCategory(move); refresh() }
    catch (e) { setErr(e instanceof Error ? e.message : t('err_move')) }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', padding: 24, boxSizing: 'border-box', height: '100%', overflow: 'hidden' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, flexShrink: 0 }}>
        <div>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('title')}</h1>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('subtitle')}</p>
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexWrap: 'wrap', justifyContent: 'flex-end' }}>
          <ViewModeToggle mode={mode} onReact={() => setMode('react')} onOld={() => { setMode('old'); setOldLoaded(true) }} />
        </div>
      </div>

      {oldLoaded && <LegacyFrame melisKey={TOOL_MELIS_KEY} title={t('title')} visible={mode === 'old'} />}

      {/* Vue React — maître-détail : arbre à gauche, édition à droite (comme melis-cms category) */}
      <div style={{ display: mode === 'react' ? 'flex' : 'none', flexDirection: 'column', gap: 12, flex: 1, minHeight: 0, marginTop: 16 }}>
        {err && <div style={{ border: '1px solid #fca5a5', background: 'color-mix(in srgb, #ef4444 8%, transparent)', color: '#dc2626', borderRadius: 8, padding: '8px 14px', fontSize: 14, flexShrink: 0 }}>{err}</div>}

        <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : 'minmax(320px, 440px) minmax(0, 1fr)', gap: 16, flex: 1, minHeight: 0 }}>
          {/* Colonne gauche : arbre — sur mobile, masqué dès qu'une sélection ouvre le formulaire (drill-down, plus de place pour 2 colonnes côte à côte). */}
          {(!narrow || !sel) && (
            <CatalogTree
              nodes={tree} languages={languages} currentLangId={langId} onLangChange={setLangId}
              selectedId={highlightId} loading={loading} can={can} t={t}
              onSelect={openEdit} onAddChild={addChild}
              onAddCatalog={() => { setErr(null); setHighlightId(null); setSel({ mode: 'new-catalog' }) }}
              onDelete={setToDelete}
              onContext={(n, x, y) => { setHighlightId(n.id); setCtxMenu({ node: n, x, y }) }}
              onReorder={doMove}
              onRefresh={refresh}
            />
          )}

          {/* Colonne droite : édition (ou placeholder) — masquée sur mobile tant que rien n'est sélectionné. */}
          {(!narrow || sel) && (
            <div style={{ minWidth: 0, minHeight: 0, overflow: 'auto' }}>
              {sel ? (
                <>
                  {narrow && (
                    <button style={{ ...btnGhost, marginBottom: 12 }} onClick={() => { setSel(null); setHighlightId(null) }}>← {t('back_to_tree')}</button>
                  )}
                  <CategoryForm
                    key={sel.mode === 'edit' ? `e${sel.catId}` : sel.mode === 'new-category' ? `nc${sel.fatherId}` : 'ncat'}
                    sel={sel} tree={tree} languages={languages} countries={countries}
                    onSaved={(id, type) => { refresh(); setHighlightId(id); setSel({ mode: 'edit', catId: id, type }) }}
                    onClose={() => { setSel(null); setHighlightId(null) }} t={t}
                  />
                </>
              ) : (
                <div style={{ ...card, height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', textAlign: 'center', padding: 40, boxSizing: 'border-box' }}>
                  <p style={{ maxWidth: 320, fontSize: 14, color: 'var(--color-muted-foreground)', margin: 0 }}>{t('editor_empty')}</p>
                </div>
              )}
            </div>
          )}
        </div>
      </div>

      {/* Menu contextuel (clic droit sur un nœud) */}
      {ctxMenu && (
        <>
          <div style={{ position: 'fixed', inset: 0, zIndex: 60 }} onClick={() => setCtxMenu(null)} onContextMenu={(e) => { e.preventDefault(); setCtxMenu(null) }} />
          <div style={{ position: 'fixed', top: ctxMenu.y, left: ctxMenu.x, zIndex: 61, ...card, padding: 4, minWidth: 160, boxShadow: '0 8px 24px rgba(0,0,0,.18)' }}>
            {[
              { icon: <PlusIcon />, label: t('ctx_add'), on: () => addChild(ctxMenu.node), cap: 'create' },
              { icon: <PencilIcon />, label: t('ctx_update'), on: () => openEdit(ctxMenu.node), cap: 'edit' },
              { icon: <TrashIcon />, label: t('ctx_delete'), on: () => setToDelete(ctxMenu.node), danger: true, cap: 'delete' },
            ].filter((it) => can(it.cap)).map((it) => (
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

// ── Formulaire inline (Propriétés / SEO / Produits) ──────────────────────────────
function CategoryForm({ sel, tree, languages, countries, onSaved, onClose, t }: {
  sel: Selection; tree: CatNode[]; languages: LangOption[]; countries: Option[]
  onSaved: (id: number, type: 'catalog' | 'category') => void; onClose: () => void
  t: (k: string, v?: Record<string, string | number>) => string
}) {
  const { can, loaded: capsLoaded } = useCaps(TOOL_MELIS_KEY)
  const narrow = useIsNarrow()
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
  const [errName, setErrName] = useState(false)
  const [formError, setFormError] = useState(false)
  const [formIssues, setFormIssues] = useState<FormIssue[]>([])
  // SEO + Produits (chargés à la demande)
  const [seo, setSeo] = useState<CatSeo[]>([])
  const [seoLoaded, setSeoLoaded] = useState(false)
  const [products, setProducts] = useState<CatProduct[]>([])
  const [prodLoaded, setProdLoaded] = useState(false)
  // Drag-and-drop de l'ordre des produits affectés (persiste pcat_order, comme le legacy).
  // Pointer Events (pas HTML5 draggable) : fonctionne aussi bien au doigt qu'à la souris —
  // draggable/dragstart ne se déclenche jamais sur un geste tactile mobile (Mantis #10843).
  const [dragProd, setDragProd] = useState<number | null>(null)
  const [dropProd, setDropProd] = useState<{ id: number; pos: 'before' | 'after' } | null>(null)
  const prodPointerDrag = usePointerDrag<number>('data-prod-id', Number)

  useEffect(() => {
    setTexts(languages.map((l) => ({ langId: l.id, langName: l.name, name: '', description: '' })))
    setSeo(languages.map((l) => ({ langId: l.id, langName: l.name, pageId: '', url: '', urlRedirect: '', url301: '', metaTitle: '', metaDescription: '' })))
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
  ].filter((tb) => can(tb.key))

  // Si l'onglet actif vient d'être retiré par les droits, basculer sur le premier autorisé.
  useEffect(() => {
    if (capsLoaded && TABS.length && !TABS.some((tb) => tb.key === tab)) setTab(TABS[0].key)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [capsLoaded])

  function setText(langId: number, field: 'name' | 'description', value: string) { setTexts((p) => p.map((x) => x.langId === langId ? { ...x, [field]: value } : x)) }
  function setSeoField(langId: number, field: 'pageId' | 'url' | 'urlRedirect' | 'url301' | 'metaTitle' | 'metaDescription', value: string) { setSeo((p) => p.map((x) => x.langId === langId ? { ...x, [field]: value } : x)) }
  // Tri-état de l'affectation pays : aucune → none, toutes → all, partielle → some (tiret).
  const countriesState: Tri = countryIds.length === 0 ? 'none' : (countries.length > 0 && countryIds.length >= countries.length ? 'all' : 'some')

  // Réordonne les produits de la catégorie (optimiste) puis persiste l'ordre (pcat_order).
  function moveProduct(fromId: number, targetId: number, pos: 'before' | 'after') {
    if (fromId === targetId || !catId) return
    const next = [...products]
    const from = next.findIndex((p) => p.id === fromId)
    if (from < 0) return
    const [moved] = next.splice(from, 1)
    let to = next.findIndex((p) => p.id === targetId)
    if (to < 0) return
    if (pos === 'after') to += 1
    next.splice(to, 0, moved)
    setProducts(next)
    reorderCategoryProducts(catId, next.map((p) => p.id)).catch(() => notify('ko', t('title'), t('err_save')))
  }

  const parentName = !isCatalog ? (findNode(tree, fatherId)?.name ?? '') : ''

  async function submit() {
    const issues: FormIssue[] = []
    if (!texts.some((x) => x.name.trim())) { setErrName(true); issues.push({ message: t('err_name') }); setTab('properties') } else setErrName(false)
    if (issues.length) { setFormIssues(issues); setFormError(true); return }
    setFormIssues([])
    if (validStart && validEnd && validStart > validEnd) { setTab('properties'); notify('ko', t('title'), t('err_dates')); return }
    setFormError(false)
    setSaving(true)
    try {
      const r = await saveCategory({
        id: catId, fatherId, status: active, reference: reference.trim(),
        validStart: validStart || null, validEnd: validEnd || null,
        texts: texts.map((x) => ({ langId: x.langId, name: x.name.trim(), description: x.description })),
        countryIds,
      })
      // SEO (si déjà chargé / modifié) — sauvé après que la catégorie ait un id.
      if (seoLoaded || seo.some((s) => s.pageId || s.url || s.urlRedirect || s.url301 || s.metaTitle || s.metaDescription)) {
        try { await saveCategorySeo(r.id, seo.map((s) => ({ langId: s.langId, pageId: s.pageId, url: s.url, urlRedirect: s.urlRedirect, url301: s.url301, metaTitle: s.metaTitle, metaDescription: s.metaDescription }))) } catch { /* */ }
      }
      notify('ok', t('title'), t('saved'))
      onSaved(r.id, isCatalog ? 'catalog' : 'category')
    } catch (e) { const msg = e instanceof Error ? e.message : t('err_save'); notify('ko', t('title'), msg) } finally { setSaving(false) }
  }

  const headerTitle = isEdit ? (isCatalog ? t('edit_catalog_title') : t('edit_category_title')) : (isCatalog ? t('new_catalog_title') : t('new_category_title'))
  const secTitle = { fontSize: 12, fontWeight: 600, display: 'flex', alignItems: 'center', gap: 8, color: 'var(--color-foreground)', margin: '0 0 12px' } as const

  return (
    <div style={{ ...card, padding: 0 }}>
      {/* En-tête du formulaire */}
      <div style={{ display: 'flex', alignItems: narrow ? 'flex-start' : 'center', justifyContent: 'space-between', gap: 16, padding: '16px 20px', borderBottom: '1px solid var(--color-border)', flexWrap: narrow ? 'wrap' : 'nowrap' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, minWidth: 0 }}>
          <span style={{ display: 'inline-flex', color: 'var(--color-primary)', flexShrink: 0 }}>{isCatalog ? <LayoutIcon /> : <TagIcon />}</span>
          <h2 style={{ fontSize: 17, fontWeight: 700, margin: 0 }}>{headerTitle}</h2>
          {sel.mode === 'new-category' && parentName && <span style={{ fontSize: 13, color: 'var(--color-muted-foreground)' }}>↳ {parentName}</span>}
        </div>
        <div style={{ display: 'flex', gap: 8, alignItems: 'center', flexBasis: narrow ? '100%' : undefined }}>
          <button style={{ ...btnGhost, flex: narrow ? 1 : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap' }} onClick={onClose}>{t('cancel')}</button>
          <button style={{ ...btnPrimary, flex: narrow ? 1 : undefined, minWidth: narrow ? 0 : undefined, justifyContent: narrow ? 'center' : undefined, whiteSpace: narrow ? 'normal' : 'nowrap' }} onClick={submit} disabled={saving || loading}>{saving ? '…' : (isCatalog ? t('save_catalog') : t('save_category'))}</button>
        </div>
      </div>

      <div style={{ padding: '16px 20px' }}>
        <Tabs tabs={TABS} active={tab} onChange={setTab} />

        {formError && <FormErrorBanner title={t('err_required_fields')} issues={formIssues} style={{ marginBottom: 14 }} />}

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

            <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : 'repeat(auto-fit,minmax(320px,1fr))', gap: 24, alignItems: 'start' }}>
              {/* Texts (sélecteur de langue vertical) */}
              <div>
                <h4 style={secTitle}><span style={{ display: 'inline-flex' }}><SettingsIcon /></span> {t('sec_texts')}</h4>
                <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '150px 1fr', gap: 14 }}>
                  <LangSwitcher languages={languages} value={lang} onChange={setLang} narrow={narrow} />
                  {texts.filter((x) => x.langId === lang).map((tx) => (
                    <div key={tx.langId}>
                      <label style={label}>{t('f_name')} *</label>
                      <input style={inputCss} value={tx.name} onChange={(e) => { setText(tx.langId, 'name', e.target.value); setErrName(false); setFormError(false) }} placeholder={t('f_name_ph')} autoComplete="off" />
                      {errName && <p style={{ margin: '4px 0 0', fontSize: 12, color: '#ef4444' }}>{t('err_name')}</p>}
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
                  <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '1fr 1fr', gap: 12 }}>
                    <div>
                      <label style={label}>{t('f_valid_start')}</label>
                      <DatePicker t={t} value={validStart} onChange={setValidStart} />
                    </div>
                    <div>
                      <label style={label}>{t('f_valid_end')}</label>
                      <DatePicker t={t} value={validEnd} onChange={setValidEnd} />
                    </div>
                  </div>
                  <label style={{ ...label, marginTop: 14 }}>{t('f_reference')}</label>
                  <input style={inputCss} value={reference} onChange={(e) => setReference(e.target.value)} placeholder={t('f_reference_ph')} autoComplete="off" />
                </div>
                <div>
                  <h4 style={secTitle}><span style={{ display: 'inline-flex' }}><GlobeIcon /></span> {t('sec_countries')}</h4>
                  {countries.length === 0 ? <p style={{ ...hint, margin: 0 }}>{t('f_no_country')}</p> : (
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 1 }}>
                      {/* Tous les pays : tri-état (tiret si sélection partielle) — design melis-core Users/droits. */}
                      <CheckRow strong state={countriesState} label={t('countries_all')}
                        onChange={(v) => setCountryIds(v ? countries.map((c) => c.id) : [])} />
                      {countries.map((c) => (
                        <CheckRow key={c.id} state={countryIds.includes(c.id) ? 'all' : 'none'} label={c.name}
                          onChange={(v) => setCountryIds((p) => v ? [...new Set([...p, c.id])] : p.filter((x) => x !== c.id))} />
                      ))}
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        ) : tab === 'seo' ? (
          !isEdit ? <p style={{ ...hint }}>{t('save_first_tab')}</p> : (
            <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : '150px 1fr', gap: 14, maxWidth: narrow ? undefined : 760 }}>
              <LangSwitcher languages={languages} value={lang} onChange={setLang} narrow={narrow} />
              {seo.filter((s) => s.langId === lang).map((s) => (
                <div key={s.langId}>
                  <label style={label}>{t('seo_page_id')}</label>
                  <input style={inputCss} value={s.pageId} onChange={(e) => setSeoField(s.langId, 'pageId', e.target.value)} placeholder={t('seo_page_id_ph')} autoComplete="off" />
                  <label style={{ ...label, marginTop: 14 }}>{t('seo_meta_title')}</label>
                  <input style={inputCss} value={s.metaTitle} onChange={(e) => setSeoField(s.langId, 'metaTitle', e.target.value)} placeholder={t('seo_meta_title_ph')} autoComplete="off" />
                  <label style={{ ...label, marginTop: 14 }}>{t('seo_meta_desc')}</label>
                  <textarea style={{ ...inputCss, minHeight: 90, resize: 'vertical', paddingTop: 8 }} value={s.metaDescription} onChange={(e) => setSeoField(s.langId, 'metaDescription', e.target.value)} placeholder={t('seo_meta_desc_ph')} />
                  <label style={{ ...label, marginTop: 14 }}>{t('seo_url')}</label>
                  <input style={inputCss} value={s.url} onChange={(e) => setSeoField(s.langId, 'url', e.target.value)} placeholder={t('seo_url_ph')} autoComplete="off" />
                  <label style={{ ...label, marginTop: 14 }}>{t('seo_url_redirect')}</label>
                  <input style={inputCss} value={s.urlRedirect} onChange={(e) => setSeoField(s.langId, 'urlRedirect', e.target.value)} placeholder={t('seo_url_redirect_ph')} autoComplete="off" />
                  <label style={{ ...label, marginTop: 14 }}>{t('seo_url_301')}</label>
                  <input style={inputCss} value={s.url301} onChange={(e) => setSeoField(s.langId, 'url301', e.target.value)} placeholder={t('seo_url_301_ph')} autoComplete="off" />
                </div>
              ))}
            </div>
          )
        ) : (
          !isEdit ? <p style={{ ...hint }}>{t('save_first_tab')}</p> : (
            <div>
              <h3 style={{ fontSize: 15, fontWeight: 600, margin: '0 0 4px' }}>{t('prod_title')}</h3>
              <p style={{ ...hint, marginTop: 0 }}>{products.length > 0 ? t('prod_reorder_hint') : t('products_n', { n: products.length })}</p>
              {products.length === 0 ? (
                <div style={{ padding: 30, textAlign: 'center', color: 'var(--color-muted-foreground)', border: '1px dashed var(--color-border)', borderRadius: 8 }}>{t('prod_empty')}</div>
              ) : (
                <div style={{ border: '1px solid var(--color-border)', borderRadius: 8, overflow: 'hidden' }}>
                  {/* En-tête */}
                  <div style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '8px 12px', background: 'var(--color-muted,rgba(0,0,0,.03))', fontSize: 11, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '.04em', color: 'var(--color-muted-foreground)' }}>
                    <span style={{ width: 16 }} />
                    <span style={{ width: 50 }}>{t('prod_col_id')}</span>
                    <span style={{ flex: 1 }}>{t('prod_col_ref')}</span>
                    <span style={{ flex: 2 }}>{t('prod_col_name')}</span>
                    <span style={{ width: 90 }}>{t('prod_col_status')}</span>
                  </div>
                  {/* Lignes drag-and-drop — seule la poignée déclenche le drag (pointerdown), pas
                      toute la ligne : sinon un simple scroll tactile sur la ligne serait intercepté. */}
                  {products.map((p) => {
                    const isTarget = dropProd?.id === p.id && dragProd !== p.id
                    return (
                      <div key={p.id} data-prod-id={p.id}
                        style={{
                          display: 'flex', alignItems: 'center', gap: 10, padding: '9px 12px', fontSize: 14,
                          borderTop: '1px solid var(--color-border)', userSelect: 'none', opacity: dragProd === p.id ? 0.4 : 1,
                          background: 'var(--color-card)',
                          boxShadow: isTarget ? (dropProd!.pos === 'before' ? 'inset 0 2px 0 0 var(--color-primary)' : 'inset 0 -2px 0 0 var(--color-primary)') : 'none',
                        }}>
                        <span style={{ display: 'inline-flex', color: 'var(--color-muted-foreground)', opacity: 0.6, cursor: 'grab', touchAction: 'none' }}
                          onPointerDown={(e) => {
                            e.preventDefault()
                            setDragProd(p.id)
                            // Vit pour toute la durée de CE geste (fermé par onHover/onDrop, tous deux
                            // passés une seule fois à startDrag, hors du cycle de rendu React) — lire
                            // `dropProd` (state) au lieu de cette variable locale relirait une closure
                            // périmée, capturée au DÉBUT du drag et jamais recréée entre-temps.
                            let currentDrop: { id: number; pos: 'before' | 'after' } | null = null
                            prodPointerDrag.startDrag(p.id, {
                              onHover: (targetId, el, ev) => {
                                if (targetId === p.id) { currentDrop = null; setDropProd(null); return }
                                const r = el.getBoundingClientRect()
                                currentDrop = { id: targetId, pos: (ev.clientY - r.top) < r.height / 2 ? 'before' : 'after' }
                                setDropProd(currentDrop)
                              },
                              onLeave: () => { currentDrop = null; setDropProd(null) },
                              onDrop: (targetId) => {
                                if (currentDrop && currentDrop.id === targetId) moveProduct(p.id, currentDrop.id, currentDrop.pos)
                                setDragProd(null); setDropProd(null)
                              },
                              onCancel: () => { setDragProd(null); setDropProd(null) },
                            })
                          }}>
                          <GripIcon />
                        </span>
                        <span style={{ width: 50, color: 'var(--color-muted-foreground)' }}>{p.id}</span>
                        <span style={{ flex: 1 }}>{p.reference || '—'}</span>
                        <span style={{ flex: 2, fontWeight: 500 }}>{p.name}</span>
                        <span style={{ width: 90 }}><StatusBadge active={p.status === 1} t={t} /></span>
                      </div>
                    )
                  })}
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
function LangSwitcher({ languages, value, onChange, narrow }: { languages: LangOption[]; value: number; onChange: (id: number) => void; narrow?: boolean }) {
  return (
    <div style={{ display: 'flex', flexDirection: narrow ? 'row' : 'column', flexWrap: narrow ? 'wrap' : 'nowrap', gap: 4 }}>
      {languages.map((l) => {
        const on = l.id === value
        return (
          <button key={l.id} onClick={() => onChange(l.id)}
            style={{ display: 'flex', alignItems: 'center', justifyContent: narrow ? 'center' : 'space-between', gap: 8, padding: '8px 10px', borderRadius: 6, cursor: 'pointer', textAlign: 'left',
              flex: narrow ? '1 1 auto' : undefined,
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
