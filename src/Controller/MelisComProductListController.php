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
use MelisCommerce\Model\Product;

class MelisComProductListController extends MelisAbstractActionController
{
    /**
     * Product List Page Container
     */
    public function renderProductListPageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Product Header Container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductListHeaderAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;

        return $view;
    }

    /**
     * Displays the add Button in the headers
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductListHeaderAddAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;

        return $view;
    }
    /**
     * Renders to the display of the content
     */
    public function renderProductListContentAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $columns = $this->getTool()->getColumns();
        $columns['action'] =  array(
            'text' => $this->getTranslation('tr_meliscommerce_product_list_col_action'),
            'css' => array('width' => '30%')
        );

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $this->getTool()->getDataTableConfiguration('#tableProductList', true, false, array('order' => '[[ 0, "desc" ]]'));

        return $view;
    }

    /**
     * Renders to the bulk action filter of the table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductListContentFilterBulkAction()
    {
        return new ViewModel();
    }

    /**
     * Renders to the search action filter of the table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductListContentFilterSearchAction()
    {
        return new ViewModel();
    }

    /**
     * Renders to the limit action filter of the table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductListContentFilterLimitAction()
    {
        return new ViewModel();
    }

    /**
     * Renders to the grid view action of the table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductListContentFilterGridViewAction()
    {
        return new ViewModel();
    }

    /**
     * Renders to the list view action of the table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductListContentFilterListViewAction()
    {
        return new ViewModel();
    }

    /**
     * Renders to the refresh action of the table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductListContentFilterRefreshAction()
    {
        return new ViewModel();
    }


    /**
     * Renders to the Edit Button in the table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductListContentActionDeleteAction()
    {
        return new ViewModel();
    }

    /**
     * Renders to the Delete Button in the table
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderProductListContentActionEditAction()
    {
        return new ViewModel();
    }


    /**
     * Renders the category filter
     */
    public function renderProductListTableCategoryAndCatalogFilterAction() {}

    /**
     * This method return the list of Products
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function getProductsListAction()
    {
        $success = 0;
        $prodSvc = $this->getServiceManager()->get('MelisComProductService');
        $categorySvc = $this->getServiceManager()->get('MelisComCategoryService');
        $docSvc = $this->getServiceManager()->get('MelisComDocumentService');
        $viewHelperManager = $this->getServiceManager()->get('ViewHelperManager');
        $toolTipTable = $viewHelperManager->get('ToolTipTable');
        $langId = $this->getTool()->getCurrentLocaleID();

        $columns = array();
        $dataCount = 0;
        $filterCount = 0;
        $draw = 0;
        $tableData = array();
        $prodData = [];

        if ($this->getRequest()->isPost()) {
            $columns = array_keys($this->getTool()->getColumns());

            $sortOrder = $this->getRequest()->getPost('order');
            $sortOrder = $sortOrder[0]['dir'];

            $order = $this->getRequest()->getPost('order');
            $selColOrder = $columns[$order[0]['column']];

            $draw = (int) $this->getRequest()->getPost('draw');

            $start = (int) $this->getRequest()->getPost('start');
            $length =  (int) $this->getRequest()->getPost('length');

            $search = $this->getRequest()->getPost('search');
            $search = $search['value'];

            $selectedCategories = $this->getRequest()->getPost('selectedCategories', []);
            $categoryIds = $this->getSelectedCategoriesAndChildrenIds($selectedCategories);

            $prodData = Product::setDataTable(true)
                ->setTooltipService($toolTipTable)
                ->setTooltipColumns($this->getToolTipColumns())
                ->setDocumentService($docSvc)
                ->setStatusIcon(true)
                ->getProducts($langId, $categoryIds, null, null, $start, $length, $selColOrder, $order[0]['dir'], $search)->get();
        }

        $paginated =  Product::getProducts(
            $langId,
            $categoryIds,
            null,
            null,
            $start,
            $length,
            $selColOrder,
            $order[0]['dir'],
            $search,
            true
        )->count();

        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsFiltered' => $paginated,
            'recordsTotal' => $prodData->count(),
            'data' => $prodData,
        ));
    }

    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_products_list');

        return $melisTool;
    }

    /**
     * Returns the translation text
     * @param String $key
     * @param array $args
     * @return string
     */
    private function getTranslation($key, $args = [])
    {
        $translator = $this->getServiceManager()->get('translator');
        $text = vsprintf($translator->translate($key), $args);

        return $text;
    }

    public function getToolTipColumns()
    {
        $columns = array(
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_id') => array(
                'class' => 'center',
                //'rowspan' => '2',
                'style' => 'width:10px;',
            ),
            ' ' => array(
                'class' => 'center',
                //'rowspan' => '2',
                'style' => 'width:50px;',
            ),
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_image') => array(
                'class' => 'center',
                //'rowspan' => '2',
                'style' => 'width:100px;',
            ),
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_sku') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),

            $this->getTranslation('tr_meliscommerce_product_tooltip_col_attributes') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),

            $this->getTranslation('tr_meliscommerce_product_tooltip_col_country') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_price') => array(
                'class' => 'text-right',
                //'rowspan' => '2',
                'style' => 'width:100px;',
            ),

            $this->getTranslation('tr_meliscommerce_product_tooltip_col_stocks') => array(
                'class' => 'text-right',
                //'rowspan' => '2',
                'style' => 'width:20px;',
            ),

        );

        return $columns;
    }

    public function getToolTipAction()
    {
        $content = array();

        if ($this->getRequest()->isPost()) {
            $productId = (int) $this->getRequest()->getPost('productId');
            $variantId = (int) $this->getRequest()->getPost('variantId');

            $productSvc = $this->getServiceManager()->get('MelisComProductService'); // added by: JRago

            // $productId = (int) $this->params()->fromQuery('productId');
            $variants = $this->getProductVariantsData($productId);
            $mainVarDom = '<span class="text-success"><i class="fa fa-fw fa-star"></i></span>';
            $imageDom      = '<img src="%s" width="60" class="rounded-circle"/>';
            $activeDom     = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
            $inactiveDom   = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
            $attributesDom = '<span class="btn btn-default cell-val-table" style="border-radius: 4px;color: #7D7B7B;">%s</span>';
            $viewHelperManager = $this->getServiceManager()->get('ViewHelperManager');
            $table = $viewHelperManager->get('ToolTipTable');
            $langId = $this->getTool()->getCurrentLocaleID();

            // added by: JRago for Junry to query product name for use on front-end
            $productData = (array) $productSvc->getProductTextsById($productId, null, $langId);
            $productName = null;
            $productNameDiffLang = null;
            if (! empty($productData)) {
                foreach ($productData as $key => $value) {
                    //store the first found product name
                    if (!empty($value->ptxt_field_short) && empty($productNameDiffLang)) {
                        $productNameDiffLang = $value->ptxt_field_short;
                    }
                    //get the text for the current language
                    if ($value->ptxt_lang_id == $langId) {
                        $productName = $value->ptxt_field_short ?? $value->ptxt_field_long;
                        break;
                    }
                }
                //check if product name is still empty to use the stored product name of the different lang
                if (empty($productName)) {
                    $productName = $productNameDiffLang;
                }
            } else {
                //use the product refrence as product name
                $data = $productSvc->getProductById($productId, $langId)->getProduct();
                if (isset($data->prd_reference) && $data->prd_reference) {
                    $productName = $data->prd_reference;
                }
            }
            // end added

            if ($variants) {
                $sContent = '';
                foreach ($variants as $variant) {

                    $sContent = '';
                    // TBODY START
                    $sContent .= $table->getBody();
                    $sContent .= $table->openTableRow();

                    // ID
                    $sContent .= $table->setRowData($variant['id'], array('class' => 'center'));



                    // STATUS
                    $status = ($variant['isActive']) ? $activeDom : $inactiveDom;


                    // MAIN VAR INDICATOR
                    $mainDom = ($variant['isMain']) ? $mainVarDom : ' ';
                    $sContent .= $table->setRowData($status . ' ' . $mainDom, array('class' => 'center'));

                    // IMAGE
                    $image = sprintf($imageDom, $variant['image']);

                    $sContent .= $table->setRowData($image, array('class' => 'center'));


                    // SKU
                    $sku = '<a class="openVariant" style="color:#fff" data-product-name="' . $productName . '" data-product-id="' . $productId . '">' . $this->getTool()->escapeHtml($variant['sku']) . '</a>';
                    $sContent .= $table->setRowData($sku, array('class' => 'text-left', 'style' => 'font-size: 14px'));

                    // ATTRIBUTES
                    $attributes = $table->setRowData('', array('class' => 'innerLR text-left'));
                    if ($variant['attributes']) {
                        $tmpAttr = '';
                        foreach ($variant['attributes'] as $attribute) {
                            $tmpAttr .= sprintf($attributesDom, $attribute);
                        }
                        $attributes = $table->setRowData($tmpAttr, array('class' => 'innerLR text-left'));
                    }
                    $sContent .= $attributes;


                    // Data Per Country
                    $sDataPerCountry = $table->setRowData('', array('class' => 'text-left')) . $table->setRowData('', array('class' => 'text-right')) . $table->setRowData('', array('class' => 'text-right'));
                    if ($variant['dataPerCountry']) {
                        $country = '';
                        $price = '';
                        $stock = '';
                        $priceNet = 0;
                        $stockQty = 0;
                        $genPrice = null;
                        $warningDom = '';
                        $endRedLabel = '';
                        foreach ($variant['dataPerCountry'] as $countryKey => $value) {
                            if ($countryKey) {
                                if ($value['price']) {
                                    $priceNet = $value['price']['price_net'];
                                } else {
                                    $priceNet = '';
                                }

                                if ($value['stock']) {
                                    if ($value['stock']['stock_quantity'] === null) {
                                        $stockQty = null;
                                    } elseif ($value['stock']['stock_quantity'] === 0) {
                                        $stockQty = 0;
                                    } else {
                                        $stockQty = $value['stock']['stock_quantity'];
                                    }
                                } else {
                                    $stockQty = null;
                                }

                                $isAllOk = false;
                                $priceNetValidator = strpos($priceNet ?? '', ',') !== false ? explode(',', $priceNet)[0] : null;
                                // if both price and stock is not 0, then display

                                if (is_null($stockQty) || is_numeric($stockQty)) {
                                    $isAllOk = true;
                                }


                                if ($isAllOk) {

                                    // label whole row with red if stock is set and it is zero
                                    if (!is_null($stockQty) && $stockQty == 0) {
                                        $warningDom = '<i class="fa fa-warning fa-2x float-left" style="color:#981a1f; font-size: 20px;"></i> ';
                                    }

                                    $image = !empty($value['flag']) ? '<img src="data:image/jpeg;base64,' . ($value['flag']) . '" class="" width="16" height="16"/>' : '<i class="fa fa-globe"></i>';
                                    $price .= $priceNet . '<br/>';
                                    $country .= $image . ' ' . $countryKey  . '<br/>';
                                    $stock .= $warningDom . $stockQty . '<br/>';
                                }
                            }
                        }
                        $sDataPerCountry = $table->setRowData($country, array('class' => 'text-left')) . $table->setRowData($price, array('class' => 'text-right')) . $table->setRowData($stock, array('class' => 'text-right'));
                    }

                    $sContent .= $sDataPerCountry;


                    $content[] = $sContent;
                    if ($variantId == $variant['id']) {
                        $vcontent[] = $sContent;
                    }
                }
            }
        }
        if (isset($vcontent)) {
            $content = $vcontent;
        }
        return new JsonModel(array(
            'content' => $content
        ));
    }
    private function getOnlyValueOnArray($keyOnArray, $haystack)
    {
        $newArray = array();
        if ($haystack) {
            foreach ($haystack as $key => $value) {
                if ($key == $keyOnArray) {
                    $newArray[$key] = $value;
                }
            }
        } else {
        }
        $newArray = "";

        return $newArray;
    }


    public function getProductVariantsData($productId)
    {
        $countriesTable = $this->getServiceManager()->get('MelisEcomCountryTable');
        $variantSvc = $this->getServiceManager()->get('MelisComVariantService');
        $countries =  $countriesTable->getCountries()->toArray();
        $langId = $this->getTool()->getCurrentLocaleID();
        $variantsData = $variantSvc->getVariantListByProductId($productId, $langId);
        $docSvc = $this->getServiceManager()->get('MelisComDocumentService');
        $productSvc = $this->getServiceManager()->get('MelisComProductService');

        $dataPrices = array();
        $variants = array();

        $varCtr = 0;
        foreach ($variantsData as $variant) {
            $varData = $variant->getVariant();
            $varAttr = $variant->getAttributeValues();
            $attributes = array();
            $dataPerCountry = array();
            $dataPricesAndStock = array();
            // attributes
            foreach ($varAttr as $attr) {

                $valCol = 'avt_v_' . $attr->atype_column_value;
                $value = '';
                //check for attribute value translations
                $foundTrans = false;
                foreach ($attr->atval_trans as $valTrans) {
                    if ($valTrans->avt_lang_id == $langId) {
                        $foundTrans = true;
                        $value = $valTrans->$valCol;
                    }
                }

                //if no corresponding translation get the first available trans
                if (!$foundTrans) {
                    foreach ($attr->atval_trans as $valTrans) {
                        $foundTrans = true;
                        $value = $valTrans->$valCol;
                        break;
                    }
                }

                //use the attribute value reference as name if no translation
                if (!$foundTrans) {
                    $value = $attr->atval_reference;
                }

                // edit value before rendering if necessary
                switch ($valCol) {
                    case 'avt_v_datetime':
                        $value = $this->getTool()->dateFormatLocale($value);
                        break;
                    case 'avt_v_text':
                    case 'avt_v_varchar':
                        $value = $this->getTool()->limitedText($this->getTool()->escapeHtml($value), 50);
                        break;
                }
                if ($value) {
                    $attributes[] = $value;
                }
            }

            // General
            $generalPricesData = $variantSvc->getVariantPricesById($varData->var_id);
            $generalStockData  = $variantSvc->getVariantStocksById($varData->var_id);
            $tmpData = array();

            $genPrice = [];
            $genStock = [];
            foreach ($generalPricesData as $genPriceData) {
                if ($genPriceData->price_country_id == -1 && $genPriceData->price_net) {
                    $genPriceData->price_net = $productSvc->formatPrice($genPriceData->price_net);
                    $genPrice = $genPriceData;
                }
            }

            foreach ($generalStockData as $genStockData) {
                if ($genStockData->stock_country_id == -1) {
                    $genStock = $genStockData;
                }
            }

            // Filtering variant that has only data of Price ar Stock
            if (!empty($genPrice) || !empty($genStock)) {
                $dataPricesAndStock[$this->getTool()->getTranslation('tr_meliscommerce_general_text')]['price'] = $this->getOnlyKeyOnArray('price_net', $genPrice);
                $dataPricesAndStock[$this->getTool()->getTranslation('tr_meliscommerce_general_text')]['stock'] = $this->getOnlyKeyOnArray('stock_quantity', $genStock);
            }

            // Countries
            foreach ($countries as $country) {

                $tmpPrice = null;
                $tmpStock = null;

                foreach ($variantSvc->getVariantPricesById($varData->var_id, $country['ctry_id']) as $vData) {
                    if ($vData->price_net) {
                        $vData->price_net = $productSvc->formatPrice($vData->price_net);
                        $tmpPrice = $this->getOnlyKeyOnArray('price_net', $vData);
                    }
                }
                foreach ($variantSvc->getVariantStocksById($varData->var_id, $country['ctry_id']) as $sData) {
                    if (is_numeric($sData->stock_quantity)) {
                        $tmpStock = $this->getOnlyKeyOnArray('stock_quantity', $sData);
                    }
                }



                // Filtering variant that has only data of Price ar Stock
                if (!empty($tmpPrice) || !empty($tmpStock)) {
                    $dataPricesAndStock[$country['ctry_name']]['flag'] = $country['ctry_flag'];
                    $dataPricesAndStock[$country['ctry_name']]['price']['price_net'] = null;
                    $dataPricesAndStock[$country['ctry_name']]['stock']['stock_quantity'] = null;

                    $dataPricesAndStock[$country['ctry_name']]['price'] = $tmpPrice;
                    $dataPricesAndStock[$country['ctry_name']]['stock'] = $tmpStock;
                }
            }

            $dataPerCountry = $dataPricesAndStock;
            $image = $docSvc->getDocDefaultImageFilePath('variant', $varData->var_id);
            $variants[$varCtr] = array(
                'id' =>   $varData->var_id,
                'isMain' => (bool) $varData->var_main_variant,
                'isActive' => (bool) $varData->var_status,
                'image' => $image,
                'sku' => $varData->var_sku,
                'attributes' => $attributes,
                'dataPerCountry' => $dataPerCountry,

            );
            $varCtr++;
        }

        return $variants;
    }

    private function getOnlyKeyOnArray($keyOnArray, $haystack)
    {
        $newArray = array();
        if ($haystack) {
            foreach ($haystack as $key => $value) {
                if ($key == $keyOnArray) {
                    $newArray[$key] = $value;
                }
            }
        } else {
            $newArray[$keyOnArray] = null;
        }


        return $newArray;
    }

    /**
     * This will get the selected categories id and it's children
     * @param $selectedCategories
     * @return array
     */
    private function getSelectedCategoriesAndChildrenIds($selectedCategories)
    {
        $categoryIds = [];
        $categoryService = $this->getServiceManager()->get('MelisComCategoryService');

        foreach ($selectedCategories as $selectedCategoryId) {
            $categoryTreeIds =  $categoryService->getAllSubCategoryIdById($selectedCategoryId, true);
            $categoryIds = $this->getCategoryIds($categoryTreeIds, $categoryIds);
        }

        return $categoryIds;
    }

    /**
     * Returns the id of a category and it's children
     * @param $categoryTreeIds
     * @param $categoryIds
     * @return mixed
     */
    private function getCategoryIds($categoryTreeIds, $categoryIds)
    {
        foreach ($categoryTreeIds as $key => $val) {
            array_push($categoryIds, $val['cat_id']);

            if (!empty($val['cat_children'])) {
                $categoryIds = $this->getCategoryIds($val['cat_children'], $categoryIds);
            }
        }

        return $categoryIds;
    }
}
