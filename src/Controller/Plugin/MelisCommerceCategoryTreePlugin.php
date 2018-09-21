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
 * $plugin = $this->MelisCommerceCategoryTreePlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCategoryTreePlugin();
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
class MelisCommerceCategoryTreePlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceCategoryTreePlugin';
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
        $rootCategoryId = !empty($data['m_box_root_category_tree_id']) ?  $data['m_box_root_category_tree_id'] : null;
        $includeRootCategory = !empty($data['m_box_include_root_category_tree']) ?  true : false;
        $selectedCategories = !empty($data['m_box_category_tree_ids_selected']) ?  $data['m_box_category_tree_ids_selected'] : array();
        
        // Getting Category Tree View form the Category Service
        $melisComCategoryService = $this->getServiceLocator()->get('MelisComCategoryService');
        $categoryListData = $melisComCategoryService->getCategoryTreeview($rootCategoryId, $langId, true);

        // Category states preparation
        $categoryList = $this->prepareCategoriesSates($categoryListData, $selectedCategories);
        /**
         * Checking if the Root category is included to result
         */
        if ($includeRootCategory && !empty($categoryList))
        if (!empty($categoryList))
        {
            $categoryList = $this->includeParentCategory($categoryList, $langId);
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
     * Function to include the parent category
     * in the list
     *
     * @param $categoryList
     * @param $langId
     * @return array
     */
    private function includeParentCategory($categoryList, $langId)
    {
        // Getting Category Tree View form the Category Service
        $melisComCategoryService = $this->getServiceLocator()->get('MelisComCategoryService');

        if(!empty($categoryList[0]['cat_father_cat_id']))
        {
            $arr = array();
            $parentId = $categoryList[0]['cat_father_cat_id'];
            $categoryData = $melisComCategoryService->getCategoryById($parentId, $langId, true);
            $parentData = $categoryData->getTranslations();
            $escaper = new \Zend\Escaper\Escaper('utf-8');

            $catName = '';
            $catNameLangName = '';
            $parent_cat_id = "";
            foreach($parentData AS $key => $val){
                $d = (array) $val;
                $parent_cat_id = $d['cat_id'];
                if ($d['elang_id'] == $langId)
                {
                    $catName = $d['catt_name'];
                }
                else
                {
                    // Getting available Name concatinated with the Language Name
                    $catName = $d['catt_name'];
                    $catNameLangName = $d['elang_name'];
                }
            }
            $arr['text'] = $escaper->escapeHtml($catName);
            $arr['textLang'] = (!empty($catNameLangName)) ? '('.$catNameLangName.')' : '';
            $arr['cat_id'] = $parent_cat_id;
            $arr['state'] = array('opened' => true, 'selected' => true);
            $arr['children'] = $categoryList;
            return array($arr);
        }
        return $categoryList;
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
        $data = parent::getFormData();

        $data['m_box_root_category_tree_id'] = (isset($this->pluginFrontConfig['m_category_tree_option']['m_box_root_category_tree_id']) ? $this->pluginFrontConfig['m_category_tree_option']['m_box_root_category_tree_id'] : null);
        $data['m_box_category_tree_ids_selected'] = (isset($this->pluginFrontConfig['m_category_tree_option']['m_box_category_tree_ids_selected']) ? $this->pluginFrontConfig['m_category_tree_option']['m_box_category_tree_ids_selected'] : array());
        $data['m_box_include_root_category_tree'] = (isset($this->pluginFrontConfig['m_category_tree_option']['m_box_include_root_category_tree']) ? $this->pluginFrontConfig['m_category_tree_option']['m_box_include_root_category_tree'] : null);
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

            if (!empty($xml->m_box_root_category_tree_id))
            {
                $configValues['m_category_tree_option']['m_box_root_category_tree_id'] = (string)$xml->m_box_root_category_tree_id;
            }

            if (!empty($xml->m_box_category_tree_ids_selected))
            {
                $configValues['m_category_tree_option']['m_box_category_tree_ids_selected'] = json_decode((string)$xml->m_box_category_tree_ids_selected, true);
            }

            if (!empty($xml->m_box_include_root_category_tree))
            {
                $configValues['m_category_tree_option']['m_box_include_root_category_tree'] = (string)$xml->m_box_include_root_category_tree;
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


        if(!empty($parameters['m_box_root_category_tree_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_root_category_tree_id><![CDATA[' . $parameters['m_box_root_category_tree_id'] . ']]></m_box_root_category_tree_id>';
        }

        if(!empty($parameters['m_box_category_tree_ids_selected']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_category_tree_ids_selected><![CDATA[' . json_encode($parameters['m_box_category_tree_ids_selected']) . ']]></m_box_category_tree_ids_selected>';
        }

        if(!empty($parameters['m_box_include_root_category_tree']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_include_root_category_tree><![CDATA[' . $parameters['m_box_include_root_category_tree'] . ']]></m_box_include_root_category_tree>';
        }
    
        // Something has been saved, let's generate an XML for DB
        //if (!empty($xmlValueFormatted))
        //{
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        //}

        return $xmlValueFormatted;
    }
    
}
