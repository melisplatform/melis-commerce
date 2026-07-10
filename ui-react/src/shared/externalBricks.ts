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
  const read = () => ((window as unknown as Record<string, Record<string, unknown>>)[globalName]?.[key] as ComponentType<P>) ?? null
  const [component, setComponent] = useState<ComponentType<P> | null>(read)

  useEffect(() => {
    if (component) return
    const id = window.setInterval(() => {
      const found = read()
      if (found) { setComponent(() => found); window.clearInterval(id) }
    }, 300)
    return () => window.clearInterval(id)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [component])

  return component
}
