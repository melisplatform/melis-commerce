<?php

namespace MelisCommerce\Controller\ReactApi;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * API REST pour l'outil Paramètres MelisCommerce (page unique, PAS de liste).
 *
 * Deux sections indépendantes, sauvées ensemble par un seul bouton Save :
 *   - "Properties" : seuil d'alerte de stock GLOBAL (melis_ecom_stock_email_alert,
 *     lignes où sea_prd_id = -1 = réglage par défaut, un email destinataire par ligne)
 *     — réutilise MelisComStockEmailAlertService (même service que l'onglet Alert du
 *     formulaire Produit, qui appelle la même action avec un productId réel au lieu de -1).
 *   - "Accounts" : type de nom de compte affiché dans MelisCommerce (melis_ecom_settings_account,
 *     ligne UNIQUE sa_type ∈ manual_input|company_name|contact_name) — pas de service dédié
 *     en legacy (accès direct à la table), on reste sur du SQL brut ici aussi.
 *
 * Routes :
 *   GET  /melis/react-api/settings          → état courant des deux sections
 *   GET  /melis/react-api/settings/options   → utilisateurs (pour le sélecteur de destinataire)
 *   POST /melis/react-api/settings/save      → enregistre les deux sections
 */
class MelisComReactApiSettingsController extends MelisAbstractActionController
{
    private const MELIS_KEY = 'meliscommerce_settings_page';
    /** sea_prd_id réservé au réglage GLOBAL (par opposition à un seuil par produit, géré ailleurs). */
    private const GLOBAL_PRD_ID = -1;

    // ─── GET /settings ──────────────────────────────────────────────────────
    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');

            // Niveau global = ligne DÉDIÉE sea_id = -1 (comme le legacy : getEntryById(-1) /
            // setDefaultValues, lue par checkStockLevelByOrderId). Les destinataires sont les AUTRES
            // lignes (sea_prd_id = -1 ET sea_id <> -1).
            $stockLevelAlert = null;
            $lvlRows = iterator_to_array($db->query('SELECT sea_stock_level_alert FROM melis_ecom_stock_email_alert WHERE sea_id = ? LIMIT 1', [self::GLOBAL_PRD_ID]));
            if (!empty($lvlRows)) {
                $v = ((array) $lvlRows[0])['sea_stock_level_alert'];
                $stockLevelAlert = $v !== null ? (int) $v : null;
            }

            $recipients = [];
            foreach ($db->query(
                'SELECT sea.sea_id, sea.sea_email, sea.sea_user_id, u.usr_firstname, u.usr_lastname
                 FROM melis_ecom_stock_email_alert sea
                 LEFT JOIN melis_core_user u ON u.usr_id = sea.sea_user_id
                 WHERE sea.sea_prd_id = ? AND sea.sea_id <> ? ORDER BY sea.sea_id ASC',
                [self::GLOBAL_PRD_ID, self::GLOBAL_PRD_ID]
            ) as $r) {
                $r = (array) $r;
                $name = trim(((string) ($r['usr_firstname'] ?? '')) . ' ' . ((string) ($r['usr_lastname'] ?? '')));
                $recipients[] = [
                    'id' => (int) $r['sea_id'],
                    'email' => (string) ($r['sea_email'] ?? ''),
                    'userId' => $r['sea_user_id'] !== null ? (int) $r['sea_user_id'] : null,
                    'userName' => $name !== '' ? $name : null,
                ];
            }

            $accountType = 'manual_input';
            $accRows = iterator_to_array($db->query('SELECT sa_type FROM melis_ecom_settings_account LIMIT 1', []));
            if (!empty($accRows)) { $accountType = (string) ((array) $accRows[0])['sa_type']; }

            return $this->jsonResponse(['success' => true, 'data' => [
                'stockLevelAlert' => $stockLevelAlert,
                'recipients' => $recipients,
                'accountType' => $accountType,
            ]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── GET /settings/options ──────────────────────────────────────────────
    public function optionsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $users = [];
            foreach ($db->query('SELECT usr_id, usr_firstname, usr_lastname, usr_email FROM melis_core_user WHERE usr_email IS NOT NULL AND usr_email <> "" ORDER BY usr_firstname, usr_lastname', []) as $r) {
                $r = (array) $r;
                $name = trim(((string) ($r['usr_firstname'] ?? '')) . ' ' . ((string) ($r['usr_lastname'] ?? '')));
                $users[] = ['id' => (int) $r['usr_id'], 'name' => $name !== '' ? $name : (string) $r['usr_email'], 'email' => (string) ($r['usr_email'] ?? '')];
            }
            return $this->jsonResponse(['success' => true, 'data' => ['users' => $users]]);
        } catch (\Throwable $e) { return $this->errorResponse($e); }
    }

