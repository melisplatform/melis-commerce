<?php

namespace MelisCommerce\Service;

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

use MelisCore\Service\MelisCoreGdprAutoDeleteInterface;
use MelisCore\Service\MelisCoreGdprAutoDeleteService as Gdpr;
use MelisCore\Service\MelisGeneralService;
use Laminas\Session\Container;

class MelisCommerceGdprAutoDeleteService extends MelisGeneralService implements MelisCoreGdprAutoDeleteInterface
{
    /**
     * @var
     */
    const MODULE_NAME = "melis-commerce";

    /**
     * @return array
     */
    public function getListOfTags()
    {
        return [
            Gdpr::TAG_LIST_KEY => [
                self::MODULE_NAME=> [
                    Gdpr::TAG_KEY  => $this->getServiceManager()->get('MelisConfig')->getItem('/meliscommerce/gdpr/tags')
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getWarningListOfUsers()
    {
        return [
            Gdpr::WARNING_LIST_KEY => [
                self::MODULE_NAME => $this->getUsersWithTagsAndConfig(Gdpr::WARNING_LIST_KEY)
            ]
        ];
    }

    /**
     * @return array
     */
    public function getSecondWarningListOfUsers()
    {
        return [
            Gdpr::SECOND_WARNING_LIST_KEY => [
                self::MODULE_NAME => $this->getUsersWithTagsAndConfig(Gdpr::SECOND_WARNING_LIST_KEY)
            ]
        ];
    }

    /**
     * retrieve user by validation key
     *
     * @param $validationKey
     * @return mixed
     */
    public function getUserPerValidationKey($validationKey)
    {
        // get all config users first warning list and second warning list
        $configUsers = array_merge($this->getWarningListOfUsers(),$this->getSecondWarningListOfUsers());
        foreach ($configUsers as $warningType => $modules) {
            foreach ($modules as $module => $emails) {
                foreach ($emails as $id => $emailOpts) {
                    // check user existence
                    if (isset($emailOpts['config']['last_date']) && !empty($emailOpts['config']['last_date'])) {
                        // search for the validation key
                        if ($emailOpts['config']['validationKey'] == $validationKey) {
                            // return user data
                            $userData = $this->getUserById($id);
                            if (! empty($userData)) {
                                // include user config options
                                $userData->config = $emailOpts['config'];

                                return $userData;
                            }
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * update user last date
     *
     * @param $validationKey
     * @return mixed
     */
    public function updateGdprUserStatus($validationKey)
    {
        $id = null;
        // get user
        $user = $this->getUserPerValidationKey($validationKey);
        if (! empty($user)) {
            // update the last date of the user
            $this->getServiceManager()->get('MelisEcomClientPersonTable')->save(['cper_last_login' => date('Y-m-d H:i:s')], $user->cper_id);
            $id = $user->cper_id ;
        }

        return $id;
    }

    /**
     * Removal of users who have missed the deadline, returns the list of users deleted with their tags
     *
     * @param $autoDeleteConfig
     * @return array
     */
    public function removeOldUnvalidatedUsers($autoDeleteConfig)
    {
        $anoUsers = [
            self::MODULE_NAME => []
        ];
        if ($autoDeleteConfig['mgdprc_module_name'] == self::MODULE_NAME) {
            foreach ($this->getUsersWithTagsAndConfig("user-deleted") as $id => $val) {
                // check if user belongs to the config site
                if ($autoDeleteConfig['mgdprc_site_id'] == $val['config']['site_id']) {
                    // delete if users days of inactivity is already pas sed the set
                    if ($this->getDaysDiff($val['config']['last_date'], date('Y-m-d')) > $autoDeleteConfig['mgdprc_delete_days']) {
                        // get user data
                        $data = (array)$this->getUserById($id);
                        if (!empty($data)) {

                            $contactData = $data;

                            $configCols = $this->getServiceManager()->get('MelisConfig')->getItem('/meliscommerce/gdpr/columns');

                            $tmpVal = Gdpr::ANO_VALUE; 

                            $data['cper_status'] = 0; // deactivating contat
                            $data['cper_password'] = $tmpVal.uniqid(); // removing password and recovery key
                            $data['cper_password_recovery_key'] = null;
                            $data['cper_anonymized'] = 1; // flag as anonymized
                            $columns = $configCols['override_columns']['contact'] ?? $configCols['contact'];
                            foreach ($columns As $col)
                                $data[$col] = $tmpVal;
                            
                            // perform updates
                            $this->getServiceManager()->get('MelisEcomClientPersonTable')->save($data, $id);

                            // perform deletion on contact emails
                            $this->getServiceManager()->get('MelisEcomClientPersonEmailsTable')
                                ->deleteByField('cpmail_email', $contactData['cper_email']);

                            // Contact addresses
                            $personAddressTbl = $this->getServiceManager()->get('MelisEcomClientAddressTable');
                            $address = $personAddressTbl->getEntryByField('cadd_client_person', $id)->toArray();

                            $data = [];
                            $columns = $configCols['override_columns']['address'] ?? $configCols['address'];
                            foreach ($columns As $col)
                                $data[$col] = $tmpVal;

                            // perform updates
                            foreach ($address As $add) 
                                $personAddressTbl->save($data, $add->cadd_id);

                            // return deleted email with its opeions
                            $anoUsers[self::MODULE_NAME][$id] = $val;
                            // trigger event for other modules
                            $this->getEventManager()->trigger('melis_commerce_gdpr_auto_delete_action_delete', $this, $data);
                        }
                    }
                }
            }
        }

        return $anoUsers;
    }

    /**
     * calculatey the diffrence of two dates in days
     *
     * @param $date1
     * @param $date2
     * @return float
     */
    private function getDaysDiff($date1, $date2)
    {
        return $this->getServiceManager()->get('MelisCoreGdprAutoDeleteService')->getDaysDiff($date1, $date2);
    }

    /**
     * @param  $type
     * @return array
     */
    private function getUsersWithTagsAndConfig($type)
    {
        $container = new Container('melis_auto_delete_gdpr');
        $clients = $this->getServiceManager()->get('MelisEcomClientPersonTable')->fetchAll()->toArray();
        // get all tags
        $tags = $this->getServiceManager()->get('MelisConfig')->getItem('/meliscommerce/gdpr/tags');
        $userList = [];
        if (! empty($clients)) {
            if ($container['config']['mgdprc_module_name'] == self::MODULE_NAME) {
                $langAvailable = $container['config']['available_lang']['alert_email'] ?? 1;
                // if for delete
                if ($type == 'user-deleted'){
                    $langAvailable = $container['config']['available_lang']['delete_email'] ?? 1;
                }

                foreach ($clients as $i => $data) {
                    // do not include data that was already anonymized
                    if (!$data['cper_anonymized'] && (!is_null($data['cper_last_login']) || !is_null($data['cper_date_creation']))) {
                        
                        // tags
                        $assigningValueOfTags = $this->assigningValueOfTags($tags, $data);
                        $config = [
                            'site_id'    => 1,  // TODO 
                            'lang'       => $langAvailable, 
                            'last_date'  => $data['cper_last_login'] ?? $data['cper_date_creation'],
                            'account_id' => $data['cper_id'],
                            'validationKey' => md5(implode('', array_keys($assigningValueOfTags)) . $type . $data['cper_id']),
                            'email' => $data['cper_email'] ?? null
                        ];

                        $userList[$data['cper_id']] = [
                            // append tags with value
                            'tags' => $assigningValueOfTags, 
                            // append config
                            'config' => $config 
                        ]; 

                        // trigger event for other modules
                        $this->getEventManager()->trigger('melis_commerce_gdpr_auto_delete_get_users_tags_configs', $this, $userList);
                    } 
                }
            }
        }

        return $userList;
    }

    /**
     * @param $email
     * @return mixed
     */
    private function getUserByEmail($email)    
    {
        return $this->getServiceManager()->get('MelisEcomClientPersonTable')->getEntryByField('cper_email', $email)->current();
    }
    /**
     * @param $id
     *
     */
    public function getUserById($id)
    {
        return $this->getServiceManager()->get('MelisEcomClientPersonTable')->getEntryById($id)->current();
    }


    /**
     * @param $tags
     * @param $userData
     * @return array
     */
    private function assigningValueOfTags($tags, $userData)
    {
        foreach ($tags as $tag => $dbField) {
            if (isset($userData[$dbField])) {
                $tags[$tag] = $userData[$dbField] ?? null;
            } else {
                if ($tags[$tag] != "%revalidation_link%") {
                    // for tags that are not the db field
                    $tags[$tag] = null;
                }
            }
        }

        return $tags;
    }
}
