<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\ReactApi;

/**
 * Account name as shown in « Comptes clients »: it follows the Commerce setting « Nom de compte »
 * (melis_ecom_settings_account.sa_type), like MelisComReactApiAccountController and the legacy
 * MelisComClientService::getAccountNameSetting(). Coupons and orders showed the stored cli_name instead,
 * so the same account had two names (Mantis #0011195, #0011199).
 */
trait MelisComReactApiAccountNameTrait
{
    /** manual_input | company_name | contact_name */
    protected function accountDisplayNameMode(): string
    {
        $db   = $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
        $rows = iterator_to_array($db->query('SELECT sa_type FROM melis_ecom_settings_account LIMIT 1', []));
        $type = $rows ? (string) ((array) $rows[0])['sa_type'] : 'manual_input';

        return in_array($type, ['manual_input', 'company_name', 'contact_name'], true) ? $type : 'manual_input';
    }

    /**
     * SQL expression of the displayed account name for the account row aliased $alias (melis_ecom_client).
     * Empty when the company / default contact the setting points to is not filled, as in « Comptes clients ».
     */
    protected function accountDisplayNameSql(string $alias = 'c', ?string $mode = null): string
    {
        switch ($mode ?? $this->accountDisplayNameMode()) {
            case 'company_name':
                return "COALESCE((SELECT dn_comp.ccomp_name FROM melis_ecom_client_company dn_comp
                                   WHERE dn_comp.ccomp_client_id = $alias.cli_id LIMIT 1), '')";
            case 'contact_name':
                return "COALESCE((SELECT TRIM(CONCAT(COALESCE(dn_p.cper_firstname,''),' ',COALESCE(dn_p.cper_name,'')))
                                    FROM melis_ecom_client_account_rel dn_r
                                    JOIN melis_ecom_client_person dn_p ON dn_p.cper_id = dn_r.car_client_person_id
                                   WHERE dn_r.car_client_id = $alias.cli_id AND dn_r.car_default_person = 1 LIMIT 1), '')";
            default:
                return "COALESCE($alias.cli_name, '')";
        }
    }
}
