<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

use MelisEngine\Controller\Plugin\MelisTemplatingPlugin;
use MelisFront\Navigation\MelisFrontNavigation;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Sendmail;
use Zend\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "lostPassword" plugin.
 * 
 * Please look inside app.plugins.php for possible awaited parameters
 * in front and back function calls.
 * 
 * front() and back() are the only functions to create / update.
 * front() generates the website view
 * back() generates the plugin view in template edition mode (TODO)
 * 
 * Configuration can be found in $pluginConfig / $pluginFrontConfig / $pluginBackConfig
 * Configuration is automatically merged with the parameters provided when calling the plugin.
 * Merge detects automatically from the route if rendering must be done for front or back.
 * 
 * How to call this plugin without parameters:
 * $plugin = $this->MelisCommerceLostPasswordGetEmailPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisFrontBreadcrumbPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/password/lost'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'lostPassword');
 * 
 * How to display in your controller's view:
 * echo $this->lostPassword;
 * 
 * 
 */
class MelisCommerceLostPasswordGetEmailPlugin extends MelisTemplatingPlugin
{
    // the key of the configuration in the app.plugins.php
    public $configPluginKey = 'meliscommerce';
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $success = 0;
        $errors = array();
        $message = 'Unable to send an email';
        
        $appConfigForm = (!empty($this->pluginFrontConfig['forms']['lost_password'])) ? $this->pluginFrontConfig['forms']['lost_password'] : array();
        $emailConfig = (!empty($this->pluginFrontConfig['email'])) ? $this->pluginFrontConfig['email'] : array();
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $lostPassword = $factory->createForm($appConfigForm);
        
        // Value that trigger if the form is submitted or requested
        $is_submit = (!empty($this->pluginFrontConfig['m_is_submit'])) ? $this->pluginFrontConfig['m_is_submit'] : false;
        
        $data['m_email'] = (!empty($this->pluginFrontConfig['m_email'])) ? $this->pluginFrontConfig['m_email'] : '';
        $sendMailSvc = $this->getServiceLocator()->get('MelisEngineSendMail');
        $translator = $this->getServiceLocator()->get('translator');
        // Setting the Datas to Lost Password Form
        $lostPassword->setData($data);

        if ($is_submit)
        {

            if ($lostPassword->isValid())
            {
                $clientSrv = $this->getServiceLocator()->get('MelisComClientService');
                
                $clientPersonTbl = $this->getServiceLocator()->get('MelisEcomClientPersonTable');
                
                if(!empty($data['m_email']))
                {
                    // Checking if the Email entered is existing on the database
                    $clientPerson = $clientSrv->getClientPersonByEmail($data['m_email']);
                    if (empty($clientPerson))
                    {
                        $message = $translator->translate('tr_meliscommerce_client_email_not_exist');
                    }
                    else 
                    {

                        $personId = $clientPerson->cper_id;
                        $recoveryKey = $clientSrv->generatePsswordRecoveryKey();
                        $clientSrv->savePasswordRecoveryKey($personId, $recoveryKey);
                        $changeLostPasswordLink = (!empty($this->pluginFrontConfig['lost_password_reset_page_link'])) ? $this->pluginFrontConfig['lost_password_reset_page_link'] : 'http://www.test.com';
                        
                        $changePassConfig = array(
                            'lostPasswordLink' => $changeLostPasswordLink,
                            'recoveryKey' => $recoveryKey
                        );
                        
                        $emailConfig['email_content_tag_replace'] = array_merge($emailConfig['email_content_tag_replace'], $changePassConfig);
                        
                        $sendMailSvc->sendEmail(
                            $emailConfig['email_template_path'], $emailConfig['email_from'], $emailConfig['email_from_name'],
                            $emailConfig['email_to'], $emailConfig['email_to_name'], $emailConfig['email_subject'],
                            $emailConfig['email_content'], $emailConfig['email_content_tag_replace'], $emailConfig['email_reply_to']
                        );
                        
                        $message = 'Email sent';
                        $success = 1;
                        $lostPassword->get('m_email')->setValue('');
                    }
                }
            }
            else 
            {
                $errors = $lostPassword->getMessages();
            }
        }
        /**
         * As default form will created with the "m_is_submit" input having value of "1"
         * so that after form render this will ready for submission
         */
        $lostPassword->get('m_is_submit')->setValue('1');
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'lostPassword' => $lostPassword,
            'message' => $message,
            'success' => $success,
            'errors' => $errors
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    /**
     * This function return the back office rendering for the template edition system
     * TODO
     */
    public function back()
    {
        return array();
    }


}
