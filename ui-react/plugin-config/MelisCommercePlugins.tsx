// Full-React config TABS for the MelisCommerce front plugins. Source lives in melis-commerce (this module),
// imported into melis-cms's SPA build and registered into the shared tab registry (PluginFormKit).
// Each tab reads/writes the shared values via ctx; one Save posts everything to the plugin's
// savePluginConfigToXml() (byte-compatible XML). Mirrors each plugin's legacy `modal_form`
// (config/plugins/**/<Plugin>.php): same tabs, same field names, same labels/tooltips.
import { useEffect, useMemo, useState, type CSSProperties, type ReactNode } from 'react'
import {
  registerPluginTab, type PluginTabContext,
  Field, CheckBox, inputStyle, TemplateField, TextField, RemoteSelectField, SwitchField, readTag, fetchFieldOptions, usePrefill,
} from '../../../melis-cms/ui-react/src/PluginFormKit'
import { peLang } from '../../../melis-cms/ui-react/src/page-editor-i18n'
import { fetchTreeNodes, type MelisTreeNode } from '../../../melis-cms/ui-react/src/cms-tree-api'

const L = ({
  fr: {
    tabProperties: 'Propriétés',
    tabConfiguration: 'Configuration',
    tabTemplate: 'Template',
    tabPagination: 'Pagination',
    tabPrice: 'Prix',
    tabCategories: 'Catalogues / Catégories',
    tabProducts: 'Produits',
    tabProductSearch: 'Recherche de produits',
    tabAttributes: 'Attributs',
    hintTemplate: 'Le template permet de choisir le rendu du plugin en fonction du site.',
    labelSite: 'Site',
    hintSite: 'ID du site utilisé pour identifier où le checkout est conduit.',
    labelCountry: 'Pays',
    hintPriceCountry: 'Choisissez le pays pour assigner la devise et le prix correspondant des produits.',
    hintStockCountry: 'Obtient le prix et le stock du produit en fonction du pays choisi.',
    labelDestinationPage: 'Page de destination',
    hintDestinationPage: "Saisissez l'url de la page de destination sur laquelle l'utilisateur sera redirigé. Ce peut être une url interne ou externe.",
    labelResetPage: 'Page de réinitialisation du mot de passe',
    hintResetPage: "Page sur laquelle sera redirigé l'utilisateur pour réinitialiser son mot de passe.",
    labelAutologin: 'Connexion automatique',
    hintAutologin: "Connecte l'utilisateur directement après la validation du formulaire.",
    labelSelectAddress: "Champ de sélection d'adresse",
    hintSelectAddress: 'Affiche un champ dans le formulaire pour sélectionner une adresse enregistrée.',
    labelSameAddress: 'Utiliser la même adresse',
    hintSameAddress: "Spécifier si l'on doit utiliser la même adresse ou non.",
    labelMultipleCoupons: 'Multiple coupons',
    hintMultipleCoupons: 'Autorise plusieurs coupons.',
    labelStep: 'Étape',
    hintStep: 'Processus étape par étape de la commande.',
    labelPerPage: 'Résultats par page',
    hintPerPage: "Nombre de résultats s'affichant sur une même page.",
    labelBeforeAfter: 'Nombre de liens avant et après la page courante',
    hintBeforeAfter: "L'affichage de la pagination génère un certain nombre de liens avant et après la page en cours, ce champ permet de limiter ce nombre de liens.",
    labelSortBy: 'Tri',
    labelOrder: 'Ordre',
    hintCatSort: 'Choisissez le tri que vous souhaitez appliquer.',
    hintPrdSort: 'Ce filtre permet de trier les produits par défaut.',
    hintOrder: "Ce filtre permet de régler l'ordre par défaut (ex : pour un titre, « Ascendant » trie de A à Z, « Descendant » trie de Z à A).",
    hintOrderHistory: "Ce filtre permet de définir l'ordre par défaut.",
    labelLimit: 'Limite',
    hintLimit: 'Ce filtre permet de régler le nombre de produits listés.',
    labelProductId: 'ID du produit',
    hintProductId: "L'identifiant du produit à afficher.",
    labelAttribute: 'Attribut',
    hintAttribute: 'Attribut du produit à afficher.',
    labelPriceColumn: 'Colonne des prix',
    hintPriceColumn: 'Détermine le prix à afficher.',
    labelPriceMin: 'Prix min',
    hintPriceMin: 'Prix minimum à afficher.',
    labelPriceMax: 'Prix max',
    hintPriceMax: 'Prix maximum à afficher.',
    labelDefaultProduct: 'Produit par défaut',
    hintDefaultProduct: 'Choisissez un produit pour afficher ses produits associés.',
    labelIncludeSub: 'Inclure les sous-catégories de produits',
    hintIncludeSub: 'Inclut les produits des sous-catégories.',
    labelIncludeRoot: 'Inclure la catégorie ou le catalogue parent',
    hintIncludeRoot: 'Inclut la catégorie / le catalogue parent dans la liste.',
    labelCategories: 'Catégories',
    hintCategories: 'Les catalogues / catégories dont les produits sont listés.',
    hintTreeCategories: 'Cochez les catégories à afficher ; l’étoile désigne la catégorie / le catalogue racine de l’arbre.',
    labelSearch: 'Recherche',
    hintSearch: 'Filtre texte appliqué aux produits (facultatif).',
    labelTextTypes: 'Types de texte',
    hintTextTypes: 'Les textes du produit dans lesquels la recherche est faite.',
    hintAttributes: 'Les valeurs d’attributs utilisées pour filtrer les produits.',
    labelPageCurrent: 'Numéro de page par défaut',
    hintPageCurrent: "Page de résultat par défaut sur laquelle arrive l'utilisateur.",
    labelNbPerPage: 'Nombre de produits',
    hintNbPerPage: 'Nombre de produits à afficher par page.',
    searchPlaceholder: 'Rechercher…',
    expandAll: 'Tout déplier',
    collapseAll: 'Tout replier',
    rootTitle: 'Définir comme racine',
    loading: 'Chargement…',
    loadError: 'Impossible de charger la liste.',
    nothing: 'Aucun élément.',
    choosePage: 'Choisir une page',
    pageLinkError: 'Impossible de récupérer le lien de la page.',
  },
  en: {
    tabProperties: 'Properties',
    tabConfiguration: 'Configuration',
    tabTemplate: 'Template',
    tabPagination: 'Pagination',
    tabPrice: 'Price',
    tabCategories: 'Catalogs / Categories',
    tabProducts: 'Products',
    tabProductSearch: 'Product search',
    tabAttributes: 'Attributes',
    hintTemplate: 'The template allows to choose the rendering of the plugin according to the site.',
    labelSite: 'Site',
    hintSite: 'Site id used to identify where the checkout is conducted.',
    labelCountry: 'Country',
    hintPriceCountry: 'Select the country to assign the corresponding currency and price of the products.',
    hintStockCountry: 'Get the product price and stock based on the selected country.',
    labelDestinationPage: 'Destination page',
    hintDestinationPage: 'Enter the url of the destination page the user will be redirected to. It can be an internal or external url.',
    labelResetPage: 'Password reset page',
    hintResetPage: 'The page where the user will be redirected to reset the password.',
    labelAutologin: 'Auto login',
    hintAutologin: 'Connect the user directly after validation of the form.',
    labelSelectAddress: 'Select address field',
    hintSelectAddress: 'Display a field in the form to select a registered address.',
    labelSameAddress: 'Use same address',
    hintSameAddress: 'Specify whether to use the same address.',
    labelMultipleCoupons: 'Multiple coupons',
    hintMultipleCoupons: 'Allow several coupons.',
    labelStep: 'Step',
    hintStep: 'Checkout step by step process.',
    labelPerPage: 'Number per page',
    hintPerPage: 'Number of results displayed on a single page.',
    labelBeforeAfter: 'No. of pagination links before & after',
    hintBeforeAfter: 'The pagination generates a number of links before and after the current page; this field limits that number of links.',
    labelSortBy: 'Sort by',
    labelOrder: 'Order',
    hintCatSort: 'This filter allows to sort the categories by default.',
    hintPrdSort: 'This filter allows to sort the products by default.',
    hintOrder: 'This filter sets the default order (ex: for a title, "Ascending" sorts from A to Z, "Descending" from Z to A).',
    hintOrderHistory: 'This filter allows to set the default sorting.',
    labelLimit: 'Limit',
    hintLimit: 'This filter sets the number of products listed.',
    labelProductId: 'Product Id',
    hintProductId: 'The ID number of the product to be displayed.',
    labelAttribute: 'Attribute',
    hintAttribute: 'Product attribute to display.',
    labelPriceColumn: 'Price Column',
    hintPriceColumn: 'Determine the price to display.',
    labelPriceMin: 'Min Price',
    hintPriceMin: 'Minimum price to display.',
    labelPriceMax: 'Max Price',
    hintPriceMax: 'Maximum price to display.',
    labelDefaultProduct: 'Default product',
    hintDefaultProduct: 'Select a product to display its related products.',
    labelIncludeSub: 'Include Sub Category Products',
    hintIncludeSub: 'Include the products of the sub categories.',
    labelIncludeRoot: 'Include Parent Category / Catalog',
    hintIncludeRoot: 'Include parent category / catalog in the list.',
    labelCategories: 'Categories',
    hintCategories: 'The catalogs / categories whose products are listed.',
    hintTreeCategories: 'Check the categories to display; the star sets the root category / catalog of the tree.',
    labelSearch: 'Search',
    hintSearch: 'Text filter applied to the products (optional).',
    labelTextTypes: 'Text types',
    hintTextTypes: 'The product texts the search is made in.',
    hintAttributes: 'The attribute values used to filter the products.',
    labelPageCurrent: 'Default page number',
    hintPageCurrent: 'Default result page the user lands on.',
    labelNbPerPage: 'Number of product',
    hintNbPerPage: 'Number of product to list in a page.',
    searchPlaceholder: 'Search…',
    expandAll: 'Expand all',
    collapseAll: 'Collapse all',
    rootTitle: 'Set as root',
    loading: 'Loading…',
    loadError: 'The list could not be loaded.',
    nothing: 'Nothing to select.',
    choosePage: 'Choose a page',
    pageLinkError: 'The page link could not be retrieved.',
  },
} as const)[peLang()]

