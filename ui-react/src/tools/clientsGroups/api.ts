import { apiFetch, type ListResult } from '../../shared/api'

export interface ClientsGroupItem {
  id: number
  name: string
  status: number
  dateCreation: string | null
}

export interface ClientsGroupStats { total: number; active: number; inactive: number }

export type ClientsGroupListResult = ListResult<ClientsGroupItem>

export function fetchClientsGroups(params: {
  search?: string; status?: number | null; page?: number; limit?: number
} = {}): Promise<ClientsGroupListResult> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  qs.set('page', String(params.page ?? 1))
  qs.set('limit', String(params.limit ?? 50))
  return apiFetch<ClientsGroupListResult>(`/melis/react-api/clients-groups?${qs}`)
}

export const fetchClientsGroupStats = () => apiFetch<ClientsGroupStats>('/melis/react-api/clients-groups/stats')
export const fetchClientsGroupById   = (id: number) => apiFetch<ClientsGroupItem>(`/melis/react-api/clients-groups/${id}`)

export const saveClientsGroup = (payload: { id?: number; name: string; status: boolean }) =>
  apiFetch<{ id: number }>('/melis/react-api/clients-groups/save', {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const deleteClientsGroup = (id: number) =>
  apiFetch<null>(`/melis/react-api/clients-groups/delete/${id}`, { method: 'DELETE' })
