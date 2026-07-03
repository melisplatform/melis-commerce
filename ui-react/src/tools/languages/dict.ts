import { mergeDict } from '../../shared/i18n'

/** Dictionnaire de l'outil Langues Commerce (fusionné avec les clés COMMUNES). */
export const DICT = mergeDict({
  fr: {
    title: 'Langues Commerce',
    subtitle: 'Retrouvez ici les différentes langues de la partie commerce. Vous pouvez activer/désactiver, ajouter ou supprimer des langues selon les besoins de vos sites.',
    new: 'Ajouter une langue', search_placeholder: 'Rechercher une langue…',
    filter_status: 'Tous les statuts',
    btn_export: 'Exporter', btn_col_manager: 'Colonnes',
    exp_title: 'Exporter les langues', exp_subtitle: '{n} langues seront exportées', exp_filename: 'langues-commerce',
    del_title: 'Supprimer la langue', del_confirm: 'Êtes-vous sûr de vouloir supprimer cette langue ?',
    col_id: 'ID', col_flag: 'Drapeau', col_status: 'Statut', col_locale: 'Locale', col_name: 'Nom', col_action: 'Action',
    no_items: 'Aucune langue trouvée.',
    field_name: 'Nom', field_locale: 'Locale', field_flag: 'Drapeau',
    err_name_required: 'Veuillez saisir le nom de la langue', err_locale_required: 'La locale est vide', err_locale_exists: 'Cette locale existe déjà',
    flag_current: 'Drapeau existant', flag_none: 'Aucun drapeau', flag_replace: 'Remplacer le drapeau',
  },
  en: {
    title: 'Commerce languages',
    subtitle: 'Find here the different languages of the commerce part. You can activate/deactivate, add or delete languages depending on the needs of your sites.',
    new: 'Add language', search_placeholder: 'Search a language…',
    filter_status: 'All statuses',
    btn_export: 'Export', btn_col_manager: 'Columns',
    exp_title: 'Export languages', exp_subtitle: '{n} languages will be exported', exp_filename: 'commerce-languages',
    del_title: 'Delete language', del_confirm: 'Are you sure you want to delete this language?',
    col_id: 'ID', col_flag: 'Flag', col_status: 'Status', col_locale: 'Locale', col_name: 'Name', col_action: 'Action',
    no_items: 'No language found.',
    field_name: 'Name', field_locale: 'Locale', field_flag: 'Flag',
    err_name_required: 'Please enter the language name', err_locale_required: 'Locale is empty', err_locale_exists: 'Language locale already exists',
    flag_current: 'Existing flag', flag_none: 'No flag', flag_replace: 'Replace flag',
  },
})
