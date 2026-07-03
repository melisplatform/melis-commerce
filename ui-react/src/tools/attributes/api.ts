import { apiFetch, type ListResult } from '../../shared/api'

export interface AttributeItem {
  id: number; reference: string; name: string
  typeId: number; typeName: string
  status: number; visible: number; searchable: number
  valuesCount: number
}

export interface AttributeStats { total: number; active: number; inactive: number }

export interface LangOption { id: number; name: string; flag: string }
export interface AttributeType { id: number; name: string; columnValue: string }
export interface AttributeOptions { languages: LangOption[]; types: AttributeType[] }

export interface AttributeTranslation { langId: number; name: string; description: string }

export interface AttributeDetail {
  id: number; reference: string; typeId: number
  status: number; visible: number; searchable: number
  translations: AttributeTranslation[]
  valuesCount: number
}

export type AttributeListResult = ListResult<AttributeItem>

/** Valeur typée d'une traduction de valeur : string (varchar/text/datetime), number (int/float), boolean (bool), ou null (binary / vide). */
export type AttributeValueRaw = string | number | boolean | null

export interface AttributeValueTranslation { avtId: number; langId: number; value: AttributeValueRaw; hasFile: boolean | null }
export interface AttributeValueItem { id: number; reference: string; translations: AttributeValueTranslation[]; displayValue: AttributeValueRaw }
export interface AttributeValuesResult { items: AttributeValueItem[]; typeColumn: string }

export function fetchAttributes(params: {
  search?: string; status?: number | null; typeId?: number | null; page?: number; limit?: number
} = {}): Promise<AttributeListResult> {
  const qs = new URLSearchParams()
  if (params.search) qs.set('search', params.search)
  if (params.status !== undefined && params.status !== null) qs.set('status', String(params.status))
  if (params.typeId !== undefined && params.typeId !== null) qs.set('typeId', String(params.typeId))
  qs.set('page', String(params.page ?? 1))
  qs.set('limit', String(params.limit ?? 50))
  return apiFetch<AttributeListResult>(`/melis/react-api/attributes?${qs}`)
}

export const fetchAttributeStats   = () => apiFetch<AttributeStats>('/melis/react-api/attributes/stats')
export const fetchAttributeOptions = () => apiFetch<AttributeOptions>('/melis/react-api/attributes/options')
export const fetchAttributeById    = (id: number) => apiFetch<AttributeDetail>(`/melis/react-api/attributes/${id}`)

export const saveAttribute = (payload: {
  id?: number; reference: string; typeId: number
  status: boolean; visible: boolean; searchable: boolean
  translations: AttributeTranslation[]
}) =>
  apiFetch<{ id: number }>('/melis/react-api/attributes/save', {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const deleteAttribute = (id: number) =>
  apiFetch<null>(`/melis/react-api/attributes/delete/${id}`, { method: 'DELETE' })

export const fetchAttributeValues = (attributeId: number) =>
  apiFetch<AttributeValuesResult>(`/melis/react-api/attributes/${attributeId}/values`)

export const saveAttributeValue = (attributeId: number, payload: {
  id?: number; reference: string
  translations: { langId: number; value: AttributeValueRaw }[]
}) =>
  apiFetch<{ id: number }>(`/melis/react-api/attributes/${attributeId}/values/save`, {
    method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload),
  })

export const deleteAttributeValue = (attributeId: number, valueId: number) =>
  apiFetch<null>(`/melis/react-api/attributes/${attributeId}/values/${valueId}`, { method: 'DELETE' })

export const attributeValueBinaryUrl = (attributeId: number, valueId: number, langId: number) =>
  `/melis/react-api/attributes/${attributeId}/values/${valueId}/binary?langId=${langId}`
