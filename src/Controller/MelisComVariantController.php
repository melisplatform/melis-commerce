<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Session\Container;
use MelisCore\Controller\MelisAbstractActionController;
use MelisCommerce\Model\ProductAttribute;

class MelisComVariantController extends MelisAbstractActionController
{

    protected $variantId;
    protected $productId;


    protected function setVariantId($variantId)
    {
        $this->variantId = $variantId;
    }

    protected function getVariantId()
    {
        return $this->variantId;
    }

    protected function setProductId($productId)
    {
        $this->productId = $productId;
    }

    protected function getProductId()
    {
        return $this->productId;
    }

    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_variants');

        return $melisTool;
    }

    private function setProductVariables($productId)
    {
        $prodSvc = $this->getServiceManager()->get('MelisComProductService');
        $prodData = $prodSvc->getProductById($productId);
        $this->layout()->setVariables(array(
            'product' =>  $prodData->getProduct(),
        ));
    }

    private function setVariantVariables($variantId)
    {
        $varService = $this->getServiceManager()->get('MelisComVariantService');
        $varObj = $varService->getVariantById($variantId);
        $this->layout()->setVariables(array(
            'variantObj' =>  $varObj,
        ));
    }

    /**
     * renders the page container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantPageAction()
    {
        $view = new ViewModel();
        $variantId = (int) $this->params()->fromQuery('variantId', '');

        if (is_numeric($this->params()->fromQuery('variantId', ''))) {
            $this->setVariantId($variantId);
            $view->variantId = $this->getVariantId();
            $this->setVariantVariables($variantId);
        }

        if (is_numeric($this->params()->fromQuery('productId', ''))) {
            $productId = (int) $this->params()->fromQuery('productId', '');
            $this->setProductId($productId);
            $this->setProductVariables($productId);
            $view->productId = $this->getProductId();
        }

        $container = new Container('meliscommerce');
        $container['documents'] = array('docRelationType' => 'variant', 'docRelationId' => $variantId);

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * renders the page header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the header title container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantHeaderLeftAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the header's heading
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantHeaderHeadingAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $prodSvc = $this->getServiceManager()->get('MelisComProductService');
        $prodTitle = $prodSvc->getProductTextsById($this->getProductId(), 'TITLE', $this->getTool()->getCurrentLocaleID());
        $prd_reference = $prodSvc->getProductById($this->getProductId(), $this->getTool()->getCurrentLocaleID())->getProduct()->prd_reference;
        $prodTitle = empty($prodTitle[0]->ptxt_field_short) ? $prd_reference : $prodTitle[0]->ptxt_field_short;
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->productId = $this->getProductId();
        $view->prodTitle = $prodTitle;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the header button container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantHeaderRightAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the header button save
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantHeaderSaveAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the header button cancel
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantHeaderCancelAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    public function renderVariantContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab head container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabHeadAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab head main tab
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab contents container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabContentsContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab individual contents container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab individual content header container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabContentHeaderContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();

        if (empty($this->getVariantId()) && $this->getVariantId() == '') {
            $variantId = $this->params()->fromQuery('variantId', 0);
            $this->setVariantId($variantId);
            $this->setVariantVariables($variantId);
        }

        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab individual content header left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabContentHeaderLeftAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab individual content header right container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabContentHeaderRightAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab individual content header
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabContentHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab individual general container, for main,price,stocks
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabContentGeneralContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the variant status switch
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabMainHeaderSwitchAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab main sub content container , <div class="col-xs-12 col-lg-6">
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabMainSubContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the sub content sub container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabSubContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the sub content heading container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabSubHeadingAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the sub content header text
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabSubHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the sub content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabSubContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the information tab content
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabMainInformationContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_variants/meliscommerce_variants_information_form', 'meliscommerce_variants_information_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $informationForm = $factory->createForm($appConfigForm);
        if (!empty($this->layout()->variantObj)) {
            $informationForm->setData((array)$this->layout()->variantObj->getVariant());
        }
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        $view->setVariable('meliscommerce_variants_information_form', $informationForm);
        return $view;
    }

    /**
     * renders the file attachement button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabMainFilesAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the files contents
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabMainFilesContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the attributes contents
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabMainAttributesContentAction()
    {
        // render-variant-tab-main-attributes-content
        $varAttrVals = [];
        $variantId = $this->params()->fromQuery('variantId', null);
        $productId = $this->params()->fromQuery('productId', null);
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $varSvc = $this->getServiceManager()->get('MelisComVariantService');
        $varAttrVals = $varSvc->getVariantAttributesValuesById($variantId);
        $langId = $this->getTool()->getCurrentLocaleID();
        $attributes = $this->updatedFetchAttributes($productId, $langId) ?: [];

        $view = new ViewModel();
        $view->variantAttributes = $varAttrVals;
        $view->attributes = $attributes;
        $view->melisKey = $melisKey;
        $view->variantId = $variantId;
        $view->uniqid = $variantId . "_id_meliscommerce_variant_main_attributes_content";
        return $view;
    }

    public function renderVariantTabMainAttributesContentPlaceholderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->variantId = $this->getVariantId();
        $view->productId = $this->getProductId();
        $view->melisKey = $melisKey;
        $view->uniqid = $this->getVariantId() . "_id_meliscommerce_variant_main_attributes_content_placeholder";

        return $view;
    }

    private function updatedFetchAttributes($productId, $langId)
    {
        $attributes = [];
        $productAttributes = ProductAttribute::getProductAttributesThroughRawQuery($productId, $langId)->get();

        foreach ($productAttributes as $prodAttr) {
            $key = $prodAttr->atrans_name ?: $prodAttr->attr_reference;
            $columnType = "avt_v_$prodAttr->atype_column_value";
            $value = $prodAttr->$columnType;

            if ($columnType === 'avt_v_datetime') {
                $value = $this->getTool()->dateFormatLocale($value);
            }

            if (in_array($columnType, ['avt_v_text', 'avt_v_varchar'])) {
                $value = $this->getTool()->limitedText($value, 50);
            }

            $attributes[$key][] = ['id' => $prodAttr->atval_id, 'value' => $value];
        }

        $formattedAttributes = [];
        foreach ($attributes as $attributes => $values) {
            $formattedAttributes[] = [
                'name' => $attributes,
                'values' => $values
            ];
        }

        return $formattedAttributes;
    }

    /**
     * renders the images filters container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantMainImagesFiltersAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the images filter type
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantMainImageFilterTypeAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the images filter country
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantMainImageFilterCountryAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the images portfolio
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantMainImagesPortfolioAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the images add image button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantPortfolioSubHeaderAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the text tab add language button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabContentHeaderAddLanguageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the text tab select field button
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabContentHeaderTextFieldAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the text tab left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabTextContentLeftContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tex tab language text list
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabTextLanguageListAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the text tab right container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabTextContentRightContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the text tab text fields
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabTextFieldsAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab prices header add acountry action
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabPricesHeaderAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab prices left content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabPricesContentLeftContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab prices right content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabPricesContentRightContainerAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab sotcks head add country
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabStocksHeaderAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the prices country list
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabStocksCountryListAction()
    {

        $ctyGeneral =   '<li class="">
                    		<a data-toggle="tab" href="#' . $this->getVariantId() . '_stock-General" data-country="General" aria-expanded="true"><span>General</span>
                    			<i class="fa fa-globe"></i>
                    		</a>
                    	</li>';
        $ctyFormat =    '<li class="">
                    		<a data-toggle="tab" href="#%s_stock-%s" data-country="%s" aria-expanded="true"><span>%s</span>
                     			%s
                    		</a>
                    	</li>';

        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        $countries = $countryTable->getCountries();
        $ctyData[] = $ctyGeneral;
        foreach ($countries as $country) {
            $countryName = $this->getTool()->escapeHtml($country->ctry_name);
            $imageData = $country->ctry_flag;
            $image = !empty($imageData) ? '<span class="float-right"><img src="data:image/jpeg;base64,' . ($imageData) . '" class="imgDisplay float-right"/></span>' : '<i class="fa fa-globe"></i>';
            $ctyData[] = sprintf($ctyFormat, $this->getVariantId(), str_replace(' ', '', $countryName), $countryName, $countryName, $image);
        }
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->countries = $ctyData;
        $view->melisKey = $melisKey;
        $view->variantId = $this->getVariantId();
        return $view;
    }

    /**
     * renders the tab prices country form for prices
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderVariantTabStocksCountryFormAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_variants/meliscommerce_variants_stocks_form', 'meliscommerce_variants_stocks_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $stocksForm = $factory->createForm($appConfigForm);
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        $countries = $countryTable->getCountries();
        $stockList = $variantSvc->getVariantStocksById($this->getVariantId());
        $c = 1;
        //set general stocks
        $emptyDate = '0000-00-00 00:00:00';
        foreach ($stockList as $stock) {
            if ($stock->stock_country_id == -1) {
                $checkDate = (string) $stock->stock_next_fill_up;
                if ($checkDate != $emptyDate) {
                    $stock->stock_next_fill_up = $this->getTool()->dateFormatLocale($stock->stock_next_fill_up);
                } else {
                    $stock->stock_next_fill_up = null;
                }

                $data[0] = (array)$stock;
            }
        }
        $data[0]['name'] = 'General';
        $data[0]['stock_country_id'] = '-1';
        //set country stocks
        foreach ($countries as $country) {
            foreach ($stockList as $stock) {
                if ($stock->stock_country_id == $country->ctry_id) {
                    $checkDate = (string) $stock->stock_next_fill_up;
                    if ($checkDate != $emptyDate) {
                        $stock->stock_next_fill_up = $this->getTool()->dateFormatLocale($stock->stock_next_fill_up);
                    } else {
                        $stock->stock_next_fill_up = null;
                    }

                    $data[$c] = (array)$stock;
                }
            }
            $data[$c]['name'] = $country->ctry_name;
            $data[$c]['stock_country_id'] = $country->ctry_id;
            $c++;
        }
        $view = new ViewModel();
        $view->data = $data;
        $view->melisKey = $melisKey;
        $view->setVariable('meliscommerce_variants_stocks_form', $stocksForm);
        $view->datePickerInit = $this->getTool()->datePickerInit('stocksDate');
        $view->variantId = $this->getVariantId();
        return $view;
    }

    public function setSeoAction()
    {
        $seoResult = array(
            'success' => array(),
            'errors' => array(),
            'datas' => array(
                'seo_data' => array(),
            ),
        );
        $postValues = $this->getRequest()->getPost()->toArray();
        $postValues = $this->getTool()->sanitizeRecursive($postValues);
        $melisComSeoService = $this->getServiceManager()->get('MelisComSeoService');

        return new JsonModel($seoResult);
    }

    /**
     * process the form data before saving, checks if form data are valid
     * @return \Laminas\View\Model\JsonModel
     */
    public function saveVariantAction()
    {
        //defaults
        $success = false;
        $data = array();
        $errors  = array();
        $textMessage = 'tr_meliscommerce_variants_page_save_fail';
        $textTitle = 'tr_meliscommerce_variants_page';
        $variantId = null;
        $varSku = '';
        $logTypeCode = '';
        $container = new Container('meliscommerce');
        unset($container['variant-tmp-data']);
        //get services
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');

        if ($this->getRequest()->isPost()) {
            $this->getEventManager()->trigger('meliscommerce_variant_save_start', $this, array());

            if (!empty($container['variant-tmp-data'])) {
                if (!empty($container['variant-tmp-data']['success'])) {
                    $success = $container['variant-tmp-data']['success'];
                }
                if (!empty($container['variant-tmp-data']['errors']))
                    $errors = $container['variant-tmp-data']['errors'];
                if (!empty($container['variant-tmp-data']['datas']))
                    $data = $container['variant-tmp-data']['datas'];
            }

            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);

            if (!empty($postValues['variantId'])) {
                $logTypeCode = 'ECOM_VARIANT_UPDATE';
                $variantId = $postValues['variantId'];
            } else {
                $logTypeCode = 'ECOM_VARIANT_ADD';
            }

            unset($container['variant-tmp-data']);
            if ($success) {
                $variantId = $data['var_id'];
                $variant = $variantSvc->getVariantById($variantId)->getVariant();
                if ($variant) {
                    $varSku = $variant->var_sku;
                }
                $textMessage = 'tr_meliscommerce_variants_page_save_success';
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
            'chunk' => array('variantId' => $variantId, 'varSku' => $varSku),
        );

        $this->getEventManager()->trigger(
            'meliscommerce_variant_save_end',
            $this,
            array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $variantId))
        );

        return new JsonModel($response);
    }

    /**
     * triggered by event, saves the processed data from the variant form
     * @return \Laminas\View\Model\JsonModel
     */
    public function saveVariantDataAction()
    {

        $success = false;
        $errors = array();
        $data = array();
        $container = new Container('meliscommerce');
        if (!empty($container['variant-tmp-data'])) {
            if (!empty($container['variant-tmp-data']['success'])) {
                $success = $container['variant-tmp-data']['success'];
                $textMessage = $this->getTool()->getTranslation('tr_meliscommerce_variants_page_save_success');
            }
            if (!empty($container['variant-tmp-data']['errors']))
                $errors = $container['variant-tmp-data']['errors'];
            if (!empty($container['variant-tmp-data']['datas']))
                $data = $container['variant-tmp-data']['datas'];
        }

        unset($container['variant-tmp-data']);

        $variantId = isset($data['variant']['var_id']) ? $data['variant']['var_id'] : null;
        unset($data['variant']['var_id']);

        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $stockTable = $this->getServiceManager()->get('MelisEcomVariantStockTable');
        if ($success) {
            if (!is_null($variantId)) {
                $variantSvc->deleteVariantAttributeById($variantId);
            }
            $stockCount = $data['stocks'];
            for ($i = 0; $i < count($stockCount); $i++) {
                $stock = $data['stocks'][$i];
                $stock_id = $stock['stock_id'];

                if (!is_numeric($stock['stock_quantity'])) {
                    unset($data['stocks'][$i]);
                    $stockTable->deleteById($stock_id);
                }
            }
            $var_id = $variantSvc->saveVariant($data['variant'], $data['prices'], $data['stocks'], $data['varAttr'], $data['seo_data'], $variantId);

            if ($var_id) {
                $success = true;
                if ($data['variant']['var_main_variant']) {

                    // un assigned main variant
                    foreach ($variantSvc->getVariantListByProductId($data['variant']['var_prd_id']) as $prodVar) {
                        if ($prodVar->getId() != $var_id) {
                            $variant = ['var_main_variant' => '0'];
                            $variantSvc->saveVariant($variant, null, null, null, array(), $prodVar->getId());
                        }
                    }
                }
                $data['var_id'] = $var_id;
            }
        }

        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );

        return new JsonModel($results);
    }

    public function validateVariantFormAction()
    {
        $data = array(
            'variant' => array()
        );
        $errors = array();
        $success = false;
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigInformationForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_variants/meliscommerce_variants_information_form', 'meliscommerce_variants_information_form');
        $informationForm = $factory->createForm($appConfigInformationForm);

        $variantTable = $this->getServiceManager()->get('MelisEcomVariantTable');

        $postValues = $this->getRequest()->getPost()->toArray();
        $postValues = $this->getTool()->sanitizeRecursive($postValues);

        if (!empty($postValues['variant'])) {
            $informationForm->setData($postValues['variant'][0]);
            if (!$informationForm->isValid()) {

                $varError = $informationForm->getMessages();
                foreach ($varError as $keyError => $valueError) {
                    foreach ($appConfigInformationForm['elements'] as $keyForm => $valueForm) {
                        if (
                            $valueForm['spec']['name'] == $keyError &&
                            !empty($valueForm['spec']['options']['label'])
                        )
                            $varError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                    }
                }
                array_push($errors, $varError);
            } else {
                $success = true;
            }

            //check if sku is unique
            $exist = $variantTable->getEntryByField('var_sku', $postValues['variant'][0]['var_sku'])->current();
            if ($exist) {
                if ($exist->var_id != $postValues['variant'][0]['var_id']) {
                    $success = false;
                    $errorTitle   = $this->getTool()->getTranslation('tr_meliscommerce_variant_main_information_main_variant_input_label');
                    $errorMessage = $this->getTool()->getTranslation('tr_meliscommerce_variants_page_duplicate_sku');
                    $errors[] = array($errorTitle => $errorMessage);
                }
            }

            $data['variant'] = $informationForm->getData();
            $data['variant']['var_id'] = $postValues['variant'][0]['var_id'];
            $data['variant']['var_prd_id'] = (int)$postValues['variant'][0]['var_prd_id'];
            $data['variant']['var_main_variant'] = $postValues['variant'][0]['var_main_variant'];
            $data['variant']['var_status'] = $postValues['variant'][0]['var_status'];
            if (empty($data['variant']['var_id'])) {
                $data['variant']['var_date_creation'] = date("Y-m-d H:i:s");
                $data['variant']['var_user_id_creation'] = $this->getTool()->getCurrentUserId();
            } else {
                $data['variant']['var_date_edit'] = date("Y-m-d H:i:s");
                $data['variant']['var_user_id_edit'] = $this->getTool()->getCurrentUserId();
            }
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }

    public function validateStockFormAction()
    {
        $data = array(
            'stocks' => array()
        );
        $errors = array();
        $success = false;
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigStockForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_variants/meliscommerce_variants_stocks_form', 'meliscommerce_variants_stocks_form');
        $stockForm = $factory->createForm($appConfigStockForm);

        $postValues = $this->getRequest()->getPost()->toArray();
        $postValues = $this->getTool()->sanitizeRecursive($postValues);
        if (!empty($postValues['stockForm'])) {
            foreach ($postValues['stockForm'] as $stock) {

                $countryId = (int) $stock['stock_country_id'];
                unset($stock['stock_country_id']);
                if (!empty($stock)) {

                    $stock['stock_country_id'] = $countryId;
                    $stockForm->setData($stock);

                    if (!$stockForm->isValid()) {
                        $stockError = $stockForm->getMessages();
                        foreach ($stockError as $keyError => $valueError) {
                            foreach ($appConfigStockForm['elements'] as $keyForm => $valueForm) {
                                if (
                                    $valueForm['spec']['name'] == $keyError &&
                                    !empty($valueForm['spec']['options']['label'])
                                )
                                    $stockError[$keyError]['label'] = $valueForm['spec']['options']['label'];
                            }
                        }
                        array_push($errors, $stockError);
                        break;
                    } else {
                        $success = true;
                    }
                    $tmp = $stockForm->getData();
                    $tmp['stock_quantity'] = ($tmp['stock_quantity'] == '') ?  null : $tmp['stock_quantity'];
                    $tmp['stock_next_fill_up'] = $this->getTool()->localeDateToSql($tmp['stock_next_fill_up']);
                    $data['stocks'][] = $tmp;
                }
            }
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }

    public function validateVariantAttributeAction()
    {
        $data = array(
            'varAttr' => array()
        );
        $errors = array();
        $success = true;
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $postValues = $this->getRequest()->getPost()->toArray();
        $postValues = $this->getTool()->sanitizeRecursive($postValues);

        if (!empty($postValues['attrVal'])) {
            foreach ($postValues['attrVal'] as $attrVal) {
                if (!empty($attrVal['vatv_attribute_value_id'])) {
                    $data['varAttr'][] = [

                        'vatv_variant_id' => '',
                        'vatv_attribute_value_id' => $attrVal['vatv_attribute_value_id'],
                    ];
                }
            }
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }

    public function validateVariantSeoAction()
    {
        $seoResult = array(
            'success' => array(),
            'errors' => array(),
            'datas' => array(
                'seo_data' => array(),
            ),
        );
        $postValues = $this->getRequest()->getPost()->toArray();
        $postValues = $this->getTool()->sanitizeRecursive($postValues);
        $melisComSeoService = $this->getServiceManager()->get('MelisComSeoService');
        if (!empty($postValues['variant_seo'])) {
            $seoResult = $melisComSeoService->validateSEOData('variant', $postValues['variant_seo']);
        }
        return new JsonModel($seoResult);
    }

    public function deleteVariantAction()
    {
        $variantId = null;
        $response = array();
        $success = 0;
        $errors  = array();
        $textMessage = 'tr_meliscommerce_variants_delete_fail';
        $textTitle = 'tr_meliscommerce_variants_page';

        $varSvc = $this->getServiceManager()->get('MelisComVariantService');
        if ($this->getRequest()->isPost()) {
            $postValues = $this->getRequest()->getPost()->toArray();

            $variantId = $postValues['var_id'];

            $this->getEventManager()->trigger('meliscommerce_variant_delete_start', $this, array());
            $success = $varSvc->deleteVariantById($variantId);
            if ($success) {
                $textMessage = 'tr_meliscommerce_variants_delete_success';
                $success = 1;
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );

        $this->getEventManager()->trigger(
            'meliscommerce_variant_delete_end',
            $this,
            array_merge($response, array('typeCode' => 'ECOM_VARIANT_DELETE', 'itemId' => $variantId))
        );

        return new JsonModel($response);
    }

    /**
     * This method deletes a stock entry if the country its affected to is deleted
     * @return \Laminas\View\Model\JsonModel
     */
    public function stockCountryDeletedAction()
    {
        $success = 0;
        $errors = array();
        $data = array();
        $countryId = -1;

        $stockTable = $this->getServiceManager()->get('MelisEcomVariantStockTable');
        $countryTable = $this->getServiceManager()->get('MelisEcomCountryTable');

        $countryId = $this->getRequest()->getPost('id');

        //check if country is already deleted
        $country = $countryTable->getEntryById($countryId);
        if ($country->count() === 0) {
            if (is_numeric($countryId)) {
                $stockTable->deleteByField('stock_country_id', $countryId);
                $success = 1;
            }
        }
        $results = array(
            'success' => $success,
            'errors' => $errors,
            'datas' => $data,
        );
        return new JsonModel($results);
    }

    /**
     * Retrieves  form errors
     * @param object $form the form object
     * @param object $formConfig the app config of the form
     * @return errors[] | null
     */
    private function getFormErrors($form, $formConfig)
    {
        $errors = array();
        foreach ($form->getMessages() as $keyError => $valueError) {
            foreach ($formConfig['elements'] as $keyForm => $valueForm) {
                if ($valueForm['spec']['name'] == $keyError && !empty($valueForm['spec']['options']['label'])) {
                    $key = $valueForm['spec']['options']['label'];
                    $errors[$key] = $valueError;
                }
            }
        }
        return $errors;
    }
}