const COG = 'fa fa-cogs', FORWARD = 'fa fa-forward', MONEY = 'fa fa-money', BOOK = 'fa fa-book', SEARCH = 'fa fa-search', CUBES = 'fa fa-cubes', SHIP = 'fa fa-truck'

/* ── Commerce data (same endpoints as the commerce back-office tools) ─────── */
type CatNode = { id: number; name: string; type: 'catalog' | 'category'; status: number; productCount: number; children: CatNode[] }
type Option = { value: string; label: string }
type OptionGroup = { title: string; options: Option[] }

async function apiGet<T>(url: string): Promise<T> {
  const r = await fetch(url, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
  const res = await r.json().catch(() => ({}))
  if (!r.ok || !res?.success) throw new Error(res?.error || `HTTP ${r.status}`)
  return res.data as T
}
/** GET a back-office JSON endpoint that answers its payload directly (no success/data envelope). */
async function apiGetRaw<T>(url: string): Promise<T> {
  const r = await fetch(url, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
  if (!r.ok) throw new Error(`HTTP ${r.status}`)
  return (await r.json()) as T
}
const once = <T,>(load: () => Promise<T>) => { let p: Promise<T> | null = null; return () => (p ??= load().catch((e) => { p = null; throw e })) }

const loadCatalogTree = once(() => apiGet<{ items: CatNode[] }>('/melis/react-api/catalog/tree').then((d) => d.items || []))
const loadTextTypes = once(() => apiGet<{ code: string; name: string }[]>('/melis/react-api/products/text-types')
  .then((types): OptionGroup[] => [{ title: '', options: types.map((t) => ({ value: t.code, label: t.name })) }]))
// Same selection as the legacy tab (getAttributeListAndValues(null, status, searchable)): active + searchable attributes.
const loadAttributeValues = once(async (): Promise<OptionGroup[]> => {
  const list = await apiGet<{ items: { id: number; name: string; status: number; searchable: number }[] }>('/melis/react-api/attributes?limit=200')
  const attrs = (list.items || []).filter((a) => a.status === 1 && a.searchable === 1)
  return Promise.all(attrs.map(async (a) => {
    const vals = await apiGet<{ items: { id: number; displayValue: unknown }[] }>(`/melis/react-api/attributes/${a.id}/values`)
    return { title: a.name, options: (vals.items || []).map((v) => ({ value: String(v.id), label: String(v.displayValue ?? v.id) })) }
  }))
})

/** Prefill a LIST field once: the page XML holds it as JSON (`["2","3"]`), the server-resolved form as the
 *  `<name>_list` companion input (which also carries hardcoded template values). */
function usePrefillList(ctx: PluginTabContext, name: string) {
  useEffect(() => {
    let cancelled = false
    try {
      const raw = JSON.parse(readTag(ctx.props.rawXml, name) || 'null')
      if (Array.isArray(raw)) ctx.setValue(name, raw.map(String))
    } catch { /* not JSON: keep server value below */ }
    fetchFieldOptions({ idPage: ctx.props.idPage, module: ctx.props.module, pluginName: ctx.props.pluginName, pluginId: ctx.props.pluginId, hc: ctx.props.hc }).then((o) => {
      const list = o.fieldValues[`${name}_list`]
      if (cancelled || list === undefined) return
      ctx.setValue(name, list.split(',').map((s) => s.trim()).filter(Boolean))
    })
    return () => { cancelled = true }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])
}

/** Prefill a scalar value set by a custom widget (hidden input in the legacy form). */
function usePrefillHidden(ctx: PluginTabContext, name: string | undefined) {
  useEffect(() => {
    if (!name) return
    let cancelled = false
    const raw = readTag(ctx.props.rawXml, name).trim()
    if (raw) ctx.setValue(name, raw)
    fetchFieldOptions({ idPage: ctx.props.idPage, module: ctx.props.module, pluginName: ctx.props.pluginName, pluginId: ctx.props.pluginId, hc: ctx.props.hc }).then((o) => {
      const v = (o.fieldValues[name] ?? '').trim()
      if (!cancelled && v) ctx.setValue(name, v)
    })
    return () => { cancelled = true }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])
}

function useAsync<T>(load: () => Promise<T>) {
  const [state, setState] = useState<{ data?: T; error?: boolean }>({})
  useEffect(() => {
    let cancelled = false
    load().then((data) => { if (!cancelled) setState({ data }) }, () => { if (!cancelled) setState({ error: true }) })
    return () => { cancelled = true }
  }, [load])
  return state
}

const muted: CSSProperties = { fontSize: 12, color: 'var(--color-muted-foreground,#6b7280)' }
const boxStyle: CSSProperties = { border: '1px solid var(--color-border,#e5e7eb)', borderRadius: 6, padding: 8, maxHeight: 320, overflowY: 'auto' }
const linkBtn: CSSProperties = { background: 'none', border: 'none', padding: 0, cursor: 'pointer', fontSize: 12, color: 'var(--color-primary,#2563eb)' }

/**
 * Catalog / category tree with checkboxes (replaces the legacy jstree tab). Writes the checked ids as an
 * ARRAY into ctx[name]; with `rootName`, a star per node sets the tree's root (scalar ctx[rootName]),
 * like the legacy "highlighted" node of the category tree plugin.
 */
function CategoryTreeField({ ctx, name, rootName, label, hint }: { ctx: PluginTabContext; name: string; rootName?: string; label: string; hint?: string }) {
  usePrefillList(ctx, name)
  usePrefillHidden(ctx, rootName)
  const { data: tree, error } = useAsync(loadCatalogTree)
  const [open, setOpen] = useState<Record<number, boolean>>({})
  const [search, setSearch] = useState('')
  const checked = new Set(ctx.valueList(name))
  const root = rootName ? ctx.value(rootName) : ''

  // With a search, keep matching nodes and their ancestors (all expanded).
  const visible = useMemo(() => {
    const q = search.trim().toLowerCase()
    if (!q || !tree) return null
    const keep = new Set<number>()
    const walk = (n: CatNode): boolean => {
      const childHit = n.children.map(walk).some(Boolean)
      const hit = childHit || n.name.toLowerCase().includes(q)
      if (hit) keep.add(n.id)
      return hit
    }
    tree.forEach(walk)
    return keep
  }, [search, tree])

  const toggle = (id: number, on: boolean) => {
    const next = new Set(checked)
    if (on) next.add(String(id)); else next.delete(String(id))
    ctx.setValue(name, [...next])
  }
  const setAll = (on: boolean) => {
    const all: Record<number, boolean> = {}
    const walk = (n: CatNode) => { all[n.id] = on; n.children.forEach(walk) }
    ;(tree || []).forEach(walk)
    setOpen(all)
  }

  const renderNode = (n: CatNode, depth: number): ReactNode => {
    if (visible && !visible.has(n.id)) return null
    const isOpen = visible ? true : open[n.id] ?? depth === 0
    return (
      <div key={n.id}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 6, padding: '2px 0', paddingLeft: depth * 18, opacity: n.status ? 1 : 0.6 }}>
          <button type="button" style={{ ...linkBtn, width: 14, color: 'inherit', visibility: n.children.length ? 'visible' : 'hidden' }}
            onClick={() => setOpen((o) => ({ ...o, [n.id]: !isOpen }))}>
            <i className={`fa fa-caret-${isOpen ? 'down' : 'right'}`} />
          </button>
          <CheckBox checked={checked.has(String(n.id))} onChange={(v) => toggle(n.id, v)}
            label={<span>{n.type === 'catalog' ? <i className="fa fa-book" style={{ marginRight: 4 }} /> : null}{n.name}{n.productCount ? <span style={muted}> ({n.productCount})</span> : null}</span>} />
          {rootName ? (
            <button type="button" title={L.rootTitle} style={{ ...linkBtn, marginLeft: 'auto', color: root === String(n.id) ? '#f59e0b' : 'var(--color-muted-foreground,#9ca3af)' }}
              onClick={() => ctx.setValue(rootName, root === String(n.id) ? '' : String(n.id))}>
              <i className={`fa fa-star${root === String(n.id) ? '' : '-o'}`} />
            </button>
          ) : null}
        </div>
        {isOpen ? n.children.map((c) => renderNode(c, depth + 1)) : null}
      </div>
    )
  }

  return (
    <Field label={label} hint={hint} error={ctx.error(name)}>
      <div style={{ display: 'flex', gap: 8, alignItems: 'center', marginBottom: 6 }}>
        <input style={{ ...inputStyle, flex: 1 }} value={search} placeholder={L.searchPlaceholder} onChange={(e) => setSearch(e.target.value)} />
        <button type="button" style={linkBtn} onClick={() => setAll(true)}>{L.expandAll}</button>
        <button type="button" style={linkBtn} onClick={() => setAll(false)}>{L.collapseAll}</button>
      </div>
      <div style={boxStyle}>
        {error ? <div style={muted}>{L.loadError}</div> : !tree ? <div style={muted}>{L.loading}</div>
          : tree.length ? tree.map((n) => renderNode(n, 0)) : <div style={muted}>{L.nothing}</div>}
      </div>
    </Field>
  )
}

