/**
 * Cache mémoire au niveau du MODULE (survit au démontage du composant) pour les pages de
 * liste MelisCommerce. Le brick hôte (Shell.tsx) démonte un outil non actif — sans cache, revenir
 * sur son onglet reparcourt tout (fetch + spinner), ce qui se voit comme un "reload" au clic sur
 * l'onglet. `makeCache()` est appelé une seule fois, à l'évaluation du module (import), donc
 * l'instance retournée est un singleton qui survit à tous les cycles montage/démontage de l'outil.
 *
 * Usage : initialiser chaque `useState` depuis `cache.get()?.champ`, puis à chaque rendu tenir un
 * `useRef` à jour avec le dernier snapshot, et à l'unmount écrire ce ref dans le cache — cf.
 * pattern éprouvé de `melis-core/ui-react/src/pages/UserListPage.tsx`. Le fetch initial n'est PAS
 * sauté (la donnée cachée s'affiche instantanément puis se rafraîchit en tâche de fond).
 */
export function makeCache<T>() {
  let value: T | null = null
  return {
    get: (): T | null => value,
    set: (v: T) => { value = v },
    clear: () => { value = null },
  }
}
