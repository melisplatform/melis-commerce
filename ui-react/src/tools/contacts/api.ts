import { apiFetch, type ListResult, type Stats, type Option } from '../../shared/api'
import type { Address, AddressOptions } from '../../shared/address'

export interface ContactItem {
  id: number; status: number; type: string
  civility: number; civilityName: string
  firstname: string; name: string; middleName: string
  langId: number; email: string
  accountId: number; accountName: string
  jobTitle: string; jobService: string
  telMobile: string; telLandline: string
  tags: string; dateCreation: string | null; dateEdit: string | null
}
export type ContactStats = Stats
// Langue commerce avec drapeau (data URI PNG) pour le sélecteur.
export interface LangOption { id: number; name: string; flag?: string | null }
export interface ContactOptions { accounts: Option[]; civilities: Option[]; languages: LangOption[] }
export interface ContactSavePayload {
  id?: number | null; status: boolean; type: string; civility: number
  firstname: string; name: string; middleName: string; langId: number; email: string
  accountId: number; jobTitle: string; jobService: string
  telMobile: string; telLandline: string; password: string; tags: string
}

export function fetchContacts(params: { search?: string; status?: number | null; accountId?: number | null } = {}): Promise<ListResult<ContactItem>> {
  const qs = new URLSearchParams()
  qs.set('limit', '9999')
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.accountId) qs.set('accountId', String(params.accountId))
  return apiFetch<ListResult<ContactItem>>(`/melis/react-api/contacts?${qs}`)
}
export const fetchContactById = (id: number) => apiFetch<ContactItem>(`/melis/react-api/contacts/${id}`)
export const fetchContactStats = () => apiFetch<ContactStats>('/melis/react-api/contacts/stats')
export const fetchContactOptions = () => apiFetch<ContactOptions>('/melis/react-api/contacts/options')
export const saveContact = (payload: ContactSavePayload) =>
  apiFetch<{ id: number }>('/melis/react-api/contacts/save', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) })
export const deleteContact = (id: number) => apiFetch<null>(`/melis/react-api/contacts/delete/${id}`, { method: 'DELETE' })

// ── Onglets de la fiche contact (sous-ressources) ──────────────────────────────
export type { Address as ContactAddress, AddressOptions } from '../../shared/address'
export interface ContactAssociation { id: number; name: string; status: number; isDefaultAccount: number; isMain: number }

export const fetchContactAddresses = (id: number) => apiFetch<{ items: Address[] }>(`/melis/react-api/contacts/${id}/addresses`)
export const fetchContactAddressOptions = () => apiFetch<AddressOptions>('/melis/react-api/contacts/address-options')
export const saveContactAddresses = (id: number, items: Address[]) =>
  apiFetch<null>(`/melis/react-api/contacts/${id}/addresses/save`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ items }) })
export const fetchContactAssociations = (id: number) => apiFetch<{ items: ContactAssociation[] }>(`/melis/react-api/contacts/${id}/associations`)
export const linkContactAssociation = (contactId: number, accountId: number) =>
  apiFetch<null>(`/melis/react-api/contacts/${contactId}/associations/link`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ accountId }) })
export const unlinkContactAssociation = (contactId: number, accountId: number) =>
  apiFetch<null>(`/melis/react-api/contacts/${contactId}/associations/${accountId}`, { method: 'DELETE' })
export const setContactAssociationDefault = (contactId: number, accountId: number) =>
  apiFetch<null>(`/melis/react-api/contacts/${contactId}/associations/${accountId}/default`, { method: 'POST' })