/** Grouped checkbox list (text types, attribute values) writing the checked values as an ARRAY into ctx[name]. */
function CheckListField({ ctx, name, label, hint, load }: { ctx: PluginTabContext; name: string; label: string; hint?: string; load: () => Promise<OptionGroup[]> }) {
  usePrefillList(ctx, name)
  const { data: groups, error } = useAsync(load)
  const checked = new Set(ctx.valueList(name))
  const toggle = (v: string, on: boolean) => {
    const next = new Set(checked)
    if (on) next.add(v); else next.delete(v)
    ctx.setValue(name, [...next])
  }
  return (
    <Field label={label} hint={hint} error={ctx.error(name)}>
      <div style={boxStyle}>
        {error ? <div style={muted}>{L.loadError}</div> : !groups ? <div style={muted}>{L.loading}</div>
          : !groups.some((g) => g.options.length) ? <div style={muted}>{L.nothing}</div>
          : groups.map((g) => (
            <div key={g.title} style={{ marginBottom: 8 }}>
              {g.title ? <div style={{ fontWeight: 600, fontSize: 13, marginBottom: 4 }}>{g.title}</div> : null}
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(140px, 1fr))', gap: 4 }}>
                {g.options.map((o) => <CheckBox key={o.value} checked={checked.has(o.value)} label={o.label} onChange={(on) => toggle(o.value, on)} />)}
              </div>
            </div>
          ))}
      </div>
    </Field>
  )
}

