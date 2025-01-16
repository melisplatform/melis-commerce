<?php
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\DashboardPlugins;

use MelisCore\Controller\DashboardPlugins\MelisCoreDashboardTemplatingPlugin;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Session\Container;

class MelisCommerceDashboardPluginSalesRevenue extends MelisCoreDashboardTemplatingPlugin
{

    public function __construct()
    {
        //set plugin. first index of plugin in dashboardplugin config.
        $this->pluginModule = 'meliscommerce';
        parent::__construct();
    }

    public function commerceSalesRevenue()
    {
        /** @var \MelisCore\Service\MelisCoreDashboardPluginsRightsService $dashboardPluginsService */
        $dashboardPluginsService = $this->getServiceManager()->get('MelisCoreDashboardPluginsService');
        //get the class name to make it as a key to the plugin
        $path = explode('\\', __CLASS__);
        $className = array_pop($path);
        $isAccessable = $dashboardPluginsService->canAccess($className);

        $view = new ViewModel();
        $view->setTemplate('MelisCommerceDashboardPluginSalesRevenue/dashboard/commerce-sales-revenue');
        $view->isAccessable = $isAccessable;

        return $view;
    }

    public function getDashboardSalesRevenueData()
    {
        $limit = 10;
        $success = 0;
        $values = array();

        if($this->getController()->getRequest()->isPost()) {
            $chartFor = $this->getController()->getRequest()->getPost()->toArray();
            $chartFor = (! array_key_exists('chartFor', $chartFor)) ? 'hourly' : $chartFor['chartFor'];

            //$pluginConfig['activeFilter'] = $chartFor;

            $melisCommerceOrdersService = $this->getServiceManager()->get('MelisComOrderService');
            // Last Date/value of the Graph will be the Current Date
            if($chartFor == 'hourly') {
                $curdate = date('Y-m-d H:i');
            }
            else if($chartFor == 'monthly'){
                $curdate = date('Y-m');
            }
            else {
                $curdate = date('Y-m-d');
            }

            //loop the initial date to deduct it depending on the type of report
            for ($ctr = $limit ; $ctr > 0 ;$ctr--)
            {
                // Retrieve Sales Revenue Data
                $salesRevenueData = $melisCommerceOrdersService->getSalesRevenueDataByDate($chartFor,$curdate);
                // Checking type of report
                switch ($chartFor) {
                    case 'hourly':
                        $values[] = array($curdate, $salesRevenueData['totalOrderPrice'], $salesRevenueData['totalShippingPrice']);
                        // Deduct 1 Hour every loop
                        $curdate = date('Y-m-d H:i',strtotime($curdate.' -1 hour'));
                        break;
                    case 'daily':
                        $values[] = array($curdate, $salesRevenueData['totalOrderPrice'], $salesRevenueData['totalShippingPrice']);
                        // Deduct 1 Day every loop
                        $curdate = date('Y-m-d',strtotime($curdate.' -1 days'));
                        break;
                    case 'weekly':
                        $values[] = array($curdate, $salesRevenueData['totalOrderPrice'], $salesRevenueData['totalShippingPrice']);
                        // Deduct 1 Week / 7 Days every loop
                        $curdate = date('Y-m-d',strtotime($curdate.' -1 week'));
                        break;
                    case 'monthly':
                        $values[] = array($curdate, $salesRevenueData['totalOrderPrice'], $salesRevenueData['totalShippingPrice']);
                        // Deduct 1 Month every loop
                        $curdate = date('Y-m-d',strtotime($curdate.' -1 months'));
                        break;
                    default:
                        # code...
                        break;
                }
            }
            $success = 1;
        }
        return new JsonModel(array(
            'date' => date('Y-m-d'),
            'success' => $success,
            'values' => $values
        ));
    }

     /**
     * This function generates the form displayed when editing the parameters of the plugin
     * @return array
     */
    public function createOptionsForms()
    {
        // construct form
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig = $this->pluginConfig['modal_form'];

        $response = [];
        $render   = [];
        if (!empty($formConfig)) {
            foreach ($formConfig as $formKey => $config) {
                $form = $factory->createForm($config);
                $request = $this->getServiceManager()->get('request');
                $parameters = $request->getQuery()->toArray();

                if (!isset($parameters['validate'])) {

                    $form->setData($this->getFormData());
                    $viewModelTab = new ViewModel();
                    $viewModelTab->setTemplate($config['tab_form_layout']);
                    $viewModelTab->modalForm = $form;
                    $viewModelTab->formData   = $this->getFormData();
                    $viewRender = $this->getServiceManager()->get('ViewRenderer');
                    $html = $viewRender->render($viewModelTab);
                    array_push($render, [
                            'name' => $config['tab_title'],
                            'icon' => $config['tab_icon'],
                            'html' => $html
                        ]
                    );
                }
                else {

                    // validate the forms and send back an array with errors by tabs
                    $post = $request->getPost()->toArray();
                    $success = false;
                    $form->setData($post);

                    $errors = array();
                    if ($form->isValid()) {
                        $data = $form->getData();
                        $success = true;
                        array_push($response, [
                            'name' => $formConfig[$formKey]['tab_title'],
                            'success' => $success,
                        ]);
                    } else {
                        $errors = $form->getMessages();

                        foreach ($errors as $keyError => $valueError) {
                            foreach ($config['elements'] as $keyForm => $valueForm) {
                                if ($valueForm['spec']['name'] == $keyError &&
                                    !empty($valueForm['spec']['options']['label'])
                                )
                                    $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                            }
                        }


                        array_push($response, [
                            'name' => $formConfig[$formKey]['tab_title'],
                            'success' => $success,
                            'errors' => $errors,
                            'message' => '',
                        ]);
                    }

                }
            }
        }

        if (!isset($parameters['validate'])) {
            return $render;
        }
        else {
            return $response;
        }
    }

    /**
     * This method will decode the XML in DB to make it in the form of the plugin config file
     * so it can overide it. Only front key is needed to update.
     * The part of the XML corresponding to this plugin can be found in $this->pluginXmlDbValue
     */
    public function loadDbXmlToPluginConfig()
    {
        $configValues = array();

        $xml = simplexml_load_string($this->pluginXmlDbValue);

        if ($xml)
        {
            if (!empty($xml->activeFilter)) {
                $configValues['activeFilter'] = (string)$xml->activeFilter;
            }
        }

        return $configValues;
    }

    public function savePluginConfigToXml($parameters){
        $xmlValueFormatted = '';

        if (!empty($parameters['activeFilter']))
            $xmlValueFormatted .= "\t\t" . '<activeFilter><![CDATA[' . $parameters['activeFilter'] . ']]></activeFilter>';

        return $xmlValueFormatted;
    }
}