import { apiFetch, type ListResult, type Stats, type Option } from '../../shared/api'

export interface AccountItem {
  id: number; status: number; name: string
  groupId: number; groupName: string
  countryId: number; countryName: string
  // Colonnes alignées sur la liste legacy des comptes.
  companyName: string; contactName: string; numOrders: number; lastOrder: string | null
  tags: string; dateCreation: string | null; dateEdit: string | null
}
export type AccountStats = Stats
export type AccountNameMode = 'manual_input' | 'company_name' | 'contact_name'
// Pays avec drapeau (data URI PNG) pour le sélecteur.
export interface CountryOption { id: number; name: string; flag?: string | null }
export interface AccountOptions { groups: Option[]; countries: CountryOption[]; accountNameMode: AccountNameMode }
export interface AccountSavePayload { id?: number | null; name: string; status: boolean; groupId: number; countryId: number; tags: string; contactId?: number }

export type AccountSortKey = 'id' | 'status' | 'group' | 'name' | 'contact' | 'company' | 'created'
export interface AccountListResult { items: AccountItem[]; total: number; nextCursor: string | null }

export function fetchAccounts(
  params: { search?: string; status?: number | null; groupId?: number | null
            limit?: number; sort?: AccountSortKey; dir?: 'asc' | 'desc'; after?: string | null } = {},
): Promise<AccountListResult> {
  const qs = new URLSearchParams()
  if (params.limit) qs.set('limit', String(params.limit))
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.groupId) qs.set('groupId', String(params.groupId))
  if (params.sort) qs.set('sort', params.sort)
  if (params.dir) qs.set('dir', params.dir)
  if (params.after) qs.set('after', params.after)
  return apiFetch<AccountListResult>(`/melis/react-api/accounts?${qs}`)
}

/** Tous les comptes filtrés (export) via boucle de curseur keyset. */
export async function fetchAllAccounts(
  params: { search?: string; status?: number | null; groupId?: number | null } = {},
): Promise<AccountItem[]> {
  const all: AccountItem[] = []
  let after: string | null = null
  do {
    const r: AccountListResult = await fetchAccounts({ ...params, limit: 200, after })
    all.push(...r.items)
    after = r.nextCursor
  } while (after)
  return all
}
export const fetchAccountById = (id: number) => apiFetch<AccountItem>(`/melis/react-api/accounts/${id}`)
export const fetchAccountStats = () => apiFetch<AccountStats>('/melis/react-api/accounts/stats')
export const fetchAccountOptions = () => apiFetch<AccountOptions>('/melis/react-api/accounts/options')
export const saveAccount = (payload: AccountSavePayload) =>
  apiFetch<{ id: number }>('/melis/react-api/accounts/save', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) })
export const deleteAccount = (id: number) => apiFetch<null>(`/melis/react-api/accounts/delete/${id}`, { method: 'DELETE' })

// ── Import CSV (bouton « Importer » de la liste) ────────────────────────────────
export interface AccountImportResult { valid?: boolean; imported?: boolean; errors: string[] }
export const testImportAccounts = (file: File) => {
  const fd = new FormData(); fd.append('account_file', file)
  return apiFetch<AccountImportResult>('/melis/react-api/accounts/import-test', { method: 'POST', body: fd })
}
export const importAccounts = (file: File) => {
  const fd = new FormData(); fd.append('account_file', file)
  return apiFetch<AccountImportResult>('/melis/react-api/accounts/import', { method: 'POST', body: fd })
}
export const ACCOUNT_IMPORT_TEMPLATE_URL = '/melis/download-account-template'

// ── Onglets de la fiche compte (sous-ressources) ────────────────────────────────
export interface CompanyData {
  id: number; name: string; numberId: string; vatNumber: string; group: string; employees: number
  compCreationDate: string
  addNumber: string; addStreet: string; addBuilding: string; addZip: string; addCity: string; addState: string; addCountry: string
  phone: string; website: string; logo: string
}
export interface AccountContact { id: number; status: number; firstname: string; name: string; email: string; isMain: number; isDefaultAccount: number; civility?: number; civilityName?: string }
export type { Address as AccountAddress, AddressOptions } from '../../shared/address'
export interface AccountOrder { id: number; reference: string; status: number; statusName: string; dateCreation: string | null }
export interface AccountFile { id: number; name: string; path: string; typeId: number }