/** One page of the site tree, children loaded when expanded (same endpoint as the shared PagePicker). */
function PageTreeNode({ node, depth, onPick }: { node: MelisTreeNode; depth: number; onPick: (id: number) => void }) {
  const [open, setOpen] = useState(false)
  const [children, setChildren] = useState<MelisTreeNode[] | null>(null)
  const toggleOpen = async () => {
    if (!node.lazy) return
    setOpen(!open)
    if (!open && children === null) setChildren(await fetchTreeNodes(node.key))
  }
  return (
    <div>
      <div style={{ display: 'flex', alignItems: 'center', gap: 4, paddingLeft: depth * 16 }}>
        <button type="button" style={{ ...linkBtn, width: 16, color: 'inherit', visibility: node.lazy ? 'visible' : 'hidden' }} onClick={toggleOpen}>
          <i className={`fa fa-caret-${open ? 'down' : 'right'}`} />
        </button>
        <button type="button" style={{ ...linkBtn, color: 'inherit', fontSize: 13, textAlign: 'left', padding: '2px 0' }} onClick={() => onPick(node.key)}>{node.title}</button>
      </div>
      {open ? (children === null ? <div style={{ ...muted, paddingLeft: depth * 16 + 20 }}>{L.loading}</div>
        : children.map((c) => <PageTreeNode key={c.key} node={c} depth={depth + 1} onPick={onPick} />)) : null}
    </div>
  )
}

