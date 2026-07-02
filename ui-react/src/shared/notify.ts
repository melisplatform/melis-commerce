/**
 * Poste une notification vers le système de toasts global de l'hôte (Shell →
 * <Notifications/>, melis-core) — le même mécanisme que les outils natifs (Users, etc.),
 * affiché en haut de l'écran par-dessus tout, plutôt qu'un popup propre à la brique.
 */
export function notify(kind: 'ok' | 'ko', title: string, message: string) {
  window.postMessage({ __melisNotif: true, kind, title, message }, window.location.origin)
}
