<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Session\SessionManager;
use Laminas\Authentication\Adapter\DbTable as AuthAdapter;
use Laminas\Authentication\Result;
use Laminas\Session\Config\SessionConfig;
use Laminas\Stdlib\ArrayUtils;
/**
 * 
 * This service handles the Authentication system of MelisCommerce.
 *
 */
class MelisComAuthenticationService extends Session
{
    /**
     * @var Laminas\ServiceManager\ServiceManager $serviceManager
     */
    protected $serviceManager;

    protected $authenticationService;
    protected $sessionManager;
    protected $session;
    
    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
        $this->sessionManager = new SessionManager();

        if (!$this->hasIdentity()) {
            /**
             * Getting the current Site module name,
             * and use as Session name of the Site
             */
            $moduleName = getenv('MELIS_MODULE');
            
            // Session storage name using module name of the site
            $this->session = new Session($moduleName);
            // Site session name will set to authentication Service
            $this->authenticationService->setStorage($this->session);
        }
    }

    /**
     * @param ServiceManager $service
     */
    public function setServiceManager(ServiceManager $service)
    {
        $this->serviceManager = $service;
    }

    /**
     * @return Laminas\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
    
    public function getStorage()
    {
        return $this->authenticationService->getStorage();
    }

    /**
     * @param $email
     * @param $password
     * @param bool $rememberMe
     * @return array
     * @throws \Laminas\Authentication\Exception\ExceptionInterface
     */
    public function login($email, $password, $rememberMe = false)
    {
        $success = 0;

        $translator = $this->getServiceManager()->get('translator');
        $clientSrv = $this->getServiceManager()->get('MelisComClientService');
        $contactSrv = $this->getServiceManager()->get('MelisComContactService');
        $clientInfo = $clientSrv->getClientPersonByEmail($email);

        //check if email exist
        if(!empty($clientInfo))
        {
            $getContactAssociatedAccounts = $contactSrv->getContactDefaultAccount($clientInfo->cper_id)->toArray();
            /**
             * Contact must have a default account
             */
            if(!empty($getContactAssociatedAccounts)) {
                //check password
                $isCorrect = $this->isPasswordCorrect($password, $clientInfo->cper_password);
                if ($isCorrect) {
                    $dbAdapter = $this->getServiceManager()->get('Laminas\Db\Adapter\Adapter');
                    $authAdapter = new AuthAdapter(
                        $dbAdapter,
                        'melis_ecom_client_person', // there is a method setTableName to do the same
                        'cper_email', // there is a method setIdentityColumn to do the same
                        'cper_password' // there is a method setCredentialColumn to do the same
                    );

                    $authAdapter->setIdentity($email)->setCredential($clientInfo->cper_password);

                    $result = $this->authenticationService->authenticate($authAdapter);
                    switch ($result->getCode()) {
                        case Result::SUCCESS:
                            $storage = $this->getStorage();

                            $personIdentity = $authAdapter->getResultRowObject(
                                null,
                                ['cper_password'] // removing the password from the result object
                            );

                            $personIdentity->clientKey = $this->getId();

                            // Client group
                            $groupId = 1;//general
                            $clientId = 0;
                            foreach($getContactAssociatedAccounts as $key => $val){
                                $cliData = $clientSrv->getClientById($val['cli_id']);
                                if(!empty($cliData)) {//first account to find
                                    $groupId = $cliData->cli_group_id ?? 1;
                                    $clientId = $cliData->cli_id;
                                    break;
                                }
                            }
                            $personIdentity->client_group = $groupId;
                            $personIdentity->client_id = $clientId;

                            $storage->write($personIdentity);

                            $config = $this->getServiceManager()->get('config');
                            $ecomClientConfig = $config['plugins']['meliscommerce']['datas']['default']['session'];

                            /**
                             * Getting the ttl for Session expiry from config
                             * Ttl of session will depend on login option "remember me"
                             */
                            $ecomDefaultTtl = ($rememberMe) ? $ecomClientConfig['remember_me_ttl'] : $ecomClientConfig['default_ttl'];
                            $this->sessionManager->rememberMe($ecomDefaultTtl);

                            $message = $translator->translate('tr_meliscommerce_plugin_login_success');
                            $success = 1;
                            break;
                        default:
                            $message = $translator->translate('tr_meliscommerce_plugin_login_invalid_email_or_password');
                            break;
                    }
                } else {
                    $message = $translator->translate('tr_meliscommerce_plugin_login_invalid_email_or_password');
                }
            }else{
                $message = $translator->translate('tr_meliscommerce_plugin_login_invalid_email_or_password');
            }
        }else{
            $message = $translator->translate('tr_meliscommerce_plugin_login_invalid_email_or_password');
        }

        return array(
            'success' => $success,
            'message' => $message
        );
    }

    public function getClientId()
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData))
            return $sessionData->client_id;
        
        return null;
    }

    public function getClientGroup()
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData))
            return $sessionData->client_group;
        
        return null;
    }
    
    public function getPersonId()
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData))
            return $sessionData->cper_id;
        
        return null;
    }
    
    public function getClientKey()
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData))
            return $sessionData->clientKey;
        
        return null;
    }
    
    public function getClientPersonSessDataByField($field)
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData) && !is_null($field))
            return $sessionData->$field;
        
        return null;
    }
    
    public function getPersonName()
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData))
            return $sessionData->cper_firstname.' '.$sessionData->cper_name;
        
        return null;
    }
    
    public function hasIdentity()
    {
        return $this->authenticationService->hasIdentity();
    }
    
    public function getIdentity()
    {
        return $this->authenticationService->getIdentity();
    }
    
    public function setId($id)
    {
        $this->sessionManager->setId($id);
    }
    
    public function getId()
    {
        return $this->sessionManager->getId();
    }
    
    public function destroy()
    {
        $this->sessionManager->destroy();
    }
    
    public function logout()
    {
        $this->authenticationService->clearIdentity();
        $this->sessionManager->forgetMe();
    }

    public function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function isPasswordCorrect($providedPassword, $storedHashPassword)
    {
        return password_verify($providedPassword, $storedHashPassword);
    }
}