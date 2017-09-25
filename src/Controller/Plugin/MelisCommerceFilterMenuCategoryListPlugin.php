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
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "filter menu category list" plugin.
 * 
 * Please look inside app.plugins.categories.php for possible awaited parameters
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
 * $plugin = $this->MelisCommerceFilterMenuCategoryListPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceFilterMenuCategoryListPlugin();
 * $parameters = array(
 *      'template_path' => 'MelisDemoCms/your-custom-template'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'filterMenuCategoryList');
 * 
 * How to display in your controller's view:
 * echo $this->filterMenuCategoryList;
 * 
 * 
 */
class MelisCommerceFilterMenuCategoryListPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceFilterMenuCategoryListPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];
        
        $data = $this->getFormData();
        
        $rootCategoryId = !empty($data['m_box_filter_root_category_id']) ?  $data['m_box_filter_root_category_id'] : null;
        $includeRootCategory = !empty($data['m_box_filter_include_root_category']) ?  true : false;
        $selectedCategories = !empty($data['m_box_filter_categories_ids_selected']) ?  $data['m_box_filter_categories_ids_selected'] : array();
        $onlyValid = !empty($data['m_box_filter_only_valid'])? true : false;
        
        // Getting Category Tree View form the Category Service
        $melisComCategoryService = $this->getServiceLocator()->get('MelisComCategoryService');
        $categoryListData = $melisComCategoryService->getCategoryTreeview($rootCategoryId, null, $onlyValid, $langId);
        
        // Category states preparation
        $categoryList = $this->prepareCategoriesSates($categoryListData, $selectedCategories, $includeRootCategory);
        
        /**
         * Checking if the Root category is included to result
         * else this will remove from the Category list
         */
        if (!$includeRootCategory && !empty($categoryList))
        {
            if(!empty($categoryList[0]['children']))
            {
                $categoryList = $categoryList[0]['children'];
            }
            else 
            {
                $categoryList = array();
            }
        }
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'pluginId' => $data['id'],
            'categoryList' => $categoryList,
            'selectedCategories' => $selectedCategories,
        );
        
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    /**
     * This mothod prepare the the Datas form the Category Service data to array that supported of the JSTree used for
     * Category Tree View
     *
     * @param int Array $categoryList list of Categories
     * @param int $selected, Selected node that assign to category bu JsTree Plugin
     * @param array $openedStateParent, this contain Categories Id's that has to be open node after the request responded
     *
     * @return int Array[]
     */
    private function prepareCategoriesSates($categoryList, $selectedCategories = array(), $openedCategories = array()){
    
        $melisEcomProductCategoryTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
        
        foreach ($categoryList As $key => $val){
            
            // Categoru number of products assigned
            $categoryList[$key]['cat_number_product'] = $melisEcomProductCategoryTable->getTotalData('pcat_cat_id', $val['cat_id']);
            
            // Category type
            $categoryList[$key]['type'] = 'category';
            if ($val['cat_father_cat_id'] == -1){
                $categoryList[$key]['type'] = 'catalog';
            }else{
                $categoryList[$key]['type'] = 'category';
            }
            
            // Checking open categories
            $selectedState = false;
            if (!empty($selectedCategories)){
                if (is_array($selectedCategories)){
                    if(in_array($val['cat_id'], $selectedCategories)){
                        $selectedState = true;
                    }
                }
            }
            
            // Checking open categories
            $openState = false;
            if (!empty($openedCategories)){
                if (is_array($openedCategories)){
                    if(in_array($val['cat_id'], $openedCategories)){
                        $openState = true;
                    }
                }
            }
            
            // Setup category state
            $categoryList[$key]['state'] = array(
                'opened' => $openState, // if the node has check this will state as open node
                'selected' =>  $selectedState,
            );
            
            if (!empty($val['children'])){
                $categoryList[$key]['children'] = $this->prepareCategoriesSates($categoryList[$key]['children'], $selectedCategories, $openedCategories);
                
                /**
                 * Checking if the sub category has selected state
                 * if has selected the Parent(s) Category will initialize Open state
                 */
                $hasOpen = false;
                foreach ($categoryList[$key]['children'] As $cKey => $cVal){
                    if ($cVal['state']['opened'] || $cVal['state']['selected']){
                        $hasOpen = true;
                        break;
                    }
                }
                
                $categoryList[$key]['state']['opened'] = $hasOpen;
            }
        }
        
        return $categoryList;
    }
    
    /**
     * This function generates the form displayed when editing the parameters of the plugin
     */
    public function createOptionsForms()
    {
        // construct form
        $factory = new \Zend\Form\Factory();
        $formElements = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig = $this->pluginBackConfig['modal_form'];
    
        $response = [];
        $render   = [];
        if (!empty($formConfig))
        {
            $request = $this->getServiceLocator()->get('request');
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
                    
                    $langTable = $this->getServiceLocator()->get('MelisEcomLangTable');
                    $langData = $langTable->langOrderByName();
                    $recLangData = array();
                    foreach($langData as $data)
                    {
                        if($data->elang_status)
                        {
                            array_push($recLangData, $data);
                        }
                    }
                    
                    $currentLangName = 'English';
                    $locale = 'en_EN';
                    $container = new Container('meliscore');
                    if($container)
                    {
                        $locale = $container['melis-lang-locale'];
                        $melisEcomLangTable = $this->getServiceLocator()->get('MelisEcomLangTable');
                        $currentLangData = $melisEcomLangTable->getEntryByField('elang_locale', $locale)->current();
                    
                        if (!empty($currentLang))
                        {
                            $currentLangName = $currentLang->elang_name;
                        }
                    }
                    
                    $viewModelTab->langData = $recLangData;
                    $viewModelTab->currentLangName = $currentLangName;
                    $viewModelTab->currentLangLocale = $locale;
                    
                    $viewRender = $this->getServiceLocator()->get('ViewRenderer');
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
    
                    $post = get_object_vars($request->getPost());
    
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
            if (!empty($xml->m_box_filter_root_category_id))
            {
                $configValues['m_box_filter_root_category_id'] = (string)$xml->m_box_filter_root_category_id;
            }
            
            if (!empty($xml->m_box_filter_categories_ids_selected))
            {
                $configValues['m_box_filter_categories_ids_selected'] = json_decode((string)$xml->m_box_filter_categories_ids_selected, true);
            }
    
            if (!empty($xml->m_box_filter_include_root_category))
            {
                $configValues['m_box_filter_include_root_category'] = (string)$xml->m_box_filter_include_root_category;
            }
            
            if (!empty($xml->m_box_filter_only_valid))
            {
                $configValues['m_box_filter_only_valid'] = (string)$xml->m_box_filter_only_valid;
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
        
        if(!empty($parameters['m_box_filter_root_category_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_filter_root_category_id><![CDATA[' . $parameters['m_box_filter_root_category_id'] . ']]></m_box_filter_root_category_id>';
        }
    
        if(!empty($parameters['m_box_filter_categories_ids_selected']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_filter_categories_ids_selected><![CDATA[' . json_encode($parameters['m_box_filter_categories_ids_selected']) . ']]></m_box_filter_categories_ids_selected>';
        }
    
        if(!empty($parameters['m_box_filter_include_root_category']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_filter_include_root_category><![CDATA[' . $parameters['m_box_filter_include_root_category'] . ']]></m_box_filter_include_root_category>';
        }
        
        if(!empty($parameters['m_box_filter_only_valid']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_filter_only_valid><![CDATA[' . $parameters['m_box_filter_only_valid'] . ']]></m_box_filter_only_valid>';
        }
    
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
    
        return $xmlValueFormatted;
    }
    
}
