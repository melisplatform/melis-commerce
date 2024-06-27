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
use Laminas\Session\Container;
use Laminas\Stdlib\ArrayUtils;
use Laminas\View\Model\ViewModel;
/**
 * This plugin implements the business logic of the
 * "categorySliderListProducts" plugin.
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
 * $plugin = $this->MelisCommerceCategoryProductListPlugin();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCommerceCategoryProductListPlugin();
 * $parameters = array(
 *      'template_path' => 'MySiteTest/melis-demo-cms'
 * );
 * $pluginView = $plugin->render($parameters);
 * 
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'categorySliderListProducts');
 * 
 * How to display in your controller's view:
 * echo $this->categorySliderListProducts;
 */
class MelisCommerceCategoryProductListPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        $this->configPluginKey = 'meliscommerce';
        $this->pluginXmlDbKey = 'MelisCommerceCategoryProductListPlugin';
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
        
        // Get the parameters and config from $this->pluginFrontConfig (default > hardcoded > get > post)
        $data = $this->getFormData();
        
        $categoryIds    =   !empty($data['m_category_ids'])                 ? $data['m_category_ids'] : array();
        $includeSubCats =   !empty($data['m_include_sub_category_products']) ? true : false;

        $onlyValid = true;
        
        $catOrderColumn = !empty($data['m_cat_col_name'])   ? $data['m_cat_col_name'] : 'catt_name';
        $catOrder       = !empty($data['m_cat_order'])      ? $data['m_cat_order'] : 'ASC';
        
        $countryId      = !empty($data['m_country_id'])     ? $data['m_country_id'] : null;
        $prdOrderColumn = !empty($data['m_prd_col_name'])   ? $data['m_prd_col_name'] : 'pcat_order';
        $prdOrder       = !empty($data['m_prd_order'])      ? $data['m_prd_order'] : 'ASC';
        $prdLimit       = !empty($data['m_prd_limit'])      ? $data['m_prd_limit'] : null;
        
        $catSrv = $this->getServiceManager()->get('MelisComCategoryService');
        $prdSrv = $this->getServiceManager()->get('MelisComProductService');
        
        $cats = array();
        
        if (!empty($categoryIds))
        {
            $cats = $catSrv->getCategoriesByIds($categoryIds, $onlyValid, $langId, $catOrderColumn, $catOrder);
            
            foreach ($cats As $key => $val)
            {
                $categoryIds = array();
                
                if (!$includeSubCats)
                {
                    array_push($categoryIds, $val->getId());
                }
                else
                {
                    $categoryTreeIds = $catSrv->getAllSubCategoryIdById($val->getId(), $onlyValid);
                    
                    $categoryIds = $this->getCategorySubCategoriesIds($categoryTreeIds, $categoryIds);
                }
                
                $val->products = $prdSrv->getProductList($langId, $categoryIds, $countryId, $onlyValid, null, $prdLimit, $prdOrderColumn, $prdOrder);
            }
        }
        
        // Create an array with the variables that will be available in the view
        $viewVariables = array(
            'categoryProducts' => $cats,
            'langId' => $langId,
            'pluginId' => $data['id'],
        );
       
        // return the variable array and let the view be created
        return $viewVariables;
    }
    
    private function getCategorySubCategoriesIds($categoryTreeIds, $categoryIds)
    {
        foreach ($categoryTreeIds As $key => $val)
        {
            array_push($categoryIds, $val['cat_id']);
            
            if (!empty($val['cat_children']))
            {
                $categoryIds = $this->getCategorySubCategoriesIds($val['cat_children'], $categoryIds);
            }
        }
        
        return $categoryIds;
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
            foreach ($formConfig as $formKey => $config)
            {
                $form = $factory->createForm($config);
                $request = $this->getServiceManager()->get('request');
                $parameters = $request->getQuery()->toArray();
                
                if (!isset($parameters['validate']))
                {
                    $form->setData($this->getFormData());
                    $viewModelTab = new ViewModel();
                    $viewModelTab->setTemplate($config['tab_form_layout']);
                    $viewModelTab->modalForm = $form;
                    $viewModelTab->formData   = $this->getFormData();
                    
                    $langTable = $this->getServiceManager()->get('MelisEcomLangTable');
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
                        $melisEcomLangTable = $this->getServiceManager()->get('MelisEcomLangTable');
                        $currentLangData = $melisEcomLangTable->getEntryByField('elang_locale', $locale)->current();
                        
                        if (!empty($currentLang))
                        {
                            $currentLangName = $currentLang->elang_name;
                        }
                    }
                    
                    $viewModelTab->langData = $recLangData;
                    $viewModelTab->currentLangName = $currentLangName;
                    $viewModelTab->currentLangLocale = $locale;
                    
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
                    if (in_array($formKey, array('melis_commerce_plugin_category_product_list_config', 'melis_commerce_plugin_category_product_list_product_config')))
                    {
                        $form->setData($post);
                        
                        if ($form->isValid()) 
                        {
                            $success = true;
                        } 
                        else 
                        {
                            $errors = $form->getMessages();
                        }
                    }
                    elseif ($formKey == 'melis_commerce_plugin_category_product_list_tree_config')
                    {
                        if (empty($post['m_category_ids']))
                        {
                            $translator = $this->getServiceManager()->get('translator');
                            
                            $errors['m_category_ids'] = array(
                                'label' => $translator->translate('tr_meliscommerce_plugin_category_product_list_category'),
                                'isEmpty' => $translator->translate('tr_meliscommerce_plugin_category_product_list_category_no_category')
                            );
                        }
                        
                        $form->setData($post);
                        
                        if (!$form->isValid())
                        {
                            if (empty($errors))
                            {
                                $errors = $form->getMessages();
                            }
                            else
                            {
                                $errors = ArrayUtils::merge($errors, $form->getMessages());
                            }
                        }
                        
                        if (empty($errors)) 
                        {
                            $success = true;
                        }
                    }
                    
                    if (!empty($errors))
                    {
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
     * @return array
     */
    public function getFormData()
    {
        $data = parent::getFormData();
        $data['m_category_ids'] = (!empty($this->pluginFrontConfig['m_category_option']['m_category_ids'])) ? $this->pluginFrontConfig['m_category_option']['m_category_ids'] : array();
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
            
            if (!empty($xml->m_category_ids))
            {
                $configValues['m_category_option']['m_category_ids'] = json_decode((string)$xml->m_category_ids);
            }
            
            if (!empty($xml->m_cat_col_name))
            {
                $configValues['m_category_option']['m_cat_col_name'] = (string)$xml->m_cat_col_name;
            }
            
            if (!empty($xml->m_cat_order))
            {
                $configValues['m_category_option']['m_cat_order'] = (string)$xml->m_cat_order;
            }
            
            if (!empty($xml->m_include_sub_category_products))
            {
                $configValues['m_category_option']['m_include_sub_category_products'] = (string)$xml->m_include_sub_category_products;
            }
            
            if (!empty($xml->m_country_id))
            {
                $configValues['m_product_option']['m_country_id'] = (string)$xml->m_country_id;
            }
            
            if (!empty($xml->m_prd_col_name))
            {
                $configValues['m_product_option']['m_prd_col_name'] = (string)$xml->m_prd_col_name;
            }
            
            if (!empty($xml->m_prd_order))
            {
                $configValues['m_product_option']['m_prd_order'] = (string)$xml->m_prd_order;
            }
            
            if (!empty($xml->m_prd_limit))
            {
                $configValues['m_product_option']['m_prd_limit'] = (string)$xml->m_prd_limit;
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
        
        if (isset($parameters['m_include_sub_category_products']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_include_sub_category_products><![CDATA[' . $parameters['m_include_sub_category_products'] . ']]></m_include_sub_category_products>';
        }
        
        if(!empty($parameters['m_category_ids']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_category_ids><![CDATA[' . json_encode($parameters['m_category_ids']) . ']]></m_category_ids>';
        }
        
        if(!empty($parameters['m_cat_col_name']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_cat_col_name><![CDATA[' . $parameters['m_cat_col_name'] . ']]></m_cat_col_name>';
        }
        
        if(!empty($parameters['m_cat_order']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_cat_order><![CDATA[' . $parameters['m_cat_order'] . ']]></m_cat_order>';
        }
        
        if(isset($parameters['m_country_id']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_country_id><![CDATA[' . $parameters['m_country_id'] . ']]></m_country_id>';
        }
        
        if(!empty($parameters['m_prd_col_name']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_prd_col_name><![CDATA[' . $parameters['m_prd_col_name'] . ']]></m_prd_col_name>';
        }
        
        if(!empty($parameters['m_prd_order']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_prd_order><![CDATA[' . $parameters['m_prd_order'] . ']]></m_prd_order>';
        }
        
        if(!empty($parameters['m_prd_limit']))
        {
            $xmlValueFormatted .= "\t\t" . '<m_prd_limit><![CDATA[' . $parameters['m_prd_limit'] . ']]></m_prd_limit>';
        }
        
        // Something has been saved, let's generate an XML for DB
        if (!empty($xmlValueFormatted))
        {
            $xmlValueFormatted = "\t".'<'.$this->pluginXmlDbKey.' id="'.$parameters['melisPluginId'].'">'.$xmlValueFormatted."\t".'</'.$this->pluginXmlDbKey.'>'."\n";
        }
        
        return $xmlValueFormatted;
    }
}