/**
 * Destination link field (legacy: text input + site tree button, `generatePageLink` callback). Stores a URL:
 * typed (internal or external) or the link of a page picked in the site tree. The tree opens INSIDE the form,
 * not as a floating dropdown, so the config modal grows with it instead of cutting it.
 */
function PageLinkField({ ctx, name, label, hint }: { ctx: PluginTabContext; name: string; label: string; hint?: string }) {
  usePrefill(ctx, name)
  const [open, setOpen] = useState(false)
  const [roots, setRoots] = useState<MelisTreeNode[] | null>(null)
  const [linkError, setLinkError] = useState(false)

  const toggleTree = async () => {
    setOpen(!open)
    if (!open && roots === null) setRoots(await fetchTreeNodes(-1))
  }
  const pick = async (pageId: number) => {
    setLinkError(false)
    try {
      const link = (await apiGetRaw<{ link?: string }>(`/melis/MelisCms/Page/getPageLink?idPage=${pageId}`)).link
      if (!link) throw new Error('no link')
      ctx.setValue(name, link)
      setOpen(false)
    } catch {
      setLinkError(true)
    }
  }

  return (
    <Field label={label} hint={hint} error={ctx.error(name) ?? (linkError ? L.pageLinkError : undefined)}>
      <div style={{ display: 'flex', gap: 6 }}>
        <input style={{ ...inputStyle, flex: 1 }} value={ctx.value(name)} onChange={(e) => ctx.setValue(name, e.target.value)} />
        <button type="button" title={L.choosePage} style={{ ...inputStyle, width: 'auto', cursor: 'pointer', padding: '0 12px' }} onClick={toggleTree}>
          <i className="fa fa-sitemap" />
        </button>
      </div>
      {open ? (
        <div style={{ ...boxStyle, marginTop: 6 }}>
          {roots === null ? <div style={muted}>{L.loading}</div>
            : roots.length ? roots.map((n) => <PageTreeNode key={n.key} node={n} depth={0} onPick={pick} />)
            : <div style={muted}>{L.nothing}</div>}
        </div>
      ) : null}
    </Field>
  )
}

