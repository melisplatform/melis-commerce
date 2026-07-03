<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour l'outil Groupes de clients MelisCommerce.
 *
 * Un groupe de clients (melis_ecom_client_groups) est une entité minimale : nom + statut.
 * Les clients sont associés à un groupe depuis l'outil Clients (hors périmètre ici). Le groupe
 * #1 ("General") est le groupe par défaut utilisé plateforme-wide quand un client n'est
 * assigné à aucun autre groupe : il ne peut jamais être supprimé (règle métier appliquée
 * côté serveur ici — le legacy ne faisait que masquer le bouton côté JS).
 *
 * Routes :
 *   GET    /melis/react-api/clients-groups             → liste paginée + filtres (search, status)
 *   GET    /melis/react-api/clients-groups/stats         → KPI (total/active/inactive)
 *   GET    /melis/react-api/clients-groups/:id           → détail
 *   POST   /melis/react-api/clients-groups/save          → créer / mettre à jour
 *   DELETE /melis/react-api/clients-groups/delete/:id     → supprimer (sauf id=1)
 *
 * Écriture : réutilise MelisComClientGroupsService::saveClientsGroup/deleteClientsGroupById
 * (legacy) — ce service applique htmlentities() sur cgroup_name à l'enregistrement, on
 * applique donc html_entity_decode() en lecture pour renvoyer un texte brut au front React
 * (évite un double-encodage visible dans l'UI). Sur une mise à jour, on repasse explicitement
 * la cgroup_date_creation existante pour empêcher le service de la réinitialiser à now()
 * (il ne le fait que si la clé est absente du tableau).
 */
class MelisComReactApiClientsGroupController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_clients_group_tool_container';

    /** Le groupe #1 ("General") est le groupe par défaut : jamais supprimable. */
    private const GENERAL_GROUP_ID = 1;

    private function formatGroup(array $r): array
    {
        return [
            'id' => (int) $r['cgroup_id'],
            'name' => html_entity_decode((string) ($r['cgroup_name'] ?? ''), ENT_QUOTES),
            'status' => (int) ($r['cgroup_status'] ?? 0),
            'dateCreation' => $r['cgroup_date_creation'] ?? null,
        ];
    }

    // ─── GET /clients-groups ────────────────────────────────────────────────────
    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $limit  = min(9999, max(1, (int) $this->params()->fromQuery('limit', 50)));
            $page   = max(1, (int) $this->params()->fromQuery('page', 1));
            $search = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawSt  = $this->params()->fromQuery('status', '');
            $status = ($rawSt !== '' && $rawSt !== null) ? (int) $rawSt : null;

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $where = []; $params = [];
            if ($status !== null) { $where[] = 'cgroup_status = ?'; $params[] = $status; }
            if ($search !== '') { $where[] = 'cgroup_name LIKE ?'; $params[] = '%' . $search . '%'; }
            $wc = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $total = (int) ((array) iterator_to_array($db->query("SELECT COUNT(*) AS t FROM melis_ecom_client_groups $wc", $params))[0])['t'];

            $offset = ($page - 1) * $limit;
            $rows = $db->query(
                "SELECT * FROM melis_ecom_client_groups $wc ORDER BY cgroup_id DESC LIMIT ? OFFSET ?",
                array_merge($params, [$limit, $offset])
            );

            $items = [];
            foreach ($rows as $r) { $items[] = $this->formatGroup((array) $r); }

            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items, 'total' => $total, 'page' => $page, 'limit' => $limit]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /clients-groups/stats ──────────────────────────────────────────────
    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = (array) iterator_to_array($db->query(
                'SELECT COUNT(*) AS total, SUM(cgroup_status=1) AS active, SUM(cgroup_status=0) AS inactive FROM melis_ecom_client_groups', []
            ))[0];
            return $this->jsonResponse(['success' => true, 'data' => [
                'total' => (int) ($row['total'] ?? 0), 'active' => (int) ($row['active'] ?? 0), 'inactive' => (int) ($row['inactive'] ?? 0),
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /clients-groups/:id ─────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT * FROM melis_ecom_client_groups WHERE cgroup_id = ? LIMIT 1', [$id]));
            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Group not found'], 404); }
            return $this->jsonResponse(['success' => true, 'data' => $this->formatGroup((array) $rows[0])]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /clients-groups/save ───────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b  = json_decode((string) $this->getRequest()->getContent(), true) ?? [];
            $id = (int) ($b['id'] ?? 0);

            $name = trim((string) ($b['name'] ?? ''));
            if ($name === '') {
                return $this->jsonResponse(['success' => false, 'error' => 'The input is required and cannot be empty'], 400);
            }

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $data = [
                'cgroup_name' => $name,
                'cgroup_status' => (int) (!empty($b['status']) ? 1 : 0),
            ];

            if ($id > 0) {
                // Ne jamais retoucher la date de création existante sur une mise à jour : le service
                // legacy la réinitialiserait à now() si la clé est absente du tableau.
                $existing = iterator_to_array($db->query('SELECT cgroup_date_creation FROM melis_ecom_client_groups WHERE cgroup_id = ? LIMIT 1', [$id]));
                if (empty($existing)) { return $this->jsonResponse(['success' => false, 'error' => 'Group not found'], 404); }
                $data['cgroup_date_creation'] = ((array) $existing[0])['cgroup_date_creation'];
            }

            ob_start();
            $newId = $this->getServiceManager()->get('MelisComClientGroupsService')->saveClientsGroup($data, $id ?: null);
            ob_end_clean();

            return $this->jsonResponse(['success' => true, 'data' => ['id' => (int) $newId]], $id ? 200 : 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── DELETE /clients-groups/delete/:id ───────────────────────────────────────
    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }
        if ($id === self::GENERAL_GROUP_ID) {
            return $this->jsonResponse(['success' => false, 'error' => 'The default General group cannot be deleted.'], 403);
        }
        try {
            ob_start();
            $this->getServiceManager()->get('MelisComClientGroupsService')->deleteClientsGroupById($id);
            ob_end_clean();
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    private function isAuthenticated(): bool { return $this->getServiceManager()->get('MelisCoreAuth')->hasIdentity(); }

    private function denyUnlessAccess(): ?HttpResponse
    {
        if (!$this->isAuthenticated()) { return $this->jsonResponse(['success' => false, 'error' => 'Unauthenticated'], 401); }
        try { if (!$this->getServiceManager()->get('MelisCoreRights')->canAccess(self::MELIS_KEY)) { return $this->jsonResponse(['success' => false, 'error' => 'Forbidden'], 403); } } catch (\Throwable) {}
        return null;
    }

    private function jsonResponse(array $data, int $status = 200): HttpResponse
    {
        /** @var HttpResponse $response */
        $response = $this->getResponse();
        $response->setStatusCode($status);
        $response->getHeaders()->addHeaders(['Content-Type' => 'application/json; charset=utf-8', 'X-Content-Type-Options' => 'nosniff']);
        $response->setContent(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return $response;
    }

    private function errorResponse(\Throwable $e, int $status = 500): HttpResponse
    {
        return $this->jsonResponse(['success' => false, 'error' => $e->getMessage(), 'file' => basename($e->getFile()) . ':' . $e->getLine()], $status);
    }
}
