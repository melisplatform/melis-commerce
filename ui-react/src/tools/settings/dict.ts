import { mergeDict } from '../../shared/i18n'

/** Dictionnaire de l'outil Paramètres commerce (fusionné avec les clés COMMUNES). */
export const DICT = mergeDict({
  fr: {
    title: 'Paramètres commerce', subtitle: 'Configurez ici les propriétés générales de la partie commerce.',
    tab_main: 'Propriétés', tab_accounts: 'Comptes',
    section_stock_alert: 'Alerte de stock',
    field_stock_level: 'Seuil de stock', tip_stock_level: 'Quantité de stock pour chaque variant à partir duquel un email d\'alerte est envoyé aux destinataires définis.',
    section_recipients: 'Destinataires', recipients_empty: 'Aucun destinataire sélectionné.', recipients_add: 'Ajouter un destinataire',
    pick_user: '— Choisir un utilisateur —',
    section_account_name: 'Nom de compte',
    field_account_type: 'Nom de compte',
    account_type_manual_input: 'Saisie manuelle', account_type_company_name: 'Nom de la société', account_type_contact_name: 'Nom du contact',
    lock_checkbox: 'Cocher pour modifier',
    lock_warning: 'Attention, la modification de ces options peut avoir des conséquences critiques et irréversibles sur les comptes du module commerce.',
    err_email_invalid: 'Adresse email invalide : {u}',
    saved: 'Enregistré ✓',
  },
  en: {
    title: 'Commerce settings', subtitle: 'Configure here the general properties of the commerce part.',
    tab_main: 'Properties', tab_accounts: 'Accounts',
    section_stock_alert: 'Stock alert',
    field_stock_level: 'Stock alert', tip_stock_level: 'Stock quantity for each variant from which an alert email is sent to the defined recipient(s).',
    section_recipients: 'Recipients', recipients_empty: 'No recipients selected.', recipients_add: 'Add recipient',
    pick_user: '— Pick a user —',
    section_account_name: 'Account name',
    field_account_type: 'Account name',
    account_type_manual_input: 'Manual input', account_type_company_name: 'Company name', account_type_contact_name: 'Contact name',
    lock_checkbox: 'Check to edit',
    lock_warning: 'Warning, editing these options can have irreversible critical consequences on the accounts of the commerce module.',
    err_email_invalid: 'Invalid email address: {u}',
    saved: 'Saved ✓',
  },
})
