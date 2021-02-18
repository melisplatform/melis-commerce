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
use Laminas\Paginator\Adapter\ArrayAdapter;
use Laminas\Paginator\Paginator;
use Laminas\Stdlib\ArrayUtils;
use Laminas\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "Product search box" plugin.
 * 
 * Please look inside app.plugins.products.php for possible awaited parameters
 * in front and back function calls.
 * 
 * front() and back() are the only functions to create / update.
 * front() generates the website view
 * 
 * Configuration can be found in $pluginConfig / $pluginFrontConfig / $pluginBackConfig
 * Configuration is automatically merged with the parameters provided when calling the plugin.
 * Merge detects automatically from the route if rendering must be done for front or back.
 * 
 * How to call this plugin without parameters:
 * $plugin = $this->MelisCommerceProductSearchPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceProductSearchPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCms/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'filterMenuProductSearch');
 * 
 * How to display in your controller's view:
 * echo $this->filterMenuProductSearch;
 * 
 * 
 */
class MelisCommerceProductSearchPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceProductSearchPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $categoryProductList = array();
        $data = $this->getFormData();

        $productSearchSvc = $this->getServiceManager()->get('MelisComProductSearchService');

        $searchKey = !empty($data['m_box_product_search']) ? $data['m_box_product_search'] : null;

        // Pagination config
        $pageCurrent        = !empty($data['m_page_current'])                ? $data['m_page_current'] : 1;
        $pageNbPerPage      = !empty($data['m_page_nb_per_page'])            ? $data['m_page_nb_per_page'] : 10;
        $pageNbBeforeAfter  = !empty($data['m_page_nb_page_before_after'])   ? $data['m_page_nb_page_before_after'] : 3;

        if(!is_null($searchKey)) {
            $categoryProductList = $productSearchSvc->searchProductFull($searchKey);
        }

        // Pagination
        $paginator = new Paginator(new ArrayAdapter($categoryProductList));
        $paginator->setCurrentPageNumber($pageCurrent)
            ->setItemCountPerPage($pageNbPerPage);

        $formData = array(
            'm_box_product_search' => $searchKey,
        );

        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'productList' => $paginator,
            'nbPageBeforeAfter' => $pageNbBeforeAfter,
            'productSearchForm' => $this->loadProductSearchForm($formData),
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }

    /**
     * @param $data
     * @return \Laminas\Form\ElementInterface
     */
    private function loadProductSearchForm($data)
    {
        // construct form
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig = $this->pluginBackConfig['product_search_form'];
        if (!empty($formConfig)) {
            foreach($formConfig AS $key=>$config){
                $form = $factory->createForm($config);
                $form->setData($data);
            }
        }
        return $form;
    }
    
    /**
     * This function generates the form displayed when editing the parameters of the plugin
     */
    public function createOptionsForms()
    {
        // construct form
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig = $this->pluginBackConfig['modal_form'];
    
        $response = [];
        $render   = [];
        if (!empty($formConfig))
        {
            $request = $this->getServiceManager()->get('request');
            $parameters = $request->getQuery()->toArray();
            if (!isset($parameters['validate'])){
                $formData = $this->getFormData();
            }
    
            foreach ($formConfig as $formKey => $config)
            {
                $form = $factory->createForm($config);
    
                if (!isset($parameters['validate']))
                {
                    $form->setData($formData);
                    $viewModelTab = new ViewModel();
                    $viewModelTab->setTemplate($config['tab_form_layout']);
                    $viewModelTab->modalForm = $form;
                    $viewModelTab->formData   = $formData;
    
                    $viewRender = $this->getServiceManager()->get('ViewRenderer');
                    $html = $viewRender->render($viewModelTab);
                    array_push($render, array(
                        'name' => $config['tab_title'],
                        'icon' => $config['tab_icon'],
                        'html' => $html
                    ));
                }
                else
                {
                    // validate the forms and send back an array with errors by tabs
                    $success = false;
                    $errors = array();
    
                    $post = $request->getPost()->toArray();
    
                    $form->setData($post);
    
                    if ($form->isValid())
                    {
                        $success = true;
                    }
                    else
                    {
                        $errors = $form->getMessages();
    
                        foreach ($errors as $keyError => $valueError)
                        {
                            foreach ($config['elements'] as $keyForm => $valueForm)
                            {
                                if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label']))
                                {
                                    $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                                }
                            }
                        }
                    }
    
                    array_push($response, array(
                        'name' => $this->pluginBackConfig['modal_form'][$formKey]['tab_title'],
                        'success' => $success,
                        'errors' => $errors,
                        'message' => '',
                    ));
                }
            }
        }
    
        if (!isset($parameters['validate']))
        {
            return $render;
        }
        else
        {
            return $response;
        }
    }
    
    /**
     * Returns the data to populate the form inside the modals when invoked
     * @return array|bool|null
     */
    public function getFormData()
    {
        $data = $this->pluginFrontConfig;
        $data = ArrayUtils::merge($data, $this->pluginFrontConfig['pagination']);

        return $data;
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
            if (!empty($xml->template_path))
            {
                $configValues['template_path'] = (string)$xml->template_path;
            }

            if (!empty($xml->m_box_product_search))
            {
                $configValues['m_box_product_search'] = (string)$xml->m_box_product_search;
            }
        }
        
        return $configValues;
    }
    
    /**
     * This method saves the XML version of this plugin in DB, for this pageId
     * Automatically called from savePageSession listenner in PageEdition
     */
    public function savePluginConfigToXml($parameters)
    {
        $xmlValueFormatted = '';
        
        // template_path is mendatory for all plugins
        if (!empty($parameters['template_path']))
        {
            $xmlValueFormatted .= "\t\t" . '<template_path><![CDATA[' . $parameters['template_path'] . ']]></template_path>';
        }

        if(!empty($parameters['m_box_product_search']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_product_search><![CDATA[' . $parameters['m_box_product_search'] . ']]></m_box_product_search>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