    // ─── POST /settings/save ────────────────────────────────────────────────
    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $b = json_decode((string) $this->getRequest()->getContent(), true) ?? [];

            $stockLevelAlert = ($b['stockLevelAlert'] ?? '') === '' || $b['stockLevelAlert'] === null ? null : (int) $b['stockLevelAlert'];
            $recipients = is_array($b['recipients'] ?? null) ? $b['recipients'] : [];
            $accountType = (string) ($b['accountType'] ?? 'manual_input');
            if (!in_array($accountType, ['manual_input', 'company_name', 'contact_name'], true)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Invalid account type.'], 400);
            }

            foreach ($recipients as $r) {
                $email = trim((string) ($r['email'] ?? ''));
                if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $this->jsonResponse(['success' => false, 'error' => "Adresse email invalide : $email"], 400);
                }
            }

            $db = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
            $stockSvc = $this->getServiceManager()->get('MelisComStockEmailAlertService');

            ob_start();

            // 1) Niveau global : ligne DÉDIÉE sea_id = -1 (upsert), comme le legacy setDefaultValues.
            //    C'est CETTE valeur que lit le mécanisme d'alerte (checkStockLevelByOrderId → getEntryById(-1)).
            $hasDefault = !empty(iterator_to_array($db->query('SELECT sea_id FROM melis_ecom_stock_email_alert WHERE sea_id = ? LIMIT 1', [self::GLOBAL_PRD_ID])));
            if ($hasDefault) {
                $db->query('UPDATE melis_ecom_stock_email_alert SET sea_stock_level_alert = ?, sea_prd_id = ? WHERE sea_id = ?', [$stockLevelAlert, self::GLOBAL_PRD_ID, self::GLOBAL_PRD_ID]);
            } else {
                $db->query('INSERT INTO melis_ecom_stock_email_alert (sea_id, sea_prd_id, sea_stock_level_alert) VALUES (?, ?, ?)', [self::GLOBAL_PRD_ID, self::GLOBAL_PRD_ID, $stockLevelAlert]);
            }

            // 2) Destinataires : lignes sea_prd_id = -1 ET sea_id <> -1 (jamais la ligne par défaut).
            $existingIds = [];
            foreach ($db->query('SELECT sea_id FROM melis_ecom_stock_email_alert WHERE sea_prd_id = ? AND sea_id <> ?', [self::GLOBAL_PRD_ID, self::GLOBAL_PRD_ID]) as $r) {
                $existingIds[] = (int) ((array) $r)['sea_id'];
            }

            $submittedIds = [];
            foreach ($recipients as $r) {
                $email = trim((string) ($r['email'] ?? ''));
                if ($email === '') { continue; }
                $id = (int) ($r['id'] ?? 0);
                $row = [
                    'sea_prd_id' => self::GLOBAL_PRD_ID,
                    'sea_stock_level_alert' => $stockLevelAlert,
                    'sea_email' => $email,
                    'sea_user_id' => !empty($r['userId']) ? (int) $r['userId'] : null,
                ];
                $newId = (int) $stockSvc->SaveStockEmailAlert($row, $id ?: null);
                $submittedIds[] = $id ?: $newId;
            }

            foreach (array_diff($existingIds, $submittedIds) as $staleId) {
                $stockSvc->deleteStockEmailAlertById($staleId);
            }

            $accRows = iterator_to_array($db->query('SELECT sa_id FROM melis_ecom_settings_account LIMIT 1', []));
            if (!empty($accRows)) {
                $saId = (int) ((array) $accRows[0])['sa_id'];
                $db->query('UPDATE melis_ecom_settings_account SET sa_type = ? WHERE sa_id = ?', [$accountType, $saId]);
            } else {
                $db->query('INSERT INTO melis_ecom_settings_account (sa_type) VALUES (?)', [$accountType]);
            }

            ob_end_clean();

            $this->getEventManager()->trigger('meliscommerce_settings_save_end', $this, [
                'success' => true, 'textTitle' => 'tr_meliscommerce_settings', 'textMessage' => 'tr_meliscommerce_settings_save_success',
                'typeCode' => 'ECOM_SETTINGS_UPDATE', 'itemId' => null,
            ]);

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
