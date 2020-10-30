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
use Laminas\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "category price filter" plugin.
 * 
 * Please look inside app.plugins.php for possible awaited parameters
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
 * $plugin = $this->MelisCommerceProductPriceRangePlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceProductPriceRangePlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCms/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'filterMenuPriceValue');
 * 
 * How to display in your controller's view:
 * echo $this->filterMenuPriceValue;
 * 
 * 
 */
class MelisCommerceProductPriceRangePlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceProductPriceRangePlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {

        $data = $this->getFormData();
        $priceColumn = $data['m_box_product_price_column'];

        // Retrieving the default Values for Product prices
        $proService = $this->getServiceManager()->get('MelisComProductService');
        $priceMin = $proService->getMaximumMinimumPrice('min', $priceColumn);
        $priceMax = $proService->getMaximumMinimumPrice('max', $priceColumn);

        $defaultMin =  $priceMin->min_price;
        $defaultMax =  $priceMax->max_price;

        $min = !empty($data['m_box_product_price_min'])? $data['m_box_product_price_min'] : $defaultMin;
        $max = !empty($data['m_box_product_price_max'])? $data['m_box_product_price_max'] : $defaultMax;

        $priceConfig = array(   
            'm_box_product_price_min' =>  (int)$min,
            'm_box_product_price_max' => (int)$max,
            'default_min_price' => (int) $defaultMin,
            'default_max_price' => (int) $defaultMax,
            'm_box_product_price_column' => $priceColumn,
        );

        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'filterMenuPriceValue' => $priceConfig,
            'product_price_range_form' => $this->loadProductPriceRangeForm($priceConfig),
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
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
     * Function to load the product price form
     *
     * @param $data
     * @return \Laminas\Form\ElementInterface
     */
    private function loadProductPriceRangeForm($data)
    {
        // construct form
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig = $this->pluginBackConfig['product_price_range_form'];
        if (!empty($formConfig)) {
            foreach($formConfig AS $key=>$config){
                $form = $factory->createForm($config);
                $form->setData($data);
            }
        }
        return $form;
    }
    
    /**
     * Returns the data to populate the form inside the modals when invoked
     * @return array|bool|null
     */
    public function getFormData()
    {
        return $this->pluginFrontConfig;
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

            if (!empty($xml->m_box_product_price_min))
            {
                $configValues['m_box_product_price_min'] = (string)$xml->m_box_product_price_min;
            }

            if (!empty($xml->m_box_product_price_column))
            {
                $configValues['m_box_product_price_column'] = (string)$xml->m_box_product_price_column;
            }
            
            if (!empty($xml->m_box_product_price_max))
            {
                $configValues['m_box_product_price_max'] = (string)$xml->m_box_product_price_max;
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

        if(!empty($parameters['m_box_product_price_column']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_product_price_column><![CDATA[' . $parameters['m_box_product_price_column'] . ']]></m_box_product_price_column>';
        }
        
        if(!empty($parameters['m_box_product_price_min']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_product_price_min><![CDATA[' . $parameters['m_box_product_price_min'] . ']]></m_box_product_price_min>';
        }
        
        if(!empty($parameters['m_box_product_price_max']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_product_price_max><![CDATA[' . $parameters['m_box_product_price_max'] . ']]></m_box_product_price_max>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
    
}
