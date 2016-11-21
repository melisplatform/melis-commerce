<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter as DbAdapter;

/**
 * Diagnostic controller.
 * Associated with /config/diagnostic.config.php
 * All tests run during diagnostics are found here
 */
class DiagnosticController extends AbstractActionController
{
    private $odbAdapter;

    public function testCommerceMediaAction()
    {
        $request = $this->params()->fromRoute('payload', $this->params()->fromQuery('payload', null));
        $label = null;
        $success = 1;
        $response = array();
        $error = null;

        if($request) {
            $label = $request['label'];
            $mediaPath = HTTP_ROOT . 'media/'.$request['folder'];
            if(file_exists($mediaPath)) {
                $response['exists'] = $label .' is existing';
                $success = 1;
                if(is_writable($mediaPath)) {
                    $response['writable'] = $label . ' is writable';
                    $success = 1;
                    if(is_readable($mediaPath)) {
                        $success = 1;
                        $response['readable'] = $label . ' is readable';
                    }
                    else {
                        $success = 0;
                        $response['readable'] = $label . ' is not readable';
                        $error = $label . ' is not readable';
                    }
                }
                else {
                    $success = 0;
                    $response['writable'] = $label . ' is not writable';
                    $error = $label . ' is not writable';
                }
            }
            else {
                $response['exists'] = $label .' is not existing';
                $success = 0;
                $error = $label . ' is not existing';
            }
        }
        $actionResponse = array_merge(array(
            'label' => $label,
            'success' => $success,
            'error' => $error
        ), array('result' => $response));

        return new JsonModel($actionResponse);
    }

    public function testCommerceMediaProductFoldersAction()
    {
        $request = $this->params()->fromRoute('payload', $this->params()->fromQuery('payload', null));
        $label = null;
        $success = 1;
        $response = array();
        $error = null;

        if($request) {
            $label = $request['label'];
            $mediaPath = HTTP_ROOT . 'media/'.$request['folder'];
            if(file_exists($mediaPath)) {
                $response['exists'] = $label .' is existing';
                $success = 1;
                if(is_writable($mediaPath)) {
                    $response['writable'] = $label . ' is writable';
                    $success = 1;
                    if(is_readable($mediaPath)) {
                        $success = 1;
                        $response['readable'] = $label . ' is readable';
                    }
                    else {
                        $success = 0;
                        $response['readable'] = $label . ' is not readable';
                        $error = $label . ' is not readable';
                    }
                }
                else {
                    $success = 0;
                    $response['writable'] = $label . ' is not writable';
                    $error = $label . ' is not writable';
                }
            }
            else {
                $response['exists'] = $label .' is not existing';
                $success = 0;
                $error = $label . ' is not existing';
            }
        }
        $actionResponse = array_merge(array(
            'label' => $label,
            'success' => $success,
            'error' => $error
        ), array('result' => $response));

        return new JsonModel($actionResponse);
    }

    public function testCommerceMediaCategoryFoldersAction()
    {
        $request = $this->params()->fromRoute('payload', $this->params()->fromQuery('payload', null));
        $label = null;
        $success = 1;
        $response = array();
        $error = null;

        if($request) {
            $label = $request['label'];
            $mediaPath = HTTP_ROOT . 'media/'.$request['folder'];
            if(file_exists($mediaPath)) {
                $response['exists'] = $label .' is existing';
                $success = 1;
                if(is_writable($mediaPath)) {
                    $response['writable'] = $label . ' is writable';
                    $success = 1;
                    if(is_readable($mediaPath)) {
                        $success = 1;
                        $response['readable'] = $label . ' is readable';
                    }
                    else {
                        $success = 0;
                        $response['readable'] = $label . ' is not readable';
                        $error = $label . ' is not readable';
                    }
                }
                else {
                    $success = 0;
                    $response['writable'] = $label . ' is not writable';
                    $error = $label . ' is not writable';
                }
            }
            else {
                $response['exists'] = $label .' is not existing';
                $success = 0;
                $error = $label . ' is not existing';
            }
        }
        $actionResponse = array_merge(array(
            'label' => $label,
            'success' => $success,
            'error' => $error
        ), array('result' => $response));

        return new JsonModel($actionResponse);
    }