export const fetchCompany = (id: number) => apiFetch<CompanyData>(`/melis/react-api/accounts/${id}/company`)
export const saveCompany = (id: number, payload: Omit<CompanyData, 'id'>) =>
  apiFetch<null>(`/melis/react-api/accounts/${id}/company/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) })
export const fetchAccountContacts = (id: number) => apiFetch<{ items: AccountContact[] }>(`/melis/react-api/accounts/${id}/contacts`)
export const fetchAccountAddresses = (id: number) => apiFetch<{ items: AccountAddress[] }>(`/melis/react-api/accounts/${id}/addresses`)
export const fetchAddressOptions = () => apiFetch<AddressOptions>('/melis/react-api/accounts/address-options')
export const saveAccountAddresses = (id: number, items: AccountAddress[]) =>
  apiFetch<null>(`/melis/react-api/accounts/${id}/addresses/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ items }) })
export type AccountOrderSortKey = 'id' | 'ref' | 'status' | 'date'
export const fetchAccountOrders = (
  id: number,
  params: { limit?: number; sort?: AccountOrderSortKey; dir?: 'asc' | 'desc'; after?: string | null; search?: string; status?: number | null; from?: string; to?: string } = {},
) => {
  const qs = new URLSearchParams()
  if (params.limit) qs.set('limit', String(params.limit))
  if (params.sort) qs.set('sort', params.sort)
  if (params.dir) qs.set('dir', params.dir)
  if (params.after) qs.set('after', params.after)
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.from) qs.set('from', params.from)
  if (params.to) qs.set('to', params.to)
  const q = qs.toString()
  return apiFetch<{ items: AccountOrder[]; total: number; nextCursor: string | null }>(`/melis/react-api/accounts/${id}/orders${q ? `?${q}` : ''}`)
}
export const fetchOrderStatuses = () => apiFetch<Option[]>('/melis/react-api/accounts/order-statuses')
export const fetchAccountFiles = (id: number) => apiFetch<{ items: AccountFile[] }>(`/melis/react-api/accounts/${id}/files`)
export interface FileOptions { types: Option[]; countries: Option[] }
export const fetchFileOptions = () => apiFetch<FileOptions>('/melis/react-api/accounts/file-options')
export const createFileType = (code: string, name: string) =>
  apiFetch<Option>('/melis/react-api/accounts/file-types', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ code, name }) })
export const uploadAccountFile = (id: number, file: File, meta: { name: string; typeId: number; countryId: number }) => {
  const fd = new FormData()
  fd.append('file', file); fd.append('name', meta.name); fd.append('typeId', String(meta.typeId)); fd.append('countryId', String(meta.countryId))
  return apiFetch<AccountFile>(`/melis/react-api/accounts/${id}/files/upload`, { method: 'POST', body: fd })
}
export const deleteAccountFile = (id: number, fileId: number) =>
  apiFetch<null>(`/melis/react-api/accounts/${id}/files/${fileId}`, { method: 'DELETE' })

// Contacts liés (onglet Contacts) — picker + lier/délier
export const fetchAllContactOptions = () => apiFetch<Option[]>('/melis/react-api/accounts/contact-options')
export const linkAccountContact = (accountId: number, contactId: number) =>
  apiFetch<null>(`/melis/react-api/accounts/${accountId}/contacts/link`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ contactId }) })
export const unlinkAccountContact = (accountId: number, contactId: number) =>
  apiFetch<null>(`/melis/react-api/accounts/${accountId}/contacts/${contactId}`, { method: 'DELETE' })
export const setAccountContactDefault = (accountId: number, contactId: number) =>
  apiFetch<null>(`/melis/react-api/accounts/${accountId}/contacts/${contactId}/default`, { method: 'POST' })
