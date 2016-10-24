	
$(document).ready(function() {
	
	var body = $("body");
	
	body.on("click", ".editvariant", function(){
		var variantId   = $(this).parent().parent().attr('id');
		var variantName = $(this).parent().parent().find("td a").data("variantname");
		var productId   = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
		var prodTabId   = productId+"_id_meliscommerce_products_page";
		melisCommerce.disableTab(prodTabId);
		melisHelper.tabOpen(variantName, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId});
		melisCommerce.setUniqueId(variantId);

	});
	
	body.on("click", ".add-variant", function(){
		var productId   = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
		melisCoreTool.processing();
		melisHelper.tabOpen('New SKU', 'icon-tag-2', 'id_meliscommerce_variants_page', 'meliscommerce_variants_page', { productId : productId, page : 'newvar'});
		melisCommerce.setUniqueId(0);
		melisCoreTool.processDone();
	});
		
	body.on("click", ".save-add-variant", function(){
		var variantPageId   = $(this).closest('.container-level-a').attr('id');
	});
	
	body.on("click", ".country-price-tab li a", function(){
		var textCountry = $(this).data('country');
		$('.country-price-label').text(textCountry + ' ' + translations.tr_meliscommerce_variant_tab_prices_pricing);
	});
	
	body.on("click", ".country-stock-tab li a", function(){
		var textCountry = $(this).data('country');
		if(melisLangId == 'fr_FR'){
			$('.country-stock-label').text(translations.tr_meliscommerce_variant_tab_stocks_header + ' ' + textCountry );
		}else{
			$('.country-stock-label').text(textCountry + ' ' + translations.tr_meliscommerce_variant_tab_stocks_header);
		}
	});
	
	$('body').on('click', '.productvariant-refresh', function(){
		var prodId = melisCommerce.getCurrentProductId();
		melisHelper.zoneReload(prodId+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : prodId});
	});
	
	body.on("click", ".deletevariant", function(){
		var del = this;
		var variantId   = $(del).parent().parent().attr('id');		
		var url = 'melis/MelisCommerce/MelisComVariant/deleteVariant';
		var dataString = [];
		dataString.push({
			name : 'var_id',
			value: variantId,
		});
		melisCoreTool.pending(del);
		melisCoreTool.confirm(
			translations.tr_meliscommerce_documents_common_label_yes,
			translations.tr_meliscommerce_documents_common_label_no,
			translations.tr_meliscommerce_variants_delete_title, 
			translations.tr_meliscommerce_variants_delete_confirm,
			function(){
				melisCommerce.postSave(url, dataString, function(data){
					if(data.success){				
						melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
						melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : melisCommerce.getCurrentProductId()});
						melisHelper.tabClose(  variantId + "_id_meliscommerce_variants_page");
					}else{
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
					}		
					melisCore.flashMessenger();	
				}, function(data){
					console.log(data);
				})
			
			});
		melisCoreTool.done(del);
		
	});
	
	body.on("click", ".save-variant", function(){
		melisCoreTool.pending(".save-variant");
		var url = 'melis/MelisCommerce/MelisComVariant/saveVariant';
		var prodId = $(this).closest('.container-level-a').data("prodid");
		var id = $(this).closest('.container-level-a').attr('id');
		var varId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
		var fixVarId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10)+'_';
		var forms = $(this).closest('.container-level-a').find('form');
		var dataString = [];
		var len;
		var ctr;
		ctr = 0;
		forms.each(function(){
			var pre = $(this).attr('name');
			var data = $(this).serializeArray();
			len = data.length;
			for(j=0; j<len; j++ ){
				dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
			}	
			ctr++;
		});
		
		dataString.push({ 
			name: "variantId",
			value: varId 
		});
		
		dataString = melisCommerceSeo.serializeSeo('variant', varId, dataString);
		
		dataString.push({name : 'variant[0][var_id]', value : varId}, {name : 'variant[0][var_prd_id]', value : prodId})
		
		$('#'+id).find('.make-switch div').each(function(){
			var field = 'variant[0]['+$(this).find('input').attr('name')+']';
			var status = $(this).hasClass('switch-on');
			var saveStatus = 0;
			if(status) {
				saveStatus = 1;
			}
			dataString.push({
				name : field,
				value: saveStatus
			})
		});
		
		
		melisCommerce.postSave(url, dataString, function(data){
			if(data.success){
				melisHelper.tabClose(  fixVarId + "id_meliscommerce_variants_page");
				melisHelper.tabOpen(data.chunk.varSku, 'icon-tag-2', data.chunk.variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : data.chunk.variantId, productId : prodId});
				melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
				melisHelper.zoneReload(prodId+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : prodId});
				melisCommerce.setUniqueId(data.chunk.variantId);
				melisCore.flashMessenger();
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');				
			}	
			melisCoreTool.done(".save-variant");
		}, function(data){
			console.log(data);
		});
		
	})
	
});
//variant list table in product page
window.initProductVariant = function(data, tblSettings) {
	var prodId = melisCommerce.getCurrentProductId();
	data.prodId = prodId	
}
window.variantLoaded = function() {
	var productId = $(".tab-pane#" + activeTabId).data("prodid");
	var prodTabId   = productId+"_id_meliscommerce_products_page";
	melisCommerce.enableTab(prodTabId);
}