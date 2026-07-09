/**
 * i18n autonome pour les briques MelisCommerce.
 *
 * Le back-office suit la locale de session ; l'hôte la pose sur <html lang>.
 * Chaque outil fournit son dictionnaire spécifique, fusionné avec les clés COMMUNES.
 */
export type Lang = 'fr' | 'en'
export type Dict = Record<Lang, Record<string, string>>
export type T = (key: string, vars?: Record<string, string | number>) => string

export function currentLang(): Lang {
  const l = (document.documentElement.lang || 'fr').toLowerCase()
  return l.startsWith('fr') ? 'fr' : 'en'
}

/** Clés partagées par tous les outils (liste, formulaire, export, colonnes…). */
export const COMMON: Dict = {
  fr: {
    cancel: 'Annuler', save: 'Enregistrer', loading: 'Chargement…', back: 'retour', refresh: 'Rafraîchir',
    edit: 'Modifier', del: 'Supprimer', saved: 'Enregistré ✓', deleted: 'Supprimé avec succès',
    action: 'Action', unlink: 'Délier', linked: 'Lié ✓', set_default: 'Définir par défaut',
    tbl_show: 'Afficher', tbl_add: 'Ajouter', prev: 'Précédent', next: 'Suivant', showing: '{a}–{b} sur {n}',
    dr_label: 'Date', dr_all: 'Toutes les dates', dr_today: "Aujourd'hui", dr_yesterday: 'Hier',
    dr_last7: '7 derniers jours', dr_last30: '30 derniers jours', dr_thismonth: 'Ce mois-ci', dr_lastmonth: 'Le mois dernier',
    dr_custom: 'Plage personnalisée', dr_from: 'Du', dr_to: 'Au', dr_apply: 'Appliquer',
    ad_section: 'Adresses', ad_add: 'Ajouter une adresse', ad_new_title: 'Nouvelle adresse', ad_empty: 'Aucune adresse',
    ad_name: "Nom de l'adresse", ad_type: "Type d'adresse", ad_civility: 'Civilité', ad_civ_ph: '— Civilité —',
    ad_firstname: 'Prénom', ad_lastname: 'Nom', ad_middlename: 'Deuxième prénom',
    ad_num: 'N° de rue', ad_street: 'Rue', ad_building: 'Bâtiment', ad_zip: 'Code postal', ad_city: 'Ville',
    ad_state: 'État/Région', ad_country: 'Pays', ad_phone: 'Mobile',
    ad_err_name: "Le nom de l'adresse est obligatoire.", ad_del_title: "Supprimer l'adresse", ad_del_confirm: 'Supprimer « {u} » ?',
    view_new: 'New', view_old: 'Old',
    search: 'Rechercher…', empty: 'Aucun résultat',
    kpi_total: 'Total', kpi_active: 'Actifs', kpi_inactive: 'Inactifs',
    status_active: 'Actif', status_inactive: 'Inactif',
    f_all: 'Tous', f_active: 'Actifs', f_inactive: 'Inactifs',
    columns: 'Colonnes', cols_visible: 'Visibles', cols_hidden: 'Masquées', drag_here: 'Glisser ici', reset: 'Réinitialiser',
    export: 'Exporter', exp_excluded: 'Exclues', exp_included: 'Incluses',
    exp_download: 'Télécharger {fmt}', exp_exporting: 'Export…',
    del_cancel: 'Annuler',
  },
  en: {
    cancel: 'Cancel', save: 'Save', loading: 'Loading…', back: 'back', refresh: 'Refresh',
    edit: 'Edit', del: 'Delete', saved: 'Saved ✓', deleted: 'Successfully deleted',
    action: 'Action', unlink: 'Unlink', linked: 'Linked ✓', set_default: 'Set as default',
    tbl_show: 'Show', tbl_add: 'Add', prev: 'Previous', next: 'Next', showing: '{a}–{b} of {n}',
    dr_label: 'Date', dr_all: 'All dates', dr_today: 'Today', dr_yesterday: 'Yesterday',
    dr_last7: 'Last 7 days', dr_last30: 'Last 30 days', dr_thismonth: 'This month', dr_lastmonth: 'Last month',
    dr_custom: 'Custom range', dr_from: 'From', dr_to: 'To', dr_apply: 'Apply',
    ad_section: 'Addresses', ad_add: 'Add address', ad_new_title: 'New address', ad_empty: 'No address',
    ad_name: 'Address name', ad_type: 'Address type', ad_civility: 'Civility', ad_civ_ph: '— Civility —',
    ad_firstname: 'First name', ad_lastname: 'Last name', ad_middlename: 'Middle name',
    ad_num: 'Street number', ad_street: 'Street name', ad_building: 'Building', ad_zip: 'Zip code', ad_city: 'City',
    ad_state: 'State/Region', ad_country: 'Country', ad_phone: 'Mobile',
    ad_err_name: 'Address name is required.', ad_del_title: 'Delete address', ad_del_confirm: 'Delete “{u}”?',
    view_new: 'New', view_old: 'Old',
    search: 'Search…', empty: 'No result',
    kpi_total: 'Total', kpi_active: 'Active', kpi_inactive: 'Inactive',
    status_active: 'Active', status_inactive: 'Inactive',
    f_all: 'All', f_active: 'Active', f_inactive: 'Inactive',
    columns: 'Columns', cols_visible: 'Visible', cols_hidden: 'Hidden', drag_here: 'Drag here', reset: 'Reset',
    export: 'Export', exp_excluded: 'Excluded', exp_included: 'Included',
    exp_download: 'Download {fmt}', exp_exporting: 'Exporting…',
    del_cancel: 'Cancel',
  },
}

/** Fusionne un dico d'outil avec les clés communes. */
export function mergeDict(tool: Dict): Dict {
  return { fr: { ...COMMON.fr, ...tool.fr }, en: { ...COMMON.en, ...tool.en } }
}

/** Construit la fonction de traduction pour le dico (déjà fusionné). */
export function makeT(dict: Dict): T {
  const lang = currentLang()
  return (key, vars) => {
    let s = dict[lang][key] ?? key
    if (vars) for (const [k, v] of Object.entries(vars)) s = s.replaceAll(`{${k}}`, String(v))
    return s
  }
}

export function fmtDate(value: string | null | undefined): string {
  if (!value) return '—'
  const d = new Date(value.replace(' ', 'T'))
  if (isNaN(d.getTime())) return '—'
  return d.toLocaleDateString(currentLang() === 'fr' ? 'fr-FR' : 'en-GB', { year: 'numeric', month: 'short', day: '2-digit' })
}