/* ── Field building blocks (one per legacy element kind) ─────────────────── */
type FieldSpec =
  | { kind: 'template' }
  | { kind: 'select' | 'text' | 'number' | 'switch' | 'link'; name: string; label: string; hint?: string }
  | { kind: 'categories'; name: string; rootName?: string; label: string; hint?: string }
  | { kind: 'checklist'; name: string; label: string; hint?: string; load: () => Promise<OptionGroup[]> }

const template: FieldSpec = { kind: 'template' }
const select = (name: string, label: string, hint?: string): FieldSpec => ({ kind: 'select', name, label, hint })
const text = (name: string, label: string, hint?: string): FieldSpec => ({ kind: 'text', name, label, hint })
const number = (name: string, label: string, hint?: string): FieldSpec => ({ kind: 'number', name, label, hint })
const toggle = (name: string, label: string, hint?: string): FieldSpec => ({ kind: 'switch', name, label, hint })

/** A tab component rendering the given fields in order. Plain selects (site, countries, attributes,
 *  products, sort…) are RemoteSelectFields: their options come from the plugin's own legacy form factory. */
function tabOf(fields: FieldSpec[]) {
  return function CommercePluginTab({ ctx }: { ctx: PluginTabContext }) {
    return (<div>
      {fields.map((f) => {
        switch (f.kind) {
          case 'template': return <TemplateField key="template_path" ctx={ctx} hint={L.hintTemplate} />
          case 'select': return <RemoteSelectField key={f.name} ctx={ctx} name={f.name} label={f.label} hint={f.hint} />
          case 'switch': return <SwitchField key={f.name} ctx={ctx} name={f.name} label={f.label} hint={f.hint} />
          case 'link': return <PageLinkField key={f.name} ctx={ctx} name={f.name} label={f.label} hint={f.hint} />
          case 'categories': return <CategoryTreeField key={f.name} ctx={ctx} name={f.name} rootName={f.rootName} label={f.label} hint={f.hint} />
          case 'checklist': return <CheckListField key={f.name} ctx={ctx} name={f.name} label={f.label} hint={f.hint} load={f.load} />
          default: return <TextField key={f.name} ctx={ctx} name={f.name} label={f.label} hint={f.hint} type={f.kind === 'number' ? 'number' : 'text'} />
        }
      })}
    </div>)
  }
}

