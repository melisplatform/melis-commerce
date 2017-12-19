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
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\ArrayUtils;
/**
 * This plugin implements the business logic of the
 * "categoryListProducts" plugin.
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
 * $plugin = $this->MelisCommerceProductListPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceProductListPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/melis-demo-cms'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'categoryListProducts');
 * 
 * How to display in your controller's view:
 * echo $this->categoryListProducts;
 */
class MelisCommerceProductListPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceProductListPlugin';
        parent::__construct($updatesPluginConfig);
    }
    
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     */
    public function front()
    {
        $categorySvc = $this->getServiceLocator()->get('MelisComCategoryService');
        $productSearchSvc = $this->getServiceLocator()->get('MelisComProductSearchService');
        
        $container = new Container('melisplugins');
        $lang = $container['melis-plugins-lang-id'];
        
        // Plugin config data
        $data = $this->getFormData();
        // Sorter config
        $sortColName    = !empty($data['m_col_name'])   ? $data['m_col_name'] : 'prd_reference';
        $sortOrder      = !empty($data['m_order'])      ? $data['m_order'] : 'ASC';
        $sort           = $sortColName. ' ' . $sortOrder;

        $priceColumn = !empty($this->pluginFrontConfig['m_box_filter_price_column']) ? $this->pluginFrontConfig['m_box_filter_price_column'] : 'price_net';
        
        // Filters config
        $onlyValid          = true;
        $search             = !empty($data['m_box_product_search'])                          ? $data['m_box_product_search'] : '';
        $fieldType          = !empty($data['m_box_product_field_type'])                ? $data['m_box_product_field_type'] : array();
        $min                = !empty($data['m_box_product_price_min'])                       ? $data['m_box_product_price_min'] : null;
        $max                = !empty($data['m_box_product_price_max'])                       ? $data['m_box_product_price_max'] : null;
        $country            = !empty($data['m_box_product_country'])                         ? $data['m_box_product_country'] : null;
        $attributeValueId   = !empty($data['m_box_product_attribute_values_ids_selected'])   ? $data['m_box_product_attribute_values_ids_selected'] : array();
        $categoryId         = !empty($data['m_box_category_tree_ids_selected'])         ? $data['m_box_category_tree_ids_selected'] : array();
        
        // Pagination config
        $pageCurrent        = !empty($data['m_pag_current'])                ? $data['m_pag_current'] : 1;
        $pageNbPerPage      = !empty($data['m_pag_nb_per_page'])            ? $data['m_pag_nb_per_page'] : null;
        $pageNbBeforeAfter  = !empty($data['m_pag_nb_page_before_after'])   ? $data['m_pag_nb_page_before_after'] : 3;
         
        foreach($categoryId as $catId){
            $categoryId = array_merge($categoryId, $this->categoryIdIterator($categorySvc->getAllSubCategoryIdById($catId, $onlyValid)));
        }

        $categoryProductList = $productSearchSvc->searchProductFull(
            $search,                        // $search
            $fieldType,                     // $fieldsTypeCodes           
            $attributeValueId,              // $attributeValuesIds      
            $min,                           // $priceMin
            $max,                           // $priceMax
            $lang,                          // $langId
            array_unique($categoryId),      // $categoryId
            $country,                       // $countryId
            $onlyValid,                     // $onlyValid
            null,                           // $start
            null,                           // $limit
            $sort,
            $priceColumn                    //price column (price_net, price_gross, etc.)
        );
        // Pagination
        $paginator = new Paginator(new ArrayAdapter($categoryProductList));
        $paginator->setCurrentPageNumber($pageCurrent)
                    ->setItemCountPerPage($pageNbPerPage);
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'categoryListProducts' => $paginator,
            'nbPageBeforeAfter' => $pageNbBeforeAfter,
            'langId' => $lang,
            'template' => $this->pluginFrontConfig['template_path'],
            'hasData' => (sizeof($categoryProductList) > 0) ? true : false,
            'sort_config' => $sort,

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
                    
                    // Category Tree Start
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
                    $langId = 1;
                    $container = new Container('meliscore');
                    if($container)
                    {
                        $locale = $container['melis-lang-locale'];
                        $melisEcomLangTable = $this->getServiceLocator()->get('MelisEcomLangTable');
                        $currentLangData = $melisEcomLangTable->getEntryByField('elang_locale', $locale)->current();
                        
                        if (!empty($currentLang))
                        {
                            $currentLangName = $currentLang->elang_name;
                            $langId = $currentLang->elang_id;
                        }
                    }
                    
                    $viewModelTab->productId = $productId = 1;
                    $viewModelTab->langData = $recLangData;
                    $viewModelTab->currentLangName = $currentLangName;
                    $viewModelTab->currentLangLocale = $locale;
                    // Category Tree End
                    
                    // Product Attributes Start
                    $attrSrv = $this->getServiceLocator()->get('MelisComAttributeService');
                    $attrs = $attrSrv->getAttributeListAndValues(null, true, true, $langId);
                    $viewModelTab->attrs = $attrs;
                    // Product Attributes End
                    
                    // Product Text Types Start
                    $prdTextTypeTbl = $this->getServiceLocator()->get('MelisEcomProductTextTypeTable');
                    $prdTextTypes = $prdTextTypeTbl->fetchAll()->toArray();
                    $viewModelTab->prdTextTypes = $prdTextTypes;
                    // Product Text Types End
                    
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
                    
                    if (in_array($formKey, array('melis_commerce_plugin_full_category_product_list_config', 'melis_commerce_plugin_full_category_product_list_pagination_config')))
                    {
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
                    }
                    else
                    {
                        $success = true;
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
        $data['template_path'] = $this->pluginFrontConfig['template_path'];
        $data['m_box_category_tree_ids_selected'] = $this->pluginFrontConfig['m_box_category_tree_ids_selected'];
        $data['m_box_filter_price_column'] = $this->pluginFrontConfig['m_box_filter_price_column'];
        $data = ArrayUtils::merge($data, $this->pluginFrontConfig['sorter']);
        $data = ArrayUtils::merge($data, $this->pluginFrontConfig['filters']);
        $data = ArrayUtils::merge($data, $this->pluginFrontConfig['pagination']);
        if(isset($this->pluginFrontConfig['m_col_name'])){
            $data['m_col_name'] = $this->pluginFrontConfig['m_col_name'];
        }
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

            if (!empty($xml->m_col_name))
            {
                $configValues['sorter']['m_col_name'] = (string)$xml->m_col_name;
            }
            
            if (!empty($xml->m_order))
            {
                $configValues['sorter']['m_order'] = (string)$xml->m_order;
            }
            
            if (!empty($xml->m_box_product_search))
            {
                $configValues['filters']['m_box_product_search'] = (string)$xml->m_box_product_search;
            }
            
            if (!empty($xml->m_box_product_field_type))
            {
                $configValues['filters']['m_box_product_field_type'] = json_decode((string)$xml->m_box_product_field_type, true);
            }

            if (!empty($xml->m_box_filter_price_column))
            {
                $configValues['m_box_filter_price_column'] = (string)$xml->m_box_filter_price_column;
            }
            
            if (!empty($xml->m_box_product_price_min))
            {
                $configValues['filters']['m_box_product_price_min'] = (string)$xml->m_box_product_price_min;
            }
            
            if (!empty($xml->m_box_product_price_max))
            {
                $configValues['filters']['m_box_product_price_max'] = (string)$xml->m_box_product_price_max;
            }
            
            if (!empty($xml->m_box_filter_docs))
            {
                $configValues['filters']['m_box_filter_docs'] = json_decode((string)$xml->m_box_filter_docs, true);
            }
            
            if (!empty($xml->m_box_product_country))
            {
                $configValues['filters']['m_box_product_country'] = (string)$xml->m_box_product_country;
            }
            
            if (!empty($xml->m_box_product_only_valid))
            {
                $configValues['filters']['m_box_product_only_valid'] = (string)$xml->m_box_product_only_valid;
            }
            
            if (!empty($xml->m_box_product_attribute_values_ids_selected))
            {
                $configValues['filters']['m_box_product_attribute_values_ids_selected'] = json_decode((string)$xml->m_box_product_attribute_values_ids_selected, true);
            }
            
            if (!empty($xml->m_box_category_tree_ids_selected))
            {
                $configValues['filters']['m_box_category_tree_ids_selected'] = json_decode((string)$xml->m_box_category_tree_ids_selected, true);
            }
            
            if (!empty($xml->m_pag_current))
            {
                $configValues['pagination']['m_pag_current'] = (string)$xml->m_pag_current;
            }
            
            if (!empty($xml->m_pag_nb_per_page))
            {
                $configValues['pagination']['m_pag_nb_per_page'] = (string)$xml->m_pag_nb_per_page;
            }
            
            if (!empty($xml->m_pag_nb_page_before_after))
            {
                $configValues['pagination']['m_pag_nb_page_before_after'] = (string)$xml->m_pag_nb_page_before_after;
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
        
        if(!empty($parameters['m_box_product_field_type']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_product_field_type><![CDATA[' . json_encode($parameters['m_box_product_field_type']) . ']]></m_box_product_field_type>';
        }

        if(!empty($parameters['m_box_filter_price_column']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_filter_price_column><![CDATA[' . $parameters['m_box_filter_price_column'] . ']]></m_box_filter_price_column>';
        }
    
        if(!empty($parameters['m_box_product_price_min']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_product_price_min><![CDATA[' . $parameters['m_box_product_price_min'] . ']]></m_box_product_price_min>';
        }
    
        if(!empty($parameters['m_box_product_price_max']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_product_price_max><![CDATA[' . $parameters['m_box_product_price_max'] . ']]></m_box_product_price_max>';
        }
    
        if(!empty($parameters['m_box_filter_docs']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_filter_docs><![CDATA[' . json_encode($parameters['m_box_filter_docs']) . ']]></m_box_filter_docs>';
        }
        
        if(!empty($parameters['m_box_product_country']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_product_country><![CDATA[' . $parameters['m_box_product_country'] . ']]></m_box_product_country>';
        }
        
        if(!empty($parameters['m_box_product_only_valid']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_product_only_valid><![CDATA[' . $parameters['m_box_product_only_valid'] . ']]></m_box_product_only_valid>';
        }
        
        if(!empty($parameters['m_pag_current']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_pag_current><![CDATA[' . $parameters['m_pag_current'] . ']]></m_pag_current>';
        }
        
        if(!empty($parameters['m_pag_nb_per_page']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_pag_nb_per_page><![CDATA[' . $parameters['m_pag_nb_per_page'] . ']]></m_pag_nb_per_page>';
        }
        
        if(!empty($parameters['m_pag_nb_page_before_after']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_pag_nb_page_before_after><![CDATA[' . $parameters['m_pag_nb_page_before_after'] . ']]></m_pag_nb_page_before_after>';
        }
        
        if(!empty($parameters['m_box_product_attribute_values_ids_selected']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_product_attribute_values_ids_selected><![CDATA[' . json_encode($parameters['m_box_product_attribute_values_ids_selected']) . ']]></m_box_product_attribute_values_ids_selected>';
        }
        
        if(!empty($parameters['m_box_category_tree_ids_selected']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_box_category_tree_ids_selected><![CDATA[' . json_encode($parameters['m_box_category_tree_ids_selected']) . ']]></m_box_category_tree_ids_selected>';
        }
        
        if(!empty($parameters['m_col_name']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_col_name><![CDATA[' . $parameters['m_col_name'] . ']]></m_col_name>';
        }
        
        if(!empty($parameters['m_order']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_order><![CDATA[' . $parameters['m_order'] . ']]></m_order>';
        }
    
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
    
    /**
     * Recurssive function to retrieve category ids
     * @param [] $categories array of categories
     * @return $categoryId[]
     */
    private function categoryIdIterator($categories)
    {
        $categoryId = array();
        foreach($categories as $category){
            $categoryId[] = $category['cat_id'];
            if(is_array($category['cat_children'])){
                $categoryId = array_merge($categoryId, $this->categoryIdIterator($category['cat_children']));
            }
        }
        return $categoryId;
    }
}
