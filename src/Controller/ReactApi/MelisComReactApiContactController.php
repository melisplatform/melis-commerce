<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour la gestion des Contacts MelisCommerce (table melis_ecom_client_person).
 *
 * Routes :
 *   GET    /melis/react-api/contacts             → liste paginée + filtres
 *   GET    /melis/react-api/contacts/stats       → statistiques (KPI)
 *   GET    /melis/react-api/contacts/options     → comptes + civilités (selects du form)
 *   GET    /melis/react-api/contacts/:id         → détail contact
 *   POST   /melis/react-api/contacts/save        → créer / mettre à jour
 *   DELETE /melis/react-api/contacts/delete/:id  → supprimer
 *
 * Mirror de MelisComReactApiAccountController.
 */
class MelisComReactApiContactController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_contact_list_page';

    /** Langue back-office courante (civt_lang_id des civilités), repli 1. */
    private function langId(): int
    {
        $c = new \Laminas\Session\Container('meliscore');
        $id = (int) ($c['melis-lang-id'] ?? 0);
        return $id > 0 ? $id : 1;
    }

    /**
     * melis_ecom_settings_account.sa_type ∈ manual_input|company_name|contact_name — same read-time
     * override as MelisComReactApiAccountController::accountNameMode()/formatAccount() (duplicated
     * here, no shared base controller) so every account name shown from the Contacts tool (list,
     * options picker, Association tab) matches what the Accounts tool itself displays, instead of
     * the raw — often blank in company_name/contact_name mode — cli_name column.
     */
    private function accountNameMode(): string
    {
        $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
        $rows = iterator_to_array($db->query('SELECT sa_type FROM melis_ecom_settings_account LIMIT 1', []));
        $type = $rows ? (string) ((array) $rows[0])['sa_type'] : 'manual_input';
        return in_array($type, ['manual_input', 'company_name', 'contact_name'], true) ? $type : 'manual_input';
    }

    /** Resolves an account's display name per accountNameMode(), from a row carrying the same
     *  dyn_company_name / dyn_contact_firstname / dyn_contact_name columns as the joins below. */
    private function resolveAccountName(array $r, string $mode): string
    {
        if ($mode === 'company_name') {
            return (string) ($r['dyn_company_name'] ?? '');
        }
        if ($mode === 'contact_name') {
            return trim(((string) ($r['dyn_contact_firstname'] ?? '')) . ' ' . ((string) ($r['dyn_contact_name'] ?? '')));
        }
        return (string) ($r['cli_name'] ?? '');
    }

    /** SQL snippet joining the tables resolveAccountName()'s dyn_* columns need, aliased for `$as`. */
    private function accountNameJoinSql(string $as): string
    {
        return "
            LEFT JOIN melis_ecom_client_company {$as}_comp ON {$as}_comp.ccomp_client_id = {$as}.cli_id
            LEFT JOIN melis_ecom_client_account_rel {$as}_dcar ON {$as}_dcar.car_client_id = {$as}.cli_id AND {$as}_dcar.car_default_person = 1
            LEFT JOIN melis_ecom_client_person {$as}_dcp ON {$as}_dcp.cper_id = {$as}_dcar.car_client_person_id
        ";
    }

    // ─── GET /contacts ────────────────────────────────────────────────────────
    public function listAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $page      = max(1, (int) $this->params()->fromQuery('page', 1));
            $limit     = min(9999, max(1, (int) $this->params()->fromQuery('limit', 25)));
            $search    = trim((string) ($this->params()->fromQuery('search', '') ?? ''));
            $rawStatus = $this->params()->fromQuery('status', '');
            $status    = ($rawStatus !== '' && $rawStatus !== null) ? (int) $rawStatus : null;
            $rawAcc    = $this->params()->fromQuery('accountId', '');
            $accountId = ($rawAcc !== '' && $rawAcc !== null) ? (int) $rawAcc : null;
            $offset    = ($page - 1) * $limit;
            $lang      = $this->langId();

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            $where  = [];
            $params = [];
            if ($status !== null)    { $where[] = 'p.cper_status = ?';    $params[] = $status; }
            if ($accountId !== null) { $where[] = 'p.cper_client_id = ?'; $params[] = $accountId; }
            if ($search !== '') {
                $like    = '%' . $search . '%';
                $where[] = '(p.cper_firstname LIKE ? OR p.cper_name LIKE ? OR p.cper_email LIKE ? OR p.cper_tags LIKE ?)';
                $params  = array_merge($params, [$like, $like, $like, $like]);
            }
            $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $countRow = iterator_to_array(
                $db->query("SELECT COUNT(*) AS total FROM melis_ecom_client_person p $whereClause", $params)
            );
            $total = (int) ($countRow[0]['total'] ?? 0);

            $mode = $this->accountNameMode();
            $dataSql = "
                SELECT p.cper_id, p.cper_status, p.cper_type, p.cper_civility, p.cper_firstname,
                       p.cper_name, p.cper_email, p.cper_client_id, p.cper_job_title, p.cper_tel_mobile,
                       p.cper_tags, p.cper_date_creation, p.cper_date_edit,
                       c.cli_name, civ.civt_min_name AS civility_name,
                       c_comp.ccomp_name AS dyn_company_name,
                       c_dcp.cper_firstname AS dyn_contact_firstname, c_dcp.cper_name AS dyn_contact_name
                FROM melis_ecom_client_person p
                LEFT JOIN melis_ecom_client c ON c.cli_id = p.cper_client_id
                LEFT JOIN melis_ecom_civility_trans civ ON civ.civt_civ_id = p.cper_civility AND civ.civt_lang_id = ?
                " . $this->accountNameJoinSql('c') . "
                $whereClause
                ORDER BY p.cper_id DESC
                LIMIT ? OFFSET ?
            ";
            $rows = $db->query($dataSql, array_merge([$lang], $params, [$limit, $offset]));

            $items = [];
            foreach ($rows as $row) { $items[] = $this->formatContact((array) $row, $mode); }

            return $this->jsonResponse([
                'success' => true,
                'data'    => ['items' => $items, 'total' => $total, 'page' => $page, 'limit' => $limit],
            ]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /contacts/stats ──────────────────────────────────────────────────
    public function statsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $row = (array) iterator_to_array($db->query(
                'SELECT COUNT(*) AS total, SUM(cper_status = 1) AS active, SUM(cper_status = 0) AS inactive
                 FROM melis_ecom_client_person', []
            ))[0];

            return $this->jsonResponse(['success' => true, 'data' => [
                'total'    => (int) ($row['total']    ?? 0),
                'active'   => (int) ($row['active']   ?? 0),
                'inactive' => (int) ($row['inactive'] ?? 0),
            ]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /contacts/options ────────────────────────────────────────────────
    public function optionsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();

            $mode = $this->accountNameMode();
            $orderExpr = match ($mode) {
                'company_name' => 'c_comp.ccomp_name',
                'contact_name' => "TRIM(CONCAT(c_dcp.cper_firstname, ' ', c_dcp.cper_name))",
                default        => 'c.cli_name',
            };
            $accounts = [];
            foreach ($db->query(
                "SELECT c.cli_id, c.cli_name,
                        c_comp.ccomp_name AS dyn_company_name,
                        c_dcp.cper_firstname AS dyn_contact_firstname, c_dcp.cper_name AS dyn_contact_name
                 FROM melis_ecom_client c
                 " . $this->accountNameJoinSql('c') . "
                 ORDER BY $orderExpr",
                []
            ) as $r) {
                $r = (array) $r;
                $accounts[] = ['id' => (int) $r['cli_id'], 'name' => $this->resolveAccountName($r, $mode)];
            }

            $civilities = [];
            foreach ($db->query(
                'SELECT civ.civ_id, t.civt_min_name
                 FROM melis_ecom_civility civ
                 LEFT JOIN melis_ecom_civility_trans t ON t.civt_civ_id = civ.civ_id AND t.civt_lang_id = ?
                 ORDER BY civ.civ_id', [$lang]
            ) as $r) {
                $r = (array) $r;
                $civilities[] = ['id' => (int) $r['civ_id'], 'name' => (string) ($r['civt_min_name'] ?? ('#' . $r['civ_id']))];
            }

            $languages = [];
            foreach ($db->query('SELECT lang_id, lang_name FROM melis_core_lang ORDER BY lang_name', []) as $r) {
                $r = (array) $r;
                $languages[] = ['id' => (int) $r['lang_id'], 'name' => (string) $r['lang_name']];
            }

            return $this->jsonResponse(['success' => true, 'data' => ['accounts' => $accounts, 'civilities' => $civilities, 'languages' => $languages]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /contacts/:id ────────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $lang = $this->langId();
            $rows = iterator_to_array($db->query(
                "SELECT p.cper_id, p.cper_status, p.cper_type, p.cper_civility, p.cper_firstname,
                        p.cper_name, p.cper_middle_name, p.cper_lang_id, p.cper_email,
                        p.cper_client_id, p.cper_job_title, p.cper_job_service,
                        p.cper_tel_mobile, p.cper_tel_landline,
                        p.cper_tags, p.cper_date_creation, p.cper_date_edit,
                        c.cli_name, civ.civt_min_name AS civility_name,
                        c_comp.ccomp_name AS dyn_company_name,
                        c_dcp.cper_firstname AS dyn_contact_firstname, c_dcp.cper_name AS dyn_contact_name
                 FROM melis_ecom_client_person p
                 LEFT JOIN melis_ecom_client c ON c.cli_id = p.cper_client_id
                 LEFT JOIN melis_ecom_civility_trans civ ON civ.civt_civ_id = p.cper_civility AND civ.civt_lang_id = ?
                 " . $this->accountNameJoinSql('c') . "
                 WHERE p.cper_id = ? LIMIT 1",
                [$lang, $id]
            ));

            if (empty($rows)) { return $this->jsonResponse(['success' => false, 'error' => 'Contact not found'], 404); }

            return $this->jsonResponse(['success' => true, 'data' => $this->formatContact((array) $rows[0], $this->accountNameMode())]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── POST /contacts/save ──────────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $body = json_decode($this->getRequest()->getContent(), true) ?? [];
            $id   = (int) ($body['id'] ?? 0);

            $status      = (int) (!empty($body['status']) ? 1 : 0);
            $type        = trim((string) ($body['type'] ?? ''));
            $civility    = (int) ($body['civility'] ?? 0);
            $firstname   = trim((string) ($body['firstname'] ?? ''));
            $name        = trim((string) ($body['name'] ?? ''));
            $middleName  = trim((string) ($body['middleName'] ?? ''));
            $langId      = (int) ($body['langId'] ?? 0);
            $email       = trim((string) ($body['email'] ?? ''));
            $accountId   = (int) ($body['accountId'] ?? 0);
            $jobTitle    = trim((string) ($body['jobTitle'] ?? ''));
            $jobService  = trim((string) ($body['jobService'] ?? ''));
            $telMobile   = trim((string) ($body['telMobile'] ?? ''));
            $telLandline = trim((string) ($body['telLandline'] ?? ''));
            $password    = trim((string) ($body['password'] ?? ''));
            $tags        = trim((string) ($body['tags'] ?? ''));

            // Pour un contact « company », le nom (cper_name) n'est pas requis — le firstname
            // (relabellisé « Société » côté UI) porte seul l'identité. Cf. legacy validateContact().
            if ($type !== 'company' && $name === '') {
                return $this->jsonResponse(['success' => false, 'error' => 'Le nom est obligatoire.'], 400);
            }
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Un email valide est obligatoire.'], 400);
            }

            $db  = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $now = date('Y-m-d H:i:s');

            if ($id > 0) {
                $setSql = 'cper_status = ?, cper_type = ?, cper_civility = ?, cper_firstname = ?, cper_name = ?,
                           cper_middle_name = ?, cper_lang_id = ?, cper_email = ?, cper_client_id = ?,
                           cper_job_title = ?, cper_job_service = ?, cper_tel_mobile = ?, cper_tel_landline = ?,
                           cper_tags = ?, cper_date_edit = ?';
                $params = [$status, $type, $civility, $firstname, $name,
                           $middleName, $langId ?: null, $email, $accountId ?: null,
                           $jobTitle, $jobService, $telMobile, $telLandline, $tags, $now];
                if ($password !== '') { $setSql .= ', cper_password = ?'; $params[] = md5($password); }
                $params[] = $id;
                $db->query("UPDATE melis_ecom_client_person SET $setSql WHERE cper_id = ?", $params);

                $this->getEventManager()->trigger('meliscommerce_contact_save_end', $this, [
                    'success' => true, 'textTitle' => 'Contact', 'textMessage' => 'tr_meliscommerce_contact_save_success',
                    'typeCode' => 'ECOM_CONTACT_UPDATE', 'itemId' => $id,
                ]);

                return $this->jsonResponse(['success' => true, 'data' => ['id' => $id]]);
            }

            $db->query(
                'INSERT INTO melis_ecom_client_person
                    (cper_client_id, cper_lang_id, cper_type, cper_status, cper_civility, cper_email,
                     cper_name, cper_firstname, cper_middle_name, cper_job_title, cper_job_service,
                     cper_tel_mobile, cper_tel_landline, cper_password, cper_tags, cper_date_creation)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [$accountId ?: null, $langId ?: $this->langId(), $type, $status, $civility, $email,
                 $name, $firstname, $middleName, $jobTitle, $jobService,
                 $telMobile, $telLandline, $password !== '' ? md5($password) : '', $tags, $now]
            );
            $newId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];

            $this->getEventManager()->trigger('meliscommerce_contact_save_end', $this, [
                'success' => true, 'textTitle' => 'Contact', 'textMessage' => 'tr_meliscommerce_contact_save_success',
                'typeCode' => 'ECOM_CONTACT_ADD', 'itemId' => $newId,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => ['id' => $newId]], 201);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── DELETE /contacts/delete/:id ──────────────────────────────────────────
    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $db->query('DELETE FROM melis_ecom_client_person WHERE cper_id = ?', [$id]);

            $this->getEventManager()->trigger('meliscommerce_contact_delete_end', $this, [
                'success' => true, 'textTitle' => 'Contact', 'textMessage' => 'tr_meliscommerce_contact_delete_contact_message_success',
                'typeCode' => 'ECOM_CONTACT_DELETE', 'itemId' => $id,
            ]);

            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── Onglets de la fiche contact (sous-ressources) ────────────────────────
    private function routeId(): int { return (int) $this->params()->fromRoute('id', 0); }

    // GET /contacts/:id/addresses — adresses du contact (cadd_client_person), champs COMPLETS (édition staged)
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
                 WHERE a.cadd_client_person = ? ORDER BY a.cadd_id DESC', [$this->langId(), $this->routeId()]
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

    // GET /contacts/address-options — types d'adresse + civilités (mêmes que côté compte)
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

    // POST /contacts/:id/addresses/save — remplace les adresses du contact (upsert + suppression des retirées)
    public function addressesSaveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $contactId = $this->routeId();
            // cadd_client_id est NOT NULL → on rattache au compte par défaut du contact (0 si aucun).
            $cliRows = iterator_to_array($this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface')->query('SELECT cper_client_id FROM melis_ecom_client_person WHERE cper_id = ? LIMIT 1', [$contactId]));
            $clientId = $cliRows ? (int) ((array) $cliRows[0])['cper_client_id'] : 0;

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
                    $db->query("UPDATE melis_ecom_client_address SET $set WHERE cadd_id = ? AND cadd_client_person = ?", array_merge(array_values($vals), [$id, $contactId]));
                    $kept[] = $id;
                } else {
                    $names = array_keys($vals);
                    $ph    = implode(', ', array_fill(0, count($names) + 3, '?'));
                    $db->query('INSERT INTO melis_ecom_client_address (cadd_client_id, cadd_client_person, ' . implode(', ', $names) . ', cadd_creation_date) VALUES (' . $ph . ')', array_merge([$clientId, $contactId], array_values($vals), [$now]));
                    $kept[] = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];
                }
            }
            foreach ($db->query('SELECT cadd_id FROM melis_ecom_client_address WHERE cadd_client_person = ?', [$contactId]) as $r) {
                $eid = (int) ((array) $r)['cadd_id'];
                if (!in_array($eid, $kept, true)) { $db->query('DELETE FROM melis_ecom_client_address WHERE cadd_id = ?', [$eid]); }
            }
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // GET /contacts/:id/associations — comptes associés au contact (melis_ecom_client_person_rel)
    public function associationsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $mode = $this->accountNameMode();
            $rows = $db->query(
                'SELECT c.cli_id, c.cli_name, c.cli_status, cpr.cpr_default_client AS is_default_account,
                        (SELECT car.car_default_person FROM melis_ecom_client_account_rel car
                         WHERE car.car_client_id = cpr.cpr_client_id AND car.car_client_person_id = cpr.cpr_client_person_id LIMIT 1) AS is_main,
                        c_comp.ccomp_name AS dyn_company_name,
                        c_dcp.cper_firstname AS dyn_contact_firstname, c_dcp.cper_name AS dyn_contact_name
                 FROM melis_ecom_client_person_rel cpr
                 JOIN melis_ecom_client c ON c.cli_id = cpr.cpr_client_id
                 ' . $this->accountNameJoinSql('c') . '
                 WHERE cpr.cpr_client_person_id = ? ORDER BY c.cli_name', [$this->routeId()]
            );
            $items = [];
            foreach ($rows as $r) {
                $r = (array) $r;
                $items[] = [
                    'id' => (int) $r['cli_id'], 'name' => $this->resolveAccountName($r, $mode), 'status' => (int) $r['cli_status'],
                    'isDefaultAccount' => (int) ($r['is_default_account'] ?? 0), 'isMain' => (int) ($r['is_main'] ?? 0),
                ];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['items' => $items]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // POST /contacts/:id/associations/:accountId/default — marque CE compte comme compte par défaut du contact
    public function associationDefaultAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $contactId = $this->routeId();
            $accountId = (int) $this->params()->fromRoute('accountId', 0);
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $db->query('UPDATE melis_ecom_client_person_rel SET cpr_default_client = 0 WHERE cpr_client_person_id = ?', [$contactId]);
            $db->query('UPDATE melis_ecom_client_person_rel SET cpr_default_client = 1 WHERE cpr_client_person_id = ? AND cpr_client_id = ?', [$contactId, $accountId]);
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // POST /contacts/:id/associations/link {accountId} — lie un compte au contact (rel)
    public function associationLinkAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $contactId = $this->routeId();
            $body      = json_decode($this->getRequest()->getContent(), true) ?? [];
            $accountId = (int) ($body['accountId'] ?? 0);
            if ($accountId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid account'], 400); }
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $this->linkClientPerson($db, $accountId, $contactId);
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // DELETE /contacts/:id/associations/:accountId — délie un compte du contact
    public function associationUnlinkAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $this->unlinkClientPerson($db, (int) $this->params()->fromRoute('accountId', 0), $this->routeId());
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    /** Lie un contact à un compte dans LES DEUX tables symétriques (car + cpr), dédupliqué. */
    private function linkClientPerson($db, int $clientId, int $contactId): void
    {
        if (!iterator_to_array($db->query('SELECT car_id FROM melis_ecom_client_account_rel WHERE car_client_id = ? AND car_client_person_id = ? LIMIT 1', [$clientId, $contactId]))) {
            $db->query('INSERT INTO melis_ecom_client_account_rel (car_client_id, car_client_person_id, car_default_person) VALUES (?, ?, 0)', [$clientId, $contactId]);
        }
        if (!iterator_to_array($db->query('SELECT cpr_id FROM melis_ecom_client_person_rel WHERE cpr_client_id = ? AND cpr_client_person_id = ? LIMIT 1', [$clientId, $contactId]))) {
            // Matches legacy's MelisComContactService::linkAccountContact(): if the contact has no
            // OTHER account association yet, this new one becomes its default account.
            $hasOther = (bool) iterator_to_array($db->query('SELECT cpr_id FROM melis_ecom_client_person_rel WHERE cpr_client_person_id = ? LIMIT 1', [$contactId]));
            $isDefault = $hasOther ? 0 : 1;
            $db->query('INSERT INTO melis_ecom_client_person_rel (cpr_client_id, cpr_client_person_id, cpr_default_client) VALUES (?, ?, ?)', [$clientId, $contactId, $isDefault]);
        }
    }

    /** Délie un contact d'un compte dans les deux tables. */
    private function unlinkClientPerson($db, int $clientId, int $contactId): void
    {
        $db->query('DELETE FROM melis_ecom_client_account_rel WHERE car_client_id = ? AND car_client_person_id = ?', [$clientId, $contactId]);
        $db->query('DELETE FROM melis_ecom_client_person_rel WHERE cpr_client_id = ? AND cpr_client_person_id = ?', [$clientId, $contactId]);
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────
    private function formatContact(array $r, string $mode = 'manual_input'): array
    {
        return [
            'id'           => (int)    $r['cper_id'],
            'status'       => (int)    $r['cper_status'],
            'type'         => (string) ($r['cper_type'] ?? ''),
            'civility'     => (int)    ($r['cper_civility'] ?? 0),
            'civilityName' => (string) ($r['civility_name'] ?? ''),
            'firstname'    => (string) ($r['cper_firstname'] ?? ''),
            'name'         => (string) ($r['cper_name'] ?? ''),
            'middleName'   => (string) ($r['cper_middle_name'] ?? ''),
            'langId'       => (int)    ($r['cper_lang_id'] ?? 0),
            'email'        => (string) ($r['cper_email'] ?? ''),
            'accountId'    => (int)    ($r['cper_client_id'] ?? 0),
            'accountName'  => $this->resolveAccountName($r, $mode),
            'jobTitle'     => (string) ($r['cper_job_title'] ?? ''),
            'jobService'   => (string) ($r['cper_job_service'] ?? ''),
            'telMobile'    => (string) ($r['cper_tel_mobile'] ?? ''),
            'telLandline'  => (string) ($r['cper_tel_landline'] ?? ''),
            'tags'         => (string) ($r['cper_tags'] ?? ''),
            'dateCreation' => $r['cper_date_creation'] ?? null,
            'dateEdit'     => $r['cper_date_edit'] ?? null,
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
