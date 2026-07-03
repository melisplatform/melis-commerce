/**
 * Pont vers la vérification CENTRALE des droits de l'hôte (MelisCore) — voir
 * melis-core `ui-react/src/lib/caps.ts`, exposée par `main.tsx` sur `window.__melisUseCaps`
 * (même mécanisme que `__melisIsModuleActive` / `MelisReact`).
 *
 * La brique NE réimplémente PAS la logique (fetch /me, default-allow, cache) : elle délègue à
 * l'hôte. Un module a juste besoin (1) de déclarer son `config/react.capabilities.php` (le
 * rights-tree + les caps de l'user sont gérés automatiquement par le core) et (2) d'appeler
 * `can()` sur ses boutons/onglets.
 *
 * Repli (`can` = tout permis) uniquement en dev standalone (`npm run dev` sans l'hôte).
 */
export interface CapsApi { can: (cap: string) => boolean; loaded: boolean }

const host = window as unknown as { __melisUseCaps?: (melisKey: string) => CapsApi }

export function useCaps(melisKey: string): CapsApi {
  // `__melisUseCaps` est TOUJOURS présent dans le BO réel (posé par l'hôte avant le montage des
  // briques) → l'appel du hook de l'hôte est stable (rules-of-hooks respectées). Absent seulement
  // en dev standalone, où l'on renvoie un « tout permis » constant.
  if (host.__melisUseCaps) return host.__melisUseCaps(melisKey)
  return { can: () => true, loaded: true }
}
