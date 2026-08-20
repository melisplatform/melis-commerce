import { useEffect, useState } from 'react'
import type { ComponentType } from 'react'

/**
 * Générique : lit un composant exposé par une AUTRE brique React sur `window`, une fois
 * son script chargé (les briques partagent la même instance React côté hôte — cf. CLAUDE.md
 * § Modularité). Ne rend rien tant que la brique cible n'est pas chargée (ex : module inactif
 * ou non installé) — melis-commerce reste 100% agnostique du contenu de cette brique, il ne
 * référence qu'un nom de global + une clé.
 */
export function useExternalBrickComponent<P extends object>(globalName: string, key: string): ComponentType<P> | null {
  return useExternalBrickValue<ComponentType<P>>(globalName, key)
}

/**
 * Générique : lit N'IMPORTE QUELLE valeur (pas seulement un composant) exposée par une autre
 * brique sur `window[globalName][key]` — ex. le libellé de l'onglet qu'elle apporte, pour que
 * le module hôte n'ait pas à dupliquer ce texte dans son propre dictionnaire (cf. CategoryDiscountTab).
 */
export function useExternalBrickValue<T>(globalName: string, key: string): T | null {
  const read = () => ((window as unknown as Record<string, Record<string, unknown>>)[globalName]?.[key] as T) ?? null
  const [value, setValue] = useState<T | null>(read)

  useEffect(() => {
    if (value) return
    const id = window.setInterval(() => {
      const found = read()
      if (found) { setValue(() => found); window.clearInterval(id) }
    }, 300)
    return () => window.clearInterval(id)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [value])

  return value
}
