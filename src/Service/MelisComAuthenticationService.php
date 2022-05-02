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
use Laminas\EventManager\EventManagerInterface;
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
	protected $eventManager;
    
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

	/**
	 * @param EventManagerInterface $eventManager
	 */
	public function setEventManager(EventManagerInterface $eventManager)
	{
		$this->eventManager = $eventManager;
	}

	/**
	 * @return EventManagerInterface
	 */
	public function getEventManager()
	{
		return $this->eventManager;
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

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscommerce_service_authentication_login_start', $arrayParameters);

        $success = 0;

        $translator = $this->getServiceManager()->get('translator');
        $clientSrv = $this->getServiceManager()->get('MelisComClientService');
        $clientInfo = $clientSrv->getClientPersonByEmail($arrayParameters['email']);

        //check if email exist
        if(!empty($clientInfo))
        {
            //check password
            $isCorrect = $this->isPasswordCorrect($arrayParameters['password'], $clientInfo->cper_password);
            if ($isCorrect)
            {
                $dbAdapter = $this->getServiceManager()->get('Laminas\Db\Adapter\Adapter');
                $authAdapter = new AuthAdapter(
                    $dbAdapter,
                    'melis_ecom_client_person', // there is a method setTableName to do the same
                    'cper_email', // there is a method setIdentityColumn to do the same
                    'cper_password' // there is a method setCredentialColumn to do the same
                );

                $authAdapter->setIdentity($arrayParameters['email'])->setCredential($clientInfo->cper_password);

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
                        $personIdentity->client_group = $clientSrv->getClientById($clientInfo->cper_client_id)->cli_group_id;

                        $storage->write($personIdentity);

                        $config = $this->getServiceManager()->get('config');
                        $ecomClientConfig = $config['plugins']['meliscommerce']['datas']['default']['session'];

                        /**
                         * Getting the ttl for Session expiry from config
                         * Ttl of session will depend on login option "remember me"
                         */
                        $ecomDefaultTtl = ($arrayParameters['rememberMe']) ? $ecomClientConfig['remember_me_ttl'] : $ecomClientConfig['default_ttl'];
                        $this->sessionManager->rememberMe($ecomDefaultTtl);

                        $message = $translator->translate('tr_meliscommerce_plugin_login_success');
                        $success = 1;
                        break;
                    default:
                        $message = $translator->translate('tr_meliscommerce_plugin_login_invalid_email_or_password');
                        break;
                }
            }else{
                $message = $translator->translate('tr_meliscommerce_plugin_login_invalid_email_or_password');
            }
        }else{
            $message = $translator->translate('tr_meliscommerce_plugin_login_invalid_email_or_password');
        }

        $arrayParameters['results'] = [
            'success' => $success,
            'message' => $message
        ];

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscommerce_service_authentication_login_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    public function getClientId()
    {
        $sessionData = $this->authenticationService->getIdentity();
        if ($this->hasIdentity() && !empty($sessionData))
            return $sessionData->cper_client_id;
        
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

    /**
	 * This method creates an array from the parameters, using parameters' name as keys
	 * and taking values or default values.
	 * It is used to prepare args for events listening.
	 * 
	 * @param string $class_method
	 * @param mixed[] $parameterValues
	 */
	public function makeArrayFromParameters($class_method, $parameterValues)
	{
		if (empty($class_method))
			return array();
		
		// Get the class name and the method name
		list($className, $methodName) = explode('::', $class_method);
		if (empty($className) || empty($methodName))
			return array();
		
		/**
		 * Build an array from the parameters
		 * Parameters' name will become keys
		 * Values will be parameters' values or default values
		 */ 
		$parametersArray = array();
		try 
		{
			// Gets the data of class/method from Reflection object
			$reflectionMethod = new \ReflectionMethod($className, $methodName);
			$parameters = $reflectionMethod->getParameters();
			
			// Loop on parameters
			foreach ($parameters as $keyParameter => $parameter)
			{
				// Check if we have a value given
				if (!empty($parameterValues[$keyParameter]))
					$parametersArray[$parameter->getName()] = $parameterValues[$keyParameter];
					else
						// No value given, check if parameter has an optional value
						if ($parameter->isOptional())
							$parametersArray[$parameter->getName()] = $parameter->getDefaultValue();
							else
								// Else
								$parametersArray[$parameter->getName()] = null;
			}
		}
		catch (\Exception $e)
		{
			// Class or method were not found
			return array();
		}
		
		return $parametersArray;
	}

	/**
	 * Send event using eventManager
	 * @param $eventName
	 * @param $parameters
	 * @param null $target
	 * @return array
	 */
	public function sendEvent($eventName, $parameters, $target = null)
	{
		if($this->eventManager) {

			if (is_null($target))
				$target = $this;

			$parameters = $this->eventManager->prepareArgs($parameters);
			$this->eventManager->trigger($eventName, $target, $parameters);
			$parameters = $parameters->getArrayCopy();
		}

		return $parameters;
	}
}