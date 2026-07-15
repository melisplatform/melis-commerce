<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour la gestion des comptes clients MelisCommerce (table melis_ecom_client).
 *
 * Routes :
 *   GET    /melis/react-api/accounts             → liste paginée + filtres
 *   GET    /melis/react-api/accounts/stats       → statistiques (KPI)
 *   GET    /melis/react-api/accounts/:id         → détail compte
 *   POST   /melis/react-api/accounts/save        → créer / mettre à jour
 *   DELETE /melis/react-api/accounts/delete/:id  → supprimer
 *
 * Mirror de MelisReactApiUserController (mêmes helpers / mêmes conventions).
 */
class MelisComReactApiAccountController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_clients_list_page';

    /**
     * melis_ecom_settings_account.sa_type ∈ manual_input|company_name|contact_name — pilote l'affichage
     * ET la validation du nom de compte partout (liste, détail, sauvegarde). Cf. MelisComClientService::
     * getAccountNameSetting() côté legacy (même table, même logique de reprise dynamique à la lecture).
     */
    private function accountNameMode(): string
    {
        $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
        $rows = iterator_to_array($db->query('SELECT sa_type FROM melis_ecom_settings_account LIMIT 1', []));
        $type = $rows ? (string) ((array) $rows[0])['sa_type'] : 'manual_input';
        return in_array($type, ['manual_input', 'company_name', 'contact_name'], true) ? $type : 'manual_input';
    }

    // ─── GET /accounts ────────────────────────────────────────────────────────

    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $page      = max(1, (int) $this->params()->fromQuery('page', 1));
            $limit     = min(9999, max(1, (int) $this->params()->fromQuery('limit', 25)));
            $search    = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawStatus = $this->params()->fromQuery('status', '');
            $status    = ($rawStatus !== '' && $rawStatus !== null) ? (int) $rawStatus : null;
            $rawGroup  = $this->params()->fromQuery('groupId', '');
            $groupId   = ($rawGroup !== '' && $rawGroup !== null) ? (int) $rawGroup : null;
            $offset    = ($page - 1) * $limit;

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $where  = [];
            $params = [];

            if ($status !== null) {
                $where[]  = 'c.cli_status = ?';
                $params[] = $status;
            }
            if ($groupId !== null) {
                $where[]  = 'c.cli_group_id = ?';
                $params[] = $groupId;
            }
            if ($search !== '') {
                $like     = '%' . $search . '%';
                $where[]  = '(c.cli_name LIKE ? OR c.cli_tags LIKE ?)';
                $params   = array_merge($params, [$like, $like]);
            }

            $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $countRow = iterator_to_array(
                $db->query("SELECT COUNT(*) AS total FROM melis_ecom_client c $whereClause", $params)
            );
            $total = (int) ($countRow[0]['total'] ?? 0);

            $mode = $this->accountNameMode();
            $dataSql = "
                SELECT c.cli_id, c.cli_status, c.cli_name, c.cli_group_id,
                       c.cli_country_id, c.cli_tags, c.cli_date_creation, c.cli_date_edit,
                       g.cgroup_name, cp.ctry_name AS cli_country_name,
                       comp.ccomp_name AS dyn_company_name,
                       dcp.cper_firstname AS dyn_contact_firstname, dcp.cper_name AS dyn_contact_name
                FROM melis_ecom_client c
                LEFT JOIN melis_ecom_client_groups g ON g.cgroup_id = c.cli_group_id
                LEFT JOIN melis_ecom_country cp ON cp.ctry_id = c.cli_country_id
                LEFT JOIN melis_ecom_client_company comp ON comp.ccomp_client_id = c.cli_id
                LEFT JOIN melis_ecom_client_account_rel dcar ON dcar.car_client_id = c.cli_id AND dcar.car_default_person = 1
                LEFT JOIN melis_ecom_client_person dcp ON dcp.cper_id = dcar.car_client_person_id
                $whereClause
                ORDER BY c.cli_id DESC
                LIMIT ? OFFSET ?
            ";
            $rows = $db->query($dataSql, array_merge($params, [$limit, $offset]));

            $items = [];
            foreach ($rows as $row) {
                $items[] = $this->formatAccount((array) $row, $mode);
            }

            return $this->jsonResponse([
                'success' => true,
                'data'    => ['items' => $items, 'total' => $total, 'page' => $page, 'limit' => $limit],
            ]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /accounts/stats ──────────────────────────────────────────────────

    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = (array) iterator_to_array($db->query(
                'SELECT COUNT(*) AS total,
                        SUM(cli_status = 1) AS active,
                        SUM(cli_status = 0) AS inactive
                 FROM melis_ecom_client',
                []
            ))[0];

            return $this->jsonResponse([
                'success' => true,
                'data'    => [
                    'total'    => (int) ($row['total']    ?? 0),
                    'active'   => (int) ($row['active']   ?? 0),
                    'inactive' => (int) ($row['inactive'] ?? 0),
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /accounts/options ────────────────────────────────────────────────
    // Listes pour les <select> du formulaire (groupes clients + pays).

    public function optionsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $groups = [];
            foreach ($db->query('SELECT cgroup_id, cgroup_name FROM melis_ecom_client_groups ORDER BY cgroup_name', []) as $r) {
                $r = (array) $r;
                $groups[] = ['id' => (int) $r['cgroup_id'], 'name' => (string) $r['cgroup_name']];
            }

            $countries = [];
            // Seuls les pays ACTIFS (ctry_status=1) — comme le back-office (EcomCountriesSelectFactory).
            foreach ($db->query('SELECT ctry_id, ctry_name FROM melis_ecom_country WHERE ctry_status = 1 ORDER BY ctry_name', []) as $r) {
                $r = (array) $r;
                $countries[] = ['id' => (int) $r['ctry_id'], 'name' => (string) $r['ctry_name']];
            }

            return $this->jsonResponse(['success' => true, 'data' => ['groups' => $groups, 'countries' => $countries, 'accountNameMode' => $this->accountNameMode()]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /accounts/:id ────────────────────────────────────────────────────

    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) {
            return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400);
        }

        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query(
                "SELECT c.cli_id, c.cli_status, c.cli_name, c.cli_group_id,
                        c.cli_country_id, c.cli_tags, c.cli_date_creation, c.cli_date_edit,
                        g.cgroup_name, cp.ctry_name AS cli_country_name,
                        comp.ccomp_name AS dyn_company_name,
                        dcp.cper_firstname AS dyn_contact_firstname, dcp.cper_name AS dyn_contact_name
                 FROM melis_ecom_client c
                 LEFT JOIN melis_ecom_client_groups g ON g.cgroup_id = c.cli_group_id
                 LEFT JOIN melis_ecom_country cp ON cp.ctry_id = c.cli_country_id
                 LEFT JOIN melis_ecom_client_company comp ON comp.ccomp_client_id = c.cli_id
                 LEFT JOIN melis_ecom_client_account_rel dcar ON dcar.car_client_id = c.cli_id AND dcar.car_default_person = 1
                 LEFT JOIN melis_ecom_client_person dcp ON dcp.cper_id = dcar.car_client_person_id
                 WHERE c.cli_id = ? LIMIT 1",
                [$id]
            ));

            if (empty($rows)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Account not found'], 404);
            }

            return $this->jsonResponse(['success' => true, 'data' => $this->formatAccount((array) $rows[0], $this->accountNameMode())]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── POST /accounts/save ──────────────────────────────────────────────────

    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $id   = (int) ($body['id'] ?? 0);

            $name      = trim((string) ($body['name'] ?? ''));
            $status    = (int) (!empty($body['status']) ? 1 : 0);
            $groupId   = (int) ($body['groupId'] ?? 0);
            $countryId = (int) ($body['countryId'] ?? 0);
            $tags      = trim((string) ($body['tags'] ?? ''));

            // manual_input seul rend le nom obligatoire ICI — en company_name/contact_name le nom est
            // dérivé dynamiquement à la lecture (cf. formatAccount()) et n'a donc plus besoin d'être saisi.
            if ($name === '' && $this->accountNameMode() === 'manual_input') {
                return $this->jsonResponse(['success' => false, 'error' => 'Le nom est obligatoire.'], 400);
            }
            if ($countryId <= 0) {
                return $this->jsonResponse(['success' => false, 'error' => 'Le pays est obligatoire.'], 400);
            }

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $now = date('Y-m-d H:i:s');

            if ($id > 0) {
                $db->query(
                    'UPDATE melis_ecom_client
                     SET cli_status = ?, cli_name = ?, cli_group_id = ?, cli_country_id = ?, cli_tags = ?, cli_date_edit = ?
                     WHERE cli_id = ?',
                    [$status, $name, $groupId ?: null, $countryId, $tags, $now, $id]
                );
                return $this->jsonResponse(['success' => true, 'data' => ['id' => $id]]);
            }

            $db->query(
                'INSERT INTO melis_ecom_client
                    (cli_status, cli_name, cli_group_id, cli_country_id, cli_tags, cli_date_creation)
                 VALUES (?, ?, ?, ?, ?, ?)',
                [$status, $name, $groupId ?: null, $countryId, $tags, $now]
            );
            $newId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];

            return $this->jsonResponse(['success' => true, 'data' => ['id' => $newId]], 201);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── DELETE /accounts/delete/:id ──────────────────────────────────────────

    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) {
            return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400);
        }

        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $db->query('DELETE FROM melis_ecom_client WHERE cli_id = ?', [$id]);
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── Onglets de la fiche compte (sous-ressources) ─────────────────────────

    /** Langue back-office courante (repli 1). */
    private function langId(): int
    {
        $c = new \Laminas\Session\Container('meliscore');
        $id = (int) ($c['melis-lang-id'] ?? 0);
        return $id > 0 ? $id : 1;
    }

    private function routeId(): int { return (int) $this->params()->fromRoute('id', 0); }

    // GET /accounts/:id/company
    public function companyAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query(
                'SELECT ccomp_id, ccomp_name, ccomp_number_id, ccomp_vat_number, ccomp_group, ccomp_employee_nb,
                        ccomp_comp_creation_date, ccomp_add_number, ccomp_add_street, ccomp_add_building,
                        ccomp_add_zipcode, ccomp_add_city, ccomp_add_state, ccomp_add_country,
                        ccomp_phone_number, ccomp_website, ccomp_logo
                 FROM melis_ecom_client_company WHERE ccomp_client_id = ? LIMIT 1', [$this->routeId()]
            ));
            $r = $rows ? (array) $rows[0] : [];
            $logo = null;
            if (!empty($r['ccomp_logo'])) {
                $mime = 'image/png';
                if (function_exists('finfo_open')) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $detected = $finfo ? finfo_buffer($finfo, (string) $r['ccomp_logo']) : false;
                    if ($detected) { $mime = $detected; }
                }
                $logo = 'data:' . $mime . ';base64,' . base64_encode((string) $r['ccomp_logo']);
            }
            return $this->jsonResponse(['success' => true, 'data' => [
                'id'               => (int)    ($r['ccomp_id'] ?? 0),
                'name'             => (string) ($r['ccomp_name'] ?? ''),
                'numberId'         => (string) ($r['ccomp_number_id'] ?? ''),
                'vatNumber'        => (string) ($r['ccomp_vat_number'] ?? ''),
                'group'            => (string) ($r['ccomp_group'] ?? ''),
                'employees'        => (int)    ($r['ccomp_employee_nb'] ?? 0),
                'compCreationDate' => $r['ccomp_comp_creation_date'] ? substr((string) $r['ccomp_comp_creation_date'], 0, 10) : '',
                'addNumber'        => (string) ($r['ccomp_add_number'] ?? ''),
                'addStreet'        => (string) ($r['ccomp_add_street'] ?? ''),
                'addBuilding'      => (string) ($r['ccomp_add_building'] ?? ''),
                'addZip'           => (string) ($r['ccomp_add_zipcode'] ?? ''),
                'addCity'          => (string) ($r['ccomp_add_city'] ?? ''),
                'addState'         => (string) ($r['ccomp_add_state'] ?? ''),
                'addCountry'       => (string) ($r['ccomp_add_country'] ?? ''),
                'phone'            => (string) ($r['ccomp_phone_number'] ?? ''),
                'website'          => (string) ($r['ccomp_website'] ?? ''),
                'logo'             => $logo,
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // POST /accounts/:id/company/save
    public function companySaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $clientId = $this->routeId();
            $b = json_decode($this->getRequest()->getContent(), true) ?? [];

            // company_name mode : le nom société pilote le nom de compte affiché partout (formatAccount()) —
            // devient obligatoire ici, cf. ticket Mantis #10677.
            if ($this->accountNameMode() === 'company_name' && trim((string) ($b['name'] ?? '')) === '') {
                return $this->jsonResponse(['success' => false, 'error' => 'Le nom de la société est obligatoire.'], 400);
            }

            $logoRaw = (string) ($b['logo'] ?? '');
            if (($p = strpos($logoRaw, ',')) !== false && strpos($logoRaw, ';base64') !== false) { $logoRaw = substr($logoRaw, $p + 1); }
            $logoBytes = $logoRaw !== '' ? base64_decode($logoRaw, true) : '';
            if ($logoBytes !== '' && $logoBytes !== false && strlen($logoBytes) > 500 * 1024) {
                return $this->jsonResponse(['success' => false, 'error' => 'Le logo dépasse la taille maximale de 500kB.'], 400);
            }

            $cols = [
                'ccomp_name'              => trim((string) ($b['name'] ?? '')),
                'ccomp_number_id'         => trim((string) ($b['numberId'] ?? '')),
                'ccomp_vat_number'        => trim((string) ($b['vatNumber'] ?? '')),
                'ccomp_group'             => trim((string) ($b['group'] ?? '')),
                'ccomp_employee_nb'       => (int) ($b['employees'] ?? 0),
                'ccomp_comp_creation_date'=> trim((string) ($b['compCreationDate'] ?? '')) ?: null,
                'ccomp_add_number'        => trim((string) ($b['addNumber'] ?? '')),
                'ccomp_add_street'        => trim((string) ($b['addStreet'] ?? '')),
                'ccomp_add_building'      => trim((string) ($b['addBuilding'] ?? '')),
                'ccomp_add_zipcode'       => trim((string) ($b['addZip'] ?? '')),
                'ccomp_add_city'          => trim((string) ($b['addCity'] ?? '')),
                'ccomp_add_state'         => trim((string) ($b['addState'] ?? '')),
                'ccomp_add_country'       => trim((string) ($b['addCountry'] ?? '')),
                'ccomp_phone_number'      => trim((string) ($b['phone'] ?? '')),
                'ccomp_website'           => trim((string) ($b['website'] ?? '')),
                'ccomp_logo'              => $logoBytes !== false ? $logoBytes : '',
            ];
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $now = date('Y-m-d H:i:s');
            $exists = iterator_to_array($db->query('SELECT ccomp_id FROM melis_ecom_client_company WHERE ccomp_client_id = ? LIMIT 1', [$clientId]));
            if ($exists) {
                $set = implode(', ', array_map(fn($c) => "$c = ?", array_keys($cols)));
                $db->query("UPDATE melis_ecom_client_company SET $set, ccomp_date_edit = ? WHERE ccomp_client_id = ?",
                    array_merge(array_values($cols), [$now, $clientId]));
            } else {
                $names = array_keys($cols);
                $ph    = implode(', ', array_fill(0, count($names) + 2, '?'));
                $db->query('INSERT INTO melis_ecom_client_company (ccomp_client_id, ' . implode(', ', $names) . ', ccomp_date_creation) VALUES (' . $ph . ')',
                    array_merge([$clientId], array_values($cols), [$now]));
            }
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // GET /accounts/:id/contacts — contacts liés au compte (melis_ecom_client_account_rel).
    // isMain = car_default_person (contact par défaut du compte) ; isDefaultAccount = cpr_default_client
    // (compte par défaut du contact, table symétrique melis_ecom_client_person_rel).
    public function contactsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = $db->query(
                'SELECT p.cper_id, p.cper_status, p.cper_firstname, p.cper_name, p.cper_email,
                        car.car_default_person AS is_main,
                        (SELECT cpr.cpr_default_client FROM melis_ecom_client_person_rel cpr
                         WHERE cpr.cpr_client_id = car.car_client_id AND cpr.cpr_client_person_id = car.car_client_person_id LIMIT 1) AS is_default_account
                 FROM melis_ecom_client_account_rel car
                 JOIN melis_ecom_client_person p ON p.cper_id = car.car_client_person_id
                 WHERE car.car_client_id = ? ORDER BY p.cper_name, p.cper_firstname', [$this->routeId()]
            );
            $items = [];
            foreach ($rows as $r) {
                $r = (array) $r;
                $items[] = [
                    'id' => (int) $r['cper_id'], 'status' => (int) $r['cper_status'],
                    'firstname' => (string) ($r['cper_firstname'] ?? ''), 'name' => (string) ($r['cper_name'] ?? ''),
                    'email' => (string) ($r['cper_email'] ?? ''),
                    'isMain' => (int) ($r['is_main'] ?? 0), 'isDefaultAccount' => (int) ($r['is_default_account'] ?? 0),
                ];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // POST /accounts/:id/contacts/:contactId/default — marque CE contact comme contact par défaut du compte
    public function contactDefaultAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $accountId = $this->routeId();
            $contactId = (int) $this->params()->fromRoute('contactId', 0);
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $db->query('UPDATE melis_ecom_client_account_rel SET car_default_person = 0 WHERE car_client_id = ?', [$accountId]);
            $db->query('UPDATE melis_ecom_client_account_rel SET car_default_person = 1 WHERE car_client_id = ? AND car_client_person_id = ?', [$accountId, $contactId]);
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // GET /accounts/:id/addresses — champs COMPLETS (édition staged + sauvegarde groupée)
    public function addressesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = $db->query(
                'SELECT a.cadd_id, a.cadd_address_name, a.cadd_type, a.cadd_civility, a.cadd_firstname, a.cadd_name,
                        a.cadd_middle_name, a.cadd_num, a.cadd_street, a.cadd_building_name, a.cadd_zipcode, a.cadd_city,
                        a.cadd_state, a.cadd_country, a.cadd_phone_mobile, t.catypt_name AS type_name
                 FROM melis_ecom_client_address a
                 LEFT JOIN melis_ecom_client_address_type_trans t ON t.catypt_type_id = a.cadd_type AND t.catypt_lang_id = ?
                 WHERE a.cadd_client_id = ? ORDER BY a.cadd_id DESC', [$this->langId(), $this->routeId()]
            );
            $items = [];
            foreach ($rows as $r) {
                $r = (array) $r;
                $items[] = [
                    'id' => (int) $r['cadd_id'], 'addressName' => (string) ($r['cadd_address_name'] ?? ''),
                    'type' => (int) ($r['cadd_type'] ?? 0), 'typeName' => (string) ($r['type_name'] ?? ''),
                    'civility' => (int) ($r['cadd_civility'] ?? 0),
                    'firstname' => (string) ($r['cadd_firstname'] ?? ''), 'name' => (string) ($r['cadd_name'] ?? ''),
                    'middleName' => (string) ($r['cadd_middle_name'] ?? ''), 'num' => (string) ($r['cadd_num'] ?? ''),
                    'street' => (string) ($r['cadd_street'] ?? ''), 'building' => (string) ($r['cadd_building_name'] ?? ''),
                    'zipcode' => (string) ($r['cadd_zipcode'] ?? ''), 'city' => (string) ($r['cadd_city'] ?? ''),
                    'state' => (string) ($r['cadd_state'] ?? ''), 'country' => (string) ($r['cadd_country'] ?? ''),
                    'phoneMobile' => (string) ($r['cadd_phone_mobile'] ?? ''),
                ];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // GET /accounts/address-options — types d'adresse + civilités (selects du form d'adresse)
    public function addressOptionsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();
            $types = [];
            foreach ($db->query('SELECT catypt_type_id, catypt_name FROM melis_ecom_client_address_type_trans WHERE catypt_lang_id = ? ORDER BY catypt_type_id', [$lang]) as $r) {
                $r = (array) $r; $types[] = ['id' => (int) $r['catypt_type_id'], 'name' => (string) ($r['catypt_name'] ?? '')];
            }
            $civ = [];
            foreach ($db->query('SELECT c.civ_id, t.civt_min_name FROM melis_ecom_civility c LEFT JOIN melis_ecom_civility_trans t ON t.civt_civ_id = c.civ_id AND t.civt_lang_id = ? ORDER BY c.civ_id', [$lang]) as $r) {
                $r = (array) $r; $civ[] = ['id' => (int) $r['civ_id'], 'name' => (string) ($r['civt_min_name'] ?? ('#' . $r['civ_id']))];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['types' => $types, 'civilities' => $civ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // POST /accounts/:id/addresses/save — remplace les adresses du compte par la liste fournie
    // (upsert par id + suppression de celles retirées). Déclenché par le bouton Save du haut.
    public function addressesSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $clientId = $this->routeId();
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $list = is_array($body['items'] ?? null) ? $body['items'] : (is_array($body) ? $body : []);
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $now = date('Y-m-d H:i:s');

            $kept = [];
            foreach ($list as $a) {
                if (!is_array($a)) { continue; }
                $name = trim((string) ($a['addressName'] ?? ''));
                if ($name === '') { continue; }
                $id = (int) ($a['id'] ?? 0);
                $vals = [
                    'cadd_type'          => (int) ($a['type'] ?? 0) ?: null,
                    'cadd_civility'      => (int) ($a['civility'] ?? 0) ?: null,
                    'cadd_firstname'     => trim((string) ($a['firstname'] ?? '')),
                    'cadd_name'          => trim((string) ($a['name'] ?? '')),
                    'cadd_middle_name'   => trim((string) ($a['middleName'] ?? '')),
                    'cadd_num'           => trim((string) ($a['num'] ?? '')),
                    'cadd_street'        => trim((string) ($a['street'] ?? '')),
                    'cadd_building_name' => trim((string) ($a['building'] ?? '')),
                    'cadd_zipcode'       => trim((string) ($a['zipcode'] ?? '')),
                    'cadd_city'          => trim((string) ($a['city'] ?? '')),
                    'cadd_state'         => trim((string) ($a['state'] ?? '')),
                    'cadd_country'       => trim((string) ($a['country'] ?? '')),
                    'cadd_phone_mobile'  => trim((string) ($a['phoneMobile'] ?? '')),
                    'cadd_address_name'  => $name,
                ];
                if ($id > 0) {
                    $set = implode(', ', array_map(fn($c) => "$c = ?", array_keys($vals)));
                    $db->query("UPDATE melis_ecom_client_address SET $set WHERE cadd_id = ? AND cadd_client_id = ?", array_merge(array_values($vals), [$id, $clientId]));
                    $kept[] = $id;
                } else {
                    $names = array_keys($vals);
                    $ph    = implode(', ', array_fill(0, count($names) + 2, '?'));
                    $db->query('INSERT INTO melis_ecom_client_address (cadd_client_id, ' . implode(', ', $names) . ', cadd_creation_date) VALUES (' . $ph . ')', array_merge([$clientId], array_values($vals), [$now]));
                    $kept[] = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];
                }
            }
            // Supprimer les adresses retirées (existantes mais absentes de la liste)
            foreach ($db->query('SELECT cadd_id FROM melis_ecom_client_address WHERE cadd_client_id = ?', [$clientId]) as $r) {
                $eid = (int) ((array) $r)['cadd_id'];
                if (!in_array($eid, $kept, true)) { $db->query('DELETE FROM melis_ecom_client_address WHERE cadd_id = ?', [$eid]); }
            }
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // GET /accounts/:id/orders
    public function ordersAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = $db->query(
                'SELECT o.ord_id, o.ord_reference, o.ord_status, o.ord_date_creation, s.ostt_status_name
                 FROM melis_ecom_order o
                 LEFT JOIN melis_ecom_order_status_trans s ON s.ostt_status_id = o.ord_status AND s.ostt_lang_id = ?
                 WHERE o.ord_client_id = ? ORDER BY o.ord_id DESC', [$this->langId(), $this->routeId()]
            );
            $items = [];
            foreach ($rows as $r) {
                $r = (array) $r;
                $items[] = [
                    'id' => (int) $r['ord_id'], 'reference' => (string) ($r['ord_reference'] ?? ''),
                    'status' => (int) $r['ord_status'], 'statusName' => (string) ($r['ostt_status_name'] ?? ''),
                    'dateCreation' => $r['ord_date_creation'] ?? null,
                ];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // GET /accounts/:id/files
    public function filesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = $db->query(
                'SELECT d.doc_id, d.doc_name, d.doc_path, d.doc_type_id
                 FROM melis_ecom_doc_relations rel
                 JOIN melis_ecom_document d ON d.doc_id = rel.rdoc_doc_id
                 WHERE rel.rdoc_client_id = ? ORDER BY d.doc_id DESC', [$this->routeId()]
            );
            $items = [];
            foreach ($rows as $r) {
                $r = (array) $r;
                $items[] = [
                    'id' => (int) $r['doc_id'], 'name' => (string) ($r['doc_name'] ?? ''),
                    'path' => (string) ($r['doc_path'] ?? ''), 'typeId' => (int) ($r['doc_type_id'] ?? 0),
                ];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // POST /accounts/:id/files/upload — téléverse un fichier et le rattache au compte
    public function fileUploadAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $clientId = $this->routeId();
            $files = $this->getRequest()->getFiles()->toArray();
            $file = $files['file'] ?? null;
            if (!$file || !isset($file['tmp_name']) || ($file['error'] ?? 1) !== UPLOAD_ERR_OK) {
                return $this->jsonResponse(['success' => false, 'error' => 'No file uploaded'], 400);
            }
            $orig = basename((string) $file['name']);
            $safe = preg_replace('/[^A-Za-z0-9._-]/', '_', $orig) ?: 'file';
            $docRoot = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? (getcwd() . '/public')), '/');
            $relDir  = '/media/commerce/' . $clientId;
            $absDir  = $docRoot . $relDir;
            if (!is_dir($absDir) && !mkdir($absDir, 0775, true) && !is_dir($absDir)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Cannot create upload dir'], 500);
            }
            $uniq    = substr(md5(uniqid('', true)), 0, 8);
            $relPath = $relDir . '/' . $uniq . '_' . $safe;
            if (!@move_uploaded_file($file['tmp_name'], $docRoot . $relPath) && !@rename($file['tmp_name'], $docRoot . $relPath)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Upload failed'], 500);
            }
            // Champs de la modale : Nom, Type (doc_type), Pays.
            $post      = $this->getRequest()->getPost()->toArray();
            $name      = trim((string) ($post['name'] ?? '')) ?: $orig;
            $typeId    = (int) ($post['typeId'] ?? 0) ?: 2; // 2 = FILE par défaut
            $countryId = (int) ($post['countryId'] ?? 0);
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $db->query('INSERT INTO melis_ecom_document (doc_name, doc_path, doc_type_id) VALUES (?, ?, ?)', [$name, $relPath, $typeId]);
            $docId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];
            $db->query('INSERT INTO melis_ecom_doc_relations (rdoc_doc_id, rdoc_client_id, rdoc_country_id) VALUES (?, ?, ?)', [$docId, $clientId, $countryId ?: null]);
            return $this->jsonResponse(['success' => true, 'data' => ['id' => $docId, 'name' => $name, 'path' => $relPath, 'typeId' => $typeId]], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // GET /accounts/file-options — types de document + pays (selects de la modale d'ajout de fichier)
    public function fileOptionsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $types = [];
            foreach ($db->query('SELECT dtype_id, dtype_name FROM melis_ecom_doc_type ORDER BY dtype_id', []) as $r) {
                $r = (array) $r; $types[] = ['id' => (int) $r['dtype_id'], 'name' => (string) ($r['dtype_name'] ?? '')];
            }
            // Seuls les pays ACTIFS (ctry_status=1) — comme le back-office (sinon « All Country » + France).
            $countries = [];
            foreach ($db->query('SELECT ctry_id, ctry_name FROM melis_ecom_country WHERE ctry_status = 1 ORDER BY ctry_name', []) as $r) {
                $r = (array) $r; $countries[] = ['id' => (int) $r['ctry_id'], 'name' => (string) ($r['ctry_name'] ?? '')];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['types' => $types, 'countries' => $countries]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // POST /accounts/file-types — crée un type de document (Type code + Type name)
    public function fileTypeCreateAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $code = substr(trim((string) ($body['code'] ?? '')), 0, 10);
            $name = trim((string) ($body['name'] ?? ''));
            if ($code === '' || $name === '') {
                return $this->jsonResponse(['success' => false, 'error' => 'Code and name are required'], 400);
            }
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $db->query('INSERT INTO melis_ecom_doc_type (dtype_code, dtype_name) VALUES (?, ?)', [$code, $name]);
            $id = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];
            return $this->jsonResponse(['success' => true, 'data' => ['id' => $id, 'name' => $name]], 201);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // DELETE /accounts/:id/files/:fileId — supprime le fichier (relation + document + fichier physique)
    public function fileDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $fileId = (int) $this->params()->fromRoute('fileId', 0);
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $rows = iterator_to_array($db->query('SELECT doc_path FROM melis_ecom_document WHERE doc_id = ? LIMIT 1', [$fileId]));
            $path = $rows ? (string) ((array) $rows[0])['doc_path'] : '';
            if ($path !== '') {
                $docRoot = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? (getcwd() . '/public')), '/');
                $abs = $docRoot . '/' . ltrim($path, '/');
                if (is_file($abs)) { @unlink($abs); }
            }
            $db->query('DELETE FROM melis_ecom_doc_relations WHERE rdoc_doc_id = ?', [$fileId]);
            $db->query('DELETE FROM melis_ecom_document WHERE doc_id = ?', [$fileId]);
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // GET /accounts/order-statuses — statuts de commande (filtre de l'onglet Commandes)
    public function orderStatusesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $items = [];
            foreach ($db->query('SELECT ostt_status_id, ostt_status_name FROM melis_ecom_order_status_trans WHERE ostt_lang_id = ? ORDER BY ostt_status_id', [$this->langId()]) as $r) {
                $r = (array) $r;
                $items[] = ['id' => (int) $r['ostt_status_id'], 'name' => (string) ($r['ostt_status_name'] ?? '')];
            }
            return $this->jsonResponse(['success' => true, 'data' => $items]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // GET /accounts/contact-options — tous les contacts (pour lier un contact au compte)
    public function contactOptionsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $items = [];
            foreach ($db->query('SELECT cper_id, cper_firstname, cper_name FROM melis_ecom_client_person ORDER BY cper_name, cper_firstname', []) as $r) {
                $r = (array) $r;
                $name = trim(($r['cper_firstname'] ?? '') . ' ' . ($r['cper_name'] ?? '')) ?: ('#' . $r['cper_id']);
                $items[] = ['id' => (int) $r['cper_id'], 'name' => $name];
            }
            return $this->jsonResponse(['success' => true, 'data' => $items]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // POST /accounts/:id/contacts/link {contactId} — lie un contact au compte (rel)
    public function contactLinkAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $clientId  = $this->routeId();
            $body      = json_decode($this->getRequest()->getContent(), true) ?? [];
            $contactId = (int) ($body['contactId'] ?? 0);
            if ($contactId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid contact'], 400); }
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $this->linkClientPerson($db, $clientId, $contactId);
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // DELETE /accounts/:id/contacts/:contactId — délie un contact du compte
    public function contactUnlinkAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $this->unlinkClientPerson($db, $this->routeId(), (int) $this->params()->fromRoute('contactId', 0));
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /accounts/import-test — valide le CSV sans rien écrire en base ──
    public function importTestAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            [$file, $error] = $this->requireCsvUpload();
            if ($error) { return $error; }

            $delimiter    = $this->detectCsvDelimiter($file['tmp_name']);
            $fileContents = $this->readImportedCsvFile($file['tmp_name']);
            $accountService = $this->getServiceManager()->get('MelisComClientService');
            $result = $accountService->importFileValidator($fileContents, $delimiter, false, 'account_name');

            // success = l'appel API a fonctionné ; le résultat métier (fichier valide ou non) est dans `data`.
            return $this->jsonResponse([
                'success' => true,
                'data'    => ['valid' => empty($result['errors']), 'errors' => $result['errors'] ?? []],
            ]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /accounts/import — importe le CSV (mêmes règles que le back-office legacy) ──
    public function importAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            [$file, $error] = $this->requireCsvUpload();
            if ($error) { return $error; }

            $delimiter    = $this->detectCsvDelimiter($file['tmp_name']);
            $fileContents = $this->readImportedCsvFile($file['tmp_name']);
            $accountService = $this->getServiceManager()->get('MelisComClientService');
            $result = $accountService->importFileValidator($fileContents, $delimiter, false, 'account_name');
            if (empty($result['proceedImporting'])) {
                return $this->jsonResponse(['success' => true, 'data' => ['imported' => false, 'errors' => $result['errors'] ?? []]]);
            }

            $adapter = $this->getServiceManager()->get('Laminas\Db\Adapter\Adapter');
            $con = $adapter->getDriver()->getConnection();
            $con->beginTransaction();
            try {
                $accountService->importAccounts($fileContents, [], $delimiter, (bool) ($result['allowOverride'] ?? false), 'account_name');
                $con->commit();
            } catch (\Throwable $ex) {
                $con->rollback();
                throw $ex;
            }
            return $this->jsonResponse(['success' => true, 'data' => ['imported' => true, 'errors' => []]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    /** @return array{0: ?array, 1: ?HttpResponse} [$file, $errorResponseOrNull] */
    private function requireCsvUpload(): array
    {
        $files = $this->getRequest()->getFiles()->toArray();
        $file  = $files['account_file'] ?? null;
        if (!$file || !isset($file['tmp_name']) || ($file['error'] ?? 1) !== UPLOAD_ERR_OK) {
            return [null, $this->jsonResponse(['success' => false, 'error' => 'No file uploaded'], 400)];
        }
        if (strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION)) !== 'csv') {
            return [null, $this->jsonResponse(['success' => false, 'error' => 'The file must be a CSV'], 400)];
        }
        return [$file, null];
    }

    /** Devine le séparateur CSV (,/;/tab) en comptant les colonnes sur les 3 premières lignes — cf. MelisComClientController::getCsvDelimiter(). */
    private function detectCsvDelimiter(string $filePath, int $checkLines = 3): string
    {
        $delimiters = [',', ';', "\t"];
        $counts = [];
        $fileObject = new \SplFileObject($filePath);
        $counter = 0;
        while ($fileObject->valid() && $counter <= $checkLines) {
            $line = $fileObject->fgets();
            foreach ($delimiters as $delimiter) {
                // str_getcsv (not explode) so a delimiter that only appears inside a quoted field
                // (e.g. the sample template's tags column "tag1,tag2") can't outscore the real one.
                $total = count(str_getcsv($line, $delimiter));
                if ($total > 1) { $counts[$delimiter] = ($counts[$delimiter] ?? 0) + $total; }
            }
            $counter++;
        }
        if (!$counts) { return ';'; }
        $best = array_keys($counts, max($counts));
        return $best[0];
    }

    /** Lit le CSV importé, en forçant l'UTF-8 si besoin — cf. MelisComClientController::readImportedCsv(). */
    private function readImportedCsvFile(string $tmpName): string
    {
        $data = file_get_contents($tmpName);
        if ($data !== false && !mb_check_encoding($data, 'UTF-8')) {
            $data = mb_convert_encoding($data, 'UTF-8', 'ISO-8859-1');
        }
        return $data === false ? '' : $data;
    }

    /** Lie un contact à un compte dans LES DEUX tables symétriques (car + cpr), dédupliqué. */
    private function linkClientPerson($db, int $clientId, int $contactId): void
    {
        if (!iterator_to_array($db->query('SELECT car_id FROM melis_ecom_client_account_rel WHERE car_client_id = ? AND car_client_person_id = ? LIMIT 1', [$clientId, $contactId]))) {
            // Premier contact associé à ce compte → devient automatiquement son contact par défaut,
            // cf. MelisComClientService::linkAccountContact() (legacy).
            $isFirst = !iterator_to_array($db->query('SELECT car_id FROM melis_ecom_client_account_rel WHERE car_client_id = ? LIMIT 1', [$clientId]));
            $db->query('INSERT INTO melis_ecom_client_account_rel (car_client_id, car_client_person_id, car_default_person) VALUES (?, ?, ?)', [$clientId, $contactId, $isFirst ? 1 : 0]);
        }
        if (!iterator_to_array($db->query('SELECT cpr_id FROM melis_ecom_client_person_rel WHERE cpr_client_id = ? AND cpr_client_person_id = ? LIMIT 1', [$clientId, $contactId]))) {
            $db->query('INSERT INTO melis_ecom_client_person_rel (cpr_client_id, cpr_client_person_id, cpr_default_client) VALUES (?, ?, 0)', [$clientId, $contactId]);
        }
    }

    /** Délie un contact d'un compte dans les deux tables. */
    private function unlinkClientPerson($db, int $clientId, int $contactId): void
    {
        $db->query('DELETE FROM melis_ecom_client_account_rel WHERE car_client_id = ? AND car_client_person_id = ?', [$clientId, $contactId]);
        $db->query('DELETE FROM melis_ecom_client_person_rel WHERE cpr_client_id = ? AND cpr_client_person_id = ?', [$clientId, $contactId]);
    }

    // ─── Helpers (copiés de MelisReactApiUserController) ───────────────────────

    /**
     * @param string $mode manual_input|company_name|contact_name — quand différent de manual_input, le nom
     * affiché est repris DYNAMIQUEMENT de la société / du contact par défaut (cli_name stocké est ignoré),
     * comme MelisComClientService::getAccountNameSetting() côté legacy (à chaque lecture, pas seulement
     * à la sauvegarde — un nom peut donc changer sans que le compte lui-même n'ait été ré-enregistré).
     */
    private function formatAccount(array $r, string $mode = 'manual_input'): array
    {
        $name = (string) ($r['cli_name'] ?? '');
        if ($mode === 'company_name') {
            $name = (string) ($r['dyn_company_name'] ?? '');
        } elseif ($mode === 'contact_name') {
            $name = trim(((string) ($r['dyn_contact_firstname'] ?? '')) . ' ' . ((string) ($r['dyn_contact_name'] ?? '')));
        }
        return [
            'id'           => (int)    $r['cli_id'],
            'status'       => (int)    $r['cli_status'],
            'name'         => $name,
            'groupId'      => (int)    ($r['cli_group_id'] ?? 0),
            'groupName'    => (string) ($r['cgroup_name'] ?? ''),
            'countryId'    => (int)    ($r['cli_country_id'] ?? 0),
            'countryName'  => (string) ($r['cli_country_name'] ?? ''),
            'tags'         => (string) ($r['cli_tags'] ?? ''),
            'dateCreation' => $r['cli_date_creation'] ?? null,
            'dateEdit'     => $r['cli_date_edit'] ?? null,
            'nameMode'     => $mode,
        ];
    }

    private function isAuthenticated(): bool
    {
        return $this->getServiceManager()->get('MelisCoreAuth')->hasIdentity();
    }

    private function denyUnlessAccess(): ?HttpResponse
    {
        if (!$this->isAuthenticated()) {
            return $this->jsonResponse(['success' => false, 'error' => 'Unauthenticated'], 401);
        }
        try {
            if (!$this->getServiceManager()->get('MelisCoreRights')->canAccess(self::MELIS_KEY)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Forbidden'], 403);
            }
        } catch (\Throwable) {}
        return null;
    }

    private function jsonResponse(array $data, int $status = 200): HttpResponse
    {
        /** @var HttpResponse $response */
        $response = $this->getResponse();
        $response->setStatusCode($status);
        $response->getHeaders()->addHeaders([
            'Content-Type'           => 'application/json; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
        ]);
        $response->setContent(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return $response;
    }

    private function errorResponse(\Throwable $e, int $status = 500): HttpResponse
    {
        return $this->jsonResponse([
            'success' => false,
            'error'   => $e->getMessage(),
            'file'    => basename($e->getFile()) . ':' . $e->getLine(),
        ], $status);
    }
}
