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
        
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $lostPassword = $factory->createForm($appConfigForm);
        
        // Value that trigger if the form is submitted or requested
        $is_submit = (!empty($this->pluginFrontConfig['m_is_submit'])) ? $this->pluginFrontConfig['m_is_submit'] : false;
        
        $data['m_email'] = (!empty($this->pluginFrontConfig['m_email'])) ? $this->pluginFrontConfig['m_email'] : '';
        
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
                    $clientPerson = $clientPersonTbl->getEntryByField('cper_email', $data['m_email'])->current();
                    if (empty($clientPerson))
                    {
                        $message = 'Email does not exist, please try another email address';
                    }
                    else 
                    {

                        $to = $data['m_email'];
                        $from = 'noreply@melistechnology.com';
                        $from_name = 'Melis Commerce Demo';
                        $subject = 'Lost Password';

                        $emailTemplatePath = $this->pluginFrontConfig['email_template_path'];


                        $personId = $clientPerson->cper_id;
                        $recoveryKey = $clientSrv->generatePsswordRecoveryKey();
                        $clientPersonTbl->save(array('cper_password_recovery_key' => $recoveryKey), $personId);
                        $changeLostPasswordLink = (!empty($this->pluginFrontConfig['lost_password_reset_page_link'])) ? $this->pluginFrontConfig['lost_password_reset_page_link'] : 'http://www.test.com';
                        $message = 'Please click the link below to change your password.<br>'
                                    .'<a href="'.$changeLostPasswordLink.'?m_recovery_key='.$recoveryKey.'">'
                                    .'Reset password link</a><br>';

                        $html = new MimePart($message);
                        $data = [];

                        $config = $this->getServiceLocator()->get('config');

                        // email template
                        $tplPathStack = isset($config['view_manager']['template_map'][$emailTemplatePath]) ?
                            ['mailTemplate' => $config['view_manager']['template_map'][$emailTemplatePath]] : 'MelisCommerce/emailLayout';

                        $view       = new \Zend\View\Renderer\PhpRenderer();
                        $resolver   = new \Zend\View\Resolver\TemplateMapResolver();
                        $viewModel  = new ViewModel();

                        $resolver->setMap($tplPathStack);
                        $view->setResolver($resolver);

                        $env             = getenv('MELIS_PLATFORM');
                        $siteDomainTable = $this->getServiceLocator()->get('MelisEngineTableSiteDomain');
                        $siteData        = $siteDomainTable->getEntryByField('sdom_env', $env)->current();

                        $domain = '/';
                        if($siteData) {
                            $domain = $siteData->sdom_scheme . '://' . $siteData->sdom_domain . '/';
                        }

                        $data = [
                            'domain'        => $domain,
                            'from_name'     => $from_name,
                            'from_email'    => $from,
                            'to_first_name' => $clientPerson->cper_firstname,
                            'to_last_name'  => mb_convert_case($clientPerson->cper_name, MB_CASE_TITLE, "UTF-8"),
                            'to_email'      => $clientPerson->cper_email,
                            'message'       => $message,
                        ];
                        $viewModel->setTemplate('mailTemplate')->setVariables($data);
                        $html = new MimePart($view->render($viewModel));

                        $html->type = "text/html";

                        $body = new MimeMessage();
                        $body->addPart($html);

                        $mail = new Message();
                        $mail->setFrom($from, $from_name);
                        $mail->addTo($to);
                        $mail->setSubject($subject);
                        $mail->setBody($body);

                        $message = $mail->toString();

                         $transport = new Sendmail();
                         $transport->send($mail);
                        
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