    public function testCommerceMediaVariantFoldersAction()
    {
        $request = $this->params()->fromRoute('payload', $this->params()->fromQuery('payload', null));
        $label = null;
        $success = 1;
        $response = array();
        $error = null;

        if($request) {
            $label = $request['label'];
            $mediaPath = HTTP_ROOT . 'media/'.$request['folder'];
            if(file_exists($mediaPath)) {
                $response['exists'] = $label .' is existing';
                $success = 1;
                if(is_writable($mediaPath)) {
                    $response['writable'] = $label . ' is writable';
                    $success = 1;
                    if(is_readable($mediaPath)) {
                        $success = 1;
                        $response['readable'] = $label . ' is readable';
                    }
                    else {
                        $success = 0;
                        $response['readable'] = $label . ' is not readable';
                        $error = $label . ' is not readable';
                    }
                }
                else {
                    $success = 0;
                    $response['writable'] = $label . ' is not writable';
                    $error = $label . ' is not writable';
                }
            }
            else {
                $response['exists'] = $label .' is not existing';
                $success = 0;
                $error = $label . ' is not existing';
            }
        }
        $actionResponse = array_merge(array(
            'label' => $label,
            'success' => $success,
            'error' => $error
        ), array('result' => $response));

        return new JsonModel($actionResponse);
    }

    public function testModuleTablesAction()
    {
        $request = $this->params()->fromRoute('payload', $this->params()->fromQuery('payload', null));


        $label = null;
        $success = 1;
        $response = array();
        $error = null;

        if($request) {
            $label = $request['label'];
            $env = getenv('MELIS_PLATFORM') ? getenv('MELIS_PLATFORM') : 'development';
            $dbConfig = 'config/autoload/platforms/'.$env.'.php';
            if(file_exists($dbConfig)) {
                $dbConfig = include('config/autoload/platforms/'.$env.'.php');
                $this->setDbAdapter($dbConfig['db']);

                $dbResults = array();
                $sql = new Sql($this->getDbAdapter());
                $select = $sql->select();


                $tables = $request['tables'];

                foreach($tables as $table) {

                    $select->from($table);
                    $statement = $sql->prepareStatementForSqlObject($select);
                    try {
                        $result = $statement->execute();
                        $response[$table] = $this->getTranslations('tr_melis_test_db_table_test_success', $table);
                    }catch(\Exception $e) {
                        $response[$table] = $this->getTranslations('tr_melis_test_db_table_test_failed', $table);
                        $error .= $this->getTranslations('tr_melis_test_db_table_exists_false', $table).PHP_EOL;
                        $success = 0;
                    }
                }

            }
            else {
                $error = $this->getTranslations('tr_melis_test_db_config_exists_false', $dbConfig);
            }
        }

        $actionResponse = array_merge(array(
            'label' => $label,
            'success' => $success,
            'error' => $error
        ), array('result' => $response));

        return new JsonModel($actionResponse);
    }



    private function getTranslations($translationKey, $args = array())
    {
        $translator = $this->getServiceLocator()->get('translator');
        $translationText = vsprintf($translator->translate($translationKey), $args);

        return $translationText;
    }

    /**
     * Set's the DB Adapter
     * @param String $config
     */
    private function setDbAdapter($config)
    {
        if(is_array($config)) {
            $this->odbAdapter = new DbAdapter(array_merge(array('driver' => 'Pdo_Mysql'), $config));
        }
    }

    /**
     * Returns the set DB Adapter
     */
    private function getDbAdapter()
    {
        return $this->odbAdapter;
    }

}