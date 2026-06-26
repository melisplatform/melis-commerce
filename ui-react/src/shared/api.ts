/**
 * Client API partagé par toutes les briques d'outils MelisCommerce.
 *
 * Toutes les routes REST passent par le module partagé MelisReactApi
 * (`/melis/react-api/...`) avec le contrat `{ success, data, error }`. Une brique
 * ne peut PAS importer les modules de l'hôte (`@/lib/...`) — ce helper est autonome.
 */
const XHR_HEADER = { 'X-Requested-With': 'XMLHttpRequest' } as const

export async function apiFetch<T>(url: string, opts?: RequestInit): Promise<T> {
  const res = await fetch(url, {
    ...opts,
    headers: { ...XHR_HEADER, ...(opts?.headers ?? {}) },
    credentials: 'include',
  })
  if (!res.ok) {
    let msg = `HTTP ${res.status}`
    try {
      const d = (await res.json()) as { error?: string }
      if (d.error) msg = d.error
    } catch { /* ignore */ }
    throw new Error(msg)
  }
  const data = (await res.json()) as { success: boolean; data?: T; error?: string }
  if (!data.success) throw new Error(data.error ?? 'API error')
  return data.data as T
}

export interface ListResult<T> { items: T[]; total: number; page: number; limit: number }
export interface Stats { total: number; active: number; inactive: number }
export interface Option { id: number; name: string }
