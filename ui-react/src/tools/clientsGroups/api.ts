import { apiFetch, type ListResult } from '../../shared/api'

export interface ClientsGroupItem {
  id: number
  name: string
  status: number
  dateCreation: string | null
}

export interface ClientsGroupStats { total: number; active: number; inactive: number }

export type ClientsGroupSortKey = 'id' | 'name' | 'status'
export interface ClientsGroupListResult { items: ClientsGroupItem[]; total: number; nextCursor: string | null }

export function fetchClientsGroups(params: {
  search?: string; status?: number | null; limit?: number; sort?: ClientsGroupSortKey; dir?: 'asc' | 'desc'; after?: string | null
} = {}): Promise<ClientsGroupListResult> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.limit) qs.set('limit', String(params.limit))
  if (params.sort) qs.set('sort', params.sort)
  if (params.dir) qs.set('dir', params.dir)
  if (params.after) qs.set('after', params.after)
  return apiFetch<ClientsGroupListResult>(`/melis/react-api/clients-groups?${qs}`)
}

/** Tous les éléments filtrés (export) via boucle de curseur keyset. */
export async function fetchAllClientsGroups(params: { search?: string; status?: number | null } = {}): Promise<ClientsGroupItem[]> {
  const all: ClientsGroupItem[] = []
  let after: string | null = null
  do {
    const rr: ClientsGroupListResult = await fetchClientsGroups({ ...params, limit: 200, after })
    all.push(...rr.items)
    after = rr.nextCursor
  } while (after)
  return all
}

export const fetchClientsGroupStats = () => apiFetch<ClientsGroupStats>('/melis/react-api/clients-groups/stats')
export const fetchClientsGroupById   = (id: number) => apiFetch<ClientsGroupItem>(`/melis/react-api/clients-groups/${id}`)

export const saveClientsGroup = (payload: { id?: number; name: string; status: boolean }) =>
  apiFetch<{ id: number }>('/melis/react-api/clients-groups/save', {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const deleteClientsGroup = (id: number) =>
  apiFetch<null>(`/melis/react-api/clients-groups/delete/${id}`, { method: 'DELETE' })
