<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
class MelisComProductListController extends AbstractActionController
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
     */
    public function renderProductListContentFilterBulkAction()
    {
        return new ViewModel();
    }
    
    /**
     * Renders to the search action filter of the table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderProductListContentFilterSearchAction()
    {
        return new ViewModel();
    }
    
    /**
     * Renders to the limit action filter of the table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderProductListContentFilterLimitAction()
    {
        return new ViewModel();
    }
    
    /**
     * Renders to the grid view action of the table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderProductListContentFilterGridViewAction()
    {
        return new ViewModel();
    }
    
    /**
     * Renders to the list view action of the table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderProductListContentFilterListViewAction()
    {
        return new ViewModel();
    }
    
    /**
     * Renders to the refresh action of the table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderProductListContentFilterRefreshAction()
    {
        return new ViewModel();
    }

    
    /**
     * Renders to the Edit Button in the table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderProductListContentActionDeleteAction()
    {
        return new ViewModel();
    }
    
    /**
     * Renders to the Delete Button in the table
     * @return \Zend\View\Model\ViewModel
     */
    public function renderProductListContentActionEditAction()
    {
        return new ViewModel();
    }
    
    /**
     * This method return the list of Products
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function getProductsListAction()
    {
        $success = 0;
        $prodSvc = $this->getServiceLocator()->get('MelisComProductService');
        $categorySvc = $this->getServiceLocator()->get('MelisComCategoryService');
        $docSvc = $this->getServiceLocator()->get('MelisComDocumentService');
        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
        $toolTipTable = $viewHelperManager->get('ToolTipTable');
        $langId = $this->getTool()->getCurrentLocaleID();
        
        $columns = array();
        $dataCount = 0;
        $filterCount = 0;
        $draw = 0;
        $tableData = array();
        
        if($this->getRequest()->isPost()) 
        {
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
            
            $prodData = $prodSvc->getProductList($langId, null, null, null, $start, $length, $selColOrder, $order[0]['dir'], $search);
            $checkBox = '<div class="checkbox checkbox-single margin-none" data-product-id="%s">
							<label class="checkbox-custom">
								<i class="fa fa-fw fa-square-o"></i>
								<input type="checkbox" class="check-product">
							</label>
						</div>';
            $prodActive     = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
            $prodInactive   = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
            $prodImage      = '<img src="%s" width="60" height="60" class="img-rounded img-responsive"/>';
            $categoryDom    = '<span class="cell-val-table" style="margin:0 2px 4px 0;display:inline-block;padding: 3px 10px;background: #ECEBEB;border-radius: 4px;color: #7D7B7B;">%s</span>';
            $toolTipTextTag = '<a id="row-%s" class="toolTipHoverEvent tooltipTable" data-productId="%s" data-hasqtip="1" aria-describedby="qtip-%s">%s</a>';
            // PRODUCT DETAILS
            $ctr = 0;
            $variantSvc = $this->getServiceLocator()->get('MelisComVariantService');
            $dataCount = $prodSvc->getProductList($langId, null, null, null, null, null, $selColOrder, $order[0]['dir'], $search);
            foreach($prodData as $prod) 
            {
                $prodText = $prodSvc->getProductName($prod->getProduct()->prd_id, $langId);
                $prodText = $this->getTool()->escapeHtml($prodText);

                $tableData[$ctr]['DT_RowData'] = array('productname', $prodText);
                $tableData[$ctr]['DT_RowId'] = $prod->getProduct()->prd_id;
                $tableData[$ctr]['product_table_checkbox'] = sprintf($checkBox, $prod->getProduct()->prd_id);
                $tableData[$ctr]['prd_id'] = '<span data-productname="'.$prodText.'">'.$prod->getProduct()->prd_id . '</span>';
                $tableData[$ctr]['prd_status'] = $prod->getProduct()->prd_status ? $prodActive : $prodInactive;
                $tableData[$ctr]['product_image'] = sprintf($prodImage, $docSvc->getDocDefaultImageFilePath('product', $prod->getProduct()->prd_id));
                
                $tableData[$ctr]['product_categories'] = '';
                foreach($prod->getCategories() as $prodCat)
                {
                    $catName = $categorySvc->getCategoryNameById($prodCat->pcat_cat_id, $langId);
                    $tableData[$ctr]['product_categories'] .= sprintf($categoryDom, $this->getTool()->escapeHtml($catName));
                }
                
                $toolTipTable->setTable('productTable'.$prod->getProduct()->prd_id, 'table-row-'.($ctr+1). ' ' . 'productTable'.$prod->getProduct()->prd_id, '');
                $toolTipTable->setColumns($this->getToolTipColumns());
                
                $prodTextData = $prod->getTexts();
               
				// Detect if Mobile remove qTipTable
				$useragent=$_SERVER['HTTP_USER_AGENT'];
				if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
				{
					$tableData[$ctr]['product_name'] = sprintf($toolTipTextTag, ($ctr+1), $prod->getProduct()->prd_id, ($ctr+1), $prodText);
				} 
				else 
				{
					$tableData[$ctr]['product_name'] = sprintf($toolTipTextTag, ($ctr+1), $prod->getProduct()->prd_id, ($ctr+1), $prodText) . $toolTipTable->render();
				}
				
                $ctr++;
            }
            
            $filterCount = count($tableData);
            
        }
        
        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => count($prodData),
            'recordsFiltered' => count($dataCount),
            'data' => $tableData,
        ));
    }
    
    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool() 
    {
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('meliscommerce', 'meliscommerce_products_list');
        
        return $melisTool;
        
    }
    
    /**
     * Returns the translation text
     * @param String $key
     * @param array $args
     * @return string
     */
    private function getTranslation($key, $args = null) 
    {
        $translator = $this->getServiceLocator()->get('translator');
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

        if($this->getRequest()->isPost()) {
            $productId = (int) $this->getRequest()->getPost('productId');
            $variantId = (int) $this->getRequest()->getPost('variantId');
            
            $productSvc = $this->getServiceLocator()->get('MelisComProductService'); // added by: JRago

            // $productId = (int) $this->params()->fromQuery('productId');
            $variants = $this->getProductVariantsData($productId);
            $mainVarDom = '<span class="text-success"><i class="fa fa-fw fa-star"></i></span>';
            $imageDom      = '<img src="%s" width="60" class="img-rounded"/>';
            $activeDom     = '<span class="text-success"><i class="fa fa-fw fa-circle"></i></span>';
            $inactiveDom   = '<span class="text-danger"><i class="fa fa-fw fa-circle"></i></span>';
            $attributesDom = '<span class="btn btn-default cell-val-table" style="border-radius: 4px;color: #7D7B7B;">%s</span>';
            $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
            $table = $viewHelperManager->get('ToolTipTable');
            $langId = $this->getTool()->getCurrentLocaleID();

            // added by: JRago for Junry to query product name for use on front-end
            $productData = (array) $productSvc->getProductTextsById($productId, null, $langId);
            $productName = null;
            if (! empty($productData)) {
                foreach ($productData as $key => $value) {
                    if ($value->ptxt_lang_id == $langId) {
                        $productName = $value->ptxt_field_short ?? $value->ptxt_field_long;
                        break;
                    }
                }
            }
            // end added

            if($variants) {
                $sContent = '';
                foreach($variants as $variant) {
                    
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
                    $sku = '<a class="text-danger openVariant" style="color:#fff" data-product-name="'.$productName.'" data-product-id="'.$productId.'">'.$this->getTool()->escapeHtml($variant['sku']).'</a>';
                    $sContent .= $table->setRowData($sku, array('class' => 'text-left', 'style' => 'font-size: 14px'));
                    
                    // ATTRIBUTES
                    $attributes = $table->setRowData('', array('class' => 'innerLR text-left'));
                    if($variant['attributes']) {
                        $tmpAttr = '';
                        foreach($variant['attributes'] as $attribute) {
                            $tmpAttr .= sprintf($attributesDom, $attribute);
                        }
                        $attributes = $table->setRowData($tmpAttr, array('class' => 'innerLR text-left'));
                    }
                    $sContent .= $attributes;
                    
                    
                    // Data Per Country
                    $sDataPerCountry = $table->setRowData('', array('class' => 'text-left')) . $table->setRowData('', array('class' => 'text-right')) . $table->setRowData('', array('class' => 'text-right'));
                    if($variant['dataPerCountry']) {
                        $country = '';
                        $price = '';
                        $stock = '';
                        $priceNet = 0;
                        $stockQty = 0;
                        $genPrice = null;
                        $warningDom = '';
                        $endRedLabel = '';
                        foreach($variant['dataPerCountry'] as $countryKey => $value) {
                            if($countryKey) {
                               if($value['price']) {
                                   $priceNet = $value['price']['price_net'];
                               }
                               else {
                                   $priceNet = '';
                               }
                               
                               if($value['stock']) {
                                   if($value['stock']['stock_quantity'] === null) {
                                       $stockQty = null;
                                   }
                                   elseif($value['stock']['stock_quantity'] === 0) {
                                       $stockQty = 0;
                                   }
                                   else {
                                       $stockQty = $value['stock']['stock_quantity'];
                                   }
                                   
                               }
                               else {
                                   $stockQty = null;
                               }
                               
                               $isAllOk = false;
                               $priceNetValidator = strpos($priceNet, ',') !== false ? explode(',', $priceNet)[0] : null;
                               // if both price and stock is not 0, then display
                                
                               if(is_null($stockQty) || is_numeric($stockQty)) {
                                   $isAllOk = true;
                               }

                               
                               if($isAllOk) {
                                   
                                   // label whole row with red if stock is set and it is zero
                                   if(!is_null($stockQty) && $stockQty == 0) {
                                       $warningDom = '<i class="fa fa-warning fa-2x float-left" style="color:#981a1f; font-size: 20px;"></i> ';
                                   }
                                   
                                   $image = !empty($value['flag']) ? '<img src="data:image/jpeg;base64,'. ($value['flag']) .'" class="" width="16" height="16"/>' : '<i class="fa fa-globe"></i>';
                                   $price .= $priceNet .'<br/>';
                                   $country .= $image.' '.$countryKey  .'<br/>';
                                   $stock .= $warningDom.$stockQty .'<br/>';
                               }
                            }
                        }
                        $sDataPerCountry = $table->setRowData($country, array('class' => 'text-left')) . $table->setRowData($price, array('class' => 'text-right')) . $table->setRowData($stock, array('class' => 'text-right'));
                    }
                    
                    $sContent .= $sDataPerCountry;
                    

                    $content[] = $sContent;
                    if($variantId == $variant['id']){
                      $vcontent[] = $sContent;  
                    }
                }
            }
            
        }
        if(isset($vcontent)){
           $content = $vcontent;
        }
       return new JsonModel(array(
           'content' => $content
       ));
    }
    private function getOnlyValueOnArray($keyOnArray, $haystack)
    {
        $newArray = array();
        if($haystack) {
            foreach($haystack as $key => $value) {
                if($key == $keyOnArray) {
                    $newArray[$key] = $value;
                }
            }
        }
        else {

        }
        $newArray = "";

        return $newArray;
    }

    
    public function getProductVariantsData($productId) 
    {
        $countriesTable = $this->getServiceLocator()->get('MelisEcomCountryTable');
        $variantSvc = $this->getServiceLocator()->get('MelisComVariantService');
        $countries =  $countriesTable->getCountries()->toArray();
        $langId = $this->getTool()->getCurrentLocaleID();
        $variantsData = $variantSvc->getVariantListByProductId($productId, $langId);
        $docSvc = $this->getServiceLocator()->get('MelisComDocumentService');
        $productSvc = $this->getServiceLocator()->get('MelisComProductService');
        
        $dataPrices = array();
        $variants = array();
        
        $varCtr = 0;
        foreach($variantsData as $variant) {
            $varData = $variant->getVariant();
            $varAttr = $variant->getAttributeValues();
            $attributes = array();
            $dataPerCountry = array();
            $dataPricesAndStock = array();
            // attributes
            foreach($varAttr as $attr) {
        
                $valCol = 'avt_v_'.$attr->atype_column_value;
                $value = '';
                //check for attribute value translations
                $foundTrans = false;
                foreach($attr->atval_trans as $valTrans){
                    if($valTrans->avt_lang_id == $langId){
                        $foundTrans = true;
                        $value = $valTrans->$valCol;
                    }
                }
        
                //if no corresponding translation get the first available trans
                if(!$foundTrans){
                    foreach($attr->atval_trans as $valTrans){
                        $foundTrans = true;
                        $value = $valTrans->$valCol;
                        break;
                    }
                }
        
                //use the attribute value reference as name if no translation
                if(!$foundTrans){
                    $value = $attr->atval_reference;
                }
        
                // edit value before rendering if necessary
                switch($valCol){
                    case 'avt_v_datetime': $value = $this->getTool()->dateFormatLocale($value); break;
                    case 'avt_v_text':
                    case 'avt_v_varchar' : $value = $this->getTool()->limitedText($this->getTool()->escapeHtml($value),50); break;
                }
                if($value) {
                    $attributes[] = $value;
                }
            }
        
            // General
            $generalPricesData = $variantSvc->getVariantPricesById($varData->var_id);
            $generalStockData  = $variantSvc->getVariantStocksById($varData->var_id);
            $tmpData = array();
        
            $genPrice = null;
            $genStock = null;
            foreach($generalPricesData as $genPriceData) {
                if($genPriceData->price_country_id == -1) {
                    $genPriceData->price_net = $productSvc->formatPrice($genPriceData->price_net);
                    $genPrice = $genPriceData->price_net;
                }
            }
            foreach($generalStockData as $genStockData) {
                if($genStockData->stock_country_id == -1) {
                    $genStock = $genStockData->stock_quantity;
                }
            }
            
            // Filtering variant that has only data of Price ar Stock
            if (!empty($genPrice) || !empty($genStock)) {
                $dataPricesAndStock[$this->getTool()->getTranslation('tr_meliscommerce_general_text')]['price'] = $this->getOnlyKeyOnArray('price_net', $genPriceData);
                $dataPricesAndStock[$this->getTool()->getTranslation('tr_meliscommerce_general_text')]['stock'] = $this->getOnlyKeyOnArray('stock_quantity', $genStockData);
            }
            
            // Countries
            foreach($countries as $country) {
                
                $tmpPrice = null;
                $tmpStock = null;
                
                foreach($variantSvc->getVariantPricesById($varData->var_id, $country['ctry_id']) as $vData) {
                    if($vData->price_net) {
                        $vData->price_net = $productSvc->formatPrice($vData->price_net);
                        $tmpPrice = $this->getOnlyKeyOnArray('price_net', $vData);
                    }
                }
                foreach($variantSvc->getVariantStocksById($varData->var_id, $country['ctry_id']) as $sData) {
                    if(is_numeric($sData->stock_quantity)) {
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
        if($haystack) {
            foreach($haystack as $key => $value) {
                if($key == $keyOnArray) {
                    $newArray[$key] = $value;
                }
            }
        }
        else {
            $newArray[$keyOnArray] = null;
        }

        
        return $newArray;
    }



}