/* Shared field groups */
const site = (name: string) => select(name, L.labelSite, L.hintSite)
const priceCountry = (name: string) => select(name, L.labelCountry, L.hintPriceCountry)
const pageLink = (name: string, label: string, hint?: string): FieldSpec => ({ kind: 'link', name, label, hint })
const destinationPage = (name: string) => pageLink(name, L.labelDestinationPage, L.hintDestinationPage)
const order = (name: string) => select(name, L.labelOrder, L.hintOrder)
const pagination = (perPage: string, beforeAfter: string) => [
  number(perPage, L.labelPerPage, L.hintPerPage),
  number(beforeAfter, L.labelBeforeAfter, L.hintBeforeAfter),
]

type TabDef = { id: string; title: string; icon: string; fields: FieldSpec[] }
const tab = (id: string, title: string, icon: string, fields: FieldSpec[]): TabDef => ({ id, title, icon, fields })
const properties = (...fields: FieldSpec[]) => tab('properties', L.tabProperties, COG, [template, ...fields])
const configuration = (...fields: FieldSpec[]) => tab('configuration', L.tabConfiguration, COG, [template, ...fields])
const paginationTab = (perPage: string, beforeAfter: string) => tab('pagination', L.tabPagination, FORWARD, pagination(perPage, beforeAfter))

