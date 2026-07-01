/**
 * Pont vers la barre de sous-onglets in-tool de l'hôte (← Back / enregistrement),
 * exposée sur `window` par MelisCore (cf. SubTabProvider). Permet à une brique
 * liste+form d'avoir le MÊME comportement que l'outil natif Users.
 *
 * Le manifeste de la brique doit porter `"subtabs": true` pour que l'hôte
 * regroupe le formulaire sous l'onglet principal (au lieu d'un 2ᵉ onglet haut).
 */
interface SubTabWindow {
  __melisOpenSubTab?: (section: string, tab: { id: string; label: string; path: string }) => void
  __melisCloseSubTab?: (section: string, id: string) => void
  __melisUpdateSubTabLabel?: (section: string, id: string, label: string) => void
}
const w = () => window as unknown as SubTabWindow

export const openSubTab = (section: string, tab: { id: string; label: string; path: string }) => w().__melisOpenSubTab?.(section, tab)
export const closeSubTab = (section: string, id: string) => w().__melisCloseSubTab?.(section, id)
export const updateSubLabel = (section: string, id: string, label: string) => w().__melisUpdateSubTabLabel?.(section, id, label)
