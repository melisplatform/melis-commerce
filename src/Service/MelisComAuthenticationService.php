<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Form\Annotation\Object;
use Zend\Session\SessionManager;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\Result;
use Zend\Session\Config\SessionConfig;
use Zend\Stdlib\ArrayUtils;
/**
 * 
 * This service handles the Authentication system of MelisCommerce.
 *
 */
class MelisComAuthenticationService extends Session
{
    protected $serviceLocator;
    protected $authenticationService;
    protected $sessionManager;
    protected $session;
    
    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
        $this->sessionManager = new SessionManager();
        
        if (!$this->hasIdentity())
        {
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
    
    public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
    }
    
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
    public function getStorage()
    {
        return $this->authenticationService->getStorage();
    }
    
    /**
     * 
     * @param unknown $email
     * @param unknown $password
     * @param string $rememberMe
     * @return array
     */
    public function login($email, $password, $rememberMe = false)
    {
        $success = 0;
        $messages = array();
        
        $melisComClientService = $this->getServiceLocator()->get('MelisComClientService');
        $password = $melisComClientService->crypt($password);
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $authAdapter = new AuthAdapter(
            $dbAdapter,
            'melis_ecom_client_person', // there is a method setTableName to do the same
            'cper_email', // there is a method setIdentityColumn to do the same
            'cper_password' // there is a method setCredentialColumn to do the same
            );
        
        $authAdapter->setIdentity($email)->setCredential($password);
        
        $result = $this->authenticationService->authenticate($authAdapter);
        
        switch ($result->getCode()) 
        {
            case Result::SUCCESS:
                $storage = $this->getStorage();
                
                $personIdentity = $authAdapter->getResultRowObject(
                    null,
                    ['cper_password'] // removing the password from the result object
                    );
                
                $personIdentity->clientKey = $this->getId();
                    
                $storage->write($personIdentity);
                
                $config = $this->getServiceLocator()->get('config');
                $ecomClientConfig = $config['plugins']['meliscommerce']['datas']['default']['session'];
                
                /**
                 * Getting the ttl for Session expiry from config
                 * Ttl of session will depend on login option "remember me"
                 */
                $ecomDefaultTtl = ($rememberMe) ? $ecomClientConfig['remember_me_ttl'] : $ecomClientConfig['default_ttl'];
                $this->sessionManager->rememberMe($ecomDefaultTtl);
                
                $message = 'Login success';
                $success = 1;
                
                break;
            default:
                $message = 'Invalid Email address or password';
                
                break;
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
        {
            return $sessionData->cper_client_id;
        }
        
        return null;
    }
    
    public function getPersonId()
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData))
        {
            return $sessionData->cper_id;
        }
        
        return null;
    }
    
    public function getClientKey()
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData))
        {
            return $sessionData->clientKey;
        }
        
        return null;
    }
    
    public function getClientPersonSessDataByField($field)
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData) && !is_null($field))
        {
            return $sessionData->$field;
        }
        
        return null;
    }
    
    public function getPersonName()
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData))
        {
            return $sessionData->cper_firstname.' '.$sessionData->cper_name;
        }
        
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
}