/** Per plugin: its tabs, in the legacy modal order. */
const PLUGINS: Record<string, TabDef[]> = {
  /* ── Clients ── */
  MelisCommerceAccountPlugin: [properties()],
  MelisCommerceProfilePlugin: [properties()],
  MelisCommerceLoginPlugin: [properties(destinationPage('m_redirection_link_ok'))],
  MelisCommerceRegisterPlugin: [properties(
    destinationPage('m_redirection_link_ok'),
    toggle('m_autologin', L.labelAutologin, L.hintAutologin),
  )],
  MelisCommerceLostPasswordGetEmailPlugin: [properties(pageLink('lost_password_reset_page_link', L.labelResetPage, L.hintResetPage))],
  MelisCommerceLostPasswordResetPlugin: [properties(
    destinationPage('m_redirection_link_ok'),
    toggle('m_autologin', L.labelAutologin, L.hintAutologin),
  )],
  MelisCommerceBillingAddressPlugin: [properties(toggle('show_select_address_data', L.labelSelectAddress, L.hintSelectAddress))],
  MelisCommerceDeliveryAddressPlugin: [properties(toggle('show_select_address_data', L.labelSelectAddress, L.hintSelectAddress))],

  /* ── Cart & checkout ── */
  MelisCommerceAddToCartPlugin: [properties()],
  MelisCommerceCartPlugin: [configuration(), paginationTab('cart_per_page', 'cart_nb_page_before_after')],
  MelisCommerceCheckoutPlugin: [properties(
    select('m_checkout_step', L.labelStep, L.hintStep),
    priceCountry('m_checkout_country_id'),
    site('m_checkout_site_id'),
    destinationPage('m_checkout_page_link'),
  )],
  MelisCommerceCheckoutCartPlugin: [properties(priceCountry('m_cc_country_id'), site('m_cc_site_id'))],
  MelisCommerceCheckoutAddressesPlugin: [properties(
    site('m_add_site_id'),
    toggle('m_add_use_same_address', L.labelSameAddress, L.hintSameAddress),
  )],
  MelisCommerceCheckoutSummaryPlugin: [properties(site('m_summary_site_id'))],
  MelisCommerceCheckoutConfirmSummaryPlugin: [properties(site('m_conf_summary_site_id'))],
  MelisCommerceCheckoutConfirmPlugin: [properties()],
  MelisCommerceCheckoutCouponPlugin: [properties(
    site('m_coupon_site_id'),
    toggle('m_coupon_multiple', L.labelMultipleCoupons, L.hintMultipleCoupons),
  )],

  /* ── Orders ── */
  MelisCommerceOrderPlugin: [properties()],
  MelisCommerceOrderAddressPlugin: [properties()],
  MelisCommerceOrderMessagesPlugin: [properties()],
  MelisCommerceOrderReturnProductPlugin: [properties()],
  MelisCommerceOrderShippingDetailsPlugin: [properties()],
  MelisCommerceOrderHistoryPlugin: [
    configuration(select('m_order_sort', L.labelOrder, L.hintOrderHistory)),
    paginationTab('order_history_per_page', 'order_history_page_before_after'),
  ],

  /* ── Categories ── */
  MelisCommerceCategoryProductListPlugin: [
    tab('template', L.tabTemplate, COG, [template]),
    tab('categories', L.tabCategories, BOOK, [
      { kind: 'categories', name: 'm_category_ids', label: L.labelCategories, hint: L.hintCategories },
      toggle('m_include_sub_category_products', L.labelIncludeSub, L.hintIncludeSub),
      select('m_cat_col_name', L.labelSortBy, L.hintCatSort),
      order('m_cat_order'),
    ]),
    tab('products', L.tabProducts, SHIP, [
      priceCountry('m_country_id'),
      select('m_prd_col_name', L.labelSortBy, L.hintPrdSort),
      order('m_prd_order'),
      number('m_prd_limit', L.labelLimit, L.hintLimit),
    ]),
  ],
  MelisCommerceCategoryTreePlugin: [
    tab('template', L.tabTemplate, COG, [template]),
    tab('categories', L.tabCategories, BOOK, [
      { kind: 'categories', name: 'm_box_category_tree_ids_selected', rootName: 'm_box_root_category_tree_id', label: L.labelCategories, hint: L.hintTreeCategories },
      toggle('m_box_include_root_category_tree', L.labelIncludeRoot, L.hintIncludeRoot),
    ]),
  ],

  /* ── Products ── */
  MelisCommerceProductListPlugin: [
    configuration(
      select('m_col_name', L.labelSortBy, L.hintPrdSort),
      order('m_order'),
      select('m_box_product_country', L.labelCountry, L.hintStockCountry),
    ),
    tab('price', L.tabPrice, MONEY, [
      select('m_box_filter_price_column', L.labelPriceColumn, L.hintPriceColumn),
      number('m_box_product_price_min', L.labelPriceMin, L.hintPriceMin),
      number('m_box_product_price_max', L.labelPriceMax, L.hintPriceMax),
    ]),
    tab('categories', L.tabCategories, BOOK, [
      { kind: 'categories', name: 'm_box_category_tree_ids_selected', label: L.labelCategories, hint: L.hintCategories },
    ]),
    tab('search', L.tabProductSearch, SEARCH, [
      text('m_box_product_search', L.labelSearch, L.hintSearch),
      { kind: 'checklist', name: 'm_box_product_field_type', label: L.labelTextTypes, hint: L.hintTextTypes, load: loadTextTypes },
    ]),
    tab('attributes', L.tabAttributes, CUBES, [
      { kind: 'checklist', name: 'm_box_product_attribute_values_ids_selected', label: L.tabAttributes, hint: L.hintAttributes, load: loadAttributeValues },
    ]),
    tab('pagination', L.tabPagination, FORWARD, [
      number('m_page_current', L.labelPageCurrent, L.hintPageCurrent),
      number('m_page_nb_per_page', L.labelNbPerPage, L.hintNbPerPage),
      number('m_page_nb_page_before_after', L.labelBeforeAfter, L.hintBeforeAfter),
    ]),
  ],
  MelisCommerceProductShowPlugin: [properties(
    number('m_product_id', L.labelProductId, L.hintProductId),
    priceCountry('m_product_country'),
  )],
  MelisCommerceAttributesShowPlugin: [properties(number('m_product_id', L.labelProductId, L.hintProductId))],
  MelisCommerceProductAttributePlugin: [properties(select('attribute_id', L.labelAttribute, L.hintAttribute))],
  MelisCommerceRelatedProductsPlugin: [configuration(select('m_product_id', L.labelDefaultProduct, L.hintDefaultProduct))],
  MelisCommerceProductPriceRangePlugin: [configuration(select('m_box_product_price_column', L.labelPriceColumn, L.hintPriceColumn))],
  MelisCommerceProductSearchPlugin: [configuration()],
}

/** Register the MelisCommerce plugins' native config tab(s). Called from melis-cms's PluginForms registry. */
export function registerMelisCommercePlugins(): void {
  for (const [pluginName, tabs] of Object.entries(PLUGINS)) {
    tabs.forEach((t, i) => {
      registerPluginTab(pluginName, { id: t.id, title: t.title, icon: t.icon, order: i, Component: tabOf(t.fields) })
    })
  }
}
